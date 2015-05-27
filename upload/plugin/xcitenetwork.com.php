<?php
            if (!preg_match('/(gif|jpeg|jpg|rar|zip|mp3|rm|ram|wma|mpeg|avi)\\z/', $lname))
                {
                    html_error("Obmennic podderzhivaet only file types: gif, jpg, jpeg, rar, zip, mp3, rm, ram, wma, mpeg, avi");
                }
?>
<table width=600 align=center>
</td></tr>
<tr><td align=center>
<?
			$url_action='http://www.xcitenetwork.com/upload.php';

			$post["descr"]=$descript;
			$post["pprotect"]="";
            $post["category"]="Applications";
            $post["myemail"]=""; 

			$url=parse_url($url_action);

			$upfiles=upfile($url["host"],defport($url), $url["path"].($url["query"] ? "?".$url["query"] : ""),"http://www.xcitenetwork.com/", 0, $post, $lfile, $lname, "upfile");
?>
<script>document.getElementById('progressblock').style.display='none';</script>
<?		
			is_page($upfiles);
            is_notpresent($upfiles,"Your file was uploaded!","Error upload file!");
            
           $tmp = cut_str($upfiles,"download link",'>h');
           $download_link = cut_str($tmp,'href="','"');
           $tmp = cut_str($upfiles,"delete link ",'>h');
           $delete_link = cut_str($tmp,'href="','"');
?>