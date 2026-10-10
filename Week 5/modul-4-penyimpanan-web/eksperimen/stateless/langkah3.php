<?php $nama=is_string($_POST['nama']??null)?$_POST['nama']:'(nama tidak tersedia)'; ?>
<!doctype html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <title>Langkah 3</title>
        <link rel="stylesheet" href="../style.css">
    </head>
    <body>
        <main>
            <h1>Langkah 3 — request baru</h1>
            <p>Nama: <?= htmlspecialchars($nama,ENT_QUOTES,'UTF-8') ?></p>
            <p>POST sebelumnya tidak otomatis ikut dalam request GET ini.</p>
            <a href="langkah1.php">Ulangi</a>
        </main>
    </body>
</html>
