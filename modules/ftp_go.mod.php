<?php
  /* модуль отправки на ftp - 2 */
if (!defined('RAPIDGET')) {
    die ("not load primary script");
}

$ftp = new ftp();
if(!$ftp->SetServer($_POST["host"], (int)$_POST["port"]))
  {
      $ftp->quit();
      echo " ".$langerconserver." ".$_POST["host"].":".$_POST["port"].".<br>".
           "<a href=\"javascript:history.back(-1);\">".$langgoback."</a><br><br>";
  }
else
  {
      echo " ".$langconecting." <b>".$_POST["host"]."</b>...";
      flush();
      if(!$ftp->connect())
        {
            $ftp->quit();
            echo "<br>".$langerconserver." ".$_POST["host"].":".$_POST["port"].".<br>".
                 "<a href=\"javascript:history.back(-1);\">".$langgoback."</a><br><br>";
        }
      else
        {
            if (!$ftp->login($_POST["login"], $_POST["password"]))
              {
                  $ftp->quit();
                  echo "<br> ".$langwrongpas." <b>".$_POST["login"].":".$_POST["password"]."</b>.<br>".
                        "<a href=\"javascript:history.back(-1);\">".$langgoback."</a><br><br>";
              }
            else
              {
                  if(!$ftp->chdir($_POST["dir"]))
                    {
                        $ftp->quit();
                        echo "<br>".$langerrorfofol."<b>".$_POST["dir"]."</b>.<br>".
                              "<a href=\"javascript:history.back(-1);\">".$langgoback."</a><br><br>";
                    }
                  else
                    {
                        ?>
                        <br>
                          <div id="status"></div><br>
                          <table cellspacing=0 cellpadding=0 style="FONT-FAMILY: Tahoma; FONT-SIZE: 11px;">
                            <tr>
                              <td></td>
                              <td>
                                <div style='border:#BBB 1px solid; width:300px; height:10px;'>
                                  <div id=progress style='background-color:#009; margin:1px; width:0%; height:8px;'>
                                  </div>
                                </div>
                              </td>
                              <td></td>
                            <tr>
                            <tr>
                              <td align=left id=received>0 KB</td>
                              <td align=center id=percent>0%</td>
                              <td align=right id=speed>0 KB/s</td>
                            </tr>
                          </table>
                          <script>
                            function pr(percent, received, speed,out){
                              document.getElementById("received").innerHTML = '<b>' + received + '</b>';
                              document.getElementById("percent").innerHTML = '<b>' + percent + '%</b>';
                              if(percent > 90){percent = percent - 1;}
                              document.getElementById("progress").style.width = percent + '%';
                              document.getElementById("speed").innerHTML = '<b>' + speed + ' KB/s</b>';
                              return true;
                            }

                            function changeStatus(file, size) {
                              document.getElementById("status").innerHTML = 'Loading File <b>' + file + '</b> ,Size <b>' + size + '</b>...<br>';
                            }
                          </script>
                        <br>
                        <?php
                        $FtpUpload = TRUE;
                        for($i = 0; $i < count($_GET["files"]); $i++)
                          {
                              $file = $list[$_GET["files"][$i]];
                              if (in_array(basename($file["name"]),$systemfile)) continue; 
                              
                              echo "<script>changeStatus('".basename($file["name"])."', '".$file["size"]."');</script>";
                              $FtpBytesTotal = getfilesize($file["name"]);
                              $FtpChunkSize = GetChunkSize($FtpBytesTotal);
                              $FtpTimeStart = getmicrotime();
                               $FtpUploadBytesSent = 0;
                              if($ftp->put($file["name"], basename($file["name"])))
                                {
                                    $time = round(getmicrotime() - $FtpTimeStart);
                                    $time = $time ? $time : 1;
                                    $speed = round($FtpBytesTotal / 1024 / $time, 2);
                                    echo "<script>pr(100, '".bytesToKbOrMb($FtpBytesTotal)."', ".$speed.",'')</script>\r\n";
                                    flush();

                                    if($_GET["del_ok"])
                                     {
                                      if(@unlink($file["name"]))
                                       {
                                        $v_ads=" and deleted ";
                                        unset($list[$_GET["files"][$i]]);
                                       }
                                      else
                                       {
                                        $v_ads=", but <b>not deleted </b>";
                                       };
                                     } else $v_ads=" ";

                                    echo "File <a href=\"ftp://".$_POST["login"].":".$_POST["password"]."@".$_POST["host"].":".$_POST["port"].
                                          $_POST["dir"]."/".basename($file["name"])."\"><b>".basename($file["name"])."</b></a> Successfully uploaded$v_ads!".
                                          "<br>Time: <b>".sec2time($time)."</b><br>Average speed: <b>".$speed." KB/s</b><br><br>";
                                }
                              else
                                {
                                    echo "Couldn't upload the file <b>".basename($file["name"])."</b>!<br>";
                                }
                          }
                        $ftp->quit();
                    }
              }
        }

     if ($_GET["del_ok"])
             {
                     if(!updateListInFile($list)) echo "$langeupdatelist<br><br>";
             }
  }  
?>
