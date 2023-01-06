<?php 

include_once 'inc/config.php';


if ($_POST['employee_id']){
    $employee_id = $_POST['employee_id'];
    $query = "SELECT * FROM tbl_personal_information WHERE employee_number = '$employee_id'";
    $result = $db->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()){
            echo '<option value = '.$row['last_name'].', '.$row['first_name'].'>'.$row['last_name'].', '.$row['first_name']. '</option>';
        }
    }else {
        // echo "Error in ".$query."<br>".$db->error;
        echo '<option value="null">No Employee name under Employee ID</option>';

    }
}

?>