<?php include 'inc/config.php'; ?>
<?php include 'inc/template_start.php'; ?>
<?php include 'inc/page_head.php'; ?>
<?php
if (empty($_SESSION['hris_id'])) {
    header('Location: login');
}
?>

<div id="page-content">
    <div class="content-header">
        <div class="header-section">
            <h1>
                <i class="fa fa-users"></i>Payroll Employee Registry
            </h1>
        </div>
    </div>
    <!-- Datatables Content -->
    <div class="block full">
        <div class="block-title">
            <div class="block-options pull-right">
                <a href="javascript:void(0)" class="btn btn-alt btn-sm btn-default" onclick="$('#modal-choose-create').modal('show');">Add Registry</a>
                <a href="javascript:void(0)" class="btn btn-alt btn-sm btn-default" onclick="$('#modal-choose-employee').modal('show');">Edit Registry</a>
            </div>
            <h2><strong>Employee</strong> List</h2>
        </div>
        <?= $res ?>
        <div class="table-responsive">
            <table id="employee-list" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">Employee ID</th>
                        <th>Employee Name</th>
                        <th>Job Title</th>
                        <th>Company</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = mysqli_query($db, "SELECT * FROM tbl_employees");
                    while ($row = mysqli_fetch_assoc($sql)) {
                    ?>
                    <tr>
                            <td class="text-center"><?= $row['emp_num'] ?></td>
                            <td><?= $row['emp_name'] ?></td>
                            <td><?= $row['company_position'] ?></td>
                            <td><?= $row['company_name'] ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- END Datatables Content -->
</div>
<!-- END Page Content -->

<div id="modal-choose-employee" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h2 class="modal-title"><i class="fa fa-clock-o"></i> Choose Employee</h2>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form method="POST" class="form-horizontal form-bordered">
                        <div class="form-group">
                            <label>Employee ID</label>
                            <select name="emp_num" class="form-control select-chosen">
                            <?php
                                $sql = mysqli_query($db, "SELECT * FROM tbl_employees");
                                while ($row = mysqli_fetch_assoc($sql)) {
                             ?>
                             <option value="<?= $row['emp_num'] ?>"><?= $row['emp_num'] ?></option>
                        <?php
                        }
                        ?>
                        </select>
                        <br><br>
                        <button class="btn btn-primary btn-block" name="edit_employee">Proceed</button>
                        <br>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="modal-choose-create" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h2 class="modal-title"><i class="fa fa-clock-o"></i> Choose Employee</h2>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form method="POST" class="form-horizontal form-bordered">
                        <div class="form-group">
                            <label>Employee ID</label>
                            <select name="emp_num" class="form-control select-chosen">
                            <option value="null">Select one...</option>
                            <?php
                                $sql = mysqli_query($db, "SELECT * FROM tbl_personal_information WHERE account_created = '0'");
                                while ($row = mysqli_fetch_assoc($sql)) {
                             ?>
                             <option value="<?= $row['employee_number'] ?>"><?= $row['employee_number'] ?></option>
                        <?php
                        }
                        ?>
                        </select>
                        <br><br>
                        <button class="btn btn-primary btn-block" name="add_payroll_registry">Proceed</button>
                        <br>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include 'inc/template_scripts.php'; ?>

<!-- Load and execute javascript code used only in this page -->
<script src="js/pages/tablesDatatables.js"></script>
<script>
    $(function() {
        TablesDatatables.init();
    });
</script>

<?php include 'inc/template_end.php'; ?>