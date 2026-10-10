@extends('layouts.app')
@section('title','Dashboard')
@section('content')<section class="panel"><h1>Selamat datang, {{ auth()->user()->nama_lengkap }}!</h1><p>Halaman ini hanya bisa dibuka setelah login.</p><p>Username: <strong>{{ auth()->user()->username }}</strong></p></section>@endsection
