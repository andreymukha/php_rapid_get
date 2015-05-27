<table width=600 align=center>
</td></tr>
<tr><td align=center>



<?php
if (isset($_REQUEST['action'])){       
			
			$url_action=$_REQUEST['action_url'];
            $post['MAX_FILE_SIZE']=1073741824;
            $post['description']= $descript;
            $post['passdel']=$_REQUEST['passdel'];
            $post['passget']="";
            $post['cifry']=$_REQUEST['confirmed_number'];
            $posr['agreement'] = 1;
			if (!$url_action || !$post['passdel']) html_error("Error get data!");
			
			$url=parse_url($url_action);
            
			$upfiles=upfile($url["host"],defport($url), $url["path"].($url["query"] ? "?".$url["query"] : ""),"http://user.kz/files/index.php?", 0, $post, $lfile, $lname, "userfile");
            is_page($upfiles);
            is_notpresent($upfiles,"успешно","Error upload file / Ошибка ввода проверочных цифр!");
?>
<script>document.getElementById('progressblock').style.display='none';</script>
<?	
            
            $tmp = cut_str($upfiles,"доступен по адресу",'>');
            $download_link = cut_str($tmp,'a href="','"');
            $tmp = cut_str($upfiles,'удаления файла','>');
            $delete_link = cut_str($tmp,'href="','"');
            $tmp = cut_str($upfiles,'продления срока','>');
            $adm_link = cut_str($tmp,'href="','"');  
           // die();
}else{
?>    
<div id=info width=100% align=center>Retrive captcha image</div>
<?php  			
            $page = geturl("user.kz", 80, "/files/index.php?", "http://user.kz/files.html", 0, 0, "");
?>
	<script>document.getElementById('info').style.display='none';</script>
<?php
            is_page($page);
            is_present($page,"только казахстанским","Сервис доступен только казахстанским пользователям сети интернет / Only for kazahstan!");
            #print_r($page);
            $action_url = cut_str($page,'METHOD=POST ACTION="','"');
            if (empty($action_url)) html_error("Error retrive Action Url!");
            $pass_del=cut_str($page,'passdel value=',">");
            $img_link = cut_str($page,"cifry.php",'">');
           //http://user.kz/files/cifry.php?sid=26530             
?>          
<form action="<? echo $_SERVER['PHP_SELF']."?".$_SERVER ['QUERY_STRING']?>" method="POST">
    <input type="hidden" name="action_url" value='<?echo $action_url?>' />
    <input type="hidden" name="passdel" value=<?echo $passdel?> />
    Enter this image <img src="http://user.kz/files/cifry.php<?echo $img_link?>"> to here <input type="text" class="text" name="confirmed_number">
    <input type="submit" name="action" value="Start Upload"  style="width:90px;">
</form>
</td></tr></table>
<?            

			die();
}
?>