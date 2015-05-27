<?php
  $systemfile[] = "pack.mod.php";
  $systemfile[] = "pack_go.mod.php";

switch ($archive_library){
    case "PEAR-TAR" :
        if (file_exists(getcwd().DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR."Tar.php") && file_exists(getcwd().DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR."PEAR.php"))
            {
                $systemfile[]="PEAR.php";
                $systemfile[]="Tar.php";
            }
                else
            {
                $archive_library=false;
            }
    break;

    case "PCL-ZIP"  :
        if (file_exists(getcwd() . DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR . "pclzip.lib.php"))
            {
                $systemfile[]="pclzip.lib.php";
            }
                else
            {
                $archive_library=false;
            }
    break;

    default:
        $archive_library = false;
    break;
}
if ($archive_library) $menu_action['pack'] = $langpackf;  
?>
