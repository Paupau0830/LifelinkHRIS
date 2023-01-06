<?php include 'inc/config.php'; ?>
<?php include 'inc/template_start.php'; ?>
<?php include 'inc/page_head.php'; ?>
<?php
if (empty($_SESSION['hris_id'])) {
    header('Location: login');
}
?>


<!-- Page content -->
<div id="page-content">
    <!-- Datatables Header -->
    <div class="content-header">
        <div class="header-section">
            <h1>
                <i class="fa fa-building"></i>Company Name
            </h1>
        </div>
    </div>
    <!-- END Datatables Header -->

    <!-- Datatables Content -->
    <div class="block full">
        <div class="block-title">
            <h2><strong>Cut-off</strong> References</h2>
        </div>
        <div class="table-responsive">
            <table id="company-management" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">Reference Number</th>
                        <th class="text-center">Date From</th>
                        <th class="text-center">Date To</th>
                        <th class="text-center">Company</th>

                    </tr>
                </thead>
                <tbody>
                   <!--  <?php
                    $sql = mysqli_query($db, "SELECT * FROM tbl_companies");
                    while ($row = mysqli_fetch_assoc($sql)) {
                    ?>
                        <tr>
                            <td class="text-center"><?= $row['ID'] ?></td>
                            <td><?= $row['company_name'] ?></td>
                            <td><?= $row['date_created'] ?></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="edit-company?<?= md5('id') . '=' . md5($row['ID']) ?>" data-toggle="tooltip" title="Edit" class="btn btn-xs btn-default"><i class="fa fa-pencil"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php
                    }
                    ?> -->
                </tbody>
            </table>
        </div>
        <br>
        <a href="company-management"><button class="btn btn-primary" name="btn_generate_report">Create Payroll</button></a>
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