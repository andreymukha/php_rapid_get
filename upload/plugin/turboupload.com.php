<table width=600 align=center>
</td></tr>
<tr><td align=center>
	
<div id=info width=100% align=center>Retrive upload ID</div>
<?
			$page = geturl("turboupload.com", 80, "/", 0, 0, 0, 0, "");
            $url_action=trim(cut_str($page,'action="','"'));
             
            
?>
	<script>document.getElementById('info').style.display='none';</script>
<?
#			is_page($page);
			
#			$upload_id=cut_str($page,'name="uploadp_id" value="','"');
			
			if (!$url_action)# || !$upload_id)
				{	
					html_error("Error retrive upload id");
				}
			
			#echo $url_action."\n".$page;
			#$url_action='http://turboupload.com/upload.tu?';
			
			$post["description"]=$descript;
			$post["terms"]="checkbox";
			$post["Submit"]="Upload File";
			
			$url=parse_url($url_action);
			$upfiles=upfile($url["host"],$url["port"] ? $url["port"] : 80, $url["path"].($url["query"] ? "?".$url["query"] : "") ,"http://turboupload.com", 0, $post, $lfile, $lname, "filetoupload");
?>
<script>document.getElementById('progressblock').style.display='none';</script>
<?		
			is_page($upfiles);
            #print_r($upfiles);
			is_notpresent($upfiles,"successfully uploaded","File not uploaded");
			
			$tmp=cut_str($upfiles,"<strong>Download-Link</strong>","</a>");
			$download_link=cut_str($tmp,'<a href="','"');
			
			$tmp=cut_str($upfiles,"<strong>Delete-Link</strong>","</a>");
			$delete_link=cut_str($tmp,'<a href="','"');
?>