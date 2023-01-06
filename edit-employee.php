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
            <li class="active">
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
            <li>
                <a href="edit-employee-employment?<?= md5('id') . '=' . $fid ?>"><i class="fa fa-briefcase"></i> Employment Information</a>
            </li>
            <li>
                <a href="edit-employee-documents?<?= md5('id') . '=' . $fid ?>"><i class="fa fa-file"></i> Documents</a>
            </li>
            <li>
                <a href="edit-employee-benefits?<?= md5('id') . '=' . $fid ?>"><i class="fa fa-exchange"></i> Benefits</a>
            </li>
            <li>
                <a href="edit-employee-balances?<?= md5('id') . '=' . $fid ?>"><i class="gi gi-wallet"></i> Balances</a>
            </li>
            <li>
                <a href="edit-employee-position?<?= md5('id') . '=' . $fid ?>"><i class="fa fa-database"></i> Position History</a>
            </li>
        </ul>
    </div>
    <div class="block full">
        <div class="block-title">
            <h2><strong>Personal</strong> Information</h2>
        </div>
        <?= $res ?>
        <div class="container-fluid">
            <form method="POST">
                <?php
                $sql = mysqli_query($db, "SELECT * FROM tbl_personal_information WHERE employee_number = '$employee_number'");
                while ($row = mysqli_fetch_assoc($sql)) {
                    $em = $row['company_email'];
                ?>
                    <input type="hidden" name="employee_number" value="<?= $row['employee_number'] ?>">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Employee Number</label>
                                    <input type="text" readonly class="form-control" value="<?= $row['employee_number'] ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Account Name</label>
                                    <input type="text" id="acc_name" name="account_name" readonly class="form-control" value="<?= $row['account_name'] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Company Email</label>
                                    <input type="email" id="company_email" name="company_email" class="form-control" value="<?= $row['company_email'] ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Date of Birth</label>
                                    <input type="date" class="form-control" name="date_of_birth" value="<?= $row['date_of_birth'] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Last Name</label>
                                    <input type="text" id="lname" name="last_name" class="form-control" value="<?= $row['last_name'] ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Age</label>
                                    <input type="number" class="form-control" id="age" readonly value="<?= $row['age'] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>First Name</label>
                                    <input type="text" id="fname" name="first_name" class="form-control" value="<?= $row['first_name'] ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Gender</label>
                                    <select name="gender" class="select-chosen" data-placeholder="Choose a gender..." style="width: 250px;">
                                        <option></option>
                                        <?php
                                        $genders = array('Male', 'Female');
                                        foreach ($genders as $k => $v) {
                                            if ($v == $row['gender']) {
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
                                    <label>Middle Name</label>
                                    <input type="text" id="mname" name="middle_name" class="form-control" value="<?= $row['middle_name'] ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Citizenship</label>
                                    <input type="text" name="citizenship" class="form-control" value="<?= $row['citizenship'] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Address</label>
                                    <textarea name="address" rows="6" class="form-control"><?= $row['address'] ?></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Civil Status</label>
                                    <select name="civil_status" class="select-chosen" data-placeholder="Choose a civil status..." style="width: 250px;">
                                        <option></option>
                                        <?php
                                        $civil = array('Single', 'Married', 'Separated', 'Widowed', 'Divorced');
                                        foreach ($civil as $k => $v) {
                                            if ($v == $row['civil_status']) {
                                                echo '<option selected>' . $v . '</option>';
                                            } else {
                                                echo '<option>' . $v . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Spouse Name</label>
                                    <input type="text" name="spouse_name" class="form-control" value="<?= $row['spouse_name'] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Personal Email</label>
                                    <input type="email" name="personal_email" class="form-control" value="<?= $row['personal_email'] ?>">
                                </div>
                                <div class="form-group">
                                    <label>Contact Number</label>
                                    <input type="text" name="contact_number" class="form-control" value="<?= $row['contact_number'] ?>">
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary" name="btn_update_personal_info">Update</button>
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