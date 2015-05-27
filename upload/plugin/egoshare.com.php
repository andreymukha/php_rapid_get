<table width=600 align=center>
</td></tr>
<tr><td align=center>
<?
			$post["upload"]="true";
			$post["submit"]="Upload File";

			$url_action="http://www.egoshare.com/";

			$url=parse_url($url_action);

			$upfiles=upfile($url["host"],$url["port"] ? $url["port"] : 80, $url["path"].($url["query"] ? "?".$url["query"] : ""),"http://www.egoshare.com", 0, $post, $lfile, $lname, "upfile");
?>
<script>document.getElementById('progressblock').style.display='none';</script>
<?		

			is_page($upfiles);
            #print_r($upfiles);
			is_notpresent($upfiles,'name="downloadlink" value="','File not received');
			
			$download_link=trim(cut_str($upfiles,'name="downloadlink" value="','"'));
			$delete_link=trim(cut_str($upfiles,'name="deletelink" value="','"'));
?>