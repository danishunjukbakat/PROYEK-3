@extends('layouts.app')
@section('title','Login')
@section('content')
<section class="panel auth"><h1>Login</h1><p class="muted">Masuk menggunakan akun praktikum Anda.</p>
<form method="POST" action="{{ route('login.store') }}">@csrf
<label for="username">Username</label><input id="username" name="username" value="{{ old('username') }}" autocomplete="username" required maxlength="50" autofocus>
<label for="password">Password</label><input type="password" id="password" name="password" autocomplete="current-password" required>
<p></p><button class="full">Masuk</button></form></section>
@endsection
