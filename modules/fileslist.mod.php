<?php
/* модуль генерации списка файлов */
if (!defined('RAPIDGET')) {
   die("not load primary script");
}

function get_name_url($show_port = false){
   if ($_SERVER['HTTPS']) {
      $my_url = 'https://';
   }else {
      $my_url = 'http://';
   }

   $my_url .= $_SERVER['HTTP_HOST'];

   if ($show_port) {
      $my_url .= ':' . $_SERVER['SERVER_PORT'];
   }
   $my_url .= dirname($_SERVER['SCRIPT_NAME']);
   
   return $my_url;
}   

$my_url=get_name_url();
echo "<div><textarea cols=70% rows=".(count($_GET["files"])+1)." id=fileslist>";
for ($i = 0; $i < count($_GET["files"]); $i++){
   $file = $list[$_GET["files"][$i]];
   
   if (in_array(basename($file["name"]), $systemfile)) continue;

   echo trim($my_url .'/'. basename($file["name"]));
   echo $i < (count($_GET["files"]) - 1) ? "\n" : "";
}
?>

</textarea>

</div>

<script>
    var app = (navigator.appName == 'Microsoft Internet Explorer') ? true : false;

    var filelist = d.getElementById('fileslist');
    filelist.focus();
    filelist.select();

    function toBuf(){
        filelist.createTextRange().execCommand('Copy');
    }

    if (app){
        d.write('<input title="copy links" onclick="toBuf()" type="button" value="Copy links" id="l" />');
    }
</script>

</div>