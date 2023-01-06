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
$emp_num = '';
$date_filed = '';
$status = '';
$id = '';
$remarks = '';
$reason = '';
$sql = mysqli_query($db, "SELECT * FROM tbl_offset_request");
while ($row = mysqli_fetch_assoc($sql)) {
    if ($fid == md5($row['id'])) {
        $id = $row['id'];
        $cid = $row['company_id'];
        $emp_num = $row['emp_num'];
        $date_from = $row['date_from'];
        $date_to = $row['date_to'];
        $date_filed = $row['date_filed'];
        $status = $row['status'];
        $remarks = $row['remarks'];
        $reason = $row['reason'];
    }
}
?>



<!-- Page content -->
<div id="page-content">
    <!-- eCommerce Dashboard Header -->
    
    <div class="content-header">
        <div class="header-section">
            <h1>
                <i class="fa fa-clock-o"></i><strong>Offset Request<strong>
            </h1>
        </div>
          
    </div>
       
      <div class="block full">
        <div class="block-title">
            <h2><strong>Offset Application</strong> Details</h2>
        </div>
        <div class="container-fluid">
            <?= $res ?>
            <form method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <label>Employee Number*</label>
                        <input type="hidden" name="id" readonly class="form-control" value="<?= $id ?>">
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
                        <label>Reason*</label>
                        <input type="text" name="reason" readonly class="form-control" value="<?= $reason ?>">
                    </div>
                    <div class="col-md-6">
                        <label>Status*</label>
                        <input type="text" name="status" readonly class="form-control" value="<?= $status ?>">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Remarks</label>
                            <textarea name="remarks" class="form-control"><?= $remarks ?></textarea>
                        </div>
                    </div>
                </div>
                <br>
                <button class="btn btn-primary" name="approve_offset">Approve</button>
                <button class="btn btn-primary" name="deny_offset">Deny</button>
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
