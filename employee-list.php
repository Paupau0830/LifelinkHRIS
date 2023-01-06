<?php include 'inc/config.php'; ?>
<?php include 'inc/template_start.php'; ?>
<?php include 'inc/page_head.php'; ?>
<?php
if (empty($_SESSION['hris_id'])) {
    header('Location: login');
}
?>

<div id="page-content">
    <div class="content-header">
        <div class="header-section">
            <h1>
                <i class="fa fa-users"></i>Employee List
            </h1>
        </div>
    </div>
    <!-- Datatables Content -->
    <div class="block full">
        <div class="block-title">
            <div class="block-options pull-right">
                <a href="onboarding" class="btn btn-alt btn-sm btn-default">Onboarding</a>
            </div>
            <h2><strong>Employee</strong> List</h2>
        </div>
        <div class="table-responsive">
            <table id="employee-list" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th class="text-center">Employee ID</th>
                        <th>Employee Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $company_id = $_SESSION['hris_company_id'];
                    $sql = mysqli_query($db, "SELECT * FROM tbl_personal_information
                    WHERE super_admin = '0' AND employee_number IN (SELECT employee_number FROM tbl_employment_information WHERE company = '$company_id')");
                    if ($_SESSION['hris_role'] == "Admin") {
                        $sql = mysqli_query($db, "SELECT * FROM tbl_personal_information
                        WHERE super_admin = '0' AND employee_number IN (SELECT employee_number FROM tbl_employment_information)");
                    }
                    while ($row = mysqli_fetch_assoc($sql)) {
                    ?>
                        <tr>
                            <td class="text-center"><?= $row['ID'] ?></td>
                            <td class="text-center"><?= $row['employee_number'] ?></td>
                            <td><?= $row['account_name'] ?></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="edit-employee?<?= md5('id') . '=' . md5($row['ID']) ?>" data-toggle="tooltip" title="Edit" class="btn btn-xs btn-default"><i class="fa fa-pencil"></i></a>
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
<!--     END Datatables Content -->
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