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
                <i class="fa fa-user"></i>Offboarding
            </h1>
        </div>
    </div>
    <div class="block full">
        <div class="block-title">
            <h2><strong>Offboarding</strong> Form</h2>
        </div>
        <div class="container-fluid">
            <?= $res ?>
            <form method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Employee ID*</label>
                            <select name="emp_num" class="form-control select-chosen" onchange="FetchState(this.value)">
                            <option value="null">Select one...</option>
                            <?php
                                $sql = mysqli_query($db, "SELECT * FROM tbl_employees");
                                while ($row = mysqli_fetch_assoc($sql)) {
                             ?>
                             <option value="<?= $row['emp_num'] ?>"><?= $row['emp_num'] ?></option>
                        <?php
                        }
                        ?>
                        </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Employee Name</label>
                            <select name="employee_name" id="employee_name" class="form-control" readonly>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label>Date*</label>
                        <input type="date" name="date" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Reason*</label>
                            <textarea name="reason" rows="3" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <button class="btn btn-success" name="btn_offboarding">Submit</button>
            </form>
            
        </div>
        
    </div>

    <div class="block full">
        <div class="block-title">
            <h2><strong>Offboarding</strong> List</h2>

        </div>
        <div class="container-fluid">
            <form method="POST" enctype="multipart/form-data">
                <div class="table-responsive">
            <table id="two-column" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">Employee Number</th>
                        <th class="text-center">Employee Name</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Reason</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = mysqli_query($db, "SELECT * FROM tbl_offboarding");
                    while ($row = mysqli_fetch_assoc($sql)) {
                    ?>
                    <tr>
                            <td class="text-center"><?= $row['emp_num'] ?></td>
                            <td class="text-center"><?= $row['emp_name'] ?></td>
                            <td class="text-center"><?= $row['date'] ?></td>
                            <td class="text-center"><?= $row['reason'] ?></td>
                           
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
            
          </div>
            </form>
        </div>
    </div>
</div>

<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>
<?php include 'inc/template_end.php'; ?>
<script src="js/pages/paginationTable.js"></script>
<script>
    function FetchState(id){

    $.ajax({
        type:'post',
        url: 'ajaxdata.php',
        data : {employee_id : id},
        success : function(data){
            $('#employee_name').html(data);
        }
    })


}

</script>
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