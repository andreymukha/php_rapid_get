<table width=600 align=center>
</td></tr>
<tr><td align=center>
<div id=info width=100% align=center>Retrive upload ID</div>
<?
			$page = geturl("www.filesend.net", 80, "/", "", 0, 0, 0, "");
?>
	<script>document.getElementById('info').style.display='none';</script>
<?
			$sid=cut_str($page,"sid=",'"');
			
			if (!$sid)
				{
					html_error("Error retrive upload id".$page);
				}
				
			$action_url="http://dl.filesend.net/cgi-bin/upload.cgi?tmp_sid=$sid";
			$finish_url="http://dl.filesend.net/upload_finished.php?tmp_sid=$sid";

			$url=parse_url($action_url);
			
			$post["MAX_FILE_SIZE"]="262144000";
			$post["confirm"]="1";
			$post["imbedded_progress_bar"]="1";
			$post["upload_range"]="1";
			
			$upfiles=upfile($url["host"],$url["port"] ? $url["port"] : 80, $url["path"].($url["query"] ? "?".$url["query"] : ""),"http://www.filesend.net", 0, $post, $lfile, $lname, "upfile_0");
?>
<script>document.getElementById('progressblock').style.display='none';</script>
<?
			is_page($upfiles);
					
			if (!strstr($upfiles,$finish_url)) html_error('File not received');
			
?>
<div id=info2 width=100% align=center><b>Get finaly file code</b></div>
<?
			$url=parse_url($finish_url);
			$spage = geturl($url["host"],$url["port"] ? $url["port"] : 80, $url["path"].($url["query"] ? "?".$url["query"] : ""),$action_url, 0, 0, 0, "");
			is_page($spage);
			
			if (strstr($spage,'Location:'))
				{	
					$finish_url2=trim(cut_str($spage,'Location:',"\n"));
					$url=parse_url($finish_url2);
					$spage = geturl($url["host"],$url["port"] ? $url["port"] : 80, $url["path"].($url["query"] ? "?".$url["query"] : ""),$finish_url, 0, 0, 0, "");
					is_page($spage);
				}
?>
	<script>document.getElementById('info2').style.display='none';</script>
<?
			
			$download_link=trim(cut_str($spage,'Download Link: <a href="','"'));
			$delete_link=trim(cut_str($spage,'Delete Link: <a href="','"'));
?>