<?php include 'inc/config.php'; ?>
<?php include 'inc/template_start.php'; ?>
<?php include 'inc/page_head.php'; ?>
<?php
if (empty($_SESSION['hris_id'])) {
    header('Location: login');
}
?>
<?php
$company_id = $_SESSION['hris_company_id'];
?>
<div id="page-content">
    <div class="content-header">
        <div class="header-section">
            <h1>
                <i class="fa fa-thumbs-up"></i>Benefits Approvers
            </h1>
        </div>
    </div>
    <div class="block">
        <div class="block-title">
            <div class="block-options pull-right">
                <a href="benefits-approver-roles" class="btn btn-alt btn-sm btn-default">Benefits Approver Roles</a>
            </div>
            <h2><strong>Add Benefits</strong> Approver</h2>
            <?= $res ?>
        </div>
        <div class="container-fluid">
            <form method="POST">
                <input type="hidden" name="company_id" value="<?= $company_id ?>">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Approver Name*</label>
                            <select name="user_id" class="select-chosen" data-placeholder="Select Approver">
                                <option></option>
                                <?php
                                $users = get_users();
                                $curr_users = array();
                                $get_current_approvers = mysqli_query($db, "SELECT * FROM tbl_benefits_approvers WHERE company_id = '$company_id'");
                                while ($row = mysqli_fetch_assoc($get_current_approvers)) {
                                    $curr_users[] = $row['user_id'];
                                }
                                foreach ($users as $k => $v) {
                                    if (!in_array($v['ID'], $curr_users)) {
                                        echo '<option value="' . $v['ID'] . '">' . $v['account_name'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Role *</label>
                            <select name="role" class="select-chosen" required data-placeholder="Choose a role..." style="width: 250px;">
                                <option></option>
                                <?php
                                $get_roles = mysqli_query($db, "SELECT * FROM tbl_benefits_approvers_role WHERE company_id = '$company_id' AND ID NOT IN (SELECT `role` FROM tbl_benefits_approvers)");
                                while ($v = mysqli_fetch_assoc($get_roles)) {
                                    echo '<option value="' . $v['ID'] . '">' . $v['role'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button name="add_benefits_approver" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
    <div class="block full">
        <div class="block-title">
            <h2><strong>Approver</strong> List</h2>
        </div>
        <div class="table-responsive">
            <table id="benefits-approver" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Name</th>
                        <th>Email Address</th>
                        <th>Role</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = mysqli_query($db, "SELECT * FROM tbl_benefits_approvers WHERE company_id = '$company_id'");
                    while ($row = mysqli_fetch_assoc($sql)) {
                        $role = '';
                        $role = get_benefits_role_name_by_ID($row['role']);
                        $role = $role['role'];
                        $user_details = get_user_details($row['user_id']);
                    ?>
                        <tr>
                            <td class="text-center"><?= $row['ID'] ?></td>
                            <td><?= $user_details['account_name'] ?></td>
                            <td><?= $user_details['email'] ?></td>
                            <td><?= $role ?></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <form method="POST">
                                        <input type="hidden" name="id" value="<?= $row['ID'] ?>">
                                        <button class="btn btn-xs btn-danger" name="btn_delete_benefits_apprrover"><i class="fa fa-trash"></i></button>
                                    </form>
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
    <!-- END Datatables Content -->
</div>
<!-- END Page Content -->

<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>

<!-- Load and execute javascript code used only in this page -->
<script src="js/pages/tablesDatatables.js"></script>
<script>
    $(function() {
        TablesDatatables.init();
    });
</script>

<?php include 'inc/template_end.php'; ?>