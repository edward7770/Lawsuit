
<div class="card-header">
	<div class="d-flex justify-content-between align-items-center">
		<h5 class="card-title"><?php echo set_value('customer'); ?></h5>
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
									<th><?php echo set_value('customers_types'); ?></th>
									<th><?php echo set_value('customer'); ?></th>
									<th><?php echo set_value('cr_no'); ?></th>
									<!--<th><?php ///echo set_value('crCopy'); ?></th> -->
									<th><?php echo set_value('passport_no'); ?></th>
									<!--<th><?php ////echo set_value('idCopy'); ?></th> -->
									<th><?php echo set_value('email'); ?></th>
									<th><?php echo set_value('mobile_no'); ?></th>
									<th><?php echo set_value('nationality'); ?></th>
									<th><?php echo set_value('address'); ?></th>
									<th><?php echo set_value('number_of_lawsuits'); ?></th>
									<th><?php echo set_value('number_of_consultations'); ?></th>
									<?php if($language=='ar') $br=25; else $br=9; ?>
									<th><?php echo wordwrap(set_value('end_date_agency'), $br, "<br>", true); ?> </th>
									<?php /*
									<th><?php echo set_value('agencyCopy'); ?></th>
									<th><?php echo set_value('created_at'); ?></th>
									<th><?php echo set_value('user'); ?></th>  */ ?>
								</tr>
							</thead>
							<tbody>
								<?php 
									if(isset($txtSearch) && !empty($txtSearch))
									{
										$custId="-1";
										///$qry="CALL sp_getLawsuitDetails_Search('".$language."',-1,'".$txtSearch."') ";
										$qry="CALL sp_getCustomers_Search(:lang,:custId,:txtSearch)";
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
										$serial=0;
										$countryName="countryName_".$language;
										foreach($resultSearch as $i=> $value)
										{
											$serial++;
										?>
										<tr>
											<td> <?php echo $serial; ?> </td>
											<td><a href="javascript:showSearch(<?php echo $value['customerId'].",'Customer.php'"; ?>);" class="btn-action-icon me-2"><span><i class="fe fe-eye"></i></span></a> </td>
											<td><?php echo $value['typeName_'.$language]; ?></td>
											<td><?php echo $value['customerName_'.$language]; ?></td>
											<td><?php if(!empty($value['crNo'])) echo $value['crNo']; ?></td>
											<!-- <td><?php /////echo $crCopy; ?></td> --->
											<td><?php if(!empty($value['idPassportNo'])) echo $value['idPassportNo']; ?></td>
											<!--<td><?php //////echo $passportCopy?></td> -->
											<td><?php echo $value['customerEmail']; ?></td>
											<td><?php echo $value['mobileNo']; ?></td>
											<td><?php echo $value[$countryName]; ?></td>
											<td><?php echo $value['address']; ?></td>
											<td><?php echo $value['address']; ?></td>
											<td><?php echo $value['address']; ?></td>
											<td><?php echo $value['endDateAgency']; ?></td>
											<?php /*<td><?php echo $agencyCopy; ?></td> 
											<td><?php echo $value['createdDate']; ?></td>
											<td><?php echo $value['createdBy']; ?></td>
											*/ ?>
										</tr>
										<?php 
											
										}
									}
						?>
					</tbody>
					</table>
					</div>
					<input type='hidden' id='lsCountCust' value="<?php if(isset($serial)) echo $serial; else echo "0"; ?>" >
					
				</div>
			</div>
		</div>
	</div>
</div>


