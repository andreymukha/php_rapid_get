<table width=600 align=center>
</td></tr>
<tr><td align=center>
    
<div id=info width=100% align=center>Retrive upload ID</div>
<?php
            $page = geturl("fileshare.in.ua", 80, "/", 0, 0, 0, 0, "");
?>
    <script>document.getElementById('info').style.display='none';</script>
<?php
            is_page($page);
            $url_action="http://wpr1.fileshare.in.ua";
            $url = parse_url($url_action);
            $post['MAX_FILE_SIZE']="2097152000";
            $post['private'] = 1;
            $post['terms'] = 1;
            $post['source'] = 'local';
            $post['description'] = $descript;
            $post['tag'] = '';
			$post['mirror'] = "pr1";
		
            $tmp = cut_str($page,'name="UPLOAD_IDENTIFIER" value="','"');
		$post['UPLOAD_IDENTIFIER']=$tmp;
            if (empty($tmp)) html_error("Error Get Hash! / Ошибка получения хеша!");
            
        //    $upfiles=upfile($url["host"], defport($url), $url["path"].($url["query"] ? "?".$url["query"] : ""),"http://fileshare.in.ua/", 0, $post, $lfile, $lname, "my_file");
            //$upfiles=upfile("file2.uafile.com",8080, "/upload.php" ,"http://file2.uafile.com", 0, $post, $lfile, $lname, "upfile");
			  $upfiles=upfile("wpr1.fileshare.in.ua",8080, "/" ,"fileshare.in.ua", 0, $post, $lfile, $lname, "my_file");

?>
<script>document.getElementById('progressblock').style.display='none';</script>
<div id=info2 width=100% align=center>Get links</div>
<?        
            is_page($upfiles);
            //is_notpresent($upfiles,"Location","Error Upload File / Ошибка загрузки файла!");
  		
            $tmp = cut_str($upfiles,"Location: ","\r\n");
            //if(empty($tmp))html_error("Error Upload File / Ошибка загрузки файла!");
            
            $url = parse_url($tmp);
            $page=geturl($url["host"],defport($url),$url['path']."?".$url["query"],"http://fileshare.in.ua/",0,0,0,0,0);            
            is_page($page);
            unset($post);
            $post['description'] = $descript;
            $post['type'] = 'other';
            $post['tag'] = '';
            $post['submit'] = "Далее >>";
            $page=geturl($url["host"],defport($url),$url['path']."?".$url["query"],"http://fileshare.in.ua/",0,$post);
            //echo $page;
	    is_page($page);
            is_notpresent($page,"Файл успешно загружен","Error upload file! / Ошибка загрузки файла!");
            $tmp = cut_str($page,"Ссылка на скачивание","readonly");
            $download_link = cut_str($tmp,'value="','"');
                      
            // Upload plug'in написан Директором Зоопарка (ru-board member kamyshew). Only for Rapidget Pro!!! Rapidkill - отстой!!! Нет плагиату!!!
?>
<script>document.getElementById('info2').style.display='none';</script> 