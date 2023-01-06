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
$sql = mysqli_query($db, "SELECT * FROM tbl_ot_application");
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
                <i class="fa fa-hourglass"></i>View Overtime Application
            </h1>
        </div>
    </div>
    <div class="block full">
        <div class="block-title">
            <div class="block-options pull-right">
                <a href="ot-list" class="btn btn-alt btn-sm btn-default">Overtime Application List</a>
            </div>
            <h2><strong>Overtime Application</strong> Details</h2>
        </div>
        <?php
        $get_details = mysqli_query($db, "SELECT * FROM tbl_ot_application WHERE ID = '$rid'");
        while ($r = mysqli_fetch_assoc($get_details)) {
            $per_info = get_personal_information($r['employee_number']);
            $emp_info = get_employment_information($r['employee_number']);
            $approver = get_personal_information($emp_info['approver']);
        ?>
            <div class="container-fluid">
                <?= $res ?>
                <p>Overtime Application #: OT-<?= format_transaction_id($r['ID']) ?></p>
                <div class="row">
                    <div class="col-md-4">
                        <p>Applied by: <?= $per_info['account_name'] ?></p>
                    </div>
                    <div class="col-md-4">
                        <p>Employee Number: <?= $r['employee_number'] ?></p>
                    </div>
                    <div class="col-md-4">
                        <p>Approver Name: <?= $approver['account_name'] ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <p>Attachment: <a href="uploads/<?= $r['attachment'] ?>" target="_blank">View</a></p>
                    </div>
                    <div class="col-md-4">
                        <p>Date Applied: <?= $r['date_created'] ?></p>
                    </div>
                    <div class="col-md-4">
                        <p>Status: <?= $r['status'] ?></p>
                    </div>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $r['ID'] ?>">
                    <input type="hidden" name="employee_number" value="<?= $r['employee_number'] ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Month of Overtime</label>
                                <select name="month_of_ot" required class="select-chosen" data-placeholder="Choose a month..." style="width: 250px;">
                                    <option></option>
                                    <?php
                                    for ($m = 1; $m <= 12; $m++) {
                                        $month = date('F', mktime(0, 0, 0, $m, 1, date('Y')));
                                        if ($month == $r['month_of_ot']) {
                                            echo '<option selected>' . $month . '</option>';
                                        } else {
                                            echo '<option>' . $month . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label>Total Hours</label>
                            <input type="number" name="total_hours" class="form-control" value="<?= $r['total_hours'] ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Remarks</label>
                        <textarea name="remarks" rows="3" class="form-control"><?= $r['remarks'] ?></textarea>
                    </div>
                    <?php
                    $approver_field = "";
                    $role =  $_SESSION['hris_role'];
                    if ($_SESSION['hris_employee_number'] != $approver['employee_number']) {
                        $approver_field = "readonly onclick='return false;'";
                    }
                    ?>
                    <div class="form-group" style="margin-top: 40px">
                        <label class="bmd-label-floating">Approver Remarks</label>
                        <textarea <?= $approver_field ?> rows="3" class="form-control" name="approver_remarks"><?= $r['approver_remarks'] ?></textarea>
                    </div>
                    <?php
                    if ($r['approver_attachment'] == "") { ?>
                        <label class="bmd-label-floating">Approver Attachment</label>
                        <input <?= $approver_field ?> type="file" name="approver_attachment" class="form-control">
                    <?php } else { ?>
                        <p>Attachment: <a target="_blank" href="uploads/<?= $r['approver_attachment'] ?>" class="text-info">View</a></p>
                    <?php }
                    if ($r['status'] == "Pending" && $approver['employee_number'] == $_SESSION['hris_employee_number']) {
                    ?>
                        <br>
                        <button class="btn btn-primary" name="btn_approve_ot">Approve</button>
                        <button class="btn btn-danger" name="btn_decline_ot">Decline</button>
                    <?php
                    }
                    // if ($row['status'] == "Approved" && $row['acknowledgement'] == "0" && strpos($_SESSION['mpw_role'], 'HR Timekeeping Admin') !== false) {
                    ?>
                    <!-- <div style="float:right">
                            <button class="btn btn-info" name="btn_ot_acknowledge" id="btn_ot_acknowledge">Acknowledge</button>
                        </div> -->
                    <?php
                    // }
                    if ($r['employee_number'] == $_SESSION['hris_employee_number'] && $r['status'] != "Declined" && $r['status'] != "Cancelled") {
                    ?>
                        <br>
                        <button class="btn btn-danger" id="btn_cancel_ot" name="btn_cancel_ot" id="btn_cancel_ot">Cancel</button>
                    <?php
                    }
                    ?>
                </form>
            </div>
        <?php
        }
        ?>
    </div>
</div>
<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>
<?php include 'inc/template_end.php'; ?>