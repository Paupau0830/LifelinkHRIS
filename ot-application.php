<?php include 'inc/config.php'; ?>
<?php include 'inc/template_start.php'; ?>
<?php include 'inc/page_head.php'; ?>
<?php
if (empty($_SESSION['hris_id'])) {
    header('Location: login');
}
?>
<div id="page-content">
    <div class="content-header">
        <div class="header-section">
            <h1>
                <i class="fa fa-hourglass"></i>Overtime Application
            </h1>
        </div>
    </div>
    <div class="block full">
        <div class="block-title">
            <div class="block-options pull-right">
                <a href="ot-list" class="btn btn-alt btn-sm btn-default">Overtime List</a>
            </div>
            <h2><strong>Overtime Application</strong> Form</h2>
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
                            <label>Month of Overtime</label>
                            <select name="month_of_ot" required class="select-chosen" data-placeholder="Choose a month..." style="width: 250px;">
                                <option></option>
                                <option>January</option>
                                <option>February</option>
                                <option>March</option>
                                <option>April</option>
                                <option>May</option>
                                <option>June</option>
                                <option>July</option>
                                <option>August</option>
                                <option>September</option>
                                <option>October</option>
                                <option>November</option>
                                <option>December</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>Total Hours</label>
                        <input type="number" name="total_hours" class="form-control">
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
                <button class="btn btn-primary" name="btn_ot_application" disabled>Submit</button>
            </form>
        </div>
    </div>
</div>

<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>
<?php include 'inc/template_end.php'; ?>