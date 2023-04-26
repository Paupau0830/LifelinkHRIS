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
$rid = ""; // row id
$cname = '';
$sql = mysqli_query($db, "SELECT * FROM tbl_companies");
while ($row = mysqli_fetch_assoc($sql)) {
    if ($fid == md5($row['ID'])) {
        $rid = $row['ID'];
        $cname = $row['company_name'];
    }
}
?>
<!-- Page content -->
<div id="page-content">
    <!-- eCommerce Dashboard Header -->
    <div class="content-header">
        <ul class="nav-horizontal text-center">
            <li>
                <a href="edit-company?<?= md5('id') . '=' . $fid ?>"><i class="fa fa-info"></i> Information</a>
            </li>
            <li class="active">
                <a href="edit-company-department?<?= md5('id') . '=' . $fid ?>"><i class="fa fa-sitemap"></i>
                    Departments</a>
            </li>
            <li>
                <a href="edit-company-job-grade?<?= md5('id') . '=' . $fid ?>"><i class="fa fa-square"></i> Job
                    Grade</a>
            </li>
            <li>
                <a href="edit-company-job-grade-set?<?= md5('id') . '=' . $fid ?>"><i class="gi gi-show_thumbnails"></i>
                    Job Grade Set</a>
            </li>
            <li>
                <a href="edit-company-benefits?<?= md5('id') . '=' . $fid ?>"><i class="fa fa-exchange"></i>
                    Benefits</a>
            </li>
            <li>
                <a href="edit-company-maintenance?<?= md5('id') . '=' . $fid ?>"><i class="gi gi-settings"></i>
                    Maintenance</a>
            </li>
        </ul>
    </div>
    <div class="block full">
        <div class="block-title">
            <div class="block-options pull-right">
                <a href="javascript:void(0)" class="btn btn-alt btn-sm btn-default"
                    onclick="$('#modal-add-department').modal('show');">Add Department</a>
            </div>
            <h2><strong>Company</strong> Departments - <?= $cname ?></h2>
        </div>
        <div class="container-fluid">
            <?= $res ?>
            <div class="table-responsive">
                <table id="company-departments" class="table table-vcenter table-condensed table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">ID</th>
                            <th class="text-center">Manual ID</th>
                            <th class="text-center">Group</th>
                            <th class="text-center">Department Name</th>
                            <th class="text-center">Date Created</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = mysqli_query($db, "SELECT * FROM tbl_departments WHERE company_id = '$rid'");
                        while ($row = mysqli_fetch_assoc($sql)) {
                        ?>
                        <tr>
                            <td class="text-center"><?= $row['ID'] ?></td>
                            <td><?= $row['manual_id'] ?></td>
                            <td><?= $row['group_id'] ?></td>
                            <td><?= $row['department'] ?></td>
                            <td><?= $row['date_created'] ?></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="javascript:void(0)" data-toggle="tooltip" title="Edit"
                                        data-company-id="<?= $row['company_id'] ?>"
                                        data-department-id="<?= $row['ID'] ?>"
                                        data-department="<?= $row['department'] ?>" class="btn btn-xs btn-default"><i
                                            class="fa fa-pencil"></i></a>
                                </div>
                            </td>
                        </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div id="modal-add-department" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h2 class="modal-title"><i class="fa fa-sitemap"></i> Add Department</h2>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" class="form-horizontal form-bordered">
                    <input type="hidden" name="company_id" value="<?= $rid ?>">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="text" name="department" required class="form-control"
                                    placeholder="Enter Department Name...">
                                <span class="input-group-btn">
                                    <button name="add_department" class="btn btn-primary">Submit</button>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div id="modal-update-dept" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="modal-dept-content">

        </div>
    </div>
</div>
<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>
<?php include 'inc/template_end.php'; ?>
<script src="js/pages/tablesDatatables.js"></script>
<script>
$(function() {
    TablesDatatables.init();
});
$(document).ready(function() {
    setInterval(function() {
        $('*[data-department-id]').on('click', function() {
            var department_id = $(this).data("department-id");
            var company_id = $(this).data("company-id");
            var department_name = $(this).data("department");
            var get_department_details = '';
            $.ajax({
                url: "inc/config.php",
                method: "POST",
                data: {
                    department_id: department_id,
                    company_id: company_id,
                    department_name: department_name,
                    get_department_details: get_department_details
                },
                success: function(data) {
                    $('#modal-dept-content').html(data);
                    $('#modal-update-dept').modal("show");
                }
            });
        });
    }, 1000);
});
</script>