<?php
   /* модуль склейки  файлов - 1 */
if (!defined('RAPIDGET')) {
    die ("not load primary script");
}

if (count($_GET["files"]) !== 1)
    {
    echo $langsetcrcf."<br><br>";
    }
else
    {
    $file = $list[$_GET["files"][0]];
    if (substr($file["name"], -4) !== ".crc")
        {
        echo $langsetcrcf."<br><br>";
        }
    else
        {
        $fs = @fopen($file["name"], "rb");
        if (!$fs)
            {
            echo $langnreadcrc."<br><br>";
            }
        else
            {
            flock($fs, LOCK_SH);
            while(!feof($fs))
                {
                $data .= trim(fgets($fs, 1024));
                if ($data === false) {break;}
                }
            flock($fs, LOCK_UN);
            fclose($fs);
            $tmp = explode("=", $data);
            $crc = array($tmp[0] => substr($tmp[1],0,-4), substr($tmp[1],-4) => substr($tmp[2],0,-5), substr($tmp[2],-5) => $tmp[3]);
            $filename = quotemeta($crc["filename"]);
            $crcarray=array_values($list);
            $crccount = count($crcarray);
            $files=array();
            for($crcsearch=0;$crcsearch <= $crccount;$crcsearch++)
                {
                    $unsplitfilename=$crcarray[$crcsearch]["name"];
                    $unsplitfilename=basename($unsplitfilename);

                    preg_match_all("'$filename.[0-9]{3}.[0-9]{3}'si",$unsplitfilename,$searcha);
                    if($searcha[0][0] and $searcha[0][0]!='')
                    {
                        $files[]= $searcha[0][0];
                    }
                }
            if (!is_array($files))
                {
                echo $langnfoudcrc."<br><br>";
                }
            else
                {
                $fs = fopen($dir.DIRECTORY_SEPARATOR.fixfilename($crc["filename"]), "xb");
                if (!$fs)
                    {
                    echo $langcantopen."<br><br>";
                    }
                else
                    {
                    flock($fs, LOCK_EX);
                    foreach ($files as $fn)
                        {
                        $fp = @fopen($dir.DIRECTORY_SEPARATOR.$fn, "rb");
                        flock($fp, LOCK_SH);
                        while (!feof($fp))
                            {
                            $data = fgets($fp, 1024);
                            if ($data === false) {break;}
                            else {fwrite($fs, $data);}
                            }
                        flock($fp, LOCK_UN);
                        fclose($fp);
                        }
                    flock($fs, LOCK_UN);
                    fclose($fs);
                    $fs = filesize($dir.DIRECTORY_SEPARATOR.fixfilename($crc["filename"]));
                    if ($fs != $crc["size"])
                        {
                        echo $langcrcersize."<br>$fs<br>";
                        }
                    else
                        {
                        ?>
<form method="post">
<input type="hidden" name="act" value="unsplit_go">
<input type="hidden" name="filename" value="<?php echo fixfilename($crc["filename"]); ?>">
<input type="hidden" name="path" value="<?php echo dirname($file["name"]); ?>">
<input type="hidden" name="size" value="<?php echo $crc["size"]; ?>">
<input type="hidden" name="crc32" value="<?php echo $crc["crc32"]; ?>">
<?php echo $landcrccheck;?><br><br>
<table>
<tr><td>
<input type="submit" name="yes" style="width:91px; height:23px" value="Recommended">
</td>
<td>&nbsp;&nbsp;&nbsp;</td>
<td>
<input type="submit" name="no" style="width:33px; height:23px" value="No">
</td></tr>
</table>
</form>
                        <?php
                        }
                    }
                }
            }
        }
    } 
?>
