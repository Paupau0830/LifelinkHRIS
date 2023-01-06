<?php

include('../config.php');

$get_employees = mysqli_query($db, "SELECT * FROM `tbl_employment_information`");
while ($row = mysqli_fetch_assoc($get_employees)) {
    $employee_number = $row['employee_number'];
    $company_id = $row['company'];
    $job_grade_set_id = $row['job_grade_set'];

    // Get hire date
    $dateToday = date('Y-m-d');
    $hireDate = date('Y-m-d', strtotime($row['date_hired']));
    $diff = abs(strtotime($hireDate) - strtotime($dateToday));
    $years_hired = floor($diff / (365 * 60 * 60 * 24));

    $get_leave_values = mysqli_query($db, "SELECT * FROM `tbl_leave_maintenance` WHERE company_id = '$company_id' AND jgs_id = '$job_grade_set_id'");
    $leave_values = mysqli_fetch_assoc($get_leave_values);

    $get_benefits_values = mysqli_query($db, "SELECT * FROM `tbl_benefits_maintenance` WHERE company_id = '$company_id' AND jgs_id = '$job_grade_set_id'");
    $benefits_values = mysqli_fetch_assoc($get_benefits_values);

    $get_benefits_eligibility = mysqli_query($db, "SELECT * FROM tbl_benefits_eligibility WHERE employee_number = '$employee_number'");
    $benefits_eligibility = mysqli_fetch_assoc($get_benefits_eligibility);

    // monthly only
    $sl = $leave_values['sl_monthly'];
    $vl = $leave_values['vl_monthly'];
    $wfh = $leave_values['wfh_monthly'];
    $el = $leave_values['el_monthly'];

    $cep = $benefits_values['cep_monthly'];
    $gas = $benefits_values['gas_monthly'];
    $gym = $benefits_values['gym_monthly'];
    $medical = $benefits_values['medical_monthly'];
    $optical = $benefits_values['optical_monthly'];

    if ($row['employment_status'] == "Regular") {
        if ($years_hired == 0) {
            // Update leave balances
            mysqli_query($db, "UPDATE tbl_leave_balances SET
                VL = VL + $vl,
                SL = SL + $sl,
                EL = EL + $el,
                WFH = WFH + $wfh
                WHERE employee_number = '$employee_number'
            ");

            // Update Benefits Balances
            if ($benefits_eligibility['cep'] == '1') {
                mysqli_query($db, "UPDATE tbl_benefits_cep_balance SET
                balance = balance + $cep WHERE employee_number = '$employee_number'");
            }
            if ($benefits_eligibility['gasoline'] == '1') {
                mysqli_query($db, "UPDATE tbl_benefits_gas_balance SET
                balance = balance + $gas WHERE employee_number = '$employee_number'");
            }
            if ($benefits_eligibility['gym'] == '1') {
                mysqli_query($db, "UPDATE tbl_benefits_gym_balance SET
                balance = balance + $gym WHERE employee_number = '$employee_number'");
            }
            if ($benefits_eligibility['medicine'] == '1') {
                mysqli_query($db, "UPDATE tbl_benefits_medical_balance SET
                balance = balance + $medical WHERE employee_number = '$employee_number'");
            }
            if ($benefits_eligibility['optical_allowance'] == '1') {
                mysqli_query($db, "UPDATE tbl_benefits_optical_balance SET
                balance = balance + $optical WHERE employee_number = '$employee_number'");
            }
        }
    }
}

$get_benefits = mysqli_query($db, "SELECT * FROM `tbl_benefits_form`");
while ($ben = mysqli_fetch_assoc($get_benefits)) {
    $benefits_id = $ben['benefits_id'];
    $cat = $ben['cat'];

    if ($cat == "CEP") {
        $get_bond = mysqli_query($db, "SELECT * FROM tbl_cep_bond WHERE benefits_id = '$benefits_id'");
        if ($r = mysqli_fetch_assoc($get_bond)) {
            $remaining = $r['remaining'];
            if ($remaining <= 1) {
                mysqli_query($db, "UPDATE tbl_cep_bond SET remaining = 0");
            } else {
                mysqli_query($db, "UPDATE tbl_cep_bond SET remaining = remaining - 1");
            }
        }
    }
}
