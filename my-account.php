<?php include 'inc/config.php'; ?>
<?php include 'inc/template_start.php'; ?>
<?php include 'inc/page_head.php'; ?>
<?php
if (empty($_SESSION['hris_id'])) {
    header('Location: login');
}
$rid = $_SESSION['hris_id']; // row id
?>
<!-- Page content -->
<div id="page-content">
    <!-- eCommerce Dashboard Header -->
    <div class="content-header">
        <div class="header-section">
            <h1>
                <i class="fa fa-user"></i>My Account
            </h1>
        </div>
    </div>
    <div class="block full">
        <div class="block-title">
            <div class="block-options pull-right">
                <a href="javascript:void(0)" class="btn btn-alt btn-sm btn-default" onclick="$('#modal-change-password').modal('show');">Change Password</a>
            </div>
            <h2><strong>My</strong> Account</h2>
        </div>
        <div class="container-fluid">
            <?= $res ?>
            <form method="POST">
                <?php
                $get_details = mysqli_query($db, "SELECT * FROM tbl_users WHERE ID = '$rid'");
                while ($r = mysqli_fetch_assoc($get_details)) {
                    $em = $r['email'];
                ?>
                    <input type="hidden" name="id" value="<?= $r['ID'] ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="email" name="email" class="form-control" value="<?= $r['email'] ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Account Name</label>
                                <input type="text" readonly name="account_name" class="form-control" value="<?= $r['account_name'] ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Role</label>
                                <input type="text" readonly class="form-control" value="<?= $r['role'] ?>">
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
                <button class="btn btn-primary" name="btn_update_myaccount">Update</button>
            </form>
        </div>
    </div>
</div>
<div id="modal-change-password" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h2 class="modal-title"><i class="fa fa-lock"></i> Change Password</h2>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form method="POST" class="form-horizontal form-bordered">
                        <input type="hidden" name="id" value="<?= $rid ?>">
                        <div class="form-group">
                            <label>New Password</label>
                            <input type="password" name="new_password" required minlength="6" class="form-control">
                        </div>
                        <button class="btn btn-primary btn-block" name="account_change_password">Update</button>
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
    $(function() {
        TablesDatatables.init();
    });
    $('#email').change(function() {
        var email = $(this).val();
        var get_account_name = '';
        $.ajax({
            url: "inc/config.php",
            method: "POST",
            data: {
                get_account_name: get_account_name,
                email: email
            },
            success: function(data) {
                $('#account_name').val(data);
            }
        });
    });
</script>