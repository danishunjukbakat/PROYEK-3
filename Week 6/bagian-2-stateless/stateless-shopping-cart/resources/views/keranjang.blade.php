@extends('layouts.app')
@section('title','Keranjang belanja')
@section('content')<h1>Keranjang belanja</h1><p class="muted">Tanpa login · tersimpan dalam session.</p>
@if(!$rows)<section class="panel empty"><h2>Keranjang kosong</h2><p>Total: Rp 0</p><a class="btn" href="{{ route('barang.index') }}">Pilih barang</a></section>
@else<div class="panel"><div class="table-wrap"><table><thead><tr><th>Barang</th><th>Harga</th><th>Jumlah</th><th>Subtotal</th><th>Aksi</th></tr></thead><tbody>
@foreach($rows as $row)<tr><td>{{ $row['product']?->nama ?? 'Barang tidak tersedia' }}@if($row['product'] && $row['jumlah']>$row['product']->stok)<p class="error-text">Stok berubah. Silakan kurangi jumlah.</p>@endif</td><td>{{ $row['product'] ? \App\Support\Money::rupiah(\App\Support\Money::cents($row['product']->harga)) : '-' }}</td><td><div class="actions">
<form method="POST" action="{{ route('keranjang.update',$row['id']) }}">@csrf @method('PATCH')<input type="hidden" name="jumlah" value="{{ $row['jumlah']-1 }}"><button class="small secondary" aria-label="Kurangi jumlah">−</button></form>
<form method="POST" action="{{ route('keranjang.update',$row['id']) }}">@csrf @method('PATCH')<input type="number" name="jumlah" value="{{ $row['jumlah'] }}" min="0" aria-label="Jumlah barang"><button class="small secondary">Ubah</button></form>
<form method="POST" action="{{ route('keranjang.update',$row['id']) }}">@csrf @method('PATCH')<input type="hidden" name="jumlah" value="{{ $row['jumlah']+1 }}"><button class="small secondary" aria-label="Tambah jumlah" @disabled(!$row['product'] || $row['jumlah'] >= $row['product']->stok)>+</button></form>
</div></td><td>{{ \App\Support\Money::rupiah($row['subtotal']) }}</td><td><form method="POST" action="{{ route('keranjang.destroy',$row['id']) }}">@csrf @method('DELETE')<button class="small danger">Hapus</button></form></td></tr>@endforeach
</tbody></table></div><div class="summary"><strong>Total: {{ \App\Support\Money::rupiah($total) }}</strong><form method="POST" action="{{ route('keranjang.clear') }}">@csrf @method('DELETE')<button class="danger">Kosongkan keranjang</button></form></div><a href="{{ route('barang.index') }}">Lanjut belanja</a></div>@endif
@endsection
