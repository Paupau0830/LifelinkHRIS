<?php include 'inc/config.php'; ?>
<?php include 'inc/template_start.php'; ?>
<?php include 'inc/page_head.php'; ?>
<?php
if (empty($_SESSION['hris_id'])) {
    header('Location: login');
}
?>
<?php
$fid = $_GET[md5('employee_number')]; // Foreign id
$rid = ""; // row id
$sql = mysqli_query($db, "SELECT * FROM tbl_personal_information");
while ($row = mysqli_fetch_assoc($sql)) {
    if ($fid == md5($row['employee_number'])) {
        $rid = $row['employee_number'];
    }
}
$balances = json_decode(get_benefits_balances($rid), true);
$car_maintenance = $balances[0]['car_maintenance'];
$cep = $balances[1]['cep'];
$gas = $balances[2]['gas'];
$gym = $balances[3]['gym'];
$medical = $balances[4]['medical'];
$optical = $balances[5]['optical'];
?>
<!-- Page content -->
<div id="page-content">
    <!-- eCommerce Dashboard Header -->
    <div class="content-header">
        <div class="header-section">
            <h1>
                <i class="gi gi-wallet"></i>Benefits Balances
            </h1>
        </div>
    </div>
    <div class="block full">
        <div class="block-title">
            <div class="block-options pull-right">
                <a href="benefits-balances" class="btn btn-alt btn-sm btn-default">Benefits Balances List</a>
            </div>
            <h2><strong>Leave</strong> Balances - <?= $rid ?></h2>
        </div>
        <div class="container-fluid">
            <?= $res ?>
            <form method="POST">
                <input type="hidden" name="employee_number" value="<?= $rid ?>">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Car Maintenance</label>
                            <input type="number" step=".01" name="car_maintenance" class="form-control" value="<?= $car_maintenance ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>CEP</label>
                            <input type="number" step=".01" name="cep" class="form-control" value="<?= $cep ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Gas</label>
                            <input type="number" step=".01" name="gas" class="form-control" value="<?= $gas ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Gym</label>
                            <input type="number" step=".01" name="gym" class="form-control" value="<?= $gym ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Medical</label>
                            <input type="number" step=".01" name="medical" class="form-control" value="<?= $medical ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Optical</label>
                            <input type="number" step=".01" name="optical" class="form-control" value="<?= $optical ?>">
                        </div>
                    </div>
                </div>
                <button class="btn btn-primary" name="btn_update_benefits_balances">Update</button>
            </form>
        </div>
    </div>
</div>
<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>
<?php include 'inc/template_end.php'; ?>