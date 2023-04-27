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
                <a href="edit-company-department?<?= md5('id') . '=' . $fid ?>"><i class="fa fa-sitemap"></i>
                    Departments</a>
            </li>
            <li class="active">
                <a href="edit-company-group?<?= md5('id') . '=' . $fid ?>"><i class="fa fa-user"></i>
                    Group / Unit</a>
            </li>
            <li>
                <a href="edit-company-job-grade?<?= md5('id') . '=' . $fid ?>"><i class="fa fa-square"></i> Job
                    Grade</a>
            </li>
            <li>
                <a href="edit-company-job-grade-set?<?= md5('id') . '=' . $fid ?>"><i class="gi gi-show_thumbnails"></i>
                    Job Grade Set</a>
            </li>
            <!-- <li>
                <a href="edit-company-benefits?<?= md5('id') . '=' . $fid ?>"><i class="fa fa-exchange"></i>
                    Benefits</a>
            </li> -->
            <li>
                <a href="edit-company-maintenance?<?= md5('id') . '=' . $fid ?>"><i class="gi gi-settings"></i>
                    Maintenance</a>
            </li>
        </ul>
    </div>
    <div class="block full">
        <div class="block-title">
            <div class="block-options pull-right">
                <a href="javascript:void(0)" class="btn btn-alt btn-sm btn-default" onclick="$('#modal-add-group').modal('show');">Add Group</a>&nbsp;
                <a href="javascript:void(0)" class="btn btn-alt btn-sm btn-default" onclick="$('#modal-add-unit').modal('show');">Add Unit</a>
            </div>
            <h2><strong>Company</strong> Departments - <?= $cname ?></h2>
        </div>
        <div class="container-fluid">
            <?= $res ?>
            <h2><strong>GROUP</strong></h2>
            <div class="table-responsive">
                <table id="company-departments" class="table table-vcenter table-condensed table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">ID</th>
                            <th class="text-center">Group</th>
                            <th class="text-center">Date Created</th>
                            <th class="text-center">Created By</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = mysqli_query($db, "SELECT * FROM tbl_department_group WHERE company_id = '$rid'");
                        while ($row = mysqli_fetch_assoc($sql)) {
                        ?>
                            <tr>
                                <td class="text-center"><?= $row['id'] ?></td>
                                <td><?= $row['name'] ?></td>
                                <td class="text-center"><?= $row['created_date'] ?></td>
                                <td class="text-center"><?= $row['created_by'] ?></td>

                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="container-fluid">
            <h2><strong>UNIT</strong></h2>
            <div class="table-responsive">
                <table id="supporting-documents" class="table table-vcenter table-condensed table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">ID</th>
                            <th class="text-center">Group</th>
                            <th class="text-center">Unit</th>
                            <th class="text-center">Date Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = mysqli_query($db, "SELECT a.*, b.name AS group_name FROM tbl_department_unit a, tbl_department_group b WHERE a.company_id = '$rid' AND a.group_id = b.id");
                        while ($row = mysqli_fetch_assoc($sql)) {
                        ?>
                            <tr>
                                <td class="text-center"><?= $row['id'] ?></td>
                                <td><?= $row['group_name'] ?></td>
                                <td><?= $row['name'] ?></td>
                                <td class="text-center"><?= $row['created_date'] ?></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <br>
    </div>
</div>
<div id="modal-add-group" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h2 class="modal-title"><i class="fa fa-sitemap"></i> Add Group</h2>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" class="form-horizontal form-bordered">
                    <input type="hidden" name="company_id" value="<?= $rid ?>">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="text" name="group_name" required class="form-control" placeholder="Enter Group Name...">
                                <span class="input-group-btn">
                                    <button name="add_department_group" class="btn btn-primary">Submit</button>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div id="modal-add-unit" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h2 class="modal-title"><i class="fa fa-sitemap"></i> Add Unit</h2>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" class="form-horizontal form-bordered">
                    <input type="hidden" name="company_id" value="<?= $rid ?>">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label> Department Group</label>
                                    <select name="dept_group" class="select-chosen">
                                        <option value="null">Select one...</option>
                                        <?php
                                        $sql = mysqli_query($db, "SELECT * FROM tbl_department_group WHERE company_id = '$rid'");
                                        while ($row = mysqli_fetch_assoc($sql)) { ?>
                                            <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="text" name="dept_unit" required class="form-control" placeholder="Enter Unit Name...">
                                </div>
                            </div>
                        </div><br>
                        <button name="add_department_unit" class="btn btn-success btn-block">Submit</button>
                        <br>
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