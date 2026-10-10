<?php
// Laboratorium: sengaja tidak memakai auth. Semua header dikirim sebelum HTML.
$mode=($_GET['mode']??'sesudah')==='sebelum'?'sebelum':'sesudah';
$options=['expires'=>time()+86400,'path'=>'/cookies','httponly'=>false,'samesite'=>'Lax'];
$counter=(int)($_COOKIE['modul4_counter']??0);
function hit(array $options,int &$counter):void{$counter++;setcookie('modul4_counter',(string)$counter,$options);}
if($mode==='sebelum') hit($options,$counter);
if($_SERVER['REQUEST_METHOD']==='POST'){
    if(isset($_POST['hapus'])) { $delete=$options;$delete['expires']=time()-3600;setcookie('modul4_nama','',$delete); }
    else { $nama=is_string($_POST['nama']??null)?mb_substr($_POST['nama'],0,100):'';setcookie('modul4_nama',$nama,$options); }
    header('Location: cookie_dasar.php?mode='.$mode,true,302);exit;
}
if($mode==='sesudah') hit($options,$counter);
?>
<!doctype html><html lang="id"><meta charset="utf-8"><title>Cookie dasar</title><link rel="stylesheet" href="../style.css"><main><h1>Cookie dasar</h1><p>Mode counter: <strong><?= $mode ?> blok redirect</strong>.</p><p>Nama dari cookie request: <?= htmlspecialchars(is_string($_COOKIE['modul4_nama']??null)?$_COOKIE['modul4_nama']:'(belum ada)',ENT_QUOTES,'UTF-8') ?></p><p>Counter terbaru: <?= $counter ?></p><form method="post"><label>Nama <input name="nama" maxlength="100" required></label><button>Simpan</button></form><form method="post"><button name="hapus" value="1">Hapus cookie nama</button></form><p><a href="?mode=sesudah">Counter sesudah redirect</a> · <a href="?mode=sebelum">Counter sebelum redirect</a></p><p>Aktifkan Preserve log di Network. POST membalas 302 lalu browser mengirim GET 200. Bandingkan selisih counter setelah satu submit: +1 versus +2. Pergantian mode sendiri juga membuat request.</p><p>Hapus <code>modul4_counter</code> di DevTools untuk memulai ulang. Nama dan counter adalah dua cookie terpisah.</p></main></html>
