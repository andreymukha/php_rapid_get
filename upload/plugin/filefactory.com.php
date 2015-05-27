<table width=600 align=center>
</td></tr>
<tr><td align=center>
	
<div id=info width=100% align=center>Retrive upload ID</div>
<?php
			$page = geturl("www.filefactory.com", 80, "/upload/single.php", "http://www.filefactory.com/", $cook, 0, 0, "");
			is_page($page);
			$tmp = cut_str($page,'<iframe name="top','>');
			$act = cut_str($tmp,'src="','"');
            $url = parse_url($act."?action=form");
			if (!$url){
				html_error("Error retrive upload id".pre($page));
			}
			$post["UPLOAD_METTER_ID"]=mktime();
			$post["submit"]="Upload File";
			$post["rec_email"]="";
			$post["description"]=$descript;

?>
	<script>document.getElementById('info').style.display='none';</script>
<?php			
			
			$upfiles=upfile($url["host"],defport($url), $url["path"].($url["query"] ? "?".$url["query"] : ""),"http://www.filefactory.com/", 0, $post, $lfile, $lname, "file");

?>
<script>document.getElementById('progressblock').style.display='none';</script>
<?php		
			is_page($upfiles);
			is_notpresent($upfiles,'location.href=','File not received');
			
			$final_url=cut_str($upfiles,"location.href='","'</script>");
			if (!$final_url)
				{
					html_error('File not received');
				}				
			$download_link=str_replace('/upc/','/file/',$final_url);
?>