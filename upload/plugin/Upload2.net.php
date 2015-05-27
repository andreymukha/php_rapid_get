<table width=600 align=center>
</td></tr>
<tr><td align=center>
<div id=info width=100% align=center>Retrive upload ID</div>
<?
            $page = geturl("www.upload2.net", 80, "/", "", 0, 0, 0, "");
            
            $action_url = cut_str($page,'action="','"');
            if (empty($action_url)) html_error("Erroê retrive action url!");            
            $url = parse_url($action_url);
            #print_r($url);
?>
<script>document.getElementById('info').style.display='none';</script>
    
<table width=600 align=center>
</td></tr>
<tr><td align=center>
<?

        $post['MAX_FILE_SIZE']=26214400;
        $post['page']='upload';
        
        $upfiles=upfile($url['host'],80,$url['path']."?".$url["query"],"http://www.upload2.net/", 0, $post, $lfile, $lname, "file");
?>
<script>document.getElementById('progressblock').style.display='none';</script>
<?
        
        is_page($upfiles);
                        
        $temp1 = trim(cut_str($upfiles,"Location:","\n"));
        $url = parse_url($temp1);
        $page = geturl($url['host'], 80, $url['path']."?".$url["query"], "http://www.upload2.net/", 0, 0, 0, "");
        
        is_page($page);
        
        $temp1=cut_str($page,"Download link",">");
        $download_link = trim(cut_str($temp1,'="','"'));
        
        $temp1=cut_str($page,"Delete",">");
        $delete_link = trim(cut_str($temp1,'="','"'));
?>                   