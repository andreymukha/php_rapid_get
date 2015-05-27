<?php
/* модуль переименования файлов - 1 */
if (!defined('RAPIDGET')) {
    die ("not load primary script");
}

 $smthExists = FALSE;
 for($i = 0; $i < count($_GET["files"]); $i++)
  {
    $file = $list[$_GET["files"][$i]];
    if(file_exists($file["name"]) & !in_array(basename($file["name"]),$systemfile))
      {
        $smthExists = TRUE;
        $newName = dirname($file["name"]).DIRECTORY_SEPARATOR.fixfilename($_GET["newName"][$i]);
        if(@rename($file["name"], $newName))
          {
            echo "<font color=green>".$langfile."</font> <b>".basename($file["name"])."</b> <font color=green>".$langrenameto."</font> <b>".basename($newName)."</b><br>";
            $list[$_GET["files"][$i]]["name"] = $newName;
          }
        else
          {
            echo "<font color=red>".$langcouldnrename."</font> <b>".basename($file["name"])."</b>!<br>";
          }
      }
    else
     {
       echo "<font color=red>".$langfile."</font> <b>".basename($file["name"])."</b> <font color=red>".$langnotfound."</font><br>";
     }
  }
 if($smthExists)
   {
     if(!updateListInFile($list))
      {
          echo "$langcouldnupdate<br><br>";
      }
   }  
?>
