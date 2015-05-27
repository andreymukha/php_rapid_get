<?
if (isset($_POST['action']))
	{
		$post[$_POST['desc_id']]=$descript;
		$post[$_POST['pass_id']]="";
		$post['confirmed_number']=$_POST['confirmed_number'];
		$post['email'] = "";
		$post['session']=$_POST['session_id'];
		$post['action']="Подтвердить";
		$url = parse_url($_POST['url']);
		$page = geturl($url['host'], 80, $url['path']."?".$url["query"], "http://ifolder.ru/", 0,$post, 0, "");
		is_page($page);
		if (preg_match('/confirmed_number/',$page)) html_error("Error code, retry again!");
		preg_match('%ifolder\\.ru/\\d+%',$page,$down);
		if (empty($down[0])) html_error("error get download link");
		$download_link = "http://".$down[0];
		$adm_link = "http://ifolder.ru/control/".cut_str($page,'"http://ifolder.ru/control/','"');
	}
		else
	{            
?>
<table width=600 align=center>
</td></tr>
<tr><td align=center>
<div id=info width=100% align=center>Retrive upload ID</div>
<?
		$page = geturl("ifolder.ru", 80, "/", "", 0, 0, 0, ""); 
		$action_url = cut_str($page,'form-data" action="','"');
		$url = parse_url($action_url);
		$post['upload_params']=cut_str($page,'params" value="','"');
		$post['clone']=cut_str($page,'clone" value="','"');
		$post['progress_bar']=cut_str($page,'_bar" value="','"');
		$post['upload_host']=cut_str($page,'host" value="','"');
		$post['MAX_FILE_SIZE']=104857600;
		$post['show_progress_bar']=0;
?>
<script>document.getElementById('info').style.display='none';</script>
    
<table width=600 align=center>
</td></tr>
<tr><td align=center>
<?

            $upfiles=upfile($url['host'],80,$url['path']."?".$url["query"],"http://ifolder.ru/", 0, $post, $lfile, $lname, "filename");
            is_page($upfiles);

?>
<script>document.getElementById('progressblock').style.display='none';</script>
<?          
            $tmp_url = trim(cut_str($upfiles,"Location:","\n"));
            if (!$tmp_url){
                html_error("Error Upload!!!");
            }
            unset($url);
            $url = parse_url($tmp_url);
            $page = geturl($url['host'], 80, $url['path']."?".$url["query"], "http://ifolder.ru/", 0, 0, 0, "");
            is_page($page);
            if (preg_match('/sys_msg/',$page)) html_error("Error Upload, vozmozhno na vash ip ban!");
            //print_r($page);
            $desc_id = "descr_".cut_str($page,"descr_",'"');
            $pass_id = "password_".cut_str($page,"password_",' ');
            $img_link = "/random/images/".cut_str($page,"/random/images/",'"');
            $session_id = cut_str($page,'session" value=','>');         
            
?>          
<form action="<? echo $_SERVER['PHP_SELF']."?".$_SERVER ['QUERY_STRING']?>" method="POST">
    <input type="hidden" name="desc_id" value=<?echo $desc_id?>>
    <input type="hidden" name="pass_id" value=<?echo $pass_id?>>
    <input type="hidden" name="session_id" value=<?echo $session_id?>>
    <input type="hidden" name="url" value=<?echo $tmp_url?>>
    <input type="hidden" name="filename" value=<? echo base64_encode($_REQUEST[filename]); ?>>
    Enter this image <img src="http://ifolder.ru<?echo $img_link?>"> to here <input type="text" class="text" name="confirmed_number">
    <input type="submit" name="action" value="Get Link's"  style="width:80px;">
</form>
</td></tr></table>
<?      
			die();
		}
?>
	