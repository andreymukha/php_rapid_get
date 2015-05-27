<table width=600 align=center>
</td></tr>
<tr><td align=center>
<?

      $post['name']=$lname;
      $post['description']=$descript;
      $post['operation']="1";
      $post['agreecheck']="1";
      $post['submit']="Закачать файл!";
      
      $upfiles=upfile("www.filestore.com.ua",80, "/upload.php" ,"www.filestore.com.ua", 0, $post, $lfile, $lname, "upfile");
   
?>
<script>document.getElementById('progressblock').style.display='none';</script>
<?

	$tmp = cut_str($upfiles,'Ссылка для скачивания:<br /><a href="','"');
	$tmp2 =  cut_str($upfiles,'Ссылка для удаления файла:<br /><a href="','"');
		if (empty($tmp)){
			print_r($upfiles);
			html_error("Error retrive download link!");
		}
	     $download_link=$tmp;
	     $delete_link = $tmp2;
?>