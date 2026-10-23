@extends('layouts.app')
@section('title', 'Manajemen role')
@section('content')
<h1>Manajemen role pengguna</h1>
<p class="muted">Admin dapat menukar customer dan staff. Akun admin dilindungi.</p>
<div class="table-wrap"><table>
<thead><tr><th>ID</th><th>Nama</th><th>Username</th><th>Role sekarang</th><th>Ubah role</th></tr></thead>
<tbody>@foreach($users as $user)<tr>
<td>{{ $user->id_user }}</td><td>{{ $user->nama_lengkap }}</td><td>{{ $user->username }}</td><td>{{ $user->role }}</td>
<td>@if($user->role !== 'admin' && !$user->is(auth()->user()))
<form method="POST" action="{{ route('admin.users.role.update', $user) }}">@csrf @method('PATCH')
<select name="role" aria-label="Role {{ $user->username }}"><option value="customer" @selected($user->role === 'customer')>Customer</option><option value="staff" @selected($user->role === 'staff')>Staff</option></select>
<button class="small">Simpan</button></form>
@else<span class="muted">Akun dilindungi</span>@endif</td>
</tr>@endforeach</tbody></table></div>
@endsection
