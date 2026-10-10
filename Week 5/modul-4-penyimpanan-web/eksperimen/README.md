# Eksperimen penyimpanan web

Di root repository jalankan `php -S 127.0.0.1:8000 -t eksperimen`, kemudian buka http://127.0.0.1:8000. PHP 8.3+, `mbstring`, dan `pdo_mysql` diperlukan. Tidak perlu Composer untuk folder ini. Gunakan origin yang sama selama percobaan; `localhost` dan `127.0.0.1` berbeda.

## Urutan eksperimen dan keluaran yang diharapkan

Keluaran di bawah merupakan panduan untuk dibandingkan dengan hasil praktik pada browser Anda; bukan klaim bahwa semua siklus hidup browser sudah diuji otomatis.

1. **Stateless:** isi nama di langkah 1. Langkah 2 menerima POST dan menampilkan nama. Klik lanjut: langkah 3 tidak menerima POST yang lama. Versi `session-nama` menyimpan nama pada server sehingga GET dan refresh masih menampilkannya.
2. **Cookie dasar:** buka DevTools > Application/Storage > Cookies dan Network; aktifkan Preserve log. Pada mode counter *sesudah redirect*, satu submit menambah 1; pada mode *sebelum redirect*, POST dan GET masing-masing menambah 1 (total +2). POST menerima 302, request berikutnya GET 200. Bandingkan counter sebelum dan sesudah satu submit, jangan menyertakan request pergantian mode. Set-Cookie baru terlihat pada request berikutnya. Hapus cookie nama melalui tombol; counter tidak ikut terhapus karena key-nya berbeda. Perhatikan path `/cookies`.
3. **Keranjang cookie:** kosongkan, tambahkan 2 Buku Tulis dan 1 Pulpen. Total Rp13.000. Refresh tetap ada. Jalankan contoh Console untuk mengubah jumlah buku ke 999: total menjadi Rp4.998.000. Latihan ini sengaja mempercayai jumlah client dan tidak menjalankan checkout. Amati header Cookie dan bandingkan dengan Local Storage. Hapus setelah eksperimen.
4. **Session login PHP:** impor `session-php/database.sql` ke MySQL; salin `config.example.php` ke `config.php` dan sesuaikan akses DB; dari root jalankan `php eksperimen/session-php/seed_user.php`. Login budi/rahasia123 atau sari/belajar123. Bandingkan ID cookie `modul4_native` sebelum/sesudah login (berubah). Password salah ditolak; akses dashboard sebelum login dialihkan; refresh setelah login tetap berhasil; POST logout menghapus session. Periksa kolom password (hash, bukan teks asli). Seeder CLI tidak dapat dipanggil melalui browser. Login ini untuk laboratorium lokal; fitur throttling tersedia pada dua aplikasi Laravel.
5. **API Local Storage:** simpan, baca, hapus nama; pilih tema gelap; refresh. Lihat nilai key `modul4_nama` dan `modul4_tema` di DevTools. Semua nilai storage berbentuk string; objek menggunakan JSON.stringify/JSON.parse.
6. **Keranjang Local Storage:** isi 2 Buku Tulis + 1 Pulpen, hasil Rp13.000; ubah `modul4_cart` via Console seperti contoh menjadi 999 buku + 1 pulpen, hasil Rp4.998.000. Data ini tidak otomatis muncul pada header Cookie. Kode menggunakan textContent agar nama tidak disisipkan sebagai HTML.
7. **Masa hidup:** tekan Simpan pada `storage_beda.html` sekali. Refresh: kedua nilai tetap ada. Tab baru tanpa opener: localStorage berbagi nilai, sessionStorage kosong. Tutup tab dan buka kembali URL secara normal: periksa perbedaannya. Setelah tutup/buka browser, localStorage umumnya bertahan; pemulihan sesi, mode privat, dan pengaturan browser dapat memengaruhi hasil. Halaman tidak menulis ulang data saat load, sehingga hilangnya nilai tidak tertutupi.
8. **Akses JavaScript:** di Console milik halaman sendiri, jalankan `localStorage.getItem('modul4_nama')`. Ini menjelaskan mengapa skrip berbahaya yang berhasil berjalan pada origin yang sama dapat membaca Local Storage. Cookie session Laravel memakai HttpOnly sehingga tidak terbaca lewat document.cookie, tetapi tetap dikirim browser pada request yang sesuai. HttpOnly bukan pencegah seluruh dampak XSS.

## Catatan pengamatan

Catat URL, tanggal, browser, nilai sebelum/sesudah, dan tangkapan layar asli. Gunakan `../docs/PANDUAN_SCREENSHOT.md`. Jangan mengganti pengamatan dengan keluaran yang diharapkan. Hash adalah fungsi satu arah untuk verifikasi password, bukan enkripsi yang didekripsi kembali.
