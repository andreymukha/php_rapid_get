<?php
/* модуль удаления файла - 1 */
if (!defined('RAPIDGET')) {die("not load primary script");}

if(count($_GET["files"]) < 1){
    echo "<div id='x1' style='padding:10px; border: 1px dashed red;'> $langselectf </div>";
  }
else{
?>
      <form method="post">
      <input type=hidden name=act value="delete_go">
      <?php echo $langfile;?><? echo count($_GET["files"]) > 1 ? "$langs" : ""; ?>:
<?php
      for($i = 0; $i < count($_GET["files"]); $i++){
          $file = $list[$_GET["files"][$i]];
?>
          <input type=hidden name="files[]" value="<?php echo $_GET["files"][$i]; ?>">
          <b><?php echo basename($file["name"]); ?></b><?php echo $i == count($_GET["files"]) - 1 ? "." : ",&nbsp"; ?>
<?php
      }
?>
      <br><?php echo $langdelete;?><? echo count($_GET["files"]) > 1 ? " $langtfiles" : " $langtfile"; ?>?<br>
      <table>
        <tr>
          <td>
            <input type=submit name="yes" style="width:33px; height:23px" value="<?php echo $langyes;?>">
          </td>
          <td>
            &nbsp;&nbsp;&nbsp;
          </td>
          <td>
            <input type=submit name="no" style="width:33px; height:23px" value="<?php echo $langno;?>">
          </td>
        </tr>
      </table>
      </form>  
<?php
  }
?>