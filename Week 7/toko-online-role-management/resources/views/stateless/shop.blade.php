<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Stateless Shopping Cart | Modul 4 Bagian 2</title>
    <link rel="stylesheet" href="{{ asset('css/stateless.css') }}">
</head>
<body>
<main>
    <header><span class="label">W07 · CART W06 TERINTEGRASI</span><h1>Stateless Shopping Cart</h1>
        <p>Setiap request membawa isi keranjang. Laravel memvalidasi dan menghitung harga dari katalog server.</p>
        <a href="{{ route('barang.index') }}">Kembali ke toko online W07 →</a>
    </header>
    <section aria-labelledby="products-title"><h2 id="products-title">Daftar produk</h2>
        <div class="products">
            @foreach ($products as $id => $product)
            <article><span class="label">PRODUK {{ $id }}</span><h3>{{ $product['name'] }}</h3>
                <p class="price">Rp{{ number_format($product['price'], 0, ',', '.') }}</p>
                <button type="button" data-add="{{ $id }}">Tambah {{ $product['name'] }}</button>
            </article>
            @endforeach
        </div>
    </section>
    <section aria-labelledby="cart-title"><h2 id="cart-title">Keranjang belanja</h2>
        <div id="items" aria-live="polite">Keranjang kosong.</div>
        <div class="summary"><strong id="total">Total: Rp0</strong><span id="count">0 unit</span></div>
        <div class="actions"><button type="button" id="recalculate">Hitung Ulang</button>
            <button type="button" id="clear" class="secondary">Kosongkan Keranjang</button></div>
        <p class="note">State hanya berada di memori JavaScript. Refresh halaman akan mengosongkan keranjang.</p>
        <p id="error" role="alert"></p>
    </section>
    <details><summary>Request payload dan respons Laravel terakhir</summary>
        <div class="diagnostics"><div><h3>Request Payload</h3><pre id="payload">Belum ada request.</pre></div>
        <div><h3>Response</h3><pre id="response">Belum ada request.</pre></div></div>
    </details>
    <footer>Danish Arva Linardhi · 251511007 · Praktikum Proyek 3</footer>
</main>
<script src="{{ asset('js/stateless-cart.js') }}" defer></script>
</body>
</html>
