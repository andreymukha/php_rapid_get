<?php
# Upload plug'in from Director Of Zoo (member ru-board <kamyshew>)
$not_done=true;
$continue_up=false;
if ($_REQUEST['action'] == "FORM")
    $continue_up=true;
else{
?>
<table border=1 style="width:270px;" cellspacing=0 align=center>
<form method=post>
<input type=hidden name=action value='FORM'><input type=hidden value=uploaded value'<?php $_REQUEST[uploaded]?>'>
<input type=hidden name=filename value='<?php echo base64_encode($_REQUEST[filename]); ?>'>
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
            echo "<b><center>Error login to filefactory. Use <span style='color:red'>FREE</span> Filefactory Account.</center></b>\n";
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
            #print_r($page);
            is_page($page);
?>
<script>document.getElementById('login').style.display='none';</script>
<?php
            if (strpos($page,"Logout?")){
                echo "<b><center>Use Filefactory Account.</center></b>\n";
                $cookie = GetCookies($page);
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
            #if ($mem===false){
                $page = geturl("www.filefactory.com", 80, "/index_singleupload.php", "http://www.filefactory.com/", $cook, 0, 0, "");			
			    is_page($page);
            #}
			$tmp =cut_str($page,'<iframe','/iframe'); 
			$url_action = cut_str($tmp,'src="','"');
			$url = parse_url($url_action."?action=form");
			$page = geturl($url['host'], 80, "/upload_iframe.php?action=form", 0, isset($cook)?$cook:0, 0, 0, "");
			is_page($page);
			
			if (!$url_action)
				{	
					html_error("Error retrive upload id".pre($page));
				}
			unset($post);
			$post["submit"]="Upload File";
			$post["rec_email"]="";
			$post["sender_email"]="";
			$post["crlf"]="";
			$post["description"]=$descript;

?>
	<script>document.getElementById('info').style.display='none';</script>
<?			
			
			$upfiles=upfile($url["host"],defport($url), $url["path"].($url["query"] ? "?".$url["query"] : ""),"http://www.filefactory.com/", isset($cook)?$cook:0, $post, $lfile, $lname, "file");

?>
<script>document.getElementById('progressblock').style.display='none';</script>
<?		
			is_page($upfiles);
			is_notpresent($upfiles,'location.href=','File not received');
			
			$final_url=cut_str($upfiles,"<script>window.top.location.href='","'</script>");
			if (!$final_url)
				{
					html_error('File not received');
				}				
			$download_link=str_replace('/upc/','/file/',$final_url);

// Upload plug'in from Director Of Zoo (member ru-board <kamyshew>)
     } 
?>