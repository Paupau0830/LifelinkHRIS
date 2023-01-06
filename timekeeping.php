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
                <i class="fa fa-clock-o"></i>Timekeeping Management
            </h1>
        </div>
    </div>
    <!-- Datatables Content -->
    <div class="block full">
        <div class="block-title">
            <div class="block-options pull-right">
                <button class="btn btn-alt btn-sm btn-default" onclick="$('#modal_upload_biometrics').modal('show')">Upload Biometrics Data</button>
                <a href="shift-management" class="btn btn-alt btn-sm btn-default">Shift Management</a>
            </div>
            <h2><strong>Employee</strong> List</h2>
        </div>
        <?= $res; ?>
        <div class="table-responsive">
            <table id="employee-list" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Employee Number</th>
                        <th>Employee Name</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $company_id = $_SESSION['hris_company_id'];
                    $sql = mysqli_query($db, "SELECT * FROM tbl_personal_information
                    WHERE employee_number IN (SELECT employee_number FROM tbl_employment_information WHERE company = '$company_id')");
                    if ($_SESSION['hris_role'] == "Admin") {
                        $sql = mysqli_query($db, "SELECT * FROM tbl_personal_information
                        WHERE employee_number IN (SELECT employee_number FROM tbl_employment_information)");
                    }
                    while ($row = mysqli_fetch_assoc($sql)) {
                    ?>
                        <tr>
                            <td class="text-center"><?= $row['ID'] ?></td>
                            <td><?= $row['employee_number'] ?></td>
                            <td><?= $row['account_name'] ?></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="timekeeping-details?<?= md5('employee_number') . '=' . md5($row['employee_number']) ?>" data-toggle="tooltip" title="Edit" class="btn btn-xs btn-default"><i class="fa fa-pencil"></i></a>
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
<div id="modal_upload_biometrics" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h2 class="modal-title"> Upload Biometrics Data</h2>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Upload CSV file here</label>
                            <input type="file" name="file" class="form-control" required>
                        </div>
                        <button class="btn btn-primary btn-block" name="btn_upload_biometrics_data">Upload</button>
                        <br>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
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