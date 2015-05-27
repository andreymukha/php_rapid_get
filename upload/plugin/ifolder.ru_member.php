<?
 // логин пароль для обменника
 $login = false; // пример "test@test.ru"
 $pass = false; // пример "test"
//-----------------------------
if (isset($_POST['action']))
	{
		// после ввода с картинки
        $post[$_POST['desc_id']]=$descript;
		$post[$_POST['pass_id']]="";
		$post['confirmed_number']=$_POST['confirmed_number'];
		$post['email'] = "";
		$post['session']=$_POST['session_id'];
		$post['action']="Подтвердить";
        $cook[0] = "sid=".$_POST['cookie'];
		$url = parse_url($_POST['url']);
		$page = geturl($url['host'], 80, $url['path']."?".$url["query"], "http://ifolder.ru/", $cook,$post, 0, "");
        #print_r($page);
		is_page($page);
		if (preg_match('/confirmed_number/',$page)) html_error("Error code, retry again!");
		preg_match('%ifolder\\.ru/\\d+%',$page,$down);
		if (empty($down[0])) html_error("error get download link");
		$download_link = "http://".$down[0];
		$adm_link = "http://ifolder.ru/control/".cut_str($page,'"http://ifolder.ru/control/','"');
	}
		else   // самое начало
	{            
        $not_done=true;
        $continue_up=false;
        if ($_REQUEST['action2'] == "FORM" || ($login && $pass))
            $continue_up=true;
        else{       // форма ввода логина/пароля
?>
<table border=1 style="width:270px;" cellspacing=0 align=center>
<form method=post>
<input type=hidden name=action2 value='FORM'>
<tr><td nowrap>&nbsp;E-mail<td>&nbsp;<input name=ifol_log value='' style="width:160px;">&nbsp;</tr>
<tr><td nowrap>&nbsp;Password<td>&nbsp;<input name=ifol_pass value='' style="width:160px;">&nbsp;</tr>
<tr><td colspan=2 align=center><input type=submit value='Upload'></tr>
</table>
</form>
<?php
        }   

        if ($continue_up){          // логин/пароль введены, начинаем аплоад
            $not_done=false;
            $action_url = "http://ifolder.ru/";
            $login = $login?$login:$_REQUEST['ifol_log'];
            $pass = $pass?$pass:$_REQUEST['ifol_pass'];
            if (!$login || !$pass){
                echo "<b><center>Error login to ifolder. <span style='color:red'>Not use</span> ifolder Account.</center></b>\n";
                $mem=false;    
            }else{
?>
<div id=login width=100% align=center>Login to ifolder / Рубимся к ifolder</div>
<?php
            
                $page = geturl("ifolder.ru",80,"/","",0,0); // получаем первую страницу
                is_page($page);
                $mem=true;
                $post["return_params"] = cut_str($page,'return_params" value="','"');            
                $post['email']=str_replace("@","%40",$login);            
                $post['password'] = $pass;
                $post['return_path'] = "%2F";
                $post['cmd'] = "authorize";
            
                $page = geturl("ifolder.ru",80,"/auth/login/","http://ifolder.ru/",0,$post);
                is_page($page);
                //print_r($page);
                if(!strpos($page,"auth_ok")) {
                    $mem=false; // ошибка регистрации
                    echo "<b><center>Error login to ifolder. <span style='color:red'>Not use</span> ifolder Account.</center></b>\n";
                }
                else{  // регистрация успешна
                    $cookie = cut_str($page,"Cookie: sid=",";");
                    $cook[0] = "sid=".$cookie;
                    $action_url = "http://ifolder.ru/?auth_ok=1";
                    echo "<b><center>Login to ifolder - Ok. <span style='color:green'>Use</span> ifolder Account.</center></b>\n";
                }            
            }       // от логина
            
     ?>
<script>document.getElementById('login').style.display='none';</script>     
<table width=600 align=center>
</td></tr>
<tr><td align=center>
<div id=info width=100% align=center>Retrive upload ID</div>
<?
		$url = parse_url($action_url);
        $page = geturl($url['host'], defport($url), $url['path']."?".$url['query'], "", ($mem?$cook:0), 0, 0, "");
        is_page($page); 
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

            $upfiles=upfile($url['host'],80,$url['path']."?".$url["query"],"http://ifolder.ru/", ($mem?$cook:0), $post, $lfile, $lname, "filename");
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
            $page = geturl($url['host'], 80, $url['path']."?".$url["query"], "http://ifolder.ru/", ($mem?$cook:0), 0, 0, "");
            is_page($page); 
            #print_r($page); 
            if (preg_match('/sys_msg/',$page)) html_error("Error Upload, vozmozhno na vash ip ban!");
            
            $desc_id = "descr_".cut_str($page,"descr_",'"');
            $pass_id = "password_".cut_str($page,"password_",' ');
            $img_link = "/random/images/".cut_str($page,"/random/images/",'"');
            $session_id = cut_str($page,'session" value=','>');         
             
            
?>          
<form action="<? echo $_SERVER['PHP_SELF']."?".$_SERVER ['QUERY_STRING']?>" method="POST">
    <input type="hidden" name="desc_id" value=<?=$desc_id?> />
    <input type="hidden" name="pass_id" value=<?=$pass_id?> />
    <input type="hidden" name="session_id" value=<?=$session_id?> />
    <input type="hidden" name="url" value=<?=$tmp_url?> />
    <input type="hidden" name="cookie" value='<?=$cookie; ?>' />
    Enter this image <img src="http://ifolder.ru<?=$img_link?>"> to here <input type="text" class="text" name="confirmed_number">
    <input type="submit" name="action" value="Get Link's"  style="width:80px;">
</form>
</td></tr></table>
<?      
			die();
		}
    }
?>
	