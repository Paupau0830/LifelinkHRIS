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
            <li class="active">
                <a href="edit-employee-ids?<?= md5('id') . '=' . $fid ?>"><i class="fa fa-id-card"></i> IDs</a>
            </li>
            <li>
                <a href="edit-employee-employment?<?= md5('id') . '=' . $fid ?>"><i class="fa fa-briefcase"></i> Employment Information</a>
            </li>
            <li>
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
            <h2><strong>Government</strong> IDs</h2>
        </div>
        <?= $res ?>
        <div class="container-fluid">
            <form method="POST">
                <?php
                $sql = mysqli_query($db, "SELECT * FROM tbl_government_id WHERE employee_number = '$employee_number'");
                while ($row = mysqli_fetch_assoc($sql)) {
                ?>
                    <input type="hidden" name="employee_number" value="<?= $row['employee_number'] ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>SSS</label>
                                <input type="text" name="sss" class="form-control" value="<?= $row['sss'] ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>PAG-IBIG</label>
                                <input type="text" name="pagibig" class="form-control" value="<?= $row['pagibig'] ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Philhealth</label>
                                <input type="text" name="philhealth" class="form-control" value="<?= $row['philhealth'] ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>TIN</label>
                                <input type="text" name="tin" class="form-control" value="<?= $row['tin'] ?>">
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary" name="btn_update_ids">Update</button>
                <?php
                }
                ?>
            </form>
        </div>
    </div>
    <div class="block full">
        <div class="block-title">
            <div class="block-options pull-right">
                <a href="javascript:void(0)" class="btn btn-alt btn-sm btn-default" onclick="$('#modal-add-id').modal('show');">Add Government ID</a>
            </div>
            <h2><strong>Other</strong> IDs</h2>
        </div>
        <div class="table-responsive">
            <table id="tbl-ids" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>ID Name</th>
                        <th>ID Number</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = mysqli_query($db, "SELECT * FROM tbl_ids WHERE employee_number = '$employee_number'");
                    while ($row = mysqli_fetch_assoc($sql)) {
                    ?>
                        <tr>
                            <td class="text-center"><?= $row['ID'] ?></td>
                            <td><?= $row['id_name'] ?></td>
                            <td><?= $row['id_number'] ?></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button data-toggle="tooltip" title="Edit" class="btn btn-xs btn-default" data-id="<?= $row['ID'] ?>" data-name="<?= $row['id_name'] ?>" data-number="<?= $row['id_number'] ?>">
                                        <i class="fa fa-pencil"></i>
                                    </button>
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
<div id="modal-add-id" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h2 class="modal-title"><i class="fa fa-id-card"></i> Add Government ID</h2>
            </div>
            <div class="modal-body">
                <form method="POST" class="form-horizontal form-bordered">
                    <input type="hidden" name="employee_number" value="<?= $employee_number ?>">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>ID Name</label>
                                    <input type="text" name="id_name" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>ID Number</label>
                                    <input type="text" name="id_number" class="form-control">
                                </div>
                            </div>
                        </div>
                        <br>
                        <button class="btn btn-primary" style="float:right" name="btn_add_id">Submit</button>
                        <br><br><br>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div id="modal-edit-id" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h2 class="modal-title"><i class="fa fa-id-card"></i> Edit Government ID</h2>
            </div>
            <div class="modal-body" id="modal-id-body">

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
    setInterval(function() {
        $('[data-id]').click(function(e) {
            var get_id_details = '';
            var id = $(this).data('id');
            var name = $(this).data('name');
            var number = $(this).data('number');
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "inc/config.php",
                data: {
                    get_id_details: get_id_details,
                    id: id,
                    name: name,
                    number: number
                },
                success: function(response) {
                    $('#modal-edit-id').modal('show');
                    $('#modal-id-body').html(response);
                }
            });
            e.preventDefault();
        });
    }, 1000);
</script>