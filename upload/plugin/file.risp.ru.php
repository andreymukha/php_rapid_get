<?php
$continue_up=false;
$not_done=true;

if ($_REQUEST['action'] == "DONE")
	{	
		if (!$_REQUEST['tekst'])
			{
				html_error('Not entered protect image code');
			}
	
		$url_='/put/?key='.$_REQUEST['session_id'];
		
		$post['cat']=$_REQUEST['cat'];
		$post['descr']='';
		$post['protect']=$_REQUEST['protect'];
		
		$post['pwd']=$_REQUEST['up_pass1'];
		$post['rep_pwd']=$_REQUEST['up_pass2'];
		$post['tekst']=$_REQUEST['tekst'];
		$post['sbm1']='Подтвердить';
		
		$cookies[]='FI='.$_REQUEST['session_id'].'; path=/';
		
		$page = geturl("file.risp.ru",80,$url_,"http://file.risp.ru".$url_,$cookies,$post);
		is_page($page);
		
		if (strstr($page,'Location: ok/'))
			{
				$page = geturl("file.risp.ru",80,'/put/ok/',"http://file.risp.ru".$url_,$cookies,$post);
				$download_link = 'http://file.risp.ru/get/file/?id='.trim(cut_str($page,'http://file.risp.ru/get/file/?id=','<'));
				$not_done=false;
			}
				else
			{
				if ($_REQUEST[count] == 1)
					{
						html_error('Cod not correctly entered by 3 attempts');
					}
						else
					{
						$access_img='http://file.risp.ru/code.php?'.time();
						$access_img = "../index.php?command=image&link=".urlencode(base64_encode($access_img))."&ref=".urlencode(base64_encode('http://file.risp.ru/put/?key='.$session_id))."&cookie=".urlencode(base64_encode(serialize($cookies)));
					
?>
<table border=1 style="width:270px;" cellspacing=0 align=center>
<form method=post>
<input type=hidden name=count value=<?php echo ($_REQUEST[count]-1) ?>>
<input type=hidden name=action value='DONE'><input type=hidden value=uploaded value'<?php $_REQUEST[uploaded]?>'>
<input type=hidden name=filename value='<? echo base64_encode($_REQUEST[filename]); ?>'>
<input type=hidden name=session_id value='<?php echo $session_id;?>'>
<tr><td nowrap>&nbsp;Код<td>&nbsp;<input type=text name=tekst value="" MAXLENGTH=4 size=4>&nbsp;<img src=<?php echo $access_img ?>></tr>

<input type=hidden name=cat value='<?php $_REQUEST[cat] ?>'>
<input type=hidden name=protect value='<?php $_REQUEST[protect] ?>'>
<input type=hidden name=up_pass1 value='<?php $_REQUEST[up_pass1] ?>'>
<input type=hidden name=up_pass2 value='<?php $_REQUEST[up_pass2] ?>'>
<tr><td colspan=2 align=center><input type=submit value='Подтвердить'></tr>
</table>
</form>
<?php
						exit();					
					}
			}
		
		$not_done=false;
	}
		else
	{
		if ($_REQUEST['action'] == "FORM")
			{
				$continue_up=true;
			}
				else
			{
?>
<table border=1 style="width:270px;" cellspacing=0 align=center>
<form method=post>
<input type=hidden name=action value='FORM'><input type=hidden value=uploaded value'<?php $_REQUEST[uploaded]?>'>
<input type=hidden name=filename value='<? echo base64_encode($_REQUEST[filename]); ?>'>
<tr><td nowrap>&nbsp;Login<td>&nbsp;<input name=up_login value='' style="width:160px;">&nbsp;</tr>
<tr><td nowrap>&nbsp;Password<td>&nbsp;<input name=up_pass value='' style="width:160px;">&nbsp;</tr>
<tr><td colspan=2 align=center><input type=submit value='Upload'></tr>
</table>
</form>
<?php			
				die();
			}
	}

if ($continue_up === true)
	{
?>
<table width=600 align=center>
<tr><td align=center>
<?php
		if ($_REQUEST[up_login] && $_REQUEST[up_pass])
			{
?>
<div id=auth width=100% align=center>Auth in services</div>
<?php
				$post["login"]=$_REQUEST[up_login];
				$post["password"]=$_REQUEST[up_pass];
				$post["logn"]="Вход";
				$page = geturl("file.risp.ru",80,"/","http://file.risp.ru/",0,$post);
				
				is_page($page);
				
				if (strstr($page,'логин или пароль введен не верно'))
					{
						echo "<div id=auth2 width=100% align=center><b>Error in auth. Use free uploading mode</b></div>\n";
						$new_url='/';
						$session_cookies=0;
					}
						else
					{	
						echo "<div id=auth2 width=100% align=center><b>Use auth uploading mode</b></div>\n";
						$session_cookies=GetCookies($page);
						
						$new_url='/my_files/';
					}
?>
<script>document.getElementById('auth').style.display='none';</script>
<?php
			}
				else
			{
				$new_url='/';
				$session_cookies=0;
			}
?>
<div id=info width=100% align=center>Retrive upload ID</div>
<?
		$page = geturl("file.risp.ru", 80, $new_url , "http://file.risp.ru/", $session_cookies, 0, 0, ""); 
		$session_id=cut_str($page,'put/?key=','"');
		
		if (!$session_id)
			{
				html_error('Error retrive upload id');
			}
?>
<script>document.getElementById('info').style.display='none';</script>
<?
			$post_up['show_progress_bar']='0';
			$post_up['add']='Закачать';
			$post_up['sogl']='1';

			$cookies[]='FI='.$session_id.'; path=/';
            $upfiles=upfile('file.risp.ru',80,'/put/?key='.$session_id,"http://file.risp.ru/", $cookies, $post_up, $lfile, $lname, "fl");
            
			is_page($upfiles);
			if (!strstr($upfiles,'Введите код, который вы видите на картинке'))
				{
					html_error('File not uploaded');
				}
				
			$access_img='http://file.risp.ru/code.php?'.time();
			$access_img = "../index.php?command=image&link=".urlencode(base64_encode($access_img))."&ref=".urlencode(base64_encode('http://file.risp.ru/put/?key='.$session_id))."&cookie=".urlencode(base64_encode(serialize($cookies)));

?>
<script>document.getElementById('progressblock').style.display='none';</script>

<table border=1 style="width:270px;" cellspacing=0 align=center>
<form method=post>
<input type=hidden name=count value=3>
<input type=hidden name=action value='DONE'><input type=hidden value=uploaded value'<?php $_REQUEST[uploaded]?>'>
<input type=hidden name=filename value='<? echo base64_encode($_REQUEST[filename]); ?>'>
<input type=hidden name=session_id value='<?php echo $session_id;?>'>
<tr><td nowrap>&nbsp;Код<td>&nbsp;<input type=text name=tekst value="" MAXLENGTH=4 size=4>&nbsp;<img src=<?php echo $access_img ?>></tr>
<tr><td nowrap>&nbsp;Категория<td>&nbsp;<select name=cat><?php echo cut_str($upfiles,'<select name=cat>','</select>'); ?></select></tr>
<tr><td nowrap colspan=2><input type=checkbox name=protect onclick='en_switch(this.checked);'> Защитить файл паролем</td></tr>
<tr><td nowrap>&nbsp;Пароль<td>&nbsp;<input id=up_pass1 name=up_pass1 value='' style="width:160px;" disabled>&nbsp;</tr>
<tr><td nowrap>&nbsp;Повтор<td>&nbsp;<input id=up_pass2 name=up_pass2 value='' style="width:160px;" disabled>&nbsp;</tr>
<tr><td colspan=2 align=center><input type=submit value='Подтвердить'></tr>
</table>
</form>
<script>
function en_switch(ch)
	{
		document.getElementById("up_pass1").disabled=!ch;
		document.getElementById("up_pass2").disabled=!ch;
	}
</script>
<?php
		print_r(GetCookies($upfiles));
	}
?>
	
</table>	