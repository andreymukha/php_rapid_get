<?php
            if (!preg_match('/(jpg|jpeg|png|gif|bmp|tif|tiff|swf)\\z/', $lname))
                {
                    html_error("Obmennic podderzhivaet only images: jpg, jpeg, png, gif, bmp, tif, tiff, swf");
                }
?>
<table width=600 align=center>
</td></tr>
<tr><td align=center>
<?
            $action_url = "http://www.imageshack.us/index.php";
            $url=parse_url($action_url);
            $post['MAX_FILE_SIZE']=3145728;
            $post['refer']='';
            $post['brand']='';
                       
            $upfiles=upfile($url['host'],80,$url['path'],"http://imageshack.us/", 0, $post, $lfile, $lname, "fileupload");

?>
<script>document.getElementById('progressblock').style.display='none';</script>
<?            
            is_page($upfiles);        
            
            $temp1=trim(cut_str($upfiles,"done.php?l=",'"'));
            if ($temp1=="")
                {
                    html_error("Error upload image!");
                }
            
            $temp2= explode('/',$temp1);
            $download_link = "http://".$temp2[0].".imageshack.us/".$temp1;
?>