<?php
   /* модуль склейки  файлов - 2 */
if (!defined('RAPIDGET')) {
    die ("not load primary script");
}

if ($_POST["yes"])
    {
    $fileContents = read_file($dir.DIRECTORY_SEPARATOR.$_POST["filename"]);
    $fc = strtoupper(dechex(crc32($fileContents)));
    $fc = str_repeat("0", 8 - strlen($fc)).$fc;
    if ($fc != $_POST["crc32"])
        {
        echo $langcrcersum."<br><br>";
        }
    else
        {
        echo $langfile." <b>".$_POST["filename"]."</b> ".$langmergeok."!<br><br>";
        }
    }
else
    {
    echo $langfile." <b>".$_POST["filename"]."</b> ".$langmergeok.", ".$langntested."<br><br>";
    }
$time = explode(" ", microtime());
$time = str_replace("0.", $time[1], $time[0]);
$list[$time] = array("name"    => $dir.DIRECTORY_SEPARATOR.$_POST["filename"],
         "size"    => bytesToKbOrMb($_POST["size"]),
         "date"    => time());
if(!updateListInFile($list)){
    echo "$langcouldnupdate<br><br>";
}  
?>
