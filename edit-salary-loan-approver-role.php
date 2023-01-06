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
$sql = mysqli_query($db, "SELECT * FROM tbl_loan_approvers_role");
while ($row = mysqli_fetch_assoc($sql)) {
    if ($fid == md5($row['ID'])) {
        $rid = $row['ID'];
    }
}
?>
<!-- Page content -->
<div id="page-content">
    <!-- eCommerce Dashboard Header -->
    <div class="content-header">
        <div class="header-section">
            <h1>
                <i class="fa fa-thumbs-up"></i>Edit Salary Loan Approver Role
            </h1>
        </div>
    </div>
    <div class="block full">
        <div class="block-title">
            <div class="block-options pull-right">
                <a href="loan-approver-roles" class="btn btn-alt btn-sm btn-default">Salary Loan Approver roles</a>
            </div>
            <h2><strong>Edit Salary Loan Approver</strong> roles</h2>
        </div>
        <?php
        $get_details = mysqli_query($db, "SELECT * FROM tbl_loan_approvers_role WHERE ID = '$rid'");
        while ($row = mysqli_fetch_assoc($get_details)) {
            $company_id = $row['company_id'];
        ?>
            <div class="container-fluid">
                <?= $res ?>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $row['ID'] ?>">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Role *</label>
                                <input type="text" name="role" required class="form-control" value="<?= $row['role'] ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Hierarchical number *</label>
                                <input type="number" name="position" required class="form-control" value="<?= $row['position'] ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>CC</label>
                                <input type="text" name="cc" class="input-tags" value="<?= $row['cc'] ?>">
                            </div>
                        </div>
                    </div>
                    <button name="update_salary_loan_approver_role" class="btn btn-primary">Update</button>
                </form>
            </div>
        <?php
        }
        ?>
    </div>
</div>
<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>
<?php include 'inc/template_end.php'; ?>