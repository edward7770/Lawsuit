<?php
if (session_status() === PHP_SESSION_NONE) {
			session_start();
	}
if(!isset($_SESSION['username']))
{
	exit('<script>window.location.replace("index.php")</script>');
}
/*
function getFullURL()
{
	$link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']
	=== 'on' ? "https" : "http") .
	"://" . $_SERVER['HTTP_HOST'] .
	$_SERVER['REQUEST_URI'];
}
*/
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
		<title>lawsuit</title>
		
		<!-- Favicon -->
		<link rel="shortcut icon" href="assets/img/favicon.png">
		<?php 
		
		if($_SESSION['lang']=='en')
		{
		?>
		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="assets/css/bootstrap.min.css">
		
		<!-- Fontawesome CSS -->
		<link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
		<link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
		<?php if(basename($_SERVER['PHP_SELF'])=='LawsuitAdd.php') { ?>
		<!-- Summernote CSS -->
		<link rel="stylesheet" href="assets/plugins/summernote/summernote-bs4.min.css">
		<?php } ?>
		<!-- Feather CSS -->
		<link rel="stylesheet" href="assets/plugins/feather/feather.css">

		<!-- Datepicker CSS -->
		<link rel="stylesheet" href="assets/css/bootstrap-datetimepicker.min.css">
		
		<!-- Datatables CSS -->
		<link rel="stylesheet" href="assets/plugins/datatables/datatables.min.css">
		
		<!-- Select2 CSS -->
		<link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">
		
		<!-- Main CSS -->
		<link rel="stylesheet" href="assets/css/style.css">
		
		
		<?php }
		if($_SESSION['lang']=='ar')
		{
		?>
		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="assets-rtl/css/bootstrap.rtl.min.css">
		
		<!-- Fontawesome CSS -->
		<link rel="stylesheet" href="assets-rtl/plugins/fontawesome/css/fontawesome.min.css">
		<link rel="stylesheet" href="assets-rtl/plugins/fontawesome/css/all.min.css">
		
		<?php if(basename($_SERVER['PHP_SELF'])=='LawsuitAdd.php') { ?>
			<!-- Summernote CSS -->
		<link rel="stylesheet" href="assets-rtl/plugins/summernote/summernote-bs4.min.css">
		<?php } ?>
		
		<!-- Feather CSS -->
		<link rel="stylesheet" href="assets-rtl/plugins/feather/feather.css">

		<!-- Datepicker CSS -->
		<link rel="stylesheet" href="assets-rtl/css/bootstrap-datetimepicker.min.css">
		
		<!-- Datatables CSS -->
		<link rel="stylesheet" href="assets-rtl/plugins/datatables/datatables.min.css">
		
		<!-- Main CSS -->
		<link rel="stylesheet" href="assets-rtl/css/style.css">
		
		
		<!-- Select2 CSS -->
		<link rel="stylesheet" href="assets-rtl/plugins/select2/css/select2.min.css">
		
		<!-- Main CSS -->
		<link rel="stylesheet" href="assets-rtl/css/style.css">
		
		<?php } ?>
		
	</head>
	<body>
	
	
		<!-- Main Wrapper -->
		<div class="main-wrapper">
		
			<!-- Header -->
			<div class="header header-one">
			
				<!-- Sidebar Toggle -->
				<a href="javascript:void(0);" id="toggle_btn">
					<span class="toggle-bars">
						<span class="bar-icons"></span>
						<span class="bar-icons"></span>
						<span class="bar-icons"></span>
						<span class="bar-icons"></span>
					</span>
				</a>
				<!-- /Sidebar Toggle -->
				
				<!-- Search -->
				<div class="top-nav-search">
					<form>
						<input type="text" class="form-control" placeholder="Search here">
						<button class="btn" type="submit"><img src="assets/img/icons/search.svg" alt="img"></button>
					</form>
				</div>
				<!-- /Search -->
				
				<!-- Mobile Menu Toggle -->
				<a class="mobile_btn" id="mobile_btn">
					<i class="fas fa-bars"></i>
				</a>
				<!-- /Mobile Menu Toggle -->
				
				<!-- Header Menu -->
				<ul class="nav nav-tabs user-menu">
					<!-- Flag -->
					<li class="nav-item dropdown has-arrow flag-nav">
						<a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button">
							<?php
								
								if($_SESSION['lang']=='ar')
									echo '<img src="assets/img/flags/om.png" alt="" height="20"><span>Arabic</span>';
								else if($_SESSION['lang']=='en')
									echo '<img src="assets/img/flags/us1.png" alt="" height="20"><span>English</span>';
								
								?>
						</a>
						<div class="dropdown-menu dropdown-menu-end">
							<a href="javascript:lang('en');" class="dropdown-item">
								<img src="assets/img/flags/us.png" alt="" height="16"><span>English</span>
							</a>
							<a href="javascript:lang('ar');" class="dropdown-item">
								<img src="assets/img/flags/om.png" alt="" height="16"><span>Arabic</span>
							</a>
						</div>
					</li>
					<!-- /Flag -->
					<li class="nav-item  has-arrow dropdown-heads ">
                        <a href="javascript:void(0);" class="win-maximize">
                            <i class="fe fe-maximize"></i>
                        </a>
                    </li>
					<!-- User Menu -->
					<li class="nav-item dropdown">
                        <a href="javascript:void(0)" class="user-link  nav-link" data-bs-toggle="dropdown">
                            <span class="user-img">
                                <img src="assets/img/profiles/avatar-07.jpg" alt="img" class="profilesidebar">
                                <span class="animate-circle"></span>
                            </span>
                            <span class="user-content">
                                <span class="user-details">Admin</span>
								<span class="user-name">John Smith</span>
                            </span>
                        </a>
                        <div class="dropdown-menu menu-drop-user">
                            <div class="profilemenu">
                                <div class="subscription-menu">
                                    <ul>
                                        <li>
                                            <a class="dropdown-item" href="profile.html">Profile</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="settings.html">Settings</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="subscription-logout">
                                    <ul>
                                        <li class="pb-0">
											<a class="dropdown-item" href="logout.php">Log Out</a>
										</li>
									</ul>
                                </div>
                            </div>
                        </div>
                    </li>
					<!-- /User Menu -->
					
				</ul>
				
				<!-- /Header Menu -->
				
			</div>
			<!-- /Header -->
			
<?php include_once('SideBar.php');  ?>
<!-- Summernote JS -->
<script src="assets/plugins/summernote/summernote-bs4.min.js"></script>