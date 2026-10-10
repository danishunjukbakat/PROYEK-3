<?php
$products=[1=>['Buku Tulis',5000],2=>['Pulpen',3000],3=>['Penggaris',4000]];
$raw=json_decode($_COOKIE['modul4_cart']??'{}',true);$cart=is_array($raw)?$raw:[];
if($_SERVER['REQUEST_METHOD']==='POST'){
    if(isset($_POST['clear'])) $cart=[];
    elseif(isset($products[(int)($_POST['id']??0)])){$id=(int)$_POST['id'];$cart[$id]=(int)($cart[$id]??0)+1;}
    setcookie('modul4_cart',json_encode($cart,JSON_FORCE_OBJECT),['expires'=>time()+86400,'path'=>'/cookies','samesite'=>'Lax']);
    header('Location: keranjang_cookie.php',true,302);exit;
}
?>
<!doctype html><html lang="id"><meta charset="utf-8"><title>Keranjang cookie</title><link rel="stylesheet" href="../style.css"><main><h1>Keranjang cookie</h1><p class="note">Khusus eksperimen manipulasi data: jumlah dalam cookie sengaja dipercaya tanpa batas stok. Jangan gunakan pola ini untuk checkout nyata.</p>
<?php foreach($products as $id=>$p): ?><form method="post"><input type="hidden" name="id" value="<?= $id ?>"><button>Tambah <?= $p[0] ?> — Rp <?= number_format($p[1],0,',','.') ?></button></form><?php endforeach; ?>
<table><tr><th>Barang</th><th>Jumlah</th><th>Subtotal</th></tr><?php $total=0;foreach($cart as $id=>$qty):if(!isset($products[$id])||!is_numeric($qty))continue;$qty=(int)$qty;$sub=$qty*$products[$id][1];$total+=$sub; ?><tr><td><?= $products[$id][0] ?></td><td><?= $qty ?></td><td>Rp <?= number_format($sub,0,',','.') ?></td></tr><?php endforeach; ?></table><p>Total: <strong>Rp <?= number_format($total,0,',','.') ?></strong></p><form method="post"><button name="clear" value="1">Kosongkan</button></form><p>Contoh perubahan sendiri di Console:</p><pre>document.cookie = 'modul4_cart=' + encodeURIComponent(JSON.stringify({1:999,2:1})) + '; path=/cookies; SameSite=Lax';
location.reload();</pre><p>Cookie dikirim kembali pada request HTTP sesuai path-nya. Aplikasi Laravel dalam paket ini memvalidasi jumlah di server.</p></main></html>
