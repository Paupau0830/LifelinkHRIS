<?php
  $date_to = $_SESSION['selecteddate_to'];
  $date_from = $_SESSION['selecteddate_from'];
  $selected_empnum = $_SESSION['selected_empnum'];
  $_SESSION['paid_leaves'] = '0.00';
  $_SESSION['company_loan'] = '0.00';
  $_SESSION['sss_loan'] = '0.00';
  $_SESSION['hdmf_loan'] = '0.00';
  $_SESSION['wis_sss'] = '0.00';
  $_SESSION['mpf_hdmf'] = '0.00';
  $_SESSION['overtime'] = '0.00';
  $_SESSION['incentive'] = '0.00';
  $_SESSION['13th_month'] = '0.00';

  $sql = mysqli_query($db, "SELECT * FROM tbl_payroll_adjustments WHERE (emp_num = '$selected_empnum') AND (type_adjustment = 'Earning') AND (date_from = '$date_from' AND date_to = '$date_to')");
    while ($row = mysqli_fetch_assoc($sql)) {
        if($row['a_description'] == "13TH MONTH"){
            $_SESSION['13th_month'] += $row['amount'];
        }else if ($row['a_description'] == "INCENTIVE"){
            $_SESSION['incentive'] += $row['amount'];
        }else if ($row['a_description'] == "PAID LEAVES"){
            $_SESSION['paid_leaves'] += $row['amount'];
        }else if($row['a_description'] == "OVERTIME"){
            $_SESSION['overtime'] += $row['amount'];
        }
    }

    $sql = mysqli_query($db, "SELECT * FROM tbl_payroll_adjustments WHERE (emp_num = '$selected_empnum') AND (type_adjustment = 'Deduction') AND (date_from = '$date_from' AND date_to = '$date_to')");
    while ($row = mysqli_fetch_assoc($sql)) {
        if($row['a_description'] == "CO. LOAN"){
            $_SESSION['company_loan'] += $row['amount'];
        }else if ($row['a_description'] == "SSS LOAN"){
            $_SESSION['sss_loan']+= $row['amount'];
        }else if ($row['a_description'] == "HMDF LOAN"){
            $_SESSION['hdmf_loan']+= $row['amount'];
        }else if($row['a_description'] == "WIS SSS"){
            $_SESSION['wis_sss'] += $row['amount'];
        }else if($row['a_description'] == "MPF HDMF"){
            $_SESSION['mpf_hdmf'] += $row['amount'];
        }
    }

?>