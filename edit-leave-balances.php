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
$sql = mysqli_query($db, "SELECT * FROM tbl_leave_balances");
while ($row = mysqli_fetch_assoc($sql)) {
    if ($fid == md5($row['employee_number'])) {
        $rid = $row['employee_number'];
    }
}
?>
<!-- Page content -->
<div id="page-content">
    <!-- eCommerce Dashboard Header -->
    <div class="content-header">
        <div class="header-section">
            <h1>
                <i class="gi gi-wallet"></i><strong>View Leave Balances</strong>
            </h1>

        </div>
    </div>
    <div class="block full">
        <div class="block-title">
            <h2><strong>Leave</strong> Balances</h2>
        </div>
        <?php
        $get_details = mysqli_query($db, "SELECT * FROM tbl_leave_balances WHERE employee_number = '$rid'");
        while ($r = mysqli_fetch_assoc($get_details)) {
        ?>
            <div class="container-fluid">
                <?= $res ?>
                <form method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Employee Number</label>
                                <input type="text" name="employee_number" class="form-control" readonly value="<?= $rid ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Employee Name</label>
                                <input type="text" name="employee_name" class="form-control" readonly value="<?= $r['emp_name'] ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>SL</label>
                                <input type="text" name="sl" id="sl" class="form-control" value="<?= $r['SL'] ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>VL</label>
                                <input type="text" name="vl" id="vl" class="form-control" value="<?= $r['VL'] ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Others</label>
                                <input type="text" name="el" id="others" class="form-control" value="<?= $r['others'] ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>APE</label>
                                <input type="text" name="ape" id="ape" class="form-control" value="<?= $r['ape'] ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>HP</label>
                                <input type="text" name="hp" id="hp" class="form-control" value="<?= $r['hp'] ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>BL</label>
                                <input type="text" name="bl" id="bl" class="form-control" value="<?= $r['bl'] ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Maternity</label>
                                <input type="text" name="maternity" id="maternity" class="form-control" value="<?= $r['maternity'] ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Paternity</label>
                                <input type="text" name="paternity" id="paternity" class="form-control" value="<?= $r['paternity'] ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Solo Parent</label>
                                <input type="text" name="solo_parent" id="solo_parent" class="form-control" value="<?= $r['solo_parent'] ?>">
                            </div>
                        </div>
                    </div>

                    <button class="btn btn-success" name="btn_update_leave_balances" formnovalidate>Update</button>&nbsp;
                    <a href="javascript:void(0)" class="btn btn-warning" onclick="$('#modal-forfeit').modal('show');">Forfeit</a>&nbsp;
                    <a href="javascript:void(0)" class="btn btn-warning" onclick="$('#modal-commute').modal('show');">Commute</a>&nbsp;

                    <!-- MODAL FOR FORFEIT -->
                    <div id="modal-forfeit" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header text-center">
                                    <h2 class="modal-title"><i class="fa fa-file"></i>&nbsp; Forfeit of Leaves</h2>
                                </div>
                                <div class="modal-body">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Employee Number</label>
                                                    <input type="text" name="employee_number" id="employee_number" class="form-control" readonly value="<?= $rid ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Employee Name</label>
                                                    <input type="text" name="employee_name" class="form-control" readonly value="<?= $r['emp_name'] ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Leave Type</label>
                                                    <select name="leave_type" id="leave_type" required class="select-chosen" data-placeholder="Choose a Leave Type..." style="width: 250px;" onchange="getAmountofLeave()">
                                                        <option></option>
                                                        <option value="VL">Vacation Leave</option>
                                                        <option value="SL">Sick Leave</option>
                                                        <option value="others">Others</option>
                                                        <option value="paternity">Paternity</option>
                                                        <option value="solo_parent">Solo Parent</option>
                                                    </select>
                                                    <div id="leave_type_others_wrapper" hidden>
                                                        <select name="leave_type_others" id="leave_type_others" class="select-chosen" data-placeholder="Choose other Leave Type..." style="width: 250px;">
                                                            <option></option>
                                                            <option value="ape">APE</option>
                                                            <option value="hp">HP</option>
                                                            <option value="bl">BL</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Leave Amount</label>
                                                    <input type="text" name="amt_val" id="amt_val" class="form-control" step="any" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Amount to Forfeit</label>
                                                    <input type="number" name="amount_forfeit" id="amount_forfeit" class="form-control" step="any" required>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <center>
                                            <button class="btn btn-success" name="forfeit_leave" style="width: 200px;">Forfeit</button>&nbsp;&nbsp;
                                            <button class="btn btn-warning" name="forfeit_all" style="width: 200px;" formnovalidate>Forfeit ALL</button>
                                        </center>
                                        <br>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        <?php
        }
        ?>
    </div>
    <div class="block full">

        <div class="block-title">

            <h2><strong>History of Leaves</strong></h2>
            <input type="hidden" value="<?= $monthnow ?>">
            <input type="hidden" value="<?= $newDate ?>">
        </div>

        <div class="table-responsive">
            <table id="company-job-grade" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr class="text-center">
                        <th class="text-center">Account Name</th>
                        <th class="text-center">Employee Number</th>
                        <th class="text-center">Leave Type</th>
                        <th class="text-center">Date Filed</th>
                        <th class="text-center">Date Requested</th>
                        <th class="text-center">Total Day(s)</th>
                        <th class="text-center">Duration</th>
                        <th class="text-center">Reason</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Leave w/o Pay</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <?php
                    $get_user = mysqli_query($db, "SELECT * FROM tbl_users WHERE employee_number = '$rid'");
                    while ($row = mysqli_fetch_assoc($get_user)) {
                        $employee_number = $row['employee_number'];
                    }
                    $sql = mysqli_query($db, "SELECT * FROM tbl_leave_requests WHERE delegated_emp_number = '$employee_number'");
                    while ($row = mysqli_fetch_assoc($sql)) {
                    ?>
                        <tr>
                            <td><?= $row['emp_name'] ?></td>
                            <td><?= $row['delegated_emp_number'] ?></td>
                            <td><?= $row['leave_type'] ?></td>
                            <td><?= $row['date_filed'] ?></td>
                            <td><?= $row['startDate'] . ' >> ' . $row['endDate'] ?></td>
                            <td><?= $row['total_day'] ?></td>
                            <td><?= $row['duration'] ?></td>
                            <td><?= $row['reason'] ?></td>
                            <td><?= $row['status'] ?></td>
                            <td><?= $row['leave_wo_pay_days'] ?></td>


                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="block full">
                <div class="block-title">

                    <h2><strong>History of Forfeited Leaves</strong></h2>
                    <input type="hidden" value="<?= $monthnow ?>">
                    <input type="hidden" value="<?= $newDate ?>">
                </div>

                <div class="table-responsive">
                    <table id="company-departments" class="table table-vcenter table-condensed table-bordered">
                        <thead>
                            <tr class="text-center">
                                <th class="text-center">Employee Name</th>
                                <th class="text-center">Employee Number</th>
                                <th class="text-center">Leave Type</th>
                                <th class="text-center">Amount Forfeited</th>
                                <th class="text-center">Date Created</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <?php
                            $sql = mysqli_query($db, "SELECT * FROM tbl_forfeit WHERE employee_number = '$rid'");
                            while ($row = mysqli_fetch_assoc($sql)) {
                            ?>
                                <tr>
                                    <td><?= $row['employee_number'] ?></td>
                                    <td><?= $row['employee_name'] ?></td>
                                    <td><?= $row['leave_type'] ?></td>
                                    <td><?= $row['amount'] ?></td>
                                    <td><?= $row['created_date'] ?></td>


                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="block full">
                <div class="block-title">

                    <h2><strong>History of Commuted Leaves</strong></h2>
                    <input type="hidden" value="<?= $monthnow ?>">
                    <input type="hidden" value="<?= $newDate ?>">
                </div>

                <div class="table-responsive">
                    <table id="company-management" class="table table-vcenter table-condensed table-bordered">
                        <thead>
                            <tr class="text-center">
                                <th class="text-center">Employee Name</th>
                                <th class="text-center">Employee Number</th>
                                <th class="text-center">Leave Type</th>
                                <th class="text-center">Amount Commuted</th>
                                <th class="text-center">Date Created</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <?php
                            $sql = mysqli_query($db, "SELECT * FROM tbl_commute WHERE employee_number = '$rid'");
                            while ($row = mysqli_fetch_assoc($sql)) {
                            ?>
                                <tr>
                                    <td><?= $row['employee_number'] ?></td>
                                    <td><?= $row['employee_name'] ?></td>
                                    <td><?= $row['leave_type'] ?></td>
                                    <td><?= $row['amount'] ?></td>
                                    <td><?= $row['created_date'] ?></td>


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
    <!-- MODAL FOR COMMUTE -->
    <form method="POST">
        <div id="modal-commute" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h2 class="modal-title"><i class="fa fa-file"></i>&nbsp; Commute of Leaves</h2>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Employee Number</label>
                                        <input type="text" name="employee_number" id="employee_number" class="form-control" readonly value="<?= $rid ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php
                                        $get_name = mysqli_query($db, "SELECT * FROM tbl_users WHERE employee_number = '$rid'");
                                        while ($row = mysqli_fetch_assoc($get_name)) {
                                            $account_name = $row['account_name'];
                                        }
                                        ?>
                                        <label>Employee Name</label>
                                        <input type="text" name="employee_name" class="form-control" readonly value="<?= $account_name ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Leave Type</label>
                                        <select name="leave_types" id="leave_types" required class="select-chosen" data-placeholder="Choose a Leave Type..." style="width: 250px;" onchange="getAmountofLeaves()">
                                            <option value="null"></option>
                                            <option value="VL">Vacation Leave</option>
                                            <!-- <option value="EL">Others</option> -->
                                            <!-- <option value="SL">Sick Leave</option>
                                                        <option value="EL">Others</option>
                                                        <option value="maternity">Maternity</option>
                                                        <option value="paternity">Paternity</option>
                                                        <option value="solo_parent">Solo Parent</option> -->
                                        </select>
                                        <div id="leave_type_others_wrapper" hidden>
                                            <select name="leave_type_others" id="leave_type_others" class="select-chosen" data-placeholder="Choose other Leave Type..." style="width: 250px;">
                                                <option></option>
                                                <option value="ape">APE</option>
                                                <option value="hp">HP</option>
                                                <option value="bl">BL</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Leave Amount</label>
                                        <input type="text" name="amt_vals" id="amt_vals" class="form-control" step="any" readonly value="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Amount to Commute</label>
                                        <input type="number" name="amount_forfeits" id="amount_forfeits" class="form-control" step="any" required>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <center>
                                <button class="btn btn-success" name="commute_leave" style="width: 200px;">Commute</button>&nbsp;&nbsp;
                                <!-- <button class="btn btn-warning" name="commute_all" style="width: 200px;" formnovalidate>Commute ALL</button> -->
                            </center>
                            <br>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>

<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>
<?php include 'inc/template_end.php'; ?>
<script src="js/pages/tablesDatatables.js"></script>
<script>
    function getAmountofLeave() {
        var sl = $('#sl').val();
        var vl = $('#vl').val();
        var others = $('#others').val();
        var maternity = $('#maternity').val();
        var paternity = $('#paternity').val();
        var solo_parent = $('#solo_parent').val();
        var leave_type = $('#leave_type').val();
        $('#leave_type_others_wrapper').hide();
        if (leave_type == 'SL') {
            document.getElementById("amt_val").value = sl;

        } else if (leave_type == 'VL') {
            document.getElementById("amt_val").value = vl;

        } else if (leave_type == 'others') {
            document.getElementById("amt_val").value = others;
            $('#leave_type_others_wrapper').show();
        } else if (leave_type == 'maternity') {
            document.getElementById("amt_val").value = maternity;

        } else if (leave_type == 'paternity') {
            document.getElementById("amt_val").value = paternity;

        } else if (leave_type == 'solo_parent') {
            document.getElementById("amt_val").value = solo_parent;

        }


    }
    $('#leave_type_others').on('change', function() {
        const ape = $('#ape').val();
        const hp = $('#hp').val();
        const bl = $('#bl').val();
        switch ($(this).val()) {
            case "ape":
                document.getElementById("amt_val").value = ape;
                break;
            case "hp":
                document.getElementById("amt_val").value = hp;
                break;
            case "bl":
                document.getElementById("amt_val").value = bl;
                break;
        }
    });

    $('#amount_forfeit').focusin(function() {
        var sl = $('#sl').val();
        var vl = $('#vl').val();
        var el = $('#el').val();
        var maternity = $('#maternity').val();
        var paternity = $('#paternity').val();
        var solo_parent = $('#solo_parent').val();
        var leave_type = $('#leave_type').val();
        var amount_forfeit = $('#amount_forfeit').val();

        if (leave_type == 'SL') {
            $('#amount_forfeit').attr('max', sl);

        } else if (leave_type == 'VL') {
            $('#amount_forfeit').attr('max', vl);

        } else if (leave_type == 'EL') {
            $('#amount_forfeit').attr('max', el);

        } else if (leave_type == 'maternity') {
            $('#amount_forfeit').attr('max', maternity);

        } else if (leave_type == 'paternity') {
            $('#amount_forfeit').attr('max', paternity);

        } else if (leave_type == 'solo_parent') {
            $('#amount_forfeit').attr('max', solo_parent);

        }

    });


    function getAmountofLeaves() {
        var vl = $('#vl').val();
        document.getElementById("amt_vals").value = vl;

    }
    $('#amount_forfeits').focusin(function() {
        var vl = $('#vl').val();

        $('#amount_forfeits').attr('max', vl);

    });
</script>
<script>
    $(function() {
        TablesDatatables.init();
    });
</script>