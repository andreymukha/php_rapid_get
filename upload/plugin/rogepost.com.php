<?
$post['MAX_FILE_SIZE']=134217728;
$post['submit']="Upload &raquo;";
?>			
<table width=600 align=center>
</td></tr>
<tr><td align=center>
<?			
			$upfiles=upfile("www.rogepost.com",80,"/","http://www.rogepost.com/", 0, $post, $lfile, $lname, "filename");
			
?>
<script>document.getElementById('progressblock').style.display='none';</script>
<?
			is_page($upfiles);
			is_notpresent($upfiles,"Congratulations!","Error upload file.");
			#print_r($upfiles);
			$tmp = cut_str($upfiles,"Download Link","</a>");			
			$download_link=cut_str($tmp,'href="','">');
			if (empty($download_link)) html_error("Error get download link!");
			#$download_link="http://www.rogepost.com/dn/".$download_link;
			$tmp = cut_str($upfiles,"Delete Link","/li>");
			$delete_link = cut_str($tmp,"http://www.rogepost.com/","<");
			$delete_link = "http://www.rogepost.com/".$delete_link;
?>