<?php
if (!defined('RAPIDGET')) {
  die("not load primary script");
}

/* Fixed Shell exploit by: icedog & Valdikss. Update: TRiTON. */
function fixfilename($fname, $fpach = '') {
  $f_name = basename($fname);
  $f_dir = dirname(eregi_replace("\.\./", "", $fname));
  $f_dir = ($f_dir == '.') ? '' : $f_dir;
  $f_dir = eregi_replace("\.\./", "", $f_dir);
  $fpach = eregi_replace("\.\./", "", $fpach);
  $f_name = eregi_replace("\.(php|hta|pl|cgi|sph|phtm|pmht|pxml|inc|conf|cfg|shtm|dhtm)", ".xxx", $f_name);
  $ret = ($fpach) ? $fpach . DIRECTORY_SEPARATOR . $f_name : ($f_dir ? $f_dir . DIRECTORY_SEPARATOR : '') . $f_name;
  return $ret;
}

function getfilesize($f) {
  global $is_windows;
  $stat = stat($f);

  if ($is_windows) {
    return sprintf("%u", $stat[7]);
  }
  if (($stat[11] * $stat[12]) < 4 * 1024 * 1024 * 1024) {
    return sprintf("%u", $stat[7]);
  }

  global $max_4gb;
  if ($max_4gb === FALSE) {
    $tmp_ = trim(@shell_exec(" ls -Ll " . @escapeshellarg($f)));
    while (strstr($tmp_, '  ')) {
      $tmp_ = @str_replace('  ', ' ', $tmp_);
    }
    $r = @explode(' ', $tmp_);
    $size_ = $r[4];
  }
  else {
    $size_ = -1;
  }

  return $size_;
}

function readquotafile($dir, $def = FALSE) {
  global $use_quota;
  if ($use_quota === FALSE or !$use_quota) {
    return $def;
  }
  if (@is_numeric($use_quota)) {
    return $use_quota;
  }
  if (!@file_exists($dir . DIRECTORY_SEPARATOR . $use_quota)) {
    return $def;
  }
  $q = @trim(@file_get_contents($dir . DIRECTORY_SEPARATOR . $use_quota));

  if ($q === FALSE) {
    return $def;
  }

  return (strtolower($q) == 'unlimited') ? TRUE : (!@is_numeric($q) ? $def : $q);
}

$fcounts = 0;
$fsumm = 0;
$dcounts = 0;

function filesinfolder($folder, $rec = FALSE, $first = FALSE) {
  global $systemfile, $fcounts, $fsumm, $dcounts;
  if ($first === TRUE) {
    $fcounts = 0;
    $fsumm = 0;
    $dcounts = 0;
  }

  $dir = dir($folder);
  if (!$dir) {
    return FALSE;
  }

  while (FALSE !== ($file = $dir->read())) {
    if ($file{0} == "." || in_array($file, $systemfile) || !is_readable($folder . DIRECTORY_SEPARATOR . $file)) {
      continue;
    }

    $file = $folder . DIRECTORY_SEPARATOR . $file;
    if (is_dir($file)) {
      $rsumm = $rec === TRUE ? filesinfolder($file . DIRECTORY_SEPARATOR, TRUE) : 0;
      $dcounts += $rsumm && $first === TRUE ? 1 : 0;
      if ($rsumm === FALSE) {
        $dir->close();
        return FALSE;
      }
      $fsumm += $rsumm;
      continue;
    }

    $fcounts++;
    $fsumm += getfilesize($file);
  }
  $dir->close();

  return TRUE;
}

function addmailtolink($domail_, $email_, $split_, $partSize_, $method_, $mdelay_ = 0) {
  return ($domail_ == "on") ? "&email=$email_" . ($split_ ? ($mdelay_ ? "&mdelay=" . $mdelay_ : "") . "&partSize=$partSize_&method=$method_" : "") : "";
}

function addmailtolink_2() {
  $domail_ = $_GET["domail"];
  $email_ = $_GET["email"];
  $split_ = $_GET["split"];
  $partSize_ = $_GET["partSize"];
  $method_ = $_GET["method"];
  $mdelay_ = $_GET["mdelay"];
  return ($domail_ == "on") ? "&email=$email_" . ($split_ ? ($mdelay_ ? "&mdelay=" . $mdelay_ : "") . "&partSize=$partSize_&method=$method_" : "") : "";
}

function defport($urls) {
  if ($urls["port"] !== '' && isset($urls["port"])) {
    return $urls["port"];
  }

  switch (strtolower($urls["scheme"])) {
    case "https" :
      return '443';
    case "ftp" :
      return '21';
    default:
      return '80';
  }
}

function chunked($data) {
  global $nn;
  list($header, $info) = explode($nn . $nn, $data, 2);

  if (!strstr($header, 'Transfer-Encoding: chunked')) {
    return $data;
  }

  while ($info) {
    list($chunk, $info) = explode($nn, $info, 2);
    $chunk = hexdec('0x' . $chunk);

    $myinfo .= substr($info, 0, $chunk);

    $info = substr($info, $chunk);
  }

  $h_list = split($nn, $header);

  for ($i = 0; $i < count($h_list); $i++) {
    if (strstr($h_list[$i], 'Transfer-Encoding: chunked')) {
      continue;
    }
    $myhead .= rtrim($h_list[$i]) . $nn;
  }

  $myinfo = $myhead . 'Content-Length: ' . strlen($myinfo) . $nn . $nn . $myinfo;
  return $myinfo;
}


function GetCookies($content, $short = FALSE) {
  global $nn;
  list($header, $info) = explode($nn . $nn, $content, 2);

  $i = preg_match_all("/Set-Cookie: (.*)\n/", $header, $matches);
  for ($j = 0; $j < $i; $j++) {
    if ($short !== TRUE) {
      $res[] = $matches[1][$j];
    }
    else {
      list($st, $tmp) = explode(';', $matches[1][$j], 2);
      $res[] = $st;
    }
  }
  return $res;
}

$__crc32_table = array();

function __crc32_init_table() {
  global $__crc32_table;
  $polynomial = 0x04c11db7;

  for ($i = 0; $i <= 0xFF; ++$i) {
    $__crc32_table[$i] = (__crc32_reflect($i, 8) << 24);
    for ($j = 0; $j < 8; ++$j) {
      $__crc32_table[$i] = (($__crc32_table[$i] << 1) ^ (($__crc32_table[$i] & (1 << 31)) ? $polynomial : 0));
    }
    $__crc32_table[$i] = __crc32_reflect($__crc32_table[$i], 32);
  }
}

function __crc32_reflect($ref, $ch) {
  $value = 0;

  for ($i = 1; $i < ($ch + 1); ++$i) {
    if ($ref & 1) {
      $value |= (1 << ($ch - $i));
    }
    $ref = (($ref >> 1) & 0x7fffffff);
  }
  return $value;
}

function __crc32_decode($crc) {
  global $__crc32_table;
  $a[0] = 0xFF;
  $a[1] = 0xFF;
  $a[2] = 0xFF;
  $a[3] = 0xFF;
  $a[4] = ($crc >> 0) & 0xFF;
  $a[5] = ($crc >> 8) & 0xFF;
  $a[6] = ($crc >> 16) & 0xFF;
  $a[7] = ($crc >> 24) & 0xFF;
  for ($i = 7; $i > 3; $i--) {
    for ($q = 0; ($i < 0xFF) && ((($__crc32_table[$q] >> 24) & (0xFF)) != $a[$i]); $q++) {
      ;
    }
    $a[$i - 4] ^= $q;
    $a[$i - 3] ^= ($__crc32_table[$q] >> 0) & 0xFF;
    $a[$i - 2] ^= ($__crc32_table[$q] >> 8) & 0xFF;
    $a[$i - 1] ^= ($__crc32_table[$q] >> 16) & 0xFF;
  }
  return chr($a[0]) . chr($a[1]) . chr($a[2]) . chr($a[3]);
}

function crc32_file($filename) {
  global $__crc32_table;
  $crc = FALSE;

  $f = fopen($filename, 'r');
  if (!$f) {
    return FALSE;
  }
  $fs = getfilesize($filename);

  __crc32_init_table();

  while (!feof($f)) {
    $fdata = fread($f, 1024);

    if (!$fdata) {
      fclose($f);
      return FALSE;
    }
    if ($crc === FALSE) {
      $crc = crc32($fdata) ^ 0xFFFFFFFF;
    }
    else {
      $lw = strlen($fdata);
      $lw = min(4, $lw);
      for ($wwq = 0; $wwq < $lw; $wwq++) {
        $crc = (($crc >> 8) & 0x00ffffff) ^ $__crc32_table[($crc & 0xFF) ^ ord($fdata[$wwq])];
      }

      if (strlen($fdata) > 4) {
        $crc = __crc32_decode($crc);
        $fileChunk[0] = $crc[0];
        $fileChunk[1] = $crc[1];
        $fileChunk[2] = $crc[2];
        $fileChunk[3] = $crc[3];

        $crc = crc32($fdata) ^ 0xFFFFFFFF;
      }
    }
  }

  fclose($f);

  return $crc;
}

function GetChunkSize($fsize) {
  if ($fsize <= 1024 * 1024) {
    return 4096;
  }
  if ($fsize <= 1024 * 1024 * 10) {
    return 4096 * 10;
  }
  if ($fsize <= 1024 * 1024 * 40) {
    return 4096 * 30;
  }
  if ($fsize <= 1024 * 1024 * 80) {
    return 4096 * 47;
  }
  if ($fsize <= 1024 * 1024 * 120) {
    return 4096 * 65;
  }
  if ($fsize <= 1024 * 1024 * 150) {
    return 4096 * 70;
  }
  if ($fsize <= 1024 * 1024 * 200) {
    return 4096 * 85;
  }
  if ($fsize <= 1024 * 1024 * 250) {
    return 4096 * 100;
  }
  if ($fsize <= 1024 * 1024 * 300) {
    return 4096 * 115;
  }
  if ($fsize <= 1024 * 1024 * 400) {
    return 4096 * 135;
  }
  if ($fsize <= 1024 * 1024 * 500) {
    return 4096 * 170;
  }
  if ($fsize <= 1024 * 1024 * 1000) {
    return 4096 * 200;
  }
  return 4096 * 210;
}

function querytoarray($query_str) {
  $res = array();
  $pars = explode('&', $query_str);
  for ($i = 0; $i < count($pars); $i++) {
    list($key, $val) = explode('=', $pars[$i]);
    $res[$key] = $val;
  }
  return $res;
}

function HTTPStatus($stauscode) {
  switch ($stauscode) {
    case 100:
      return ("$stauscode: Continue");
    case 101:
      return ("$stauscode: Switching Protocols");

    case 200:
      return ("$stauscode: OK");
    case 201:
      return ("$stauscode: Created");
    case 202:
      return ("$stauscode: Accepted");
    case 203:
      return ("$stauscode: Non-Authoritative Information");
    case 204:
      return ("$stauscode: No Content");
    case 205:
      return ("$stauscode: Reset Content");
    case 206:
      return ("$stauscode: Partial Content");

    case 300:
      return ("$stauscode: Multiple Choices");
    case 301:
      return ("$stauscode: Moved Permanently");
    case 302:
      return ("$stauscode: Found");
    case 303:
      return ("$stauscode: See Other");
    case 304:
      return ("$stauscode: Not Modified");
    case 305:
      return ("$stauscode: Use Proxy");
    case 307:
      return ("$stauscode: Temporary Redirect");

    case 400:
      return ("$stauscode: Bad request");
    case 401:
      return ("$stauscode: Authorization Required");
    case 402:
      return ("$stauscode: Payment Required");
    case 403:
      return ("$stauscode: Forbidden");
    case 404:
      return ("$stauscode: Not found");
    case 405:
      return ("$stauscode: Method not Allowed");
    case 406:
      return ("$stauscode: Not Acceptable");
    case 407:
      return ("$stauscode: Proxy Authentication Required");
    case 408:
      return ("$stauscode: Request timeout");
    case 409:
      return ("$stauscode: Conflict");
    case 410:
      return ("$stauscode: Gone");
    case 411:
      return ("$stauscode: Length required");
    case 412:
      return ("$stauscode: Precondition failed");
    case 413:
      return ("$stauscode: Request entity too large");
    case 414:
      return ("$stauscode: Request URI too large");
    case 415:
      return ("$stauscode: Unsupported media type");
    case 416:
      return ("$stauscode: Range not satisfiable. Not found file or other problems.");
    case 417:
      return ("$stauscode: Expectation Failed");
    case 422:
      return ("$stauscode: Unprocessable Entity");
    case 423:
      return ("$stauscode: Locked");
    case 424:
      return ("$stauscode: Failed Dependency");
    case 426:
      return ("$stauscode: Upgrade Required");

    case 500:
      return ("$stauscode: Internal server error");
    case 501:
      return ("$stauscode: Not Implemented");
    case 502:
      return ("$stauscode: Bad Gateway");
    case 503:
      return ("$stauscode: Service unavailable");
    case 504:
      return ("$stauscode: Gateway Timeout");
    case 505:
      return ("$stauscode: Version Not Supported");
    case 506:
      return ("$stauscode: Variant also varies");
    case 507:
      return ("$stauscode: Insufficient Storage");
    case 510:
      return ("$stauscode: Not Extended");
    default:
      return ("$stauscode: $extcode");
  }
}

function is_present($lpage, $mystr, $strerror = "") {
  $strerror = !$strerror ? $mystr : $strerror;
  if (strstr($lpage, $mystr)) {
    html_error($strerror);
  }
}

function is_notpresent($lpage, $mystr, $strerror) {
  if (!strstr($lpage, $mystr)) {
    html_error($strerror);
  }
}

function insert_location($newlocation, $url = "", $showurl = FALSE, $test = FALSE) {

  if ($url && $showurl) {
    echo "<center><b>$url</b></center>\n";
    return TRUE;
  }

  if ($test === FALSE) {
    ?>

    <script language=javascript>
      location.href = '<?php echo $newlocation ?>';
    </script>
  <?php
  }
  else {
    echo "<center><b>$newlocation</b></center>\n";
  }
  flush();
  return TRUE;
}

function insert_timer($countd, $caption = "", $timeouttext = "", $hide = FALSE) {
  global $disable_timer;

  if ($disable_timer === TRUE) {
    return TRUE;
  }
  if (!$countd || !is_numeric($countd)) {
    return FALSE;
  }

  $timerid = rand(1000, time());
  ?>
  <center><span id=global<? echo $timerid; ?>><br><span
        style="FONT-FAMILY: Tahoma; FONT-SIZE: 11px;"><?php echo $caption ?></span>&nbsp;&nbsp;<span
        id='timerlabel<? echo $timerid; ?>'
        style="FONT-FAMILY: Tahoma; FONT-SIZE: 11px;"></span></span></center>
  <script language=javascript>
    var count<? echo $timerid; ?> =<?php echo $countd; ?>;
    function timer<? echo $timerid; ?>() {
      if (count<? echo $timerid; ?> > 0) {
        document.getElementById('timerlabel<? echo $timerid; ?>').innerHTML = "Please wait " + count<? echo $timerid; ?> + ' sec...';
        count<? echo $timerid; ?> = count<? echo $timerid; ?> - 1;
        setTimeout("timer<? echo $timerid; ?>()", 1000)
      }
    }
    timer<? echo $timerid; ?>();
  </script>
  <!-- <?php
  flush();
  for ($nnn = 0; $nnn < $countd; $nnn++) {
    echo "$nnn ";
    flush();
    sleep(1);
  }
  ?>
-->
  <?php

  if ($hide === TRUE) {
    ?>
    <script language=javascript>
      document.getElementById('global<? echo $timerid; ?>').style.display = 'none';
    </script>
    <?php
    flush();
    return TRUE;
  }

  if ($timeouttext) {
    ?>
    <script language=javascript>
      document.getElementById('global<? echo $timerid; ?>').innerHTML = '<?php echo $timeouttext; ?>';
    </script>
    <?php
    flush();
    return TRUE;
  }
}

function is_page($lpage) {
  global $lastError;
  if (!$lpage) {
    html_error("Error retriving the link<br>$lastError");
  }
}

function getftpurl($host, $port, $url, $saveToFile = 0) {
  global $nn, $lastError, $PHP_SELF, $AUTH, $IS_FTP, $FtpBytesTotal, $FtpBytesReceived, $FtpTimeStart, $FtpChunkSize, $range;
  global $sleep_time, $sleep_count, $ftp_local_sleep, $title_update, $continuefilesizeFTP;
//$urlencode= urlencode($url);
//$urlencode = ereg_replace('%5C',"",$urlencode);
//$urlencode= urldecode($urlencode);
//$urlencode = ereg_replace('`','%60',$urlencode);
//$urlencode = ereg_replace("'",'%27',$urlencode);
  $urlencode = stripslashes($url);
  $ftp_file = basename($urlencode);
  $ftp_dir = dirname($url);
  $saveToFile = $saveToFile;

  $ftp = new ftp(FALSE, FALSE);
  if (!$ftp->SetServer(urldecode($host), (int) $port)) {
    $ftp->quit();
    $lastError = "Could not be established the server " . $host . ":" . $port . ".<br>" . "<a href=\"javascript:history.back(-1);\">Go Back</a><br><br>";
    return FALSE;
  }
  else {
    if (!$ftp->connect()) {
      $ftp->quit();
      $lastError = "Could not be been connected with the server " . $host . ":" . $port . ".<br><a href=\"javascript:history.back(-1);\">Go Back</a><br><br>";
      return FALSE;
    }
    else {
      if (!$ftp->login(urldecode($AUTH["ftp"]["login"]), urldecode($AUTH["ftp"]["password"]))) {
        $ftp->quit();
        $lastError = "Incorrect login or the password <b>" . urldecode($AUTH["ftp"]["login"]) . ":" . urldecode($AUTH["ftp"]["password"]) . "</b>.<br><a href=\"javascript:history.back(-1);\">Go Back</a><br><br>";
        return FALSE;
      }
      else {
        $ftp->Passive(TRUE);
        $ftp->chdir($ftp_dir);

        if (!$ftp->is_exists($ftp_file)) {
          $ftp->quit();
          $lastError = "File <b>$ftp_file</b> not found in folder <b>$ftp_dir</b><br><a href=\"javascript:history.back(-1);\">Go Back</a><br><br>";
          return FALSE;
        }

        $fileSize = $FtpBytesTotal = $ftp->filesize($ftp_file);
        if ($fileSize <= 0) {
          $fileSizeONE = $ftp->nlist("-la", $ftp_dir . "/" . $ftp_file);
          $fileSizeTWO = $fileSizeONE[0];
          preg_match_all("'(.+)\s([0-9]+?)\s(.*?)\s([0-9a-z]+)'si", $fileSizeTWO, $fileSizeTHREE);
          $fileSizeFOUR = $fileSizeTHREE[2][0];
          if (is_numeric($fileSizeFOUR)) {
            echo "<div>GOOD filesize</div>";
          }
          else {
            echo "<div>BAD filesize</div>";
            $fileSizeFOUR = '0';
          }
          if ($fileSizeFOUR <= 0) {
            $ftp->quit();
            $lastError = "Incorrect filesize [<b>$fileSizeFOUR</b>].<br><a href=\"javascript:history.back(-1);\">Go Back</a><br><br>";
            return FALSE;
          }
          else {
            $fileSize = $FtpBytesTotal = $fileSizeFOUR;
          }
        }
        /*������� ��� FTP*/
        if ($range == 'continueON') {
          $continuefilename = fixfilename($_GET["FileName"]);
          $continuefile = $_GET["saveto"] . DIRECTORY_SEPARATOR . $continuefilename;
          $continuefilesizeFTP = getfilesize($continuefile);
          $ftp->_exec("REST $continuefilesizeFTP", "Restarting");
        }
        $ftp->SetType(FTP_BINARY);

        $FtpChunkSize = GetChunkSize($fileSize);

        list($saveToFile, $tmp) = explode('?', $saveToFile);

        if (file_exists($saveToFile)) {
          /*��� ������ ��� ������������ �����*/
          if ($range == 'continueON') {
            $saveToFile = dirname($saveToFile) . DIRECTORY_SEPARATOR . basename($saveToFile);
          }
          else {
            $saveToFile = dirname($saveToFile) . DIRECTORY_SEPARATOR . time() . "_" . basename($saveToFile);
          }
        }
        echo "Downloaded File <b>" . basename($saveToFile) . "</b>, Size <b>" . bytesToKbOrMb($fileSize) . "</b>...<br>";
        ?>
        <br>
        <table cellspacing=0 cellpadding=0
               style="FONT-FAMILY: Tahoma; FONT-SIZE: 11px;">
          <tr>
            <td width=100>&nbsp;</td>
            <td width=300 nowrap>
              <div style='border:#BBBBBB 1px solid; width:300px; height:10px;'>
                <div id=progress
                     style='background-color:#000099; margin:1px; width:0%; height:8px;'></div>
              </div>
            </td>
            <td width=100>&nbsp;</td>
          <tr>
          <tr>
            <td align=right id=received width=100 nowrap>0 KB</td>
            <td align=center id=percent width=300>0%</td>
            <td align=left id=speed width=100 nowrap>0 KB/s</td>
          </tr>
        </table>
        <script>
          function pr(percent, received, speed, out) {
            document.getElementById("received").innerHTML = '<b>' + received + '</b>';
            <?php
							if ($title_update)
								{
?>
            document.title = 'Downloading ' + percent + '% [' + orlink + ']';
            <?php
								}
?>
            if (out) {
              document.getElementById("percent").innerHTML = '<b>' + percent + '%, ' + out + '</b>';
            }
            else {
              document.getElementById("percent").innerHTML = '<b>' + percent + '%</b>';
            }
            if (percent > 90) {
              percent = percent - 1;
            }
            document.getElementById("progress").style.width = percent + '%';
            document.getElementById("speed").innerHTML = '<b>' + speed + ' KB/s</b>';
            return true;
          }

          function minfo(str, field) {
            document.getElementById("mailPart." + field).innerHTML = str;
            return true;
          }
        </script>
        <br>
        <?php
        $FtpTimeStart = getmicrotime();

        if (($sleep_count !== FALSE) && ($sleep_time !== FALSE) && is_numeric($sleep_time) && is_numeric($sleep_count) && ($sleep_count > 0) && ($sleep_time > 0)) {
          $ftp_local_sleep = $sleep_count;
        }

        /*��� ������� range, ��� �� �� ��������� ������, ��� �������� ����, ��� ������ � ����*/
        if ($ftp->get($ftp_file, $saveToFile, $range)) {
          return array(
            "time" => sec2time(round($time)),
            "speed" => round($FtpBytesTotal / 1024 / (getmicrotime() - $FtpTimeStart), 2),
            "received" => TRUE,
            "size" => bytesToKbOrMb($fileSize),
            "bytesReceived" => $FtpBytesReceived,
            "bytesTotal" => $FtpBytesTotal,
            "file" => $saveToFile
          );
        }
        else {
          return FALSE;
        }
        $ftp->quit();
      }
    }
  }
}

function geturl($host, $port, $url, $referer = 0, $cookie = 0, $post = 0, $saveToFile = 0, $proxy = 0, $pauth = 0, $scheme = "http") {
  global $nn, $lastError, $PHP_SELF, $AUTH, $HTTPcode, $progressbar_via_time, $agent, $sleep_time, $sleep_count, $max_speed, $title_update, $range;
  global $is_header;

  if (($post !== 0) && ((strtolower($scheme) == "http") || (strtolower($scheme) == "https"))) {
    $method = "POST";
    $postdata = formpostdata($post);
    $length = strlen($postdata);
    $content_tl = "Content-Type: application/x-www-form-urlencoded" . $nn . "Content-Length: " . $length . $nn;
  }
  else {
    $method = "GET";
    $postdata = "";
    $content_tl = "";
  }

  if ($cookie) {
    if (is_array($cookie)) {
      for ($h = 0; $h < count($cookie); $h++) {
        $cookies .= "Cookie: " . trim($cookie[$h]) . $nn;
      }
    }
    else {
      $cookies = "Cookie: " . trim($cookie) . $nn;
    }
  }

  $referer = $referer ? "Referer: " . $referer . $nn : "";

  if ($proxy) {
    list($proxyHost, $proxyPort) = explode(":", $proxy);
    $url = strtolower($scheme) . "://" . $host . ":" . $port . $url;
  }

  if ($range == 'continueON') {
    $continuefilename = fixfilename($_GET["FileName"]);
    $continuefile = $_GET["saveto"] . DIRECTORY_SEPARATOR . $continuefilename;
    $continuefilesize = getfilesize($continuefile);
    $RangeGO = "Range: bytes=" . $continuefilesize . "-" . $nn . "";
    /*После тире можно указать до какого байта качать*/
  }
  else {
    $RangeGO = '';
  }

  $auth = ($AUTH["use"] === TRUE) ? "Authorization: Basic " . $AUTH["str"] . $nn : "";
  $proxyauth = ($pauth) ? "Proxy-Authorization: Basic $pauth" . $nn : "";

  $zapros = $method . " " . str_replace(" ", "%20", $url) . " HTTP/1.1" . $nn . "Accept: image/gif, image/x-xbitmap, image/jpeg, image/pjpeg, application/x-ms-application, application/vnd.ms-xpsdocument, application/xaml+xml, application/x-ms-xbap, application/x-icq, application/msword, application/x-shockwave-flash, application/vnd.ms-excel, */*" . $nn . "Accept-Language: en-us;q=0.7,en;q=0.3" . $nn . "Accept-Charset: windows-1251,utf-8;q=0.7,*;q=0.7" . $nn . /*"Content-Type: application/x-www-form-urlencoded".$nn.*/
    $RangeGO . "Host: " . $host . $nn . "User-Agent: $agent" . $nn . "Cache-Control: no-cache" . $nn . "Connection: Close" . $nn . $auth . $proxyauth . $referer . $cookies . $content_tl . $nn . $postdata;

  echo $is_header ? "<pre>$zapros</pre>\n" : '';

  $fp = @fsockopen($proxyHost ? $proxyHost : (((strtolower($scheme) == "https") && !$proxy) ? "ssl://" : "") . $host, $proxyPort ? $proxyPort : $port, $errno, $errstr, 15);

  if (!$fp) {
    html_error("Error open socket [" . ($proxyHost ? $proxyHost : $host) . ':' . ($proxyPort ? $proxyPort : $port) . "]<br>$errstr");
  }

  @stream_set_timeout($fp, 300);

  if ($errno || $errstr) {
    $lastError = $errstr;

    return FALSE;
  }

  fputs($fp, $zapros);
  fflush($fp);
  $timeStart = getmicrotime();

  $size_buff = 256;

  while (!feof($fp)) {
    $data = fgets($fp, $size_buff);
    if ($data === FALSE) {
      break;
    }

    if (($sleep_count !== FALSE) && ($sleep_time !== FALSE) && is_numeric($sleep_time) && is_numeric($sleep_count) && ($sleep_count > 0) && ($sleep_time > 0)) {
      $local_sleep--;
      if ($local_sleep == 0) {
        usleep($sleep_time);
        $local_sleep = $sleep_count;
      }
    }

    if ($saveToFile) {
      if ($headersReceived) {
        $bytesSaved = fwrite($fs, $data);
        if ($bytesSaved > -1) {
          $bytesReceived += $bytesSaved;

          if ($bytesTotal && ($bytesReceived > $bytesTotal)) {
            $bytesReceived = $bytesTotal;
            fseek($fs, $bytesTotal);
            ftruncate($fs, $bytesTotal);

            fclose($fp);
            unset($fp);

            break;
          }
        }
        else {
          echo "It is not possible to carry out a record in File" . basename($saveToFile);
          flock($fs, LOCK_UN);
          fclose($fs);
          return FALSE;
        }

        $time = getmicrotime() - $timeStart;
        $chunkTime = $time - $lastChunkTime;
        $chunkTime = $chunkTime ? $chunkTime : 1;
        $lastChunkTime = $time;
        $speed = round(($bytesReceived - $last) / 1024 / $chunkTime, 2);
        $last = $bytesReceived;

        if ((is_numeric($progressbar_via_time) && (time() - $lastsent > $progressbar_via_time)) || (!is_numeric($progressbar_via_time) && ($bytesReceived > $lastReceive + $chunkSize))) {
          $time2 = getmicrotime() - $timeStart;
          $chunkTime2 = $time2 - $lastChunkTime2;
          $chunkTime2 = $chunkTime2 ? $chunkTime2 : 1;
          $mspeed = round(($bytesReceived - $lastReceive) / 1024 / $chunkTime2, 2);
          $mspeed = $mspeed ? $mspeed : 1;
          $lastChunkTime2 = $time2;

          $lastsent = time();
          $lastReceive = $bytesReceived;

          $received = bytesToKbOrMb($bytesReceived);

          if (!$bytesTotal || ($bytesReceived >= $bytesTotal)) {
            $percent = "100%";
            $out = '';
          }
          else {
            $percent = round($bytesReceived / $bytesTotal * 100, 2);

            $out = ($bytesTotal - $bytesReceived) / ($mspeed * 1024);
            $out = sec2time(round($out, 2));
          }

          echo "<script>pr('" . $percent . "', '" . $received . "', '" . $mspeed . "','" . $out . "')</script>\r\n";
          /*usleep(000003);*/
        }

      }
      else {
        $tmp .= $data;
        if (strstr($tmp, "\n\n")) {
          $det = "\n\n";
        }
        elseif (strstr($tmp, $nn . $nn)) {
          $det = $nn . $nn;
        }

        if ($det) {
          $size_buff = 4096;

          list($header, $dopdata) = explode($det, $tmp);
          $headersReceived = TRUE;

          $headers = explode((($det == "\n\n") ? "\n" : $nn), $header);
          list($prototype, $HTTPcode, $httpres) = explode(' ', $headers[0]);

          switch ($HTTPcode) {
            case 200:
              unset($range);
              $range == '200';
              break;
            case 201:
            case 202:
            case 203:
            case 204:
            case 205:
            case 206:
              $contentRange = trim(cut_str($header, "Content-Range: bytes ", "-"));
              if ($contentRange == $continuefilesize) {
                echo "<div>Continued download supported.</div>";
              }
              /*else echo '������� �� ��������������, ���� ������ �������� � ��������� 206. ��� ����� �������:'.$header;*/
              break;
            case 300:
            case 301:
            case 302:
            case 303:
            case 304:
            case 305:
            case 307:
              $redirect = cut_str($header, "Location:", "\n");

              if ($redirect) {
                $lastError = "$HTTPcode Info!! it is redirected to [" . $redirect . "] On the course of events, the reference became obsolete. So start from the beginning...<br><br><a href=\"" . $PHP_SELF . "\" style=\"color: #0000FF;\">Main</a>";
              }
              else {
                $lastError = "$HTTPcode Error!! it is redirected. On the course of events, the reference became obsolete. So start from the beginning...<br><br><a href=\"" . $PHP_SELF . "\" style=\"color: #0000FF;\">Main</a>";
              }
              return $header;
              break;

            case 401:
              $lastError = "401 Error!! This site requires authorization. For the indication of login and password of access it is necessary to use similar url:<br>http://<b>login:password@</b>www.site.com/file.exe";
              return FALSE;
              break;

            case 403:
              $lastError = "403 Error!! Access denied";
              return FALSE;
              break;

            case 407:
              $lastError = "407 Error!! Proxy Authentication Required';";
              return FALSE;
              break;

            case 404:
              $lastError = "404 Error!! Necessary file not discovered on server";
              return FALSE;
              break;

            case 400:
            case 401:
            case 402:
            case 405:
            case 406:
            case 407:
            case 408:
            case 409:
            case 410:
            case 411:
            case 412:
            case 413:
            case 414:
            case 415:
            case 416:
              $crf = cut_str($header, "Content-Range: bytes */", "\n");
              $lastError = "<div>416 Error! Requested Range Not Satisfiable. Not found file or other problem. " . $continuefilesize . " bytes from $crf bytes was loaded.</div>";
              return FALSE;
              break;

            case 417:
            case 422:
            case 423:
            case 424:
            case 426:
            case 500:
            case 501:
            case 502:
            case 503:
            case 504:
            case 505:
            case 506:
            case 507:
            case 510:
              $lastError = "Error!! " . HTTPStatus($HTTPcode);
              return FALSE;
              break;

            default:
              $lastError = "Error!! Unknown HTTP Status: " . pre($header);
              return FALSE;
              break;
          }

          $bytesTotal = trim(cut_str($header, "Content-Length: ", "\n"));
          $bytesTotal = $bytesTotal ? $bytesTotal : 0;

          $fileSize = bytesToKbOrMb($bytesTotal);


          $bname = fixfilename(basename($saveToFile));
          $dname = dirname(fixfilename($saveToFile));

          $ContentDisposition = trim(cut_str($header, 'Content-Disposition:', "\n"));
          if ($bname == "attachment") {
            if ($ContentDisposition && strstr($ContentDisposition, "filename=")) {
              $saveToFile_tmp = trim(cut_str($ContentDisposition . "\n", "filename=", "\n"));
              list($saveToFile_tmp, $tmp) = explode(';', $saveToFile_tmp);
              $saveToFile = $saveToFile_tmp ? $dname . DIRECTORY_SEPARATOR . $saveToFile_tmp : $saveToFile = $dname . DIRECTORY_SEPARATOR . time() . '_by_rapidget';
            }
            else {
              $saveToFile = $dname . DIRECTORY_SEPARATOR . time() . '_by_rapidget';
            }
          }
          else {
            if ($ContentDisposition && strstr($ContentDisposition, "filename=")) {
              $saveToFile_tmp = trim(cut_str($ContentDisposition . "\n", "filename=", "\n"));
              list($saveToFile_tmp, $tmp) = explode(';', $saveToFile_tmp);
              $saveToFile = $saveToFile_tmp ? $dname . DIRECTORY_SEPARATOR . $saveToFile_tmp : $saveToFile;
            }
          }

          $saveToFile = str_replace('"', '', $saveToFile);
          $saveToFile = str_replace("'", '', $saveToFile);

          list($saveToFile, $temp) = explode('?', $saveToFile);
          if ($range == 'continueON') {
            $saveToFile = file_exists($saveToFile) ? dirname($saveToFile) . DIRECTORY_SEPARATOR . $_GET["FileName"] : $saveToFile;
            $saveToFile = fixfilename($saveToFile);
            if ($continuefilesize > 0) {
              if (!is_readable($saveToFile)) {
                echo "Not possible continuation of loading. File is busy.";
                return FALSE;
                die;
              }
              $fs = @fopen($saveToFile, "a");/*�������� �����*/
            }
            else {
              echo "FILE NOT FOUND.";
              $fs = @fopen($saveToFile, "a+");/*�������� �����*/
            }
          }
          else {
            $saveToFile = file_exists($saveToFile) ? dirname($saveToFile) . DIRECTORY_SEPARATOR . time() . "_" . basename($saveToFile) : $saveToFile;
            $saveToFile = fixfilename($saveToFile);
            $fs = @fopen($saveToFile, "w");
          }
          if (!$fs) {
            $lastError = "It is not possible to open the file <b>" . basename($saveToFile) . "</b> in folder <b>" . dirname($saveToFile) . "</b><br>" . "Wrong Installation, that the way for the retention is assigned correctly and in the script" . "File couldn't be stored into this folder (try chmod the folder to 777).<br>" . "<a href=javascript:location.reload(); style=\"color: #0000FF;\">Repeat</a>";
            return FALSE;
          }

          flock($fs, LOCK_EX);

          $bytesSaved = fwrite($fs, $dopdata);

          if ($bytesSaved > -1) {
            $bytesReceived += $bytesSaved;
          }
          else {
            echo "It is not possible to carry out a record in File" . basename($saveToFile) . "<br>";
            flock($fs, LOCK_UN);
            fclose($fs);
            if (file_exists($saveToFile)) {
              unlink($saveToFile);
            }
            return FALSE;
          }

          $chunkSize = GetChunkSize($bytesTotal);
          if ($range == 'continueON') {
            echo "Continuation of loading from " . $continuefilesize . " bytes.<br>";
          }
          echo "File <b>" . basename($saveToFile) . "</b>, size <b>" . $fileSize . "</b>...<br>";
          ?>
          <br>
          <table cellspacing=0 cellpadding=0
                 style="FONT-FAMILY: Tahoma; FONT-SIZE: 11px;">
            <tr>
              <td width=100>&nbsp;</td>
              <td width=300 nowrap>
                <div
                  style='border:#BBBBBB 1px solid; width:300px; height:10px;'>
                  <div id=progress
                       style='background-color:#000099; margin:1px; width:0%; height:8px;'>
                  </div>
                </div>
              </td>
              <td width=100>&nbsp;</td>
            <tr>
              <td align=right id=received width=100 nowrap>0 KB</td>
              <td align=center id=percent width=300>0%</td>
              <td align=left id=speed width=100 nowrap>0 KB/s</td>
            </tr>
          </table>
          <script>
            function pr(percent, received, speed, out) {
              document.getElementById("received").innerHTML = '<b>' + received + '</b>';
              <?php
	if ($title_update)
		{
?>
              document.title = 'Downloading ' + percent + '% [' + orlink + ']';
              <?php
		}
?>
              if (out) {
                document.getElementById("percent").innerHTML = '<b>' + percent + '%, ' + out + '</b>';
              }
              else {
                document.getElementById("percent").innerHTML = '<b>' + percent + '%</b>';
              }
              if (percent > 90) {
                percent = percent - 1;
              }
              document.getElementById("progress").style.width = percent + '%';

              document.getElementById("speed").innerHTML = '<b>' + speed + ' KB/s</b>';
              return true;
            }

            function minfo(str, field) {
              document.getElementById("mailPart." + field).innerHTML = str;
              return true;
            }
          </script>
          <br>
        <?php
        }
      }
    }
    else {
      $page .= $data;
    }
  }

  if ($fp) {
    fclose($fp);
  }

  if ($saveToFile) {
    if ($fs) {
      flock($fs, LOCK_UN);
      fclose($fs);
    }

    if ($bytesReceived <= 0) {
      $lastError = "Wrong Link? Error Downloading File..<br><a href=\"javascript:history.back(-1);\">Go Back</a>";
      if (file_exists($saveToFile)) {
        unlink($saveToFile);
      }
      return FALSE;
    }

    if ($bytesTotal) {
      if ($bytesReceived < $bytesTotal) {
        $lastError = "Received <b>$bytesReceived</b> from <b>$bytesTotal</b><br>Connection Lost :-(<br>Incomplete file is not removed<br><a href=\"javascript:history.back(-1);\">Go Back</a>";
        flock($saveToFile, LOCK_UN);
        echo "<div>If the server supports continuation of download - use it <b>for</b> following <b>loading this file</b> and <b>you</b> can (<b>must</b>) <b>establish</b> (check) <b>redownload flag</b>.</div>";
        /*if (file_exists($saveToFile)) {unlink($saveToFile); }*/
        return FALSE;
      }
    }

    $fileSize = (!$bytesTotal) ? bytesToKbOrMb($bytesReceived) : bytesToKbOrMb($bytesTotal);

    return array(
      "time" => sec2time(round($time)),
      "speed" => round($bytesTotal / 1024 / (getmicrotime() - $timeStart), 2),
      "received" => TRUE,
      "size" => $fileSize,
      "bytesReceived" => $bytesReceived,
      "bytesTotal" => $bytesTotal,
      "file" => $saveToFile
    );
  }
  else {
    return chunked($page);
  }
}

function formpostdata($lpost) {
  $postdata = "";
  if (count($lpost)) {
    foreach ($lpost as $lkey => $lvalue) {
      $postdata .= $postdata ? "&" . $lkey . "=" . $lvalue : $lkey . "=" . $lvalue;
    }
  }
  return $postdata;
}

function cut_str($str, $left, $right) {
  $str = substr(stristr($str, $left), strlen($left));
  $leftLen = strlen(stristr($str, $right));
  $leftLen = $leftLen ? -($leftLen) : strlen($str);
  $str = substr($str, 0, $leftLen);
  return $str;
}

function write_file($file_name, $data, $trunk = 1) {
  if ($trunk == 1) {
    $mode = "w";
  }
  elseif ($trunk == 0) {
    $mode = "a";
  }
  $fp = fopen($file_name, $mode);
  if (!$fp) {
    return FALSE;
  }
  else {
    if (!flock($fp, LOCK_EX)) {
      return FALSE;
    }
    else {
      if (!fwrite($fp, $data)) {
        return FALSE;
      }
      else {
        if (!flock($fp, LOCK_UN)) {
          return FALSE;
        }
        else {
          if (!fclose($fp)) {
            return FALSE;
          }
        }
      }
    }
  }
  return TRUE;
}

function read_file($file_name, $count = -1) {
  if ($count == -1) {
    $count = getfilesize($file_name);
  }
  $fp = fopen($file_name, "r");
  flock($fp, LOCK_SH);
  $ret = fread($fp, $count);
  flock($fp, LOCK_UN);
  fclose($fp);
  return $ret;
}

function pre($var) {
  echo "<pre>";
  print_r($var);
  echo "</pre>";
}

function getmicrotime() {
  list($usec, $sec) = explode(" ", microtime());
  return ((float) $usec + (float) $sec);
}

function html_error($msg) {
  ?>
  <html>
  <head>
    <meta http-equiv=Content-Type content="text/html; charset=windows-1251">
    <title>Working....</title>
  </head>
  <body style="FONT-FAMILY: Tahoma; FONT-SIZE: 11px;">
  <center>
    <?php echo "<big><font color=red>$msg</font></big>"; ?><br>
    <a href="javascript:history.back(-1);">Go Back</a>
  </center>
  </body>
  </html>
  <?php
  die;
}

function sec2time($time) {
  $hour = round($time / 3600, 2);
  if ($hour >= 1) {
    $hour = floor($hour);
    $time -= $hour * 3600;
  }
  $min = round($time / 60, 2);

  if ($min >= 1) {
    $min = floor($min);
    $time -= $min * 60;
  }

  $sec = round($time, 0);

  $hour = ($hour >= 1) ? $hour . " hr " : "";
  $min = ($min >= 1) ? $min . " min " : "";
  $sec = ($sec >= 1) ? $sec . " sec " : "";
  return trim($hour . $min . $sec);
}

function bytesToKbOrMb($bytes) {
  $size = ($bytes >= (1024 * 1024 * 1024 * 1024)) ? round($bytes / (1024 * 1024 * 1024 * 1024), 2) . " TB" : (($bytes >= (1024 * 1024 * 1024)) ? round($bytes / (1024 * 1024 * 1024), 2) . " GB" : (($bytes >= (1024 * 1024)) ? round($bytes / (1024 * 1024), 2) . " MB" : round($bytes / 1024, 2) . " KB"));
  return $size;
}

function checkmail($mail) {
  if (strlen($mail) == 0) {
    return FALSE;
  }
  if (!preg_match("/^[a-z0-9_\.-]{1,20}@(([a-z0-9-]+\.)+(com|net|org|mil|" . "edu|gov|arpa|info|biz|inc|name|[a-z]{2})|[0-9]{1,3}\.[0-9]{1,3}\.[0-" . "9]{1,3}\.[0-9]{1,3})$/is", $mail)) {
    return FALSE;
  }
  return TRUE;
}

if (file_exists("smtp.php")) {
  include "smtp.php";
}
$smtp_mail = $smtp_mail && function_exists('smtpmail');

include "xmail.php";

function addAdditionalHeaders($head) {
  global $un;
  if ($head["msg"]) {
    $ret = "------------" . $un . "\nContent-Type: text/plain; charset=Windows-1251\n" . "Content-Transfer-Encoding: 8bit\n\n" . $head["msg"] . "\n\n";
  }
  if ($head["file"]["filename"]) {
    $ret .= "------------" . $un . "\n" . "Content-Type: application/octet-stream; name=\"" . basename($head["file"]["filename"]) . "\"\n" . "Content-Transfer-Encoding: base64\n" . "Content-Disposition: attachment; filename=\"" . basename($head["file"]["filename"]) . "\"\n\n" . $head["file"]["stream"] . "\n\n";
  }
  return $ret;
}

function updateListInFile($list) {
  global $_COOKIE, $show_all, $workpath;

  $files_lst = $workpath . DIRECTORY_SEPARATOR . "files.lst";

  if (($_COOKIE["showAll"] == 1) && ($show_all === TRUE)) {
    return TRUE;
  }
  elseif (count($list) > 0) {
    foreach ($list as $key => $value) {
      $list[$key] = serialize($value);
    }
    return (!@write_file($files_lst, implode("\r\n", $list) . "\r\n") & count($list) > 0) ? FALSE : TRUE;
  }
  else {
    if (file_exists($files_lst)) {
      $fo = fopen($files_lst, 'w');
      return $fo ? fclose($fo) : FALSE;
    }
    else {
      return TRUE;
    }
  }
}

$lastsent = 0;

function updateFtpProgress($bytesReceived) {
  global $FtpBytesTotal, $FtpBytesReceived, $FtpTimeStart, $FtpLastChunkTime, $FtpChunkSize, $FtpLast, $FtpUpload, $FtpUploadBytesSent, $lastsent;
  global $progressbar_via_time, $sleep_time, $sleep_count, $ftp_local_count;
  if ($FtpUpload) {
    $FtpUploadBytesSent += $bytesReceived;
    $bytesReceived = $FtpUploadBytesSent;
  }
  $FtpBytesReceived = $bytesReceived;

  if (($sleep_count !== FALSE) && ($sleep_time !== FALSE) && is_numeric($sleep_time) && is_numeric($sleep_count) && ($sleep_count > 0) && ($sleep_time > 0)) {
    $ftp_local_sleep--;
    if ($ftp_local_sleep == 0) {
      usleep($sleep_time);
      $ftp_local_sleep = $sleep_count;
    }
  }

  if ((is_numeric($progressbar_via_time) && (time() - $lastsent > $progressbar_via_time)) || (!is_numeric($progressbar_via_time) && ($bytesReceived > $FtpLast + $FtpChunkSize))) {
    $lastsent = time();
    $time = getmicrotime() - $FtpTimeStart;
    $chunkTime = $time - $FtpLastChunkTime;
    $chunkTime = $chunkTime ? $chunkTime : 1;
    $FtpLastChunkTime = $time;
    $speed = round(($bytesReceived - $FtpLast) / 1024 / $chunkTime, 2);
    $speed = $speed ? $speed : 1;
    $FtpBytesReceived = $bytesReceived;
    if ($bytesReceived > $FtpBytesTotal) {
      $percent = 100;
      $out = '';
    }
    else {
      $percent = round($bytesReceived / $FtpBytesTotal * 100, 2);

      $out = ($FtpBytesTotal - $bytesReceived) / ($speed * 1024);
      $out = sec2time(round($out, 2));
    }

    $FtpLast = $bytesReceived;

    echo "<script>pr(" . $percent . ", '" . bytesToKbOrMb($bytesReceived) . "', " . $speed . ",'" . $out . "')</script>\r\n";
  }
}

/*if(!defined('CRLF')) define('CRLF',"\r\n");
if(!defined("FTP_AUTOASCII")) define("FTP_AUTOASCII", -1);
if(!defined("FTP_BINARY")) define("FTP_BINARY", 1);
if(!defined("FTP_ASCII")) define("FTP_ASCII", 0);
if(!defined('FTP_FORCE')) define('FTP_FORCE', TRUE);
define('FTP_OS_Unix','u');
define('FTP_OS_Windows','w');
define('FTP_OS_Mac','m');  */

/*Коды откликов
110 Комментарий
120 Функция будет реализована через nnn минут
125 Канал открыт, обмен данными начат
150 Статус файла правилен, подготавливается открытие канала
200 Команда корректна
211 Системный статус или отклик на справочный запрос
212 Состояние каталога
213 Состояние файла
214 Справочное поясняющее сообщение
220 Слишком много подключений к FTP-серверу (можете попробовать позднее). В некоторых версиях указывает на успешное завершение промежуточной процедуры
221 Благополучное завершение по команде quit
225 Канал сформирован, но информационный обмен отсутствует
226 Закрытие канала, обмен завершен успешно
230 Пользователь идентифицирован, продолжайте
250 Запрос прошел успешно
331 Имя пользователя корректно, нужен пароль
332 Для входа в систему необходима аутентификация
421 Процедура не возможна, канал закрывается
425 Открытие информационного канала не возможно
426 Канал закрыт, обмен прерван
450 Запрошенная функция не реализована, файл не доступен, например, занят
451 Локальная ошибка, операция прервана
452 Ошибка при записи файла (не достаточно места)
500 Синтаксическая ошибка, команда не может быть интерпретирована (возможно она слишком длинна)
501 Синтаксическая ошибка (неверный параметр или аргумент)
502 Команда не используется (нелегальный тип MODE)
503 Неудачная последовательность команд
504 Команда не применима для такого параметра
530 Система не загружена (not logged in)
532 Необходима аутентификация для запоминания файла
550 Запрошенная функция не реализована, файл не доступен, например, не найден
552 Запрошенная операция прервана, недостаточно выделено памяти
*/

class ftp_base {
  /* Public variables */
  var $LocalEcho = FALSE;
  var $Verbose = FALSE;
  var $OS_local;

  /* Private variables */
  var $_lastaction = NULL;
  var $_errors;
  var $_type;
  var $_umask;
  var $_timeout;
  var $_passive;
  var $_host;
  var $_fullhost;
  var $_port;
  var $_datahost;
  var $_dataport;
  var $_ftp_control_sock;
  var $_ftp_data_sock;
  var $_ftp_temp_sock;
  var $_login;
  var $_password;
  var $_connected;
  var $_ready;
  var $_code;
  var $_message;
  var $_can_restore;
  var $_port_available;

  var $_error_array = array();
  var $AuthorizedTransferMode = array(
    FTP_AUTOASCII,
    FTP_ASCII,
    FTP_BINARY
  );
  var $OS_FullName = array(
    FTP_OS_Unix => 'UNIX',
    FTP_OS_Windows => 'WINDOWS',
    FTP_OS_Mac => 'MACOS'
  );
  var $NewLineCode = array(
    FTP_OS_Unix => "\n",
    FTP_OS_Mac => "\r",
    FTP_OS_Windows => "\r\n"
  );
  var $AutoAsciiExt = array(
    "ASP",
    "BAT",
    "C",
    "CPP",
    "CSV",
    "H",
    "HTM",
    "HTML",
    "SHTML",
    "INI",
    "LOG",
    "PHP",
    "PHP3",
    "PL",
    "PERL",
    "SH",
    "SQL",
    "TXT"
  );

  /* Constructor */
  function ftp_base($port_mode = FALSE) {
    $this->_port_available = ($port_mode == TRUE);
    $this->SendMSG("Staring FTP client class with" . ($this->_port_available ? "" : "out") . " PORT mode support");
    $this->_connected = FALSE;
    $this->_ready = FALSE;
    $this->_can_restore = FALSE;
    $this->_code = 0;
    $this->_message = "";
    $this->SetUmask(0022);
    $this->SetType(FTP_AUTOASCII);
    $this->SetTimeout(30);
    $this->Passive(!$this->_port_available);
    $this->_login = "anonymous";
    $this->_password = "anon@ftp.com";
    $this->OS_local = FTP_OS_Unix;
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
      $this->OS_local = FTP_OS_Windows;
    }
    elseif (strtoupper(substr(PHP_OS, 0, 3)) === 'MAC') {
      $this->OS_local = FTP_OS_Mac;
    }
  }

// <!-- --------------------------------------------------------------------------------------- -->
// <!--       Public functions                                                                  -->
// <!-- --------------------------------------------------------------------------------------- -->
  function parselisting($list) {
//Parses i line like:"drwxrwx---  2 owner group 4096 Apr 23 14:57 text"
    if (preg_match("/^([-ld])([rwxst-]+)\s+(\d+)\s+([-_\w]+)\s+([-_\w]+)\s+(\d+)\s+(\w{3})\s+(\d+)\s+([\:\d]+)\s+(.+)$/i", $list, $ret)) {
      $v = array(
        "type" => ($ret[1] == "-" ? "f" : $ret[1]),
        "perms" => 0,
        "inode" => $ret[3],
        "owner" => $ret[4],
        "group" => $ret[5],
        "size" => $ret[6],
        "date" => $ret[7] . " " . $ret[8] . " " . $ret[9],
        "name" => $ret[10]
      );
      $v["perms"] += 00400 * (int) ($ret[2]{0} == "r");
      $v["perms"] += 00200 * (int) ($ret[2]{1} == "w");
      $v["perms"] += 00100 * (int) in_array($ret[2]{2}, array("x", "s"));
      $v["perms"] += 00040 * (int) ($ret[2]{3} == "r");
      $v["perms"] += 00020 * (int) ($ret[2]{4} == "w");
      $v["perms"] += 00010 * (int) in_array($ret[2]{5}, array("x", "s"));
      $v["perms"] += 00004 * (int) ($ret[2]{6} == "r");
      $v["perms"] += 00002 * (int) ($ret[2]{7} == "w");
      $v["perms"] += 00001 * (int) in_array($ret[2]{8}, array("x", "t"));
      $v["perms"] += 04000 * (int) in_array($ret[2]{2}, array("S", "s"));
      $v["perms"] += 02000 * (int) in_array($ret[2]{5}, array("S", "s"));
      $v["perms"] += 01000 * (int) in_array($ret[2]{8}, array("T", "t"));
    }
    return $v;
  }

  function SendMSG($message = "", $crlf = TRUE) {
    if ($this->Verbose) {
      echo $message . ($crlf ? CRLF : "");
      flush();
    }
    return TRUE;
  }

  function SetType($mode = FTP_AUTOASCII) {
    if (!in_array($mode, $this->AuthorizedTransferMode)) {
      $this->SendMSG("Wrong type");
      return FALSE;
    }
    $this->_type = $mode;
    $this->_data_prepare($mode);
    $this->SendMSG("Transfer type: " . ($this->_type == FTP_BINARY ? "binary" : ($this->_type == FTP_ASCII ? "ASCII" : "auto ASCII")));
    return TRUE;
  }

  function Passive($pasv = NULL) {
    if (is_null($pasv)) {
      $this->_passive = !$this->_passive;
    }
    else {
      $this->_passive = $pasv;
    }
    if (!$this->_port_available and !$this->_passive) {
      $this->SendMSG("Only passive connections available!");
      $this->_passive = TRUE;
      return FALSE;
    }
    $this->SendMSG("Passive mode " . ($this->_passive ? "on" : "off"));
    return TRUE;
  }

  function SetServer($host, $port = 21, $reconnect = TRUE) {
    if (!is_long($port)) {
      $this->verbose = TRUE;
      $this->SendMSG("Incorrect port syntax");
      return FALSE;
    }
    else {
      $ip = @gethostbyname($host);
      $dns = @gethostbyaddr($host);
      if (!$ip) {
        $ip = $host;
      }
      if (!$dns) {
        $dns = $host;
      }
      if (ip2long($ip) === -1) {
        $this->SendMSG("Wrong host name/address \"" . $host . "\"");
        return FALSE;
      }
      $this->_host = $ip;
      $this->_fullhost = $dns;
      $this->_port = $port;
      $this->_dataport = $port - 1;
    }
    $this->SendMSG("Host \"" . $this->_fullhost . "(" . $this->_host . "):" . $this->_port . "\"");
    if ($reconnect) {
      if ($this->_connected) {
        $this->SendMSG("Reconnecting");
        if (!$this->quit(FTP_FORCE)) {
          return FALSE;
        }
        if (!$this->connect()) {
          return FALSE;
        }
      }
    }
    return TRUE;
  }

  function SetUmask($umask = 0022) {
    $this->_umask = $umask;
    umask($this->_umask);
    $this->SendMSG("UMASK 0" . decoct($this->_umask));
    return TRUE;
  }

  function SetTimeout($timeout = 30) {
    $this->_timeout = $timeout;
    $this->SendMSG("Timeout " . $this->_timeout);
    if ($this->_connected) {
      if (!$this->_settimeout($this->_ftp_control_sock)) {
        return FALSE;
      }
    }
    return TRUE;
  }

  function connect() {
    $this->SendMsg('Local OS : ' . $this->OS_FullName[$this->OS_local]);
    if (!($this->_ftp_control_sock = $this->_connect($this->_host, $this->_port))) {
      $this->SendMSG("Error : Cannot connect to remote host \"" . $this->_fullhost . " :" . $this->_port . "\"");
      return FALSE;
    }
    $this->SendMSG("Connected to remote host \"" . $this->_fullhost . ":" . $this->_port . "\". Waiting for greeting.");
    do {
      if (!$this->_readmsg()) {
        return FALSE;
      }
      if (!$this->_checkCode()) {
        return FALSE;
      }
      $this->_lastaction = time();
    } while ($this->_code < 200);
    $this->_ready = TRUE;
    return TRUE;
  }

  function quit($force = FALSE) {
    if ($this->_ready) {
      if (!$this->_exec("QUIT") and !$force) {
        return FALSE;
      }
      if (!$this->_checkCode() and !$force) {
        return FALSE;
      }
      $this->_ready = FALSE;
      $this->SendMSG("Session finished");
    }
    $this->_quit();
    return TRUE;
  }

  function login($user = NULL, $pass = NULL) {
    if (!is_null($user)) {
      $this->_login = $user;
    }
    else {
      $this->_login = "anonymous";
    }
    if (!is_null($pass)) {
      $this->_password = $pass;
    }
    else {
      $this->_password = "anon@anon.com";
    }
    if (!$this->_exec("USER " . $this->_login, "login")) {
      return FALSE;
    }
    if (!$this->_checkCode()) {
      return FALSE;
    }
    if ($this->_code != 230) {
      if (!$this->_exec((($this->_code == 331) ? "PASS " : "ACCT ") . $this->_password, "login")) {
        return FALSE;
      }
      if (!$this->_checkCode()) {
        return FALSE;
      }
    }
    $this->SendMSG("Authentication succeeded");
    $this->_can_restore = $this->restore(100);
    $this->SendMSG("This server can" . ($this->_can_restore ? "" : "'t") . " resume broken uploads/downloads");
    return TRUE;
  }

  function pwd() {
    if (!$this->_exec("PWD", "pwd")) {
      return FALSE;
    }
    if (!$this->_checkCode()) {
      return FALSE;
    }
    return ereg_replace("^[0-9]{3} \"(.+)\" .+" . CRLF, "\\1", $this->_message);
  }

  function cdup() {
    if (!$this->_exec("CDUP", "cdup")) {
      return FALSE;
    }
    if (!$this->_checkCode()) {
      return FALSE;
    }
    return TRUE;
  }

  function chdir($pathname) {
    if (!$this->_exec("CWD " . $pathname, "chdir")) {
      return FALSE;
    }
    if (!$this->_checkCode()) {
      return FALSE;
    }
    return TRUE;
  }

  function rmdir($pathname) {
    if (!$this->_exec("RMD " . $pathname, "rmdir")) {
      return FALSE;
    }
    if (!$this->_checkCode()) {
      return FALSE;
    }
    return TRUE;
  }

  function mkdir($pathname) {
    if (!$this->_exec("MKD " . $pathname, "mkdir")) {
      return FALSE;
    }
    if (!$this->_checkCode()) {
      return FALSE;
    }
    return TRUE;
  }

  function rename($from, $to) {
    if (!$this->_exec("RNFR " . $from, "rename")) {
      return FALSE;
    }
    if (!$this->_checkCode()) {
      return FALSE;
    }
    if ($this->_code == 350) {
      if (!$this->_exec("RNTO " . $to, "rename")) {
        return FALSE;
      }
      if (!$this->_checkCode()) {
        return FALSE;
      }
    }
    else {
      return FALSE;
    }
    return TRUE;
  }

  function filesize($pathname) {
    if (!$this->_exec("SIZE " . $pathname, "filesize")) {
      return FALSE;
    }
    if (!$this->_checkCode()) {
      return FALSE;
    }
    return ereg_replace("^[0-9]{3} ([0-9]+)" . CRLF, "\\1", $this->_message);
  }

  function mdtm($pathname) {
    if (!$this->_exec("MDTM " . $pathname, "mdtm")) {
      return FALSE;
    }
    if (!$this->_checkCode()) {
      return FALSE;
    }
    $mdtm = ereg_replace("^[0-9]{3} ([0-9]+)" . CRLF, "\\1", $this->_message);
    $date = sscanf($mdtm, "%4d%2d%2d%2d%2d%2d");
    $timestamp = mktime($date[3], $date[4], $date[5], $date[1], $date[2], $date[0]);
    return $timestamp;
  }

  function systype() {
    if (!$this->_exec("SYST", "systype")) {
      return FALSE;
    }
    if (!$this->_checkCode()) {
      return FALSE;
    }
    $DATA = explode(" ", $this->_message);
    return $DATA[1];
  }

  function delete($pathname) {
    if (!$this->_exec("DELE " . $pathname, "delete")) {
      return FALSE;
    }
    if (!$this->_checkCode()) {
      return FALSE;
    }
    return TRUE;
  }

  function site($command, $fnction = "site") {
    if (!$this->_exec("SITE " . $command, $fnction)) {
      return FALSE;
    }
    if (!$this->_checkCode()) {
      return FALSE;
    }
    return TRUE;
  }

  function chmod($pathname, $mode) {
    if (!$this->site("CHMOD " . decoct($mode) . " " . $pathname, "chmod")) {
      return FALSE;
    }
    return TRUE;
  }

  /*���������� ���������*/
  function restore($from) {
    $restore = $continuefilesizeFTP;
    if (!$this->_exec("REST " . $from, "Restart")) {
      return FALSE;
    }
    if (!$this->_checkCode()) {
      return FALSE;
    }
    return TRUE;
  }

  function retrrestore($from) {
    if (!$this->_exec("RETR " . $from, "get")) {
      return FALSE;
    }
    if (!$this->_checkCode()) {
      return FALSE;
    }
    return TRUE;
  }

  function features() {
    if (!$this->_exec("FEAT", "features")) {
      return FALSE;
    }
    if (!$this->_checkCode()) {
      return FALSE;
    }
    return preg_split("/[" . CRLF . "]+/", ereg_replace("[0-9]{3}[ -][^" . CRLF . "]*" . CRLF, "", $this->_message), -1, PREG_SPLIT_NO_EMPTY);
  }

  function rawlist($arg = "", $pathname = "") {
    return $this->_list(($arg ? " " . $arg : "") . ($pathname ? " " . $pathname : ""), "LIST", "rawlist");
  }

  function nlist($arg = "", $pathname = "") {
    return $this->_list(($arg ? " " . $arg : "") . ($pathname ? " " . $pathname : ""), "NLST", "nlist");
  }

  function is_exists($pathname) {
    if (!($remote_list = $this->nlist("-a", dirname($pathname)))) {
      $this->SendMSG("Error : Cannot get remote file list");
      return -1;
    }
    reset($remote_list);
    while (list(, $value) = each($remote_list)) {
      if ($value == basename($pathname)) {
        $this->SendMSG("Remote file " . $pathname . " exists");
        return TRUE;
      }
    }
    $this->SendMSG("Remote file " . $pathname . " does not exist");
    return FALSE;
  }

  /*������� ��� �������*/
  function get($remotefile, $localfile = NULL, $range) {
    if (is_null($localfile)) {
      $localfile = $remotefile;
    }
    $localfile = fixfilename($localfile);
    if (@file_exists($localfile)) {
      $this->SendMSG("Warning : local file will be overwritten");
    }
    if ($range == 'continueON') {
      echo "�������, �������� A+";
      $fp = @fopen($localfile, "a+");
    }
    else {
      echo "�� �������, �������� W";
      $fp = @fopen($localfile, "w");
    }
    if (!$fp) {
      $this->PushError("get", "can't open local file", "Cannot create \"" . $localfile . "\"");
      return FALSE;
    }
    $pi = pathinfo($remotefile);
    $mode = FTP_BINARY;
    if (!$this->_data_prepare($mode)) {
      fclose($fp);
      return FALSE;
    }
    /*������� ��� �������*/
    if ($this->_can_restore) {
      if (!$this->_exec("RETR " . $remotefile, "get")) {
        $this->_data_close();
        fclose($fp);
        return FALSE;
      }
    }
    if (!$this->_checkCode()) {
      $this->_data_close();
      fclose($fp);
      return FALSE;
    }
    $out = $this->_data_read($mode, $fp);
    fclose($fp);
    $this->_data_close();
    if (!$this->_readmsg()) {
      return FALSE;
    }
    if (!$this->_checkCode()) {
      return FALSE;
    }
    return $out;
  }

  function get2($remotefile, $from) {
    $mode = FTP_BINARY;
    if (!$this->_data_prepare($mode)) {
      return FALSE;
    }
    /*������� ��� �������*/
    if ($this->_can_restore) {
      if (!$this->_exec("RETR " . $remotefile, "get")) {
        $this->_data_close();
        return FALSE;
      }
    }
    if (!$this->_checkCode()) {
      $this->_data_close();
      return FALSE;
    }
    $out = $this->_data_read2();
    $this->_data_close();
    if (!$this->_readmsg()) {
      return FALSE;
    }
    if (!$this->_checkCode()) {
      return FALSE;
    }
    return $out;
  }

  function put($localfile, $remotefile = NULL) {
    if (is_null($remotefile)) {
      $remotefile = $localfile;
    }
    if (!@file_exists($localfile)) {
      $this->PushError("put", "can't open local file", "No such file or directory \"" . $localfile . "\"");
      return FALSE;
    }
    $fp = @fopen($localfile, "r");
    if (!$fp) {
      $this->PushError("put", "can't open local file", "Cannot read file \"" . $localfile . "\"");
      return FALSE;
    }
    $pi = pathinfo($localfile);
    $mode = FTP_BINARY;
    if (!$this->_data_prepare($mode)) {
      fclose($fp);
      return FALSE;
    }
    if ($this->_can_restore) {
      $this->restore(0);
    }
    if (!$this->_exec("STOR " . $remotefile, "put")) {
      $this->_data_close();
      fclose($fp);
      return FALSE;
    }
    if (!$this->_checkCode()) {
      $this->_data_close();
      fclose($fp);
      return FALSE;
    }
    $ret = $this->_data_write($mode, $fp);
    fclose($fp);
    $this->_data_close();
    if (!$this->_readmsg()) {
      return FALSE;
    }
    if (!$this->_checkCode()) {
      return FALSE;
    }
    return $ret;
  }

// <!-- --------------------------------------------------------------------------------------- -->
// <!--       Private functions                                                                 -->
// <!-- --------------------------------------------------------------------------------------- -->
  function _checkCode() {
    return ($this->_code < 400 and $this->_code > 0);
  }

  function _list($arg = "", $cmd = "LIST", $fnction = "_list") {
    if (!$this->_data_prepare()) {
      return FALSE;
    }
    if (!$this->_exec($cmd . $arg, $fnction)) {
      $this->_data_close();
      return FALSE;
    }
    if (!$this->_checkCode()) {
      $this->_data_close();
      return FALSE;
    }
    $out = $this->_data_read();
    $this->_data_close();
    if (!$this->_readmsg()) {
      return FALSE;
    }
    if (!$this->_checkCode()) {
      return FALSE;
    }
    if ($out === FALSE) {
      return FALSE;
    }
    $out = preg_split("/[" . CRLF . "]+/", $out, -1, PREG_SPLIT_NO_EMPTY);
    $this->SendMSG(implode($this->NewLineCode[$this->OS_local], $out));
    return $out;
  }

// <!-- --------------------------------------------------------------------------------------- -->
// <!-- Partie : gestion des erreurs                                                            -->
// <!-- --------------------------------------------------------------------------------------- -->
// Genere une erreur pour traitement externe a la classe
  function PushError($fctname, $msg, $desc = FALSE) {
    $error = array();
    $error['time'] = time();
    $error['fctname'] = $fctname;
    $error['msg'] = $msg;
    $error['desc'] = $desc;
    if ($desc) {
      $tmp = ' (' . $desc . ')';
    }
    else {
      $tmp = '';
    }
    $this->SendMSG($fctname . ': ' . $msg . $tmp);
    return (array_push($this->_error_array, $error));
  }

// Recupere une erreur externe
  function PopError() {
    if (count($this->_error_array)) {
      return (array_pop($this->_error_array));
    }
    else {
      return (FALSE);
    }
  }
}

$mod_sockets = TRUE;
if (!extension_loaded('sockets')) {
  $prefix = (PHP_SHLIB_SUFFIX == 'dll') ? 'php_' : '';
  if (!@dl($prefix . 'sockets.' . PHP_SHLIB_SUFFIX)) {
    $mod_sockets = FALSE;
  }
}

$mod_sockets = TRUE;
if (!extension_loaded('sockets')) {
  $prefix = (PHP_SHLIB_SUFFIX == 'dll') ? 'php_' : '';
  if (!@dl($prefix . 'sockets.' . PHP_SHLIB_SUFFIX)) {
    $mod_sockets = FALSE;
  }
}

class ftp extends ftp_base {
  function ftp($verb = FALSE, $le = FALSE) {
    $this->LocalEcho = $le;
    $this->Verbose = $verb;
    $this->ftp_base();
  }

// <!-- --------------------------------------------------------------------------------------- -->
// <!--       Private functions                                                                 -->
// <!-- --------------------------------------------------------------------------------------- -->

  function _settimeout($sock) {
    echo "===";
    /*if(!@stream_set_timeout($sock, $this->_timeout)) {
$this->PushError('_settimeout','socket set send timeout');
$this->_quit();
return FALSE;
}
*/
    return TRUE;
  }

  function _connect($host, $port) {
    $this->SendMSG("Creating socket");
    $sock = @fsockopen($host, $port, $errno, $errstr, $this->_timeout);
    if (!$sock) {
      $this->PushError('_connect', 'socket connect failed', $errstr . " (" . $errno . ")");
      return FALSE;
    }
    $this->_connected = TRUE;
    return $sock;
  }

  function _readmsg($fnction = "_readmsg") {
    if (!$this->_connected) {
      $this->PushError($fnction, 'Connect first');
      return FALSE;
    }
    $result = TRUE;
    $this->_message = "";
    $this->_code = 0;
    $go = TRUE;
    do {
      $tmp = @fgets($this->_ftp_control_sock, 2048);
      if ($tmp === FALSE) {
        $go = $result = FALSE;
        $this->PushError($fnction, 'Read failed');
      }
      else {
        $this->_message .= $tmp;
//for($i=0; $i<strlen($this->_message); $i++)
//if(ord($this->_message[$i])<32) echo "#".ord($this->_message[$i]); else echo $this->_message[$i];
//echo CRLF;
        if (preg_match("/^([0-9]{3})(-(.*" . CRLF . ")+\\1)? [^" . CRLF . "]*" . CRLF . "$/", $this->_message, $regs)) {
          $go = FALSE;
        }
      }
    } while ($go);
    if ($this->LocalEcho) {
      echo "GET < " . rtrim($this->_message, CRLF) . CRLF;
    }
    $this->_code = (int) $regs[1];
    return $result;
  }

  function _exec($cmd, $fnction = "_exec") {
    if (!$this->_ready) {
      $this->PushError($fnction, 'Connect first');
      return FALSE;
    }
    if ($this->LocalEcho) {
      echo "PUT > ", $cmd, CRLF;
    }
    $status = @fputs($this->_ftp_control_sock, $cmd . CRLF);
    if ($status === FALSE) {
      $this->PushError($fnction, 'socket write failed');
      return FALSE;
    }
    $this->_lastaction = time();
    if (!$this->_readmsg($fnction)) {
      return FALSE;
    }
    return TRUE;
  }

  function _data_prepare($mode = FTP_ASCII) {
    if ($mode == FTP_BINARY) {
      if (!$this->_exec("TYPE I", "_data_prepare")) {
        return FALSE;
      }
    }
    else {
      if (!$this->_exec("TYPE A", "_data_prepare")) {
        return FALSE;
      }
    }
    if ($this->_passive) {
      if (!$this->_exec("PASV", "pasv")) {
        $this->_data_close();
        return FALSE;
      }
      if (!$this->_checkCode()) {
        $this->_data_close();
        return FALSE;
      }
      $ip_port = explode(",", ereg_replace("^.+ \\(?([0-9]{1,3},[0-9]{1,3},[0-9]{1,3},[0-9]{1,3},[0-9]+,[0-9]+)\\)?.*" . CRLF . "$", "\\1", $this->_message));
      $this->_datahost = $ip_port[0] . "." . $ip_port[1] . "." . $ip_port[2] . "." . $ip_port[3];
      $this->_dataport = (((int) $ip_port[4]) << 8) + ((int) $ip_port[5]);
      $this->SendMSG("Connecting to " . $this->_datahost . ":" . $this->_dataport);
      $this->_ftp_data_sock = @fsockopen($this->_datahost, $this->_dataport, $errno, $errstr, $this->_timeout);
      if (!$this->_ftp_data_sock) {
        $this->PushError("_data_prepare", "fsockopen fails", $errstr . " (" . $errno . ")");
        $this->_data_close();
        return FALSE;
      }
      else {
        $this->_ftp_data_sock;
      }
    }
    else {
      $this->SendMSG("Only passive connections available!");
      return FALSE;
    }
    return TRUE;
  }

  function _data_read($mode = FTP_ASCII, $fp = NULL) {
    $NewLine = $this->NewLineCode[$this->OS_local];
    if (is_resource($fp)) {
      $out = 0;
    }
    else {
      $out = "";
    }
    if (!$this->_passive) {
      $this->SendMSG("Only passive connections available!");
      return FALSE;
    }
    if ($mode != FTP_BINARY) {
      while (!feof($this->_ftp_data_sock)) {
        $tmp = fread($this->_ftp_data_sock, 4096);
        $line .= $tmp;
        if (!preg_match("/" . CRLF . "$/", $line)) {
          continue;
        }
        $line = rtrim($line, CRLF) . $NewLine;
        if (is_resource($fp)) {
          $out += fwrite($fp, $line, strlen($line));
          updateFtpProgress($out);
        }
        else {
          $out .= $line;
        }
        $line = "";
      }
    }
    else {
      while (!feof($this->_ftp_data_sock)) {
        $block = fread($this->_ftp_data_sock, 4096);
        if (is_resource($fp)) {
          $out += fwrite($fp, $block, strlen($block));
          updateFtpProgress($out);
        }
        else {
          $out .= $line;
        }
      }
    }
    return $out;
  }

  function _data_read2() {
    $NewLine = $this->NewLineCode[$this->OS_local];
    $out = 0;
    if (!$this->_passive) {
      $this->SendMSG("Only passive connections available!");
      return FALSE;
    }
    while (!feof($this->_ftp_data_sock)) {
      $block = fread($this->_ftp_data_sock, 4096);
      $out += strlen($block);
      echo $block;
    }
    return $out;
  }

  function _data_write($mode = FTP_ASCII, $fp = NULL) {
    $NewLine = $this->NewLineCode[$this->OS_local];
    if (is_resource($fp)) {
      $out = 0;
    }
    else {
      $out = "";
    }
    if (!$this->_passive) {
      $this->SendMSG("Only passive connections available!");
      return FALSE;
    }
    if (is_resource($fp)) {
      while (!feof($fp)) {
        $line = fgets($fp, 4096);
        if ($mode != FTP_BINARY) {
          $line = rtrim($line, CRLF) . CRLF;
        }
        do {
          if (($res = @fwrite($this->_ftp_data_sock, $line)) === FALSE) {
            $this->PushError("_data_write", "Can't write to socket");
            return FALSE;
          }
          else {
            updateFtpProgress(strlen($line));
          }
          $line = substr($line, $res);
        } while ($line != "");
      }
    }
    else {
      if ($mode != FTP_BINARY) {
        $fp = rtrim($fp, $NewLine) . CRLF;
      }
      do {
        if (($res = @fwrite($this->_ftp_data_sock, $fp)) === FALSE) {
          $this->PushError("_data_write", "Can't write to socket");
          return FALSE;
        }
        else {
          updateFtpProgress(strlen($fp));
        }
        $fp = substr($fp, $res);
      } while ($fp != "");
    }
    return TRUE;
  }

  function _data_close() {
    @fclose($this->_ftp_data_sock);
    $this->SendMSG("Disconnected data from remote host");
    return TRUE;
  }


  function _quit($force = FALSE) {
    if ($this->_connected or $force) {
      @fclose($this->_ftp_control_sock);
      $this->_connected = FALSE;
      $this->SendMSG("Socket closed");
    }
  }
}

function _cmp_list_enums($a, $b) {
  return strcmp($a["name"], $b["name"]);
}

function delete_xakep($workfolder) {
  $workfolder = str_replace("..", "", $workfolder);
  $workfolder = str_replace("^", "", $workfolder);
  $workfolder = str_replace("<", "", $workfolder);
  $workfolder = str_replace(">", "", $workfolder);
  $workfolder = str_replace("//", "", $workfolder);
  $workfolder = str_replace("&", "", $workfolder);
  $workfolder = str_replace("\r", "", $workfolder);
  $workfolder = str_replace("\n", "", $workfolder);
  return $workfolder;
}

function _create_list($recreate = FALSE, $dir = FALSE) {
  global $list, $_COOKIE, $systemfile, $show_all, $workpath, $workfolder, $auth_user, $admin_logins, $loginpathoriginal, $workpathoriginal;
  //$systemfile=array("add","upload","plugin","addons","proxy.lst","proxy.php","xmail.php","myconfig.php","build.php","services.php","login.php","smtp.php","spbland.php","base64.js",".htaccess","map.php","license.html","sendspace.php","config.php","cookie.php","function.php","index.php","notlink.php","files.lst","PEAR.php","Tar.php","pclzip.lib.php","index.html","pack.php","opera.php","upload.php","auth.php","langpack.php", "line.gif", "bg.jpg", "logo.png", "autodownload.php", "rgquota.rgquota", "style", ".htaccess", "info.php");

//		if ($list && $recreate === false) return true;

  $files_lst = $workpath . DIRECTORY_SEPARATOR . "files.lst";
  $glist = array();

  if (($show_all === TRUE) && ($_COOKIE["showAll"] == 1)) {
    if (isset($dir) and $dir != '') {
      $workfolderconvert = rawurldecode($dir);/*�������� � ����������� ���*/
      $workfolderconvert = realpath($workfolderconvert);/*������ �������� ����*/
      $find = strpos($workfolderconvert, $workpath); /*��������� ����������� ����*/
      if ($find === FALSE) {
        $workfolderconvert = realpath($workpath);
        echo "<div>������� ������</div>";
        if (!is_readable($workfolderconvert)) {
          echo "<div>������� ������� �� ��������.</div>";
          return FALSE;
        }
      }
    }
    else {
      $workfolderconvert = $workpath;
    }
    $mydir = $workfolderconvert;
    $dir = dir($mydir . DIRECTORY_SEPARATOR);
    while (FALSE !== ($file = $dir->read())) {
      if ($file[0] != "." && (!in_array($file, $systemfile)) && is_readable($mydir . DIRECTORY_SEPARATOR . $file)) {
        $file = $mydir . DIRECTORY_SEPARATOR . $file;
        $isdir = FALSE;
        if (is_dir($file)) {
          $isdir = TRUE;
        }
        $time = filectime($file);
        while (isset($glist[$time])) {
          $time++;
        }
        $glist[substr(md5(basename($file)), 8, 8)] = array(
          "name" => realpath($file),
          "size" => bytesToKbOrMb(getfilesize($file)),
          "date" => $time,
          "is_dir" => $isdir
        );
      }
    }
    $dir->close();
    @uasort($glist, "_cmp_list_enums");
  }
  else {
    if (@file_exists($files_lst)) {
      $glist = file($files_lst);
      $dlist = array();
      foreach ($glist as $key => $record) {
        foreach (unserialize($record) as $field => $value) {
          $listReformat[$key][$field] = $value;
          if ($field == "date") {
            $date = $value;
          }
          if ($field == "name") {
            $filename = $value;
          }
        }

        if (!@file_exists($filename)) {
          unset($glist[$key], $listReformat[$key]);
          continue;
        }

        if (!isset($dlist[$filename]) || ($dlist[$filename] < $date)) {
          unset($glist[$dlist[$filename]]);
          $dlist[$filename] = $date;
          $glist[substr(md5(basename($filename)), 8, 8)] = $listReformat[$key];
        }
        unset($glist[$key], $listReformat[$key]);
      }
    }
  }
  $list = $glist;
  /*	print_r($list);*/
}

function basename_r($sdir = '') {
  if ($sdir == '') {
    return (FALSE);
  }
  else {
    $spos = strrpos($sdir, DIRECTORY_SEPARATOR);
    if ($spos === FALSE) {
      return ($sdir);
    }
    else {
      return (substr($sdir, $spos + 1));
    }
  }
}

function transtr($st) {
  $st = strtr($st, "������������������������_", "abvgdeeziyklmnoprstufh'iei");
  $st = strtr($st, "�����Ũ������������������_", "ABVGDEEZIYKLMNOPRSTUFH'IEI");
  $st = strtr($st, array(
      "�" => "zh",
      "�" => "ts",
      "�" => "ch",
      "�" => "sh",
      "�" => "shch",
      "�" => "",
      "�" => "yu",
      "�" => "ya",
      "�" => "ZH",
      "�" => "TS",
      "�" => "CH",
      "�" => "SH",
      "�" => "SHCH",
      "�" => "",
      "�" => "YU",
      "�" => "YA",
      "�" => "i",
      "�" => "Yi",
      "�" => "ie",
      "�" => "Ye"
    ));
  return $st;
}

?>
