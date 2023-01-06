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
                  <li>
                  <a href="#"><i class="fa fa-clock-o" style="margin-right: 3px;"></i> Time in</a></li>
                  </li>
                  <li>
                      <a href="timeout_adjustment_application"><i class="fa fa-clock-o"></i> Time out</a>
                  </li>
                  <li class="active">
                      <a href="manual_adjustment_application"><i class="fa fa-clock-o"></i> Manual Adjustment</a>
                  </li>
                        
              </ul> 
        </div>
          
        </div>
       
    <div class="block full">
        <div class="block-title">
            <h2><strong>Attendance</strong> List</h2>
            <?= $res ?>

        </div>
        <div class="container-fluid">
            <form method="POST" enctype="multipart/form-data">
                <div class="table-responsive">
            <table id="two-column" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">Employee Number</th>
                        <th class="text-center">Employee Name</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Time</th>
                        <th class="text-center">Duration</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = mysqli_query($db, "SELECT * FROM tbl_attendance");
                    while ($row = mysqli_fetch_assoc($sql)) {
                    ?>
                    <tr>
                            <td class="text-center"><?= $row['emp_num'] ?></td>
                            <td class="text-center"><?= $row['emp_name'] ?></td>
                            <td class="text-center"><?= $row['statusnow'] ?></td>
                            <td class="text-center"><?= $row['datenow'] ?></td>
                            <td class="text-center"><?= $row['timenow'] ?></td>
                            <td class="text-center"><?= $row['total_duration'] ?></td>
                            <!-- <td class="text-center">
                                <div class="btn-group">
                                    <a href="attendance_adjustment_request-viewonly?<?= md5('id') . '=' . md5($row['id']) ?>" class="btn btn-primary">View</a>
                                </div>
                            </td> -->
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
            
            <br><a href="javascript:void(0)" class="btn btn-success" onclick="$('#modal-add-manual-adjustment').modal('show');">Add</a>&nbsp;
            <!-- <a href="javascript:void(0)" class="btn btn-warning" onclick="$('#modal-edit-depot').modal('show');">Edit</a>&nbsp; -->
            <a href="javascript:void(0)" class="btn btn-danger" onclick="$('#modal-delete-manual-adjustment').modal('show');">Delete</a>
          </div>
            </form>
        </div>
    </div>



         <!--end of tab_adjustment-->
        </div> <!--end of tab-content-->
    </div>
    
    
</div>
</div>
<div id="modal-add-manual-adjustment" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h2 class="modal-title"><i class="fa fa-clock-o"></i> Add Time In & Time Out</h2>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form method="POST" class="form-horizontal form-bordered">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Employee Name*</label>
                                <!-- <input type="text" name="name" class="form-control"> -->
                                <select name="employee_id" required class="form-control select-chosen">
                                <option value="null">Select Employee</option>

                                    <?php
                                        $sql = mysqli_query($db, "SELECT * FROM tbl_personal_information WHERE super_admin != '1'");
                                        while ($row = mysqli_fetch_assoc($sql)) {
                                    ?>
                                    <option value="<?= $row['employee_number'] ?>"><?= $row['account_name'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date*</label>
                                <input type="date" required name="date" id="date" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Time In -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Time In*</label>
                                <input type="time" required name="time_in" id="time_out" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Time Out*</label>
                                <input type="time" required name="time_out" id="time_out" class="form-control">
                            </div>
                        </div>
                    </div>
                        <br>
                        <button class="btn btn-success btn-block" name="manual_timeinandout">Add</button>
                        <br>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
         
<div id="modal-delete-manual-adjustment" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h2 class="modal-title"><i class="fa fa-clock-o"></i> Delete Attendance</h2>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <form method="POST" class="form-horizontal form-bordered">
                            <div class="form-group">
                                <div class="table-responsive">
                                    <table id="three-column" class="table table-vcenter table-condensed table-bordered">
                                        <thead>
                                            <tr>
                                                <th style="font-size:14px;">Emp #</th>
                                                <th style="font-size:14px;">Employee Name</th>
                                                <th style="font-size:14px;">Status</th>
                                                <th style="font-size:14px;">Date</th>
                                                <th style="font-size:14px;">Time</th>
                                                <th>#</th>
                                            </tr>
                                        </thead>
                                    <tbody>
                                        <?php
                                            $sql = mysqli_query($db, "SELECT * FROM tbl_attendance ORDER BY datenow");
                                            while ($row = mysqli_fetch_assoc($sql)) {
                                            ?>
                                    <tr>
                                        <td style="font-size:13px;"><input type="checkbox" name="selected_attendance_delete[]" value="<?= $row['id'] ?>">&ensp;<?= $row['emp_num'] ?></td>
                                        <td style="font-size:11px;"><?= $row['emp_name'] ?></td>
                                        <td style="font-size:13px;"><?= $row['statusnow'] ?></td>
                                        <td style="font-size:13px;"><?= $row['datenow'] ?></td>
                                        <td style="font-size:13px;"><?= $row['timenow'] ?></td>
                                        <td style="font-size:13px;"><?= $row['total_duration'] ?></td>
                                    </tr>
                                        <?php
                                        }
                                        ?>
                                </tbody>
                            </table>
                            </div>
                            <button class="btn btn-danger btn-block" name="delete_manual_attendance">Delete</button>
                            <br>
                        </form>
                    </div>
                </div>
        </div>
    </div>

<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>
<?php include 'inc/template_end.php'; ?>
<script src="js/pages/pagination.js"></script>
<script src="js/pages/paginationTable.js"></script>

<script>
    $(function() {
        TablesDatatables.init();
    });
</script>
