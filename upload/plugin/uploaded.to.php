<table width=600 align=center>
</td></tr>
<tr><td align=center>
	
<div id=info width=100% align=center>Retrive upload ID</div>
<?php
			$page = geturl("uploaded.to", 80, "/");
			
			is_page($page);
            
            $action_url = cut_str($page,'form-data" action="','"');
            if (!$action_url) html_error("Error Get Upload Id / Ошибка получения идентификатора");
			
            $sid= rand(1,10000).'0'.rand(1,10000);
            $post['css_name']='uploaded';
            $post['tmpl_name']='uploaded';
            $post['file_name2']='';
            $post['domain_id'] = 'uploaded.to';
            $post['lang'] = 'ENG';
            $post['inline'] = 'on';
 

            $url = parse_url($action_url.$sid);         


?>
	<script>document.getElementById('info').style.display='none';</script>
<?php			
			
			$upfiles=upfile($url["host"],defport($url), $url["path"].($url["query"] ? "?".$url["query"] : ""),"http://uploaded.to/", 0, $post, $lfile, $lname, "file1x");

?>
<script>document.getElementById('progressblock').style.display='none';</script>
<?php		
			is_page($upfiles);
            #print_r("<!--".$upfiles."-->");
			is_notpresent($upfiles,'action=','File not received / Ошибка аплоада файла!');
			
            unset($post);
            $post['upload_done']='1';
            $post['domain_id'] = 'uploaded.to';
            $post['lang'] = 'ENG';
            
            $action_url = cut_str($upfiles,'action="','"');
            if (!$action_url) html_error("File not received / Ошибка аплоада файла!");
            $url = parse_url($action_url);
            
            $page = geturl($url['host'],defport($url),$url['path']."?".$url["query"],"http://uploaded.to");
            is_page($page);
            is_notpresent($upfiles,'action=','File not received / Ошибка аплоада файла!'); 
            
            $post['upload_done']='1';
            $post['lang'] = 'ENG';
            $action_url = cut_str($page,'action="','"');            
            if (!$action_url) html_error("File not received / Ошибка аплоада файла!");
            $url = parse_url($action_url);
            
            $page = geturl($url['host'],defport($url),$url['path']."?".$url["query"],"http://uploaded.to");
            is_page($page);
            
            $download_link = cut_str($page,"fileuploader_fix='","<'"); 

            // Upload plug'in from Director Of Zoo (member ru-board <kamyshew>). Only for Rapid Get script 2007. No Rapidkill!!!!!!!!!!!!!
?>