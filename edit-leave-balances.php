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
$sql = mysqli_query($db, "SELECT * FROM tbl_leave_balances");
while ($row = mysqli_fetch_assoc($sql)) {
    if ($fid == md5($row['employee_number'])) {
        $rid = $row['employee_number'];
    }
}
?>
<!-- Page content -->
<div id="page-content">
    <!-- eCommerce Dashboard Header -->
    <div class="content-header">
        <div class="header-section">
            <h1>
                <i class="gi gi-wallet"></i><strong>View Leave Balances</strong>
            </h1>

        </div>
    </div>
    <div class="block full">
        <div class="block-title">
            <div class="block-options pull-right">
                <a href="leave-balances" class="btn btn-alt btn-sm btn-default">Leave Balances List</a>
            </div>
            <h2><strong>Leave</strong> Balances</h2>
        </div>
        <?php
        $get_details = mysqli_query($db, "SELECT * FROM tbl_leave_balances WHERE employee_number = '$rid'");
        while ($r = mysqli_fetch_assoc($get_details)) {
        ?>
            <div class="container-fluid">
                <?= $res ?>
                <form method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Employee Number</label>
                                <input type="text" name="employee_number" class="form-control" readonly value="<?= $rid ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Employee Name</label>
                                <input type="text" name="employee_name" class="form-control" readonly value="<?= $r['emp_name'] ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>SL</label>
                                <input type="text" name="sl" class="form-control" value="<?= $r['SL'] ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>VL</label>
                                <input type="text" name="vl" class="form-control" value="<?= $r['VL'] ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Others</label>
                                <input type="text" name="el" class="form-control" value="<?= $r['EL'] ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Maternity</label>
                                <input type="text" name="maternity" class="form-control" value="<?= $r['maternity'] ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Paternity</label>
                                <input type="text" name="paternity" class="form-control" value="<?= $r['paternity'] ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Solo Parent</label>
                                <input type="text" name="solo_parent" class="form-control" value="<?= $r['solo_parent'] ?>">
                            </div>
                        </div>
                    </div>

                    <button class="btn btn-primary" name="btn_update_leave_balances">Update</button>
                </form>
            </div>
        <?php
        }
        ?>
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
                    $get_user = mysqli_query($db, "SELECT * FROM tbl_users WHERE employee_number = '$rid'");
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
</div>
<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>
<?php include 'inc/template_end.php'; ?>
<script src="js/pages/paginationTable.js"></script>

<script>
    $(function() {
        TablesDatatables.init();
    });
</script>