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
            <li>
                <a href="edit-company-department?<?= md5('id') . '=' . $fid ?>"><i class="fa fa-sitemap"></i> Departments</a>
            </li>
            <li>
                <a href="edit-company-job-grade?<?= md5('id') . '=' . $fid ?>"><i class="fa fa-square"></i> Job Grade</a>
            </li>
            <li class="active">
                <a href="edit-company-job-grade-set?<?= md5('id') . '=' . $fid ?>"><i class="gi gi-show_thumbnails"></i> Job Grade Set</a>
            </li>
            <li>
                <a href="edit-company-benefits?<?= md5('id') . '=' . $fid ?>"><i class="fa fa-exchange"></i> Benefits</a>
            </li>
            <li>
                <a href="edit-company-maintenance?<?= md5('id') . '=' . $fid ?>"><i class="gi gi-settings"></i> Maintenance</a>
            </li>
        </ul>
    </div>
    <div class="block full">
        <div class="block-title">
            <div class="block-options pull-right">
                <a href="javascript:void(0)" class="btn btn-alt btn-sm btn-default" onclick="$('#modal-add-job-grade').modal('show');">Add Job Grade Set</a>
            </div>
            <h2><strong>Company</strong> Job Grade Set - <?= $cname ?></h2>
        </div>
        <div class="container-fluid">
            <?= $res ?>
            <div class="table-responsive">
                <table id="company-job-grade-set" class="table table-vcenter table-condensed table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">ID</th>
                            <th>Job Grade Set</th>
                            <th>Date Created</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = mysqli_query($db, "SELECT * FROM tbl_job_grade_set WHERE company_id = '$rid'");
                        while ($row = mysqli_fetch_assoc($sql)) {
                        ?>
                            <tr>
                                <td class="text-center"><?= $row['ID'] ?></td>
                                <td><?= $row['job_grade_set'] ?></td>
                                <td><?= $row['date_created'] ?></td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="javascript:void(0)" data-toggle="tooltip" title="Edit" data-company-id="<?= $row['company_id'] ?>" data-job-grade-set-id="<?= $row['ID'] ?>" data-job-grade-set="<?= $row['job_grade_set'] ?>" class="btn btn-xs btn-default"><i class="fa fa-pencil"></i></a>
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
<div id="modal-add-job-grade" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h2 class="modal-title"><i class="gi gi-show_thumbnails"></i> Add Job Grade Set</h2>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" class="form-horizontal form-bordered">
                    <input type="hidden" name="company_id" value="<?= $rid ?>">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="text" name="job_grade_set" required class="form-control" placeholder="Enter Job Grade Set...">
                                <span class="input-group-btn">
                                    <button name="add_job_grade_set" class="btn btn-primary">Submit</button>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div id="modal-update-job-grade-set" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="modal-job-grade-set-content">

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
            $('*[data-job-grade-set-id]').on('click', function() {
                var job_grade_set_id = $(this).data("job-grade-set-id");
                var company_id = $(this).data("company-id");
                var job_grade_set = $(this).data("job-grade-set");
                var get_job_grade_set_details = '';
                $.ajax({
                    url: "inc/config.php",
                    method: "POST",
                    data: {
                        job_grade_set_id: job_grade_set_id,
                        company_id: company_id,
                        job_grade_set: job_grade_set,
                        get_job_grade_set_details: get_job_grade_set_details
                    },
                    success: function(data) {
                        $('#modal-job-grade-set-content').html(data);
                        $('#modal-update-job-grade-set').modal("show");
                    }
                });
            });
        }, 1000);
    });
</script>