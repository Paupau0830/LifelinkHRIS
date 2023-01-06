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
                <i class="fa fa-clock-o"></i><strong>Offset Application<strong>
            </h1>
        </div>
          
    </div>
       
      <div class="block full">
        <div class="block-title">
            <h2><strong>Offset Request</strong> Form</h2>
        </div>
        <div class="container-fluid">
            <?= $res ?>
            <form method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>From*</label>
                            <input type="datetime-local" name="date_from" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>To*</label>
                        <input type="datetime-local" name="date_to" class="form-control">
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
                <button class="btn btn-primary" name="btn_request_offset">Submit</button>
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
                        <th class="text-center">Date From</th>
                        <th class="text-center">Date To</th>
                        <th class="text-center">Date Filed</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">View</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = mysqli_query($db, "SELECT * FROM tbl_offset_request WHERE emp_num = '$emp_num'");
                    while ($row = mysqli_fetch_assoc($sql)) {
                    ?>
                    <tr>
                            <td class="text-center"><?= $row['id'] ?></td>
                            <td class="text-center"><?= $row['date_from'] ?></td>
                            <td class="text-center"><?= $row['date_to'] ?></td>
                            <td class="text-center"><?= $row['date_filed'] ?></td>
                            <td class="text-center"><?= $row['status'] ?></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="offset_application_viewonly?<?= md5('id') . '=' . md5($row['id']) ?>" class="btn btn-primary">View</a>
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
