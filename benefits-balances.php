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
                <i class="gi gi-wallet"></i>Benefits Balances
            </h1>
        </div>
    </div>
    <!-- Datatables Content -->
    <div class="block full">
        <div class="block-title">
            <div class="block-options pull-right">
                <a href="reimbursement-list" class="btn btn-alt btn-sm btn-default">Benefits Reimbursement List</a>
            </div>
            <h2><strong>Benefits</strong> Balances</h2>
        </div>
        <div class="table-responsive">
            <table id="benefits-balances-list" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Employee Number</th>
                        <th>Employee Name</th>
                        <th>Car Maintenance</th>
                        <th>CEP</th>
                        <th>Gasoline</th>
                        <th>Gym</th>
                        <th>Medical</th>
                        <th>Optical</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = mysqli_query($db, "SELECT t.*, t1.company FROM tbl_personal_information t 
                    INNER JOIN tbl_employment_information t1
                    ON t.employee_number = t1.employee_number
                    WHERE t1.company = '$company_id'");
                    while ($row = mysqli_fetch_assoc($sql)) {
                        $balances = json_decode(get_benefits_balances($row['employee_number']), true);
                        $car_maintenance = $balances[0]['car_maintenance'];
                        $cep = $balances[1]['cep'];
                        $gas = $balances[2]['gas'];
                        $gym = $balances[3]['gym'];
                        $medical = $balances[4]['medical'];
                        $optical = $balances[5]['optical'];
                    ?>
                        <tr>
                            <td class="text-center"><?= $row['ID'] ?></td>
                            <td><?= $row['employee_number'] ?></td>
                            <td><?= $row['account_name'] ?></td>
                            <td><?= $car_maintenance ?></td>
                            <td><?= $cep ?></td>
                            <td><?= $gas ?></td>
                            <td><?= $gym ?></td>
                            <td><?= $medical ?></td>
                            <td><?= $optical ?></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="edit-benefits-balance?<?= md5('employee_number') . '=' . md5($row['employee_number']) ?>" data-toggle="tooltip" title="Edit" class="btn btn-xs btn-default"><i class="fa fa-pencil"></i></a>
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