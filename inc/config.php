<?php

/**
 * config
 *
 * Author: pixelcave
 *
 * Configuration file. It contains variables used in the template as well as the primary navigation array from which the navigation is created
 *
 */
error_reporting(1);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

date_default_timezone_set('Asia/Manila');
$datetime = date('Y-m-d H:i:s', time());
session_start();
ob_start();

$res = '';

function connect()
{
    return mysqli_connect('lifelink-hris.c4o680t6dw9r.ap-southeast-1.rds.amazonaws.com', 'admin', 'lifelink_password', 'hrisv2', '3306');
}
$db = connect();

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

    $sql = "SELECT t.*, t1.employee_number
    FROM tbl_users t
    INNER JOIN tbl_personal_information t1
    ON t.email = t1.company_email
    WHERE t.email = ? AND t.password = ?";

    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $email, $password);


    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);


    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['hris_role'] = $row['role'];
        $_SESSION['hris_id'] = $row['ID'];
        $_SESSION['hris_email'] = $row['email'];
        $_SESSION['hris_company_id'] = $row['company_id'];
        $_SESSION['hris_account_name'] = $row['account_name'];
        $_SESSION['hris_employee_number'] = $row['employee_number'];
        $_SESSION['pending_task'] = $row['pending_task'];
        $_SESSION['file201'] = $row['file201'];
        $_SESSION['timekeeping'] = $row['timekeeping'];
        $_SESSION['training'] = $row['training'];
        $_SESSION['performance'] = $row['performance'];
        $_SESSION['generate_reports'] = $row['generate_reports'];
        $_SESSION['holiday_maintenance'] = $row['holiday_maintenance'];
        if (isset($_POST['remember_me'])) {
            if ($_POST["remember_me"] == '1' || $_POST["remember_me"] == 'on') {
                $hour = time() + 3600 * 24 * 30;
                setcookie('email', $email, $hour);
                setcookie('password', $password, $hour);
            }
        }
        try {
            mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$email','Logged in to the system','$datetime')");
        } catch (mysqli_sql_exception $e) {
            //CONTINUE
        }
        header('Location: index');
    } else {
        $sql1 = mysqli_query($db, "SELECT * FROM tbl_users WHERE email = '$email' AND `password` = '$password'");
        $sql1 = "SELECT * FROM tbl_users
        WHERE email = ? AND password = ?";

        $stmt = mysqli_prepare($db, $sql1);
        mysqli_stmt_bind_param($stmt, "ss", $email, $password);

        $email = $_POST['email'];
        $password = md5($_POST['password']);

        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $row1 = mysqli_fetch_assoc($result);
            $_SESSION['hris_id'] = $row1['ID'];
            $_SESSION['hris_email'] = $row1['email'];
            $_SESSION['hris_account_name'] = $row1['account_name'];
            $_SESSION['hris_role'] = $row1['role'];
            $_SESSION['pending_task'] = $row1['pending_task'];
            $_SESSION['file201'] = $row1['file201'];
            $_SESSION['timekeeping'] = $row1['timekeeping'];
            $_SESSION['training'] = $row1['training'];
            $_SESSION['holiday_maintenance'] = $row1['holiday_maintenance'];
            $_SESSION['performance'] = $row1['performance'];
            $_SESSION['generate_reports'] = $row1['generate_reports'];
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
if (isset($_POST['print_bank_summary'])) {
    $selected_cname = $_SESSION['summary_company'];
    $date_from = $_SESSION['summary_datefrom'];
    $date_to = $_SESSION['summary_dateto'];
    $total = 0;
    require('FPDF/fpdf.php');

    $pdf = new FPDF();

    $pdf->AddPage();
    $pdf->Cell(190, 3, '', '0', 1, 'C');
    $pdf->SetFont('Arial', 'B', 12);

    $pdf->Cell(190, 3, 'PAYROLL SUMMARY FOR BANK', '0', 1, 'C');

    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(190, 8, 'LIFELINK', '0', 1, 'C');

    $pdf->Cell(190, 8, '', '0', 1, 'C');

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(45, 8, 'DATE', 'LRT', 0, 'C');
    $pdf->Cell(55, 8, 'ACCOUNT NAME', 'LRT', 0, 'C');
    $pdf->Cell(45, 8, 'ACCOUNT NUMBER', 'LRT', 0, 'C');
    $pdf->Cell(45, 8, 'AMOUNT', 'LRT', 1, 'C');

    $pdf->SetFont('Arial', '', 8);

    $sql1 = mysqli_query($db, "SELECT * FROM tbl_employees_payslip WHERE (date_from = '$date_from' AND date_to = '$date_to') AND company_name = '$selected_cname'");
    while ($row = mysqli_fetch_assoc($sql1)) {
        $pdf->Cell(45, 8, $row['date_generated'], 'LRT', 0, 'C');
        $pdf->Cell(55, 8, $row['emp_name'], 'LRT', 0, 'C');
        $pdf->Cell(45, 8, $row['account_number'], 'LRT', 0, 'C');
        $pdf->Cell(45, 8, number_format($row['net_salary'], 2, ".", ","), 'LRT', 1, 'C');
        $total += $row['net_salary'];
    }

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(190, 4, '', 'T', 1, 'C');
    $pdf->Cell(190, 8, 'TOTAL:   ' . number_format($total, 2, ".", ","), '0', 1, 'L');

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(190, 8, '', '0', 1, 'C');
    $pdf->Cell(190, 8, '', '0', 1, 'C');
    $pdf->Cell(190, 8, '', '0', 1, 'C');
    $pdf->Cell(35, 8, 'DATE GENERATED:', '0', 0, 'L');
    $pdf->Cell(150, 8, $datetime, '0', 1, 'L');

    $pdf->Output();
}
if (isset($_POST['print_attendance_summary'])) {
    $date_from = $_POST['date_from'];
    $date_to = $_POST['date_to'];
    $employee_num = $_SESSION['hris_employee_number'];
    $employee_name = $_SESSION['hris_account_name'];
    $working_hours = 0;
    $paid_hours = 0;
    $hourMin = array();
    $payableHrs = array();

    require('FPDF/fpdf.php');

    $pdf = new FPDF();
    $pdf = new FPDF('L', 'mm', 'Legal');

    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 12);

    $pdf->Cell(335, 3, 'ATTENDANCE SUMMARY REPORT', '0', 1, 'C');

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(335, 8, 'LIFELINK', '0', 1, 'C');

    $pdf->Cell(335, 3, '', '0', 1, 'C');

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(335, 8, 'Period From ' . $date_from . ' to ' . $date_to, '0', 1, 'C');

    $pdf->Cell(335, 3, '', '0', 1, 'C');

    $pdf->Cell(335, 3, '', '0', 1, 'C');

    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(47, 8, 'EMP NUMBER', 'LRTB', 0, 'C');
    $pdf->Cell(63, 8, 'FULL NAME', 'LRTB', 0, 'C');
    $pdf->Cell(45, 8, 'WORKING HOURS', 'LRTB', 0, 'C');
    $pdf->Cell(45, 8, 'HOURS WITH PAY', 'LRTB', 0, 'C');
    $pdf->Cell(45, 8, 'TARDINESS', 'LRTB', 0, 'C');
    $pdf->Cell(45, 8, 'LEAVE W/OUT PAY', 'LRTB', 0, 'C');
    $pdf->Cell(45, 8, 'LEAVE WITH PAY', 'LRTB', 1, 'C');

    // computation of hours -------------------------------------------------------------------------------

    $get_workinghrs = mysqli_query($db, "SELECT * FROM tbl_attendance WHERE (datenow BETWEEN '$date_from' AND '$date_to') AND (emp_num = '$employee_num' AND total_duration != 0)");
    while ($row = mysqli_fetch_assoc($get_workinghrs)) {
        array_push($hourMin, $row['total_duration']);

        if ($row['duration_hours'] > 8) {
            array_push($payableHrs, '8:00');
        } else {
            array_push($payableHrs, $row['total_duration']);
        }
    }

    $hours = 0;
    $mins  = 0;
    foreach ($hourMin as $val) {
        $explodeHoursMins = explode(':', $val);
        $hours += $explodeHoursMins[0];
        $mins  += $explodeHoursMins[1];
    }

    $minToHours =  date('H:i', mktime(0, $mins)); //Calculate Hours From Minutes
    $explodeMinToHours = explode(':', $minToHours);
    $hours += $explodeMinToHours[0];
    $finalMinutes = $explodeMinToHours[1];
    $working_hours = $hours . ':' . $finalMinutes;

    $hours1 = 0;
    $mins1  = 0;
    foreach ($payableHrs as $val) {
        $explodeHoursMins1 = explode(':', $val);
        $hours1 += $explodeHoursMins1[0];
        $mins1  += $explodeHoursMins1[1];
    }

    $minToHours1 =  date('H:i', mktime(0, $mins1)); //Calculate Hours From Minutes
    $explodeMinToHours1 = explode(':', $minToHours1);
    $hours1 += $explodeMinToHours1[0];
    $finalMinutes1 = $explodeMinToHours1[1];
    $paid_hours = $hours1 . ':' . $finalMinutes1;

    // computation of hours -------------------------------------------------------------------------------

    // computation of tardiness -------------------------------------------------------------------------------


    $hours_completed = '';
    $mins_completed = '';
    $tardinessArray = array();
    $tardiness = '';

    $get_tardiness = mysqli_query($db, "SELECT * FROM tbl_attendance WHERE (emp_num = '$employee_num' AND total_duration != '0') AND (datenow BETWEEN '$date_from' AND '$date_to')");
    while ($row = mysqli_fetch_assoc($get_tardiness)) {
        $hours_completed = $row['duration_hours'];
        $mins_completed = $row['duration_minutes'];

        $hour_deficiency = 0;
        $min_deficiency = 0;

        if ($hours_completed < 8) {
            $additional = 0;
            if ($mins_completed > 0) {
                $additional = 1;
            }

            $hour_deficiency = (8 - $additional) - $hours_completed;
            $min_deficiency = 60 - $mins_completed;
            $toPush = $hour_deficiency . ':' . $min_deficiency;
            array_push($tardinessArray, $toPush);
        }
    }

    $hours2 = 0;
    $mins2 = 0;
    foreach ($tardinessArray as $val) {
        $explodeHoursMins2 = explode(':', $val);
        $hours2 += $explodeHoursMins2[0];
        $mins2  += $explodeHoursMins2[1];
    }

    $minToHours2 =  date('H:i', mktime(0, $mins2)); //Calculate Hours From Minutes
    $explodeMinToHours2 = explode(':', $minToHours2);
    $hours2 += $explodeMinToHours2[0];
    $finalMinutes2 = $explodeMinToHours2[1];
    $tardiness = $hours2 . ':' . $finalMinutes2;

    // computation of tardiness -------------------------------------------------------------------------------

    // computation of absent ---------------------------------------------------------------------------------

    $holidays = array();
    $accomplished_days = 0;
    $accomplished_holiday = 0;
    $holidays_this_cutoff = 0;

    $get_holidays = mysqli_query($db, "SELECT * FROM tbl_holidays WHERE holiday_date BETWEEN '$date_from' AND '$date_to'");
    while ($row = mysqli_fetch_assoc($get_holidays)) {
        array_push($holidays, $row['holiday_date']);
    }


    $get_no_holidays = mysqli_query($db, "SELECT * FROM tbl_holidays WHERE holiday_date BETWEEN '$date_from' AND '$date_to'");
    while ($row = mysqli_fetch_assoc($get_no_holidays)) {
        $holidays_this_cutoff += 1;
    }


    $get_accomplished = mysqli_query($db, "SELECT * FROM tbl_attendance WHERE (emp_num = '$employee_num' AND total_duration != '0') AND (datenow BETWEEN '$date_from' AND '$date_to')");
    while ($row = mysqli_fetch_assoc($get_accomplished)) {
        $accomplished_days += 1;
    }

    $get_accomplished_holidays = mysqli_query($db, "SELECT * FROM tbl_attendance WHERE (emp_num = '$employee_num' AND total_duration != '0') AND (datenow BETWEEN '$date_from' AND '$date_to')");
    while ($row = mysqli_fetch_assoc($get_accomplished_holidays)) {
        foreach ($holidays as $holiday) {
            if ($row['datenow'] == $holiday) {
                $accomplished_holiday += 1;
                $accomplished_days -= 1;
            }
        }
    }

    $no_working_days = getWorkingDays($date_from, $date_to, $holidays);

    if ($accomplished_days < $no_working_days) {
        $absent = $no_working_days - $accomplished_days;
    } else {
        $absent = 0;
    }

    // computation of absent ---------------------------------------------------------------------------------

    // computation of leave ---------------------------------------------------------------------------------
    $total_leaves = '0';
    $get_leaves = mysqli_query($db, "SELECT * FROM tbl_leave_requests WHERE (delegated_emp_number = '$employee_num' AND status = 'Approved') AND (startDate BETWEEN '$date_from' AND '$date_to') AND (endDate BETWEEN '$date_from' AND '$date_to')");
    while ($row = mysqli_fetch_assoc($get_leaves)) {
        $total_leaves += $row['total_day'];
    }

    // computation of leave ---------------------------------------------------------------------------------

    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(47, 8, $employee_num, 'LRTB', 0, 'C');
    $pdf->Cell(63, 8, $employee_name, 'LRTB', 0, 'C');
    $pdf->Cell(45, 8, $working_hours, 'LRTB', 0, 'C');
    $pdf->Cell(45, 8, $paid_hours, 'LRTB', 0, 'C');
    $pdf->Cell(45, 8, $tardiness, 'LRTB', 0, 'C');
    $pdf->Cell(45, 8, $absent . ' day(s)', 'LRTB', 0, 'C');
    $pdf->Cell(45, 8, $total_leaves . ' day(s)', 'LRTB', 1, 'C');


    $pdf->Cell(335, 3, '', '0', 1, 'C');
    $pdf->Cell(335, 3, '', '0', 1, 'C');
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(335, 3, 'DAILY ATTENDANCE SUMMARY', '0', 1, 'C');
    $pdf->Cell(335, 3, '', '0', 1, 'C');
    $pdf->Cell(335, 3, '', '0', 1, 'C');


    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(57, 8, 'EMP NUMBER', 'LRTB', 0, 'C');
    $pdf->Cell(57, 8, 'FULL NAME', 'LRTB', 0, 'C');
    $pdf->Cell(50, 8, 'DATE', 'LRTB', 0, 'C');
    $pdf->Cell(57, 8, 'TIME-IN', 'LRTB', 0, 'C');
    $pdf->Cell(57, 8, 'TIME-OUT', 'LRTB', 0, 'C');
    $pdf->Cell(57, 8, 'DURATION', 'LRTB', 1, 'C');

    $pdf->SetFont('Arial', 'B', 8);

    $get_attendance = mysqli_query($db, "SELECT * FROM tbl_attendance WHERE (emp_num = '$employee_num' AND statusnow = 'Time Out') AND (datenow BETWEEN '$date_from' AND '$date_to')");
    while ($row = mysqli_fetch_assoc($get_attendance)) {

        $pdf->Cell(57, 8, $row['emp_num'], 'LRTB', 0, 'C');
        $pdf->Cell(57, 8, $row['emp_name'], 'LRTB', 0, 'C');
        $pdf->Cell(50, 8, $row['datenow'], 'LRTB', 0, 'C');
        $pdf->Cell(57, 8, getTimeIn($row['emp_num'], $row['datenow']), 'LRTB', 0, 'C');
        $pdf->Cell(57, 8, $row['timenow'], 'LRTB', 0, 'C');
        $ttl = explode(':', $row['total_duration']);
        if (!empty($ttl[1])) {
            $ttl[1] = $ttl[1];
        } else {
            $ttl[1] = '0';
        }
        $pdf->Cell(57, 8, $ttl[0] . ' hrs ' . $ttl[1] . ' min', 'LRTB', 1, 'C');
    }

    $pdf->Output();
}
function getTimeIn($employee_num, $date)
{
    $timein = null;
    $db = connect();
    $get_timein = mysqli_query($db, "SELECT * FROM tbl_attendance WHERE (emp_num = '$employee_num' AND statusnow = 'Time In') AND (datenow = '$date')");
    while ($row = mysqli_fetch_assoc($get_timein)) {
        $timein = $row['timenow'];
    }

    return $timein;
}
// config manual-attendance
if (isset($_POST['manual_timeinandout'])) {
    if ($_POST['employee_id'] == "null") {
        $res = '<div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-times"></i> Please fill up all necessary fields.</h4>
            </div>';
    } else {

        $employee_num = $_POST['employee_id'];

        $date = $_POST['date'];
        $time_in = $_POST['time_in'];
        $time_out = $_POST['time_out'];

        $get_employee_name = mysqli_query($db, "SELECT * FROM tbl_personal_information WHERE employee_number = '$employee_num' ");
        while ($row = mysqli_fetch_assoc($get_employee_name)) {
            $employee_name = $row['account_name'];
        }
        // time in
        $timein_now = date('h:i:s a', strtotime($time_in));
        $timein24_now = date("H:i:s", strtotime($time_in));
        mysqli_query($db, "INSERT INTO tbl_attendance VALUES('','$employee_num','$employee_name','Time In','$date','$timein_now', '$timein24_now', '0', '0', '0')");

        // time out
        $timeout_now = date('h:i:s a', strtotime($time_out));
        $timeout24_now = date("H:i:s", strtotime($time_out));

        // COMPUTATION OF DURATION
        $starttime = $timein_now;
        $endtime = $timeout24_now;

        function dateTimeDiff($time1, $time2)
        {
            $diff = array();
            $first = strtotime($time1);
            $second = strtotime($time2);
            $datediff = abs($first - $second);
            $dif['h'] = floor($datediff / (60 * 60));
            $dif['m'] = floor($datediff / (60));

            return $dif;
        }
        $workingHours = dateTimeDiff($endtime, $starttime)['h'] - 1;
        $initial_workingMinutes = dateTimeDiff($endtime, $starttime)['m'];

        $n = $initial_workingMinutes / 60;

        $whole = floor($n);
        $fraction = $n - $whole;

        $workingMinutes = $fraction * 60;

        $total_duration = $workingHours . ':' . $workingMinutes;


        mysqli_query($db, "INSERT INTO tbl_attendance VALUES('','$employee_num','$employee_name','Time Out','$date','$timeout_now', '$timeout24_now', '$workingHours', '$workingMinutes','$total_duration')");




        $res = '<div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="fa fa-check-circle"></i> Manual adding of Time In & Time Out Success.</h4>
        </div>';
    }
}
if (isset($_POST['delete_manual_attendance'])) {
    if (empty($_POST['selected_attendance_delete'])) {
        $all_manualattendance = null;
    } else {
        $all_manualattendance = $_POST['selected_attendance_delete'];
    }

    if ($all_manualattendance == null) {
        $res = '<div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-times"></i> Select at least one to delete.</h4>
        </div>';
    } else {
        foreach ($all_manualattendance as $manual_attendance_id) {
            $db = connect();
            mysqli_query($db, "DELETE FROM tbl_attendance WHERE id = '$manual_attendance_id'");
        }

        $res = '<div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="fa fa-check-circle"></i> Selected Attendance(s) has been deleted successfully.</h4>
        </div>';
    }
}
// end config manual-attendance

if (isset($_POST['delete_cutoff'])) {

    if (empty($_POST['selected_cutoff_delete'])) {
        $all_cutoffid = null;
    } else {
        $all_cutoffid = $_POST['selected_cutoff_delete'];
    }

    if ($all_cutoffid == null) {
        $res = '<div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-times"></i> Select at least one to delete.</h4>
        </div>';
    } else {
        foreach ($all_cutoffid as $cutoff_id) {
            $db = connect();
            mysqli_query($db, "DELETE FROM tbl_cutoffs WHERE reference_num = '$cutoff_id'");
        }

        $res = '<div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="fa fa-check-circle"></i> Selected Cutoff(s) has been deleted successfully.</h4>
        </div>';
    }
}
if (isset($_POST['delete_registry'])) {

    if (empty($_POST['selected_cutoff_delete'])) {
        $all_cutoffid = null;
    } else {
        $all_cutoffid = $_POST['selected_cutoff_delete'];
    }

    if ($all_cutoffid == null) {
        $res = '<div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-times"></i> Select at least one to delete.</h4>
        </div>';
    } else {
        foreach ($all_cutoffid as $cutoff_id) {
            $db = connect();
            $date_from = '0000-00-00';
            $date_to = '0000-00-00';
            $company_name = '';
            $get_dates = mysqli_query($db, "SELECT * FROM tbl_cutoffs WHERE reference_num = '$cutoff_id'");
            while ($row = mysqli_fetch_assoc($get_dates)) {
                $date_from = $row['date_from'];
                $date_to = $row['date_to'];
                $company_name = $row['company_name'];
            }

            mysqli_query($db, "DELETE FROM tbl_employees_payslip WHERE (company_name = '$company_name') AND (date_from = '$date_from' AND date_to = '$date_to')");

            mysqli_query($db, "DELETE FROM tbl_cutoffs WHERE reference_num = '$cutoff_id'");
        }

        $res = '<div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="fa fa-check-circle"></i> Selected Registry(s) and Cutoff(s) has been deleted successfully.</h4>
        </div>';
    }
}
if (isset($_POST['print_overall_attendance_summary'])) {
    $date_from = $_POST['date_from'];
    $date_to = $_POST['date_to'];
    $emp_numz = array();

    $employee_num = '';
    $employee_name = '';

    $get_all = mysqli_query($db, "SELECT * FROM tbl_employees");
    while ($row = mysqli_fetch_assoc($get_all)) {
        array_push($emp_numz, $row['emp_num']);
    }

    require('FPDF/fpdf.php');

    $pdf = new FPDF();
    $pdf = new FPDF('L', 'mm', 'Legal');

    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 12);

    $pdf->Cell(335, 3, 'ATTENDANCE SUMMARY REPORT', '0', 1, 'C');

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(335, 8, 'LIFELINK', '0', 1, 'C');

    $pdf->Cell(335, 3, '', '0', 1, 'C');

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(335, 8, 'Period From ' . $date_from . ' to ' . $date_to, '0', 1, 'C');

    $pdf->Cell(335, 3, '', '0', 1, 'C');

    $pdf->Cell(335, 3, '', '0', 1, 'C');

    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(47, 8, 'EMP NUMBER', 'LRTB', 0, 'C');
    $pdf->Cell(63, 8, 'FULL NAME', 'LRTB', 0, 'C');
    $pdf->Cell(45, 8, 'WORKING HOURS', 'LRTB', 0, 'C');
    $pdf->Cell(45, 8, 'HOURS WITH PAY', 'LRTB', 0, 'C');
    $pdf->Cell(45, 8, 'TARDINESS', 'LRTB', 0, 'C');
    $pdf->Cell(45, 8, 'LEAVE W/OUT PAY', 'LRTB', 0, 'C');
    $pdf->Cell(45, 8, 'LEAVE WITH PAY', 'LRTB', 1, 'C');

    // computation of hours -------------------------------------------------------------------------------

    foreach ($emp_numz as $employee_num) {
        $working_hours = 0;
        $paid_hours = 0;
        $hourMin = array();
        $payableHrs = array();
        $get_name = mysqli_query($db, "SELECT * FROM tbl_employees WHERE emp_num = '$employee_num'");
        while ($row = mysqli_fetch_assoc($get_name)) {
            $employee_name = $row['emp_name'];
        }

        $get_workinghrs = mysqli_query($db, "SELECT * FROM tbl_attendance WHERE (datenow BETWEEN '$date_from' AND '$date_to') AND (emp_num = '$employee_num' AND total_duration != 0)");
        while ($row = mysqli_fetch_assoc($get_workinghrs)) {
            array_push($hourMin, $row['total_duration']);

            if ($row['duration_hours'] > 8) {
                array_push($payableHrs, '8:00');
            } else {
                array_push($payableHrs, $row['total_duration']);
            }
        }

        $hours = 0;
        $mins  = 0;
        foreach ($hourMin as $val) {
            $explodeHoursMins = explode(':', $val);
            $hours += $explodeHoursMins[0];
            $mins  += $explodeHoursMins[1];
        }

        $minToHours =  date('H:i', mktime(0, $mins)); //Calculate Hours From Minutes
        $explodeMinToHours = explode(':', $minToHours);
        $hours += $explodeMinToHours[0];
        $finalMinutes = $explodeMinToHours[1];
        $working_hours = $hours . ':' . $finalMinutes;

        $hours1 = 0;
        $mins1  = 0;
        foreach ($payableHrs as $val) {
            $explodeHoursMins1 = explode(':', $val);
            $hours1 += $explodeHoursMins1[0];
            $mins1  += $explodeHoursMins1[1];
        }

        $minToHours1 =  date('H:i', mktime(0, $mins1)); //Calculate Hours From Minutes
        $explodeMinToHours1 = explode(':', $minToHours1);
        $hours1 += $explodeMinToHours1[0];
        $finalMinutes1 = $explodeMinToHours1[1];
        $paid_hours = $hours1 . ':' . $finalMinutes1;

        // computation of hours -------------------------------------------------------------------------------

        // computation of tardiness -------------------------------------------------------------------------------


        $hours_completed = '';
        $mins_completed = '';
        $tardinessArray = array();
        $tardiness = '';

        $get_tardiness = mysqli_query($db, "SELECT * FROM tbl_attendance WHERE (emp_num = '$employee_num' AND total_duration != '0') AND (datenow BETWEEN '$date_from' AND '$date_to')");
        while ($row = mysqli_fetch_assoc($get_tardiness)) {
            $hours_completed = $row['duration_hours'];
            $mins_completed = $row['duration_minutes'];

            $hour_deficiency = 0;
            $min_deficiency = 0;

            if ($hours_completed < 8) {
                $additional = 0;
                if ($mins_completed > 0) {
                    $additional = 1;
                }

                $hour_deficiency = (8 - $additional) - $hours_completed;
                $min_deficiency = 60 - $mins_completed;
                $toPush = $hour_deficiency . ':' . $min_deficiency;
                array_push($tardinessArray, $toPush);
            }
        }

        $hours2 = 0;
        $mins2 = 0;
        foreach ($tardinessArray as $val) {
            $explodeHoursMins2 = explode(':', $val);
            $hours2 += $explodeHoursMins2[0];
            $mins2  += $explodeHoursMins2[1];
        }

        $minToHours2 =  date('H:i', mktime(0, $mins2)); //Calculate Hours From Minutes
        $explodeMinToHours2 = explode(':', $minToHours2);
        $hours2 += $explodeMinToHours2[0];
        $finalMinutes2 = $explodeMinToHours2[1];
        $tardiness = $hours2 . ':' . $finalMinutes2;

        // computation of tardiness -------------------------------------------------------------------------------

        // computation of absent ---------------------------------------------------------------------------------

        $holidays = array();
        $accomplished_days = 0;
        $accomplished_holiday = 0;
        $holidays_this_cutoff = 0;

        $get_holidays = mysqli_query($db, "SELECT * FROM tbl_holidays WHERE holiday_date BETWEEN '$date_from' AND '$date_to'");
        while ($row = mysqli_fetch_assoc($get_holidays)) {
            array_push($holidays, $row['holiday_date']);
        }


        $get_no_holidays = mysqli_query($db, "SELECT * FROM tbl_holidays WHERE holiday_date BETWEEN '$date_from' AND '$date_to'");
        while ($row = mysqli_fetch_assoc($get_no_holidays)) {
            $holidays_this_cutoff += 1;
        }


        $get_accomplished = mysqli_query($db, "SELECT * FROM tbl_attendance WHERE (emp_num = '$employee_num' AND total_duration != '0') AND (datenow BETWEEN '$date_from' AND '$date_to')");
        while ($row = mysqli_fetch_assoc($get_accomplished)) {
            $accomplished_days += 1;
        }

        $get_accomplished_holidays = mysqli_query($db, "SELECT * FROM tbl_attendance WHERE (emp_num = '$employee_num' AND total_duration != '0') AND (datenow BETWEEN '$date_from' AND '$date_to')");
        while ($row = mysqli_fetch_assoc($get_accomplished_holidays)) {
            foreach ($holidays as $holiday) {
                if ($row['datenow'] == $holiday) {
                    $accomplished_holiday += 1;
                    $accomplished_days -= 1;
                }
            }
        }

        $no_working_days = getWorkingDays($date_from, $date_to, $holidays);

        if ($accomplished_days < $no_working_days) {
            $absent = $no_working_days - $accomplished_days;
        } else {
            $absent = 0;
        }

        // computation of absent ---------------------------------------------------------------------------------

        // computation of leave ---------------------------------------------------------------------------------
        $total_leaves = '0';
        $get_leaves = mysqli_query($db, "SELECT * FROM tbl_leave_requests WHERE (delegated_emp_number = '$employee_num' AND status = 'Approved') AND (startDate BETWEEN '$date_from' AND '$date_to') AND (endDate BETWEEN '$date_from' AND '$date_to')");
        while ($row = mysqli_fetch_assoc($get_leaves)) {
            $total_leaves += $row['total_day'];
        }

        // computation of leave ---------------------------------------------------------------------------------

        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(47, 8, $employee_num, 'LRTB', 0, 'C');
        $pdf->Cell(63, 8, $employee_name, 'LRTB', 0, 'C');
        $pdf->Cell(45, 8, $working_hours, 'LRTB', 0, 'C');
        $pdf->Cell(45, 8, $paid_hours, 'LRTB', 0, 'C');
        $pdf->Cell(45, 8, $tardiness, 'LRTB', 0, 'C');
        $pdf->Cell(45, 8, $absent . ' day(s)', 'LRTB', 0, 'C');
        $pdf->Cell(45, 8, $total_leaves . ' day(s)', 'LRTB', 1, 'C');
    }

    $pdf->Cell(335, 3, '', '0', 1, 'C');
    $pdf->Cell(335, 3, '', '0', 1, 'C');
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(335, 3, 'DAILY ATTENDANCE SUMMARY', '0', 1, 'C');
    $pdf->Cell(335, 3, '', '0', 1, 'C');
    $pdf->Cell(335, 3, '', '0', 1, 'C');


    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(57, 8, 'EMP NUMBER', 'LRTB', 0, 'C');
    $pdf->Cell(57, 8, 'FULL NAME', 'LRTB', 0, 'C');
    $pdf->Cell(50, 8, 'DATE', 'LRTB', 0, 'C');
    $pdf->Cell(57, 8, 'TIME-IN', 'LRTB', 0, 'C');
    $pdf->Cell(57, 8, 'TIME-OUT', 'LRTB', 0, 'C');
    $pdf->Cell(57, 8, 'DURATION', 'LRTB', 1, 'C');

    $pdf->SetFont('Arial', '', 8);
    $get_attendance = mysqli_query($db, "SELECT * FROM tbl_attendance WHERE (statusnow = 'Time Out') AND (datenow BETWEEN '$date_from' AND '$date_to')");
    while ($row = mysqli_fetch_assoc($get_attendance)) {

        $pdf->Cell(57, 8, $row['emp_num'], 'LRTB', 0, 'C');
        $pdf->Cell(57, 8, $row['emp_name'], 'LRTB', 0, 'C');
        $pdf->Cell(50, 8, $row['datenow'], 'LRTB', 0, 'C');
        $pdf->Cell(57, 8, getTimeIn($row['emp_num'], $row['datenow']), 'LRTB', 0, 'C');
        $pdf->Cell(57, 8, $row['timenow'], 'LRTB', 0, 'C');
        $ttl = explode(':', $row['total_duration']);
        if (!empty($ttl[1])) {
            $ttl[1] = $ttl[1];
        } else {
            $ttl[1] = '0';
        }
        $pdf->Cell(57, 8, $ttl[0] . ' hrs ' . $ttl[1] . ' min', 'LRTB', 1, 'C');
    }


    $pdf->Output();
}
if (isset($_POST['print_payroll_summary'])) {
    $selected_cname = $_SESSION['summary_company'];
    $date_from = $_SESSION['summary_datefrom'];
    $date_to = $_SESSION['summary_dateto'];
    $total = 0;
    require('FPDF/fpdf.php');

    $pdf = new FPDF('L', 'mm', 'Legal');

    $pdf->AddPage();
    $pdf->Cell(190, 3, '', '0', 1, 'C');
    $pdf->SetFont('Arial', 'B', 14);

    $pdf->Cell(330, 3, 'PAYROLL SUMMARY FOR EMPLOYEES', '0', 1, 'C');

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(330, 8, 'LIFELINK', '0', 1, 'C');

    $pdf->Cell(190, 5, '', '0', 1, 'C');

    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(30, 8, 'EMPLOYEE NUMBER', 'LRT', 0, 'C');
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(50, 8, 'EMPLOYEE NAME', 'LRT', 0, 'C');
    $pdf->Cell(37, 8, 'ACCOUNT NUMBER', 'LRT', 0, 'C');
    $pdf->Cell(32, 8, 'Gross Pay', 'LRT', 0, 'C');
    $pdf->Cell(32, 8, 'SSS', 'LRT', 0, 'C');
    $pdf->Cell(32, 8, 'Philhealth', 'LRT', 0, 'C');
    $pdf->Cell(31, 8, 'Pagibig', 'LRT', 0, 'C');
    $pdf->Cell(31, 8, 'Deminimis', 'LRT', 0, 'C');
    $pdf->Cell(31, 8, 'Withholding Tax', 'LRT', 0, 'C');
    $pdf->Cell(31, 8, 'AMOUNT', 'LRT', 1, 'C');

    $pdf->SetFont('Arial', '', 8);

    $sql = mysqli_query($db, "SELECT * FROM tbl_employees_payslip WHERE (date_from = '$date_from' AND date_to = '$date_to') AND company_name = '$selected_cname'");
    while ($row = mysqli_fetch_assoc($sql)) {
        $pdf->Cell(30, 8, $row['emp_num'], 'LRT', 0, 'C');
        $pdf->Cell(50, 8, $row['emp_name'], 'LRT', 0, 'C');
        $pdf->Cell(37, 8, $row['account_number'], 'LRT', 0, 'C');
        $pdf->Cell(32, 8, number_format($row['gross_pay'], 2, ".", ","), 'LRT', 0, 'C');
        $pdf->Cell(32, 8, number_format($row['sss'], 2, ".", ","), 'LRT', 0, 'C');
        $pdf->Cell(32, 8, number_format($row['philhealth'], 2, ".", ","), 'LRT', 0, 'C');
        $pdf->Cell(31, 8, number_format($row['pagibig'], 2, ".", ","), 'LRT', 0, 'C');
        $pdf->Cell(31, 8, number_format($row['deminimis'], 2, ".", ","), 'LRT', 0, 'C');
        $pdf->Cell(31, 8, number_format($row['withholding_tax'], 2, ".", ","), 'LRT', 0, 'C');
        $pdf->Cell(31, 8, number_format($row['net_salary'], 2, ".", ","), 'LRT', 1, 'C');
        $total += $row['net_salary'];
    }

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(335, 4, '', 'T', 1, 'C');
    $pdf->Cell(190, 10, 'TOTAL:   ' . number_format($total, 2, ".", ","), '0', 1, 'L');
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(190, 8, '', '0', 1, 'C');
    $pdf->Cell(35, 8, 'DATE GENERATED:', '0', 0, 'L');
    $pdf->Cell(150, 8, $datetime, '0', 1, 'L');

    $pdf->Output();
}

if (isset($_POST['add_cutoff'])) {
    $company_name = $_POST['company_name'];
    $date_from = $_POST['date_from'];
    $date_to = $_POST['date_to'];
    $created_by = $_SESSION['hris_account_name'];
    $status = 'active';

    if ($date_from == null || $date_to == null) {
        $res = '<div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="fa fa-warning"></i>Please complete the data needed to proceed.</h4>
        </div>';
    } else {
        $sql = mysqli_query($db, "INSERT INTO tbl_cutoffs VALUES('','$company_name','$date_from','$date_to','$created_by','$status')");
        if ($sql) {

            $res = '<div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="fa fa-check"></i>Cut off has been added in ' . $company_name . ' Company</h4>
        </div>';

            mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$created_by','Added a Cutoff: $company_name','$datetime')");
        }
    }
}
if (isset($_POST['btn_decline_leave_application'])) {
    $leaveapplication_id = $_POST['la_id'];
    $date_filed = $_POST['la_application_date'];
    $leave_type = $_POST['la_leave_type'];
    $startDate = $_POST['la_startDate'];
    $endDate = $_POST['la_endDate'];
    $applied_by = $_POST['applied_by'];
    $approver_remarks = $_POST['la_approver_remarks'];
    $reason_for_cancellation = $_POST['reason_for_cancellation'];


    $total_days = $_POST['la_total_days'];
    $employee_number = $_POST['la_emp_number'];
    if (isset($_POST['la_approver'])) {
        $approver = $_POST['la_approver'];
    }
    $approver = $_SESSION['hris_employee_number'];
    $status = "Declined";
    $sql = mysqli_query($db, "UPDATE tbl_leave_requests SET status = '$status', approver = '$approver', approver_remarks = '$approver_remarks', leave_wo_pay_days = '0' WHERE ID = '$leaveapplication_id'");
    $at_name = $_SESSION['hris_account_name'];

    $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> Leave Application for <strong>' . $applied_by . '<strong> has been declined.</h4>
        </div>';
    $cancellation_reason = $approver_remarks;

    $_SESSION['la_status'] = $status;
    $_SESSION['approver'] = $approver;
    $_SESSION['approver_remarks'] = $approver_remarks;

    $at_name = $_SESSION['hris_account_name'];
    mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Declined Leave Application ID: LA-$leaveapplication_id','$datetime')");
}
if (isset($_POST['generate_payroll'])) {
    $created_by = $_SESSION['hris_account_name'];
    $all_cutid = $_POST['selected_cutoff'];
    $company_name = $_POST['company_name'];
    $date_from;
    $date_to;

    if ($all_cutid == null) {
        $res = '<div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="fa fa-warning"></i>Failed to proceed, no selected cutoff.</h4>
        </div>';
    } else {

        foreach ($all_cutid as $cut_id) {
            $db = connect();
            $sql1 = mysqli_query($db, "SELECT * FROM tbl_cutoffs WHERE reference_num = '$cut_id'");
            while ($row = mysqli_fetch_assoc($sql1)) {
                $date_from = $row['date_from'];
                $date_to = $row['date_to'];
            }


            $sql = mysqli_query($db, "SELECT * FROM tbl_employees WHERE company_name = '$company_name'");
            while ($row = mysqli_fetch_assoc($sql)) {
                /*data retrieval*/
                $emp_num = $row['emp_num'];
                $emp_name = $row['emp_name'];
                $company_name = $row['company_name'];
                $company_position = $row['company_position'];
                $account_number = $row['account_number'];
                $sss = $row['sss_ee'];
                $philhealth = $row['hdmf_ee'];
                $pagibig = $row['philhealth_ee'];
                $sss_er = $row['sss_er'];
                $sss_ec = $row['sss_ec'];
                $philhealth_er = $row['philhealth_er'];
                $pagibig_er = $row['hdmf_er'];
                $deminimis = $row['deminimis'];
                $basic_pay = $row['basic_salary'];
                $log_exemption = $row['log_exemption'];
                $commission = $row['commission'];
                $withholding_tax = $row['withholding_tax'];

                $explode_date = explode('-', $date_from);
                $cutday = $explode_date[2];

                if ($cutday == '1') {
                    $sss = 0;
                    $philhealth = 0;
                    $pagibig = 0;
                    $sss_er = 0;
                    $sss_ec = 0;
                    $philhealth_er = 0;
                    $pagibig_er = 0;
                }


                //// computation of absent -------------------------------------------------------------

                $holidays = array();
                $accomplished_days = 0;
                $accomplished_holiday = 0;
                $holidays_this_cutoff = 0;

                $get_holidays = mysqli_query($db, "SELECT * FROM tbl_holidays WHERE holiday_date BETWEEN '$date_from' AND '$date_to'");
                while ($row = mysqli_fetch_assoc($get_holidays)) {
                    array_push($holidays, $row['holiday_date']);
                }


                $get_no_holidays = mysqli_query($db, "SELECT * FROM tbl_holidays WHERE holiday_date BETWEEN '$date_from' AND '$date_to'");
                while ($row = mysqli_fetch_assoc($get_no_holidays)) {
                    $holidays_this_cutoff += 1;
                }


                $get_accomplished = mysqli_query($db, "SELECT * FROM tbl_attendance WHERE (emp_num = '$emp_num' AND total_duration != '0') AND (datenow BETWEEN '$date_from' AND '$date_to')");
                while ($row = mysqli_fetch_assoc($get_accomplished)) {
                    $accomplished_days += 1;
                }

                $get_accomplished_holidays = mysqli_query($db, "SELECT * FROM tbl_attendance WHERE (emp_num = '$emp_num' AND total_duration != '0') AND (datenow BETWEEN '$date_from' AND '$date_to')");
                while ($row = mysqli_fetch_assoc($get_accomplished_holidays)) {
                    foreach ($holidays as $holiday) {
                        if ($row['datenow'] == $holiday) {
                            $accomplished_holiday += 1;
                            $accomplished_days -= 1;
                        }
                    }
                }

                $no_working_days = getWorkingDays($date_from, $date_to, $holidays);

                if ($accomplished_days < $no_working_days) {
                    $absent = $no_working_days - $accomplished_days;
                } else {
                    $absent = 0;
                }

                $daily_rate = $basic_pay / 20;
                $hourly_rate = $daily_rate / 8;
                $minute_rate = $hourly_rate / 60;

                $absent_deduction = $daily_rate * $absent;
                $absent_deduction = round($absent_deduction, 2);

                //// computation of absent -------------------------------------------------------------



                //// computation of tardiness -------------------------------------------------------------
                $tardiness = 0;
                $daily_rate = $basic_pay / 20;
                $hourly_rate = $daily_rate / 8;
                $minute_rate = $hourly_rate / 60;

                $get_tardiness = mysqli_query($db, "SELECT * FROM tbl_attendance WHERE (emp_num = '$emp_num' AND total_duration != '0') AND (datenow BETWEEN '$date_from' AND '$date_to')");
                while ($row = mysqli_fetch_assoc($get_tardiness)) {
                    $hours_completed = $row['duration_hours'];
                    $mins_completed = $row['duration_minutes'];

                    $hour_deduction = 0;
                    $min_deduction = 0;
                    $initial_tardiness = 0;
                    $hour_deficiency = 0;
                    $min_deficiency = 0;

                    if ($hours_completed < 8) {
                        $additional = 0;
                        if ($mins_completed > 0) {
                            $additional = 1;
                        }

                        $hour_deficiency = (8 - $additional) - $hours_completed;
                        $min_deficiency = 60 - $mins_completed;

                        $hour_deduction = $hourly_rate * $hour_deficiency;
                        if ($min_deficiency != 60) {
                            $min_deduction = $minute_rate * $min_deficiency;
                        }

                        $initial_tardiness = $hour_deduction + $min_deduction;
                        $tardiness += $initial_tardiness;
                    }
                }

                $tardiness = round($tardiness, 2);


                //// computation of tardiness -------------------------------------------------------------

                $holiday_pay = 0;

                if ($holidays_this_cutoff > 0) {
                    $holiday_pay = $holidays_this_cutoff * $daily_rate;
                }

                if ($accomplished_holiday > 0) {
                    $temp = $accomplished_holiday * $daily_rate;
                    $holiday_pay += $temp;
                }

                /*computation of net salary*/
                $gross_pay = $basic_pay / 2;
                $contributions = $sss + $philhealth + $pagibig;


                if ($log_exemption == '1') {
                    $absent_deduction = 0;
                    $tardiness = 0;
                    $taxable_income = ($gross_pay + $commission) - ($contributions + $tardiness + $absent_deduction);
                    $net_salary = ($taxable_income + $deminimis + $holiday_pay) - ($withholding_tax);
                } else {
                    $taxable_income = ($gross_pay + $commission) - ($contributions + $tardiness + $absent_deduction);
                    $net_salary = ($taxable_income + $deminimis + $holiday_pay) - ($withholding_tax + $tardiness + $absent_deduction);
                }

                $net_salary = round($net_salary, 2);

                if ($net_salary < 0) {
                    $net_salary = 0;
                }

                mysqli_query($db, "INSERT INTO tbl_employees_payslip VALUES('','$emp_num','$emp_name','$company_name','$company_position','$account_number','$sss','$sss_er','$sss_ec','$philhealth','$philhealth_er','$pagibig','$pagibig_er','$deminimis','$commission','$gross_pay','$taxable_income','$withholding_tax','$absent_deduction','$tardiness','$holiday_pay','$net_salary','$net_salary','$date_from','$date_to','$datetime')");



                mysqli_query($db, "UPDATE tbl_cutoffs SET status = 'completed' WHERE reference_num = '$cut_id'");
            }
        }

        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$created_by','Generates payroll: $company_name','$datetime')");
        $res = '<div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="fa fa-check"></i>Payroll has been generated.</h4>
        </div>';
    }
}
if (isset($_POST['view_payslip'])) {
    if (empty($_POST['selected_cutoff'])) {
        $res = '<div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="fa fa-times"></i> No cutoff selected.</h4>
        </div>';
    } else {
        reset_view_payslip();
        $date_from = null;
        $date_to = null;
        $selected_empnum = $_POST['select_empnum'];
        $selected_cutid = $_POST['selected_cutoff'];
        $result = true;

        $sql3 = mysqli_query($db, "SELECT * FROM tbl_cutoffs WHERE reference_num = '$selected_cutid'");
        while ($row = mysqli_fetch_assoc($sql3)) {
            $date_from = $row['date_from'];
            $date_to = $row['date_to'];
        }

        $sql4 = mysqli_query($db, "SELECT * FROM tbl_employees_payslip WHERE (date_to = '$date_to' AND date_from = '$date_from') AND emp_num = '$selected_empnum'");
        if (mysqli_num_rows($sql4) <= 0) {
            $result = false;
        }

        if (empty($selected_empnum) || $date_to == null || $date_from == null) {
            $res = '<div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-times"></i> Please complete the data needed to proceed.</h4>
            </div>';
        } else if ($result == false) {
            $res = '<div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-times"></i> Payslip not found.</h4>
            </div>';
        } else {
            $sql = mysqli_query($db, "SELECT * FROM tbl_employees_payslip WHERE (emp_num = '$selected_empnum' AND date_from = '$date_from') AND date_to = '$date_to'");
            while ($row = mysqli_fetch_assoc($sql)) {
                $_SESSION['selected_empname'] = $row['emp_name'];
                $_SESSION['selected_jobtitle'] = $row['company_position'];
                $_SESSION['selected_comp'] = $row['company_name'];
                $_SESSION['selected_gross_pay'] = $row['gross_pay'];
                $_SESSION['selected_tax'] = $row['withholding_tax'];
                $_SESSION['selected_netsalary'] = $row['net_salary'];
                $_SESSION['selected_sss'] = $row['sss'];
                $_SESSION['selected_philhealth'] = $row['philhealth'];
                $_SESSION['selected_pagibig'] = $row['pagibig'];
                $_SESSION['selected_sss_er'] = $row['sss_er'];
                $_SESSION['selected_philhealth_er'] = $row['philhealth_er'];
                $_SESSION['selected_pagibig_er'] = $row['pagibig_er'];
                $_SESSION['selected_taxable_income'] = $row['taxable_income'];
                $_SESSION['selected_deminimis'] = $row['deminimis'];
                $_SESSION['selected_tardiness'] = $row['tardiness'];
                $_SESSION['selected_absent'] = $row['absent'];
                $_SESSION['selected_netsalary_beforeadjustment'] = $row['net_salary_before_adjustment'];
                $_SESSION['holiday_pay'] = $row['holiday_pay'];
            }

            $_SESSION['selected_empnum'] = $selected_empnum;
            $_SESSION['selecteddate_from'] = $date_from;
            $_SESSION['selecteddate_to'] = $date_to;


            header('Location: payroll-registry-view');
        }
    }
}
if (isset($_POST['add_employee'])) {
    $employee_num = $_POST['emp_num'];
    $emp_name = $_POST['emp_name'];
    $company_position = $_POST['job_title'];
    $company_name = $_POST['select_company'];
    $account_num = $_POST['acc_num'];
    $basic_salary = $_POST['basic_salary'];
    $sss_er = $_POST['sss_er'];
    $sss_ee = $_POST['sss_ee'];
    $sss_ec = $_POST['sss_ec'];
    $hdmf_er = $_POST['hdmf_er'];
    $hdmf_ee = $_POST['hdmf_ee'];
    $philhealth_er = $_POST['philhealth_er'];
    $philhealth_ee = $_POST['philhealth_ee'];
    $bank_name = $_POST['bank_name'];
    $deminimis = $_POST['deminimis'];
    $commission = $_POST['commission'];
    $remarks = $_POST['remarks'];
    $withholding_tax = '';
    $annual_medical_allowance = $_POST['annual_medical_allowance'];
    $cola = $_POST['cola'];
    $additional_allowance = $_POST['additional_allowance'];
    $additional_allowance_amount = $_POST['additional_allowance_amount'];
    $monthly_gross = $_POST['monthly_gross'];

    $gross_pay = $basic_salary / 2;
    $contributions = (int)$sss_er + (int)$hdmf_er + (int)$philhealth_er;
    $taxable_income = ($gross_pay + $commission) - $contributions;

    $get_whtax = mysqli_query($db, "SELECT * FROM tbl_wh_tax");
    while ($row = mysqli_fetch_assoc($get_whtax)) {
        /*data retrieval*/
        if ($taxable_income >= $row['minimum'] && $taxable_income <= $row['maximum']) {
            $base_tax = ($taxable_income - $row['minimum']) * $row['base_percentage'];
            $withholding_tax = $row['fix_deduction'] + $base_tax;
            $withholding_tax = round($withholding_tax, 2);
        }
    }

    $get_sss = mysqli_query($db, "SELECT * FROM tbl_sss_contribution");
    while ($row = mysqli_fetch_assoc($get_sss)) {
        /*data retrieval*/
        if ($basic_salary >= $row['minimum'] && $basic_salary <= $row['maximum']) {
            $sss_er = $row['ER'];
            $sss_ee = $row['EE'];
            $sss_ec = $row['EC'];
        }
    }

    if ($basic_salary < 10000) {
        $philhealth_er = 200;
        $philhealth_ee = 200;
    } else {
        $ph_contrib = ($basic_salary * 0.04) / 2;
        $philhealth_er = $ph_contrib;
        $philhealth_ee = $ph_contrib;
    }

    $hdmf_er = 100;
    $hdmf_ee = 100;


    if (!$bank_name || !$company_position || !$company_name || !$account_num || !$basic_salary || !$sss_er || !$hdmf_er || !$philhealth_er || !$deminimis) {
        $res = '<div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="fa fa-times"></i>Please complete the data needed to proceed.</h4>
        </div>';
    } else {
        $created_by = $_SESSION['hris_account_name'];

        mysqli_query($db, "INSERT INTO tbl_employees VALUES('','$employee_num','$emp_name','$company_position','$company_name','$bank_name','$commission','$account_num','$basic_salary','$sss_er', '$sss_ee', '$sss_ec','$hdmf_er', '$hdmf_ee', '$philhealth_er', '$philhealth_ee','$withholding_tax','$deminimis','0','0','$remarks', '$annual_medical_allowance', '$cola', '$additional_allowance', '$additional_allowance_amount', '$monthly_gross')");

        mysqli_query($db, "UPDATE tbl_personal_information SET account_created = '1' WHERE employee_number = '$employee_num'");

        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$created_by','Added Employee Payroll Registry: $company_name','$datetime')");


        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check-circle"></i> ' . $emp_name . ' has been added to ' . $company_name . ' Payroll Registry List</h4>
            </div>';

        header('Location: payroll-employee-list');
    }
}

if (isset($_POST['add_payroll_registry'])) {
    $_SESSION['add_payroll_empnum'] = null;
    $_SESSION['add_payroll_empname'] = null;
    $_SESSION['add_payroll_empjob'] = null;
    $_SESSION['add_payroll_empcomp'] = null;

    if ($_POST['emp_num'] == 'null') {
        $res = '<div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="fa fa-times"></i>No employee selected.</h4>
        </div>';
    } else {
        $emp_num = $_POST['emp_num'];
        $company_id = '';

        $_SESSION['add_payroll_empnum'] = $emp_num;

        $sql = mysqli_query($db, "SELECT * FROM tbl_personal_information WHERE employee_number = '$emp_num'");
        while ($row = mysqli_fetch_assoc($sql)) {
            $_SESSION['add_payroll_empname'] = $row['account_name'];
        }

        $sql1 = mysqli_query($db, "SELECT * FROM tbl_employment_information WHERE employee_number = '$emp_num'");
        while ($row = mysqli_fetch_assoc($sql1)) {
            $_SESSION['add_payroll_empjob'] = $row['position_title'];
            $company_id = $row['company'];
        }


        $sql2 = mysqli_query($db, "SELECT * FROM tbl_companies WHERE ID = '$company_id'");
        while ($row = mysqli_fetch_assoc($sql2)) {
            $_SESSION['add_payroll_empcomp'] = $row['company_name'];
        }

        header('Location: payroll-add-employee');
    }
}

// add earnings
if (isset($_POST['add_earnings'])) {
    reset_add_earnings();
    $selected_emp_num = $_SESSION['selected_empnum'];
    $selected_emp_name = $_SESSION['selected_empname'];
    $selected_date_from = $_SESSION['selecteddate_from'];
    $selected_date_to = $_SESSION['selecteddate_to'];

    $selected_earndescription = strtoupper($_POST['earn_description']);
    $selected_earnaltdescription = strtoupper($_POST['alt_description_earning']);
    $selected_earnamount = $_POST['earn_amount'];
    $selected_net_salary = $_SESSION['selected_netsalary'];
    $previous_net_salary = $selected_net_salary;

    if ($selected_earndescription == null || $selected_earnamount == null) {
        $res = '<div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="fa fa-times"></i>Please complete the data needed to add earnings.</h4>
        </div>';
    } else {
        $created_by = $_SESSION['hris_account_name'];
        // INSERT ON TABLE tbl_payroll_adjustment
        mysqli_query($db, "INSERT INTO tbl_payroll_adjustments VALUES('','$selected_emp_num','$selected_emp_name','Earning','$selected_earndescription','$selected_earnaltdescription','$selected_earnamount','$selected_date_from ','$selected_date_to')");

        $selected_net_salary =  $selected_net_salary + $selected_earnamount;

        // UPDATE ON TABLE tbl_employees_payslip
        $sql = mysqli_query($db, "UPDATE tbl_employees_payslip SET net_salary = '$selected_net_salary' WHERE (emp_num = '$selected_emp_num' AND date_from = '$selected_date_from') AND date_to = '$selected_date_to'");
        $res = '<div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="fa fa-check-circle"></i> Net Salary has been updated.</h4>
        </div>';


        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$created_by','Added Employee Earnings: $selected_emp_name','$datetime')");
    }
    $_SESSION['selected_netsalary'] = $selected_net_salary;

    mysqli_query($db, "INSERT INTO tbl_adjustment_history VALUES('','$selected_emp_num','$selected_date_from','$selected_date_to','$previous_net_salary','$selected_net_salary','$selected_earndescription','Earning','$selected_earnamount')");
}

// minus deductions;
if (isset($_POST['minus_deduction'])) {
    reset_minus_deductions();
    $selected_emp_num = $_SESSION['selected_empnum'];
    $selected_emp_name = $_SESSION['selected_empname'];
    $selected_date_from = $_SESSION['selecteddate_from'];
    $selected_date_to = $_SESSION['selecteddate_to'];

    $selected_deducdescription = strtoupper($_POST['deduc_description']);
    $selected_deducaltdescription = strtoupper($_POST['alt_description_deduct']);
    $selected_deducamount = $_POST['deduc_amount'];
    $selected_net_salary = $_SESSION['selected_netsalary'];
    $previous_net_salary = $selected_net_salary;

    if ($selected_deducdescription == null || $selected_deducamount == null) {
        $res = '<div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="fa fa-times"></i>Please complete the data needed for deductions.</h4>
        </div>';
    } else {
        $created_by = $_SESSION['hris_account_name'];
        // INSERT ON TABLE tbl_payroll_adjustment
        $sql = mysqli_query($db, "INSERT INTO tbl_payroll_adjustments VALUES('','$selected_emp_num','$selected_emp_name','Deduction','$selected_deducdescription','$selected_deducaltdescription','$selected_deducamount','$selected_date_from ','$selected_date_to')");

        $selected_net_salary =  $selected_net_salary - $selected_deducamount;

        // UPDATE ON TABLE tbl_employees_payslip
        $sql = mysqli_query($db, "UPDATE tbl_employees_payslip SET net_salary = '$selected_net_salary' WHERE (emp_num = '$selected_emp_num' AND date_from = '$selected_date_from') AND date_to = '$selected_date_to'");
        $res = '<div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="fa fa-check-circle"></i> Net Salary has been updated.</h4>
        </div>';

        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$created_by','Added Employee Deduction: $selected_emp_name','$datetime')");
    }
    $_SESSION['selected_netsalary'] = $selected_net_salary;

    mysqli_query($db, "INSERT INTO tbl_adjustment_history VALUES('','$selected_emp_num','$selected_date_from','$selected_date_to','$previous_net_salary','$selected_net_salary','$selected_deducdescription','Deduction','$selected_deducamount')");


    // $_SESSION['$selected_netsalary'] = $selected_net_salary;


}
if (isset($_POST['update_testcase'])) {
    $emp_num = $_POST['emp_num'];
    $emp_name = $_POST['emp_name'];
    $select_company = $_POST['select_company'];
    $company_position = $_POST['job_title'];
    $basic_salary = $_POST['basic_salary'];
    $acc_num = $_POST['acc_num'];
    $sss = $_POST['sss'];
    $philhealth = $_POST['philhealth'];
    $pagibig = $_POST['pagibig'];
    $created_by = $_SESSION['hris_account_name'];
    $deminimis = $_POST['deminimis'];
    $bank_name = $_POST['bank_name'];
    $commission = $_POST['commission'];
    $remarks = $_POST['remarks'];
    $withholding_tax = '';

    $contributions = $sss + $philhealth + $pagibig;
    $taxable_income = $basic_salary - $contributions;

    $get_whtax = mysqli_query($db, "SELECT * FROM tbl_wh_tax");
    while ($row = mysqli_fetch_assoc($get_whtax)) {
        /*data retrieval*/
        if ($taxable_income >= $row['minimum'] && $taxable_income <= $row['maximum']) {

            $withholding_tax = $row['fix_deduction'] + (($taxable_income - $row['minimum']) * $row['base_percentage']);
            $withholding_tax = round($withholding_tax, 2);
        }
    }

    $basic_pay = $basic_salary + $deminimis;
    $net_salary = $basic_pay - $sss - $pagibig - $philhealth - $withholding_tax;

    if (empty($emp_num) || empty($emp_name)) {
        $res = '<div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="fa fa-times"></i>Please complete the data needed to proceed.</h4>
        </div>';
    } else {
        if ($_POST['whtax'] != $withholding_tax && $_POST['whtax'] != 0) {
            $withholding_tax = $_POST['whtax'];
        }
        mysqli_query($db, "UPDATE tbl_employees SET emp_name = '$emp_name', company_position = '$company_position', company_name = '$select_company', basic_salary = '$basic_salary', account_number = '$acc_num', sss = '$sss', philhealth = '$philhealth', pagibig = '$pagibig', deminimis = '$deminimis', commission = '$commission', bank_name = '$bank_name', withholding_tax = '$withholding_tax', remarks = '$remarks' WHERE emp_num = '$emp_num'");



        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check-circle"></i> ' . $emp_num . ' has been updated successfully.</h4>
            </div>';
        reset_all_edit_session();
        $sql = mysqli_query($db, "SELECT * FROM tbl_employees WHERE emp_num = '$emp_num'");
        while ($row = mysqli_fetch_assoc($sql)) {
            $_SESSION['selected_edit_empname'] = $row['emp_name'];
            $_SESSION['selected_edit_company'] = $row['company_name'];
            $_SESSION['selected_edit_jobtitle'] = $row['company_position'];
            $_SESSION['selected_edit_basicsalary'] = $row['basic_salary'];
            $_SESSION['selected_edit_accno'] = $row['account_number'];
            $_SESSION['selected_edit_sss'] = $row['sss'];
            $_SESSION['selected_edit_pagibig'] = $row['pagibig'];
            $_SESSION['selected_edit_philhealth'] = $row['philhealth'];
            $_SESSION['selected_edit_deminimis'] = $row['deminimis'];
            $_SESSION['selected_edit_bankname'] = $row['bank_name'];
            $_SESSION['selected_edit_commission'] = $row['commission'];
            $_SESSION['selected_edit_whtax'] = $row['withholding_tax'];
            $_SESSION['selected_edit_remarks'] = $row['remarks'];
            $_SESSION['selected_taxable_test'] = $taxable_income;;
            $_SESSION['selected_net_test'] = $net_salary;
        }
        $_SESSION['selected_editemp'] = $emp_num;
    }
}
if (isset($_POST['test_case'])) {
    $emp_num = $_POST['emp_num'];
    reset_all_edit_session();
    $_SESSION['selected_editemp'] = $emp_num;


    $sql = mysqli_query($db, "SELECT * FROM tbl_employees WHERE emp_num = '$emp_num'");
    while ($row = mysqli_fetch_assoc($sql)) {
        $_SESSION['selected_edit_empname'] = $row['emp_name'];
        $_SESSION['selected_edit_company'] = $row['company_name'];
        $_SESSION['selected_edit_jobtitle'] = $row['company_position'];
        $_SESSION['selected_edit_basicsalary'] = $row['basic_salary'];
        $_SESSION['selected_edit_accno'] = $row['account_number'];
        $_SESSION['selected_edit_sss'] = $row['sss'];
        $_SESSION['selected_edit_pagibig'] = $row['pagibig'];
        $_SESSION['selected_edit_philhealth'] = $row['philhealth'];
        $_SESSION['selected_edit_gsis'] = $row['gsis'];
        $_SESSION['selected_edit_deminimis'] = $row['deminimis'];
        $_SESSION['selected_edit_bankname'] = $row['bank_name'];
        $_SESSION['selected_edit_commission'] = $row['commission'];
        $_SESSION['selected_edit_whtax'] = $row['withholding_tax'];
        $_SESSION['selected_edit_remarks'] = $row['remarks'];
        $_SESSION['selected_taxable_test'] = '0';
        $_SESSION['selected_net_test'] = '0';
    }

    header('Location: test-case2');
}

if (isset($_POST['edit_employee'])) {
    $emp_num = $_POST['emp_num'];
    reset_all_edit_session();
    $_SESSION['selected_editemp'] = $emp_num;

    $sql = mysqli_query($db, "SELECT * FROM tbl_employees WHERE emp_num = '$emp_num'");
    while ($row = mysqli_fetch_assoc($sql)) {
        $_SESSION['selected_edit_empname'] = $row['emp_name'];
        $_SESSION['selected_edit_company'] = $row['company_name'];
        $_SESSION['selected_edit_jobtitle'] = $row['company_position'];
        $_SESSION['selected_edit_basicsalary'] = $row['basic_salary'];
        $_SESSION['selected_edit_accno'] = $row['account_number'];
        $_SESSION['selected_edit_sss_er'] = $row['sss_er'];
        $_SESSION['selected_edit_sss_ee'] = $row['sss_ee'];
        $_SESSION['selected_edit_sss_ec'] = $row['sss_ec'];
        $_SESSION['selected_edit_hdmf_er'] = $row['hdmf_er'];
        $_SESSION['selected_edit_hdmf_ee'] = $row['hdmf_ee'];
        $_SESSION['selected_edit_philhealth_er'] = $row['philhealth_er'];
        $_SESSION['selected_edit_philhealth_ee'] = $row['philhealth_ee'];
        $_SESSION['selected_edit_gsis'] = $row['gsis'];
        $_SESSION['selected_edit_deminimis'] = $row['deminimis'];
        $_SESSION['selected_edit_bankname'] = $row['bank_name'];
        $_SESSION['selected_edit_commission'] = $row['commission'];
        $_SESSION['selected_edit_whtax'] = $row['withholding_tax'];
        $_SESSION['selected_edit_remarks'] = $row['remarks'];
        $_SESSION['selected_annual_medical_allowance'] = $row['annual_medical_allowance'];
        $_SESSION['selected_edit_cola'] = $row['cola'];
        $_SESSION['selected_edit_additional_allowance'] = $row['additional_allowance'];
        $_SESSION['selected_edit_additional_allowance_amount'] = $row['additional_allowance_amount'];
        $_SESSION['selected_edit_monthly_gross'] = $row['monthly_gross'];
    }

    header('Location: payroll-edit-employee');
}

if (isset($_POST['update_employee'])) {
    $emp_num = $_POST['emp_num'];
    $emp_name = $_POST['emp_name'];
    $select_company = $_POST['select_company'];
    $company_position = $_POST['job_title'];
    $basic_salary = $_POST['basic_salary'];
    $acc_num = $_POST['acc_num'];
    $sss_er = $_POST['sss_er'];
    $sss_ee = $_POST['sss_ee'];
    $sss_ec = $_POST['sss_ec'];
    $hdmf_er = $_POST['hdmf_er'];
    $hdmf_ee = $_POST['hdmf_ee'];
    $philhealth_er = $_POST['philhealth_er'];
    $philhealth_ee = $_POST['philhealth_ee'];
    $created_by = $_SESSION['hris_account_name'];
    $deminimis = $_POST['deminimis'];
    $bank_name = $_POST['bank_name'];
    $commission = $_POST['commission'];
    $remarks = $_POST['remarks'];
    $withholding_tax = $_POST['whtax'];
    $annual_medical_allowance = $_POST['annual_medical_allowance'];
    $cola = $_POST['cola'];
    $additional_allowance = $_POST['additional_allowance'];
    $additional_allowance_amount = $_POST['additional_allowance_amount'];
    $monthly_gross = $_POST['monthly_gross'];

    $gross_pay = $basic_salary / 2;
    $contributions = $sss_ee + $philhealth_ee + $hdmf_ee;
    $taxable_income = ($gross_pay + $commission) - $contributions;

    $get_whtax = mysqli_query($db, "SELECT * FROM tbl_wh_tax");
    while ($row = mysqli_fetch_assoc($get_whtax)) {
        /*data retrieval*/
        if ($taxable_income >= $row['minimum'] && $taxable_income <= $row['maximum']) {
            $base_tax = ($taxable_income - $row['minimum']) * $row['base_percentage'];
            $withholding_tax = $row['fix_deduction'] + $base_tax;
            $withholding_tax = round($withholding_tax, 2);
        }
    }

    $get_sss = mysqli_query($db, "SELECT * FROM tbl_sss_contribution");
    while ($row = mysqli_fetch_assoc($get_sss)) {
        /*data retrieval*/
        if ($basic_salary >= $row['minimum'] && $basic_salary <= $row['maximum']) {
            $sss_er = $row['ER'];
            $sss_ee = $row['EE'];
            $sss_ec = $row['EC'];
        }
    }

    if ($basic_salary < 10000) {
        $philhealth_er = 200;
        $philhealth_ee = 200;
    } else {
        $ph_contrib = ($basic_salary * 0.04) / 2;
        $philhealth_er = $ph_contrib;
        $philhealth_ee = $ph_contrib;
    }

    $hdmf_er = 100;
    $hdmf_ee = 100;

    if (empty($emp_num) || empty($emp_name)) {
        $res = '<div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="fa fa-times"></i>Please complete the data needed to proceed.</h4>
        </div>';
    } else {
        if ($_POST['whtax'] != $withholding_tax && $_POST['whtax'] != 0) {
            $withholding_tax = $_POST['whtax'];
        }
        mysqli_query($db, "UPDATE tbl_employees SET emp_name = '$emp_name', company_position = '$company_position', company_name = '$select_company', basic_salary = '$basic_salary', account_number = '$acc_num', sss_er = '$sss_er', sss_ee = '$sss_ee', sss_ec = '$sss_ec', hdmf_er = '$hdmf_er', hdmf_ee = '$hdmf_ee', philhealth_er = '$philhealth_er', philhealth_ee = '$philhealth_ee',deminimis = '$deminimis', commission = '$commission', bank_name = '$bank_name', withholding_tax = '$withholding_tax', remarks = '$remarks', annual_medical_allowance = '$annual_medical_allowance', cola = '$cola', additional_allowance = '$additional_allowance', additional_allowance_amount = '$additional_allowance_amount', monthly_gross = '$monthly_gross' WHERE emp_num = '$emp_num'");

        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$created_by','Updated Employee Payroll Registry: $emp_name','$datetime')");


        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check-circle"></i> ' . $emp_num . ' has been updated successfully.</h4>
            </div>';
        reset_all_edit_session();
        $sql = mysqli_query($db, "SELECT * FROM tbl_employees WHERE emp_num = '$emp_num'");
        while ($row = mysqli_fetch_assoc($sql)) {
            $_SESSION['selected_edit_empname'] = $row['emp_name'];
            $_SESSION['selected_edit_company'] = $row['company_name'];
            $_SESSION['selected_edit_jobtitle'] = $row['company_position'];
            $_SESSION['selected_edit_basicsalary'] = $row['basic_salary'];
            $_SESSION['selected_edit_accno'] = $row['account_number'];
            $_SESSION['selected_edit_sss_er'] = $row['sss_er'];
            $_SESSION['selected_edit_sss_ee'] = $row['sss_ee'];
            $_SESSION['selected_edit_sss_ec'] = $row['sss_ec'];
            $_SESSION['selected_edit_hdmf_er'] = $row['hdmf_er'];
            $_SESSION['selected_edit_hdmf_ee'] = $row['hdmf_ee'];
            $_SESSION['selected_edit_philhealth_er'] = $row['philhealth_er'];
            $_SESSION['selected_edit_philhealth_ee'] = $row['philhealth_ee'];
            $_SESSION['selected_edit_deminimis'] = $row['deminimis'];
            $_SESSION['selected_edit_bankname'] = $row['bank_name'];
            $_SESSION['selected_edit_commission'] = $row['commission'];
            $_SESSION['selected_edit_whtax'] = $row['withholding_tax'];
            $_SESSION['selected_edit_remarks'] = $row['remarks'];
            $_SESSION['selected_edit_cola'] = $row['cola'];
            $_SESSION['selected_edit_additional_allowance'] = $row['additional_allowance'];
            $_SESSION['selected_edit_additional_allowance_amount'] = $row['additional_allowance_amount'];
            $_SESSION['selected_edit_monthly_gross'] = $row['monthly_gross'];
        }
        $_SESSION['selected_editemp'] = $emp_num;
    }
}

if (isset($_POST['create_account'])) {
    $_SESSION['create_emp_num'] = null;
    $_SESSION['create_emp_email'] = null;
    $_SESSION['create_emp_name'] = null;
    $_SESSION['create_emp_comp'] = null;

    if ($_POST['emp_num'] == "null") {
        $res = '<div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="fa fa-times"></i>There is no selected employee.</h4>
        </div>';
    } else {
        $emp_num = $_POST['emp_num'];
        $_SESSION['create_emp_num'] = $emp_num;

        $sql = mysqli_query($db, "SELECT * FROM tbl_personal_information WHERE employee_number = '$emp_num'");
        while ($row = mysqli_fetch_assoc($sql)) {
            $_SESSION['create_emp_email'] = $row['company_email'];
            $_SESSION['create_emp_name'] = $row['account_name'];
        }

        $sql1 = mysqli_query($db, "SELECT * FROM tbl_employees WHERE emp_num = '$emp_num'");
        while ($row = mysqli_fetch_assoc($sql1)) {
            $_SESSION['create_emp_comp'] = $row['company_name'];
        }

        header('Location: create-account');
    }
}
if (isset($_POST['btn_offboarding'])) {
    if ($_POST['emp_num'] == "null" || $_POST['date'] == null || $_POST['reason'] == null) {
        $res = '<div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="fa fa-times"></i> Complete the required fields.</h4>
        </div>';
    } else {
        $emp_num = $_POST['emp_num'];
        $date = $_POST['date'];
        $reason = $_POST['reason'];
        $a_id = $_SESSION['hris_id'];

        $get_id = mysqli_query($db, "SELECT * FROM tbl_users WHERE id = '$a_id'");
        while ($row = mysqli_fetch_assoc($get_id)) {
            $admin_id = $row['employee_number'];
        }

        // from tbl_personal_information
        $get_emp_name = mysqli_query($db, "SELECT * FROM tbl_personal_information WHERE employee_number = '$emp_num'");
        while ($row = mysqli_fetch_assoc($get_emp_name)) {
            $employee_name = $row['last_name'] . ', ' . $row['first_name'];
            $company_email = $row['company_email'];
            $address = $row['address'];
            $contact_number = $row['contact_number'];
            $account_name = $row['account_name'];
            $date_of_birth = $row['date_of_birth'];
            $age = $row['age'];
            $gender = $row['gender'];
            $citizenship = $row['citizenship'];
            $civil_status = $row['civil_status'];
            $spouse_name = $row['spouse_name'];
            $pi_date_created = $row['date_created'];
            $super_admin = $row['super_admin'];
        }

        $get_employment_info = mysqli_query($db, "SELECT * FROM tbl_employment_information WHERE employee_number = '$emp_num'");
        while ($row = mysqli_fetch_assoc($get_employment_info)) {
            $position_number = $row['position_number'];
            $position_title = $row['position_title'];
            $job_description = $row['job_description'];
            $date_hired = $row['date_hired'];
            $company = $row['company'];
            $department = $row['department'];
            $employment_status = $row['employment_status'];
            $approver = $row['approver'];
            $reporting_to = $row['reporting_to'];
            $vendor_id = $row['vendor_id'];
            $filing = $row['filing'];
            $is_approver = $row['is_approver'];
        }

        $get_employees_payroll = mysqli_query($db, "SELECT * FROM tbl_employees WHERE emp_num = '$emp_num'");
        while ($row = mysqli_fetch_assoc($get_employees_payroll)) {
            $bank_name = $row['bank_name'];
            $commission = $row['commission'];
            $account_number = $row['account_number'];
            $basic_salary = $row['basic_salary'];
            $sss_er = $row['sss_er'];
            $sss_ee = $row['sss_ee'];
            $sss_ec = $row['sss_ec'];
            $hdmf_er = $row['hdmf_er'];
            $hdmf_ee = $row['hdmf_ee'];
            $philhealth_er = $row['philhealth_er'];
            $philhealth_ee = $row['philhealth_ee'];
            $withholding_tax = $row['withholding_tax'];
            $deminimis = $row['deminimis'];
            $remarks = $row['remarks'];
        }

        $get_leave_balance = mysqli_query($db, "SELECT * FROM tbl_leave_balances WHERE employee_number = '$emp_num'");
        while ($row = mysqli_fetch_assoc($get_leave_balance)) {
            $VL = $row['VL'];
            $SL = $row['SL'];
            $EL = $row['EL'];
        }

        $get_users_account = mysqli_query($db, "SELECT * FROM tbl_users WHERE employee_number = '$emp_num'");
        while ($row = mysqli_fetch_assoc($get_users_account)) {
            $role = $row['role'];
            $account_email = $row['email'];
            $password = $row['password'];
            $account_created = $row['date_created'];
            $password = $row['password'];
        }

        $at_name = $_SESSION['hris_account_name'];
        $account_status = 'Inactive';

        $sql = mysqli_query($db, "INSERT INTO tbl_offboarding VALUES('','$emp_num','$employee_name','$date','$reason','$admin_id','$datetime')");

        $res = '<div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="fa fa-times"></i> Successfully offboarded <strong>' . $employee_name . '</strong> to the system.</h4>
        </div>';

        mysqli_query($db, "INSERT INTO tbl_employees_backup VALUES(
            '',
            '$emp_num',
            '$account_name',
            '$company_email',
            '$address',
            '$contact_number',
            '$date_of_birth',
            '$age',
            '$gender',
            '$citizenship',
            '$civil_status',
            '$spouse_name',
            '$pi_date_created',
            '$super_admin',
            '$position_number',
            '$position_title',
            '$job_description',
            '$date_hired',
            '$company',
            '$department',
            '$employment_status',
            '$account_status',
            '$approver',
            '$reporting_to',
            '$vendor_id',
            '$filing',
            '$is_approver',
            '$bank_name',
            '$commission',
            '$account_number',
            '$basic_salary',
            '$sss_er',
            '$sss_ee',
            '$sss_ec',
            '$hdmf_er',
            '$hdmf_ee',
            '$philhealth_er',
            '$philhealth_ee',
            '$withholding_tax',
            '$deminimis',
            '$remarks',
            '$VL',
            '$SL',
            '$EL',
            '$role',
            '$account_email',
            '$password',
            '$account_created',
            '$admin_id',
            '$datetime')");

        mysqli_query($db, "DELETE FROM tbl_personal_information WHERE employee_number = '$emp_num'");
        mysqli_query($db, "DELETE FROM tbl_employment_information WHERE employee_number = '$emp_num'");
        mysqli_query($db, "DELETE FROM tbl_employees WHERE emp_num = '$emp_num'");
        mysqli_query($db, "DELETE FROM tbl_users WHERE employee_number = '$emp_num'");
        mysqli_query($db, "DELETE FROM tbl_leave_balances WHERE employee_number = '$emp_num'");

        mysqli_query($db, "DELETE FROM tbl_post_graduate WHERE employee_number = '$emp_num'");
        mysqli_query($db, "DELETE FROM tbl_college WHERE employee_number = '$emp_num'");
        mysqli_query($db, "DELETE FROM tbl_emergency_contacts WHERE employee_number = '$emp_num'");
        mysqli_query($db, "DELETE FROM tbl_government_id WHERE employee_number = '$emp_num'");
        mysqli_query($db, "DELETE FROM tbl_ids WHERE employee_number = '$emp_num'");
        mysqli_query($db, "DELETE FROM tbl_documents WHERE employee_number = '$emp_num'");
        mysqli_query($db, "DELETE FROM tbl_benefits_eligibility WHERE employee_number = '$emp_num'");
        mysqli_query($db, "DELETE FROM tbl_benefits_gas_balance WHERE employee_number = '$emp_num'");
        mysqli_query($db, "DELETE FROM tbl_benefits_car_balance WHERE employee_number = '$emp_num'");
        mysqli_query($db, "DELETE FROM tbl_benefits_medical_balance WHERE employee_number = '$emp_num'");
        mysqli_query($db, "DELETE FROM tbl_benefits_gym_balance WHERE employee_number = '$emp_num'");
        mysqli_query($db, "DELETE FROM tbl_benefits_optical_balance WHERE employee_number = '$emp_num'");
        mysqli_query($db, "DELETE FROM tbl_benefits_cep_balance WHERE employee_number = '$emp_num'");


        if ($sql) {
            $at_name = $_SESSION['hris_account_name'];
            mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Deleted Employee: $employee_name','$datetime')");
        }
    }
}
if (isset($_POST['view_attachment'])) {
    $_SESSION['attachment_name'] = null;
    $_SESSION['attachment_name'] = $_POST['attachment_name'];

    header('Location: file');
}

if (isset($_POST['view_attachment_doc'])) {

    // $file = 'uploads/' . $_POST['doc_attachment'];
    // $filename = $_POST['doc_attachment'];
    // header('Content-type: application/pdf');
    // header('Content-Disposition: inline; filename="' . $filename . '"');
    // header('Content-Transfer: Encoding: binary');
    // header('Accept-Ranges: bytes');
    $filename = 'https://lifelink-storage.s3.ap-southeast-1.amazonaws.com/ONBOARDING/' . $_POST['doc_attachment'];

    // Header content type
    header("Content-type: application/pdf");
    header('Content-Disposition: inline; filename="' . $filename . '"');

    header("Content-Length: " . filesize($filename));

    // Send the file to the browser.
    @readfile($filename);
    // @readfile($file);
}

if (isset($_POST['view_attachment_cert'])) {
    // $_SESSION['la_attachment'] = null;
    // $_SESSION['la_attachment'] = $_POST['la_attachment'];

    // header('Location: file');

    $file = 'uploads/' . $_POST['la_attachment'];
    $filename = $_POST['la_attachment'];
    header('Content-type: application/pdf');
    header('Content-Disposition: inline; filename="' . $filename . '"');
    header('Content-Transfer: Encoding: binary');
    header('Accept-Ranges: bytes');
    @readfile($file);
}
if (isset($_POST['add_department'])) {
    $department = $_POST['department'];
    $company_id = $_POST['company_id'];
    $dept_group = $_POST['dept_group'];
    $dept_manual_id = $_POST['dept_manual_id'];
    if (check_if_department_exist($department, $company_id) == true) {
        $res = '<div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-times"></i> Submission failed. Department already exist.</h4>
        </div>';
    } else {
        $sql = mysqli_query($db, "INSERT INTO tbl_departments VALUES('','$dept_manual_id','$department','$company_id','$datetime','$dept_group')");
        $res = '<div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="fa fa-check-circle"></i> ' . $department . ' has been added as Department</h4>
        </div>';
    }
    $at_name = $_SESSION['hris_account_name'];
    mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Added a department: $department','$datetime')");
}
if (isset($_POST['add_department_group'])) {
    $group_name = $_POST['group_name'];
    $company_id = $_POST['company_id'];
    if (check_if_group_exist($group_name, $company_id) == true) {
        $res = '<div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-times"></i> Submission failed. Department already exist.</h4>
        </div>';
    } else {
        $at_name = $_SESSION['hris_account_name'];
        $sql = mysqli_query($db, "INSERT INTO tbl_department_group VALUES('','$company_id','$group_name','$at_name','$datetime')");
        $res = '<div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="fa fa-check-circle"></i> ' . $group_name . ' has been added as Group</h4>
        </div>';
    }
    $at_name = $_SESSION['hris_account_name'];
    mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Added a group: $group_name','$datetime')");
}
if (isset($_POST['add_department_unit'])) {
    $dept_group = $_POST['dept_group'];
    $dept_unit = $_POST['dept_unit'];
    $company_id = $_POST['company_id'];
    $at_name = $_SESSION['hris_account_name'];

    $sql = mysqli_query($db, "INSERT INTO tbl_department_unit VALUES('','$company_id','$dept_group','$dept_unit','$at_name','$datetime')");
    $res = '<div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="fa fa-check-circle"></i> ' . $dept_unit . ' has been added as Unit</h4>
        </div>';

    mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Added a unit: $dept_unit','$datetime')");
}
if (isset($_POST['get_department_details'])) {
    $department_id = $_POST['department_id'];
    $company_id = $_POST['company_id'];
    $department_name = $_POST['department_name'];
    $manual_id = '';
    $group = '';

    $sql = mysqli_query($db, "SELECT * FROM tbl_departments WHERE ID = '$department_id'");
    while ($row = mysqli_fetch_assoc($sql)) {
        $manual_id = $row['manual_id'];
        $group = $row['group_id'];
    }

    echo '<div class="modal-header text-center">
    <h2 class="modal-title"><i class="fa fa-sitemap"></i> Update Department</h2>
    </div>
    <div class="modal-body">
        <div class="container-fluid">
            <form method="POST" enctype="multipart/form-data" class="form-horizontal form-bordered">
                <input type="hidden" name="department_id" value="' . $department_id . '">
                <input type="hidden" name="company_id" value="' . $company_id . '">
                <div class="form-group">
                    <label>Department Name</label>
                    <input type="text" name="department" required class="form-control" placeholder="Enter Department Name..." value="' . $department_name . '">
                    <br>
                    <label>Manual ID</label>
                    <input type="text" name="manual_id" required class="form-control" value="' . $manual_id . '">
                    <br>
                    <label>Group</label>
                    <input type="text" name="group" required class="form-control" value="' . $group . '">
                    <br>
                    <button name="update_department" class="btn btn-primary btn-block">Update</button>
                </div>
            </form>
        </div>
    </div>';
}
if (isset($_POST['view_summary'])) {
    $date_from = null;
    $date_to = null;
    $company_name = null;
    $selected_cutid = $_POST['selected_cutoff'];

    $sql = mysqli_query($db, "SELECT * FROM tbl_cutoffs WHERE reference_num = '$selected_cutid'");
    while ($row = mysqli_fetch_assoc($sql)) {
        $company_name = $row['company_name'];
        $date_from = $row['date_from'];
        $date_to = $row['date_to'];
    }

    $_SESSION['summary_company'] = $company_name;
    $_SESSION['summary_datefrom'] = $date_from;
    $_SESSION['summary_dateto'] = $date_to;
    header('Location: payroll-summary-view');
}
if (isset($_POST['update_department'])) {
    $department_id = $_POST['department_id'];
    $company_id = $_POST['company_id'];
    $department = $_POST['department'];
    $manual_id = $_POST['manual_id'];
    $group = $_POST['group'];

    // if (check_if_department_exist($department, $company_id) == true) {
    //     $res = '<div class="alert alert-danger alert-dismissable">
    //         <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    //         <h4><i class="fa fa-times"></i> Update failed. Department already exist.</h4>
    //     </div>
    //   ';
    // } else {
    $sql = mysqli_query($db, "UPDATE tbl_departments SET department = '$department', manual_id = '$manual_id', group_id = '$group' WHERE ID = '$department_id'");
    $res = '<div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="fa fa-check-circle"></i> ' . $department . ' has been updated.</h4>
        </div>';

    $at_name = $_SESSION['hris_account_name'];
    mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','updated a department: $department_id','$datetime')");
    // }
}
if (isset($_POST['update_departments'])) {
    $department = $_POST['department'];
    $m_dept_manual_id = $_POST['m_dept_manual_id'];
    $m_dept_group = $_POST['m_dept_group'];
    // $manual_id = $_POST['manual_id'];
    // $group = $_POST['group'];

    $sql = mysqli_query($db, "UPDATE tbl_departments SET manual_id = '$m_dept_manual_id', group_id = '$m_dept_group' WHERE ID = '$department'");
    $res = '<div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="fa fa-check-circle"></i> Department has been updated.</h4>
        </div>';

    $at_name = $_SESSION['hris_account_name'];
    mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','updated a department: $department','$datetime')");
    // }
}

if (isset($_POST['delete_departments'])) {
    if (empty($_POST['selected_departments_delete'])) {
        $all_deptid = null;
    } else {
        $all_deptid = $_POST['selected_departments_delete'];
    }

    if ($all_deptid == null) {
        $res = '<div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="fa fa-times"></i> Select at least one to delete.</h4>
    </div>';
    } else {
        foreach ($all_deptid as $dept_id) {
            $db = connect();
            mysqli_query($db, "DELETE FROM tbl_departments WHERE ID = '$dept_id'");
        }

        $res = '<div class="alert alert-success alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h4><i class="fa fa-check-circle"></i> Department(s) has been deleted successfully.</h4>
    </div>';
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
if (isset($_POST['approve_ot_request'])) {

    if ($_POST['emp_num'] == null || $_POST['date_filed'] == null || $_POST['total_duration'] == null) {
        $res = '<div class="alert alert-danger alert-dismissable">
             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
             <h4><i class="fa fa-times"></i> Complete the fields.</h4>
             </div>';
    } else {

        $emp_num = $_POST['emp_num'];
        $str = $_POST['total_duration'];
        $id = $_POST['id'];
        $day = 0;
        $hr = 0;
        $min = 0;
        $matches = 0;
        $basic_salary = 0;
        $matches_count = preg_match_all('!\d+!', $str, $matches);

        if ($matches_count == 3) {
            $day = preg_replace('/[^0-9]/', '', $str)[0];
            $hr = preg_replace('/[^0-9]/', '', $str)[1];
            $min = preg_replace('/[^0-9]/', '', $str)[2];
        } else if ($matches_count == 2) {
            $hr = preg_replace('/[^0-9]/', '', $str)[0];
            $min = preg_replace('/[^0-9]/', '', $str)[1];
        } else {
            $min = preg_replace('/[^0-9]/', '', $str)[0];
        }

        $sql = mysqli_query($db, "SELECT * FROM tbl_employees WHERE emp_num = '$emp_num'");
        while ($row = mysqli_fetch_assoc($sql)) {
            $basic_salary = $row['basic_salary'];
        }

        $daily_rate = $basic_salary / 20;
        $hourly_rate = $daily_rate / 8;
        $minute_rate = $hourly_rate / 60;

        $amount = ($day * $daily_rate) + ($hr * $hourly_rate) + ($min * $minute_rate);
        $amount = round($amount, 2);

        mysqli_query($db, "UPDATE tbl_ot_request SET amount = '$amount', status = 'Approved' WHERE id = '$id'");

        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Approve OT Request','$datetime')");

        $res = '<div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="fa fa-check-circle"></i> OT Application has been approved.</h4>
        </div>';
    }
}
if (isset($_POST['deny_ot_request'])) {
    if ($_POST['date_filed'] == null || $_POST['remarks'] == null) {
        $res = '<div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-times"></i> Please put on remarks or complete the fields.</h4>
            </div>';
    } else {
        $remarks = $_POST['remarks'];
        $id = $_POST['id'];
        $emp_num = $_POST['emp_num'];
        mysqli_query($db, "UPDATE tbl_ot_request SET status = 'Denied', remarks = '$remarks' WHERE id = '$id'");

        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Denied OT Request','$datetime')");

        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check-circle"></i> Request has been denied.</h4>
            </div>';
    }
}
if (isset($_POST['btn_request_ot'])) {


    if ($_POST['date_from'] == null || $_POST['date_to'] == null) {
        $res = '<div class="alert alert-danger alert-dismissable">
             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
             <h4><i class="fa fa-times"></i> Complete the fields.</h4>
             </div>';
    } else {
        // FILE UPLOAD

        $attachment = $_FILES['attachment']['name'];
        $fileSize = $_FILES['attachment']['size'];
        $fileType = $_FILES['attachment']['type'];
        $initialExt = explode('.', $attachment);
        $fileExt = strtolower(end($initialExt));

        $allowed = array('pdf');

        if (in_array($fileExt, $allowed)) {
            $fileNewName = uniqid('', true) . '.' . $fileExt;
            $target = 'uploads/' . $fileNewName;
            move_uploaded_file($_FILES['attachment']['tmp_name'], $target);

            function format_interval(DateInterval $interval)
            {
                $result = "";
                if ($interval->y) {
                    $result .= $interval->format("%y years ");
                }
                if ($interval->m) {
                    $result .= $interval->format("%m months ");
                }
                if ($interval->d) {
                    $result .= $interval->format("%d days ");
                }
                if ($interval->h) {
                    $result .= $interval->format("%h 1 ");
                }
                if ($interval->i) {
                    $result .= $interval->format("%i minutes ");
                }
                if ($interval->s) {
                    $result .= $interval->format("%s seconds ");
                }

                return $result;
            }
            $company = $_SESSION['hris_company_id'];
            $emp_num = $_SESSION['hris_employee_number'];
            $date_from = $_POST['date_from'];
            $date_to = $_POST['date_to'];
            $approver = $_POST['approver'];

            $first_date = new DateTime("$date_from");
            $second_date = new DateTime("$date_to");

            $difference = $first_date->diff($second_date);
            $total_duration = format_interval($difference);

            mysqli_query($db, "INSERT INTO tbl_ot_request VALUES('','$emp_num','$company','$date_from','$date_to',Now(),'Pending','$total_duration','0','$fileNewName','','$approver')");

            $at_name = $_SESSION['hris_account_name'];
            mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Filed OT Request','$datetime')");

            $res = '<div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="fa fa-check-circle"></i> Overtime application submitted.</h4>
        </div>';
        } else {
            $res = '<div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check-circle"></i> You cannot upload files of this type.</h4>
            </div>';
        }


        // FILE UPLOAD

    }
}
if (isset($_POST['btn_request_offset'])) {
    if ($_POST['date_from'] == null || $_POST['date_to'] == null || $_POST['reason'] == null) {
        $res = '<div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-times"></i> Please complete the fields.</h4>
        </div>';
    } else {
        $date_from = $_POST['date_from'];
        $date_to = $_POST['date_to'];
        $reason = $_POST['reason'];
        $approver = $_POST['approver'];
        $company = $_SESSION['hris_company_id'];
        $emp_num = $_SESSION['hris_employee_number'];


        mysqli_query($db, "INSERT INTO tbl_offset_request VALUES('','$emp_num','$company','$date_from','$date_to','Pending',Now(),'$reason','','$approver')");

        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Filed Offset Request','$datetime')");

        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check-circle"></i> Request successfully submitted.</h4>
            </div>';
    }
}
if (isset($_POST['approve_offset'])) {
    if ($_POST['emp_num'] == null || $_POST['date_to'] == null || $_POST['reason'] == null) {
        $res = '<div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-times"></i> Please complete the fields.</h4>
        </div>';
    } else {
        $date_from = $_POST['date_from'];
        $date_to = $_POST['date_to'];
        $reason = $_POST['reason'];
        $remarks = $_POST['remarks'];
        $id = $_POST['id'];
        $company = $_SESSION['hris_company_id'];
        $emp_num = $_SESSION['hris_employee_number'];


        mysqli_query($db, "UPDATE tbl_offset_request SET status = 'Approved', remarks = '$remarks' WHERE id = '$id'");

        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Approved Offset Request','$datetime')");

        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check-circle"></i> Offset Request has been approved.</h4>
            </div>';
    }
}
if (isset($_POST['deny_offset'])) {
    if ($_POST['emp_num'] == null || $_POST['date_to'] == null || $_POST['remarks'] == null) {
        $res = '<div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-times"></i> Please input remarks or complete the fields.</h4>
        </div>';
    } else {
        $date_from = $_POST['date_from'];
        $date_to = $_POST['date_to'];
        $reason = $_POST['reason'];
        $remarks = $_POST['remarks'];
        $id = $_POST['id'];
        $company = $_SESSION['hris_company_id'];
        $emp_num = $_SESSION['hris_employee_number'];


        mysqli_query($db, "UPDATE tbl_offset_request SET status = 'Denied', remarks = '$remarks' WHERE id = '$id'");

        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Denied Offset Request','$datetime')");

        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check-circle"></i> Offset Request has been denied.</h4>
            </div>';
    }
}
if (isset($_POST['btn_timein_request'])) {
    if ($_POST['time_in'] == null || $_POST['date'] == null || $_POST['reason'] == null) {
        $res = '<div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-times"></i> Please complete the fields.</h4>
        </div>';
    } else {
        $initial_timein = $_POST['time_in'];
        $date = $_POST['date'];
        $reason = $_POST['reason'];
        $approver = $_POST['approver'];
        $company = $_SESSION['hris_company_id'];
        $emp_num = $_SESSION['hris_employee_number'];
        $timein_now = date("h:i:s a", strtotime($initial_timein));
        $timein24_now = date("H:i:s", strtotime($initial_timein));


        mysqli_query($db, "INSERT INTO tbl_attendance_adjust_request VALUES('','$emp_num','$date','$timein_now','$timein24_now','$reason','Time In','$company','Pending','','$approver')");

        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Filed Attendance Adjustment','$datetime')");

        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check-circle"></i> Request successfully submitted.</h4>
            </div>';
    }
}
if (isset($_POST['btn_timeout_request'])) {
    if ($_POST['time_out'] == null || $_POST['date'] == null || $_POST['reason'] == null) {
        $res = '<div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-times"></i> Please complete the field.</h4>
        </div>';
    } else {
        $initial_timeout = $_POST['time_out'];
        $date = $_POST['date'];
        $reason = $_POST['reason'];
        $approver = $_POST['approver'];
        $company = $_SESSION['hris_company_id'];
        $emp_num = $_SESSION['hris_employee_number'];
        $timein_now = date("h:i:s a", strtotime($initial_timeout));
        $timein24_now = date("H:i:s", strtotime($initial_timeout));


        mysqli_query($db, "INSERT INTO tbl_attendance_adjust_request VALUES('','$emp_num','$date','$timein_now','$timein24_now','$reason','Time Out','$company','Pending','','$approver')");

        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Filed Attendance Adjustment','$datetime')");

        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check-circle"></i> Request successfully submitted.</h4>
            </div>';
    }
}
if (isset($_POST['approve_attendance_request'])) {
    if ($_POST['date'] == null || $_POST['time'] == null || $_POST['request_type'] == null || $_POST['reason'] == null) {
        $res = '<div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-times"></i> Please complete the field.</h4>
        </div>';
    } else {
        $request_type = $_POST['request_type'];

        if ($request_type == "Time In") {
            $date = $_POST['date'];
            $emp_num = $_POST['emp_num'];
            $time = $_POST['time'];
            $time_24 = date("H:i:s", strtotime($time));
            $exist = false;
            $at_name = $_SESSION['hris_account_name'];
            $emp_name = $_POST['emp_name'];

            $sql = mysqli_query($db, "SELECT * FROM tbl_attendance WHERE emp_num = '$emp_num' AND (datenow = '$date' AND statusnow = 'Time In')");
            while ($row = mysqli_fetch_assoc($sql)) {
                $exist = true;
            }

            if ($exist == true) {

                mysqli_query($db, "UPDATE tbl_attendance SET timenow = '$time', timenow_m = '$time_24' WHERE emp_num = '$emp_num' AND (datenow = '$date' AND statusnow = 'Time In')");

                mysqli_query($db, "UPDATE tbl_attendance_adjust_request SET status = 'Approved' WHERE id = '$id'");


                mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Approved Attendance Adjustment: $id','$datetime')");
            } else {

                mysqli_query($db, "INSERT INTO tbl_attendance VALUES('','$emp_num','$emp_name','Time In','$date','$time','$time_24','0','0','0')");

                mysqli_query($db, "UPDATE tbl_attendance_adjust_request SET status = 'Approved' WHERE id = '$id'");


                mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Approved Attendance Adjustment: $id','$datetime')");
            }

            $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check-circle"></i> Request has been approved.</h4>
            </div>';
        } else {

            $date = $_POST['date'];
            $emp_num = $_POST['emp_num'];
            $time = $_POST['time'];
            $time_24 = date("H:i:s", strtotime($time));
            $exist = false;
            $emp_name = $_POST['emp_name'];
            $at_name = $_SESSION['hris_account_name'];
            $selectedtimein;
            $id = $_POST['id'];

            $sql = mysqli_query($db, "SELECT timenow_m FROM tbl_attendance WHERE emp_num = '$emp_num' AND (statusnow = 'Time In' AND datenow = '$date')");
            while ($row = mysqli_fetch_assoc($sql)) {
                $selectedtimein = $row['timenow_m'];
            }

            $starttime = $selectedtimein;
            $endtime = $time_24;

            function dateTimeDiff($time1, $time2)
            {
                $diff = array();
                $first = strtotime($time1);
                $second = strtotime($time2);
                $datediff = abs($first - $second);
                $dif['h'] = floor($datediff / (60 * 60));
                $dif['m'] = floor($datediff / (60));

                return $dif;
            }
            $workingHours = dateTimeDiff($endtime, $starttime)['h'] - 1;
            $initial_workingMinutes = dateTimeDiff($endtime, $starttime)['m'];

            $n = $initial_workingMinutes / 60;

            $whole = floor($n);
            $fraction = $n - $whole;

            $workingMinutes = $fraction * 60;

            $total_duration = $workingHours . ':' . $workingMinutes;

            $if_exist = mysqli_query($db, "SELECT * FROM tbl_attendance WHERE emp_num = '$emp_num' AND (datenow = '$date' AND statusnow = 'Time Out')");
            while ($row = mysqli_fetch_assoc($if_exist)) {
                $exist = true;
            }

            if ($exist == true) {
                mysqli_query($db, "UPDATE tbl_attendance SET timenow = '$time', timenow_m = '$time_24', duration_hours = '$workingHours', duration_minutes = '$workingMinutes', total_duration = '$total_duration' WHERE emp_num = '$emp_num' AND (datenow = '$date' AND statusnow = 'Time Out')");

                mysqli_query($db, "UPDATE tbl_attendance_adjust_request SET status = 'Approved' WHERE WHERE id = '$id'");


                mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Approved Attendance Adjustment: $id','$datetime')");
            } else {
                $status = 'Time Out';
                mysqli_query($db, "INSERT INTO tbl_attendance VALUES('','$emp_num','$emp_name','$status','$date','$time', '$time_24', '$workingHours', '$workingMinutes','$total_duration')");

                mysqli_query($db, "UPDATE tbl_attendance_adjust_request SET status = 'Approved' WHERE WHERE id = '$id'");

                $at_name = $_SESSION['hris_account_name'];
                mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Approved Attendance Adjustment: $id','$datetime')");
            }

            $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check-circle"></i> Request has been approved.</h4>
            </div>';
        }
    }
}
if (isset($_POST['deny_attendance_request'])) {
    if ($_POST['date'] == null || $_POST['time'] == null || $_POST['request_type'] == null || $_POST['reason'] == null || $_POST['remarks'] == null) {
        $res = '<div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-times"></i> Please put on remarks or complete the fields.</h4>
            </div>';
    } else {
        $remarks = $_POST['remarks'];
        $id = $_POST['id'];
        $emp_num = $_POST['emp_num'];
        mysqli_query($db, "UPDATE tbl_attendance_adjust_request SET status = 'Denied', remarks = '$remarks' WHERE id = '$id'");

        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check-circle"></i> Request has been denied.</h4>
            </div>';
    }
}
if (isset($_POST['btn_timeinout'])) {

    if ($_POST['status'] == "Time In") {

        // TIME IN CONDITION

        $date_now = date('y-m-d');
        $timein_now = date('h:i:s a');
        $timein24_now = date("H:i:s");
        $employee_name = $_POST['emp_name'];
        $employee_num = $_POST['emp_num'];
        // $company_name = $_SESSION['hris_company_id'];
        $status = "Time In";
        $durationin = "";

        $aydi = $_SESSION['hris_id'];
        $emeyl = $_SESSION['hris_email'];
        $exist = false;


        mysqli_query($db, "INSERT INTO tbl_attendance VALUES('','$employee_num','$employee_name','$status','$date_now','$timein_now', '$timein24_now', '0', '0', '0')");

        mysqli_query($db, "INSERT INTO tbl_attendance_trail VALUES('','$aydi','$emeyl','$employee_name','$employee_num','$timein_now', '$date_now','Time In')");

        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$employee_name','Time In: $timein_now','$datetime')");


        $if_exist = mysqli_query($db, "SELECT * FROM tbl_attendance WHERE emp_num = '00029' AND (datenow = '$date_now' AND statusnow = 'Time In')");
        while ($row = mysqli_fetch_assoc($if_exist)) {
            $exist = true;
        }

        if ($exist == false) {
            mysqli_query($db, "INSERT INTO tbl_attendance VALUES('','00029','Rolirey Herico Flores','$status','$date_now','9:00:00 am', '9:00:00', '0', '0', '0')");
        }

        $res = '<div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="fa fa-check-circle"></i> ' . $employee_name . ' Time In Success.</h4>
        </div>';
    } else {

        // TIME OUT CONDITION
        $_SESSION['btn_disabled'] = true;


        $date_now = date('y-m-d');
        $timeout_now = date('h:i:s a');
        $timeout24_now = date("H:i:s");
        $employee_name = $_POST['emp_name'];
        $employee_num = $_POST['emp_num'];
        $status = "Time Out";
        $exist = false;

        $sql3 = mysqli_query($db, "SELECT timenow_m FROM tbl_attendance WHERE emp_num = '$employee_num' AND (statusnow = 'Time In' AND datenow = '$date_now')");
        while ($row = mysqli_fetch_assoc($sql3)) {
            $selectedtimein = $row['timenow_m'];
        }


        // COMPUTATION OF DURATION
        $starttime = $selectedtimein;
        $endtime = $timeout24_now;

        function dateTimeDiff($time1, $time2)
        {
            $diff = array();
            $first = strtotime($time1);
            $second = strtotime($time2);
            $datediff = abs($first - $second);
            $dif['h'] = floor($datediff / (60 * 60));
            $dif['m'] = floor($datediff / (60));

            return $dif;
        }
        $workingHours = dateTimeDiff($endtime, $starttime)['h'] - 1;
        $initial_workingMinutes = dateTimeDiff($endtime, $starttime)['m'];

        $n = $initial_workingMinutes / 60;

        $whole = floor($n);
        $fraction = $n - $whole;

        $workingMinutes = $fraction * 60;

        $total_duration = $workingHours . ':' . $workingMinutes;

        $aydi = $_SESSION['hris_id'];
        $emeyl = $_SESSION['hris_email'];

        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$employee_name','Time Out: $timeout_now','$datetime')");

        mysqli_query($db, "INSERT INTO tbl_attendance_trail VALUES('','$aydi','$emeyl','$employee_name','$employee_num','$timeout_now', '$date_now','Time Out')");

        $if_exist = mysqli_query($db, "SELECT * FROM tbl_attendance WHERE emp_num = '00029' AND (datenow = '$date_now' AND statusnow = 'Time Out')");
        while ($row = mysqli_fetch_assoc($if_exist)) {
            $exist = true;
        }

        if ($exist == false) {
            mysqli_query($db, "INSERT INTO tbl_attendance VALUES('','00029','Rolirey Herico Flores','$status','$date_now','6:00:00 pm', '18:00:00', '9', '0','9:00')");
        }

        $sql = mysqli_query($db, "INSERT INTO tbl_attendance VALUES('','$employee_num','$employee_name','$status','$date_now','$timeout_now', '$timeout24_now', '$workingHours', '$workingMinutes','$total_duration')");
        $res = '<div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="fa fa-check-circle"></i> ' . $employee_name . ' Time Out Success.</h4>
        </div>';
    }
}
function getWorkingDays($startDate, $endDate, $holidays)
{
    // do strtotime calculations just once
    $endDate = strtotime($endDate);
    $startDate = strtotime($startDate);


    //The total number of days between the two dates. We compute the no. of seconds and divide it to 60*60*24
    //We add one to inlude both dates in the interval.
    $days = ($endDate - $startDate) / 86400 + 1;

    $no_full_weeks = floor($days / 7);
    $no_remaining_days = fmod($days, 7);

    //It will return 1 if it's Monday,.. ,7 for Sunday
    $the_first_day_of_week = date("N", $startDate);
    $the_last_day_of_week = date("N", $endDate);

    //---->The two can be equal in leap years when february has 29 days, the equal sign is added here
    //In the first case the whole interval is within a week, in the second case the interval falls in two weeks.
    if ($the_first_day_of_week <= $the_last_day_of_week) {
        if ($the_first_day_of_week <= 6 && 6 <= $the_last_day_of_week) $no_remaining_days--;
        if ($the_first_day_of_week <= 7 && 7 <= $the_last_day_of_week) $no_remaining_days--;
    } else {
        // (edit by Tokes to fix an edge case where the start day was a Sunday
        // and the end day was NOT a Saturday)

        // the day of the week for start is later than the day of the week for end
        if ($the_first_day_of_week == 7) {
            // if the start date is a Sunday, then we definitely subtract 1 day
            $no_remaining_days--;

            if ($the_last_day_of_week == 6) {
                // if the end date is a Saturday, then we subtract another day
                $no_remaining_days--;
            }
        } else {
            // the start date was a Saturday (or earlier), and the end date was (Mon..Fri)
            // so we skip an entire weekend and subtract 2 days
            $no_remaining_days -= 2;
        }
    }

    //The no. of business days is: (number of weeks between the two dates) * (5 working days) + the remainder
    //---->february in none leap years gave a remainder of 0 but still calculated weekends between first and last day, this is one way to fix it
    $workingDays = $no_full_weeks * 5;
    if ($no_remaining_days > 0) {
        $workingDays += $no_remaining_days;
    }

    //We subtract the holidays
    foreach ($holidays as $holiday) {
        $time_stamp = strtotime($holiday);
        //If the holiday doesn't fall in weekend
        if ($startDate <= $time_stamp && $time_stamp <= $endDate && date("N", $time_stamp) != 6 && date("N", $time_stamp) != 7)
            $workingDays--;
    }

    return $workingDays;
}
function check_if_job_grade_set_exist($job_grade_set, $company_id)
{
    $db = connect();
    $sql = mysqli_query($db, "SELECT * FROM tbl_job_grade_set WHERE job_grade_set = '$job_grade_set' AND company_id = '$company_id'");
    if (mysqli_num_rows($sql) > 0) {
        return true;
    }
}
function reset_view_payslip()
{
    $_SESSION['selected_empname'] = null;
    $_SESSION['selected_jobtitle'] = null;
    $_SESSION['selected_comp'] = null;
    $_SESSION['selected_basicsalary'] = null;
    $_SESSION['selected_tax'] = null;
    $_SESSION['selected_netsalary'] = null;
    $_SESSION['selected_sss'] = null;
    $_SESSION['selected_philhealth'] = null;
    $_SESSION['selected_pagibig'] = null;
    $_SESSION['selected_gsis'] = null;
}
function reset_all_edit_session()
{
    $_SESSION['selected_edit_emp'] = null;
    $_SESSION['selected_edit_empname'] = null;
    $_SESSION['selected_edit_company'] = null;
    $_SESSION['selected_edit_jobtitle'] = null;
    $_SESSION['selected_edit_basicsalary'] = null;
    $_SESSION['selected_edit_accno'] = null;
    $_SESSION['selected_edit_sss'] = null;
    $_SESSION['selected_edit_pagibig'] = null;
    $_SESSION['selected_edit_philhealth'] = null;
    $_SESSION['selected_edit_gsis'] = null;
    $_SESSION['selected_edit_deminimis'] = null;
    $_SESSION['selected_edit_bankname'] = null;
    $_SESSION['selected_edit_commission'] = null;
    $_SESSION['selected_edit_whtax'] = null;
    $_SESSION['selected_edit_remarks'] = null;
    $_SESSION['selected_edit_cola'] = null;
    $_SESSION['selected_edit_additional_allowance'] = null;
    $_SESSION['selected_edit_additional_allowance_amount'] = null;
    $_SESSION['selected_edit_monthly_gross'] = null;
}

// newly added config
function reset_add_earnings()
{
    $_SESSION['selected_earndescription'] = null;
    $_SESSION['selected_earnamount'] = null;
    $_SESSION['selected_current_netsalary'] = null;
}
function reset_minus_deductions()
{
    $_SESSION['selected_deducdescription'] = null;
    $_SESSION['selected_deducamount'] = null;
    $_SESSION['selected_current_netsalary'] = null;
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
    $suffix = $_POST['suffix'];
    $initials = $_POST['initials'];
    $account_created = '0';

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
        '$datetime',
        '$account_created',
        '0',
        '$suffix',
        '$initials'
        )");
    if ($insert_personal_info) {
        $employee_number = mysqli_insert_id($db);
        $employee_number =  sprintf("%05d", $employee_number);
        $update_emp_num = mysqli_query($db, "UPDATE tbl_personal_information
        SET employee_number = '$employee_number'
        WHERE employee_number = 'xxx-xxx'");
    }
    $employee_number =  sprintf("%05d", $employee_number);
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
    $employee_name = $_SESSION['hris_account_name'];
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
    $group = $_POST['group'];
    $unit = $_POST['unit'];
    $position = $_POST['position'];
    $rank = $_POST['rank'];
    $hmo_number = $_POST['hmo_number'];
    $tenure = $_POST['tenure'];

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
        '$is_approver',
        '$group',
        '$unit',
        '$position',
        '$rank',
        '$hmo_number',
        '$tenure'
        );");

    // Supporting Documents
    $attachment = $_FILES['attachment']['name']; // array
    $attachment_tmp = $_FILES['attachment']['tmp_name']; // array
    $attachment_remarks = $_POST['attachment_remarks']; // array
    // foreach ($attachment as $k => $v) {
    //     $value = md5($v);
    //     $supporting_attachment = uploadAttachment('attachment');
    //     mysqli_query($db, "INSERT INTO tbl_documents VALUES('','','$supporting_attachment','$attachment_remarks[$k]')");
    //     move_uploaded_file($attachment_tmp[$k], "uploads/" . $value);
    // }
    $supporting_attachment = uploadAttachment('attachment');
    mysqli_query($db, "INSERT INTO tbl_documents VALUES('','$employee_number','$supporting_attachment','$attachment_remarks')");
    move_uploaded_file($attachment_tmp, "uploads/" . $supporting_attachment);

    // Benefits Eligibility
    $parking = "0";
    $gasoline = "0";
    $car_maintenance = "0";
    $medicine = "0";
    $optical_allowance = "0";
    $others = "0";
    $medical_allowance = "0";
    $transportation_allowance = "0";
    $meal_allowance = "0";
    $leave_credits = "0";
    $hmo = "0";
    $maternity_paternity = "0";
    $gym = "0";
    $cep = "0";
    $club_membership = "0";
    $maternity = "0";

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
            if ($v == "Optical Allowance") {
                $optical_allowance = "1";
            }
            if ($v == "Others") {
                $others = "1";
            }
            if ($v == "Medical Allowance") {
                $medical_allowance = "1";
            }
            if ($v == "Transportation Allowance") {
                $transportation_allowance = "1";
            }
            if ($v == "Meal Allowance") {
                $meal_allowance = "1";
            }
            if ($v == "Leave Credits") {
                $leave_credits = "1";
            }
            if ($v == "HMO") {
                $hmo = "1";
            }
            if ($v == "Maternity and/or Paternity Gift") {
                $maternity_paternity = "1";
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
            '$others',
            '$medical_allowance',
            '$transportation_allowance',
            '$meal_allowance',
            '$leave_credits',
            '$hmo',
            '$maternity_paternity')");
    }
    // Leave Balances
    mysqli_query($db, "INSERT INTO tbl_leave_balances VALUES(
        '',
        '$employee_number',
        '$account_name',
        '0',
        '0',
        '0',
        '0',
        '0',
        '0',
        '0',
        '0',
        '0',
        '0')");

    $at_name = $_SESSION['hris_account_name'];
    mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Onboarded an employee: $account_name','$datetime')");

    $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> Employee added in 201 File.</h4>
        </div>';
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
    $suffix = $_POST['suffix'];
    $initials = $_POST['initials'];

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
        age = '$age',
        suffix = '$suffix',
        initials = '$initials'
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
    $group = $_POST['group'];
    $unit = $_POST['unit'];
    $position = $_POST['position'];
    $rank = $_POST['rank'];
    $hmo_number = $_POST['hmo_number'];
    $tenure = $_POST['tenure'];

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
        is_approver = '$is_approver',
        group_name = '$group',
        unit = '$unit',
        position = '$position',
        rank_name = '$rank',
        hmo_number = '$hmo_number',
        tenure = '$tenure'
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

if (isset($_POST['btn_educational_loan'])) {
    if ($_POST['selected_employee_educ'] == 'null') {
        $res = '<div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="fa fa-times"></i> Complete necessary fields to proceed.</h4>
    </div>';
    } else {
        $selected_employee_id = $_POST['selected_employee_educ'];
        $educ_loan_amount = $_POST['educ_loan_amount'];
        $educ_description = $_POST['educ_description'];
        $educ_date_availed = $_POST['educ_date_availed'];
        $at_name = $_SESSION['hris_account_name'];

        $get_employee_name = mysqli_query($db, "SELECT * FROM tbl_personal_information WHERE employee_number = '$selected_employee_id'");
        while ($row = mysqli_fetch_assoc($get_employee_name)) {
            $employee_name = $row['account_name'];
        }
        // Supporting Documents
        $attachment = $_FILES['educ_attachment']['name']; // array
        $attachment_tmp = $_FILES['educ_attachment']['tmp_name']; // array

        $supporting_educ_attachment = uploadAttachmentLoan('attachment');
        move_uploaded_file($attachment_tmp, "uploads/" . $supporting_educ_attachment);

        $sql = mysqli_query($db, "INSERT INTO tbl_loan_records VALUES('','$selected_employee_id','$employee_name','Educational Loan','$educ_loan_amount','$educ_description','$educ_date_availed','$at_name','$datetime','$supporting_educ_attachment')");

        if ($sql) {
            $res = '<div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="fa fa-check"></i> Educational Loan has been registered.</h4>
            </div>';
            mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Added Educational Loan for: $employee_name','$datetime')");
        }
    }
}

if (isset($_POST['btn_add_document'])) {
    $employee_number = $_POST['employee_number'];

    // Supporting Documents
    $attachment = $_FILES['attachment']['name']; // array
    $attachment_tmp = $_FILES['attachment']['tmp_name']; // array
    $attachment_remarks = $_POST['attachment_remarks']; // array 

    $supporting_attachment = uploadAttachment('attachment');
    $sql = mysqli_query($db, "INSERT INTO tbl_documents VALUES('','$employee_number','$supporting_attachment','$attachment_remarks')");
    move_uploaded_file($attachment_tmp, "uploads/" . $supporting_attachment);
    if ($sql) {
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> Document has been added.</h4>
        </div>';
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
    $meal = "0";
    $medical = "0";
    $transportation = "0";
    $leave = "0";
    $hmo = "0";
    $maternity_paternity = "0";
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
            if ($v == "Medical Allowance") {
                $medical = "1";
            }
            if ($v == "Transportation Allowance") {
                $transportation = "1";
            }
            if ($v == "Meal Allowance") {
                $meal = "1";
            }
            if ($v == "Leave Credits") {
                $leave = "1";
            }
            if ($v == "HMO") {
                $hmo = "1";
            }
            if ($v == "Maternity and/or Paternity Gift") {
                $maternity_paternity = "1";
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
        others = '$others',
        medical_allowance = '$medical',
        transportation_allowance = '$transportation',
        meal_allowance = '$meal',
        leave_credits = '$leave',
        hmo = '$hmo',
        maternity_paternity = '$maternity_paternity'
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
if (isset($_POST['cancellation_emp'])) {

    $applied_by = $_POST['applied_by'];
    $la_emp_number = $_POST['la_emp_number'];
    $leaveapplication_id = $_POST['la_id'];
    $status = "For Cancellation";
    $cancellation_remarks = $_POST['cancellation_remarks'];
    $at_name = $_SESSION['hris_account_name'];

    $sql = mysqli_query($db, "SELECT * FROM tbl_personal_information WHERE employee_number = '$la_emp_number'");
    while ($row = mysqli_fetch_assoc($sql)) {
        $account_name = $row['account_name'];
    }
    $sql = mysqli_query($db, "INSERT INTO tbl_cancellation VALUES('','$la_emp_number','$account_name','$leaveapplication_id','$cancellation_remarks','$status','$at_name','$datetime')");

    if ($sql) {
        $res = '<div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="fa fa-check"></i> Leave Application for <strong>' . $account_name . ' </strong> has been updated.</h4>
            </div>';
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Employee: $account_name applied for Cancellation of Leave','$datetime')");
    }
}
if (isset($_POST['transfer_of_leave_emp'])) {

    if ($_POST['emp_totalnumDays'] == null || $_POST['emp_m_startDate'] == null || $_POST['emp_m_endDate'] == null || $_POST['emp_leave_duration'] == 'null') {
        $res = '<div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-times"></i> Complete all necessary fields to proceed.</h4>
        </div>';
    } else {
        $leave_duration = $_POST['emp_leave_duration'];
        $orig_startDate = $_POST['tol_startDate'];
        $orig_endDate = $_POST['tol_endDate'];
        $modified_startDate = $_POST['emp_m_startDate'];
        $modified_endDate = $_POST['emp_m_endDate'];
        $totalnumDays = $_POST['emp_totalnumDays'];
        $leave_app_id = $_POST['leave_app_id'];
        $la_emp_number = $_POST['la_emp_number'];
        $reason = $_POST['reason'];
        $status = 'Pending';
        $at_name = $_SESSION['hris_account_name'];

        $get_employee = mysqli_query($db, "SELECT * FROM tbl_users WHERE employee_number = '$la_emp_number'");
        while ($row = mysqli_fetch_assoc($get_employee)) {
            $account_name = $row['account_name'];
        }

        $sql = mysqli_query($db, "INSERT INTO tbl_transfer_of_leave VALUES('','$la_emp_number','$account_name','$leave_app_id','$orig_startDate','$orig_endDate','$modified_startDate','$modified_endDate','$leave_duration','$totalnumDays','$status','$at_name','$datetime','$reason')");

        if ($sql) {
            $res = '<div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="fa fa-check"></i> Leave Application for <strong>' . $account_name . ' </strong> has been updated.</h4>
            </div>';
            mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Employee: $account_name applied for Transfer of Leave','$datetime')");
        }
    }
}
if (isset($_POST['transfer_of_leave'])) {

    if ($_POST['totalnumDays'] == null || $_POST['m_startDate'] == null || $_POST['m_endDate'] == null || $_POST['leave_duration'] == 'null') {
        $res = '<div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-times"></i> Complete all necessary fields to proceed.</h4>
        </div>';
    } else {
        // initialize
        $tol_id = '';
        if (isset($_POST['tol_id'])) {
            $tol_id = $_POST['tol_id'];
        }
        $leave_duration = $_POST['leave_duration'];
        $modified_startDate = $_POST['m_startDate'];
        $modified_endDate = $_POST['m_endDate'];
        $totalnumDays = $_POST['totalnumDays'];
        $leave_app_id = $_POST['leave_app_id'];
        $la_emp_number = $_POST['la_emp_number'];

        $get_employee = mysqli_query($db, "SELECT * FROM tbl_users WHERE employee_number = '$la_emp_number'");
        while ($row = mysqli_fetch_assoc($get_employee)) {
            $account_name = $row['account_name'];
        }

        $sql = mysqli_query($db, "UPDATE tbl_leave_requests SET startDate = '$modified_startDate', endDate = '$modified_endDate', total_day = '$totalnumDays', duration = '$leave_duration' WHERE id = '$leave_app_id'");

        if ($tol_id != '0') {
            mysqli_query($db, "UPDATE tbl_transfer_of_leave SET status = 'Approved' WHERE id = '$tol_id'");
        }


        if ($sql) {
            $res = '<div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="fa fa-check"></i> Leave Application for <strong>' . $account_name . ' </strong> has been updated.</h4>
            </div>';
            $at_name = $_SESSION['hris_account_name'];
            mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Updated Leave Application for Employee: $account_name','$datetime')");
        }
    }
}

if (isset($_POST['compute_leave_duration1'])) {
    $emp_m_startDate = $_POST['emp_m_startDate'];
    $emp_m_endDate = $_POST['emp_m_endDate'];
    $la_leave_type = $_POST['la_leave_type'];

    $emp_m_startDate = new DateTime($emp_m_startDate);
    $emp_m_endDate = new DateTime($emp_m_endDate);
    $emp_m_endDate->modify('+1 day');
    $interval = $emp_m_endDate->diff($emp_m_startDate);
    $days2 = $interval->days;
    $period = new DatePeriod($emp_m_startDate, new DateInterval('P1D'), $emp_m_endDate);
    $holidays = array();

    // Get holidays
    $sql = mysqli_query($db, "SELECT * FROM tbl_holidays");
    while ($row = mysqli_fetch_assoc($sql)) {
        $holidays[] = $row['holiday_date'];
    }
    foreach ($period as $dt) {
        $curr = $dt->format('D');
        if ($curr == 'Sun') {
            $days2--;
        } elseif (in_array($dt->format('Y-m-d'), $holidays)) {
            $days2--;
        }
    }

    echo $days2;
}
if (isset($_POST['compute_leave_durationn'])) {
    $m_startDate1 = $_POST['m_startDate'];
    $m_endDate1 = $_POST['m_endDate'];
    $la_leave_type = $_POST['la_leave_type'];

    $m_startDate = new DateTime($m_startDate1);
    $m_endDate = new DateTime($m_endDate1);
    $m_endDate->modify('+1 day');
    $interval = $m_endDate->diff($m_startDate);
    $days1 = $interval->days;
    $period = new DatePeriod($m_startDate, new DateInterval('P1D'), $m_endDate);
    $holidays = array();

    // Get holidays
    $sql = mysqli_query($db, "SELECT * FROM tbl_holidays");
    while ($row = mysqli_fetch_assoc($sql)) {
        $holidays[] = $row['holiday_date'];
    }
    foreach ($period as $dt) {
        $curr = $dt->format('D');
        if ($curr == 'Sun') {
            $days1--;
        } elseif (in_array($dt->format('Y-m-d'), $holidays)) {
            $days1--;
        }
    }

    echo $days1;
}

if (isset($_POST['compute_leave_duration'])) {
    $startDate1 = $_POST['startDate'];
    $endDate1 = $_POST['endDate'];

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

    foreach ($period as $dt) {
        $curr = $dt->format('D');
        if ($curr == 'Sun') {
            $days--;
        } elseif (in_array($dt->format('Y-m-d'), $holidays)) {
            $days--;
        }
    }

    echo $days;
}

// with combi
if (isset($_POST['select_leave_balances'])) {
    $leaves = array('SL', 'VL', 'others');
    $combi = 0;
    $leave_type = $_POST['leave_type'];
    if ($leave_type == 'SLVL') {
        $employee_number = $_POST['delegate'];

        $com = mysqli_query($db, "SELECT * FROM tbl_leave_balances WHERE employee_number = '$employee_number'");

        if ($row = mysqli_fetch_assoc($com)) {
            $sl_leave = $row['SL'];
            $vl_leave = $row['VL'];
            $combi =  $vl_leave +  $sl_leave;
            echo $combi;
        }
    }
    foreach ($leaves as $k => $v) {
        if ($leave_type == $v) {
            $employee_number = $_POST['delegate'];

            $sql = mysqli_query($db, "SELECT * FROM tbl_leave_balances WHERE employee_number = '$employee_number'");

            if ($row = mysqli_fetch_assoc($sql)) {
                echo $row[$leave_type];
            }
        }
    }
}
if (isset($_POST['select_leave_balances_others'])) {
    $leaves_others = array('ape', 'hp', 'bl');
    $leave_type_others = $_POST['leave_type_others'];
    foreach ($leaves_others as $k => $v) {
        if ($leave_type_others == $v) {
            $employee_number = $_POST['delegate'];

            $sql = mysqli_query($db, "SELECT * FROM tbl_leave_balances WHERE employee_number = '$employee_number'");

            if ($row = mysqli_fetch_assoc($sql)) {
                echo $row[$leave_type_others];
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
if (isset($_POST['commute_leave'])) {
    $employee_number = $_POST['employee_number'];
    $employee_name = $_POST['employee_name'];
    $leave_type = $_POST['leave_types'];
    $amount_forfeit = $_POST['amount_forfeits'];
    $amt_val = $_POST['amt_vals'];
    $userid = $_SESSION['hris_id'];

    $total_forfeit_leave = $amt_val - $amount_forfeit;
    mysqli_query($db, "UPDATE tbl_leave_balances SET VL = '$total_forfeit_leave' WHERE employee_number = '$employee_number'");
    mysqli_query($db, "INSERT INTO tbl_commute VALUES('','$employee_number','$employee_name','$leave_type','$amount_forfeit','$userid','$datetime')");
    $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> Commuted Leave for <strong>' . $employee_name . '</strong> has been registered.</h4>
        </div>';
}
if (isset($_POST['forfeit_leave'])) {
    $employee_number = $_POST['employee_number'];
    $employee_name = $_POST['employee_name'];
    $leave_type = $_POST['leave_type'];
    $amount_forfeit = $_POST['amount_forfeit'];
    $amt_val = $_POST['amt_val'];
    $userid = $_SESSION['hris_id'];

    $total_forfeit_leave = $amt_val - $amount_forfeit;
    if ($leave_type == 'SL') {
        mysqli_query($db, "UPDATE tbl_leave_balances SET SL = '$total_forfeit_leave' WHERE employee_number = '$employee_number'");
        mysqli_query($db, "INSERT INTO tbl_forfeit VALUES('','$employee_number','$employee_name','$leave_type','$amount_forfeit','$userid','$datetime')");
    } else if ($leave_type == 'VL') {
        mysqli_query($db, "UPDATE tbl_leave_balances SET VL = '$total_forfeit_leave' WHERE employee_number = '$employee_number'");
        mysqli_query($db, "INSERT INTO tbl_forfeit VALUES('','$employee_number','$employee_name','$leave_type','$amount_forfeit','$userid','$datetime')");
    } else if ($leave_type == 'EL') {
        mysqli_query($db, "UPDATE tbl_leave_balances SET EL = '$total_forfeit_leave' WHERE employee_number = '$employee_number'");
        mysqli_query($db, "INSERT INTO tbl_forfeit VALUES('','$employee_number','$employee_name','$leave_type','$amount_forfeit','$userid','$datetime')");
    } else if ($leave_type == 'maternity') {
        mysqli_query($db, "UPDATE tbl_leave_balances SET maternity = '$total_forfeit_leave' WHERE employee_number = '$employee_number'");
        mysqli_query($db, "INSERT INTO tbl_forfeit VALUES('','$employee_number','$employee_name','$leave_type','$amount_forfeit','$userid','$datetime')");
    } else if ($leave_type == 'paternity') {
        mysqli_query($db, "UPDATE tbl_leave_balances SET paternity = '$total_forfeit_leave' WHERE employee_number = '$employee_number'");
        mysqli_query($db, "INSERT INTO tbl_forfeit VALUES('','$employee_number','$employee_name','$leave_type','$amount_forfeit','$userid','$datetime')");
    } else if ($leave_type == 'solo_parent') {
        mysqli_query($db, "UPDATE tbl_leave_balances SET solo_parent = '$total_forfeit_leave' WHERE employee_number = '$employee_number'");
        mysqli_query($db, "INSERT INTO tbl_forfeit VALUES('','$employee_number','$employee_name','$leave_type','$amount_forfeit','$userid','$datetime')");
    }
    $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> Forfeited Leave for <strong>' . $employee_name . '</strong> has been registered.</h4>
        </div>';
}
if (isset($_POST['forfeit_all'])) {
    $employee_number = $_POST['employee_number'];
    $employee_name = $_POST['employee_name'];
    $userid = $_SESSION['hris_id'];


    mysqli_query($db, "UPDATE tbl_leave_balances SET solo_parent = '0', SL = '0', VL = '0', EL = '0', maternity = '0', paternity = '0' WHERE employee_number = '$employee_number'");
    $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> Forfeited Leave for <strong>' . $employee_name . '</strong> has been registered.</h4>
        </div>';
}

if (isset($_POST['btn_leave_application'])) {
    $employee_name = $_SESSION['hris_account_name']; // row id
    $employee_num = $_SESSION['hris_employee_number'];
    $leave_type = $_POST['leave_type'];
    $emp_name = $_POST['delegate_emp_number'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    $duration = $_POST['duration'];
    $total_days = $_POST['total_days'];

    $late_filing_val = '0';
    $reason = $_POST['reason'];
    // $remarks = $_POST['remarks'];
    $approver = $_POST['approver'];
    $approver_remarks = "";
    $lwop_days = 0;
    if ($_SESSION['hris_role'] == "Admin") {
        $sql = mysqli_query($db, "SELECT * FROM tbl_personal_information WHERE employee_number = '$emp_name'");
        while ($row = mysqli_fetch_assoc($sql)) {
            $employee_name = $row['account_name'];
        }
    }

    // get lwop days
    $get_lb = mysqli_query($db, "SELECT * FROM tbl_leave_balances WHERE employee_number = '$emp_name'");
    while ($row = mysqli_fetch_assoc($get_lb)) {
        $lb_vl = $row['VL'];
        $lb_sl = $row['SL'];
        $lb_others = $row['others'];
        $lb_maternity = $row['maternity'];
        $lb_paternity = $row['paternity'];
        $lb_solo_parent = $row['solo_parent'];
        $lb_ape = $row['ape'];
        $lb_hp = $row['hp'];
        $lb_bl = $row['bl'];
    }

    $combi_slvl = $lb_sl + $lb_vl;

    if ($leave_type == 'VL') {
        $lwop_days = $total_days - $lb_vl;
        if ($lwop_days > 0) {
            $leave_without_pay = $lwop_days;
        } else {
            $leave_without_pay = '0';
        }
    } else if ($leave_type == 'SL') {
        $lwop_days = $total_days - $lb_sl;
        if ($lwop_days > 0) {
            $leave_without_pay = $lwop_days;
        } else {
            $leave_without_pay = '0';
        }
    } else if ($leave_type == 'SLVL') {
        $lwop_days = $total_days - $combi_slvl;
        if ($lwop_days > 0) {
            $leave_without_pay = $lwop_days;
        } else {
            $leave_without_pay = '0';
        }
    } else if ($leave_type == 'others') {
        if (isset($_POST['leave_type_others'])) {
            $leave_type_others = $_POST['leave_type_others'];
        }

        if ($leave_type_others == 'ape') {
            $lwop_days = $total_days - $lb_ape;
            if ($lwop_days > 0) {
                $leave_without_pay = $lwop_days;
            } else {
                $leave_without_pay = '0';
            }
        }
        if ($leave_type_others == 'hp') {
            $lwop_days = $total_days - $lb_hp;
            if ($lwop_days > 0) {
                $leave_without_pay = $lwop_days;
            } else {
                $leave_without_pay = '0';
            }
        }
        if ($leave_type_others == 'bl') {
            $lwop_days = $total_days - $lb_bl;
            if ($lwop_days > 0) {
                $leave_without_pay = $lwop_days;
            } else {
                $leave_without_pay = '0';
            }
        }
    }


    $company_id = $_SESSION['hris_company_id'];
    $month = date('F');
    $year = date('Y');
    if ($leave_type == 'others') {
        $leave_type_others = $_POST['leave_type_others'];
        if ($leave_type_others == 'ape') {
            $leave_type = 'others - APE';
        }
        if ($leave_type_others == 'hp') {
            $leave_type = 'others - HP';
        }
        if ($leave_type_others == 'bl') {
            $leave_type = 'others - BL';
        }
    }

    // FILE UPLOAD
    if (empty($_FILES['attachment']['tmp_name']) || !is_uploaded_file($_FILES['attachment']['tmp_name'])) {

        $sql = mysqli_query($db, "INSERT INTO tbl_leave_requests VALUES('','$company_id','$employee_num','$emp_name','$employee_name','$leave_type','$startDate','$endDate','$total_days','$reason','$duration','','$approver','$approver_remarks','Pending','$datetime','$late_filing_val','$reason','$month','$year','$leave_without_pay')");

        $at_name = $_SESSION['hris_account_name'];
        // audit trail
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Filed a leave application','$datetime')");
        $res = '<div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="fa fa-check"></i> Leave Application for <strong>' . $employee_name . '</strong> has been reqested.</h4>
    </div>';
    } else {
        $attachment = $_FILES['attachment']['name'];
        $fileSize = $_FILES['attachment']['size'];
        $fileType = $_FILES['attachment']['type'];
        $initialExt = explode('.', $attachment);
        $fileExt = strtolower(end($initialExt));

        $la_attachment = uploadFile('attachment');
        $sql = mysqli_query($db, "INSERT INTO tbl_leave_requests VALUES('','$company_id','$employee_num','$emp_name','$employee_name','$leave_type','$startDate','$endDate','$total_days','$reason','$duration','$la_attachment','$approver','$approver_remarks','Pending','$datetime','$late_filing_val','$approver_remarks','$month','$year','$leave_without_pay')");

        if ($sql) {
            $at_name = $_SESSION['hris_account_name'];
            mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Filed a leave application','$datetime')");
            $res = '<div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="fa fa-check"></i> Leave Application for <strong>' . $employee_name . '</strong> has been reqested.</h4>
                </div>';
        } else {
            $res = '<div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="fa fa-check"></i> Failed to file the Leave Application.</h4>
                </div>';
        }
    }
}



use Aws\S3\S3Client;

function uploadAttachmentLoan($fieldName)
{
    require 'vendor/autoload.php';

    // Instantiate an Amazon S3 client.
    $s3Client = new S3Client([
        'version' => 'latest',
        'region'  => 'ap-southeast-1',
        'credentials' => [
            'key'    => 'AKIAR2IERYMVHI6C36NV',
            'secret' => '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'
        ]
    ]);
    // Check if file was uploaded without errors
    if (isset($_FILES[$fieldName]) && $_FILES[$fieldName]["error"] == 0) {
        $allowed = array("pdf" => "application/pdf");
        $prefilename = $_FILES[$fieldName]["name"];
        $filetype = $_FILES[$fieldName]["type"];
        $filesize = $_FILES[$fieldName]["size"];

        $ext = pathinfo($prefilename, PATHINFO_EXTENSION);

        $info = pathinfo($prefilename);
        $file_name =  $info['filename'];
        $filename = $file_name . date("YmdHms") . '.' . $ext;

        // Validate file extension
        if (!array_key_exists($ext, $allowed)) die("Error: Please select a valid file format.");
        // Validate file size - 10MB maximum
        $maxsize = 10 * 1024 * 1024;
        if ($filesize > $maxsize) die("Error: File size is larger than the allowed limit.");
        // Validate type of the file
        if (in_array($filetype, $allowed)) {
            // Check whether file exists before uploading it
            if (file_exists("uploads/" . $filename)) {
                echo $filename . " is already exists.";
            } else {
                if (move_uploaded_file($_FILES[$fieldName]["tmp_name"], "uploads/" . $filename)) {
                    $bucket = 'lifelink-storage';
                    $file_Path = 'uploads/' . $filename;
                    $key = basename($file_Path);
                    try {
                        $result = $s3Client->putObject([
                            'Bucket' => $bucket,
                            'Key'    => 'LOAN/' . $key,
                            'Body'   => fopen($file_Path, 'r'),
                            'ACL'    => 'public-read', // make file 'public'
                        ]);
                        $res = '<div class="alert alert-success alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h4><i class="fa fa-check-circle"></i> Image uploaded successfully. Image path is: ' . $result->get('ObjectURL') . ' .</h4>
                                    </div>';
                    } catch (Aws\S3\Exception\S3Exception $e) {
                        $res = '<div class="alert alert-success alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h4><i class="fa fa-times"></i> There was an error uploading the file. ' . $e->getMessage() . ' .</h4>
                                    </div>';
                    }
                } else {
                }
            }
        } else {
        }
    } else {
    }

    return $filename;
}

function uploadAttachment($fieldName)
{
    require 'vendor/autoload.php';

    // Instantiate an Amazon S3 client.
    $s3Client = new S3Client([
        'version' => 'latest',
        'region'  => 'ap-southeast-1',
        'credentials' => [
            'key'    => 'AKIAR2IERYMVHI6C36NV',
            'secret' => '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'
        ]
    ]);
    // Check if file was uploaded without errors
    if (isset($_FILES[$fieldName]) && $_FILES[$fieldName]["error"] == 0) {
        $allowed = array("pdf" => "application/pdf");
        $prefilename = $_FILES[$fieldName]["name"];
        $filetype = $_FILES[$fieldName]["type"];
        $filesize = $_FILES[$fieldName]["size"];

        $ext = pathinfo($prefilename, PATHINFO_EXTENSION);

        $info = pathinfo($prefilename);
        $file_name =  $info['filename'];
        $filename = $file_name . date("YmdHms") . '.' . $ext;

        // Validate file extension
        if (!array_key_exists($ext, $allowed)) die("Error: Please select a valid file format.");
        // Validate file size - 10MB maximum
        $maxsize = 10 * 1024 * 1024;
        if ($filesize > $maxsize) die("Error: File size is larger than the allowed limit.");
        // Validate type of the file
        if (in_array($filetype, $allowed)) {
            // Check whether file exists before uploading it
            if (file_exists("uploads/" . $filename)) {
                echo $filename . " is already exists.";
            } else {
                if (move_uploaded_file($_FILES[$fieldName]["tmp_name"], "uploads/" . $filename)) {
                    $bucket = 'lifelink-storage';
                    $file_Path = 'uploads/' . $filename;
                    $key = basename($file_Path);
                    try {
                        $result = $s3Client->putObject([
                            'Bucket' => $bucket,
                            'Key'    => 'ONBOARDING/' . $key,
                            'Body'   => fopen($file_Path, 'r'),
                            'ACL'    => 'public-read', // make file 'public'
                        ]);
                        $res = '<div class="alert alert-success alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h4><i class="fa fa-check-circle"></i> Image uploaded successfully. Image path is: ' . $result->get('ObjectURL') . ' .</h4>
                                    </div>';
                    } catch (Aws\S3\Exception\S3Exception $e) {
                        $res = '<div class="alert alert-success alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h4><i class="fa fa-times"></i> There was an error uploading the file. ' . $e->getMessage() . ' .</h4>
                                    </div>';
                    }
                } else {
                }
            }
        } else {
        }
    } else {
    }

    return $filename;
}

function uploadFile($fieldName)
{
    require 'vendor/autoload.php';

    // Instantiate an Amazon S3 client.
    $s3Client = new S3Client([
        'version' => 'latest',
        'region'  => 'ap-southeast-1',
        'credentials' => [
            'key'    => 'AKIAR2IERYMVHI6C36NV',
            'secret' => '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'
        ]
    ]);
    // Check if file was uploaded without errors
    if (isset($_FILES[$fieldName]) && $_FILES[$fieldName]["error"] == 0) {
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png", "pdf" => "application/pdf");
        $prefilename = $_FILES[$fieldName]["name"];
        $filetype = $_FILES[$fieldName]["type"];
        $filesize = $_FILES[$fieldName]["size"];

        $ext = pathinfo($prefilename, PATHINFO_EXTENSION);

        $info = pathinfo($prefilename);
        $file_name =  $info['filename'];
        $filename = $file_name . date("YmdHms") . '.' . $ext;

        // Validate file extension
        if (!array_key_exists($ext, $allowed)) die("Error: Please select a valid file format.");
        // Validate file size - 10MB maximum
        $maxsize = 10 * 1024 * 1024;
        if ($filesize > $maxsize) die("Error: File size is larger than the allowed limit.");
        // Validate type of the file
        if (in_array($filetype, $allowed)) {
            // Check whether file exists before uploading it
            if (file_exists("uploads/" . $filename)) {
                echo $filename . " is already exists.";
            } else {
                if (move_uploaded_file($_FILES[$fieldName]["tmp_name"], "uploads/" . $filename)) {
                    $bucket = 'lifelink-storage';
                    $file_Path = 'uploads/' . $filename;
                    $key = basename($file_Path);
                    try {
                        $result = $s3Client->putObject([
                            'Bucket' => $bucket,
                            'Key'    => 'LA/' . $key,
                            'Body'   => fopen($file_Path, 'r'),
                            'ACL'    => 'public-read', // make file 'public'
                        ]);
                        $res = '<div class="alert alert-success alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h4><i class="fa fa-check-circle"></i> Image uploaded successfully. Image path is: ' . $result->get('ObjectURL') . ' .</h4>
                                    </div>';
                        // echo "Image uploaded successfully. Image path is: " . $result->get('ObjectURL');
                    } catch (Aws\S3\Exception\S3Exception $e) {
                        $res = '<div class="alert alert-success alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h4><i class="fa fa-times"></i> There was an error uploading the file. ' . $e->getMessage() . ' .</h4>
                                    </div>';
                    }
                } else {
                }
            }
        } else {
        }
    } else {
        echo "Error: " . $_FILES[$fieldName]["error"];
    }

    return $filename;
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
    $employee_number = $_POST['employee_number'];
    $company_id = '';
    $cname = $_POST['company_name'];
    $sql = mysqli_query($db, "SELECT * FROM tbl_companies WHERE company_name = '$cname'");
    while ($row = mysqli_fetch_assoc($sql)) {
        $company_id = $row['ID'];
    }


    $email = $_POST['email_address'];
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
    $file201 = '0';
    $certificate_request = '0';
    $generate_reports = '0';
    $application_management = '0';
    $leave_management = '0';
    $holiday_maintenance = '0';
    $generate_reports_emp = '0';
    $payroll_management = '0';




    if (isset($_POST['file201'])) {
        $file201 = $_POST['file201'];
    }
    if (isset($_POST['leave_management'])) {
        $leave_management = $_POST['leave_management'];
    }
    if (isset($_POST['certificate_request'])) {
        $certificate_request = $_POST['certificate_request'];
    }
    if (isset($_POST['holiday_maintenance'])) {
        $holiday_maintenance = $_POST['holiday_maintenance'];
    }
    if (isset($_POST['generate_reports'])) {
        $generate_reports = $_POST['generate_reports'];
    }
    if (isset($_POST['payroll_management'])) {
        $payroll_management = $_POST['payroll_management'];
    }
    if (isset($_POST['generate_reports_emp'])) {
        $generate_reports_emp = $_POST['generate_reports_emp'];
    }
    if (isset($_POST['application_management'])) {
        $application_management = $_POST['application_management'];
    }



    $sql = mysqli_query($db, "INSERT INTO tbl_users VALUES(
        0,
        '$company_id',
        '$employee_number',
        '$email',
        '$account_name',
        '$role',
        '$file201',
        '$certificate_request',
        '$generate_reports',
        '$application_management',
        '$leave_management',
        '$holiday_maintenance',
        '$generate_reports_emp',
        '$payroll_management',
        '$password',
        '$datetime',
        '0'
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
        mysqli_query($db, "UPDATE tbl_employees SET account_created = '1' WHERE emp_num = '$employee_number'");
    }
}
if (isset($_POST['btn_update_account'])) {
    $id = $_POST['id'];
    $email = $_POST['email'];
    $account_name = $_POST['account_name'];
    $role = $_POST['role'];
    $cid = $_POST['cid'];

    $file201 = '0';
    $certificate_request = '0';
    $generate_reports = '0';
    $application_management = '0';
    $leave_management = '0';
    $holiday_maintenance = '0';
    $generate_reports_emp = '0';
    $payroll_management = '0';

    if (isset($_POST['file201'])) {
        $file201 = $_POST['file201'];
    }
    if (isset($_POST['certificate_request'])) {
        $certificate_request = $_POST['certificate_request'];
    }
    if (isset($_POST['generate_reports'])) {
        $generate_reports = $_POST['generate_reports'];
    }
    if (isset($_POST['application_management'])) {
        $application_management = $_POST['application_management'];
    }
    if (isset($_POST['leave_management'])) {
        $leave_management = $_POST['leave_management'];
    }
    if (isset($_POST['holiday_maintenance'])) {
        $holiday_maintenance = $_POST['holiday_maintenance'];
    }
    if (isset($_POST['generate_reports_emp'])) {
        $generate_reports_emp = $_POST['generate_reports_emp'];
    }
    if (isset($_POST['payroll_management'])) {
        $payroll_management = $_POST['payroll_management'];
    }



    $sql = mysqli_query($db, "UPDATE tbl_users SET
        email = '$email',
        account_name = '$account_name',
        role = '$role',
        company_id = '$cid',
        file201 = '$file201',
        certificate_requests = '$certificate_request',
        generate_reports = '$generate_reports',
        application_management = '$application_management',
        leave_management = '$leave_management',
        holiday_maintenance = '$holiday_maintenance',
        generate_reports_emp = '$generate_reports_emp',
        payroll_management = '$payroll_management'
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

    header('Location: account-list');
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

    $sql = mysqli_query($db, "UPDATE tbl_users SET `password` = '$new_password' WHERE ID = '$id'");
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
    $ape = $_POST['ape'];
    $hp = $_POST['hp'];
    $bl = $_POST['bl'];
    $maternity = $_POST['maternity'];
    $paternity = $_POST['paternity'];
    $solo_parent = $_POST['solo_parent'];

    $sql = mysqli_query($db, "UPDATE tbl_leave_balances SET
    SL = '$sl',
    VL = '$vl',
    others = '$el',
    ape = '$ape',
    hp = '$hp',
    bl = '$bl',
    maternity = '$maternity',
    solo_parent = '$solo_parent',
    paternity = '$paternity'
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

    $attachment = $_FILES['dmg_attachment']['name'];
    $fileTmp = $_FILES['dmg_attachment']['tmp_name'];
    $initialExt = explode('.', $attachment);
    $fileExt = strtolower(end($initialExt));

    if (in_array($fileExt, $allowed)) {
        $info = pathinfo($attachment);
        $file_name =  $info['filename'];
        $dmgfile = $file_name . date("Ymd") . '.' . $fileExt;
        $target = 'uploads/' . $dmgfile;
        move_uploaded_file($fileTmp, $target);
    }



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
    $role = $_SESSION['hris_role'];
    if ($role == "User") {
        $emp_number = $_SESSION['hris_employee_number'];
        $sql = mysqli_query($db, "SELECT * FROM tbl_employees WHERE emp_num ='$employee_number'");
        while ($row = mysqli_fetch_assoc($sql)) {

            $employee_number = $row['emp_name'];
        }
    } else {
        // $get_employeenumber = $_POST['employee_number'];
        $sql = mysqli_query($db, "SELECT * FROM tbl_employees WHERE emp_name ='$employee_number'");
        while ($row = mysqli_fetch_assoc($sql)) {

            $emp_number = $row['emp_num'];
        }
    }


    $sql = mysqli_query($db, "INSERT INTO tbl_certificate_requests VALUES(
        '',
        '$company_id',
        '$emp_number',
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
        /*        include('phpMailer.php');
        certificateRequest($cert_id, $pers_info['company_email'], $pers_info['account_name'], $certificate_type, $employee_number);*/
        $at_name = $_SESSION['hris_account_name'];
        mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Submitted a Certificate Request','$datetime')");
        $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> Certificate Request for ' . $employee_number . ' has been submitted.</h4>
        </div>';
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
    $res = '<div class="alert alert-success alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h4><i class="fa fa-check"></i> Acknowledged Successfully </h4>
            </div>';
    // if ($sql) {
    //     include('phpMailer.php');
    //     certificateAcknowledge($id, get_personal_information($employee_number)['company_email'], get_personal_information($employee_number)['account_name'], $certificate_type, $hr_remarks, $employee_number);
    //     $at_name = $_SESSION['hris_account_name'];
    //     mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Acknowledged a Certificate Request: CR-$id','$datetime')");
    //     echo '<script>alert("Certificate Request has been acknowledged.");window.location.replace("certificate-request-list")</script>';
    // }


}

use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfReader;

if (isset($_POST['btn_download_certificate'])) {
    $certificate_type = $_POST['certificate_type'];

    // changes $at_name
    if ($certificate_type == "Certificate of Employment") {
        $id = $_POST['id'];
        // $at_name = $_POST['employee_name'];
        $requested_by = $_POST['employee_name'];
        $employee_number = $_POST['employee_number'];
        $certificate_type = $_POST['certificate_type'];
        $day_now = date('d');
        $month_now = date("F");
        $year_now = date("Y");
        $date_now = date("Y/m/d");

        $sql = mysqli_query($db, "SELECT * FROM tbl_employees WHERE emp_num = '$employee_number'");
        while ($row = mysqli_fetch_assoc($sql)) {
            $rid = $row['ID'];
            $position = $row['company_position'];
            $basic_salary = $row['basic_salary'];
        }

        $sql1 = mysqli_query($db, "SELECT * FROM tbl_employees_payslip WHERE emp_num = '$employee_number'");
        while ($row = mysqli_fetch_assoc($sql1)) {
            $rid = $row['ID'];
            $position = $row['company_position'];
        }

        $sql2 = mysqli_query($db, "SELECT * FROM tbl_personal_information WHERE employee_number = '$employee_number'");
        while ($row = mysqli_fetch_assoc($sql2)) {
            $rid = $row['ID'];
            $at_name = $row['account_name'];
            $lastname = $row['last_name'];
        }

        require_once('fpdf/fpdf.php');
        require_once('fpdi/src/autoload.php');

        $pdf = new fpdi();

        $pageCount = $pdf->setSourceFile('inc/certs/CertofEmployment.pdf');
        $pageId = $pdf->importPage(1, PdfReader\PageBoundaries::MEDIA_BOX);
        $pdf->addPage();
        $pdf->useTemplate($pageId, 0, 0, 210);
        // $pdf->useImportedPage($pageId, 10, 10, 100);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(168, 187, '' . $at_name . '', '0', 1, 'C');
        // -----
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(63, -177, '' . $position . '', '0', 0, 'C');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(3, -177, 'April 26', '0', 0, 'C');
        $pdf->Cell(42, -177, 'May 19', '0', 0, 'C');
        $pdf->Cell(72, -177, '' . $basic_salary . '', '0', 1, 'C');
        // -----
        $pdf->Cell(225, 195, '' . $lastname . '', '0', 1, 'C');
        // -----
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(66, -151, '' . $day_now . '', '0', 0, 'C');
        $pdf->Cell(-21, -151, '' . $month_now . '', '0', 0, 'C');
        $pdf->Cell(60, -151, '' . $year_now . '', '0', 1, 'C');
        // -----
        // $pdf->SetFont('Arial', 'B', 11);
        // $pdf->Cell(295, 204, ''.$date_now.'', '0', 1, 'C');
        // // -----
        // $pdf->Cell(295, -124, ''.$date_now.'', '0', 1, 'C');

        $pdf->Output('I', 'CertofEmployment.pdf');
    } else if ($certificate_type == "Certificate of Clearance") {
        $id = $_POST['id'];
        // $at_name = $_SESSION['hris_account_name'];
        $emp_name = $_POST['employee_name'];

        $employee_number = $_POST['employee_number'];
        $certificate_type = $_POST['certificate_type'];
        $day_now = date('d');
        $month_now = date("F");
        $year_now = date("Y");
        $date_now = date("Y/m/d");

        require_once('fpdf/fpdf.php');
        require_once('fpdi/src/autoload.php');

        $pdf = new fpdi();

        $pageCount = $pdf->setSourceFile('inc/certs/EmployeeClearance.pdf');
        $pageId = $pdf->importPage(1, PdfReader\PageBoundaries::MEDIA_BOX);
        $pdf->addPage();
        $pdf->useTemplate($pageId, 0, 0, 210);
        // $pdf->useImportedPage($pageId, 10, 10, 100);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(318, 122, '' . $date_now . '', '0', 1, 'C');
        // -----
        $pdf->Cell(175, -59, '' . $emp_name . '', '0', 1, 'C');
        // $pdf->SetFont('Arial', 'B', 8);
        // $pdf->Cell(63, -177, ''.$position.'', '0', 0, 'C');
        // $pdf->SetFont('Arial', 'B', 9);
        // $pdf->Cell(3, -177, 'April 26', '0', 0, 'C');
        // $pdf->Cell(42, -177, 'May 19', '0', 0, 'C');
        // $pdf->Cell(72, -177, ''.$basic_salary.'', '0', 1, 'C');
        // // -----
        // $pdf->Cell(225, 195, ''.$lastname.'', '0', 1, 'C');
        // // -----
        // $pdf->SetFont('Arial', 'B', 10);
        // $pdf->Cell(66, -151, ''.$day_now.'', '0', 0, 'C');
        // $pdf->Cell(-21, -151, ''.$month_now.'', '0', 0, 'C');
        // $pdf->Cell(60, -151, ''.$year_now.'', '0', 1, 'C');
        // // -----
        // $pdf->SetFont('Arial', 'B', 11);
        // $pdf->Cell(295, 204, ''.$date_now.'', '0', 1, 'C');
        // // -----
        // $pdf->Cell(295, -124, ''.$date_now.'', '0', 1, 'C');

        $pdf->Output('I', 'EmployeeClearance.pdf');
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


if (isset($_POST['cancellation'])) {
    $applied_by = $_POST['applied_by'];
    $la_emp_number = $_POST['la_emp_number'];
    $leaveapplication_id = $_POST['la_id'];
    $approver = $_SESSION['approver'];
    $approver_remarks = $_POST['la_approver_remarks'];
    $cancel_id = $_POST['cancel_id'];
    $status = "Cancelled";

    $sql = mysqli_query($db, "UPDATE tbl_leave_requests SET status = '$status', approver_remarks = '$approver_remarks' WHERE ID = '$leaveapplication_id'");
    $sql = mysqli_query($db, "UPDATE tbl_cancellation SET status = '$status' WHERE id = '$cancel_id'");

    $sql = mysqli_query($db, "SELECT * FROM tbl_personal_information WHERE employee_number = '$la_emp_number'");
    while ($row = mysqli_fetch_assoc($sql)) {
        $account_name = $row['account_name'];
    }
    $res = '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-check"></i> Leave Application for ' . $account_name . ' has been cancelled.</h4>
        </div>';
}
if (isset($_POST['btn_approve_leave_application'])) {

    $applied_by = $_POST['applied_by'];
    $leaveapplication_id = $_POST['la_id'];
    if (isset($_POST['late_filing_val'])) {
        $late_filing_val = $_POST['late_filing_val'];
    } else {
        $late_filing_val = '0';
    }
    // $leave_details = get_leave_details($leave_id);
    $date_filed = $_POST['la_application_date'];
    $leave_type = $_POST['la_leave_type'];
    $startDate = $_POST['la_startDate'];
    $endDate = $_POST['la_endDate'];
    $approver_remarks = $_POST['la_approver_remarks'];

    $total_days = $_POST['la_total_days'];
    $employee_number = $_POST['la_emp_number'];
    if (isset($_POST['next_approver'])) {
        $next_approver = $_POST['next_approver'];
    } else {
        $next_approver = '';
    }
    if ($_SESSION['hris_role'] == 'Admin') {
        $status = 'Approved';

        $sql = mysqli_query($db, "UPDATE tbl_leave_requests SET status = '$status', late_filing = '$late_filing_val', approver_remarks = '$approver_remarks' WHERE ID = '$leaveapplication_id'");
        $at_name = $_SESSION['hris_account_name'];

        $res = '<div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="fa fa-check"></i> Leave Application for ' . $applied_by . ' has been approved.</h4>
            </div>';

        $sql = mysqli_query($db, "SELECT * FROM tbl_leave_balances WHERE employee_number = '$employee_number'");
        while ($row = mysqli_fetch_assoc($sql)) {
            $rid = $row['ID'];
            $empnum = $row['employee_number'];
            $num_vl = $row['VL'];
            $num_sl = $row['SL'];
            $num_others = $row['others'];
            $num_ape = $row['ape'];
            $num_hp = $row['hp'];
            $num_bl = $row['bl'];
        }

        if ($leave_type == "SL") {
            $num_sick_leave = $num_sl;
            $num_sick_leave = $num_sick_leave - $total_days;
            if ($num_sick_leave < 0) {
                $num_sick_leave = 0;
            }
            $sql = mysqli_query($db, "UPDATE tbl_leave_balances SET SL = '$num_sick_leave' WHERE employee_number = '$empnum'");
        } else if ($leave_type == "VL") {
            $num_vacation_leave = $num_vl;
            $num_vacation_leave = $num_vacation_leave - $total_days;
            if ($num_vacation_leave < 0) {
                $num_vacation_leave = 0;
            }
            $sql = mysqli_query($db, "UPDATE tbl_leave_balances SET VL = '$num_vacation_leave' WHERE employee_number = '$empnum'");
        } else if ($leave_type == "others") {
            $num_others_leave = $num_others;
            $num_others_leave = $num_others_leave - $total_days;
            if ($num_others_leave < 0) {
                $num_others_leave = 0;
            }
            $sql = mysqli_query($db, "UPDATE tbl_leave_balances SET others = '$num_others_leave' WHERE employee_number = '$empnum'");
        } else if ($leave_type == "others - APE") {
            $num_ape_leave = $num_ape;
            $num_ape_leave = $num_ape_leave - $total_days;
            if ($num_ape_leave < 0) {
                $num_ape_leave = 0;
            }
            $sql = mysqli_query($db, "UPDATE tbl_leave_balances SET ape = '$num_ape_leave' WHERE employee_number = '$empnum'");
        } else if ($leave_type == "others - HP") {
            $num_hp_leave = $num_hp;
            $num_hp_leave = $num_hp_leave - $total_days;
            if ($num_hp_leave < 0) {
                $num_hp_leave = 0;
            }
            $sql = mysqli_query($db, "UPDATE tbl_leave_balances SET hp = '$num_hp_leave' WHERE employee_number = '$empnum'");
        } else if ($leave_type == "others - BL") {
            $num_bl_leave = $num_bl;
            $num_bl_leave = $num_bl_leave - $total_days;
            if ($num_bl_leave < 0) {
                $num_bl_leave = 0;
            }
            $sql = mysqli_query($db, "UPDATE tbl_leave_balances SET bl = '$num_bl_leave' WHERE employee_number = '$empnum'");
        } else if ($leave_type == "SLVL") {
            $num_slvl_leave = $num_vl + $num_sl;
            $num_slvl_leave = $num_slvl_leave - $total_days;
            if ($num_slvl_leave < 0) {
                $num_slvl_leave = 0;
            }
            $sql = mysqli_query($db, "UPDATE tbl_leave_balances SET SL = '$num_slvl_leave', VL = '$num_slvl_leave' WHERE employee_number = '$empnum'");
        }
    } else {
        if (isset($_POST['late_filing_val'])) {
            $late_filing_val = $_POST['late_filing_val'];
        } else {
            $late_filing_val = '0';
        }
        if ($next_approver == 'Manager') {
            $status = 'Manager Approval';
        } else if ($next_approver == 'HR Processing') {
            $status = 'HR Approval';
        } else {
            $status = 'Boss Approval';
        }

        mysqli_query($db, "UPDATE tbl_leave_requests SET status = '$status', late_filing = '$late_filing_val', approver_remarks = '$approver_remarks' WHERE ID = '$leaveapplication_id'");

        $res = '<div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="fa fa-check"></i> Leave Application has been proceeded to the next step: ' . $status . '</h4>
    </div>';
    }
}
if (isset($_POST['btn_generate_report'])) {
    $category = $_POST['category'];
    $company = $_POST['company'];
    $employment_status;


    if ($category == "Payslip") {
        if (isset($_POST['selected_cutoff'])) {
            $ID = $_POST['selected_cutoff'];
            $startDate;
            $endDate;
            $employee_name;
            $empnum;
            $job_title;
            $sss;
            $pagibig;
            $philhealth;
            $net_salary;
            $account_number;
            $tardiness;
            $absent;
            $holiday_pay;
            $commission;

            $sql1 = mysqli_query($db, "SELECT * FROM tbl_employees_payslip WHERE ID = '$ID'");
            while ($row = mysqli_fetch_assoc($sql1)) {
                $startDate = $row['date_from'];
                $endDate = $row['date_to'];
                $empnum = $row['emp_num'];
                $employee_name = $row['emp_name'];
                $job_title = $row['company_position'];
                $gross_pay = $row['gross_pay'];
                $wtax = $row['withholding_tax'];
                $sss = $row['sss'];
                $pagibig = $row['pagibig'];
                $philhealth = $row['philhealth'];
                $sss_er = $row['sss_er'];
                $sss_ec = $row['sss_ec'];
                // $philhealth_ee = $row['philhealth_ee'];
                $philhealth_er = $row['philhealth_er'];
                $net_salary = $row['net_salary'];
                $account_number = $row['account_number'];
                $deminimis = $row['deminimis'];
                $taxable_income = $row['taxable_income'];
                $tardiness = $row['tardiness'];
                $absent = $row['absent'];
                $holiday_pay = $row['holiday_pay'];
                $commission = $row['commission'];
            }
            $get_emp_status = mysqli_query($db, "SELECT * FROM tbl_employment_information WHERE employee_number = '$empnum'");
            while ($row = mysqli_fetch_assoc($get_emp_status)) {
                $employment_status = $row['employment_status'];
            }
            $employment_status = strtoupper($employment_status);

            $paid_leaves = '0.00';
            $company_loan = '0.00';
            $sss_loan = '0.00';
            $hdmf_loan = '0.00';
            $wis_sss = '0.00';
            $mpf_hdmf = '0.00';
            $overtime = '0.00';
            $incentive = '0.00';
            $tirteenth_month = '0.00';
            $others_adjustments = '0.00';
            $others_deductions = '0.00';
            $allowance = '0.00';
            $deductions = '0.00';


            $sql = mysqli_query($db, "SELECT * FROM tbl_payroll_adjustments WHERE (emp_num = '$empnum') AND (type_adjustment = 'Earning') AND (date_from = '$startDate' AND date_to = '$endDate')");
            while ($row = mysqli_fetch_assoc($sql)) {
                if ($row['a_description'] == "13TH MONTH") {
                    $tirteenth_month += $row['amount'];
                } else if ($row['a_description'] == "INCENTIVE") {
                    $incentive += $row['amount'];
                } else if ($row['a_description'] == "PAID LEAVES") {
                    $paid_leaves += $row['amount'];
                } else if ($row['a_description'] == "OVERTIME") {
                    $overtime += $row['amount'];
                } else {
                    $others_adjustments += $row['amount'];
                }
                $allowance += $row['amount'];
            }

            $sql = mysqli_query($db, "SELECT * FROM tbl_payroll_adjustments WHERE (emp_num = '$empnum') AND (type_adjustment = 'Deduction') AND (date_from = '$startDate' AND date_to = '$endDate')");
            while ($row = mysqli_fetch_assoc($sql)) {
                if ($row['a_description'] == "CO. LOAN") {
                    $company_loan += $row['amount'];
                } else if ($row['a_description'] == "SSS LOAN") {
                    $sss_loan += $row['amount'];
                } else if ($row['a_description'] == "HMDF LOAN") {
                    $hdmf_loan += $row['amount'];
                } else if ($row['a_description'] == "WIS SSS") {
                    $wis_sss += $row['amount'];
                } else if ($row['a_description'] == "MPF HDMF") {
                    $mpf_hdmf += $row['amount'];
                } else {
                    $others_deductions += $row['amount'];
                }
                $deductions += $row['amount'];
            }

            $year = date("Y");
            $temp = explode('-', $startDate);
            $year_presented = $temp[0];
            $year_gross_income = '0.00';
            $year_taxable_income = '0.00';
            $year_net_pay = '0.00';
            $year_allowance = '0.00';
            $year_deduction = '0.00';
            $year_philhealth = '0.00';
            $year_pagibig = '0.00';
            $year_sss = '0.00';
            $year_whtax = '0.00';
            $year_sss_er = '0.00';
            $year_sss_ec = '0.00';
            $sql = mysqli_query($db, "SELECT * FROM tbl_employees_payslip WHERE emp_num = '$empnum'");
            while ($row = mysqli_fetch_assoc($sql)) {
                if ($year_presented == $year) {
                    $year_gross_income += $row['gross_pay'];
                    $year_taxable_income += $row['taxable_income'];
                    $year_net_pay += $row['net_salary'];
                    $year_philhealth += $row['philhealth'];
                    $year_pagibig += $row['pagibig'];
                    $year_sss += $row['sss'];
                    $year_sss_er += $row['sss_er'];
                    $year_sss_ec += $row['sss_ec'];
                    $year_whtax += $row['withholding_tax'];
                    $year_allowance += $row['deminimis'] + $row['holiday_pay'];
                    $year_deduction += $row['philhealth'] + $row['pagibig'] + $row['sss'] + $row['tardiness'] + $row['withholding_tax'] + $row['absent'];
                }
            }

            $sql = mysqli_query($db, "SELECT * FROM tbl_payroll_adjustments WHERE emp_num = '$empnum'");
            while ($row = mysqli_fetch_assoc($sql)) {
                if ($year_presented == $year) {
                    if ($row['type_adjustment'] == 'Earning') {
                        $year_allowance += $row['amount'];
                    } else {
                        $year_deduction += $row['amount'];
                    }
                }
            }

            $allowance += $deminimis + $holiday_pay;
            $deductions += $wtax + $philhealth + $pagibig + $sss + $tardiness + $absent;
            $basic_pay = $gross_pay * 2;


            require('FPDF/fpdf.php');
            require_once('fpdf/fpdf.php');
            require_once('fpdi/src/autoload.php');

            $pdf = new fpdi();

            $pageCount = $pdf->setSourceFile('inc/certs/Payslip_format.pdf');
            $pageId = $pdf->importPage(1, PdfReader\PageBoundaries::MEDIA_BOX);
            $pdf->addPage();
            $pdf->useTemplate($pageId, 0, 0, 210);

            // $pdf->MultiCell(0,5,utf8_decode($variable1 . chr(10) . $variable2),1);
            $pdf->SetFont('Arial', 'B', 12);
            // $pdf->Cell( 40, 40, $pdf->Image($image1, $pdf->GetX(), $pdf->GetY(), 33.78), 0, 0, 'L', false );
            $pdf->Cell(140, 45, 'DEMO', 'LRT', 0, 'L');
            $pdf->Cell(50, 23, '', 'TR', 1, 'L');

            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(65, 8, 'PAYSLIP - SEMI-MONTHLY PAYROLL', 'L', 0, 'L');
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(75, 8, 'PERIOD: ' . $startDate . ' - ' . $endDate, 'R', 0, 'C');
            $pdf->Cell(50, 8, '', 'R', 1, 'L');

            $pdf->Cell(140, 4, '', 1, 0, 'L');
            $pdf->Cell(50, 4, '', 'R', 1, 'L');

            // -----------------------------

            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(80, 6, 'EMPLOYEE: ' . strtoupper($employee_name), 1, 0, 'L');
            $pdf->Cell(60, 6, 'STATUS: ' . $employment_status, 1, 0, 'C');

            $pdf->Cell(20, 6, 'BASIC PAY: ', 'B', 0, 'L');
            $pdf->Cell(30, 6, number_format($basic_pay, 2, ".", ","), 'BR', 1, 'R');

            // ------------------------------

            $pdf->Cell(140, 6, 'POSITION: ' . strtoupper($job_title), 1, 0, 'L');
            $pdf->Cell(50, 6, '', 1, 1, 'L');

            // -----------------------------

            $pdf->Cell(20, 6, 'OVERTIME', 'LB', 0, 'C');
            $pdf->Cell(10, 6, 'MIN', 'B', 0, 'C');
            $pdf->Cell(20, 6, 'PAY', 'RB', 0, 'C');

            $pdf->Cell(30, 6, 'ADJUSTMENTS', 'LB', 0, 'C');
            $pdf->Cell(20, 6, 'AMOUNT', 'RB', 0, 'C');

            $pdf->Cell(20, 6, 'DEDUCTION', 'LB', 0, 'C');
            $pdf->Cell(20, 6, 'AMOUNT', 'RB', 0, 'C');

            $pdf->Cell(20, 6, 'OVERTIME: ', '', 0, 'L');
            $pdf->Cell(30, 6, number_format($overtime, 2, ".", ","), 'R', 1, 'R');

            // -----------------------------

            $pdf->Cell(20, 6, 'REGULAR', 'L', 0, 'L');
            $pdf->Cell(10, 6, '0', 0, 0, 'C');
            $pdf->Cell(20, 6, number_format($overtime, 2, ".", ","), 'R', 0, 'R');

            $pdf->Cell(30, 6, '13TH MONTH', 'L', 0, 'L');
            $pdf->Cell(20, 6, number_format($tirteenth_month, 2, ".", ","), 'R', 0, 'R');

            $pdf->Cell(20, 6, 'W/H TAX', 'L', 0, 'L');
            $pdf->Cell(20, 6, number_format($wtax, 2, ".", ","), 'R', 0, 'R');

            $pdf->Cell(20, 6, '13TH MONTH: ', '', 0, 'L');
            $pdf->Cell(30, 6, number_format($tirteenth_month, 2, ".", ","), 'R', 1, 'R');

            // -------------------------------


            $pdf->Cell(20, 6, '', 'L', 0, 'C');
            $pdf->Cell(10, 6, '', 0, 0, 'C');
            $pdf->Cell(20, 6, '', 'R', 0, 'R');

            $pdf->Cell(30, 6, 'INCENTIVE', 'L', 0, 'L');
            $pdf->Cell(20, 6, number_format($incentive, 2, ".", ","), 'R', 0, 'R');

            $pdf->Cell(20, 6, 'SSS', 'L', 0, 'L');
            $pdf->Cell(20, 6, number_format($sss, 2, ".", ","), 'R', 0, 'R');

            $pdf->Cell(20, 6, 'ALLOWANCE: ', '', 0, 'L');
            $pdf->Cell(30, 6, number_format($allowance, 2, ".", ","), 'R', 1, 'R');

            // --------------------------------

            $pdf->Cell(20, 6, '', 'L', 0, 'L');
            $pdf->Cell(10, 6, '', 0, 0, 'C');
            $pdf->Cell(20, 6, '', 'R', 0, 'R');

            $pdf->Cell(30, 6, 'PAID LEAVES', 'L', 0, 'L');
            $pdf->Cell(20, 6, number_format($paid_leaves, 2, ".", ","), 'R', 0, 'R');

            $pdf->Cell(20, 6, 'PHILHEALTH', 'L', 0, 'L');
            $pdf->Cell(20, 6, number_format($philhealth, 2, ".", ","), 'R', 0, 'R');

            $pdf->Cell(20, 6, '', '', 0, 'L');
            $pdf->Cell(30, 6, '', 'R', 1, 'R');

            // -----------------------------

            $pdf->Cell(20, 6, '', 'L', 0, 'L');
            $pdf->Cell(10, 6, '', 0, 0, 'C');
            $pdf->Cell(20, 6, '', 'R', 0, 'R');

            $pdf->Cell(30, 6, 'HOLIDAY PAY', 'L', 0, 'L');
            $pdf->Cell(20, 6, number_format($holiday_pay, 2, ".", ","), 'R', 0, 'R');

            $pdf->Cell(20, 6, 'PAG-IBIG', 'L', 0, 'L');
            $pdf->Cell(20, 6, number_format($pagibig, 2, ".", ","), 'R', 0, 'R');

            $pdf->Cell(20, 6, 'GROSS PAY:', '', 0, 'L');
            $pdf->Cell(30, 6, number_format($gross_pay, 2, ".", ","), 'R', 1, 'R');

            // ------------------------------

            $pdf->Cell(20, 6, '', 'L', 0, 'L');
            $pdf->Cell(10, 6, '', 0, 0, 'C');
            $pdf->Cell(20, 6, '', 'R', 0, 'R');

            $pdf->Cell(30, 6, 'DEMINIMIS', 'L', 0, 'L');
            $pdf->Cell(20, 6, number_format($deminimis, 2, ".", ","), 'R', 0, 'R');

            $pdf->Cell(20, 6, 'TARDINESS', 'L', 0, 'L');
            $pdf->Cell(20, 6, number_format($tardiness, 2, ".", ","), 'R', 0, 'R');

            $pdf->Cell(20, 6, 'DEDUCTION:', '', 0, 'L');
            $pdf->Cell(30, 6, number_format($deductions, 2, ".", ","), 'R', 1, 'R');

            // -------------------------------

            $pdf->Cell(20, 6, '', 'L', 0, 'L');
            $pdf->Cell(10, 6, '', 0, 0, 'C');
            $pdf->Cell(20, 6, '', 'R', 0, 'R');

            $pdf->Cell(30, 6, 'COMMISSION', 'L', 0, 'L');
            $pdf->Cell(20, 6, number_format($commission, 2, ".", ","), 'R', 0, 'R');

            $pdf->Cell(20, 6, 'ABSENT', 'L', 0, 'L');
            $pdf->Cell(20, 6, number_format($absent, 2, ".", ","), 'R', 0, 'R');

            $pdf->Cell(20, 6, '', '', 0, 'L');
            $pdf->Cell(30, 6, '', 'R', 1, 'R');

            // -----------------------------------

            $pdf->Cell(20, 6, '', 'L', 0, 'L');
            $pdf->Cell(10, 6, '', 0, 0, 'C');
            $pdf->Cell(20, 6, '', 'R', 0, 'R');

            $pdf->Cell(30, 6, 'OTHERS', 'L', 0, 'L');
            $pdf->Cell(20, 6, number_format($others_adjustments, 2, ".", ","), 'R', 0, 'R');

            $pdf->Cell(20, 6, 'CO.LOAN', 'L', 0, 'L');
            $pdf->Cell(20, 6, number_format($company_loan, 2, ".", ","), 'R', 0, 'R');

            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 6, 'NET PAY:', '', 0, 'L');
            $pdf->Cell(30, 6, number_format($net_salary, 2, ".", ","), 'R', 1, 'R');

            // --------------------------------

            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(20, 6, '', 'L', 0, 'L');
            $pdf->Cell(10, 6, '', 0, 0, 'C');
            $pdf->Cell(20, 6, '', 'R', 0, 'R');

            $pdf->Cell(30, 6, '', 'L', 0, 'L');
            $pdf->Cell(20, 6, '', 'R', 0, 'R');

            $pdf->Cell(20, 6, 'SSS LOAN', 'L', 0, 'L');
            $pdf->Cell(20, 6, number_format($sss_loan, 2, ".", ","), 'R', 0, 'R');

            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 6, 'RECEIVED BY:', '', 0, 'L');
            $pdf->Cell(30, 6, '', 'R', 1, 'R');

            // --------------------------------

            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(20, 6, '', 'L', 0, 'L');
            $pdf->Cell(10, 6, '', 0, 0, 'C');
            $pdf->Cell(20, 6, '', 'R', 0, 'R');

            $pdf->Cell(30, 6, '', 'L', 0, 'L');
            $pdf->Cell(20, 6, '', 'R', 0, 'R');

            $pdf->Cell(20, 6, 'HMDF LOAN', 'L', 0, 'L');
            $pdf->Cell(20, 6, number_format($hdmf_loan, 2, ".", ","), 'R', 0, 'R');

            $pdf->Cell(20, 6, '', '', 0, 'L');
            $pdf->Cell(30, 6, '', 'R', 1, 'R');

            // -------------------------------

            $pdf->Cell(20, 6, '', 'L', 0, 'L');
            $pdf->Cell(10, 6, '', 0, 0, 'C');
            $pdf->Cell(20, 6, '', 'R', 0, 'R');

            $pdf->Cell(30, 6, '', 'L', 0, 'L');
            $pdf->Cell(20, 6, '', 'R', 0, 'R');

            $pdf->Cell(20, 6, 'WIS SSS', 'L', 0, 'L');
            $pdf->Cell(20, 6, number_format($wis_sss, 2, ".", ","), 'R', 0, 'R');

            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(20, 6, 'YEAR-TO-DATE FIGURES', 'T', 0, 'L');
            $pdf->Cell(30, 6, '', 'TR', 1, 'R');

            // ---------------------------------

            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(20, 6, '', 'L', 0, 'L');
            $pdf->Cell(10, 6, '', 0, 0, 'C');
            $pdf->Cell(20, 6, '', 'R', 0, 'R');

            $pdf->Cell(30, 6, '', 'L', 0, 'L');
            $pdf->Cell(20, 6, '', 'R', 0, 'R');

            $pdf->Cell(20, 6, 'MPF-HDMF', 'L', 0, 'L');
            $pdf->Cell(20, 6, number_format($mpf_hdmf, 2, ".", ","), 'R', 0, 'R');

            $pdf->Cell(20, 6, 'GROSS INCOME', '', 0, 'L');
            $pdf->Cell(30, 6, number_format($year_gross_income, 2, ".", ","), 'R', 1, 'R');

            // --------------------------------

            $pdf->Cell(20, 6, '', 'L', 0, 'L');
            $pdf->Cell(10, 6, '', 0, 0, 'C');
            $pdf->Cell(20, 6, '', 'R', 0, 'R');

            $pdf->Cell(30, 6, '', 'L', 0, 'L');
            $pdf->Cell(20, 6, '', 'R', 0, 'R');

            $pdf->Cell(20, 6, 'OTHERS', 'L', 0, 'L');
            $pdf->Cell(20, 6, number_format($others_deductions, 2, ".", ","), 'R', 0, 'R');

            $pdf->Cell(20, 6, 'TAXABLE INCOME', '', 0, 'L');
            $pdf->Cell(30, 6, number_format($year_taxable_income, 2, ".", ","), 'R', 1, 'R');

            // -------------------------------

            $pdf->Cell(20, 6, '', 'L', 0, 'L');
            $pdf->Cell(10, 6, '', 0, 0, 'C');
            $pdf->Cell(20, 6, '', 'R', 0, 'R');

            $pdf->Cell(30, 6, '', 'L', 0, 'L');
            $pdf->Cell(20, 6, '', 'R', 0, 'R');

            $pdf->Cell(20, 6, '', 'L', 0, 'L');
            $pdf->Cell(20, 6, '', 'R', 0, 'R');

            $pdf->Cell(20, 6, 'NET PAY', '', 0, 'L');
            $pdf->Cell(30, 6, number_format($year_net_pay, 2, ".", ","), 'R', 1, 'R');

            // -------------------------------

            $pdf->Cell(20, 6, '', 'L', 0, 'L');
            $pdf->Cell(10, 6, '', 0, 0, 'C');
            $pdf->Cell(20, 6, '', 'R', 0, 'R');

            $pdf->Cell(30, 6, '', 'L', 0, 'L');
            $pdf->Cell(20, 6, '', 'R', 0, 'R');

            $pdf->Cell(20, 6, '', 'L', 0, 'L');
            $pdf->Cell(20, 6, '', 'R', 0, 'R');

            $pdf->Cell(20, 6, 'ALLOWANCE', '', 0, 'L');
            $pdf->Cell(30, 6, number_format($year_allowance, 2, ".", ","), 'R', 1, 'R');

            // ---------------------------------

            $pdf->Cell(20, 6, '', 'L', 0, 'L');
            $pdf->Cell(10, 6, '', 0, 0, 'C');
            $pdf->Cell(20, 6, '', 'R', 0, 'R');

            $pdf->Cell(30, 6, '', 'L', 0, 'L');
            $pdf->Cell(20, 6, '', 'R', 0, 'R');

            $pdf->Cell(20, 6, '', 'L', 0, 'L');
            $pdf->Cell(20, 6, '', 'R', 0, 'R');

            $pdf->Cell(20, 6, 'DEDUCTION', '', 0, 'L');
            $pdf->Cell(30, 6, number_format($year_deduction, 2, ".", ","), 'R', 1, 'R');

            // --------------------------------

            $pdf->Cell(20, 6, '', 'L', 0, 'L');
            $pdf->Cell(10, 6, '', 0, 0, 'C');
            $pdf->Cell(20, 6, '', 'R', 0, 'R');

            $pdf->Cell(30, 6, '', 'L', 0, 'L');
            $pdf->Cell(20, 6, '', 'R', 0, 'R');

            $pdf->Cell(20, 6, '', 'L', 0, 'L');
            $pdf->Cell(20, 6, '', 'R', 0, 'R');

            $pdf->Cell(20, 6, 'SSS EMPLOYER', '', 0, 'L');
            $pdf->Cell(30, 6, number_format($year_sss_er, 2, ".", ","), 'R', 1, 'R');

            // -------------------------------

            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(20, 6, '', 'L', 0, 'L');
            $pdf->Cell(10, 6, '', 0, 0, 'C');
            $pdf->Cell(20, 6, '', 'R', 0, 'R');

            $pdf->Cell(30, 6, '', 'L', 0, 'L');
            $pdf->Cell(20, 6, '', 'R', 0, 'R');

            $pdf->Cell(20, 6, '', 'L', 0, 'L');
            $pdf->Cell(20, 6, '', 'R', 0, 'R');

            $pdf->Cell(20, 6, 'SSS EC EMPLOYER', '', 0, 'L');
            $pdf->Cell(30, 6, number_format($year_sss_ec, 2, ".", ","), 'R', 1, 'R');

            // ---------------------------------

            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(20, 6, '', 'L', 0, 'L');
            $pdf->Cell(10, 6, '', 0, 0, 'C');
            $pdf->Cell(20, 6, '', 'R', 0, 'R');

            $pdf->Cell(30, 6, '', 'L', 0, 'L');
            $pdf->Cell(20, 6, '', 'R', 0, 'R');

            $pdf->Cell(20, 6, '', 'L', 0, 'L');
            $pdf->Cell(20, 6, '', 'R', 0, 'R');

            $pdf->Cell(20, 6, 'PHILHEALTH', '', 0, 'L');
            $pdf->Cell(30, 6, number_format($year_philhealth, 2, ".", ","), 'R', 1, 'R');

            // -------------------------------


            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(20, 6, '', 'L', 0, 'L');
            $pdf->Cell(10, 6, '', '', 0, 'C');
            $pdf->Cell(20, 6, '', 'R', 0, 'R');

            $pdf->Cell(30, 6, '', 'L', 0, 'L');
            $pdf->Cell(20, 6, '', 'R', 0, 'R');

            $pdf->Cell(20, 6, '', 'L', 0, 'L');
            $pdf->Cell(20, 6, '', 'R', 0, 'R');

            $pdf->Cell(20, 6, 'PAGIBIG', '', 0, 'L');
            $pdf->Cell(30, 6, number_format($year_pagibig, 2, ".", ","), 'R', 1, 'R');


            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(20, 6, '', 'LB', 0, 'L');
            $pdf->Cell(10, 6, '', 'B', 0, 'C');
            $pdf->Cell(20, 6, '', 'RB', 0, 'R');

            $pdf->Cell(30, 6, '', 'LB', 0, 'L');
            $pdf->Cell(20, 6, '', 'RB', 0, 'R');

            $pdf->Cell(20, 6, '', 'LB', 0, 'L');
            $pdf->Cell(20, 6, '', 'RB', 0, 'R');

            $pdf->Cell(20, 6, 'TAX', 'B', 0, 'L');
            $pdf->Cell(30, 6, number_format($year_whtax, 2, ".", ","), 'RB', 1, 'R');



            $pdf->Output();

            $startDate = $_POST['start_date'];
            $endDate = $_POST['end_date'];
            $at_name = $_SESSION['hris_account_name'];
            mysqli_query($db, "INSERT INTO tbl_audit_trail VALUES('','$at_name','Generated a Report for $category','$datetime')");
        }
    } else {
        $res = '<div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="fa fa-times"></i>No cut-off selected.</h4>
            </div>';
    }

    $sql = '';
    $headers = '';
    $data = array();

    if ($category == "Leave Applications") {
        $startDate = $_POST['start_date'];
        $endDate = $_POST['end_date'];
        $sql = mysqli_query($db, "SELECT * FROM tbl_leave_requests
            WHERE date_filed BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59' AND company_id = '$company'
            ORDER BY ID DESC");

        $headers = array(
            'ID',
            'Company ID',
            'Requestor',
            'Delegated Employee',
            'Employee Name',
            'Leave Type',
            'Start Date',
            'End Date',
            'Total Day',
            'Reason',
            'Duration',
            'Arrachment',
            'Approver ID',
            'Approver Remarks',
            'Status',
            'Date Filed',
        );
    }
    if ($category == "OT Applications") {
        $startDate = $_POST['start_date'];
        $endDate = $_POST['end_date'];
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
        $startDate = $_POST['start_date'];
        $endDate = $_POST['end_date'];
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
        $startDate = $_POST['start_date'];
        $endDate = $_POST['end_date'];
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
            t1.employment_status,
            t1.account_status,
            t1.approver,
            t1.reporting_to,
            t1.vendor_id,
            t1.filing,
            t1.is_approver
            FROM tbl_personal_information t
            INNER JOIN tbl_employment_information t1
            ON t.employee_number = t1.employee_number
            INNER JOIN tbl_companies t2
            ON t1.company = t2.ID
            INNER JOIN tbl_departments t3
            ON t1.department = t3.ID
            WHERE t.date_created BETWEEN '$startDate' AND '$endDate' AND t1.company = '$company'
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
            'Employment Status',
            'Account Status',
            'Approver',
            'Reporting to',
            'Vendor ID',
            'Filing',
            'Is Approver'
        );
        $id_exist = array();
        $educ_exist = array();
        $contact_exist = array();
        while ($row = mysqli_fetch_assoc($sql)) {

            $data[] = $row;
        }
    }
    if ($category == "Salary Loan Application") {
        $startDate = $_POST['start_date'];
        $endDate = $_POST['end_date'];
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
        $startDate = $_POST['start_date'];
        $endDate = $_POST['end_date'];
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
    $db = connect();
    $sql = mysqli_query($db, "SELECT * FROM tbl_training_approvers WHERE `user_id` = '$user_id'");
    if (mysqli_num_rows($sql) > 0) {
        $value = '1';
    }
    return $value;
}
function get_users()
{
    $val = array();
    $db = connect();
    $sql = mysqli_query($db, "SELECT * FROM `tbl_users`");
    while ($row = mysqli_fetch_assoc($sql)) {
        $val[] = $row;
    }
    return $val;
}
function get_user_details($user_id)
{
    $val = '';
    $db = connect();
    $sql = mysqli_query($db, "SELECT * FROM `tbl_users` WHERE ID = '$user_id'");
    while ($row = mysqli_fetch_assoc($sql)) {
        $val = $row;
    }
    return $val;
}
function get_benefits_pending_count($user_id, $company_id)
{
    $position_id = '';
    $db = connect();
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
    $db = connect();
    $sql = mysqli_query($db, "SELECT COUNT(*) as c FROM tbl_leave_requests WHERE approver = '$employee_number' AND `status` = 'Pending'");
    $count_requests = mysqli_fetch_assoc($sql);

    return $count_requests['c'];
}
function get_pending_ot_count($employee_number)
{
    $db = connect();
    $sql = mysqli_query($db, "SELECT COUNT(*) as c FROM tbl_ot_application WHERE approver = '$employee_number' AND `status` = 'Pending'");
    $count_requests = mysqli_fetch_assoc($sql);

    return $count_requests['c'];
}
function get_pending_certificate_count($user_id, $company_id)
{
    $data = 0;
    $db = connect();

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
    $db = connect();
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
    $db = connect();
    $sql = mysqli_query($db, "SELECT * FROM `tbl_leave_maintenance` WHERE company_id = '$company_id' AND jgs_id = '$job_grade_set_id'");
    if (mysqli_num_rows($sql) > 0) {
        $val = '1';
    }
    return $val;
}
function check_if_benefits_maintenance_exist($company_id, $job_grade_set_id)
{
    $val = '0';
    $db = connect();
    $sql = mysqli_query($db, "SELECT * FROM `tbl_benefits_maintenance` WHERE company_id = '$company_id' AND jgs_id = '$job_grade_set_id'");
    if (mysqli_num_rows($sql) > 0) {
        $val = '1';
    }
    return $val;
}
function get_permissions($user_id)
{
    $val = '';
    $db = connect();
    $sql = mysqli_query($db, "SELECT * FROM `tbl_users` WHERE ID = '$user_id'");
    while ($row = mysqli_fetch_assoc($sql)) {
        $val = $row;
    }
    return $val;
}
function get_certificate_request_approvers($company_id)
{
    $db = connect();
    $approver = array();
    $sql = mysqli_query($db, "SELECT * FROM tbl_certificate_requests_approvers WHERE company_id = '$company_id'");
    while ($row = mysqli_fetch_assoc($sql)) {
        $approver[] = $row;
    }
    return $approver;
}
function get_training_approvers($company_id)
{
    $db = connect();
    $approver = array();
    $sql = mysqli_query($db, "SELECT * FROM tbl_training_approvers WHERE company_id = '$company_id'");
    while ($row = mysqli_fetch_assoc($sql)) {
        $approver[] = $row;
    }
    return $approver;
}
function get_salary_loan_role_name($role_id)
{
    $db = connect();
    $role = '';
    $sql = mysqli_query($db, "SELECT * FROM tbl_loan_approvers_role WHERE position = '$role_id'");
    while ($row = mysqli_fetch_assoc($sql)) {
        $role = $row;
    }
    return $role;
}
function get_salary_loan_role_name_by_ID($role_id)
{
    $db = connect();
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
    $db = connect();
    $sql = mysqli_query($db, "SELECT * FROM tbl_benefits_gasoline_details WHERE benefits_id = '$benefits_id'");
    $val = mysqli_fetch_assoc($sql);
    $val = $val['requested_liters'];
    return $val;
}
function deduct_benefits_amount($benefits_id, $amount, $category)
{
    $db = connect();
    mysqli_query($db, "UPDATE tbl_benefits_reimbursement SET amount = amount - $amount WHERE ID = '$benefits_id'");
    mysqli_query($db, "UPDATE tbl_benefits_total_amount SET total_amount = total_amount - $amount WHERE benefits_id = '$benefits_id' AND cat = '$category'");
}
function add_benefits_amount($benefits_id, $amount, $category)
{
    $db = connect();
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
    $db = connect();
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
    $db = connect();
    $role = '';
    $sql = mysqli_query($db, "SELECT * FROM tbl_benefits_approvers_role WHERE position = '$role_id'");
    while ($row = mysqli_fetch_assoc($sql)) {
        $role = $row;
    }
    return $role;
}
function get_benefits_role_name_by_ID($role_id)
{
    $db = connect();
    $role = '';
    $sql = mysqli_query($db, "SELECT * FROM tbl_benefits_approvers_role WHERE ID = '$role_id'");
    while ($row = mysqli_fetch_assoc($sql)) {
        $role = $row;
    }
    return $role;
}
function get_benefits_role($company_id)
{
    $db = connect();
    $roles = array();
    $sql = mysqli_query($db, "SELECT * FROM tbl_benefits_approvers_role WHERE company_id = '$company_id' ORDER BY ID DESC");
    while ($row = mysqli_fetch_assoc($sql)) {
        $roles[] = array('ID' => $row['ID'], 'role' => $row['role']);
    }
    return $roles;
}
function get_salary_loan_role($company_id)
{
    $db = connect();
    $roles = array();
    $sql = mysqli_query($db, "SELECT * FROM tbl_loan_approvers_role WHERE company_id = '$company_id' ORDER BY ID DESC");
    while ($row = mysqli_fetch_assoc($sql)) {
        $roles[] = array('ID' => $row['ID'], 'role' => $row['role']);
    }
    return $roles;
}
function get_last_approver_loan($company_id)
{
    $db = connect();
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
    $db = connect();
    $sql = mysqli_query($db, "SELECT * FROM tbl_loan_application WHERE ID = '$loan_id'");
    while ($row = mysqli_fetch_assoc($sql)) {
        $val = $row;
    }
    return $val;
}
function get_last_approver($company_id)
{
    $db = connect();
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
    $db = connect();
    $sql = mysqli_query($db, "SELECT * FROM tbl_benefits_reimbursement WHERE ID = '$benefits_id'");
    while ($row = mysqli_fetch_assoc($sql)) {
        $val = $row;
    }
    return $val;
}
function get_approvers_from_role($position, $company_id)
{
    $val = '';
    $db = connect();
    $sql = mysqli_query($db, "SELECT * FROM tbl_benefits_approvers_role WHERE company_id = '$company_id' AND `position` = '$position'");
    while ($row = mysqli_fetch_assoc($sql)) {
        $val = $row;
    }
    return $val;
}
function get_approvers_from_role_loan($position, $company_id)
{
    $val = '';
    $db = connect();
    $sql = mysqli_query($db, "SELECT * FROM tbl_loan_approvers_role WHERE company_id = '$company_id' AND `position` = '$position'");
    while ($row = mysqli_fetch_assoc($sql)) {
        $val = $row;
    }
    return $val;
}
function get_benefits_approvers($company_id, $role_id)
{
    $val = array();
    $db = connect();
    $sql = mysqli_query($db, "SELECT * FROM tbl_benefits_approvers WHERE company_id = '$company_id' AND `role` = '$role_id'");
    while ($row = mysqli_fetch_assoc($sql)) {
        $val[] = $row;
    }
    return $val;
}
function get_salary_loan_approvers($company_id, $role_id)
{
    $val = array();
    $db = connect();
    $sql = mysqli_query($db, "SELECT * FROM tbl_loan_approvers WHERE company_id = '$company_id' AND `role` = '$role_id'");
    while ($row = mysqli_fetch_assoc($sql)) {
        $val[] = $row;
    }
    return $val;
}
function get_benefits_approver_role($company_id, $email)
{
    $val = '';
    $db = connect();
    $sql = mysqli_query($db, "SELECT * FROM tbl_benefits_approvers WHERE email = '$email' AND company_id = '$company_id'");
    while ($row = mysqli_fetch_assoc($sql)) {
        $val = $row['role'];
    }
    return $val;
}
function get_benefits_balances($employee_number)
{
    $db = connect();
    $balances = array();

    $get_car_balance = mysqli_query($db, "SELECT * FROM tbl_benefits_car_balance WHERE employee_number = '$employee_number'");
    $car_balance = mysqli_fetch_assoc($get_car_balance);
    // $balances[] = array('car_maintenance' => $car_balance['balance']);

    $get_cep_balance = mysqli_query($db, "SELECT * FROM tbl_benefits_cep_balance WHERE employee_number = '$employee_number'");
    $cep_balance = mysqli_fetch_assoc($get_cep_balance);
    // $balances[] = array('cep' => $cep_balance['balance']);

    $get_gas_balance = mysqli_query($db, "SELECT * FROM tbl_benefits_gas_balance WHERE employee_number = '$employee_number'");
    $gas_balance = mysqli_fetch_assoc($get_gas_balance);
    // $balances[] = array('gas' => $gas_balance['balance']);

    $get_gym_balance = mysqli_query($db, "SELECT * FROM tbl_benefits_gym_balance WHERE employee_number = '$employee_number'");
    $gym_balance = mysqli_fetch_assoc($get_gym_balance);
    // $balances[] = array('gym' =>  $gym_balance['balance']);

    $get_medical_balance = mysqli_query($db, "SELECT * FROM tbl_benefits_medical_balance WHERE employee_number = '$employee_number'");
    $medical_balance = mysqli_fetch_assoc($get_medical_balance);
    // $balances[] = array('medical' => $medical_balance['balance']);

    $get_optical_balance = mysqli_query($db, "SELECT * FROM tbl_benefits_optical_balance WHERE employee_number = '$employee_number'");
    $optical_balance = mysqli_fetch_assoc($get_optical_balance);
    // $balances[] = array('optical' => $optical_balance['balance']);

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
    $db = connect();
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
    $db = connect();
    $sql = mysqli_query($db, "SELECT * FROM tbl_certificate_requests_approvers WHERE `user_id` = '$user_id'");
    if (mysqli_num_rows($sql) > 0) {
        $value = '1';
    }
    return $value;
}
function get_employees_from_company($company_id)
{
    $db = connect();
    $employees = array();
    $sql = mysqli_query($db, "SELECT * FROM tbl_employment_information WHERE company = '$company_id' ORDER BY ID DESC");
    while ($row = mysqli_fetch_assoc($sql)) {
        $employees[] = array('employee_number' => $row['employee_number']);
    }
    return $employees;
}
function get_leave_details($leave_id)
{
    $db = connect();
    $details = '';
    $sql = mysqli_query($db, "SELECT * FROM tbl_leave_requests WHERE ID = '$leave_id'");
    while ($row = mysqli_fetch_assoc($sql)) {
        $details = $row;
    }
    return $details;
}
function get_ot_details($ot_id)
{
    $db = connect();
    $details = '';
    $sql = mysqli_query($db, "SELECT * FROM tbl_ot_application WHERE ID = '$ot_id'");
    while ($row = mysqli_fetch_assoc($sql)) {
        $details = $row;
    }
    return $details;
}
function get_personal_information($employee_number)
{
    $db = connect();
    $details = '';
    $sql = mysqli_query($db, "SELECT * FROM tbl_personal_information WHERE employee_number = '$employee_number'");
    while ($row = mysqli_fetch_assoc($sql)) {
        $details = $row;
    }
    return $details;
}
function get_employment_information($employee_number)
{
    $db = connect();
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
    $db = connect();
    $sql = mysqli_query($db, "SELECT * FROM tbl_companies WHERE company_name = '$company'");
    if (mysqli_num_rows($sql) > 0) {
        return true;
    }
}
function check_if_department_exist($department, $company_id)
{
    $db = connect();
    $sql = mysqli_query($db, "SELECT * FROM tbl_departments WHERE department = '$department' AND company_id = '$company_id'");
    if (mysqli_num_rows($sql) > 0) {
        return true;
    }
}
function check_if_group_exist($group_name, $company_id)
{
    $db = connect();
    $sql = mysqli_query($db, "SELECT * FROM tbl_department_group WHERE name = '$group_name' AND company_id = '$company_id'");
    if (mysqli_num_rows($sql) > 0) {
        return true;
    }
}
function check_if_job_grade_exist($job_grade, $company_id)
{
    $db = connect();
    $sql = mysqli_query($db, "SELECT * FROM tbl_job_grade WHERE job_grade = '$job_grade' AND company_id = '$company_id'");
    if (mysqli_num_rows($sql) > 0) {
        return true;
    }
}
function get_companies()
{
    $db = connect();
    $companies = array();
    $sql = mysqli_query($db, "SELECT * FROM tbl_companies ORDER BY ID DESC");
    while ($row = mysqli_fetch_assoc($sql)) {
        $companies[] = array('ID' => $row['ID'], 'company_name' => $row['company_name']);
    }
    return $companies;
}
function get_company($company_id)
{
    $db = connect();
    $company = '';
    $sql = mysqli_query($db, "SELECT * FROM tbl_companies WHERE ID = '$company_id' ORDER BY ID DESC");
    while ($row = mysqli_fetch_assoc($sql)) {
        $company = $row['company_name'];
    }
    return $company;
}
function get_company_array($company_id)
{
    $db = connect();
    $companies = array();
    $sql = mysqli_query($db, "SELECT * FROM tbl_companies WHERE ID = '$company_id' ORDER BY ID DESC");
    while ($row = mysqli_fetch_assoc($sql)) {
        $companies[] = array('ID' => $row['ID'], 'company_name' => $row['company_name']);
    }
    return $companies;
}
function get_departments($company_id)
{
    $db = connect();
    $departments = array();
    $sql = mysqli_query($db, "SELECT * FROM tbl_departments WHERE company_id = '$company_id' ORDER BY ID DESC");
    while ($row = mysqli_fetch_assoc($sql)) {
        $departments[] = array('ID' => $row['ID'], 'department' => $row['department']);
    }
    return $departments;
}
function get_groups($company_id)
{
    $db = connect();
    $groups = array();
    $sql = mysqli_query($db, "SELECT * FROM tbl_department_group WHERE company_id = '$company_id' ORDER BY ID DESC");
    while ($row = mysqli_fetch_assoc($sql)) {
        $groups[] = array('id' => $row['id'], 'name' => $row['name']);
    }
    return $groups;
}
function get_job_grade_set($company_id)
{
    $db = connect();
    $job_grade_sets = array();
    $sql = mysqli_query($db, "SELECT * FROM tbl_job_grade_set WHERE company_id = '$company_id' ORDER BY ID DESC");
    while ($row = mysqli_fetch_assoc($sql)) {
        $job_grade_sets[] = array('ID' => $row['ID'], 'job_grade_set' => $row['job_grade_set']);
    }
    return $job_grade_sets;
}
function get_job_grade($company_id)
{
    $db = connect();
    $job_grade = array();
    $sql = mysqli_query($db, "SELECT * FROM tbl_job_grade WHERE company_id = '$company_id' ORDER BY ID DESC");
    while ($row = mysqli_fetch_assoc($sql)) {
        $job_grade[] = array('ID' => $row['ID'], 'job_grade' => $row['job_grade']);
    }
    return $job_grade;
}
function get_group($company_id)
{
    $db = connect();
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
function get_approvers($company_id)
{
    $db = connect();
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
    $db = connect();
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
        'url' => 'company',
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
