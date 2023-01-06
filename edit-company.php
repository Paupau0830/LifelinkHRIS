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
$get_count_departments = mysqli_query($db, "SELECT COUNT(*) as c FROM tbl_departments WHERE company_id = '$rid'");
$count_dept = mysqli_fetch_assoc($get_count_departments);

$get_count_jg = mysqli_query($db, "SELECT COUNT(*) as c FROM tbl_job_grade WHERE company_id = '$rid'");
$count_jg = mysqli_fetch_assoc($get_count_jg);

$get_count_jgs = mysqli_query($db, "SELECT COUNT(*) as c FROM tbl_job_grade_set WHERE company_id = '$rid'");
$count_jgs = mysqli_fetch_assoc($get_count_jgs);
?>
<!-- Page content -->
<div id="page-content">
    <!-- eCommerce Dashboard Header -->
    <div class="content-header">
        <ul class="nav-horizontal text-center">
            <li class="active">
                <a href="edit-company?<?= md5('id') . '=' . $fid ?>"><i class="fa fa-info"></i> Information</a>
            </li>
            <li>
                <a href="edit-company-department?<?= md5('id') . '=' . $fid ?>"><i class="fa fa-sitemap"></i> Departments</a>
            </li>
            <li>
                <a href="edit-company-job-grade?<?= md5('id') . '=' . $fid ?>"><i class="fa fa-square"></i> Job Grade</a>
            </li>
            <li>
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
    <div class="row text-center">
        <div class="col-md-4">
            <a href="javascript:void(0)" class="widget widget-hover-effect2">
                <div class="widget-extra themed-background">
                    <h4 class="widget-content-light"><strong>Departments</strong></h4>
                </div>
                <div class="widget-extra-full"><span class="h2 animation-expandOpen"><?= $count_dept['c'] ?></span></div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="javascript:void(0)" class="widget widget-hover-effect2">
                <div class="widget-extra themed-background-dark">
                    <h4 class="widget-content-light"><strong>Job Grades</strong></h4>
                </div>
                <div class="widget-extra-full"><span class="h2 themed-color-dark animation-expandOpen"><?= $count_jg['c'] ?></span></div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="javascript:void(0)" class="widget widget-hover-effect2">
                <div class="widget-extra themed-background-dark">
                    <h4 class="widget-content-light"><strong>Job Grade Sets</strong></h4>
                </div>
                <div class="widget-extra-full"><span class="h2 themed-color-dark animation-expandOpen"><?= $count_jgs['c'] ?></span></div>
            </a>
        </div>
    </div>
    <div class="block full">
        <div class="block-title">
            <h2><strong>Company</strong> Information</h2>
        </div>
        <?= $res; ?>
        <div class="container-fluid">
            <form method="post" class="form-horizontal form-bordered">
                <input type="hidden" name="id" value="<?= $rid ?>">
                <div class="form-group">
                    <label>Company Name</label>
                    <div class="input-group">
                        <span class="input-group-btn">
                            <button name="edit_company" class="btn btn-primary">Update</button>
                        </span>
                        <input type="text" name="company_name" required class="form-control" placeholder="Enter Company Name..." value="<?= $cname ?>">
                    </div>
                </div>
            </form>
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
</script>