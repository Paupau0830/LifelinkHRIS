<div id="page-content">
    <!-- eCommerce Dashboard Header -->
    <?php
    require_once 'views/company/partials/navigation.php'
    ?>

    <div class="block full">
        <div class="block-title">
            <h2><strong>Benefits</strong> Eligibility - <?= $company->company_name ?></h2>
        </div>
        <div class="container-fluid">
            <?= flash() ?>
            <form method="post" class="form-horizontal form-bordered"
                  action="<?= asset('/company/' . $id . '/benefits/update') ?>">
                <input type="hidden" name="id" value="<?= $benefit->ID ?>">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="switch switch-primary">
                                <input type="checkbox" <?= $benefit->parking ? 'checked' : '' ?> name="parking"
                                       value="1">
                                <span></span>
                            </label>
                            <label>Parking</label>
                        </div>
                        <div class="form-group">
                            <label class="switch switch-primary">
                                <input type="checkbox" <?= $benefit->gasoline ? 'checked' : '' ?> name="gasoline"
                                       value="1">
                                <span></span>
                            </label>
                            <label>Gasoline</label>
                        </div>
                        <div class="form-group">
                            <label class="switch switch-primary">
                                <input type="checkbox" <?= $benefit->car_maintenance ? 'checked' : '' ?>
                                       name="car_maintenance" value="1">
                                <span></span>
                            </label>
                            <label>Car Maintenance</label>
                        </div>
                        <div class="form-group">
                            <label class="switch switch-primary">
                                <input type="checkbox" <?= $benefit->medicine ? 'checked' : '' ?> name="medicine"
                                       value="1">
                                <span></span>
                            </label>
                            <label>Medicine</label>
                        </div>
                        <div class="form-group">
                            <label class="switch switch-primary">
                                <input type="checkbox" <?= $benefit->gym ? 'checked' : '' ?> name="gym" value="1">
                                <span></span>
                            </label>
                            <label>Gym</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="switch switch-primary">
                                <input type="checkbox" <?= $benefit->optical_allowance ? 'checked' : '' ?>
                                       name="optical_allowance" value="1">
                                <span></span>
                            </label>
                            <label>Optical Allowance</label>
                        </div>
                        <div class="form-group">
                            <label class="switch switch-primary">
                                <input type="checkbox" <?= $benefit->cep ? 'checked' : '' ?> name="cep" value="1">
                                <span></span>
                            </label>
                            <label>CEP</label>
                        </div>
                        <div class="form-group">
                            <label class="switch switch-primary">
                                <input type="checkbox" <?= $benefit->club_membership ? 'checked' : '' ?>
                                       name="club_membership" value="1">
                                <span></span>
                            </label>
                            <label>Club Membership</label>
                        </div>
                        <div class="form-group">
                            <label class="switch switch-primary">
                                <input type="checkbox" <?= $benefit->maternity ? 'checked' : '' ?> name="maternity"
                                       value="1">
                                <span></span>
                            </label>
                            <label>Maternity</label>
                        </div>
                        <div class="form-group">
                            <label class="switch switch-primary">
                                <input type="checkbox" <?= $benefit->others ? 'checked' : '' ?> name="others"
                                       value="1">
                                <span></span>
                            </label>
                            <label>Others</label>
                        </div>
                    </div>
                </div>
                <br>
                <button class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>
