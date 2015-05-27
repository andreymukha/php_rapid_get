<?php
    /* модуль архивации rar 2 */
if (!defined('RAPIDGET')) {
    die ("not load primary script");
}
    // compress
    $compresslevel=(int)$_REQUEST['compression_level'];
    $compresslevel = $compresslevel>5?5:$compresslevel;
    
    // archname
    $archname=preg_replace("![&?\"=']!", "_", basename($_REQUEST['archname']));
    $file=$list[$_GET["files"][0]];                                                         
    $files_str="";
    $cmd = ($is_windows) ? "rar.exe" : getcwd().DIRECTORY_SEPARATOR."rar";
    if(!$is_windows)
    {
        //fix archname
        $archname = escapeshellarg($archname);
        
        if(!file_exists("/usr/bin/rar") & !file_exists("/bin/rar"))
            $cmd="./rar";
    }
    else
        $archname=$dir."\\".$archname;
    $sys=(($cmd=="rar.exe") ? "" : "cd $workpath; nice -n 19 ")."$cmd a -y -m$compresslevel -ep1";
    if(!empty($_REQUEST['comment']))
        {
            $comment=transtr($_REQUEST['comment']);
            $h=fopen(".comments", "w");
            fputs($h, $comment);
            fclose($h);
            $sys.=" -z.comments";
        }
        if(!empty($_REQUEST['password']))
        {
            $password=$_REQUEST['password'];
            $password = $is_windows?$password:escapeshellarg($password);
            $sys.=" -p$password";
        }
        if(!empty($_REQUEST['recovery']))
        {
            if(is_numeric($_REQUEST['recovery']))
            {
                if($_REQUEST['recovery']>10) $_REQUEST['recovery']=10;
                $proc=$_REQUEST['recovery'];
                $sys.=" -rr$proc"."p";
            }    
        }
        if($_REQUEST['block'])
        {
            $sys.=" -k";
        }
        if(!empty($_REQUEST['part_size']) && is_numeric($_REQUEST['part_size']))
        {
            $psize=$_REQUEST['part_size'];
            $sys.=" -v$psize"."m";
        }
        for($i = 0; $i < count($_GET["files"]); $i++)
        {
            $file = $list[$_GET["files"][$i]];
            if (in_array(basename($file["name"]),$systemfile)) continue;
            $files_str.=(($is_windows) ? " ".$file["name"] : " ".escapeshellarg(basename($file["name"])));
        }

        if($is_windows)
        {
            #запуск архиватора в бекграунде в windows пока совершить не удается
            /*$localdir=getcwd();
            $sys='cmd /C "'.$localdir.'\\'.$sys.' '.$archname.' '.$files_str.' > '.$archname.'.log"';
            $WshShell = new COM("WScript.Shell");
        $oExec = $WshShell->Run($sys, 0, false);*/
        echo "
        <script>
        var inner='';
        function progress()
        {
            switch(inner)
            {
                case '|': inner='/'; break;
                case '/': inner='-'; break;
                case '-': inner='\\\'; break;
                case '\\\': inner='|'; break;
                default: inner='|';
            }
            document.title=' Архивирую ['+inner+'] ';
        }
        pid=window.setInterval('progress()', 300);
        </script>";
        flush();
        $result=`$sys "$archname" $files_str`;
        echo "<script>clearInterval(pid)</script>";
        }    
        else{
            $logname = substr(md5(mktime().basename($archname)),8,8);
            $q = "$sys $archname $files_str > ".$logname.".log &";
            @shell_exec("$q");
        }
        $pr=0;
?>
    <script>
              function pr(percent){

                                                      document.getElementById("percent").innerHTML = '<b>' + percent + '%</b>';
                                                      document.getElementById("progress").style.width = percent + '%';
                                                      return true;
                                  }
         </script>                         
                 <center> Архивация начата <?php echo $psize ? "[многотомная]" : ""; ?></center>
                 <table cellspacing=0 cellpadding=0 style="FONT-FAMILY: Tahoma; FONT-SIZE: 11px;">
                 <tr>
                   <td></td>
                   <td>
                     <div style='border:#BBB 1px solid; width:300px; height:10px;'>
                       <div id=progress style='background-color:#FF3300; margin:1px; width:0%; height:8px;'>  </div>
                     </div>
                   </td>
                   <td></td>
                  </tr>
                  <tr>
                                                      <td align=left id=received></td>
                                                      <td align=center id=percent>0%</td>
                                                     <td align=right id=speed></td>
                   </tr>
                </table>
<?php   
    $log=""; $i=0;
if(!$is_windows)
{    
    while($pr!=100 && !ereg("Calculating the control sum [^\n]+\nDone", $log) && $i<30 && strpos($log, "Locking archive\nDone")===false)
    {
        $log=file_get_contents($workpath.DIRECTORY_SEPARATOR.$logname.".log"); 
        $log=ereg_replace("Adding data recovery record[0-9 %]*%", "", $log);#это чтоб прогресс-бар адекватный рисовался
        $log=ereg_replace("Calculating the control sum[0-9 %]*%", "", $log);
        $tpr=$pr;
        if(preg_match_all("# *([0-9]+)%#", $log, $array)) $pr=$array[1][count($array[1])-1];
        else $pr=0;
        if($tpr==$pr) $i++; else $i=0;
        echo "<script>pr($pr)</script>"; flush();
        sleep(2);
    }
    //echo "<script>pr(100)</script>";
    @unlink($workpath.DIRECTORY_SEPARATOR.$logname.".log");
}    
        $result=str_replace("\n", "<br>", $log);
        if(strpos($result, "WARNING:")===false && strpos($result, "ERROR:")===false && $i<10)
        {
            if(!empty($result))
            {
                echo "<center>Архивация успешно завершена<br></center>";echo "<script>pr(100)</script>";
                if($_REQUEST['delifsuccess'])
                {
                    for($i = 0; $i < count($_GET["files"]); $i++)
                    {    
                        $file = $list[$_GET["files"][$i]];
                        @unlink($file["name"]);
                       }
                }
            }    
        }    
        else
        {
            echo "<center><font color='red'>Возникли ошибки при архивации файлов</font><br>Лог:<br>$result</center>";    
        }
        _create_list(true);
        
/*
This file was generated by php_mega_coder utility ©. Artificial intelligence was here.
*/
?>