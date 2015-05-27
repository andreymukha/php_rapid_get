<?php
  $systemfile[] = "mail.mod.php";
  $systemfile[] = "mail_go.mod.php";
  
  if (!$mail_not_support || $smtp_mail){
        $menu_action['mail'] = $langemailf;
  }  
?>
