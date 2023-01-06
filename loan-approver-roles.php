<?php include 'inc/config.php'; ?>
<?php include 'inc/template_start.php'; ?>
<?php include 'inc/page_head.php'; ?>
<?php
if (empty($_SESSION['hris_id'])) {
    header('Location: login');
}
?>
<?php
$company_id = $_SESSION['hris_company_id'];
?>
<div id="page-content">
    <div class="content-header">
        <div class="header-section">
            <h1>
                <i class="fa fa-money"></i>Salary Loan Approver Roles
            </h1>
        </div>
    </div>
    <div class="block">
        <div class="block-title">
            <div class="block-options pull-right">
                <a href="loan-approvers" class="btn btn-alt btn-sm btn-default">Salary Loan Approvers</a>
            </div>
            <h2><strong>Add Salary Loan Approver</strong> Role</h2>
            <?= $res ?>
        </div>
        <div class="container-fluid">
            <form method="POST">
                <input type="hidden" name="company_id" value="<?= $company_id ?>">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Role *</label>
                            <input type="text" name="role" required class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Hierarchical number *</label>
                            <input type="number" name="hierarchical_number" required class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group ccs">
                            <label>CC (seperate with commas)</label>
                            <input type="text" name="cc" class="input-tags">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button name="add_salary_loan_approver_role" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
    <div class="block full">
        <div class="block-title">
            <h2><strong>Approver Roles</strong> List</h2>
        </div>
        <div class="table-responsive">
            <table id="salary-loan-approver-roles" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Role</th>
                        <th>Hierarchical Number</th>
                        <th>CC</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = mysqli_query($db, "SELECT * FROM tbl_loan_approvers_role WHERE company_id = '$company_id'");
                    while ($row = mysqli_fetch_assoc($sql)) {
                    ?>
                        <tr>
                            <td class="text-center"><?= $row['ID'] ?></td>
                            <td><?= $row['role'] ?></td>
                            <td><?= $row['position'] ?></td>
                            <td><?= $row['cc'] ?></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="edit-salary-loan-approver-role?<?= md5('id') . '=' . md5($row['ID']) ?>" data-toggle="tooltip" title="Edit" class="btn btn-xs btn-default"><i class="fa fa-pencil"></i></a>
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