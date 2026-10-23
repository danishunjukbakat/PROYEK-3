@extends('layouts.app')
@section('title','Daftar barang')
@section('content')<h1>Alat tulis untuk hari produktif</h1><p class="muted">Lihat katalog tanpa login. Masuk untuk menyimpan keranjang dan melakukan checkout.</p><div class="grid">
@foreach($products as $p)<article class="card"><img src="{{ asset('images/'.$p->gambar) }}" alt="{{ $p->nama_barang }}"><h2>{{ $p->nama_barang }}</h2><p class="muted">{{ $p->deskripsi }}</p><div class="price">{{ \App\Support\Money::rupiah(\App\Support\Money::cents($p->harga)) }}</div>@can('adjust-stock')<p class="stock">Stok: {{ $p->stok }}</p>@endcan
@can('use-cart')<form method="POST" action="{{ route('keranjang.store') }}">@csrf<input type="hidden" name="id_barang" value="{{ $p->id_barang }}"><button class="full" @disabled($p->stok===0)>{{ $p->stok ? 'Masukkan ke keranjang' : 'Stok habis' }}</button></form>@endcan</article>@endforeach
</div>@endsection
