<?php
require __DIR__.'/bootstrap.php';
if($_SERVER['REQUEST_METHOD']!=='POST'){http_response_code(405);header('Allow: POST');exit;}
checkCsrf();$_SESSION=[];$params=session_get_cookie_params();$params['expires']=time()-3600;unset($params['lifetime']);
setcookie(session_name(),'',$params);session_destroy();header('Location: login.php',true,302);exit;
