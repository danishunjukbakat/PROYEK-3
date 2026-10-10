<?php
session_name('modul4_nama');session_start();
$_SESSION['nama']=is_string($_POST['nama']??null)?mb_substr($_POST['nama'],0,100):'';
header('Location: langkah3.php',true,302);exit;
