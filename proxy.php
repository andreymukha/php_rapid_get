<?php
	if (file_exists('proxy.lst'))
		{
			$parray=file('proxy.lst');
			
			$count_pl=0;
			for ($gfh=0; $gfh<count($parray); $gfh++)
				{
					$line_=trim($parray[$gfh]);
					
					if (($line_[0] == '#') || ($line_[0] == ';')) continue;
					list($paddr,$plogin,$ppass)=explode(';',$line_);
					if ($paddr)
						{
							$count_pl++;

							$proxyvar.="plist[$count_pl] = new Array('$paddr','$plogin','$ppass');\n";
							$proxylist.="<option value=$count_pl>$paddr</option>";
						}
				}
			
			if ($proxylist)
				{
?>
<script>
var plist = new Array();
<?php echo $proxyvar ?>

function select_p(id)
	{
		document.getElementById("myproxy").value=(id == 0) ? "" : plist[id][0];
		document.getElementById("myproxyuser").value=(id == 0) ? "" : plist[id][1];
		document.getElementById("myproxypass").value=(id == 0) ? "" : plist[id][2];
	}
</script>
<tr><td><?php echo $langproxylist;?></td><td><select class="in" id=proxylist size=1 style="width:150px;" onChange=javascript:select_p(this.options[selectedIndex].value);><option value=0></option><?php echo $proxylist; ?></select></td></tr>
<?php
				}
		}
?>
