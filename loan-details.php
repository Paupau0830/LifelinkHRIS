<?php include 'inc/config.php'; ?>
<?php include 'inc/template_start.php'; ?>
<?php include 'inc/page_head.php'; ?>
<?php
if (empty($_SESSION['hris_id'])) {
    header('Location: login');
}
?>
<?php
$fid = $_GET[md5('id')]; // Foreign id
$rid = ""; // row id
$sql = mysqli_query($db, "SELECT * FROM tbl_loan_application");
while ($row = mysqli_fetch_assoc($sql)) {
    if ($fid == md5($row['ID'])) {
        $rid = $row['ID'];
    }
}
$approvers_array = array();
?>
<div id="page-content">
    <div class="content-header">
        <div class="header-section">
            <h1>
                <i class="fa fa-money"></i>Salary Loan Details
            </h1>
        </div>
    </div>
    <div class="block full">
        <div class="block-title">
            <div class="block-options pull-right">
                <a href="loan-list" class="btn btn-alt btn-sm btn-default">Salary Loan List</a>
            </div>
            <h2><strong>Salary Loan</strong> Details</h2>
        </div>
        <div class="container-fluid">
            <p class="text-danger" id="res"></p>
            <?= $res ?>
            <form method="POST" enctype="multipart/form-data">
                <?php
                $sql = mysqli_query($db, "SELECT * FROM tbl_loan_application WHERE ID = '$rid'");
                while ($details = mysqli_fetch_assoc($sql)) {
                    $employee_number = $details['employee_number'];
                    $company_id = $details['company_id'];
                    $stat = '';
                    if ($details['status'] == 'Approved') {
                        $stat = 'Approved';
                    } elseif ($details['status'] == 'Declined') {
                        $stat = 'Declined';
                    } elseif ($details['status'] == 'Cancelled') {
                        $stat = 'Cancelled';
                    } else {
                        $stat = 'Pending - ' . get_salary_loan_role_name($details['status'])['role'];
                    }

                    $personal_info = get_personal_information($details['employee_number']);
                    $get_approvers = mysqli_query($db, "SELECT * FROM tbl_loan_approvers WHERE company_id = '$company_id'");
                    while ($app = mysqli_fetch_assoc($get_approvers)) {
                        $approvers_array[] = get_user_details($app['user_id']);
                        // $approvers_array[] = $app['email'];
                    }
                ?>
                    <input type="hidden" name="id" value="<?= $rid ?>">
                    <input type="hidden" name="company_id" value="<?= $company_id ?>">
                    <input type="hidden" name="status" value="<?= $details['status'] ?>">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Payee *</label>
                                <select required id="payee" name="payee" class="select-chosen" style="width: 250px;">
                                    <option></option>
                                    <?php
                                    $get_employees = mysqli_query($db, "SELECT t.*, t1.company 
                                    FROM tbl_personal_information t
                                    INNER JOIN tbl_employment_information t1
                                    ON t.employee_number = t1.employee_number
                                    WHERE t1.company = '$company_id'");
                                    while ($row = mysqli_fetch_assoc($get_employees)) {
                                        if ($row['employee_number'] == $details['employee_number']) {
                                            echo '<option selected value="' . $row['employee_number'] . '">' . $row['account_name'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Status</label>
                                <input type="text" readonly class="form-control" value="<?= $stat ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Date Created</label>
                                <input type="date" readonly class="form-control" value="<?= $details['date_created'] ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Attachment</label><br>
                                <a href="uploads/<?= $details['attachment'] ?>" class="form-control" target="_blank">View Attachment</a>
                            </div>
                        </div>
                    </div>
                    <div class="block full">
                        <!-- Working Tabs Title -->
                        <div class="block-title">
                            <h2><strong>Salary Loan Application</strong> Details</h2>
                        </div>
                        <p class="text-danger" id="res"></p>
                        <input type="hidden" name="employee_number" value="<?= $_SESSION['hris_employee_number'] ?>">
                        <input type="hidden" name="company_id" value="<?= $_SESSION['hris_company_id'] ?>">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Type of Loan *</label>
                                    <select name="type" id="typeofloan" required class="select-chosen" data-placeholder="Choose a type of loan..." style="width: 250px;">
                                        <option></option>
                                        <?php
                                        $types = array('Medical / Educational', 'Others');
                                        foreach ($types as $k => $v) {
                                            if ($v == $details['type']) {
                                                echo '<option selected>' . $v . '</option>';
                                            } else {
                                                echo '<option>' . $v . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Payment Terms *</label>
                                    <select name="terms" id="terms" required class="select-chosen" data-placeholder="Choose a payment terms..." style="width: 250px;">
                                        <option></option>
                                        <?php
                                        $term = array('1 Month', '2 Months', '3 Months', '4 Months', '5 Months', '6 Months', '7 Months', '8 Months', '9 Months', '10 Months', '11 Months', '12 Months');
                                        $term_num = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12');
                                        foreach ($term_num as $k => $m) {
                                            $selected = ($details['terms'] == $m) ? "selected" : "";
                                            echo '<option ' . $selected . ' value="' . $m . '">' . $term[$k] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Amount *</label>
                            <input type="number" name="amount" id="amount" class="form-control" value="<?= $details['amount'] ?>">
                        </div>
                        <div class="form-group">
                            <label>Remarks</label>
                            <textarea name="remarks" rows="3" class="form-control"><?= $details['remarks'] ?></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Amount Approved</label>
                                <input type="number" name="amount_approved" id="amount_approved" class="form-control" value="<?= $details['amount_approved'] ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date Approved</label>
                                <input type="date" class="form-control" readonly value="<?= $details['date_approved'] ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Start of Loan Date</label>
                                <select name="startDate" class="select-chosen" data-placeholder="Choose a start date..." style="width: 250px;">
                                    <option></option>
                                    <?php
                                    $months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
                                    $months_num = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
                                    foreach ($months_num as $k => $m) {
                                        $selected = ($details['start_date'] == $m) ? "selected" : "";
                                        echo '<option ' . $selected . ' value="' . $m . '">' . $months[$k] . ', ' . date('Y') .  '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Monthly Deduction</label>
                                <input type="text" name="monthly_deduction" readonly class="form-control" id="m_deduction" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>HR Remarks</label>
                        <textarea name="hr_remarks" class="form-control" rows="3"><?= $details['hr_remarks'] ?></textarea>
                    </div>
                    <div style="float:right">
                    <?php
                    if ($details['status'] == "Approved") {
                    } elseif ($details['status'] == "Declined") {
                    } elseif (!empty($_SESSION['hris_employee_number'])) {
                        if ($details['employee_number'] == $_SESSION['hris_employee_number']) {
                            if ($details['status'] != "Cancelled") {
                                echo '<button class="btn btn-danger" name="btn_cancel_loan">Cancel</button></div>';
                            }
                        }
                    } else {
                        if ($details['status'] != "Cancelled") {
                            $current_approver = $details['status'];
                            $approver_role_info = get_approvers_from_role_loan($current_approver, $company_id);
                            $approvers = get_salary_loan_approvers($company_id, $approver_role_info['ID']);
                            $recipients_cc = $approver_role_info['cc'];
                            $recipients_cc = explode(',', $recipients_cc);
                            if (in_array($_SESSION['hris_email'], $recipients_cc) or $_SESSION['hris_id'] == $approvers[0]['user_id']) {
                                echo '
                                <button class="btn btn-danger" name="btn_decline_loan" id="btn_decline_loan">Decline</button>
                                <button class="btn btn-info" name="btn_approve_loan" id="btn_approve_loan">Approve</button></div>';
                            }
                        }
                    }
                }
                    ?>
            </form>
        </div>
        <hr>
        <div class="block full">
            <div class="block-title">
                <div class="block-options pull-right">
                    <button class="btn btn-alt btn-sm btn-default" onclick="$('#modal_prepay_list').modal('show')">Prepay List</button>
                </div>
                <h2><strong>Monthly Deduction</strong> Details</h2>
            </div>
            <div class="table-responsive">
                <table id="loan-status-list" class="table table-vcenter table-condensed table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">ID</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = mysqli_query($db, "SELECT * FROM tbl_loan_status WHERE loan_id = '$rid'");
                        while ($row = mysqli_fetch_assoc($sql)) {
                        ?>
                            <tr>
                                <td><?= $row['ID'] ?></td>
                                <td><?= number_format($row['monthly_deduction'], 2) ?></td>
                                <td><?= $row['date'] ?></td>
                                <td><?= $row['status'] ?></td>
                                <td class="text-center">
                                    <?php
                                    if ($row['status'] == 'Unpaid') {
                                    ?>
                                        <button class="btn btn-primary" data-prepay-id="<?= $row['ID'] ?>">Prepay</button>
                                    <?php
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>
<?php include 'inc/template_end.php'; ?>
<div id="modal_prepay_list" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h2 class="modal-title"><i class="fa fa-money"></i> Prepay List</h2>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <table id="loan-status-list" class="table table-vcenter table-condensed table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">ID</th>
                                <th>Remarks</th>
                                <th>Attachment</th>
                                <th>Status</th>
                                <th>Date Created</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = mysqli_query($db, "SELECT * FROM tbl_prepay WHERE loan_id = '$rid' ORDER BY ID DESC");
                            while ($row = mysqli_fetch_assoc($sql)) {
                            ?>
                                <tr>
                                    <td><?= $row['ID'] ?></td>
                                    <td><?= $row['prepay_remarks'] ?></td>
                                    <td><a href="uploads/<?= $row['attachment'] ?>" target="_blank">View</a></td>
                                    <td><?= $row['status'] ?></td>
                                    <td><?= $row['date_created'] ?></td>
                                    <td class="text-center">
                                        <?php
                                        if ($row['status'] == 'Prepay Pending') {
                                            if (in_array($_SESSION['hris_id'], $approvers_array)) {
                                        ?>
                                                <form method="POST">
                                                    <input type="hidden" name="id" value="<?= $row['ID'] ?>">
                                                    <input type="hidden" name="prepay_id" value="<?= $row['prepay_id'] ?>">
                                                    <button class="btn btn-primary" name="btn_approve_prepay">Approve</button>
                                                    <button class="btn btn-primary" name="btn_decline_prepay">Decline</button>
                                                </form>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="modal-prepay" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h2 class="modal-title"><i class="fa fa-money"></i> Prepay Application</h2>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="prepay_id" id="prepay_id">
                        <input type="hidden" name="loan_id" value="<?= $rid ?>">
                        <input type="hidden" name="employee_number" value="<?= $employee_number ?>">
                        <div class="form-group">
                            <label>Remarks</label>
                            <input type="text" name="prepay_remarks" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Attachment</label>
                            <input type="file" name="prepay_attachment" class="form-control">
                        </div>
                        <button class="btn btn-primary btn-block" name="btn_prepay">Submit</button>
                        <br>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var m_deduction = $('#m_deduction');
    var payment_term = parseFloat($('#terms').val().replace(/,/g, ''));
    var loan_approved = parseFloat($('#amount_approved').val().replace(/,/g, ''));
    var amount_applied = parseFloat($('#amount').val().replace(/,/g, ''));
    if (loan_approved > amount_applied) {
        m_deduction.val("Value exceeded");
    } else {
        m_deduction.val((loan_approved / payment_term).toFixed(2));
    }
    $("#amount_approved").keyup(function() {
        var payment_term = parseFloat($('#terms').val().replace(/,/g, ''));
        var loan_approved = parseFloat($('#amount_approved').val().replace(/,/g, ''));
        var amount_applied = parseFloat($('#amount').val().replace(/,/g, ''));
        if (loan_approved > amount_applied) {
            m_deduction.val("Value exceeded");
        } else {
            m_deduction.val((loan_approved / payment_term).toFixed(2));
        }
    });
    $("#terms").change(function() {
        var payment_term = parseFloat($('#terms').val().replace(/,/g, ''));
        var loan_approved = parseFloat($('#amount_approved').val().replace(/,/g, ''));
        var amount_applied = parseFloat($('#amount').val().replace(/,/g, ''));
        if (loan_approved > amount_applied) {
            m_deduction.val("Value exceeded");
        } else {
            m_deduction.val((loan_approved / payment_term).toFixed(2));
        }
    });
    var amount = $('#amount');
    if ($('#typeofloan').val() == "Others") {
        amount.attr("max", "70000");
    } else {
        amount.attr("max", "100000");
    }

    $("#typeofloan").change(function() {
        if ($('#typeofloan').val() == "Others") {
            amount.attr("max", "70000");
        } else {
            amount.attr("max", "100000");
        }
    });
    $('*[data-prepay-id]').click(function() {
        var prepay_id = $(this).data('prepay-id');
        $('#prepay_id').val(prepay_id);
        $('#modal-prepay').modal('show');
    });
</script>