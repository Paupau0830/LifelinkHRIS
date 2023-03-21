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
$employee_number = '';
$sql = mysqli_query($db, "SELECT * FROM tbl_personal_information");
while ($row = mysqli_fetch_assoc($sql)) {
    if ($fid == md5($row['ID'])) {
        $rid = $row['ID'];
        $employee_number = $row['employee_number'];
    }
}
$em = '';
?>
<!-- Page content -->
<div id="page-content">
    <!-- eCommerce Dashboard Header -->
    <div class="content-header">
        <ul class="nav-horizontal text-center">
            <li>
                <a href="edit-employee?<?= md5('id') . '=' . $fid ?>"><i class="fa fa-info"></i> Information</a>
            </li>
            <li>
                <a href="edit-employee-education?<?= md5('id') . '=' . $fid ?>"><i class="fa fa-graduation-cap"></i> Education</a>
            </li>
            <li>
                <a href="edit-employee-contacts?<?= md5('id') . '=' . $fid ?>"><i class="fa fa-phone"></i> Emergency Contacts</a>
            </li>
            <li>
                <a href="edit-employee-ids?<?= md5('id') . '=' . $fid ?>"><i class="fa fa-id-card"></i> IDs</a>
            </li>
            <li>
                <a href="edit-employee-employment?<?= md5('id') . '=' . $fid ?>"><i class="fa fa-briefcase"></i> Employment Information</a>
            </li>
            <li class="active">
                <a href="edit-employee-documents?<?= md5('id') . '=' . $fid ?>"><i class="fa fa-file"></i> Documents</a>
            </li>
            <li>
                <a href="edit-employee-benefits?<?= md5('id') . '=' . $fid ?>"><i class="fa fa-exchange"></i> Benefits</a>
            </li>
            <!-- <li>
                <a href="edit-employee-balances?<?= md5('id') . '=' . $fid ?>"><i class="gi gi-wallet"></i> Balances</a>
            </li> -->
            <li>
                <a href="edit-employee-position?<?= md5('id') . '=' . $fid ?>"><i class="fa fa-database"></i> Position History</a>
            </li>
        </ul>
    </div>
    <div class="block full">
        <div class="block-title">
            <div class="block-options pull-right">
                <a href="javascript:void(0)" class="btn btn-alt btn-sm btn-default" onclick="$('#modal-add-document').modal('show');">Add Supporting Document</a>
            </div>
            <h2><strong>Supporting</strong> Documents</h2>
        </div>
        <?= $res ?>
        <div class="table-responsive">
            <table id="supporting-documents" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th class="text-center">Attachment</th>
                        <th class="text-center">Remarks</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = mysqli_query($db, "SELECT * FROM tbl_documents WHERE employee_number = '$employee_number'");
                    while ($row = mysqli_fetch_assoc($sql)) {
                    ?>
                        <tr>
                            <td class="text-center"><?= $row['ID'] ?></td>
                            <td class="text-center"><a href="uploads/<?= $row['attachment'] ?>" target="_blank">View</a></td>
                            <td class="text-center"><?= $row['remarks'] ?></td>
                            <td class="text-center">
                                <form method="POST">
                                    <input type="hidden" name="id" value="<?= $row['ID'] ?>">
                                    <button title="Edit" class="btn btn-xs btn-danger" name="btn_delete_document"><i class="fa fa-trash"></i></button>
                                </form>
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
<div id="modal-add-document" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h2 class="modal-title"><i class="fa fa-file"></i> Add Supporting Document</h2>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="employee_number" value="<?= $employee_number ?>">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Supporting Document</label>
                                <input type="file" class="form-control" name="attachment">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Remarks</label>
                                <textarea name="attachment_remarks" rows="3" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <br>
                    <button class="btn btn-primary" name="btn_add_document">Submit</button>
                    <br><br>
                </form>
            </div>
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