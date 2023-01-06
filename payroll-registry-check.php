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
    <!-- Datatables Header -->
    <div class="content-header">
        <div class="header-section">
            <h1>
                <i class="fa fa-building"></i><strong>Payroll Check Registry</strong>
            </h1>
        </div>
    </div>
    <!-- END Datatables Header -->
    <!-- Datatables Content -->
    <div class="block full">
        <div class="block-title">
            <h2><strong><?= $cname ?></strong> Employee List</h2>
            <?= $res ?>
        </div>
        <div class="table-responsive">
            <table id="three-column-table" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">Employee ID</th>
                        <th class="text-center"> Employee Name</th>
                        <th class="text-center">Job Title</th>
                        <th class="text-center">Basic Salary</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = mysqli_query($db, "SELECT * FROM tbl_employees WHERE company_name = '$cname'");
                    while ($row = mysqli_fetch_assoc($sql)) {
                    ?>
                        <tr>
                            <td class="text-center"><?= $row['emp_num'] ?></td>
                            <td class="text-center"><?= $row['emp_name'] ?></td>
                            <td class="text-center"><?= $row['company_position'] ?></td>
                            <td class="text-center"><?= $row['basic_salary'] ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>

            <br><a href="javascript:void(0)" class="btn btn-primary" onclick="$('#modal-choose-cutoff').modal('show');">Proceed</a>&nbsp;
            <a href="javascript:void(0)" class="btn btn-primary" onclick="$('#modal-delete-registry').modal('show');">Delete Registry</a>&nbsp;
        </div>
    </div>
    <!-- END Datatables Content -->
</div>
<!-- END Page Content -->

<div id="modal-choose-cutoff" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h2 class="modal-title"><i class="fa fa-calendar"></i> Choose Cutoff</h2>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form method="POST" class="form-horizontal form-bordered">
                        <div class="form-group">
                        <input type="hidden" name="company_name" value="<?= $cname ?>">
                            <label>Employee ID</label>
                            <select name="select_empnum" class="form-control">
                            <?php
                                $sql = mysqli_query($db, "SELECT * FROM tbl_employees WHERE company_name = '$cname'");
                                while ($row = mysqli_fetch_assoc($sql)) {
                             ?>
                             <option value="<?= $row['emp_num'] ?>"><?= $row['emp_num'] ?></option>
                        <?php
                        }
                        ?>
                        </select>
                        <label><br>Cutoffs:</label>
                        <table id="two-column-table" class="table table-vcenter table-condensed table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Ref #</th>
                                            <th class="text-center">Date From</th>
                                            <th class="text-center">Date To</th>
                                            <th class="text-center">Created by</th>
                                        </tr>
                                    </thead>
                                <tbody>
                                    <?php
                                        $sql = mysqli_query($db, "SELECT * FROM tbl_cutoffs WHERE company_name = '$cname' AND status = 'completed' ORDER BY reference_num DESC");
                                        while ($row = mysqli_fetch_assoc($sql)) {
                                        ?>
                                        <tr>
                                                <td class="text-center"><input type="radio" name="selected_cutoff" checked="checked" value="<?= $row['reference_num'] ?>">&ensp;<?= $row['reference_num'] ?></td>
                                                <td><?= $row['date_from'] ?></td>
                                                <td><?= $row['date_to'] ?></td>
                                                <td><?= $row['created_by'] ?></td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                            </tbody>
                        </table>
                        </div>
                        <button class="btn btn-primary btn-block" name="view_payslip">View</button>
                        <br>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="modal-delete-registry" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h2 class="modal-title"><i class="fa fa-calendar"></i> Delete Registry(s)</h2>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form method="POST" class="form-horizontal form-bordered">
                        <div class="form-group">
                            <input type="hidden" name="company_name" value="<?= $cname ?>">
                            <div class="table-responsive">
                                <table id="one-column-table" class="table table-vcenter table-condensed table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Ref #</th>
                                            <th>Date From</th>
                                            <th>Date To</th>
                                            <th>Created by</th>
                                        </tr>
                                    </thead>
                                <tbody>
                                    <?php
                                        $sql = mysqli_query($db, "SELECT * FROM tbl_cutoffs WHERE company_name = '$cname' AND status = 'completed' ORDER BY reference_num DESC");
                                        while ($row = mysqli_fetch_assoc($sql)) {
                                        ?>
                                <tr>
                                    <td><input type="checkbox" name="selected_cutoff_delete[]" value="<?= $row['reference_num'] ?>">&ensp;<?= $row['reference_num'] ?></td>
                                    <td><?= $row['date_from'] ?></td>
                                    <td><?= $row['date_to'] ?></td>
                                    <td><?= $row['created_by'] ?></td>
                                </tr>
                                    <?php
                                    }
                                    ?>
                            </tbody>
                        </table>
                        </div>
                        <button class="btn btn-primary btn-block" name="delete_registry">Delete</button>
                        <br>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include 'inc/template_scripts.php'; ?>

<!-- Load and execute javascript code used only in this page -->
<script src="js/pages/customPagination.js"></script>
<script>
    $(function() {
        TablesDatatables.init();
    });
</script>

<?php include 'inc/template_end.php'; ?>