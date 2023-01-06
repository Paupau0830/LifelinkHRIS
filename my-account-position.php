<?php include 'inc/config.php'; ?>
<?php include 'inc/template_start.php'; ?>
<?php include 'inc/page_head.php'; ?>
<?php
if (empty($_SESSION['hris_id'])) {
    header('Location: login');
}
?>
<?php
$employee_number = $_SESSION['hris_employee_number'];
?>
<!-- Page content -->
<div id="page-content">
    <!-- eCommerce Dashboard Header -->
    <div class="content-header">
        <ul class="nav-horizontal text-center">
            <li>
                <a href="my-account"><i class="fa fa-info"></i> Information</a>
            </li>
            <li>
                <a href="my-account-education"><i class="fa fa-graduation-cap"></i> Education</a>
            </li>
            <li>
                <a href="my-account-contacts"><i class="fa fa-phone"></i> Emergency Contacts</a>
            </li>
            <li>
                <a href="my-account-ids"><i class="fa fa-id-card"></i> IDs</a>
            </li>
            <li>
                <a href="my-account-employment"><i class="fa fa-briefcase"></i> Employment Information</a>
            </li>
            <li>
                <a href="my-account-documents"><i class="fa fa-file"></i> Documents</a>
            </li>
            <li>
                <a href="my-account-benefits"><i class="fa fa-exchange"></i> Benefits</a>
            </li>
            <li>
                <a href="my-account-balances"><i class="gi gi-wallet"></i> Balances</a>
            </li>
            <li class="active">
                <a href="my-account-position"><i class="fa fa-database"></i> Position History</a>
            </li>
        </ul>
    </div>
    <div class="block full">
        <div class="block-title">
            <h2><strong>Position</strong> History</h2>
        </div>
        <?= $res ?>
        <div class="table-responsive">
            <table id="position-history" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th class="text-center">Previous Position</th>
                        <th class="text-center">New Position</th>
                        <th class="text-center">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = mysqli_query($db, "SELECT t.*, t1.job_grade as prev_pos, t2.job_grade as new_pos 
                    FROM `tbl_position_history` t
                    INNER JOIN tbl_job_grade t1
                    ON t.prev_position = t1.ID
                    INNER JOIN tbl_job_grade t2
                    ON t.new_position = t2.ID 
                    WHERE employee_number = '$employee_number'");
                    while ($row = mysqli_fetch_assoc($sql)) {
                    ?>
                        <tr>
                            <td class="text-center"><?= $row['ID'] ?></td>
                            <td class="text-center"><?= $row['prev_pos'] ?></td>
                            <td class="text-center"><?= $row['new_pos'] ?></td>
                            <td class="text-center"><?= $row['date_promoted'] ?></td>
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
<script src="js/pages/tablesDatatables.js"></script>
<script>
    $(function() {
        TablesDatatables.init();
    });
</script>