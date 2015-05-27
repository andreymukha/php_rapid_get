<?php
  $systemfile[] = "space.mod.php";

  if (!$auth_user || $admin_logins===false || (($admin_logins !== false ) && in_array($auth_user,$admin_logins))){
        $menu_action['space'] = $languspace;
  }

?>