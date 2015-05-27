<table width=600 align=center>
</td></tr>
<tr><td align=center>
<?
      
      $post['name']=$lname;
	$post['mainDC']="1";
      $post['submit']="ÇÀÃÐÓÇÈÒÜ";

	$page = geturl("www.freespace.com.ua", 80, "/", 0, 0, 0, 0, "");
      $jsp_sID = cut_str($page,'<form name="uploadForm" action="http://www.freespace.com.ua/upload2.jsp?sId=','"');

      $upfiles=upfile("www.freespace.com.ua",80, "/upload2.jsp?sId=".$jsp_sID, "www.freespace.com.ua", 0, $post, $lfile, $lname, "upfile");

?>

<script>document.getElementById('progressblock').style.display='none';</script>
<?
		//echo $upfiles;
		$get_link_page = "/uploadComplete.jsp?sId=".$jsp_sID;
		$page = geturl("www.freespace.com.ua", 80, "$get_link_page", 0, 0, 0, 0, "");
		$tmp = cut_str($page,'value="http://www.freespace.com.ua/file/','"');
		$tmp2 =  cut_str($page,'value="http://www.freespace.com.ua/fadmin/','"');
		if (empty($tmp)){
			print_r($upfiles);
			html_error("Error retrive download link!");
		}
	     $download_link="http://www.freespace.com.ua/file/".$tmp;
	     $delete_link = "http://www.freespace.com.ua/fadmin/".$tmp2;

?>