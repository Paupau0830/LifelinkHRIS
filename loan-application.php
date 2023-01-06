<?php include 'inc/config.php'; ?>
<?php include 'inc/template_start.php'; ?>
<?php include 'inc/page_head.php'; ?>
<?php
if (empty($_SESSION['hris_id'])) {
    header('Location: login');
}
?>
<?php
$company_id = $_SESSION['hris_company_id'];
$get_max_loan = mysqli_query($db, "SELECT * FROM tbl_loan_max_value WHERE company_id = '$company_id'");
$max_loan = mysqli_fetch_assoc($get_max_loan);

?>
<div id="page-content">
    <div class="content-header">
        <div class="header-section">
            <h1>
                <i class="fa fa-hourglass"></i>Salary Loan Application
            </h1>
        </div>
    </div>
    <div class="block full">
        <div class="block-title">
            <div class="block-options pull-right">
                <a href="loan-application-list" class="btn btn-alt btn-sm btn-default">Salary Loan List</a>
            </div>
            <h2><strong>Salary Loan Application</strong> Form</h2>
        </div>
        <div class="container-fluid">
            <p class="text-danger" id="res"></p>
            <?= $res ?>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="employee_number" value="<?= $_SESSION['hris_employee_number'] ?>">
                <input type="hidden" name="company_id" value="<?= $_SESSION['hris_company_id'] ?>">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Type of Loan *</label>
                            <select name="type" id="typeofloan" required class="select-chosen" data-placeholder="Choose a type of loan..." style="width: 250px;">
                                <option></option>
                                <option>Medical / Educational</option>
                                <option>Others</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Payment Terms *</label>
                            <select name="terms" required class="select-chosen" data-placeholder="Choose a payment terms..." style="width: 250px;">
                                <option></option>
                                <option value="1">1 Month</option>
                                <option value="2">2 Months</option>
                                <option value="3">3 Months</option>
                                <option value="4">4 Months</option>
                                <option value="5">5 Months</option>
                                <option value="6">6 Months</option>
                                <option value="7">7 Months</option>
                                <option value="8">8 Months</option>
                                <option value="9">9 Months</option>
                                <option value="10">10 Months</option>
                                <option value="11">11 Months</option>
                                <option value="12">12 Months</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-10">
                        <div class="form-group">
                            <label>Amount *</label>
                            <input type="number" name="amount" id="amount" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Max Amount</label>
                            <input type="text" readonly id="max_amount" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Remarks</label>
                    <textarea name="remarks" rows="3" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <label>Attachment</label>
                    <input type="file" name="attachment" class="form-control-file">
                </div>
                <?php
                $employee_number = $_SESSION['hris_employee_number'];
                $sql = mysqli_query($db, "SELECT * FROM tbl_loan_application WHERE employee_number = '$employee_number' AND `status` NOT IN('Approved','Cancelled','Declined')");
                if ($row = mysqli_fetch_assoc($sql)) {
                ?>
                    <div class="alert alert-danger">
                        <h4><i class="fa fa-times"></i> You still have pending loan payments. Please complete the loan payment first to enable new request.</h4>
                    </div>
                    <?php
                } else {
                    $get_loans = mysqli_query($db, "SELECT * FROM tbl_loan_application WHERE employee_number LIKE '$employee_number' AND `status` LIKE 'Approved'");
                    $has_existing_loan = 0;
                    while ($row = mysqli_fetch_assoc($get_loans)) {
                        $lid = $row['ID'];
                        $get_loan_status = mysqli_query($db, "SELECT * FROM tbl_loan_status WHERE loan_id LIKE '$lid'");
                        while ($r = mysqli_fetch_assoc($get_loan_status)) {
                            if ($r['status'] == "Unpaid") {
                                $has_existing_loan = 1;
                            }
                        }
                    }
                    if ($has_existing_loan == 1) {
                        echo '
                        <div class="alert alert-danger">
                            <h4><i class="fa fa-times"></i> You still have pending loan payments. Please complete the loan payment first to enable new request.</h4>
                        </div>';
                    } else {
                    ?>
                        <button class="btn btn-primary" name="btn_loan_application">Submit</button>
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
<script>
    var amount = $('#amount');
    var max_amount = $('#max_amount');
    $("#typeofloan").change(function() {
        if ($('#typeofloan').val() == "Others") {
            amount.attr("max", "<?= $max_loan['others_max_value'] ?>");
            max_amount.val("<?= number_format($max_loan['others_max_value'], 2) ?>");
        } else {
            amount.attr("max", "<?= $max_loan['max_value'] ?>");
            max_amount.val("<?= number_format($max_loan['max_value'], 2) ?>");
        }
    });
</script>