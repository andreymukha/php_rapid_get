<?php
  $systemfile[] = "rar.mod.php";
  $systemfile[] = "rar_go.mod.php";
  $systemfile[] = "rar";
  
  if ($is_windows || (!$is_windows & !$max_4gb)) $menu_action['rar'] = "Архивация Rar";
?>
