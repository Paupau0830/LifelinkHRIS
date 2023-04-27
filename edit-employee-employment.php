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
            <li class="active">
                <a href="edit-employee-employment?<?= md5('id') . '=' . $fid ?>"><i class="fa fa-briefcase"></i> Employment Information</a>
            </li>
            <li>
                <a href="edit-employee-documents?<?= md5('id') . '=' . $fid ?>"><i class="fa fa-file"></i> Documents</a>
            </li>
            <li>
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
            <h2><strong>Employment</strong> Information</h2>
        </div>
        <?= $res ?>
        <div class="container-fluid">
            <form method="POST">
                <?php
                $sql = mysqli_query($db, "SELECT * FROM tbl_employment_information WHERE employee_number = '$employee_number'");
                while ($row = mysqli_fetch_assoc($sql)) {
                    $cid = $row['company'];
                    $group_name = $row['group_name'];
                    $unit = $row['unit'];
                    // $group_name = $row['group_name'];
                    // $group_name = $row['group_name'];
                    $on_behalf = '';
                    $is_approver = '';
                    if ($row['filing'] == "1") {
                        $on_behalf = 'checked';
                    }
                    if ($row['is_approver'] == "1") {
                        $is_approver = 'checked';
                    }
                ?>
                    <input type="hidden" name="employee_number" value="<?= $row['employee_number'] ?>">
                    <input type="hidden" name="old_position" value="<?= $row['job_grade'] ?>">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Position Number</label>
                                    <input type="text" readonly class="form-control" value="<?= $row['position_number'] ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Position Title</label>
                                    <input type="text" name="position_title" class="form-control" value="<?= $row['position_title'] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Job Description</label>
                                    <textarea name="job_description" rows="6" class="form-control"><?= $row['job_description'] ?></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Date Hired</label>
                                    <input type="date" name="date_hired" class="form-control" value="<?= $row['date_hired'] ?>">
                                </div>
                                <div class="form-group">
                                    <label>Company</label>
                                    <select name="company" class="select-chosen" id="company" data-placeholder="Choose a company..." style="width: 250px;">
                                        <option></option>
                                        <?php
                                        $companies = get_companies();
                                        foreach ($companies as $k => $v) {
                                            if ($v['ID'] == $row['company']) {
                                                echo '<option value="' . $v['ID'] . '" selected>' . $v['company_name'] . '</option>';
                                            } else {
                                                echo '<option value="' . $v['ID'] . '">' . $v['company_name'] . '</option>';
                                            }
                                        ?>
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
                                        $depts = get_departments($cid);
                                        foreach ($depts as $k => $v) {
                                            if ($v['ID'] == $row['department']) {
                                                echo '<option value="' . $v['ID'] . '" selected>' . $v['department'] . '</option>';
                                            } else {
                                                echo '<option value="' . $v['ID'] . '">' . $v['department'] . '</option>';
                                            }
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
                                        $jgs = get_job_grade_set($cid);
                                        foreach ($jgs as $k => $v) {
                                            if ($v['ID'] == $row['job_grade_set']) {
                                                echo '<option value="' . $v['ID'] . '" selected>' . $v['job_grade_set'] . '</option>';
                                            } else {
                                                echo '<option value="' . $v['ID'] . '">' . $v['job_grade_set'] . '</option>';
                                            }
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
                                        <?php
                                        // added project based and Intern
                                        $emp_stat = array('Intern', 'Project Based', 'Probationary', 'Regular', 'Consultant');
                                        foreach ($emp_stat as $k => $v) {
                                            if ($v == $row['employment_status']) {
                                                echo '<option selected>' . $v . '</option>';
                                            } else {
                                                echo '<option>' . $v . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Job Grade</label>
                                    <select name="job_grade" id="job_grade" class="select-chosen" data-placeholder="Choose a job grade..." style="width: 250px;">
                                        <option></option>
                                        <?php
                                        $jg = get_job_grade($cid);
                                        foreach ($jg as $k => $v) {
                                            if ($v['ID'] == $row['job_grade']) {
                                                echo '<option value="' . $v['ID'] . '" selected>' . $v['job_grade'] . '</option>';
                                            } else {
                                                echo '<option value="' . $v['ID'] . '">' . $v['job_grade'] . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Group</label>
                                    <select name="group" id="group" class="select-chosen" data-placeholder="Choose a group..." style="width: 250px;">
                                        <?php
                                        $sql = mysqli_query($db, "SELECT * FROM tbl_department_group");
                                        while ($row_group = mysqli_fetch_assoc($sql)) {
                                        ?>
                                            <option value="<?= $row_group['id'] ?>" <?php if ($group_name == $row_group['id']) echo 'selected="selected"'; ?>><?= $row_group['name'] ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                    <!-- <input type="text" name="group" class="form-control" value="<?= $row['group_name'] ?>"> -->
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Unit</label>
                                    <select name="unit" id="unit" class="select-chosen" data-placeholder="Choose a unit..." style="width: 250px;">
                                        <?php
                                        $sql = mysqli_query($db, "SELECT * FROM tbl_department_unit");
                                        while ($row_unit = mysqli_fetch_assoc($sql)) {
                                        ?>
                                            <option value="<?= $row_unit['id'] ?>" <?php if ($unit == $row_unit['id']) echo 'selected="selected"'; ?>><?= $row_unit['name'] ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                    <!-- <input type="text" name="unit" class="form-control" value="<?= $row['unit'] ?>"> -->

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Position</label>
                                    <input type="text" name="position" class="form-control" value="<?= $row['position'] ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Rank</label>
                                    <input type="text" name="rank" class="form-control" value="<?= $row['rank_name'] ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>HMO Number</label>
                                    <input type="text" name="hmo_number" class="form-control" value="<?= $row['hmo_number'] ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tenure</label>
                                    <input type="text" name="tenure" class="form-control" value="<?= $row['tenure'] ?>">
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
                                        $app = get_approvers($cid);
                                        foreach ($app as $k => $v) {
                                            if ($v['employee_number'] == $row['approver']) {
                                                echo '<option value="' . $v['employee_number'] . '" selected>' . $v['account_name'] . '</option>';
                                            } else {
                                                echo '<option value="' . $v['employee_number'] . '">' . $v['account_name'] . '</option>';
                                            }
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
                                        <?php
                                        $acc_stat = array('Active', 'Inactive');
                                        foreach ($acc_stat as $k => $v) {
                                            if ($v == $row['account_status']) {
                                                echo '<option selected>' . $v . '</option>';
                                            } else {
                                                echo '<option>' . $v . '</option>';
                                            }
                                        }
                                        ?>
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
                                        $app = get_approvers($cid);
                                        foreach ($app as $k => $v) {
                                            if ($v['employee_number'] == $row['reporting_to']) {
                                                echo '<option value="' . $v['employee_number'] . '" selected>' . $v['account_name'] . '</option>';
                                            } else {
                                                echo '<option value="' . $v['employee_number'] . '">' . $v['account_name'] . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Vendor ID</label>
                                    <input type="text" class="form-control" name="vendor_id" value="<?= $row['vendor_id'] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="on_behalf_filing" value="1" <?= $on_behalf ?>> Allow on behalf filing
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="is_approver" value="1" <?= $is_approver ?>> Approver
                                </label>
                            </div>
                        </div>

                        <button class="btn btn-primary" name="btn_update_employment_info">Update</button>
                    </div>
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
<script>
    $('#company_email').keyup(function(e) {
        var onboarding_validate_email = $(this).val();
        var em = '<?= $em ?>';
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "inc/config.php",
            data: {
                onboarding_validate_email: onboarding_validate_email
            },
            success: function(response) {
                if (onboarding_validate_email !== em) {
                    if (response == "1") {
                        alert('Email was already onboard');
                        $('#company_email').val('');
                    }
                }
            }
        });
        e.preventDefault();
    });
</script>