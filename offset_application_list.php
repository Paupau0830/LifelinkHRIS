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
            <h2><strong>Submitted</strong> Form</h2>
        </div>
        <div class="container-fluid">
            <form method="POST" enctype="multipart/form-data">
                <div class="table-responsive">
            <table id="two-column" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th class="text-center">Employee</th>
                        <th class="text-center">Date From</th>
                        <th class="text-center">Date To</th>
                        <th class="text-center">Date Filed</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">View</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = mysqli_query($db, "SELECT * FROM tbl_offset_request WHERE approver = '$emp_num'");
                    while ($row = mysqli_fetch_assoc($sql)) {
                    ?>
                    <tr>
                            <td class="text-center"><?= $row['id'] ?></td>
                            <td class="text-center"><?= $row['emp_num'] ?></td>
                            <td class="text-center"><?= $row['date_from'] ?></td>
                            <td class="text-center"><?= $row['date_to'] ?></td>
                            <td class="text-center"><?= $row['date_filed'] ?></td>
                            <td class="text-center"><?= $row['status'] ?></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="offset_application_view?<?= md5('id') . '=' . md5($row['id']) ?>" class="btn btn-primary">View</a>
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
