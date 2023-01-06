<?php

/**
 * config
 *
 * Author: pixelcave
 *
 * Configuration file. It contains variables used in the template as well as the primary navigation array from which the navigation is created
 *
 */

//$db = mysqli_connect('localhost', 'root', 'root', 'hrisv2', '8889');
$db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
date_default_timezone_set('Asia/Manila');
$datetime = date('Y-m-d H:i:s', time());
session_start();
ob_start();

$res = '';

function getToken($length)
{
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet .= "0123456789";
    $max = strlen($codeAlphabet);
    for ($i = 0; $i < $length; $i++) {
        $token .= $codeAlphabet[random_int(0, $max - 1)];
    }
    return $token;
}
if (isset($_POST['btn_login'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $sql = mysqli_query($db, "SELECT t.*, t1.employee_number 
    FROM tbl_users t
    INNER JOIN tbl_personal_information t1
    ON t.email = t1.company_email
    WHERE t.email = '$email' AND t.`password` = '$password'
    ");
    if (mysqli_num_rows($sql) > 0) {
        $row = mysqli_fetch_assoc($sql);
        $_SESSION['hris_role'] = $row['role'];
        $_SESSION['hris_id'] = $row['ID'];
        $_SESSION['hris_email'] = $row['email'];
        $_SESSION['hris_company_id'] = $row['company_id'];
        $_SESSION['hris_account_name'] = $row['account_name'];
        $_SESSION['hris_employee_number'] = $row['employee_number'];
        if (isset($_POST['remember_me'])) {
            if ($_POST["remember_me"] == '1' || $_POST["remember_me"] == 'on') {
                $hour = time() + 3600 * 24 * 30;
                setcookie('email', $email, $hour);
                setcookie('password', $password, $hour);
            }
        }
        header('Location: index');
    } else {
        $sql1 = mysqli_query($db, "SELECT * FROM tbl_users WHERE email = '$email' AND `password` = '$password'");
        if (mysqli_num_rows($sql1) > 0) {
            $row1 = mysqli_fetch_assoc($sql1);
            $_SESSION['hris_id'] = $row1['ID'];
            $_SESSION['hris_email'] = $row1['email'];
            $_SESSION['hris_account_name'] = $row1['account_name'];
            $_SESSION['hris_role'] = $row1['role'];

            if (isset($_POST['remember_me'])) {
                if ($_POST["remember_me"] == '1' || $_POST["remember_me"] == 'on') {
                    $hour = time() + 3600 * 24 * 30;
                    setcookie('email', $email, $hour);
                    setcookie('password', $password, $hour);
                }
            }
            if ($row1['role'] == "Processor") {
                $cid = explode(',', $row1['company_id']);
                $_SESSION['hris_company_id'] = $cid[0];
                header('Location: pending-tasks');
            } else {
                $_SESSION['hris_company_id'] = $row1['company_id'];
            }
            header('Location: index');
        } else {
            $res = '<div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-times"></i> Email or Password is incorrect.</h4>
        </div>
      ';
        }
    }
    mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$email','Logged in to the system','$datetime')");
}
if (isset($_POST['add_company'])) {
    $company_name = $_POST['company_name'];
    if (check_if_company_exist($company_name) == true) {
        $res = '<div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-times"></i> Submission failed. Company already exist.</h4>
        </div>
      ';
    } else {
        $sql = mysqli_query($db, "INSERT INTO tbl_companies VALUES('','$company_name','$datetime','0')");
        $res = '<div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="fa fa-check-circle"></i> ' . $company_name . ' has been added as Company</h4>
        </div>';
        $qid = mysqli_insert_id($db);
        mysqli_query($db, "INSERT INTO tbl_company_benefits VALUES('','$qid','0','0','0','0','0','0','0','0','0','0')");
        mysqli_query($db, "INSERT INTO tbl_loan_max_value VALUES('','$qid','0','0','$datetime')");
        mysqli_query($db, "INSERT INTO tbl_maintenance VALUES('','$qid','default-logo.png','default-banner.png', '$company_name','$datetime')");
    }
    $at_name = $_SESSION['hris_account_name'];
    mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Added a Company: $company_name','$datetime')");
}
if (isset($_POST['add_department'])) {
    $department = $_POST['department'];
    $company_id = $_POST['company_id'];
    if (check_if_department_exist($department, $company_id) == true) {
        $res = '<div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-times"></i> Submission failed. Department already exist.</h4>
        </div>
      ';
    } else {
        $sql = mysqli_query($db, "INSERT INTO tbl_departments VALUES('','$department','$company_id','$datetime')");
        $res = '<div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="fa fa-check-circle"></i> ' . $department . ' has been added as Department</h4>
        </div>';
    }
    $at_name = $_SESSION['hris_account_name'];
    mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Added a department: $department','$datetime')");
}
if (isset($_POST['get_department_details'])) {
    $department_id = $_POST['department_id'];
    $company_id = $_POST['company_id'];
    $department_name = $_POST['department_name'];

    echo '<div class="modal-header text-center">
    <h2 class="modal-title"><i class="fa fa-sitemap"></i> Update Department</h2>
    </div>
    <div class="modal-body">
        <form method="POST" enctype="multipart/form-data" class="form-horizontal form-bordered">
            <input type="hidden" name="department_id" value="' . $department_id . '">
            <input type="hidden" name="company_id" value="' . $company_id . '">
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <input type="text" name="department" required class="form-control" placeholder="Enter Department Name..." value="' . $department_name . '">
                        <span class="input-group-btn">
                            <button name="update_department" class="btn btn-primary">Update</button>
                        </span>
                    </div>
                </div>
            </div>
        </form>
    </div>';
}
if (isset($_POST['update_department'])) {
    $department_id = $_POST['department_id'];
    $company_id = $_POST['company_id'];
    $department = $_POST['department'];

    if (check_if_department_exist($department, $company_id) == true) {
        $res = '<div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-times"></i> Update failed. Department already exist.</h4>
        </div>
      ';
    } else {
        $sql = mysqli_query($db, "UPDATE tbl_departments SET department = '$department' WHERE ID = '$department_id'");
        $res = '<div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="fa fa-check-circle"></i> ' . $department . ' has been updated.</h4>
        </div>';

        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','updated a department: $department_id','$datetime')");
    }
}
if (isset($_POST['add_job_grade'])) {
    $company_id = $_POST['company_id'];
    $job_grade = $_POST['job_grade'];

    if (check_if_job_grade_exist($job_grade, $company_id) == true) {
        $res = '<div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-times"></i> Submission failed. Job Grade already exist.</h4>
        </div>
      ';
    } else {
        $sql = mysqli_query($db, "INSERT INTO tbl_job_grade VALUES('','$company_id','$job_grade','$datetime')");
        $res = '<div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="fa fa-check-circle"></i> ' . $job_grade . ' has been added as Job Grade</h4>
        </div>';

        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Added a job grade: $job_grade','$datetime')");
    }
}
if (isset($_POST['get_job_grade_details'])) {
    $job_grade_id = $_POST['job_grade_id'];
    $company_id = $_POST['company_id'];
    $job_grade = $_POST['job_grade'];

    echo '<div class="modal-header text-center">
    <h2 class="modal-title"><i class="fa fa-square"></i> Update Job Grade</h2>
    </div>
    <div class="modal-body">
        <form method="POST" enctype="multipart/form-data" class="form-horizontal form-bordered">
            <input type="hidden" name="job_grade_id" value="' . $job_grade_id . '">
            <input type="hidden" name="company_id" value="' . $company_id . '">
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <input type="text" name="job_grade" required class="form-control" placeholder="Enter Job Grade Name..." value="' . $job_grade . '">
                        <span class="input-group-btn">
                            <button name="update_job_grade" class="btn btn-primary">Update</button>
                        </span>
                    </div>
                </div>
            </div>
        </form>
    </div>';
}
if (isset($_POST['update_job_grade'])) {
    $job_grade_id = $_POST['job_grade_id'];
    $company_id = $_POST['company_id'];
    $job_grade = $_POST['job_grade'];

    if (check_if_job_grade_exist($job_grade, $company_id) == true) {
        $res = '<div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="fa fa-times"></i> Update failed. Job Grade already exist.</h4>
            </div>
          ';
    } else {
        $sql = mysqli_query($db, "UPDATE tbl_job_grade SET job_grade = '$job_grade' WHERE ID = '$job_grade_id'");
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check-circle"></i> ' . $job_grade . ' has been updated.</h4>
            </div>';

        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Updated a job grade: $job_grade_id','$datetime')");
    }
}
if (isset($_POST['add_job_grade_set'])) {
    $company_id = $_POST['company_id'];
    $job_grade_set = $_POST['job_grade_set'];

    if (check_if_job_grade_set_exist($job_grade_set, $company_id) == true) {
        $res = '<div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-times"></i> Submission failed. Job Grade Set already exist.</h4>
        </div>
      ';
    } else {
        $sql = mysqli_query($db, "INSERT INTO tbl_job_grade_set VALUES('','$company_id','$job_grade_set','$datetime')");
        $res = '<div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="fa fa-check-circle"></i> ' . $job_grade_set . ' has been added as Job Grade Set</h4>
        </div>';

        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Added a Job grade set: $job_grade_set','$datetime')");
    }
}
function check_if_job_grade_set_exist($job_grade_set, $company_id)
{
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
    $sql = mysqli_query($db, "SELECT * FROM tbl_job_grade_set WHERE job_grade_set = '$job_grade_set' AND company_id = '$company_id'");
    if (mysqli_num_rows($sql) > 0) {
        return true;
    }
}
if (isset($_POST['get_job_grade_set_details'])) {
    $job_grade_set_id = $_POST['job_grade_set_id'];
    $company_id = $_POST['company_id'];
    $job_grade_set = $_POST['job_grade_set'];

    echo '<div class="modal-header text-center">
    <h2 class="modal-title"><i class="gi gi-show_thumbnails"></i> Update Job Grade Set</h2>
    </div>
    <div class="modal-body">
        <form method="POST" enctype="multipart/form-data" class="form-horizontal form-bordered">
            <input type="hidden" name="job_grade_set_id" value="' . $job_grade_set_id . '">
            <input type="hidden" name="company_id" value="' . $company_id . '">
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <input type="text" name="job_grade_set" required class="form-control" placeholder="Enter Job Grade Set..." value="' . $job_grade_set . '">
                        <span class="input-group-btn">
                            <button name="update_job_grade_set" class="btn btn-primary">Update</button>
                        </span>
                    </div>
                </div>
            </div>
        </form>
    </div>';
}
if (isset($_POST['update_job_grade_set'])) {
    $job_grade_set_id = $_POST['job_grade_set_id'];
    $company_id = $_POST['company_id'];
    $job_grade_set = $_POST['job_grade_set'];

    if (check_if_job_grade_set_exist($job_grade_set, $company_id) == true) {
        $res = '<div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="fa fa-times"></i> Update failed. Job Grade Set already exist.</h4>
            </div>
          ';
    } else {
        $sql = mysqli_query($db, "UPDATE tbl_job_grade_set SET job_grade_set = '$job_grade_set' WHERE ID = '$job_grade_set_id'");
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check-circle"></i> ' . $job_grade_set . ' has been updated.</h4>
            </div>';

        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Updated a Job grade set: $job_grade_set_id','$datetime')");
    }
}
if (isset($_POST['btn_benefits_eligibility'])) {
    $company_id = $_POST['company_id'];
    $benefits_id = $_POST['benefits_id'];
    $parking = '0';
    $gasoline = '0';
    $car_maintenance = '0';
    $medicine = '0';
    $gym = '0';
    $optical_allowance = '0';
    $cep = '0';
    $club_membership = '0';
    $maternity = '0';
    $others = '0';
    if (isset($_POST['parking'])) {
        $parking = '1';
    }
    if (isset($_POST['gasoline'])) {
        $gasoline = '1';
    }
    if (isset($_POST['car_maintenance'])) {
        $car_maintenance = '1';
    }
    if (isset($_POST['medicine'])) {
        $medicine = '1';
    }
    if (isset($_POST['gym'])) {
        $gym = '1';
    }
    if (isset($_POST['optical_allowance'])) {
        $optical_allowance = '1';
    }
    if (isset($_POST['cep'])) {
        $cep = '1';
    }
    if (isset($_POST['club_membership'])) {
        $club_membership = '1';
    }
    if (isset($_POST['maternity'])) {
        $maternity = '1';
    }
    if (isset($_POST['others'])) {
        $others = '1';
    }

    $sql = mysqli_query($db, "UPDATE tbl_company_benefits SET
        parking = '$parking',
        gasoline = '$gasoline',
        car_maintenance = '$car_maintenance',
        medicine = '$medicine',
        gym = '$gym',
        optical_allowance = '$optical_allowance',
        cep = '$cep',
        club_membership = '$club_membership',
        maternity = '$maternity',
        others = '$others'
        WHERE ID = '$benefits_id'
    ");
    if ($sql) {
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check-circle"></i> Benefits Eligibility has been updated.</h4>
            </div>';

        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Updated a Benefits Eligibility for company ID: $company_id','$datetime')");
    }
}
if (isset($_POST['btn_maintenance'])) {
    $company_id = $_POST['company_id'];
    $prefix = $_POST['prefix'];

    if ($_FILES['logo']['name'] != "") {
        $logo = $_FILES['logo']['name'];
        $logo_tmp = $_FILES['logo']['tmp_name'];
        $logo = md5($logo);
        mysqli_query($db, "UPDATE tbl_maintenance SET logo = '$logo' WHERE company_id = '$company_id'");
        move_uploaded_file($logo_tmp, "uploads/" . $logo);
    }
    if ($_FILES['banner']['name'] != "") {
        $banner = $_FILES['banner']['name'];
        $banner_tmp = $_FILES['banner']['tmp_name'];
        $banner = md5($banner);
        mysqli_query($db, "UPDATE tbl_maintenance SET banner = '$banner' WHERE company_id = '$company_id'");
        move_uploaded_file($banner_tmp, "uploads/" . $banner);
    }
    mysqli_query($db, "UPDATE tbl_maintenance SET prefix = '$prefix' WHERE company_id = '$company_id'");

    $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check-circle"></i> Company Maintenance has been updated.</h4>
            </div>';
    $at_name = $_SESSION['hris_account_name'];
    mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Updated company maintenance for company ID: $company_id','$datetime')");
}
if (isset($_POST['btn_maintenance_loan'])) {
    $company_id = $_POST['company_id'];
    $loan_max_value = $_POST['loan_max_value'];
    $loan_others_max_value = $_POST['loan_others_max_value'];
    $sql = mysqli_query($db, "UPDATE tbl_loan_max_value SET max_value = '$loan_max_value', others_max_value = '$loan_others_max_value' WHERE company_id = '$company_id'");

    if ($sql) {
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Updated Loan Max Values for company ID: $company_id','$datetime')");
        $res = '<div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="fa fa-check-circle"></i> Loan Max Values has been updated.</h4>
        </div>';
    }
}
if (isset($_POST['onboarding_validate_email'])) {
    $email = $_POST['onboarding_validate_email'];
    $sql = mysqli_query($db, "SELECT * FROM tbl_personal_information WHERE company_email = '$email'");
    if (mysqli_num_rows($sql) > 0) {
        echo "1";
    }
}
if (isset($_POST['btn_onboarding'])) {
    // Personal Info
    $employee_number = 'xxx-xxx';
    $account_name = $_POST['account_name'];
    $company_email = $_POST['company_email'];
    $date_of_birth = $_POST['date_of_birth'];
    $last_name = $_POST['last_name'];
    $first_name = $_POST['first_name'];
    $gender = $_POST['gender'];
    $middle_name = $_POST['middle_name'];
    $citizenship = $_POST['citizenship'];
    $address = $_POST['address'];
    $civil_status = $_POST['civil_status'];
    $spouse_name = $_POST['spouse_name'];
    $personal_email = $_POST['personal_email'];
    $contact_number = $_POST['contact_number'];

    // Get Age
    $bday = date('m-d-Y', strtotime($date_of_birth));
    $bday = explode("-", $bday);
    //get age from date or birthdate
    $age = (date("md", date("U", mktime(0, 0, 0, $bday[0], $bday[1], $bday[2]))) > date("md")
        ? ((date("Y") - $bday[2]) - 1)
        : (date("Y") - $bday[2]));

    // / personal info

    $insert_personal_info = mysqli_query($db, "INSERT INTO tbl_personal_information VALUES(
        '',
        '$employee_number',
        '$company_email',
        '$last_name',
        '$first_name',
        '$middle_name',
        '$address',
        '$personal_email',
        '$contact_number',
        '$account_name',
        '$date_of_birth',
        '$age',
        '$gender',
        '$citizenship',
        '$civil_status',
        '$spouse_name',
        '$datetime'
        )");
    if ($insert_personal_info) {
        $employee_number = mysqli_insert_id($db);
        $employee_number =  sprintf("%04d", $employee_number);
        $update_emp_num = mysqli_query($db, "UPDATE tbl_personal_information 
        SET employee_number = '$employee_number' 
        WHERE employee_number = 'xxx-xxx'");
    }
    $employee_number =  sprintf("%04d", $employee_number);
    // Post graduate
    $post_graduate_school = $_POST['post_graduate_school'];
    $from_pgs = $_POST['from_pgs'];
    $to_pgs = $_POST['to_pgs'];
    mysqli_query($db, "INSERT INTO tbl_post_graduate VALUES('','$employee_number','$post_graduate_school','$from_pgs','$to_pgs')");

    // University / College
    // Array variables
    $universitycollege = $_POST['universitycollege'];
    $from_uc = $_POST['from_uc'];
    $to_uc = $_POST['to_uc'];
    $degree = $_POST['degree'];

    foreach ($universitycollege as $k => $v) {
        mysqli_query($db, "INSERT INTO tbl_college VALUES('','$employee_number','$v','$from_uc[$k]','$to_uc[$k]','$degree[$k]')");
    }

    // Emergency Contacts
    $contact_name = $_POST['contact_name'];
    $ec_contact_number = $_POST['ec_contact_number'];
    $ec_email = $_POST['ec_email'];
    $ec_relationship = $_POST['ec_relationship'];
    foreach ($contact_name as $k => $v) {
        mysqli_query($db, "INSERT INTO tbl_emergency_contacts VALUES('','$employee_number','$v','$ec_contact_number[$k]','$ec_email[$k]','$ec_relationship[$k]')");
    }

    // Gov IDs
    $sss = $_POST['sss'];
    $pagibig = $_POST['pagibig'];
    $philhealth = $_POST['philhealth'];
    $tin = $_POST['tin'];
    mysqli_query($db, "INSERT INTO tbl_government_id VALUES('','$employee_number','$sss','$pagibig','$philhealth', '$tin')");

    $id_name = $_POST['id_name'];
    $id_number = $_POST['id_number'];
    foreach ($id_name as $k => $v) {
        mysqli_query($db, "INSERT INTO tbl_ids VALUES('','$employee_number','$v','$id_number[$k]')");
    }

    // Employment info
    $position_number = 'PN-' . $employee_number;
    $position_title = $_POST['position_title'];
    $job_description = $_POST['job_description'];
    $date_hired = $_POST['date_hired'];
    $company = $_POST['company'];
    $department = $_POST['department'];
    $job_grade_set = $_POST['job_grade_set'];
    $employment_status = $_POST['employment_status'];
    $job_grade = $_POST['job_grade'];
    $approver = $_POST['approver'];
    $account_status = $_POST['account_status'];
    $reporting_to = $_POST['reporting_to'];
    $vendor_id = $_POST['vendor_id'];
    $on_behalf_filing = '0';
    if (isset($_POST['on_behalf_filing'])) {
        $on_behalf_filing = $_POST['on_behalf_filing'];
    }
    $is_approver = '0';
    if (isset($_POST['is_approver'])) {
        $is_approver = $_POST['is_approver'];
    }
    mysqli_query($db, "INSERT INTO tbl_employment_information VALUES(
        '',
        '$employee_number',
        '$position_number',
        '$position_title',
        '$job_description',
        '$date_hired',
        '$company',
        '$department',
        '$job_grade_set',
        '$job_grade',
        '$employment_status',
        '$account_status',
        '$approver',
        '$reporting_to',
        '$vendor_id',
        '$on_behalf_filing',
        '$is_approver'
        );");

    // Supporting Documents
    $attachment = $_FILES['attachment']['name']; // array
    $attachment_tmp = $_FILES['attachment']['tmp_name']; // array
    $attachment_remarks = $_POST['attachment_remarks']; // array
    foreach ($attachment as $k => $v) {
        $value = md5($v);
        mysqli_query($db, "INSERT INTO tbl_documents VALUES('','$employee_number','$value','$attachment_remarks[$k]')");
        move_uploaded_file($attachment_tmp[$k], "uploads/" . $value);
    }

    // Benefits Eligibility
    $parking = "0";
    $gasoline = "0";
    $car_maintenance = "0";
    $medicine = "0";
    $gym = "0";
    $optical_allowance = "0";
    $cep = "0";
    $club_membership = "0";
    $maternity = "0";
    $others = "0";
    if (isset($_POST['benefits_eligibility'])) {
        $benefits_eligibility = $_POST['benefits_eligibility'];
        foreach ($benefits_eligibility as $k => $v) {
            if ($v == "Parking") {
                $parking = "1";
            }
            if ($v == "Gasoline") {
                $gasoline = "1";
            }
            if ($v == "Car Maintenance") {
                $car_maintenance = "1";
            }
            if ($v == "Medicine") {
                $medicine = "1";
            }
            if ($v == "Gym") {
                $gym = "1";
            }
            if ($v == "Optical Allowance") {
                $optical_allowance = "1";
            }
            if ($v == "CEP") {
                $cep = "1";
            }
            if ($v == "Club Membership") {
                $club_membership = "1";
            }
            if ($v == "Maternity") {
                $maternity = "1";
            }
            if ($v == "Others") {
                $others = "1";
            }
        }
        mysqli_query($db, "INSERT INTO tbl_benefits_eligibility VALUES(
            '',
            '$employee_number',
            '$parking', 
            '$gasoline',
            '$car_maintenance',
            '$medicine',
            '$gym',
            '$optical_allowance',
            '$cep',
            '$club_membership',
            '$maternity',
            '$others')");
    }
    // Leave Balances
    mysqli_query($db, "INSERT INTO tbl_leave_balances VALUES(
        '',
        '$employee_number',
        '0',
        '0',
        '5',
        '0',
        '7',
        '5',
        '7',
        '60',
        '0',
        '0',
        '0',
        '0',
        '0',
        '0',
        '0',
        '105',
        '60',
        '7')");

    // Benefits Balances

    // Gas Balance
    mysqli_query($db, "INSERT INTO tbl_benefits_gas_balance VALUES('', '$employee_number', '0', '0')");

    // Car Maintenance
    mysqli_query($db, "INSERT INTO tbl_benefits_car_balance VALUES('', '$employee_number', '0', '0')");

    // Medical Maintenance
    mysqli_query($db, "INSERT INTO tbl_benefits_medical_balance VALUES('', '$employee_number', '0', '0')");

    // Gym maintenance
    mysqli_query($db, "INSERT INTO tbl_benefits_gym_balance VALUES('', '$employee_number', '0', '0')");

    // Optical maintenance
    mysqli_query($db, "INSERT INTO tbl_benefits_optical_balance VALUES('', '$employee_number', '0', '0')");

    // CEP maintenance
    mysqli_query($db, "INSERT INTO tbl_benefits_cep_balance VALUES('', '$employee_number', '0', '0')");

    $at_name = $_SESSION['hris_account_name'];
    mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Onboarded an employee: $account_name','$datetime')");
}
if (isset($_POST['onboarding_get_departments'])) {
    $company_id = $_POST['onboarding_get_departments'];
    $departments = get_departments($company_id);
    foreach ($departments as $k => $v) {
        echo '<option value="' . $v['ID'] . '">' . $v['department'] . '</option>';
    }
}
if (isset($_POST['btn_update_personal_info'])) {
    $employee_number = $_POST['employee_number'];
    $account_name = $_POST['account_name'];
    $company_email = $_POST['company_email'];
    $date_of_birth = $_POST['date_of_birth'];
    $last_name = $_POST['last_name'];
    $first_name = $_POST['first_name'];
    $gender = $_POST['gender'];
    $middle_name = $_POST['middle_name'];
    $citizenship = $_POST['citizenship'];
    $address = $_POST['address'];
    $spouse_name = $_POST['spouse_name'];
    $personal_email = $_POST['personal_email'];
    $contact_number = $_POST['contact_number'];

    // Get Age
    $bday = date('m-d-Y', strtotime($date_of_birth));
    $bday = explode("-", $bday);
    //get age from date or birthdate
    $age = (date("md", date("U", mktime(0, 0, 0, $bday[0], $bday[1], $bday[2]))) > date("md")
        ? ((date("Y") - $bday[2]) - 1)
        : (date("Y") - $bday[2]));

    $sql = mysqli_query($db, "UPDATE tbl_personal_information SET
        account_name = '$account_name',
        company_email = '$company_email',
        date_of_birth = '$date_of_birth',
        last_name = '$last_name',
        first_name = '$first_name',
        gender = '$gender',
        middle_name = '$middle_name',
        citizenship = '$citizenship',
        `address` = '$address',
        spouse_name = '$spouse_name',
        personal_email = '$personal_email',
        contact_number = '$contact_number',
        age = '$age'
        WHERE employee_number = '$employee_number'
    ");
    if ($sql) {
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> Personal information has been updated.</h4>
        </div>';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Updated Personal Information for employee number: $employee_number','$datetime')");
    }
}
if (isset($_POST['btn_update_post_graduate'])) {
    $employee_number = $_POST['employee_number'];
    $post_graduate_school = $_POST['post_graduate_school'];
    $from_pgs = $_POST['from_pgs'];
    $to_pgs = $_POST['to_pgs'];

    $sql = mysqli_query($db, "UPDATE tbl_post_graduate SET 
    school = '$post_graduate_school',
    from_date = '$from_pgs',
    to_date = '$to_pgs' 
    WHERE employee_number = '$employee_number'");
    if ($sql) {
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> Post Graduate School has been updated.</h4>
        </div>';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Updated Post Graduate information for employee number: $employee_number','$datetime')");
    }
}
if (isset($_POST['add_college'])) {
    $employee_number = $_POST['employee_number'];
    $college = $_POST['college'];
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
    $degree = $_POST['degree'];

    $sql = mysqli_query($db, "INSERT INTO tbl_college VALUES('','$employee_number','$college','$from_date','$to_date','$degree')");
    if ($sql) {
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> ' . $college . ' has been added.</h4>
        </div>';

        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Added an education for employee number: $employee_number','$datetime')");
    }
}
if (isset($_POST['get_educ_details'])) {
    $id = $_POST['id'];
    $college = $_POST['college'];
    $degree = $_POST['degree'];
    $from = $_POST['from'];
    $to = $_POST['to'];

    echo '
    <form method="POST" class="form-horizontal form-bordered">
                    <input type="hidden" name="id" value="' . $id . '">
                    <div class="container-fluid">
                        <div class="form-group">
                            <label>University / College Name</label>
                            <input type="text" name="college" required class="form-control" value="' . $college . '">
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>From</label>
                                    <input type="text" name="from_date" required class="form-control" value="' . $from . '">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>To</label>
                                    <input type="text" name="to_date" required class="form-control" value="' . $to . '">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Degree</label>
                            <input type="text" name="degree" required class="form-control" value="' . $degree . '">
                        </div>
                        <br>
                        <div style="float:right">
                        <button class="btn btn-secondary" name="btn_delete_college">Delete</button>
                        <button class="btn btn-primary" name="btn_update_college">Update</button>
                        </div>
                        <br><br><br>
                    </div>
                </form>
    ';
}
if (isset($_POST['btn_update_college'])) {
    $id = $_POST['id'];
    $college = $_POST['college'];
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
    $degree = $_POST['degree'];

    $sql = mysqli_query($db, "UPDATE tbl_college SET
    college = '$college',
    from_date = '$from_date',
    to_date = '$to_date',
    degree = '$degree'
    WHERE ID = '$id'
    ");
    if ($sql) {
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> University / College has been updated.</h4>
        </div>';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Updated Educational info ID: $id','$datetime')");
    }
}
if (isset($_POST['btn_delete_college'])) {
    $id = $_POST['id'];
    $college = $_POST['college'];
    $sql = mysqli_query($db, "DELETE FROM tbl_college WHERE ID = '$id'");
    if ($sql) {
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-trash"></i> ' . $college . ' has been deleted.</h4>
        </div>';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Deleted Education info ID: $id','$datetime')");
    }
}
if (isset($_POST['btn_add_emergency_contact'])) {
    $employee_number = $_POST['employee_number'];
    $contact_name = $_POST['contact_name'];
    $contact_number = $_POST['contact_number'];
    $email = $_POST['email'];
    $relationship = $_POST['relationship'];

    $sql = mysqli_query($db, "INSERT INTO tbl_emergency_contacts VALUES('','$employee_number','$contact_name','$contact_number','$email','$relationship')");
    if ($sql) {
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> ' . $contact_name . ' has been added as Emergency contact.</h4>
        </div>';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Added an emergency contact for employee number: $employee_number','$datetime')");
    }
}
if (isset($_POST['get_contact_details'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $number = $_POST['number'];
    $email = $_POST['email'];
    $relationship = $_POST['relationship'];

    echo '
    <div class="container-fluid">
                    <form method="POST" class="form-horizontal form-bordered">
                        <input type="hidden" name="id" value="' . $id . '">
                        <div class="form-group">
                            <label>Contact Name</label>
                            <input type="text" name="contact_name" class="form-control" value="' . $name . '">
                        </div>
                        <div class="form-group">
                            <label>Contact Number</label>
                            <input type="text" name="contact_number" class="form-control" value="' . $number . '">
                        </div>
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="text" name="email" class="form-control" value="' . $email . '">
                        </div>
                        <div class="form-group">
                            <label>Relationship</label>
                            <input type="text" name="relationship" class="form-control" value="' . $relationship . '">
                        </div>
                        <br>
                        <div style="float:right">
                        <button class="btn btn-secondary" name="btn_delete_emergency_contact">Delete</button>
                        <button class="btn btn-primary" name="btn_update_emergency_contact">Update</button>
                        </div>
                        <br><br><br>
                    </form>
                </div>
    ';
}
if (isset($_POST['btn_update_emergency_contact'])) {
    $id = $_POST['id'];
    $contact_name = $_POST['contact_name'];
    $contact_number = $_POST['contact_number'];
    $email = $_POST['email'];
    $relationship = $_POST['relationship'];

    $sql = mysqli_query($db, "UPDATE tbl_emergency_contacts SET 
    contact_name = '$contact_name',
    contact_number = '$contact_number',
    email_address = '$email',
    relationship = '$relationship'
    WHERE ID = '$id'
    ");
    if ($sql) {
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> Emergency contact has been updated.</h4>
        </div>';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Updated an emergency contact: $contact_name','$datetime')");
    }
}
if (isset($_POST['btn_delete_emergency_contact'])) {
    $id = $_POST['id'];
    $contact_name = $_POST['contact_name'];
    $sql = mysqli_query($db, "DELETE FROM tbl_emergency_contacts WHERE ID = '$id'");
    if ($sql) {
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-trash"></i> ' . $contact_name . ' has been deleted as Emergency contact.</h4>
        </div>';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Deleted an emergency contact: $contact_name','$datetime')");
    }
}
if (isset($_POST['btn_update_ids'])) {
    $employee_number = $_POST['employee_number'];
    $sss = $_POST['sss'];
    $pagibig = $_POST['pagibig'];
    $philhealth = $_POST['philhealth'];
    $tin = $_POST['tin'];

    $sql = mysqli_query($db, "UPDATE tbl_government_id SET
        sss = '$sss',
        pagibig = '$pagibig',
        philhealth = '$philhealth',
        tin = '$tin'
        WHERE employee_number = '$employee_number'
    ");
    if ($sql) {
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> Government IDs has been updated.</h4>
        </div>';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Updated government IDs for employee number: $employee_number','$datetime')");
    }
}
if (isset($_POST['btn_add_id'])) {
    $employee_number = $_POST['employee_number'];
    $id_name = str_replace("'", '', $_POST['id_name']);
    $id_number = $_POST['id_number'];

    $sql = mysqli_query($db, "INSERT INTO tbl_ids VALUES('','$employee_number','$id_name','$id_number')");
    if ($sql) {
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> ' . $id_name . ' has been updated.</h4>
        </div>';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Added an ID for employee number: $employee_number','$datetime')");
    }
}
if (isset($_POST['get_id_details'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $number = $_POST['number'];

    echo '
    <form method="POST" class="form-horizontal form-bordered">
                    <input type="hidden" name="id" value="' . $id . '">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>ID Name</label>
                                    <input type="text" name="id_name" class="form-control" value="' . $name . '">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>ID Number</label>
                                    <input type="text" name="id_number" class="form-control" value="' . $number . '">
                                </div>
                            </div>
                        </div>
                        <br>
                        <div style="float:right">
                        <button class="btn btn-secondary" name="btn_delete_id">Delete</button>
                        <button class="btn btn-primary" style="float:right" name="btn_update_id">Update</button>
                        </div>
                        <br><br><br>
                    </div>
                </form>
    ';
}
if (isset($_POST['btn_update_id'])) {
    $id = $_POST['id'];
    $id_name = str_replace("'", '', $_POST['id_name']);
    $id_number = $_POST['id_number'];

    $sql = mysqli_query($db, "UPDATE tbl_ids SET id_name = '$id_name', id_number = '$id_number' WHERE ID = '$id'");
    if ($sql) {
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> ' . $id_name . ' has been updated.</h4>
        </div>';
    }
}
if (isset($_POST['btn_delete_id'])) {
    $id = $_POST['id'];
    $id_name = str_replace("'", '', $_POST['id_name']);

    $sql = mysqli_query($db, "DELETE FROM tbl_ids WHERE ID = '$id'");
    if ($sql) {
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-trash"></i> ' . $id_name . ' has been deleted.</h4>
        </div>';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Deleted an ID for employee number: $id_name','$datetime')");
    }
}
if (isset($_POST['btn_update_employment_info'])) {
    $employee_number = $_POST['employee_number'];
    $position_title = $_POST['position_title'];
    $date_hired = $_POST['date_hired'];
    $company = $_POST['company'];
    $department = $_POST['department'];
    $job_grade_set = $_POST['job_grade_set'];
    $employment_status = $_POST['employment_status'];
    $job_grade = $_POST['job_grade'];
    $approver = $_POST['approver'];
    $account_status = $_POST['account_status'];
    $reporting_to = $_POST['reporting_to'];
    $vendor_id = $_POST['vendor_id'];
    $on_behalf_filing = '0';
    if (isset($_POST['on_behalf_filing'])) {
        $on_behalf_filing = $_POST['on_behalf_filing'];
    }
    $is_approver = '0';
    if (isset($_POST['is_approver'])) {
        $is_approver = $_POST['is_approver'];
    }

    $old_position = $_POST['old_position'];

    $sql = mysqli_query($db, "UPDATE tbl_employment_information SET
        position_title = '$position_title',
        date_hired = '$date_hired',
        company = '$company',
        department = '$department',
        job_grade_set = '$job_grade_set',
        employment_status = '$employment_status',
        job_grade = '$job_grade',
        approver = '$approver',
        account_status = '$account_status',
        reporting_to = '$reporting_to',
        vendor_id = '$vendor_id',
        filing = '$on_behalf_filing',
        is_approver = '$is_approver'
        WHERE employee_number = '$employee_number'
    ");
    if ($sql) {
        if ($old_position != $job_grade) {
            mysqli_query($db, "INSERT INTO tbl_position_history VALUES('','$employee_number','$old_position','$job_grade','$datetime')");
        }
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> Employee Information has been updated.</h4>
        </div>';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Updated employment info for employee number: $employee_number','$datetime')");
    }
}
if (isset($_POST['btn_delete_document'])) {
    $id = $_POST['id'];
    $sql = mysqli_query($db, "DELETE FROM tbl_documents WHERE ID = '$id'");
    if ($sql) {
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-trash"></i> Document has been deleted.</h4>
        </div>';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Deleted a document','$datetime')");
    }
}
if (isset($_POST['btn_add_document'])) {
    $employee_number = $_POST['employee_number'];
    $attachment = $_FILES['attachment']['name'];
    $attachment = md5($attachment);
    $attachment_tmp = $_FILES['attachment']['tmp_name'];
    $attachment_remarks = $_POST['attachment_remarks'];

    $sql = mysqli_query($db, "INSERT INTO tbl_documents VALUES('','$employee_number','$attachment','$attachment_remarks')");
    if ($sql) {
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> Document has been added.</h4>
        </div>';
        move_uploaded_file($attachment_tmp, "uploads/" . $attachment);
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Added a document for employee number: $employee_number','$datetime')");
    }
}
if (isset($_POST['btn_update_benefits_eligibility'])) {
    // Benefits Eligibility
    $employee_number = $_POST['employee_number'];
    $parking = "0";
    $gasoline = "0";
    $car_maintenance = "0";
    $medicine = "0";
    $gym = "0";
    $optical_allowance = "0";
    $cep = "0";
    $club_membership = "0";
    $maternity = "0";
    $others = "0";
    if (isset($_POST['benefits_eligibility'])) {
        $benefits_eligibility = $_POST['benefits_eligibility'];
        foreach ($benefits_eligibility as $k => $v) {
            if ($v == "Parking") {
                $parking = "1";
            }
            if ($v == "Gasoline") {
                $gasoline = "1";
            }
            if ($v == "Car Maintenance") {
                $car_maintenance = "1";
            }
            if ($v == "Medicine") {
                $medicine = "1";
            }
            if ($v == "Gym") {
                $gym = "1";
            }
            if ($v == "Optical Allowance") {
                $optical_allowance = "1";
            }
            if ($v == "CEP") {
                $cep = "1";
            }
            if ($v == "Club Membership") {
                $club_membership = "1";
            }
            if ($v == "Maternity") {
                $maternity = "1";
            }
            if ($v == "Others") {
                $others = "1";
            }
        }
        $sql = mysqli_query($db, "UPDATE tbl_benefits_eligibility SET
        parking = '$parking',
        gasoline = '$gasoline',
        car_maintenance = '$car_maintenance',
        medicine = '$medicine',
        gym = '$gym',
        optical_allowance = '$optical_allowance',
        cep = '$cep',
        club_membership = '$club_membership',
        maternity = '$maternity',
        others = '$others'
        WHERE employee_number = '$employee_number'
        ");
        if ($sql) {
            $res = '<div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="fa fa-check"></i> Benefits Eligibility has been updated.</h4>
            </div>';
            $at_name = $_SESSION['hris_account_name'];
            mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Updated benefits eligibility info for employee number: $employee_number','$datetime')");
        }
    }
}
if (isset($_POST['compute_leave_duration'])) {
    $startDate1 = $_POST['startDate'];
    $endDate1 = $_POST['endDate'];
    $leave_type = $_POST['leave_type'];

    $startDate = new DateTime($startDate1);
    $endDate = new DateTime($endDate1);
    $endDate->modify('+1 day');
    $interval = $endDate->diff($startDate);
    $days = $interval->days;
    $period = new DatePeriod($startDate, new DateInterval('P1D'), $endDate);
    $holidays = array();

    // Get holidays
    $sql = mysqli_query($db, "SELECT * FROM tbl_holidays");
    while ($row = mysqli_fetch_assoc($sql)) {
        $holidays[] = $row['holiday_date'];
    }
    if ($leave_type != "MM") {
        if ($leave_type != "MNCS") {
            if ($leave_type != "MLWOP") {
                foreach ($period as $dt) {
                    $curr = $dt->format('D');
                    if ($curr == 'Sat' || $curr == 'Sun') {
                        $days--;
                    } elseif (in_array($dt->format('Y-m-d'), $holidays)) {
                        $days--;
                    }
                }
            }
        }
    }
    echo $days;
}
if (isset($_POST['select_leave_balances'])) {
    $leaves = array('SL', 'VL', 'EL', 'ML', 'MLWOP', 'PL', 'BL', 'SPL', 'SLBW', 'WFH', 'OB', 'CSR', 'SLWOP', 'VLWOP', 'ECU', 'SLBANK', 'MNCS', 'MM', 'PLA');
    $leave_type = $_POST['leave_type'];
    foreach ($leaves as $k => $v) {
        if ($leave_type == $v) {
            $employee_number = $_POST['delegate'];

            $sql = mysqli_query($db, "SELECT * FROM tbl_leave_balances 
            WHERE employee_number = '$employee_number'");

            if ($row = mysqli_fetch_assoc($sql)) {
                echo $row[$leave_type];
            }
        }
    }
}
if (isset($_POST['check_if_between_dates'])) {
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    $employee_number = $_POST['empnum'];

    $period = new DatePeriod(
        new DateTime($startDate),
        new DateInterval('P1D'),
        new DateTime($endDate)
    );
    $dates = array();
    $row_dates = array();
    foreach ($period as $key => $value) {
        $dates[] = $value->format('Y-m-d');
    }
    $dates[] = $endDate;

    $sql = mysqli_query($db, "SELECT * FROM tbl_leave_requests 
    WHERE endDate >= CURDATE() AND `status` LIKE 'Approved' AND delegated_emp_number = '$employee_number'
    OR endDate >= CURDATE() AND `status` LIKE 'Pending' AND delegated_emp_number = '$employee_number'");
    while ($row = mysqli_fetch_assoc($sql)) {
        $row_period = new DatePeriod(
            new DateTime($row['startDate']),
            new DateInterval('P1D'),
            new DateTime($row['endDate'])
        );
        foreach ($row_period as $key => $value) {
            $row_dates[] = $value;
        }
        $row_dates[] = $row['endDate'];
    }
    $c = 0;
    $conflict_dates = "";
    foreach ($row_dates as $k => $v) {
        if (in_array($v, $dates)) {
            $conflict_dates .= $v . ', ';
        }
    }
    echo $conflict_dates;
}
if (isset($_POST['btn_leave_application'])) {
    $delegate_emp_number = $_POST['delegate_emp_number'];
    $leave_type = $_POST['leave_type'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    $duration = $_POST['duration'];
    $total_days = $_POST['total_days'];
    if ($duration != "Whole Day") {
        $total_days = (int) $total_days / 2;
    }
    $reason = $_POST['reason'];
    $attachment = $_FILES['attachment']['name'];
    $attachment = md5($attachment);
    $attachment_tmp = $_FILES['attachment']['tmp_name'];
    $approver = "";
    $name = "";
    $em = "";

    $get_approver = mysqli_query($db, "SELECT t.*, t1.account_name FROM tbl_employment_information t
    INNER JOIN tbl_personal_information t1
    ON t.employee_number = t1.employee_number
    WHERE t.employee_number = '$delegate_emp_number'");
    if ($row = mysqli_fetch_assoc($get_approver)) {
        $approver = $row['approver'];
        $name = $row['account_name'];
    }
    $company_id = $_SESSION['hris_company_id'];

    $sql = mysqli_query($db, "INSERT INTO tbl_leave_requests VALUES(
        '',
        '$company_id',
        '$delegate_emp_number',
        '$delegate_emp_number',
        '$leave_type',
        '$startDate',
        '$endDate',
        '$total_days',
        '$reason',
        '$duration',
        '$attachment',
        '$approver',
        '',
        'Pending',
        '$datetime',
        ''
    )");
    if ($sql) {
        $leave_id = mysqli_insert_id($db);
        move_uploaded_file($attachment_tmp, "uploads/leaves/" . $attachment);
        include('phpMailer.php');
        leaveApplication($leave_id);
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Filed a leave application','$datetime')");
        echo "<script>alert('Leave Application has been submitted successfully.');window.location.replace('leave-list');</script>";
    }
}
if (isset($_POST['get_account_name'])) {
    $email = $_POST['email'];
    $sql = mysqli_query($db, "SELECT * FROM tbl_personal_information WHERE company_email = '$email'");
    $row = mysqli_fetch_assoc($sql);
    echo $row['account_name'];
}
if (isset($_POST['validate_email'])) {
    $email = $_POST['validate_email'];
    $sql = mysqli_query($db, "SELECT * FROM tbl_users WHERE email = '$email'");
    if ($sql) {
        if (mysqli_num_rows($sql) == 1) {
            echo "1";
        }
    }
}
if (isset($_POST['btn_register'])) {
    $company_id = '';
    $email = $_POST['email'];
    $account_name = $_POST['account_name'];
    $role = $_POST['role'];
    $password = md5($_POST['password']);


    if ($role == "Processor") {
        $companies = $_POST['companies'];
        foreach ($companies as $c) {
            $company_id .= $c . ',';
        }
        $company_id = rtrim($company_id, ", ");
    }
    $pending_tasks = '0';
    $file201 = '0';
    $leave_management = '0';
    $ot_management = '0';
    $certificate_request = '0';
    $salary_loan = '0';
    $benefits_reimbursement = '0';
    $holiday_maintenance = '0';
    $generate_reports = '0';
    $timekeeping = '0';
    $training = '0';
    $performance = '0';

    if (isset($_POST['pending_tasks'])) {
        $pending_tasks = $_POST['pending_tasks'];
    }
    if (isset($_POST['file201'])) {
        $file201 = $_POST['file201'];
    }
    if (isset($_POST['leave_management'])) {
        $leave_management = $_POST['leave_management'];
    }
    if (isset($_POST['ot_management'])) {
        $ot_management = $_POST['ot_management'];
    }
    if (isset($_POST['certificate_request'])) {
        $certificate_request = $_POST['certificate_request'];
    }
    if (isset($_POST['salary_loan'])) {
        $salary_loan = $_POST['salary_loan'];
    }
    if (isset($_POST['benefits_reimbursement'])) {
        $benefits_reimbursement = $_POST['benefits_reimbursement'];
    }
    if (isset($_POST['holiday_maintenance'])) {
        $holiday_maintenance = $_POST['holiday_maintenance'];
    }
    if (isset($_POST['generate_reports'])) {
        $generate_reports = $_POST['generate_reports'];
    }
    if (isset($_POST['timekeeping'])) {
        $timekeeping = $_POST['timekeeping'];
    }
    if (isset($_POST['training'])) {
        $training = $_POST['training'];
    }
    if (isset($_POST['performance'])) {
        $performance = $_POST['performance'];
    }

    $sql = mysqli_query($db, "INSERT INTO tbl_users VALUES(
        0,
        '$company_id',
        '$email',
        '$account_name',
        '$role',
        '$pending_tasks',
        '$file201',
        '$leave_management',
        '$ot_management',
        '$certificate_request',
        '$salary_loan',
        '$benefits_reimbursement',
        '$timekeeping',
        '$training',
        '$performance',
        '$holiday_maintenance',
        '$generate_reports',
        '$password',
        '$datetime'
    )")  or die(mysqli_error($db));;
    if ($sql) {
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> ' . $email . ' has been registered.</h4>
        </div>';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query(
            $db,
            "INSERT INTO tbl_audit_trail VALUES(0,'$at_name','Registered a user: $account_name','$datetime')"
        );
    }
}
if (isset($_POST['btn_update_account'])) {
    $id = $_POST['id'];
    $email = $_POST['email'];
    $account_name = $_POST['account_name'];
    $role = $_POST['role'];
    $cid = $_POST['cid'];
    $company_id = '';

    if ($role == "Processor") {
        $companies = $_POST['companies'];
        foreach ($companies as $c) {
            $company_id .= $c . ',';
        }
        $company_id = rtrim($company_id, ", ");
    } else {
        $company_id = $cid;
    }

    $pending_tasks = '0';
    $file201 = '0';
    $leave_management = '0';
    $ot_management = '0';
    $certificate_request = '0';
    $salary_loan = '0';
    $benefits_reimbursement = '0';
    $holiday_maintenance = '0';
    $generate_reports = '0';
    $timekeeping = '0';
    $training = '0';
    $performance = '0';

    if (isset($_POST['pending_tasks'])) {
        $pending_tasks = $_POST['pending_tasks'];
    }
    if (isset($_POST['file201'])) {
        $file201 = $_POST['file201'];
    }
    if (isset($_POST['leave_management'])) {
        $leave_management = $_POST['leave_management'];
    }
    if (isset($_POST['ot_management'])) {
        $ot_management = $_POST['ot_management'];
    }
    if (isset($_POST['certificate_request'])) {
        $certificate_request = $_POST['certificate_request'];
    }
    if (isset($_POST['salary_loan'])) {
        $salary_loan = $_POST['salary_loan'];
    }
    if (isset($_POST['benefits_reimbursement'])) {
        $benefits_reimbursement = $_POST['benefits_reimbursement'];
    }
    if (isset($_POST['holiday_maintenance'])) {
        $holiday_maintenance = $_POST['holiday_maintenance'];
    }
    if (isset($_POST['generate_reports'])) {
        $generate_reports = $_POST['generate_reports'];
    }
    if (isset($_POST['timekeeping'])) {
        $timekeeping = $_POST['timekeeping'];
    }
    if (isset($_POST['training'])) {
        $training = $_POST['training'];
    }
    if (isset($_POST['performance'])) {
        $performance = $_POST['performance'];
    }

    $sql = mysqli_query($db, "UPDATE tbl_users SET
        email = '$email',
        account_name = '$account_name',
        `role` = '$role',
        `company_id` = '$company_id',
        pending_task = '$pending_tasks',
        file201 = '$file201',
        leave_management = '$leave_management',
        ot_management = '$ot_management',
        certificate_requests = '$certificate_request',
        salary_loan_management = '$salary_loan',
        benefits_reimbursement = '$benefits_reimbursement',
        holiday_maintenance = '$holiday_maintenance',
        generate_reports = '$generate_reports',
        timekeeping = '$timekeeping',
        training = '$training',
        performance = '$performance'
        WHERE ID = '$id'
    ");
    if ($sql) {
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> ' . $account_name . ' account has been updated.</h4>
        </div>';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Updated an account: $account_name','$datetime')");
    }
}
if (isset($_POST['btn_update_myaccount'])) {
    $id = $_POST['id'];
    $email = $_POST['email'];
    $account_name = $_POST['account_name'];

    $sql = mysqli_query($db, "UPDATE tbl_users SET
        email = '$email',
        account_name = '$account_name'
        WHERE ID = '$id'
    ");
    if ($sql) {
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> Your account has been updated.</h4>
        </div>';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Updated an account: $account_name','$datetime')");
    }
}
if (isset($_POST['account_change_password'])) {
    $id = $_POST['id'];
    $new_password = md5($_POST['new_password']);

    $sql = mysqli_query($db, "UPDATE tbl_users SET `password` = '$new_password'");
    if ($sql) {
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> Password has been changed successfully</h4>
        </div>';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Changed password ID: $id','$datetime')");
    }
}
if (isset($_POST['btn_approve_leave'])) {
    $leave_id = $_POST['id'];
    $leave_details = get_leave_details($leave_id);

    $leave_type = $_POST['leave_type'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    $approver_remarks = $_POST['approver_remarks'];

    $total_days = $_POST['total_days'];
    $leave_type = $_POST['leave_type'];
    $employee_number = $_POST['delegate_emp_number'];
    $approver = $_POST['approver'];

    $em = '';
    $name = "";

    // Get name
    $get_info = mysqli_query($db, "SELECT * FROM tbl_personal_information WHERE employee_number LIKE '$employee_number'");
    if ($row = mysqli_fetch_assoc($get_info)) {
        $name = $row['account_name'];
        $em = $row['company_email'];
    }
    $sql = mysqli_query($db, "UPDATE tbl_leave_requests SET 
    leave_type = '$leave_type',
    startDate = '$startDate',
    endDate = '$endDate',
    total_day = '$total_days',
    approver_remarks = '$approver_remarks',
    `status` = 'Approved'
    WHERE ID = '$leave_id'");
    if ($sql) {
        include('phpMailer.php');
        $available_leaves = array('SL', 'VL', 'EL', 'WFH', 'ECU', 'MNCS', 'MM', 'BL', 'PLA', 'PL', 'SPL', 'SLBANK');
        if (in_array($leave_type, $available_leaves)) {
            $sql1 = mysqli_query($db, "UPDATE tbl_leave_balances 
            SET $leave_type = $leave_type - $total_days 
            WHERE employee_number = '$employee_number'");
        }
        if ($leave_type == "CSR") {
            mysqli_query($db, "UPDATE tbl_leave_balances 
            SET VL = VL + ($total_days/2)
            WHERE employee_number = '$employee_number'");
        }
        leaveApplicationStatus($leave_id, $name, $em, $leave_type, $startDate, $endDate, $total_days, "Approved", $employee_number);
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> Leave Application has been approved.</h4>
        </div>';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Approved Leave Application ID: LA-$leave_id','$datetime')");
    }
}
if (isset($_POST['btn_cancel_leave'])) {
    $leave_id = $_POST['leave_id'];
    $leave_details = get_leave_details($leave_id);

    $leave_type = $leave_details['leave_type'];
    $startDate = $leave_details['startDate'];
    $endDate = $leave_details['endDate'];
    $approver_remarks = $leave_details['approver_remarks'];

    $reason_for_cancellation = $_POST['reason_for_cancellation'];

    $total_days = $leave_details['total_day'];
    $leave_type = $leave_details['leave_type'];
    $employee_number = $leave_details['delegated_emp_number'];
    $stat = $leave_details['status'];
    $approver = $leave_details['approver'];

    $em = '';
    $name = "";

    // Get name
    $get_info = mysqli_query($db, "SELECT * FROM tbl_personal_information WHERE employee_number LIKE '$employee_number'");
    if ($row = mysqli_fetch_assoc($get_info)) {
        $name = $row['account_name'];
        $em = $row['company_email'];
    }
    if ($stat == "Approved") {
        $sql = "UPDATE tbl_leave_requests SET 
        `status` = 'Requested Cancellation',
        cancellation_reason = '$reason_for_cancellation'
        WHERE ID = '$leave_id'";
    } elseif ($stat == "Pending") {
        $sql = "UPDATE tbl_leave_requests
        SET `status` = 'Cancelled',
        cancellation_reason = '$reason_for_cancellation'
        WHERE ID = '$leave_id'";
    }
    $stmt = mysqli_query($db, $sql);
    if ($stmt) {
        include('phpMailer.php');
        if ($stat == "Approved") {
            leaveApplicationCancelApproved($leave_id, $em, $name, 'Requested Cancellation', $leave_type, $startDate, $endDate, $total_days, $employee_number, $approver);
        } elseif ($stat == "Pending") {
            leaveApplicationCancelPending($leave_id, $em, $name, 'Cancelled', $leave_type, $startDate, $endDate, $total_days, $approver, $employee_number);
        }
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Cancelled Leave Application ID: LA-$leave_id','$datetime')");
        $res = "alert('Leave Cancellation Requested.');window.location.replace('leave-list');";
    }
}
if (isset($_POST['btn_approve_cancellation'])) {
    $leave_id = $_POST['id'];
    $leave_details = get_leave_details($leave_id);

    $leave_type = $_POST['leave_type'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    $approver_remarks = $_POST['approver_remarks'];

    $total_days = $_POST['total_days'];
    $leave_type = $_POST['leave_type'];
    $employee_number = $_POST['delegate_emp_number'];
    $approver = $_POST['approver'];

    $em = '';
    $name = "";

    // Get name
    $get_info = mysqli_query($db, "SELECT * FROM tbl_personal_information WHERE employee_number LIKE '$employee_number'");
    if ($row = mysqli_fetch_assoc($get_info)) {
        $name = $row['account_name'];
        $em = $row['company_email'];
    }
    $sql = mysqli_query($db, "UPDATE tbl_leave_requests SET 
    leave_type = '$leave_type',
    startDate = '$startDate',
    endDate = '$endDate',
    total_day = '$total_days',
    approver_remarks = '$approver_remarks',
    `status` = 'Cancelled'
    WHERE ID = '$leave_id'");
    if ($sql) {
        include('phpMailer.php');
        $available_leaves = array('SL', 'VL', 'EL', 'WFH', 'ECU', 'MNCS', 'MM', 'BL', 'PLA', 'PL', 'SPL', 'SLBANK');
        if (in_array($leave_type, $available_leaves)) {
            $sql1 = mysqli_query($db, "UPDATE tbl_leave_balances 
            SET $leave_type = $leave_type + $total_days 
            WHERE employee_number = '$employee_number'");
        }
        if ($leave_type == "CSR") {
            mysqli_query($db, "UPDATE tbl_leave_balances 
            SET VL = VL - ($total_days/2)
            WHERE employee_number = '$employee_number'");
        }
        leaveApplicationStatus($leave_id, $name, $em, $leave_type, $startDate, $endDate, $total_days, "Cancelled", $employee_number);
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> Leave Application has been cancelled.</h4>
        </div>';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Approved Leave cancellation ID: LA-$leave_id','$datetime')");
    }
}
if (isset($_POST['btn_update_leave_balances'])) {
    $employee_number = $_POST['employee_number'];
    $sl = $_POST['sl'];
    $vl = $_POST['vl'];
    $el = $_POST['el'];
    $wfh = $_POST['wfh'];
    $ecu = $_POST['ecu'];
    $bl = $_POST['bl'];
    $pla = $_POST['pla'];
    $pl = $_POST['pl'];
    $spl = $_POST['spl'];
    $slbank = $_POST['slbank'];

    $sql = mysqli_query($db, "UPDATE tbl_leave_balances SET
    SL = '$sl',
    VL = '$vl',
    EL = '$el',
    WFH = '$wfh',
    ECU = '$ecu',
    BL = '$bl',
    PL = '$pl',
    PLA = '$pla',
    SPL = '$spl',
    SLBANK = '$slbank'
    WHERE employee_number = '$employee_number'
    ");
    if ($sql) {
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> Leave Balances has been updated.</h4>
        </div>';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Updated Leave balances for employee number: $employee_number','$datetime')");
    }
}
if (isset($_POST['btn_ot_application'])) {
    $company_id = $_POST['company_id'];
    $employee_number = $_POST['employee_number'];
    $month_of_ot = $_POST['month_of_ot'];
    $total_hours = $_POST['total_hours'];
    $remarks = $_POST['remarks'];
    $attachment = $_FILES['attachment']['name'];
    $attachment = md5($attachment);
    $attachment_tmp = $_FILES['attachment']['tmp_name'];

    $get_approver = get_employment_information($employee_number);
    $approver = $get_approver['approver'];

    $sql = mysqli_query($db, "INSERT INTO tbl_ot_application VALUES(
        '',
        '$company_id',
        '$employee_number',
        '$month_of_ot',
        '$total_hours',
        '$remarks',
        '$attachment',
        '$approver',
        '',
        '',
        'Pending',
        '$datetime'
    )");
    if ($sql) {
        $ot_id = mysqli_insert_id($db);
        move_uploaded_file($attachment_tmp, "uploads/" . $attachment);
        $personal_info = get_personal_information($employee_number);
        $employment_info = get_employment_information($employee_number);
        include('phpMailer.php');
        otApplication($ot_id, $personal_info['company_email'], $personal_info['account_name'], $employment_info['approver'], $month_of_ot, $total_hours, $employee_number);
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Submitted an OT Application','$datetime')");
        echo '<script>alert("OT application has been submitted.");window.location.replace("ot-list")</script>';
    }
}
if (isset($_POST['btn_cancel_ot'])) {
    $id = $_POST['id'];
    $employee_number = $_POST['employee_number'];
    $month_of_ot = $_POST['month_of_ot'];
    $total_hours = $_POST['total_hours'];
    $remarks = $_POST['remarks'];
    $per_info = get_personal_information($employee_number);

    $sql = mysqli_query($db, "UPDATE tbl_ot_application 
    SET `status` = 'Cancelled'
    WHERE ID = '$id'");
    if ($sql) {
        include('phpMailer.php');
        overtimeResponseCancel($per_info['company_email'], $per_info['account_name'], $id, $employee_number);
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> OT application has been cancelled.</h4>
        </div>';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Cancelled an OT Application','$datetime')");
    }
}
if (isset($_POST['btn_approve_ot'])) {
    $id = $_POST['id'];
    $employee_number = $_POST['employee_number'];
    $per_info = get_personal_information($employee_number);

    $month_of_ot = $_POST['month_of_ot'];
    $total_hours = $_POST['total_hours'];
    $remarks = $_POST['remarks'];

    $approver_remarks = $_POST['approver_remarks'];
    $approver_attachment = $_FILES['approver_attachment']['name'];
    $approver_attachment = md5($approver_attachment);
    $approver_attachment_tmp = $_FILES['approver_attachment']['tmp_name'];

    $sql = mysqli_query($db, "UPDATE tbl_ot_application 
    SET month_of_ot = '$month_of_ot',
    total_hours = '$total_hours',
    remarks = '$remarks',
    approver_remarks = '$approver_remarks',
    approver_attachment = '$approver_attachment',
    `status` = 'Approved'
    WHERE ID LIKE '$id'");
    if ($sql) {
        move_uploaded_file($approver_attachment_tmp, "uploads/" . $approver_attachment);
        include('phpMailer.php');
        overtimeResponse($per_info['company_email'], $per_info['account_name'], $approver_remarks, $id, "Approved", $employee_number);
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> OT application has been approved.</h4>
        </div>';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Approved an OT Application ID: OT-$id','$datetime')");
    }
}
if (isset($_POST['btn_decline_ot'])) {
    $id = $_POST['id'];
    $employee_number = $_POST['employee_number'];
    $per_info = get_personal_information($employee_number);

    $month_of_ot = $_POST['month_of_ot'];
    $total_hours = $_POST['total_hours'];
    $remarks = $_POST['remarks'];

    $approver_remarks = $_POST['approver_remarks'];
    $approver_attachment = $_FILES['approver_attachment']['name'];
    $approver_attachment = md5($approver_attachment);
    $approver_attachment_tmp = $_FILES['approver_attachment']['tmp_name'];

    $sql = mysqli_query($db, "UPDATE tbl_ot_application 
    SET month_of_ot = '$month_of_ot',
    total_hours = '$total_hours',
    remarks = '$remarks',
    approver_remarks = '$approver_remarks',
    approver_attachment = '$approver_attachment',
    `status` = 'Declined'
    WHERE ID LIKE '$id'");
    if ($sql) {
        move_uploaded_file($approver_attachment_tmp, "uploads/" . $approver_attachment);
        include('phpMailer.php');
        overtimeResponse($per_info['company_email'], $per_info['account_name'], $approver_remarks, $id, "Declined", $employee_number);
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> OT application has been declined.</h4>
        </div>';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Declined an OT Application ID: $id','$datetime')");
    }
}
if (isset($_POST['btn_certificate_request'])) {
    $company_id = $_SESSION['hris_company_id'];
    $employee_number = $_POST['employee_number'];
    $requested_by = $_POST['requested_by'];
    $certificate_type = $_POST['certificate_type'];
    $date_required = $_POST['date_required'];
    $purpose = $_POST['purpose'];
    $remarks = $_POST['remarks'];

    $pers_info = get_personal_information($employee_number);

    $sql = mysqli_query($db, "INSERT INTO tbl_certificate_requests VALUES(
        '',
        '$company_id',
        '$employee_number',
        '$requested_by',
        '$certificate_type',
        '$date_required',
        '$purpose',
        '$remarks',
        '',
        '',
        'Pending',
        '$datetime'
    )");
    if ($sql) {
        $cert_id = mysqli_insert_id($db);
        include('phpMailer.php');
        certificateRequest($cert_id, $pers_info['company_email'], $pers_info['account_name'], $certificate_type, $employee_number);
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Submitted a Certificate Request','$datetime')");
        echo '<script>alert("Certificate Request has been submitted.");window.location.replace("certificate-request-list")</script>';
    }
}
if (isset($_POST['btn_acknowledge_request'])) {

    $id = $_POST['id'];
    $employee_number = $_POST['employee_number'];
    $certificate_type = $_POST['certificate_type'];
    $date_required = $_POST['date_required'];
    $purpose = $_POST['purpose'];
    $remarks = $_POST['remarks'];
    $hr_remarks = $_POST['hr_remarks'];
    $acknowledged_by = $_POST['acknowledged_by'];

    $sql = mysqli_query($db, "UPDATE tbl_certificate_requests SET 
    certificate_type = '$certificate_type',
    date_required = '$date_required',
    purpose = '$purpose',
    remarks = '$remarks',
    hr_remarks = '$hr_remarks',
    acknowledged_by = '$acknowledged_by',
    `status` = 'Acknowledged'
    WHERE ID = '$id'");

    if ($sql) {
        include('phpMailer.php');
        certificateAcknowledge($id, get_personal_information($employee_number)['company_email'], get_personal_information($employee_number)['account_name'], $certificate_type, $hr_remarks, $employee_number);
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Acknowledged a Certificate Request: CR-$id','$datetime')");
        echo '<script>alert("Certificate Request has been acknowledged.");window.location.replace("certificate-request-list")</script>';
    }
}
if (isset($_POST['add_holiday'])) {
    $holiday_date = $_POST['holiday_date'];
    $holiday_type = $_POST['holiday_type'];
    $description = str_replace("'", "`", $_POST['description']);

    $sql = mysqli_query($db, "INSERT INTO tbl_holidays VALUES('','$holiday_date','$holiday_type','$description')");
    if ($sql) {
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> ' . $holiday_date . ' has been added as holiday.</h4>
        </div>';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Added a holiday','$datetime')");
    }
}
if (isset($_POST['update_holiday'])) {
    $id = $_POST['id'];
    $holiday_date = $_POST['holiday_date'];
    $old_date = $_POST['old_date'];
    $type = $_POST['type'];
    $description = str_replace("'", "`", $_POST['description']);
    if (check_if_holiday_exist($holiday_date, $old_date) == '0') {
        $sql = mysqli_query($db, "UPDATE tbl_holidays SET
            holiday_date = '$holiday_date',
            `type` = '$type',
            `description` = '$description'
            WHERE ID = '$id'
            ");
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Updated holiday ID: $id','$datetime')");
        echo '<script>alert("Updated successfully.");window.location.replace("holiday-maintenance")</script>';
    } else {
        $res = '<div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="fa fa-times"></i> ' . $holiday_date . ' exists as holiday.</h4>
            </div>';
    }
}
if (isset($_POST['btn_car_registration'])) {
    $company_id = $_POST['company_id'];
    $employee_number = $_POST['employee_number'];
    $model = $_POST['model'];
    $plate_number = $_POST['plate_number'];
    $date_acquired = $_POST['date_acquired'];
    $description = $_POST['description'];

    $sql = mysqli_query($db, "INSERT INTO tbl_car_registration VALUES(
        '',
        '$company_id',
        '$employee_number',
        '$model',
        '$plate_number',
        '$date_acquired',
        '$description',
        '$datetime'
    )");
    if ($sql) {
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> Car has been registered to ' . $employee_number . '.</h4>
        </div>';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Registered a car for employee number: $employee_number','$datetime')");
    }
}
if (isset($_POST['btn_update_car_registration'])) {
    $id = $_POST['id'];
    $employee_number = $_POST['employee_number'];
    $model = $_POST['model'];
    $plate_number = $_POST['plate_number'];
    $date_acquired = $_POST['date_acquired'];
    $description = $_POST['description'];

    $sql = mysqli_query($db, "UPDATE tbl_car_registration SET
        employee_number = '$employee_number',
        model = '$model',
        plate_number = '$plate_number',
        date_acquired = '$date_acquired',
        `description` = '$description'
        WHERE ID = '$id'
    ");
    if ($sql) {
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> Car details has been updated.</h4>
        </div>';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Updated Car Details for employee number: $employee_number','$datetime')");
    }
}
if (isset($_POST['add_benefits_approver_role'])) {
    $company_id = $_POST['company_id'];
    $role = $_POST['role'];
    $hierarchical_number = $_POST['hierarchical_number'];
    $cc = $_POST['cc'];

    $sql = mysqli_query($db, "INSERT INTO tbl_benefits_approvers_role VALUES('','$company_id','$role','$hierarchical_number','$cc')");
    if ($sql) {
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> Benefits Approver Role has been added.</h4>
        </div>';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Added a benefits approver role: $role','$datetime')");
    }
}
if (isset($_POST['add_benefits_approver'])) {
    $company_id = $_POST['company_id'];
    $user_id = $_POST['user_id'];
    $role = $_POST['role'];
    $sql = mysqli_query($db, "INSERT INTO tbl_benefits_approvers VALUES('','$company_id','$user_id','$role');");
    if ($sql) {
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> Benefits Approver has been added.</h4>
        </div>';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Added a benefits approver: $user_id user ID','$datetime')");
    }
}
if (isset($_POST['btn_delete_benefits_apprrover'])) {
    $id = $_POST['id'];
    $sql = mysqli_query($db, "DELETE FROM tbl_benefits_approvers WHERE ID = '$id'");
    if ($sql) {
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> Benefits Approver has been deleted.</h4>
        </div>';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Deleted a benefits approver','$datetime')");
    }
}
if (isset($_POST['btn_delete_loan_apprrover'])) {
    $id = $_POST['id'];
    $sql = mysqli_query($db, "DELETE FROM tbl_loan_approvers WHERE ID = '$id'");
    if ($sql) {
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> Salary Loan Approver has been deleted.</h4>
        </div>';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Deleted a salary loan approver','$datetime')");
    }
}
if (isset($_POST['add_salary_loan_approver_role'])) {
    $company_id = $_POST['company_id'];
    $role = $_POST['role'];
    $hierarchical_number = $_POST['hierarchical_number'];
    $cc = $_POST['cc'];

    $sql = mysqli_query($db, "INSERT INTO tbl_loan_approvers_role VALUES('','$company_id','$role','$hierarchical_number','$cc')");
    if ($sql) {
        $res = '<div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="fa fa-check"></i> Salary Loan Approver Role has been added.</h4>
    </div>';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Added a salary loan approver role: $role','$datetime')");
    }
}
if (isset($_POST['add_salary_loan_approver'])) {
    $company_id = $_POST['company_id'];
    $user_id = $_POST['user_id'];
    $role = $_POST['role'];
    $sql = mysqli_query($db, "INSERT INTO tbl_loan_approvers VALUES('','$company_id','$user_id','$role')");
    if ($sql) {
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> Salary Loan Approver has been added.</h4>
        </div>';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Added a salary loan approver: $user_id user ID','$datetime')");
    }
}
if (isset($_POST['update_salary_loan_approver'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $sql = mysqli_query($db, "UPDATE tbl_loan_approvers SET 
    `name` = '$name', 
    email = '$email',
    `role` = '$role'
    WHERE ID = '$id'");
    if ($sql) {
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Updated Salary Loan Approver: $name','$datetime')");
        echo "<script>alert('Salary Loan Approver has been updated.');window.location.replace('loan-approvers');</script>";
    }
}
if (isset($_POST['update_salary_loan_approver_role'])) {
    $id = $_POST['id'];
    $role = $_POST['role'];
    $position = $_POST['position'];
    $cc = $_POST['cc'];

    $sql = mysqli_query($db, "UPDATE tbl_loan_approvers_role SET `role` = '$role', position = '$position', cc = '$cc' WHERE ID = '$id'");
    if ($sql) {
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Updated a salary loan approver role: $role','$datetime')");
        echo "<script>alert('Salary Loan Approver role has been updated.');window.location.replace('loan-approver-roles');</script>";
    }
}
if (isset($_POST['update_benefits_approver_role'])) {
    $id = $_POST['id'];
    $role = $_POST['role'];
    $position = $_POST['position'];
    $cc = $_POST['cc'];

    $sql = mysqli_query($db, "UPDATE tbl_benefits_approvers_role SET `role` = '$role', position = '$position', cc = '$cc' WHERE ID = '$id'");
    if ($sql) {
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Updated a benefits approver role: $role','$datetime')");
        echo "<script>alert('Benefits Approver role has been updated.');window.location.replace('benefits-approver-roles');</script>";
    }
}
if (isset($_POST['update_benefits_approver'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $sql = mysqli_query($db, "UPDATE tbl_benefits_approvers SET 
    `name` = '$name', 
    email = '$email',
    `role` = '$role'
    WHERE ID = '$id'");
    if ($sql) {
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Updated a benefits approver: $name','$datetime')");
        echo "<script>alert('Benefits Approver has been updated.');window.location.replace('benefits-approvers');</script>";
    }
}
if (isset($_POST['get_benefits_category'])) {
    $value = '';
    $employee_number = $_POST['get_benefits_category'];
    $sql = mysqli_query($db, "SELECT * FROM tbl_benefits_eligibility WHERE employee_number = '$employee_number'");
    if ($r = mysqli_fetch_assoc($sql)) {
        if ($r['parking'] == '1') {
            $value .= '<li><a href="#tab_parking"><i class="gi gi-cars" style="margin-right: 3px;"></i> Parking</a></li>';
        }
        if ($r['gasoline'] == '1') {
            $value .= '<li><a href="#tab_gas"><i class="gi gi-tint" style="margin-right: 3px;"></i> Gasoline</a></li>';
        }
        if ($r['car_maintenance'] == '1') {
            $value .= '<li><a href="#tab_car"><i class="fa fa-car" style="margin-right: 3px;"></i> Car Maintenance</a></li>';
        }
        if ($r['medicine'] == '1') {
            $value .= '<li><a href="#tab_medical"><i class="gi gi-hospital" style="margin-right: 3px;"></i> Medical</a></li>';
        }
        if ($r['gym'] == '1') {
            $value .= '<li class="active"><a href="#tab_gym"><i class="gi gi-bicycle" style="margin-right: 3px;"></i> Gym</a></li>';
        }
        if ($r['optical_allowance'] == '1') {
            $value .= '<li><a href="#tab_optical"><i class="fa fa-eye" style="margin-right: 3px;"></i> Optical</a></li>';
        }
        if ($r['cep'] == '1') {
            $value .= '<li><a href="#tab_cep"><i class="fa fa-graduation-cap" style="margin-right: 3px;"></i> CEP</a></li>';
        }
        if ($r['club_membership'] == '1') {
            $value .= '<li><a href="#tab_club"><i class="fa fa-building" style="margin-right: 3px;"></i> Club Membership</a></li>';
        }
        if ($r['maternity'] == '1') {
            $value .= '<li><a href="#tab_maternity"><i class="gi gi-parents" style="margin-right: 3px;"></i> Maternity</a></li>';
        }
        if ($r['others'] == '1') {
            $value .= '<li><a href="#tab_others"><i class="fa fa-navicon" style="margin-right: 3px;"></i> Others</a></li>';
        }
    }
    echo $value;
}
if (isset($_POST['ajax_get_benefits_balances'])) {
    $employee_number = $_POST['ajax_get_benefits_balances'];
    echo json_encode(get_benefits_balances($employee_number), JSON_FORCE_OBJECT);
}
if (isset($_POST['btn_benefits_reimbursement'])) {
    $company_id = $_POST['company_id'];
    $requestor = $_POST['employee_number'];
    $payee = $_POST['payee'];
    $amount = $_POST['amount'];
    $payment_for = $_POST['payment_for'];
    $special_instruction = $_POST['special_instruction'];
    $categories_applied = '';

    $sql = mysqli_query($db, "INSERT INTO tbl_benefits_reimbursement VALUES(
        '',
        '$company_id',
        '$requestor',
        '$payee',
        '$amount',
        '$payment_for',
        '$special_instruction',
        '$categories_applied',
        '',
        '1',
        '$datetime'
    )");
    if ($sql) {
        $benefits_id = mysqli_insert_id($db);

        // Parking
        $p_count = 0;
        $parking_total = str_replace(",", "", $_POST['parking_total']);
        // Array vars
        $parking_requested_amount = $_POST['parking_requested_amount'];
        $parking_remarks = $_POST['parking_remarks'];
        $parking_attachment = $_FILES['parking_attachment']['name'];
        $parking_attachment_tmp = $_FILES['parking_attachment']['tmp_name'];

        foreach ($parking_requested_amount as $k => $v) {
            $amount = $v;
            if ($amount != "") {
                if ($p_count == 0) {
                    $categories_applied .= "Parking, ";
                    mysqli_query($db, "INSERT INTO tbl_benefits_total_amount VALUES('','$benefits_id','$parking_total', 'Parking')");
                }
                $remarks = $parking_remarks[$k];
                if (empty($remarks)) {
                    $remarks = "N/A";
                }
                $attachment = md5($parking_attachment[$k]);

                mysqli_query($db, "INSERT INTO tbl_benefits_form VALUES(
                    '',
                    '$benefits_id',
                    '$amount',
                    '$remarks',
                    '$attachment',
                    'Parking'
                )");
                move_uploaded_file($parking_attachment_tmp[$k], "uploads/" . $attachment);
                $p_count++;
            }
        }

        // Gasoline
        $g_count = 0;
        $gas_total = str_replace(",", "", $_POST['gas_total']);
        $requested_liters = $_POST['requested_liters'];
        // Array vars
        $gas_requested_amount = $_POST['gas_requested_amount'];
        $gas_remarks = $_POST['gas_remarks'];
        $gas_attachment = $_FILES['gas_attachment']['name'];
        $gas_attachment_tmp = $_FILES['gas_attachment']['tmp_name'];

        foreach ($gas_requested_amount as $k => $v) {
            $amount = $v;
            if ($amount != "") {
                if ($g_count == 0) {
                    $categories_applied .= "Gas, ";
                    mysqli_query($db, "INSERT INTO tbl_benefits_total_amount VALUES('','$benefits_id','$gas_total', 'Gas')");
                    mysqli_query($db, "INSERT INTO tbl_benefits_gasoline_details VALUES('','$benefits_id','$requested_liters')");
                }
                $remarks = $gas_remarks[$k];
                if (empty($remarks)) {
                    $remarks = "N/A";
                }
                $attachment = md5($gas_attachment[$k]);
                mysqli_query($db, "INSERT INTO tbl_benefits_form VALUES(
                    '',
                    '$benefits_id',
                    '$amount',
                    '$remarks',
                    '$attachment',
                    'Gas'
                )");
                move_uploaded_file($gas_attachment_tmp[$k], "uploads/" . $attachment);
                $g_count++;
            }
        }

        // Car Maintenance
        $c_count = 0;
        $car_total = str_replace(",", "", $_POST['car_total']);
        // Array vars
        $car_requested_amount = $_POST['car_requested_amount'];
        $car_remarks = $_POST['car_remarks'];
        $car_attachment = $_FILES['car_attachment']['name'];
        $car_attachment_tmp = $_FILES['car_attachment']['tmp_name'];

        foreach ($car_requested_amount as $k => $v) {
            $amount = $v;
            if ($amount != "") {
                if ($c_count == 0) {
                    $categories_applied .= "Car, ";
                    mysqli_query($db, "INSERT INTO tbl_benefits_total_amount VALUES('','$benefits_id','$car_total', 'Car')");
                }
                $remarks = $car_remarks[$k];
                if (empty($remarks)) {
                    $remarks = "N/A";
                }
                $attachment = md5($car_attachment[$k]);

                mysqli_query($db, "INSERT INTO tbl_benefits_form VALUES(
                    '',
                    '$benefits_id',
                    '$amount',
                    '$remarks',
                    '$attachment',
                    'Car'
                )");
                move_uploaded_file($car_attachment_tmp[$k], "uploads/" . $attachment);
                $c_count++;
            }
        }

        // Medical
        $m_count = 0;
        $medical_total = str_replace(",", "", $_POST['medical_total']);
        // Array vars
        $medical_requested_amount = $_POST['medical_requested_amount'];
        $medical_remarks = $_POST['medical_remarks'];
        $medical_attachment = $_FILES['medical_attachment']['name'];
        $medical_attachment_tmp = $_FILES['medical_attachment']['tmp_name'];

        foreach ($medical_requested_amount as $k => $v) {
            $amount = $v;
            if ($amount != "") {
                if ($m_count == 0) {
                    $categories_applied .= "Medical, ";
                    mysqli_query($db, "INSERT INTO tbl_benefits_total_amount VALUES('','$benefits_id','$medical_total', 'Medical')");
                }
                $remarks = $medical_remarks[$k];
                if (empty($remarks)) {
                    $remarks = "N/A";
                }
                $attachment = md5($medical_attachment[$k]);

                mysqli_query($db, "INSERT INTO tbl_benefits_form VALUES(
                    '',
                    '$benefits_id',
                    '$amount',
                    '$remarks',
                    '$attachment',
                    'Medical'
                )");
                move_uploaded_file($medical_attachment_tmp[$k], "uploads/" . $attachment);
                $m_count++;
            }
        }

        // Gym
        $g_count = 0;
        $gym_total = str_replace(",", "", $_POST['gym_total']);
        // Array vars
        $gym_requested_amount = $_POST['gym_requested_amount'];
        $gym_remarks = $_POST['gym_remarks'];
        $gym_attachment = $_FILES['gym_attachment']['name'];
        $gym_attachment_tmp = $_FILES['gym_attachment']['tmp_name'];

        foreach ($gym_requested_amount as $k => $v) {
            $amount = $v;
            if ($amount != "") {
                if ($g_count == 0) {
                    $categories_applied .= "Gym, ";
                    mysqli_query($db, "INSERT INTO tbl_benefits_total_amount VALUES('','$benefits_id','$gym_total', 'Gym')");
                }
                $remarks = $gym_remarks[$k];
                if (empty($remarks)) {
                    $remarks = "N/A";
                }
                $attachment = md5($gym_attachment[$k]);

                mysqli_query($db, "INSERT INTO tbl_benefits_form VALUES(
                    '',
                    '$benefits_id',
                    '$amount',
                    '$remarks',
                    '$attachment',
                    'Gym'
                )");
                move_uploaded_file($gym_attachment_tmp[$k], "uploads/" . $attachment);
                $g_count++;
            }
        }

        // Optical
        $o_count = 0;
        $optical_total = str_replace(",", "", $_POST['optical_total']);
        // Array vars
        $optical_requested_amount = $_POST['optical_requested_amount'];
        $optical_remarks = $_POST['optical_remarks'];
        $optical_attachment = $_FILES['optical_attachment']['name'];
        $optical_attachment_tmp = $_FILES['optical_attachment']['tmp_name'];

        foreach ($optical_requested_amount as $k => $v) {
            $amount = $v;
            if ($amount != "") {
                if ($o_count == 0) {
                    $categories_applied .= "Optical, ";
                    mysqli_query($db, "INSERT INTO tbl_benefits_total_amount VALUES('','$benefits_id','$optical_total', 'Optical')");
                }
                $remarks = $optical_remarks[$k];
                if (empty($remarks)) {
                    $remarks = "N/A";
                }
                $attachment = md5($optical_attachment[$k]);

                mysqli_query($db, "INSERT INTO tbl_benefits_form VALUES(
                    '',
                    '$benefits_id',
                    '$amount',
                    '$remarks',
                    '$attachment',
                    'Optical'
                )");
                move_uploaded_file($optical_attachment_tmp[$k], "uploads/" . $attachment);
                $o_count++;
            }
        }

        // CEP
        $cep_count = 0;
        $cep_total = str_replace(",", "", $_POST['cep_total']);
        $type = $_POST['cep_type'];
        $premise = $_POST['cep_premise'];
        $bond = 0;
        // Array vars
        $cep_requested_amount = $_POST['cep_requested_amount'];
        $cep_remarks = $_POST['cep_remarks'];
        $cep_attachment = $_FILES['cep_attachment']['name'];
        $cep_attachment_tmp = $_FILES['cep_attachment']['tmp_name'];

        foreach ($cep_requested_amount as $k => $v) {
            $amount = $v;
            if ($type == "CEP" and $premise == "Local") {
                $bond = (int) $amount / 8000;
            }
            if ($type == "CEP" and $premise == "International") {
                $bond = (int) $amount / 15000;
            }
            if ($type == "Training" and $premise == "Local") {
                $bond = 0;
            }
            if ($type == "Training" and $premise == "International") {
                $bond = (int) $amount / 15000;
            }
            $bond = number_format($bond, 1);
            if ($amount != "") {
                if ($cep_count == 0) {
                    $categories_applied .= "CEP, ";
                    mysqli_query($db, "INSERT INTO tbl_benefits_total_amount VALUES('','$benefits_id','$cep_total', 'CEP')");
                    mysqli_query($db, "INSERT INTO tbl_cep_bond VALUES('','$benefits_id','$type','$premise','$bond','$bond')");
                }
                $remarks = $cep_remarks[$k];
                if (empty($remarks)) {
                    $remarks = "N/A";
                }
                $attachment = md5($cep_attachment[$k]);

                mysqli_query($db, "INSERT INTO tbl_benefits_form VALUES(
                    '',
                    '$benefits_id',
                    '$amount',
                    '$remarks',
                    '$attachment',
                    'CEP'
                )");
                move_uploaded_file($cep_attachment_tmp[$k], "uploads/" . $attachment);
                $cep_count++;
            }
        }

        // Maternity
        $mat_count = 0;
        $maternity_total = str_replace(",", "", $_POST['maternity_total']);
        $maternity_type = 'Maternity';
        // Array vars
        $maternity_requested_amount = $_POST['maternity_requested_amount'];
        $maternity_remarks = $_POST['maternity_remarks'];
        $maternity_attachment = $_FILES['maternity_attachment']['name'];
        $maternity_attachment_tmp = $_FILES['maternity_attachment']['tmp_name'];

        foreach ($maternity_requested_amount as $k => $v) {
            $amount = $v;
            if ($amount != "") {
                if ($mat_count == 0) {
                    $categories_applied .= "Maternity, ";
                    mysqli_query($db, "INSERT INTO tbl_benefits_total_amount VALUES('','$benefits_id','$maternity_total', 'Maternity')");
                }
                $remarks = $maternity_remarks[$k];
                if (empty($remarks)) {
                    $remarks = "N/A";
                }
                $attachment = md5($maternity_attachment[$k]);

                mysqli_query($db, "INSERT INTO tbl_benefits_form VALUES(
                    '',
                    '$benefits_id',
                    '$amount',
                    '$remarks',
                    '$attachment',
                    '$maternity_type'
                )");
                move_uploaded_file($maternity_attachment_tmp[$k], "uploads/" . $attachment);
                $mat_count++;
            }
        }

        // Others
        $oth_count = 0;
        $others_total = str_replace(",", "", $_POST['others_total']);
        // Array vars
        $others_requested_amount = $_POST['others_requested_amount'];
        $others_remarks = $_POST['others_remarks'];
        $others_attachment = $_FILES['others_attachment']['name'];
        $others_attachment_tmp = $_FILES['others_attachment']['tmp_name'];

        foreach ($others_requested_amount as $k => $v) {
            $amount = $v;
            if ($amount != "") {
                if ($oth_count == 0) {
                    $categories_applied .= "Others, ";
                    mysqli_query($db, "INSERT INTO tbl_benefits_total_amount VALUES('','$benefits_id','$others_total', 'Others')");
                }
                $remarks = $others_remarks[$k];
                if (empty($remarks)) {
                    $remarks = "N/A";
                }
                $attachment = md5($others_attachment[$k]);

                mysqli_query($db, "INSERT INTO tbl_benefits_form VALUES(
                    '',
                    '$benefits_id',
                    '$amount',
                    '$remarks',
                    '$attachment',
                    'Others'
                )");
                move_uploaded_file($others_attachment_tmp[$k], "uploads/" . $attachment);
                $oth_count++;
            }
        }
        mysqli_query($db, "UPDATE tbl_benefits_reimbursement SET categories_applied = '$categories_applied' WHERE ID = '$benefits_id'");
        include('phpMailer.php');
        reimbursementApplication($benefits_id);
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Submitted a Benefits Reimbursement','$datetime')");
        echo '<script>alert("Benefits Reimbursement has been submitted successfully");window.location.replace("reimbursement-list")</script>';
    }
}
if (isset($_POST['btn_delete_parking'])) {
    $parking_id = $_POST['parking_id'];
    $benefits_id = $_POST['benefits_id'];
    $parking_amount = $_POST['parking_amount'];

    $sql = mysqli_query($db, "DELETE FROM tbl_benefits_form WHERE ID = '$parking_id'");
    if ($sql) {
        deduct_benefits_amount($benefits_id, $parking_amount, 'Parking');
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> Deleted Parking Benefits data.</h4>
        </div>';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Deleted Parking for Benefits ID: BR-$benefits_id','$datetime')");
    }
}
if (isset($_POST['btn_add_parking'])) {
    $benefits_id = $_POST['benefits_id'];
    $parking_requested_amount = $_POST['parking_requested_amount'];
    $parking_remarks = $_POST['parking_remarks'];
    $parking_attachment = $_FILES['parking_attachment']['name'];
    $parking_attachment = md5($parking_attachment);
    $parking_attachment_tmp = $_FILES['parking_attachment']['tmp_name'];

    $sql = mysqli_query($db, "INSERT INTO tbl_benefits_form VALUES(
        '',
        '$benefits_id',
        '$parking_requested_amount',
        '$parking_remarks',
        '$parking_attachment',
        'Parking'
    )");
    if ($sql) {
        add_benefits_amount($benefits_id, $parking_requested_amount, 'Parking');
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> Added Benefits Parking data.</h4>
        </div>
      ';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Added Parking for Benefits ID: BR-$benefits_id','$datetime')");
    }
    move_uploaded_file($parking_attachment_tmp, "uploads/" . $parking_attachment);
}
if (isset($_POST['btn_delete_gas'])) {
    $gas_id = $_POST['gas_id'];
    $benefits_id = $_POST['benefits_id'];
    $gas_amount = $_POST['gas_amount'];

    $sql = mysqli_query($db, "DELETE FROM tbl_benefits_form WHERE ID = '$gas_id'");
    if ($sql) {
        deduct_benefits_amount($benefits_id, $gas_amount, 'Gas');
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> Deleted Gasoline Benefits data.</h4>
        </div>
      ';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Deleted Gasoline for Benefits ID: BR-$benefits_id','$datetime')");
    }
}
if (isset($_POST['btn_add_gas'])) {
    $benefits_id = $_POST['benefits_id'];
    $gas_requested_amount = $_POST['gas_requested_amount'];
    $gas_remarks = $_POST['gas_remarks'];
    $gas_attachment = $_FILES['gas_attachment']['name'];
    $gas_attachment = md5($gas_attachment);
    $gas_attachment_tmp = $_FILES['gas_attachment']['tmp_name'];

    $sql = mysqli_query($db, "INSERT INTO tbl_benefits_form VALUES(
        '',
        '$benefits_id',
        '$gas_requested_amount',
        '$gas_remarks',
        '$gas_attachment',
        'Gas'
    )");
    if ($sql) {
        add_benefits_amount($benefits_id, $gas_requested_amount, 'Gas');
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> Added Benefits Gasoline data.</h4>
        </div>
      ';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Added Gasoline for Benefits ID: BR-$benefits_id','$datetime')");
    }
    move_uploaded_file($gas_attachment_tmp, "uploads/" . $gas_attachment);
}
if (isset($_POST['update_requested_liters'])) {
    $benefits_id = $_POST['benefits_id'];
    $requested_liters = $_POST['requested_liters'];
    $sql = mysqli_query($db, "UPDATE tbl_benefits_gasoline_details SET requested_liters = '$requested_liters' WHERE benefits_id = '$benefits_id'");
    if ($sql) {
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> Updated Requested Liters from Gasoline Benefits</h4>
        </div>';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Updated Requested Liters for Benefits ID: BR-$benefits_id','$datetime')");
    }
}
if (isset($_POST['btn_delete_car'])) {
    $car_id = $_POST['car_id'];
    $benefits_id = $_POST['benefits_id'];
    $car_amount = $_POST['car_amount'];

    $sql = mysqli_query($db, "DELETE FROM tbl_benefits_form WHERE ID = '$car_id'");
    if ($sql) {
        deduct_benefits_amount($benefits_id, $car_amount, 'Car');
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> Deleted Car Benefits data.</h4>
        </div>
      ';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Deleted Car Maintenance for Benefits ID: BR-$benefits_id','$datetime')");
    }
}
if (isset($_POST['btn_add_car'])) {
    $benefits_id = $_POST['benefits_id'];
    $car_requested_amount = $_POST['car_requested_amount'];
    $car_remarks = $_POST['car_remarks'];
    $car_attachment = $_FILES['car_attachment']['name'];
    $car_attachment = md5($car_attachment);
    $car_attachment_tmp = $_FILES['car_attachment']['tmp_name'];

    $sql = mysqli_query($db, "INSERT INTO tbl_benefits_form VALUES(
        '',
        '$benefits_id',
        '$car_requested_amount',
        '$car_remarks',
        '$car_attachment',
        'Car'
    )");
    if ($sql) {
        add_benefits_amount($benefits_id, $car_requested_amount, 'Car');
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> Added Car Benefits data.</h4>
        </div>';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Added Car Maintenance for Benefits ID: BR-$benefits_id','$datetime')");
    }
    move_uploaded_file($car_attachment_tmp, "uploads/" . $car_attachment);
}
if (isset($_POST['btn_delete_medical'])) {
    $medical_id = $_POST['medical_id'];
    $benefits_id = $_POST['benefits_id'];
    $medical_amount = $_POST['medical_amount'];

    $sql = mysqli_query($db, "DELETE FROM tbl_benefits_form WHERE ID = '$medical_id'");
    if ($sql) {
        deduct_benefits_amount($benefits_id, $medical_amount, 'Medical');
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> Deleted Medical Benefits data.</h4>
        </div>';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Deleted Medical for Benefits ID: BR-$benefits_id','$datetime')");
    }
}
if (isset($_POST['btn_add_medical'])) {
    $benefits_id = $_POST['benefits_id'];
    $medical_requested_amount = $_POST['medical_requested_amount'];
    $medical_remarks = $_POST['medical_remarks'];
    $medical_attachment = $_FILES['medical_attachment']['name'];
    $medical_attachment = md5($medical_attachment);
    $medical_attachment_tmp = $_FILES['medical_attachment']['tmp_name'];

    $sql = mysqli_query($db, "INSERT INTO tbl_benefits_form VALUES(
        '',
        '$benefits_id',
        '$medical_requested_amount',
        '$medical_remarks',
        '$medical_attachment',
        'Medical'
    )");
    if ($sql) {
        add_benefits_amount($benefits_id, $medical_requested_amount, 'Medical');
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> Added Medical Benefits data.</h4>
        </div>';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Added Medical for Benefits ID: BR-$benefits_id','$datetime')");
    }
    move_uploaded_file($medical_attachment_tmp, "uploads/" . $medical_attachment);
}
if (isset($_POST['btn_delete_gym'])) {
    $gym_id = $_POST['gym_id'];
    $benefits_id = $_POST['benefits_id'];
    $gym_amount = $_POST['gym_amount'];

    $sql = mysqli_query($db, "DELETE FROM tbl_benefits_form WHERE ID = '$gym_id'");
    if ($sql) {
        deduct_benefits_amount($benefits_id, $gym_amount, 'Gym');
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> Deleted Gym Benefits data.</h4>
        </div>
      ';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Deleted Gym for Benefits ID: BR-$benefits_id','$datetime')");
    }
}
if (isset($_POST['btn_add_gym'])) {
    $benefits_id = $_POST['benefits_id'];
    $gym_requested_amount = $_POST['gym_requested_amount'];
    $gym_remarks = $_POST['gym_remarks'];
    $gym_attachment = $_FILES['gym_attachment']['name'];
    $gym_attachment = md5($gym_attachment);
    $gym_attachment_tmp = $_FILES['gym_attachment']['tmp_name'];

    $sql = mysqli_query($db, "INSERT INTO tbl_benefits_form VALUES(
        '',
        '$benefits_id',
        '$gym_requested_amount',
        '$gym_remarks',
        '$gym_attachment',
        'Gym'
    )");
    if ($sql) {
        add_benefits_amount($benefits_id, $gym_requested_amount, 'Gym');
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> Added Gym Benefits data.</h4>
        </div>
      ';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Added Gym for Benefits ID: BR-$benefits_id','$datetime')");
    }
    move_uploaded_file($gym_attachment_tmp, "uploads/" . $gym_attachment);
}
if (isset($_POST['btn_delete_optical'])) {
    $optical_id = $_POST['optical_id'];
    $benefits_id = $_POST['benefits_id'];
    $optical_amount = $_POST['optical_amount'];

    $sql = mysqli_query($db, "DELETE FROM tbl_benefits_form WHERE ID = '$optical_id'");
    if ($sql) {
        deduct_benefits_amount($benefits_id, $optical_amount, 'Optical');
        $res = '<div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="fa fa-check"></i> Deleted Optical Benefits data.</h4>
    </div>
    ';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Deleted Optical for Benefits ID: BR-$benefits_id','$datetime')");
    }
}
if (isset($_POST['btn_add_optical'])) {
    $benefits_id = $_POST['benefits_id'];
    $optical_requested_amount = $_POST['optical_requested_amount'];
    $optical_remarks = $_POST['optical_remarks'];
    $optical_attachment = $_FILES['optical_attachment']['name'];
    $optical_attachment = md5($optical_attachment);
    $optical_attachment_tmp = $_FILES['optical_attachment']['tmp_name'];

    $sql = mysqli_query($db, "INSERT INTO tbl_benefits_form VALUES(
    '',
    '$benefits_id',
    '$optical_requested_amount',
    '$optical_remarks',
    '$optical_attachment',
    'Optical'
    )");
    if ($sql) {
        add_benefits_amount($benefits_id, $optical_requested_amount, 'Optical');
        $res = '<div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="fa fa-check"></i> Added Optical Benefits data.</h4>
    </div>
    ';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Added Optical for Benefits ID: BR-$benefits_id','$datetime')");
    }
    move_uploaded_file($optical_attachment_tmp, "uploads/" . $optical_attachment);
}
if (isset($_POST['btn_delete_cep'])) {
    $cep_id = $_POST['cep_id'];
    $benefits_id = $_POST['benefits_id'];
    $cep_amount = $_POST['cep_amount'];

    $sql = mysqli_query($db, "DELETE FROM tbl_benefits_form WHERE ID = '$cep_id'");
    if ($sql) {
        $get_cep_details = mysqli_query($db, "SELECT * FROM tbl_benefits_form WHERE benefits_id = '$benefits_id' AND cat = 'CEP'");
        if (mysqli_num_rows($get_cep_details) == 0) {
            mysqli_query($db, "DELETE FROM tbl_cep_bond WHERE benefits_id = '$benefits_id'");
        }
        deduct_benefits_amount($benefits_id, $cep_amount, 'CEP');

        $get_cep_total_amount = mysqli_query($db, "SELECT * FROM tbl_benefits_total_amount WHERE benefits_id = '$benefits_id' AND cat = 'CEP'");
        $cep_total_amount = mysqli_fetch_assoc($get_cep_total_amount);

        $check_cep = mysqli_query($db, "SELECT * FROM tbl_cep_bond WHERE benefits_id = '$benefits_id'");
        if (mysqli_num_rows($check_cep) > 0) {
            $cep_details = mysqli_fetch_assoc($check_cep);
            $type = $cep_details['type'];
            $premise = $cep_details['premise'];
            $amount = round(str_replace(",", "", $cep_total_amount['total_amount']), 0);
            $bond = 0;

            if ($type == "CEP" and $premise == "Local") {
                $bond = (int) $amount / 8000;
            }
            if ($type == "CEP" and $premise == "International") {
                $bond = (int) $amount / 15000;
            }
            if ($type == "Training" and $premise == "Local") {
                $bond = 0;
            }
            if ($type == "Training" and $premise == "International") {
                $bond = (int) $amount / 15000;
            }
            $bond = number_format($bond, 1);
            mysqli_query($db, "UPDATE tbl_cep_bond SET `type` = '$type', premise = '$premise', bond = '$bond', remaining = '$bond' WHERE benefits_id = '$benefits_id'");
        }

        $res = '<div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="fa fa-check"></i> Deleted CEP Benefits data.</h4>
    </div>
    ';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Deleted CEP for Benefits ID: BR-$benefits_id','$datetime')");
    }
}
if (isset($_POST['btn_add_cep'])) {
    $benefits_id = $_POST['benefits_id'];
    $cep_requested_amount = $_POST['cep_requested_amount'];
    $cep_remarks = $_POST['cep_remarks'];
    $cep_attachment = $_FILES['cep_attachment']['name'];
    $cep_attachment = md5($cep_attachment);
    $cep_attachment_tmp = $_FILES['cep_attachment']['tmp_name'];

    $sql = mysqli_query($db, "INSERT INTO tbl_benefits_form VALUES(
    '',
    '$benefits_id',
    '$cep_requested_amount',
    '$cep_remarks',
    '$cep_attachment',
    'CEP'
    )");
    if ($sql) {
        add_benefits_amount($benefits_id, $cep_requested_amount, 'CEP');

        $get_cep_total_amount = mysqli_query($db, "SELECT * FROM tbl_benefits_total_amount WHERE benefits_id = '$benefits_id' AND cat = 'CEP'");
        $cep_total_amount = mysqli_fetch_assoc($get_cep_total_amount);

        $check_cep = mysqli_query($db, "SELECT * FROM tbl_cep_bond WHERE benefits_id = '$benefits_id'");
        if (mysqli_num_rows($check_cep) > 0) {
            $cep_details = mysqli_fetch_assoc($check_cep);
            $type = $cep_details['type'];
            $premise = $cep_details['premise'];
            $amount = round(str_replace(",", "", $cep_total_amount['total_amount']), 0);
            $bond = 0;

            if ($type == "CEP" and $premise == "Local") {
                $bond = (int) $amount / 8000;
            }
            if ($type == "CEP" and $premise == "International") {
                $bond = (int) $amount / 15000;
            }
            if ($type == "Training" and $premise == "Local") {
                $bond = 0;
            }
            if ($type == "Training" and $premise == "International") {
                $bond = (int) $amount / 15000;
            }
            $bond = number_format($bond, 1);
            mysqli_query($db, "UPDATE tbl_cep_bond SET `type` = '$type', premise = '$premise', bond = '$bond', remaining = '$bond' WHERE benefits_id = '$benefits_id'");
        }
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> Added CEP Benefits data.</h4>
        </div>
    ';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Added CEP for Benefits ID: BR-$benefits_id','$datetime')");
    }
    move_uploaded_file($cep_attachment_tmp, "uploads/" . $cep_attachment);
}
if (isset($_POST['update_cep_bond'])) {
    $benefits_id = $_POST['benefits_id'];
    $type = $_POST['cep_type'];
    $premise = $_POST['cep_premise'];
    $amount = round(str_replace(",", "", $_POST['cep_total']), 0);
    $bond = 0;

    if ($type == "CEP" and $premise == "Local") {
        $bond = (int) $amount / 8000;
    }
    if ($type == "CEP" and $premise == "International") {
        $bond = (int) $amount / 15000;
    }
    if ($type == "Training" and $premise == "Local") {
        $bond = 0;
    }
    if ($type == "Training" and $premise == "International") {
        $bond = (int) $amount / 15000;
    }
    $bond = number_format($bond, 1);
    $sql = mysqli_query($db, "SELECT * FROM tbl_cep_bond WHERE benefits_id = '$benefits_id'");
    if (mysqli_num_rows($sql) > 0) {
        mysqli_query($db, "UPDATE tbl_cep_bond SET `type` = '$type', premise = '$premise', bond = '$bond', remaining = '$bond' WHERE benefits_id = '$benefits_id'");
    } else {
        mysqli_query($db, "INSERT INTO tbl_cep_bond VALUES('','$benefits_id','$type','$premise','$bond','$bond')");
    }
    $at_name = $_SESSION['hris_account_name'];
    mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Updated CEP Bond for Benefits ID: BR-$benefits_id','$datetime')");
}
if (isset($_POST['btn_delete_club'])) {
    $club_id = $_POST['club_id'];
    $benefits_id = $_POST['benefits_id'];
    $club_amount = $_POST['club_amount'];

    $sql = mysqli_query($db, "DELETE FROM tbl_benefits_form WHERE ID = '$club_id'");
    if ($sql) {
        deduct_benefits_amount($benefits_id, $club_amount, 'Club');
        $res = '<div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="fa fa-check"></i> Deleted Club Benefits data.</h4>
    </div>
    ';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Deleted Club Membership for Benefits ID: BR-$benefits_id','$datetime')");
    }
}
if (isset($_POST['btn_add_club'])) {
    $benefits_id = $_POST['benefits_id'];
    $club_requested_amount = $_POST['club_requested_amount'];
    $club_remarks = $_POST['club_remarks'];
    $club_attachment = $_FILES['club_attachment']['name'];
    $club_attachment = md5($club_attachment);
    $club_attachment_tmp = $_FILES['club_attachment']['tmp_name'];

    $sql = mysqli_query($db, "INSERT INTO tbl_benefits_form VALUES(
    '',
    '$benefits_id',
    '$club_requested_amount',
    '$club_remarks',
    '$club_attachment',
    'Club'
    )");
    if ($sql) {
        add_benefits_amount($benefits_id, $club_requested_amount, 'Club');
        $res = '<div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="fa fa-check"></i> Added Club Benefits data.</h4>
    </div>
    ';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Added Club Membership for Benefits ID: BR-$benefits_id','$datetime')");
    }
    move_uploaded_file($club_attachment_tmp, "uploads/" . $club_attachment);
}
if (isset($_POST['btn_delete_maternity'])) {
    $maternity_id = $_POST['maternity_id'];
    $benefits_id = $_POST['benefits_id'];
    $maternity_amount = $_POST['maternity_amount'];

    $sql = mysqli_query($db, "DELETE FROM tbl_benefits_form WHERE ID = '$maternity_id'");
    if ($sql) {
        deduct_benefits_amount($benefits_id, $maternity_amount, 'Maternity');
        $res = '<div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="fa fa-check"></i> Deleted Maternity Benefits data.</h4>
    </div>
    ';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Deleted Maternity for Benefits ID: BR-$benefits_id','$datetime')");
    }
}
if (isset($_POST['btn_add_maternity'])) {
    $benefits_id = $_POST['benefits_id'];
    $maternity_requested_amount = $_POST['maternity_requested_amount'];
    $maternity_remarks = $_POST['maternity_remarks'];
    $maternity_attachment = $_FILES['maternity_attachment']['name'];
    $maternity_attachment = md5($maternity_attachment);
    $maternity_attachment_tmp = $_FILES['maternity_attachment']['tmp_name'];

    $sql = mysqli_query($db, "INSERT INTO tbl_benefits_form VALUES(
    '',
    '$benefits_id',
    '$maternity_requested_amount',
    '$maternity_remarks',
    '$maternity_attachment',
    'Maternity'
    )");
    if ($sql) {
        add_benefits_amount($benefits_id, $maternity_requested_amount, 'Maternity');
        $res = '<div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="fa fa-check"></i> Added Maternity Benefits data.</h4>
    </div>
    ';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Added Maternity for Benefits ID: BR-$benefits_id','$datetime')");
    }
    move_uploaded_file($maternity_attachment_tmp, "uploads/" . $maternity_attachment);
}
if (isset($_POST['btn_delete_others'])) {
    $others_id = $_POST['others_id'];
    $benefits_id = $_POST['benefits_id'];
    $others_amount = $_POST['others_amount'];

    $sql = mysqli_query($db, "DELETE FROM tbl_benefits_form WHERE ID = '$others_id'");
    if ($sql) {
        deduct_benefits_amount($benefits_id, $others_amount, 'Others');
        $res = '<div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="fa fa-check"></i> Deleted Others Benefits data.</h4>
    </div>
    ';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Deleted Others for Benefits ID: BR-$benefits_id','$datetime')");
    }
}
if (isset($_POST['btn_add_others'])) {
    $benefits_id = $_POST['benefits_id'];
    $others_requested_amount = $_POST['others_requested_amount'];
    $others_remarks = $_POST['others_remarks'];
    $others_attachment = $_FILES['others_attachment']['name'];
    $others_attachment = md5($others_attachment);
    $others_attachment_tmp = $_FILES['others_attachment']['tmp_name'];

    $sql = mysqli_query($db, "INSERT INTO tbl_benefits_form VALUES(
    '',
    '$benefits_id',
    '$others_requested_amount',
    '$others_remarks',
    '$others_attachment',
    'Others'
    )");
    if ($sql) {
        add_benefits_amount($benefits_id, $others_requested_amount, 'Others');
        $res = '<div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="fa fa-check"></i> Added Others Benefits data.</h4>
    </div>
    ';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Added Others for Benefits ID: BR-$benefits_id','$datetime')");
    }
    move_uploaded_file($others_attachment_tmp, "uploads/" . $others_attachment);
}
if (isset($_POST['btn_update_benefits_reimbursement'])) {
    $benefits_id = $_POST['id'];
    $company_id = $_POST['company_id'];
    $status = $_POST['status'];
    $employee_number = $_POST['payee'];
    $payment_for = $_POST['payment_for'];
    $special_instruction = $_POST['special_instruction'];
    $hr_remarks = $_POST['hr_remarks'];

    $next_approver = get_next_benefits_approver($company_id, $benefits_id);
    $last_approver = get_last_approver($company_id);
    include('phpMailer.php');
    reimbursementApplicationStatus($benefits_id);
    if ($status == $last_approver) {
        $sql = mysqli_query($db, "UPDATE tbl_benefits_reimbursement SET 
        payment_for = '$payment_for', special_instruction = '$special_instruction', hr_remarks = '$hr_remarks', `status` = 'Approved'
        WHERE ID = '$benefits_id'");

        // Update balances
        $get_total_amounts = mysqli_query($db, "SELECT * FROM tbl_benefits_total_amount WHERE benefits_id = '$benefits_id'");
        while ($row = mysqli_fetch_assoc($get_total_amounts)) {
            $cat = $row['cat'];
            $amount = $row['total_amount'];
            if ($cat == "Car") {
                mysqli_query($db, "UPDATE tbl_benefits_car_balance SET balance = balance - $amount, used = used + $amount WHERE employee_number = '$employee_number'");
            }
            if ($cat == "CEP") {
                mysqli_query($db, "UPDATE tbl_benefits_cep_balance SET balance = balance - $amount, used = used + $amount WHERE employee_number = '$employee_number'");
            }
            if ($cat == "Gas") {
                $gas_amount = get_requested_liters($benefits_id);
                mysqli_query($db, "UPDATE tbl_benefits_cep_balance SET balance = balance - $gas_amount, used = used + $gas_amount WHERE employee_number = '$employee_number'");
            }
            if ($cat == "Gym") {
                $aa = mysqli_query($db, "UPDATE tbl_benefits_gym_balance SET balance = balance - $amount, used = used + $amount WHERE employee_number = '$employee_number'");
            }
            if ($cat == "Medical") {
                mysqli_query($db, "UPDATE tbl_benefits_medical_balance SET balance = balance - $amount, used = used + $amount WHERE employee_number = '$employee_number'");
            }
            if ($cat == "Optical") {
                mysqli_query($db, "UPDATE tbl_benefits_optical_balance SET balance = balance - $amount, used = used + $amount WHERE employee_number = '$employee_number'");
            }
        }
        echo '<script>alert("Benefits Reimbursement has been fully approved : BR-' . format_transaction_id($benefits_id) . '");window.location.replace("reimbursement-list")</script>';
    } else {
        $sql = mysqli_query($db, "UPDATE tbl_benefits_reimbursement SET 
        payment_for = '$payment_for', special_instruction = '$special_instruction', hr_remarks = '$hr_remarks', `status` = '$next_approver'
        WHERE ID = '$benefits_id'");
        echo '<script>alert("Benefits Reimbursement has been updated : BR-' . format_transaction_id($benefits_id) . '");window.location.replace("reimbursement-list")</script>';
    }
    $at_name = $_SESSION['hris_account_name'];
    mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Approved Benefits ID: BR-$benefits_id','$datetime')");
}
if (isset($_POST['btn_cancel_benefits'])) {
    $benefits_id = $_POST['id'];

    $sql = mysqli_query($db, "UPDATE tbl_benefits_reimbursement SET `status` = 'Cancelled' WHERE ID = '$benefits_id'");
    if ($sql) {
        include('phpMailer.php');
        reimbursementApplicationCancel($benefits_id);
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Cancelled Benefits Reimbursement ID: BR-$benefits_id','$datetime')");
        echo '<script>alert("Benefits Reimbursement has been cancelled : BR-' . format_transaction_id($benefits_id) . '");window.location.replace("reimbursement-list")</script>';
    }
}
if (isset($_POST['btn_decline_benefits_reimbursement'])) {
    $benefits_id = $_POST['id'];

    $sql = mysqli_query($db, "UPDATE tbl_benefits_reimbursement SET `status` = 'Declined' WHERE ID = '$benefits_id'");
    if ($sql) {
        include('phpMailer.php');
        reimbursementApplicationDecline($benefits_id);
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Declined Benefits Reimbursement ID: BR-$benefits_id','$datetime')");
        echo '<script>alert("Benefits Reimbursement has been Declined : BR-' . format_transaction_id($benefits_id) . '");window.location.replace("reimbursement-list")</script>';
    }
}
if (isset($_POST['btn_update_request_benefits'])) {
    $benefits_id = $_POST['id'];
    $em_status = $_POST['em_status'];

    $sql = mysqli_query($db, "UPDATE tbl_benefits_reimbursement SET `status` = 'Update Requested' WHERE ID = '$benefits_id'");
    if ($sql) {
        include('phpMailer.php');
        reimbursementApplicationRequestUpdate($benefits_id, $em_status);
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Requested an update for Benefits Reimbursement ID: BR-$benefits_id','$datetime')");
        echo '<script>alert("Requested an update for BR-' . format_transaction_id($benefits_id) . '");window.location.replace("reimbursement-list")</script>';
    }
}
if (isset($_POST['btn_user_update_benefits'])) {
    $benefits_id = $_POST['id'];
    $payment_for = $_POST['payment_for'];
    $special_instruction = $_POST['special_instruction'];

    $sql = mysqli_query($db, "UPDATE tbl_benefits_reimbursement SET 
        payment_for = '$payment_for', special_instruction = '$special_instruction', `status` = '1'
        WHERE ID = '$benefits_id'");
    if ($sql) {
        include('phpMailer.php');
        reimbursementApplication($benefits_id);
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Updated Benefits Reimbursement ID: BR-$benefits_id','$datetime')");
        echo '<script>alert("Benefits Reimbursement has been updated : BR-' . format_transaction_id($benefits_id) . '");window.location.replace("reimbursement-list")</script>';
    }
}
if (isset($_POST['btn_update_benefits_balances'])) {
    $employee_number = $_POST['employee_number'];
    $car_maintenance = $_POST['car_maintenance'];
    $cep = $_POST['cep'];
    $gas = $_POST['gas'];
    $gym = $_POST['gym'];
    $medical = $_POST['medical'];
    $optical = $_POST['optical'];

    mysqli_query($db, "UPDATE tbl_benefits_car_balance SET balance = $car_maintenance WHERE employee_number = '$employee_number'");
    mysqli_query($db, "UPDATE tbl_benefits_cep_balance SET balance = $cep WHERE employee_number = '$employee_number'");
    mysqli_query($db, "UPDATE tbl_benefits_gas_balance SET balance = $gas WHERE employee_number = '$employee_number'");
    mysqli_query($db, "UPDATE tbl_benefits_gym_balance SET balance = $gym WHERE employee_number = '$employee_number'");
    mysqli_query($db, "UPDATE tbl_benefits_medical_balance SET balance = $medical WHERE employee_number = '$employee_number'");
    mysqli_query($db, "UPDATE tbl_benefits_optical_balance SET balance = $optical WHERE employee_number = '$employee_number'");
    $at_name = $_SESSION['hris_account_name'];
    mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Updated Benefits Balances for Employee: $employee_number','$datetime')");
    echo '<script>alert("Benefits balances has been updated for employee #' . $employee_number . '");window.location.replace("benefits-balances")</script>';
}
if (isset($_POST['btn_update_benefits_balances_201'])) {
    $employee_number = $_POST['employee_number'];
    $car_maintenance = $_POST['car_maintenance'];
    $cep = $_POST['cep'];
    $gas = $_POST['gas'];
    $gym = $_POST['gym'];
    $medical = $_POST['medical'];
    $optical = $_POST['optical'];

    mysqli_query($db, "UPDATE tbl_benefits_car_balance SET balance = $car_maintenance WHERE employee_number = '$employee_number'");
    mysqli_query($db, "UPDATE tbl_benefits_cep_balance SET balance = $cep WHERE employee_number = '$employee_number'");
    mysqli_query($db, "UPDATE tbl_benefits_gas_balance SET balance = $gas WHERE employee_number = '$employee_number'");
    mysqli_query($db, "UPDATE tbl_benefits_gym_balance SET balance = $gym WHERE employee_number = '$employee_number'");
    mysqli_query($db, "UPDATE tbl_benefits_medical_balance SET balance = $medical WHERE employee_number = '$employee_number'");
    mysqli_query($db, "UPDATE tbl_benefits_optical_balance SET balance = $optical WHERE employee_number = '$employee_number'");

    $res = '<div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="fa fa-check"></i> Benefits Balances has been updated.</h4>
    </div>
    ';
    $at_name = $_SESSION['hris_account_name'];
    mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Updated Benefits Balances for Employee: $employee_number','$datetime')");
}
if (isset($_POST['btn_loan_application'])) {
    $company_id = $_POST['company_id'];
    $employee_number = $_POST['employee_number'];
    $type = $_POST['type'];
    $terms = $_POST['terms'];
    $amount = $_POST['amount'];
    $remarks = $_POST['remarks'];
    $attachment = $_FILES['attachment']['name'];
    $attachment = md5($attachment);
    $attachment_tmp = $_FILES['attachment']['tmp_name'];
    $amount_approved = 0;
    $monthly_deduction = 0;
    $date_approved = '';
    $start_date = '';
    $hr_remarks = '';
    $status = '1';

    $sql = mysqli_query($db, "INSERT INTO tbl_loan_application VALUES(
        '',
        '$company_id',
        '$employee_number',
        '$amount',
        '$type',
        '$terms',
        '$attachment',
        '$remarks',
        '$amount_approved',
        '$monthly_deduction',
        '$date_approved',
        '$start_date',
        '$hr_remarks',
        '$datetime',
        '$status'
    )");
    if ($sql) {
        $loan_id = mysqli_insert_id($db);
        move_uploaded_file($attachment_tmp, "uploads/" . $attachment);
        include('phpMailer.php');
        loanApplication($loan_id);
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Submitted Salary Loan Application','$datetime')");
        echo '<script>alert("Salary Loan application has been submitted for employee #' . $employee_number . '");window.location.replace("loan-application-list")</script>';
    }
}
if (isset($_POST['edit_company'])) {
    $id = $_POST['id'];
    $company_name = $_POST['company_name'];

    $sql = mysqli_query($db, "UPDATE tbl_companies SET company_name = '$company_name' WHERE ID = '$id'");
    if ($sql) {
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> Company Name has been updated.</h4>
        </div>';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Edited Company details: $id','$datetime')");
    }
}
if (isset($_POST['btn_cancel_loan'])) {
    $id = $_POST['id'];

    $sql = mysqli_query($db, "UPDATE tbl_loan_application SET `status` = 'Cancelled' WHERE ID = '$id'");
    if ($sql) {
        include('phpMailer.php');
        loanApplicationCancel($id);
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Cancelled Salary Loan Application: $id','$datetime')");
        echo '<script>alert("Salary Loan application has been cancelled");window.location.replace("loan-application-list")</script>';
    }
}
if (isset($_POST['btn_approve_loan'])) {
    $id = $_POST['id'];
    $company_id = $_POST['company_id'];
    $old_status = $_POST['status'];
    $type = $_POST['type'];
    $terms = $_POST['terms'];
    $amount = $_POST['amount'];
    $amount_approved = $_POST['amount_approved'];
    $monthly_deduction = $_POST['monthly_deduction'];
    $startDate = $_POST['startDate'];
    $hr_remarks = $_POST['hr_remarks'];

    $loan_info = get_salary_loan_info($id);

    $last_approver = get_last_approver_loan($company_id);
    $next_approver = get_next_benefits_approver_loan($company_id, $id);
    include('phpMailer.php');
    loanApplicationStatus($id);

    if ($old_status == $last_approver) {
        mysqli_query($db, "UPDATE tbl_loan_application SET 
        amount_approved = '$amount_approved', 
        monthly_deduction = '$monthly_deduction', 
        date_approved = '$datetime',
        `start_date` = '$startDate',
        hr_remarks = '$hr_remarks',
        `status` = 'Approved'
        WHERE ID = '$id'");
        $y = 1;
        $year = date("Y");
        $month_sched = date("$year-$startDate-01");
        while ($y <= $terms) {
            $month_line_15 = strtotime($month_sched . " +14 day");
            $day = date("Y-m-d", $month_line_15);
            mysqli_query($db, "INSERT INTO tbl_loan_status VALUES('','$id','$monthly_deduction','$day','Unpaid')");
            $month_sched = date("Y-m-d", strtotime($month_sched . " +1month"));
            $y++;
        }
    } else {
        mysqli_query($db, "UPDATE tbl_loan_application SET 
        amount_approved = '$amount_approved', 
        monthly_deduction = '$monthly_deduction', 
        date_approved = '$datetime',
        `start_date` = '$startDate',
        hr_remarks = '$hr_remarks',
        `status` = '$next_approver'
        WHERE ID = '$id'");
    }
    $at_name = $_SESSION['hris_account_name'];
    mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Approved Salary Loan Application: $id','$datetime')");
    echo '<script>alert("Salary Loan application has been approved");window.location.replace("loan-application-list")</script>';
}
if (isset($_POST['btn_prepay'])) {
    $prepay_id = $_POST['prepay_id'];
    $loan_id = $_POST['loan_id'];
    $employee_number = $_POST['employee_number'];
    $prepay_remarks = $_POST['prepay_remarks'];
    $attachment = $_FILES['prepay_attachment']['name'];
    $attachment = md5($attachment);
    $attachment_tmp = $_FILES['prepay_attachment']['tmp_name'];

    $sql = mysqli_query($db, "INSERT INTO tbl_prepay VALUES(
        '',
        '$employee_number',
        '$prepay_id',
        '$loan_id',
        '$prepay_remarks',
        '$attachment',
        'Prepay Pending',
        '$datetime')");
    if ($sql) {
        move_uploaded_file($attachment_tmp, "uploads/" . $attachment);
        mysqli_query($db, "UPDATE `tbl_loan_status` SET `status` = 'Prepay Pending' WHERE `tbl_loan_status`.`ID` = '$prepay_id'");
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> Prepay has been submitted successfully.</h4>
        </div>';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Submitted a prepay for Salary Loan ID: LA-$loan_id','$datetime')");
    }
}
if (isset($_POST['btn_approve_prepay'])) {
    $id = $_POST['id'];
    $prepay_id = $_POST['prepay_id'];
    mysqli_query($db, "UPDATE `tbl_loan_status` SET `status` = 'Paid' WHERE ID = '$prepay_id'");
    mysqli_query($db, "UPDATE tbl_prepay SET `status` = 'Approved' WHERE ID = '$id'");
    $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> Prepay has been approved.</h4>
        </div>';
    $at_name = $_SESSION['hris_account_name'];
    mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Approved a prepay ID: $prepay_id','$datetime')");
}
if (isset($_POST['btn_decline_prepay'])) {
    $id = $_POST['id'];
    $prepay_id = $_POST['prepay_id'];
    mysqli_query($db, "UPDATE `tbl_loan_status` SET `status` = 'Unpaid' WHERE ID = '$prepay_id'");
    mysqli_query($db, "UPDATE tbl_prepay SET `status` = 'Declined' WHERE ID = '$id'");
    $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> Prepay has been declined.</h4>
        </div>';
    $at_name = $_SESSION['hris_account_name'];
    mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Declined a prepay ID: $prepay_id','$datetime')");
}
if (isset($_POST['btn_delete_certificate_approver'])) {
    $id = $_POST['id'];

    $sql = mysqli_query($db, "DELETE FROM tbl_certificate_requests_approvers WHERE ID = '$id'");
    if ($sql) {
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> An approver has been deleted.</h4>
        </div>';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Deleted Certificate Request Approver: $id','$datetime')");
    }
}
if (isset($_POST['add_certificate_approver'])) {
    $company_id = $_POST['company_id'];
    $employee_number = $_POST['employee_number'];

    $sql = mysqli_query($db, "INSERT INTO tbl_certificate_requests_approvers VALUES('','$company_id','$employee_number');");
    if ($sql) {
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i>Approver has been added.</h4>
        </div>';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Added Certificate Request Approver: $employee_number','$datetime')");
    }
}
if (isset($_POST['add_training_approver'])) {
    $company_id = $_POST['company_id'];
    $employee_number = $_POST['employee_number'];

    $sql = mysqli_query($db, "INSERT INTO tbl_training_approvers VALUES('','$company_id','$employee_number');");
    if ($sql) {
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i>Approver has been added.</h4>
        </div>';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Added Training Approver: $employee_number','$datetime')");
    }
}
if (isset($_POST['btn_delete_training_approver'])) {
    $id = $_POST['id'];

    $sql = mysqli_query($db, "DELETE FROM tbl_training_approvers WHERE ID = '$id'");
    if ($sql) {
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> An approver has been deleted.</h4>
        </div>';
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Deleted Training Approver: $id','$datetime')");
    }
}
if (isset($_POST['btn_generate_report'])) {
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    $category = $_POST['category'];
    $company = $_POST['company'];

    $at_name = $_SESSION['hris_account_name'];
    mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Generated a Report for $category','$datetime')");

    $sql = '';
    $headers = '';
    $data = array();

    if ($category == "Leave Applications") {
        $sql = mysqli_query($db, "SELECT * FROM tbl_leave_requests
        WHERE date_filed BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59' AND company_id = '$company'
        ORDER BY ID DESC");

        $headers = array(
            'ID',
            'Company ID',
            'Requestor',
            'Delegated Employee',
            'Leave Type',
            'Start Date',
            'End Date',
            'Total Day',
            'Reason',
            'Duration',
            'Attachment',
            'Approver',
            'Approver Remarks',
            'Status',
            'Date Filed',
            'Cancellation Reason',
        );
    }
    if ($category == "OT Applications") {
        $sql = mysqli_query($db, "SELECT * FROM tbl_ot_application
        WHERE date_created BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59' AND company_id = '$company'
        ORDER BY ID DESC");

        $headers = array(
            'ID',
            'Company ID',
            'Employee Number',
            'Month of OT',
            'Total hours',
            'Remarks',
            'Attachment Name',
            'Approver Remarks',
            'Approver Attachment name',
            'Status',
            'Date Created'
        );
    }
    if ($category == "Certificate Requests") {
        $sql = mysqli_query($db, "SELECT * FROM tbl_certificate_requests
        WHERE date_created BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59' AND company_id = '$company'
        ORDER BY ID DESC");

        $headers = array(
            'ID',
            'Company ID',
            'Employee Number',
            'Requested by',
            'Certificate Type',
            'Date Required',
            'Purpose',
            'Remarks',
            'HR Remarks',
            'Acknowledged by',
            'Status',
            'Date Created'
        );
    }
    if ($category == "201 File") {
        $sql = mysqli_query($db, "SELECT 
        t.employee_number,
        t.company_email,
        t.last_name,
        t.first_name,
        t.middle_name,
        t.`address`,
        t.personal_email,
        t.contact_number,
        t.account_name,
        t.date_of_birth,
        t.age,
        t.gender,
        t.citizenship,
        t.civil_status,
        t.spouse_name,
        t1.position_number,
        t1.position_title,
        t1.job_description,
        t1.date_hired,
        t2.company_name,
        t3.department,
        t4.job_grade_set,
        t5.job_grade,
        t1.employment_status,
        t1.account_status,
        t1.approver,
        t1.reporting_to,
        t1.vendor_id,
        t1.filing,
        t1.is_approver,
        t6.school,
        t6.from_date,
        t6.to_date
        FROM tbl_personal_information t
        INNER JOIN tbl_employment_information t1
        ON t.employee_number = t1.employee_number
        INNER JOIN tbl_companies t2
        ON t1.company = t2.ID
        INNER JOIN tbl_departments t3
        ON t1.department = t3.ID
        INNER JOIN tbl_job_grade_set t4
        ON t1.job_grade_set = t4.ID
        INNER JOIN tbl_job_grade t5
        ON t1.job_grade = t5.ID
        INNER JOIN tbl_post_graduate t6
        ON t.employee_number = t6.employee_number
        WHERE t.date_created BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59' AND t1.company = '$company'
        ORDER BY t.ID DESC");

        $headers = array(
            'Employee Number',
            'Company Email',
            'Last Name',
            'First Name',
            'Middle Name',
            'Address',
            'Personal Email',
            'Contact Number',
            'Account Name',
            'Date of Birth',
            'Age',
            'Gender',
            'Citizenship',
            'Civil Status',
            'Spouse Name',
            'Position Number',
            'Position Title',
            'Job Description',
            'Date Hired',
            'Company Name',
            'Department',
            'Job Grade Set',
            'Job Grade',
            'Employment Status',
            'Account Status',
            'Approver',
            'Reporting to',
            'Vendor ID',
            'Filing',
            'Is Approver',
            'Post Graduate',
            'PG From',
            'PG To',
        );
        $id_exist = array();
        $educ_exist = array();
        $contact_exist = array();
        while ($row = mysqli_fetch_assoc($sql)) {
            $empnum = $row['employee_number'];

            $get_educs = mysqli_query($db, "SELECT * FROM `tbl_college` WHERE employee_number = '$empnum'");
            $educ_count = 0;
            while ($educ = mysqli_fetch_assoc($get_educs)) {
                $educ_count++;
                $row[] = $educ['college'];
                $row[] = $educ['from_date'];
                $row[] = $educ['to_date'];
                $row[] = $educ['degree'];

                if (!in_array('College ' . $educ_count, $educ_exist)) {
                    $headers[] = 'College ' . $educ_count;
                    $headers[] = 'From ' . $educ_count;
                    $headers[] = 'To ' . $educ_count;
                    $headers[] = 'Degree ' . $educ_count;
                }
                $educ_exist[] = 'College ' . $educ_count;
            }
            // ========================

            $get_contacts = mysqli_query($db, "SELECT * FROM `tbl_emergency_contacts` WHERE employee_number = '$empnum'");
            $contact_count = 0;
            while ($cont = mysqli_fetch_assoc($get_contacts)) {
                $contact_count++;
                $row[] = $cont['contact_name'];
                $row[] = $cont['contact_number'];
                $row[] = $cont['email_address'];
                $row[] = $cont['relationship'];

                if (!in_array('Contact Name ' . $contact_count, $contact_exist)) {
                    $headers[] = 'Contact Name ' . $contact_count;
                    $headers[] = 'Contact Number ' . $contact_count;
                    $headers[] = 'Email Address ' . $contact_count;
                    $headers[] = 'Relationship ' . $contact_count;
                }
                $contact_exist[] = 'Contact Name ' . $contact_count;
            }

            // =========================
            $get_ids = mysqli_query($db, "SELECT * FROM `tbl_ids` WHERE employee_number = '$empnum'");
            $id_count = 0;
            while ($ids = mysqli_fetch_assoc($get_ids)) {
                $id_count++;
                $row[] = $ids['id_name'];
                $row[] = $ids['id_number'];

                if (!in_array('ID ' . $id_count, $id_exist)) {
                    $headers[] = 'ID ' . $id_count;
                    $headers[] = 'ID Number ' . $id_count;
                }
                $id_exist[] = 'ID ' . $id_count;
            }
            $data[] = $row;
        }
    }
    if ($category == "Salary Loan Application") {
        $sql = mysqli_query($db, "SELECT * FROM tbl_loan_application
        WHERE date_created BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59' AND company_id = '$company'
        ORDER BY ID DESC");

        $headers = array(
            'ID',
            'Company ID',
            'Employee Number',
            'Amount',
            'Type',
            'Terms',
            'Attachment',
            'Remarks',
            'Amount Approved',
            'Monthly Deductions',
            'Date Approved',
            'Start Month',
            'HR Remarks',
            'Date Created',
            'Status'
        );
    }
    if ($category == "Benefits Reimbursement") {
        $sql = mysqli_query($db, "SELECT t.*, t1.amount as cat_amount, t1.remarks, t1.cat 
        FROM tbl_benefits_reimbursement t 
        INNER JOIN tbl_benefits_form t1 
        ON t.ID = t1.benefits_id
        WHERE t.date_created BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59' AND t.company_id = '$company'
        ORDER BY ID DESC");

        $headers = array(
            'ID',
            'Company ID',
            'Requestor',
            'Payee',
            'Total Amount',
            'Payment For',
            'Special Instructions',
            'Categories Applied',
            'HR Remarks',
            'Status',
            'Date Created',
            'Amount',
            'Remarks',
            'Category'
        );
    }

    if (!$sql) die(mysqli_error($db));

    $fp = fopen('php://output', 'w');
    if ($fp && $sql) {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $category . ' report.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');
        fputcsv($fp, $headers);
        if ($category != '201 File') {
            while ($row = mysqli_fetch_assoc($sql)) {
                fputcsv($fp, array_values($row));
            }
        } else {
            foreach ($data as $k => $v) {
                fputcsv($fp, array_values($v));
            }
        }
        die;
    }
}
if (isset($_POST['btn_csv_audit_trail'])) {
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];

    $sql = mysqli_query($db, "SELECT * FROM tbl_audit_trail
        WHERE date_created BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59'
        ORDER BY ID DESC");
    if ($startDate == "" && $endDate == "") {
        $sql = mysqli_query($db, "SELECT * FROM tbl_audit_trail ORDER BY ID DESC");
    }

    $headers = array(
        'ID',
        'Name',
        'Description',
        'Date Created'
    );
    if (!$sql) die(mysqli_error($db));

    $fp = fopen('php://output', 'w');
    if ($fp && $sql) {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="Audit Trail.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');
        fputcsv($fp, $headers);
        while ($row = mysqli_fetch_assoc($sql)) {
            fputcsv($fp, array_values($row));
        }
        die;
    }
}
if (isset($_POST['btn_maintenance_leave'])) {
    $company_id = $_POST['company_id'];
    $job_grade_set = $_POST['job_grade_set'];
    $sl_monthly = $_POST['sl_monthly'];
    $sl_annual = $_POST['sl_annual'];
    $vl_monthly = $_POST['vl_monthly'];
    $vl_annual = $_POST['vl_annual'];
    $wfh_monthly = $_POST['wfh_monthly'];
    $wfh_annual = $_POST['wfh_annual'];
    $el_monthly = $_POST['el_monthly'];
    $el_annual = $_POST['el_annual'];
    $ecu_annual = $_POST['ecu_annual'];
    $bl_annual = $_POST['bl_annual'];
    $pl_annual = $_POST['pl_annual'];
    $pla_annual = $_POST['pla_annual'];
    $spl_annual = $_POST['spl_annual'];

    $at_name = $_SESSION['hris_account_name'];

    if (check_if_leave_maintenance_exist($company_id, $job_grade_set) == '1') {
        $sql = mysqli_query($db, "UPDATE tbl_leave_maintenance SET
            sl_monthly = '$sl_monthly',
            sl_annual = '$sl_annual',
            vl_monthly = '$vl_monthly',
            vl_annual = '$vl_annual',
            wfh_monthly = '$wfh_monthly',
            wfh_annual = '$wfh_annual',
            el_monthly = '$el_monthly',
            el_annual = '$el_annual',
            ecu_annual = '$ecu_annual',
            bl_annual = '$bl_annual',
            pl_annual = '$pl_annual',
            pla_annual = '$pla_annual',
            spl_annual = '$spl_annual'
            WHERE company_id = '$company_id' AND jgs_id = '$job_grade_set'
        ");
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Updated Leave Values of Company: $company_id','$datetime')");
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> Leave Values has been updated.</h4>
        </div>';
    } else {
        $sql = mysqli_query($db, "INSERT INTO tbl_leave_maintenance VALUES(
        '',
        '$company_id',
        '$job_grade_set',
        '$sl_monthly',
        '$sl_annual',
        '$vl_monthly',
        '$vl_annual',
        '$wfh_monthly',
        '$wfh_annual',
        '$el_monthly',
        '$el_annual',
        '$ecu_annual',
        '$bl_annual',
        '$pl_annual',
        '$pla_annual',
        '$spl_annual'
    )");
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Added Leave Values for Company: $company_id','$datetime')");
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> Leave Values has been added.</h4>
        </div>';
    }
}
if (isset($_POST['get_company_leave_balances'])) {
    $data = array();
    $company_id = $_POST['company_id'];
    $job_grade_set_id = $_POST['job_grade_set_id'];

    $sql = mysqli_query($db, "SELECT * FROM `tbl_leave_maintenance` WHERE company_id = '$company_id' AND jgs_id = '$job_grade_set_id'");
    if ($row = mysqli_fetch_assoc($sql)) {
        $data[] = array(
            'sl_monthly' => $row['sl_monthly'],
            'sl_annual' => $row['sl_annual'],
            'vl_monthly' => $row['vl_monthly'],
            'vl_annual' => $row['vl_annual'],
            'wfh_monthly' => $row['wfh_monthly'],
            'wfh_annual' => $row['wfh_annual'],
            'el_monthly' => $row['el_monthly'],
            'el_annual' => $row['el_annual'],
            'ecu_annual' => $row['ecu_annual'],
            'bl_annual' => $row['bl_annual'],
            'pl_annual' => $row['pl_annual'],
            'pla_annual' => $row['pla_annual'],
            'spl_annual' => $row['spl_annual']
        );
    }
    echo json_encode($data, JSON_FORCE_OBJECT);
}
if (isset($_POST['btn_maintenance_benefits'])) {
    $company_id = $_POST['company_id'];
    $jgs_id = $_POST['job_grade_set_benefits'];
    $car_year1 = $_POST['car_year1'];
    $car_year2 = $_POST['car_year2'];
    $car_year3 = $_POST['car_year3'];
    $car_year4 = $_POST['car_year4'];
    $car_year5 = $_POST['car_year5'];
    $cep_annual = $_POST['cep_annual'];
    $cep_monthly = $_POST['cep_monthly'];
    $gas_monthly = $_POST['gas_monthly'];
    $gym_annual = $_POST['gym_annual'];
    $gym_monthly = $_POST['gym_monthly'];
    $medical_annual = $_POST['medical_annual'];
    $medical_monthly = $_POST['medical_monthly'];
    $optical_annual = $_POST['optical_annual'];
    $optical_monthly = $_POST['optical_monthly'];

    $at_name = $_SESSION['hris_account_name'];

    if (check_if_benefits_maintenance_exist($company_id, $jgs_id) == '1') {
        $sql = mysqli_query($db, "UPDATE tbl_benefits_maintenance SET
            car_year1 = '$car_year1',
            car_year2 = '$car_year2',
            car_year3 = '$car_year3',
            car_year4 = '$car_year4',
            car_year5 = '$car_year5',
            cep_annual = '$cep_annual',
            cep_monthly = '$cep_monthly',
            gas_monthly = '$gas_monthly',
            gym_annual = '$gym_annual',
            gym_monthly = '$gym_monthly',
            medical_annual = '$medical_annual',
            medical_monthly = '$medical_monthly',
            optical_annual = '$optical_annual',
            optical_monthly = '$optical_monthly'
            WHERE company_id = '$company_id' AND jgs_id = '$jgs_id'
        ");
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Updated Benefits Values for Company: $company_id','$datetime')");
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> Benefits Values has been updated.</h4>
        </div>';
    } else {
        $sql = mysqli_query($db, "INSERT INTO tbl_benefits_maintenance VALUES(
            '',
            '$company_id',
            '$jgs_id',
            '$car_year1',
            '$car_year2',
            '$car_year3',
            '$car_year4',
            '$car_year5',
            '$cep_annual',
            '$cep_monthly',
            '$gas_monthly',
            '$gym_annual',
            '$gym_monthly',
            '$medical_annual',
            '$medical_monthly',
            '$optical_annual',
            '$optical_monthly'
        )");
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Added Benefits Values for Company: $company_id','$datetime')");
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> Benefits Values has been added.</h4>
        </div>';
    }
}
if (isset($_POST['get_company_benefits_balances'])) {
    $data = array();
    $company_id = $_POST['company_id'];
    $job_grade_set_id = $_POST['job_grade_set_id'];

    $sql = mysqli_query($db, "SELECT * FROM `tbl_benefits_maintenance` WHERE company_id = '$company_id' AND jgs_id = '$job_grade_set_id'");
    if ($row = mysqli_fetch_assoc($sql)) {
        $data[] = array(
            'car_year1' => $row['car_year1'],
            'car_year2' => $row['car_year2'],
            'car_year3' => $row['car_year3'],
            'car_year4' => $row['car_year4'],
            'car_year5' => $row['car_year5'],
            'cep_annual' => $row['cep_annual'],
            'cep_monthly' => $row['cep_monthly'],
            'gas_monthly' => $row['gas_monthly'],
            'gym_annual' => $row['gym_annual'],
            'gym_monthly' => $row['gym_monthly'],
            'medical_annual' => $row['medical_annual'],
            'medical_monthly' => $row['medical_monthly'],
            'optical_annual' => $row['optical_annual'],
            'optical_monthly' => $row['optical_monthly']
        );
    }
    echo json_encode($data, JSON_FORCE_OBJECT);
}
if (isset($_POST['btn_upload_biometrics_data'])) {
    if ($_FILES['file']['name']) {
        $filename = explode(".", $_FILES['file']['name']);
        if ($filename[1] == 'csv') {
            $handler = fopen($_FILES['file']['tmp_name'], "r");
            while ($data = fgetcsv($handler)) {
                mysqli_query($db, "INSERT INTO `tbl_timekeeping` VALUES('','$data[0]', '$data[1]', '$data[2]', '$data[3]', '$data[4]', '$data[5]', '$data[6]')");
            }
            fclose($handler);
        }
    }
    $at_name = $_SESSION['hris_account_name'];
    mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Uploaded Biometrics Data','$datetime')");
    $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> Biometrics data has been uploaded.</h4>
            </div>';
}
if (isset($_POST['change_company'])) {
    $company_id = $_POST['company_id'];
    $_SESSION['hris_company_id'] = $company_id;
    echo 'ok';
}
if (isset($_POST['btn_training'])) {
    $company_id = $_POST['company_id'];
    $admin_email = $_POST['admin_email'];
    $assigned_employee = $_POST['assigned_employee'];
    $subject = $_POST['subject'];
    $target_date = $_POST['target_date'];
    $description = $_POST['description'];

    $sql = mysqli_query($db, "INSERT INTO tbl_training VALUES('','$admin_email','$company_id','$assigned_employee','$subject','$description','$datetime','$target_date','','0')");
    if ($sql) {
        $tid = mysqli_insert_id($db);
        $tid =  format_transaction_id($tid);
        include('phpMailer.php');
        initiateTraining($tid, $assigned_employee, $subject, $target_date, $description);
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Initiated a training for employee: $assigned_employee','$datetime')");
        echo '<script>alert("Training has been initiated.");window.location.replace("training")</script>';
    }
}
if (isset($_POST['btn_training_complete'])) {
    $id = $_POST['id'];
    $subject = $_POST['subject'];
    $target_date = $_POST['target_date'];
    $description = $_POST['description'];
    $assigned_employee = $_POST['assigned_employee'];
    $attachment = $_FILES['attachment']['name'];
    $attachment = md5($attachment);
    $attachment_tmp = $_FILES['attachment']['tmp_name'];

    $sql = mysqli_query($db, "UPDATE tbl_training SET `status` = '1', attachment = '$attachment' WHERE ID = '$id'");
    if ($sql) {
        move_uploaded_file($attachment_tmp, "uploads/training/" . $attachment);
        include('phpMailer.php');
        $tid =  format_transaction_id($id);
        initiateTrainingComplete($tid, $assigned_employee, $subject, $target_date, $description);
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Completed Training #$id','$datetime')");
        echo '<script>alert("Training has been submitted.");window.location.replace("training")</script>';
    }
}
if (isset($_POST['btn_approve_training'])) {
    $id = $_POST['id'];
    $assigned_employee = $_POST['assigned_employee'];
    $subject = $_POST['subject'];
    $target_date = $_POST['target_date'];
    $description = $_POST['description'];

    $sql = mysqli_query($db, "UPDATE tbl_training SET `status` = 'Completed' WHERE ID = '$id'");
    if ($sql) {
        include('phpMailer.php');
        $tid =  format_transaction_id($id);
        initiateTrainingApproved($tid, $assigned_employee, $subject, $target_date, $description);
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Completed a training for employee: $assigned_employee','$datetime')");
        echo '<script>alert("Training has been completed.");window.location.replace("training")</script>';
    }
}
if (isset($_POST['btn_decline_training'])) {
    $id = $_POST['id'];
    $assigned_employee = $_POST['assigned_employee'];
    $subject = $_POST['subject'];
    $target_date = $_POST['target_date'];
    $description = $_POST['description'];

    $sql = mysqli_query($db, "UPDATE tbl_training SET `status` = 'Declined' WHERE ID = '$id'");
    if ($sql) {
        include('phpMailer.php');
        $tid =  format_transaction_id($id);
        initiateTrainingDecline($tid, $assigned_employee, $subject, $target_date, $description);
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Declined a training for employee: $assigned_employee','$datetime')");
        echo '<script>alert("Training has been declined.");window.location.replace("training")</script>';
    }
}
if (isset($_POST['btn_request_update_training'])) {
    $id = $_POST['id'];
    $assigned_employee = $_POST['assigned_employee'];
    $subject = $_POST['subject'];
    $target_date = $_POST['target_date'];
    $description = $_POST['description'];

    $sql = mysqli_query($db, "UPDATE tbl_training SET `status` = '0' AND attachment = '' WHERE ID = '$id'");
    if ($sql) {
        include('phpMailer.php');
        $tid =  format_transaction_id($id);
        initiateTrainingUpdate($tid, $assigned_employee, $subject, $target_date, $description);
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Requested an update on training for employee: $assigned_employee','$datetime')");
        echo '<script>alert("Update has been requested.");window.location.replace("training")</script>';
    }
}
function is_approver_training($user_id)
{
    $value = '0';
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
    $sql = mysqli_query($db, "SELECT * FROM tbl_training_approvers WHERE `user_id` = '$user_id'");
    if (mysqli_num_rows($sql) > 0) {
        $value = '1';
    }
    return $value;
}
function get_users()
{
    $val = array();
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
    $sql = mysqli_query($db, "SELECT * FROM `tbl_users`");
    while ($row = mysqli_fetch_assoc($sql)) {
        $val[] = $row;
    }
    return $val;
}
function get_user_details($user_id)
{
    $val = '';
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
    $sql = mysqli_query($db, "SELECT * FROM `tbl_users` WHERE ID = '$user_id'");
    while ($row = mysqli_fetch_assoc($sql)) {
        $val = $row;
    }
    return $val;
}
function get_benefits_pending_count($user_id, $company_id)
{
    $position_id = '';
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
    $sql = mysqli_query($db, "SELECT t.*, t1.position
    FROM `tbl_benefits_approvers` t
    INNER JOIN tbl_benefits_approvers_role t1
    ON t.`role` = t1.ID
    WHERE t.user_id = '$user_id'");
    if ($row = mysqli_fetch_assoc($sql)) {
        $position_id = $row['position'];
    }
    $get_count_requests = mysqli_query($db, "SELECT COUNT(*) as c FROM tbl_benefits_reimbursement WHERE `status` = '$position_id' AND company_id = '$company_id'");
    $count_requests = mysqli_fetch_assoc($get_count_requests);

    return $count_requests['c'];
}
function get_pending_leave_count($employee_number)
{
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
    $sql = mysqli_query($db, "SELECT COUNT(*) as c FROM tbl_leave_requests WHERE approver = '$employee_number' AND `status` = 'Pending'");
    $count_requests = mysqli_fetch_assoc($sql);

    return $count_requests['c'];
}
function get_pending_ot_count($employee_number)
{
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
    $sql = mysqli_query($db, "SELECT COUNT(*) as c FROM tbl_ot_application WHERE approver = '$employee_number' AND `status` = 'Pending'");
    $count_requests = mysqli_fetch_assoc($sql);

    return $count_requests['c'];
}
function get_pending_certificate_count($user_id, $company_id)
{
    $data = 0;
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);

    $check_if_approver = mysqli_query($db, "SELECT * FROM `tbl_certificate_requests_approvers` WHERE `user_id` = '$user_id'");
    if (mysqli_num_rows($check_if_approver) > 0) {
        $sql = mysqli_query($db, "SELECT COUNT(*) as c FROM tbl_certificate_requests WHERE `status` = 'Pending' AND company_id = '$company_id'");
        $count_requests = mysqli_fetch_assoc($sql);

        $data =  $count_requests['c'];
    }
    return $data;
}
function get_loan_pending_count($user_id, $company_id)
{
    $position_id = '';
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
    $sql = mysqli_query($db, "SELECT t.*, t1.position
    FROM `tbl_loan_approvers` t
    INNER JOIN tbl_loan_approvers_role t1
    ON t.`role` = t1.ID
    WHERE t.user_id = '$user_id'");
    if ($row = mysqli_fetch_assoc($sql)) {
        $position_id = $row['position'];
    }
    $get_count_requests = mysqli_query($db, "SELECT COUNT(*) as c FROM tbl_loan_application WHERE `status` = '$position_id' AND company_id = '$company_id'");
    $count_requests = mysqli_fetch_assoc($get_count_requests);

    return $count_requests['c'];
}
function get_all_request_count()
{
    $data = 0;
    $employee_number = $_SESSION['hris_employee_number'];
    $userid = $_SESSION['hris_id'];
    $company_id = $_SESSION['hris_company_id'];

    $data = get_benefits_pending_count($userid, $company_id) + get_pending_leave_count($employee_number) + get_pending_ot_count($employee_number) + get_pending_certificate_count($userid, $company_id) + get_loan_pending_count($userid, $company_id);

    return $data;
}
function get_all_request_count_processor()
{
    $data = 0;
    $userid = $_SESSION['hris_id'];
    $company_id = $_SESSION['hris_company_id'];

    $data = get_benefits_pending_count($userid, $company_id) + get_pending_certificate_count($userid, $company_id) + get_loan_pending_count($userid, $company_id);

    return $data;
}
function check_if_leave_maintenance_exist($company_id, $job_grade_set_id)
{
    $val = '0';
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
    $sql = mysqli_query($db, "SELECT * FROM `tbl_leave_maintenance` WHERE company_id = '$company_id' AND jgs_id = '$job_grade_set_id'");
    if (mysqli_num_rows($sql) > 0) {
        $val = '1';
    }
    return $val;
}
function check_if_benefits_maintenance_exist($company_id, $job_grade_set_id)
{
    $val = '0';
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
    $sql = mysqli_query($db, "SELECT * FROM `tbl_benefits_maintenance` WHERE company_id = '$company_id' AND jgs_id = '$job_grade_set_id'");
    if (mysqli_num_rows($sql) > 0) {
        $val = '1';
    }
    return $val;
}
function get_permissions($user_id)
{
    $val = '';
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
    $sql = mysqli_query($db, "SELECT * FROM `tbl_users` WHERE ID = '$user_id'");
    while ($row = mysqli_fetch_assoc($sql)) {
        $val = $row;
    }
    return $val;
}
function get_certificate_request_approvers($company_id)
{
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
    $approver = array();
    $sql = mysqli_query($db, "SELECT * FROM tbl_certificate_requests_approvers WHERE company_id = '$company_id'");
    while ($row = mysqli_fetch_assoc($sql)) {
        $approver[] = $row;
    }
    return $approver;
}
function get_training_approvers($company_id)
{
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
    $approver = array();
    $sql = mysqli_query($db, "SELECT * FROM tbl_training_approvers WHERE company_id = '$company_id'");
    while ($row = mysqli_fetch_assoc($sql)) {
        $approver[] = $row;
    }
    return $approver;
}
function get_salary_loan_role_name($role_id)
{
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
    $role = '';
    $sql = mysqli_query($db, "SELECT * FROM tbl_loan_approvers_role WHERE position = '$role_id'");
    while ($row = mysqli_fetch_assoc($sql)) {
        $role = $row;
    }
    return $role;
}
function get_salary_loan_role_name_by_ID($role_id)
{
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
    $role = '';
    $sql = mysqli_query($db, "SELECT * FROM tbl_loan_approvers_role WHERE ID = '$role_id'");
    while ($row = mysqli_fetch_assoc($sql)) {
        $role = $row;
    }
    return $role;
}
function get_requested_liters($benefits_id)
{
    $val = '';
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
    $sql = mysqli_query($db, "SELECT * FROM tbl_benefits_gasoline_details WHERE benefits_id = '$benefits_id'");
    $val = mysqli_fetch_assoc($sql);
    $val = $val['requested_liters'];
    return $val;
}
function deduct_benefits_amount($benefits_id, $amount, $category)
{
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
    mysqli_query($db, "UPDATE tbl_benefits_reimbursement SET amount = amount - $amount WHERE ID = '$benefits_id'");
    mysqli_query($db, "UPDATE tbl_benefits_total_amount SET total_amount = total_amount - $amount WHERE benefits_id = '$benefits_id' AND cat = '$category'");
}
function add_benefits_amount($benefits_id, $amount, $category)
{
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
    mysqli_query($db, "UPDATE tbl_benefits_reimbursement SET amount = amount + $amount WHERE ID = '$benefits_id'");

    $sql = mysqli_query($db, "SELECT * FROM tbl_benefits_total_amount WHERE benefits_id = '$benefits_id' AND cat = '$category'");
    if (mysqli_num_rows($sql) > 0) {
        mysqli_query($db, "UPDATE tbl_benefits_total_amount SET total_amount = total_amount + $amount WHERE benefits_id = '$benefits_id' AND cat = '$category'");
    } else {
        mysqli_query($db, "INSERT INTO tbl_benefits_total_amount VALUES('','$benefits_id','$amount','$category')");
    }
}
function get_benefits_category_amount($benefits_id, $cat)
{
    $val = '';
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
    $sql = mysqli_query($db, "SELECT * FROM tbl_benefits_total_amount WHERE benefits_id = '$benefits_id' AND cat = '$cat'");
    if (mysqli_num_rows($sql) > 0) {
        $row = mysqli_fetch_assoc($sql);
        $val = $row['total_amount'];
    } else {
        $val = 0;
    }
    return $val;
}
function get_benefits_role_name($role_id)
{
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
    $role = '';
    $sql = mysqli_query($db, "SELECT * FROM tbl_benefits_approvers_role WHERE position = '$role_id'");
    while ($row = mysqli_fetch_assoc($sql)) {
        $role = $row;
    }
    return $role;
}
function get_benefits_role_name_by_ID($role_id)
{
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
    $role = '';
    $sql = mysqli_query($db, "SELECT * FROM tbl_benefits_approvers_role WHERE ID = '$role_id'");
    while ($row = mysqli_fetch_assoc($sql)) {
        $role = $row;
    }
    return $role;
}
function get_benefits_role($company_id)
{
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
    $roles = array();
    $sql = mysqli_query($db, "SELECT * FROM tbl_benefits_approvers_role WHERE company_id = '$company_id' ORDER BY ID DESC");
    while ($row = mysqli_fetch_assoc($sql)) {
        $roles[] = array('ID' => $row['ID'], 'role' => $row['role']);
    }
    return $roles;
}
function get_salary_loan_role($company_id)
{
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
    $roles = array();
    $sql = mysqli_query($db, "SELECT * FROM tbl_loan_approvers_role WHERE company_id = '$company_id' ORDER BY ID DESC");
    while ($row = mysqli_fetch_assoc($sql)) {
        $roles[] = array('ID' => $row['ID'], 'role' => $row['role']);
    }
    return $roles;
}
function get_last_approver_loan($company_id)
{
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
    $sql = mysqli_query($db, "SELECT * FROM tbl_loan_approvers_role WHERE company_id = '$company_id' ORDER BY position DESC");
    $last_approver = mysqli_fetch_assoc($sql);
    return $last_approver['position'];
}
function get_next_salary_loan_approver_loan($company_id, $loan_id)
{
    $loan_info = get_salary_loan_info($loan_id);
    $current_status = $loan_info['status'];
    $last_approver = get_last_approver_loan($company_id);
    $next_approver = $current_status + 1;
    if ($last_approver >= $next_approver) {
        return $next_approver;
    } else {
        return 'Approved';
    }
    return null;
}
function get_salary_loan_info($loan_id)
{
    $val = '';
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
    $sql = mysqli_query($db, "SELECT * FROM tbl_loan_application WHERE ID = '$loan_id'");
    while ($row = mysqli_fetch_assoc($sql)) {
        $val = $row;
    }
    return $val;
}
function get_last_approver($company_id)
{
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
    $sql = mysqli_query($db, "SELECT * FROM tbl_benefits_approvers_role WHERE company_id = '$company_id' ORDER BY position DESC");
    $last_approver = mysqli_fetch_assoc($sql);
    return $last_approver['position'];
}
function get_next_benefits_approver($company_id, $benefits_id)
{
    $benefits_info = get_benefits_info($benefits_id);
    $current_status = $benefits_info['status'];
    $last_approver = get_last_approver($company_id);
    $next_approver = $current_status + 1;
    if ($last_approver >= $next_approver) {
        return $next_approver;
    } else {
        return 'Approved';
    }
    return null;
}
function get_next_benefits_approver_loan($company_id, $loan_id)
{
    $loan_info = get_salary_loan_info($loan_id);
    $current_status = $loan_info['status'];
    $last_approver = get_last_approver_loan($company_id);
    $next_approver = $current_status + 1;
    if ($last_approver >= $next_approver) {
        return $next_approver;
    } else {
        return 'Approved';
    }
    return null;
}
function get_benefits_info($benefits_id)
{
    $val = '';
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
    $sql = mysqli_query($db, "SELECT * FROM tbl_benefits_reimbursement WHERE ID = '$benefits_id'");
    while ($row = mysqli_fetch_assoc($sql)) {
        $val = $row;
    }
    return $val;
}
function get_approvers_from_role($position, $company_id)
{
    $val = '';
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
    $sql = mysqli_query($db, "SELECT * FROM tbl_benefits_approvers_role WHERE company_id = '$company_id' AND `position` = '$position'");
    while ($row = mysqli_fetch_assoc($sql)) {
        $val = $row;
    }
    return $val;
}
function get_approvers_from_role_loan($position, $company_id)
{
    $val = '';
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
    $sql = mysqli_query($db, "SELECT * FROM tbl_loan_approvers_role WHERE company_id = '$company_id' AND `position` = '$position'");
    while ($row = mysqli_fetch_assoc($sql)) {
        $val = $row;
    }
    return $val;
}
function get_benefits_approvers($company_id, $role_id)
{
    $val = array();
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
    $sql = mysqli_query($db, "SELECT * FROM tbl_benefits_approvers WHERE company_id = '$company_id' AND `role` = '$role_id'");
    while ($row = mysqli_fetch_assoc($sql)) {
        $val[] = $row;
    }
    return $val;
}
function get_salary_loan_approvers($company_id, $role_id)
{
    $val = array();
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
    $sql = mysqli_query($db, "SELECT * FROM tbl_loan_approvers WHERE company_id = '$company_id' AND `role` = '$role_id'");
    while ($row = mysqli_fetch_assoc($sql)) {
        $val[] = $row;
    }
    return $val;
}
function get_benefits_approver_role($company_id, $email)
{
    $val = '';
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
    $sql = mysqli_query($db, "SELECT * FROM tbl_benefits_approvers WHERE email = '$email' AND company_id = '$company_id'");
    while ($row = mysqli_fetch_assoc($sql)) {
        $val = $row['role'];
    }
    return $val;
}
function get_benefits_balances($employee_number)
{
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
    $balances = array();

    $get_car_balance = mysqli_query($db, "SELECT * FROM tbl_benefits_car_balance WHERE employee_number = '$employee_number'");
    $car_balance = mysqli_fetch_assoc($get_car_balance);
    $balances[] = array('car_maintenance' => $car_balance['balance']);

    $get_cep_balance = mysqli_query($db, "SELECT * FROM tbl_benefits_cep_balance WHERE employee_number = '$employee_number'");
    $cep_balance = mysqli_fetch_assoc($get_cep_balance);
    $balances[] = array('cep' => $cep_balance['balance']);

    $get_gas_balance = mysqli_query($db, "SELECT * FROM tbl_benefits_gas_balance WHERE employee_number = '$employee_number'");
    $gas_balance = mysqli_fetch_assoc($get_gas_balance);
    $balances[] = array('gas' => $gas_balance['balance']);

    $get_gym_balance = mysqli_query($db, "SELECT * FROM tbl_benefits_gym_balance WHERE employee_number = '$employee_number'");
    $gym_balance = mysqli_fetch_assoc($get_gym_balance);
    $balances[] = array('gym' =>  $gym_balance['balance']);

    $get_medical_balance = mysqli_query($db, "SELECT * FROM tbl_benefits_medical_balance WHERE employee_number = '$employee_number'");
    $medical_balance = mysqli_fetch_assoc($get_medical_balance);
    $balances[] = array('medical' => $medical_balance['balance']);

    $get_optical_balance = mysqli_query($db, "SELECT * FROM tbl_benefits_optical_balance WHERE employee_number = '$employee_number'");
    $optical_balance = mysqli_fetch_assoc($get_optical_balance);
    $balances[] = array('optical' => $optical_balance['balance']);

    return json_encode($balances);
    // $obj = json_decode(get_benefits_balances('0009'), true);
    // $name = $obj[0]['car_maintenance'];
}
function removeCookie()
{
    setcookie("email", NULL, time() - 3600);
    setcookie("password", NULL, time() - 3600);
}
function get_car_age($date_acquired)
{
    $date_acquired = date('m-d-Y', strtotime($date_acquired));
    $date_acquired = explode("-", $date_acquired);
    $age = (date("md", date("U", mktime(0, 0, 0, $date_acquired[0], $date_acquired[1], $date_acquired[2]))) > date("md")
        ? ((date("Y") - $date_acquired[2]) - 1)
        : (date("Y") - $date_acquired[2]));
    return $age;
}
function check_if_holiday_exist($holiday_date, $old_date)
{
    $value = '0';
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
    $sql = mysqli_query($db, "SELECT * FROM tbl_holidays WHERE holiday_date = '$holiday_date'");
    if (mysqli_num_rows($sql) > 0) {
        if ($old_date != $holiday_date) {
            $value = '1';
        }
    }
    return $value;
}
function is_approver_certificate_requests($user_id)
{
    $value = '0';
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
    $sql = mysqli_query($db, "SELECT * FROM tbl_certificate_requests_approvers WHERE `user_id` = '$user_id'");
    if (mysqli_num_rows($sql) > 0) {
        $value = '1';
    }
    return $value;
}
function get_employees_from_company($company_id)
{
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
    $employees = array();
    $sql = mysqli_query($db, "SELECT * FROM tbl_employment_information WHERE company = '$company_id' ORDER BY ID DESC");
    while ($row = mysqli_fetch_assoc($sql)) {
        $employees[] = array('employee_number' => $row['employee_number']);
    }
    return $employees;
}
function get_leave_details($leave_id)
{
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
    $details = '';
    $sql = mysqli_query($db, "SELECT * FROM tbl_leave_requests WHERE ID = '$leave_id'");
    while ($row = mysqli_fetch_assoc($sql)) {
        $details = $row;
    }
    return $details;
}
function get_ot_details($ot_id)
{
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
    $details = '';
    $sql = mysqli_query($db, "SELECT * FROM tbl_ot_application WHERE ID = '$ot_id'");
    while ($row = mysqli_fetch_assoc($sql)) {
        $details = $row;
    }
    return $details;
}
function get_personal_information($employee_number)
{
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
    $details = '';
    $sql = mysqli_query($db, "SELECT * FROM tbl_personal_information WHERE employee_number = '$employee_number'");
    while ($row = mysqli_fetch_assoc($sql)) {
        $details = $row;
    }
    return $details;
}
function get_employment_information($employee_number)
{
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
    $details = '';
    $sql = mysqli_query($db, "SELECT * FROM tbl_employment_information WHERE employee_number = '$employee_number'");
    while ($row = mysqli_fetch_assoc($sql)) {
        $details = $row;
    }
    return $details;
}
function format_transaction_id($id)
{
    return sprintf("%04d", $id);
}
function check_if_company_exist($company)
{
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
    $sql = mysqli_query($db, "SELECT * FROM tbl_companies WHERE company_name = '$company'");
    if (mysqli_num_rows($sql) > 0) {
        return true;
    }
}
function check_if_department_exist($department, $company_id)
{
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
    $sql = mysqli_query($db, "SELECT * FROM tbl_departments WHERE department = '$department' AND company_id = '$company_id'");
    if (mysqli_num_rows($sql) > 0) {
        return true;
    }
}
function check_if_job_grade_exist($job_grade, $company_id)
{
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
    $sql = mysqli_query($db, "SELECT * FROM tbl_job_grade WHERE job_grade = '$job_grade' AND company_id = '$company_id'");
    if (mysqli_num_rows($sql) > 0) {
        return true;
    }
}
function get_companies()
{
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
    $companies = array();
    $sql = mysqli_query($db, "SELECT * FROM tbl_companies ORDER BY ID DESC");
    while ($row = mysqli_fetch_assoc($sql)) {
        $companies[] = array('ID' => $row['ID'], 'company_name' => $row['company_name']);
    }
    return $companies;
}
function get_company($company_id)
{
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
    $company = '';
    $sql = mysqli_query($db, "SELECT * FROM tbl_companies WHERE ID = '$company_id' ORDER BY ID DESC");
    while ($row = mysqli_fetch_assoc($sql)) {
        $company = $row['company_name'];
    }
    return $company;
}
function get_company_array($company_id)
{
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
    $companies = array();
    $sql = mysqli_query($db, "SELECT * FROM tbl_companies WHERE ID = '$company_id' ORDER BY ID DESC");
    while ($row = mysqli_fetch_assoc($sql)) {
        $companies[] = array('ID' => $row['ID'], 'company_name' => $row['company_name']);
    }
    return $companies;
}
function get_departments($company_id)
{
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
    $departments = array();
    $sql = mysqli_query($db, "SELECT * FROM tbl_departments WHERE company_id = '$company_id' ORDER BY ID DESC");
    while ($row = mysqli_fetch_assoc($sql)) {
        $departments[] = array('ID' => $row['ID'], 'department' => $row['department']);
    }
    return $departments;
}
function get_job_grade_set($company_id)
{
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
    $job_grade_sets = array();
    $sql = mysqli_query($db, "SELECT * FROM tbl_job_grade_set WHERE company_id = '$company_id' ORDER BY ID DESC");
    while ($row = mysqli_fetch_assoc($sql)) {
        $job_grade_sets[] = array('ID' => $row['ID'], 'job_grade_set' => $row['job_grade_set']);
    }
    return $job_grade_sets;
}
function get_job_grade($company_id)
{
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
    $job_grade = array();
    $sql = mysqli_query($db, "SELECT * FROM tbl_job_grade WHERE company_id = '$company_id' ORDER BY ID DESC");
    while ($row = mysqli_fetch_assoc($sql)) {
        $job_grade[] = array('ID' => $row['ID'], 'job_grade' => $row['job_grade']);
    }
    return $job_grade;
}
function get_approvers($company_id)
{
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
    $approvers = array();
    $sql = mysqli_query($db, "SELECT t.*, t1.account_name FROM tbl_employment_information t
    INNER JOIN tbl_personal_information t1
    ON t.employee_number = t1.employee_number
    WHERE t.company = '$company_id' 
    AND is_approver = '1'
    ORDER BY ID DESC");
    while ($row = mysqli_fetch_assoc($sql)) {
        $approvers[] = array('employee_number' => $row['employee_number'], 'account_name' => $row['account_name']);
    }
    return $approvers;
}
function get_num_hours($login, $logout)
{
    $date1 = new DateTime($login);
    $date2 = new DateTime($logout);

    $diff = $date2->diff($date1);
    $minutes = ($diff->days * 24 * 60) +
        ($diff->h * 60) + $diff->i;
    // return $diff->format('%h hours');
    return $minutes / 60;
}
function format_num_hours($time)
{
    $data = '';
    if ($time == "None") {
        $data = 'None';
    } else {
        $seconds = ($time * 3600);
        $hours = floor($time);
        $seconds -= $hours * 3600;
        $minutes = floor($seconds / 60);
        $seconds -= $minutes * 60;
        $h = lz($hours);
        $data = $h . " hours and " . lz($minutes) . ' minutes';
    }
    return $data;
}
function lz($num)
{
    return (strlen($num) < 2) ? "0{$num}" : $num;
}
function allowed_on_behalf_filing($employee_number)
{
    $db = mysqli_connect('localhost', 'hrisv2', 'hrisv2@123', 'hrisv2') or die("" . $db->error);
    $data = '0';
    $sql = mysqli_query($db, "SELECT * FROM tbl_employment_information WHERE employee_number = '$employee_number'");
    $row = mysqli_fetch_assoc($sql);
    if ($row['filing'] == '1') {
        $data = '1';
    }
    return $data;
}
function is_training_admin($email)
{
    return '1';
}
$template = array(
    'name'              => 'HRIS',
    'version'           => '1.0',
    'author'            => 'John Harold Carlos',
    'robots'            => 'noindex, nofollow',
    'title'             => 'HRIS - Human Resource Information System',
    'description'       => 'HRIS - Human Resource Information System',
    // true                     enable page preloader
    // false                    disable page preloader
    'page_preloader'    => true,
    // true                     enable main menu auto scrolling when opening a submenu
    // false                    disable main menu auto scrolling when opening a submenu
    'menu_scroll'       => false,
    // 'navbar-default'         for a light header
    // 'navbar-inverse'         for a dark header
    'header_navbar'     => 'navbar-default',
    // ''                       empty for a static layout
    // 'navbar-fixed-top'       for a top fixed header / fixed sidebars
    // 'navbar-fixed-bottom'    for a bottom fixed header / fixed sidebars
    'header'            => '',
    // ''                                               for a full main and alternative sidebar hidden by default (> 991px)
    // 'sidebar-visible-lg'                             for a full main sidebar visible by default (> 991px)
    // 'sidebar-partial'                                for a partial main sidebar which opens on mouse hover, hidden by default (> 991px)
    // 'sidebar-partial sidebar-visible-lg'             for a partial main sidebar which opens on mouse hover, visible by default (> 991px)
    // 'sidebar-mini sidebar-visible-lg-mini'           for a mini main sidebar with a flyout menu, enabled by default (> 991px + Best with static layout)
    // 'sidebar-mini sidebar-visible-lg'                for a mini main sidebar with a flyout menu, disabled by default (> 991px + Best with static layout)
    // 'sidebar-alt-visible-lg'                         for a full alternative sidebar visible by default (> 991px)
    // 'sidebar-alt-partial'                            for a partial alternative sidebar which opens on mouse hover, hidden by default (> 991px)
    // 'sidebar-alt-partial sidebar-alt-visible-lg'     for a partial alternative sidebar which opens on mouse hover, visible by default (> 991px)
    // 'sidebar-partial sidebar-alt-partial'            for both sidebars partial which open on mouse hover, hidden by default (> 991px)
    // 'sidebar-no-animations'                          add this as extra for disabling sidebar animations on large screens (> 991px) - Better performance with heavy pages!
    'sidebar'           => 'sidebar-partial sidebar-visible-lg sidebar-no-animations',
    // ''                       empty for a static footer
    // 'footer-fixed'           for a fixed footer
    'footer'            => '',
    // ''                       empty for default style
    // 'style-alt'              for an alternative main style (affects main page background as well as blocks style)
    'main_style'        => '',
    // ''                           Disable cookies (best for setting an active color theme from the next variable)
    // 'enable-cookies'             Enables cookies for remembering active color theme when changed from the sidebar links (the next color theme variable will be ignored)
    'cookies'           => 'enable-cookies',
    // 'night', 'amethyst', 'modern', 'autumn', 'flatie', 'spring', 'fancy', 'fire', 'coral', 'lake',
    // 'forest', 'waterlily', 'emerald', 'blackberry' or '' leave empty for the Default Blue theme
    'theme'             => '',
    // ''                       for default content in header
    // 'horizontal-menu'        for a horizontal menu in header
    // This option is just used for feature demostration and you can remove it if you like. You can keep or alter header's content in page_head
    'header_content'    => '',
    'active_page'       => basename($_SERVER['PHP_SELF'])
);

/* Primary navigation array (the primary navigation will be created automatically based on this array, up to 3 levels deep) */
$primary_nav = array(
    array(
        'name'  => 'Dashboard',
        'url'   => 'index',
        'icon'  => 'gi gi-stopwatch'
    ),
    array(
        'name'  => 'Pending Tasks <span class="badge badge-danger badge-pill">0</span>',
        'url'   => 'pending-tasks',
        'icon'  => 'gi gi-list'
    ),
    array(
        'name'  => '201 File',
        'icon'  => 'gi gi-folder_open',
        'sub'   => array(
            array(
                'name'  => 'Employee List',
                'url'   => 'employee-list'
            ),
            array(
                'name'  => 'Onboarding',
                'url'   => 'onboarding'
            ),
            array(
                'name'  => 'Offboarding',
                'url'   => '#'
            )
        )
    ),
    array(
        'name'  => 'Leave Management',
        'icon'  => 'fa fa-user-times',
        'sub'   => array(
            array(
                'name'  => 'Leave Application',
                'url'   => 'leave-application'
            ),
            array(
                'name'  => 'Leave Application List',
                'url'   => 'leave-list'
            ),
            array(
                'name'  => 'Leave Balances',
                'url'   => 'leave-balances'
            )
        )
    ),
    array(
        'name'  => 'OT Management',
        'icon'  => 'fa fa-hourglass',
        'sub'   => array(
            array(
                'name'  => 'OT Application',
                'url'   => 'ot-application'
            ),
            array(
                'name'  => 'OT Application List',
                'url'   => 'ot-list'
            )
        )
    ),
    array(
        'name'  => 'Certificate Requests',
        'icon'  => 'fa fa-file',
        'sub'   => array(
            array(
                'name'  => 'Request Certificate',
                'url'   => 'certificate-request'
            ),
            array(
                'name'  => 'Certificate Request List',
                'url'   => 'certificate-request-list'
            ),
            array(
                'name'  => 'Certificate Request Approvers',
                'url'   => 'certificate-request-approvers'
            )
        )
    ),
    array(
        'name'  => 'Salary Loan Management',
        'icon'  => 'fa fa-money',
        'sub'   => array(
            array(
                'name'  => 'Loan Application',
                'url'   => 'loan-application'
            ),
            array(
                'name'  => 'Loan Application List',
                'url'   => 'loan-application-list'
            ),
            array(
                'name'  => 'Loan Approvers',
                'url'   => 'loan-approvers'
            )
        )
    ),
    array(
        'name'  => 'Benefits Reimbursement',
        'icon'  => 'fa fa-exchange',
        'sub'   => array(
            array(
                'name'  => 'Reimbursement Application',
                'url'   => 'reimbursement-application'
            ),
            array(
                'name'  => 'Reimbursement List',
                'url'   => 'reimbursement-list'
            ),
            array(
                'name'  => 'Bond Management',
                'url'   => 'bond-management'
            ),
            array(
                'name'  => 'Car Maintenance',
                'url'   => 'car-maintenance'
            ),
            array(
                'name'  => 'Benefits Balances',
                'url'   => 'benefits-balances'
            ),
            array(
                'name'  => 'Benefits Approvers',
                'url'   => 'benefits-approvers'
            )
        )
    ),
    array(
        'name'  => 'Holiday Maintenance',
        'url'   => 'holiday-maintenance',
        'icon'  => 'fa fa-calendar'
    ),
    array(
        'name'  => 'Generate Reports',
        'url'   => 'generate-reports',
        'icon'  => 'fa fa-files-o'
    ),
    array(
        'name'  => 'Audit Trail',
        'url'   => 'audit-trail',
        'icon'  => 'fa fa-sliders'
    ),
    array(
        'name'  => 'Account Management',
        'icon'  => 'fa fa-users',
        'sub'   => array(

            array(
                'name'  => 'Create an Account',
                'url'   => 'create-account'
            ),
            array(
                'name'  => 'Account List',
                'url'   => 'account-list'
            )
        )
    ),
    array(
        'name'  => 'My account',
        'url'   => 'my-account',
        'icon'  => 'fa fa-user'
    ),
    array(
        'name'  => 'Company Management', // department, job grade set, job grade, loan max value, benefits value monthly and annual, roles, logo, banner
        'url' => 'company-management',
        'icon'  => 'fa fa-cogs',
    ),
    // array(
    //     'name'  => 'eCommerce',
    //     'icon'  => 'gi gi-shopping_cart',
    //     'sub'   => array(
    //         array(
    //             'name'  => 'Dashboard',
    //             'url'   => 'page_ecom_dashboard'
    //         ),
    //         array(
    //             'name'  => 'Orders',
    //             'url'   => 'page_ecom_orders'
    //         ),
    //         array(
    //             'name'  => 'Order View',
    //             'url'   => 'page_ecom_order_view'
    //         ),
    //         array(
    //             'name'  => 'Products',
    //             'url'   => 'page_ecom_products'
    //         ),
    //         array(
    //             'name'  => 'Product Edit',
    //             'url'   => 'page_ecom_product_edit'
    //         ),
    //         array(
    //             'name'  => 'Customer View',
    //             'url'   => 'page_ecom_customer_view'
    //         )
    //     )
    // ),
    // array(
    //     'name'  => 'Widget Kit',
    //     'opt'   => '<a href="javascript:void(0)" data-toggle="tooltip" title="Quick Settings"><i class="gi gi-settings"></i></a>' .
    //         '<a href="javascript:void(0)" data-toggle="tooltip" title="Create the most amazing pages with the widget kit!"><i class="gi gi-lightbulb"></i></a>',
    //     'url'   => 'header',
    // ),
    // array(
    //     'name'  => 'Statistics',
    //     'url'   => 'page_widgets_stats',
    //     'icon'  => 'gi gi-charts'
    // ),
    // array(
    //     'name'  => 'Social',
    //     'url'   => 'page_widgets_social',
    //     'icon'  => 'gi gi-share_alt'
    // ),
    // array(
    //     'name'  => 'Media',
    //     'url'   => 'page_widgets_media',
    //     'icon'  => 'gi gi-film'
    // ),
    // array(
    //     'name'  => 'Links',
    //     'url'   => 'page_widgets_links',
    //     'icon'  => 'gi gi-link'
    // ),
    // array(
    //     'name'  => 'Design Kit',
    //     'opt'   => '<a href="javascript:void(0)" data-toggle="tooltip" title="Quick Settings"><i class="gi gi-settings"></i></a>',
    //     'url'   => 'header'
    // ),
    // array(
    //     'name'  => 'User Interface',
    //     'icon'  => 'gi gi-certificate',
    //     'sub'   => array(
    //         array(
    //             'name'  => 'Grid &amp; Blocks',
    //             'url'   => 'page_ui_grid_blocks'
    //         ),
    //         array(
    //             'name'  => 'Draggable Blocks',
    //             'url'   => 'page_ui_draggable_blocks'
    //         ),
    //         array(
    //             'name'  => 'Typography',
    //             'url'   => 'page_ui_typography'
    //         ),
    //         array(
    //             'name'  => 'Buttons &amp; Dropdowns',
    //             'url'   => 'page_ui_buttons_dropdowns'
    //         ),
    //         array(
    //             'name'  => 'Navigation &amp; More',
    //             'url'   => 'page_ui_navigation_more'
    //         ),
    //         array(
    //             'name'  => 'Horizontal Menu',
    //             'url'   => 'page_ui_horizontal_menu'
    //         ),
    //         array(
    //             'name'  => 'Progress &amp; Loading',
    //             'url'   => 'page_ui_progress_loading'
    //         ),
    //         array(
    //             'name'  => 'Page Preloader',
    //             'url'   => 'page_ui_preloader'
    //         ),
    //         array(
    //             'name'  => 'Color Themes',
    //             'url'   => 'page_ui_color_themes'
    //         )
    //     )
    // ),
    // array(
    //     'name'  => 'Forms',
    //     'icon'  => 'gi gi-notes_2',
    //     'sub'   => array(
    //         array(
    //             'name'  => 'General',
    //             'url'   => 'page_forms_general'
    //         ),
    //         array(
    //             'name'  => 'Components',
    //             'url'   => 'page_forms_components'
    //         ),
    //         array(
    //             'name'  => 'Validation',
    //             'url'   => 'page_forms_validation'
    //         ),
    //         array(
    //             'name'  => 'Wizard',
    //             'url'   => 'page_forms_wizard'
    //         )
    //     )
    // ),
    // array(
    //     'name'  => 'Tables',
    //     'icon'  => 'gi gi-table',
    //     'sub'   => array(
    //         array(
    //             'name'  => 'General',
    //             'url'   => 'page_tables_general'
    //         ),
    //         array(
    //             'name'  => 'Responsive',
    //             'url'   => 'page_tables_responsive'
    //         ),
    //         array(
    //             'name'  => 'Datatables',
    //             'url'   => 'page_tables_datatables'
    //         )
    //     )
    // ),
    // array(
    //     'name'  => 'Icon Sets',
    //     'icon'  => 'gi gi-cup',
    //     'sub'   => array(
    //         array(
    //             'name'  => 'Font Awesome',
    //             'url'   => 'page_icons_fontawesome'
    //         ),
    //         array(
    //             'name'  => 'Glyphicons Pro',
    //             'url'   => 'page_icons_glyphicons_pro'
    //         )
    //     )
    // ),
    // array(
    //     'name'  => 'Page Layouts',
    //     'icon'  => 'gi gi-show_big_thumbnails',
    //     'sub'   => array(
    //         array(
    //             'name'  => 'Static',
    //             'url'   => 'page_layout_static'
    //         ),
    //         array(
    //             'name'  => 'Static + Fixed Footer',
    //             'url'   => 'page_layout_static_fixed_footer'
    //         ),
    //         array(
    //             'name'  => 'Fixed Top Header',
    //             'url'   => 'page_layout_fixed_top'
    //         ),
    //         array(
    //             'name'  => 'Fixed Top Header + Footer',
    //             'url'   => 'page_layout_fixed_top_footer'
    //         ),
    //         array(
    //             'name'  => 'Fixed Bottom Header',
    //             'url'   => 'page_layout_fixed_bottom'
    //         ),
    //         array(
    //             'name'  => 'Fixed Bottom Header + Footer',
    //             'url'   => 'page_layout_fixed_bottom_footer'
    //         ),
    //         array(
    //             'name'  => 'Mini Main Sidebar',
    //             'url'   => 'page_layout_static_main_sidebar_mini'
    //         ),
    //         array(
    //             'name'  => 'Partial Main Sidebar',
    //             'url'   => 'page_layout_static_main_sidebar_partial'
    //         ),
    //         array(
    //             'name'  => 'Visible Main Sidebar',
    //             'url'   => 'page_layout_static_main_sidebar_visible'
    //         ),
    //         array(
    //             'name'  => 'Partial Alternative Sidebar',
    //             'url'   => 'page_layout_static_alternative_sidebar_partial'
    //         ),
    //         array(
    //             'name'  => 'Visible Alternative Sidebar',
    //             'url'   => 'page_layout_static_alternative_sidebar_visible'
    //         ),
    //         array(
    //             'name'  => 'No Sidebars',
    //             'url'   => 'page_layout_static_no_sidebars'
    //         ),
    //         array(
    //             'name'  => 'Both Sidebars Partial',
    //             'url'   => 'page_layout_static_both_partial'
    //         ),
    //         array(
    //             'name'  => 'Animated Sidebar Transitions',
    //             'url'   => 'page_layout_static_animated'
    //         )
    //     )
    // ),
    // array(
    //     'name'  => 'Develop Kit',
    //     'opt'   => '<a href="javascript:void(0)" data-toggle="tooltip" title="Quick Settings"><i class="gi gi-settings"></i></a>',
    //     'url'   => 'header',
    // ),
    // array(
    //     'name'  => 'Ready Pages',
    //     'icon'  => 'gi gi-brush',
    //     'sub'   => array(
    //         array(
    //             'name'  => 'Errors',
    //             'sub'   => array(
    //                 array(
    //                     'name'  => '400',
    //                     'url'   => 'page_ready_400'
    //                 ),
    //                 array(
    //                     'name'  => '401',
    //                     'url'   => 'page_ready_401'
    //                 ),
    //                 array(
    //                     'name'  => '403',
    //                     'url'   => 'page_ready_403'
    //                 ),
    //                 array(
    //                     'name'  => '404',
    //                     'url'   => 'page_ready_404'
    //                 ),
    //                 array(
    //                     'name'  => '500',
    //                     'url'   => 'page_ready_500'
    //                 ),
    //                 array(
    //                     'name'  => '503',
    //                     'url'   => 'page_ready_503'
    //                 )
    //             )
    //         ),
    //         array(
    //             'name'  => 'Get Started',
    //             'sub'   => array(
    //                 array(
    //                     'name'  => 'Blank',
    //                     'url'   => 'page_ready_blank'
    //                 ),
    //                 array(
    //                     'name'  => 'Blank Alternative',
    //                     'url'   => 'page_ready_blank_alt'
    //                 )
    //             )
    //         ),
    //         array(
    //             'name'  => 'Search Results (4)',
    //             'url'   => 'page_ready_search_results'
    //         ),
    //         array(
    //             'name'  => 'Article',
    //             'url'   => 'page_ready_article'
    //         ),
    //         array(
    //             'name'  => 'User Profile',
    //             'url'   => 'page_ready_user_profile'
    //         ),
    //         array(
    //             'name'  => 'Contacts',
    //             'url'   => 'page_ready_contacts'
    //         ),
    //         array(
    //             'name'  => 'e-Learning',
    //             'sub'   => array(
    //                 array(
    //                     'name'  => 'Courses',
    //                     'url'   => 'page_ready_elearning_courses'
    //                 ),
    //                 array(
    //                     'name'  => 'Course - Lessons',
    //                     'url'   => 'page_ready_elearning_course_lessons'
    //                 ),
    //                 array(
    //                     'name'  => 'Course - Lesson Page',
    //                     'url'   => 'page_ready_elearning_course_lesson'
    //                 )
    //             )
    //         ),
    //         array(
    //             'name'  => 'Message Center',
    //             'sub'   => array(
    //                 array(
    //                     'name'  => 'Inbox',
    //                     'url'   => 'page_ready_inbox'
    //                 ),
    //                 array(
    //                     'name'  => 'Compose Message',
    //                     'url'   => 'page_ready_inbox_compose'
    //                 ),
    //                 array(
    //                     'name'  => 'View Message',
    //                     'url'   => 'page_ready_inbox_message'
    //                 )
    //             )
    //         ),
    //         array(
    //             'name'  => 'Chat',
    //             'url'   => 'page_ready_chat'
    //         ),
    //         array(
    //             'name'  => 'Timeline',
    //             'url'   => 'page_ready_timeline'
    //         ),
    //         array(
    //             'name'  => 'Files',
    //             'url'   => 'page_ready_files'
    //         ),
    //         array(
    //             'name'  => 'Tickets',
    //             'url'   => 'page_ready_tickets'
    //         ),
    //         array(
    //             'name'  => 'Bug Tracker',
    //             'url'   => 'page_ready_bug_tracker'
    //         ),
    //         array(
    //             'name'  => 'Tasks',
    //             'url'   => 'page_ready_tasks'
    //         ),
    //         array(
    //             'name'  => 'FAQ',
    //             'url'   => 'page_ready_faq'
    //         ),
    //         array(
    //             'name'  => 'Pricing Tables',
    //             'url'   => 'page_ready_pricing_tables'
    //         ),
    //         array(
    //             'name'  => 'Invoice',
    //             'url'   => 'page_ready_invoice'
    //         ),
    //         array(
    //             'name'  => 'Forum (3)',
    //             'url'   => 'page_ready_forum'
    //         ),
    //         array(
    //             'name'  => 'Coming Soon',
    //             'url'   => 'page_ready_coming_soon'
    //         ),
    //         array(
    //             'name'  => 'Login, Register &amp; Lock',
    //             'sub'   => array(
    //                 array(
    //                     'name'  => 'Login',
    //                     'url'   => 'login'
    //                 ),
    //                 array(
    //                     'name'  => 'Login (Full Background)',
    //                     'url'   => 'login_full'
    //                 ),
    //                 array(
    //                     'name'  => 'Login 2',
    //                     'url'   => 'login_alt'
    //                 ),
    //                 array(
    //                     'name'  => 'Password Reminder',
    //                     'url'   => 'login#reminder'
    //                 ),
    //                 array(
    //                     'name'  => 'Password Reminder 2',
    //                     'url'   => 'login_alt#reminder'
    //                 ),
    //                 array(
    //                     'name'  => 'Register',
    //                     'url'   => 'login#register'
    //                 ),
    //                 array(
    //                     'name'  => 'Register 2',
    //                     'url'   => 'login_alt#register'
    //                 ),
    //                 array(
    //                     'name'  => 'Lock Screen',
    //                     'url'   => 'page_ready_lock_screen'
    //                 ),
    //                 array(
    //                     'name'  => 'Lock Screen 2',
    //                     'url'   => 'page_ready_lock_screen_alt'
    //                 )
    //             )
    //         )
    //     )
    // ),
    // array(
    //     'name'  => 'Components',
    //     'icon'  => 'fa fa-wrench',
    //     'sub'   => array(
    //         array(
    //             'name'  => '3 Level Menu',
    //             'sub'   => array(
    //                 array(
    //                     'name'  => 'Link 1',
    //                     'url'   => '#'
    //                 ),
    //                 array(
    //                     'name'  => 'Link 2',
    //                     'url'   => '#'
    //                 )
    //             )
    //         ),
    //         array(
    //             'name'  => 'Maps',
    //             'url'   => 'page_comp_maps'
    //         ),
    //         array(
    //             'name'  => 'Charts',
    //             'url'   => 'page_comp_charts'
    //         ),
    //         array(
    //             'name'  => 'Gallery',
    //             'url'   => 'page_comp_gallery'
    //         ),
    //         array(
    //             'name'  => 'Carousel',
    //             'url'   => 'page_comp_carousel'
    //         ),
    //         array(
    //             'name'  => 'Calendar',
    //             'url'   => 'page_comp_calendar'
    //         ),
    //         array(
    //             'name'  => 'CSS3 Animations',
    //             'url'   => 'page_comp_animations'
    //         ),
    //         array(
    //             'name'  => 'Syntax Highlighting',
    //             'url'   => 'page_comp_syntax_highlighting'
    //         )
    //     )
    // )
);
