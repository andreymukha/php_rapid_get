<table width=600 align=center>
</td></tr>
<tr><td align=center>
	
<div id=info width=100% align=center>Retrive upload ID</div>
<?php
			$page = geturl("files.to", 80, "/upload.php");

			is_page($page);
            
            $action_url = cut_str($page,'form-data" action="','"');
            if (!$action_url) html_error("Error Get Upload Id / Ошибка получения идентификатора");
            
            $post['UPLOAD_IDENTIFIER']=cut_str($page,'UPLOAD_IDENTIFIER" value="','"');
            $post['MAX_FILE_SIZE']=104857600;
            $post['description']=$descript;
            $post['email'] = '';
            $post['agree'] = 'on';
            $post['sessionid'] = ''; 

            $url = parse_url("http://files.to/".$action_url);         


?>
	<script>document.getElementById('info').style.display='none';</script>
<?php			
			
			$upfiles=upfile($url["host"],defport($url), $url["path"].($url["query"] ? "?".$url["query"] : ""),"http://files.to/", 0, $post, $lfile, $lname, "file[0]");

?>
<script>document.getElementById('progressblock').style.display='none';</script>
<?php		
			is_page($upfiles);
            #print_r("<!--".$upfiles."-->");
			is_notpresent($upfiles,'uploaded successfully','File not received / Ошибка аплоада файла!');            
            
            $tmp = cut_str($upfiles,"http://files.to/get/",'"');
            if (!$tmp)html_error("Error Get Download Link / Ошибка получения ссылки на скачку");
            $download_link = "http://files.to/get/".$tmp;
            $tmp2= cut_str($upfiles,"http://files.to/delete/",'"');
            if($tmp) $delete_link = "http://files.to/delete/".$tmp2;

            // Upload plug'in from Director Of Zoo (member ru-board <kamyshew>). Only for Rapid Get script. 2007. No Rapidkill!!!!!!!!!!!!!
?>