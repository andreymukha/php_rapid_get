<?php
  /* модуль массовой рассылки 1 */
if (!defined('RAPIDGET')) {
    die ("not load primary script");
}
    //if($_GET["partSize"]>$max_mailsize){echo "Неправельный размер части!";$_GET["partSize"]=$max_mailsize;}
    //if($_GET["partSize"]<1){echo "Неправельный размер части!";$_GET["partSize"]=$max_mailsize;}
    //if(!$_GET["split"]){echo "Отправка файлов возможна только частями по ".$max_mailsize."MB!";$_GET["split"]='on';}
    echo '<table align="center" style="width:450px;">';
    $_GET["partSize"] = ((isset($_GET["partSize"]) & $_GET["split"] == "on") ? $_GET["partSize"] * 1024 * 1024 : FALSE);
    $emails=trim($_POST["emails"]);
    $v_mails = explode("\n",$emails);
    $v_min=count((count($_GET["files"])<count($v_mails)) ? $_GET["files"] : $v_mails);

    for($i = 0; $i < $v_min; $i++)
      {
        $file = $list[$_GET["files"][$i]];
        if (in_array(basename($file["name"]),$systemfile)) continue; 
        
        $v_mail = trim($v_mails[$i]);
      if(!checkmail($v_mail))
       {
        echo "<b>$v_mail</b> - ".$langinvalidmail."<br><br>";
       }
      elseif(file_exists($file["name"]))
          {
            if(xmail("$fromaddr", $v_mail, "File ".basename($file["name"]), "File: ".basename($file["name"])."\r\n"."Link: ".$file["link"].($file["comment"] ? "\r\nComments: ".str_replace("\\r\\n", "\r\n", $file["comment"]) : ""), $file["name"], $_GET["partSize"], $_GET["method"]))
              {
                if ($_GET["del_ok"])
                  {
                   if(@unlink($file["name"]))
                     {
                      $v_ads=" ".$langanddelete." !";
                      unset($list[$_GET["files"][$i]]);
                     }
                   else
                     {
                      $v_ads=", <b>".$langbutnotdelete." !</b>";
                     };
                  } else $v_ads=" !";
                echo "<script language=\"JavaScript\">mail('".$langfile." <b>".basename($file["name"])."</b> ".$langsentaddress." <b>".$v_mail."</b>".$v_ads."', '".md5(basename($file["name"]))."');</script>\r\n<br>";
              }
            else
              {
                echo $langersend."<br>";
              }
          }
        else
          {
            echo $langfile." <b>".$file["name"]."</b> ".$langnotfound."<br><br>";
          }
      }
     echo "</table><div>";
     if (count($_GET["files"])<count($v_mails))
      {
       echo "<b>".$langattention."</b> ".$langmuchm."<br><br><b>";
       for($i = count($_GET["files"]); $i < count($v_mails); $i++)
        {
          $v_mail = trim($v_mails[$i]);
          echo "$v_mail.</b><br><br>";
        };
        echo "</b><br>";
      }
 elseif (count($_GET["files"])>count($v_mails))
      {
       echo "<b>".$langattention."</b> ".$langlittlem."<br><br><b>";
       for($i = count($v_mails); $i < count($_GET["files"]); $i++)
        {
        $file = $list[$_GET["files"][$i]];
        if(file_exists($file["name"]))
          {
            echo basename($file["name"])."<br><br>";
          }
        else
          {
            echo "</b>".$langfile." <b>".$file["name"]."</b> ".$langnotfound."<b><br><br>";
          }
        }
       echo "</b><br>";
      };

   if ($_GET["del_ok"])
    {
     if(!updateListInFile($list))
       {
          echo $langcouldnupdate."!<br><br>";
       }
    }
  echo "</div>";  
?>
