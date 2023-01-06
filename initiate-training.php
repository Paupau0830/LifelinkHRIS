<?php include 'inc/config.php'; ?>
<?php include 'inc/template_start.php'; ?>
<?php include 'inc/page_head.php'; ?>
<?php
if (empty($_SESSION['hris_id'])) {
    header('Location: login');
}
?>
<div id="page-content">
    <div class="content-header">
        <div class="header-section">
            <h1>
                <i class="fa fa-heart"></i>Initiate Training
            </h1>
        </div>
    </div>
    <div class="block full">
        <div class="block-title">
            <div class="block-options pull-right">
                <a href="training" class="btn btn-alt btn-sm btn-default">Training List</a>
            </div>
            <h2><strong>Training</strong> Form</h2>
        </div>
        <div class="container-fluid">
            <p class="text-danger" id="res"></p>
            <?= $res ?>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="company_id" value="<?= $_SESSION['hris_company_id'] ?>">
                <input type="hidden" name="admin_email" value="<?= $_SESSION['hris_email'] ?>">
                <div class="form-group">
                    <label>Assigned Employee *</label>
                    <select name="assigned_employee" required class="select-chosen" data-placeholder="Choose an employee..." style="width: 250px;">
                        <option></option>
                        <?php
                        $company_id = $_SESSION['hris_company_id'];
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
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Subject</label>
                            <input type="text" name="subject" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Target Date</label>
                            <input type="date" name="target_date" class="form-control" required min="<?= date('Y-m-d') ?>">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Description/Details</label>
                    <textarea name="description" rows="3" class="form-control" required></textarea>
                </div>
                <button class="btn btn-primary" name="btn_training">Submit</button>
            </form>
        </div>
    </div>
</div>

<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>
<?php include 'inc/template_end.php'; ?>