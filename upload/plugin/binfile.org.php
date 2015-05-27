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
<tr><td nowrap>&nbsp;Login<td>&nbsp;<input name=bin_login value='' style="width:160px;">&nbsp;</tr>
<tr><td nowrap>&nbsp;Password<td>&nbsp;<input name=bin_pass value='' style="width:160px;">&nbsp;</tr>
<tr><td colspan=2 align=center><input type=submit value='Upload'></tr>
</table>
</form>
<?php
	}

if ($continue_up)
	{
		$not_done=false;
		$login = trim($_REQUEST['bin_login']);
		$pass = trim($_REQUEST['bin_pass']);
            
			if (empty($login)){
            	html_error("Enter binfile login!");
			}
            
            if (empty($pass)){
            	html_error("Enter binfile password!");
            }
			
?> 
<table width=600 align=center> 
</td></tr> 
<tr><td align=center> 
<div id=login width=100% align=center>Login to Binfile.org</div> 
<? 
            $post["act"]='login';
            $post["user"]=$_REQUEST['bin_login'];
            $post["pass"]=$_REQUEST['bin_pass'];
            $post["login"]="Login+me+in";
            $post["autologin"]=1;
            $post["xxx"]="";
			
			$page = geturl("binfile.org",80,"/login.php","http://binfile.org",0,$post);
			
			is_notpresent($page,"location: http://binfile.org/members.php","Error login to binfile.org. Error password/login");
			
			$cookie = "PHPSESSID=".cut_str($page,'Cookie: PHPSESSID=',";")."; ";
			$cookie .= "yab_passhash=".cut_str($page,'Cookie: yab_passhash=',";")."; ";
			$cookie .= "yab_sess_id=".cut_str($page,'Cookie: yab_sess_id=',";")."; ";
			$cookie .= "yab_last_click=".cut_str($page,'Cookie: yab_last_click=',";")."; ";
			$cookie .= "yab_uid=7225; yab_logined=1; yab_autologin=1;";
?>
<script>document.getElementById('login').style.display='none';</script> 
<div id=info width=100% align=center>Retrive upload ID</div> 
<?
				
            $page = geturl("binfile.org",80,"/","http://binfile.org/member.php",$cookie);
            #echo $page;
 
            $temp = cut_str($page,"uploadform",">"); 
            $action_url = cut_str($temp,'action="','"'); 
            $url = parse_url($action_url); 
             
            $post["sessionid"]=cut_str($temp,"sid=","&"); 
            $post["UploadSession"] = $post["sessionid"]; 
            $post["AccessKey"] = cut_str($page,'AccessKey" value="','"'); 
            $post["maxfilesize"]= 104860000; 
            $post["phpuploadscript"]=cut_str($page,'phpuploadscript" value="','"'); 
            $post["returnurl"]=cut_str($page,'returnurl" value="','"'); 
            $post["uploadmode"]=1; 
             
?> 
<script>document.getElementById('info').style.display='none';</script> 
<? 
            $upfiles=upfile($url['host'],80,$url['path']."?".$url["query"],"http://binfile.org/", 0, $post, $lfile, $lname, "uploadfile_0"); 

?> 
<script>document.getElementById('progressblock').style.display='none';</script> 
<div id=info2 width=100% align=center>Get links</div> 
<?     
            is_page($upfiles); 

            $temp2 = trim(cut_str($upfiles,"Location: ","\n")); 
            $locat = parse_url($temp2); 
            sleep(5); 
            flush(); 
            $page2=geturl($locat["host"],80,$locat['path']."?".$locat["query"],"binfile.org",0,0,0,0,0); 
            $path="/emaillinks.php?UploadSession=".$post["UploadSession"]."&AccessKey=".$post["AccessKey"]."&uploadmode=1&submitnums=0&fromemail=&toemail="; 
         
            $page3=geturl("binfile.org",80,$path,"binfile.org",0,0,0,0,0); 
            is_page($page3); 
            $download_link = trim(cut_str($page3,'downloadurl">','<')); 
            $delete_link = trim(cut_str($page3,'filedel">','<')); 
?> 
<script>document.getElementById('info2').style.display='none';</script>
<? } ?>