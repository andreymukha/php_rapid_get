<table width=600 align=center>
</td></tr>
<tr><td align=center>
<?
      
	$post['MAX_FILE_SIZE']="150000000";
      $post['name']=$lname;
	$post['description']=$descript;
      $post['section']=4;
	$post['pass']="";
	$post['type']="";
	$post['flag']="0";
      $post['submit']="Загрузить";
      
      $upfiles=upfile("www.file.oboz.ua",80, "/" ,"www.file.oboz.ua", 0, $post, $lfile, $lname, "video");
   
?>
<script>document.getElementById('progressblock').style.display='none';</script>
<?
		$tmp = cut_str($upfiles,'window.location.replace("index.php?flag=','"');
		if (empty($tmp)){
			print_r($upfiles);
			html_error("Error retrive download link!");
		}
	     $download_link='http://file.oboz.ua/download.php?fid='.$tmp;
?>