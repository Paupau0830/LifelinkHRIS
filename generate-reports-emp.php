<?php include 'inc/config.php'; ?>
<?php include 'inc/template_start.php'; ?>
<?php include 'inc/page_head.php'; ?>
<?php
if (empty($_SESSION['hris_id'])) {
    header('Location: login');
}
?>
<?php
$empID = $_SESSION['hris_id'];
$cID = '';
$cname = '';
$emp_num = '';
$sql = mysqli_query($db, "SELECT * FROM tbl_users WHERE ID = '$empID'");
while ($row = mysqli_fetch_assoc($sql)) {
    $emp_num = $row['employee_number'];
    $cID = $row['company_id'];
}

$sql1 = mysqli_query($db, "SELECT * FROM tbl_companies WHERE ID = '$cID'");
while ($row = mysqli_fetch_assoc($sql1)) {
    $cname = $row['company_name'];
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
                            <input type="text" readonly name="company" class="form-control" value="<?= $cname ?>">
                            <input type="hidden" name="emp_num" value="<?= $emp_num ?>">
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>Category*</label>
                        <select name="category" required class="select-chosen" data-placeholder="Choose a category..." style="width: 250px;">
                            <option>Payslip</option>
                        </select>
                    </div>
                </div>
            <label><br><strong>Cut-offs</strong></strong></label>
            <div class="table-responsive">
            <table id="company-management" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th class="text-center">Date From</th>
                        <th class="text-center">Date To</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = mysqli_query($db, "SELECT * FROM tbl_employees_payslip WHERE emp_num = '$emp_num'");
                    while ($row = mysqli_fetch_assoc($sql)) {
                    ?>
                    <tr>
                            <td class="text-center"><input type="radio" name="selected_cutoff" checked="checked" value="<?= $row['ID'] ?>">&ensp;<?= $row['ID'] ?></td>
                            <td><?= $row['date_from'] ?></td>
                            <td><?= $row['date_to'] ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
            <br><br>
                <button class="btn btn-primary" name="btn_generate_report">Generate</button>
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
<script src="js/pages/tablesDatatables.js"></script>
<script>
    $(function() {
        TablesDatatables.init();
    });
</script>