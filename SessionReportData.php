<?php
$_POST['reportType'] = "detailed";
$_POST['type'] = -1;
$_POST['state'] = -1;
$_POST['stage'] = -1;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once('config/conn.php');
$language = $_SESSION['lang'];
/////include_once('languageActions.php');

$pageName = "Lawsuit";
$pageName2 = "LawsuitDetail";
$qry = "SELECT l.`phrase`, $language AS VALUE FROM `language` l
	LEFT JOIN languagepageref r ON r.languageid=l.`id`
	INNER JOIN `tbl_pagemenu` m ON m.`pageId`=r.`menuId`
	WHERE m.`pageName` IN(:pageName,:pageName2)";
$stmt = $dbo->prepare($qry);
$stmt->bindParam(":pageName", $pageName, PDO::PARAM_STR);
$stmt->bindParam(":pageName2", $pageName2, PDO::PARAM_STR);
if ($stmt->execute()) {
    $resultPhrase = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $errorInfo = $stmt->errorInfo();
    exit($json = $errorInfo[2]);
}
////print_r($result);
function set_value($val)
{
    foreach ($GLOBALS['resultPhrase'] as $value) {
        if (trim($value['phrase']) == trim($val)) {
            return $value['VALUE'];
            break;
        }
    }
}


?>
<table class="table table-center table-hover datatable" id="example">
    <thead class="thead-light">
        <tr>
            <th>#</th>
            <th><?php echo set_value('lsMasterCode'); ?></th>
            <th><?php echo set_value('lawsuitId'); ?></th>
            <th><?php echo set_value('lawsuitLocation'); ?></th>
            <th><?php echo set_value('customer'); ?></th>
            <th><?php echo set_value('opponent'); ?></th>
            <th> <?php echo set_value('sessions'); ?></th>
            <th> <?php echo set_value('dateSession'); ?></th>
            <th> <?php echo set_value('timeSession'); ?></th>
        </tr>
    </thead>
    <tbody>


        <?php

        ////$qry="CALL sp_getLawsuitDetails('".$language."',".$_SESSION['customerId'].",".$_POST['type'].",".$_POST['state'].",".$_POST['stage'].") ";
        $qry = "CALL sp_getLawsuitDetails_Summary('" . $language . "'," . $_POST['type'] . "," . $_POST['state'] . "," . $_POST['stage'] . ") ";
        $stmt = $dbo->prepare($qry);
        //$stmt->bindParam(":to_date",$to_date,PDO::PARAM_STR);
        if ($stmt->execute()) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $errorInfo = $stmt->errorInfo();
            exit($json = $errorInfo[2]);
        }
        ///exit;
        $serial = 1;
        $lsDetailsId = 0;

        function getCountSessions($lsDetailsId)
        {
            $count = 0;
            foreach ($GLOBALS['result'] as $i => $value) {
                if ($lsDetailsId == $value['lsDetailsId_Session'])
                    $count++;
            }
            return $count + 2;
        }

        foreach ($result as $i => $value) {
            if ($lsDetailsId != $value['lsDetailsId']) {
                $lsDetailsId = $value['lsDetailsId'];
                $rowspan = 0;
                if ($_POST['reportType'] == "detailed" && $value['lsDetailsId_Session'] > 0) {
                    $rowspan = getCountSessions($lsDetailsId);
                }

        ?>
                <!-- <tr>
					<td <?php if ($rowspan > 0) echo "rowspan='" . $rowspan . "'"; ?> > <?php echo $serial; ?> </td>
					<td <?php if ($rowspan > 0) echo "rowspan='" . $rowspan . "'"; ?> ><?php echo $value['ls_code']; ?></td>
					<td> <?php echo $value['empName_' . $language]; ?> </td>
					<td> <?php echo $value['lawsuitId']; ?> </td>
					<td> <?php echo $value['customerName']; ?> </td>
					<td <?php if ($rowspan > 0) echo "rowspan='" . $rowspan . "'"; ?> > <?php echo $value['oppoName']; ?> </td>
					<td <?php if ($rowspan > 0) echo "rowspan='" . $rowspan . "'"; ?> > <?php echo $value['lsStateName_' . $language]; ?> </td>
					<td <?php if ($rowspan > 0) echo "rowspan='" . $rowspan . "'"; ?> > <?php echo $value['lsStagesName_' . $language]; ?> </td>
					<td <?php if ($rowspan > 0) echo "rowspan='" . $rowspan . "'"; ?> > <?php
                                                                                    if (!empty($value['lsDate'])) {
                                                                                        $displayDate = "displayDate_$language";
                                                                                        echo  $displayDate($value['lsDate']);
                                                                                    }
                                                                                    ?>
					</td>
				</tr> -->
                <?php
                if ($rowspan > 0) {
                ?>
                    <?php
                    foreach ($result as $i => $innerValue) {
                        if ($lsDetailsId == $innerValue['lsDetailsId_Session']) {
                            if (new DateTime($_POST['from']) <= new DateTime($innerValue['sessionDate']) && new DateTime($innerValue['sessionDate']) <= new DateTime($_POST['to'])) {
                    ?>
                                <tr>
                                    <td> <?php echo $serial; ?> </td>
                                    <td><?php echo $value['ls_code']; ?></td>
                                    <!-- <td> <?php echo $value['empName_' . $language]; ?> </td> -->
                                    <td> <?php echo $value['lawsuitId']; ?> </td>
                                    <td> <?php echo $value['location']; ?> </td>
                                    <td> <?php echo $value['customerName']; ?> </td>
                                    <td> <?php echo $value['oppoName']; ?> </td>
                                    <td> <?php echo $innerValue['sessionName']; ?> </td>
                                    <td> <?php echo $innerValue['sessionDate']; ?></td>
                                    <td> <?php echo $innerValue['sessionTime']; ?></td>
                                </tr>
                    <?php
                                $serial++;
                            }
                        }
                    }
                    ?>
                    </tr>
                <?php
                } ?>

        <?php
                ///break;

            }
        }
        ?>
    </tbody>
</table>
<script>
    $('#setData_session .datatable').DataTable({
        "bFilter": true,
        "destroy": true,
        "sDom": 'fBtlpi',
        "ordering": true,
        "order": [],

        "language": {
            search: '<i class="fas fa-search"></i>',
            searchPlaceholder: "Search",
            sLengthMenu: '_MENU_',
            paginate: {
                next: 'Next <i class=" fa fa-angle-double-right ms-2"></i>',
                previous: '<i class="fa fa-angle-double-left me-2"></i> Previous'
            },
        },
        initComplete: (settings, json) => {
            $('.dataTables_filter').appendTo('#tableSearch');
            $('.dataTables_filter').appendTo('.search-input');
            $('.dt-buttons').css('display', 'none');
        },
    });
</script>