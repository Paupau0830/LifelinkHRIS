<?php include 'inc/config.php'; ?>
<?php
if (empty($_SESSION['hris_id'])) {
    header('Location: login');
}
?>
<?php include 'inc/template_start.php'; ?>
<?php include 'inc/page_head.php'; ?>
<?php
if ($_SESSION['hris_role'] == "Processor") {
    header('Location: pending-tasks');
}
?>
<?php
$cid = $_SESSION['hris_company_id'];
$emp_num = $_SESSION['hris_employee_number'];
$banner = 'default-banner.png';
$get_maintenance = mysqli_query($db, "SELECT * FROM tbl_maintenance WHERE company_id = '$cid'");
$img = mysqli_fetch_assoc($get_maintenance);
?>
<div id="page-content">
    <div class="content-header">
        <div class="header-section">
            <div class="row">
                <div class="col-md-4 col-lg-6 hidden-xs hidden-sm">
                    <h1>Welcome <strong><?= $_SESSION['hris_account_name'] ?>!</strong></h1>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 col-lg-3">
            <a href="leave-application" class="widget widget-hover-effect1">
                <div class="widget-simple">
                    <div class="widget-icon pull-left themed-background-autumn animation-fadeIn">
                        <i class="fa fa-user-times"></i>
                    </div>
                    <h3 class="widget-content text-right animation-pullDown">
                        <strong>Leave Application</strong>
                    </h3>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-lg-3">
            <a href="offset_application" class="widget widget-hover-effect1">
                <div class="widget-simple">
                    <div class="widget-icon pull-left themed-background-spring animation-fadeIn">
                        <i class="fa fa-exchange"></i>
                    </div>
                    <h3 class="widget-content text-right animation-pullDown">
                        <strong>Offset Application</strong>
                    </h3>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-lg-3">
            <a href="overtime_request" class="widget widget-hover-effect1">
                <div class="widget-simple">
                    <div class="widget-icon pull-left themed-background-fire animation-fadeIn">
                        <i class="fa fa-hourglass"></i>
                    </div>
                    <h3 class="widget-content text-right animation-pullDown">
                        <strong>OT Application</strong>
                    </h3>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-lg-3">
            <a href="certificate-request" class="widget widget-hover-effect1">
                <div class="widget-simple">
                    <div class="widget-icon pull-left themed-background-amethyst animation-fadeIn">
                        <i class="fa fa-file"></i>
                    </div>
                    <h3 class="widget-content text-right animation-pullDown">
                        <strong>Certificate Request</strong>
                    </h3>
                </div>
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="block full">
                <div class="block-title">
                    <h2><strong>Recent Leave Applications</strong></h2>
                </div>
                <div class="table-responsive">
                    <table class="table table-vcenter table-condensed table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">ID</th>
                                <th class="text-center">Employee #</th>
                                <th>Leave Type</th>
                                <th>Duration</th>
                                <th>Status</th>
                                <th>Date Created</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $company_id = $_SESSION['hris_company_id'];
                            $sql = mysqli_query($db, "SELECT * FROM tbl_leave_requests WHERE company_id = '$company_id'");
                            if ($_SESSION['hris_role'] == "User") {
                                $empnum = $_SESSION['hris_employee_number'];
                                $sql = mysqli_query($db, "SELECT * FROM tbl_leave_requests WHERE delegated_emp_number = '$empnum'");
                            }
                            while ($row = mysqli_fetch_assoc($sql)) {
                            ?>
                                <tr>
                                    <td class="text-center">LA-<?= format_transaction_id($row['ID']) ?></td>
                                    <td><?= $row['delegated_emp_number'] ?></td>
                                    <td><?= $row['leave_type'] ?></td>
                                    <td><?= $row['startDate'] . ' >> ' . $row['endDate'] ?></td>
                                    <td><?= $row['status'] ?></td>
                                    <td><?= $row['date_filed'] ?></td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="leave-details?<?= md5('id') . '=' . md5($row['ID']) ?>" data-toggle="tooltip" title="View" class="btn btn-xs btn-default"><i class="fa fa-eye"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="block full">
                <div class="block-title">
                            <h2><strong>Recent Attendance Adjustment Applications</strong></h2>
                        </div>
                        <div class="table-responsive">
                            <table id="two-column" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th class="text-center">Employee</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Time</th>
                        <th class="text-center">Type</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = mysqli_query($db, "SELECT * FROM tbl_attendance_adjust_request WHERE emp_num = '$emp_num'");
                    while ($row = mysqli_fetch_assoc($sql)) {
                    ?>
                    <tr>
                            <td class="text-center"><?= $row['id'] ?></td>
                            <td class="text-center"><?= $row['emp_num'] ?></td>
                            <td class="text-center"><?= $row['date'] ?></td>
                            <td class="text-center"><?= $row['time'] ?></td>
                            <td class="text-center"><?= $row['request_type'] ?></td>
                            <td class="text-center"><?= $row['status'] ?></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="attendance_adjustment_request-viewonly?<?= md5('id') . '=' . md5($row['id']) ?>" data-toggle="tooltip" title="View" class="btn btn-xs btn-default"><i class="fa fa-eye"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
                        </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="block full">
                        <div class="block-title">
                            <h2><strong>Recent OT Applications</strong></h2>
                        </div>
                        <div class="table-responsive">
                            <table id="two-column" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th class="text-center">Date From</th>
                        <th class="text-center">Date To</th>
                        <th class="text-center">Date Filed</th>
                        <th class="text-center">Duration</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = mysqli_query($db, "SELECT * FROM tbl_ot_request WHERE emp_num = '$emp_num'");
                    while ($row = mysqli_fetch_assoc($sql)) {
                    ?>
                    <tr>
                            <td class="text-center"><?= $row['id'] ?></td>
                            <td class="text-center"><?= $row['date_from'] ?></td>
                            <td class="text-center"><?= $row['date_to'] ?></td>
                            <td class="text-center"><?= $row['date_filed'] ?></td>
                            <td class="text-center"><?= $row['total_duration'] ?></td>
                            <td class="text-center"><?= $row['status'] ?></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="overtime_request_list-viewonly?<?= md5('id') . '=' . md5($row['id']) ?>" data-toggle="tooltip" title="View" class="btn btn-xs btn-default"><i class="fa fa-eye"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="block full">
                        <div class="block-title">
                    <h2><strong>Recent Offset Applications</strong></h2>
                </div>
                <div class="table-responsive">
                    <table class="table table-vcenter table-condensed table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">ID</th>
                                <th class="text-center">Date From</th>
                                <th class="text-center">Date To</th>
                                <th class="text-center">Date Filed</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = mysqli_query($db, "SELECT * FROM tbl_offset_request WHERE emp_num = '$emp_num'");
                            while ($row = mysqli_fetch_assoc($sql)) {
                            ?>
                            <tr>
                            <td class="text-center"><?= $row['id'] ?></td>
                            <td class="text-center"><?= $row['date_from'] ?></td>
                            <td class="text-center"><?= $row['date_to'] ?></td>
                            <td class="text-center"><?= $row['date_filed'] ?></td>
                            <td class="text-center"><?= $row['status'] ?></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="offset_application_viewonly?<?= md5('id') . '=' . md5($row['id']) ?>" data-toggle="tooltip" title="View" class="btn btn-xs btn-default"><i class="fa fa-eye"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Widgets Row -->
</div>
<!-- END Page Content -->

<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>

<!-- Google Maps API Key (you will have to obtain a Google Maps API key to use Google Maps) -->
<!-- For more info please have a look at https://developers.google.com/maps/documentation/javascript/get-api-key#key -->
<script src="https://maps.googleapis.com/maps/api/js?key="></script>
<script src="js/helpers/gmaps.min.js"></script>

<!-- Load and execute javascript code used only in this page -->
<script src="js/pages/index.js"></script>
<script src="js/pages/tablesDatatables.js"></script>
<script>
    $(function() {
        Index.init();
        TablesDatatables.init();
    });
</script>

<?php include 'inc/template_end.php'; ?>