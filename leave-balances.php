<?php include 'inc/config.php'; ?>
<?php include 'inc/template_start.php'; ?>
<?php include 'inc/page_head.php'; ?>
<?php
if (empty($_SESSION['hris_id'])) {
    header('Location: login');
}

$datenow = date('Y-m-d');
$monthnow = date('m');
$daynow = date('d');
$test_date_now = strtotime(date('Y-m-d'));
$newDate = date("Y-m-d", strtotime("+1 month", $test_date_now));
$newMonth = date("m", strtotime("+1 month", $test_date_now));

// Every 1st day of the Month. 
if ($daynow == '1') {

    mysqli_query($db, "UPDATE tbl_leave_balances SET SL = SL + 0.6667, updated_monthly = 1 WHERE ID > '0' AND updated_monthly = '0'");
    mysqli_query($db, "UPDATE tbl_leave_balances SET VL = VL + 1, updated_monthly = 1 WHERE ID > '0' AND updated_monthly = '0'");

    // ROUND OFF
    mysqli_query($db, "UPDATE tbl_leave_balances SET SL = ROUND(SL,4) WHERE ID > '0' ");
    mysqli_query($db, "UPDATE tbl_leave_balances SET VL = ROUND(VL,2) WHERE ID > '0'");
}

if ($daynow == '2') {
    mysqli_query($db, "UPDATE tbl_leave_balances SET updated_monthly = 0 WHERE ID > '0' AND updated_monthly = '1'");
}
?>

<div id="page-content">
    <div class="content-header">
        <div class="header-section">
            <h1>
                <i class="gi gi-wallet"></i><strong>Leave Balances</strong>
            </h1>

        </div>
    </div>
    <!-- Datatables Content -->
    <div class="block full">
        <div class="block-title">
            <div class="block-options pull-right">
                <a href="leave-list" class="btn btn-alt btn-sm btn-default">Leave Application List</a>
            </div>
            <h2><strong>Leave Balances </strong>List </h2>
            <input type="hidden" value="<?= $monthnow ?>">
            <input type="hidden" value="<?= $newDate ?>">
        </div>
        <div class="table-responsive">
            <table id="three-column" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">Account Name</th>
                        <th class="text-center">Employee Number</th>
                        <th class="text-center">SL</th>
                        <th class="text-center">VL</th>
                        <th class="text-center">Others</th>
                        <th class="text-center" style="width:60px;">Maternity</th>
                        <th class="text-center" style="width:60px;">Paternity</th>
                        <?php
                        if ($_SESSION['hris_role'] == "Admin") { ?>
                            <th class="text-center">Action</th>
                        <?php } ?>




                    </tr>
                </thead>
                <tbody class="text-center">
                    <?php
                    $company_id = $_SESSION['hris_company_id'];
                    $sql = mysqli_query($db, "SELECT t.*, t1.company, t2.company_name FROM tbl_leave_balances t
                    INNER JOIN tbl_employment_information t1
                    ON t.employee_number = t1.employee_number
                    INNER JOIN tbl_companies t2
                    ON t1.company = t2.ID
                    WHERE t1.company = '$company_id'");
                    while ($row = mysqli_fetch_assoc($sql)) {
                        $name = get_personal_information($row['employee_number']);
                    ?>
                        <tr>
                            <td class="text-center"><?= $name['account_name'] ?></td>
                            <td class="text-center"><?= $row['employee_number'] ?></td>
                            <td class="text-center"><?= $row['SL'] ?></td>
                            <td class="text-center"><?= $row['VL'] ?></td>
                            <td class="text-center"><?= $row['EL'] ?></td>
                            <td class="text-center"><?= $row['maternity'] ?></td>
                            <td class="text-center"><?= $row['paternity'] ?></td>
                            <?php
                            if ($_SESSION['hris_role'] == "Admin") { ?>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="edit-leave-balances?<?= md5('id') . '=' . md5($row['employee_number']) ?>" data-toggle="tooltip" title="Edit" class="btn btn-s btn-primary"><i class="fa fa-pencil"></i> &nbsp;Edit</a>
                                    </div>
                                </td>
                            <?php } ?>

                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="block full">
        <div class="block-title">
            <h2><strong>Solo Parent </strong>List </h2>
            <input type="hidden" value="<?= $monthnow ?>">
            <input type="hidden" value="<?= $newDate ?>">
        </div>

        <div class="table-responsive">
            <table id="two-column" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr class="text-center">
                        <th class="text-center">Account Name</th>
                        <th class="text-center">Employee Number</th>
                        <th class="text-center">Solo Parent</th>





                    </tr>
                </thead>
                <tbody class="text-center">
                    <?php
                    $company_id = $_SESSION['hris_company_id'];
                    $sql = mysqli_query($db, "SELECT * FROM tbl_leave_balances WHERE solo_parent != '0'");
                    while ($row = mysqli_fetch_assoc($sql)) {
                        $name = get_personal_information($row['employee_number']);
                    ?>
                        <tr>
                            <td class="text-center"><?= $name['account_name'] ?></td>
                            <td class="text-center"><?= $row['employee_number'] ?></td>
                            <td class="text-center"><?= $row['solo_parent'] ?></td>


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
<?php include 'inc/template_end.php'; ?>
<script src="js/pages/tablesDatatables.js"></script>
<script src="js/pages/paginationTable.js"></script>

<script>
    $(function() {
        TablesDatatables.init();
    });
</script>