'use strict';

const statusEl = document.querySelector('#status');
const tombolCobaLagi = document.querySelector('#coba-lagi');
const kontenProfil = document.querySelector('#konten-profil');
const namaProfil = document.querySelector('#nama-profil');
const deskripsiProfil = document.querySelector('#deskripsi-profil');
const tombolDetail = document.querySelector('#toggle-detail');
const detailProfil = document.querySelector('#detail-profil');
const daftarKeterampilan = document.querySelector('#daftar-keterampilan');
const formKeterampilan = document.querySelector('#form-keterampilan');
const inputKeterampilan = document.querySelector('#input-keterampilan');
const errorKeterampilan = document.querySelector('#error-keterampilan');
const tombolTema = document.querySelector('#toggle-tema');
const btnTambah = document.querySelector('#btn-tambah');

let dataKeterampilan = [];

// 1. Ganti Tema
tombolTema.addEventListener('click', () => {
  document.body.classList.toggle('dark-theme');
});

// 2. Toggle Detail (classList.toggle & aria-expanded)
tombolDetail.addEventListener('click', () => {
  const isHidden = detailProfil.classList.toggle('tersembunyi');
  tombolDetail.setAttribute('aria-expanded', String(!isHidden));
  tombolDetail.textContent = isHidden ? 'Tampilkan Detail' : 'Sembunyikan Detail';
});

// 3. Mengatur State Antarmuka
function aturState(state, pesan) {
  statusEl.textContent = pesan;
  statusEl.className = '';
  tombolCobaLagi.hidden = true;
  kontenProfil.hidden = true;
  
  if (state === 'loading') {
    statusEl.classList.add('status-loading');
  } else if (state === 'error') {
    statusEl.classList.add('status-error');
    tombolCobaLagi.hidden = false;
  } else if (state === 'empty') {
    statusEl.classList.add('status-empty');
  } else if (state === 'success') {
    statusEl.textContent = '';
    kontenProfil.hidden = false;
  }
}

// 4. Render Keterampilan dan Hapus Item
function renderKeterampilan() {
  daftarKeterampilan.replaceChildren(); // Mencegah duplikasi data render
  
  if (dataKeterampilan.length === 0) {
    const li = document.createElement('li');
    li.textContent = 'Belum ada keterampilan.';
    daftarKeterampilan.append(li);
    return;
  }

  for (const item of dataKeterampilan) {
    const li = document.createElement('li');
    li.textContent = item.nama;
    
    const btnHapus = document.createElement('button');
    btnHapus.textContent = 'Hapus';
    btnHapus.type = 'button';
    btnHapus.addEventListener('click', () => {
      dataKeterampilan = dataKeterampilan.filter(k => k.id !== item.id);
      renderKeterampilan();
    });

    li.append(btnHapus);
    daftarKeterampilan.append(li);
  }
}

// 5. Asynchronous Loader
async function ambilProfil() {
  const response = await fetch('data/profile.json');
  if (!response.ok) {
    throw new Error(`HTTP ${response.status}`);
  }
  return response.json();
}

async function muatData() {
  aturState('loading', 'Memuat profil...');
  tombolCobaLagi.disabled = true;
  
  try {
    const data = await ambilProfil();
    
    // Mengecek apakah data JSON benar-benar kosong
    if (!data || Object.keys(data).length === 0) {
      aturState('empty', 'Data profil kosong.');
      return;
    }

    namaProfil.textContent = data.nama;
    deskripsiProfil.textContent = data.deskripsi;
    dataKeterampilan = data.keterampilan || [];
    
    renderKeterampilan();
    aturState('success', '');
  } catch (error) {
    console.error(error);
    aturState('error', `Gagal memuat profil: ${error.message}`);
  } finally {
    tombolCobaLagi.disabled = false;
  }
}

// 6. Validasi Form Tambah Keterampilan
formKeterampilan.addEventListener('submit', (event) => {
  event.preventDefault(); // Mencegah perilaku memuat ulang halaman
  
  const namaBaru = inputKeterampilan.value.trim();
  
  if (!namaBaru) {
    errorKeterampilan.textContent = 'Keterampilan tidak boleh kosong.';
    inputKeterampilan.setAttribute('aria-invalid', 'true');
    return;
  }

  // Jika input valid
  errorKeterampilan.textContent = '';
  inputKeterampilan.removeAttribute('aria-invalid');
  btnTambah.disabled = true; // Mencegah double click secara beruntun
  
  const skillBaru = {
    id: Date.now(), // Menggunakan timestamp untuk ID unik sederhana
    nama: namaBaru
  };
  
  dataKeterampilan.push(skillBaru);
  inputKeterampilan.value = '';
  renderKeterampilan();
  
  btnTambah.disabled = false;
});

tombolCobaLagi.addEventListener('click', muatData);

// Panggil fungsi muat data pertama kali saat halaman dimuat
muatData();