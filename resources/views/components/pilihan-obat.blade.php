@props(['obat', 'terpilih' => null])

<option value="">— Pilih obat —</option>
@foreach ($obat as $o)
    <option value="{{ $o->id_obat }}" @selected($terpilih == $o->id_obat)>{{ $o->namaLengkap() }}</option>
@endforeach
