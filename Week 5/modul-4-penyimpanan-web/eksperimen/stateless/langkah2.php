<?php $nama=is_string($_POST['nama']??null)?$_POST['nama']:''; ?>
<!doctype html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <title>Langkah 2</title>
        <link rel="stylesheet" href="../style.css">
    </head>
    <body>
        <main>
            <h1>Langkah 2 — POST diterima</h1>
            <p>Nama: <?= htmlspecialchars($nama,ENT_QUOTES,'UTF-8') ?></p>
            <a href="langkah3.php">Lanjut melalui GET</a>
        </main>
    </body>
</html>
