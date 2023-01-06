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
                <i class="fa fa-file"></i>Generate Reports
            </h1>
        </div>
    </div>
    <div class="block full">
        <div class="block-title">
            <h2><strong>Generate Reports</strong> Form</h2>
        </div>
        <div class="container-fluid">
            <?= $res ?>
            <form method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Company*</label>
                            <select name="company" class="select-chosen" id="company" required data-placeholder="Choose a company..." style="width: 250px;">
                                <option></option>
                                <option>All</option>
                                <?php
                                $companies = get_companies();
                                foreach ($companies as $k => $v) {
                                ?>
                                    <option value="<?= $v['ID'] ?>"><?= $v['company_name'] ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>Category*</label>
                        <select name="category" required class="select-chosen" data-placeholder="Choose a category..." style="width: 250px;">
                            <option></option>
                            <option>201 File</option>
                            <option>Leave Applications</option>
                            <option>OT Applications</option>
                            <option>Certificate Requests</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" max="<?= date('Y-m-d') ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control">
                        </div>
                    </div>
                </div>
                <button class="btn btn-primary" name="btn_generate_report">Submit</button>
            </form>
        </div>
    </div>
</div>

<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>
<?php include 'inc/template_end.php'; ?>
<script>
    $('#start_date').change(function() {
        $('#end_date').val($(this).val());
        $('#end_date').attr('min', $(this).val());
    });
</script>