'use strict';

function validasiNilai(nilai) {
    // Return true hanya untuk number finite pada 0-100
    return typeof nilai === 'number' && Number.isFinite(nilai) && nilai >= 0 && nilai <= 100;
}

function tentukanKategori(nilai) {
    // Tangani nilai tidak valid, lalu kembalikan A/B/C/D
    if (!validasiNilai(nilai)) {
        return null;
    }
    
    // Susun kondisi dari batas tertinggi ke terendah
    if (nilai >= 85) return 'A';
    if (nilai >= 70) return 'B';
    if (nilai >= 60) return 'C';
    return 'D';
}

function tentukanStatus(nilai) {
    // Kembalikan Data tidak valid, Lulus, atau Tidak lulus
    if (!validasiNilai(nilai)) {
        return 'Data tidak valid';
    }
    
    if (nilai >= 60) return 'Lulus';
    return 'Tidak lulus';
}

function buatRingkasan(nama, nilai) {
    // Return object berisi nama, nilai, kategori, dan status
    return {
        nama: nama,
        nilai: nilai,
        kategori: tentukanKategori(nilai),
        status: tentukanStatus(nilai)
    };
}

// Kasus Uji bawaan dan tambahan
const kasusUji = [
    { nama: 'Alya', nilai: 0 },
    { nama: 'Bima', nilai: 59 },
    { nama: 'Citra', nilai: 60 },
    { nama: 'Danu', nilai: 69 },
    { nama: 'Eka', nilai: 70 },
    { nama: 'Fani', nilai: 85 },
    { nama: 'Gilang', nilai: 101 },
    // Kasus uji buatan sendiri
    { nama: 'Hana', nilai: 84 },
    { nama: 'Iqbal', nilai: -1 },
    { nama: 'Jihan', nilai: '80' },
    { NaN_Test: 'Kiki', nilai: NaN }
];

const hasilUji = kasusUji.map(({ nama, nilai }) => 
    buatRingkasan(nama, nilai)
);

console.table(hasilUji);