'use strict';

const status = document.querySelector('#status');
const daftar = document.querySelector('#daftar-materi');
const tombolMuat = document.querySelector('#muat');
const tombolCobaLagi = document.querySelector('#coba-lagi');

function aturState(state, pesan) {
    status.dataset.state = state;
    status.textContent = pesan;
    tombolCobaLagi.hidden = state !== 'error';
}

async function ambilMateri() {
    const response = await fetch('data/materi.json');
    
    // Periksa status HTTP, throw Error jika tidak ok (misal 404)
    if (!response.ok) {
        throw new Error(`HTTP Error: ${response.status}`);
    }
    
    return response.json();
}

function renderMateri(data) {
    // Kosongkan daftar sebelum merender data baru
    daftar.replaceChildren();
    
    const fragment = document.createDocumentFragment();
    for (const item of data) {
        const article = document.createElement('article');
        article.classList.add('kartu');
        
        const heading = document.createElement('h2');
        heading.textContent = item.judul;
        
        const durasi = document.createElement('p');
        durasi.textContent = `Durasi: ${item.durasi} menit`;
        
        article.append(heading, durasi);
        fragment.append(article);
    }
    
    daftar.append(fragment);
}

async function muatData() {
    aturState('loading', 'Memuat data...');
    tombolMuat.disabled = true;
    daftar.replaceChildren();

    try {
        const data = await ambilMateri();
        
        // Bedakan penanganan array kosong dan data berisi
        if (data.length === 0) {
            aturState('empty', 'Data materi belum tersedia.');
        } else {
            renderMateri(data);
            aturState('success', `Berhasil memuat ${data.length} materi.`);
        }
    } catch (error) {
        // Tampilkan state error ke pengguna, log detail ke Console
        console.error(error);
        aturState('error', `Gagal memuat: ${error.message}`);
    } finally {
        // Selalu aktifkan kembali tombol baik saat sukses maupun gagal
        tombolMuat.disabled = false;
    }
}

tombolMuat.addEventListener('click', muatData);
tombolCobaLagi.addEventListener('click', muatData);