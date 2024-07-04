<?php 
	$protocol = isset($_SERVER['HTTPS']) ? 'https://' : 'http://'; // Get the protocol used (http or https)
	$hostServer=$_SERVER['SERVER_NAME'];
	$port=':'.$_SERVER['SERVER_PORT'] ;
	if(empty($port) || $port="443")
		$port="";
	$folderName=dirname($_SERVER['PHP_SELF']);
	if(empty($folderName))
		$folderName="";
	
	$filename ='/'.basename(__FILE__); // Get the current file name
	$url=$protocol.$hostServer.$port.$folderName.$filename;
	
?>