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
$em = '';
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
            <li class="active">
                <a href="edit-employee-benefits?<?= md5('id') . '=' . $fid ?>"><i class="fa fa-exchange"></i> Benefits</a>
            </li>
            <!-- <li>
                <a href="edit-employee-balances?<?= md5('id') . '=' . $fid ?>"><i class="gi gi-wallet"></i> Balances</a>
            </li> -->
            <li>
                <a href="edit-employee-position?<?= md5('id') . '=' . $fid ?>"><i class="fa fa-database"></i> Position History</a>
            </li>
        </ul>
    </div>
    <div class="block full">
        <div class="block-title">
            <h2><strong>Benefits</strong> Eligibility</h2>
        </div>
        <?= $res ?>
        <form method="post" class="form-horizontal form-bordered">
            <div class="container-fluid">
                <?php
                $ben_id = '';
                $par = '';
                $gas = '';
                $car = '';
                $med = '';
                $gy = '';
                $opt = '';
                $ce = '';
                $club = '';
                $mat = '';
                $oth = '';
                $get_benefits = mysqli_query($db, "SELECT * FROM tbl_benefits_eligibility WHERE employee_number = '$employee_number'");
                while ($row = mysqli_fetch_assoc($get_benefits)) {
                    $ben_id = $row['ID'];
                    if ($row['parking'] == '1') {
                        $par = 'checked';
                    }
                    if ($row['gasoline'] == '1') {
                        $gas = 'checked';
                    }
                    if ($row['car_maintenance'] == '1') {
                        $car = 'checked';
                    }
                    if ($row['medicine'] == '1') {
                        $med = 'checked';
                    }
                    if ($row['gym'] == '1') {
                        $gy = 'checked';
                    }
                    if ($row['optical_allowance'] == '1') {
                        $opt = 'checked';
                    }
                    if ($row['cep'] == '1') {
                        $ce = 'checked';
                    }
                    if ($row['club_membership'] == '1') {
                        $club = 'checked';
                    }
                    if ($row['maternity'] == '1') {
                        $mat = 'checked';
                    }
                    if ($row['others'] == '1') {
                        $oth = 'checked';
                    }
                }
                ?>
                <input type="hidden" name="employee_number" value="<?= $employee_number ?>">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="switch switch-primary">
                                <input type="checkbox" <?= $par ?> name="benefits_eligibility[]" value="Parking">
                                <span></span>
                            </label>
                            <label>Parking</label>
                        </div>
                        <div class="form-group">
                            <label class="switch switch-primary">
                                <input type="checkbox" <?= $gas ?> name="benefits_eligibility[]" value="Gasoline">
                                <span></span>
                            </label>
                            <label>Gasoline</label>
                        </div>
                        <div class="form-group">
                            <label class="switch switch-primary">
                                <input type="checkbox" <?= $car ?> name="benefits_eligibility[]" value="Car Maintenance">
                                <span></span>
                            </label>
                            <label>Car Maintenance</label>
                        </div>
                        <div class="form-group">
                            <label class="switch switch-primary">
                                <input type="checkbox" <?= $med ?> name="benefits_eligibility[]" value="Medicine">
                                <span></span>
                            </label>
                            <label>Medicine</label>
                        </div>
                        <div class="form-group">
                            <label class="switch switch-primary">
                                <input type="checkbox" <?= $gy ?> name="benefits_eligibility[]" value="Gym">
                                <span></span>
                            </label>
                            <label>Gym</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="switch switch-primary">
                                <input type="checkbox" <?= $opt ?> name="benefits_eligibility[]" value="Optical Allowance">
                                <span></span>
                            </label>
                            <label>Optical Allowance</label>
                        </div>
                        <div class="form-group">
                            <label class="switch switch-primary">
                                <input type="checkbox" <?= $ce ?> name="benefits_eligibility[]" value="CEP">
                                <span></span>
                            </label>
                            <label>CEP</label>
                        </div>
                        <div class="form-group">
                            <label class="switch switch-primary">
                                <input type="checkbox" <?= $club ?> name="benefits_eligibility[]" value="Club Membership">
                                <span></span>
                            </label>
                            <label>Club Membership</label>
                        </div>
                        <div class="form-group">
                            <label class="switch switch-primary">
                                <input type="checkbox" <?= $mat ?> name="benefits_eligibility[]" value="Maternity">
                                <span></span>
                            </label>
                            <label>Maternity</label>
                        </div>
                        <div class="form-group">
                            <label class="switch switch-primary">
                                <input type="checkbox" <?= $oth ?> name="benefits_eligibility[]" value="Others">
                                <span></span>
                            </label>
                            <label>Others</label>
                        </div>
                    </div>
                </div><br>
                <button class="btn btn-primary" name="btn_update_benefits_eligibility">Update</button>
            </div>
        </form>
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