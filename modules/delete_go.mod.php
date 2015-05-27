<?php
/* модуль удаления файла - 2 */
if (!defined('RAPIDGET')) {die("not load primary script");}

if ($_GET["yes"]){
    for ($i = 0; $i < count($_GET["files"]); $i++){
        $file = $list[$_GET["files"][$i]];

        if (file_exists($file["name"])){
            if (is_file($file["name"])){
                if (!in_array(basename($file["name"]),$systemfile)){
                    if (@unlink($file["name"])){
                        echo "<font color=green>$langfile</font> <b>" . basename($file["name"]) . "</b> <font color=green>$langdeleted</font><br>";
                        unset ($list[$_GET["files"][$i]]);
                    }
                    else{
                        echo "<font color=red>$langerrordelete</font> <b>" . basename($file["name"]) . "</b>!<br>";
                    }
                }else{
                    echo "<font color=red>Невозможно удалить системный файл:</font> <b>" . basename($file["name"]) . "</b>!<br>";        
                }
            }elseif (is_dir($file["name"])) {
                rmdir ($file["name"]);
            }
        }
        else{
            echo "<font color=green>$langfile</font> <b>" . basename($file["name"]) . "</b> <font color=red>$langnotfound</font><br>";
        }
    }
    if (!updateListInFile($list)) echo "<font color=red>$langeupdatelist</font>!<br>";
}
else{
?>

    <script>
    location.href="<?php echo substr($PHP_SELF, 0, strlen($PHP_SELF) - strlen(strstr($PHP_SELF, "?")))."?act=files"; ?>";
    </script>

<?php
}
?>