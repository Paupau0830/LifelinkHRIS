<?php include 'inc/config.php'; ?>
<?php include 'inc/template_start.php'; ?>
<?php include 'inc/page_head.php'; ?>
<?php
if (empty($_SESSION['hris_id'])) {
    header('Location: login');
}
?>
<?php
$fid = $_GET[md5('id')]; // Foreign id
$rid = ""; // row id
$sql = mysqli_query($db, "SELECT * FROM tbl_certificate_requests");
while ($row = mysqli_fetch_assoc($sql)) {
    if ($fid == md5($row['ID'])) {
        $rid = $row['ID'];
    }
}
?>
<!-- Page content -->
<div id="page-content">
    <!-- eCommerce Dashboard Header -->
    <div class="content-header">
        <div class="header-section">
            <h1>
                <i class="fa fa-file"></i> <strong> Certificate Request Details</strong>
            </h1>
            <!-- <ul class="nav-horizontal text-center">
                  <li class="">
                  <a href="certificate-request"><i class="fa fa-certificate" style="margin-right: 3px;"></i> Certificate Request</a></li>
                  </li>
                </ul>  -->
        </div>
    </div>
    <div class="block full">
        <div class="block-title">
            <div class="block-options pull-right">
                <a href="certificate-request-list" class="btn btn-alt btn-sm btn-default">Certificate Request List</a>
            </div>
            <h2><strong>Certificate Request</strong> Details</h2>
        </div>
        <?php
        $get_details = mysqli_query($db, "SELECT * FROM tbl_certificate_requests WHERE ID = '$rid'");
        while ($row = mysqli_fetch_assoc($get_details)) {
            $r_id = $row['ID'];
            $account_name = $row['employee_name'];
            $emp_number = $row['employee_number'];
            $requested_by = $row['requested_by'];
            $created_date = $row['date_created'];
            $status = $row['status'];
            $cert_type = $row['certificate_type'];
            $date_required = $row['date_required'];
            $purpose = $row['purpose'];
            $remarks = $row['remarks'];
            $hr_remarks = $row['hr_remarks'];
            $acknowledge_by = $row['acknowledged_by'];
        }
        ?>
            <div class="container-fluid">
                <?= $res ?>
                <div class="row">
                    <div class="col-md-6">
                        <p> Certificate Request #: <b>CR-<?= format_transaction_id($r_id) ?></b></p>
                    </div>
                    <div class="col-md-6"></div>
                    <div class="col-md-6">
                        <p>Requested by: <b><?= $account_name ?></b></p>
                    </div>
                    <div class="col-md-6">
                        <p>Delegate Name: <b><?= $requested_by ?></b></p>
                    </div>
                    <div class="col-md-6">
                        <p>Date Applied: <b><?= $created_date ?></b></p>
                    </div>
                    <div class="col-md-6">
                        <p>Status: <b><?= $status ?></b></p>
                    </div>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $r_id ?>">
                    <input type="hidden" name="employee_number" value="<?= $emp_number ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Requestor *</label>
                                <select name="employee_name" class="select-chosen" data-placeholder="Choose a requestor..." style="width: 250px;">
                                    <option value="<?= $account_name?>"><?= $account_name?></option>
                                    <?php
                                    // $employees = get_employees_from_company($_SESSION['hris_company_id']);
                                    // foreach ($employees as $k => $v) {
                                    //     $name = get_personal_information($v['employee_number']);
                                    //     if ($v['employee_number'] == $row['employee_number']) {
                                    //         echo '<option value="' . $v['employee_number'] . '" selected>' . $name['account_name'] . '</option>';
                                    //     } else {
                                    //         echo '<option value="' . $v['employee_number'] . '">' . $name['account_name'] . '</option>';
                                    //     }
                                    // }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label>Certificate Type</label>
                            
                            <!-- <input type="text" class="select-chosen" value="<?=$cert_type?>">  -->
                                   
                            <select name="certificate_type" class="form-control select-chosen" data-placeholder="Choose a certificate type..." style="width: 250px;">
                                <option><?=$cert_type?></option>
                                <?php
                                
                                ?>
                            </select>
                            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date Required</label>
                                <input type="date" name="date_required" readonly class="form-control" value="<?= $date_required ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Purpose</label>
                                <input type="text" name="purpose" readonly class="form-control" value="<?= $purpose ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Remarks</label>
                        <textarea name="remarks" rows="3" readonly class="form-control"><?= $remarks ?></textarea>
                    </div>
                    <?php
                    $approver_field = "";
                    $is_approver = "";
                    $is_approver = is_approver_certificate_requests($_SESSION['hris_id']);
                    if ($_SESSION['hris_role'] == "User") {
                        $approver_field = "readonly onclick='return false;'";
                    }else {
                        $approver_field = "";
                    }
                    ?>
                    <div class="form-group">
                        <label class="bmd-label-floating">HR Remarks</label>
                        <textarea <?= $approver_field ?> rows="5" class="form-control" name="hr_remarks"><?= $hr_remarks ?></textarea>
                    </div>
                    <input type="hidden" name="acknowledged_by" value="<?= $_SESSION['hris_employee_number'] ?>">
                    <?php


                        
                    $role = $_SESSION['hris_role'];
                    if($role == "User"){
                        if($status == "Acknowledged"){?>
                            <p style="float:right">Acknowledged by: <?= $acknowledge_by?> </p>
                            <button class="btn btn-success" name="btn_download_certificate">Download Certificate</button>
                        <?php
                        }
                    }else{
                        if($status != "Acknowledged"){
                            echo '<button class="btn btn-success" name="btn_acknowledge_request">Acknowledge</button>';
                        }else{
                            echo '<button class="btn btn-success" name="btn_download_certificate">Download Certificate</button>';
                        }
                            
                    }
                            
                    
                    
                    ?>
                </form>
            </div>
        <?php
        
        ?>
    </div>
</div>
<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>
<?php include 'inc/template_end.php'; ?>