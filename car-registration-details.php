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
$fid = $_GET[md5('id')]; // Foreign id
$rid = ""; // row id
$sql = mysqli_query($db, "SELECT * FROM tbl_car_registration");
while ($row = mysqli_fetch_assoc($sql)) {
    if ($fid == md5($row['ID'])) {
        $rid = $row['ID'];
    }
}
?>
<div id="page-content">
    <div class="content-header">
        <div class="header-section">
            <h1>
                <i class="fa fa-car"></i>Car Registration Details
            </h1>
        </div>
    </div>
    <div class="block full">
        <div class="block-title">
            <div class="block-options pull-right">
                <a href="car-maintenance" class="btn btn-alt btn-sm btn-default">Car Maintenance</a>
            </div>
            <h2><strong>Car Registration</strong> Details</h2>
        </div>
        <div class="container-fluid">
            <p class="text-danger" id="res"></p>
            <?= $res ?>
            <form method="POST" enctype="multipart/form-data">
                <?php
                $get_details = mysqli_query($db, "SELECT * FROM tbl_car_registration WHERE ID = '$rid'");
                while ($row = mysqli_fetch_assoc($get_details)) {
                ?>
                    <input type="hidden" name="id" value="<?= $row['ID'] ?>">
                    <div class="form-group">
                        <label>Employee *</label>
                        <select name="employee_number" required class="select-chosen" data-placeholder="Choose an employee..." style="width: 250px;">
                            <option></option>
                            <?php
                            $get_employees = mysqli_query($db, "SELECT t.*, t1.company 
                                FROM tbl_personal_information t
                                INNER JOIN tbl_employment_information t1
                                ON t.employee_number = t1.employee_number
                                WHERE t1.company = '$company_id'");
                            while ($r = mysqli_fetch_assoc($get_employees)) {
                                if ($r['employee_number'] == $row['employee_number']) {
                                    echo '<option selected value="' . $r['employee_number'] . '">' . $r['account_name'] . '</option>';
                                } else {
                                    echo '<option value="' . $r['employee_number'] . '">' . $r['account_name'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label>Model</label>
                            <input type="text" name="model" class="form-control" value="<?= $row['model'] ?>">
                        </div>
                        <div class="col-md-4">
                            <label>Plate Number</label>
                            <input type="text" name="plate_number" class="form-control" value="<?= $row['plate_number'] ?>">
                        </div>
                        <div class="col-md-4">
                            <label>Date Acquired</label>
                            <input type="date" name="date_acquired" class="form-control" value="<?= $row['date_acquired'] ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" rows="3" class="form-control"><?= $row['description'] ?></textarea>
                    </div>
                    <button class="btn btn-primary" name="btn_update_car_registration">Submit</button>
                <?php
                }
                ?>
            </form>
        </div>
    </div>
</div>

<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>
<?php include 'inc/template_end.php'; ?>