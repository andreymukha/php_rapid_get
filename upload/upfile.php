<?
function upfile($host, $port, $url, $referer = 0, $cookie = 0, $post = 0, $file, $filename, $fieldname,  $proxy = 0, $fieldname2=0, $filename2="") {
global $nn, $lastError, $sleep_time, $sleep_count, $agent;
global $is_header;

$bound="--------".substr(md5(time()),-8);
$saveToFile=0;

unset($postdata);
foreach ($post as $key => $value) 
	{
		$postdata.="--".$bound.$nn;
		$postdata.="Content-Disposition: form-data; name=\"$key\"".$nn.$nn;
		$postdata.=$value.$nn;
	}

$fileSize=filesize($file);

$fieldname = $fieldname ? $fieldname : file.md5($filename);

if (!is_readable($file))
	{
		$lastError="Error read file $file";
		return FALSE;
	}
    
if ($fieldname2){
    $postdata.="--".$bound.$nn;
    $postdata.="Content-Disposition: form-data; name=\"$fieldname2\"; filename=\"$filename2\"".$nn;
    $postdata.="Content-Type: application/octet-stream".$nn.$nn;
}
$postdata.="--".$bound.$nn;
$postdata.="Content-Disposition: form-data; name=\"$fieldname\"; filename=\"$filename\"".$nn;
$postdata.="Content-Type: application/octet-stream".$nn.$nn;

$cookies="";

if ($cookie)
	{
		if (is_array($cookie))
			{
				for( $h=0; $h<count($cookie); $h++)
					{
						$cookies.="Cookie: ".trim($cookie[$h]).$nn;
					}
			}
				else
			{
				$cookies = "Cookie: ".trim($cookie).$nn;
			}
	}

$referer = $referer ? "Referer: ".$referer.$nn : "";
//echo $proxy;
if($proxy)
  {
    list($proxyHost, $proxyPort) = explode(":", $proxy);
    $url = "http://".$host.":".$port.$url;
    $host = $proxyHost.":".$proxyPort;
  }

$zapros=
"POST ".str_replace(" ", "%20", $url)." HTTP/1.0".$nn.
"Host: ".$host.$nn.$cookies.
"Content-Type: multipart/form-data; boundary=".$bound.$nn."Content-Length: ".(strlen($postdata)+strlen($nn."--".$bound."--".$nn)+$fileSize).$nn.
"User-Agent: ".$agent.$nn.
"Accept: text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5".$nn.
"Accept-Language: en-en,en;q=0.5".$nn.
"Accept-Charset: windows-1251;koi8-r;q=0.7,*;q=0.7".$nn.
"Connection: Close".$nn.
$auth.
$referer.
$nn.
$postdata;
if ($is_header) print_r($zapros);
#write_file('debug',$zapros);

$fp = @fsockopen($proxyHost ? $proxyHost : $host, $proxyPort ? $proxyPort : $port, &$errno, &$errstr, 150);
stream_set_timeout($fp, 300);

if($errno || $errstr || !$fp)
	{
  		$lastError = 'err'.$errstr;
		return false;
	}


echo "File <b>".$filename."</b>, size <b>".bytesToKbOrMb($fileSize)."</b>...<br>";
?>
<table cellspacing=0 cellpadding=0 style="FONT-FAMILY: Tahoma; FONT-SIZE: 11px;" id=progressblock>
<tr>
    <td width=100>&nbsp;</td>
    <td nowrap colspan=3>
        <div style='border:#BBBBBB 1px solid; width:300px; height:10px;'>
            <div id=progress style='background-color:#000099; margin:1px; width:0%; height:8px;'>
            </div>
        </div>
    </td>
<td width=100>&nbsp;</td>
</tr>
<tr>
    <td align=right id=received width=100 nowrap>0 KB</td>
    <td align=right id=percent width=122>0%</td>
    <td align=left width=60> , RemTime: </td>
    <td align=left id=timerem width=133>0 sec</td>
    <td align=left id=speed width=100 nowrap>0 KB/s</td>
</tr>
</table>
<script>
function pr(percent, received, speed, timeremain)
{
	document.getElementById("received").innerHTML = '<b>' + received + '</b>';
	document.getElementById("percent").innerHTML = '<b>' + percent + '%</b>';
	document.title='Uploading ' + percent + '% ['+orlink+']';
	document.getElementById("timerem").innerHTML = '<b>' + ((!timeremain)?'1 sec':timeremain) + '</b>';
	if (percent > 90) {percent=percent-1;}
	document.getElementById("progress").style.width = percent + '%';
	document.getElementById("speed").innerHTML = '<b>' + speed + ' KB/s</b>';
	return true;
}

function mail(str, field)
{
	document.getElementById("mailPart." + field).innerHTML = str;
	return true;
}
</script>
<br>
<?

flush();


//$len=strlen($zapros);

$chunkSize=GetChunkSize($fileSize);

fputs($fp,$zapros);
fflush($fp);

$pac=ceil($fileSize / $chunkSize);
$fs=fopen($file,'r');

$i=0;

$local_sleep=$sleep_count;
$timeStart=getmicrotime();
while (!feof($fs))
	{
		$data=fread($fs,$chunkSize);
		if ($data === false)
			{
				fclose($fs);
				fclose($fp);
				html_error('Error READ Data');
			}
			
	 	if (($sleep_count !== false) && ($sleep_time !== false) && is_numeric($sleep_time) && is_numeric($sleep_count) && ($sleep_count > 0) && ($sleep_time > 0))
	 		{
	 			$local_sleep--;
	 			if ($local_sleep == 0)
	 				{
	 					usleep($sleep_time);
	 					$local_sleep=$sleep_count;
	 				}
			}

		$sendbyte=fputs($fp,$data);
		fflush($fp);
		
		if ($sendbyte === false)
			{
				fclose($fs);
				fclose($fp);
				html_error('Error SEND Data');
			}
		//usleep(400000);	
		$totalsend+=$sendbyte;
		
		$time = getmicrotime() - $timeStart; // время с начала
		$chunkTime = $time - $lastChunkTime; // время последнего куска
		$chunkTime = $chunkTime ? $chunkTime : 1;
		$lastChunkTime = $time;
		$speed = round($totalsend / 1024 / $time, 2); // скорость
		$percent = round($totalsend / $fileSize * 100, 2); // проценты
		$ostalos_bytes = $fileSize - $totalsend; // осталось отправить
		$ostalos_time = $ostalos_bytes/($speed*1024); // времени в секундах осталось
		$out = sec2time(round($ostalos_time,2)); // времени нормального осталось
		echo "<script>pr(".$percent.", '".bytesToKbOrMb($totalsend)."', ".$speed.", '".$out."')</script>\n";
		flush();
	}
fclose($fs);

fputs($fp,$nn."--".$bound."--".$nn);
fflush($fp);

while(!feof($fp))
	{
	  	$data=fgets($fp,1024);
  		if ($data === false) {break;}
		$page.=$data;
	};

fclose($fp);
return $page;
}
?>