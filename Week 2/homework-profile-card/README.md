# Interactive Profile Card (Homework Modul 2)

Proyek ini adalah Interactive Profile Card berbasis Vanilla JavaScript untuk memenuhi evaluasi Homework Modul 2.

## Fitur Tersedia
- Memuat profil awal (nama, deskripsi, array daftar keahlian) dari file `data/profile.json` menggunakan fungsi asinkron (fetch) secara langsung saat dibuka.
- State UI yang reaktif: *loading*, *empty*, dan penanganan gagal jaringan HTTP (*error*) beserta opsi tombol *Coba Lagi*.
- Tombol ubah tema gelap/terang.
- Menambah Keterampilan: Mencegah *form submit* saat input kosong tanpa memuat ulang layar browser.
- Menghapus Keterampilan: Setiap keterampilan memiliki tombol untuk menghapus item secara individu.
- Mencegah klik berulang / modifikasi DOM tak terkontrol (Double Click Prevention).

## Cara Menjalankan Aplikasi
Dikarenakan aplikasi ini menggunakan implementasi `fetch` API lokal, file **tidak boleh** langsung dibuka pada browser menggunakan protokol `file://` agar browser tidak memblokir akses ke `profile.json` karena alasan keamanan/CORS.

**Langkah-langkah:**
1. Buka folder root proyek ini di Text Editor Anda (contoh: VS Code).
2. Jalankan *local development server*. Jika di VS Code, Anda bisa menginstall *Extension "Live Server"*. 
3. Klik kanan pada file `index.html` dan pilih **Open with Live Server**.
4. Aplikasi akan secara otomatis terbuka pada peramban default Anda dengan alamat _localhost_ (misalnya `http://127.0.0.1:5500/index.html`). 
5. Buka **Console DevTools** (F12) untuk melihat *logs* apabila melakukan pengujian pembacaan error (*Troubleshooting*).