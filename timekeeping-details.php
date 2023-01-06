<?php include 'inc/config.php'; ?>
<?php include 'inc/template_start.php'; ?>
<?php include 'inc/page_head.php'; ?>
<?php
if (empty($_SESSION['hris_id'])) {
    header('Location: login');
}
$fid = $_GET[md5('employee_number')]; // Foreign id
$rid = ""; // row id
$sql = mysqli_query($db, "SELECT * FROM tbl_timekeeping");
while ($row = mysqli_fetch_assoc($sql)) {
    if ($fid == md5($row['employee_number'])) {
        $rid = $row['employee_number'];
    }
}
?>
<?php $company_id = $_SESSION['hris_company_id']; ?>
<div id="page-content">
    <div class="content-header">
        <div class="header-section">
            <h1>
                <i class="fa fa-clock-o"></i>Timekeeping Details
            </h1>
        </div>
    </div>
    <!-- Datatables Content -->
    <div class="block full">
        <div class="block-title">
            <div class="block-options pull-right">
                <a href="timekeeping" class="btn btn-alt btn-sm btn-default">Timekeeping Management</a>
            </div>
            <h2><strong>Attendance</strong> List</h2>
        </div>
        <div class="table-responsive">
            <table id="attendance-list" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Company</th>
                        <th>Employee Name</th>
                        <th>ARSID</th>
                        <th>From Date</th>
                        <th>Day</th>
                        <th>Time In</th>
                        <th>Time Out</th>
                        <th>Shift Code</th>
                        <th>Time Rendered</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = mysqli_query($db, "SELECT * FROM tbl_timekeeping WHERE employee_number = '$rid'");
                    while ($row = mysqli_fetch_assoc($sql)) {
                        $account_name = get_personal_information($row['employee_number']);
                        $company_name = get_company($row['company_id']);

                        $login = $row['timein'];
                        $logout = $row['timeout'];
                        $time_rendered = number_format(get_num_hours($login, $logout), 2);
                    ?>
                        <tr>
                            <td class="text-center">ID</td>
                            <td><?= $company_name ?></td>
                            <td><?= $account_name['account_name'] ?></td>
                            <td><?= $row['arsid'] ?></td>
                            <td><?= $row['from_date'] ?></td>
                            <td><?= $row['day'] ?></td>
                            <td><?= $row['timein'] ?></td>
                            <td><?= $row['timeout'] ?></td>
                            <td>Shift Code</td>
                            <td><?= format_num_hours($time_rendered) ?></td>
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
<script src="js/pages/tablesDatatables.js"></script>
<script>
    $(function() {
        TablesDatatables.init();
    });
</script>

<?php include 'inc/template_end.php'; ?>