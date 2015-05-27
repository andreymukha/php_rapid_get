<table width=600 align=center>
</td></tr>
<tr><td align=center>
<div id=info width=100% align=center>Retrive upload ID</div>
<?
			$page = geturl("www.shareonall.com", 80, "/index.php?show=upload", "", 0, 0, 0, $_GET["proxy"]);
            is_page($page);      
?>
	<script>document.getElementById('info').style.display='none';</script>
<?
			 $action_url = cut_str($page,'form action="','"');
             if (!$action_url) html_error("Error Get action Url");
             $url = parse_url($action_url);            
            
            $post['UPLOAD_IDENTIFIER'] = cut_str($page,'IDENTIFIER" value="','"');
            if (!$post['UPLOAD_IDENTIFIER']) html_error("Error Get IDENTIFIER");
            
            $post['id'] = 0;
            $post["userfilename[]"]=$lname;            
            $post["desc[]"]=$descript;
            
            /* используем серваки:
                    www.depositfiles.com
                    www.megaupload.com
                    www.filefactory.com
                    www.badongo.com
                    www.netload.in
              */
            $post["server[0]"] = 2;
            $post["server[1]"] = 4;
            $post["server[2]"] = 8;
            $post["server[3]"] = 256;
            $post["server[4]"] = 512;
            
            $post["conditions[1]"] = 4;
            $post["conditions[0]"] = 2;
            $post["conditions[2]"] = 32;
            $post["conditions[3]"] = 128;
            $post["conditions[4]"] = 256;
            
            $post["email"] = "";

            $upfiles=upfile($url["host"],defport($url), $url["path"].($url["query"] ? "?".$url["query"] : ""),"http://www.shareonall.com/index.php?show=upload", 0, $post, $lfile, $lname, "upload[]",$_GET["proxy"]);
			
?>
<script>document.getElementById('progressblock').style.display='none';</script>
<!-- (c) Autor - Director Of Zoo 2007 -->
<?
			
			is_page($upfiles);
            is_notpresent($upfiles,'location=',"Error Get location link / Ошибка получения ссылки перенаправления");
            
            $locat = cut_str($upfiles,'location="','"');
            $locat .= "&lang=eng";
            $url = parse_url($locat);
            $page = geturl($url["host"],defport($url), $url["path"].($url["query"] ? "?".$url["query"] : ""),"http://www.shareonall.com/index.php?show=upload",0,0,0,$_GET["proxy"]);
            is_page($page);
            
            is_notpresent($page,"Your upload was successfull!","Error upload file / Ошибка загрузки файла");
            
            $tmp = cut_str($page,'Link to your file','</a>');
            $download_link = cut_str($tmp,"href='","'");
?>