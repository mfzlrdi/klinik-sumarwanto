<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_dapat_diakses_tanpa_login(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Masuk Aplikasi')
            ->assertSee('Praktik Dokter');
    }
}
