<table width=600 align=center>
</td></tr>
<tr><td align=center>
        
<div id=info width=100% align=center>Retrive upload ID</div>
<?
                        $lang = "lang_current=en";// определяем язык	
						$page = geturl("depositfiles.com", 80, "/en/", 0, $lang, 0, 0, "");
?>
        <script>document.getElementById('info').style.display='none';</script>
<?
                        is_page($page);
                        
                        $cook = GetCookies($page);
						$cookie = @implode("; ",$cook);
						$cookie .= "; ".$lang;

                        $UPLOAD_IDENTIFIER=cut_str($page,'UPLOAD_IDENTIFIER" value="','"');

                        if (!$UPLOAD_IDENTIFIER)
                                {
                                        html_error("Error retrive UPLOAD_IDENTIFIER");
                                }
                                
                        $post["agree"]=1;
                        $post["UPLOAD_IDENTIFIER"]=$UPLOAD_IDENTIFIER;
                        $post["MAX_FILE_SIZE"]=314572800;
                        $post["go"]=1;
                        //$post["submit"]="Upload";
                        $post["description"]=$descript;
                        
                        $url_action = cut_str($page,'multipart/form-data" action="','"');                       
                        
                        $url=@parse_url($url_action);
                        if(!$url) html_error("Error Retrive Action Url <br /> Ошибка получения адреса для загрузки файла");
                        
                        $upfiles=upfile($url["host"],defport($url), $url["path"].($url["query"] ? "?".$url["query"] : ""),"http://depositfiles.com/en/", $cookie, $post,
$lfile, $lname, "files");

?>
<script>document.getElementById('progressblock').style.display='none';</script>
<?              
                        is_page($upfiles);
                        is_notpresent($upfiles,'parent.ud_download_url','File not uploaded');
                        
                        $download_link=cut_str($upfiles,"parent.ud_download_url = '","';");
                        $delete_link=cut_str($upfiles,"parent.ud_delete_url = '","';");
?>