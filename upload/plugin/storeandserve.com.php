<table width=600 align=center>
</td></tr>
<tr><td align=center>
<div id=info width=100% align=center>Retrive upload ID</div>
<?php
            
        $page = geturl("upload4.storeandserve.com", 80, "/?uploads/upload", "http://storeandserve.com", 0, 0, 0, "");
        is_page($page);
        $temp1=trim(cut_str($page,'action="','"'));
        $page = geturl("upload4.storeandserve.com",80,"/".$temp1);
        is_page($page);
        $action_url = trim(cut_str($page,'action="','"'));        
        $post["ubtn"] ="Upload";
        
?>
    <script>document.getElementById('info').style.display='none';</script>
<?php
            
        $upfiles = upfile("upload4.storeandserve.com",80, $action_url,"http://upload2.storeandserve.com/?uploads/upload", 0, $post, $lfile, $lname, "uploadname");
?>
<script>document.getElementById('progressblock').style.display='none';</script>
<?php        
        is_page($upfiles);
        unset($post);
        $post['hidSession'] = cut_str($upfiles,'Session" value="','"');
        $post['hidFileName'] = cut_str($upfiles,'Name" value="','"');
        $page = geturl("upload4.storeandserve.com", 80, "/?uploads/uploaded_over_redirect", "http://upload2.storeandserve.com".$action_url,0,$post);
        is_page($page);
        $temp1 = trim(cut_str($page,"dh' value='","'"));
        if (empty($temp1)) html_error("Error Get Download Link! / Ошибка получения ссылок!");
        $download_link = "http://storeandserve.com/download/".$temp1."/";
        # Upload plug'in написан Директором Зоопарка (ru-board member kamyshew). Only for Rapidget Pro!!! Rapidkill - отстой!!! Нет плагиату!!!
?>