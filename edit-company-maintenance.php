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
$cname = '';
$sql = mysqli_query($db, "SELECT * FROM tbl_companies");
while ($row = mysqli_fetch_assoc($sql)) {
    if ($fid == md5($row['ID'])) {
        $rid = $row['ID'];
        $cname = $row['company_name'];
    }
}
$get_count_departments = mysqli_query($db, "SELECT COUNT(*) as c FROM tbl_departments WHERE company_id = '$rid'");
$count_dept = mysqli_fetch_assoc($get_count_departments);

$get_count_jg = mysqli_query($db, "SELECT COUNT(*) as c FROM tbl_job_grade WHERE company_id = '$rid'");
$count_jg = mysqli_fetch_assoc($get_count_jg);

$get_count_jgs = mysqli_query($db, "SELECT COUNT(*) as c FROM tbl_job_grade_set WHERE company_id = '$rid'");
$count_jgs = mysqli_fetch_assoc($get_count_jgs);

$get_max_loan = mysqli_query($db, "SELECT * FROM tbl_loan_max_value WHERE company_id = '$rid'");
$max_loan = mysqli_fetch_assoc($get_max_loan);

?>
<!-- Page content -->
<div id="page-content">
    <!-- eCommerce Dashboard Header -->
    <div class="content-header">
        <ul class="nav-horizontal text-center">
            <li>
                <a href="edit-company?<?= md5('id') . '=' . $fid ?>"><i class="fa fa-info"></i> Information</a>
            </li>
            <li>
                <a href="edit-company-department?<?= md5('id') . '=' . $fid ?>"><i class="fa fa-sitemap"></i> Departments</a>
            </li>
            <li>
                <a href="edit-company-job-grade?<?= md5('id') . '=' . $fid ?>"><i class="fa fa-square"></i> Job Grade</a>
            </li>
            <li>
                <a href="edit-company-job-grade-set?<?= md5('id') . '=' . $fid ?>"><i class="gi gi-show_thumbnails"></i> Job Grade Set</a>
            </li>
            <li>
                <a href="edit-company-benefits?<?= md5('id') . '=' . $fid ?>"><i class="fa fa-exchange"></i> Benefits</a>
            </li>
            <li class="active">
                <a href="edit-company-maintenance?<?= md5('id') . '=' . $fid ?>"><i class="gi gi-settings"></i> Maintenance</a>
            </li>
        </ul>
    </div>
    <?= $res ?>
    <div class="block full">
        <!-- Working Tabs Title -->
        <div class="block-title">
            <ul class="nav nav-tabs push" data-toggle="tabs" id="maintenance_cats">
                <li class="active"><a href="#tab_maintenance"><i class="fa fa-cogs" style="margin-right: 3px;"></i> Company Maintenance</a></li>
                <li><a href="#tab_loan"><i class="fa fa-money" style="margin-right: 3px;"></i> Loan Value</a></li>
                <li><a href="#tab_leaves"><i class="fa fa-user-times" style="margin-right: 3px;"></i> Leave Balances</a></li>
                <li><a href="#tab_benefits"><i class="fa fa-exchange" style="margin-right: 3px;"></i> Benefits Balances</a></li>
            </ul>
        </div>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_maintenance">
                <form method="post" enctype="multipart/form-data" class="form-horizontal form-bordered">
                    <div class="container-fluid">
                        <input type="hidden" name="company_id" value="<?= $rid ?>">
                        <?php
                        $get_maintenance = mysqli_query($db, "SELECT * FROM tbl_maintenance WHERE company_id = '$rid'");
                        $img = mysqli_fetch_assoc($get_maintenance);
                        ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Employee Number Prefix</label>
                                    <input type="text" name="prefix" class="form-control" value="<?= $img['prefix'] ?? '' ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <center><img src="uploads/<?= $img['logo'] ?>" alt="" height="150" width="250"></center>
                                <div class="form-group">
                                    <label>Change Logo</label>
                                    <input type="file" name="logo" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <center><img src="uploads/<?= $img['banner'] ?>" alt="" height="150" style="max-width: 400px;width:100%"></center>
                                <div class="form-group">
                                    <label>Change Banner</label>
                                    <input type="file" name="banner" class="form-control">
                                </div>
                            </div>
                        </div>
                        <button name="btn_maintenance" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
            <div class="tab-pane" id="tab_loan">
                <form method="post" enctype="multipart/form-data" class="form-horizontal form-bordered">
                    <div class="container-fluid">
                        <input type="hidden" name="company_id" value="<?= $rid ?>">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Loan Max Value</label>
                                    <input type="number" name="loan_max_value" class="form-control" value="<?= $max_loan['max_value'] ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Loan Others Max Value</label>
                                    <input type="number" name="loan_others_max_value" class="form-control" value="<?= $max_loan['others_max_value'] ?>">
                                </div>
                            </div>
                        </div>
                        <button name="btn_maintenance_loan" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
            <div class="tab-pane" id="tab_leaves">
                <form method="post" enctype="multipart/form-data">
                    <div class="container-fluid">
                        <input type="hidden" name="company_id" value="<?= $rid ?>">
                        <div class="form-group">
                            <label>Job Grade Set*</label>
                            <select name="job_grade_set" id="job_grade_set" required class="select-chosen" data-placeholder="Choose a Job Grade Set..." style="width: 250px;">
                                <option></option>
                                <?php
                                $jgs = get_job_grade_set($rid);
                                foreach ($jgs as $k => $v) {
                                    echo '<option value="' . $v['ID'] . '">' . $v['job_grade_set'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <h3>Sick Leave</h3>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Annual Value *</label>
                                            <input type="number" name="sl_annual" step=".01" required class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Monthly Value *</label>
                                            <input type="number" name="sl_monthly" step=".01" required class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <h3>Vacation Leave</h3>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Annual Value *</label>
                                            <input type="number" required step=".01" name="vl_annual" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Monthly Value *</label>
                                            <input type="number" name="vl_monthly" step=".01" required class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <h3>Work From Home</h3>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Annual Value *</label>
                                            <input type="number" name="wfh_annual" step=".01" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Monthly Value *</label>
                                            <input type="number" name="wfh_monthly" step=".01" required class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <h3>Emergency Leave</h3>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Annual Value *</label>
                                            <input type="number" required name="el_annual" step=".01" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Monthly Value *</label>
                                            <input type="number" name="el_monthly" required step=".01" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <h3>Executive Check-up Schedule</h3>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Annual Value *</label>
                                            <input type="number" required name="ecu_annual" step=".01" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <h3>Bereavement Leave</h3>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Annual Value *</label>
                                            <input type="number" required name="bl_annual" step=".01" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <h3>Paternity Leave</h3>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Annual Value *</label>
                                            <input type="number" required name="pl_annual" step=".01" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <h3>Paternity Leave - Additional</h3>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Annual Value *</label>
                                            <input type="number" required name="pla_annual" step=".01" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <h3>Solo Parent Leave</h3>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Annual Value *</label>
                                            <input type="number" required name="spl_annual" step=".01" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button name="btn_maintenance_leave" id="btn_maintenance_leave" class="btn btn-primary" disabled>Update</button>
                    </div>
                </form>
            </div>
            <div class="tab-pane" id="tab_benefits">
                <form method="post" enctype="multipart/form-data">
                    <div class="container-fluid">
                        <input type="hidden" name="company_id" value="<?= $rid ?>">
                        <div class="form-group">
                            <label>Job Grade Set*</label>
                            <select name="job_grade_set_benefits" id="job_grade_set_benefits" required class="select-chosen" data-placeholder="Choose a Job Grade Set..." style="width: 250px;">
                                <option></option>
                                <?php
                                $jgs = get_job_grade_set($rid);
                                foreach ($jgs as $k => $v) {
                                    echo '<option value="' . $v['ID'] . '">' . $v['job_grade_set'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <h3>Car Maintenance</h3>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Year 1 *</label>
                                            <input type="number" name="car_year1" step=".01" required class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Year 2 *</label>
                                            <input type="number" name="car_year2" step=".01" required class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Year 3 *</label>
                                            <input type="number" name="car_year3" step=".01" required class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Year 4 *</label>
                                            <input type="number" name="car_year4" step=".01" required class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Year 5 *</label>
                                            <input type="number" name="car_year5" step=".01" required class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <h3>CEP</h3>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Annual Value *</label>
                                            <input type="number" name="cep_annual" step=".01" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Monthly Value *</label>
                                            <input type="number" name="cep_monthly" step=".01" required class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <h3>Gasoline</h3>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-6"></div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Monthly Value *</label>
                                            <input type="number" name="gas_monthly" step=".01" required class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <h3>Gym</h3>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Annual Value *</label>
                                            <input type="number" name="gym_annual" step=".01" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Monthly Value *</label>
                                            <input type="number" name="gym_monthly" step=".01" required class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <h3>Medical</h3>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Annual Value *</label>
                                            <input type="number" name="medical_annual" step=".01" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Monthly Value *</label>
                                            <input type="number" name="medical_monthly" step=".01" required class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <h3>Optical</h3>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Annual Value *</label>
                                            <input type="number" name="optical_annual" step=".01" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Monthly Value *</label>
                                            <input type="number" name="optical_monthly" step=".01" required class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button name="btn_maintenance_benefits" id="btn_maintenance_benefits" class="btn btn-primary" disabled>Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>
<?php include 'inc/template_end.php'; ?>
<script src="js/pages/tablesDatatables.js"></script>
<script>
    $(function() {
        TablesDatatables.init();
    });
    $('#job_grade_set').change(function() {
        $('#btn_maintenance_leave').prop('disabled', false);
        var job_grade_set_id = $(this).val();
        var get_company_leave_balances = '';
        var company_id = '<?= $rid ?>';
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: "inc/config.php",
            data: {
                company_id: company_id,
                job_grade_set_id: job_grade_set_id,
                get_company_leave_balances: get_company_leave_balances
            },
            success: function(response) {
                $('[name="sl_monthly"]').val(response[0].sl_monthly);
                $('[name="sl_annual"]').val(response[0].sl_annual);
                $('[name="vl_monthly"]').val(response[0].vl_monthly);
                $('[name="vl_annual"]').val(response[0].vl_annual);
                $('[name="wfh_monthly"]').val(response[0].wfh_monthly);
                $('[name="wfh_annual"]').val(response[0].wfh_annual);
                $('[name="el_monthly"]').val(response[0].el_monthly);
                $('[name="el_annual"]').val(response[0].el_annual);
                $('[name="ecu_annual"]').val(response[0].ecu_annual);
                $('[name="bl_annual"]').val(response[0].bl_annual);
                $('[name="pl_annual"]').val(response[0].pl_annual);
                $('[name="pla_annual"]').val(response[0].pla_annual);
                $('[name="spl_annual"]').val(response[0].spl_annual);
            }
        });
    });
    $('#job_grade_set_benefits').change(function() {
        $('#btn_maintenance_benefits').prop('disabled', false);
        var job_grade_set_id = $(this).val();
        var get_company_benefits_balances = '';
        var company_id = '<?= $rid ?>';
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: "inc/config.php",
            data: {
                company_id: company_id,
                job_grade_set_id: job_grade_set_id,
                get_company_benefits_balances: get_company_benefits_balances
            },
            success: function(response) {
                $('[name="car_year1"]').val(response[0].car_year1);
                $('[name="car_year2"]').val(response[0].car_year2);
                $('[name="car_year3"]').val(response[0].car_year3);
                $('[name="car_year4"]').val(response[0].car_year4);
                $('[name="car_year5"]').val(response[0].car_year5);
                $('[name="cep_annual"]').val(response[0].cep_annual);
                $('[name="cep_monthly"]').val(response[0].cep_monthly);
                $('[name="gas_monthly"]').val(response[0].gas_monthly);
                $('[name="gym_annual"]').val(response[0].gym_annual);
                $('[name="gym_monthly"]').val(response[0].gym_monthly);
                $('[name="medical_annual"]').val(response[0].medical_annual);
                $('[name="medical_monthly"]').val(response[0].medical_monthly);
                $('[name="optical_annual"]').val(response[0].optical_annual);
                $('[name="optical_monthly"]').val(response[0].optical_monthly);
            }
        });
    });
</script>
