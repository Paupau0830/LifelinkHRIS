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
                <i class="fa fa-car"></i>Car Registration
            </h1>
        </div>
    </div>
    <div class="block full">
        <div class="block-title">
            <div class="block-options pull-right">
                <a href="car-maintenance" class="btn btn-alt btn-sm btn-default">Car Maintenance</a>
            </div>
            <h2><strong>Car Registration</strong> Form</h2>
        </div>
        <div class="container-fluid">
            <p class="text-danger" id="res"></p>
            <?= $res ?>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="company_id" value="<?= $_SESSION['hris_company_id'] ?>">
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
                        while ($row = mysqli_fetch_assoc($get_employees)) {
                            echo '<option value="' . $row['employee_number'] . '">' . $row['account_name'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label>Model</label>
                        <input type="text" name="model" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label>Plate Number</label>
                        <input type="text" name="plate_number" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label>Date Acquired</label>
                        <input type="date" name="date_acquired" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="3" class="form-control"></textarea>
                </div>
                <button class="btn btn-primary" name="btn_car_registration">Submit</button>
            </form>
        </div>
    </div>
</div>

<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>
<?php include 'inc/template_end.php'; ?>