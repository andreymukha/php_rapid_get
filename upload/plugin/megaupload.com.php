<table width=600 align=center>
</td></tr>
<tr><td align=center>
	
<div id=info width=100% align=center>Retrive upload ID</div>
<?
			$page = geturl("www.megaupload.com", 80, "/en/", 0, 0, 0, 0, "");
?>
	<script>document.getElementById('info').style.display='none';</script>
<?
			is_page($page);

			$url_action=cut_str($page,'"multipart/form-data" action="','"');
			$sessionid=cut_str($page,'name="sessionid" value="','"');
			$UPLOAD_IDENTIFIER=cut_str($page,'name="UPLOAD_IDENTIFIER" value="','"');

			if (!$url_action || !$sessionid || !$UPLOAD_IDENTIFIER)
				{	
					html_error("Error retrive upload id".pre($page));
				}
			
			$post["sessionid"]=$sessionid;
			$post["UPLOAD_IDENTIFIER"]=$UPLOAD_IDENTIFIER;
			$post["accept"]=1;
			$post["message"]=$descript;

			$url=parse_url($url_action);
			$upfiles=upfile($url["host"],$url["port"] ? $url["port"] : 80, $url["path"].($url["query"] ? "?".$url["query"] : ""),"http://www.megaupload.com/en/", 0, $post, $lfile, $lname, "file");

?>
<script>document.getElementById('progressblock').style.display='none';</script>
<?		
			is_page($upfiles);
			
			is_notpresent($upfiles,"downloadurl = '","File not upload");
			
			$download_link=cut_str($upfiles,"downloadurl = '","'");
?>