<?php include 'inc/config.php'; ?>
<?php include 'inc/template_start.php'; ?>
<?php include 'inc/page_head.php'; ?>
<?php
if (empty($_SESSION['hris_id'])) {
    header('Location: login');
}
$fid = $_GET[md5('id')]; // Foreign id
$rid = ""; // row id
$sql = mysqli_query($db, "SELECT * FROM tbl_training");
while ($row = mysqli_fetch_assoc($sql)) {
    if ($fid == md5($row['ID'])) {
        $rid = $row['ID'];
    }
}
?>
<div id="page-content">
    <div class="content-header">
        <div class="header-section">
            <h1>
                <i class="fa fa-heart"></i>Training Details
            </h1>
        </div>
    </div>
    <div class="block full">
        <div class="block-title">
            <div class="block-options pull-right">
                <a href="training" class="btn btn-alt btn-sm btn-default">Training List</a>
            </div>
            <h2><strong>Training Details</strong> Form</h2>
        </div>
        <div class="container-fluid">
            <p class="text-danger" id="res"></p>
            <?= $res ?>
            <form method="POST" enctype="multipart/form-data">
                <?php
                $get_training_details = mysqli_query($db, "SELECT * FROM tbl_training WHERE ID = '$rid'");
                while ($row = mysqli_fetch_assoc($get_training_details)) {
                ?>
                    <input type="hidden" name="id" value="<?= $row['ID'] ?>">
                    <div class="form-group">
                        <label>Assigned Employee *</label>
                        <select name="assigned_employee" required class="select-chosen" data-placeholder="Choose an employee..." style="width: 250px;">
                            <option></option>
                            <?php
                            $company_id = $_SESSION['hris_company_id'];
                            $get_employees = mysqli_query($db, "SELECT t.*, t1.company 
                                    FROM tbl_personal_information t
                                    INNER JOIN tbl_employment_information t1
                                    ON t.employee_number = t1.employee_number
                                    WHERE t1.company = '$company_id'");
                            while ($r = mysqli_fetch_assoc($get_employees)) {
                                if ($row['assigned_employee'] == $r['employee_number']) {
                                    echo '<option selected value="' . $r['employee_number'] . '">' . $r['account_name'] . '</option>';
                                } 
                                // else {
                                //     echo '<option value="' . $r['employee_number'] . '">' . $r['account_name'] . '</option>';
                                // }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Subject</label>
                                <input type="text" name="subject" class="form-control" required value="<?= $row['subject'] ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Target Date</label>
                                <input type="date" name="target_date" class="form-control" required value="<?= $row['target_date'] ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Description/Details</label>
                        <textarea name="description" rows="3" class="form-control" required><?= $row['description'] ?></textarea>
                    </div>
                    <?php
                    if (!empty($_SESSION['hris_employee_number'])) {
                        if ($row['assigned_employee'] == $_SESSION['hris_employee_number'] && $row['status'] == '0') {
                    ?>
                            <div class="form-group">
                                <label>Certificate of Completion</label>
                                <input type="file" name="attachment" class="form-control" required>
                            </div>
                            <button class="btn btn-primary" name="btn_training_complete">Submit</button>
                        <?php
                        }
                    } elseif (is_approver_training($_SESSION['hris_id']) == "1" && $row['status'] == '1') {
                        ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Certificate of Completion</label>
                                    <a href="uploads/training/<?= $row['attachment'] ?>" class="form-control" target="_blank">View Certificate</a>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-success" name="btn_approve_training">Approve</button>
                        <button class="btn btn-danger" name="btn_decline_training">Decline</button>
                        <button class="btn btn-default" name="btn_request_update_training">Update</button>
                <?php
                    }
                }
                ?>
            </form>
        </div>
    </div>
</div>

<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>
<?php include 'inc/template_end.php'; ?>