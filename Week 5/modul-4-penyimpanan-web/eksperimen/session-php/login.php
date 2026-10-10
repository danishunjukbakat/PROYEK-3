<?php
require __DIR__.'/bootstrap.php';
if(isset($_SESSION['user'])){header('Location: dashboard.php');exit;}
$error='';$username='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    checkCsrf();$username=is_string($_POST['username']??null)?trim($_POST['username']):'';$password=is_string($_POST['password']??null)?$_POST['password']:'';
    if($username===''||$password==='') $error='Username dan password wajib diisi.';
    else {
        $stmt=db()->prepare('SELECT id,username,password,nama_lengkap FROM users WHERE username=? LIMIT 1');$stmt->execute([$username]);$user=$stmt->fetch();
        // Hash dummy menjaga jalur verifikasi tetap dijalankan jika username tidak dikenal.
        $hash=$user['password']??'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.';
        $valid=password_verify($password,$hash);
        if($user&&$valid){session_regenerate_id(true);$_SESSION['user']=['id'=>$user['id'],'username'=>$user['username'],'nama_lengkap'=>$user['nama_lengkap']];$_SESSION['csrf']=bin2hex(random_bytes(32));header('Location: dashboard.php',true,302);exit;}
        $error='Username atau password salah.';
    }
}
?>
<!doctype html><html lang="id"><meta charset="utf-8"><title>Login session PHP</title><link rel="stylesheet" href="../style.css"><main><h1>Login PHP native</h1><p><?= e($error) ?></p><form method="post"><input type="hidden" name="csrf" value="<?= e($_SESSION['csrf']) ?>"><p><label>Username <input name="username" value="<?= e($username) ?>" maxlength="50" autocomplete="username" required></label></p><p><label>Password <input name="password" type="password" autocomplete="current-password" required></label></p><button>Login</button></form><p>Siapkan database dan jalankan seed melalui CLI sesuai README.</p></main></html>
