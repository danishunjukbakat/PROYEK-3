@extends('layouts.app')
@section('title','Registrasi customer')
@section('content')
<section class="panel auth"><h1>Daftar customer</h1><p>Akun baru otomatis mendapat role customer.</p>
<form method="POST" action="{{ route('register.store') }}">@csrf
@foreach(['username'=>'Username','nama_lengkap'=>'Nama lengkap','email'=>'Email','no_hp'=>'Nomor HP','alamat'=>'Alamat'] as $field=>$label)
<label>{{ $label }}<input name="{{ $field }}" value="{{ old($field) }}" required></label>@endforeach
<label>Password<input name="password" type="password" minlength="8" required autocomplete="new-password"></label>
<label>Konfirmasi password<input name="password_confirmation" type="password" required autocomplete="new-password"></label>
<button>Daftar</button></form></section>
@endsection
