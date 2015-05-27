<?php
$smtp_last_message="";
$first_message=true;

function get_return_smtp_code($info)
	{
		if (!$info) return false;
		if (strlen(trim($info)) < 3) return false;
		return substr($info,0,3);
	}

function smtpmail($server = '127.0.0.1',$port = '25', $smtp_login_, $smtp_passwd_ ,$to=0, $message= '')
	{
		global $smtp_ehlo, $smtp_last_message,$fromaddr, $first_message;
		
		$echo=false;
		
		/*
		if (!$first_message)
			{
				unsleep(5);
				$first_message=false;
			}
		*/
		
		$smtp_last_message="";
		
		if (!$to) return false;
		
		$smtp_ehlo = trim($smtp_ehlo ? $smtp_ehlo : "yandex.ru");
		$smtp_server = (!trim($server)) ? '127.0.0.1' : $server;
		$smtp_port = (!$port || !is_numeric($port)) ? 25 : $port;
		
		$smtp = @fsockopen($smtp_server, $smtp_port, $errno, $errstr,5);
		
		if (!$smtp)
			{
				$smtp_last_message=$errstr;
				return false;
			}
		
		$get=fread($smtp,2048); if ($echo) echo " $get";
		$ret_code=get_return_smtp_code($get);
		
		if ($ret_code != 220)
			{
				$smtp_last_message=$get;
				fclose($smtp);
				return false;
			}
		
		fwrite($smtp,"EHLO $smtp_ehlo\n");
		fflush($smtp);
		
		$get=fread($smtp,2048); if ($echo) echo " $get";
		$ret_code=get_return_smtp_code($get);
		
		if ($ret_code != 250)
			{
				fclose($smtp);
				$smtp_last_message=$get;
				return false;
			}
		
		$max_size=false;
		if (strstr($get,'SIZE ') || strstr($get,'SIZE='))
			{
				$i=strpos($get,'SIZE ');
				$i = $i === false ? strpos($get,'SIZE=') : $i;
				
				$j=strpos($get,"\n",$i);
				$max_size = trim(substr($get,$i+5,$j-$i-5));
				$max_size = is_numeric($max_size) ? $max_size : false;
			}
			
		$is_smtp_auth=false;
		if (strstr($get,'AUTH ') || strstr($get,'AUTH='))
			{
				$is_smtp_auth=true;
				$i=strpos($get,'AUTH ');
				$i = $i === false ? strpos($get,'AUTH=') : $i;
				
				$j=strpos($get,"\n",$i);
				$login = trim(substr($get,$i+5,$j-$i-5));
				$smtp_login_type=(strpos($login,'PLAIN') !== false ? "PLAIN" : "");
				$smtp_login_type=(strpos($login,'LOGIN') !== false ? "LOGIN" : "");
			}
		
		if ($smtp_login_ && $smtp_passwd_ && $is_smtp_auth)
			{
				switch ($smtp_login_type)
					{
						case "LOGIN":
							fwrite($smtp,"AUTH LOGIN\n");
							
							$get=fread($smtp,2048); if ($echo) echo " $get";
							$ret_code=get_return_smtp_code($get);
							if ($ret_code != 334)
								{
									fclose($smtp);
									$smtp_last_message=$get;
									return false;
								}
							
							fwrite($smtp,base64_encode($smtp_login_)."\n");
							
							$get=fread($smtp,2048); if ($echo) echo " $get";
							$ret_code=get_return_smtp_code($get);
							if ($ret_code != 334)
								{
									fclose($smtp);
									$smtp_last_message=$get;
									return false;
								}
							fwrite($smtp,base64_encode($smtp_passwd_)."\n");
	
							$get=fread($smtp,2048); if ($echo) echo " $get";
							$ret_code=get_return_smtp_code($get);
							if ($ret_code != 235)
								{
									fclose($smtp);
									$smtp_last_message=$get;
									return false;
								}
						break;
						
						case "PLAIN":
							fwrite($smtp,"AUTH PLAIN\n");
							
							$get=fread($smtp,2048); if ($echo) echo " $get";
							$ret_code=get_return_smtp_code($get);
							if ($ret_code != 334)
								{
									fclose($smtp);
									$smtp_last_message=$get;
									return false;
								}

							fwrite($smtp,base64_encode($smtp_login_."\0".$smtp_login_."\0".$smtp_passwd_)."\n");
							
							$get=fread($smtp,2048); if ($echo) echo " $get";
							$ret_code=get_return_smtp_code($get);
							if ($ret_code != 235)
								{
									fclose($smtp);
									$smtp_last_message=$get;
									return false;
								}
						break;
						
						default:
						break;
					}
			}
			
		
		$addr=(is_array($to)) ? $to[0] : $to;
		$message_size=strlen($message)+strlen("TO: $addr\n");
		
		fwrite($smtp,"MAIL FROM: <$fromaddr> SIZE=$message_size\n");
		fflush($smtp);
		
		$get=fread($smtp,2048); if ($echo) echo " $get";
		$ret_code=get_return_smtp_code($get);

		if ($ret_code != 250)
			{
				fclose($smtp);
				$smtp_last_message=$get;
				return false;
			}
			
		if (is_array($to))
			{
				for ($i=0; $i<count($to); $i++)
					{
						fwrite($smtp,"RCPT TO: <".trim($to[$i]).">\n");
						fflush($smtp);

						$get=fread($smtp,2048); if ($echo) echo " $get";
						$ret_code=get_return_smtp_code($get);

						if ($ret_code != 250)
							{
								fclose($smtp);
								$smtp_last_message=$get;
								return false;
							}
					}
			}
				else
			{
				fwrite($smtp,"RCPT TO: <".trim($to).">\n");
				fflush($smtp);

				$get=fread($smtp,2048); if ($echo) echo " $get";
				$ret_code=get_return_smtp_code($get);
				
				if ($ret_code != 250)
					{
						fclose($smtp);
						$smtp_last_message=$get;
						return false;
					}
			}

		fwrite($smtp,"DATA\n");
		fflush($smtp);

		$get=fread($smtp,2048); if ($echo) echo " $get";
		$ret_code=get_return_smtp_code($get);

		if ($ret_code != 250 && $ret_code != 354)
			{
				fclose($smtp);
				$smtp_last_message=$get;
				return false;
			}
		
		fwrite($smtp,"TO: $addr\n");
		fflush($smtp);
		
		$mes_len=strlen($message);
		
		$mes_count=ceil($mes_len / 2048);
		
		$j=0;
		for ($i=0; $i < $mes_count; $i++)
			{
				$buff=substr($message,($i*2048),2048);
				
				$res=fputs($smtp, $buff);
				fflush($smtp);
				if ($res === true)
					{
						fclose($smtp);
						return false;
					}
				
				$j++;
				if ($j > 5)
					{
						$j=0;
						usleep(2);
					}
			}

		fwrite($smtp,"\n.\n");
		
		fflush($smtp);
		
		$get=fread($smtp,2048); if ($echo) echo " $get";
		$ret_code=get_return_smtp_code($get);

		if ($ret_code != 250)
			{
				fclose($smtp);
				$smtp_last_message=$get;
				return false;
			}
			
		fwrite($smtp,"QUIT\n");
		fclose($smtp);
		return true;
	}
?>