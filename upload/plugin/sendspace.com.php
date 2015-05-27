<table width=600 align=center>
</td></tr>
<tr><td align=center>
    
<div id=info width=100% align=center>Retrive upload ID</div>
<?
            $page = geturl("sendspace.com", 80, "/", "", 0, 0, 0, "");
?>
    <script>document.getElementById('info').style.display='none';</script>
<?
            is_page($page);
            $cook =GetCookies($page);
            $tmp = cut_str($page,'DESTINATION','>');            
            $DESTINATION_DIR=cut_str($tmp,'value="','"');
            
            $url_action=cut_str($page,'post" action="','"');
            $UPLOAD_IDENTIFIER=cut_str($page,'name=UPLOAD_IDENTIFIER value="','"');
            $UPLOAD_IDENTIFIER=$UPLOAD_IDENTIFIER ? $UPLOAD_IDENTIFIER : cut_str($page,'name="UPLOAD_IDENTIFIER" value="','"');
            
            if (empty($url_action) || empty($UPLOAD_IDENTIFIER) || empty($DESTINATION_DIR))
                {    
                    html_error("Error retrive upload id".$page);
                }
            
            $post["UPLOAD_IDENTIFIER"]=$UPLOAD_IDENTIFIER;
            $post["DESTINATION_DIR"]=$DESTINATION_DIR;
            $post["MAX_FILE_SIZE"]="314572800";
            $post["terms"]=1;
            $post["btnupload"]="Upload File";
            $post["desc"]=$descript;
            
            $url=parse_url($url_action);
            $upfiles=upfile($url["host"],$url["port"] ? $url["port"] : 80, $url["path"].($url["query"] ? "?".$url["query"] : ""),"http://sendspace.com/", $cook, $post, $lfile, $lname, "file_0");
?>
<script>document.getElementById('progressblock').style.display='none';</script>
<?        
			//print_r($upfiles);
			is_page($upfiles);
			is_present($upfiles,"uploadprocerr.html","Error Upload file! / Ошибка загрузки файла!");

            //$page = geturl($url["host"],defport($url), $url["path"].($url["query"] ? "?".$url["query"] : ""),$url_action, $cook, 0, 0, "");
            //is_page($page);
            $tmp = cut_str($upfiles,'Download Link in HTML',"'>");
            $download_link=cut_str($tmp,"href='",'"');
            $tmp = cut_str($upfiles,'File Delete Link','/>');
            $delete_link=cut_str($tmp,'value="','"');
			# Автор плагина для sendspace.com - Director Of Zoo (ru-board aka  - kamyshew) 2007. Только для Rapidget Pro. Rapidkill вместе с Checkmate идите в жопу.
?>