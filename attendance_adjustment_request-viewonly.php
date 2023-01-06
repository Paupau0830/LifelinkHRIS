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
$date = '';
$time = '';
$request_type = '';
$reason = '';
$emp_num = '';
$emp_name = '';
$remarks = '';
$status = '';
$sql = mysqli_query($db, "SELECT * FROM tbl_attendance_adjust_request");
while ($row = mysqli_fetch_assoc($sql)) {
    if ($fid == md5($row['id'])) {
        $id = $row['id'];
        $cid = $row['company'];
        $date = $row['date'];
        $time = $row['time'];
        $request_type = $row['request_type'];
        $reason = $row['reason'];
        $emp_num = $row['emp_num'];
        $remarks = $row['remarks'];
        $status = $row['status'];
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
                <i class="fa fa-clock-o"></i><strong>Attendance Adjustment<strong>
            </h1>
        </div>
          
    </div>
       
      <div class="block full">
        <div class="block-title">
            <h2><strong>Time Adjustment</strong> Request</h2>
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
                        <label>Date*</label>
                        <input type="text" name="date" readonly class="form-control" value="<?= $date ?>">
                    </div>
                    
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6">
                        <label>Time In*</label>
                        <input type="text" name="time" readonly class="form-control" value="<?= $time ?>">
                    </div>
                    <div class="col-md-6">
                        <label>Request Type*</label>
                        <input type="text" name="request_type" readonly class="form-control" value="<?= $request_type ?>">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Reason*</label>
                            <textarea name="reason" readonly class="form-control"><?= $reason ?></textarea>
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
