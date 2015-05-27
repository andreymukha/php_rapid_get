<table width=600 align=center>
</td></tr>
<tr><td align=center>
<?php

			$page = geturl("letitbit.net", 80, "/", 0, 0, 0, 0, "");

            $post["uid"]=123;
			$post["sessionid"]="";
            $post["css_name"]="";
            $post["accept"]=0;
            $post["checkbox"]='checkbox';
            $post["upload_a"]='Upload';
            $post["owner"]='';
            $post["tmpl_name"]='';
            $post["MAX_FILE_SIZE"]=1200000000;
            $cookie = "lang=us";
            $fl_array = array_pop(preg_grep('/enctype="multipart\/form-data"/', explode("\n",$page)));
            $action_url=trim(cut_str($fl_array,'action="','"'));             
            //print_r($action_url);
            $UID = rand(1,10000).'0'.rand(1,10000);
            
			$action_url .= $UID;

			$url=parse_url($action_url);

			$upfiles=upfile($url["host"],defport($url), $url["path"].($url["query"] ? "?".$url["query"] : ""),"http://letitbit.net", $cookie, $post, $lfile, $lname, "myfile");
?>
<script>document.getElementById('progressblock').style.display='none';</script>
<?php		

			is_page($upfiles);


            is_notpresent($upfiles,"<textarea name='myfile_status'>OK","File not upload");
            
            unset($post);
            $post["sessionid"]=cut_str($upfiles,"<textarea name='sessionid'>","</textarea>");
            $post["css_name"]="";
            $post["MAX_FILE_SIZE"]=cut_str($upfiles,"<textarea name='MAX_FILE_SIZE'>","</textarea>");
            $post["uid"]=cut_str($upfiles,"<textarea name='uid'>","</textarea>");
            $post["terms_a"]=cut_str($upfiles,"<textarea name='terms_a'>","</textarea>");
            $post["upload_a"]=cut_str($upfiles,"<textarea name='upload_a'>","</textarea>");
            $post["self_desc"]=$descript;
            $post["myfile"]=$lname;
            $post["myfile_status"]=cut_str($upfiles,"<textarea name='myfile_status'>","</textarea>");
            $post["target_dir"]=trim(cut_str($upfiles,"<textarea name='target_dir'>","</textarea>"));
            
            if(!$post["target_dir"]) html_error("File not upload");
            
            $final_url=trim(cut_str($upfiles,"action='","'"));
            
?>
<div id=info2 width=100% align=center><b>Get finaly file code</b></div>
<?php

            $url=parse_url($final_url);
            $spage = geturl($url["host"],defport($url), $url["path"].($url["query"] ? "?".$url["query"] : ""),$action_url, $cookie, $post, 0, "");
            
            is_page($spage);
?>
    <script>document.getElementById('info2').style.display='none';</script>
<?php
            is_present($spage,'Failed to upload file.');
            $finish_url=trim(cut_str($spage,'LOCATION:',"\n"));
            
            if(!$finish_url) html_error("File not upload");
            
            $url=parse_url($finish_url);
            $spage = geturl($url["host"],defport($url), $url["path"].($url["query"] ? "?".$url["query"] : ""),$action_url, $cookie, $post, 0, "");
            is_page($spage);
            
            $tmp=cut_str($spage,'Download link','</a>');
            $download_link=trim(cut_str($tmp,"<a href='","'"));

            $tmp=cut_str($spage,'Delete link','</a>');
            $delete_link=trim(cut_str($tmp,"<a href='","'"));
?>
