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
                <i class="fa fa-user"></i>Account Creation
            </h1>
        </div>
    </div>
    <div class="block full">
        <div class="block-title">
            <div class="block-options pull-right">
                <a href="account-list" class="btn btn-alt btn-sm btn-default">Account List</a>
            </div>
            <h2><strong>Account Creation</strong> Form</h2>
        </div>
        <div class="container-fluid">
            <?= $res ?>
            <form method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" readonly name="email_address" class="form-control" value ="<?= $_SESSION['create_emp_email'] ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Account Name</label>
                            <input type="text" readonly name="account_name" class="form-control" value ="<?= $_SESSION['create_emp_name'] ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Company</label>
                            <input type="text" readonly name="company_name" class="form-control" value ="<?= $_SESSION['create_emp_comp'] ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Employee Number</label>
                            <input type="text" readonly name="employee_number" class="form-control" value ="<?= $_SESSION['create_emp_num'] ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" minlength="6" class="form-control" required>
                        </div>
                    </div>
                </div>
                <hr>
                <h5><b>Role</b></h5>
                <div class="row">
                    <?php
                    // $company_id = $_SESSION['hris_company_id'];
                    // $get_roles = mysqli_query($db, "SELECT * FROM tbl_roles WHERE company_id = '$company_id'");
                    // while ($row = mysqli_fetch_assoc($get_roles)) {
                    ?>
                    <!-- <div class="col-md-6">
                            <div class="form-group">
                                <label class="switch switch-primary">
                                    <input type="checkbox" name="role[]" value="<?= $row['ID'] ?>">
                                    <span></span>
                                </label>
                                <label><?= $row['role'] ?></label>
                            </div>
                        </div> -->
                    <?php
                    // }
                    ?>
                    <div class="form-group">
                        <div class="container-fluid">
                            <label class="radio-inline">
                                <input type="radio" name="role" value="Admin" required> Admin
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="role" value="Manager" required> Manager
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="role" value="Supervisor" required> Supervisor
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="role" value="User" required> User
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row" style="display: none;" id="handled_company">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Handled Company</label>
                            <select name="companies[]" id="companies" data-placeholder="Choose Companies..." class="select-chosen" multiple style="width:350px;" tabindex="4">
                                <option value=""></option>
                                <?php
                                $companies = get_companies();
                                foreach ($companies as $k => $v) {
                                    echo '<option value="' . $v['ID'] . '">' . $v['company_name'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="ifnotprocessor">
                    <h5><b>Modules</b></h5>
                    <div class="row">
                        <!-- <div class="col-md-6">
                            <div class="form-group">
                                <label class="switch switch-primary">
                                    <input type="checkbox" name="pending_tasks" value="1">
                                    <span></span>
                                </label>
                                <label>Pending Tasks</label>
                            </div>
                        </div> -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="switch switch-primary">
                                    <input type="checkbox" id="201" name="file201" value="1">
                                    <span></span>
                                </label>
                                <label>201 Files</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="switch switch-primary">
                                    <input type="checkbox" id="leave" name="leave_management" value="1">
                                    <span></span>
                                </label>
                                <label>Leave Management</label>
                            </div>
                        </div>
<!--                         <div class="col-md-6">
                            <div class="form-group">
                                <label class="switch switch-primary">
                                    <input type="checkbox" name="ot_management" value="1">
                                    <span></span>
                                </label>
                                <label>OT Management</label>
                            </div>
                        </div> -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="switch switch-primary">
                                    <input type="checkbox" id="cert" name="certificate_request" value="1">
                                    <span></span>
                                </label>
                                <label>Certificate Requests</label>
                            </div>
                        </div>
                        <!-- <div class="col-md-6">
                            <div class="form-group">
                                <label class="switch switch-primary">
                                    <input type="checkbox" name="salary_loan" value="1">
                                    <span></span>
                                </label>
                                <label>Salary Loan Management</label>
                            </div>
                        </div> -->
                        <!-- <div class="col-md-6">
                            <div class="form-group">
                                <label class="switch switch-primary">
                                    <input type="checkbox" name="benefits_reimbursement" value="1">
                                    <span></span>
                                </label>
                                <label>Benefits Reimbursement</label>
                            </div>
                        </div> -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="switch switch-primary">
                                    <input type="checkbox" id="holiday" name="holiday_maintenance" value="1">
                                    <span></span>
                                </label>
                                <label>Holiday Maintenance</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="switch switch-primary">
                                    <input type="checkbox" id="generate" name="generate_reports" value="1">
                                    <span></span>
                                </label>
                                <label>Generate Reports</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="switch switch-primary">
                                    <input type="checkbox" id="payslip" name="generate_reports_emp" value="1">
                                    <span></span>
                                </label>
                                <label>Generate Payslip</label>
                            </div>
                        </div>
                        <!-- <div class="col-md-6">
                            <div class="form-group">
                                <label class="switch switch-primary">
                                    <input type="checkbox" name="timekeeping" value="1">
                                    <span></span>
                                </label>
                                <label>Timekeeping</label>
                            </div>
                        </div> -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="switch switch-primary">
                                    <input type="checkbox" id="training" name="training" value="1">
                                    <span></span>
                                </label>
                                <label>Training</label>
                            </div>
                        </div>
                        <!-- <div class="col-md-6">
                            <div class="form-group">
                                <label class="switch switch-primary">
                                    <input type="checkbox" name="performance" value="1">
                                    <span></span>
                                </label>
                                <label>Performance Management</label>
                            </div>
                        </div> -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="switch switch-primary">
                                    <input type="checkbox" id="payroll" name="payroll_management" value="1">
                                    <span></span>
                                </label>
                                <label>Payroll Management</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="switch switch-primary">
                                    <input type="checkbox" id="app" name="application_management" value="1">
                                    <span></span>
                                </label>
                                <label>Application Management</label>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="btn btn-primary" name="btn_register">Submit</button>
            </form>
        </div>
    </div>
</div>

<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>
<?php include 'inc/template_end.php'; ?>
<script>
    $(".chosen-select").chosen();
    $('#email').change(function() {
        var email = $(this).val();
        var get_account_name = '';
        $.ajax({
            url: "inc/config.php",
            method: "POST",
            data: {
                get_account_name: get_account_name,
                email: email
            },
            success: function(data) {
                $('#account_name').val(data);
            }
        });
    });
    $('[name="role"]').click(function() {
        if ($(this).val() == "Admin") {
            $('#201').prop('checked', true);
            $('#cert').prop('checked', true);
            $('#leave').prop('checked', true);
            $('#holiday').prop('checked', true);
            $('#generate').prop('checked', true);
            $('#training').prop('checked', true);
            $('#payslip').prop('checked', true);
            $('#app').prop('checked', true);
            $('#payroll').prop('checked', true);
        }else if ($(this).val() == "Manager" || $(this).val() == "Supervisor" ){
            $('#201').prop('checked', false);
            $('#cert').prop('checked', true);
            $('#leave').prop('checked', true);
            $('#holiday').prop('checked', false);
            $('#generate').prop('checked', false);
            $('#training').prop('checked', true);
            $('#payslip').prop('checked', true);
            $('#app').prop('checked', true);
            $('#payroll').prop('checked', false);
        }else{
            $('#201').prop('checked', false);
            $('#cert').prop('checked', true);
            $('#leave').prop('checked', true);
            $('#holiday').prop('checked', false);
            $('#generate').prop('checked', false);
            $('#training').prop('checked', false);
            $('#payslip').prop('checked', true);
            $('#app').prop('checked', false);
            $('#payroll').prop('checked', false);
        }
        if ($(this).val() == "Processor") {
            $('input:checkbox').prop('checked', true);
            $('.ifnotprocessor').hide();
            $('#handled_company').removeAttr('style');
            $('#companies').prop('required', true);
        } else {
            $('.ifnotprocessor').show();
            $('#handled_company').attr('style', 'display:none');
            $('#companies').prop('required', false);
        }
    });
</script>