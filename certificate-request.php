<?php include 'inc/config.php'; ?>
<?php include 'inc/template_start.php'; ?>
<?php include 'inc/page_head.php'; ?>
<?php
if (empty($_SESSION['hris_id'])) {
    header('Location: login');
}
?>
<div id="page-content">
    <div class="content-header">
        <div class="header-section">
            <h1>
                <i class="fa fa-file"></i> <strong> Certificate Request</strong>
            </h1>
                
        </div>
    </div>
    <div class="block full">
        <div class="block-title">
            
            <h2><strong>Certificate Request</strong> Form</h2>
        </div>
        <div class="container-fluid">
            <?= $res ?>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="requested_by" value="<?= $_SESSION['hris_employee_number'] ?>">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Requestor *</label>
                            <select name="employee_number" required class="select-chosen" data-placeholder="Choose a requestor..." style="width: 250px;">
                                <option></option>
                                <?php
                                $empnum = $_SESSION['hris_employee_number'];
                                if ($_SESSION['hris_role'] == 'User') {
                                    if (allowed_on_behalf_filing($empnum) == '0') {
                                        echo '<option value="' . $empnum . '" selected>' . $_SESSION['hris_account_name'] . '</option>';
                                    } else {
                                        $employees = get_employees_from_company($_SESSION['hris_company_id']);
                                        foreach ($employees as $k => $v) {
                                            $name = get_personal_information($v['employee_number']);
                                            echo '<option value="' . $v['employee_number'] . '">' . $name['account_name'] . '</option>';
                                        }
                                    }
                                }else {
                                    $get_details = mysqli_query($db, "SELECT * FROM tbl_employees");
                                        while ($row = mysqli_fetch_assoc($get_details)) {
                                            $r_id = $row['ID'];
                                            $account_name = $row['emp_name'];
                                            // $emp_number = $row['emp_num'];
                                            echo '<option value"'.$row['emp_num']. '">' . $account_name . '</option>';
                                        }
                                        
                                        
                                }

                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>Certificate Type</label>
                        <select name="certificate_type" required class="select-chosen" data-placeholder="Choose a certificate type..." style="width: 250px;">
                            <option></option>
                            <option>Certificate of Employment</option>
                            <option>Certificate of Clearance</option>
                            <!-- <option>Certificate of Evaluation</option> -->
                            <!-- <option>Certificate of Completion - Intern</option> -->
                            <option value="others">Others (Please include in remarks)</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Date Required</label>
                            <input type="date" name="date_required" class="form-control" min="<?= date('Y-m-d') ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Purpose</label>
                            <input type="text" name="purpose" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Remarks</label>
                    <textarea name="remarks" rows="3" class="form-control"></textarea>
                </div>
                <button class="btn btn-primary" name="btn_certificate_request">Submit</button>
            </form>
        </div>
    </div>
</div>

<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>
<?php include 'inc/template_end.php'; ?>