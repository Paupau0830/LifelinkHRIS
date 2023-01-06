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
$emp_num = $_SESSION['hris_employee_number'];
$approver = '';

$sql = mysqli_query($db, "SELECT approver FROM tbl_employment_information WHERE employee_number = '$emp_num'");
    while ($row = mysqli_fetch_assoc($sql)) {
        $approver = $row['approver'];
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
            <ul class="nav-horizontal text-center">
                  <li class="active">
                  <a href="#"><i class="fa fa-clock-o" style="margin-right: 3px;"></i> Time in</a></li>
                  </li>
                  <li>
                      <a href="timeout_adjustment_application"><i class="fa fa-clock-o"></i> Time out</a>
                  </li>

                  <?php 
                  
                  if($_SESSION['hris_role'] == "Admin"){

                  ?>
                  <li>
                      <a href="manual_adjustment_application"><i class="fa fa-clock-o"></i> Manual Adjustment</a>
                  </li>
                  <?php 
                  }
                  ?>
                        
              </ul> 
        </div>
          
    </div>
       
      <div class="block full">
        <div class="block-title">
            <h2><strong>Time In Adjustment</strong> Form</h2>
        </div>
        <div class="container-fluid">
            <?= $res ?>
            <form method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Date*</label>
                            <input type="date" name="date" id="date" class="form-control" max="<?= date('Y-m-d') ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>Time In*</label>
                        <input type="time" name="time_in" id="time_in" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Approver*</label>
                            <input type="text" name="approver" id="approver" readonly class="form-control" value="<?= $approver ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Reason*</label>
                            <textarea name="reason" rows="3" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <button class="btn btn-primary" name="btn_timein_request">Submit</button>
            </form>
        </div>
    </div>

    <div class="block full">
        <div class="block-title">
            <h2><strong>Submitted</strong> Form</h2>
        </div>
        <div class="container-fluid">
            <form method="POST" enctype="multipart/form-data">
                <div class="table-responsive">
            <table id="two-column" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Time</th>
                        <th class="text-center">Type</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">View</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = mysqli_query($db, "SELECT * FROM tbl_attendance_adjust_request WHERE emp_num = '$emp_num' AND request_type = 'Time In'");
                    while ($row = mysqli_fetch_assoc($sql)) {
                    ?>
                    <tr>
                            <td class="text-center"><?= $row['id'] ?></td>
                            <td class="text-center"><?= $row['date'] ?></td>
                            <td class="text-center"><?= $row['time'] ?></td>
                            <td class="text-center"><?= $row['request_type'] ?></td>
                            <td class="text-center"><?= $row['status'] ?></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="attendance_adjustment_request-viewonly?<?= md5('id') . '=' . md5($row['id']) ?>" class="btn btn-primary">View</a>
                                </div>
                            </td>
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
