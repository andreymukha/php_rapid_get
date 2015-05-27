<?php
/* модуль упаковка файлов 2 */
if (!defined('RAPIDGET')) {
   die("not load primary script");
}

if (count($_GET["files"]) < 1) {
   echo "<div id='x1' style='padding:10px; border: 1px dashed red;'> $langselectf </div>";
}else {
   $arc_name=$_GET["arc_name"];
   if (!$arc_name){
      echo "$langentfilen<br><br>";
   }else{
       if (file_exists($workpath.DIRECTORY_SEPARATOR.$arc_name)){
          echo "$langfile <b>'$arc_name'</b> $langalreexis<br><br>"; 
       }else{

           $pack_ok=true;
           for ($i = 0; $i < count($_GET["files"]); $i++){
              $file = $list[$_GET["files"][$i]];

              if (file_exists($file["name"]) & !in_array(basename($file["name"]), $systemfile)) {
                 $v_list[] = $file["name"];
              }else {
                 echo "$langfile <br>" . basename($file["name"]) . "</b> $langnotfound<br><br>";
                 $pack_ok=false;
              }
           }

           if (!$pack_ok){
              echo "$langabthesefnotf<br>'<b>" . implode("</b>', '<b>", $v_list) . "</b>'<br><br>";
           }else{               

               $path=$_GET["path"];
               $arc_name=$path.DIRECTORY_SEPARATOR.$arc_name;
               

               switch ($archive_library){
                   case "PEAR-TAR":
                   //echo getcwd().DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR."Tar.php";
                       if (!file_exists(getcwd().DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR."Tar.php") || !file_exists(getcwd().DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR."PEAR.php")){
                          echo ' ' . $langcomponents . ' "Tar.php" or "PEAR.php" ' . $langnotfound . '<br><br>';
                          echo 123;
                       }else{
                           include_once(getcwd().DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR."PEAR.php"); 
                           include_once(getcwd().DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR."Tar.php");
                           $arc=&new Archive_Tar($arc_name);
                           $pack_ok=$arc->createModify($v_list, null, $path . DIRECTORY_SEPARATOR);
                           $pack_ok &= (count($v_list) == count($tmp = $arc->listContent()));

                           if (is_dir($file["name"]) === true){
                              echo "<div id='x1' style='padding:10px; border: 1px dashed red;'>An error in archived. Was packed working directory path. | Ошибка в архиве. Был упакован рабочий путь к каталогу.</div>";
                              $pack_ok=1;
                           }
                       }
                       break;

                   case "PCL-ZIP":
                           if (!file_exists(getcwd() . DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR . "pclzip.lib.php")){
                              echo ' ' . $langcomponents . ' "pclzip.lib.php" ' . $langfound . '<br><br>';
                           }else{
                                include_once(getcwd() . DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR . 'pclzip.lib.php');
                                $arc=new PclZip($arc_name);
                                if ($_GET["use_compress"]) {
                                    $pack_ok = ($arc->create($v_list, PCLZIP_OPT_REMOVE_PATH, $path . DIRECTORY_SEPARATOR) != 0);
                                }else {
                                    $workpath;
                                    $pack_ok = ($arc->create($v_list, PCLZIP_OPT_REMOVE_PATH, $path . DIRECTORY_SEPARATOR, PCLZIP_OPT_NO_COMPRESSION) != 0);
                                }

                                if (!$pack_ok) {
                                    echo ' ' . $langpackingerror . '<b>' . $arc->errorInfo(true) . '</b>';                                    
                                }
                           }
                           break;
               }

               if (!$pack_ok){
                    echo 'Archive ' . $arc_name . ' not created.<br>Check your hosting support library ' . $archive_library . '<br>';

                    if (!file_exists($workpath.DIRECTORY_SEPARATOR.$arc_name)) {
                         echo 'Check permisions on directory (try chmod the folder to 777)<br><br>';
                    }elseif (!@unlink($workpath.DIRECTORY_SEPARATOR.$arc_name)) {
                         echo ' Error to delete broken archive<br><br>';
                    }else {
                         echo ' Broken archive deleted<br><br>';
                      }
               }else{

                   echo " " . $langarchive . " <b>" . basename($arc_name) . "</b> " . $langwassucreated . "<br><br>";

                   $time=explode(" ", microtime());
                   $time=str_replace("0.", $time[1], $time[0]);

                   $list[$time]=array
                       (
                       "name" => $arc_name,
                       "size" => bytesToKbOrMb(getfilesize($workpath.DIRECTORY_SEPARATOR.$arc_name)),
                       "date" => $time,
                       "link" => "",
                       "comment" => "$langarchive"
                       );

                   for ($i = 0; $i < count($_GET["files"]); $i++){
                      $file = $list[$_GET["files"][$i]];

                      if ($_GET["del_ok"]){
                         if (@unlink($file["name"])){
                            unset($list[$_GET["files"][$i]]);
                            $v_ads=" $langanddelete";
                         }
                         else{
                            $v_ads = ", <b>$langbutnotdelete !</b></b>";
                         }

                         ;
                      }

                      echo " " . $langfile . " <b>" . basename($file["name"]) . "</b> $langwaspacked $v_ads<br><br>";
                   }

                   ;

                   if (!updateListInFile($list)) {
                      echo "$langcouldnupdate!<br><br>";
                   }
               }
           } 
       }
   }
}
?>