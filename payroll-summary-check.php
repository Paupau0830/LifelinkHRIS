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
                <i class="fa fa-building"></i>Payroll Summary
            </h1>
        </div>
    </div>
    <!-- END Datatables Header -->


    <!-- Datatables Content -->
    <div class="block full">
        <div class="block-title">
            <h2><strong><?= $cname ?></strong> Cutoff List</h2>
        </div>
        <?= $res ?>
        <div class="table-responsive">
            <table id="three-column" class="table table-vcenter table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">Reference Number</th>
                        <th class="text-center">Date From</th>
                        <th class="text-center">Date To</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = mysqli_query($db, "SELECT * FROM tbl_cutoffs WHERE company_name = '$cname' AND status = 'completed'");
                    while ($row = mysqli_fetch_assoc($sql)) {
                    ?>
                        <tr>
                            <td class="text-center"><?= $row['reference_num'] ?></td>
                            <td class="text-center"><?= $row['date_from'] ?></td>
                            <td class="text-center"><?= $row['date_to'] ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <br><a href="javascript:void(0)" class="btn btn-primary" onclick="$('#modal-choose-cutoff').modal('show');">Proceed</a>
    </div>
    <!-- END Datatables Content -->
</div>
<!-- END Page Content -->
<div id="modal-choose-cutoff" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h2 class="modal-title"><i class="fa fa-clock-o"></i> Choose Cutoff</h2>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form method="POST" class="form-horizontal form-bordered">
                        <div class="form-group">
            <label><br>Cutoffs:</label>

            <div class="table-responsive">
            <table id="three-column" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">Reference #</th>
                        <th class="text-center">Date From</th>
                        <th class="text-center">Date To</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = mysqli_query($db, "SELECT * FROM tbl_cutoffs WHERE company_name = '$cname' AND status = 'completed'");
                    while ($row = mysqli_fetch_assoc($sql)) {
                    ?>
                    <tr>
                            <td class="text-center"><input type="radio" name="selected_cutoff" checked="checked" value="<?= $row['reference_num'] ?>">&ensp;<?= $row['reference_num'] ?></td>
                            <td><?= $row['date_from'] ?></td>
                            <td><?= $row['date_to'] ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
                        </div>
                        <button class="btn btn-primary btn-block" name="view_summary">View</button>
                        <br>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



<?php include 'inc/template_scripts.php'; ?>

<!-- Load and execute javascript code used only in this page -->
<script src="js/pages/paginationTable.js"></script>
<script>
    $(function() {
        TablesDatatables.init();
    });
</script>

<?php include 'inc/template_end.php'; ?>