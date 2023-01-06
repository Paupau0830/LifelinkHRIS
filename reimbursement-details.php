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
$sql = mysqli_query($db, "SELECT * FROM tbl_benefits_reimbursement");
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
                <i class="fa fa-exchange"></i>Benefits Reimbursement Details
            </h1>
        </div>
    </div>
    <div class="block full">
        <div class="block-title">
            <div class="block-options pull-right">
                <a href="reimbursement-list" class="btn btn-alt btn-sm btn-default">Benefits Reimbursement List</a>
            </div>
            <h2><strong>Benefits Reimbursement</strong> Details</h2>
        </div>
        <div class="container-fluid">
            <p class="text-danger" id="res"></p>
            <?= $res ?>
            <form method="POST" enctype="multipart/form-data">
                <?php
                $sql = mysqli_query($db, "SELECT * FROM tbl_benefits_reimbursement WHERE ID = '$rid'");
                while ($details = mysqli_fetch_assoc($sql)) {
                    $employee_number = $details['payee'];
                    $company_id = $details['company_id'];
                    $stat = '';
                    if ($details['status'] == 'Approved') {
                        $stat = 'Approved';
                    } elseif ($details['status'] == 'Declined') {
                        $stat = 'Declined';
                    } elseif ($details['status'] == 'Cancelled') {
                        $stat = 'Cancelled';
                    } elseif ($details['status'] == 'Update Requested') {
                        $stat = 'Update Requested';
                    } else {
                        $stat = 'Pending - ' . get_benefits_role_name($details['status'])['role'];
                    }

                    $personal_info = get_personal_information($details['payee']);
                    $req_personal_info = get_personal_information($details['requestor']);
                ?>
                    <input type="hidden" name="id" value="<?= $rid ?>">
                    <input type="hidden" name="company_id" value="<?= $company_id ?>">
                    <input type="hidden" name="status" value="<?= $details['status'] ?>">
                    <input type="hidden" name="em_status" value="<?= get_benefits_role_name($details['status'])['role'] ?>">
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
                                        if ($row['employee_number'] == $details['payee']) {
                                            echo '<option selected value="' . $row['employee_number'] . '">' . $row['account_name'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Requestor</label>
                                <input type="text" readonly class="form-control" value="<?= $personal_info['account_name'] ?>">
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
                    </div>
                    <div class="block full">
                        <!-- Working Tabs Title -->
                        <div class="block-title">
                            <ul class="nav nav-tabs push" data-toggle="tabs" id="benefits_categories">
                                <?php
                                $sql = mysqli_query($db, "SELECT * FROM tbl_benefits_eligibility WHERE employee_number = '$employee_number'");
                                if ($r = mysqli_fetch_assoc($sql)) {
                                    if ($r['parking'] == '1') {
                                        echo '<li><a href="#tab_parking"><i class="gi gi-cars" style="margin-right: 3px;"></i> Parking</a></li>';
                                    }
                                    if ($r['gasoline'] == '1') {
                                        echo '<li><a href="#tab_gas"><i class="gi gi-tint" style="margin-right: 3px;"></i> Gasoline</a></li>';
                                    }
                                    if ($r['car_maintenance'] == '1') {
                                        echo '<li><a href="#tab_car"><i class="fa fa-car" style="margin-right: 3px;"></i> Car Maintenance</a></li>';
                                    }
                                    if ($r['medicine'] == '1') {
                                        echo '<li><a href="#tab_medical"><i class="gi gi-hospital" style="margin-right: 3px;"></i> Medical</a></li>';
                                    }
                                    if ($r['gym'] == '1') {
                                        echo '<li class="active"><a href="#tab_gym"><i class="gi gi-bicycle" style="margin-right: 3px;"></i> Gym</a></li>';
                                    }
                                    if ($r['optical_allowance'] == '1') {
                                        echo '<li><a href="#tab_optical"><i class="fa fa-eye" style="margin-right: 3px;"></i> Optical</a></li>';
                                    }
                                    if ($r['cep'] == '1') {
                                        echo '<li><a href="#tab_cep"><i class="fa fa-graduation-cap" style="margin-right: 3px;"></i> CEP</a></li>';
                                    }
                                    if ($r['club_membership'] == '1') {
                                        echo '<li><a href="#tab_club"><i class="fa fa-building" style="margin-right: 3px;"></i> Club Membership</a></li>';
                                    }
                                    if ($r['maternity'] == '1') {
                                        echo '<li><a href="#tab_maternity"><i class="gi gi-parents" style="margin-right: 3px;"></i> Maternity</a></li>';
                                    }
                                    if ($r['others'] == '1') {
                                        echo '<li><a href="#tab_others"><i class="fa fa-navicon" style="margin-right: 3px;"></i> Others</a></li>';
                                    }
                                }
                                ?>
                            </ul>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane" id="tab_parking">
                                <h4>Parking</h4>
                                <hr>
                                <div class="form-group">
                                    <label>Total Requested Amount</label>
                                    <input type="text" name="parking_total" readonly id="parking_total" class="form-control total_cat" value="<?= number_format(get_benefits_category_amount($rid, 'Parking'), 2); ?>">
                                </div>
                                <table class="table table-bordered">
                                    <thead>
                                        <th>Requested Amount</th>
                                        <th>Remarks</th>
                                        <th class="text-center">Attachment</th>
                                        <th class="text-center">Action</th>
                                    </thead>
                                    <tbody id="table-parking">
                                        <?php
                                        $get_parking = mysqli_query($db, "SELECT * FROM tbl_benefits_form WHERE benefits_id = '$rid' AND cat = 'Parking' ORDER BY ID DESC");
                                        while ($row = mysqli_fetch_assoc($get_parking)) {
                                        ?>
                                            <tr>
                                                <td><?= number_format($row['amount'], 2) ?></td>
                                                <td><?= $row['remarks'] ?></td>
                                                <td class="text-center"><a target="_blank" href="uploads/<?= $row['attachment'] ?>">View</a></td>
                                                <td class="text-center">
                                                    <form method="POST">
                                                        <input type="hidden" name="parking_id" value="<?= $row['ID'] ?>">
                                                        <input type="hidden" name="parking_amount" value="<?= $row['amount'] ?>">
                                                        <input type="hidden" name="benefits_id" value="<?= $rid ?>">
                                                        <button class="btn btn-xs btn-default" name="btn_delete_parking"><i class="fa fa-trash"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <button type="button" onclick="$('#modal-add-parking').modal('show');" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add Parking</button>
                            </div>
                            <div class="tab-pane" id="tab_gas">
                                <h4>Gasoline</h4>
                                <hr>
                                <p id="w_gas" class="text-danger"></p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Reimbursable Liters</label>
                                            <input type="number" id="reimbusesable_liters" step=".01" readonly class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Total Requested Amount</label>
                                            <input type="text" name="gas_total" step=".01" id="gas_total" readonly class="form-control total_cat" value="<?= number_format(get_benefits_category_amount($rid, 'Gas'), 2); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Requested Liters</label>
                                    <input type="number" name="requested_liters" id="requested_liters" step=".01" class="form-control" value="<?= get_requested_liters($rid) ?>">
                                </div>
                                <button class="btn btn-primary btn-sm" name="update_requested_liters">Update</button>
                                <br><br>
                                <table class="table table-bordered">
                                    <thead>
                                        <th>Requested Amount</th>
                                        <th>Remarks</th>
                                        <th class="text-center">Attachment</th>
                                        <th class="text-center">Action</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $get_gas = mysqli_query($db, "SELECT * FROM tbl_benefits_form WHERE benefits_id = '$rid' AND cat = 'Gas' ORDER BY ID DESC");
                                        while ($row = mysqli_fetch_assoc($get_gas)) {
                                        ?>
                                            <tr>
                                                <td><?= number_format($row['amount'], 2) ?></td>
                                                <td><?= $row['remarks'] ?></td>
                                                <td class="text-center"><a target="_blank" href="uploads/<?= $row['attachment'] ?>">View</a></td>
                                                <td class="text-center">
                                                    <form method="POST">
                                                        <input type="hidden" name="gas_id" value="<?= $row['ID'] ?>">
                                                        <input type="hidden" name="gas_amount" value="<?= $row['amount'] ?>">
                                                        <input type="hidden" name="benefits_id" value="<?= $rid ?>">
                                                        <button class="btn btn-xs btn-default" name="btn_delete_gas"><i class="fa fa-trash"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <button type="button" onclick="$('#modal-add-gas').modal('show');" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add Gasoline</button>
                            </div>
                            <div class="tab-pane" id="tab_car">
                                <h4>Car Maintenance</h4>
                                <hr>
                                <p id="w_car" class="text-danger"></p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Car Maintenance Balance</label>
                                            <input type="number" id="car_maintenance_balance" step=".01" readonly class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Total Requested Amount</label>
                                            <input type="text" name="car_total" id="car_total" readonly class="form-control total_cat" value="<?= number_format(get_benefits_category_amount($rid, 'Car'), 2); ?>">
                                        </div>
                                    </div>
                                </div>
                                <table class="table table-bordered">
                                    <thead>
                                        <th>Requested Amount</th>
                                        <th>Remarks</th>
                                        <th class="text-center">Attachment</th>
                                        <th class="text-center">Action</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $get_car = mysqli_query($db, "SELECT * FROM tbl_benefits_form WHERE benefits_id = '$rid' AND cat = 'Car' ORDER BY ID DESC");
                                        while ($row = mysqli_fetch_assoc($get_car)) {
                                        ?>
                                            <tr>
                                                <td><?= number_format($row['amount'], 2) ?></td>
                                                <td><?= $row['remarks'] ?></td>
                                                <td class="text-center"><a target="_blank" href="uploads/<?= $row['attachment'] ?>">View</a></td>
                                                <td class="text-center">
                                                    <form method="POST">
                                                        <input type="hidden" name="car_id" value="<?= $row['ID'] ?>">
                                                        <input type="hidden" name="car_amount" value="<?= $row['amount'] ?>">
                                                        <input type="hidden" name="benefits_id" value="<?= $rid ?>">
                                                        <button class="btn btn-xs btn-default" name="btn_delete_car"><i class="fa fa-trash"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <button type="button" onclick="$('#modal-add-car').modal('show');" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add Car Benefits</button>
                            </div>
                            <div class="tab-pane" id="tab_medical">
                                <h4>Medical</h4>
                                <hr>
                                <p id="w_medical" class="text-danger"></p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Medical Balance</label>
                                            <input type="number" step=".01" id="medical_balance" readonly class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Total Requested Amount</label>
                                            <input type="text" name="medical_total" id="medical_total" readonly class="form-control total_cat" value="<?= number_format(get_benefits_category_amount($rid, 'Medical'), 2); ?>">
                                        </div>
                                    </div>
                                </div>
                                <table class="table table-bordered">
                                    <thead>
                                        <th>Requested Amount</th>
                                        <th>Remarks</th>
                                        <th class="text-center">Attachment</th>
                                        <th class="text-center">Action</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $get_medical = mysqli_query($db, "SELECT * FROM tbl_benefits_form WHERE benefits_id = '$rid' AND cat = 'Medical' ORDER BY ID DESC");
                                        while ($row = mysqli_fetch_assoc($get_medical)) {
                                        ?>
                                            <tr>
                                                <td><?= number_format($row['amount'], 2) ?></td>
                                                <td><?= $row['remarks'] ?></td>
                                                <td class="text-center"><a target="_blank" href="uploads/<?= $row['attachment'] ?>">View</a></td>
                                                <td class="text-center">
                                                    <form method="POST">
                                                        <input type="hidden" name="medical_id" value="<?= $row['ID'] ?>">
                                                        <input type="hidden" name="medical_amount" value="<?= $row['amount'] ?>">
                                                        <input type="hidden" name="benefits_id" value="<?= $rid ?>">
                                                        <button class="btn btn-xs btn-default" name="btn_delete_medical"><i class="fa fa-trash"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <button type="button" onclick="$('#modal-add-medical').modal('show');" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add Car Benefits</button>
                            </div>
                            <div class="tab-pane active" id="tab_gym">
                                <h4>Gym</h4>
                                <hr>
                                <p id="w_gym" class="text-danger"></p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Gym Balance</label>
                                            <input type="number" step=".01" id="gym_balance" readonly class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Total Requested Amount</label>
                                            <input type="text" name="gym_total" id="gym_total" readonly class="form-control total_cat" value="<?= number_format(get_benefits_category_amount($rid, 'Gym'), 2); ?>">
                                        </div>
                                    </div>
                                </div>
                                <table class="table table-bordered">
                                    <thead>
                                        <th>Requested Amount</th>
                                        <th>Remarks</th>
                                        <th class="text-center">Attachment</th>
                                        <th class="text-center">Action</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $get_gym = mysqli_query($db, "SELECT * FROM tbl_benefits_form WHERE benefits_id = '$rid' AND cat = 'Gym' ORDER BY ID DESC");
                                        while ($row = mysqli_fetch_assoc($get_gym)) {
                                        ?>
                                            <tr>
                                                <td><?= number_format($row['amount'], 2) ?></td>
                                                <td><?= $row['remarks'] ?></td>
                                                <td class="text-center"><a target="_blank" href="uploads/<?= $row['attachment'] ?>">View</a></td>
                                                <td class="text-center">
                                                    <form method="POST">
                                                        <input type="hidden" name="gym_id" value="<?= $row['ID'] ?>">
                                                        <input type="hidden" name="gym_amount" value="<?= $row['amount'] ?>">
                                                        <input type="hidden" name="benefits_id" value="<?= $rid ?>">
                                                        <button class="btn btn-xs btn-default" name="btn_delete_gym"><i class="fa fa-trash"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <button type="button" onclick="$('#modal-add-gym').modal('show');" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add Gym Benefits</button>
                            </div>
                            <div class="tab-pane" id="tab_optical">
                                <h4>Optical Allowance</h4>
                                <hr>
                                <p id="w_optical" class="text-danger"></p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Optical Balance</label>
                                            <input type="number" step=".01" id="optical_balance" readonly class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Total Requested Amount</label>
                                            <input type="text" name="optical_total" id="optical_total" readonly class="form-control total_cat" value="<?= number_format(get_benefits_category_amount($rid, 'Optical'), 2); ?>">
                                        </div>
                                    </div>
                                </div>
                                <table class="table table-bordered">
                                    <thead>
                                        <th>Requested Amount</th>
                                        <th>Remarks</th>
                                        <th class="text-center">Attachment</th>
                                        <th class="text-center">Action</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $get_optical = mysqli_query($db, "SELECT * FROM tbl_benefits_form WHERE benefits_id = '$rid' AND cat = 'Optical' ORDER BY ID DESC");
                                        while ($row = mysqli_fetch_assoc($get_optical)) {
                                        ?>
                                            <tr>
                                                <td><?= number_format($row['amount'], 2) ?></td>
                                                <td><?= $row['remarks'] ?></td>
                                                <td class="text-center"><a target="_blank" href="uploads/<?= $row['attachment'] ?>">View</a></td>
                                                <td class="text-center">
                                                    <form method="POST">
                                                        <input type="hidden" name="optical_id" value="<?= $row['ID'] ?>">
                                                        <input type="hidden" name="optical_amount" value="<?= $row['amount'] ?>">
                                                        <input type="hidden" name="benefits_id" value="<?= $rid ?>">
                                                        <button class="btn btn-xs btn-default" name="btn_delete_optical"><i class="fa fa-trash"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <button type="button" onclick="$('#modal-add-optical').modal('show');" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add Optical Benefits</button>
                            </div>
                            <div class="tab-pane" id="tab_cep">
                                <h4>CEP</h4>
                                <hr>
                                <p id="w_cep" class="text-danger"></p>
                                <p id="cep_note" class="text-danger"></p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>CEP Balance</label>
                                            <input type="number" step=".01" id="cep_balance" readonly class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Total Requested Amount</label>
                                            <input type="text" name="cep_total" id="cep_total" readonly class="form-control total_cat" value="<?= number_format(get_benefits_category_amount($rid, 'CEP'), 2); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <?php
                                    $get_cep_details = mysqli_query($db, "SELECT * FROM tbl_cep_bond WHERE benefits_id = '$rid'");
                                    $cep_details = mysqli_fetch_assoc($get_cep_details);
                                    ?>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Type</label>
                                            <select name="cep_type" id="cep_type" class="select-chosen" data-placeholder="Choose a CEP type..." style="width: 250px;">
                                                <option></option>
                                                <?php
                                                $cep_types = array('CEP', 'Training');
                                                foreach ($cep_types as $k => $v) {
                                                    if ($v == $cep_details['type']) {
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
                                            <label>Premise</label>
                                            <select name="cep_premise" id="cep_premise" class="select-chosen" data-placeholder="Choose a premise..." style="width: 250px;">
                                                <option></option>
                                                <?php
                                                $cep_premises = array('Local', 'International');
                                                foreach ($cep_premises as $k => $v) {
                                                    if ($v == $cep_details['premise']) {
                                                        echo '<option selected>' . $v . '</option>';
                                                    } else {
                                                        echo '<option>' . $v . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-primary" name="update_cep_bond">Update</button>
                                <br><br>
                                <table class="table table-bordered">
                                    <thead>
                                        <th>Requested Amount</th>
                                        <th>Remarks</th>
                                        <th class="text-center">Attachment</th>
                                        <th class="text-center">Action</th>
                                    </thead>
                                    <tbody id="cep_tbody">
                                        <?php
                                        $get_cep = mysqli_query($db, "SELECT * FROM tbl_benefits_form WHERE benefits_id = '$rid' AND cat = 'CEP' ORDER BY ID DESC");
                                        while ($row = mysqli_fetch_assoc($get_cep)) {
                                        ?>
                                            <tr>
                                                <td><?= number_format($row['amount'], 2) ?></td>
                                                <td><?= $row['remarks'] ?></td>
                                                <td class="text-center"><a target="_blank" href="uploads/<?= $row['attachment'] ?>">View</a></td>
                                                <td class="text-center">
                                                    <form method="POST">
                                                        <input type="hidden" name="cep_id" value="<?= $row['ID'] ?>">
                                                        <input type="hidden" name="cep_amount" value="<?= $row['amount'] ?>">
                                                        <input type="hidden" name="benefits_id" value="<?= $rid ?>">
                                                        <button class="btn btn-xs btn-default" name="btn_delete_cep"><i class="fa fa-trash"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <button type="button" onclick="$('#modal-add-cep').modal('show');" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add CEP Benefits</button>
                            </div>
                            <div class="tab-pane" id="tab_club">
                                <h4>Club Membership</h4>
                                <hr>
                                <div class="form-group">
                                    <label>Total Requested Amount</label>
                                    <input type="text" name="club_total" readonly id="club_total" class="form-control total_cat" value="<?= number_format(get_benefits_category_amount($rid, 'Club'), 2); ?>">
                                </div>
                                <table class="table table-bordered">
                                    <thead>
                                        <th>Requested Amount</th>
                                        <th>Remarks</th>
                                        <th class="text-center">Attachment</th>
                                        <th class="text-center">Action</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $get_club = mysqli_query($db, "SELECT * FROM tbl_benefits_form WHERE benefits_id = '$rid' AND cat = 'Club' ORDER BY ID DESC");
                                        while ($row = mysqli_fetch_assoc($get_club)) {
                                        ?>
                                            <tr>
                                                <td><?= number_format($row['amount'], 2) ?></td>
                                                <td><?= $row['remarks'] ?></td>
                                                <td class="text-center"><a target="_blank" href="uploads/<?= $row['attachment'] ?>">View</a></td>
                                                <td class="text-center">
                                                    <form method="POST">
                                                        <input type="hidden" name="club_id" value="<?= $row['ID'] ?>">
                                                        <input type="hidden" name="club_amount" value="<?= $row['amount'] ?>">
                                                        <input type="hidden" name="benefits_id" value="<?= $rid ?>">
                                                        <button class="btn btn-xs btn-default" name="btn_delete_club"><i class="fa fa-trash"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <button type="button" onclick="$('#modal-add-club').modal('show');" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add Club Benefits</button>
                            </div>
                            <div class="tab-pane" id="tab_maternity">
                                <h4>Matenity</h4>
                                <hr>
                                <p id="w_maternity" class="text-danger"></p>
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>Total Requested Amount</label>
                                            <input type="text" name="maternity_total" id="maternity_total" readonly class="form-control total_cat" value="<?= number_format(get_benefits_category_amount($rid, 'Club'), 2); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>Type</label>
                                            <select name="maternity_type" id="maternity_type" class="select-chosen" data-placeholder="Choose a maternity type..." style="width: 250px;">
                                                <option></option>
                                                <option value="Normal">Normal</option>
                                                <option value="CS">Cesarean</option>
                                                <option value="Misc">Miscrarriage / Medically Necessary Abortion</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Max</label>
                                            <input type="text" class="form-control" readonly id="maternity_max">
                                        </div>
                                    </div>
                                </div>
                                <table class="table table-bordered">
                                    <thead>
                                        <th>Requested Amount</th>
                                        <th>Remarks</th>
                                        <th class="text-center">Attachment</th>
                                        <th class="text-center">Action</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $get_maternity = mysqli_query($db, "SELECT * FROM tbl_benefits_form WHERE benefits_id = '$rid' AND cat = 'Maternity' ORDER BY ID DESC");
                                        while ($row = mysqli_fetch_assoc($get_maternity)) {
                                        ?>
                                            <tr>
                                                <td><?= number_format($row['amount'], 2) ?></td>
                                                <td><?= $row['remarks'] ?></td>
                                                <td class="text-center"><a target="_blank" href="uploads/<?= $row['attachment'] ?>">View</a></td>
                                                <td class="text-center">
                                                    <form method="POST">
                                                        <input type="hidden" name="maternity_id" value="<?= $row['ID'] ?>">
                                                        <input type="hidden" name="maternity_amount" value="<?= $row['amount'] ?>">
                                                        <input type="hidden" name="benefits_id" value="<?= $rid ?>">
                                                        <button class="btn btn-xs btn-default" name="btn_delete_maternity"><i class="fa fa-trash"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <button type="button" onclick="$('#modal-add-maternity').modal('show');" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add Maternity Benefits</button>
                            </div>
                            <div class="tab-pane" id="tab_others">
                                <h4>Others</h4>
                                <hr>
                                <div class="form-group">
                                    <label>Total Requested Amount</label>
                                    <input type="text" name="others_total" readonly id="others_total" class="form-control total_cat" value="<?= number_format(get_benefits_category_amount($rid, 'Club'), 2); ?>">
                                </div>
                                <table class="table table-bordered">
                                    <thead>
                                        <th>Requested Amount</th>
                                        <th>Remarks</th>
                                        <th class="text-center">Attachment</th>
                                        <th class="text-center">Action</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $get_others = mysqli_query($db, "SELECT * FROM tbl_benefits_form WHERE benefits_id = '$rid' AND cat = 'Others' ORDER BY ID DESC");
                                        while ($row = mysqli_fetch_assoc($get_others)) {
                                        ?>
                                            <tr>
                                                <td><?= number_format($row['amount'], 2) ?></td>
                                                <td><?= $row['remarks'] ?></td>
                                                <td class="text-center"><a target="_blank" href="uploads/<?= $row['attachment'] ?>">View</a></td>
                                                <td class="text-center">
                                                    <form method="POST">
                                                        <input type="hidden" name="others_id" value="<?= $row['ID'] ?>">
                                                        <input type="hidden" name="others_amount" value="<?= $row['amount'] ?>">
                                                        <input type="hidden" name="benefits_id" value="<?= $rid ?>">
                                                        <button class="btn btn-xs btn-default" name="btn_delete_others"><i class="fa fa-trash"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <button type="button" onclick="$('#modal-add-others').modal('show');" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add Others Benefits</button>
                                <br>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Amount in Figures</label>
                                <input type="number" name="amount" id="amount" class="form-control" readonly value="<?= $details['amount'] ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Payment For</label>
                                <input type="text" name="payment_for" class="form-control" required value="<?= $details['payment_for'] ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Special Instructions</label>
                        <textarea name="special_instruction" class="form-control" rows="3"><?= $details['special_instruction'] ?></textarea>
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
                            if ($details['payee'] == $_SESSION['hris_employee_number']) {
                                if ($details['status'] != "Cancelled") {
                                    if ($details['status'] == 'Update Requested') {
                                        echo '<button class="btn btn-primary" name="btn_user_update_benefits">Update</button>';
                                    }
                                    echo '<button class="btn btn-danger" name="btn_cancel_benefits">Cancel</button>';
                                }
                            }
                        } else {
                            if ($details['status'] == 'Update Requested') {
                            } else {
                                if ($details['status'] != "Cancelled") {
                                    $current_approver = $details['status'];
                                    $approver_role_info = get_approvers_from_role($current_approver, $company_id);
                                    $approvers = get_benefits_approvers($company_id, $approver_role_info['ID']);
                                    $recipients_cc = $approver_role_info['cc'];
                                    $recipients_cc = explode(',', $recipients_cc);
                                    if (in_array($_SESSION['hris_email'], $recipients_cc) or $_SESSION['hris_id'] == $approvers[0]['user_id']) {
                                        echo '
                                    <button class="btn btn-primary" name="btn_update_benefits_reimbursement">Approve</button>
                                    <button class="btn btn-warning" name="btn_update_request_benefits">Request Update</button>
                                    <button class="btn" name="btn_decline_benefits_reimbursement">Decline</button>';
                                    }
                                }
                            }
                        }
                        ?>
                    </div>
                <?php
                }
                ?>
            </form>
        </div>
    </div>
</div>
<?php include 'inc/benefits-modal.php'; ?>
<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>
<?php include 'inc/template_end.php'; ?>
<script src="js/custom.js"></script>