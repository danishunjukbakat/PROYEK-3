@extends('layouts.app')
@section('title','Eksperimen W07')
@section('content')
<h1>Eksperimen request role dan cart</h1>
<p>Lingkungan lokal praktikum. Aktor: <strong>{{ auth()->user()->username }}</strong> · role: <strong>{{ auth()->user()->role }}</strong>.</p>
<section class="panel">
<form id="experiment">
<label for="scenario">Skenario</label><select id="scenario" name="scenario">
<option value="promote">Admin customer ke staff</option><option value="same">Admin role sama staff</option>
<option value="demote">Admin staff ke customer</option><option value="self">Admin mengubah diri sendiri</option>
<option value="other">Admin mengubah admin lain</option><option value="invalid">Input role admin</option>
<option value="denied">Customer atau staff PATCH role</option><option value="cart">POST cart/add</option>
<option value="cartnext">POST cart/add membawa state sebelumnya</option>
</select><p><button type="submit">Jalankan request</button></p></form>
<h2 id="http-status" aria-live="polite">Belum ada request</h2>
<div class="details"><div><h3>Request</h3><pre id="request-output"></pre></div><div><h3>Response</h3><pre id="response-output"></pre></div></div>
<p class="muted">Snapshot database dan delta log diperiksa pada bukti eksperimen. Cookie dan token tidak ditampilkan.</p>
</section>
<script src="{{ asset('js/role-lab.js') }}" defer></script>
@endsection
