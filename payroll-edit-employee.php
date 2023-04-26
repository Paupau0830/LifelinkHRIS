<?php include 'inc/config.php'; ?>
<?php include 'inc/template_start.php'; ?>
<?php include 'inc/page_head.php'; ?>
<?php
if (empty($_SESSION['hris_id'])) {
    header('Location: login');
}
?>
<?php
$employee_number = $_SESSION['selected_editemp'];
$employment_status = '';
$sql = mysqli_query($db, "SELECT * FROM tbl_employment_information WHERE employee_number = '$employee_number'");
while ($row = mysqli_fetch_assoc($sql)) {
    $employment_status = $row['employment_status'];
}
?>
<div id="page-content">
    <div class="content-header">
        <div class="header-section">
            <h1>
                <i class="fa fa-file"></i>Payroll Employee Registry
            </h1>
        </div>
    </div>
    <div class="block full">
        <div class="block-title">
            <h2><strong>Edit</strong> Employee</h2>
        </div>
        <div class="container-fluid">
            <?= $res ?>
            <form method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Employee ID*</label>
                            <input type="text" readonly name="emp_num" required class="form-control"
                                value="<?= $_SESSION['selected_editemp'] ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>Employee Name*</label>
                        <input type="text" readonly name="emp_name" required class="form-control"
                            value="<?= $_SESSION['selected_edit_empname'] ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Company*</label>
                            <select readonly name="select_company" required class="form-control"
                                value="<?= $_SESSION['selected_edit_company'] ?>">
                                <?php
                                $sql = mysqli_query($db, "SELECT * FROM tbl_companies");
                                while ($row = mysqli_fetch_assoc($sql)) {
                                ?>
                                <option value="<?= $row['company_name'] ?>"><?= $row['company_name'] ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Job Title*</label>
                            <input type="text" readonly name="job_title" required class="form-control"
                                value="<?= $_SESSION['selected_edit_jobtitle'] ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Employment Status*</label>
                            <input type="text" readonly name="employment_status" required class="form-control"
                                value="<?= $employment_status ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Basic Salary*</label>
                            <input type="number" name="basic_salary" required class="form-control"
                                value="<?= $_SESSION['selected_edit_basicsalary'] ?>" step="any">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Account number*</label>
                            <input type="number" name="acc_num" required class="form-control"
                                value="<?= $_SESSION['selected_edit_accno'] ?>" step="any">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Bank Name*</label>
                            <input type="text" name="bank_name" required class="form-control"
                                value="<?= $_SESSION['selected_edit_bankname'] ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>SSS ER*</label>
                            <input type="number" name="sss_er" readonly class="form-control"
                                value="<?= $_SESSION['selected_edit_sss_er'] ?>" step="any">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>SSS EE*</label>
                            <input type="number" name="sss_ee" readonly class="form-control"
                                value="<?= $_SESSION['selected_edit_sss_ee'] ?>" step="any">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>SSS EC*</label>
                            <input type="number" name="sss_ec" readonly class="form-control"
                                value="<?= $_SESSION['selected_edit_sss_ec'] ?>" step="any">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Commission*</label>
                            <input type="number" name="commission" required class="form-control"
                                value="<?= $_SESSION['selected_edit_commission'] ?>" step="any">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>HDMF ER*</label>
                            <input type="number" name="hdmf_er" readonly class="form-control"
                                value="<?= $_SESSION['selected_edit_hdmf_er'] ?>" step="any">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>HDMF EE*</label>
                            <input type="number" name="hdmf_ee" readonly class="form-control"
                                value="<?= $_SESSION['selected_edit_hdmf_ee'] ?>" step="any">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Philhealth ER*</label>
                            <input type="number" name="philhealth_er" readonly class="form-control"
                                value="<?= $_SESSION['selected_edit_philhealth_er'] ?>" step="any">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Philhealth EE*</label>
                            <input type="number" name="philhealth_ee" readonly class="form-control"
                                value="<?= $_SESSION['selected_edit_philhealth_ee'] ?>" step="any">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Deminimis*</label>
                            <!-- from selected_edit_pagibig ->  selected_edit_deminimis value -->
                            <input type="number" name="deminimis" required class="form-control"
                                value="<?= $_SESSION['selected_edit_deminimis'] ?>" step="any">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Withholding tax*</label>
                            <input type="number" name="whtax" required class="form-control"
                                value="<?= $_SESSION['selected_edit_whtax'] ?>" step="any">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Annual Medical Allowance*</label>
                            <input type="number" name="annual_medical_allowance" required class="form-control"
                                step="any" value="<?= $_SESSION['selected_annual_medical_allowance'] ?>">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Remarks*</label>
                            <textarea name="remarks" class="form-control"
                                step="any"><?= $_SESSION['selected_edit_remarks'] ?></textarea>
                        </div>
                    </div>
                </div>
                <button class="btn btn-primary" name="update_employee">Submit</button>&nbsp;
                <a href="payroll-employee-list" class="btn btn-primary">Back</a>
            </form>
        </div>
    </div>
</div>

<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>
<?php include 'inc/template_end.php'; ?>
<script>
$('#start_date').change(function() {
    $('#end_date').val($(this).val());
    $('#end_date').attr('min', $(this).val());
});
</script>