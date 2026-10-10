<?php
require __DIR__.'/bootstrap.php';if(!isset($_SESSION['user'])){header('Location: login.php');exit;}
?>
<!doctype html><html lang="id"><meta charset="utf-8"><title>Dashboard session</title><link rel="stylesheet" href="../style.css"><main><h1>Selamat datang, <?= e($_SESSION['user']['nama_lengkap']) ?></h1><p>Username: <?= e($_SESSION['user']['username']) ?></p><p>Identitas tersimpan pada server; browser hanya membawa cookie ID session.</p><form method="post" action="logout.php"><input type="hidden" name="csrf" value="<?= e($_SESSION['csrf']) ?>"><button>Logout</button></form></main></html>
