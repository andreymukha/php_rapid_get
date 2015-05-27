<table width=600 align=center>
</td></tr>
<tr><td align=center>
<?php
error_reporting(7);
# АВТОР: VALDIKSS.
# Привел в работоспособное состояние: TRiTON4ik.
# Плагин обновлен по просьбе: Pensal.
# Форум IP.Board 2.3.1.
# UPLOAD PLUGIN ONLY FOR PHP RAPID GET.
$continue_up=false;
if ($_REQUEST['action'] == "OK")
    $continue_up=true;
else{	?><form method=post>
<div>IP.Board 2.3.1</div>
<input type=hidden value=uploaded value'<?php $_REQUEST[uploaded]?>'><br>
<input type=hidden name=filename value='<?php echo base64_encode($_REQUEST[filename]); ?>'><br>
LOGIN:<input name=log value='' type=input style="width:160px;"><br>
PASS:<input name=pass value='' type=input style="width:160px;"><br>
DOMAIN:<input name=domain value='www.DOMAIN.ru' type=input style="width:160px;"><br>
PATH:<!--Edit path in plugin//--><input name=domain value='/forum' type=input style="width:160px;" disabled><br>
<input type=hidden name=action value='OK'><br>
<input type=submit value='Upload'><br>
</table>
</form>
	<?
	}
if($continue_up==true);
{
// Config
$login=$_REQUEST['log'];            /*Ваш логин на форуме. */  /*Your login on forum.   */
$password=$_REQUEST['pass'];       /*Ваш пароль на форуме.*/  /*Your password on forum.*/
$server=$_REQUEST['domain'];      /*Доменное имя форума. */  /*Domain name of forum.  */
$forum="/forum";                 /*Путь до форума.      */  /*Patch to a forum.      */

if (!$login) html_error("No login!<br>");
if (!$password) html_error("No password!<br>");
if (!$server) html_error("No domain!<br>");
$post_auth["UserName"]=$login;
$post_auth["PassWord"]=$password;
$post_auth["x"]='0';
$post_auth["y"]='0';
//Получаем необходимые параметры на этой странице.
$pagecook = geturl($server, 80, $forum."/index.php", 0, 0, 0, 0, "");
//Получили куки.
$cookies = GetCookies($pagecook,true);
//Конектимся под логином и паролем.
$page = geturl($server, 80, $forum."/index.php?s=1&act=Login&CODE=01&amp;CookieDate=1", 0, $cookies, $post_auth, 0, "");
//Обновляем куки.
$cookie = GetCookies($page,true);
//Подкоректируем их.
$cookie=$cookie[0].";".$cookie[1].";".$cookie[2].";".$cookie[3];
//Тут мы создаем новый топик.
$page=geturl($server, 80, $forum."/index.php?act=post&do=new_post&f=6", 0, $cookie, 0, 0, "");
//Обновляем куки.
$cookie = GetCookies($page,true);
	//Переменные ниже(авторский код Valdikss`а) нужны для создания поста, но мы только заливаем файл, поэтому пропустим.
	/*$post["act"]='Post';
	  $post["attachgo"]='1';
	  $post["auth_key"]=cut_str($page,"auth_key' value='","'");
	  $post["CODE"]='01';
	  $post["f"]='6';
	  $post["post_key"]=cut_str($page,"post_key' value='","'"); */
//Готовим параметры для загрузки файла.
$post['act']='attach';
$post['code']='attach_upload_process';
$post['attach_rel_module']='post';
$post['attach_rel_id']='0';
$keyres = cut_str($page,"ipsattach.iframe_init_url",";");
$key = cut_str($keyres,"attach_post_key=","&");
$post['attach_post_key']=$key;
$post['forum_id']='6';
$post['--ff--forum_id']='6';
//Подкоректируем куки.
$cookie=$cookie[0].";".$cookie[1].";".$cookie[2].";".$cookie[3];
//Делаем загрузку на сервер. Дублируем параметры.
$upfiles=upfile($server,80,$forum."/index.php?&act=attach&code=attach_upload_process&attach_rel_module=post&attach_rel_id=0&attach_post_key=".$key."&forum_id=6&--ff--forum_id=6",0, $cookie, $post, $lfile, $lname, "FILE_UPLOAD", 0);
//Вырезаем со страницы переменную parent.ipsattach.add_current_item.
$files = cut_str($upfiles,'// Populate menu','//]]>');
$files = cut_str($upfiles,'parent.ipsattach.add_current_item',';');
//Подготавливаем параметры внутри parent.ipsattach.add_current_item.
$files = ereg_replace(" '",'',$files);
$files = ereg_replace("'",'',$files);
$files = ereg_replace('\(','',$files);
$files = ereg_replace('\)','',$files);
//Производим преобразование строчки в переменные.
$ids = explode(",",$files);
//Выделаяем из множества перемен, только первую.
$id=$ids[0];
$download_link="http://".$server.$forum."/index.php?act=Attach&type=post&id=".$id;
}
?>
<script>document.getElementById('progressblock').style.display='none';</script>