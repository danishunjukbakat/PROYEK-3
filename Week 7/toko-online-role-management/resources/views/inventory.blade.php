@extends('layouts.app')
@section('title', 'Stok dan produk')
@section('content')
<h1>Stok dan produk</h1><p class="muted">Staff dan admin dapat melihat stok. Perubahan produk hanya untuk admin.</p>
@foreach($products as $p)<article class="panel" style="margin-bottom:16px">
<h2>{{ $p->id_barang }} · {{ $p->nama_barang }}</h2><p>Stok: {{ $p->stok }}</p>
@can('manage-products')<details><summary>Edit produk</summary><form method="POST" action="{{ route('admin.products.update', $p) }}">@csrf @method('PATCH')
<label>Nama <input name="nama_barang" value="{{ $p->nama_barang }}" required maxlength="50"></label>
<label>Deskripsi <textarea name="deskripsi" required>{{ $p->deskripsi }}</textarea></label>
<label>Harga <input name="harga" value="{{ $p->harga }}" required></label>
<label>Stok <input type="number" name="stok" min="0" value="{{ $p->stok }}" required></label><button>Simpan produk</button></form></details>@endcan
</article>@endforeach
@endsection
