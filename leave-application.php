<?php include 'inc/config.php'; ?>
<?php include 'inc/template_start.php'; ?>

<?php include 'inc/page_head.php'; ?>
<?php
if (empty($_SESSION['hris_id'])) {
    header('Location: login');
}
$company_id = $_SESSION['hris_company_id'];
$emp_num = $_SESSION['hris_employee_number'];
$approver = '';

$sql = mysqli_query($db, "SELECT approver FROM tbl_employment_information WHERE employee_number = '$emp_num'");
while ($row = mysqli_fetch_assoc($sql)) {
    $approver = $row['approver'];
}
?>
<div id="page-content">
    <div class="content-header">
        <div class="header-section">
            <h1>
                <i class="fa fa-user-times"></i><strong>Leave Application</strong>
            </h1>
        </div>
    </div>
    <div class="block full">
        <div class="block-title">
            <h2><strong>Leave Application</strong> Form</h2>
        </div>
        <div class="container-fluid">
            <p class="text-danger" id="result"></p>
            <p class="text-danger" id="res"></p>
            <?= $res ?>
            <form method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>Requestor *</label>
                            <select name="delegate_emp_number" id="delegate_name" required class="select-chosen" data-placeholder="Choose a requestor..." style="width: 250px;">
                                <option></option>
                                <?php
                                if ($_SESSION['hris_role'] == 'User') {
                                    if (allowed_on_behalf_filing($emp_num) == '0') {
                                        // echo '<option value="' . $empnum . '" selected>' . $_SESSION['hris_account_name'] . '</option>';
                                        $get_employees = mysqli_query($db, "SELECT * FROM tbl_personal_information WHERE employee_number = '$emp_num'");
                                        while ($row = mysqli_fetch_assoc($get_employees)) {
                                            echo '<option value="' . $row['employee_number'] . '">' . $row['account_name'] . '</option>';
                                        }
                                    } else {
                                        $get_employees = mysqli_query($db, "SELECT t.*, t1.company 
                                        FROM tbl_personal_information t
                                        INNER JOIN tbl_employment_information t1
                                        ON t.employee_number = t1.employee_number
                                        WHERE t1.company = '$company_id' AND t.super_admin != '1'");
                                        while ($row = mysqli_fetch_assoc($get_employees)) {
                                            echo '<option value="' . $row['employee_number'] . '">' . $row['account_name'] . '</option>';
                                        }
                                    }
                                } else {
                                    $get_employees = mysqli_query($db, "SELECT t.*, t1.company 
                                    FROM tbl_personal_information t
                                    INNER JOIN tbl_employment_information t1
                                    ON t.employee_number = t1.employee_number
                                    WHERE t1.company = '$company_id' AND t.super_admin != '1'");
                                    while ($row = mysqli_fetch_assoc($get_employees)) {
                                        echo '<option value="' . $row['employee_number'] . '">' . $row['account_name'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>Leave Type *</label>
                            <select name="leave_type" id="leave_type" required class="select-chosen" data-placeholder="Choose a Leave Type..." style="width: 250px;">
                                <option></option>
                                <option value="VL">Vacation Leave</option>
                                <option value="SL">Sick Leave</option>
                                <option value="EL">Others</option>

                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Balance</label>
                            <input type="number" readonly id="leave_balance" step="any" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>Start Date *</label>
                            <?php
                            $date_now1 = date('y-m-d');
                            $role = $_SESSION['hris_role'];
                            ?>
                            <input type="hidden" name="roletype" id="roletype" value="<?= $role ?>">
                            <input type="date" name="startDate" required id="startDate" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>End Date *</label>
                            <input type="date" name="endDate" required id="endDate" class="form-control">
                            <input type="checkbox" name="late_filing" id="late_filing" onclick="getCheckboxValue()">
                            <label for="late_filing">Late Filing</label>
                            <input type="hidden" name="late_filing_val" id="late_filing_val" value="0">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Total Days</label>
                            <input type="number" id="totalDays" name="total_days" step="any" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>Duration</label>
                            <select name="duration" id="duration" required class="select-chosen" data-placeholder="Choose a Leave Type..." style="width: 250px;">
                                <option></option>
                                <option>Whole Day</option>
                                <option>Half Day AM</option>
                                <option>Half Day PM</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>Attachment</label>
                            <input type="file" name="attachment" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Approver*</label>
                            <input type="text" name="approver" id="approver" readonly class="form-control" value="<?= $approver ?>">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Reason</label>
                    <textarea name="reason" class="form-control" rows="3" required></textarea>
                </div>
                <button class="btn btn-success" name="btn_leave_application" id="btn_leave_application">Submit</button>
            </form>
        </div>
    </div>
</div>

<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>

<script>
    function getCheckboxValue() {
        var checkbox_lf = document.getElementById("late_filing");
        if (checkbox_lf.checked == true) {
            document.getElementById("late_filing_val").value = '1';
        } else {
            document.getElementById("late_filing_val").value = '0';
        }
    }
    setInterval(function() {
        if ($('#endDate').val().length === 0) {
            $('#totalDays').val('');
        }
    }, 1000);
    $('#startDate, #endDate').focusout(function() {
        var startDate = $('#startDate').val();
        var endDate = $('#endDate').val();
        var leave_type = $('#leave_type').val();
        var compute_leave_duration = '';
        $.ajax({
            url: "inc/config.php",
            method: "POST",
            data: {
                compute_leave_duration: compute_leave_duration,
                startDate: startDate,
                endDate: endDate,
                leave_type: leave_type
            },
            success: function(data) {
                $('#totalDays').val(data);
                var balance = $('#leave_balance').val();
                var numDays = $('#totalDays').val();
                var nd = parseInt(numDays);
                var bal = parseInt(balance);
                if (nd > bal) {
                    $('#result').html("Note: You don't have enough leave credits to file this.");
                    $('#btn_leave_application').prop("disabled", true);
                } else {
                    $('#result').html('');
                    $('#btn_leave_application').prop("disabled", false);
                }
            },
            complete: function() {
                if ($('#endDate').val().length !== 0) {
                    $('#btn_leave_application').prop("disabled", false);
                    $('#btn_leave_application').text('Submit');

                    var startDate = $('#startDate').val();
                    var endDate = $('#endDate').val();
                    var empnum = $('#delegate_name').val();
                    var check_if_between_dates = '';
                    $.ajax({
                        url: "inc/config.php",
                        method: "POST",
                        data: {
                            check_if_between_dates: check_if_between_dates,
                            startDate: startDate,
                            endDate: endDate,
                            empnum: empnum
                        },
                        success: function(data) {
                            if (data === "") {
                                $('#res').html('');
                                $('#btn_leave_application').prop("disabled", false);
                            } else {
                                $('#res').html("Note: The following dates were already filed: " + data);
                                $('#btn_leave_application').prop("disabled", true);
                            }
                        }
                    });
                }
            }
        });
    });
    $('#startDate').focusout(function() {
        var startDate = $('#startDate').val();
        // var endDate = $('#endDate').val();

        if (startDate.length == 0) {
            $('#endDate').prop("disabled", true);
        } else {
            $('#endDate').prop("disabled", false);
        }

    });
    $('#leave_type').on('change', function() {
        var leave_type = $(this).val();
        var role_type = $('#roletype').val();
        if (leave_type === "EL") {
            // $('#startDate').removeAttr('min');
            <?php
            $DateToday = date('Y-m-d');
            ?>

            if (role_type === "Admin") {
                $('#startDate').removeAttr('max');
                $('#startDate').removeAttr('min');
                $('#endDate').removeAttr('max');
                $('#endDate').removeAttr('min');

            } else {
                $('#startDate').removeAttr('min');
                // $('#startDate').attr('min', '<?= $DateToday ?>');
                $('#startDate').attr('max', '<?= date('Y-m-d', strtotime(' + 6 days')); ?>');
                $('#endDate').removeAttr('min');
                // $('#endDate').attr('min', '<?= $DateToday ?>');
                $('#endDate').removeAttr('max');
            }

        } else if (leave_type === "VL") {
            if (role_type === "Admin") {
                $('#startDate').removeAttr('max');
                $('#startDate').removeAttr('min');
                $('#endDate').removeAttr('max');
                $('#endDate').removeAttr('min');

            } else {
                $('#startDate').attr('min', '<?= date('Y-m-d', strtotime(' + 2 weeks')); ?>');
                $('#startDate').removeAttr('max');
                $('#endDate').attr('min', '<?= date('Y-m-d', strtotime(' + 2 weeks')); ?>');
                $('#endDate').removeAttr('max');
            }
        } else if (leave_type === "SL") {
            if (role_type === "Admin") {
                $('#startDate').removeAttr('max');
                $('#startDate').removeAttr('min');
                $('#endDate').removeAttr('max');
                $('#endDate').removeAttr('min');

            } else {
                // $('#startDate').attr('min', '<?= $DateToday ?>');
                $('#startDate').removeAttr('min');
                $('#startDate').removeAttr('max');
                // $('#endDate').attr('min', '<?= $DateToday ?>');
                $('#endDate').removeAttr('min');
                $('#endDate').removeAttr('max');
            }
        } else {
            $('#startDate').removeAttr('max');
            $('#endDate').removeAttr('max');
        }
        var delegate = $('#delegate_name').val();
        var select_leave_balances = '';
        var available_leaves = ['SL', 'VL', 'EL']; // MNCS = maternity normal or cs -- MM = Maternity Miscarriage 
        if ($.inArray(leave_type, available_leaves) !== -1) {
            $('#btn_leave_application').prop("disabled", true);
            $('#btn_leave_application').text('Loading...');
            $.ajax({
                url: "inc/config.php",
                method: "POST",
                data: {
                    select_leave_balances: select_leave_balances,
                    leave_type: leave_type,
                    delegate: delegate
                },
                success: function(data) {
                    $('#leave_balance').val(data);
                },
                complete: function() {
                    $('#btn_leave_application').prop("disabled", false);
                    $('#btn_leave_application').text('Submit');
                    var numDays = $('#numDays').text();
                    var balance = $('#leave_balance').text();
                    var nd = parseInt(numDays);
                    var bal = parseInt(balance);
                    if (nd > bal) {
                        $('#result').html("Note: You don't have enough leave credits to file this.");
                        $('#btn_leave_application').prop("disabled", true);
                    } else {
                        $('#result').html('');
                        $('#btn_leave_application').prop("disabled", false);
                    }
                }
            });
        } else {

            $('#leave_balance').val('Actuals');
        }
    });
    $('#delegate_name').on('change', function() {
        var leave_type = $('#leave_type').val();
        var delegate = $('#delegate_name').val();
        var select_leave_balances = '';
        var available_leaves = ['SL', 'VL', 'EL']; // MNCS = maternity normal or cs -- MM = Maternity Miscarriage 
        if ($.inArray(leave_type, available_leaves) !== -1) {
            $('#btn_leave_application').prop("disabled", true);
            $('#btn_leave_application').text('Loading...');
            $.ajax({
                url: "inc/config.php",
                method: "POST",
                data: {
                    select_leave_balances: select_leave_balances,
                    leave_type: leave_type,
                    delegate: delegate
                },
                success: function(data) {
                    $('#leave_balance').val(data);
                },
                complete: function() {
                    $('#btn_leave_application').prop("disabled", false);
                    $('#btn_leave_application').text('Submit');

                    var startDate = $('#startDate').val();
                    var endDate = $('#endDate').val();
                    var empnum = $('#delegate_name').val();
                    var check_if_between_dates = '';
                    $.ajax({
                        url: "inc/config.php",
                        method: "POST",
                        data: {
                            check_if_between_dates: check_if_between_dates,
                            startDate: startDate,
                            endDate: endDate,
                            empnum: empnum
                        },
                        success: function(data) {
                            if (data === "") {
                                $('#result').html('');
                                $('#btn_leave_application').prop("disabled", false);
                            } else {
                                $('#result').html("Note: The following dates were already filed: " + data);
                                $('#btn_leave_application').prop("disabled", true);
                            }
                        }
                    });
                }
            });
        }
    });
    $('#duration').on('change', function() {
        var startDate = $('#startDate').val();
        var endDate = $('#endDate').val();
        var type = $(this).val();
        var leave_type = $('#leave_type').val();
        var compute_leave_duration = '';

        $('#btn_leave_application').prop("disabled", true);
        $('#btn_leave_application').text('Loading...');

        $.ajax({
            url: "inc/config.php",
            method: "POST",
            data: {
                compute_leave_duration: compute_leave_duration,
                startDate: startDate,
                endDate: endDate,
                leave_type: leave_type
            },
            success: function(data) {
                $('#totalDays').val(data);
                if (type != 'Whole Day') {
                    totaldays = data / 2;
                    $('#totalDays').val(totaldays);
                    $('#numDays').html(totaldays);
                } else {
                    totaldays = $('#totalDays').val();
                    $('#totalDays').val(totaldays);
                    $('#numDays').html(totaldays);
                }
            },
            complete: function() {
                $('#btn_leave_application').prop("disabled", false);
                $('#btn_leave_application').text('Submit');
                var numDays = $('#totalDays').val();
                var balance = $('#leave_balance').val();
                var nd = parseInt(numDays);
                var bal = parseInt(balance);
                var empnum = $('#delegate_name').val();
                if (nd > bal) {
                    $('#result').html("Note: You don't have enough leave credits to file this.");
                    $('#btn_leave_application').prop("disabled", true);
                } else {
                    $('#result').html('');
                    $('#btn_leave_application').prop("disabled", false);
                }

                if (data === "") {
                    $('#result').html('');
                    $('#btn_leave_application').prop("disabled", false);
                } else {
                    $('#result').html("Note: The following dates were already filed: " + data);
                    $('#btn_leave_application').prop("disabled", true);
                }
            }
        });
    });
</script>

<?php include 'inc/template_end.php'; ?>