<?php include 'inc/config.php'; ?>
<?php include 'inc/template_start.php'; ?>
<?php include 'inc/page_head.php'; ?>
<?php
if (empty($_SESSION['hris_id'])) {
    header('Location: login');
}
?>
<?php $company_id = $_SESSION['hris_company_id']; ?>
<div id="page-content">
    <div class="content-header">
        <div class="header-section">
            <h1>
                <i class="fa fa-files-o"></i>Audit Trail
            </h1>
        </div>
    </div>
    <!-- Datatables Content -->
    <div class="block full">
        <div class="block-title">
            <div class="block-options pull-right">
                <button class="btn btn-alt btn-sm btn-default" onclick="$('#modal_audit_trail').modal('show')">Export</button>
            </div>
            <h2><strong>Audit</strong> Trail</h2>
        </div>
        <div class="table-responsive">
            <table id="tbl-audit-trail" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Date Created</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = mysqli_query($db, "SELECT * FROM tbl_audit_trail");
                    while ($row = mysqli_fetch_assoc($sql)) {
                    ?>
                        <tr>
                            <td><?= $row['ID'] ?></td>
                            <td><?= $row['name'] ?></td>
                            <td><?= $row['description'] ?></td>
                            <td><?= $row['date_created'] ?></td>
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
<div id="modal_audit_trail" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h2 class="modal-title"><i class="fa fa-files-o"></i> Export Audit Trail</h2>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Start Date</label>
                            <input type="date" name="startDate" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>End Date</label>
                            <input type="date" name="endDate" class="form-control">
                        </div>
                        <button class="btn btn-primary btn-block" name="btn_csv_audit_trail" onclick="$('#modal_audit_trail').modal('hide')">Export</button>
                        <br>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>

<!-- Load and execute javascript code used only in this page -->
<script src="js/pages/tablesDatatables.js"></script>
<script>
    $(function() {
        TablesDatatables.init();
    });
    $('[name="startDate"]').change(function() {
        $('[name="endDate"]').attr('min', $(this).val());
    });
</script>

<?php include 'inc/template_end.php'; ?>