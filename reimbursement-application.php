<?php include 'inc/config.php'; ?>
<?php include 'inc/template_start.php'; ?>
<?php include 'inc/page_head.php'; ?>
<?php
if (empty($_SESSION['hris_id'])) {
    header('Location: login');
}
$empnum = $_SESSION['hris_employee_number'];
?>
<style>
    @media only screen and (min-width: 992px) {
        .rem_button {
            margin-top: 25px;
        }
    }
</style>
<?php
$company_id = $_SESSION['hris_company_id'];
$employee_number = $_SESSION['hris_employee_number'];
?>
<div id="page-content">
    <div class="content-header">
        <div class="header-section">
            <h1>
                <i class="fa fa-exchange"></i>Benefits Reimbursement
            </h1>
        </div>
    </div>
    <div class="block full">
        <div class="block-title">
            <div class="block-options pull-right">
                <a href="reimbursement-list" class="btn btn-alt btn-sm btn-default">Benefits Reimbursement List</a>
            </div>
            <h2><strong>Benefits Reimbursement</strong> Form</h2>
        </div>
        <div class="container-fluid">
            <p class="text-danger" id="res"></p>
            <?= $res ?>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="employee_number" value="<?= $employee_number ?>">
                <input type="hidden" name="company_id" value="<?= $company_id ?>">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Payee *</label>
                            <select name="payee" id="payee" required class="select-chosen" data-placeholder="Choose a payee..." style="width: 250px;">
                                <option></option>
                                <?php
                                if ($_SESSION['hris_role'] == 'User') {
                                    if (allowed_on_behalf_filing($empnum) == '0') {
                                        echo '<option value="' . $empnum . '" selected>' . $_SESSION['hris_account_name'] . '</option>';
                                    } else {
                                        $get_employees = mysqli_query($db, "SELECT t.*, t1.company 
                                FROM tbl_personal_information t
                                INNER JOIN tbl_employment_information t1
                                ON t.employee_number = t1.employee_number
                                WHERE t1.company = '$company_id'");
                                        while ($row = mysqli_fetch_assoc($get_employees)) {
                                            if ($row['employee_number'] == $employee_number) {
                                                echo '<option selected value="' . $row['employee_number'] . '">' . $row['account_name'] . '</option>';
                                            } else {
                                                echo '<option value="' . $row['employee_number'] . '">' . $row['account_name'] . '</option>';
                                            }
                                        }
                                    }
                                } else {
                                    $get_employees = mysqli_query($db, "SELECT t.*, t1.company 
                                    FROM tbl_personal_information t
                                    INNER JOIN tbl_employment_information t1
                                    ON t.employee_number = t1.employee_number
                                    WHERE t1.company = '$company_id'");
                                    while ($row = mysqli_fetch_assoc($get_employees)) {
                                        if ($row['employee_number'] == $employee_number) {
                                            echo '<option selected value="' . $row['employee_number'] . '">' . $row['account_name'] . '</option>';
                                        } else {
                                            echo '<option value="' . $row['employee_number'] . '">' . $row['account_name'] . '</option>';
                                        }
                                    }
                                }
                                ?>
                            </select>
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
                                <input type="text" name="parking_total" readonly id="parking_total" class="form-control total_cat" value="0">
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Requested Amount</label>
                                        <input type="number" id="parking_requested_amount" name="parking_requested_amount[]" step=".01" class="form-control parking_requested_amount">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Remarks</label>
                                        <input type="text" name="parking_remarks[]" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Attachment</label>
                                        <input type="file" name="parking_attachment[]" class="form-control parking_attachment">
                                    </div>
                                </div>
                            </div>
                            <div id="div_add_parking"></div>
                            <button type="button" disabled class="add_parking btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add more</button>
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
                                        <input type="text" name="gas_total" step=".01" id="gas_total" readonly class="form-control total_cat" value="0">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Requested Liters</label>
                                <input type="number" name="requested_liters" id="requested_liters" step=".01" class="form-control">
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Requested Amount</label>
                                        <input type="number" step=".01" id="gas_requested_amount" name="gas_requested_amount[]" class="form-control gas_requested_amount">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Remarks</label>
                                        <input type="text" name="gas_remarks[]" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Attachment</label>
                                        <input type="file" name="gas_attachment[]" class="form-control gas_attachment">
                                    </div>
                                </div>
                            </div>
                            <div id="div_add_gas"></div>
                            <button type="button" disabled class="add_gas btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add more</button>
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
                                        <input type="text" name="car_total" id="car_total" readonly class="form-control total_cat" value="0">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Requested Amount</label>
                                        <input type="number" step=".01" id="car_requested_amount" name="car_requested_amount[]" class="form-control car_requested_amount">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Remarks</label>
                                        <input type="text" name="car_remarks[]" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Attachment</label>
                                        <input type="file" name="car_attachment[]" class="form-control car_attachment">
                                    </div>
                                </div>
                            </div>
                            <div id="div_add_car"></div>
                            <button type="button" disabled class="add_car btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add more</button>
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
                                        <input type="text" name="medical_total" id="medical_total" readonly class="form-control total_cat" value="0">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Requested Amount</label>
                                        <input type="number" step=".01" id="medical_requested_amount" name="medical_requested_amount[]" class="form-control medical_requested_amount">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Remarks</label>
                                        <input type="text" name="medical_remarks[]" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Attachment</label>
                                        <input type="file" name="medical_attachment[]" class="form-control medical_attachment">
                                    </div>
                                </div>
                            </div>
                            <div id="div_add_medical"></div>
                            <button type="button" disabled class="add_medical btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add more</button>
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
                                        <input type="text" name="gym_total" id="gym_total" readonly class="form-control total_cat" value="0">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Requested Amount</label>
                                        <input type="number" step=".01" id="gym_requested_amount" name="gym_requested_amount[]" class="form-control gym_requested_amount">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Remarks</label>
                                        <input type="text" name="gym_remarks[]" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Attachment</label>
                                        <input type="file" name="gym_attachment[]" class="form-control gym_attachment">
                                    </div>
                                </div>
                            </div>
                            <div id="div_add_gym"></div>
                            <button type="button" disabled class="add_gym btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add more</button>
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
                                        <input type="text" name="optical_total" id="optical_total" readonly class="form-control total_cat" value="0">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Requested Amount</label>
                                        <input type="number" step=".01" id="optical_requested_amount" name="optical_requested_amount[]" class="form-control optical_requested_amount">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Remarks</label>
                                        <input type="text" name="optical_remarks[]" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Attachment</label>
                                        <input type="file" name="optical_attachment[]" class="form-control optical_attachment">
                                    </div>
                                </div>
                            </div>
                            <div id="div_add_optical"></div>
                            <button type="button" disabled class="add_optical btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add more</button>
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
                                        <input type="text" name="cep_total" id="cep_total" readonly class="form-control total_cat" value="0">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Type</label>
                                        <select name="cep_type" id="cep_type" class="select-chosen" data-placeholder="Choose a CEP type..." style="width: 250px;">
                                            <option></option>
                                            <option>CEP</option>
                                            <option>Training</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Premise</label>
                                        <select name="cep_premise" id="cep_premise" class="select-chosen" data-placeholder="Choose a premise..." style="width: 250px;">
                                            <option></option>
                                            <option>Local</option>
                                            <option>International</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Requested Amount</label>
                                        <input type="number" step=".01" id="cep_requested_amount" name="cep_requested_amount[]" class="form-control cep_requested_amount">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Remarks</label>
                                        <input type="text" name="cep_remarks[]" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Attachment</label>
                                        <input type="file" name="cep_attachment[]" class="form-control cep_attachment">
                                    </div>
                                </div>
                            </div>
                            <div id="div_add_cep"></div>
                            <button type="button" disabled class="add_cep btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add more</button>
                        </div>
                        <div class="tab-pane" id="tab_club">
                            <h4>Club Membership</h4>
                            <hr>
                            <div class="form-group">
                                <label>Total Requested Amount</label>
                                <input type="text" name="club_total" readonly id="club_total" class="form-control total_cat" value="0">
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Requested Amount</label>
                                    <input type="number" id="club_requested_amount" name="club_requested_amount[]" step=".01" class="form-control club_requested_amount">
                                </div>
                                <div class="col-md-4">
                                    <label>Remarks</label>
                                    <input type="text" name="club_remarks[]" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label>Attachment</label>
                                    <input type="file" name="club_attachment[]" class="form-control club_attachment">
                                </div>
                            </div>
                            <br>
                            <div id="div_add_club"></div>
                            <button type="button" disabled class="add_club btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add more</button>
                        </div>
                        <div class="tab-pane" id="tab_maternity">
                            <h4>Matenity</h4>
                            <hr>
                            <p id="w_maternity" class="text-danger"></p>
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>Total Requested Amount</label>
                                        <input type="text" name="maternity_total" id="maternity_total" readonly class="form-control total_cat" value="0">
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
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Requested Amount</label>
                                        <input type="number" step=".01" id="maternity_requested_amount" name="maternity_requested_amount[]" class="form-control maternity_requested_amount">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Remarks</label>
                                        <input type="text" name="maternity_remarks[]" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Attachment</label>
                                        <input type="file" name="maternity_attachment[]" class="form-control maternity_attachment">
                                    </div>
                                </div>
                            </div>
                            <div id="div_add_maternity"></div>
                            <button type="button" disabled class="add_maternity btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add more</button>
                        </div>
                        <div class="tab-pane" id="tab_others">
                            <h4>Others</h4>
                            <hr>
                            <div class="form-group">
                                <label>Total Requested Amount</label>
                                <input type="text" name="others_total" readonly id="others_total" class="form-control total_cat" value="0">
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Requested Amount</label>
                                    <input type="number" id="others_requested_amount" name="others_requested_amount[]" step=".01" class="form-control others_requested_amount">
                                </div>
                                <div class="col-md-4">
                                    <label>Remarks</label>
                                    <input type="text" name="others_remarks[]" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label>Attachment</label>
                                    <input type="file" name="others_attachment[]" class="form-control others_attachment">
                                </div>
                            </div>
                            <br>
                            <div id="div_add_others"></div>
                            <button type="button" disabled class="add_others btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add more</button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Amount in Figures</label>
                            <input type="number" name="amount" id="amount" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Payment For</label>
                            <input type="text" name="payment_for" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Special Instructions</label>
                    <textarea name="special_instruction" class="form-control" rows="3"></textarea>
                </div>
                <button class="btn btn-primary" name="btn_benefits_reimbursement" id="btn_benefits_reimbursement" disabled>Submit</button>
            </form>
        </div>
    </div>
</div>
<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>
<?php include 'inc/template_end.php'; ?>
<script src="js/custom.js"></script>