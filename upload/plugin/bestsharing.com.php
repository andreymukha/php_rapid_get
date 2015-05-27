<table width=600 align=center>
</td></tr>
<tr><td align=center>
	
<div id=info width=100% align=center>Retrive upload ID</div>
<?
			$page = geturl("www.bestsharing.com", 80, "/pageup/upload.js", "http://www.bestsharing.com/", 0, 0, 0, "");
?>
	<script>document.getElementById('info').style.display='none';</script>
<?
			is_page($page);
			
			$url_action=trim(cut_str($page,'action="','"'));
			#echo $url_action;
			if (!$url_action)
				{	
					html_error("Error retrive upload id".pre($page));
				}

			$post["LANG"]="en";
			$post["Upload!"]="UPLOAD!";
			
			$url=parse_url($url_action);

			$upfiles=upfile($url["host"],$url["port"] ? $url["port"] : 80, $url["path"].($url["query"] ? "?".$url["query"] : ""),$url_action, 0, $post, $lfile, $lname, "FILENAME");
?>
<script>document.getElementById('progressblock').style.display='none';</script>
<?		
			is_page($upfiles);
			#print_r($upfiles);
			is_notpresent($upfiles,"Location","File not uploaded");
			
			$spage_url=trim(cut_str($upfiles,"Location:","\n"));
			if (!$spage_url)
				{	
					html_error("Error retrive second page");
				}

			$url=parse_url($spage_url);
			$spage=$page = geturl($url["host"],$url["port"] ? $url["port"] : 80, $url["path"].($url["query"] ? "?".$url["query"] : ""),$url_action, 0, 0, 0, "");
			is_page($spage);
			
			is_notpresent($spage,"Your download page link","File not uploaded");
			$tmp=cut_str($spage,'<input id="downlink',">");
			$download_link=trim(cut_str($tmp,'value="','"'));
			
			$tmp=cut_str($spage,'<input id="dellink',">");
			$delete_link=trim(cut_str($tmp,'value="','"'));
			
			
			$spage_url='http://'.$url["host"].'/cgi/update_file_info.cgi';
			$delete_id=cut_str($spage,'<input name="delete_id" type="hidden" value="','"');
			$post2["description"]=$descript;
			$post2["delete_id"]=$delete_id;

			$url=parse_url($spage_url);
			$spage=$page = geturl($url["host"],$url["port"] ? $url["port"] : 80, $url["path"].($url["query"] ? "?".$url["query"] : ""),$url_action, 0, $post2, 0, "");
		
?>