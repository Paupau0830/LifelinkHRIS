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
            <li>
                <a href="my-account-education"><i class="fa fa-graduation-cap"></i> Education</a>
            </li>
            <li class="active">
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
            <div class="block-options pull-right">
                <a href="javascript:void(0)" class="btn btn-alt btn-sm btn-default" onclick="$('#modal-add-contact').modal('show');">Add Emergency Contact</a>
            </div>
            <h2><strong>Emergency</strong> Contacts</h2>
        </div>
        <?= $res ?>
        <div class="table-responsive">
            <table id="emergency-contacts" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Contact Name</th>
                        <th>Contact Number</th>
                        <th>Email Address</th>
                        <th>Relationship</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = mysqli_query($db, "SELECT * FROM tbl_emergency_contacts WHERE employee_number = '$employee_number'");
                    while ($row = mysqli_fetch_assoc($sql)) {
                    ?>
                        <tr>
                            <td class="text-center"><?= $row['ID'] ?></td>
                            <td><?= $row['contact_name'] ?></td>
                            <td><?= $row['contact_number'] ?></td>
                            <td><?= $row['email_address'] ?></td>
                            <td><?= $row['relationship'] ?></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button data-toggle="tooltip" title="Edit" class="btn btn-xs btn-default" data-id="<?= $row['ID'] ?>" data-name="<?= $row['contact_name'] ?>" data-number="<?= $row['contact_number'] ?>" data-email="<?= $row['email_address'] ?>" data-relationship="<?= $row['relationship'] ?>">
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
<div id="modal-add-contact" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h2 class="modal-title"><i class="fa fa-phone"></i> Add Emergency Contact</h2>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form method="POST" class="form-horizontal form-bordered">
                        <input type="hidden" name="employee_number" value="<?= $employee_number ?>">
                        <div class="form-group">
                            <label>Contact Name</label>
                            <input type="text" name="contact_name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Contact Number</label>
                            <input type="text" name="contact_number" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="text" name="email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Relationship</label>
                            <input type="text" name="relationship" class="form-control">
                        </div>
                        <br>
                        <button class="btn btn-primary" style="float:right" name="btn_add_emergency_contact">Submit</button>
                        <br><br><br>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<div id="modal-edit-contact" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h2 class="modal-title"><i class="fa fa-phone"></i> Edit Emergency Contact</h2>
            </div>
            <div class="modal-body" id="modal-contact-body">

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
            var get_contact_details = '';
            var id = $(this).data('id');
            var name = $(this).data('name');
            var number = $(this).data('number');
            var email = $(this).data('email');
            var relationship = $(this).data('relationship');
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "inc/config.php",
                data: {
                    get_contact_details: get_contact_details,
                    id: id,
                    name: name,
                    number: number,
                    email: email,
                    relationship: relationship
                },
                success: function(response) {
                    $('#modal-edit-contact').modal('show');
                    $('#modal-contact-body').html(response);
                }
            });
            e.preventDefault();
        });
    }, 1000);
</script>