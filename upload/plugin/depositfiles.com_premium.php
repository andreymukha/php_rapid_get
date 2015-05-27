<?php
# Upload plug'in from Director Of Zoo (member ru-board <kamyshew>)
#за предоставленый акк для тестов благодарим ru-board member <starsh1ne>

##########################################
# тут можно ввести логин/пасс по умлочанию
$deposit_login = ""; // например "login"
$deposit_pass = ""; // например "password"
##########################################


$not_done=true;
$continue_up=false;
if ($deposit_login & $deposit_pass){
	$_REQUEST['my_login'] = $deposit_login;
	$_REQUEST['my_pass'] = $deposit_pass;
	$_REQUEST['action'] = "FORM";
	echo "<b><center>Use Default Depositfiles.com login/pass.</center></b>\n";
}

if ($_REQUEST['action'] == "FORM")
    $continue_up=true;
else{
?>
<table border=1 style="width:270px;" cellspacing=0 align=center>
<form method=post>
<input type=hidden name=action value='FORM' />
<tr><td nowrap>&nbsp;Login<td>&nbsp;<input name=my_login value='' style="width:160px;" />&nbsp;</tr>
<tr><td nowrap>&nbsp;Password<td>&nbsp;<input name=my_pass value='' style="width:160px;" />&nbsp;</tr>
<tr><td colspan=2 align=center><input type=submit value='Upload' /></tr>
</table>
</form>
<?php
	}

if ($continue_up)
	{
		$lang = "lang_current=en";// определяем язык	
		$not_done=false;        
        if (empty($_REQUEST['my_login']) || empty($_REQUEST['my_pass'])){
            echo "<b><center>Empty login/pass Depositfiles.com. Use <span style='color:red'>FREE</span> Depositfiles Account.</center></b>\n";
            $mem=false;
        }else{
?>
<div id=login width=100% align=center>Login to Depositfiles</div>
<?php
					
			$mem=true;
            $post['login']=$_REQUEST['my_login'];
            $post['password'] = $_REQUEST['my_pass'];
            $post['go']=1;
            $page = geturl("depositfiles.com",80,"/en/login.php","http://depositfiles.com/en/gold.php",$lang,$post);			
			is_page($page);
			$cookie = GetCookies($page,true);
			$cook = @implode("; ",$cookie);
			$cook .= "; ".$lang;
		}	
?>
<script>document.getElementById('login').style.display='none';</script>

<table width=600 align=center>
</td></tr>
<tr><td align=center>
	
<div id=info width=100% align=center>Retrive upload ID</div>
<?
			$cook=$cook?($cook."; ".$lang):$lang;
			$page = geturl("depositfiles.com",80,"/en/","http://depositfiles.com/en/gold.php",$cook,0, 0, "");
			is_page($page);
			if (strpos($page,"logout")){
                echo "<b><center>Use Depositfiles <span style='color:green;'>Gold Account</span>.</center></b>\n";
            }else {
				$cookie=GetCookies($page,true);
				$cook = @implode("; ",$cookie);	
                echo $mem?"<b><center>Error login to Depositfiles. Use <span style='color:red'>FREE</span> Depositfiles Account.</center></b>\n":"";				
                $mem=false;
            }
			

?>
	<script>document.getElementById('info').style.display='none';</script>
<?
		
			$UPLOAD_IDENTIFIER=cut_str($page,'UPLOAD_IDENTIFIER" value="','"');            

			if (!$UPLOAD_IDENTIFIER)
				{
					html_error("Error retrive UPLOAD_IDENTIFIER");
				}
				
			$post["agree"]=1;
			$post["UPLOAD_IDENTIFIER"]=$UPLOAD_IDENTIFIER;
			$post["MAX_FILE_SIZE"]=$mem?2097152000:314572800;
			$post["go"]=1;
			$post["description"]=$descript;
			
			$url_action = cut_str($page,'multipart/form-data" action="','"');			
			
			$url=@parse_url($url_action);
			if(!$url) html_error("Error Retrive Action Url <br /> Ошибка получения адреса для загрузки файла");
			
			$upfiles=upfile($url["host"],defport($url), $url["path"].($url["query"] ? "?".$url["query"] : ""),"http://depositfiles.com/en/", $cook, $post, $lfile, $lname, "files");

?>
<script>document.getElementById('progressblock').style.display='none';</script>
<?		
			is_page($upfiles);
			is_notpresent($upfiles,'parent.ud_download_url','File not uploaded');
			
			$download_link=cut_str($upfiles,"parent.ud_download_url = '","';");
			$delete_link=cut_str($upfiles,"parent.ud_delete_url = '","';");
	}
	// Upload plug'in from Director Of Zoo ( ru-board member <kamyshew>). No rapidkill!!! Checkmate Use Google, my friend !!! No plagiarism!!!
?>