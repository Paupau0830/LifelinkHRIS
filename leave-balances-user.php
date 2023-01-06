<?php include 'inc/config.php'; ?>
<?php include 'inc/template_start.php'; ?>
<?php include 'inc/page_head.php'; ?>
<?php
if (empty($_SESSION['hris_id'])) {
    header('Location: login');
}

$datenow = date('Y-m-d');
$monthnow = date('m');
$daynow = date('d');
$test_date_now = strtotime(date('Y-m-d'));
$newDate = date("Y-m-d", strtotime("+1 month", $test_date_now));
$newMonth = date("m", strtotime("+1 month", $test_date_now));

// Every 1st day of the Month. 
if ($daynow == '1') {

    mysqli_query($db, "UPDATE tbl_leave_balances SET SL = SL + 0.6667, updated_monthly = 1 WHERE ID > '0' AND updated_monthly = '0'");
    mysqli_query($db, "UPDATE tbl_leave_balances SET VL = VL + 1, updated_monthly = 1 WHERE ID > '0' AND updated_monthly = '0'");

    // ROUND OFF
    mysqli_query($db, "UPDATE tbl_leave_balances SET SL = ROUND(SL,4) WHERE ID > '0' ");
    mysqli_query($db, "UPDATE tbl_leave_balances SET VL = ROUND(VL,2) WHERE ID > '0'");
}

if ($daynow == '2') {
    mysqli_query($db, "UPDATE tbl_leave_balances SET updated_monthly = 0 WHERE ID > '0' AND updated_monthly = '1'");
}
?>


<div id="page-content">
    <div class="content-header">
        <div class="header-section">
            <h1>
                <i class="gi gi-wallet"></i><strong>Leave Balances</strong>
            </h1>
        </div>
    </div>
    <!-- Datatables Content -->
    <div class="block full">
        <div class="container-fluid" style="display:flex; justify-content:center;">
            <table style="border: none;">
                <thead>
                    <tr class="text-center">
                        <th style="padding: 10px; font-size:30px; padding-right:100px;">SL</th>
                        <th style="padding: 10px; font-size:30px; padding-right:100px;">VL</th>
                        <th style="padding: 10px; font-size:30px; padding-right:100px;">Others</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <?php
                    $user_id = $_SESSION['hris_id'];
                    $get_user = mysqli_query($db, "SELECT * FROM tbl_users WHERE id = '$user_id'");
                    while ($row = mysqli_fetch_assoc($get_user)) {
                        $employee_number = $row['employee_number'];
                    }
                    $sql = mysqli_query($db, "SELECT * FROM tbl_leave_balances WHERE employee_number = '$employee_number'");
                    while ($row = mysqli_fetch_assoc($sql)) {
                    ?>
                        <tr>
                            <td style="padding: 5px; font-size:20px; padding-right:100px;"><?= $row['SL'] ?></td>
                            <td style="padding: 5px; font-size:20px; padding-right:100px;"><?= $row['VL'] ?></td>
                            <td style="padding: 5px; font-size:20px; padding-right:100px;"><?= $row['EL'] ?></td>

                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>

        </div>
        <br><br>
    </div>
    <div class="block full">

        <div class="block-title">

            <h2><strong>History of Leaves</strong></h2>
            <input type="hidden" value="<?= $monthnow ?>">
            <input type="hidden" value="<?= $newDate ?>">
        </div>

        <div class="table-responsive">
            <table id="three-column" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr class="text-center">
                        <th class="text-center">Account Name</th>
                        <th class="text-center">Employee Number</th>
                        <th class="text-center">Leave Type</th>
                        <th class="text-center">Date Filed</th>
                        <th class="text-center">Date Requested</th>
                        <th class="text-center">Total Day(s)</th>
                        <th class="text-center">Duration</th>
                        <th class="text-center">Reason</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <?php
                    $user_id = $_SESSION['hris_id'];
                    $get_user = mysqli_query($db, "SELECT * FROM tbl_users WHERE id = '$user_id'");
                    while ($row = mysqli_fetch_assoc($get_user)) {
                        $employee_number = $row['employee_number'];
                    }
                    $sql = mysqli_query($db, "SELECT * FROM tbl_leave_requests WHERE delegated_emp_number = '$employee_number'");
                    while ($row = mysqli_fetch_assoc($sql)) {
                    ?>
                        <tr>
                            <td><?= $row['emp_name'] ?></td>
                            <td><?= $row['delegated_emp_number'] ?></td>
                            <td><?= $row['leave_type'] ?></td>
                            <td><?= $row['date_filed'] ?></td>
                            <td><?= $row['startDate'] . ' >> ' . $row['endDate'] ?></td>
                            <td><?= $row['total_day'] ?></td>
                            <td><?= $row['duration'] ?></td>
                            <td><?= $row['reason'] ?></td>
                            <td><?= $row['status'] ?></td>


                        </tr>
                    <?php
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
<?php include 'inc/template_end.php'; ?>
<script src="js/pages/paginationTable.js"></script>

<script>
    $(function() {
        TablesDatatables.init();
    });
</script>