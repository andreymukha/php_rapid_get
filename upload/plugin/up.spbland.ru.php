<table width=600 align=center>
</td></tr>
<tr><td align=center>
<?
				$post["des"]=$descript;
	
				$url=parse_url('http://up.spbland.ru/');
		
				$upfiles=upfile($url["host"],defport($url), $url["path"].($url["query"] ? "?".$url["query"] : "") ,"http://zalil.ru/", 0, $post, $lfile, $lname, "fup");

?>
<script>document.getElementById('progressblock').style.display='none';</script>
<?		
				 is_page($upfiles);
                 is_notpresent($upfiles,"успешно","File not uploaded / Ошибка загрузки файла!");
				 $tmp = cut_str($upfiles,"http://up.spbland.ru/files/",'">');
                 if(!$tmp) html_error("Error upload file / Ошибка загрузки файла!");
				 $download_link="http://up.spbland.ru/files/".$tmp;
                 
                 # Автор plug'in-a Director Of Zoo (ru-board member - kamyshew). Only for rapidget. No Rapidkill. Checkmate - kiss my ass!!!
?>