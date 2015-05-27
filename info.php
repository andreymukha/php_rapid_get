<?
define('RAPIDGET','yes');include "config.php";

/*[settings > Languges | Язык]*/
if(file_exists(getcwd().DIRECTORY_SEPARATOR.'language'.DIRECTORY_SEPARATOR.'language.'.($language?$language:(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2))).'.php')){
    include_once(getcwd().DIRECTORY_SEPARATOR.'language'.DIRECTORY_SEPARATOR.'language.'.($language?$language:(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2))).'.php');    
}else{
    die("Не найден файл локализации! Проверьте структуру и настройки!");    
}
/*[settings < Languges | Язык]*/
$is_alias=substr($_SERVER[SCRIPT_FILENAME],-strlen($_SERVER[SCRIPT_NAME])) != $_SERVER[SCRIPT_NAME] ? true : false;
define('HOSTROOT',($is_alias ? '' : realpath(substr($_SERVER[SCRIPT_FILENAME],0,-strlen($_SERVER[SCRIPT_NAME])))));
$mail_not_support=in_array("mail",$disable_function) || in_array("base64_encode",$disable_function) || in_array("chunk_split",$disable_function);
$nn = "\r\n";
include "auth.php";
include "function.php";?>
<html><noindex><head><link rel="stylesheet" type="text/css" href="style/css.css"><meta http-equiv="Content-Type" content="text/html; charset=windows-1251"><body >
<div><img src="logo.png" align="absmiddle" border="0"></div><div>
<?php echo $langcurrentver." ".SCRIPTNAME." version: ".VERSIONS." build: ".BUILD." ".SCRIPTTYPE ?><br>
<?php echo $langscriptbuild." ".VERSION ?><br>
<?php echo $langscripthelped." ".VERSIONHELP ?><br>
</noindex><a href=http://rapidgetpro.ru/><?php echo $langhpage;?></a><noindex>  |   <a href="http://forum.ru-board.com/topic.cgi?forum=5&topic=24768"><?php echo $langspage;?></a>
</div>
<br>
<div><!--Редактирование этого блока категорически запрещено//-->
<?php echo $langscriptsupport; ?> Pensal, Alex, Abzal_LSC, Lexan.</noindex>
<br><a href=http://sibnet.cn/>SIBnet.cn - Все о файлообмене, и близком к нему.</a>
<br><a href=http://airwarez.net/>AirWarez.Net - Игры,музыка,кино.</a>
<br><a href=http://warex.ru/>wareX.Ru - Фильмы,игры,софт.</a>
<br><a href=http://fastvps.ru/><b>FastVPS</b> -Виртуальные сервера под рапидгет</a>
<noindex></div>
<hr><br>
<div id='x1' style='padding:3px;'><!--Редактируйте этот блок в конфиге//-->
<b><?php echo $langscriptadmin." ".$adminperson;?>
<br><?php echo $adminemail;?> | <?php echo $adminICQ;?></b>
</div>
<div><form method=post enctype='multipart/form-data'><b>Upload on this server:</b><input name="filename" size=25 disabled value="Not supports in this version"><input type='file' name="uploalink" disabled><input type=submit value="upload" disabled></form><div>
<hr>
<?
$dateABC= date('d');
if ($dateABC=="1"){$showupdate="OK";}
elseif ($dateABC=="3"){$showupdate="OK";}
elseif ($dateABC=="7"){$showupdate="OK";}
elseif ($dateABC=="15"){$showupdate="OK";}
elseif ($dateABC=="21"){$showupdate="OK";}
if($showupdate=="OK"){
$url="http://rapidgetpro.ru/updatescript.php?version=".VERSIONS."&build=".BUILD."type=".SCRIPTTYPE;
$Url=parse_url($url);
$updatepage = geturl($Url["host"], defport($Url), $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), 0, 0, 0, 0, $_GET["proxy"] ,$pauth);
is_page($updatepage);
$updatepage=cut_str($updatepage, "<xml>", "</xml>");
echo $updatepage;
}
if (version_compare(phpversion(), "4.3.0", "<"))
	{
?>
<html>
<p><center><font color=red><b><?php echo $langerror[1];?></b></font><br><b><?php echo $langerror[2];?> <?php echo phpversion() ?></b></center>
</html>
<?php
		//exit;
	}

if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
	{
?>
<html>
<p><center><font color=red><b><?php echo $langerror[1];?></b></font><br><b><?php echo $langerror[3];?></b></center>
</html>
<?php
		//exit;
	}
if (in_array("fsockopen",$disable_function))
	{
?>
<html>
<p><center><font color=red><b><?php echo $langerror[1];?></b></font><br><b><?php echo $langerror[4];?></b></center>
</html>
<!--
<?php echo "disable_function=$disable_str\nsafe_mode=".(ini_get('safe_mode'))."\n"; ?>
-->
<?
		//exit;
	}
?>
<body ></noindex></html>
