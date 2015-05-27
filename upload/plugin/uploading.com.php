<table width=600 align=center>
</td></tr>
<tr><td align=center>
<div id=info width=100% align=center>Retrive upload ID</div>
<?			
			$cook="setlang=en; unique=1";
			$page = geturl("www.uploading.com", 80, "/en/", 0, $cook);
			is_page($page);
			#echo $page;
			$action_url = cut_str($page,'eturn postIt();" action="','"');
			#echo 	$action_url;
			if (empty($action_url)) html_error("Error retrive action url!");
			$url = parse_url($action_url);
			#print_r($url);
			$UPLOAD_METTER_ID = cut_str($action_url,'umid=','"');
			#echo $UPLOAD_METTER_ID;
			$post['UPLOAD_METTER_ID']=$UPLOAD_METTER_ID;
			$post['message']=$descript;
			
?>
<script>document.getElementById('info').style.display='none';</script>
    
<table width=600 align=center>
</td></tr>
<tr><td align=center>
<?
			$upfiles=upfile($url['host'],80,$url['path']."?".$url["query"],"http://uploading.com/en/", 0, $post, $lfile, $lname, "file_p");
			is_page($upfiles);
?>
<script>document.getElementById('progressblock').style.display='none';</script>
<?			
			
			
			$download_link = cut_str($upfiles,"downloadurl = '","'");
			$delete_link = cut_str($upfiles,"filedelurl = '","'");
			  
?>