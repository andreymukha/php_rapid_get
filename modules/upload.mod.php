<?php
  /* модуль аплоада */
if (!defined('RAPIDGET')) {
    die ("not load primary script");
}
    if(count($_GET["files"]) < 1)
        {
            echo "<div id='x1' style='padding:10px; border: 1px dashed red;'> $langselectf </div>";
        }
            else
        {

            $d = opendir(getcwd().DIRECTORY_SEPARATOR."upload".DIRECTORY_SEPARATOR."plugin");
            while (false !== ($modules = readdir($d)))
                {
                    if($modules!="." && $modules!="..")
                        {
                            if(is_file(getcwd().DIRECTORY_SEPARATOR."upload".DIRECTORY_SEPARATOR."plugin".DIRECTORY_SEPARATOR.$modules))
                                {
                                    if (strpos($modules,".index.php")) include_once(getcwd().DIRECTORY_SEPARATOR."upload".DIRECTORY_SEPARATOR."plugin".DIRECTORY_SEPARATOR.$modules);
                                }
                        }
                }
        
            if (empty($upload_services)) 
                {
                    echo "<span style='color:red'>$langnosuppupser</span>";
                }
                    else
                {
                    sort($upload_services);
                    reset($upload_services);
                    foreach($upload_services as $upl){
                        
                        $uploadtype .= "shared[shared.length] = new Array('".$upl."',".($max_file_size[$upl]?$max_file_size[$upl]:"'Unlim'").");\n";                        
                    }
?>
<script>
var upservice = new Array();

function replace_str(txt,cut_str,paste_str){ 
    var f=0;
    var ht='';
    ht = ht + txt;
    f=ht.indexOf(cut_str);
    while (f!=-1){ 
        //цикл для вырезания всех имеющихся подстрок 
        f=ht.indexOf(cut_str);
        if (f>0){
            ht = ht.substr(0,f) + paste_str + ht.substr(f+cut_str.length);
        };
    };
return ht;
};

function fill(id){
    sel = d.getElementById(id);
    for(var i=0;i<shared.length;i++){
        oOption = d.createElement("option");
        oOption.setAttribute("value", shared[i][0]);
        oOption.appendChild(d.createTextNode(replace_str(shared[i][0],'_',' ')+'('+shared[i][1]+(((shared[i][1]+'').substr(0,2)=='un'?")":' Mb)'))));
        sel.appendChild(oOption);                            
    }
}

function fastch(id){
    upl = d.getElementById('upload');
    sel = d.getElementsByTagName('select');
    for(var j=0;j<sel.length;j++){
        if(sel[j].id.substr(0,2)=='d_'){
            sel[j].selectedIndex = id;            
        }    
    }
}

var shared = new Array();;
var d = document;            
<?php echo $uploadtype; ?>


function openwinup(id)
    {
        var options = "width=700,height=250,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,copyhistory=no";
        document.getElementById('proxyupload').value=document.getElementById('myproxy').value;
        win=window.open('', id, options);
        win.focus();
        return true;
    }
</script>
<div id='fastchoose' align=center>
    Быстрый выбор: <select id='choose' name='choose' onchange='fastch(this.selectedIndex)'>                
    </select>
    <script>fill('choose');</script>
</div>
<hr />
<?php
                    for($i = 0; $i < count($_GET["files"]); $i++){
                            $file = $list[($_GET["files"][$i])];
                            if (in_array(basename($file["name"]),$systemfile)) continue;                            
                            $tid=substr(md5(basename($file)),8,8);
?>

        <div id='upload'>
        <form action=upload/index.php method=get target=<?php echo $tid ?> onSubmit="return openwinup('<?php echo $tid ?>');">
            <table align="center">                                      
                <tr>
                    <td><b><?php echo basename($file["name"])."</b>  , ". $file["size"] ?></b></td>
                    <td>
                        <select name=uploaded id='d_<?php echo $tid; ?>'></select>
                        <script>fill('d_<?php echo $tid; ?>');</script>
                    </td>
                    <td><input type=submit value=<?php echo $langupload2;?>></td>
                </tr>
                <tr>
                    <td colspan="3" align="center">
                        <input type="checkbox" name="protect" value="1"> <?php echo $langprotectdl;?>
                        <input type=hidden name=filename value='<?php echo base64_encode($file["name"]); ?>'>
                        
                    </td>
                </tr>
            </table>
        </form>
        </div>
<?php
                        }
?>
</table>
<?php
                }
        }       
?>
