<table width=600 align=center> 
</td></tr> 
<tr><td align=center>
<?

            $page = geturl("www.bigshare.net", 80, "/", "", 0, $post);

            $post['sid']=cut_str($page,'sid" value="','"');
            $post['timestamp']=time();
            $action_url = "http://www.bigshare.net/script/u.pl?sid={$post['sid']}&ts={$post['timestamp']}&filename=".$lname;
            $cookie = "PHPSESSID={$post['sid']}; b=b";
            $url = parse_url($action_url);
            
            $upfiles=upfile($url['host'],defport($url),$url['path']."?".$url["query"],"http://www.bigshare.net/", $cookie, $post, $lfile, $lname, "uploadedFile"); 
         
?> 
<script>document.getElementById('progressblock').style.display='none';</script> 
<?     
            is_page($upfiles);
            is_notpresent($upfiles,"finished","Error Upload File / Ошибка загрузки файла");
            
            $page = geturl("www.bigshare.net", 80, "/register_file.php?action=store&timestamp=".$post['timestamp'],"http://www.bigshare.net/",$cookie);
            is_page($page);
            is_notpresent($page,"File was successfully uploaded.","Error Upload File / Ошибка загрузки файла 2");
            

            $tmp = trim(cut_str($page,"http://www.bigshare.net/download.php?file=",'"'));
            if (!$tmp) html_error("Error Get Download Link / Ошибка получения ссылок");
            
            $download_link = "http://www.bigshare.net/download.php?file=".$tmp;

?>