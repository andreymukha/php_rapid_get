<table width=600 align=center>
</td></tr>
<tr><td align=center>
<div id=info width=100% align=center>Retrive upload ID</div>
<?
			$page = geturl("sendfiles.com.ua", 80, "/", "", 0, 0, 0, "");
?>
	<script>document.getElementById('info').style.display='none';</script>
<?
            $tmp = cut_str($page,'upload?id=','"');
            if(!$tmp) html_error("Error retrive upload ID / ������ ��������� ��������������!");

            
            
			$url=parse_url("http://www.sendfiles.com.ua:8080/upload?id=".$tmp);			
			$post["condition"]=1;
			
			$upfiles=upfile($url["host"],defport($url), $url["path"].($url["query"] ? "?".$url["query"] : ""),"http://sendfiles.com.ua/", 0, $post, $lfile, $lname, "file");
?>
<script>document.getElementById('progressblock').style.display='none';</script>
<?
			is_page($upfiles);
            is_notpresent($upfiles,"complete","Error Upload file! / ������ �������� �����!");
            $tmp = trim(cut_str($upfiles,"Location:","\r\n"));
            if (!$tmp) html_error("Error Upload file! / ������ �������� �����!");
            $url = parse_url($tmp);
            $page = geturl($url["host"],defport($url), $url["path"].($url["query"] ? "?".$url["query"] : ""),"http://sendfiles.com.ua/");
            is_page($page);
            is_notpresent($page,"��� ���� ��������","Error Get Download Link / ������ ��������� ������");
            
            $tmp = cut_str($page,"����������","</a>");
            if (!$tmp) html_error("Error Get Download Link / ������ ��������� ������");            			
			$download_link=trim(cut_str($tmp,'href="','"'));
            
            $tmp = cut_str($page,"��������","<br>");
			$delete_link=trim(cut_str($tmp,'<b>','</b>'));
?>