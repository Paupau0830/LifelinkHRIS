<?php include 'inc/config.php'; ?>
<?php include 'inc/template_start.php'; ?>
<?php include 'inc/page_head.php'; ?>
<?php
if (empty($_SESSION['hris_id'])) {
    header('Location: login');
}
?>
<?php $company_id = $_SESSION['hris_company_id']; ?>
<div id="page-content">
    <div class="content-header">
        <div class="header-section">
            <h1>
                <i class="fa fa-heart"></i>Training List
            </h1>
        </div>
    </div>
    <!-- Datatables Content -->
    <div class="block full">
        <div class="block-title">
            <div class="block-options pull-right">
                <a href="initiate-training" class="btn btn-alt btn-sm btn-default">Initiate Training</a>
            </div>
            <h2><strong>Training</strong> List</h2>
        </div>
        <?= $res; ?>
        <div class="table-responsive">
            <table id="training-list" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Assigned Employee</th>
                        <th>Subject</th>
                        <th>Description</th>
                        <th>Date Of Request</th>
                        <th>Target Date</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $em = $_SESSION['hris_email'];
                    $sql = mysqli_query($db, "SELECT * FROM tbl_training WHERE company_id = '$company_id'");
                    if ($_SESSION['hris_role'] == "User") {
                        $empnum = $_SESSION['hris_employee_number'];
                        $sql = mysqli_query($db, "SELECT * FROM tbl_training WHERE assigned_employee = '$empnum'");
                    }
                    if ($_SESSION['hris_role'] == "Admin") {
                        $sql = mysqli_query($db, "SELECT * FROM tbl_training");
                    }
                    while ($row = mysqli_fetch_assoc($sql)) {
                        $stat = '';
                        if ($row['status'] == '0') {
                            $stat = 'Pending - Employee';
                        } elseif ($row['status'] == '1') {
                            $stat = 'Pending - HR';
                        } else {
                            $stat = $row['status'];
                        }
                    ?>
                        <tr>
                            <td class="text-center">T-<?= format_transaction_id($row['ID']) ?></td>
                            <td><?= $row['assigned_employee'] ?></td>
                            <td><?= $row['subject'] ?></td>
                            <td><?= $row['description'] ?></td>
                            <td><?= $row['date_of_request'] ?></td>
                            <td><?= $row['target_date'] ?></td>
                            <td><?= $stat ?></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="training-details?<?= md5('id') . '=' . md5($row['ID']) ?>" data-toggle="tooltip" title="Edit" class="btn btn-xs btn-default"><i class="fa fa-pencil"></i></a>
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