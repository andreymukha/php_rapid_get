<?php
if (!defined('RAPIDGET')) {die("not load primary script");}

if($_COOKIE["clearsettings"])
   {
		setcookie("domail", "", time() - 1601200);
		setcookie("email", "", time() - 1601200);
		setcookie("saveto", "", time() - 1601200);
		setcookie("savedir", "", time() - 1601200);
		setcookie("mdelay", "", time() - 1601200);
		setcookie("useproxy", "", time() - 1601200);
		setcookie("proxy", "", time() - 1601200);
		setcookie("proxyuser", "", time() - 1601200);
		setcookie("proxypass", "", time() - 1601200);
		setcookie("split", "", time() - 1601200);
		setcookie("partSize", "", time() - 1601200);
		setcookie("rapidpremium", "", time() - 1601200);
		setcookie("rapidlogin", "", time() - 1601200);
		setcookie("rapidpass", "", time() - 1601200);
		setcookie("savesettings", "", time() - 1601200);
		setcookie("clearsettings", "", time() - 1601200);
	}


if($_GET["savesettings"] == "on")
	{
		if($_GET["rapidpremium"] == "on")
			{
				setcookie("rapidpremium", "on", time()+1601200);

				if($_GET["rrapidlogin"])
					{
						setcookie("rrapidlogin", $_GET["rrapidlogin"],time()+1601200);
					}
						else
					{
						setcookie("rrapidlogin", "", time() - 1601200);
					}
				if($_GET["rrapidpass"])
					{
						setcookie("rrapidpass", $_GET["rrapidpass"],time()+1601200);
					}
						else
					{
						setcookie("rrapidpass", "", time() - 1601200);
					}
			}
				else
			{
				setcookie("rapidpremium", "", time()-1601200);
			}
		
		if($_GET["rapidpremium_com"] == "on")
			{
				setcookie("rapidpremium_com", "on", time()+1601200);

				if($_GET["rrapidlogin_com"])
					{
						setcookie("rrapidlogin_com", $_GET["rrapidlogin_com"],time()+1601200);
					}
						else
					{
						setcookie("rrapidlogin_com", "", time() - 1601200);
					}
				if($_GET["rrapidpass_com"])
					{
						setcookie("rrapidpass_com", $_GET["rrapidpass_com"],time()+1601200);
					}
						else
					{
						setcookie("rrapidpass_com", "", time() - 1601200);
					}
			}
				else
			{
				setcookie("rapidpremium_com", "", time()-1601200);
			}
		
		setcookie("savesettings", TRUE,time()+1601200);
		
		if($_GET["icqnotify"] == "on")
			{
				setcookie("icqnotify", "on", time() + 1601200);
				setcookie("icquin", $_GET["icquin"], time() + 1601200);
			}
				else
			{
				setcookie("icqnotify", "", time() - 1601200);
			}
		
		if($_GET["domail"] == "on")
			{
				setcookie("domail", TRUE,time()+1601200);
				if(checkmail($_GET["email"]))
					{
						setcookie("email", $_GET["email"],time()+1601200);
					}
						else
					{
						setcookie("email", "", time() - 1601200);
					}
					
				if($_GET["split"] == "on")
					{
						setcookie("split", TRUE,time()+1601200);
						if(is_numeric($_GET["partSize"]))
							{
								setcookie("partSize", $_GET["partSize"],time()+1601200);
							}
								else
							{
								setcookie("partSize", "", time() - 1601200);
							}
							
						if(in_array($_GET["method"], array("tc", "rfc")))
							{
								setcookie("method", $_GET["method"],time()+1601200);
							}
								else
							{
								setcookie("method", "", time() - 1601200);
							}
							
						if(is_numeric($_GET["mdelay"]))
							{
								setcookie("mdelay", $_GET["mdelay"],time()+1601200);
							}
								else
							{
								setcookie("mdelay", "", time() - 1601200);
							}
					}
						else
					{
						setcookie("split", "", time() - 1601200);
					}
			}
				else
			{
				setcookie("domail", "", time() - 1601200);
			}
			
		if($_GET["saveto"] == "on")
			{
				setcookie("saveto", TRUE,time()+1601200);
				if(isset($_GET["savedir"]))
					{
						setcookie("savedir", $_GET["path"],time()+1601200);
					}
						else
					{
						setcookie("savedir", "", time() - 1601200);
					}
			}
				else
			{
				setcookie("saveto", "", time() - 1601200);
			}
			
		if($_GET["useproxy"] == "on")
			{
				setcookie("useproxy", TRUE,time()+1601200);
				if(strlen(strstr($_GET["proxy"], ":")) > 0)
					{
						setcookie("proxy", $_GET["proxy"],time()+1601200);
					}
						else
					{
						setcookie("proxy", "", time() - 1601200);
					}

				if($_GET["proxyuser"])
					{
						setcookie("proxyuser", $_GET["proxyuser"],time()+1601200);
					}
						else
					{
						setcookie("proxyuser", "", time() - 1601200);
					}

				if($_GET["proxypass"])
					{
						setcookie("proxypass", $_GET["proxypass"],time()+1601200);
					}
						else
					{
						setcookie("proxypass", "", time() - 1601200);
					}
			}
				else
			{
				setcookie("useproxy", "", time() - 1601200);
			}
	}
    
    /* управление проверкой наличия новой версии */
    if ((!$_COOKIE['updateversion'] || $_COOKIE['updateversion']!=BUILD.SCRIPTTYPE) & $check_update_day){
        setcookie("updateversion", BUILD.SCRIPTTYPE, time()+(3600*24*$check_update_day));
        $show_update = true;        
    }else
        $show_update = false;
?>