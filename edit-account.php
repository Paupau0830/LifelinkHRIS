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
$acc_name = '';
$sql = mysqli_query($db, "SELECT * FROM tbl_users");
while ($row = mysqli_fetch_assoc($sql)) {
    if ($fid == md5($row['ID'])) {
        $rid = $row['ID'];
        $acc_name = $row['account_name'];
    }
}


?>
<!-- Page content -->
<div id="page-content">
    <!-- eCommerce Dashboard Header -->
    <div class="content-header">
        <div class="header-section">
            <h1>
                <i class="fa fa-user"></i>Edit Account - <?= $acc_name ?>
            </h1>
        </div>
    </div>
    <div class="block full">
        <div class="block-title">
            <div class="block-options pull-right">
                <a href="javascript:void(0)" class="btn btn-alt btn-sm btn-default" onclick="$('#modal-change-password').modal('show');">Change Password</a>
            </div>
            <h2><strong>Edit</strong> Account</h2>
        </div>
        <div class="container-fluid">
            <?= $res ?>
            <form method="POST">
                <?php
                $get_details = mysqli_query($db, "SELECT * FROM tbl_users WHERE ID = '$rid'");
                while ($r = mysqli_fetch_assoc($get_details)) {
                    $cid = $r['company_id'];
                    $em = $r['email'];
                    $file = $r['file201']; // 201 file
                    $lm = ''; // leave
                    $cert = ''; // cert req
                    $hol = ''; // Holiday
                    $rep = ''; // Generate reports
                    $ap = ''; // Application Management
                    $tra = ''; // Training
                    $pr = ''; // Payroll Management
                    $pay = ''; // Generate payslip

                    if ($r['file201'] == '1') {
                        $file = 'checked';
                    }
                    if ($r['leave_management'] == '1') {
                        $lm = 'checked';
                    }
                    if ($r['certificate_requests'] == '1') {
                        $cert = 'checked';
                    }
                    if ($r['holiday_maintenance'] == '1') {
                        $hol = 'checked';
                    }
                    if ($r['generate_reports'] == '1') {
                        $rep = 'checked';
                    }
                    if ($r['generate_reports_emp'] == '1') {
                        $pay = 'checked';
                    }
                    if ($r['payroll_management'] == '1') {
                        $pr = 'checked';
                    }
                    if ($r['application_management'] == '1') {
                        $ap = 'checked';
                    }
                ?>
                    <input type="hidden" name="id" value="<?= $r['ID'] ?>">
                    <input type="hidden" name="cid" value="<?= $cid ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="email" name="email" value="<?= $r['email'] ?>" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Account Name</label>
                                <input type="text" name="account_name" class="form-control" value="<?= $r['account_name'] ?>">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h5><b>Role</b></h5>
                    <div class="row">
                        <?php
                        $role = $r['role'];
                        $boss = '';
                        $hr = '';
                        $user = '';
                        $manager = '';
                        $gh = '';
                        $officer = '';
                        if ($role == 'Admin') {
                            $boss = 'checked';
                        }
                        if ($role == 'Officer') {
                            $officer = 'checked';
                        }
                        if ($role == 'HR Processing') {
                            $hr = 'checked';
                        }
                        if ($role == 'Manager') {
                            $manager = 'checked';
                        }
                        if ($role == 'User') {
                            $user = 'checked';
                        }
                        if ($role == 'Group Head') {
                            $gh = 'checked';
                        }
                        ?>
                        <div class="form-group">
                            <div class="container-fluid">
                                <label class="radio-inline">
                                    <input type="radio" name="role" value="Admin" required <?= $boss ?>> Boss
                                </label>
                                <!-- <label class="radio-inline">
                                    <input type="radio" name="role" value="Officer" required <?= $officer ?>> Officer
                                </label> -->
                                <label class="radio-inline">
                                    <input type="radio" name="role" value="HR Processing" required <?= $hr ?>> HR Processing
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="role" value="Group Head" required <?= $officer ?>> Officer
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="role" value="Manager" required <?= $manager ?>> Manager
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="role" value="User" required <?= $user ?>> User
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
                                    $handled_companies = explode(',', $r['company_id']);
                                    $companies = get_companies();
                                    foreach ($companies as $k => $v) {
                                        if (in_array($v['ID'], $handled_companies)) {
                                            echo '<option selected value="' . $v['ID'] . '">' . $v['company_name'] . '</option>';
                                        } else {
                                            echo '<option value="' . $v['ID'] . '">' . $v['company_name'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h5><b>Modules</b></h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="switch switch-primary">
                                    <input type="checkbox" id="201" name="file201" value="1" <?= $file ?>>
                                    <span></span>
                                </label>
                                <label>201 Files</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="switch switch-primary">
                                    <input type="checkbox" id="leave" name="leave_management" value="1" <?= $lm ?>>
                                    <span></span>
                                </label>
                                <label>Leave Management</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="switch switch-primary">
                                    <input type="checkbox" id="cert" name="certificate_request" value="1" <?= $cert ?>>
                                    <span></span>
                                </label>
                                <label>Certificate Requests</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="switch switch-primary">
                                    <input type="checkbox" id="holiday" name="holiday_maintenance" value="1" <?= $hol ?>>
                                    <span></span>
                                </label>
                                <label>Holiday Maintenance</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="switch switch-primary">
                                    <input type="checkbox" id="generate" name="generate_reports" value="1" <?= $rep ?>>
                                    <span></span>
                                </label>
                                <label>Generate Reports</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="switch switch-primary">
                                    <input type="checkbox" id="payslip" name="generate_reports_emp" value="1" <?= $pay ?>>
                                    <span></span>
                                </label>
                                <label>Generate Payslip</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="switch switch-primary">
                                    <input type="checkbox" id="payroll" name="payroll_management" value="1" <?= $pr ?>>
                                    <span></span>
                                </label>
                                <label>Payroll Management</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="switch switch-primary">
                                    <input type="checkbox" id="app" name="application_management" value="1" <?= $ap ?>>
                                    <span></span>
                                </label>
                                <label>Application Management</label>
                            </div>
                        </div>
                    <?php
                }
                    ?>


                    </div>
                    <button class="btn btn-primary" name="btn_update_account">Update</button>
            </form>
        </div>
    </div>
    <div id="modal-change-password" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h2 class="modal-title"><i class="fa fa-lock"></i> Change Password</h2>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <form method="POST" class="form-horizontal form-bordered">
                            <input type="hidden" name="id" value="<?= $rid ?>">
                            <div class="form-group">
                                <label>New Password</label>
                                <input type="password" name="new_password" required minlength="6" class="form-control">
                            </div>
                            <button class="btn btn-primary btn-block" name="account_change_password">Update</button>
                            <br>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'inc/page_footer.php'; ?>
    <?php include 'inc/template_scripts.php'; ?>
    <?php include 'inc/template_end.php'; ?>
    <script src="js/pages/tablesDatatables.js"></script>
    <script>
        $(function() {
            TablesDatatables.init();
            if ($('[name="role"]').val() == "Admin") {
                $('input:checkbox').prop('checked', true);
            }
        });
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
                $('#payslip').prop('checked', true);
                $('#app').prop('checked', true);
                $('#payroll').prop('checked', true);
            } else if ($(this).val() == "Manager" || $(this).val() == "HR Processing" || $(this).val() == "Group Head") {
                $('#201').prop('checked', false);
                $('#cert').prop('checked', true);
                $('#leave').prop('checked', true);
                $('#holiday').prop('checked', false);
                $('#generate').prop('checked', false);
                $('#payslip').prop('checked', true);
                $('#app').prop('checked', true);
                $('#payroll').prop('checked', false);
            } else {
                $('#201').prop('checked', false);
                $('#cert').prop('checked', true);
                $('#leave').prop('checked', true);
                $('#holiday').prop('checked', false);
                $('#generate').prop('checked', false);
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