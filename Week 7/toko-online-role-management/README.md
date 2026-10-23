# Toko Online W07 Role Management

Kelanjutan toko W05 (`Week 5/modul-4-penyimpanan-web/tugas-toko-online`) dan cart stateless W06 dalam repository PROYEK-3 yang sama. Folder mingguan dipertahankan agar hasil lama dapat dibandingkan.

## Menjalankan

PHP >= 8.3 dengan pdo_sqlite, Composer. Tidak memerlukan Node/Vite.

```powershell
composer install
Copy-Item .env.example .env
New-Item database/database.sqlite -ItemType File
php artisan key:generate
php artisan migrate --seed
php artisan serve --host=127.0.0.1 --port=8007
```

Jika `.env` atau database sudah ada, gunakan berkas yang ada dan jalankan `php artisan migrate`. Database SQLite W07 terpisah dari database W05. Tidak perlu `migrate:fresh`.

URL: http://127.0.0.1:8007. Route `/dashboard`, `/admin/users`, `/inventory`, `/stateless-shop`, `/lab`. `/lab` hanya tersedia pada lingkungan local/testing dan menggunakan endpoint aplikasi yang sama, dengan CSRF tetap aktif.

## Akun dummy lokal

| Username | Password demo | Role |
| --- | --- | --- |
| admin, admin2 | PraktikumW07! | admin |
| staff | PraktikumW07! | staff |
| budi | rahasia123 | customer |
| sari | belajar123 | customer |

Akun tambahan dibuat hanya oleh RoleDemoSeeder di local/testing. Registrasi publik `/register` menolak field role dan membuat customer. Role tidak berada di `$fillable` User. Tidak ada alur publik untuk memperoleh admin.

## Ability matrix

| Aksi | Guest | Customer | Staff | Admin |
| --- | --- | --- | --- | --- |
| Katalog | Ya | Ya | Ya | Ya |
| Cart W05/W06 dan checkout | Tidak | Ya | Tidak | Tidak |
| Lihat stok | Tidak | Tidak | Ya | Ya |
| Edit produk | Tidak | Tidak | Tidak | Ya |
| Ubah role | Tidak | Tidak | Tidak | Ya |

Gate `manage-roles`, `manage-products`, `adjust-stock`, `use-cart` di AppServiceProvider. Semua pembatasan dipasang pada route; Blade hanya menyesuaikan tampilan.

BR-01 customer default. BR-02 admin saja. BR-03 customer <-> staff saja. BR-04 akun sendiri/admin lain dilindungi. BR-05 role sama mengembalikan `changed:false` tanpa save. BR-06 Observer mencatat target ID, role lama/baru, actor ID. BR-07 cart stateless hanya customer.

## Arsitektur

`PATCH /admin/users/{user}/role` -> auth -> can:manage-roles -> ChangeRoleRequest -> RoleController -> RoleManagementService -> instance User::save -> UserObserver::updated -> Laravel Log.

Service memeriksa ulang aktor dan target di transaksi dengan lockForUpdate; SQLite lokal mengandalkan locking transaksi SQLite, bukan row lock MySQL. Role invalid ditolak juga jika service dipanggil langsung. Primary key lama `id_user` tetap digunakan, termasuk pada logging melalui `getKey()`.

JSON dengan Accept: application/json: sukses/no-op 200, Gate 403, validasi/business rule 422, guest 401. Form HTML sukses dan error validasi redirect 302, pesan ditampilkan setelah GET. CSRF yang hilang dapat menghasilkan 419.

Cart W06 menerima integer product_id 1-3 dan seluruh isi cart pada setiap request. Katalog contoh tiga produk W06 tetap dihitung oleh server; katalog toko W05 memakai tabel products. Cart stateless adalah demonstrasi tersendiri, tidak otomatis dipindahkan ke cart_items/checkout W05. Rencana lanjutan: adapter ID BRG001 dst, service harga berbasis products, dan konversi eksplisit ke checkout. Login dan Gate telah terintegrasi; kontrak cart W06 tetap dapat direproduksi.

## Verifikasi dan bukti

```powershell
php artisan test
php artisan route:list --except-vendor
php tools/observer_experiment.php
```

Eksperimen terakhir hanya SQLite lokal dummy, mengubah lalu memulihkan USR001 menggunakan bulk update. Bulk update melewati model events. Log modul merupakan bukti pembelajaran dan bukan audit transaksi produksi yang tahan kegagalan.

29 tes, 195 assertions pada PHP 8.5.0 / Laravel 13.35.0. Tes memakai SQLite in-memory, terpisah dari DB demo. Bukti browser, request/response aman, snapshot users dan delta log tersedia di `../evidence`. Cookie, token, `.env`, vendor, dan database lokal diabaikan Git.

Independent challenge: OrderPolicy diimplementasikan untuk kepemilikan order customer, dengan pemeriksaan scope pemilik lama tetap dipertahankan (akun lain 404). Analisis multi-role tersedia pada worksheet.

Referensi: Modul 5 dan slide W07; https://laravel.com/docs/13.x/authorization ; https://laravel.com/docs/13.x/eloquent#observers .
