<table width=600 align=center>
</td></tr>
<tr><td align=center>
<div id=info width=100% align=center>Retrive upload ID</div>
<?
			$page = geturl("www.shareua.com", 80, "/", 0, 0, 0, 0, "");
?>
	<script>document.getElementById('info').style.display='none';</script>
<?
			
			$sid=cut_str($page,"beginUpload('","');");
			if (empty($sid)) html_error("Error Retrive Upload ID");
			is_page($page);
			$url_action='http://shareua.com/cgi-bin/upload.cgi?sid='.$sid;
			$url=parse_url($url_action);

		//	$post["MAX_FILE_SIZE"]="60000000";
			$upfiles=upfile($url["host"],$url["port"] ? $url["port"] : 80, $url["path"].($url["query"] ? "?".$url["query"] : ""),"http://shareua.com/", 0, $post, $lfile, $lname, "file");

			$post['ft']='file';
			$post['sid']=$sid;
            $page = geturl("shareua.com", 80, "/", "", 0, $post);
		  //  echo $page;
	       // exit;
			
?>
<script>document.getElementById('progressblock').style.display='none';</script>
<?		
			 $tmp = trim(cut_str($page,'<a href="http://shareua.com/file/','"'));
	         if (empty($tmp)){
				print_r($upfiles);
				print_r("\n*******\n");
				print_r($page);
				html_error("Error retrive download link!");
			}	
		$download_link = 'http://shareua.com/files/'.$tmp;
?>