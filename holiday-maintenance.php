<?php include 'inc/config.php'; ?>
<?php include 'inc/template_start.php'; ?>
<?php include 'inc/page_head.php'; ?>
<?php
if (empty($_SESSION['hris_id'])) {
    header('Location: login');
}
?>

<div id="page-content">
    <div class="content-header">
        <div class="header-section">
            <h1>
                <i class="fa fa-calendar"></i>Holiday Maintenance
            </h1>
        </div>
    </div>
    <div class="block">
        <div class="block-title">
            <h2><strong>Add</strong> Holiday</h2>
            <?= $res ?>
        </div>
        <div class="container-fluid">
            <form method="post">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Date</label>
                            <input type="date" name="holiday_date" required class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Type</label>
                            <select name="holiday_type" required class="select-chosen" data-placeholder="Choose a holiday type..." style="width: 250px;">
                                <option></option>
                                <option>Regular Holiday</option>
                                <option>Normal Holiday</option>
                                <option>Non-working Holiday</option>
                                <option>Special non-working Holiday</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="3" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <button name="add_holiday" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
    <div class="block full">
        <div class="block-title">
            <h2><strong>Holiday</strong> List</h2>
        </div>
        <div class="table-responsive">
            <table id="holiday-list" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = mysqli_query($db, "SELECT * FROM tbl_holidays");
                    while ($row = mysqli_fetch_assoc($sql)) {
                    ?>
                        <tr>
                            <td class="text-center"><?= $row['ID'] ?></td>
                            <td><?= $row['holiday_date'] ?></td>
                            <td><?= $row['type'] ?></td>
                            <td><?= $row['description'] ?></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="edit-holiday?<?= md5('id') . '=' . md5($row['ID']) ?>" data-toggle="tooltip" title="Edit" class="btn btn-xs btn-default"><i class="fa fa-pencil"></i></a>
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