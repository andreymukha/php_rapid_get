<table width=600 align=center>
</td></tr>
<tr><td align=center>
<div id=info width=100% align=center>Retrive upload ID</div>
<?
			$page = geturl("up-file.com", 80, "/index1.php", "", 0, 0, 0, "");
?>
	<script>document.getElementById('info').style.display='none';</script>
<?
			is_page($page);
			
			$fl_array = array_pop(preg_grep('/enctype="multipart\/form-data"/', explode("\n",$page)));
			$action_url=trim(cut_str($fl_array,'action="','"'));
			
			$UID = rand(1,10000).'0'.rand(1,10000);
			
			$action_url.=$UID;
			
			$post["sessionid"]=$UID;
			$post["css_name"]="";
			$post["MAX_FILE_SIZE"]="1200000000";
			$post["uid"]="123";
			$post["terms_a"]="0";
			$post["upload_a"]="Upload";
			$post["self_desc"]=$descript;
			
			$url=parse_url($action_url);
			$upfiles=upfile($url["host"],$url["port"] ? $url["port"] : 80, $url["path"].($url["query"] ? "?".$url["query"] : ""),"http://up-file.com", 0, $post, $lfile, $lname, "myfile");
			
?>
<script>document.getElementById('progressblock').style.display='none';</script>
<!-- (c) Autor - Director Of Zoo 2007 -->
<?
			
			is_page($upfiles);
            #print_r($upfiles);
			is_notpresent($upfiles,"<textarea name='myfile_status'>OK","File not upload");
			
			unset($post);
			$post["sessionid"]=cut_str($upfiles,"<textarea name='sessionid'>","</textarea>");
			$post["css_name"]="";
			$post["MAX_FILE_SIZE"]=cut_str($upfiles,"<textarea name='MAX_FILE_SIZE'>","</textarea>");
			$post["uid"]=cut_str($upfiles,"<textarea name='uid'>","</textarea>");
			$post["terms_a"]=cut_str($upfiles,"<textarea name='terms_a'>","</textarea>");
			$post["upload_a"]=cut_str($upfiles,"<textarea name='upload_a'>","</textarea>");
			$post["self_desc"]=$descript;
			$post["myfile"]=$lname;
			$post["myfile_status"]=cut_str($upfiles,"<textarea name='myfile_status'>","</textarea>");
			$post["target_dir"]=trim(cut_str($upfiles,"<textarea name='target_dir'>","</textarea>"));
			
			if(!$post["target_dir"]) html_error("File not upload");
			
			$final_url=trim(cut_str($upfiles,"action='","'"));
			
?>
<div id=info2 width=100% align=center><b>Get finaly file code</b></div>
<?

			$url=parse_url($final_url);
			$spage = geturl($url["host"],$url["port"] ? $url["port"] : 80, $url["path"].($url["query"] ? "?".$url["query"] : ""),$action_url, 0, $post, 0, "");

			is_page($spage);
?>
	<script>document.getElementById('info2').style.display='none';</script>
<?
			is_present($spage,'Failed to upload file.');
			$finish_url=trim(cut_str($spage,'Location:',"\n"));
			
			if(!$finish_url) html_error("File not upload");
			
			$url=parse_url($finish_url);
			$spage = geturl($url["host"],$url["port"] ? $url["port"] : 80, $url["path"].($url["query"] ? "?".$url["query"] : ""),$action_url, 0, $post, 0, "");
			is_page($spage);
			
			
			$tmp=cut_str($spage,'Your Download','</a>');
			$download_link=trim(cut_str($tmp,'<a href="','"'));
			
			$tmp=cut_str($spage,'Your secret Delete Link','</a>');
			$delete_link=trim(cut_str($tmp,'<a href="','"'));
?>