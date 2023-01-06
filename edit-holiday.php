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
$sql = mysqli_query($db, "SELECT * FROM tbl_holidays");
while ($row = mysqli_fetch_assoc($sql)) {
    if ($fid == md5($row['ID'])) {
        $rid = $row['ID'];
    }
}
?>
<!-- Page content -->
<div id="page-content">
    <!-- eCommerce Dashboard Header -->
    <div class="content-header">
        <div class="header-section">
            <h1>
                <i class="fa fa-calendar"></i>Edit Holiday
            </h1>
        </div>
    </div>
    <div class="block full">
        <div class="block-title">
            <div class="block-options pull-right">
                <a href="holiday-maintenance" class="btn btn-alt btn-sm btn-default">Holiday Maintenance</a>
            </div>
            <h2><strong>Holiday</strong> Maintenance</h2>
        </div>
        <?php
        $get_details = mysqli_query($db, "SELECT * FROM tbl_holidays WHERE ID = '$rid'");
        while ($row = mysqli_fetch_assoc($get_details)) {
        ?>
            <div class="container-fluid">
                <?= $res ?>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $row['ID'] ?>">
                    <input type="hidden" name="old_date" value="<?= $row['holiday_date'] ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date *</label>
                                <input type="date" name="holiday_date" class="form-control" required value="<?= $row['holiday_date'] ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label>Type</label>
                            <select name="type" required class="select-chosen" data-placeholder="Choose a holiday type..." style="width: 250px;">
                                <option></option>
                                <?php
                                $types = array('Regular Holiday', 'Normal Holiday', 'Non-working Holiday', 'Special non-working Holiday');
                                foreach ($types as $k => $v) {
                                    if ($v == $row['type']) {
                                        echo '<option selected>' . $v . '</option>';
                                    } else {
                                        echo '<option>' . $v . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" rows="3" class="form-control"><?= $row['description'] ?></textarea>
                    </div>
                    <button name="update_holiday" class="btn btn-primary">Update</button>
                </form>
            </div>
        <?php
        }
        ?>
    </div>
</div>
<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>
<?php include 'inc/template_end.php'; ?>