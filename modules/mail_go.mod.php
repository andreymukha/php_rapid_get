<?php
/* модуль отправки на мыло - 2 */
if (!defined('RAPIDGET')) {
    die ("not load primary script");
}


$parts_need = (isset($_REQUEST['partnumber2']) && (@trim($_REQUEST['partnumber2']) != '') && $_GET["split"] == "on" && @isset($_GET["partSize"]) && @is_numeric($_GET["partSize"])) ? @explode(',',$_REQUEST['partnumber2']) : false;
$crypt_need = (isset($_REQUEST['cryptmail']) && $_REQUEST['cryptmail']=='on') ? true : false;

if(!checkmail($_GET["email"]=trim($_GET["email"])))
  {
    echo "'<b>".$_GET["email"]."</b>' $langinvalidmail<br><br>";
  }
else
  {
    echo "<table cellpadding=\"1\" cellspacing=\"1\"><thead><tr bgcolor=\"#A6A6A6\"><th>$langpart<th>Mail<th>$langstatus</tr></thead><tbody>";
    $_GET["partSize"] = ((isset($_GET["partSize"]) && $_GET["split"] == "on") ? $_GET["partSize"] * 1024 * 1024 : FALSE);
    for($i = 0; $i < count($_GET["files"]); $i++)
      {
        if ($i > 0) insertmaildelay(is_numeric($_GET["mdelay"]) ? $_GET["mdelay"] : 0);

        $file = $list[$_GET["files"][$i]];
        if(file_exists($file["name"]) & !in_array(basename($file["name"]),$systemfile))
          {
            $mailed=xmail("$fromaddr", $_GET[email], "File ".basename($file["name"]), "File: ".basename($file["name"])."\r\n"."Link: ".$file["link"].($file["comment"] ? "\r\nComments: ".str_replace("\\r\\n", "\r\n", $file["comment"]) : ""), $file["name"], $_GET["partSize"], $_GET["method"], (is_numeric($_REQUEST["mdelay"]) ? $_REQUEST["mdelay"] : 0),$parts_need,$crypt_need);
             if ($_GET["del_ok"]){
                if (!$mailed) { 
                    echo '<tr bgcolor="#A6A6A6"><td colspan=3 style="color :#F00">File <b>'.basename($file["name"]).'</b> Not Deleted</td></tr>';
                }
             elseif (@unlink($file["name"])){
                unset($list[$_GET["files"][$i]]);
                $need_update_list=true;
                echo '<tr bgcolor="#A6A6A6"><td colspan=3 style="color :#0A0">'.$langfile.' <b>'.basename($file["name"]).'</b> '.$langdeleted.'</td></tr>';
            }else{
                echo '<tr align="center" bgcolor="#A6A6A6"><td colspan=3 style="color :#F00">'.$langerrordelete.' <b>'.basename($file["name"]).'</b></tr>';
            };
            }
          }
        else
          {
            echo '<tr><td align="center" colspan=3 style="color :#FF0000">'.$langfile.' <b>'.basename($file["name"]).'</b> '.$langnotfound.'</td></tr>';
          }
      }
     echo "</tbody></table>";
     if ($need_update_list){
        if(!updateListInFile($list)) echo "$langeupdatelist<br><br>";
     }
  }  
?>
