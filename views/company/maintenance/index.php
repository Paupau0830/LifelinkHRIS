<div id="page-content">
    <!-- eCommerce Dashboard Header -->
    <?php
    require_once 'views/company/partials/navigation.php'
    ?>

    <?= flash() ?>
    <div class="block full">
        <!-- Working Tabs Title -->
        <div class="block-title">
            <ul class="nav nav-tabs push" data-toggle="tabs" id="maintenance_cats">
                <li class="active"><a href="#tab_maintenance"><i class="fa fa-cogs" style="margin-right: 3px;"></i>
                        Company Maintenance</a></li>
                <li><a href="#tab_loan"><i class="fa fa-money" style="margin-right: 3px;"></i> Loan Value</a></li>
                <li><a href="#tab_leaves"><i class="fa fa-user-times" style="margin-right: 3px;"></i> Leave Balances</a>
                </li>
                <li><a href="#tab_benefits"><i class="fa fa-exchange" style="margin-right: 3px;"></i> Benefits Balances</a>
                </li>
            </ul>
        </div>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_maintenance">
                <form action="<?= asset('/company/' . $id . '/maintenance/' . $maintenance->ID . '/update') ?>"
                      method="post" enctype="multipart/form-data" class="form-horizontal form-bordered">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Employee Number Prefix</label>
                                    <input type="text" name="prefix" class="form-control"
                                           value="<?= $maintenance->prefix ?? '' ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <center><img src="<?= asset('/uploads/' . $maintenance->logo ?? '') ?>" alt=""
                                             height="150" width="250"></center>
                                <div class="form-group">
                                    <label>Change Logo</label>
                                    <input type="file" name="logo" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <center><img src="<?= asset('/uploads/' . $maintenance->banner ?? '') ?>" alt=""
                                             height="150" style="max-width: 400px;width:100%"></center>
                                <div class="form-group">
                                    <label>Change Banner</label>
                                    <input type="file" name="banner" class="form-control">
                                </div>
                            </div>
                        </div>
                        <!--                        <button name="btn_maintenance" class="btn btn-primary">Update</button>-->
                        <button class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
            <div class="tab-pane" id="tab_loan">
                <form action="<?= asset('/company/' . $id . '/loan-value/' . $maxLoan->ID . '/update') ?>" method="post"
                      enctype="multipart/form-data" class="form-horizontal form-bordered">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Loan Max Value</label>
                                    <input type="number" name="max_value" class="form-control"
                                           value="<?= $maxLoan->max_value ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Loan Others Max Value</label>
                                    <input type="number" name="others_max_value" class="form-control"
                                           value="<?= $maxLoan->others_max_value ?>">
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
            <div class="tab-pane" id="tab_leaves">
                <form method="post" enctype="multipart/form-data"
                      action="<?= asset('/company/' . $id . '/leave-balance/update') ?>">
                    <div class="container-fluid">
                        <div class="form-group">
                            <label>Job Grade Set*</label>
                            <select name="jgs_id" id="job_grade_set" required class="select-chosen"
                                    data-placeholder="Choose a Job Grade Set..." style="width: 250px;">
                                <option></option>
                                <?php
                                foreach ($jobGradeSets as $k => $v) {
                                    echo '<option value="' . $k . '">' . $v . '</option>';
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
                                            <input type="number" name="sl_annual" step=".01" required
                                                   class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Monthly Value *</label>
                                            <input type="number" name="sl_monthly" step=".01" required
                                                   class="form-control">
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
                                            <input type="number" required step=".01" name="vl_annual"
                                                   class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Monthly Value *</label>
                                            <input type="number" name="vl_monthly" step=".01" required
                                                   class="form-control">
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
                                            <input type="number" name="wfh_monthly" step=".01" required
                                                   class="form-control">
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
                                            <input type="number" required name="el_annual" step=".01"
                                                   class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Monthly Value *</label>
                                            <input type="number" name="el_monthly" required step=".01"
                                                   class="form-control">
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
                                            <input type="number" required name="ecu_annual" step=".01"
                                                   class="form-control">
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
                                            <input type="number" required name="bl_annual" step=".01"
                                                   class="form-control">
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
                                            <input type="number" required name="pl_annual" step=".01"
                                                   class="form-control">
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
                                            <input type="number" required name="pla_annual" step=".01"
                                                   class="form-control">
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
                                            <input type="number" required name="spl_annual" step=".01"
                                                   class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button id="btn_maintenance_leave" class="btn btn-primary"
                                disabled>Update
                        </button>
                    </div>
                </form>
            </div>
            <div class="tab-pane" id="tab_benefits">
                <form method="post" enctype="multipart/form-data" action="<?= asset('/company/' . $id . '/maintenance-benefits/update') ?>">
                    <div class="container-fluid">
                        <input type="hidden" name="company_id" value="<?= $rid ?>">
                        <div class="form-group">
                            <label>Job Grade Set*</label>
                            <select name="jgs_id" id="job_grade_set_benefits" required
                                    class="select-chosen" data-placeholder="Choose a Job Grade Set..."
                                    style="width: 250px;">
                                <option></option>
                                <?php
                                    foreach ($jobGradeSets as $k => $v) {
                                        echo '<option value="' . $k . '">' . $v . '</option>';
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
                                            <input type="number" name="car_year1" step=".01" required
                                                   class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Year 2 *</label>
                                            <input type="number" name="car_year2" step=".01" required
                                                   class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Year 3 *</label>
                                            <input type="number" name="car_year3" step=".01" required
                                                   class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Year 4 *</label>
                                            <input type="number" name="car_year4" step=".01" required
                                                   class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Year 5 *</label>
                                            <input type="number" name="car_year5" step=".01" required
                                                   class="form-control">
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
                                            <input type="number" name="cep_monthly" step=".01" required
                                                   class="form-control">
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
                                            <input type="number" name="gas_monthly" step=".01" required
                                                   class="form-control">
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
                                            <input type="number" name="gym_monthly" step=".01" required
                                                   class="form-control">
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
                                            <input type="number" name="medical_monthly" step=".01" required
                                                   class="form-control">
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
                                            <input type="number" name="optical_monthly" step=".01" required
                                                   class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button id="btn_maintenance_benefits" class="btn btn-primary"
                                disabled>Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Load and execute javascript code used only in this page -->
<script src="<?= asset('/js/pages/tablesDatatables.js') ?>"></script>
<script>
    $(function () {
        TablesDatatables.init();
    });
    $('#job_grade_set').change(function () {
        $('#btn_maintenance_leave').prop('disabled', false);
        $.ajax({
            type: "GET",
            dataType: 'json',
            url: "<?=asset('/company/' . $id . '/leave-balance/get-company')?>",
            data: {
                jgs_id: $(this).val(),
            },
            success: function (response) {
                $('[name="sl_monthly"]').val(response.sl_monthly);
                $('[name="sl_annual"]').val(response.sl_annual);
                $('[name="vl_monthly"]').val(response.vl_monthly);
                $('[name="vl_annual"]').val(response.vl_annual);
                $('[name="wfh_monthly"]').val(response.wfh_monthly);
                $('[name="wfh_annual"]').val(response.wfh_annual);
                $('[name="el_monthly"]').val(response.el_monthly);
                $('[name="el_annual"]').val(response.el_annual);
                $('[name="ecu_annual"]').val(response.ecu_annual);
                $('[name="bl_annual"]').val(response.bl_annual);
                $('[name="pl_annual"]').val(response.pl_annual);
                $('[name="pla_annual"]').val(response.pla_annual);
                $('[name="spl_annual"]').val(response.spl_annual);
            }
        });
    });
    $('#job_grade_set_benefits').change(function () {
        $('#btn_maintenance_benefits').prop('disabled', false);

        $.ajax({
            type: "GET",
            dataType: 'json',
            url: "<?=asset('/company/' . $id . '/maintenance-benefits')?>",
            data: {
                jgs_id: $(this).val(),
            },
            success: function (response) {
                $('[name="car_year1"]').val(response.car_year1);
                $('[name="car_year2"]').val(response.car_year2);
                $('[name="car_year3"]').val(response.car_year3);
                $('[name="car_year4"]').val(response.car_year4);
                $('[name="car_year5"]').val(response.car_year5);
                $('[name="cep_annual"]').val(response.cep_annual);
                $('[name="cep_monthly"]').val(response.cep_monthly);
                $('[name="gas_monthly"]').val(response.gas_monthly);
                $('[name="gym_annual"]').val(response.gym_annual);
                $('[name="gym_monthly"]').val(response.gym_monthly);
                $('[name="medical_annual"]').val(response.medical_annual);
                $('[name="medical_monthly"]').val(response.medical_monthly);
                $('[name="optical_annual"]').val(response.optical_annual);
                $('[name="optical_monthly"]').val(response.optical_monthly);
            }
        });
    });
</script>
