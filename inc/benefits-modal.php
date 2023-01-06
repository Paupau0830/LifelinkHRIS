<div id="modal-add-parking" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h2 class="modal-title"><i class="gi gi-cars"></i> Add Parking</h2>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="benefits_id" value="<?= $rid ?>">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Requested Amount</label>
                                    <input type="number" name="parking_requested_amount" step=".01" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Attachment</label>
                                    <input type="file" name="parking_attachment" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Remarks</label>
                            <input type="text" name="parking_remarks" class="form-control">
                        </div>
                        <button class="btn btn-primary btn-block" name="btn_add_parking">Submit</button>
                        <br>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="modal-add-gas" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h2 class="modal-title"><i class="gi gi-tint"></i> Add Gasoline</h2>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="benefits_id" value="<?= $rid ?>">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Requested Amount</label>
                                    <input type="number" name="gas_requested_amount" step=".01" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Attachment</label>
                                    <input type="file" name="gas_attachment" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Remarks</label>
                            <input type="text" name="gas_remarks" class="form-control">
                        </div>
                        <button class="btn btn-primary btn-block" name="btn_add_gas">Submit</button>
                        <br>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="modal-add-car" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h2 class="modal-title"><i class="gi gi-car"></i> Add Car Benefits</h2>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="benefits_id" value="<?= $rid ?>">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Requested Amount</label>
                                    <input type="number" name="car_requested_amount" step=".01" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Attachment</label>
                                    <input type="file" name="car_attachment" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Remarks</label>
                            <input type="text" name="car_remarks" class="form-control">
                        </div>
                        <button class="btn btn-primary btn-block" name="btn_add_car">Submit</button>
                        <br>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="modal-add-medical" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h2 class="modal-title"><i class="gi gi-hospital"></i> Add Medical Benefits</h2>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="benefits_id" value="<?= $rid ?>">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Requested Amount</label>
                                    <input type="number" name="medical_requested_amount" step=".01" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Attachment</label>
                                    <input type="file" name="medical_attachment" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Remarks</label>
                            <input type="text" name="medical_remarks" class="form-control">
                        </div>
                        <button class="btn btn-primary btn-block" name="btn_add_medical">Submit</button>
                        <br>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="modal-add-gym" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h2 class="modal-title"><i class="gi gi-bicycle"></i> Add Gym Benefits</h2>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="benefits_id" value="<?= $rid ?>">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Requested Amount</label>
                                    <input type="number" name="gym_requested_amount" step=".01" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Attachment</label>
                                    <input type="file" name="gym_attachment" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Remarks</label>
                            <input type="text" name="gym_remarks" class="form-control">
                        </div>
                        <button class="btn btn-primary btn-block" name="btn_add_gym">Submit</button>
                        <br>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="modal-add-optical" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h2 class="modal-title"><i class="fa fa-eye"></i> Add Optical Benefits</h2>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="benefits_id" value="<?= $rid ?>">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Requested Amount</label>
                                    <input type="number" name="optical_requested_amount" step=".01" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Attachment</label>
                                    <input type="file" name="optical_attachment" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Remarks</label>
                            <input type="text" name="optical_remarks" class="form-control">
                        </div>
                        <button class="btn btn-primary btn-block" name="btn_add_optical">Submit</button>
                        <br>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="modal-add-cep" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h2 class="modal-title"><i class="fa fa-graduation-cap"></i> Add CEP Benefits</h2>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="benefits_id" value="<?= $rid ?>">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Requested Amount</label>
                                    <input type="number" name="cep_requested_amount" step=".01" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Attachment</label>
                                    <input type="file" name="cep_attachment" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Remarks</label>
                            <input type="text" name="cep_remarks" class="form-control">
                        </div>
                        <button class="btn btn-primary btn-block" name="btn_add_cep">Submit</button>
                        <br>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="modal-add-club" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h2 class="modal-title"><i class="fa fa-building"></i> Add Club Benefits</h2>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="benefits_id" value="<?= $rid ?>">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Requested Amount</label>
                                    <input type="number" name="club_requested_amount" step=".01" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Attachment</label>
                                    <input type="file" name="club_attachment" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Remarks</label>
                            <input type="text" name="club_remarks" class="form-control">
                        </div>
                        <button class="btn btn-primary btn-block" name="btn_add_club">Submit</button>
                        <br>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="modal-add-maternity" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h2 class="modal-title"><i class="gi gi-parents"></i> Add Maternity Benefits</h2>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="benefits_id" value="<?= $rid ?>">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Requested Amount</label>
                                    <input type="number" name="maternity_requested_amount" step=".01" class="form-control maternity_requested_amount">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Attachment</label>
                                    <input type="file" name="maternity_attachment" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Remarks</label>
                            <input type="text" name="maternity_remarks" class="form-control">
                        </div>
                        <button class="btn btn-primary btn-block" name="btn_add_maternity">Submit</button>
                        <br>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="modal-add-others" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h2 class="modal-title"><i class="fa fa-navicon"></i> Add Others Benefits</h2>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="benefits_id" value="<?= $rid ?>">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Requested Amount</label>
                                    <input type="number" name="others_requested_amount" step=".01" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Attachment</label>
                                    <input type="file" name="others_attachment" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Remarks</label>
                            <input type="text" name="others_remarks" class="form-control">
                        </div>
                        <button class="btn btn-primary btn-block" name="btn_add_others">Submit</button>
                        <br>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>