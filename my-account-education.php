<?php include 'inc/config.php'; ?>
<?php include 'inc/template_start.php'; ?>
<?php include 'inc/page_head.php'; ?>
<?php
if (empty($_SESSION['hris_id'])) {
    header('Location: login');
}
?>
<?php
$employee_number = $_SESSION['hris_employee_number'];
?>
<!-- Page content -->
<div id="page-content">
    <!-- eCommerce Dashboard Header -->
    <div class="content-header">
        <ul class="nav-horizontal text-center">
            <li>
                <a href="my-account"><i class="fa fa-info"></i> Information</a>
            </li>
            <li class="active">
                <a href="my-account-education"><i class="fa fa-graduation-cap"></i> Education</a>
            </li>
            <li>
                <a href="my-account-contacts"><i class="fa fa-phone"></i> Emergency Contacts</a>
            </li>
            <li>
                <a href="my-account-ids"><i class="fa fa-id-card"></i> IDs</a>
            </li>
            <li>
                <a href="my-account-employment"><i class="fa fa-briefcase"></i> Employment Information</a>
            </li>
            <li>
                <a href="my-account-documents"><i class="fa fa-file"></i> Documents</a>
            </li>
            <li>
                <a href="my-account-benefits"><i class="fa fa-exchange"></i> Benefits</a>
            </li>
            <li>
                <a href="my-account-balances"><i class="gi gi-wallet"></i> Balances</a>
            </li>
            <li>
                <a href="my-account-position"><i class="fa fa-database"></i> Position History</a>
            </li>
        </ul>
    </div>
    <div class="block full">
        <div class="block-title">
            <h2><strong>Post Graduate</strong> School</h2>
        </div>
        <?= $res ?>
        <div class="container-fluid">
            <form method="POST">
                <?php
                $sql = mysqli_query($db, "SELECT * FROM tbl_post_graduate WHERE employee_number = '$employee_number'");
                while ($row = mysqli_fetch_assoc($sql)) {
                ?>
                    <input type="hidden" name="employee_number" value="<?= $row['employee_number'] ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Post Graduate School</label>
                                <input type="text" name="post_graduate_school" class="form-control" value="<?= $row['school'] ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>From</label>
                                        <input type="text" id="from_pgs" name="from_pgs" class="form-control" value="<?= $row['from_date'] ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>To</label>
                                        <input type="text" id="to_pgs" name="to_pgs" class="form-control" value="<?= $row['to_date'] ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary" name="btn_update_post_graduate">Update</button>
                <?php
                }
                ?>
            </form>
        </div>
    </div>
    <div class="block full">
        <div class="block-title">
            <div class="block-options pull-right">
                <a href="javascript:void(0)" class="btn btn-alt btn-sm btn-default" onclick="$('#modal-add-college').modal('show');">Add University / College</a>
            </div>
            <h2><strong>University</strong> / College</h2>
        </div>
        <div class="table-responsive">
            <table id="univ-col" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>University / College</th>
                        <th>Degree</th>
                        <th>From</th>
                        <th>To</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = mysqli_query($db, "SELECT * FROM tbl_college WHERE employee_number = '$employee_number'");
                    while ($row = mysqli_fetch_assoc($sql)) {
                    ?>
                        <tr>
                            <td class="text-center"><?= $row['ID'] ?></td>
                            <td><?= $row['college'] ?></td>
                            <td><?= $row['degree'] ?></td>
                            <td><?= $row['from_date'] ?></td>
                            <td><?= $row['to_date'] ?></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button data-toggle="tooltip" title="Edit" class="btn btn-xs btn-default" data-id="<?= $row['ID'] ?>" data-college="<?= $row['college'] ?>" data-degree="<?= $row['degree'] ?>" data-from="<?= $row['from_date'] ?>" data-to="<?= $row['to_date'] ?>">
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
<div id="modal-add-college" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h2 class="modal-title"><i class="fa fa-graduation-cap"></i> Add University / College</h2>
            </div>
            <div class="modal-body">
                <form method="POST" class="form-horizontal form-bordered">
                    <input type="hidden" name="employee_number" value="<?= $employee_number ?>">
                    <div class="container-fluid">
                        <div class="form-group">
                            <label>University / College Name</label>
                            <input type="text" name="college" required class="form-control">
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>From</label>
                                    <input type="text" name="from_date" required class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>To</label>
                                    <input type="text" name="to_date" required class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Degree</label>
                            <input type="text" name="degree" required class="form-control">
                        </div>
                        <br>
                        <button class="btn btn-primary" style="float:right" name="add_college">Submit</button>
                        <br><br><br>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div id="modal-edit-college" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h2 class="modal-title"><i class="fa fa-graduation-cap"></i> Edit University / College</h2>
            </div>
            <div class="modal-body" id="modal-college-body">

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
            var get_educ_details = '';
            var id = $(this).data('id');
            var college = $(this).data('college');
            var degree = $(this).data('degree');
            var from = $(this).data('from');
            var to = $(this).data('to');
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "inc/config.php",
                data: {
                    get_educ_details: get_educ_details,
                    id: id,
                    college: college,
                    degree: degree,
                    from: from,
                    to: to
                },
                success: function(response) {
                    $('#modal-edit-college').modal('show');
                    $('#modal-college-body').html(response);
                }
            });
            e.preventDefault();
        });
    }, 1000);
</script>