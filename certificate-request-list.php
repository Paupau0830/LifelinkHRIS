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
                <i class="fa fa-file"></i>Certificate Request List
            </h1>
            
        </div>
    </div>
    <!-- Datatables Content -->
    <div class="block full">
        <div class="block-title">
            
            <h2><strong>Certificate Request</strong> List</h2>
        </div>
        <div class="table-responsive">
            <table id="certificate-request-list" class="table table-center table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Employee Name</th>
                        <th>Employee Number</th>
                        <th>Type</th>
                        <th>Date Required</th>
                        <th>Status</th>
                        <th>Date Created</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $company_id = $_SESSION['hris_company_id'];
                    $sql = mysqli_query($db, "SELECT * FROM tbl_certificate_requests");
                    if ($_SESSION['hris_role'] == "Admin") {
                        $sql = mysqli_query($db, "SELECT * FROM tbl_certificate_requests");
                    }else if ($_SESSION['hris_role'] == "User") {
                        $empnum = $_SESSION['hris_employee_number'];
                        $sql = mysqli_query($db, "SELECT * FROM tbl_certificate_requests WHERE employee_number = '$empnum'");
                    }
                    while ($row = mysqli_fetch_assoc($sql)) {
                        // $pers_info = get_personal_information($row['employee_number']);
                    ?>
                        <tr>
                            <td class="text-center"><?= format_transaction_id($row['ID']) ?></td>
                            <td><?= $row['employee_name'] ?></td>
                            <td><?= $row['employee_number'] ?></td>
                            <td><?= $row['certificate_type'] ?></td>
                            <td><?= $row['date_required'] ?></td>
                            <td><?= $row['status'] ?></td>
                            <td><?= $row['date_created'] ?></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="certificate-request-details?<?= md5('id') . '=' . md5($row['ID']) ?>" data-toggle="tooltip" title="View" class="btn btn-s btn-primary"><i class="fa fa-eye">&nbsp; View</i></a>
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