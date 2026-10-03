
# Activity Manager

Aplikasi web berbasis Laravel untuk mengelola aktivitas secara terstruktur. Aplikasi ini memungkinkan pengguna menambahkan, melihat, mengubah, menghapus, dan memfilter aktivitas berdasarkan status.

## Fitur

- **Create:** Menambahkan aktivitas baru.
- **Read:** Melihat daftar dan detail aktivitas.
- **Update:** Mengubah informasi aktivitas.
- **Delete:** Menghapus aktivitas.
- **Filter Status:** Memfilter aktivitas berdasarkan status Planned, Ongoing, dan Done.
- **Validasi Form:** Memastikan data aktivitas sesuai dengan aturan yang ditentukan.
- **Manajemen Status:** Mengatur transisi status aktivitas dari Planned ke Ongoing, kemudian Done.

## Teknologi yang Digunakan

- Laravel
- PHP
- SQLite
- Blade Template
- Tailwind CSS
- PHPUnit / Laravel Testing
- SonarQube for IDE

## Struktur Data Aktivitas

| Field | Tipe Data | Keterangan |
|---|---|---|
| title | String | Judul aktivitas |
| description | Text | Deskripsi aktivitas, opsional |
| activity_date | Date | Tanggal aktivitas |
| category | String | Kategori aktivitas |
| status | String | Status aktivitas: Planned, Ongoing, atau Done |

## Persyaratan

Pastikan perangkat sudah memiliki:

- PHP sesuai versi yang dibutuhkan project.
- Composer.
- Node.js dan npm.
- Git.

## Instalasi

### 1. Clone Repository

```bash
git clone <URL_REPOSITORY>
cd activity-manager
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Konfigurasi Environment

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Konfigurasi Database

Atur koneksi database SQLite pada file `.env` sesuai konfigurasi project.

Pastikan file database SQLite tersedia sebelum menjalankan migrasi.

### 5. Jalankan Migrasi dan Seeder

```bash
php artisan migrate --seed
```

### 6. Jalankan Aplikasi

Buka terminal pertama:

```bash
php artisan serve
```

Buka terminal kedua:

```bash
npm run dev
```

Akses aplikasi melalui:

http://127.0.0.1:8000/activities

## Pengujian

Jalankan pengujian menggunakan perintah:

```bash
php artisan test
```

## Analisis Kualitas Kode

Project ini menggunakan SonarQube for IDE untuk membantu mendeteksi potensi masalah kualitas kode dan aksesibilitas.

## Status Pengembangan

Fitur CRUD dan filter status telah diimplementasikan. Pengujian dasar Laravel telah dijalankan.

## Lisensi

Project ini dibuat untuk keperluan pembelajaran.

## Acceptance Criteria

1. Pengguna dapat menambahkan aktivitas dengan data yang valid.
2. Pengguna dapat melihat daftar dan detail aktivitas.
3. Pengguna dapat mengubah informasi aktivitas.
4. Pengguna dapat menghapus aktivitas.
5. Pengguna dapat memfilter aktivitas berdasarkan status.
6. Status aktivitas mengikuti transisi Planned → Ongoing → Done.
7. Sistem menolak data yang tidak memenuhi validasi.