<?
error_reporting(0);
ignore_user_abort(true);
set_time_limit(0);
error_reporting(7);

@ini_set('memory_limit', '1024M');
@ob_end_clean();
ob_implicit_flush(TRUE);
$nn = "\r\n";
$descript="Upload by rapidget, http://rapidgetpro.ru/";

define('RAPIDGET','yes');

include "../config.php";
if ($authorization!==false){
        if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($authorization[$_SERVER['PHP_AUTH_USER']]) || ($authorization[$_SERVER['PHP_AUTH_USER']] !== $_SERVER['PHP_AUTH_PW']))
            {
                header("WWW-Authenticate: Basic realm=\"RapidGet\"");
                header("HTTP/1.0 401 Unauthorized");
                die("<h1><a href=http://www.php.net>RapidGet</a>: Access Denied - password erroneous</h1>\n");
            }
    }

if(!defined('CRLF')) define('CRLF',"\r\n");

include "../function.php";
include "upfile.php";


if (!$_REQUEST[uploaded])
	{
		html_error("Not selected upload services");
	}

if (!$_REQUEST[filename] || in_array(basename($_REQUEST[filename]),$systemfile))
	{
		html_error("Not select file to upload");
	}

$_REQUEST[filename]=base64_decode($_REQUEST[filename]);

$pt = @explode(DIRECTORY_SEPARATOR,getcwd());
array_pop($pt);
$workpath = @implode(DIRECTORY_SEPARATOR,$pt);

$lfile=$_REQUEST[filename];
$lname=basename($lfile);

$path_to_file = str_replace($lname,"",$lfile);
$path_to_file = realpath($path_to_file); 

/* запрещаем обращение к системным папкам */
# папка с плагинами
if (is_dir($workpath.DIRECTORY_SEPARATOR.'plugin')){
        $plugin_dir=opendir($workpath.DIRECTORY_SEPARATOR.'plugin');
        while (($file = readdir($plugin_dir)) !== false){
            if (is_dir($workpath.DIRECTORY_SEPARATOR.'plugin'.DIRECTORY_SEPARATOR.$file)) continue;
            $systemfile[] = $file;
        }
}

# папка с upload
if (is_dir($workpath.DIRECTORY_SEPARATOR.'upload')){
        $plugin_dir=opendir($workpath.DIRECTORY_SEPARATOR.'upload');
        while (($file = readdir($plugin_dir)) !== false){
            if (is_dir($workpath.DIRECTORY_SEPARATOR.'upload'.DIRECTORY_SEPARATOR.$file)) continue;
            $systemfile[] = $file;
        }
}
# upload плагины
if (is_dir($workpath.DIRECTORY_SEPARATOR.'upload'.DIRECTORY_SEPARATOR.'plugin')){
        $plugin_dir=opendir($workpath.DIRECTORY_SEPARATOR.'upload'.DIRECTORY_SEPARATOR.'plugin');
        while (($file = readdir($plugin_dir)) !== false){
            if (is_dir($workpath.DIRECTORY_SEPARATOR.'upload'.DIRECTORY_SEPARATOR.'plugin'.DIRECTORY_SEPARATOR.$file)) continue;
            $systemfile[] = $file;
        }
}
# модули
if (is_dir($workpath.DIRECTORY_SEPARATOR.'modules')){
        $plugin_dir=opendir($workpath.DIRECTORY_SEPARATOR.'modules');
        while (($file = readdir($plugin_dir)) !== false){
            if (is_dir($workpath.DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.$file)) continue;
            $systemfile[] = $file;
        }
}
# addons
if (is_dir($workpath.DIRECTORY_SEPARATOR.'addons')){
        $plugin_dir=opendir($workpath.DIRECTORY_SEPARATOR.'addons');
        while (($file = readdir($plugin_dir)) !== false){
            if (is_dir($workpath.DIRECTORY_SEPARATOR.'addons'.DIRECTORY_SEPARATOR.$file)) continue;
            $systemfile[] = $file;
        }
}

# language
if (is_dir($workpath.DIRECTORY_SEPARATOR.'language')){
        $plugin_dir=opendir($workpath.DIRECTORY_SEPARATOR.'language');
        while (($file = readdir($plugin_dir)) !== false){
            if (is_dir($workpath.DIRECTORY_SEPARATOR.'language'.DIRECTORY_SEPARATOR.$file)) continue;
            $systemfile[] = $file;
        }
}

?>
<html>
<head>
<title>Uploading file <? echo basename($_REQUEST[filename]); ?> to <? echo $_REQUEST[uploaded]; ?></title>
<script>
var orlink='<? echo basename($_REQUEST[filename]); ?> to <? echo $_REQUEST[uploaded]; ?>';
</script>
</head>

<?
if (!file_exists($lfile)){
		html_error("ERROR: file not exist ".$lname);
}
				
if (!is_readable($lfile)){
    html_error("ERROR: not readable ".$lname);
}

if (in_array($lname,$systemfile)){
    html_error("ERROR: The use of sytem file is disabled (forbidden). Access is denied.");    
}
if (stristr($path_to_file,$workpath)===false){
    html_error("ERROR: The work with system folders is denied.");    
}

$fsize=getfilesize($lfile);

if (file_exists("./plugin/".basename($_REQUEST[uploaded]).".php")){    
    include_once("./plugin/".basename($_REQUEST[uploaded]).".index.php");
    if ($max_file_size["$_REQUEST[uploaded]"]!=false)
        if ($fsize > $max_file_size["$_REQUEST[uploaded]"]*1024*1024)       
            html_error("The maximum possible file size for this shared services is exeeded");   
    include_once("./plugin/".$page_upload["$_REQUEST[uploaded]"]);
}else html_error('This file shared services are not supported'); 

?>
</td></tr></table>
<?
	if ($download_link || $delete_link || $stat_link || $adm_link)
		{
			//Protect down link with http://lix.in/
			if ($_REQUEST['protect']==1){
				unset($post);
				$post['url'] =$download_link;
				$post['button'] = 'Protect+Link';
				$post['op'] = 'crypt_single';
				$post['reset']='Clear';
				$page = geturl("lix.in",80,"/index.php","http://lix.in/",0,$post);
				$tmp = cut_str($page,"http://lix.in/","'");
				if (!empty($tmp)) $protect = "http://lix.in/".$tmp;
			}
						
			
			echo "\n<table width=100% border=0>";
			echo ($download_link ? "<tr><td width=100 nowrap>Download-Link:<td width=80%><input value='$download_link' style=\"width: 550px; border: 1px solid #FF7C0B; background-color: #F5F5F5;\" readonly></tr>" : "");
			echo ($delete_link ? "<tr><td width=100 nowrap>Delete-Link:<td width=80%><input value='$delete_link' style=\"width: 550px; border: 1px solid #FF7C0B; background-color: #F5F5F5;\" readonly></tr>" : "");
			echo ($stat_link ? "<tr><td width=100 nowrap>Stat-Link:<td width=80%><input value='$stat_link' style=\"width: 550px; border: 1px solid #FF7C0B; background-color: #F5F5F5;\" readonly></tr>" : "");
			echo ($adm_link ? "<tr><td width=100 nowrap>ADM-Link:<td width=80%><input value='$adm_link' style=\"width: 550px; border: 1px solid #FF7C0B; background-color: #F5F5F5;\" readonly></tr>": "");
			echo ($user_id ? "<tr><td width=100 nowrap>USER-ID:<td width=80%><input value='$user_id' style=\"width: 550px; border: 1px solid #FF7C0B; background-color: #F5F5F5;\" readonly></tr>": "");
			echo ($ftp_uplink ? "<tr><td width=100 nowrap>FTP UPLOAD:<td width=80%><input value='$ftp_uplink' style=\"width: 550px; border: 1px solid #FF7C0B; background-color: #F5F5F5;\" readonly></tr>": "");
			echo ($access_pass ? "<tr><td width=100 nowrap>PASSWD:<td width=80%><input value='$access_pass' style=\"width: 550px; border: 1px solid #FF7C0B; background-color: #F5F5F5;\" readonly></tr>": "");
			echo ($protect ? "<tr><td width=100 nowrap>Protect link:<td width=80%><input value='$protect' style=\"width: 550px; border: 1px solid #FF7C0B; background-color: #F5F5F5;\" readonly></tr>": "");
			echo "</table>\n";
			
			$fr=fopen(trim($lfile).".upload",'a+');
			if ($fr)
				{
					fwrite($fr,date("Y-m-d H:i:s")."\n");
					fwrite($fr,$lname."  ".bytesToKbOrMb($fsize)."\n");
					if ($download_link) { fwrite($fr,"download link: $download_link\r\n");}
					if ($delete_link) { fwrite($fr,"delete link: $delete_link\r\n");}
					if ($stat_link) { fwrite($fr,"stat link: $stat_link\r\n");}
					if ($adm_link) { fwrite($fr,"ADM link: $adm_link\r\n");}
					if ($user_id) {fwrite($fr,"USER ID: $user_id\r\n");}
					if ($access_pass) {fwrite($fr,"PASSWD: $access_pass\r\n");}
					if ($ftp_uplink) {fwrite($fr,"ftp upload: $ftp_uplink\r\n");}
					if ($protect) {fwrite($fr,"protect link: $protect\r\n");}
					fwrite($fr,"\n");
					fclose($fr);
				}
		}
echo $not_done ? "" : '<p><center>DONE</center>';
?>

</html>