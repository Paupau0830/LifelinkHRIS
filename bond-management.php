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
                <i class="fa fa-cubes"></i>Bond Maintenance
            </h1>
        </div>
    </div>
    <!-- Datatables Content -->
    <div class="block full">
        <div class="block-title">
            <div class="block-options pull-right">
                <a href="reimbursement-list" class="btn btn-alt btn-sm btn-default">Benefits Reimbursement List</a>
            </div>
            <h2><strong>Bond</strong> Maintenance</h2>
        </div>
        <div class="table-responsive">
            <table id="bond-list" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Employee Number</th>
                        <th>Benefits ID</th>
                        <th>Type</th>
                        <th>Premise</th>
                        <th>Bond</th>
                        <th>Remaining</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = mysqli_query($db, "SELECT * FROM tbl_cep_bond");
                    while ($row = mysqli_fetch_assoc($sql)) {
                        $benefits_info = get_benefits_info($row['benefits_id']);
                        $personal_info = get_personal_information($benefits_info['payee']);
                        if ($benefits_info['payee'] == $_SESSION['hris_employee_number']) {
                    ?>
                            <tr>
                                <td class="text-center"><?= $row['ID'] ?></td>
                                <td><?= $benefits_info['payee'] ?></td>
                                <td><?= $row['benefits_id'] ?></td>
                                <td><?= $row['type'] ?></td>
                                <td><?= $row['premise'] ?></td>
                                <td><?= $row['bond'] ?></td>
                                <td><?= $row['remaining'] ?></td>
                            </tr>
                    <?php
                        }
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