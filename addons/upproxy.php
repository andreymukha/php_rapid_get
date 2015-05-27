<title>Proxy list upload</title>
<form method=post>
  Proxies:<br>
  <input type=submit value=OK>
  <center>
    <textarea name="proxy" cols=65 rows=12></textarea><br>
<?php
if (@$_POST['proxy']) {
  $f = fopen('../proxy.lst', 'w');
  if (fwrite($f, htmlspecialchars($_POST['proxy']))) {
    echo "<b>Well Done!</b>";
  }
  else {
    echo "<b>Error! Try to chmod 777 proxy.lst</b>";
  }
  fclose($f);
}
// Done by ValdikSS
?>