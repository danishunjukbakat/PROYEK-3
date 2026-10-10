# tugas-1-login

Petunjuk lengkap ada pada [README root](../README.md).

1. PHP 8.3+, Composer 2, MySQL; buat database sesuai `.env.example`.
2. `composer install`
3. Salin `.env.example` ke `.env`; edit akses database.
4. `php artisan key:generate`
5. `php artisan migrate --seed`
6. `php artisan serve --host=127.0.0.1 --port=8001`
7. Buka http://127.0.0.1:8001.

Pengujian: `php artisan test` (memerlukan pdo_sqlite; memakai database memori terisolasi). Aset sudah tersedia tanpa npm. Akun Tugas 1/toko: budi / rahasia123 dan sari / belajar123; Tugas 2 tanpa login. Jangan commit `.env` atau `vendor`.
