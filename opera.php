<?php
	$new_link="";
	foreach ($_REQUEST as $key => $value)
		{
			$new_value = (($key == "link") || ($key == "ref")) ? base64_encode($value) : $value;
			$new_link .= ($new_link ? "&" : "") . "$key=".$new_value;
		}
	
	Header("Location: index.php?$new_link");
?>