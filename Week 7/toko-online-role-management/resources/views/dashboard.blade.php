@extends('layouts.app')
@section('title','Dashboard')
@section('content')<section class="panel"><h1>Halo, {{ auth()->user()->nama_lengkap }}</h1><p>Role aktif: <strong>{{ auth()->user()->role }}</strong></p><p>Hak akses dibaca ulang dari database pada setiap request.</p>
@can('manage-roles')<p><a class="btn" href="{{ route('admin.users.index') }}">Kelola role pengguna</a></p>@endcan
@can('adjust-stock')<p><a href="{{ route('inventory') }}">Lihat stok dan produk</a></p>@endcan
@can('use-cart')<p><a href="{{ route('stateless.shop') }}">Belanja dengan cart stateless</a></p>@endcan</section>@endsection