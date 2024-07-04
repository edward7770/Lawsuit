<?php 
	session_start();
	unset($_SESSION['username']);
	unset($_SESSION['lang']);
	exit('<script>window.location.replace("index.php")</script>');
?>