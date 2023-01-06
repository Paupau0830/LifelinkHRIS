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
$cid = '';
$date_from = '';
$date_to = '';
$date_filed = '';
$total_duration = '';
$emp_num = '';
$emp_name = '';
$amount = '';
$status = '';
$remarks = '';
$attachment = '';
$sql = mysqli_query($db, "SELECT * FROM tbl_ot_request");
while ($row = mysqli_fetch_assoc($sql)) {
    if ($fid == md5($row['id'])) {
        $id = $row['id'];
        $cid = $row['company_id'];
        $date_from = $row['date_from'];
        $date_to = $row['date_to'];
        $date_filed = $row['date_filed'];
        $total_duration = $row['total_duration'];
        $emp_num = $row['emp_num'];
        $amount = $row['amount'];
        $status = $row['status'];
        $remarks = $row['remarks'];
        $attachment = $row['attachment'];
    }
}

$sql = mysqli_query($db, "SELECT * FROM tbl_personal_information WHERE employee_number = '$emp_num'");
while ($row = mysqli_fetch_assoc($sql)) {
        $emp_name = $row['account_name'];
    }
?>



<!-- Page content -->
<div id="page-content">
    <!-- eCommerce Dashboard Header -->
    
    <div class="content-header">
        <div class="header-section">
            <h1>
                <i class="fa fa-clock-o"></i><strong>OT Application<strong>
            </h1>
        </div>
          
    </div>
       
      <div class="block full">
        <div class="block-title">
            <h2><strong>Overtime</strong> Request</h2>
        </div>
        <div class="container-fluid">
            <?= $res ?>
            <form method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <label>Employee Number*</label>
                        <input type="hidden" name="id" readonly class="form-control" value="<?= $id ?>">
                        <input type="hidden" name="emp_name" readonly class="form-control" value="<?= $emp_name ?>">
                        <input type="text" name="emp_num" readonly class="form-control" value="<?= $emp_num ?>">
                    </div>
                    <div class="col-md-6">
                        <label>Date Filed*</label>
                        <input type="text" name="date_filed" readonly class="form-control" value="<?= $date_filed ?>">
                    </div>
                    
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6">
                        <label>Date From*</label>
                        <input type="text" name="date_from" readonly class="form-control" value="<?= $date_from ?>">
                    </div>
                    <div class="col-md-6">
                        <label>Date To*</label>
                        <input type="text" name="date_to" readonly class="form-control" value="<?= $date_to ?>">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Amount</label>
                            <input type="text" name="amount" readonly class="form-control" value="<?= $amount ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Attachment</label>
                            <input type="text" name="attachment_name" readonly class="form-control" value="<?= $attachment ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Remarks</label>
                            <textarea name="remarks" class="form-control"><?= $remarks ?></textarea>
                        </div>
                    </div>
                </div>
                <center>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <h3><strong>STATUS: <?= strtoupper($status) ?></strong></h3>
                        </div>
                    </div>
                </div>
            </center>
            </form>
        </div>
    </div>




         <!--end of tab_adjustment-->
        </div> <!--end of tab-content-->
    </div>

<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>
<?php include 'inc/template_end.php'; ?>
<script src="js/pages/pagination.js"></script>
<script>
    $(function() {
        TablesDatatables.init();
    });
</script>
