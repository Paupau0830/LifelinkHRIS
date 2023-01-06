<?php include 'inc/config.php'; ?>
<?php include 'inc/template_start.php'; ?>
<?php include 'inc/page_head.php'; ?>
<?php
if (empty($_SESSION['hris_id'])) {
    header('Location: login');
}

$company_id = $_SESSION['hris_company_id'];
$empnum = $_SESSION['hris_employee_number'];
?>

<div id="page-content">
    <div class="content-header">
        <div class="header-section">
            <h1>
                <i class="fa fa-users"></i><strong>Leave Application</strong>
            </h1>
        </div>
    </div>
    <!-- Datatables Content -->
    <div class="block full">
        <div class="block-title">
            <div class="block-options pull-left">
                <!-- <a href="leave-application" class="btn btn-primary">Leave Application</a> -->
            </div>
            <h2><strong>Leave Application</strong> Summary List</h2>
        </div>
        <div class="table-responsive">
            <table id="leave-list" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Employee Number</th>
                        <th>Employee Name</th>
                        <th>Leave Type</th>
                        <th>Duration</th>
                        <th>Total Days</th>
                        <th>Status</th>
                        <th>Date Created</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <?php
                $role = $_SESSION['hris_role'];
                ?>
                <tbody>
                    <?php
                    if ($role == "Admin" || $role == "Supervisor") {
                        $sql = mysqli_query($db, "SELECT * FROM tbl_leave_requests");
                        while ($row = mysqli_fetch_assoc($sql)) {
                    ?>
                            <tr>
                                <td class="text-center"><?= format_transaction_id($row['ID']) ?></td>
                                <td><?= $row['delegated_emp_number'] ?></td>
                                <td><?= $row['emp_name'] ?></td>
                                <td><?= $row['leave_type'] ?></td>
                                <td><?= $row['startDate'] . ' >> ' . $row['endDate'] ?></td>
                                <td><?= $row['total_day'] ?></td>
                                <td><?= $row['status'] ?></td>
                                <td><?= $row['date_filed'] ?></td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="leave-details?<?= md5('id') . '=' . md5($row['ID']) ?>" data-toggle="tooltip" title="View" class="btn btn-s btn-default"><i class="fa fa-eye">&nbsp; View</i></a>
                                    </div>
                                </td>
                            </tr>
                    <?php
                        }
                    }
                    ?>

                    <?php
                    if ($role == "User") {
                        $sql = mysqli_query($db, "SELECT * FROM tbl_leave_requests WHERE delegated_emp_number = '$empnum'");
                        while ($row = mysqli_fetch_assoc($sql)) {
                    ?>
                            <tr>
                                <td class="text-center"><?= format_transaction_id($row['ID']) ?></td>
                                <td><?= $row['delegated_emp_number'] ?></td>
                                <td><?= $row['emp_name'] ?></td>
                                <td><?= $row['leave_type'] ?></td>
                                <td><?= $row['startDate'] . ' >> ' . $row['endDate'] ?></td>
                                <td><?= $row['total_day'] ?></td>
                                <td><?= $row['status'] ?></td>
                                <td><?= $row['date_filed'] ?></td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="leave-details?<?= md5('id') . '=' . md5($row['ID']) ?>" data-toggle="tooltip" title="View" class="btn btn-s btn-default"><i class="fa fa-eye">&nbsp; View</i></a>
                                    </div>
                                </td>
                            </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- END Datatables Content -->
</div>
<!-- END Page Content -->

<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>

<!-- Load and execute javascript code used only in this page -->
<script src="js/pages/tablesDatatables.js"></script>
<script>
    $(function() {
        TablesDatatables.init();
    });
</script>

<?php include 'inc/template_end.php'; ?>