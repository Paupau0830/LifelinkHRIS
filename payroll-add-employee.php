<?php include 'inc/config.php'; ?>
<?php include 'inc/template_start.php'; ?>
<?php include 'inc/page_head.php'; ?>
<?php
if (empty($_SESSION['hris_id'])) {
    header('Location: login');
}

$employee_number = $_SESSION['add_payroll_empnum'];
$bank_name = '';
$employment_status = '';
$sql = mysqli_query($db, "SELECT * FROM tbl_employment_information WHERE employee_number = '$employee_number'");
while ($row = mysqli_fetch_assoc($sql)) {
    $employment_status = $row['employment_status'];
    if ($row['employment_status'] != 'Regular') {
        $bank_name = 'Cash';
    }
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
            <h2><strong>Add</strong> Employee</h2>
        </div>
        <div class="container-fluid">
            <?= $res ?>
            <form method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Employee ID</label>&nbsp;<span style="color:red;font-weight:bold">*</span>
                            <input type="text" readonly name="emp_num" required class="form-control" value="<?= $_SESSION['add_payroll_empnum'] ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>Employee Name</label>&nbsp;<span style="color:red;font-weight:bold">*</span>
                        <input type="text" readonly name="emp_name" required class="form-control" value="<?= $_SESSION['add_payroll_empname'] ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Company</label>&nbsp;<span style="color:red;font-weight:bold">*</span>
                            <input type="text" readonly name="select_company" required class="form-control" value="<?= $_SESSION['add_payroll_empcomp'] ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Job Title</label>&nbsp;<span style="color:red;font-weight:bold">*</span>
                            <input type="text" readonly name="job_title" required class="form-control" value="<?= $_SESSION['add_payroll_empjob'] ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Employment Status</label>&nbsp;<span style="color:red;font-weight:bold">*</span>
                            <input type="text" readonly name="employment_status" required class="form-control" value="<?= $employment_status ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Basic Salary</label>&nbsp;<span style="color:red;font-weight:bold">*</span>
                            <input type="number" name="basic_salary" id="basic_salary" required class="form-control" step="any" onkeyup="calculateGross()">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Account number</label>&nbsp;<span style="color:red;font-weight:bold">*</span>
                            <input type="number" name="acc_num" required class="form-control" step="any">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Bank Name</label>&nbsp;<span style="color:red;font-weight:bold">*</span>
                            <input type="text" name="bank_name" required class="form-control" value="<?= $bank_name ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>SSS EC</label>&nbsp;<span style="color:red;font-weight:bold">*</span>
                            <input type="number" name="sss_ec" readonly class="form-control" step="any">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>SSS EE</label>&nbsp;<span style="color:red;font-weight:bold">*</span>
                            <input type="number" name="sss_ee" readonly class="form-control" step="any">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>SSS ER</label>&nbsp;<span style="color:red;font-weight:bold">*</span>
                            <input type="number" name="sss_er" readonly class="form-control" step="any">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>HDMF EE</label>&nbsp;<span style="color:red;font-weight:bold">*</span>
                            <input type="number" name="hdmf_ee" readonly class="form-control" step="any">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>HDMF ER</label>&nbsp;<span style="color:red;font-weight:bold">*</span>
                            <input type="number" name="hdmf_er" readonly class="form-control" step="any">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Philhealth EE</label>&nbsp;<span style="color:red;font-weight:bold">*</span>
                            <input type="number" name="philhealth_ee" readonly class="form-control" step="any">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Philhealth ER</label>&nbsp;<span style="color:red;font-weight:bold">*</span>
                            <input type="number" name="philhealth_er" readonly class="form-control" step="any">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Annual Medical Allowance</label>&nbsp;<span style="color:red;font-weight:bold">*</span>
                            <input type="number" name="annual_medical_allowance" required class="form-control" step="any">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Commission</label>&nbsp;<span style="color:red;font-weight:bold">*</span>
                            <input type="number" name="commission" required class="form-control" step="any">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Deminimis</label>&nbsp;<span style="color:red;font-weight:bold">*</span>
                            <input type="number" name="deminimis" required class="form-control" step="any">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Other Additional Allowance</label>&nbsp;<span style="font-style:italic">(please
                                specify)</span>&nbsp;<span style="color:red;font-weight:bold">*</span>
                            <input type="text" name="additional_allowance" class="form-control" step="any">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Amount</label>&nbsp;<span style="color:red;font-weight:bold">*</span>
                            <input type="number" name="additional_allowance_amount" required class="form-control" step="any">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Cost of Living Allowance</label>&nbsp;<span>(COLA)</span>&nbsp;<span style="color:red;font-weight:bold">*</span>
                            <input type="number" name="cola" id="cola" required class="form-control" step="any" onkeyup="calculateGross()">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Monthly Gross Salary</label>&nbsp;<span style="color:red;font-weight:bold">*</span>
                            <input type="number" name="monthly_gross" id="monthly_gross" readonly class="form-control" step="any">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Remarks</label>&nbsp;<span style="color:red;font-weight:bold">*</span>
                            <textarea name="remarks" class="form-control" step="any"></textarea>
                        </div>
                    </div>
                </div>
                <button class="btn btn-primary" name="add_employee">Submit</button>
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