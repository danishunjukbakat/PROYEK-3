# Modul 4 — Penyimpanan Data pada Web

**Danish Arva Linardhi · NIM 251511007**

Paket source lengkap untuk Tugas 1, Tugas 2, tugas integrasi toko online, serta eksperimen cookies, session, dan Local Storage. Ketiga aplikasi Laravel berdiri sendiri dan disimpan dalam satu root repository. Login ditulis manual menggunakan fasilitas Auth Laravel, tanpa Breeze/Jetstream/starter kit autentikasi.

## Isi paket

| Folder | Isi |
|---|---|
| `tugas-1-login` | Login username/password hash, dashboard terlindungi, logout, 2 akun awal |
| `tugas-2-keranjang` | 5 barang, keranjang tanpa login dengan session di server, tambah/ubah/hapus/kosongkan |
| `tugas-toko-online` | 10 barang bergambar, login, keranjang per akun, checkout transaksi, riwayat + detail pesanan |
| `eksperimen` | HTTP stateless, cookies, session PHP + MySQL, Local Storage dan sessionStorage |
| `database/setup_databases.sql` | Membuat tiga database Laravel tanpa menghapus data |
| `docs` | Keputusan teknis, hasil uji, panduan screenshot dan GitHub |

Setiap aplikasi mencakup `artisan`, `composer.json`, `composer.lock`, `.env.example`, routes, controllers, models, migration, seeder, views, aset CSS/SVG, dan pengujian. Folder `vendor` tidak dimasukkan ke ZIP/Git; Composer mengisinya sesuai lockfile. Tidak perlu Node.js/npm atau kompilasi Vite karena aset langsung tersedia di `public`.

## Prasyarat

- PHP **8.3 atau lebih baru**, Composer 2, MySQL 8 dengan tabel InnoDB.
- Ekstensi PHP untuk aplikasi/test: ctype, curl, dom, fileinfo, filter, hash, mbstring, openssl, pcre, PDO, pdo_mysql, session, tokenizer, xml, xmlwriter; aktifkan pdo_sqlite untuk tes otomatis. Composer akan memeriksa kebutuhan dependency lainnya.
- Pastikan `php -v` di terminal menunjukkan PHP yang benar. Beberapa paket XAMPP masih menggunakan PHP lebih lama; cek `where php` (Windows), `which php` (macOS/Linux), dan `php --ini`.
- Internet diperlukan saat `composer install` pertama kali. Lockfile saat pembuatan memakai Laravel framework **13.35.0** dan PHPUnit **12.5.38**.

## Jalankan tiga aplikasi

1. Ekstrak ZIP. Buka terminal pada folder `modul-4-penyimpanan-web`.
2. Nyalakan MySQL. Impor `database/setup_databases.sql` melalui phpMyAdmin atau MySQL client. File membuat `db_tugas`, `db_keranjang`, dan `db_toko_online`.
3. Untuk Tugas 1, jalankan:

```sh
cd tugas-1-login
composer install
```

4. Salin `.env.example` menjadi `.env`. Pada Windows CMD/PowerShell: `copy .env.example .env`. Pada macOS/Linux: `cp .env.example .env`.
5. Edit `.env`: cocokkan DB_HOST, DB_PORT, DB_USERNAME, DB_PASSWORD dengan MySQL lokal. DB_DATABASE sudah disetel sesuai aplikasi. Jangan gunakan satu database yang sama untuk ketiga aplikasi karena skema tabelnya berbeda.
6. Lanjutkan:

```sh
php artisan key:generate
php artisan migrate --seed
php artisan serve --host=127.0.0.1 --port=8001
```

7. Buka http://127.0.0.1:8001. Biarkan terminal server menyala.
8. Buka terminal baru, masuk ke `tugas-2-keranjang`, ulangi Composer, penyalinan/edit `.env`, key dan migration. Jalankan `php artisan serve --host=127.0.0.1 --port=8002`.
9. Untuk toko online, ulangi pada `tugas-toko-online` lalu jalankan `php artisan serve --host=127.0.0.1 --port=8003`.

| Aplikasi | Database | URL |
|---|---|---|
| Tugas 1 | db_tugas | http://127.0.0.1:8001 |
| Tugas 2 | db_keranjang | http://127.0.0.1:8002 |
| Toko online | db_toko_online | http://127.0.0.1:8003 |

Pada setiap direktori aplikasi, `php artisan migrate --seed` menyiapkan skema dan data awal. Seeder memakai `firstOrCreate`, sehingga pemanggilan ulang tidak mereset stok atau mengganti password akun yang sudah ada. Bila perlu data benar-benar baru, gunakan database praktikum baru; jangan menghapus database yang masih diperlukan.

## Akun demo

| Username | Password awal | Berlaku pada |
|---|---|---|
| budi | rahasia123 | Tugas 1, toko online, eksperimen session PHP setelah seed |
| sari | belajar123 | Tugas 1, toko online, eksperimen session PHP setelah seed |

Akun dan alamat seed merupakan data contoh. Tugas 2 tidak meminta login. Tidak tersedia registrasi/admin/pembayaran eksternal karena tidak diperlukan oleh fitur minimum tugas. Checkout membuat pesanan internal tanpa biaya kirim.

## Coba alur utama

**Tugas 1:** buka dashboard sebelum login → dialihkan; coba password salah → pesan umum; login benar → nama pengguna tampil; logout → dashboard kembali terlindungi. Password pada tabel adalah hash, bukan teks asli atau nilai yang didekripsi.

**Tugas 2:** tambah 2 Buku Tulis @Rp5.000 dan 1 Pulpen @Rp3.000 → 3 unit, total Rp13.000. Refresh → isi tetap. Ubah jumlah ke 0 → item dihapus. Jumlah negatif, pecahan, dan melebihi stok ditolak di server. Gunakan browser/profil independen untuk membuktikan keranjang session berbeda. Tugas 2 memang tidak memiliki checkout.

**Toko online:** login budi, isi 2 Buku Tulis dan 1 Pulpen, buka checkout, isi alamat, konfirmasi. Pesanan total Rp13.000 dibuat dengan 2 detail; stok awal 20/30 menjadi 18/29; keranjang kosong. Buka riwayat dan detail. Login sari untuk memeriksa bahwa pesanan Budi tidak dapat diakses. Produk Gunting sengaja memiliki stok 0 untuk pengujian tombol habis dan validasi server.

## Mengapa Tugas 2 memilih session?

Session cocok untuk keranjang tamu: pengguna tidak perlu akun, ID barang dan jumlah bertahan antar-request, dan isi keranjang disimpan di server. Browser membawa cookie ID session; harga/nama/stok selalu dibaca ulang dari database. Ini memudahkan validasi dan mencegah aplikasi mempercayai harga client. Data tetap wajib divalidasi ketika formulir dikirim.

Konfigurasi: `SESSION_DRIVER=database`, `SESSION_LIFETIME=120` menit tidak aktif, `SESSION_EXPIRE_ON_CLOSE=false`, cookie HttpOnly/SameSite=Lax. Masa berlaku bukan jaminan keranjang tersimpan selamanya; penghapusan cookie, sesi kedaluwarsa, atau pembersihan data server dapat menghilangkannya. Ketiga aplikasi memakai nama cookie berbeda agar tidak saling menimpa pada host lokal. Cookies tidak dipisahkan berdasarkan port.

Toko online memakai **tabel `cart_items` per akun** karena login wajib; keranjang tetap ada setelah logout dan dapat dibuka kembali oleh akun yang sama. Session toko online menyimpan status autentikasi.

## Eksperimen

Dari root repository, jalankan `php -S 127.0.0.1:8000 -t eksperimen` lalu buka http://127.0.0.1:8000. Panduan lengkap ada pada [eksperimen/README.md](eksperimen/README.md), termasuk setup MySQL khusus login PHP. Keranjang cookie dan Local Storage adalah latihan manipulasi data yang sengaja tidak memvalidasi batas stok; implementasi tugas Laravel memiliki validasi.

## Pengujian

Pada masing-masing aplikasi:

```sh
php artisan test
```

Konfigurasi test memaksa SQLite `:memory:` agar tidak menyentuh database MySQL praktikum. Hasil pembuatan: **27 tes, 165 assertion, semuanya lulus**; rincian ada pada [docs/PENGUJIAN.md](docs/PENGUJIAN.md). Pengujian ini tidak membuktikan perilaku locking/concurrency MySQL; langkah manual MySQL disediakan terpisah.

## Repository GitHub dan laporan

Paket ini sudah memiliki struktur untuk **satu repository**, tetapi **belum dipublikasikan ke GitHub** dan belum memiliki URL repository nyata. Ikuti [docs/GITHUB.md](docs/GITHUB.md), lalu cantumkan URL aktual pada laporan Word. Jangan menuliskan URL contoh sebagai hasil publikasi. Checklist bukti layar ada pada [docs/PANDUAN_SCREENSHOT.md](docs/PANDUAN_SCREENSHOT.md).

## Masalah umum

- **No application encryption key:** `php artisan key:generate` sesudah membuat `.env`.
- **Access denied / unknown database:** perbaiki kredensial dan buat database yang sesuai, lalu `php artisan config:clear`.
- **could not find driver:** aktifkan pdo_mysql (aplikasi) atau pdo_sqlite (tes) pada php.ini milik PHP CLI yang sedang digunakan.
- **Tabel sessions belum ada:** jalankan migration; tabel sudah termasuk, tidak perlu membuat migration sessions tambahan.
- **419:** refresh form agar token CSRF baru, gunakan host yang konsisten, dan pastikan session database dapat ditulis.
- **Port terpakai:** gunakan port lain dan sesuaikan APP_URL. Nama cookie setiap aplikasi tetap harus unik pada host yang sama.
- **Permission denied:** proses PHP harus dapat menulis `storage` dan `bootstrap/cache`.

`.env.example` menggunakan mode lokal. Jika dipasang ke server sungguhan, gunakan HTTPS, APP_DEBUG=false, SESSION_SECURE_COOKIE=true dan akun database khusus; jangan memasukkan `.env` ke Git.
