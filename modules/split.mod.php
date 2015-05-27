<?php
/* модуль разбивки на части - 1 */
if (!defined('RAPIDGET')) {
    die ("not load primary script");
}

if(count($_GET["files"]) < 1)
  {
    echo "<div id='x1' style='padding:10px; border: 1px dashed red;'> $langselectf </div>";
  }
else
  {
    ?>
    <form method="post">
    <input type=hidden name=act value="split_go">
    <table align="center">
    <tr><td>
    <table>
    <?php
    for($i = 0; $i < count($_GET["files"]); $i++)
      {
        $file = $list[$_GET["files"][$i]];
    ?>
    <input type=hidden name="files[]" value="<?php echo $_GET["files"][$i]; ?>">
    <tr><td align="center"><b><?php echo basename($file["name"]); ?></b></td></tr>
    <tr<td><?php echo $langpartsize;?>&nbsp;<input name="partSize[]" size=2 value=<?php echo $_COOKIE["partSize"] ? $_COOKIE["partSize"] : $max_mailsize; ?>>&nbsp;<?php echo $langmb;?></td></tr>
    <!--<tr><td><?php echo $langsaveto;?>&nbsp;<input name="saveTo[]" size=40 value="<?php echo addslashes(dirname($file["name"])); ?>"></td></tr>--><tr>
    <tr><td><input type=checkbox class="checkbox" name=del_ok checked>&nbsp;<?php echo $langdelsucsplit?></td></tr>
    <tr><td>&nbsp;</td></tr>
    <?php
      }
    ?>
    </table></td>
    <td><input type=submit value="<?php echo $langsplit;?>"></td></tr>
    <tr><td></td></tr></table></form>
    <?php
}  
?>
