<?php

include('../config.php');

$d = date('Y-m-d');
mysqli_query($db, "UPDATE tbl_loan_status SET `status` = 'Paid' WHERE `date` = '$d'");