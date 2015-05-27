<?php
/* модуль обратимого шифрования имени файла - 1 */
if (!defined('RAPIDGET')) {
   die("not load primary script");
}

if (count($_GET["files"]) < 1) {
   echo "<div id='x1' style='padding:10px; border: 1px dashed red;'> $langselectf </div>";
}else {
?>

   <form method = "post"><input type = hidden name = act value = "rename_go">
       <table align = "center">
           <tr><td>
                   <table border = 1 frame = box cellpadding = 0 cellspacing = 0>
                    <?php
                       for ($i = 0; $i < count($_GET["files"]); $i++){
                          $file = $list[$_GET["files"][$i]];
                          if (in_array(basename($file["name"]),$systemfile)) continue; 
                    ?>

                             <input type = hidden name = "files[]" value = "<?php echo $_GET["files"][$i]; ?>">

                             <tr><td align = "left" nowrap>&nbsp;<b>
                                     <?php echo basename($file["name"]); ?> </b>&nbsp;<td>&nbsp;>&nbsp;<td>&nbsp;
                                       <?php
                                             $r_f_name=basename($file["name"]);
                                             $r_f_name=explode(".", $r_f_name, 2);
                                             $r_f_to_this_name=trim(str_rot13($r_f_name[0]) . "." . $r_f_name[1]);
                                       ?>

                                             <input style = "width:350px;" name = "newName[]" size = 25 value = "<?php echo $r_f_to_this_name;?>">
                                         </td>
                             </tr>
                    <?php
                       }
                    ?>
                   </table></td><td><input type = submit value = "<?php echo $langrename;?>"></td>
           </tr>

           <tr><td></td>
           </tr>
       </table>
   </form>
<?php
}
?>