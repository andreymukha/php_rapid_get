<?php
/* модуль добавления расширения к файлам - 1 */
if (!defined('RAPIDGET')) {
    die ("not load primary script");
}

if (count($_GET["files"]) < 1) {
    echo "<div id='x1' style='padding:10px; border: 1px dashed red;'> $langselectf </div>";
}else {
?>

    <form method = "post">
         <input class = "in" type = hidden name = act value = "mrename_go"> <?php echo $langfile ?><? echo count($_GET["files"]) > 1 ? "$langs" : ""; ?>:

<?php
         for ($i = 0; $i < count($_GET["files"]); $i++) {
             $file = $list[$_GET["files"][$i]];
             if (in_array(basename($file["name"]),$systemfile)) continue;
?>

        <input class = "in" type = hidden name = "files[]" value = "<?php echo $_GET["files"][$i]; ?>"> <b><?php echo basename($file["name"]); ?></b><?php echo $i == count($_GET["files"]) - 1 ? "." : ",&nbsp"; ?>

<?php
        }
?>

          <table>
                <hr>
                <tr>
                    <td valign = center>
                        <b><?php echo $langaddexten; ?>&nbsp;</b>
                        <font size = +1 color = green><?php echo $langexfilename; ?><? echo count($_GET["files"]) > 1 ? "$langs1" : "$langs2"; ?><b>.</b></font>
                        <b>
                            <input type = input name = "extension" style = "width:60px; height:23px" value = ''>&nbsp;
                        </b>&nbsp;
                        <input name = yes type = submit style = "height:23px" value = "<?php echo $langrename;?>">&nbsp;&nbsp;
                        <input name = no type = submit style = "height:23px" value = "<?php echo $langcancel;?>">
                        <hr>
                    </td>
                </tr>
          </table>
    </form>

<?php
}
?>