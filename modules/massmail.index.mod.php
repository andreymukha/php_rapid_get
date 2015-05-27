<?php
  $systemfile[] = "massmail.mod.php";
  $systemfile[] = "massmail_go.mod.php";
  
  if (!$mail_not_support || $smtp_mail){
        $menu_action['massmail'] = $langmassmail;
  }  
?>
