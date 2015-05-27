<table width=600 align=center>
</td></tr>
<tr><td align=center>
<div id=info width=100% align=center>Retrive upload ID</div>
<?
			$page = geturl("www.sharing.ru", 80, "/", "", 0, 0, 0, "");
?>
	<script>document.getElementById('info').style.display='none';</script>
<?			
			is_page($page);
			is_notpresent($page,'UPLOAD_IDENTIFIER=','Upload ID not received');
			
			$post["UPLOAD_IDENTIFIER"]=cut_str($page,'UPLOAD_IDENTIFIER=','"');
			$post["name"]=$lname;
			$post["rules"]=1;
			
			$upfiles=upfile("www.sharing.ru",80, "/upload/index.php" ,"http://www.sharing.ru", 0, $post, $lfile, $lname, "file");

?>
<script>document.getElementById('progressblock').style.display='none';</script>
<?
			is_page($upfiles);
			is_notpresent($upfiles,'закачан и Скачать его можно по адресу','File not uploaded');
			
			$download_link=trim(cut_str($upfiles,"<a href='","'"));
?>