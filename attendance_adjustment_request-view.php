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
$cname = '';
$date = '';
$time = '';
$request_type = '';
$reason = '';
$emp_num = '';
$emp_name = '';
$remarks = '';
$sql = mysqli_query($db, "SELECT * FROM tbl_attendance_adjust_request");
while ($row = mysqli_fetch_assoc($sql)) {
    if ($fid == md5($row['id'])) {
        $id = $row['id'];
        $cname = $row['company'];
        $date = $row['date'];
        $time = $row['time'];
        $request_type = $row['request_type'];
        $reason = $row['reason'];
        $emp_num = $row['emp_num'];
        $remarks = $row['remarks'];
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
                <button class="btn btn-primary" name="approve_attendance_request">Approve</button>&nbsp;
                <button class="btn btn-primary" name="deny_attendance_request">Deny</button>
            </form>
        </div>
    </div>

<div class="block full">
        <div class="block-title">
            <h2><strong>Day Attendance Overview</strong></h2>
        </div>
        <div class="container-fluid">
            <form method="POST" enctype="multipart/form-data">
                <div class="table-responsive">
            <table id="two-column" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">Employee Number</th>
                        <th class="text-center">Employee Name</th>
                        <th class="text-center">Time</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Total Duration</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = mysqli_query($db, "SELECT * FROM tbl_attendance WHERE emp_num = '$emp_num' AND datenow = '$date'");
                    while ($row = mysqli_fetch_assoc($sql)) {
                    ?>
                    <tr>
                            <td class="text-center"><?= $row['emp_num'] ?></td>
                            <td class="text-center"><?= $row['emp_name'] ?></td>
                            <td class="text-center"><?= $row['timenow'] ?></td>
                            <td class="text-center"><?= $row['datenow'] ?></td>
                            <td class="text-center"><?= $row['statusnow'] ?></td>
                            <td class="text-center"><?= $row['total_duration'] ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
          </div>
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
