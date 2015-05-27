<table width=600 align=center>
</td></tr>
<tr><td align=center>
<?
				$post["submit"]="Sending";
				$post["mirror"]=$url_action;
	
				$url=parse_url('http://zalil.ru/upload/');
		
				$upfiles=upfile($url["host"],$url["port"] ? $url["port"] : 80, $url["path"].($url["query"] ? "?".$url["query"] : "") ,"http://zalil.ru/", 0, $post, $lfile, $lname, "file");

?>
<script>document.getElementById('progressblock').style.display='none';</script>
<?		
				is_notpresent($upfiles,"Location","File not uploaded");
				$newlink=trim(cut_str($upfiles,"Location:","\n"));
				$download_link="http://zalil.ru/".array_pop(explode("/",$newlink));
?>