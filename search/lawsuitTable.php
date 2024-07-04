<div class="card-header">
	<div class="d-flex justify-content-between align-items-center">
		<h5 class="card-title"><?php echo set_value('lawsuits'); ?></h5>
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
										<th><?php echo set_value('lsMasterCode'); ?></th>
										<th><?php echo set_value('referenceNo'); ?></th>
										<th><?php echo set_value('lawsuitId'); ?></th>
										<th><?php echo set_value('customer'); ?></th>
										<th><?php echo set_value('subjectLawsuit'); ?></th>
										<th><?php echo set_value('employeeName'); ?></th>
										<th><?php echo set_value('lawsuitLawyer'); ?></th>
										<th><?php echo set_value('lawsuits_Type'); ?></th>
										<th><?php echo set_value('state'); ?></th>
										<th><?php echo set_value('stage'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php 
									if(isset($txtSearch) && !empty($txtSearch))
									{
										$custId="-1";
										///$qry="CALL sp_getLawsuitDetails_Search('".$language."',-1,'".$txtSearch."') ";
										$qry="CALL sp_getLawsuitDetails_Search(:lang,:custId,:txtSearch)";
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
											<td><a href="javascript:showLSSearch(<?php echo $value['lsMasterId'].",".$value['lsDetailsId'].",0,0"; ?>);" class="btn-action-icon me-2"><span><i class="fe fe-eye"></i></span></a> </td>
											<td><?php echo $value['ls_code']; ?></td>
											<td><?php echo $value['referenceNo']; ?></td>
											<td><?php echo $value['lawsuitId']; ?></td>
											<td> <?php echo $value['customerName']; ?> </td>
											<td> <?php echo $value['lsSubject']; ?> </td>
											<td> <?php echo $value['empName_'.$language]; ?> </td>
											<td> <?php echo $value['lsStagesName_'.$language]; ?> </td>
											<td><?php echo $value['lsTypeName_'.$language]; ?></td>
											<td style="color:<?php echo $value['lsColor']; ?>"><?php echo $value['lsStateName_'.$language]; ?></td>
											<td><?php echo $value["lsStagesName_".$language]; ?></td>
										</tr>
										<?php 
											
										}
									}
						?>
					</tbody>
					</table>
					</div>
					<input type='hidden' id='lsCount' value="<?php if(isset($serial)) echo $serial; else echo "0"; ?>" >
					
				</div>
			</div>
		</div>
	</div>
</div>


