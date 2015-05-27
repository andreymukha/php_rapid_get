<table width=600 align=center> 
</td></tr> 
<tr><td align=center>
<?
            $page = geturl("cocoshare.cc", 80, "/");
            $post['emailto']="";
            $post['emailfromname']="";
            $post['emailfrommail']="";
            $post['emailcomment']=$descript;
            $post['upload']=".";
            
            $upfiles=upfile("cocoshare.cc",80,"/","http://cocoshare.cc/", 0, $post, $lfile, $lname, "file"); 
         
?> 
<script>document.getElementById('progressblock').style.display='none';</script> 
<?     
            is_page($upfiles);
            is_notpresent($upfiles,"Location","Error upload file!");
            $tmp = cut_str($upfiles,"Location: ","/info");
            if (empty($tmp)) html_error("Error retrive download link!");
            $download_link = "http://cocoshare.cc".$tmp;
            $tmp = trim(cut_str($upfiles,"info","\n"));
            $delete_link = $download_link.$tmp;
?>