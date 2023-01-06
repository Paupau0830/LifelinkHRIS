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

    // annual only
    $sl = $leave_values['sl_annual'];
    $vl = $leave_values['vl_annual'];
    $wfh = $leave_values['wfh_annual'];
    $el = $leave_values['el_annual'];
    $ecu = $leave_values['ecu_annual'];
    $bl = $leave_values['bl_annual'];
    $pl = $leave_values['pl_annual'];
    $pla = $leave_values['pla_annual'];
    $spl = $leave_values['spl_annual'];

    $car_val = array(
        '1' => $benefits_values['car_year1'],
        '2' => $benefits_values['car_year2'],
        '3' => $benefits_values['car_year3'],
        '4' => $benefits_values['car_year4'],
        '5' => $benefits_values['car_year5']
    );
    $cep = $benefits_values['cep_annual'];
    $gym = $benefits_values['gym_annual'];
    $medical = $benefits_values['medical_annual'];
    $optical = $benefits_values['optical_annual'];

    // Real values
    $get_leave_balances = mysqli_query($db, "SELECT * FROM `tbl_leave_balances` WHERE employee_number = '$employee_number'");
    $leave_balances = mysqli_fetch_assoc($get_leave_balances);
    $vl_val = 0;
    $slbank_val = 0;

    $slbank_val = $sl - $leave_balances['SLBANK'];
    if ($slbank_val >= 5) {
        $slbank_val = 5;
    }
    $max_vl = $vl * 3;
    $vl_val = $leave_balances['VL'] + $vl;
    if ($vl_val >= $max_vl) {
        $vl_val = $max_vl;
    }


    if ($row['employment_status'] == "Regular") {
        if ($years_hired != 0) {
            // Update leave balances
            mysqli_query($db, "UPDATE tbl_leave_balances SET
                VL = $vl_val,
                SL = $sl,
                EL = $el,
                WFH = $wfh,
                SLBANK = SLBANK + $slbank_val,
                EL = $el,
                ECU = $ecu,
                BL = $bl,
                PL = $pl,
                PLA = $pla,
                SPL = $spl
                WHERE employee_number = '$employee_number'
            ");

            // Update Benefits Balances
            if ($benefits_eligibility['cep'] == '1') {
                mysqli_query($db, "UPDATE tbl_benefits_cep_balance SET
                balance = $cep WHERE employee_number = '$employee_number'");
            }
            if ($benefits_eligibility['gym'] == '1') {
                mysqli_query($db, "UPDATE tbl_benefits_gym_balance SET
                balance = $gym WHERE employee_number = '$employee_number'");
            }
            if ($benefits_eligibility['medicine'] == '1') {
                mysqli_query($db, "UPDATE tbl_benefits_medical_balance SET
                balance = $medical WHERE employee_number = '$employee_number'");
            }
            if ($benefits_eligibility['optical_allowance'] == '1') {
                mysqli_query($db, "UPDATE tbl_benefits_optical_balance SET
                balance = $optical WHERE employee_number = '$employee_number'");
            }
            if ($benefits_eligibility['car_maintenance'] == '1') {
                $get_registered_car = mysqli_query($db, "SELECT * FROM `tbl_car_registration` WHERE employee_number = '$employee_number'");
                $registered_car = mysqli_fetch_assoc($get_registered_car);
                $get_age = get_car_age($registered_car['date_acquired']);
                $value_to_add = $car_val[$get_age];

                mysqli_query($db, "UPDATE tbl_benefits_car_balance SET
                balance = $value_to_add WHERE employee_number = '$employee_number'");
            }
        }
    }
}
