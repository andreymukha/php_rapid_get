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
						html_error('Error retrive new upload ID');
				
?>
<table width=600 align=center>
</td></tr>
<tr><td align=center>
<?
			
				flush();
				$post["u"]="Uploading...";
				$post["mirror"]=$url_action;

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
?>