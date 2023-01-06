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
                <i class="fa fa-building"></i>Payroll Registry
            </h1>
        </div>
    </div>
    <!-- END Datatables Header -->
    
    <!-- Datatables Content -->
    <div class="block full">
        <div class="block-title">
            <h2><strong>Company</strong> List</h2>
        </div>
        <div class="table-responsive">
            <table id="two-column" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr>
                        <th>Company Name</th>
                        <th class="text-center">Employee List</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = mysqli_query($db, "SELECT * FROM tbl_companies");
                    while ($row = mysqli_fetch_assoc($sql)) {
                    ?>
                        <tr>
                            <td><?= $row['company_name'] ?></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="payroll-registry-check?<?= md5('id') . '=' . md5($row['ID']) ?>" class="btn btn-primary">View</a>
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
<script src="js/pages/paginationTable.js"></script>
<script>
    $(function() {
        TablesDatatables.init();
    });
</script>

<?php include 'inc/template_end.php'; ?>