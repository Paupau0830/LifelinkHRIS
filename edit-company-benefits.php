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
$cname = '';
$sql = mysqli_query($db, "SELECT * FROM tbl_companies");
while ($row = mysqli_fetch_assoc($sql)) {
    if ($fid == md5($row['ID'])) {
        $rid = $row['ID'];
        $cname = $row['company_name'];
    }
}
?>
<!-- Page content -->
<div id="page-content">
    <!-- eCommerce Dashboard Header -->
    <div class="content-header">
        <ul class="nav-horizontal text-center">
            <li>
                <a href="edit-company?<?= md5('id') . '=' . $fid ?>"><i class="fa fa-info"></i> Information</a>
            </li>
            <li>
                <a href="edit-company-department?<?= md5('id') . '=' . $fid ?>"><i class="fa fa-sitemap"></i> Departments</a>
            </li>
            <li>
                <a href="edit-company-job-grade?<?= md5('id') . '=' . $fid ?>"><i class="fa fa-square"></i> Job Grade</a>
            </li>
            <li>
                <a href="edit-company-job-grade-set?<?= md5('id') . '=' . $fid ?>"><i class="gi gi-show_thumbnails"></i> Job Grade Set</a>
            </li>
            <li class="active">
                <a href="edit-company-benefits?<?= md5('id') . '=' . $fid ?>"><i class="fa fa-exchange"></i> Benefits</a>
            </li>
            <li>
                <a href="edit-company-maintenance?<?= md5('id') . '=' . $fid ?>"><i class="gi gi-settings"></i> Maintenance</a>
            </li>
        </ul>
    </div>
    <div class="block full">
        <div class="block-title">
            <h2><strong>Benefits</strong> Eligibility - <?= $cname ?></h2>
        </div>
        <div class="container-fluid">
            <?= $res ?>
            <form method="post" class="form-horizontal form-bordered">
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
                $get_benefits = mysqli_query($db, "SELECT * FROM tbl_company_benefits WHERE company_id = '$rid'");
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
                <input type="hidden" name="company_id" value="<?= $rid ?>">
                <input type="hidden" name="benefits_id" value="<?= $ben_id ?>">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="switch switch-primary">
                                <input type="checkbox" <?= $par ?> name="parking" value="Parking">
                                <span></span>
                            </label>
                            <label>Parking</label>
                        </div>
                        <div class="form-group">
                            <label class="switch switch-primary">
                                <input type="checkbox" <?= $gas ?> name="gasoline" value="Gasoline">
                                <span></span>
                            </label>
                            <label>Gasoline</label>
                        </div>
                        <div class="form-group">
                            <label class="switch switch-primary">
                                <input type="checkbox" <?= $car ?> name="car_maintenance" value="Car Maintenance">
                                <span></span>
                            </label>
                            <label>Car Maintenance</label>
                        </div>
                        <div class="form-group">
                            <label class="switch switch-primary">
                                <input type="checkbox" <?= $med ?> name="medicine" value="Medicine">
                                <span></span>
                            </label>
                            <label>Medicine</label>
                        </div>
                        <div class="form-group">
                            <label class="switch switch-primary">
                                <input type="checkbox" <?= $gy ?> name="gym" value="Gym">
                                <span></span>
                            </label>
                            <label>Gym</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="switch switch-primary">
                                <input type="checkbox" <?= $opt ?> name="optical_allowance" value="Optical Allowance">
                                <span></span>
                            </label>
                            <label>Optical Allowance</label>
                        </div>
                        <div class="form-group">
                            <label class="switch switch-primary">
                                <input type="checkbox" <?= $ce ?> name="cep" value="CEP">
                                <span></span>
                            </label>
                            <label>CEP</label>
                        </div>
                        <div class="form-group">
                            <label class="switch switch-primary">
                                <input type="checkbox" <?= $club ?> name="club_membership" value="Club Membership">
                                <span></span>
                            </label>
                            <label>Club Membership</label>
                        </div>
                        <div class="form-group">
                            <label class="switch switch-primary">
                                <input type="checkbox" <?= $mat ?> name="maternity" value="Maternity">
                                <span></span>
                            </label>
                            <label>Maternity</label>
                        </div>
                        <div class="form-group">
                            <label class="switch switch-primary">
                                <input type="checkbox" <?= $oth ?> name="others" value="Others">
                                <span></span>
                            </label>
                            <label>Others</label>
                        </div>
                    </div>
                </div>
                <br>
                <button name="btn_benefits_eligibility" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>

<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>
<?php include 'inc/template_end.php'; ?>
