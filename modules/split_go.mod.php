<?php
/* модуль разбивки на части - 2 */
if (!defined('RAPIDGET')) {
    die ("not load primary script");
}

__crc32_init_table();
for($i = 0; $i < count($_GET["files"]); $i++)
 {
  $file = $list[$_GET["files"][$i]];
  if (!@file_exists($file["name"]))
   { echo "$langfile <b>".basename($file["name"])."</b> $langnotfound<br><br>"; }
elseif (!@is_readable($file["name"]))
   { echo "$langfile <b>".basename($file["name"])."</b> $langnotread<br><br>"; }
else
   {
    $split_ok=true;

    $partSize = round(urldecode($_GET["partSize"][$i]) * 1024 * 1024);

    $saveTo = urldecode($_GET["saveTo"][$i]);
    $fileSize = getfilesize($file["name"]);

    $totalParts = ceil($fileSize / $partSize);

    $TParts=str_pad($totalParts, 3, "0", STR_PAD_LEFT);
    $TParts = ($TParts == "000") ? '' : '.'.$TParts;

    echo "$langstartsplit <b>".basename($file["name"])."</b> of parts ".bytesToKbOrMb($partSize).", $langusmethodtotcom<br>";
    echo "$langtotalparts <b>".(($totalParts == 0) ? "Unknown" : $totalParts)."</b><br>";
    $fileTmp = $fileNamePerman = basename($file["name"]);
    $fileName = $fileTmp;#substr($fileTmp, 0, strrpos($fileTmp, "."));

    $path = $workpath.DIRECTORY_SEPARATOR; #stripslashes($saveTo.(strstr(realpath("./"), ":") ? "\\\\" : "/"));

    $Fsource=fopen($file["name"],'r');

    echo "<br>$langsection ";

    $crc=false;
    $split_ok=true;
    $read_size=min($partSize, $_max_split_buffer);

    $parts_p_count=ceil($partSize / $read_size);

    $total_write=0;
    $total_read=0;

    $j=0;
    $created_file=array();
    while (!feof($Fsource)){
          $j++;
          $writed=0;
          echo ($j == 1 ? $j : ', '.$j); flush();
          $num = str_pad($j, 3, "0", STR_PAD_LEFT);
          $file_p=@fopen($path.$fileName."$TParts.$num",'w+');

          $created_file[]=$path.$fileName."$TParts.$num";

          $kk=0;

          while (!feof($Fsource) and ($kk < $parts_p_count))
              {
                  $read_size_2 =(($kk+1) == $parts_p_count) ? ($partSize - ($read_size*$kk)) : $read_size;

                  $kk++;

                  $fileChunk=fread($Fsource,$read_size_2+3);

                  usleep(10);
                  echo "<!--$kk-->"; flush();

                if (!$fileChunk) { $split_ok=false; echo "<br>Ahtung Minen! Error read file.<br><br>"; break; }
                $ch_len=strlen($fileChunk);
                $total_read+=$ch_len;


                  if ($crc === false) { $crc=crc32($fileChunk)^0xFFFFFFFF; }
                          else
                      {
                        $lw = min(4,$ch_len);

                         for ($wwq=0; $wwq<$lw; $wwq++) { $crc=(($crc >> 8) & 0x00ffffff) ^ $__crc32_table[($crc & 0xFF) ^ ord($fileChunk[$wwq])]; }

                         if ($ch_len>4)
                             {
                                 $crc=__crc32_decode($crc);
                                 $buff_[0]=$fileChunk[0];$fileChunk[0]=$crc[0]; $buff_[1]=$fileChunk[1];$fileChunk[1]=$crc[1];
                                 $buff_[2]=$fileChunk[2];$fileChunk[2]=$crc[2]; $buff_[3]=$fileChunk[3];$fileChunk[3]=$crc[3];
                                 $crc=crc32($fileChunk)^0xFFFFFFFF;

                                 $fileChunk[0]=$buff_[0];$fileChunk[1]=$buff_[1];
                                 $fileChunk[2]=$buff_[2];$fileChunk[3]=$buff_[3];
                            }
                    }

                $write_2=@fwrite($file_p, $fileChunk);
                if (($write_2 === false) or ($ch_len != $write_2))
                    {
                        echo "<br>$langnotwritepf <b>".$fileName."$TParts.$num</b> !<br><br>";
                        $split_ok=false;
                        break;
                    }
                $writed+=$write_2;
                $total_write+=$write_2;
            }

        @fclose($file_p);

        if ($split_ok === true)
            {
                $time = explode(" ", microtime());
                $time = str_replace("0.", $time[1], $time[0]);
                $list[$time] = array("name"    => $path.$fileName."$TParts.$num",
                                     "size"    => bytesToKbOrMb($writed),
                                     "date"    => $time,
                                     "comment" => ($j)."$langbytparts (from $totalParts) files ".$fileNamePerman);
            }
                else
            {
                echo "<br>$langdelcrfil\n";
                for ($dd=0; $dd< count($created_file); $dd++)
                    {
                        @unlink($created_file[$dd]);
                    }
                break;
            }
      }

    if ($split_ok === true)
        {
            @fclose($Fsource);
            $crc^=0xFFFFFFFF;
            $crc=strtoupper(dechex($crc));
            $crc = str_repeat("0", 8 - strlen($crc)).$crc;

            if(!@write_file($path.$fileName."$TParts.crc", "filename=".basename($file["name"])."\r\n"."size=$total_write\r\n"."crc32=".$crc."\r\n"))
                   {
                     echo "<br>$langnotsplitfp<b>$fileName$TParts.crc"."</b> !<br><br>";
                     $split_ok=false;
                   }
                      else
                   {
                    $time = explode(" ", microtime());
                    $time = str_replace("0.", $time[1], $time[0]);
                    $list[$time] = array("name"    => $path.$fileName."$TParts.crc",
                                        "size"    => bytesToKbOrMb(getfilesize($path.$fileName."$TParts.crc")),
                                        "date"    => $time,
                                         "comment" => "$langtitlepartf ".$fileNamePerman);
                   }
           }

   }

    unset($fileName);
    if ($_GET["del_ok"])
     {
       if (!$split_ok )
        {
         echo "<br>$langsourcedelerror<br><br>";
        }
   elseif(@unlink($file["name"]))
        {
          unset($list[$_GET["files"][$i]]);
          echo "<br>$langsourcefdel<br><br>";
        }
       else
        {
         echo "<br>$langsourcef <b>$langnotdel !</b><br><br>";
        };
     };
    if(!updateListInFile($list)) echo "<br>$langeupdatelist<br><br>";
   }  
?>
