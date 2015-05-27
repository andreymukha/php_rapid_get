<?php
  /* модуль место на харде */
if (!defined('RAPIDGET')) {
    die ("not load primary script");
}

if ($loginpath === false)
{
    $ret=@filesinfolder($workpath.DIRECTORY_SEPARATOR,false,true);
?>
<table width=300 border=1 cellpadding=0 cellspacing=0><caption><?php echo $langspaceuworkpatc;?></caption>
<tr><td width=100 nowrap align=center><?php echo $langcountff;?></td><td align=center><?php echo $langspaceu;?></tr>
<tr><td width=100 nowrap align=center><b><?php echo $fcounts.' \\ '.$dcounts?></b></td><td nowrap align=center><b><?php echo bytesToKbOrMb($fsumm); ?></b></tr>
<?php
}
    else
{
?>
<table width=400 border=1 cellpadding=0 cellspacing=0><caption><?php echo $langspaceuworkpatc;?></caption>
<tr><td width=100 align=center><?php echo $langsubuser;?></td><td width=100 nowrap align=center><?php echo $langcountff;?></td><td align=center><?php echo $langspaceu;?></tr>
<?php
    $dname=dirname($workpath.DIRECTORY_SEPARATOR);
    $dir_ = dir($dname);
    while(false !== ($file = $dir_->read()))
        {
            if($file{0} == "." || in_array($file,$systemfile) || !is_readable($dname.DIRECTORY_SEPARATOR.$file)) continue;
            $file = $dname.DIRECTORY_SEPARATOR.$file;

            if (is_dir($file))
                {
                    $rsumm = filesinfolder($file.DIRECTORY_SEPARATOR,true,true);

                    if ($fcounts === 0) continue;

                    $all_fcount+=$fcounts;
                    $all_dcount+=$dcounts;
                    $all_fsumm+=$fsumm;


?>
<tr><td width=100 nowrap align=center><b><?php echo basename($file); ?></b><td width=100 nowrap align=center><b><?php echo $fcounts.' \\ '.$dcounts?></b></td><td nowrap align=center><b><?php echo bytesToKbOrMb($fsumm); ?></b></tr>
<?php
                }
                    else
                {
                    continue;
                }

            if ($rsumm === false)
                {
                    $dir->close();
                    break;
                }
        }

?>
<tr><td width=100 nowrap align=center><b>TOTAL<td width=100 nowrap align=center><b><?php echo $all_fcount.' \\ '.$all_dcount?></b></td><td nowrap align=center><b><?php echo bytesToKbOrMb($all_fsumm); ?></b></tr>
<?php
}
?>
</table>  

