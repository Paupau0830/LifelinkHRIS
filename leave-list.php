<?php include 'inc/config.php'; ?>
<?php include 'inc/template_start.php'; ?>
<?php include 'inc/page_head.php'; ?>
<?php
if (empty($_SESSION['hris_id'])) {
    header('Location: login');
}

$company_id = $_SESSION['hris_company_id'];
$empnum = $_SESSION['hris_employee_number'];
if (isset($_GET['selected_emp_name'])) {
    $emp_name = $_GET['selected_emp_name'];
} else {
    $emp_name = '';
}
$monthyear = '';
$monthName = '';
$year = '';
$month = '';
if (isset($_GET['selected_monthyear'])) {
    $monthyear = $_GET['selected_monthyear'];
    $monthyear = explode('-', $monthyear);
    $year = $monthyear[0];
    $month = $monthyear[1];
    $monthName = date("F", mktime(0, 0, 0, $month, 10));
}

$get_role = mysqli_query($db, "SELECT * FROM tbl_users WHERE employee_number = '$empnum'");
while ($row = mysqli_fetch_assoc($get_role)) {
    $role = $row['role'];
}

if ($role == 'Manager') {
    $status = 'Manager Approval';
} else if ($role == 'HR Processing') {
    $status = 'HR Approval';
} else {
    $status = 'Boss Approval';
}
?>

<div id="page-content">
    <div class="content-header">
        <div class="header-section">
            <h1>
                <i class="fa fa-users"></i><strong>Leave Application</strong>
            </h1>
        </div>
    </div>
    <!-- Datatables Content -->
    <div class="block full">
        <div class="block-title">
            <div class="block-options pull-left">
                <!-- <a href="leave-application" class="btn btn-primary">Leave Application</a> -->
            </div>
            <h2><strong>Leave Application</strong> Summary List</h2>
        </div>
        <?php

        if ($role != 'User') {
        ?>
            <form method="GET">
                <div class="container-fluid">
                    <div class="row">

                        <div class="col-md-3">
                            <label style="margin-top: 8px;"> Month & Year: </label> &nbsp; &nbsp;
                            <input type="month" name="selected_monthyear" class="form-control" required>
                            <!-- <input type="text" value="<?= $monthName ?>"> -->
                        </div>
                        <div class="col-md-4">
                            <label style="margin-top: 8px; padding-right:30px;">Employee Name: </label> &nbsp; &nbsp;

                            <select name="selected_emp_name" id="selected_emp_name" class="form-control select-chosen">
                                <option></option>
                                <?php
                                $sql = mysqli_query($db, "SELECT * FROM tbl_personal_information");
                                while ($row = mysqli_fetch_assoc($sql)) {
                                ?>
                                    <option value="<?= $row['account_name'] ?>"><?= $row['account_name'] ?></option>

                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-warning" name="filter_out" style="margin-top: 32px; width:100px; font-weight:800; letter-spacing:1px;">Filter</button>
                            <a href="leave-list" class="btn btn-danger" style="margin-top: 32px; width:100px; font-weight:800; letter-spacing:1px;">Clear</a>
                        </div>
                    </div>


                </div>
            </form>
            <br>

        <?php
        }

        ?>
        <div class="table-responsive">

            <table id="leave-list" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center" style="font-size: 12px;">ID</th>
                        <th style="font-size: 12px;">Employee Number</th>
                        <th style="font-size: 12px;">Employee Name</th>
                        <th style="font-size: 12px;">Leave Type</th>
                        <th style="font-size: 12px;">Duration</th>
                        <th style="font-size: 12px;">Total Days</th>
                        <th style="font-size: 12px;">Status</th>
                        <th style="font-size: 12px;">Date Created</th>
                        <th class="text-center" style="font-size: 12px;">Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    if ($role != "User") {
                        if ($emp_name != '' && $monthyear != '') {
                            $sql = mysqli_query($db, "SELECT * FROM tbl_leave_requests WHERE emp_name = '$emp_name' AND month_selected = '$monthName' AND year_selected = '$year' AND status = '$status'");
                        } else {
                            $sql = mysqli_query($db, "SELECT * FROM tbl_leave_requests WHERE status = '$status'");
                        }

                        while ($row = mysqli_fetch_assoc($sql)) {
                    ?>
                            <tr style="font-size: 11px;">
                                <td class="text-center"><?= format_transaction_id($row['ID']) ?></td>
                                <td><?= $row['delegated_emp_number'] ?></td>
                                <td><?= $row['emp_name'] ?></td>
                                <td><?= $row['leave_type'] ?></td>
                                <td><?= $row['startDate'] . ' >> ' . $row['endDate'] ?></td>
                                <td><?= $row['total_day'] ?></td>
                                <td><?= $row['status'] ?></td>
                                <td><?= $row['date_filed'] ?></td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="leave-details?<?= md5('id') . '=' . md5($row['ID']) ?>" data-toggle="tooltip" title="View" class="btn btn-s btn-default"><i class="fa fa-eye">&nbsp; View</i></a>
                                    </div>
                                </td>
                            </tr>
                        <?php
                        }
                    } else {
                        $sql = mysqli_query($db, "SELECT * FROM tbl_leave_requests WHERE delegated_emp_number = '$empnum'");
                        while ($row = mysqli_fetch_assoc($sql)) {
                        ?>
                            <tr style="font-size: 11px;">
                                <td class="text-center"><?= format_transaction_id($row['ID']) ?></td>
                                <td><?= $row['delegated_emp_number'] ?></td>
                                <td><?= $row['emp_name'] ?></td>
                                <td><?= $row['leave_type'] ?></td>
                                <td><?= $row['startDate'] . ' >> ' . $row['endDate'] ?></td>
                                <td><?= $row['total_day'] ?></td>
                                <td><?= $row['status'] ?></td>
                                <td><?= $row['date_filed'] ?></td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="leave-details?<?= md5('id') . '=' . md5($row['ID']) ?>" data-toggle="tooltip" title="View" class="btn btn-s btn-default"><i class="fa fa-eye">&nbsp; View</i></a>
                                    </div>
                                </td>
                            </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- END Datatables Content -->
</div>
<!-- END Page Content -->

<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>

<!-- Load and execute javascript code used only in this page -->
<script src="js/pages/tablesDatatables.js"></script>
<script>
    function UnsetGet() {
        unset($GLOBALS['emp_name']);
        unset($GLOBALS['monthyear']);
    }
</script>
<script>
    $(function() {
        TablesDatatables.init();
    });
</script>

<?php include 'inc/template_end.php'; ?>