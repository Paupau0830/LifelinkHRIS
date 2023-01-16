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
// $employee_name = $_SESSION['hris_account_name']; // row id
$employee_num = $_SESSION['hris_employee_number'];

$sql = mysqli_query($db, "SELECT t.* FROM tbl_leave_requests t ");
while ($row = mysqli_fetch_assoc($sql)) {
    if ($fid == md5($row['ID'])) {
        $rid = $row['ID'];
        $company_id = $row['company_id'];
        $requestor = $row['requestor'];
        $delegated_emp_number = $row['delegated_emp_number'];
        $employee_name = $row['emp_name'];
        $leave_type = $row['leave_type'];
        $start_date = $row['startDate'];
        $end_date = $row['endDate'];
        $total_day = $row['total_day'];
        $reason = $row['reason'];
        $remarks = $row['remarks'];
        $duration = $row['duration'];
        $attachment = $row['attachment'];
        $_SESSION['approver'] = $row['approver'];
        $_SESSION['approver_remarks'] = $row['approver_remarks'];
        $_SESSION['status'] = $row['status'];
        $date_filed = $row['date_filed'];
        $late_filing = $row['late_filing'];
        // $_SESSION['cancellation_reason'] = $row['cancellation_reason'];


    }
}




?>
<!-- Page content -->
<div id="page-content">
    <!-- eCommerce Dashboard Header -->
    <div class="content-header">
        <div class="header-section">

            <h1>
                <i class="fa fa-user-times"></i>View Leave Application
            </h1>
        </div>
    </div>
    <div class="block full">
        <div class="block-title">

            <h2><strong>Leave Application</strong> Details</h2>
        </div>

        <div class="container-fluid">
            <p class="text-danger" id="result"></p>
            <p class="text-danger" id="res"></p>
            <?= $res ?>
            <!-- leave details approver's information -->
            <form method="POST">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Leave Application # </label>
                            <input type="text" id="leave_app_num" name="la_id" value="<?= format_transaction_id($rid) ?>" class="form-control" readonly>
                            <input type="hidden" id="leave_app_id" name="leave_app_id" value="<?= $rid ?>" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Date Filed</label>
                            <input type="text" readonly id="app_date" name="la_application_date" value="<?= $date_filed ?>" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Applied By</label>
                            <input type="text" readonly id="applied_by" value="<?= $employee_name ?>" name="applied_by" class="form-control">
                        </div>
                    </div>
                </div>


                <!-- <form method="POST"> -->
                <!-- <input type="hidden" name="id" value="<?= $r['ID'] ?>">
                    <input type="hidden" name="approver" value="<?= $r['approver'] ?>"> -->
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>Employee Number</label>
                            <input type="text" id="la_emp_number" name="la_emp_number" value="<?= $delegated_emp_number ?>" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>Leave Type</label>
                            <input type="text" id="la_leave_type" name="la_leave_type" value="<?= $leave_type ?>" class="form-control" readonly>

                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Status</label>
                            <input type="text" readonly id="application_file" name="la_status" value="<?= $_SESSION['status'] ?>" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>Start Date *</label>
                            <input type="text" readonly name="la_startDate" required id="startDate" class="form-control" value="<?= $start_date ?>">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>End Date *</label>
                            <input type="text" readonly name="la_endDate" required id="endDate" class="form-control" value="<?= $end_date ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Total Day/s</label>
                            <input type="text" readonly name="la_total_days" class="form-control" value="<?= $total_day ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">

                            <label>Duration</label>
                            <input type="text" readonly name="la_duration" class="form-control" value="<?= $duration ?>">

                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>Attachment</label>


                            <div class="inlinetb" style="display:flex;">
                                <?php
                                if ($attachment != '') {
                                    $exp_attachment = explode('.', $attachment);
                                    $file = $exp_attachment[0];
                                    $ext = $exp_attachment[1];
                                } else {
                                    $exp_attachment = '';
                                    $file = '';
                                    $ext = '';
                                }
                                ?>
                                <input type="text" readonly name="la_attachment" id="la_attachment" class="form-control" value="<?= $attachment ?>">
                                <?php

                                if ($attachment != "" && $ext == 'pdf') {

                                ?>
                                    <div class="btn-group" style="margin-left:5px;">
                                        <button class="btn btn-primary" name="view_attachment_cert" formnovalidate>View Attachment</button>

                                    </div>
                                <?php
                                } else if ($attachment != "" && $ext != 'pdf') { ?>
                                    <div class="img-group" style="margin-left:5px;">
                                        <!-- <button class="btn btn-primary" name="img_attachment_cert" formnovalidate>View Attachment</button> -->
                                        <a href="javascript:void(0)" class="btn btn-primary" onclick="$('#modal-view-img').modal('show');">View Attachment</a>

                                        <div id="modal-view-img" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header text-center">
                                                        <h2 class="modal-title"><i class="fa fa-image"></i> View Image Attachment</h2>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="container-fluid">
                                                            <div class="form-group">
                                                                <?php
                                                                $image_details  = mysqli_query($db, "SELECT * FROM tbl_leave_requests WHERE id = '$rid'");
                                                                while ($row = mysqli_fetch_array($image_details)) {

                                                                    echo "<img src='uploads/" . $row['attachment'] . "' style='width:100%; height:100%;' >";
                                                                }
                                                                ?>
                                                            </div>
                                                            <br>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Late Filing</label>
                            <?php
                            if ($late_filing == '1') {
                                $late_filing = 'Yes';
                            } else {
                                $late_filing = 'No';
                            }
                            ?>
                            <input type="text" readonly name="late_filing" class="form-control" value="<?= $late_filing ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Reason</label>
                            <textarea name="la_reason" readonly class="form-control" rows="3" style="resize:none;"><?= $reason ?></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Remarks</label>
                            <textarea name="la_remarks" readonly class="form-control" rows="3" style="resize:none;"><?= $remarks ?></textarea>
                        </div>
                    </div>
                </div>

                <hr>


                <?php

                $status = $_SESSION['status'];
                $approver = $_SESSION['approver'];
                $role = $_SESSION['hris_role'];
                if ($status != "Approved" && ($role == "Admin" || $role == "Manager" || $role == "Supervisor")) {
                ?>
                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Approver</label>
                                <input type="text" id="approver" value="<?= $_SESSION['approver'] ?>" name="la_approver" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Remarks</label>
                                <input type="text" id="app_remarks" value="<?= $_SESSION['approver_remarks'] ?>" name="la_approver_remarks" class="form-control">
                            </div>
                        </div>

                    </div>
                    <div style="float:right">
                        <?php

                        if ($status != "Cancelled" && ($role == "Admin" || $role == "Manager" || $role == "Supervisor")) {
                        ?>
                            <a href="javascript:void(0)" class="btn btn-warning" onclick="$('#modal-cancellation').modal('show');">Cancellation</a>&nbsp;
                            <div id="modal-cancellation" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header text-center">
                                            <h2 class="modal-title"><i class="fa fa-file"></i>&nbsp; Cancellation of Leave</h2>
                                        </div>
                                        <div class="modal-body">
                                            <div class="container-fluid">
                                                <div class="form-group">

                                                    <center>
                                                        <p style="font-size:20px;">This will Cancel the requested Leave Application. Do you wish to proceed?</p>
                                                        <p style="font-size:20px;">If <strong>YES</strong>, click <i>proceed</i>. If <strong>NO</strong>, click <i>outside the modal</i>.</p>
                                                    </center>

                                                    <?php
                                                    $cancel_id = '';
                                                    $cancellation_status = '';
                                                    $get_la = mysqli_query($db, "SELECT * FROM tbl_cancellation WHERE employee_number='$delegated_emp_number' AND leave_application_id = '$rid' AND status = 'For Cancellation'");
                                                    while ($row = mysqli_fetch_assoc($get_la)) {
                                                        $cancel_id = $row['id'];
                                                        $cancellation_status = $row['status'];
                                                    }

                                                    if ($cancel_id != '' || $cancellation_status == 'For Cancellation') {
                                                    ?>

                                                        <br>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <label>Employee's Request</label>
                                                                <div class="table-responsive">
                                                                    <table id="company-departments" class="table table-vcenter table-condensed table-bordered">
                                                                        <thead>
                                                                            <tr>
                                                                                <th class="text-center">Emp #</th>
                                                                                <th class="text-center">Name</th>
                                                                                <th class="text-center">Leave Application ID</th>
                                                                                <th class="text-center">Status</th>
                                                                                <th class="text-center">Reason</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php
                                                                            $tol_id = '0';
                                                                            $sql = mysqli_query($db, "SELECT * FROM tbl_cancellation WHERE employee_number='$delegated_emp_number' AND id = '$cancel_id' AND status = 'For Cancellation'");
                                                                            while ($row = mysqli_fetch_assoc($sql)) {
                                                                                $tol_id = $row['id'];
                                                                            ?>
                                                                                <tr>
                                                                                    <td class="text-center"><?= $row['employee_number'] ?></td>
                                                                                    <td class="text-center"><?= $row['employee_name'] ?></td>
                                                                                    <td class="text-center"><?= $row['leave_application_id'] ?></td>
                                                                                    <td class="text-center"><?= $row['status'] ?></td>
                                                                                    <td class="text-center"><?= $row['reason'] ?></td>
                                                                                </tr>
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                        </tbody>
                                                                    </table>
                                                                    <input type="hidden" name="tol_id" value="<?= $tol_id ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                                <input type="hidden" name="cancel_id" value="<?= $cancel_id ?>">
                                                <br><button class="btn btn-warning btn-block" name="cancellation" formnovalidate>Proceed</button>
                                                <br>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php } ?>

                        <a href="javascript:void(0)" class="btn btn-warning" onclick="$('#modal-transfer').modal('show');">Transfer of Leave</a>&nbsp;

                        <div id="modal-transfer" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header text-center">
                                        <h2 class="modal-title"><i class="fa fa-file"></i>&nbsp; Transfer of Leave</h2>
                                    </div>
                                    <div class="modal-body">
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Start Date</label>
                                                        <input type="text" readonly name="tol_startDate" id="tol_startDate" class="form-control" readonly value="<?= $start_date ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>End Date</label>
                                                        <input type="text" readonly name="tol_endDate" id="tol_endDate" class="form-control" readonly value="<?= $end_date ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Duration</label>
                                                        <input type="text" readonly name="tol_duration" id="tol_totaldays" class="form-control" readonly value="<?= $duration ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Total Days</label>
                                                        <input type="text" readonly name="tol_totaldays" id="tol_totaldays" class="form-control" readonly value="<?= $total_day ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Modified Start Date *</label>
                                                        <input type="date" name="m_startDate" required id="m_startDate" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Modified End Date *</label>
                                                        <input type="date" name="m_endDate" required id="m_endDate" class="form-control">

                                                    </div>
                                                </div>
                                                <div class=" col-md-3">
                                                    <div class="form-group">
                                                        <label>Duration</label>
                                                        <select name="leave_duration" id="leave_duration" required class="form-control select-chosen">
                                                            <option value="null">Select duration...</option>
                                                            <option value="Whole Day">Whole Day</option>
                                                            <option value="Half Day AM">Half Day AM</option>
                                                            <option value="Half Day PM">Half Day PM</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class=" col-md-3">
                                                    <div class="form-group">
                                                        <label>Total Days</label>
                                                        <input type="number" id="totalnumDays" required name="totalnumDays" step="any" class="form-control" value="0">
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                            $tol_appid = '';
                                            $status = '';
                                            $sql = mysqli_query($db, "SELECT * FROM tbl_transfer_of_leave WHERE employee_number='$delegated_emp_number' AND leaveapplication_id = '$rid' AND status = 'Pending'");
                                            while ($row = mysqli_fetch_assoc($sql)) {
                                                $tol_appid = $row['id'];
                                                $status = $row['status'];
                                            }

                                            if ($tol_appid != '' || $status == 'Pending') {
                                            ?>

                                                <br>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Employee's Request</label>
                                                        <div class="table-responsive">
                                                            <table id="company-job-grade" class="table table-vcenter table-condensed table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="text-center" style="font-size: 12px;">LA #</th>
                                                                        <th class="text-center" style="font-size: 12px;">Emp #</th>
                                                                        <th class="text-center" style="font-size: 12px;">Name</th>
                                                                        <th class="text-center" style="font-size: 12px;">Requested Start Date</th>
                                                                        <th class="text-center" style="font-size: 12px;">Requested End Date</th>
                                                                        <th class="text-center" style="font-size: 12px;">Duration</th>
                                                                        <th class="text-center" style="font-size: 12px;">Total Days</th>
                                                                        <th class="text-center" style="font-size: 12px;">Status</th>
                                                                        <th class="text-center" style="font-size: 12px;">Reason</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $tol_id = '0';
                                                                    $sql = mysqli_query($db, "SELECT * FROM tbl_transfer_of_leave WHERE employee_number='$delegated_emp_number' AND leaveapplication_id = '$rid' AND status = 'Pending' AND orig_startdate = '$start_date' AND orig_end_date = '$end_date'");
                                                                    while ($row = mysqli_fetch_assoc($sql)) {
                                                                        $tol_id = $row['id'];
                                                                    ?>
                                                                        <tr>
                                                                            <td class="text-center" style="font-size: 12px;"><?= $row['leaveapplication_id'] ?></td>
                                                                            <td class="text-center" style="font-size: 12px;"><?= $row['employee_number'] ?></td>
                                                                            <td class="text-center" style="font-size: 12px;"><?= $row['employee_name'] ?></td>
                                                                            <td class="text-center" style="font-size: 12px;"><?= $row['modified_startdate'] ?></td>
                                                                            <td class="text-center" style="font-size: 12px;"><?= $row['modified_enddate'] ?></td>
                                                                            <td class="text-center" style="font-size: 12px;"><?= $row['duration'] ?></td>
                                                                            <td class="text-center" style="font-size: 12px;"><?= $row['total_days'] ?></td>
                                                                            <td class="text-center" style="font-size: 12px;"><?= $row['status'] ?></td>
                                                                            <td class="text-center" style="font-size: 12px;"><?= $row['reason'] ?></td>
                                                                        </tr>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </tbody>
                                                            </table>
                                                            <input type="hidden" name="tol_id" value="<?= $tol_id ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php
                                            } ?>
                                            <br><br>

                                            <button class="btn btn-warning btn-block" name="transfer_of_leave" formnovalidate>Update</button>
                                            <br>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-success" name="btn_approve_leave_application" formnovalidate>Approve</button>
                        <button class="btn btn-danger" name="btn_decline_leave_application" id="btn_decline_leave" formnovalidate>Decline</button>
                    </div>
                <?php } else { ?>
                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Approver</label>
                                <input type="text" readonly id="approver" value="<?= $_SESSION['approver'] ?>" name="la_approver" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Remarks</label>
                                <input type="text" readonly id="app_remarks" value="<?= $_SESSION['approver_remarks'] ?>" name="la_approver_remarks" class="form-control">
                            </div>
                        </div>

                    </div>
                    <?php if ($role == "User") { ?>

                        <div class="right" style="float:right">
                            <a href="javascript:void(0)" class="btn btn-warning" onclick="$('#modal-transfer').modal('show');">Transfer of Leave</a>&nbsp;

                            <div id="modal-transfer" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header text-center">
                                            <h2 class="modal-title"><i class="fa fa-file"></i>&nbsp; Transfer of Leave</h2>
                                        </div>
                                        <div class="modal-body">
                                            <div class="container-fluid">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Start Date</label>
                                                            <input type="text" readonly name="tol_startDate" id="tol_startDate" class="form-control" readonly value="<?= $start_date ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>End Date</label>
                                                            <input type="text" readonly name="tol_endDate" id="tol_endDate" class="form-control" readonly value="<?= $end_date ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Duration</label>
                                                            <input type="text" readonly name="tol_duration" id="tol_totaldays" class="form-control" readonly value="<?= $duration ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Total Days</label>
                                                            <input type="text" readonly name="tol_totaldays" id="tol_totaldays" class="form-control" readonly value="<?= $total_day ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Modified Start Date *</label>
                                                            <input type="date" name="emp_m_startDate" required id="emp_m_startDate" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Modified End Date *</label>
                                                            <input type="date" name="emp_m_endDate" required id="emp_m_endDate" class="form-control">

                                                        </div>
                                                    </div>
                                                    <div class=" col-md-3">
                                                        <div class="form-group">
                                                            <label>Duration</label>
                                                            <select name="emp_leave_duration" id="emp_leave_duration" required class="form-control select-chosen">
                                                                <option value="null">Select duration...</option>
                                                                <option value="Whole Day">Whole Day</option>
                                                                <option value="Half Day AM">Half Day AM</option>
                                                                <option value="Half Day PM">Half Day PM</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class=" col-md-3">
                                                        <div class="form-group">
                                                            <label>Total Days</label>
                                                            <input type="number" id="emp_totalnumDays" required name="emp_totalnumDays" step="any" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Reason</label>
                                                            <input type="text" id="reason" required name="reason" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>

                                                <button class="btn btn-warning btn-block" name="transfer_of_leave_emp" formnovalidate>Submit</button>
                                                <br>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a href="javascript:void(0)" class="btn btn-warning" onclick="$('#modal-cancel').modal('show');">Cancellation</a>&nbsp;
                            <div id="modal-cancel" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header text-center">
                                            <h2 class="modal-title"><i class="fa fa-file"></i>&nbsp; Cancellation of Leave</h2>
                                        </div>
                                        <div class="modal-body">
                                            <div class="container-fluid">
                                                <div class="form-group">

                                                    <center>
                                                        <p style="font-size:20px;">This will Cancel the requested Leave Application. Do you wish to proceed?</p>
                                                        <p style="font-size:20px;">If <strong>YES</strong>, click <i>proceed</i>. If <strong>NO</strong>, click <i>outside the modal</i>.</p>
                                                    </center>
                                                    <label>Reason</label>
                                                    <input type="text" name="cancellation_remarks" class="form-control">


                                                </div>
                                                <br><button class="btn btn-warning btn-block" name="cancellation_emp" formnovalidate>Proceed</button>
                                                <br>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                } ?>

            </form>
        </div>
        <!--contentainer - fluid -->

    </div>
    <!--block content -->
</div>
<!--content header-->
</div>
<!--pagecontent-->


<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>
<script src="js/pages/tablesDatatables.js"></script>
<script>
    $(function() {
        TablesDatatables.init();
    });
</script>

<script>
    setInterval(function() {
        if ($('#endDate').val().length === 0) {
            $('#totalDays').val('');
        }
    }, 1000);
    $('#emp_m_endDate').focusout(function() {
        var emp_m_startDate = $('#emp_m_startDate').val();
        var emp_m_endDate = $('#emp_m_endDate').val();
        var la_leave_type = $('#la_leave_type').val();
        var compute_leave_durationn = '';
        $.ajax({
            url: "inc/config.php",
            method: "POST",
            data: {
                compute_leave_duration1: compute_leave_duration1,
                emp_m_startDate: emp_m_startDate,
                emp_m_endDate: emp_m_endDate,
                la_leave_type: la_leave_type
            },
            success: function(data) {
                $('#emp_totalnumDays').val(data);

            },
            complete: function() {

            }
        });
    });
    $('#m_endDate').focusout(function() {
        var m_startDate = $('#m_startDate').val();
        var m_endDate = $('#m_endDate').val();
        var la_leave_type = $('#la_leave_type').val();
        var compute_leave_durationn = '';
        $.ajax({
            url: "inc/config.php",
            method: "POST",
            data: {
                compute_leave_durationn: compute_leave_durationn,
                m_startDate: m_startDate,
                m_endDate: m_endDate,
                la_leave_type: la_leave_type
            },
            success: function(data) {
                $('#totalnumDays').val(data);

            },
            complete: function() {

            }
        });
    });
    $('#m_startDate').focusin(function() {
        var m_startDate = $('#m_startDate').val();

        if (m_startDate.length == 0) {
            $('#m_endDate').prop("disabled", true);
            $('#totalnumDays').val('0');

        } else {
            $('#m_endDate').prop("disabled", false);
        }

    });
    $('#m_startDate').focusout(function() {
        var m_startDate = $('#m_startDate').val();

        if (m_startDate.length == 0) {
            $('#m_endDate').prop("disabled", true);
            $('#totalnumDays').val('0');

        } else {
            $('#m_endDate').prop("disabled", false);

        }

    });
</script>
<?php include 'inc/template_end.php'; ?>