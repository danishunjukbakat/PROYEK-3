<?php
declare(strict_types=1);
function e(string $s):string{return htmlspecialchars($s,ENT_QUOTES,'UTF-8');}
function db():PDO{
    $file=__DIR__.'/config.php';
    if(!is_file($file)) throw new RuntimeException('Salin config.example.php ke config.php terlebih dahulu.');
    $config=require $file;
    return new PDO($config['dsn'],$config['username'],$config['password'],[
        PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,PDO::ATTR_EMULATE_PREPARES=>false
    ]);
}
if(PHP_SAPI!=='cli'){
    session_name('modul4_native');
    ini_set('session.use_strict_mode','1');
    session_set_cookie_params(['lifetime'=>0,'path'=>'/session-php','secure'=>!empty($_SERVER['HTTPS'])&&$_SERVER['HTTPS']!=='off','httponly'=>true,'samesite'=>'Lax']);
    session_start();
    $_SESSION['csrf']??=bin2hex(random_bytes(32));
    header('Cache-Control: no-store');
    header('X-Content-Type-Options: nosniff');
}
function checkCsrf():void{
    if(!is_string($_POST['csrf']??null)||!hash_equals($_SESSION['csrf'],$_POST['csrf'])){http_response_code(419);exit('Token formulir tidak valid. Muat ulang halaman.');}
}
