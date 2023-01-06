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
<style>
    .wizard-steps span {
        width: 110px;
    }

    @media only screen and (min-width: 992px) {
        .rem_button {
            margin-top: 38px;
        }
    }

    @media (min-width: 768px) {

        .seven-cols .col-md-1,
        .seven-cols .col-sm-1,
        .seven-cols .col-lg-1 {
            width: 100%;
            *width: 100%;
        }
    }

    @media (min-width: 992px) {

        .seven-cols .col-md-1,
        .seven-cols .col-sm-1,
        .seven-cols .col-lg-1 {
            width: 14.285714285714285714285714285714%;
            *width: 14.285714285714285714285714285714%;
        }
    }

    /**
 *  The following is not really needed in this case
 *  Only to demonstrate the usage of @media for large screens
 */
    @media (min-width: 1200px) {

        .seven-cols .col-md-1,
        .seven-cols .col-sm-1,
        .seven-cols .col-lg-1 {
            width: 14.285714285714285714285714285714%;
            *width: 14.285714285714285714285714285714%;
        }
    }
</style>
<!-- Page content -->
<div id="page-content">
    <!-- Wizard Header -->
    <div class="content-header">
        <div class="header-section">
            <h1>
                <i class="fa fa-magic"></i>Onboarding<br><small>Please fill up all required fields</small>
            </h1>
        </div>
    </div>
    <ul class="breadcrumb breadcrumb-top">
        <li>201 File</li>
        <li><a href="">Onboarding</a></li>
    </ul>
    <div class="block">
        <!-- Wizard with Validation Title -->
        <div class="block-title">
            <h2><strong>Onboarding</strong> Form</h2>
        </div>
        <!-- END Wizard with Validation Title -->

        <!-- Wizard with Validation Content -->
        <form id="advanced-wizard" enctype="multipart/form-data" method="post" class="form-horizontal form-bordered">
            <!-- First Step -->
            <div id="advanced-first" class="step">
                <!-- Step Info -->
                <div class="wizard-steps">
                    <div class="row seven-cols">
                        <div class="col-md-1 active">
                            <span>1. Information</span>
                        </div>
                        <div class="col-md-1">
                            <span>2. Education</span>
                        </div>
                        <div class="col-md-1">
                            <span>3. Contacts</span>
                        </div>
                        <div class="col-md-1">
                            <span>4. IDs</span>
                        </div>
                        <div class="col-md-1">
                            <span>5. Employment</span>
                        </div>
                        <div class="col-md-1">
                            <span>6. Documents</span>
                        </div>
                        <div class="col-md-1">
                            <span>7. Benefits</span>
                        </div>
                    </div>
                </div>
                <!-- END Step Info -->
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Employee Number</label>
                                <input type="text" readonly class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Account Name</label>
                                <input type="text" id="acc_name" name="account_name" readonly class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Company Email</label>
                                <input type="email" id="company_email" name="company_email" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date of Birth</label>
                                <input type="date" class="form-control" name="date_of_birth">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Last Name</label>
                                <input type="text" id="lname" name="last_name" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Age</label>
                                <input type="number" class="form-control" id="age" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>First Name</label>
                                <input type="text" id="fname" name="first_name" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Gender</label>
                                <select name="gender" class="select-chosen" data-placeholder="Choose a gender..." style="width: 250px;">
                                    <option></option>
                                    <option>Male</option>
                                    <option>Female</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Middle Name</label>
                                <input type="text" id="mname" name="middle_name" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Citizenship</label>
                                <input type="text" name="citizenship" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Address</label>
                                <textarea name="address" rows="6" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Civil Status</label>
                                <select name="civil_status" class="select-chosen" data-placeholder="Choose a civil status..." style="width: 250px;">
                                    <option></option>
                                    <option>Single</option>
                                    <option>Married</option>
                                    <option>Separated</option>
                                    <option>Widowed</option>
                                    <option>Divorced</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Spouse Name</label>
                                <input type="text" name="spouse_name" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Personal Email</label>
                                <input type="email" name="personal_email" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Contact Number</label>
                                <input type="text" name="contact_number" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END First Step -->

            <!-- Second Step -->
            <div id="advanced-second" class="step">
                <!-- Step Info -->
                <div class="wizard-steps">
                    <div class="row seven-cols">
                        <div class="col-md-1 done">
                            <span>1. Information</span>
                        </div>
                        <div class="col-md-1 active">
                            <span>2. Education</span>
                        </div>
                        <div class="col-md-1">
                            <span>3. Contacts</span>
                        </div>
                        <div class="col-md-1">
                            <span>4. IDs</span>
                        </div>
                        <div class="col-md-1">
                            <span>5. Employment</span>
                        </div>
                        <div class="col-md-1">
                            <span>6. Documents</span>
                        </div>
                        <div class="col-md-1">
                            <span>7. Benefits</span>
                        </div>
                    </div>
                </div>
                <!-- END Step Info -->
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Post Graduate School</label>
                                <input type="text" id="post_graduate_school" name="post_graduate_school" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>From</label>
                                        <input type="text" id="from_pgs" name="from_pgs" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>To</label>
                                        <input type="text" id="to_pgs" name="to_pgs" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>University / College</label>
                                <input type="text" name="universitycollege[]" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>From</label>
                                        <input type="text" name="from_uc[]" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>To</label>
                                        <input type="text" name="to_uc[]" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Degree / Vocational / Course</label>
                        <input type="text" name="degree[]" class="form-control">
                    </div>
                    <br>
                    <div id="div_add_education"></div>
                    <button type="button" class="add_education btn btn-success btn-sm"><i class="fa fa-plus"></i> Add more</button>
                </div>
            </div>
            <!-- END Second Step -->
            <div id="advanced-third" class="step">
                <!-- Step Info -->
                <div class="wizard-steps">
                    <div class="row seven-cols">
                        <div class="col-md-1 done">
                            <span>1. Information</span>
                        </div>
                        <div class="col-md-1 done">
                            <span>2. Education</span>
                        </div>
                        <div class="col-md-1 active">
                            <span>3. Contacts</span>
                        </div>
                        <div class="col-md-1">
                            <span>4. IDs</span>
                        </div>
                        <div class="col-md-1">
                            <span>5. Employment</span>
                        </div>
                        <div class="col-md-1">
                            <span>6. Documents</span>
                        </div>
                        <div class="col-md-1">
                            <span>7. Benefits</span>
                        </div>
                    </div>
                </div>
                <!-- END Step Info -->
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Contact Name</label>
                                <input type="text" name="contact_name[]" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Contact Number</label>
                                <input type="text" name="ec_contact_number[]" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="text" name="ec_email[]" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Relationship</label>
                                <input type="text" name="ec_relationship[]" class="form-control">
                            </div>
                        </div>
                    </div><br>
                    <div id="div_add_emergency_contact"></div>
                    <button type="button" class="add_emergency_contact btn btn-success btn-sm"><i class="fa fa-plus"></i> Add more</button>
                </div>
            </div>
            <div id="advanced-fourth" class="step">
                <!-- Step Info -->
                <div class="wizard-steps">
                    <div class="row seven-cols">
                        <div class="col-md-1 done">
                            <span>1. Information</span>
                        </div>
                        <div class="col-md-1 done">
                            <span>2. Education</span>
                        </div>
                        <div class="col-md-1 done">
                            <span>3. Contacts</span>
                        </div>
                        <div class="col-md-1 active">
                            <span>4. IDs</span>
                        </div>
                        <div class="col-md-1">
                            <span>5. Employment</span>
                        </div>
                        <div class="col-md-1">
                            <span>6. Documents</span>
                        </div>
                        <div class="col-md-1">
                            <span>7. Benefits</span>
                        </div>
                    </div>
                </div>
                <!-- END Step Info -->
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>SSS</label>
                                <input type="text" name="sss" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>PAG-IBIG</label>
                                <input type="text" name="pagibig" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Philhealth</label>
                                <input type="text" name="philhealth" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>TIN</label>
                                <input type="text" name="tin" class="form-control">
                            </div>
                        </div>
                    </div>
                    <p>Others</p>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>ID Name</label>
                                <input type="text" name="id_name[]" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>ID Number</label>
                                <input type="text" name="id_number[]" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div id="div_add_gov_id"></div>
                    <button type="button" class="add_gov_id btn btn-success btn-sm"><i class="fa fa-plus"></i> Add more</button>
                </div>
            </div>
            <div id="advanced-fifth" class="step">
                <!-- Step Info -->
                <div class="wizard-steps">
                    <div class="row seven-cols">
                        <div class="col-md-1 done">
                            <span>1. Information</span>
                        </div>
                        <div class="col-md-1 done">
                            <span>2. Education</span>
                        </div>
                        <div class="col-md-1 done">
                            <span>3. Contacts</span>
                        </div>
                        <div class="col-md-1 done">
                            <span>4. IDs</span>
                        </div>
                        <div class="col-md-1 active">
                            <span>5. Employment</span>
                        </div>
                        <div class="col-md-1">
                            <span>6. Documents</span>
                        </div>
                        <div class="col-md-1">
                            <span>7. Benefits</span>
                        </div>
                    </div>
                </div>
                <!-- END Step Info -->
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Position Number</label>
                                <input type="text" readonly class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Position Title</label>
                                <input type="text" name="position_title" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Job Description</label>
                                <textarea name="job_description" rows="6" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date Hired</label>
                                <input type="date" name="date_hired" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Company</label>
                                <select name="company" class="select-chosen" id="company" data-placeholder="Choose a company..." style="width: 250px;">
                                    <option></option>
                                    <?php
                                    $companies = get_companies();
                                    foreach ($companies as $k => $v) {
                                    ?>
                                        <option value="<?= $v['ID'] ?>"><?= $v['company_name'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Department</label>
                                <select name="department" id="department" class="select-chosen" data-placeholder="Choose a department..." style="width: 250px;">
                                    <option></option>
                                    <?php
                                    $departments = get_departments($company_id);
                                    foreach ($departments as $k => $v) {
                                    ?>
                                        <option value="<?= $v['ID'] ?>"><?= $v['department'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Job Grade Set</label>
                                <select name="job_grade_set" id="job_grade_set" class="select-chosen" data-placeholder="Choose a job grade set..." style="width: 250px;">
                                    <option></option>
                                    <?php
                                    $jgs = get_job_grade_set($company_id);
                                    foreach ($jgs as $k => $v) {
                                    ?>
                                        <option value="<?= $v['ID'] ?>"><?= $v['job_grade_set'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Employment Status</label>
                                <select name="employment_status" class="select-chosen" data-placeholder="Choose an employment status..." style="width: 250px;">
                                    <option></option>
                                    <option>Probationary</option>
                                    <option>Regular</option>
                                    <option>Consultant</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Job Grade</label>
                                <select name="job_grade" id="job_grade" class="select-chosen" data-placeholder="Choose a job grade..." style="width: 250px;">
                                    <option></option>
                                    <?php
                                    $job_grade = get_job_grade($company_id);
                                    foreach ($job_grade as $k => $v) {
                                    ?>
                                        <option value="<?= $v['ID'] ?>"><?= $v['job_grade'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Approver</label>
                                <select name="approver" class="select-chosen" data-placeholder="Choose an approvers..." style="width: 250px;">
                                    <option></option>
                                    <?php
                                    $approver = get_approvers($company_id);
                                    foreach ($approver as $k => $v) {
                                    ?>
                                        <option value="<?= $v['employee_number'] ?>"><?= $v['account_name'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Account Status</label>
                                <select name="account_status" class="select-chosen" data-placeholder="Choose an account status..." style="width: 250px;">
                                    <option></option>
                                    <option>Active</option>
                                    <option>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Reporting To</label>
                                <select name="reporting_to" class="select-chosen" data-placeholder="Choose a superior..." style="width: 250px;">
                                    <option></option>
                                    <?php
                                    $approver = get_approvers($company_id);
                                    foreach ($approver as $k => $v) {
                                    ?>
                                        <option value="<?= $v['employee_number'] ?>"><?= $v['account_name'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6" style="display: none;">
                            <div class="form-group">
                                <label>Vendor ID</label>
                                <input type="text" class="form-control" name="vendor_id" value="none">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="on_behalf_filing" value="1"> Allow on behalf filing
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="is_approver" value="1"> Approver
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div id="advanced-sixth" class="step">
                <!-- Step Info -->
                <div class="wizard-steps">
                    <div class="row seven-cols">
                        <div class="col-md-1 done">
                            <span>1. Information</span>
                        </div>
                        <div class="col-md-1 done">
                            <span>2. Education</span>
                        </div>
                        <div class="col-md-1 done">
                            <span>3. Contacts</span>
                        </div>
                        <div class="col-md-1 done">
                            <span>4. IDs</span>
                        </div>
                        <div class="col-md-1 done">
                            <span>5. Employment</span>
                        </div>
                        <div class="col-md-1 active">
                            <span>6. Documents</span>
                        </div>
                        <div class="col-md-1">
                            <span>7. Benefits</span>
                        </div>
                    </div>
                </div>
                <!-- END Step Info -->
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Supporting Document</label>
                                <input type="file" class="form-control" name="attachment[]">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Remarks</label>
                                <textarea name="attachment_remarks[]" rows="5" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div id="div_add_attachment" style="margin-left:2px;margin-right:2px"></div>
                    <button type="button" class="add_attachment btn btn-success btn-sm"><i class="fa fa-plus"></i> Add more attachment</button>
                </div>
            </div>
            <div id="advanced-seventh" class="step">
                <!-- Step Info -->
                <div class="wizard-steps">
                <div class="row seven-cols">
                        <div class="col-md-1 done">
                            <span>1. Information</span>
                        </div>
                        <div class="col-md-1 done">
                            <span>2. Education</span>
                        </div>
                        <div class="col-md-1 done">
                            <span>3. Contacts</span>
                        </div>
                        <div class="col-md-1 done">
                            <span>4. IDs</span>
                        </div>
                        <div class="col-md-1 done">
                            <span>5. Employment</span>
                        </div>
                        <div class="col-md-1 done">
                            <span>6. Documents</span>
                        </div>
                        <div class="col-md-1 active">
                            <span>7. Benefits</span>
                        </div>
                    </div>
                </div>
                <!-- END Step Info -->
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="switch switch-primary">
                                    <input type="checkbox" name="benefits_eligibility[]" value="Parking">
                                    <span></span>
                                </label>
                                <label>Parking</label>
                            </div>
                            <div class="form-group">
                                <label class="switch switch-primary">
                                    <input type="checkbox" name="benefits_eligibility[]" value="Gasoline">
                                    <span></span>
                                </label>
                                <label>Gasoline</label>
                            </div>
                            <div class="form-group">
                                <label class="switch switch-primary">
                                    <input type="checkbox" name="benefits_eligibility[]" value="Car Maintenance">
                                    <span></span>
                                </label>
                                <label>Car Maintenance</label>
                            </div>
                            <div class="form-group">
                                <label class="switch switch-primary">
                                    <input type="checkbox" checked name="benefits_eligibility[]" value="Medicine">
                                    <span></span>
                                </label>
                                <label>Medicine</label>
                            </div>
                            <div class="form-group">
                                <label class="switch switch-primary">
                                    <input type="checkbox" checked name="benefits_eligibility[]" value="Gym">
                                    <span></span>
                                </label>
                                <label>Gym</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="switch switch-primary">
                                    <input type="checkbox" name="benefits_eligibility[]" value="Optical Allowance">
                                    <span></span>
                                </label>
                                <label>Optical Allowance</label>
                            </div>
                            <div class="form-group">
                                <label class="switch switch-primary">
                                    <input type="checkbox" name="benefits_eligibility[]" value="CEP">
                                    <span></span>
                                </label>
                                <label>CEP</label>
                            </div>
                            <div class="form-group">
                                <label class="switch switch-primary">
                                    <input type="checkbox" name="benefits_eligibility[]" value="Club Membership">
                                    <span></span>
                                </label>
                                <label>Club Membership</label>
                            </div>
                            <div class="form-group">
                                <label class="switch switch-primary">
                                    <input type="checkbox" name="benefits_eligibility[]" value="Maternity">
                                    <span></span>
                                </label>
                                <label>Maternity</label>
                            </div>
                            <div class="form-group">
                                <label class="switch switch-primary">
                                    <input type="checkbox" checked name="benefits_eligibility[]" value="Others">
                                    <span></span>
                                </label>
                                <label>Others</label>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="benefits_eligibility[]" value="Parking"> Parking
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="benefits_eligibility[]" value="Gasoline"> Gasoline
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="benefits_eligibility[]" value="Car Maintenance"> <i class="fa fa-parking"></i> Car Maintenance
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="benefits_eligibility[]" checked value="Medicine"> Medicine
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="benefits_eligibility[]" checked value="Gym"> Gym
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="benefits_eligibility[]" value="Optical Allowance"> Optical Allowance
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="benefits_eligibility[]" value="CEP"> CEP
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="benefits_eligibility[]" value="Club Membership"> Club Membership
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="benefits_eligibility[]" value="Maternity"> Maternity
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="benefits_eligibility[]" checked value="Others"> Others
                            </label>
                        </div>
                    </div> -->
                    <!-- <div class="form-group">
                        <label class="col-md-4 control-label"><a href="#modal-terms" data-toggle="modal">Terms</a> <span class="text-danger">*</span></label>
                        <div class="col-md-6">
                            <label class="switch switch-primary" for="val_terms">
                                <input type="checkbox" id="val_terms" name="val_terms" value="1">
                                <span data-toggle="tooltip" title="I agree to the terms!"></span>
                            </label>
                        </div>
                    </div> -->
                </div>
            </div>
            <!-- Form Buttons -->
            <div class="form-group form-actions">
                <div class="col-md-8 col-md-offset-4">
                    <input type="reset" class="btn btn-sm btn-warning" id="back3" value="Back">
                    <input type="submit" name="btn_onboarding" class="btn btn-sm btn-primary" id="next3" value="Next">
                </div>
            </div>
            <!-- END Form Buttons -->
        </form>
        <!-- END Wizard with Validation Content -->
    </div>
    <!-- END Wizard with Validation Block -->
    <!-- Terms Modal -->
    <div id="modal-terms" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3 class="modal-title"><i class="gi gi-pen"></i> Service Terms</h3>
                </div>
                <div class="modal-body">
                    <h4 class="sub-header">1.1 | General</h4>
                    <p>Donec lacinia venenatis metus at bibendum? In hac habitasse platea dictumst. Proin ac nibh rutrum lectus rhoncus eleifend. Sed porttitor pretium venenatis. Suspendisse potenti. Aliquam quis ligula elit. Aliquam at orci ac neque semper dictum. Sed tincidunt scelerisque ligula, et facilisis nulla hendrerit non. Suspendisse potenti. Pellentesque non accumsan orci. Praesent at lacinia dolor. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    <h4 class="sub-header">1.2 | Account</h4>
                    <p>Donec lacinia venenatis metus at bibendum? In hac habitasse platea dictumst. Proin ac nibh rutrum lectus rhoncus eleifend. Sed porttitor pretium venenatis. Suspendisse potenti. Aliquam quis ligula elit. Aliquam at orci ac neque semper dictum. Sed tincidunt scelerisque ligula, et facilisis nulla hendrerit non. Suspendisse potenti. Pellentesque non accumsan orci. Praesent at lacinia dolor. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    <h4 class="sub-header">1.3 | Service</h4>
                    <p>Donec lacinia venenatis metus at bibendum? In hac habitasse platea dictumst. Proin ac nibh rutrum lectus rhoncus eleifend. Sed porttitor pretium venenatis. Suspendisse potenti. Aliquam quis ligula elit. Aliquam at orci ac neque semper dictum. Sed tincidunt scelerisque ligula, et facilisis nulla hendrerit non. Suspendisse potenti. Pellentesque non accumsan orci. Praesent at lacinia dolor. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    <h4 class="sub-header">1.4 | Payments</h4>
                    <p>Donec lacinia venenatis metus at bibendum? In hac habitasse platea dictumst. Proin ac nibh rutrum lectus rhoncus eleifend. Sed porttitor pretium venenatis. Suspendisse potenti. Aliquam quis ligula elit. Aliquam at orci ac neque semper dictum. Sed tincidunt scelerisque ligula, et facilisis nulla hendrerit non. Suspendisse potenti. Pellentesque non accumsan orci. Praesent at lacinia dolor. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">Ok, I've read them!</button>
                </div>
            </div>
        </div>
    </div>
    <!-- END Terms Modal -->
</div>
<!-- END Page Content -->

<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>

<!-- Load and execute javascript code used only in this page -->
<script src="js/pages/formsWizard.js"></script>
<script>
    $(function() {
        FormsWizard.init();
    });
    $(document).ready(function() {
        var accname = $('#acc_name');
        var fname = $('#fname');
        var mname = $('#mname');
        var lname = $('#lname');

        $("#lname").keyup(function() {
            accname.val($('#fname').val() + ' ' + $('#mname').val() + ' ' + $(this).val());
        });
        $("#fname").keyup(function() {
            accname.val($(this).val() + ' ' + $('#mname').val() + ' ' + $('#lname').val());
        });
        $("#mname").keyup(function() {
            accname.val($('#fname').val() + ' ' + $(this).val() + ' ' + $('#lname').val());
        });
        $('#company_email').keyup(function(e) {
            var onboarding_validate_email = $(this).val();
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "inc/config.php",
                data: {
                    onboarding_validate_email: onboarding_validate_email
                },
                success: function(response) {
                    if (response == "1") {
                        alert('Email was already onboard');
                        $('#company_email').val('');
                    }
                }
            });
            e.preventDefault();
        });
        // $('#company').change(function(e) {
        //     var onboarding_get_departments = $(this).val();
        //     e.preventDefault();
        //     $.ajax({
        //         type: "POST",
        //         url: "inc/config.php",
        //         data: {
        //             onboarding_get_departments: onboarding_get_departments
        //         },
        //         success: function(response) {
        //             $("#department").html(response);
        //             console.log(response);
        //         }
        //     });
        //     e.preventDefault();
        // });
    });
    $(".add_education").click(function() {
        var lastField = $("#div_add_education div:last");
        var intId =
            (lastField && lastField.length && lastField.data("idx") + 1) || 1;
        var fieldWrapper = $('<div class="row" id="field' + intId + '"/>');
        fieldWrapper.data("idx", intId);

        var college = $(
            '<div class="col-md-6"><div class="form-group"><label>University / College</label><input type="text" name="universitycollege[]" class="form-control"></div></div>'
        );
        var college_from_to = $(
            '<div class="col-md-6"><div class="row"><div class="col-md-6"><div class="form-group"><label>From</label><input type="text" name="from_uc[]" class="form-control"></div></div><div class="col-md-6"><div class="form-group"><label>To</label><input type="text" name="to_uc[]" class="form-control"></div></div></div></div>'
        );
        var degree = $(
            '<div class="col-md-11"><div class="form-group"><label>Degree / Vocational / Course</label><input type="text" class="form-control" name="degree[]"></div></div>'
        );
        var removeButton = $(
            "<div class='col-md-1 rem_button'><button class='btn btn-danger btn-sm'>–</button></div><br>"
        );
        removeButton.click(function() {
            $(this).parent().remove();
        });
        fieldWrapper.append(college);
        fieldWrapper.append(college_from_to);
        fieldWrapper.append(degree);
        fieldWrapper.append(removeButton);
        $("#div_add_education").append(fieldWrapper);
    });
    $(".add_emergency_contact").click(function() {
        var lastField = $("#div_add_emergency_contact div:last");
        var intId =
            (lastField && lastField.length && lastField.data("idx") + 1) || 1;
        var fieldWrapper = $('<div class="row" id="field' + intId + '"/>');
        fieldWrapper.data("idx", intId);

        var contact_name = $(
            '<div class="col-md-6"><div class="form-group"> <label>Contact Name</label> <input type="text" name="contact_name[]" class="form-control"></div></div>'
        );
        var details = $(
            '<div class="col-md-5"><div class="form-group"> <label>Contact Number</label> <input type="text" name="ec_contact_number[]" class="form-control"></div><div class="form-group"> <label>Email Address</label> <input type="text" name="ec_email[]" class="form-control"></div><div class="form-group"> <label>Relationship</label> <input type="text" name="ec_relationship[]" class="form-control"></div></div>'
        );
        var removeButton = $(
            "<div class='col-md-1 rem_button'><button class='btn btn-danger btn-sm'>–</button></div><br>"
        );
        removeButton.click(function() {
            $(this).parent().remove();
        });

        fieldWrapper.append(contact_name);
        fieldWrapper.append(details);
        fieldWrapper.append(removeButton);
        $("#div_add_emergency_contact").append(fieldWrapper);
    });
    $(".add_gov_id").click(function() {
        var lastField = $("#div_add_gov_id div:last");
        var intId =
            (lastField && lastField.length && lastField.data("idx") + 1) || 1;
        var fieldWrapper = $('<div class="row" id="field' + intId + '"/>');
        fieldWrapper.data("idx", intId);

        var gov_details = $(
            '<div class="col-md-6"><div class="form-group"> <label>ID Name</label> <input type="text" name="id_name[]" class="form-control"></div></div><div class="col-md-5"><div class="form-group"> <label>ID Number</label> <input type="text" name="id_number[]" class="form-control"></div></div>'
        );
        var removeButton = $(
            "<div class='col-md-1 rem_button'><button class='btn btn-danger btn-sm'>–</button></div><br>"
        );
        removeButton.click(function() {
            $(this).parent().remove();
        });
        fieldWrapper.append(gov_details);
        fieldWrapper.append(removeButton);
        $("#div_add_gov_id").append(fieldWrapper);
    });
    $(".add_attachment").click(function() {
        var lastField = $("#div_add_attachment div:last");
        var intId =
            (lastField && lastField.length && lastField.data("idx") + 1) || 1;
        var fieldWrapper = $('<div class="row" id="field' + intId + '"/>');
        fieldWrapper.data("idx", intId);

        var attachment = $(
            '<div class="col-md-11"><div class="form-group"> <label>Supporting Document</label> <input type="file" class="form-control" name="attachment[]"></div></div>'
        );
        var remarks = $(
            '<div class="col-md-12"><div class="form-group"> <label>Remarks</label><textarea name="attachment_remarks[]" rows="5" class="form-control"></textarea></div></div>'
        );
        var removeButton = $(
            "<div class='col-md-1 rem_button'><button class='btn btn-danger btn-sm'>–</button></div>"
        );
        removeButton.click(function() {
            $(this).parent().remove();
        });

        fieldWrapper.append(attachment);
        fieldWrapper.append(removeButton);
        fieldWrapper.append(remarks);
        $("#div_add_attachment").append(fieldWrapper);
    });
</script>

<?php include 'inc/template_end.php'; ?>