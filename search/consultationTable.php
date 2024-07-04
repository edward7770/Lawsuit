
<div class="card-header">
	<div class="d-flex justify-content-between align-items-center">
		<h5 class="card-title"><?php echo set_value('consultationList'); ?></h5>
	</div>
</div>
<div class="card-body">
	
	<div class="row">
		<div class="col-sm-12">
			<div class="card-table">
				<div class="card-body">
					<div class="table-responsive">
						<table id="lawsuitTable" class="table table-center table-hover datatable">
							<thead class="thead-light">
								<tr>
										<th>#</th>
										<th><?php echo set_value('action'); ?></th>
										<th><?php echo set_value('customer'); ?></th>
										<th><?php echo set_value('lawsuitLawyer'); ?></th>
										<th><?php echo set_value('titleConsultation'); ?></th>
										<th><?php echo set_value('ContractDate'); ?></th>
										<th><?php echo set_value('amountContract'); ?></th>
										<th><?php echo set_value('taxValue'); ?></th>
										<th><?php echo set_value('ContAmountinclTax'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php 
									if(isset($txtSearch) && !empty($txtSearch))
									{
										$custId="-1";
										///$qry="CALL sp_getLawsuitDetails_Search('".$language."',-1,'".$txtSearch."') ";
										$qry="CALL sp_getConsultation_Search(:lang,:custId,:txtSearch)";
										$stmt=$dbo->prepare($qry);
										$stmt->bindParam(":lang",$language,PDO::PARAM_STR);
										$stmt->bindParam(":custId",$custId,PDO::PARAM_STR);
										$stmt->bindParam(":txtSearch",$txtSearch,PDO::PARAM_STR);
										if($stmt->execute())
										{
											$resultSearch = $stmt->fetchAll(PDO::FETCH_ASSOC);
										}
										else 
										{
											$errorInfo = $stmt->errorInfo();
											exit($json =$errorInfo[2]);
										}
										///exit;
										$serial=0;
										foreach($resultSearch as $i=> $value)
										{
											$serial++;
											?>
										<tr>
											<td> <?php echo $serial; ?> </td>
											<td><a href="javascript:showSearch(<?php echo $value['consId'].",'consultation.php'"; ?>);" class="btn-action-icon me-2"><span><i class="fe fe-eye"></i></span></a> </td>
											<td><?php echo $value['customerName']; ?></td>
											<td><?php echo $value['empName']; ?></td>
											<td><?php echo $value['title']; ?></td>
											<td><?php echo $value['contractDate']; ?></td>
											<td><?php echo setAmountDecimal($value['amount']); ?></td>
											<td><?php echo $value['tax']; ?></td>
											<td><?php echo setAmountDecimal($value['totalAmount']); ?></td>
										</tr>
										<?php 
											
										}
									}
						?>
					</tbody>
					</table>
					</div>
					<input type='hidden' id='lsCountCons' value="<?php if(isset($serial)) echo $serial; else echo "0"; ?>" >
				</div>
			</div>
		</div>
	</div>
</div>


