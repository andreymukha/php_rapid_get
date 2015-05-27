<?php
/* модуль расчёта md5 */
if (!defined('RAPIDGET')) {
    die("not load primary script");
}

if (count($_GET["files"]) < 1) {
    echo "<div id='x1' style='padding:10px; border: 1px dashed red;'> $langselectf </div>";
}else {
?>

    <table align = "center" border = 0 frame = box rules = all>
                    <tr><td align = center><?php echo $langfile; ?><td align = center><?php echo $langsize; ?><td align = center><?php echo $langmd5; ?>
                    </tr>

                <?php
                    for ($i = 0; $i < count($_GET["files"]); $i++){
                        $file = $list[($_GET["files"][$i])];

                        if (file_exists($file["name"]) & !in_array(basename($file["name"]),$systemfile)){
                ?>

<tr>
    <td nowrap><b>&nbsp;<?php echo basename($file["name"]) . "</b><td align=center>&nbsp;" . $file["size"] ?>&nbsp;</td>
    <td nowrap><b>&nbsp;<font style = "font-family: monospace;"><?php echo md5_file($file["name"]) ?></font>&nbsp;</b>
</tr>
                <?php
                        }
                    }
                ?>
    </table>
<?php
}
?>