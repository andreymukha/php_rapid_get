<?php
/* модуль добавления расширения к файлам - 2 */
if (!defined('RAPIDGET')) {
    die ("not load primary script");
}

if ($_GET["yes"] && @trim($_REQUEST[extension])){
    $_REQUEST[extension]=@trim($_REQUEST[extension]);

    while ($_REQUEST[extension][0] == '.')
         $_REQUEST[extension]=substr($_REQUEST[extension], 1);

    if ($_REQUEST[extension]){
        for ($i = 0; $i < count($_GET["files"]); $i++){
            $file = $list[$_GET["files"][$i]];

            if (file_exists($file["name"]) & !in_array(basename($file["name"]),$systemfile)){
                if (@rename($file["name"], fixfilename($file["name"] . ".$_REQUEST[extension]"))){
                    echo "<font color=green>$langfile</font> <b>" . basename($file["name"]) . "</b> <font color=green>$langrenameto</font> <b>" . fixfilename(basename($file["name"] . ".$_REQUEST[extension]")) . "</b><br>";
                    $list[$_GET["files"][$i]]["name"] .= '.' . $_REQUEST[extension];
                    $list[$_GET["files"][$i]]["name"]=fixfilename($list[$_GET["files"][$i]]["name"]);
                }
                else{
                    echo "<font color=red>Error rename the file</font><b>" . basename($file["name"]) . "</b>!<br>";
                }
            }
            else{
                echo "<font color=red>$langfile</font> <b>" . basename($file["name"]) . "</b> <font color=red>$langnotfound</font><br>";
            }
        }

        if (!updateListInFile($list)) echo "$langeupdatelist<br>";
    }
}
else{
?>

    <script>
    location.href="<?php echo substr($PHP_SELF, 0, strlen($PHP_SELF) - strlen(strstr($PHP_SELF, "?")))."?act=files";?>";
    </script>

<?php
}
?>