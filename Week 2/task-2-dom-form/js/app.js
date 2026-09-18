'use strict';

const peserta = [
    { id: 1, nama: 'Raka', prodi: 'Teknik Informatika' },
    { id: 2, nama: 'Danish', prodi: 'Sistem Informasi' },
];

const form = document.querySelector('#form-peserta');
const namaInput = document.querySelector('#nama');
const prodiInput = document.querySelector('#prodi');
const filterInput = document.querySelector('#filter-prodi');
const daftar = document.querySelector('#daftar-peserta');
const status = document.querySelector('#status');
const errorNama = document.querySelector('#error-nama');
const errorProdi = document.querySelector('#error-prodi');

function validasiPeserta(calon) {
    let valid = true;
    let errorNama = '';
    let errorProdi = '';

    // Nama minimal 3 karakter setelah trim
    if (calon.nama.length < 3) {
        valid = false;
        errorNama = 'Nama minimal 3 karakter.';
    }

    // Program studi wajib dipilih
    if (!calon.prodi) {
        valid = false;
        errorProdi = 'Program studi wajib dipilih.';
    }

    return { valid, errorNama, errorProdi };
}

function buatKartuPeserta(item) {
    const article = document.createElement('article');
    article.classList.add('kartu');
    
    const heading = document.createElement('h2');
    heading.textContent = item.nama;
    
    const deskripsi = document.createElement('p');
    deskripsi.textContent = item.prodi;
    
    article.append(heading, deskripsi);
    return article;
}

function renderPeserta(data) {
    // Kosongkan daftar terlebih dahulu untuk mencegah duplikasi
    daftar.replaceChildren();
    
    // Tangani jika data kosong
    if (data.length === 0) {
        status.textContent = 'Tidak ada peserta';
        return;
    }
    
    // Bersihkan status pesan jika ada data
    status.textContent = '';
    
    const fragment = document.createDocumentFragment();
    for (const item of data) {
        fragment.append(buatKartuPeserta(item));
    }
    daftar.append(fragment);
}

form.addEventListener('submit', (event) => {
    event.preventDefault(); // Mencegah reload halaman
    
    const calonPeserta = {
        nama: namaInput.value.trim(),
        prodi: prodiInput.value
    };
    
    const hasilValidasi = validasiPeserta(calonPeserta);
    
    // Atur pesan error dan aria-invalid untuk Nama
    errorNama.textContent = hasilValidasi.errorNama;
    namaInput.setAttribute('aria-invalid', String(Boolean(hasilValidasi.errorNama)));
    
    // Atur pesan error dan aria-invalid untuk Prodi
    errorProdi.textContent = hasilValidasi.errorProdi;
    prodiInput.setAttribute('aria-invalid', String(Boolean(hasilValidasi.errorProdi)));
    
    // Hentikan proses jika validasi gagal
    if (!hasilValidasi.valid) {
        return; 
    }
    
    // Jika valid, push object baru dengan ID unik, reset, dan render ulang
    const pesertaBaru = {
        id: Date.now(), // Menggunakan timestamp untuk ID unik sementara
        nama: calonPeserta.nama,
        prodi: calonPeserta.prodi
    };
    
    peserta.push(pesertaBaru);
    form.reset();
    
    // Kembalikan filter ke 'semua' agar data baru yang baru masuk terlihat
    filterInput.value = 'semua';
    renderPeserta(peserta);
});

filterInput.addEventListener('change', () => {
    const pilihanFilter = filterInput.value;
    
    if (pilihanFilter === 'semua') {
        renderPeserta(peserta);
    } else {
        // Filter array tanpa mengubah isi array asli
        const hasilFilter = peserta.filter(item => item.prodi === pilihanFilter);
        renderPeserta(hasilFilter);
    }
});

// Render inisial saat halaman dimuat
renderPeserta(peserta);