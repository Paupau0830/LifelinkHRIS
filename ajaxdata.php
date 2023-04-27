<?php

include_once 'inc/config.php';


if ($_POST['employee_id']) {
    $employee_id = $_POST['employee_id'];
    $query = "SELECT * FROM tbl_personal_information WHERE employee_number = '$employee_id'";
    $result = $db->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<option value = ' . $row['last_name'] . ', ' . $row['first_name'] . '>' . $row['last_name'] . ', ' . $row['first_name'] . '</option>';
        }
    } else {
        // echo "Error in ".$query."<br>".$db->error;
        echo '<option value="null">No Employee name under Employee ID</option>';
    }
}
if ($_POST['department_id_ajax']) {

    $department_id_ajax = $_POST['department_id_ajax'];
    $query = mysqli_query($db, "SELECT * FROM tbl_departments WHERE id = '$department_id_ajax'");
    $row = mysqli_fetch_assoc($query);

    echo '<option value = ' . $row['manual_id'] . '>' . $row['manual_id'] . '</option>';
}
if ($_POST['department_id_group']) {

    $department_id_group = $_POST['department_id_group'];
    $query = mysqli_query($db, "SELECT * FROM tbl_departments WHERE id = '$department_id_group'");
    $row = mysqli_fetch_assoc($query);
    $group = $row['group_id'];

    $get_group_name = mysqli_query($db, "SELECT * FROM tbl_department_group WHERE id = '$group'");
    $row = mysqli_fetch_assoc($get_group_name);


    echo '<option value = ' . $row['name'] . '>' . $row['name'] . '</option>';
}
