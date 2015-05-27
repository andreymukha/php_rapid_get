<?php
@clearstatcache();
@ini_set('output_buffering', 'off'); //запретить буфферизацию
@ini_set('max_execution_time', 0); //время выполнения 0
@ini_set('memory_limit', '128M'); //лимит памяти 128 метров

error_reporting(1);
@ignore_user_abort(1);
@set_time_limit(0);
@ob_end_clean();
@ob_implicit_flush(TRUE);
define('RAPIDGET', 'yes');
header("Content-Type: text/html; charset=utf-8");

include "config.php";
/*[settings > Languges | Язык]*/
if (file_exists(getcwd() . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR . 'language.' . ($language ? $language : (substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2))) . '.php')) {
  @include_once(getcwd() . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR . 'language.' . ($language ? $language : (substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2))) . '.php');
}
else {
  if (file_exists(getcwd() . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR . 'language.en.php')) {
    @include_once(getcwd() . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR . 'language.en.php');
  }
  else {
    die("Не найден файл локализации! Проверьте структуру и настройки!");
  }
}
/*[settings < Languges | Язык]*/
$is_alias = substr($_SERVER[SCRIPT_FILENAME], -strlen($_SERVER[SCRIPT_NAME])) != $_SERVER[SCRIPT_NAME] ? TRUE : FALSE;
define('HOSTROOT', ($is_alias ? '' : realpath(substr($_SERVER[SCRIPT_FILENAME], 0, -strlen($_SERVER[SCRIPT_NAME])))));
$nn = "\r\n";
include "auth.php";
include "function.php";
$globalquota = readquotafile($workpath);
$workpathoriginal = $workpath;
$loginpathoriginal = $loginpath;
$workpath .= (($auth_user !== FALSE) && ($auth_user !== "") && ($loginpath === TRUE)) ? DIRECTORY_SEPARATOR . $auth_user : "";
if (!realpath($workpath)) {
  die("The path \"<b>" . $workpath . "/</b>\" does not exist");
};
$workpath = realpath($workpath);
if ($workpath != getcwd()) {
  $systemfile[] = array(".htaccess", "files.lst");
}
$personalquota = readquotafile($workpath, $globalquota);
$used_quota = (($personalquota === FALSE) or ($personalquota === TRUE)) ? FALSE : $personalquota;
if (!@headers_sent() && $no_cache && $_GET["command"] != "image") {
  header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
  header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
  header("Cache-Control: no-cache, must-revalidate");
  header("Pragma: no-cache");
}
if (!defined('CRLF')) {
  define('CRLF', "\r\n");
}
define('FTP_OS_Unix', 'u');
define('FTP_OS_Windows', 'w');
define('FTP_OS_Mac', 'm');
$HTTPcode = 0;
foreach ($_POST as $key => $value) {
  $_GET[$key] = $value;
}
if (!$_COOKIE) {
  if (strstr($_SERVER["HTTP_COOKIE"], ";")) {
    foreach (explode("; ", $_SERVER["HTTP_COOKIE"]) as $key => $value) {
      list($var, $val) = explode("=", $value);
      $_COOKIE[$var] = $val;
    }
  }
  else {
    list($var, $val) = @explode("=", $_SERVER["HTTP_COOKIE"]);
    $_COOKIE[$var] = $val;
  }
}

include "cookie.php";
#load from first and secondpage dir
$firstpage_plugin = array();
$secondpage_plugin = array();
$subdomain_plugin = array();
$subreplace_plugin = array();

if (is_dir('plugin') & $_GET["command"] != "image") {
  $plugin_dir = opendir('plugin');
  while (($file = readdir($plugin_dir)) !== FALSE) {
    if ($file[0] == '.') {
      continue;
    }
    if (is_dir('plugin' . DIRECTORY_SEPARATOR . $file)) {
      continue;
    }
    $finfo = pathinfo($file);
    if ($finfo['extension'] == $index_plug_ext) {
      include 'plugin' . DIRECTORY_SEPARATOR . $file;
    }
  }
}

if (isset($_GET[useproxy]) && $_GET[useproxy] && (!$_GET[proxy] || !strstr($_GET[proxy], ":"))) {
  html_error("Not address of the proxy server is specified");
}
else {
  if ($_GET["pauth"]) {
    $pauth = urldecode($_GET["pauth"]);
  }
  else {
    $pauth = ($_GET["proxyuser"] && $_GET["proxypass"]) ? base64_encode($_GET[proxyuser] . ":" . $_GET[proxypass]) : "";
  }
}

if (!$_GET["command"]) {
  include "notlink.php";
  die;
}

if ($_GET["command"] == "image") {
  $LINK = base64_decode(urldecode(trim($_GET["link"])));

  if (!$LINK) {
    die("<html></html>");
  }
  $Referer = (trim($_GET["ref"])) ? base64_decode(urldecode(trim($_GET["ref"]))) : $LINK;

  if (!isset($_GET[useproxy]) || !$_GET[useproxy]) {
    $_GET[proxy] = "";
  }

  $Url = parse_url($LINK);

  if ($_REQUEST["cookie"]) {
    $cookies = unserialize(base64_decode(urldecode($_REQUEST["cookie"])));
  }
  else {
    $cookies = 0;
  }

  $image = geturl($Url["host"], defport($Url), $Url["path"] . ($Url["query"] ? "?" . $Url["query"] : ""), $Referer, $cookies, 0, 0, $_GET["proxy"], $pauth, strtolower($Url["scheme"]));

  list($header, $img) = explode($nn . $nn, $image, 2);

  $h_list = split($nn, $header);

  header("Content-Transfer-Encoding: binary");

  for ($i = 0; $i < count($h_list); $i++) {
    header($h_list[$i], TRUE);
  }
  echo $img;
  exit;
}


if ($_GET["command"] == "second") {

  if ($_GET["saveto"] != "on") {
    $_GET["savedir"] = $workpath;
    $_GET["saveto"] = "on";
  }

  $_GET["link"] = base64_decode(urldecode(trim($_GET["link"])));

  $_GET["orlink"] = trim($_GET["orlink"]) ? $_GET["orlink"] : $_GET["link"];
  $_GET["orlink"] = base64_decode(urldecode(trim($_GET["orlink"])));

  $LINK = $_GET["link"];

  echo "<html><head><title>Page2: Download " . $_GET["orlink"] . "</title><meta http-equiv=Content-Type content=\"text/html; charset=windows-1251\"></head>\n";
  flush();

  if (!isset($_GET[useproxy])) {
    $_GET[proxy] = "";
  }

  if (isset($_GET[domail]) && $_GET[domail] && !checkmail($_GET[email])) {
    html_error("Is Not specified or wrong E-mail");
    if ($_GET[split] & !is_numeric($_GET[partSize])) {
      html_error("Untrue a size of the part is specified");
    }
  }

  $Referer = (trim($_GET["ref"])) ? base64_decode(urldecode(trim($_GET["ref"]))) : trim($LINK);
  $Url = parse_url($LINK);

  if (!$_REQUEST[accesscode]) {
    html_error('Not code of the access is entered with pictures');
  }

  if (array_key_exists($_GET["services"], $secondpage_plugin)) {
    include 'plugin' . DIRECTORY_SEPARATOR . $secondpage_plugin[$_GET["services"]];
    exit;
  }

  exit;
}

if ($_GET["command"] == "request") {
  $LINK = $_GET["link"];

  if (!$LINK) {
    include "notlink.php";
    die;
  }
  if ($_GET["saveto"] != "on") {
    $_GET["savedir"] = $workpath;
    $_GET["saveto"] = "on";
  }

  $LINK = urldecode(ltrim($LINK));

  //$range=(int)$_GET['range'];
  $range = $_GET['range'];

  $_GET["orlink"] = trim($_GET["orlink"]) ? urldecode($_GET["orlink"]) : $_GET["link"];

  if (!isset($_GET[useproxy])) {
    $_GET[proxy] = "";
  }

  if (isset($_GET[domail]) & !checkmail($_GET[email])) {
    html_error("Is Not specified or wrong E-mail");
    if ($_GET[split] & !is_numeric($_GET[partSize])) {
      html_error("Untrue a size of the part is specified");
    }
  }

  $Referer = (trim($_GET["ref"])) ? base64_decode(urldecode(trim($_GET["ref"]))) : trim($LINK);

  $Url = parse_url($LINK);

  if (!$Url["scheme"]) {
    html_error("Unknown URL type (ftp,http,https)");
    $Url["scheme"] = 'http';
  }

  $Url["scheme"] = strtolower($Url["scheme"]);

  if (in_array($Url[host], $unsupported_service)) {
    html_error("This service not support");
  }

  $orhost = FALSE;

  for ($kji = 0; $kji < count($subdomain_plugin); $kji++) {
    $sd = '.' . $subdomain_plugin[$kji];
    $sf = substr($Url[host], -strlen($sd));

    if ($sf == $sd) {
      $orhost = $Url[host];
      $newhost = $subdomain_plugin[$kji];

      $OR_LINK = $LINK;

      if ($subreplace_plugin[$newhost] === TRUE) {
        $Url[host] = $subdomain_plugin[$kji];
        $LINK = str_replace($orhost, $subdomain_plugin[$kji], $LINK);
      }

      break;
    }
  }

  $host_plug = $orhost === FALSE ? $Url[host] : $newhost;

  if (!in_array($host_plug, $supported_service)) {
    echo "<html><head><title>Download $LINK </title><meta http-equiv=Content-Type content=\"text/html; charset=windows-1251\"></head>\n";
    $Href = $LINK;

    $Url = parse_url($Href);

    $FileName = !$FileName ? (basename($Url["path"]) ? basename($Url["path"]) : "attachment") : $FileName;

    $auth = (trim($_REQUEST[httplogin])) ? $auth = "&auth=" . base64_encode(urldecode(trim($_REQUEST[httplogin])) . ":" . urldecode(trim($_REQUEST[httppasswd]))) : "";

    insert_location("$PHP_SELF?command=download&FileName=" . urlencode(base64_encode(urlencode($FileName))) . "&host=" . urlencode(base64_encode($Url[host])) . "&path=" . urlencode(base64_encode(urlencode($Url[path] . ($Url["query"] ? "?" . $Url["query"] : "")))) . "&referer=" . urlencode(base64_encode(urlencode($Referer))) . addmailtolink_2() . "&range=" . $range . "&proxy=" . ($_GET["useproxy"] ? $_GET["proxy"] : "") . "&saveto=" . urlencode($_GET["savedir"]) . "&link=" . urlencode(base64_encode(urlencode($LINK))) . "&orlink=" . urlencode(base64_encode(urlencode($_GET["orlink"]))) . ($_REQUEST["add_comment"] ? "&add_comment=on&comment=" . urlencode($_REQUEST[comment]) : "") . $auth . ($pauth ? "&pauth=" . urlencode($pauth) : "") . "&cookie=" . urlencode(base64_encode(serialize($_GET["usercook"]))), $LINK, $_GET["showdirect"] == "on");
    exit;
  }
  else {
    echo "<html><head><title>Download " . ($OR_LINK ? $OR_LINK : $LINK) . "</title><meta http-equiv=Content-Type content=\"text/html; charset=windows-1251\"></head>\n";

    if (array_key_exists($host_plug, $firstpage_plugin)) {
      include 'plugin' . DIRECTORY_SEPARATOR . $firstpage_plugin[$host_plug];
      exit;
    }
    else {
      html_error("This service not allowed");
    }
  }

}

if ($_GET["command"] == "download") {
  if ($used_quota !== FALSE) {
    @filesinfolder($workpath, FALSE, TRUE);
    if ($fsumm > $used_quota) {
      $excees = ($fsumm - $used_quota);
      if ($hard_quota === TRUE) {
        html_error('You have it is exceed the possible quota on size of the prestored files <br><b>Quota: ' . bytesToKbOrMb($used_quota) . '<br>Exceed: ' . bytesToKbOrMb($excees) . '</b>');
      }

      $dtimer = @min(@round(($excees / $used_quota) * 100), 40);
    }
  }

  $show_all = FALSE;

  if (!$_GET[host]) {
    html_error('Error!, HOST not is specified for this link');
  }

  if (!$_GET[path]) {
    html_error('Error!, PATH not is specified for this link');
  }

  if (!$_GET[FileName]) {
    $_GET[FileName] = "unknown";
    #html_error('Error!, FileName not is specified for this link');
  }
  ?>
  <html>
  <head>
    <meta http-equiv=Content-Type content="text/html; charset=windows-1251">
    <title>Downloaded</title>
  </head>
  <body style="FONT-FAMILY: Tahoma; FONT-SIZE: 11px;">
  <?php insert_timer($dtimer, "Waiting quota timelock.", "", TRUE);
  flush(); ?>
  <center>
  <?php

  #	echo "<pre>\n"; print_r($_GET); echo "</pre>\n";

  $saveto_ = realpath($_GET["saveto"] ? $_GET["saveto"] : $workpath);

  if ($saveto_ === FALSE) {
    html_error('Selected directory not exist');
  }
  if (!is_writable($saveto_)) {
    html_error("Directory $saveto_ is write protected");
  }

  $_GET["saveto"] = $saveto_;

  $_GET["referer"] = urldecode(base64_decode(urldecode($_GET["referer"])));
  $_GET["link"] = urldecode(base64_decode(urldecode($_GET["link"])));
  $_GET["orlink"] = urldecode(base64_decode(urldecode($_GET["orlink"])));
  $_GET["orlink"] = $_GET["orlink"] ? $_GET["orlink"] : $_GET["link"];

  echo "\n<script>\n<!--\nvar orlink='" . str_replace("'", "\\'", urldecode($_GET["orlink"])) . "';\n-->\n</script>\n";
  echo "\n<script>\n<!--\ndocument.title='Downloading 0% ['+orlink+']';\n-->\n</script>\n";

  $_GET["path"] = urldecode(base64_decode(urldecode($_GET["path"])));

  if ($_GET["newagent"] != "") {
    $agent = urldecode($_GET["newagent"]);
  }

  $_GET["host"] = urldecode(base64_decode(urldecode($_GET["host"])));
  $_GET["FileName"] = urldecode(base64_decode(urldecode($_GET["FileName"])));

  $cookies = ($_GET["cookie"]) ? unserialize(base64_decode(urldecode($_GET["cookie"]))) : 0;

  if ($_GET["rapidsharecomconfig"] == "on") {
    unset($cookies);
    $cookies[] = "user=$rapidlogin_com" . '-' . urlencode($rapidpass_com) . "; path=/";
  }

  $pauth = urldecode($_GET["pauth"]);

  $AUTH = ($_GET['auth']) ? array(
    'use' => TRUE,
    'str' => $_GET['auth']
  ) : FALSE;

  $postquery = ($_GET["post"]) ? unserialize(base64_decode(urldecode(trim($_GET["post"])))) : 0;

  do {

    list($_GET["FileName"], $tmp) = explode('?', urldecode(trim($_GET["FileName"])));

    $_GET["path"] = trim($_GET["path"]);
    $_GET["referer"] = trim($_GET["referer"]);
    $_GET["link"] = trim($_GET["link"]);
    if ($_GET["add_comment"] == 'on' && $_GET["comment"] == '' && $_GET[range] == '') {
      $range = 'continueON';
      global $range;
    }
    else {
      $range = $_GET[range];
      global $range;
    }

    $redirectto = "";

    $ftp = parse_url($_GET["link"]);

    $IS_FTP = strtolower($ftp["scheme"]) == "ftp" ? TRUE : FALSE;

    $pathWithName = $_GET["saveto"] . DIRECTORY_SEPARATOR . $_GET["FileName"];

    list($pathWithName, $tmp) = explode('?', $pathWithName);

    switch (strtolower($ftp["scheme"])) {
      case "ftp":
        $defport = 21;
        break;

      case "http":
        $defport = 80;
        break;

      case "https":
        if (!$ssl_support) {
          html_error('This protocol [<b>' . strtolower($ftp["scheme"]) . '</b>] not supported via current host');
        }
        if ($_GET["proxy"]) {
          html_error('This protocol [<b>' . strtolower($ftp["scheme"]) . '</b>] not supported via PROXY');
        }
        $defport = 443;
        break;

      default:
        html_error('Unknown protocol [<b>' . strtolower($ftp["scheme"]) . '</b>]');
        break;
    }

    $ports = $ftp["port"] ? $ftp["port"] : $defport;

    if ($_GET["proxy"]) {
      echo "<p>Connected to proxy : <b>" . $_GET["proxy"] . "</b>...<br>\n";
      echo "GET : <b>" . $_GET["link"] . "</b>...<br>\n";
    }
    else {
      echo "<p>$langconnectedto <b>" . $ftp[host] . ":$ports</b>...<br>";
    }

    switch (strtolower($ftp["scheme"])) {
      case "ftp":
        if (!$_GET["proxy"]) {
          if ($AUTH) {
            list($plog, $ppass) = explode(':', base64_decode($AUTH["str"]), 2);
          }
          else {
            $plog = $ppass = '';
          }

          $AUTH["ftp"]['login'] = ($plog ? urldecode($plog) : "anonymous");
          $AUTH["ftp"]['password'] = ($ppass ? urldecode($ppass) : "admin@microsoft.com");

          $file = getftpurl($_GET["host"], $ports, $_GET[path], $pathWithName);
        }
        else {
          $file = geturl($_GET["host"], $ports, $_GET[path], $_GET[referer], $cookies, $postquery, $pathWithName, $_GET["proxy"], $pauth, strtolower($ftp["scheme"]));
        }
        break;

      case "http":
      case "https":
        $file = geturl($_GET["host"], $ports, $_GET[path], $_GET[referer], $cookies, $postquery, $pathWithName, $_GET["proxy"], $pauth, strtolower($ftp["scheme"]));
        break;
    }

    if ($redir && $lastError && strstr($lastError, "it is redirected to [")) {

      $redirectto = trim(cut_str($lastError, "it is redirected to [", "]"));
      echo "Redirecting to: <b>$redirectto</b> ... <br>";


      if ($_REQUEST["fixcookies"] != "yes") {
        $cookies = GetCookies($file);
      }

      $redirectto = ($redirectto[0] == '.' && $redirectto[1] == '/') ? strtolower($ftp["scheme"]) . "://" . $ftp["host"] . dirname($ftp["path"]) . substr($redirectto, 1) : $redirectto;
      $redirectto = $redirectto[0] == '/' ? strtolower($ftp["scheme"]) . "://" . $ftp["host"] . $redirectto : $redirectto;

      $_GET["link"] = urldecode(urldecode($redirectto));
      $purl = parse_url($redirectto);
      list($_GET["FileName"], $tmp) = explode('?', basename($redirectto));
      $_GET["path"] = $purl["path"] . ($purl["query"] ? "?" . $purl["query"] : "");
      $_GET["host"] = $purl["host"];

      $auth = ($purl["user"] && $purl["pass"]) ? base64_encode($purl["user"] . ":" . $purl["pass"]) : "";

      $AUTH = ($auth) ? array('use' => TRUE, 'str' => $auth) : FALSE;

      $postquery = 0;
      $lastError = "";
    }

  } while ($redirectto && !$lastError);

  if ($lastError) {
    echo $lastError;
  }
  elseif (($file[bytesReceived] == $file[bytesTotal]) or (($file[bytesReceived] > 0) && !$file[bytesTotal])) {
    $inCurrDir = (HOSTROOT != '') && (strpos(dirname($file["file"]), HOSTROOT . DIRECTORY_SEPARATOR) == 0) ? TRUE : FALSE;

    if ($inCurrDir) {
      $Path = dirname(substr($file["file"], strlen(HOSTROOT)));
      $Path = str_replace(DIRECTORY_SEPARATOR, '/', $Path);
      $Path = str_replace("'", "\\'", $Path == '/' ? "" : $Path);
    }

    echo "<script>pr(100, '" . $file[size] . "', '" . $file[speed] . "','')</script>\r\n";

    echo "$langfile <b>" . ($inCurrDir ? "<a href='" . $Path . "/" . str_replace("'", "\\'", basename($file["file"])) . "'>" : "") . basename($file["file"]) . ($inCurrDir ? "</a>" : "") . "</b> (<b>" . $file[size] . "</b>) $langsaved!<br>$langtime <b>" . $file[time] . "</b><br>$langaveragespeed <b>" . $file[speed] . " KB/s</b><br>";

    if (!write_file($workpath . DIRECTORY_SEPARATOR . "files.lst", serialize(array(
          "name" => $file["file"],
          "size" => $file["size"],
          "date" => time(),
          "link" => $_GET["orlink"],
          "comment" => str_replace("\n", "\\n", str_replace("\r", "\\r", $_GET["comment"]))
        )) . "\r\n", 0)
    ) {
      echo "Couldn't update the files list<br>";
    }
    if ($_GET["email"]) {
      echo "<p>\n";
      while (file_exists(".sending"))//Проверяет наличие файла
      {
        echo "<center>already sending messages, waiting 10 seconds...</center>";
        flush();
        sleep(10);
      }
      touch(".sending");//Создает файл
      echo "<table cellpadding=1 cellspacing=1 align=center><thead><tr bgcolor=#F2F2F2><th>Part<th>Mail<th>Status</tr></thead>\n";
      $_GET[partSize] = (isset($_GET[partSize]) ? $_GET[partSize] * 1024 * 1024 : FALSE);

      if (xmail($fromaddr, $_GET["email"], "File " . basename($file["file"]), "File: " . basename($file["file"]) . "\r\n" . "Link: " . $_GET["orlink"] . ($_GET["comment"] ? "\r\n" . "Comments: " . str_replace("\\r\\n", "\r\n", $_GET["comment"]) : ""), $file["file"], $_GET["partSize"], $_GET["method"], (is_numeric($_GET["mdelay"]) ? $_GET["mdelay"] : 0))) {
        echo "<script>minfo('File was sent to this address<b> " . $_GET[email] . "</b>!', '" . md5(basename($file["file"])) . "');</script>\r\n";
      }
      else {
        echo "<script>minfo('Error Sending file " . basename($file["file"]) . " to <b>" . $_GET[email] . "</b>!', '" . md5(basename($file["file"])) . "');</script>\r\n";
      }
      echo "</table>\n";
      unlink(".sending");//Удаляет файл
    }

    echo "<br><a href=$PHP_SELF style=\"color: #0000FF;\">$langgobacktomain!</a>";
    echo "<br><a href=$PHP_SELF onclick=javascript:window.close(); style=\"color: #0000FF;\">$langclosewindow!</a>";
    if ($showautoclose == "true") {
      echo "<br><script><!-- \n var time = " . $timeautoclose . ";function vbaccept(){time--;frm = document.vbaccept;if (frm) frm.submit.value = 'Автозакрытие через '+time+''; if (time>0)window.setTimeout(\"vbaccept()\",1);else if (frm){frm.submit.value = 'Закрыть окно?';frm.submit.disabled=0;window.close(self);}}vbaccept(); \n --></script><form id=vbaccept name=vbaccept> <input type=submit name=submit value=\"включи javascript\" disabled></form>";
    }
  }
  ?>
  </center>
  </body>
  </html>
<?php
}
?>
