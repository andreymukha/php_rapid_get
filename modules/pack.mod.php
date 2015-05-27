<?php
  /* модуль упаковка файлов 1 */
if (!defined('RAPIDGET')) {
    die ("not load primary script");
}

$lib_fail=false;
switch ($archive_library)
    {
     case "PEAR-TAR":
            if ($lib_fail=(!file_exists(getcwd().DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR."Tar.php") || !file_exists(getcwd().DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR."PEAR.php")))
               { echo ' '.$langcomponents.' "Tar.php" or "PEAR.php" '.$langnotfound.'<br><br>'; };
            break;
     case "PCL-ZIP":
            if ($lib_fail=(!file_exists(getcwd().DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR."pclzip.lib.php")))
               { echo ' '.$langcomponents.' "pclzip.lib.php" '.$langfound.'<br><br>'; }
            break;
     default :
            echo "$langarcompnotins<br><br>";
            $lib_fail=true;
            break;
    }
    
if (!$lib_fail) {

    if(count($_GET["files"]) < 1)
      {
        echo "<div id='x1' style='padding:10px; border: 1px dashed red;'> $langselectf </div>";
      }
    else
      {
        ?>
          <form method="post">
          <input type=hidden name=act value="pack_go">
          <?php
          echo "[".count($_GET["files"])."]<br> $langfile".(count($_GET["files"]) > 1 ? "$langs" : "").":";

          for($i = 0; $i < count($_GET["files"]); $i++)
            {
              $file = $list[($_GET["files"][$i])];
              ?>
              <input type=hidden name="files[]" value="<?php echo $_GET["files"][$i]; ?>">
              <b><?php echo basename($file["name"]); ?></b><?php echo $i == count($_GET["files"]) - 1 ? "." : ",&nbsp";
            }
          ?><br><br>
           <table align="center">
             <tr>
               <td>
                 <?php echo $langname;?>:&nbsp;<input name=arc_name size=30>
               </td>
               <td>
                 <input type=submit value="<?php echo $langpack;?>">
               </td>
             </tr>
             <tr>
               <td>
                 <?php echo $maysaveto ? "$langpatch &nbsp;" : ""; ?><input<? echo !$maysaveto ? " type=hidden" : ""; ?> name=path size=30 value="<?php echo ($_COOKIE["path"] ? $_COOKIE["path"] : (strstr(realpath("./"), ":") ? addslashes($workpath) : $workpath)); ?>">
               </td>
             </tr>
             <tr>
               <td>
                 <input type=checkbox class="checkbox" name=del_ok checked>&nbsp;<?php echo $langdelaftersupack;?>
               </td>
             </tr>
            <tr>
              <td>
                 <?php if ($archive_library=="PCL-ZIP") {?>
                   <input type=checkbox class="checkbox" name=use_compress>&nbsp; <?php echo $langusezip;?>
                 <?php }; ?>
                 <?php if ($archive_library=="PEAR-TAR") {?>
                   <?php echo $languseext;?> "Tar, Tar.gz or Tar.bz2"
                 <?php }; ?>
              </td>
            </tr>
           </table>
          </form>
        <?php
      } 
}else{
    echo "Не найдена библиотека!";
} 
?>
