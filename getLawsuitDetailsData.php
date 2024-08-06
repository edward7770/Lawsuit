<?php
	/////echo $_POST['lsDId'];
	if(isset($_POST['lsDId']) && !empty($_POST['lsDId']))
	{
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}

		include_once('config/conn.php');
		$qry="call LawsuitDetailsData(:lsDetailId)"; 
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":lsDetailId",$_POST['lsDId'],PDO::PARAM_INT);
		if($stmt->execute())
		{
			$resultLawsuitDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$customerArray=explode (",", $resultLawsuitDetails[0]['custName']);
			$opponentArray=explode (",", $resultLawsuitDetails[0]['OpponentsName']);
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			exit($json =$errorInfo[2]);
		}

		// $invoiceNoLength = strlen($_SESSION['invoice_no']);
		// $numericPart = substr($_SESSION['invoice_no'], -$invoiceNoLength);
		// $incrementedNumber = str_pad((int)$numericPart + 1, $invoiceNoLength, '0', STR_PAD_LEFT);
		// $_SESSION['invoice_no'] = substr($_SESSION['invoice_no'], 0, -$invoiceNoLength) . $incrementedNumber;

		$invoiceDate = date('n/j/Y');
	}
	
	/////exit('<script>window.location.replace("Lawsuit.php")</script>');
	?>
	<div class="card">
					<div class="card-body">
						<ul class="list-unstyled mb-0">
							<li class="pt-2 py-0">
								<h6><?php echo set_value('invoice_number'); ?></h6>
							</li>	
							<li id="invoice_number_list">
								<?php if($resultLawsuitDetails[0]['lawsuitInvoiceNumber'] == '' || $resultLawsuitDetails[0]['lawsuitInvoiceNumber'] == null) {
									echo $resultLawsuitDetails[0]['lawsuitInvoiceNumber'];
								} {
									$parts = explode("LS-", $resultLawsuitDetails[0]['ls_code']);  
									echo $parts[1];
								}  ?> </h6>
							</li>
							<li class="pt-2 py-0">
								<h6><?php echo set_value('invoice_date'); ?></h6>
							</li>	
							<li id="invoice_date_list">
								<?php echo $invoiceDate; ?> </h6>
							</li>
							<li class="pt-2 py-0">
								<h6><?php echo set_value('lsMasterCode'); ?></h6>
							</li>
							<li style="color:red">
								<?php echo $resultLawsuitDetails[0]['ls_code']; ?>
							</li>
							
							<li class="pt-2 py-0">
								<h6><?php echo set_value('referenceNo'); ?></h6>
							</li>
							<li>
								<?php if(empty($resultLawsuitDetails[0]['referenceNo'])) echo "-"; else echo $resultLawsuitDetails[0]['referenceNo']; ?>
							</li>
							
							<li class="pt-2 py-0">
								<h6><?php echo set_value('lawsuitId'); ?></h6>
							</li>
							<li>
								<?php if(empty($resultLawsuitDetails[0]['lawsuitId'])) echo "-"; else echo $resultLawsuitDetails[0]['lawsuitId']; ?>
							</li>
							
							<li class="pt-2 pb-0">
								<h6> <?php echo set_value('type'); ?> </h6>
							</li>
							<li>
								<?php echo $resultLawsuitDetails[0]['lsTypeName_'.$language]; ?> </h6>
							</li>
							<li class="pt-2 pb-0">
								<h6> <?php echo set_value('state'); ?> </h6>
							</li>
							<li style="color:<?php echo $resultLawsuitDetails[0]['lsColor']; ?>">
								<?php echo $resultLawsuitDetails[0]['lsStateName_'.$language]; ?> </h6>
							</li>
							<li class="pt-2 pb-0">
								<h6> <?php echo set_value('stage'); ?> </h6>
							</li>
							<li>
								<?php echo $resultLawsuitDetails[0]['lsStagesName_'.$language]; ?> </h6>
							</li>
							<li class="pt-2 pb-0">
								<h6> <?php echo set_value('subject'); ?> </h6>
							</li>
							<li>
								<?php if(!empty($resultLawsuitDetails[0]['lsSubject'])) echo $resultLawsuitDetails[0]['lsSubject']; else echo '-'; ?> </h6>
							</li>
							
							<li class="pt-2 pb-0">
								<h6> <?php echo set_value('customer'); ?> </h6>
							</li>
							<li>
								<?php 
									foreach($customerArray as $row)
									{
										echo '<a href="#">'.$row.'</a><br>';
									}
								?>
							</li>
							<li class="pt-2 pb-0">
								<h6><?php echo set_value('opponent'); ?></h6>
							</li>
							<li>
								<?php 
									foreach($opponentArray as $row)
									{
										echo '<a href="#">'.$row.'</a><br>';
									}
								?>
							</li>
						</ul>
					</div>
				</div>