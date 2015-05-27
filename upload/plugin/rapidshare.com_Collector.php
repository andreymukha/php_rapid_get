<?php
$not_done=true;
$continue_up=false;
if ($_REQUEST['action'] == "FORM")
	{
		$rapidlogin_coll=$_REQUEST["rapidalogin_coll"] ? $_REQUEST["rapidalogin_coll"] : false ;
		$rapidpass_coll=$_REQUEST["rapidapassword_coll"] ? $_REQUEST["rapidapassword_coll"] : false ;
		
		$continue_up=true;
	}
		else
	{
?>
<table border=1 style="width:270px;" cellspacing=0 align=center>
<form method=post>
<input type=hidden name=action value='FORM'><input type=hidden value=uploaded value'<?php $_REQUEST[uploaded]?>'>
<input type=hidden name=filename value='<? echo base64_encode($_REQUEST[filename]); ?>'>
<tr><td nowrap>&nbsp;Collector ID<td>&nbsp;<input name=rapidalogin_coll value='' style="width:160px;">&nbsp;</tr>
<tr><td nowrap>&nbsp;Password<td>&nbsp;<input name=rapidapassword_coll value='' style="width:160px;">&nbsp;</tr>
<tr><td colspan=2 align=center><input type=submit value='Upload'></tr>
</table>
</form>
<?php
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
		
				if (!$url_action)
					{	
						html_error('Error retrive new upload ID');
					}
				
?>
<table width=600 align=center>
</td></tr>
<tr><td align=center>
<?
			
				flush();
				$post["u"]="Uploading...";
				$post["mirror"]=$url_action;
                
				if ($rapidlogin_coll && $rapidpass_coll){
			        $post["freeaccountid"]=$rapidlogin_coll;
					$post["password"]=$rapidpass_coll;
					echo "<b><center>USE COLLECTOR ZONE ACCOUNT.</center></b>\n";
				}else{
			        echo "<b><center>Login or Password not entered.</center></b>\n";
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
                
                if (!strstr($upfiles,'uploaded')){
                    $alert="<script>alert('".cut_str($upfiles,"<script>alert('","')</script>")."')</script>"; 
				    if ($rapidlogin_coll && $rapidpass_coll){
					    die("<center><b>See URL in you rapidshare Collector's zone page.</b><br />USE FREE ACCOUNT.</center>\n");
					}
				    echo $alert;
				}
	}
?>