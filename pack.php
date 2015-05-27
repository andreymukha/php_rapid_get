<?php 
define('RAPIDGET','yes');
include "config.php";
include "auth.php";
error_reporting(0);
?>
<?/*[settings > Languges | Язык]*/
if (file_exists(getcwd() . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR . 'language.' . ($language ? $language : (substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2))) . '.php')) {
  include_once(getcwd() . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR . 'language.' . ($language ? $language : (substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2))) . '.php');
}
else {
  die("Не найден файл локализации! Проверьте структуру и настройки!");
}
/*[settings < Languges | Язык]*/?><head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<title> <?php $str = 'UmFwaWRnZXQgNS4xIFBybyBSZXZPbHVUaW9u';echo base64_decode($str).' Pack modules';?></title>
<link rel="stylesheet" type="text/css" href="style/css.css">
</head>
<body background="bg.jpg" bgcolor="#999999">
<center><table width="100%" height="100%" border="0">
  <tr>
    <td valign="middle"><?php
if ($_REQUEST["GO"] == "GO")
	{
		$getlinks=explode("\r\n",trim($_REQUEST[links]));
		
		if (!count($getlinks) || (trim($_REQUEST[links]) == ""))
			{
				die('<p class=error align=center>'.$langinslink.'</p>');
			}
			

		$start_link='index.php?command=request&ref=';

		if(isset($_REQUEST[useproxy]) && $_REQUEST[useproxy] && (!$_REQUEST[proxy] || !strstr($_REQUEST[proxy], ":")))
		    {
	        	die('<p class=error align=center>Not address of the proxy server is specified</p>');
	    	}
	    		else
	    	{
	    		if ($_REQUEST[useproxy] == "on")
	    			{
						$start_link.='&useproxy=on';
						$start_link.='&proxy='.$_REQUEST[proxy];
						$start_link.='&proxyuser='.$_REQUEST[proxyuser];
						$start_link.='&proxypass='.$_REQUEST[proxypass];
					}
	    	}

		$start_link.='&rapidpremium='.$_REQUEST[rapidpremium];
		$start_link.='&rrapidlogin='.$_REQUEST[rrapidlogin];
		$start_link.='&rrapidpass='.$_REQUEST[rrapidpass];
		$start_link.='&rapidpremium_com='.$_REQUEST[rapidpremium_com];
		$start_link.='&rrapidlogin_com='.$_REQUEST[rrapidlogin_com];
		$start_link.='&rrapidpass_com='.$_REQUEST[rrapidpass_com];
		
?>
<script language="javascript">

	var set_delay=0;
	var current_dlink=-1;
	var last_status = new Array();
	var links = new Array();
	var idwindow = new Array();
	var dwindow = '<?php echo '_'.substr(md5(time()),0,7).'_'; ?>';
	var start_link='<?php echo $start_link; ?>';

	function download(id)
		{
			opennewwindow(id);
		
			document.getElementById('auto').style.display='none';
			document.getElementById('dButton'+id).style.display='none';
		}
	
	function startauto()
		{
			var delay_=document.getElementById('delay').value;
			if (!((delay_>=1) && (delay_<=1800)))
				{
					alert('Ошибки в интервале задержки (от 1 до 600 секунд)');
					return;
				}
				
			set_delay=delay_*1000;
		
			current_dlink=-1;
			document.getElementById('auto').style.display='none';
			
			for(var i=0; i<links.length; i++)
				{
					document.getElementById('dButton'+i).style.display='none';
					document.getElementById('status'+i).innerHTML='<font color="#FFFF00"><span style="background-color: #FF0000">&nbsp;<?php echo $langwait;?></span></font>';
					
				}
				
			nextlink();
		}
		
	function nextlink()
		{
			current_dlink++;
			
			document.getElementById('status'+current_dlink).innerHTML='<span style="background-color: #00FF00">&nbsp;<?php echo $langstarted;?></span>';
			document.getElementById('dButtonR'+current_dlink).style.display='block';
			if (current_dlink < links.length)
				{
					opennewwindow(current_dlink);
					setTimeout('nextlink()',set_delay);
				}
		}

	function opennewwindow(id)
		{
			var options = "width=700,height=450,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,copyhistory=no";
			idwindow[id] = window.open(start_link+'&link='+links[id], dwindow+id, options);
			idwindow[id].opener=self;
			idwindow[id].focus();
		}
	
<?php
		
		for ($i=0; $i<count($getlinks); $i++)
			{
				echo "\tlast_status[$i]=''; links[".$i."]='".urlencode($getlinks[$i])."';\n";
			}
?>
</script>

<table width=100% border=1 rules=all bgcolor=#DDECFE>
<tr><td width=80% bgcolor="#99CCFF"><?php echo $langdownlink;?><td width=70 bgcolor="#99CCFF">&nbsp;<?php echo $langaction;?>&nbsp;<td width=70 bgcolor="#99CCFF">&nbsp;<?php echo $langstatus;?>&nbsp;</tr>
<?php
		for ($i=0; $i<count($getlinks); $i++)
			{
				echo "<tr><td width=80% nowrap id=row".$i.">".$getlinks[$i]."</td>";
				echo "<td width=70 id=action".$i."><p align=center><input type=button onClick=javascript:download($i); value='$langdownload' id=dButton".$i."><input type=button onClick=javascript:download(".$i."); value=".$langrepeat." id=dButtonR".$i." style=display:none;></p></td>";
				echo "<td width=70 id=status".$i.">&nbsp;</td>";
				echo "</tr>\n";
			}
?>
<tr id=auto><td colspan=3 align=center bgcolor=#99CCFF><?php echo $langdelay;?> (1..1800)&nbsp;<input type=text id=delay name=delay size=5 value=20 title="Интервал">&nbsp;<?php echo $langsecond;?>&nbsp;<input type=button value='<?php echo $langstartautoload;?>' onClick=javascript:startauto();></tr>
</table>
<?php
		
		
		
		exit;
	}
?>
<script language=javascript>
	function ViewPage(page)
		{
			document.getElementById('listing').style.display='none';
			document.getElementById('options').style.display='none';
			document.getElementById(page).style.display='block';
		}
	function HideAll()
		{
			document.getElementById('entered').style.display='none';
			document.getElementById('worked_frame').style.display='block';
		}
</script>
<center><table border=0 cellspacing=0 cellpadding=1 id=entered><tr><td>
<form action=?GO=GO method=post target=worked_frame>
<table width=700 border=0>
<tr id=menu><td width=700 align=center>
<a href=javascript:ViewPage('listing');><?php echo $langdownlinks;?></a>&nbsp;|&nbsp;<a href=javascript:ViewPage('options');><?php echo $langoption;?></a>
</td></tr>
<tr> <td width=100% valign=top>
<div id=listing style="display:block;">
<table border=0 style="width:710px;">
<tr><td><textarea class="in" id=links name=links rows=15 cols=60 style="width:600px; height:400px;"></textarea></td><td valign=top><input class="in" type=submit value="<?php echo $langdownload;?>" onClick=javascript:HideAll(); style="width:100px;"></tr>
</table>
</div>
<div width=100% id=options style="display:none;">
    <table id="tb2" name="tb" cellspacing="5" style="width:710px;">
      <tbody>
      <tr>
        <td align="center">

          <table align="center">
            <tr>
              <td>
                <input type="checkbox" id=useproxy name=useproxy onClick="javascript:var displ=this.checked?'':'none';document.getElementById('proxy').style.display=displ;"<?php echo $_COOKIE["useproxy"] ? " checked" : ""; ?>>&nbsp;<?php echo $languseproxy;?>
              </td>
              <td>&nbsp;

              </td>
              <td id=proxy<? echo $_COOKIE["useproxy"] ? "" : " style=\"display: none;\""; ?>>
                <table border=0>
                  <tr><td><?php echo $langproxy;?></td><td><input name=proxy size=25<? echo $_COOKIE["proxy"] ? " value=\"".$_COOKIE["proxy"]."\"" : ""; ?>></td></tr>
                  <tr><td><?php echo $languser;?></td><td><input name=proxyuser size=25 <?php echo $_COOKIE["proxyuser"] ? " value=\"".$_COOKIE["proxyuser"]."\"" : ""; ?>></td></tr>
                  <tr><td><?php echo $langpass;?></td><td><input name=proxypass size=25 <?php echo $_COOKIE["proxypass"] ? " value=\"".$_COOKIE["proxypass"]."\"" : ""; ?>></td></tr>
                </table>
              </td>
            </tr>
            <tr>
              <td>
              </td>
            </tr>
            <?php
            if ($maysaveto === true)
                    {
            ?>
            <tr>
              <td>
                <input type="checkbox" name=saveto id=saveto onClick="javascript:var displ=this.checked?'':'none';document.getElementById('path').style.display=displ;"<?php echo $_COOKIE["saveto"] ? " checked" : ""; ?>>&nbsp;<?php echo $langsaveto;?>
              </td>
              <td>&nbsp;

              </td>
              <td id=path <?php echo $_COOKIE["saveto"] ? "" : " style=\"display: none;\""; ?> test>
                <?php echo $langpatch;?>&nbsp;<input name=savedir size=30 value="<?php echo realpath(($_COOKIE["savedir"] ? $_COOKIE["savedir"] : (strstr(realpath("./"), ":") ? addslashes($workpath) : $workpath))) ?>">
              </td>
            </tr>
            <?php
                    }
            ?>

            <?php
            if (!$rapidlogin || !$rapidpass)
                    {
            ?>
            <tr>
              <td>
                <input type="checkbox" value="on" name=rapidpremium id=rapidpremium<? echo $_COOKIE["rapidpremium"] ? " checked" : ""; ?> onClick="javascript:var displ=this.checked?'':'none';document.getElementById('rapidblock').style.display=displ;">&nbsp;<?php echo $langrapidde;?><br><?php echo $langpremium;;?>
              </td>
              <td>&nbsp;</td>
              <td id=rapidblock<? echo $_COOKIE["rapidpremium"] ? "" : " style=\"display: none;\""; ?>>
                <table width=150 border=0>
                 <tr><td><?php echo $langpremiumid;?></td><td><input type=text name=rrapidlogin size=15 value="<?php echo ($_COOKIE["rrapidlogin"] ? $_COOKIE["rrapidlogin"] : ""); ?>"></td></tr>
                 <tr><td><?php echo $langpasssmall;?></td><td><input type=text name=rrapidpass size=15 value="<?php echo ($_COOKIE["rrapidpass"] ? $_COOKIE["rrapidpass"] : ""); ?>"></td></tr>
                </table>
              </td>
            </tr>
            <?php }
            if (!$rapidlogin_com || !$rapidpass_com)
            	{
            ?>
            <tr>
              <td>
                <input type="checkbox" value="on" name=rapidpremium_com id=rapidpremium_com<? echo $_COOKIE["rapidpremium_com"] ? " checked" : ""; ?> onClick="javascript:var displcom=this.checked?'':'none';document.getElementById('rapidblockcom').style.display=displcom;">&nbsp;<?php echo $langrapidcom;?><br><?php echo $langpremium;?>
              </td>
              <td>&nbsp;</td>
              <td id=rapidblockcom<? echo $_COOKIE["rapidpremium_com"] ? "" : " style=\"display: none;\""; ?>>
                <table width=150 border=0>
                 <tr><td><?php echo $langpremiumid;?></td><td><input type=text name=rrapidlogin_com size=15 value="<?php echo ($_COOKIE["rrapidlogin_com"] ? $_COOKIE["rrapidlogin_com"] : ""); ?>"></td></tr>
                 <tr><td><?php echo $langpasssmall;?></td><td><input type=text name=rrapidpass_com size=15 value="<?php echo ($_COOKIE["rrapidpass_com"] ? $_COOKIE["rrapidpass_com"] : ""); ?>"></td></tr>
                </table>
              </td>
            </tr>
            <?php
            	}
            ?>
          </table>
        </td>
      </tr>
      </tbody>
    </table>
            
</div>
</td></tr>
</table>
</form>
</td></tr></table></center>
<IFRAME width=700 height=450 id=worked_frame name=worked_frame style="display:none;"></IFRAME></td>
  </tr>
</table>
</center>