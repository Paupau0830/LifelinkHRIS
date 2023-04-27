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
                <a href="javascript:void(0)" class="btn btn-alt btn-sm btn-default" onclick="$('#modal-add-department').modal('show');">Add Department</a>
            </div>
            <h2><strong>Company</strong> Departments - <?= $cname ?></h2>
        </div>
        <div class="container-fluid">
            <?= $res ?>
            <div class="table-responsive">
                <table id="company-departments" class="table table-vcenter table-condensed table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">Manual ID</th>
                            <th class="text-center">Group</th>
                            <th class="text-center">Department Name</th>
                            <th class="text-center">Date Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // $sql = mysqli_query($db, "SELECT * FROM tbl_departments WHERE company_id = '$rid'");
                        $sql = mysqli_query($db, "SELECT a.*, b.name AS group_name FROM tbl_departments a, tbl_department_group b WHERE a.company_id = '$rid' AND a.group_id = b.id ORDER by a.ID ASC");
                        while ($row = mysqli_fetch_assoc($sql)) {
                        ?>
                            <tr>
                                <td><?= $row['manual_id'] ?></td>
                                <td><?= $row['group_name'] ?></td>
                                <td><?= $row['department'] ?></td>
                                <td><?= $row['date_created'] ?></td>

                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <br>
            <a href="javascript:void(0)" class="btn btn-warning" onclick="$('#modal-update-department').modal('show');">Update</a>&nbsp;
            <a href="javascript:void(0)" class="btn btn-danger" onclick="$('#modal-delete-department').modal('show');">Delete</a>

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
                <div class="container-fluid">
                    <form method="POST" enctype="multipart/form-data" class="form-horizontal form-bordered">
                        <input type="hidden" name="company_id" value="<?= $rid ?>">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label> Department Name:</label>
                                    <input type="text" name="department" required class="form-control" placeholder="Enter Department Name...">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label> Department ID:</label>
                                    <input type="text" name="dept_manual_id" required class="form-control" placeholder="Enter Manual ID...">
                                </div>
                            </div>
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
                        </div>
                        <button name="add_department" class="btn btn-primary btn-block">Submit</button>
                        <br>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="modal-update-department" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h2 class="modal-title"><i class="fa fa-sitemap"></i> Update Department</h2>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form method="POST" enctype="multipart/form-data" class="form-horizontal form-bordered">
                        <input type="hidden" name="company_id" value="<?= $rid ?>">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label> Department Name:</label>

                                    <select name="department" id="department" class="select-chosen" onchange="DeptName(this.value)">
                                        <option value="null">Select one...</option>
                                        <?php
                                        $sql = mysqli_query($db, "SELECT * FROM tbl_departments WHERE company_id = '$rid'");
                                        while ($row = mysqli_fetch_assoc($sql)) { ?>
                                            <option value="<?= $row['ID'] ?>"><?= $row['department'] ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label> Department ID:</label>
                                    <select name="dept_manual_id" id="dept_manual_id" class="form-control" readonly>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label> Modified Department ID:</label>
                                    <input type="text" name="m_dept_manual_id" id="m_dept_manual_id" required class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label> Department Group</label>
                                    <select name="dept_group" id="dept_group" class="form-control" readonly>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label> Department Group</label>
                                    <select name="m_dept_group" class="select-chosen">
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
                        </div>
                        <button name="update_departments" class="btn btn-primary btn-block">Submit</button>&nbsp;

                        <br>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="modal-delete-department" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h2 class="modal-title"><i class="fa fa-globe"></i> Delete Department</h2>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form method="POST" class="form-horizontal form-bordered">
                        <div class="form-group">
                            <div class="table-responsive">
                                <table id="tbl-ids" class="table table-vcenter table-condensed table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Manual ID</th>
                                            <th>Group</th>
                                            <th>Date Created</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = mysqli_query($db, "SELECT * FROM tbl_departments");
                                        while ($row = mysqli_fetch_assoc($sql)) {
                                        ?>
                                            <tr>
                                                <td><input type="checkbox" name="selected_departments_delete[]" value="<?= $row['ID'] ?>">&ensp;<?= $row['department'] ?></td>
                                                <td><?= $row['manual_id'] ?></td>
                                                <td><?= $row['group_id'] ?></td>
                                                <td><?= $row['date_created'] ?></td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div><br>
                            <button class="btn btn-danger btn-block" name="delete_departments">Delete</button>
                            <br>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>
<?php include 'inc/template_end.php'; ?>
<script src="js/pages/tablesDatatables.js"></script>
<script>
    function DeptName(id) {

        $.ajax({
            type: 'post',
            url: 'ajaxdata.php',
            data: {
                department_id_ajax: id
            },
            success: function(data) {
                $('#dept_manual_id').html(data);
            }
        })
        $.ajax({
            type: 'post',
            url: 'ajaxdata.php',
            data: {
                department_id_group: id
            },
            success: function(data) {
                $('#dept_group').html(data);
            }
        })



    }
</script>
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