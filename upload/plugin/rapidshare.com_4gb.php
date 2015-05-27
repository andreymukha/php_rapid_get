<?php
# Upload plug'in from Director Of Zoo (member ru-board <kamyshew>)

##########################################
# тут можно ввести логин/пасс по умлочанию
$rapid_login = false; // например "login"
$rapid_pass = false; // например "password"
##########################################


$not_done=true;
$continue_up=false;
if ($rapid_login & $rapid_pass){
	$_REQUEST['my_login'] = $rapid_login;
	$_REQUEST['my_pass'] = $rapid_pass;
	$_REQUEST['action'] = "FORM";
	echo "<b><center>Use Default rapidshare.com login/pass.</center></b>\n";
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
		$agent = "RAPIDSHARE MANAGER Application Version: NOT INSTALLED";
		$not_done=false;        
        if (empty($_REQUEST['my_login']) || empty($_REQUEST['my_pass'])){
            echo "<b><center>Empty login/pass rapidshare.com. Use <span style='color:red'>FREE</span> rapidshare Account.</center></b>\n";
            $mem=false;
        }else{
            $post['login'] = $_REQUEST['my_login'];
            $post['password'] = $_REQUEST['my_pass'];    
        }
            
?>

<table width=600 align=center>
</td></tr>
<tr><td align=center>
	
<div id=info width=100% align=center>Retrive upload ID</div>
<?
			$page = geturl("www.rapidshare.com",80,"/cgi-bin/rsapi.cgi?sub=nextuploadserver_v1","",0,0, 0, "");
			is_page($page);
            // зеркало
            if (preg_match('/\\d*\\z/', $page, $regs)) {
                $mirror = $regs[0];
            }else{
                html_error("Error Get mirror! <br /> Ошибка получения зеркала для аплоада!");    
            }             
            $url_action = "http://rs".$mirror.".rapidshare.com/cgi-bin/upload.cgi";
            
?>
	<script>document.getElementById('info').style.display='none';</script>
<?
		   				
			$post["rsapi_v1"]=1;
			$post["go"]=1;
			$post["description"]=$descript;
			
			$url=@parse_url($url_action);
			
			$upfiles=upfile($url["host"],defport($url), $url["path"].($url["query"] ? "?".$url["query"] : ""),"", 0, $post, $lfile, $lname, "filecontent");

?>
<script>document.getElementById('progressblock').style.display='none';</script>
<?		
			is_page($upfiles);
            is_notpresent($upfiles,'savedfiles=1','File not uploaded');
			
			$download_link=cut_str($upfiles,"File1.1=","\n");
			$delete_link=cut_str($upfiles,"File1.2=","\n");
	}     
	// Upload plug'in from Director Of Zoo ( ru-board member <kamyshew>).
    // За идею реализации аплоада - спасибо d()wn
?>