<table width=600 align=center>
</td></tr>
<tr><td align=center>
<div id=info width=100% align=center>Retrive upload ID</div>
<?
            $page = geturl("badongo.com", 80, "/", "", 0, 0, 0, "");
            is_page($page);
            if (preg_match('/upload\\.badongo\\.com\/[a-z0-9\\_]*\/.\//', $page, $regs)) {
                $url = parse_url("http://".$regs[0]);
               // print_r($url);
                
            }else html_error("Error retrive action page!");

            $page = geturl($url['host'], 80, $url['path']."?".$url["query"], "", 0, 0, 0, "");
?>
    <script>document.getElementById('info').style.display='none';</script>
<?            
            //print_r($page);
                        
            $post["UL_ID"]=cut_str($page,'"UL_ID" value="','"');
            $post["desc"]=$descript;            
            $post["affiliate"]="";
            $post["toc"]=1;
            
            $upfiles=upfile("upload.badongo.com", 80, "/index.php?page=upload_s&s=" ,"http://upload.badongo.com/", 0, $post, $lfile, $lname, "filename");

?>
<script>document.getElementById('progressblock').style.display='none';</script>
<?
            
            $locat = cut_str($upfiles,"location.href='","'");
            $temp1=cut_str($upfiles,"'&s=","'");
            
            $url = parse_url($locat.$temp1);
            $page = geturl($url['host'],80,$url['path']."?".$url["query"],"http://www.badongo.com");
            
            is_page($page);
            //print_r($page);
            $tmp = trim(cut_str($page,"badongo.com/file/","<"));
            if(empty($tmp)) {
                $tmp = trim(cut_str($page,"badongo.com/pic/","<"));
                if(empty($tmp)) {
            	    print_r($page);
            	    html_error("Error get download link");
                }
            }
            $download_link="http://www.badongo.com/file/".$tmp;
            $delete_link="http://www.badongo.com/delete/".trim(cut_str($page,"http://www.badongo.com/delete/",'"'));
?>