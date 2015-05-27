<table width=600 align=center>
</td></tr>
<tr><td align=center>
	
<div id=info width=100% align=center>Retrive upload ID</div>
<?
			$page = geturl("mihd.net", 80, "/", "", 0, 0, 0, "");
			is_page($page);
            $cook = trim(cut_str($page,"Cookie:","\n"));


			$post['d_uploadform']="Upload [max 101MB]";
            $post['uploadform']= "";
            $tmp = cut_str($page,'multipart/form-data','">');
			$action_url = cut_str($tmp,'action="','"');
            $action_url = str_replace("&amp;","&",$action_url);
			if (!$action_url) html_error("Error retrive upload id".$page);

			$url=parse_url($action_url);

?>
	<script>document.getElementById('info').style.display='none';</script>
<?

			$upfiles=upfile($url["host"],defport($url), $url["path"].($url["query"] ? "?".$url["query"] : ""),"http://mihd.net/", $cook, $post, $lfile, $lname, "Filedata");

?>
<script>document.getElementById('progressblock').style.display='none';</script>
<?		
			is_page($upfiles);
            is_notpresent($upfiles,"Location","Error Upload File!");
            $location = cut_str($upfiles,"Location: ","\n");
            $url=parse_url($location);
            $page = geturl($url["host"],defport($url),$url['path'],"http://mihd.net/",$cook,0,0,0,0);
            is_page($page);
            is_notpresent($page,"http://mihd.net/","Error Upload file");
            
            $page = geturl("mihd.net", 80, "/","http://mihd.net/",$cook,0,0,0,0);
            is_page($page); 
            
            $tmp = cut_str($page,"download_url","/>");
            $download_link = cut_str($tmp,'value="','"');
            $tmp = cut_str($page,"delete_url","/>");
            $delete_link = cut_str($tmp,'value="','"'); 
?>
    