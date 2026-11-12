<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(Request $request): View
    {
        return view('auth.login', [
            'akun' => $request->query('akun') === 'resepsionis' ? 'resepsionis' : 'dokter',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'alamat_email' => ['required', 'email', 'max:255'],
            'kata_sandi' => ['required', 'string', 'max:255'],
        ], [
            'alamat_email.required' => 'Alamat email wajib diisi.',
            'alamat_email.email' => 'Masukkan alamat email yang valid.',
            'kata_sandi.required' => 'Kata sandi wajib diisi.',
        ]);

        $key = Str::transliterate(Str::lower($credentials['alamat_email']).'|'.$request->ip());

        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw ValidationException::withMessages([
                'alamat_email' => 'Terlalu banyak percobaan masuk. Coba lagi dalam '.RateLimiter::availableIn($key).' detik.',
            ]);
        }

        $credentials['alamat_email'] = Str::lower($credentials['alamat_email']);

        if (! Auth::attempt([
            'alamat_email' => $credentials['alamat_email'],
            'password' => $credentials['kata_sandi'],
            'status_aktif' => true,
        ], $request->boolean('ingat_saya'))) {
            RateLimiter::hit($key, 60);

            throw ValidationException::withMessages([
                'alamat_email' => 'Email atau kata sandi tidak sesuai, atau akun tidak aktif.',
            ]);
        }

        RateLimiter::clear($key);
        $request->session()->regenerate();
        $request->user()->forceFill(['terakhir_masuk_pada' => now()])->save();

        return redirect()->intended(route('dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'Anda berhasil keluar dari aplikasi.');
    }
}
