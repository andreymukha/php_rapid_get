<?php

function subject_win1251($subj)
	{
		$subzh='';
		for($i = 0; $i < strlen($subj); $i++) { $subzh .= "=".strtoupper(dechex(ord(substr($subj, $i, 1)))); }
		return "=?Windows-1251?Q?".$subzh.'?=';
	}

function insertmaildelay($delay_)
	{
		if ($delay_ > 0)
			{
				echo "<tr bgcolor=#F2F2F2><td align=center colspan=3>Waiting $delay_ second</tr>\n<!--";
				flush();
				for ($kll=0; $kll < $delay_; $kll++)
					{
						sleep(1); echo '~';
					}
				echo "-->\n";
			}
	}

function xmail($from, $to, $subj, $text, $filename, $partSize = FALSE, $method = FALSE, $delay=0, $only_parts=false, $crypt=false)
	{
		global $un, $__crc32_table,$smtp_mail, $smpt_server, $smtp_port, $smtp_login, $smtp_passwd;
		global $smtp_last_message;
		$to=trim($to);

		if ($mail_not_support && !$smtp_mail)
			{
				echo '<tr bgcolor="#E1E1E1"><td colspan=3 align="center" width=350>MAIL NOT SUPPORT</b></td></tr>';
				return false;
			}

		if (!is_readable($filename)) return false;
		$fileSize = getfilesize($filename);
		$crypt_key='0x'.strtoupper(substr(md5($fileSize),0,2));
		$crypt_key_dec=hexdec($crypt_key);

		$text.=($crypt === true ? "\r\nFile crypted by key: $crypt_key" : '');

		$fid=md5(basename($filename));
		$un=$fid;

		$CorrectMail=checkmail($to);
		if (!$CorrectMail)
			{
				echo '<td style="color :#FF0000" nowrap><b>Skip - Uncorrect Mail</b>';
				flush(); fclose($fsend); return false;
			}

		echo '<tr bgcolor="#E1E1E1"><td colspan=3 align="center" width=350 nowrap>File <b>'.basename($filename).'</b></td></tr>';
		flush();

		if ($only_parts)
			{
				echo '<tr bgcolor="#E1E1E1"><td colspan=3 align="center" width=350 nowrap>Only parts <b>'.implode(',',$only_parts).'</b></td></tr>';
			}

		if (($partSize === false) || !$partSize)
			{
				$totalParts=1;
			}
				else
			{
				$partSize = round($partSize);
				$totalParts = ceil($fileSize / $partSize);
				$TParts=str_pad($totalParts, 3, "0", STR_PAD_LEFT);
			}

		$zag = "------------".$un."\nContent-Type: text/plain; charset=Windows-1251\n".
				"Content-Transfer-Encoding: 8bit\n\n".$text."\n\n".
				"------------".$un."\n".
				"Content-Type: application/octet-stream; name=\"".basename($filename)."\"\n".
				"Content-Transfer-Encoding: base64\n".
				"Content-Disposition: attachment; filename=\"".basename($filename)."\"\n\n";

        $mailed=true;
		if ($totalParts == 1)
			{
				$head = "From: ".$from."\n".
						"X-Mailer: PHP RapidGet\n".
						"X-Split: None\n".
						"Reply-To: ".$from."\n".
						"Mime-Version: 1.0\n".
						"Content-Type: multipart/mixed; boundary=\"----------".$un."\"\n";

				$subjout= subject_win1251($subj);

				echo '<tr bgcolor="#F2F2F2"><td align="center">1 / 1<td>'.$to;
				flush();

				$fileContents=file_get_contents($filename);

				if (!$fileContents)
					{
						echo '<td style="color :#FF0000" nowrap><b>Error Reading From Disk</b>';
						flush(); return false;
					}

				if ($crypt === true)
					{
						$ln_=strlen($fileContents);
						for ($ggg = 0; $ggg < min(10,$ln_); $ggg++)
							{
								$fileContents[$ggg] = chr(ord($fileContents[$ggg]) ^ $crypt_key_dec);
							}
					}

				if ($smtp_mail)
					{
						$smtp_result=smtpmail($smpt_server,$smtp_port,$smtp_login,$smtp_passwd,$to,"Subject: $subjout\n".$head.$zag.chunk_split(base64_encode($fileContents)));
						if (!$smtp_result) echo $smtp_last_message;
					}
						else
					{
						$smtp_result=mail($to, $subjout, $zag.chunk_split(base64_encode($fileContents)), $head);
					}


				if ($smtp_result)
					{
						echo '<td style="color :#00AA00" nowrap><b>Letter Successful Mailed ||| Письмо успешно отправлено</b>';
					}
						else
					{
						$mailed=false;
						echo '<td style="color :#FF0000" nowrap><b>Error Sending Letter ||| Проверьте заголовки</b>';
					}
				flush();
			}

	if(($method == "rfc") && ($totalParts > 1))
		{
			$subjout = subject_win1251($subj);

			$head = "From: $from\n".
					"X-Mailer: PHP RapidGet\n".
					"X-Split: RFC 2046\n".
					"Reply-To: $from\n".
					"Subject: $subjout\n".
					"Mime-Version: 1.0\n".
					"Content-Type: multipart/mixed; boundary=\"----------".$un."\"\n";

			$multiHeadMain = 	"From: $from\n".
								"X-Mailer: PHP RapidGet\n".
								"X-Split: RFC 2046\n".
								"Reply-To: $from\n".
								"Mime-Version: 1.0\n".
								"Content-Type: message/partial; ";

			$fsend=fopen($filename,'r');
			if (!$fsend)
				{
					echo '<tr bgcolor="#F2F2F2"><td bgcolor="#FF0000">Error Reading From Disk';
					flush(); fclose($fsend); return false;
				}

			$mailed = TRUE;
			for($i = 0; $i < $totalParts; $i++)
				{
					if ($i > 0)
						{
							if (($only_parts == false) or ($only_parts && in_array($i,$only_parts))) insertmaildelay($delay);
						}

					$multiHead = $multiHeadMain."id=\"".$fid."\"; number=".($i + 1)."; total=".$totalParts."\n\n";
					echo '<tr bgcolor="#F2F2F2"><td align="center">'.($i + 1).' / '.$totalParts.'<td>'.$to;
					flush();

					if ($only_parts && !in_array($i+1,$only_parts))
						{
							echo '<td style="color :#00AA00" nowrap><b>Skip this parts</b>'; flush(); continue;
						}
							else
						{
							@fseek($fsend,($i*$partSize));

							$fileChunk=false;
							$fileChunk=fread($fsend,$partSize);

							if (!$fileChunk)
								{
									fclose($fsend);
									echo '<td style="color :#FF0000"><b>Abort - Error Reading From Disk</b>';
									flush(); fclose($fsend); return false;
								}
						}

					if ($crypt === true)
						{
							if($i == 0)
								{
									$ln_=strlen($fileChunk);
									for ($ggg = 0; $ggg < min(10,$ln_); $ggg++)
										{
											$fileChunk[$ggg] = chr(ord($fileChunk[$ggg]) ^ $crypt_key_dec);
										}
								}
						}

					if($i == 0)
						{
							$multiHead = $multiHead.$head;
							$fileChunk = $zag.chunk_split(base64_encode($fileChunk));
						}
							else
						{
							$fileChunk = chunk_split(base64_encode($fileChunk)).($i == ($totalParts - 1) ? "\n------------$un--\n" : '');
						}



					$subjout = subject_win1251($subj.' ('.($i + 1).'/'.$totalParts.')');

					if ($smtp_mail)
						{
							$smtp_result=smtpmail($smpt_server,$smtp_port,$smtp_login,$smtp_passwd,$to,"Subject: $subjout\n".$multiHead.$fileChunk);
							if (!$smtp_result) echo $smtp_last_message;
						}
							else
						{
							$smtp_result=mail($to, $subjout, $fileChunk, $multiHead);
						}

					if ($smtp_result)
						{
							echo '<td style="color :#00AA00"><b>Letter Successful Mailed</b>';
							flush();
						}
							else
						{
							echo '<td style="color :#FF0000"><b>Abort - Error Sending Letter</b>';
							flush(); fclose($fsend); return false;
						}
				}
			fclose($fsend);
		}

	if (($method == "tc") && ($totalParts > 1))
		{
			$fsend=fopen($filename,'r');
			if (!$fsend)
				{
					fclose($fsend);
					echo '<tr bgcolor="#F2F2F2" nowrap><td bgcolor="#FF0000">Error Reading From Disk';
					flush();
					return false;
				}

			$head = "From: ".$from."\n".
					"X-Mailer: PHP RapidGet\n".
					"X-Split: Total Commander\n".
					"Reply-To: ".$from."\n".
					"Mime-Version: 1.0\n".
					"Content-Type: multipart/mixed; boundary=\"----------".$un."\"\n";

			$mailed = TRUE;
			#$filepref = substr(basename($filename), 0, strrpos(basename($filename), "."));
			$filepref = basename($filename);
			__crc32_init_table();

			for($i = 0; $i < $totalParts; $i++)
				{
					$addHeads=false;
					if ($i > 0)
						{
							if (($only_parts == false) or ($only_parts && in_array($i,$only_parts))) insertmaildelay($delay);
						}

					echo '<tr bgcolor="#F2F2F2"><td align="center">'.($i + 1).' / '.$totalParts.'<td>'.$to;
					flush();

					$fileChunk=false;
					$fileChunk=fread($fsend,$partSize);

					if (!$fileChunk)
						{
							fclose($fsend);
							echo '<td style="color :#FF0000" nowrap><b>Abort - Error Reading From Disk</b>';
							flush(); fclose($fsend); return false;
						}

					if (($crypt === true))
						{
							if ($i == 0)
								{
									$ln_=strlen($fileChunk);
									for ($ggg = 0; $ggg < min(10,$ln_); $ggg++)
										{
											$fileChunk[$ggg] = chr(ord($fileChunk[$ggg]) ^ $crypt_key_dec);
										}
								}
						}

					$md5[$i]=md5($fileChunk)." *$filepref.$TParts.".str_pad(($i+1), 3, "0", STR_PAD_LEFT);

					if (!($only_parts && !in_array($i+1,$only_parts)))
						{
							$addHeads = addAdditionalHeaders(array("msg" => $text."\r\n"."File: $filepref (часть ".($i + 1)." из $totalParts)."));
							$addHeads.= addAdditionalHeaders(array("file" => array("filename" => "$filepref.$TParts.".str_pad(($i+1), 3, "0", STR_PAD_LEFT), "stream" => chunk_split(base64_encode($fileChunk)))));
						}

					if ($i == 0)
						{
							$crc=crc32($fileChunk)^0xFFFFFFFF;
						}
							else
						{
							$lw=strlen($fileChunk);
							$lw=min(4,$lw);

							for ($wwq=0; $wwq<$lw; $wwq++)
								{
									$crc=(($crc >> 8) & 0x00ffffff) ^ $__crc32_table[($crc & 0xFF) ^ ord($fileChunk[$wwq])];
								}

							if (strlen($fileChunk) > 4)
								{
									$crc=__crc32_decode($crc);
									$fileChunk[0]=$crc[0];
									$fileChunk[1]=$crc[1];
									$fileChunk[2]=$crc[2];
									$fileChunk[3]=$crc[3];
									$crc=crc32($fileChunk) ^ 0xFFFFFFFF;
								}
						}

					if ($only_parts && !in_array($i+1,$only_parts))
						{
							echo '<td style="color :#00AA00" nowrap><b>Skip this parts</b>'; flush();
						}
							else
						{
							$subjout = subject_win1251($subj." (".($i + 1)."/".$totalParts.")");
							if ($smtp_mail)
								{
									$smtp_result=@smtpmail($smpt_server,$smtp_port,$smtp_login,$smtp_passwd,$to,"Subject: $subjout\n".$head.$addHeads);
									if (!$smtp_result) echo $smtp_last_message;
								}
									else
								{
									$smtp_result=mail($to, $subjout, $addHeads, $head);
								}

							$addHeads=false;

							if ($smtp_result)
								{
									echo '<td style="color :#00AA00" nowrap><b>Letter Successful Mailed</b>';
									flush();
								}
									else
								{
									echo '<td style="color :#FF0000" nowrap><b>Abort - Error Sending Letter</b>';
									flush(); fclose($fsend); return false;
								}
						}
				}

			fclose($fsend);

			insertmaildelay($delay);

			echo '<tr bgcolor="#F2F2F2" nowrap><td align="center">CRC<td>'.$to;
			$addHeads = addAdditionalHeaders(array("msg" => $text."\r\n"."File: $filepref ( CRC32 )."));

			$crc^= 0xffffffff;
			$crc = strtoupper(dechex($crc));
			$crc = str_repeat("0", 8 - strlen($crc)).$crc;
			$md5=implode("\r\n",$md5);

			$subjout = subject_win1251($subj.' (CRC)');

			$addHeads.= addAdditionalHeaders(array("file" => array("filename" => "$filepref.$TParts.crc", "stream" => chunk_split(base64_encode("filename=".basename($filename)."\r\n"."size=".$fileSize."\r\n"."crc32=".$crc."\r\n")))));
			$addHeads.= addAdditionalHeaders(array("file" => array("filename" => "$filepref.md5", "stream" => chunk_split(base64_encode($md5)))));
			if ($crypt)	$addHeads.= addAdditionalHeaders(array("file" => array("filename" => "$filepref.crypt", "stream" => chunk_split(base64_encode($crypt_key)))));

			if ($smtp_mail)
				{
					$smtp_result=smtpmail($smpt_server,$smtp_port,$smtp_login,$smtp_passwd,$to,"Subject: $subjout\n".$head.$addHeads);
					if (!$smtp_result) echo $smtp_last_message;
				}
					else
				{
					$smtp_result=mail($to, $subjout, $addHeads, $head);
				}

			if ($smtp_result)
				{
					echo '<td style="color :#00AA00"><b>Letter Successful Mailed</b>';
					flush();
				}
					else
				{
					echo '<td style="color :#FF0000"><b>Abort - Error Sending Letter</b>';
					flush(); fclose($fsend); return false;
				}
		}

		return $mailed ? true : false;
	}
#It is updated by triton4ik. 29.09.2007
#special for mchost.
?>