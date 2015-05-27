<table width=600 align=center>
</td></tr>
<tr><td align=center>
	
<div id=info width=100% align=center>Retrive upload ID</div>
<?
			$page = geturl("getfile.biz", 80, "/upload/cl.html", 0, 0, 0, 0, "");
?>
	<script>document.getElementById('info').style.display='none';</script>
<?
			is_page($page);

			$url_action=trim(cut_str($page,'action="','"'));
			$sessionid=cut_str($page,'name="sessionid" value="','"');

			if (!$url_action || !$sessionid)
				{	
					html_error("Error retrive upload id".pre($page));
				}
			
			$post["sessionid"]=$sessionid;
			$post["MAX_FILE_SIZE"]=52000000;
			$post["u"]="Upload!";

			$url_action='http://getfile.biz'.$url_action;
			$url=parse_url($url_action);
			echo "<!-- 'http://getfile.biz'.$url_action -->\n";
			
			$upfiles=upfile($url["host"],$url["port"] ? $url["port"] : 80, $url["path"].($url["query"] ? "?".$url["query"] : ""),"http://getfile.biz/pload/cl.html", 0, $post, $lfile, $lname, "file[0]");
?>
<script>document.getElementById('progressblock').style.display='none';</script>
<?		
			is_page($upfiles);
			
			echo '<input type="hidden" name="filename" value="'.trim($lname).'">'.$nn;
			is_notpresent($upfiles,'<input type="hidden" name="filename" value="'.trim($lname).'">',"Your file not received");
			
			$confirmpage=cut_str($upfiles,'<form action="','"');
			if (!$confirmpage)
				{
					html_error("Error get confirm page URL");
				}
			
			$post2["filename"]=$lname;
			$confirmpage='http://getfile.biz'.$confirmpage;
			
?>
<div id=info2 width=100% align=center>Get confirm file code</div>
<?
			flush();
			$url=parse_url($confirmpage);
			
			echo "<!-- $confirmpage -->\n";
			$page = geturl($url["host"],$url["port"] ? $url["port"] : 80, $url["path"].($url["query"] ? "?".$url["query"] : ""),"", $post2, 0, 0, "");
?>
	<script>document.getElementById('info2').style.display='none';</script>
<?
			is_page($page);
			
			is_notpresent($page,'<img src="http://getfile.biz/images/button.html?fid=',"Your file not received");
			$temp1=cut_str($page,'<img src="http://getfile.biz/images/button.html?fid=','"');

			$download_link='http://getfile.biz/'.$temp1;
?>