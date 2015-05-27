<?php if (!defined('RAPIDGET')) {echo "<HTML><HEAD><meta http-equiv=\"Refresh\" content=\"0; url=index.php\"></HEAD></HTML>"; die("not load primary script"); }
/* подрубаем модули action */ 
    $actions = false;     
    if (is_dir(getcwd().DIRECTORY_SEPARATOR."modules")){
        if($act_dir=@opendir(getcwd().DIRECTORY_SEPARATOR."modules")){;
            while (($file = readdir($act_dir)) !== false){
                if (is_dir(getcwd().DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR.$file)) continue;
                if (substr($file, -13) == 'index.mod.php') {
                    include_once(getcwd().DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR.$file);               
                }
                    
            }
            closedir($act_dir);
        }
    }
    
    if(count($menu_action)>0){
        foreach($menu_action as $menu_key=>$menu_value){
            $actions .= "<option value='".$menu_key."'>".$menu_value."</option>\n";   
        }        
    }
    //pre($actions);
/* конец подцепки модулей */
                     
 ?>
<html><noindex><head><link rel="stylesheet" type="text/css" href="style/css.css"><meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<script>
<!--
var d = document;
function resetfields()
	{
		d.getElementById("link_l").value="";
		d.getElementById("usercook_l").value="";
		d.getElementById("usercook").value="";
		d.getElementById("ref_l").value="";
		d.getElementById("link").value="";
		d.getElementById("ref").value="";
		d.getElementById("link_l").focus();
	}
-->
</script>
<script>
function switchCell(m) {
  var style
  d.getElementById("tb1").className = "hide-table";
  d.getElementById("tb2").className = "hide-table";
  d.getElementById("tb3").className = "hide-table";
  d.getElementById("tb" + m).className = "tab-content show-table";
}
function showMenu(m) {
  d.getElementById("tb4").className = "tab-content show-table";
}
function hideMenu(m) {
  d.getElementById("tb4").className = "hide-table";
}
</script>
<title> <?php $str = 'UmFwaWRnZXQgNy4xIFBybyBSZXZPbHVUaW9u';echo base64_decode($str); echo ($auth_user ? ' ['.$auth_user.']' : '').' [Quota: '.($used_quota ? bytesToKbOrMb($used_quota) : 'Unlimited') ?>] [Files: <?php echo ($max_4gb === false ? ' >4Gb' : '<=4Gb') ?>]</title>
</head>
<body <?php if($background=="true"){echo 'background="bg.jpg"';}?>>
<script language="JavaScript">
function getCookie(name) {
  var dc = d.cookie;
  var prefix = name + "=";
  var begin = dc.indexOf("; " + prefix);
  if (begin == -1) {
    begin = dc.indexOf(prefix);
    if (begin != 0) return null;
  } else
    begin += 2;
  var end = d.cookie.indexOf(";", begin);
  if (end == -1)
    end = dc.length;
  return unescape(dc.substring(begin + prefix.length, end));
}

function deleteCookie(name, path, domain) {
  if (getCookie(name)) {
    d.cookie = name + "=" +
    ((path) ? "; path=" + path : "") +
    ((domain) ? "; domain=" + domain : "") +
    "; expires=Thu, 01-Jan-70 00:00:01 GMT";
  }
}
</script>
<center><table align="center">
            <table border="0" cellpadding="0" cellspacing="0" align="center"><a href="index.php"><?php if($showlogo=="true"){echo'<img src="logo.png" align="absmiddle" border="0">';}?></a></table>
  <tbody>
  <tr>
    <td>
      <table id="tb_content" >
        <tbody>
        <tr>
          <td align="center">
          <div class="hide-table" id="tb4" name="tb" width="100%">
              <?php if($showline=="true"){echo '<img src="line.gif">';} else {echo '<hr>';}?><div>
              <?php echo "<a href=\"javascript:switchCell(1);hideMenu(4);\" >".$langmain."</a>&nbsp;&nbsp;<a href=\"javascript:switchCell(2);hideMenu(4);\" id=\"navcell2\">".$langoption."</a>&nbsp;&nbsp;<a href=\"javascript:switchCell(3);showMenu(4);\" id=\"navcell3\">".$langfiles."</a>&nbsp;&nbsp;<a href=pack.php target=_blank id=\"navcell4\">".$langpackage."</a>&nbsp;&nbsp;";?>
             </div> <?php if($showline=="true"){echo '<img src="line.gif">';} else {echo '<hr>';}?>
            </div>
<script language=javascript src=base64.js></script>
<script language=javascript>

function unsc_url(id)
	{
		d.getElementById(id).value=decodeURI(d.getElementById(id).value);
	}

var target_id_tmp='<?php $target=substr(md5(time()),0,8); echo $target; ?>';
var openjava='<?php echo $java_new_window !== false ? 'yes' : 'no' ?>';

function esc_1($0,$1)
	{
  		return encodeURIComponent($0);
	}

function encode_url()
{
var link_=String(d.getElementById("link_l").value).replace(/[#+]/g,esc_1);
var usercook_=String(d.getElementById("usercook_l").value).replace(/[#+]/g,esc_1);
var ref_=String(d.getElementById("ref_l").value).replace(/[#+]/g,esc_1);

if (link_ == "")
	{
		alert("<?php echo $langinslink;?>");
		return false;
	}

d.getElementById("link").value=link_ ? link_ : "";
d.getElementById("usercook").value=usercook_ ? usercook_ : "";
d.getElementById("ref").value=ref_ ? ref_ : "";

if ((openjava == 'yes') && d.getElementById('opennew').checked)
	{
		var options = "width=600,height=300,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,copyhistory=no";
		new_window = window.open("about:blank", d.getElementById('dform').target, options);
		new_window.opener=self;
		new_window.focus();

	}

if (d.getElementById('opennew').checked)
	{
<?php echo $clear_link ===true ? "\t\td.getElementById('link_l').value='';\n\t\td.getElementById('ref_l').value='';\n\t\td.getElementById('usercook_l').value='';\n" : ''; ?>
	}

setTimeout("settarget();",1000);
return true;
}

function settarget()
	{
		target_id=target_id_tmp+Math.random();
		d.getElementById('dform').target=d.getElementById('opennew').checked ? target_id : '';
	}
</script>
<form method=post accept-charset=cp1251 action=<?php echo $PHP_SELF; ?> onSubmit="return encode_url(); true;" id=dform <?php echo (($new_window === true) && ($default_new_window === true)) ? "target=$target" : ""; ?>><input type=hidden name=command value="request">
<input type=hidden name=link id=link value=""><input type=hidden name=ref id=ref value=""><input type=hidden name=usercook id=usercook value="">
<table class="tab-content" id="tb1" name="tb" cellspacing="5" width="100%" align=center width=600>
<tbody>
<tr>
<td align="center">
<center>
<table border=0 width=100%>
<tr><td><?php echo $langurl;?></td><td>&nbsp;<input id="link_l" size=75 style="width:367px;" class="in"><!--&nbsp;<input type=button onClick=javascript:unsc_url('link_l'); value='UNESCAPE'>--></td><td rowspan=3 valign="top"><input type=submit value="<?php echo $langdownload;?>" default id=submits class="in"></td></tr>
<tr><td><?php echo $langhttpftp;?></td><td>&nbsp;<input name=httplogin size=15 style="width:182px;" class="in">&nbsp;<input name=httppasswd size=15 style="width:182px;" class="in"></td></tr>
<tbody id="comment" style="DISPLAY: none;">
<tr><td><div><?php echo $langaddcome;?></div></td><td><textarea name="comment" rows=2 cols=70 style="width:367px;" class="in"></textarea></td></tr>
<tr><td><div><?php echo $langreferer;?></div></td><td><input id="ref_l" size="70" style="width:367px;" class="in"></td></tr>
<tr><td><div>Cookie</div></td><td><input id="usercook_l" size="70" style="width:367px;" class="in"></td></tr>
<tr><td><div><?php echo $langpasfore;?></div></td><td><input type="password" size="70" name=password style="width:367px;" class="in"></td></tr>
<tr><td><div><?php echo $langrange;?></div></td><td><input class="checkbox" name="range" type="checkbox" value="continueON"></td></tr>
</center>
</tbody>
<table>
<tr><td align="center" colspan=3>
<div><?php
                   if ($show_direct === true) {echo "$langdirectl<input type=checkbox class=\"checkbox\"  name=showdirect value=on ".($default_checked_showlink === true ? "checked" : "").">&nbsp;&nbsp;\n";}
                   if ($new_window !== false) {echo "$langinnwin<input type=checkbox class=\"checkbox\"  name=opennew id=opennew ".($default_new_window === true ? "checked" : "")." onClick=settarget(); ><script>settarget();</script>&nbsp;&nbsp;\n";}
echo $langaddition;?><input class="checkbox" name="add_comment" type="checkbox" value="on" onClick="javascript:var displ=this.checked?'':'none';d.getElementById('comment').style.display=displ;"></div></td>
</table></table>
</td>
</tr>
</tbody>
</table>
<table class="hide-table" id="tb2" name="tb" cellspacing="5" width="100%" align=center width=600>
<tbody>
<?php
//ICQ//ICQ//ICQ
if (function_exists('icqsendmassage'))
	{
?>
<script>
function icqbutton()
	{
		check=d.getElementById('icqnotify');
		d.getElementById('icqinfo').style.display=check.checked ? '' : 'none';
		d.getElementById('icquin').disabled=!check.checked;
	}
</script>
<tr><td>&nbsp;<input type="checkbox" class="checkbox" value=on name=icqnotify id=icqnotify onClick="javascript:icqbutton();"<?php echo $_COOKIE["icqnotify"] ? " checked" : ""; ?>>&nbsp;<?php echo $langnoticq;?></td>
<td id=icqinfo <? echo $_COOKIE["icqnotify"] ? "" : " style=\"display: none;\""; ?>><?php echo $languin;?>: &nbsp;<input type=text name=icquin id=icquin <? echo $_COOKIE["icquin"] ? " value='".$_COOKIE["icquin"]."'" : ""; ?> disabled></tr>
<script>icqbutton();</script>
<?php
	}
?>
<tr>
<td align="center">

<table align="center">
<?php
                  if (!$mail_not_support || $smtp_mail)
                          {
?>
<script>
function mbutton()
{
	check=d.getElementById('domail');
	d.getElementById('mdelay3').style.display=check.checked ? '' : 'none';
	//d.getElementById('crypt3').style.display=check.checked ? '' : 'none';
	d.getElementById('emailtd').style.display=check.checked ? '' : 'none';
	d.getElementById('splittd').style.display=check.checked ? '' : 'none';
	d.getElementById('mailinf').style.display=check.checked ? '' : 'none';
	d.getElementById('methodtd').style.display=(d.getElementById('splitchkbox').checked && check.checked) ? '' : 'none';
}
</script>
<tr><td><input type="checkbox" class="checkbox" value=on name=domail id=domail onClick="javascript:mbutton();"<?php echo $_COOKIE["domail"] ? " checked" : ""; ?>>&nbsp;<?php echo $langsendmail;?></td>
<td>&nbsp;</td>
<td id=emailtd<? echo $_COOKIE["domail"] ? "" : " style=\"display: none;\""; ?>><?php echo $langemail;?>&nbsp;<input name=email<? echo $_COOKIE["email"] ? " value='".$_COOKIE["email"]."'" : ""; ?> style="width:250px;" class="in">
</td></tr>
<!--<tr id=crypt3><td align=left nowrap colspan=2><input id=cryptbox type="checkbox" class="checkbox" name=cryptmail value=on>&nbsp;<?php echo $langcryfiles;?></td></tr>-->

<tr id=mdelay3 <? echo $_COOKIE["domail"] ? "" : " style=\"display: none;\""; ?>>
<td><?php echo $langdelay;?><td><td><?php echo $langsecond;?>&nbsp;<input class="in" name=mdelay size=3 value=<?php echo $_COOKIE["mdelay"] ? $_COOKIE["mdelay"] : $defdelay; ?>></td>
</tr>

<tr id=splittd<? echo $_COOKIE["domail"] ? "" : " style=\"display: none;\""; ?>>
<td><input id=splitchkbox type="checkbox" class="checkbox" name=split onClick="javascript:var displ=this.checked?'':'none';d.getElementById('methodtd').style.display=displ;"<?php echo $_COOKIE["split"] ? " checked" : ""; ?>>&nbsp;<?php echo $langspfiles;?></td>
<td>&nbsp;</td>
<td id=methodtd<? echo $_COOKIE["split"] ? "" : " style=\"display: none;\""; ?>>
<table border=0 width=200 cellspacing=0>
	<tr><td><?php echo $langmethod;?><td><select name=method class="in"><option value=tc<? echo $_COOKIE["method"] == "tc" ? " selected" : ""; ?>><?php echo $langtc;?></option><option value=rfc<? echo $_COOKIE["method"] == "rfc" ? " selected" : ""; ?>><?php echo $langrfc;?></option></select></td></tr>
	<tr><td><?php echo $langpartsize;?><td><input class="in" name=partSize size=3 value=<?php echo $_COOKIE["partSize"] ? $_COOKIE["partSize"] : $max_mailsize; ?>>&nbsp;<?php echo $langmb;?></td></tr>
</table>
</td></tr>
<tr id=mailinf<? echo $_COOKIE["domail"] ? "" : " style=\"display: none;\""; ?>><td colspan=3><hr size=1></tr>
<script>mbutton();</script>
<?php
                    }
?>
<tr><td colspan=3></td></tr>
<tr><td>
<input type="checkbox" class="checkbox" id=useproxy name=useproxy onClick="javascript:var displ=this.checked?'':'none';d.getElementById('proxy').style.display=displ;"<?php echo $_COOKIE["useproxy"] ? " checked" : ""; ?>>&nbsp;<?php echo $languseproxy;?>
</td>
<td>&nbsp;</td>
<td id=proxy<? echo $_COOKIE["useproxy"] ? "" : " style=\"display: none;\""; ?>>
<table border=0>
	<?php if (file_exists('proxy.php')) include "proxy.php"; ?>
	<tr><td><?php echo $langproxy;?></td><td><input class="in" name=proxy size=25<? echo $_COOKIE["proxy"] ? " value=\"".$_COOKIE["proxy"]."\"" : ""; ?> id=myproxy></td></tr>
	<tr><td><?php echo $languser;?></td><td><input class="in" name=proxyuser size=25 <?php echo $_COOKIE["proxyuser"] ? " value=\"".$_COOKIE["proxyuser"]."\"" : ""; ?> id=myproxyuser></td></tr>
	<tr><td><?php echo $langpass;?></td><td><input class="in" name=proxypass size=25 <?php echo $_COOKIE["proxypass"] ? " value=\"".$_COOKIE["proxypass"]."\"" : ""; ?> id=myproxypass></td></tr>
</table>
</td>
</tr>

<tr><td colspan=3></td>
</tr>
<?php
					if ($maysaveto === true)
						{
?>
<tr>
<td><input type="checkbox" class="checkbox" name=saveto id=saveto onClick="javascript:var displ=this.checked?'':'none';d.getElementById('path').style.display=displ;"<?php echo $_COOKIE["saveto"] ? " checked" : ""; ?>>&nbsp;<?php echo $langsaveto;?></td>
<td>&nbsp;</td>
<td id=path <?php echo $_COOKIE["saveto"] ? "" : " style=\"display: none;\""; ?>><?php echo $langpatch;?>&nbsp;<input class="in" name=savedir size=30 value="<?php echo realpath(($_COOKIE["savedir"] ? $_COOKIE["savedir"] : (strstr(realpath("./"), ":") ? addslashes($workpath) : $workpath))) ?>">
</td>
</tr>
<?php
						}
					if (!$rapidlogin || !$rapidpass)
						{
?>
<tr>
<td><input type="checkbox" class="checkbox" value="on" name=rapidpremium id=rapidpremium<? echo $_COOKIE["rapidpremium"] ? " checked" : ""; ?> onClick="javascript:var displ=this.checked?'':'none';d.getElementById('rapidblock').style.display=displ;">&nbsp;<?php echo $langrapidde;?><br><?php echo $langpremium;?></td>
<td>&nbsp;</td>
<td id=rapidblock<? echo $_COOKIE["rapidpremium"] ? "" : " style=\"display: none;\""; ?>>
<table width=150 border=0>
	<tr><td><?php echo $langpremiumid;?></td><td><input class="in" type=text name=rrapidlogin size=15 value="<?php echo ($_COOKIE["rrapidlogin"] ? $_COOKIE["rrapidlogin"] : ""); ?>"></td></tr>
	<tr><td><?php echo $langpasssmall;?></td><td><input class="in" type=text name=rrapidpass size=15 value="<?php echo ($_COOKIE["rrapidpass"] ? $_COOKIE["rrapidpass"] : ""); ?>"></td></tr>
</table>
</td>
</tr>
<?php
						}
                    if (!$rapidlogin_com || !$rapidpass_com)
                    	{
?>
<tr>
<td><input type="checkbox" class="checkbox" value="on" name=rapidpremium_com id=rapidpremium_com<? echo $_COOKIE["rapidpremium_com"] ? " checked" : ""; ?> onClick="javascript:var displcom=this.checked?'':'none';d.getElementById('rapidblockcom').style.display=displcom;">&nbsp;<?php echo $langrapidcom;?><br><?php echo $langpremium;?></td>
<td>&nbsp;</td>
<td id=rapidblockcom<? echo $_COOKIE["rapidpremium_com"] ? "" : " style=\"display: none;\""; ?>>
<table width=150 border=0>
	<tr><td><?php echo $langpremiumid;?></td><td><input class="in" type=text name=rrapidlogin_com size=15 value="<?php echo ($_COOKIE["rrapidlogin_com"] ? $_COOKIE["rrapidlogin_com"] : ""); ?>"></td></tr>
	<tr><td><?php echo $langpasssmall;?></td><td><input class="in" type=text name=rrapidpass_com size=15 value="<?php echo ($_COOKIE["rrapidpass_com"] ? $_COOKIE["rrapidpass_com"] : ""); ?>"></td></tr>
</table>
</td>
</tr>
<?php
						}
?>
<tr>
<td><input class="checkbox" type="checkbox" name=savesettings id=savesettings<? echo $_COOKIE["savesettings"] ? " checked" : ""; ?> onClick="javascript:var displ=this.checked?'':'none';d.getElementById('clearsettings').style.display=displ;">&nbsp;<?php echo $langsaveset;?></td>
<td>&nbsp;</td>
<td id=clearsettings<? echo $_COOKIE["savesettings"] ? "" : " style=\"display: none;\""; ?>>
<script>
function clearSettings()
{
clear("domail"); clear("email"); clear("split"); clear("method");
clear("partSize"); clear("useproxy"); clear("proxy"); clear("saveto");
clear("path"); clear("savesettings");
clear("rrapidlogin");clear("rrapidpass");clear("rapidpremium");
clear("rapidpremium_com");

d.getElementById('domail').checked = false;
d.getElementById('splitchkbox').checked = false;
d.getElementById('useproxy').checked = false;
d.getElementById('saveto').checked = false;
d.getElementById('savesettings').checked = false;
d.getElementById('rapidpremium').checked = false;
d.getElementById('rapidpremium_com').checked = false;

d.getElementById('rrapidlogin').value="";
d.getElementById('rrapidpass').value="";
d.getElementById('rapidblock').style.display = "none";
d.getElementById('rapidblockcom').style.display = "none";

d.getElementById('mdelay3').style.display="none";
d.getElementById('mailinf').style.display="none";
d.getElementById('emailtd').style.display = "none";
d.getElementById('splittd').style.display = "none";
d.getElementById('methodtd').style.display = "none";
d.getElementById('proxy').style.display = "none";
d.getElementById('path').style.display = "none";
d.getElementById('clearsettings').style.display = "none";

d.cookie = "clearsettings = 1;";
}

function clear(name)
{
d.cookie = name + " = " + "; expires=Thu, 01-Jan-70 00:00:01 GMT";
}
</script>
<a href="javascript:clearSettings();"><?php echo $langclearset;?></a>
</td>
</tr>
</table>
</td>
</tr>
</tbody>
</table>
</form>
            <table class="hide-table" id="tb3" name="tb" cellspacing="5" width="100%">
              <tbody><?php echo $_GET["act"] ? "<script>switchCell(3);showMenu(4);</script>" : "<script>switchCell(1);hideMenu(4);</script>"; ?>
               <tr>
                <td align=center width=100%>
                  <?php
	   				if(($auth_user !== false) && ($auth_user !== "") && ($loginpathoriginal === true) && !in_array($auth_user,$admin_logins)){
						//echo "<div>Авторизация задействована. Пользователь: $auth_user не является администратором! Его папка: $workpath</div>";
					}
					else {$workpath=realpath($workpathoriginal);}
                  if(($show_all !== true) | ($_COOKIE["showAll"] != 1)){$dir=$workpath;_create_list("",$dir);}
                  else {
                  	if(isset($_GET["dir"]) & $step_dir){
                  	    $dir=$workpath.$_GET["dir"];
					    if(!file_exists($dir)){$dir=false;}
		  			} else {
					    $dir=false;
		  			}
                    if($dir===false){$dir=$workpath;}
                    _create_list("",$dir);
                  }
/* подрубаем модули */
if (file_exists(getcwd().DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR.basename($_REQUEST['act'].".mod.php")) & $_REQUEST['act']!='files'){
    include_once(getcwd().DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR.basename($_REQUEST['act'].".mod.php"));
} 
/* конец подрубаемых модулей */                 

                  ?>
                   <script>
                    function setCheckboxes(act)
                    {
                      elts =  d.forms["flist"].elements["files[]"];

                      var elts_cnt  = (typeof(elts) != 'undefined') && (typeof(elts.length) != 'undefined') ? elts.length : 0;

                      if (elts_cnt)
                        {
                          for (var i = 0; i < elts_cnt; i++)
                            {
                              elts[i].checked = (act == 1 || act == 0) ? act : elts[i].checked ? 0 : 1;
                            }
                        }
                      if (!elts_cnt && elts)
                               {
                                       elts.checked = (act == 1 || act == 0) ? act : elts.checked ? 0 : 1;
                               }
                    }
                    <?php if ($show_all === true) { ?>
                    function showAll() {
                     <?php
                       $Path = parse_url($PHP_SELF);
                       $Path = substr($Path["path"], 0, strlen($Path["path"]) - strlen(strrchr($Path["path"], "?")));
                     ?>
                      if(getCookie("showAll") == 1)
                        {
                          deleteCookie("showAll");
                          location.href = "<?php echo $Path."?act=files"; ?>";
                        }
                      else
                        {
                          d.cookie = "showAll = 1;";
                          location.href = "<?php echo $Path."?act=files"; ?>";
                        }
                    }
                    <?php unset($Path); ?>
                    <?php } ?>
</script>
<form name="flist" method="post">
<table align="center"><tbody><tr><td>
<a href="javascript:setCheckboxes(1);"><?php echo $langcheckall;?></a> |
<a href="javascript:setCheckboxes(0);"><?php echo $languncheckall;?></a> |
<a href="javascript:setCheckboxes(2);"><?php echo $langinvertsel;?></a>
<?php if ($show_all === true) { ?>|
<a href="javascript:showAll();"><?php echo $langshow;?>
<script language="JavaScript">
	if(getCookie("showAll") == 1)
		{
			d.write("<?php echo $langdownloaded;?>");
		}
			else
		{
			d.write("<?php echo $langeverythink;?>");
		}
</script></a><?php } ?></td></tr></tbody></table>

<?php
if ($used_quota)
	{
		filesinfolder($workpath,false,true);
		$pused=round(($fsumm / $used_quota)*100,2);
		if ($pused >= $procent_message && $pused < 100) $mess_q="<center><h1><font color=blue><b>$langyouused $pused% $langdiscspace</b></font></h1></center>\n";
		if ($pused >= 100) $mess_q="<center><h1><font color=red><b>$langdownlnewdisabled</b></font></h1></center>\n";

		echo $mess_q ? $mess_q : '';
	}
?>
<table cellpadding="3" cellspacing="1" width=600 align=center>
<tbody>
<tr align="center" class="action"><td COLSPAN=<?php echo ((($show_all !== true) | ($_COOKIE["showAll"] != 1)) ? ($show_download_link ? "6" : "5") : "4") ?>>
<?php
if ($actions){
    echo "<select name=\"act\" onChange=\"javascript:void(d.flist.submit());\">\n";
    echo "<option>".$langaction."</option>\n";
    echo $actions;
    echo "</select>";    
}
echo $langfreespacefolder;
echo @bytesToKbOrMb(@disk_free_space($workpath));
echo $langfrom;
echo @bytesToKbOrMb(@disk_total_space($workpath));
?>
</td></tr>

</noindex><tbody><tr class="folder" valign="bottom" align="center"><td COLSPAN=<?php echo ((($show_all !== true) | ($_COOKIE["showAll"] != 1)) ? ($show_download_link ? "6" : "5") : "4") ?>>
<b><?php
$spos=strrpos($workpath,DIRECTORY_SEPARATOR)+1;
echo("<a href='index.php?act=files'>".substr($workpath,$spos)."</a>".DIRECTORY_SEPARATOR);
if($dir && $step_dir){
	$p_ts=str_replace($workpath,"",$dir).DIRECTORY_SEPARATOR;
	$spos=strpos($p_ts,DIRECTORY_SEPARATOR);
	$p_ts2="";
	while($spos!==false){
		$spos2=strpos($p_ts,DIRECTORY_SEPARATOR,$spos+1);
		if($spos2===false){break;}
		$p_ts2.=urlencode(DIRECTORY_SEPARATOR.substr($p_ts,$spos+1,$spos2-$spos-1));
		echo "<a href='index.php?act=files&dir=".$p_ts2."'>".substr($p_ts,$spos+1,$spos2-$spos-1)."</a>".DIRECTORY_SEPARATOR;
		$spos=$spos2;
	}
}
?></b>
</td></tr></tbody><tr bgcolor="#A6A6A6" valign="bottom" align="center">
<td width=3>
</td>
<td width=50%><?php echo $langname;?></td>
<td width=50><?php echo $langsize;?></td>
<?php
					if (($show_all !== true) | ($_COOKIE["showAll"] != 1))
						{
							if ($show_download_link)
								{
?>
<td width=100><?php echo $langdownlink;?></td>
<?php 							}
?>
<td width=100><?php echo $langcomments;?></td>
<?php 						};
				  ?>
<td width=60><?php echo $langdate;?></td>
<script>
    var colorfix, coloractive,colornormal;
    colorfix = '#ff0000';
    colornormal = "#A6A6A6";
    coloractive = "#C8D0E6";
    function fixstring(obj){
        
        if (obj.className!='nch'){
            if(obj.parentNode.bgColor==colorfix) obj.parentNode.bgColor = colornormal;
            else obj.parentNode.bgColor = colorfix;
        } 
    }
    function over(obj){
        if(obj.bgColor==colorfix) return;
        obj.bgColor = coloractive;               
    }
    function out(obj){
        if(obj.bgColor==colorfix) return;
        obj.bgColor = colornormal;        
    }
	function ar(p1,p2,p3,p4,p5,p6,p7)
		{
			d.write('<tr class="e1" <?php echo ($embellishment ? '  onMouseOver="over(this);" onMouseOut="out(this);"' : '') ?> align=center  bgcolor=#A6A6A6>');
			d.write('<td class=\'nch\' onclick="fixstring(this);"><input type=checkbox class="checkbox" name="files[]" value="'+p1+'"></td>');
			d.write('<td onclick="fixstring(this);">'+(p2 ? '<a href="'+p2+'">' : '')+p3+(p2 ? '</a>' : '')+'</td>');
			d.write('<td nowrap onclick="fixstring(this);">'+p4+'</td>');
<?php
			if (($show_all !== true) | ($_COOKIE["showAll"] != 1))
				{
					if ($show_download_link)
						{
?>
			d.write('<td wrap>'+(p7 ? '<a href="'+p7+'" style="color: #0FF;">'+p7+'</a>' : "")+'</td>');
<?php
						}
?>
			d.write('<td wrap>'+p5+'</td>');
<?php
				}
?>
			d.write('<td nowrap>'+p6+'</td>');
			d.write("</tr>\n");
		}
</script>
</tr>
<?php
         $total_files=0;

                  if($list)
                    {
		      for($p_dir_i=1;$p_dir_i<=2;$p_dir_i++) {
                      foreach($list as $key => $file)
                        {
                          if(file_exists($file["name"]))
                            {
							$p_d_show=true;

							$inCurrDir = (HOSTROOT != '') && (strpos(dirname($file["name"]), HOSTROOT.DIRECTORY_SEPARATOR) == 0) ? TRUE : FALSE;
							if($inCurrDir)
								{
									$Path = dirname(substr($file["name"],strlen(HOSTROOT)));
									$Path=str_replace(DIRECTORY_SEPARATOR,'/',$Path);
									$Path = ($Path == '/' ? "" : $Path);
								}

							$p1=substr(md5(basename($file["name"])),8,8);$file["date"];

							if($file["is_dir"]===true){
								if($p_dir_i==1){//Папка//
									$p2= $step_dir?"'index.php?act=files&dir=".rawurlencode(str_replace("'","\\'",str_replace($workpath,"",$file["name"])))."'":"'#'";
									$p3="'[".(str_replace("'","\\'",basename_r($file["name"])))."]'";
									$p4="'".$file["size"]."'";
									$total_files++;
									$total_size+=getfilesize($file["name"]);
								} else {
									$p_d_show=false;
								}
							} else {
								if($p_dir_i==2){//Файл//
									$p2="'".(str_replace("'","\\'",str_replace("%5C","\\",str_replace("%2F","/",rawurlencode($inCurrDir ? $Path."/".basename_r($file["name"]) : '')))))."'";
									$p3="'".(str_replace("'","\\'",basename_r($file["name"])))."'";
									$p4="'".$file["size"]."'";
									$total_files++;
									$total_size+=getfilesize($file["name"]);
								} else {
									$p_d_show=false;
								}
							}

							if (($show_all !== true) | ($_COOKIE["showAll"] != 1))
								{
									if ($show_download_link)
										{
											$p7="'".str_replace("'","\\'",$file["link"] ? $file["link"] : "")."'";
										}
											else
										{
											$p7='';
										}
									$p5="'".str_replace("'","\\'",($file["comment"] ? str_replace("\\r\\n", "<br>", $file["comment"]) : ""))."'";
								}
									else
								{
									$p7='';
									$p5='';
								}
							$p6="'".date("d.m.Y H:i:s", $file[date])."'";

							if($p_d_show){echo "<script>ar('$p1',".($p2 ? $p2 : "''").",".($p3 ? $p3 : "''").",".($p4 ? $p4 : "''").",".($p5 ? $p5 : "''").",".($p6 ? $p6 : "''").",".($p7 ? $p7 : "''").", '$p_dir_i');</script>\n";}

                            }
                        }
		     }
                      if (($total_files>1) & ($total_size>0)) echo "<tr class=\"summary\" align=\"center\">\n<td></td>\n<td>$langsummary</td>\n<td>".
                       bytesToKbOrMb($total_size).($used_quota ? '<b>&nbsp;'.round(($total_size / $used_quota)*100,2).'%</b>' : '')."</td><td></td>".((($show_all !== true) | ($_COOKIE["showAll"] != 1)) ? ($show_download_link ? "<td></td>": "")."<td></td>" : "")."\n</tr>";
                    }
                    if ($total_files==0) echo "<tr bgcolor=\"#939393\" align=\"center\"><td COLSPAN=".((($show_all !== true) | ($_COOKIE["showAll"] != 1)) ? ($show_download_link ? "6" : "5") : "4").">$langnofilesfound</td></tr>\n";
                    unset($total_files,$total_size);
                  ?>
<noindex></tbody>
</table>
</form>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</table>
</td>
</tr>
</tbody>
</table>

<table width="60%" align="center" cellpadding="0" cellspacing="0" align=center>
  <tr>
    <td>
      <div align="center">
<?php  if($menunew=="true"){
  if ($showline == "true") {
    echo '<img src="line.gif">';
  }
  else {
    echo '<hr>';
  }
echo "<br><a href=\"javascript:switchCell(1);hideMenu(4);\">" . $langmain . "</a>&nbsp;&nbsp;<a href=\"javascript:switchCell(2);hideMenu(4);\">" . $langoption . "</a>&nbsp;&nbsp;<a href=\"javascript:switchCell(3);showMenu(4);\">" . $langfiles . "</a>&nbsp;&nbsp;<a href=pack.php target=_blank>" . $langpackage . "</a>&nbsp;&nbsp;";
echo "<a id=\"contactthisb\" style=\"DISPLAY: '';\" href=\"#\" onclick=\"javascript:var displ=this.onclick?'':'none';d.getElementById('contactthis').style.display=displ; displb=this.onclick?'none':'';d.getElementById('contactthisb').style.display=displb; displb2=this.onclick?'':'none';d.getElementById('contactthisb2').style.display=displb2;\">".$langcontact."</a>";
echo "<a id=\"contactthisb2\" style=\"DISPLAY: none;\" href=\"#\" onclick=\"javascript:var displ=this.onclick?'none':'';d.getElementById('contactthis').style.display=displ; displb=this.onclick?'':'none';d.getElementById('contactthisb').style.display=displb; displb2=this.onclick?'none':'';d.getElementById('contactthisb2').style.display=displb2;\">".$langhide."</a>&nbsp;&nbsp;</noindex><a href=\"info.php\">".$langinfo."</a><noindex>&nbsp;&nbsp;<a href=#>".$langupgrade."</a><br><br>";}?><center><?php if($showline=="true"){echo '<img src="line.gif">';} else {echo '<hr>';}?></center><br>
<div align="center" id="contactthis" style="display:none">
<?php echo $langbugsreport." ".$adminemail." , ".$adminICQ?><br>
<?php if($menunew="true"){echo "<center>!!! <a href=http://forum.ru-board.com/topic.cgi?forum=5&bm=1&topic=24768&glp#lt target=_blank>$langspage</a> | </noindex><a href=http://rapidgetpro.ru/index.php target=_blank><noindex>$langhpage</a> !!!</center>";}?>
</div>
<span style="color:#000;text-decoration: none"><?php echo $langcurrentver ?></span> Rapidget 7.1 Pro RevOluTion version: <?php echo VERSIONS ?> build: <?php echo BUILD ?> type: <?php echo SCRIPTTYPE ?><br>Designed by p1xels.ru
<?php
$dateABC= date('d');
if ($dateABC=="1") echo "<br><font color=white>$langupdateABC</font> <a href=http://rapidgetpro.ru/index.php target=_blank>$langhpage</a>.";
elseif ($dateABC=="15") echo "<br><font color=white>$langtrablABC</font> <a href=http://forum.ru-board.com/topic.cgi?forum=5&bm=1&topic=24768&glp#lt target=_blank>$langspage</a>.";
?><br><br><center><?php if($showline=="true"){echo '<img src="line.gif">';} else {echo '<hr>';}?></center></div>
    </td>
  </tr>
<?php
    if ($show_update & $check_update_day){
        echo "<script src=http://rapidgetpro.ru/update.php?version=".VERSIONS."&build=".BUILD."&type=".SCRIPTTYPE."></script>";
    }
?>
</table></center>
</body>
</noindex>
</html>
