<?php
if (session_status() === PHP_SESSION_NONE) {
			session_start();
	}
if(!isset($_SESSION['username']) || (!isset($_SESSION['lang'])))
{
	exit('<script>window.location.replace("index.php")</script>');
}

$language=$_SESSION['lang'];
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
		<title>Lawsuit</title>
		
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
		
		<!-- Feather CSS -->
		<link rel="stylesheet" href="assets/plugins/feather/feather.css">

		<!-- Datepicker CSS -->
		<link rel="stylesheet" href="assets/css/bootstrap-datetimepicker.min.css">
		
		<!-- Full Calander CSS -->
        <link rel="stylesheet" href="assets/plugins/fullcalendar/fullcalendar.min.css">
		
		<!-- Datatables CSS -->
		<link rel="stylesheet" href="assets/plugins/datatables/datatables.min.css"> 
		
		<!-- Select2 CSS -->
		<link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">
		
		<!-- Toatr CSS -->		
        <link rel="stylesheet" href="assets/plugins//toastr/toatr.css">

		
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
		
		<!-- Feather CSS -->
		<link rel="stylesheet" href="assets-rtl/plugins/feather/feather.css">

		<!-- Datepicker CSS -->
		<link rel="stylesheet" href="assets-rtl/css/bootstrap-datetimepicker.min.css">
		
		<!-- Full Calander CSS -->
        <link rel="stylesheet" href="assets/plugins/fullcalendar/fullcalendar.min.css">
		
		<!-- Datatables CSS -->
		<link rel="stylesheet" href="assets-rtl/plugins/datatables/datatables.min.css">
		
		<!-- Main CSS -->
		<link rel="stylesheet" href="assets-rtl/css/style.css">
		
		
		<!-- Select2 CSS -->
		<link rel="stylesheet" href="assets-rtl/plugins/select2/css/select2.min.css">
		
		<!-- Toatr CSS -->		
        <link rel="stylesheet" href="assets/plugins//toastr/toatr.css">

		
		<!-- Main CSS -->
		<link rel="stylesheet" href="assets-rtl/css/style.css">
		
		<?php } ?>
		
		<?php if(basename($_SERVER['PHP_SELF'])=='LawsuitDetailPayment.php' || 
				////basename($_SERVER['PHP_SELF'])=='LawsuitEdit.php' ||
				basename($_SERVER['PHP_SELF'])=='LawsuitDetail.php' ||
				basename($_SERVER['PHP_SELF'])=='consultationAdd.php' ||
				basename($_SERVER['PHP_SELF'])=='ContractTerms.php' ||
				basename($_SERVER['PHP_SELF'])=='task.php') { ?>
		<!-- Summernote CSS -->
		<link rel="stylesheet" href="assets/plugins/summernote/summernote-lite.min.css"/>
		<?php } ?>
		
	</head>
	<body>
	
	
		<!-- Main Wrapper -->
		<div class="main-wrapper">
		
			<!-- Header -->
			<div class="header header-one">
			<?php if($_SESSION['customerId']<=0)
			{ ?>
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
					<form action="javascript:setSearch('txtSearch')">
						<input type="text" id="txtSearch" class="form-control" placeholder="Search here" required>
						<button class="btn"><img src="assets/img/icons/search.svg" alt="img"></button>
					</form>
				</div>
				<!-- /Search -->
				
				<!-- Mobile Menu Toggle -->
				<a class="mobile_btn" id="mobile_btn">
					<i class="fas fa-bars"></i>
				</a>
				<!-- /Mobile Menu Toggle -->
			<?php } ?>
				
				
				
				<!-- Header Menu -->
				<ul class="nav nav-tabs user-menu">
					<!-- Flag -->
					<li class="nav-item dropdown has-arrow flag-nav">
						<a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button">
							<?php
								
								if($_SESSION['lang']=='ar')
									echo '<span>العربية</span>';
								else if($_SESSION['lang']=='en')
									echo '<span>English</span>';
								
								?>
						</a>
						<div class="dropdown-menu dropdown-menu-end">
							<a href="javascript:lang('en');" class="dropdown-item">
								<span>English</span>
							</a>
							<a href="javascript:lang('ar');" class="dropdown-item">
								<span>العربية</span>
							</a>
						</div>
					</li>
					<!-- /Flag -->
					
					<li class="nav-item  has-arrow dropdown-heads mobile-view position-relative">
                        <a href="javascript:void(0);" class="search-mobileinput">
                            <i class="fe fe-search"></i>
                        </a>
						
                    </li>
					
				
				
				
				
					<li class="nav-item dropdown  flag-nav dropdown-heads">
						<a class="nav-link" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
							<i class="fe fe-bell"></i> <span class="badge rounded-pill"></span>
						</a>
						<?php
							include_once('config/conn.php');
							///$dbo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
							/////$qry="CALL sp_getNotificationHeader('".$language."')";
							$qry="CALL `sp_getNotifications`('en',1)";
							$stmt=$dbo->prepare($qry);
							//$stmt->bindParam(":to_date",$to_date,PDO::PARAM_STR);
							if($stmt->execute())
							{
								$resultNotification = $stmt->fetchAll(PDO::FETCH_ASSOC);
							}
							else 
							{
								$errorInfo = $stmt->errorInfo();
								exit($json =$errorInfo[2]);
								///exit(get_lang_msg('errorMessage'));
							}
							
							
						?>
						
						<div class="dropdown-menu notifications" style="">
							<div class="topnav-dropdown-header">
								<span class="notification-title">Notifications</span>
							</div>
							<div class="noti-content">
								<ul class="notification-list">
									<li class="notification-message">
									<?php 
										$displayDate="displayDate_$language";
										foreach($resultNotification as $noti_row )
										{ 
											if($noti_row['url']=='LawsuitDetail.php')
												$href="javascript:showLSSearch('".$noti_row['lsMasterId']."','".$noti_row['lsDetailId']."','".$noti_row['id']."','".$noti_row['collapsedText']."')";
											else 
												$href="javascript:showSearch('".$noti_row['id']."','".$noti_row['url']."')";
											if($noti_row['generateType']=="1")
												$generateType="noti-details-danger";
											else 
												$generateType="noti-details-success";
										?>
										<a href="<?php echo $href; ?>">
											<div class="media d-flex">
												<div class="media-body">
													<p class="<?php echo $generateType; ?>"><span class="noti-title"><?php echo $noti_row['title_'.$language]; ?></span> <?php echo $noti_row['description_'.$language]." ".$displayDate($noti_row['date']); ?></p>
													<p class="noti-time"><span class="notification-time"> 4 min ago </span></p>
												</div>
											</div>
										</a>
										<?php 	
										} 
										?>
									</li>
								</ul>
							</div>
							<div class="topnav-dropdown-footer">
								<a href="notifications.php">View all Notifications</a>
							</div>
						</div>
					</li>
				
				
					<!-- User Menu -->
					<li class="nav-item dropdown">
                        <a href="javascript:void(0)" class="user-link  nav-link" data-bs-toggle="dropdown">
                            <span class="user-img">
                                <img src="assets/img/profiles/avatar-07.jpg" alt="img" class="profilesidebar">
                                <span class="animate-circle"></span>
                            </span>
                            <span class="user-content">
                                <!--<span class="user-details"><?php echo $_SESSION['username']; ?></span> -->
								<span class="user-name"><?php echo $_SESSION['username']; ?></span>
                            </span>
                        </a>
                        <div class="dropdown-menu menu-drop-user">
                            <div class="profilemenu">
                                <div class="subscription-logout">
                                    <ul>
                                        <li class="pb-0">
											<a class="dropdown-item" href="Logout.php">Log Out</a>
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
			
<?php 
	////include_once('SideBarDB.php');
	include_once('SideBarDBAutoCollpase.php');
	/////print_r($set_url_array);
	////echo search;
	////exit;
	if(!in_array(search,$set_url_array))
	{ 
		 //echo "<br><br>not found";
		//header('Location: restricted.php');
		echo '<script>location.replace("Restricted.php")</script>';
		////echo '<script>alert("Restricted");</script>';
	}
?>

