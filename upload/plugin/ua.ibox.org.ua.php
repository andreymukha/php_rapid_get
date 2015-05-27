<?php
$not_done=true;
$continue_up=false;
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
<tr><td nowrap>&nbsp;User ID<td>&nbsp;<input name=ibox_org_ua_userid value='' style="width:160px;">&nbsp;</tr>
<tr><td nowrap>&nbsp;Folder name<td>&nbsp;<input name=ibox_org_ua_name value='' style="width:160px;">&nbsp;</tr>
<tr><td nowrap>&nbsp;Folder ID<td>&nbsp;<input name=ibox_org_ua_fid value='' style="width:160px;">&nbsp;</tr>
<tr><td nowrap>&nbsp;Folder pass<td>&nbsp;<input name=ibox_org_ua_fpass value='' style="width:160px;">&nbsp;</tr>
<tr><td colspan=2 align=center><input type=submit value='Upload'></tr>
</table>
</form>
<?php
	}

if ($continue_up)
	{
		$not_done=false;
		if (!$_REQUEST["ibox_org_ua_name"] && (!$_REQUEST["ibox_org_ua_fid"] || !$_REQUEST["ibox_org_ua_fpass"]))
			{
				html_error("Not entered box name");
			}
?>
<table width=600 align=center>
</td></tr>
<tr><td align=center>
<div id=info width=100% align=center>Retrive upload ID</div>
<?
			$user_id=$_REQUEST["ibox_org_ua_userid"];
			$cookie_=($user_id) ? "uid=$user_id; expires=11 Oct 2020 14:09:18 GMT; path=/; domain=.ibox.org.ua" : 0; 
			$page = geturl("ua.ibox.org.ua", 80, "/create/", "", $cookie_, 0, 0, "");
?>
	<script>document.getElementById('info').style.display='none';</script>
<?			
			is_page($page);
			
			$cook=GetCookies($page);
			$user_id=cut_str($page,'Set-Cookie: uid=',';');

			if ($_REQUEST["ibox_org_ua_fid"] && $_REQUEST["ibox_org_ua_fpass"])
				{
					$post["id"]=$_REQUEST[ibox_org_ua_fid];
					$post["pass"]=$_REQUEST[ibox_org_ua_fpass];
				}
					else
				{
					$post["header"]=$_REQUEST["ibox_org_ua_name"];
					$post["description"]=$descript;
					$post["tou"]="";
					$post["fromu"]="";
					$post["quest"]="";
					$post["answ"]="";
				}
			
			$upfiles=upfile("ua.ibox.org.ua",80, "/create/" ,"http://ua.ibox.org.ua/", $cook, $post, $lfile, $lname, "file");
			
?>
<script>document.getElementById('progressblock').style.display='none';</script>
<?
			is_page($upfiles);
			if (strstr($upfiles,'—сылка на созданную коробку:'))
				{
					$ready=true;
					$download_link=cut_str(strstr($upfiles,'—сылка на созданную коробку:'),'a href="','"');
					$access_pass=cut_str($upfiles,'name="pass" value="','"');
			
					$ftp_uplink=cut_str($upfiles,'<a href="ftp://','"');
					$ftp_uplink = $ftp_uplink ? 'ftp://'.$ftp_uplink : "";
				}
				
			if (!$ready)
				{
					echo $upfiles;
					html_error('Not Uploaded');
				}  			  
	}
?>