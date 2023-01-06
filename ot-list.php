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
                <i class="fa fa-users"></i>Overtime List
            </h1>
        </div>
    </div>
    <!-- Datatables Content -->
    <div class="block full">
        <div class="block-title">
            <div class="block-options pull-right">
                <a href="ot-application" class="btn btn-alt btn-sm btn-default">Overtime Application</a>
            </div>
            <h2><strong>Overtime</strong> List</h2>
        </div>
        <div class="table-responsive">
            <table id="ot-list" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Employee Number</th>
                        <th>Employee Name</th>
                        <th>Month</th>
                        <th>Total Hours</th>
                        <th>Attachment</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = mysqli_query($db, "SELECT * FROM tbl_ot_application WHERE company_id = '$company_id'");
                    if ($_SESSION['hris_role'] == "Admin") {
                        $sql = mysqli_query($db, "SELECT * FROM tbl_ot_application");
                    }
                    if ($_SESSION['hris_role'] == "User") {
                        $empnum = $_SESSION['hris_employee_number'];
                        $sql = mysqli_query($db, "SELECT * FROM tbl_ot_application WHERE employee_number = '$empnum'");
                    }
                    while ($row = mysqli_fetch_assoc($sql)) {
                        $account_name = get_personal_information($row['employee_number']);
                    ?>
                        <tr>
                            <td class="text-center">OT-<?= format_transaction_id($row['ID']) ?></td>
                            <td><?= $row['employee_number'] ?></td>
                            <td><?= $account_name['account_name'] ?></td>
                            <td><?= $row['month_of_ot'] ?></td>
                            <td><?= $row['total_hours'] ?></td>
                            <td class="text-center"><a href="uploads/<?= $row['attachment'] ?>" target="_blank">View</a></td>
                            <td><?= $row['status'] ?></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="ot-details?<?= md5('id') . '=' . md5($row['ID']) ?>" data-toggle="tooltip" title="Edit" class="btn btn-xs btn-default"><i class="fa fa-pencil"></i></a>
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