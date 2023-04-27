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
                            <label>Employee ID</label>&nbsp;<span style="color:red;font-weight:bold">*</span>
                            <input type="text" readonly name="emp_num" required class="form-control"
                                value="<?= $_SESSION['selected_editemp'] ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>Employee Name</label>&nbsp;<span style="color:red;font-weight:bold">*</span>
                        <input type="text" readonly name="emp_name" required class="form-control"
                            value="<?= $_SESSION['selected_edit_empname'] ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Company</label>&nbsp;<span style="color:red;font-weight:bold">*</span>
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
                            <label>Job Title</label>&nbsp;<span style="color:red;font-weight:bold">*</span>
                            <input type="text" readonly name="job_title" required class="form-control"
                                value="<?= $_SESSION['selected_edit_jobtitle'] ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Employment Status</label>&nbsp;<span style="color:red;font-weight:bold">*</span>
                            <input type="text" readonly name="employment_status" required class="form-control"
                                value="<?= $employment_status ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Basic Salary</label>&nbsp;<span style="color:red;font-weight:bold">*</span>
                            <input type="number" name="basic_salary" id="basic_salary" required class="form-control"
                                value="<?= $_SESSION['selected_edit_basicsalary'] ?>" step="any"
                                onkeyup="calculateGross()">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Account number</label>&nbsp;<span style="color:red;font-weight:bold">*</span>
                            <input type="number" name="acc_num" required class="form-control"
                                value="<?= $_SESSION['selected_edit_accno'] ?>" step="any">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Bank Name</label>&nbsp;<span style="color:red;font-weight:bold">*</span>
                            <input type="text" name="bank_name" required class="form-control"
                                value="<?= $_SESSION['selected_edit_bankname'] ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>SSS EC</label>&nbsp;<span style="color:red;font-weight:bold">*</span>
                            <input type="number" name="sss_ec" readonly class="form-control"
                                value="<?= $_SESSION['selected_edit_sss_ec'] ?>" step="any">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>SSS EE</label>&nbsp;<span style="color:red;font-weight:bold">*</span>
                            <input type="number" name="sss_ee" readonly class="form-control"
                                value="<?= $_SESSION['selected_edit_sss_ee'] ?>" step="any">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>SSS ER</label>&nbsp;<span style="color:red;font-weight:bold">*</span>
                            <input type="number" name="sss_er" readonly class="form-control"
                                value="<?= $_SESSION['selected_edit_sss_er'] ?>" step="any">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>HDMF EE</label>&nbsp;<span style="color:red;font-weight:bold">*</span>
                            <input type="number" name="hdmf_ee" readonly class="form-control"
                                value="<?= $_SESSION['selected_edit_hdmf_ee'] ?>" step="any">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>HDMF ER</label>&nbsp;<span style="color:red;font-weight:bold">*</span>
                            <input type="number" name="hdmf_er" readonly class="form-control"
                                value="<?= $_SESSION['selected_edit_hdmf_er'] ?>" step="any">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Philhealth EE</label>&nbsp;<span style="color:red;font-weight:bold">*</span>
                            <input type="number" name="philhealth_ee" readonly class="form-control"
                                value="<?= $_SESSION['selected_edit_philhealth_ee'] ?>" step="any">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Philhealth ER</label>&nbsp;<span style="color:red;font-weight:bold">*</span>
                            <input type="number" name="philhealth_er" readonly class="form-control"
                                value="<?= $_SESSION['selected_edit_philhealth_er'] ?>" step="any">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Annual Medical Allowance</label>&nbsp;<span
                                style="color:red;font-weight:bold">*</span>
                            <input type="number" name="annual_medical_allowance" required class="form-control"
                                step="any" value="<?= $_SESSION['selected_annual_medical_allowance'] ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Commission</label>&nbsp;<span style="color:red;font-weight:bold">*</span>
                            <input type="number" name="commission" required class="form-control"
                                value="<?= $_SESSION['selected_edit_commission'] ?>" step="any">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Deminimis</label>&nbsp;<span style="color:red;font-weight:bold">*</span>
                            <input type="number" name="deminimis" required class="form-control"
                                value="<?= $_SESSION['selected_edit_deminimis'] ?>" step="any">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Other Additional Allowance</label>&nbsp;<span style="font-style:italic">(please
                                specify)</span>&nbsp;<span style="color:red;font-weight:bold">*</span>
                            <input type="text" name="additional_allowance" class="form-control" step="any"
                                value="<?= $_SESSION['selected_edit_additional_allowance'] ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Amount</label>&nbsp;<span style="color:red;font-weight:bold">*</span>
                            <input type="number" name="additional_allowance_amount" required class="form-control"
                                step="any" value="<?= $_SESSION['selected_edit_additional_allowance_amount'] ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Cost of Living Allowancee</label>&nbsp;<span>(COLA)</span>&nbsp;<span
                                style="color:red;font-weight:bold">*</span>
                            <input type="number" name="cola" id="cola" required class="form-control" step="any"
                                onkeyup="calculateGross()" value="<?= $_SESSION['selected_edit_cola'] ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Monthly Gross Salary</label>&nbsp;<span style="color:red;font-weight:bold">*</span>
                            <input type="number" name="monthly_gross" id="monthly_gross" readonly class="form-control"
                                step="any" value="<?= $_SESSION['selected_edit_monthly_gross'] ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Withholding tax</label>&nbsp;<span style="color:red;font-weight:bold">*</span>
                            <input type="number" name="whtax" required class="form-control"
                                value="<?= $_SESSION['selected_edit_whtax'] ?>" step="any">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Remarks</label>&nbsp;<span style="color:red;font-weight:bold">*</span>
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
function calculateGross() {
    // Get the input values
    var basic_salary = Number(document.getElementById("basic_salary").value);
    var cola = Number(document.getElementById("cola").value);

    // Calculate the vat
    var monthly_gross = basic_salary + cola;

    // Display the result in the input field
    document.getElementById("monthly_gross").value = monthly_gross;
}
</script>