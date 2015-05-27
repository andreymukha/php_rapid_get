<?
$not_done=true;
$continue_up=false;
if ($_REQUEST['action'] == "FORM")
	{
		$uprapidlogin_com=$_REQUEST["rapidalogin_com"] ? $_REQUEST["rapidalogin_com"] : false ;
		$uprapidpass_com=$_REQUEST["rapidapassword_com"] ? $_REQUEST["rapidapassword_com"] : false ;
		
		$continue_up=true;
	}
		else
	{
		if ($uprapidlogin_com && $uprapidpass_com)
			{
				$continue_up=true;
			}
				else
			{
?>
<table border=1 style="width:250px;" cellspacing=0 align=center>
<form method=post>
<input type=hidden name=action value='FORM'><input type=hidden value=uploaded value'<?php $_REQUEST[uploaded]?>'>
<input type=hidden name=filename value='<? echo base64_encode($_REQUEST[filename]); ?>'>
<tr><td nowrap>&nbsp;ID<td>&nbsp;<input name=rapidalogin_com value='<?php echo ($_COOKIE["rrapidlogin_com"] ? $_COOKIE["rrapidlogin_com"] : ""); ?>' style="width:160px;">&nbsp;</tr>
<tr><td nowrap>&nbsp;Password<td>&nbsp;<input name=rapidapassword_com value='<?php echo ($_COOKIE["rrapidpass_com"] ? $_COOKIE["rrapidpass_com"] : ""); ?>' style="width:160px;">&nbsp;</tr>
<tr><td colspan=2 align=center><input type=submit value='Upload'></tr>
</table>
</form>
<?php
			}
	}

if ($continue_up)
	{
		$not_done=false;
?>
<div id=info width=100% align=center>Retrive upload ID</div>
<?
		$page = geturl("rapidshare.com", 80, "/", 0, 0, 0, 0, "");
?>
	<script>document.getElementById('info').style.display='none';</script>
<?
		is_page($page);
		$upload_form="<iframe".cut_str($page,"<iframe","</form>")."</form>";
		$url_action=cut_str($page,"onclick=\"document.ul.action='","'\">");
		
		if (!$url_action) html_error('Error retrive new upload ID');
?>
<table width=600 align=center>
</td></tr>
<tr><td align=center>
<?
			
				flush();
				$post["u"]="Uploading...";
				$post["mirror"]=$url_action;
                
				if ($uprapidlogin_com && $uprapidpass_com){
				    $post["login"]=$uprapidlogin_com;
					$post["password"]=$uprapidpass_com;
								
					echo "<b><center>USE PREMIUM ACCOUNT.</center></b>\n";
				}else{
				    echo "<b><center>Login or Password not entered. <br />USE FREE ACCOUNT.</center></b>\n";
				}
                
                				$url=parse_url($url_action);
		
				$upfiles=upfile($url["host"],$url["port"] ? $url["port"] : 80, $url["path"].($url["query"] ? "?".$url["query"] : ""),"http://rapidshare.com/", 0, $post, $lfile, $lname, "filecontent");
	
?>
<script>document.getElementById('progressblock').style.display='none';</script>
<?		
				if ($lastError)
					{
						html_error($lastError);
					}
					
				$tmp=cut_str($upfiles,'Download-Link','</a>');
				$download_link=trim(cut_str($tmp,'<a href="','"'));
				
				$tmp=cut_str($upfiles,'Delete-Link','</a>');
				$delete_link=trim(cut_str($tmp,'<a href="','"'));
                
                if (!strstr($upfiles,'uploaded'))
					{
						$alert="<script>alert('".cut_str($upfiles,"<script>alert('","')</script>")."')</script>";
                        if ($uprapidlogin_com && $uprapidpass_com) 
							{
								die("<center><b>See URL in you rapidshare Premium page.</b></center>\n</td></tr></table><p><center>DONE</center>\n</html>");
							}
						
						echo $alert;
					}
	}
?>