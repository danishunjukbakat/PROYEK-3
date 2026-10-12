@extends('layouts.app')
@section('title','Daftar barang')
@section('content')<h1>Daftar barang</h1><p class="muted">Tambahkan kebutuhan alat tulis tanpa perlu login.</p><div class="grid">
@foreach($barang as $p)<article class="card"><img src="{{ asset('images/'.$p->id.'.svg') }}" alt="{{ $p->nama }}"><h2>{{ $p->nama }}</h2><div class="price">{{ \App\Support\Money::rupiah(\App\Support\Money::cents($p->harga)) }}</div><p class="stock">Stok: {{ $p->stok }}</p><form method="POST" action="{{ route('keranjang.store') }}">@csrf<input type="hidden" name="id" value="{{ $p->id }}"><button class="full" @disabled($p->stok===0)>{{ $p->stok ? 'Masukkan ke keranjang' : 'Stok habis' }}</button></form></article>@endforeach
</div>@endsection
