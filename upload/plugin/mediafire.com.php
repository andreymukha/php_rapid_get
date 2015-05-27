<table width=600 align=center>
</td></tr>
<tr><td align=center>
<div id=info width=100% align=center>Retrive upload ID</div>
<?			
			$page = geturl("www.mediafire.com", 80, "/");
			is_page($page);
			$identifier_seed = cut_str($page,"identifier_seed = '","'");
			if (empty($identifier_seed)) html_error("error retrive identifier seed!");
						
?>
<script>document.getElementById('info').style.display='none';</script>
    
<table width=600 align=center>
</td></tr>
<tr><td align=center>
<?
			$post['test']=1;
			$upfiles=upfile("upload.mediafire.com",80,"/".$identifier_seed."p","http://www.mediafire.com", 0, $post, $lfile, $lname, "file_name0");
			is_page($upfiles);
?>
<script>document.getElementById('progressblock').style.display='none';</script>
<div id=final width=100% align=center>Get final code</div>
<? 			
			$location = cut_str($upfiles,"url=",'">');
			
			$id = cut_str($location,"id=","&did");
			if (!$id) html_error("Error retrive final id");
			$error=true;
			for ($i=1;$i<10;$i++){
				sleep(5);
				$page = geturl("www.mediafire.com",80,"/dynamic/verifystatus.php?identifier=".$id,"http://www.mediafire.com");
				is_page($page);
				if (!stristr($page,"Waiting") && !stristr($page,"All done")){
					echo $page;
					html_error("Error verification file!");					
				}
				if (strstr($page,"All done")){$error = false; break;}
			}
			if ($error == true) html_error("Error verification time out!");
			$links_up_file = cut_str($page,'upload_complete.php?','||');
			if (!$links_up_file){
				echo $page;
				html_error("Error retrive upload links!");
			}
			$page = geturl("www.mediafire.com",80,"/upload_complete.php?".$links_up_file,"http://www.mediafire.com");
			is_page($page);
			$tmp = cut_str($page,'http://www.mediafire.com/?','"');
			if (!$tmp) {
				echo $tmp;
				html_error("Error get download link!");
			}
			$download_link = 'http://www.mediafire.com/?'.$tmp;		

?>
<script>document.getElementById('final').style.display='none';</script>
<?