<?php
# Начало авторизацим
$auth_user = FALSE;

function verifypass($pass_or, $pass_in) {
  if (strlen($pass_or) > 4 && @substr($pass_or, 0, 4) === 'MD5:') {
    $new_pass_or = strtoupper(substr($pass_or, 4));
    $new_pass_in = strtoupper(md5($pass_in));
  }
  else {
    $new_pass_or = $pass_or;
    $new_pass_in = $pass_in;
  }

  return $new_pass_or === $new_pass_in;
}

if ($external_auth === TRUE) {
  $auth_user = isset($_SERVER['REMOTE_USER']) ? $_SERVER['REMOTE_USER'] : FALSE;
  if ($authorization !== FALSE) {
    if ($auth_user === FALSE) {
      die("<h1 align=center>Access not granted for anonymouse</h1>\n");
    }
    if (!array_key_exists($auth_user, $authorization)) {
      die("<h1 align=center>Access not Granted for user <b>$auth_user</b></h1>\n");
    }
  }
}
else {
  if ($authorization !== FALSE) {
    if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
      list($user_, $pw_) = explode(':', base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));
      $_SERVER['PHP_AUTH_USER'] = $user_;
      $_SERVER['PHP_AUTH_PW'] = $pw_;
    }

    if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($authorization[$_SERVER['PHP_AUTH_USER']]) || !verifypass($authorization[$_SERVER['PHP_AUTH_USER']], $_SERVER['PHP_AUTH_PW'])) {
      header('WWW-Authenticate: Basic realm="RapidGet"');
      header("HTTP/1.0 401 Unauthorized");
      die("<h1 align=center>Access Denied - username or password is incorrect</h1>\n");
    }
    else {
      $auth_user = $_SERVER['PHP_AUTH_USER'];
    }

  }
}
# Конец авторизации
?>