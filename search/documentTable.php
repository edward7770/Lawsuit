<?php 
if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
?>

<div class="card-header">
	<div class="d-flex justify-content-between align-items-center">
		<h5 class="card-title"><?php echo set_value('documentList'); ?></h5>
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
										<th><?php echo set_value("documentName_ar"); ?></th>
										<th><?php echo set_value("documentName_en"); ?></th>
										<th><?php echo set_value("description"); ?></th>
										<th><?php echo set_value("fileUpload"); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php 
									if(isset($txtSearch) && !empty($txtSearch))
									{
										$custId="-1";
										///$qry="CALL sp_getLawsuitDetails_Search('".$language."',-1,'".$txtSearch."') ";
										$qry="CALL sp_getFile_Search(:lang,:custId,:txtSearch)";
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
										$noImage='<a href="#" class="btn-action-icon"><span><i class="fa fa-times fa-2x red-color"></i></span></a>';
										foreach($resultSearch as $i=> $value)
										{
											$serial++;
											if(empty($value['docFilePath']) || empty($value['docFileName']))
												$fileImage=$noImage;
											else 
												$fileImage='<a href="'.$value['docFilePath'].$value['docFileName'].'" target="_blank" class="btn-action-icon"><span><i class="fa fa-file fa-2x"></i></span></a>';	
										?>
										<tr>
											<td> <?php echo $serial; ?> </td>
											<td><a href="javascript:showSearch(<?php echo $value['docsId'].",'Document.php'"; ?>);" class="btn-action-icon me-2"><span><i class="fe fe-eye"></i></span></a> </td>
											<td><?php echo $value['docName_ar']; ?></td>
											<td><?php echo $value['docName_en']; ?></td>
											<td><?php echo $value['docDescription']; ?></td>
											<td> <?php echo $fileImage; ?> </td>
										</tr>
										<?php 
											
										}
									}
						?>
					</tbody>
					</table>
					</div>
					<input type='hidden' id='lsCountDoc' value="<?php if(isset($serial)) echo $serial; else echo "0"; ?>" >
					
				</div>
			</div>
		</div>
	</div>
</div>


