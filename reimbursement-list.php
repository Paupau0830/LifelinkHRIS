<?php include 'inc/config.php'; ?>
<?php include 'inc/template_start.php'; ?>
<?php include 'inc/page_head.php'; ?>
<?php
if (empty($_SESSION['hris_id'])) {
    header('Location: login');
}
$processor_id = '';
if ($_SESSION['hris_role'] == 'Processor') {
    $pid = $_SESSION['hris_id'];
    $get_processor_id = mysqli_query($db, "SELECT * FROM tbl_benefits_approvers WHERE `user_id` = '$pid'");
    $processor_id = mysqli_fetch_assoc($get_processor_id);
    $processor_id = $processor_id['role'];
    $processor_id = get_benefits_role_name_by_ID($processor_id)['position'];
}
?>
<?php $company_id = $_SESSION['hris_company_id']; ?>
<div id="page-content">
    <div class="content-header">
        <div class="header-section">
            <h1>
                <i class="fa fa-exchange"></i>Benefits Reimbursement List
            </h1>
        </div>
    </div>
    <!-- Datatables Content -->
    <div class="block full">
        <div class="block-title">
            <div class="block-options pull-right">
                <a href="reimbursement-application" class="btn btn-alt btn-sm btn-default">Benefits Reimbursement Application</a>
            </div>
            <h2><strong>Benefits Reimbursement</strong> List</h2>
        </div>
        <div class="table-responsive">
            <table id="reimbursement-list" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Payee Employee Number</th>
                        <th>Employee Name</th>
                        <th>Amount</th>
                        <th>Categories Applied</th>
                        <th>Status</th>
                        <th>Date Created</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = mysqli_query($db, "SELECT * FROM tbl_benefits_reimbursement WHERE company_id = '$company_id'");
                    if ($_SESSION['hris_role'] == "Admin") {
                        $sql = mysqli_query($db, "SELECT * FROM tbl_benefits_reimbursement");
                    }
                    if ($_SESSION['hris_role'] == "User") {
                        $empnum = $_SESSION['hris_employee_number'];
                        $sql = mysqli_query($db, "SELECT * FROM tbl_benefits_reimbursement WHERE payee = '$empnum'");
                    }
                    if ($_SESSION['hris_role'] == "Processor") {
                        $sql = mysqli_query($db, "SELECT * FROM tbl_benefits_reimbursement WHERE `status` = '$processor_id'");
                    }
                    while ($row = mysqli_fetch_assoc($sql)) {
                        $account_name = get_personal_information($row['payee']);
                        $stat = '';
                        if ($row['status'] == 'Approved') {
                            $stat = 'Approved';
                        } elseif ($row['status'] == 'Declined') {
                            $stat = 'Declined';
                        } elseif ($row['status'] == 'Cancelled') {
                            $stat = 'Cancelled';
                        } elseif ($row['status'] == 'Update Requested') {
                            $stat = 'Update Requested';
                        } else {
                            $stat = 'Pending - ' . get_benefits_role_name($row['status'])['role'];
                        }
                    ?>
                        <tr>
                            <td class="text-center">BR-<?= format_transaction_id($row['ID']) ?></td>
                            <td><?= $row['payee'] ?></td>
                            <td><?= $account_name['account_name'] ?></td>
                            <td><?= number_format($row['amount'], 2) ?></td>
                            <td><?= rtrim($row['categories_applied'], ", ") ?></td>
                            <td><?= $stat ?></td>
                            <td><?= $row['date_created'] ?></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="reimbursement-details?<?= md5('id') . '=' . md5($row['ID']) ?>" data-toggle="tooltip" title="Edit" class="btn btn-xs btn-default"><i class="fa fa-pencil"></i></a>
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