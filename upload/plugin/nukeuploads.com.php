<?php
#                  X  X
#	              /    \
#                X      X
#              X         X
#             X_____Y_____X Best Sharing Service
#           Nuke Uploads.com
#			PHPrapidGET PLUGIN
#			  http://rapidgetpro.ru
$nukelogin=false; #'phprapidget';
$nukepassword=false; #'phprapidget';

if ($nukelogin & $nukepassword){
	$_REQUEST['filefac_log'] = $nukelogin;
	$_REQUEST['filefac_pass'] = $nukepassword;
	$_REQUEST['action'] = "GOODlogin";
}
if ($_REQUEST['action'] == "GOODlogin") {$nukeupload_member=true; $pagecook="HTTP/1.1 \nSet-Cookie: nuplds_login=".$nukelogin."; path=/ \nSet-Cookie: nuplds_password=".$nukepassword."; path=/ \nConnection: close\n";$cookies = GetCookies($pagecook,true);}
else{ echo "<div>USE ONLY MEMBER TYPE</div>\n";$nukeupload_member=false; die;}
if ($nukeupload_member==true){							#Global settings							$cookies = $cookies[0]."; ".$cookies[1];							$Referer=$uploadserver='http://nukeuploads.com/';

							#GET SERVER UPLOAD							$url=parse_url($uploadserver);
							$spage = geturl($url["host"],defport($url), $url["path"].($url["query"] ? "?".$url["query"] : ""),$Referer, $cookies, $post, 0, "");
							$uploadlink=cut_str($spage,'<form enctype="multipart/form-data" action='," method=post>");

							#UPLOAD
							$uploadurl=parse_url($uploadlink);
							$post[step]=3;
							$post["upload_type"]="local";
							$post["agree"]="agree";
							$post["submit"]="Upload";
							$upfiles=upfile($uploadurl["host"],$uploadurl["port"] ? $uploadurl["port"] : 80, $uploadurl["path"].($uploadurl["query"] ? "?".$uploadurl["query"] : "") ,$uploadserver, $cookies, $post, $lfile, $lname, "file");

is_page($upfiles);
?>
<script>document.getElementById('progressblock').style.display='none';</script>
<?
//is_notpresent($upfiles,"Can not upload file","File not upload");

							#Global settings
							unset($post);
							$result=cut_str($upfiles,'<!-- ÊÎÍÒÅÍÒ -->','<!-- Ñ×ÅÒ×ÈÊÈ -->');
							$resulttext=cut_str($result,'<h4>','</h4>');
							echo "<div id=info width=100% align=center><strong>".$resulttext."</strong></div>";
							$post[step]=3;
							$post[validate]=cut_str($result,"name=validate value='","'>");
							$post[key]=cut_str($result,"name=key value='","'>");
							$post["agree"]="agree";
							$post["folder"]=cut_str($result,"name=folder value='","'>");
							$post["f_name"]=cut_str($result,"name=f_name value='","'>");
							$post["password"]='';
							$post["description"]=base64_decode("VGhpcyBmaWxlIGhhcyBiZWVuIHVwbG9hZGVkIGJ5IGEgUEhQIHJhcGlkIEdFVC4=");
							$post["submit"]="Continue";

                            #GET LINKS
							$endpage = geturl($uploadurl["host"],defport($uploadurl), $uploadurl["path"].($uploadurl["query"] ? "?".$uploadurl["query"] : ""),$Referer, $cookies, $post, 0, "");
							is_page($endpage);
							$echoendpage=cut_str($endpage,'<form name=frm1 method=post>',"<input type=submit value='Send Email Now'>");
							echo "<div>".$echoendpage."</form></div>";
}
?>
<script>document.getElementById('info').style.display='none';</script>