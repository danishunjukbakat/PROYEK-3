<?php
if(PHP_SAPI!=='cli'){http_response_code(404);exit;}
require __DIR__.'/bootstrap.php';
$stmt=db()->prepare('INSERT INTO users(username,password,nama_lengkap) VALUES(?,?,?) ON DUPLICATE KEY UPDATE username=VALUES(username)');
$stmt->execute(['budi',password_hash('rahasia123',PASSWORD_DEFAULT),'Budi Santoso']);
$stmt->execute(['sari',password_hash('belajar123',PASSWORD_DEFAULT),'Sari Lestari']);
echo "Dua akun disiapkan. Akun yang sudah ada tidak diubah.\n";
