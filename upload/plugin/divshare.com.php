<table width=600 align=center>
</td></tr>
<tr><td align=center>
<div id=info width=100% align=center>Retrive upload ID</div>
<?php
			$page = geturl('www.divshare.com',80,'/upload');
            $action_url = cut_str($page,'form-data" action="','"');
?>
<script>document.getElementById('info').style.display='none';</script>  
<?php            
            if(empty($action_url)) html_error("error retrive 'url action'/Ошибка получения адреса аплоада!".$page);         
            
			$post["email_to"]="julie@gmail.com, patrick@aol.com";
			$post["gallery_title"]="";
            $post["terms"]="on";
            $post["upload_submit"]="Upload >";
            $post["description[0]"]=$descript;           
            
			$url=parse_url("http://www.divshare.com".$action_url);
            #$url = parse_url("http://www.divshare.com/upload");
            #print_r($action_url);
			$upfiles=upfile($url["host"],defport($url), $url["path"].($url["query"] ? "?".$url["query"] : ""),"http://www.divshare.com/upload", 0, $post, $lfile, $lname, "file[0]");
?>
<script>document.getElementById('progressblock').style.display='none';</script>
<?php		
            is_page($upfiles);
 
            unset($post['upload_submit']);
            $post['upload_method'] = 'progress';
            preg_match('/[a-z0-9]{10,}/', $action_url, $regs);             
            $post['data_form_sid']=$regs[0];
           
            $page = geturl("www.divshare.com",80,"/upload","http://www.divshare.com",0,$post);
            is_page($page);
            is_present($page,"can't upload a file of this type","Error file type (service not support exe-files)! / Сервис не поддерживает аплоад exe - файлов!");
            is_notpresent($page,"Upload Complete","Error upload file!");
            
            $tmp = cut_str($page,"http://www.divshare.com/download/",'">');
            if (!$tmp) html_error("Error retrive download link!");
            $download_link = "http://www.divshare.com/download/".$tmp;
            
            # Upload plug'in написан Директором Зоопарка (ru-board member kamyshew). Only for Rapidget Pro!!! Rapidkill - отстой!!! Нет плагиату!!!
?>