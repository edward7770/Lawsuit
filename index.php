<?php 
	if(isset($_POST['email'],$_POST['pass']))
	{
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
		require_once('config/conn.php');
		////$myquery="SELECT u.userId,u.userName,u.roleId FROM tbl_user u WHERE u.isActive=1 AND u.userName=:username AND u.password=:password";
		$myquery="SELECT u.userId,u.userName,u.roleId, p.pageName, u.isLawyer, u.customerId, u.empId FROM tbl_user u 
		INNER JOIN tbl_role r ON r.roleId=u.roleId
		LEFT JOIN tbl_pagemenu p ON p.pageId=r.roleDefaultPage 
		WHERE u.isActive=1 AND u.userName=:username AND u.password=:password";
		$stmt=$dbo->prepare($myquery);
		$stmt->bindParam(":username",$_POST['email'],PDO::PARAM_STR);
		$stmt->bindParam(":password",$_POST['pass'],PDO::PARAM_STR);
		if($stmt->execute())
		{
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			exit($json =$errorInfo[2]);
		}

		$paymentquery="SELECT * FROM tbl_lawsuit_payment ORDER BY lsPaymentId DESC LIMIT 1";
		$stmt_payment=$dbo->prepare($paymentquery);
		if($stmt_payment->execute())
		{
			$result_payment = $stmt_payment->fetchAll(PDO::FETCH_ASSOC);
		}
		else 
		{
			$errorInfo = $stmt_payment->errorInfo();
			exit($json =$errorInfo[2]);
		}
		
		if ($result)
		{
			foreach($result as $rsd) 
			{
				$_SESSION['username']=$rsd['userName'];
				$_SESSION['roleId']=$rsd['roleId'];
				$_SESSION['isLawyer']=$rsd['isLawyer'];
				$_SESSION['customerId']=$rsd['customerId'];
				$_SESSION['empId']=$rsd['empId'];
				$_SESSION['lang']="en";

				$_SESSION['invoice_no']="00001";
				$_SESSION['receipt_no']="001";
				echo "1";
				if(empty($rsd['pageName']))
					echo 'Dashboard';
				else 
					echo $rsd['pageName'];
			}
		}
		else
			echo "0";
	}
	else 
	{
		session_start();
		if(isset($_SESSION['username']) && (isset($_SESSION['roleId'])))
		{
			
			require_once('config/conn.php');
			$myquery="SELECT p.pageName FROM tbl_role r 
				LEFT JOIN tbl_pagemenu p ON p.pageId=r.roleDefaultPage
				WHERE r.isActive=1 AND r.roleId=:roleId";
			$stmt=$dbo->prepare($myquery);
			$stmt->bindParam(":roleId",$_SESSION['roleId'],PDO::PARAM_INT);
			if($stmt->execute())
			{
				$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			}
			else 
			{
				$errorInfo = $stmt->errorInfo();
				exit($json =$errorInfo[2]);
			}
			if($result && !empty($result[0]['pageName']))
				exit('<script>window.location.replace("'.$result[0]['pageName'].'.php")</script>');	
			else 
				
				exit('<script>window.location.replace("Dashboard.php")</script>');
		}
		
	?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
		<title>BeinLawyer - Login</title>
		
		<!-- Favicon -->
		<link rel="shortcut icon" href="assets/img/favicon.png">
		
		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="assets/css/bootstrap.min.css">
		
		<!-- Fontawesome CSS -->
		<link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
		<link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
		
		<!-- Toatr CSS -->		
        <link rel="stylesheet" href="assets/plugins//toastr/toatr.css">

		<!-- Main CSS -->
		<link rel="stylesheet" href="assets/css/style.css">
		<style>
			.bodyClr{
			
			background-color: #000;
			
			}
		</style>
	</head>
	<body class="bodyClr">
	
		<!-- Main Wrapper -->
		<div class="main-wrapper login-body">
			<div class="login-wrapper">
				<div class="container">
				
					<img class="img-fluid logo-dark mb-2" src="assets/img/logo2.png" alt="Logo">
					<div class="loginbox">
						
						<div class="login-right">
							<div class="login-right-wrap">
								<h1>BeinLawyer</h1>
								<br>
								<form action="javascript:login();">
									<div class="form-group">
										<label class="form-control-label">Username</label>
										<!--<input type="email" id="email" class="form-control"> -->
										<input type="text" id="email" class="form-control">
									</div>
									<div class="form-group">
										<label class="form-control-label">Password</label>
										<div class="pass-group">
											<input type="password" id="pass" class="form-control pass-input">
											<span class="fas fa-eye toggle-password"></span>
										</div>
									</div>
									<?php /*
									<div class="form-group">
										<div class="row">
											<div class="col-6">
												<div class="custom-control custom-checkbox">
													<input type="checkbox" class="custom-control-input" id="cb1">
													<label class="custom-control-label" for="cb1">Remember me</label>
												</div>
											</div>
											<div class="col-6 text-end">
												<a class="forgot-link" href="forgot-password.html">Forgot Password ?</a>
											</div>
										</div>
									</div>
									*/
									?>
									<button class="btn btn-lg btn-block btn-primary w-100" id="login" type="submit">Login</button>
									
								</form>
								<br/>
								<div id='ajax_loader' style="position: fixed; left: 47%; top: 84%; display:none">
									<button class="btn btn-primary" type="button" disabled>
										<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
										Loading...
									</button>  
								</div> 
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- /Main Wrapper -->
		
		<!-- jQuery -->
		<script src="assets/js/jquery-3.6.3.min.js"></script>
		
		<!-- Bootstrap Core JS -->
		<script src="assets/js/bootstrap.bundle.min.js"></script>
		<!-- Mask JS -->
		<script src="assets/plugins/toastr/toastr.min.js"></script>
		
		<!-- Custom JS -->
		<script src="assets/js/script.js"></script>
		<script src="js_custom/showToastrMessage.js"> </script>
	</body>
</html>
<script>
function login()
{

	var condition = navigator.onLine ? "online" : "offline";
    if( condition == 'offline' ){
    	alert('No Internet / Network Connection, please reconnect and try again');
		return;
	}
	var email=$("#email").val();
	var pass=$("#pass").val();
	$.ajax({
		type:"POST",
		url: "index.php",
		data: {"email":email,"pass":pass},
		
		beforeSend: function()
		{
			$("#ajax_loader").show();
			$('#login').prop("disabled", true);
		},
		
		success: function (data) {
			var datta=data.replace(/\D/g, "");   //Return only numbers from string
			if(datta=="1")
			{
				var page=data.replace(/[0-9]/g, "");
				window.location.replace(page+".php");
			}
			else 
			{
				//////toastr.error('Your provided credentials is not valid. Please provide correct credentials',"",{positionClass:"toast-bottom-center",closeButton:!0,tapToDismiss:!1,rtl:o});
				showMessage('Your provided credentials is not valid. Please provide correct credentials');
			}
		},
		error: function (jqXHR, exception) {
			if (jqXHR.status === 0) {
				showMessage("Not connect.\n Verify Network");
			} else if (jqXHR.status == 404) {
				showMessage("Requested page not found. [404]");
			} else if (jqXHR.status == 500) {
				showMessage("Internal Server Error [500]");
			} else if (exception === 'parsererror') {
				showMessage("Requested JSON parse failed.");
			} else if (exception === 'timeout') {
				showMessage("Time out error.");
			} else if (exception === 'abort') {
				showMessage("Ajax request aborted");
			}
			$("#ajax_loader").hide();
			$('#login').prop("disabled", false);
		},
		complete: function (jqXHR, exception) {
			$("#ajax_loader").hide();
			$('#login').prop("disabled", false);
		}
	}); 
}		
</script>
<?php } ?>