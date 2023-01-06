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
                <i class="fa fa-building"></i><strong><?= $cname ?></strong> Company
            </h1>
        </div>
    </div>
    <!-- END Datatables Header -->
    <!-- Datatables Content -->
    <div class="block full">
        <div class="block-title">
            <h2><strong>Cut-off</strong> References</h2>
            <?= $res ?>
        </div>
        <div class="table-responsive">
            <table id="three-column-table" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">Reference Number</th>
                        <th class="text-center">Date From</th>
                        <th class="text-center">Date To</th>
                        <th class="text-center">Created By</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = mysqli_query($db, "SELECT * FROM tbl_cutoffs WHERE company_name = '$cname' AND status = 'active'");
                    while ($row = mysqli_fetch_assoc($sql)) {
                    ?>
                    <tr>
                            <td class="text-center"><?= $row['reference_num'] ?></td>
                            <td class="text-center"><?= $row['date_from'] ?></td>
                            <td class="text-center"><?= $row['date_to'] ?></td>
                            <td class="text-center"><?= $row['created_by'] ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>

            <br><a href="javascript:void(0)" class="btn btn-primary" onclick="$('#modal-create-cutoff').modal('show');">New</a>&nbsp;
            <a href="javascript:void(0)" class="btn btn-primary" onclick="$('#modal-delete-cutoff').modal('show');">Delete</a>&nbsp;
            <a href="javascript:void(0)" class="btn btn-primary" onclick="$('#modal-generate-payroll').modal('show');">Generate Payroll</a>
        </div>
    </div>
    <!-- END Datatables Content -->
</div>
<!-- END Page Content -->

<div id="modal-create-cutoff" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h2 class="modal-title"><i class="fa fa-calendar"></i> Add Cutoff</h2>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form method="POST" class="form-horizontal form-bordered">
                        <div class="form-group">
                        <input type="hidden" name="company_name" value="<?= $cname ?>">
                            <label>Date From</label>
                            <input type="date" name="date_from" required class="form-control">
                            <label>Date To</label>
                            <input type="date" name="date_to" required class="form-control">
                        </div>
                        <button class="btn btn-primary btn-block" name="add_cutoff">Add</button>
                        <br>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modal-generate-payroll" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h2 class="modal-title"><i class="fa fa-money"></i> Generate Payroll</h2>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form method="POST" class="form-horizontal form-bordered">
                        <input type="hidden" name="company_name" value="<?= $cname ?>">
                        <label>Choose cutoffs to proceed.</label><br>
                        <table id="two-column-table" class="table table-vcenter table-condensed table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Reference Number</th>
                                            <th class="text-center">Date From</th>
                                            <th class="text-center">Date To</th>
                                        </tr>
                                    </thead>
                                <tbody>
                                    <?php
                            $sql = mysqli_query($db, "SELECT * FROM tbl_cutoffs WHERE company_name = '$cname' AND status = 'active'");
                            while ($row = mysqli_fetch_assoc($sql)) {
                            ?>
                            <tr>
                                    <td class="text-center"><input type="checkbox" name="selected_cutoff[]" value="<?= $row['reference_num'] ?>">&ensp;<?= $row['reference_num'] ?></td>
                                    <td><?= $row['date_from'] ?></td>
                                    <td><?= $row['date_to'] ?></td>
                                </tr>
                            <?php
                            }
                            ?>
                            </tbody>
                        </table>
                        <br>
                        
                        <button class="btn btn-primary btn-block" name="generate_payroll">Generate Payroll</button>
                        <br>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modal-delete-cutoff" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h2 class="modal-title"><i class="fa fa-calendar"></i> Delete Cutoff(s)</h2>
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
                                            <th>Date From</th>
                                            <th>Date To</th>
                                            <th>Created by</th>
                                        </tr>
                                    </thead>
                                <tbody>
                                    <?php
                                        $sql = mysqli_query($db, "SELECT * FROM tbl_cutoffs WHERE status != 'completed'");
                                        while ($row = mysqli_fetch_assoc($sql)) {
                                        ?>
                                <tr>
                                    <td><input type="checkbox" name="selected_cutoff_delete[]" value="<?= $row['reference_num'] ?>">&ensp;<?= $row['date_from'] ?></td>
                                    <td><?= $row['date_to'] ?></td>
                                    <td><?= $row['created_by'] ?></td>
                                </tr>
                                    <?php
                                    }
                                    ?>
                            </tbody>
                        </table>
                        </div>
                        <button class="btn btn-primary btn-block" name="delete_cutoff">Delete</button>
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