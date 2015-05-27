<table width=600 align=center>
</td></tr>
<tr><td align=center>
<div id=info width=100% align=center>Retrive upload ID</div>
<?			
			$page = geturl("fileho.com", 80, "/", "", 0, 0, 0, "");
			is_page($page);
			$sid= rand(1,10000).'0'.rand(1,10000);
			$post['sessionid']=$sid;
			$post['accept']='ON';
			$post['spam']='ON';
			$post['MAX_FILE_SIZE']=1200000000;
			$post['tmpl_name']='';
			$post['css_name']='';
			$post['uid']=123;
			$post['upload_a']='Upload';
			$post['published']=1;
			$post['owner']="";
			$post['self_desc']=$descript;
			$tmp = cut_str($page,'form-data" action="','"');
			if (empty($tmp)) html_error("error retrive identifier seed!");
			$url = parse_url($tmp.$sid);
			
			
?>
<script>document.getElementById('info').style.display='none';</script>
    
<table width=600 align=center>
</td></tr>
<tr><td align=center>
<?
			$upfiles=upfile($url['host'],80,$url['path']."?".$url["query"],"http://fileho.com", 0, $post, $lfile, $lname, "myfile");
			is_page($upfiles);
?>
<script>document.getElementById('progressblock').style.display='none';</script>
<div id=final width=100% align=center>Get final code</div>
<? 
			$post['myfile']=$lname;
			$post['myfile_status']=cut_str($upfiles,"'myfile_status'>","</");
			$post['target_dir']='%2Fdata%2Ftemp%2F';
			
			$page = geturl($url['host'],80, "/upload.php",$tmp.$sid,0,$post,0, 0, "");			
			is_page($page);
			#print_r($page);
			$location = trim(cut_str($page,"http://","\n"));
			if (empty($location)) html_error("Failed to upload file!");
			$url = parse_url("http://".$location);
			unset($page);
			$page = geturl($url['host'],80,$url['path']."?".$url["query"],$tmp.$sid);
			
			is_page($page);	
			$tmp=cut_str($page,"http://fileho.com/download/",'">');
			if (!$tmp) html_error("Error retrive download link");
			$download_link = "http://fileho.com/download/".$tmp;
			$delete_link = "http://fileho.com/download/delete".cut_str($page,'http://fileho.com/download/delete','">');	
?>
<script>document.getElementById('final').style.display='none';</script>
