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
$employee_number = '';
$sql = mysqli_query($db, "SELECT * FROM tbl_personal_information");
while ($row = mysqli_fetch_assoc($sql)) {
    if ($fid == md5($row['ID'])) {
        $rid = $row['ID'];
        $employee_number = $row['employee_number'];
    }
}
$balances = json_decode(get_benefits_balances($employee_number), true);
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
        <ul class="nav-horizontal text-center">
            <li>
                <a href="edit-employee?<?= md5('id') . '=' . $fid ?>"><i class="fa fa-info"></i> Information</a>
            </li>
            <li>
                <a href="edit-employee-education?<?= md5('id') . '=' . $fid ?>"><i class="fa fa-graduation-cap"></i> Education</a>
            </li>
            <li>
                <a href="edit-employee-contacts?<?= md5('id') . '=' . $fid ?>"><i class="fa fa-phone"></i> Emergency Contacts</a>
            </li>
            <li>
                <a href="edit-employee-ids?<?= md5('id') . '=' . $fid ?>"><i class="fa fa-id-card"></i> IDs</a>
            </li>
            <li>
                <a href="edit-employee-employment?<?= md5('id') . '=' . $fid ?>"><i class="fa fa-briefcase"></i> Employment Information</a>
            </li>
            <li>
                <a href="edit-employee-documents?<?= md5('id') . '=' . $fid ?>"><i class="fa fa-file"></i> Documents</a>
            </li>
            <li>
                <a href="edit-employee-benefits?<?= md5('id') . '=' . $fid ?>"><i class="fa fa-exchange"></i> Benefits</a>
            </li>
            <li class="active">
                <a href="edit-employee-balances?<?= md5('id') . '=' . $fid ?>"><i class="gi gi-wallet"></i> Balances</a>
            </li>
            <li>
                <a href="edit-employee-position?<?= md5('id') . '=' . $fid ?>"><i class="fa fa-database"></i> Position History</a>
            </li>
        </ul>
    </div>
    <?= $res ?>
    <div class="block full">
        <div class="block-title">
            <div class="block-options pull-right">
                <a href="leave-balances" class="btn btn-alt btn-sm btn-default">Leave Balances List</a>
            </div>
            <h2><strong>Leave</strong> Balances</h2>
        </div>
        <?php
        $get_details = mysqli_query($db, "SELECT * FROM tbl_leave_balances WHERE employee_number = '$employee_number'");
        while ($r = mysqli_fetch_assoc($get_details)) {
        ?>
            <div class="container-fluid">
                <form method="POST">
                    <input type="hidden" name="employee_number" value="<?= $employee_number ?>">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>SL</label>
                                <input type="text" name="sl" class="form-control" value="<?= $r['SL'] ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>VL</label>
                                <input type="text" name="vl" class="form-control" value="<?= $r['VL'] ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>EL</label>
                                <input type="text" name="el" class="form-control" value="<?= $r['others'] ?>">
                            </div>
                        </div>
                        <!-- <div class="col-md-2">
                            <div class="form-group">
                                <label>WFH</label>
                                <input type="text" name="wfh" class="form-control" value="<?= $r['WFH'] ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>ECU</label>
                                <input type="text" name="ecu" class="form-control" value="<?= $r['ECU'] ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>BL</label>
                                <input type="text" name="bl" class="form-control" value="<?= $r['BL'] ?>">
                            </div>
                        </div> -->
                    </div>
                    <!-- <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>PLA</label>
                                <input type="text" name="pla" class="form-control" value="<?= $r['PLA'] ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>PL</label>
                                <input type="text" name="pl" class="form-control" value="<?= $r['PL'] ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>SPL</label>
                                <input type="text" name="spl" class="form-control" value="<?= $r['SPL'] ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>SLBANK</label>
                                <input type="text" name="slbank" class="form-control" value="<?= $r['SLBANK'] ?>">
                            </div>
                        </div>
                    </div> -->
                    <button class="btn btn-primary" name="btn_update_leave_balances">Update</button>
                </form>
            </div>
        <?php
        }
        ?>
    </div>
    <div class="block full">
        <div class="block-title">
            <h2><strong>Benefits</strong> Balances</h2>
        </div>
        <div class="container-fluid">
            <form method="POST">
                <input type="hidden" name="employee_number" value="<?= $employee_number ?>">
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
                <button class="btn btn-primary" name="btn_update_benefits_balances_201">Update</button>
            </form>
        </div>
    </div>
</div>
<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>
<?php include 'inc/template_end.php'; ?>
<script src="js/pages/tablesDatatables.js"></script>
<script>
    $(function() {
        TablesDatatables.init();
    });
</script>