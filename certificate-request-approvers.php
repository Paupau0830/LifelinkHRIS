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
                <i class="fa fa-money"></i>Certificate Request Approvers
            </h1>
        </div>
    </div>
    <?= $res ?>
    <div class="block">
        <div class="block-title">
            <h2><strong>Add Certificate Request</strong> Approver</h2>
        </div>
        <div class="container-fluid">
            <form method="POST">
                <input type="hidden" name="company_id" value="<?= $company_id ?>">
                <div class="form-group">
                    <label>Approver Name*</label>
                    <select name="employee_number" class="select-chosen" data-placeholder="Select Approver">
                        <option></option>
                        <?php
                        $users = get_users();
                        $curr_users = array();
                        $get_current_approvers = mysqli_query($db, "SELECT * FROM tbl_certificate_requests_approvers WHERE company_id = '$company_id'");
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
                <div class="form-group">
                    <button name="add_certificate_approver" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
    <div class="block full">
        <div class="block-title">
            <h2><strong>Approvers</strong> List</h2>
        </div>
        <div class="table-responsive">
            <table id="certificate-request-approver" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Name</th>
                        <th>Email Address</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = mysqli_query($db, "SELECT * FROM tbl_certificate_requests_approvers WHERE company_id = '$company_id'");
                    while ($row = mysqli_fetch_assoc($sql)) {
                        $user_details = get_user_details($row['user_id']);
                    ?>
                        <tr>
                            <td class="text-center"><?= $row['ID'] ?></td>
                            <td><?= $user_details['account_name'] ?></td>
                            <td><?= $user_details['email'] ?></td>
                            <td class="text-center">
                                <form method="POST">
                                    <input type="hidden" name="id" value="<?= $row['ID'] ?>">
                                    <button class="btn btn-sm btn-danger" name="btn_delete_certificate_approver"><i class="fa fa-trash"></i></button>
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