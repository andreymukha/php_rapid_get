<?php
  $systemfile[] = "ftp.mod.php";
  $systemfile[] = "ftp_go.mod.php";
  
  $menu_action['ftp'] = $langftpfile;
  
  if(!defined("FTP_AUTOASCII")) define("FTP_AUTOASCII", -1);
  if(!defined("FTP_BINARY")) define("FTP_BINARY", 1);
  if(!defined("FTP_ASCII")) define("FTP_ASCII", 0);
  if(!defined('FTP_FORCE')) define('FTP_FORCE', TRUE);
?>