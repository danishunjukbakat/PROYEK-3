<!DOCTYPE html>
<html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>@yield('title',config('app.name'))</title><link rel="stylesheet" href="{{ asset('css/app.css') }}"></head>
<body><header><nav><a class="brand" href="{{ url('/') }}">{{ config('app.name') }}</a><div class="links"><a href="{{ route('barang.index') }}">Daftar barang</a><a href="{{ route('keranjang.index') }}">Keranjang <span class="badge">{{ array_sum(session('keranjang',[])) }}</span></a></div></nav></header>
<main>@if(session('success'))<div class="alert" role="status">{{ session('success') }}</div>@endif
@if($errors->any())<div class="alert error" role="alert"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
@yield('content')</main><footer>Praktikum Modul 4 · Danish Arva Linardhi</footer></body></html>
