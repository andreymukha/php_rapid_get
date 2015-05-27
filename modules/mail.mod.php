<?php
/* модуль отправки на мыло - 1 */
if (!defined('RAPIDGET')) {
    die ("not load primary script");
}

                        if(count($_GET["files"]) < 1)
                          {
                            echo "<div id='x1' style='padding:10px; border: 1px dashed red;'> $langselectf </div>";
                          }
                        else
                          {
                           if ($use_js_check_mails) {?>
<script>
function isEmail(str)
 {
  var supported = 0;
  if (window.RegExp)
   {
    var tempStr = "a";
    var tempReg = new RegExp(tempStr);
    if (tempReg.test(tempStr)) supported = 1;
   }
  if (!supported) return (str.indexOf(".") > 2) && (str.indexOf("@") > 0);
  var r1 = new RegExp("(@.*@)|(\\.\\.)|(@\\.)|(^\\.)");
  var r2 = new
  RegExp("^.+\\@(\\[?)[a-zA-Z0-9\\-\\.]+\\.([a-zA-Z]{2,3}|[0-9]{1,3})(\\]?)$");
  return (!r1.test(str) && r2.test(str));
 }

function trim(str) {
    return str.replace(/^\s*|\s*$/g,"");
}

function checkform()
{
 var eml=trim(document.getElementById("m_email").value);
 var erm="";
 if (!eml) { erm="<?php echo $langmailemp;?>"; }
      else { if (isEmail(eml)) { document.getElementById("form1").submit(); return; }                          else { erm="'"+eml+"' <?php echo $languncorrectmail;?>"; };
           };

 if (confirm(erm+"\n\n<?php echo $langcontinue;?>"))
  { document.getElementById("form1").submit(); };
}
</script>

<?php }; ?>

<form id="form1" method="post">
<input type=hidden name=act value="mail_go">
<?php
                            for($i = 0; $i < count($_GET["files"]); $i++){
                                    $file = $list[($_GET["files"][$i])];
                                    if (in_array(basename($file["name"]),$systemfile)) continue;
                                    
                                    $mform.='<input type=hidden name="files[]" value="'.$_GET["files"][$i].'">';
                                    $mfiles.='<b>'.basename($file["name"]).'</b>'.($i == count($_GET["files"]) - 1 ? "." : ", ");
                            }
?>
<table align="center" style="width:450px;">
<?php echo $mform; ?>
<tr><td colspan=2 align=center><?php echo $langfile;?><? echo count($_GET["files"]) > 1 ? "$langs" : ""; ?>:<?php echo $mfiles; ?></tr>
<tr>
<td><?php echo $langemail;?>&nbsp;<input id="m_email" name=email<? echo $_COOKIE["email"] ? " value=\"".$_COOKIE["email"]."\"" : ""; ?> style="width:300px;"></td>
<td><input type=<?php if ($use_js_check_mails) { ?>"button" onClick="checkform();"<?php } else { ?>"submit"<?php }; ?> value="<?php echo $langsend;?>"></td>
</tr>
<tr><td align=left nowrap colspan=2><input id=cryptbox2 type="checkbox" class="checkbox" name=cryptmail title="XOR first 10 byte: Value sent in mail">&nbsp;<?php echo "$langcryfiles"; echo " $langxor";?></td></tr>
<tr>
<td colspan=2><input type=checkbox class="checkbox"  name=del_ok>&nbsp;<?php echo $langdelsuccsumbit;?></td></tr>
<tr><td colspan=2></td></tr>
<tr><td colspan=2><?php echo "$langdelay"; echo " $langsecond";?>&nbsp;<input name=mdelay size=3 value=<?php echo $_COOKIE["mdelay"] ? $_COOKIE["mdelay"] : $defdelay; ?>></td></tr>

<tr><td colspan=2>
<script>
var c_files='<?php echo count($_GET["files"]); ?>';

function splitemail()
{
    if ((!document.getElementById('splitchkbox2').checked) || (c_files>1))
        {
            document.getElementById('partnumber_tr2').style.display='none';
            document.getElementById('partnumber2').disabled=true;
            return true;
        }

    document.getElementById('partnumber_tr2').style.display='';
    document.getElementById('partnumber2').disabled=false;
}
</script>
<table>
<tr>
<td><input id=splitchkbox2 type="checkbox" class="checkbox" name=split onClick="javascript:var displ=this.checked?'':'none';splitemail();document.getElementById('methodtd2').style.display=displ;"<?php echo $_COOKIE["split"] ? " checked" : ""; ?>>&nbsp;<?php echo $langsplitbyparts;?></td>
<td style="width=15px;"></td>
<td id=methodtd2<? echo $_COOKIE["split"] ? "" : " style=\"display: none;\""; ?>>
<table border=0 width=250 cellspacing=0>
    <tr><td align=left nowrap><?php echo $langmethod;?><td align=left width=200 nowrap><select name=method id=method2 onChange=splitemail();><option value=tc<? echo $_COOKIE["method"] == "tc" ? " selected" : ""; ?>><?php echo $langtc;?></option><option value=rfc<? echo $_COOKIE["method"] == "rfc" ? " selected" : ""; ?>><?php echo $langrfc;?></option></select></td></tr>
    <tr><td align=left nowrap><?php echo $langpartsize;?><td align=left width=200 nowrap><input name=partSize size=2 value=<?php echo $_COOKIE["partSize"] ? $_COOKIE["partSize"] : $max_mailsize; ?>>&nbsp;<?php echo $langmb;?></td></tr>
    <tr id=partnumber_tr2><td align=left nowrap><?php echo $langonlyparts;?><td align=left width=200 nowrap><input name=partnumber2 id=partnumber2  size=10 value=''>&nbsp;<?php echo $langsimple;?></td></tr>
</table>
<script> splitemail();</script>
</td>
</tr>
</table>
</table>
</form>
                            <?php
                          }  
?>
