<?php
# Upload plug'in from Director Of Zoo (member ru-board <kamyshew>)
#за предоставленый акк дл€ тестов благодарим ru-board member <starsh1ne>

##########################################
# тут можно ввести логин/пасс по умлочанию
$login = false; // например мыло "test@test.test"
$pass = false; // например пароль "test"
##########################################


$not_done=true;
$continue_up=false;

if ($login & $pass){
	$_REQUEST['filefac_log'] = $login;
	$_REQUEST['filefac_pass'] = $pass;
	$_REQUEST['action'] = "FORM";
	echo "<b><center>Use Default Filefactory.com login/pass.</center></b>\n";
}

if ($_REQUEST['action'] == "FORM")
    $continue_up=true;
else{
?>
<table border=1 style="width:270px;" cellspacing=0 align=center>
<form method=post>
<input type=hidden name=action value='FORM'>
<tr><td nowrap>&nbsp;E-mail<td>&nbsp;<input name=filefac_log value='' style="width:160px;">&nbsp;</tr>
<tr><td nowrap>&nbsp;Password<td>&nbsp;<input name=filefac_pass value='' style="width:160px;">&nbsp;</tr>
<tr><td colspan=2 align=center><input type=submit value='Upload'></tr>
</table>
</form>
<?php
	}

if ($continue_up)
	{
		$not_done=false;

        if (empty($_REQUEST['filefac_log']) || empty($_REQUEST['filefac_pass'])){
            echo "<b><center>Empty login/pass. Use <span style='color:red'>FREE</span> Filefactory Upload.</center></b>\n";
            $mem=false;
        }else{
?>
<div id=login width=100% align=center>Login to filefactory</div>
<?php
            $mem=true;
            $post['email']=str_replace("@","%40",$_REQUEST['filefac_log']);
            $post['password'] = $_REQUEST['filefac_pass'];
            $post['login']='Log+In';
            $page = geturl("www.filefactory.com",80,"/index.php","http://www.filefactory.com/",0,$post);
            is_page($page);
?>
<script>document.getElementById('login').style.display='none';</script>
<?php
            if (strpos($page,"Logout?")){
                echo "<b><center>Use <span style='color:green'>Member/Premium</span> Filefactory Account.</center></b>\n";
                $cookie = GetCookies($page, true);
                $cook = @implode("; ",$cookie);
            }else {
                echo "<b><center>Error login to filefactory. Use <span style='color:red'>FREE</span> Filefactory Account.</center></b>\n";
                $mem=false;
            }
        }
?>

<table width=600 align=center>
</td></tr>
<tr><td align=center>
<div id=info width=100% align=center>Retrive upload ID</div>
<?php

                $page = geturl("www.filefactory.com", 80, "/upload/single.php", "http://www.filefactory.com/", $cook?$cook:0, 0, 0, "");
			    is_page($page);
			//print_r($cook);
			$tmp = cut_str($page,'<iframe name="top','>'); // ппц, не пойму этой логики, ну впрочем как и € твоей!!!
			$act = cut_str($tmp,'src="','"');  // к тому же криво работает
            $url = parse_url($act."?action=form");			
			//print_r($url);
            #preg_match('/http:\/\/dl\d*\.filefactory.com\/upload_iframe\.php/',$page,$m); вот это логика!!! вообще не пашет, регул€рки надо более м€гкие
            //$url = parse_url($m[0]."?action=form");
			if (!$url){
					html_error("Error retrive upload id".pre($page));
			}
			$page = geturl($url['host'], 80, "/upload_iframe.php?action=form", 0, $cook?$cook:0, 0, 0, "");
			is_page($page);

			unset($post);
			$post["UPLOAD_METTER_ID"]=mktime();
			$post["submit"]="Upload File";
			$post["rec_email"]="";
			$post["description"]=$descript;

?>
	<script>document.getElementById('info').style.display='none';</script>
<?

			$upfiles=upfile($url["host"],defport($url), $url["path"].($url["query"] ? "?".$url["query"] : ""),"http://www.filefactory.com/", $cook?$cook:0, $post, $lfile, $lname, "file");

?>
<script>document.getElementById('progressblock').style.display='none';</script>
<?
			is_page($upfiles);
			is_present($upfiles,"Could not create location to store new file","FF error: Could not create location to store new file!");
			is_notpresent($upfiles,'location.href=','File not received'."<!--".$upfiles."-->");
			
			$final_url=cut_str($upfiles,"location.href='","'</script>");
			if (!$final_url)
				{
					html_error('File not received'."<!--".$upfiles."-->");
				}
			$download_link=str_replace('/upc/','/file/',$final_url);

// Upload plug'in from Director Of Zoo ( ru-board member <kamyshew>). I Hate rapidkill!!! Checkmate Use Google, my friend !!! No plagiarism!!!
     }
?>