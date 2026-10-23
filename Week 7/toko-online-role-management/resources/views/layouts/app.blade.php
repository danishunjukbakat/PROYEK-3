<!DOCTYPE html>
<html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="csrf-token" content="{{ csrf_token() }}"><title>@yield('title',config('app.name'))</title><link rel="stylesheet" href="{{ asset('css/app.css') }}"></head>
<body><header><nav><a class="brand" href="{{ url('/') }}">Toko Online W07</a><div class="links"><a href="{{ route('barang.index') }}">Katalog</a><a href="{{ route('stateless.shop') }}">Cart stateless</a>@auth
<a href="{{ route('dashboard') }}">Dashboard</a>
@can('use-cart')<a href="{{ route('keranjang.index') }}">Keranjang W05</a><a href="{{ route('pesanan.index') }}">Pesanan</a>@endcan
@can('adjust-stock')<a href="{{ route('inventory') }}">Stok</a>@endcan
@can('manage-roles')<a href="{{ route('admin.users.index') }}">Role pengguna</a>@endcan
<form method="POST" action="{{ route('logout') }}">@csrf<button class="secondary small">Logout</button></form>
@else<a href="{{ route('login') }}">Login</a><a href="{{ route('register') }}">Daftar</a>@endauth</div></nav></header>
<main>@if(session('success'))<div class="alert" role="status">{{ session('success') }}</div>@endif
@if($errors->any())<div class="alert error" role="alert"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
@yield('content')</main><footer>Praktikum Modul 5 · Danish Arva Linardhi · 251511007</footer></body></html>