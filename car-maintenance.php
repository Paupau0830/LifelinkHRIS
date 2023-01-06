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
                <i class="fa fa-car"></i>Car Maintenance
            </h1>
        </div>
    </div>
    <!-- Datatables Content -->
    <div class="block full">
        <div class="block-title">
            <div class="block-options pull-right">
                <a href="car-registration" class="btn btn-alt btn-sm btn-default">Car Registration</a>
            </div>
            <h2><strong>Car</strong> Maintenance</h2>
        </div>
        <div class="table-responsive">
            <table id="car-maintenance-list" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Employee Number</th>
                        <th>Employee Name</th>
                        <th>Model</th>
                        <th>Plate Number</th>
                        <th>Description</th>
                        <th>Date Acquired</th>
                        <th>Age</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = mysqli_query($db, "SELECT * FROM tbl_car_registration WHERE company_id = '$company_id'");
                    while ($row = mysqli_fetch_assoc($sql)) {
                        $age = get_car_age($row['date_acquired']);
                        $account_name = get_personal_information($row['employee_number']);
                    ?>
                        <tr>
                            <td class="text-center"><?= $row['ID'] ?></td>
                            <td><?= $row['employee_number'] ?></td>
                            <td><?= $account_name['account_name'] ?></td>
                            <td><?= $row['model'] ?></td>
                            <td><?= $row['plate_number'] ?></td>
                            <td><?= $row['description'] ?></td>
                            <td><?= $row['date_acquired'] ?></td>
                            <td><?= $age ?></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="car-registration-details?<?= md5('id') . '=' . md5($row['ID']) ?>" data-toggle="tooltip" title="Edit" class="btn btn-xs btn-default"><i class="fa fa-pencil"></i></a>
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