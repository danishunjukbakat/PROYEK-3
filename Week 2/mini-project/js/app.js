'use strict';

// ==========================================
// 1. DATA DUMMY (Array of Objects)
// ==========================================
const dataKegiatan = [
  {
    id: 1,
    judul: 'Bedah HTML5 Semantik & WCAG',
    kategori: 'html-css',
    waktu: 'Sabtu, 10:00 WIB',
    deskripsi: 'Memahami penggunaan elemen semantik dan pengujian kriteria aksesibilitas standar WCAG 2.1.',
    ikon: '🏷️'
  },
  {
    id: 2,
    judul: 'Penguasaan Layout Flexbox & Grid',
    kategori: 'html-css',
    waktu: 'Senin, 16:00 WIB',
    deskripsi: 'Teknik menyusun tata letak responsif modern tanpa bantuan library eksternal.',
    ikon: '📐'
  },
  {
    id: 3,
    judul: 'DOM Manipulation & Event Listener',
    kategori: 'javascript',
    waktu: 'Rabu, 15:30 WIB',
    deskripsi: 'Membuat elemen halaman menjadi dinamis dan merespons interaksi pengguna secara alami.',
    ikon: '⚡'
  },
  {
    id: 4,
    judul: 'Simulasi Responsi & Code Defense',
    kategori: 'accessibility',
    waktu: 'Jumat, 13:30 WIB',
    deskripsi: 'Latihan mempresentasikan dan mempertahankan keputusan teknis kode di depan penguji.',
    ikon: '🛡️'
  }
];

// ==========================================
// 2. TEMA GELAP / TERANG (DARK MODE)
// ==========================================
const themeToggleBtn = document.querySelector('#theme-toggle');

function inisialisasiTema() {
  themeToggleBtn.addEventListener('click', () => {
    document.body.classList.toggle('dark-theme');
    const isDark = document.body.classList.contains('dark-theme');
    themeToggleBtn.textContent = isDark ? '☀️ Tema Terang' : '🌙 Tema Gelap';
    themeToggleBtn.setAttribute('aria-label', isDark ? 'Ganti ke Tema Terang' : 'Ganti ke Tema Gelap');
  });
}

// ==========================================
// 3. NAVIGASI MOBILE (HAMBURGER TOGGLE)
// ==========================================
const mobileMenuBtn = document.querySelector('#mobile-menu-btn');
const mainNav = document.querySelector('#main-nav');

function inisialisasiNavigasi() {
  mobileMenuBtn.addEventListener('click', () => {
    const isOpen = mainNav.classList.toggle('is-open');
    mobileMenuBtn.setAttribute('aria-expanded', String(isOpen));
  });

  // Tutup menu saat link navigasi diklik pada tampilan mobile
  const navLinks = document.querySelectorAll('.nav-link');
  navLinks.forEach(link => {
    link.addEventListener('click', () => {
      if (mainNav.classList.contains('is-open')) {
        mainNav.classList.remove('is-open');
        mobileMenuBtn.setAttribute('aria-expanded', 'false');
      }
    });
  });
}

// ==========================================
// 4. RENDER & FILTER DAFTAR DINAMIS
// ==========================================
const daftarKegiatanEl = document.querySelector('#daftar-kegiatan');
const filterSelect = document.querySelector('#filter-kategori');
const statusKegiatan = document.querySelector('#status-kegiatan');

function buatKartuKegiatan(item) {
  const article = document.createElement('article');
  article.classList.add('card', 'feature-card');

  const badge = document.createElement('span');
  badge.classList.add('badge-kategori');
  badge.textContent = item.kategori.replace('-', ' ');

  const ikon = document.createElement('div');
  ikon.classList.add('card-icon');
  ikon.textContent = item.ikon;

  const judul = document.createElement('h3');
  judul.textContent = item.judul;

  const waktu = document.createElement('small');
  waktu.style.color = 'var(--color-primary)';
  waktu.style.fontWeight = 'bold';
  waktu.textContent = item.waktu;

  const deskripsi = document.createElement('p');
  deskripsi.textContent = item.deskripsi;

  article.append(badge, ikon, judul, waktu, deskripsi);
  return article;
}

function renderDaftarKegiatan(data) {
  // Kosongkan container dengan aman tanpa innerHTML
  daftarKegiatanEl.replaceChildren();

  if (data.length === 0) {
    const pesanKosong = document.createElement('p');
    pesanKosong.textContent = 'Tidak ada jadwal kegiatan untuk kategori ini.';
    daftarKegiatanEl.append(pesanKosong);
    statusKegiatan.textContent = 'Daftar kosong.';
    return;
  }

  data.forEach(item => {
    daftarKegiatanEl.append(buatKartuKegiatan(item));
  });

  statusKegiatan.textContent = `Menampilkan ${data.length} kegiatan.`;
}

function inisialisasiFilter() {
  filterSelect.addEventListener('change', (e) => {
    const KategoriPilihan = e.target.value;
    if (KategoriPilihan === 'semua') {
      renderDaftarKegiatan(dataKegiatan);
    } else {
      const dataTerfilter = dataKegiatan.filter(item => item.kategori === KategoriPilihan);
      renderDaftarKegiatan(dataTerfilter);
    }
  });

  // Render awal saat halaman dimuat
  renderDaftarKegiatan(dataKegiatan);
}

// ==========================================
// 5. ACCORDION FAQ
// ==========================================
const faqButtons = document.querySelectorAll('.faq-btn');

function inisialisasiFAQ() {
  faqButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      const isExpanded = btn.getAttribute('aria-expanded') === 'true';

      // Tutup FAQ lainnya (Single accordion behavior)
      faqButtons.forEach(otherBtn => {
        if (otherBtn !== btn) {
          otherBtn.setAttribute('aria-expanded', 'false');
        }
      });

      // Toggle status elemen yang diklik
      btn.setAttribute('aria-expanded', String(!isExpanded));
    });
  });
}

// ==========================================
// 6. VALIDASI FORM PENDAFTARAN
// ==========================================
const signupForm = document.querySelector('#signup-form');
const inputNama = document.querySelector('#nama');
const inputEmail = document.querySelector('#email');
const selectPeminatan = document.querySelector('#peminatan');

const errorNama = document.querySelector('#error-nama');
const errorEmail = document.querySelector('#error-email');
const errorPeminatan = document.querySelector('#error-peminatan');
const statusForm = document.querySelector('#status-form');

function setPesanError(elementInput, elementError, pesan) {
  elementError.textContent = pesan;
  if (pesan) {
    elementInput.setAttribute('aria-invalid', 'true');
  } else {
    elementInput.removeAttribute('aria-invalid');
  }
}

function validasiEmail(email) {
  const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return regexEmail.test(email);
}

function inisialisasiValidasiForm() {
  signupForm.addEventListener('submit', (event) => {
    event.preventDefault();

    let isValid = true;
    const namaVal = inputNama.value.trim();
    const emailVal = inputEmail.value.trim();
    const peminatanVal = selectPeminatan.value;

    // Validasi Nama
    if (namaVal.length < 3) {
      setPesanError(inputNama, errorNama, 'Nama lengkap minimal 3 karakter.');
      isValid = false;
    } else {
      setPesanError(inputNama, errorNama, '');
    }

    // Validasi Email
    if (!validasiEmail(emailVal)) {
      setPesanError(inputEmail, errorEmail, 'Masukkan format email yang valid.');
      isValid = false;
    } else {
      setPesanError(inputEmail, errorEmail, '');
    }

    // Validasi Pilihan
    if (!peminatanVal) {
      setPesanError(selectPeminatan, errorPeminatan, 'Silakan pilih salah satu minat program.');
      isValid = false;
    } else {
      setPesanError(selectPeminatan, errorPeminatan, '');
    }

    // Tanggapan Status
    if (isValid) {
      statusForm.className = 'status-msg success';
      statusForm.textContent = `Terima kasih, ${namaVal}! Pendaftaran Anda berhasil dikirim.`;
      signupForm.reset();
    } else {
      statusForm.className = 'status-msg error';
      statusForm.textContent = 'Mohon perbaiki formulir yang ditandai di atas.';
    }
  });
}

// ==========================================
// 7. TOMBOL KEMBALI KE ATAS (BACK TO TOP)
// ==========================================
const backToTopBtn = document.querySelector('#back-to-top');

function inisialisasiBackToTop() {
  window.addEventListener('scroll', () => {
    if (window.scrollY > 300) {
      backToTopBtn.hidden = false;
    } else {
      backToTopBtn.hidden = true;
    }
  });
}

// ==========================================
// 8. INISIALISASI UTAMA
// ==========================================
document.addEventListener('DOMContentLoaded', () => {
  inisialisasiTema();
  inisialisasiNavigasi();
  inisialisasiFilter();
  inisialisasiFAQ();
  inisialisasiValidasiForm();
  inisialisasiBackToTop();
});