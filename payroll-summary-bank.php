<?php include 'inc/config.php'; ?>
<?php include 'inc/template_start.php'; ?>
<?php include 'inc/page_head.php'; ?>
<?php
if (empty($_SESSION['hris_id'])) {
    header('Location: login');
}
?>
<?php
$selected_cname = $_SESSION['summary_company'];
$date_from = $_SESSION['summary_datefrom'];
$date_to= $_SESSION['summary_dateto'];
$total = 0;
?>


<!-- Page content -->
<div id="page-content">
    <!-- eCommerce Dashboard Header -->
    
    <?= $res ?>
    <div class="content-header">
        <div class="header-section">
            <h1>
                <i class="fa fa-building"></i><strong>Payroll Summary</strong>
            </h1>
            <ul class="nav-horizontal text-center">
                  <li>
                  <a href="payroll-summary-view"><i class="fa fa-book" style="margin-right: 3px;"></i> Summary</a></li>
                  </li>
                  <li class="active">
                      <a href="#summary_tab"><i class="fa fa-university"></i> Bank</a>
                  </li>
                        
              </ul> 
        </div>
          
    </div>
    <!-- Datatables Content -->
    <div class="block full" id="summary_tab">
        <div class="block-title">
            <h2>Summary for <strong>Bank</strong></h2>
        </div>
        <div class="table-responsive">
            <table id="three-column" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">Date</th>
                        <th class="text-center">Account Name</th>
                        <th class="text-center">Account Number</th>
                        <th class="text-center">Amount</th>
                        
                        

                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = mysqli_query($db, "SELECT * FROM tbl_employees_payslip WHERE (date_from = '$date_from' AND date_to = '$date_to') AND company_name = '$selected_cname'");
                    while ($row = mysqli_fetch_assoc($sql)) {
                        $total += $row['net_salary'];
                    ?>
                        <tr>
                            <td class="text-center"><?= $row['date_generated'] ?></td>
                            <td class="text-center"><?= $row['emp_name'] ?></td>
                            <td class="text-center"><?= $row['account_number'] ?></td>
                            <td class="text-center"><?= number_format($row['net_salary'], 2, ".", ",")  ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table><br>
            <div style="text-align: center">
                <div style="width: 100%; margin: 0 auto; text-align: left">
                    <h4><strong>Total: <?= number_format($total, 2, ".", ",")  ?></strong></h4>
                </div>
                <div style="width: 100%; margin: 0 auto; text-align: right">
                    <form method="POST">
                        <button class="btn btn-primary" name="print_bank_summary">Download Summary</button>
                    </form>
                </div>
            </div>
        </div>
        
    </div>
    <!-- END Datatables Content -->
</div>

<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>
<?php include 'inc/template_end.php'; ?>
<script src="js/pages/paginationTable.js"></script>
<script>
    $(function() {
        TablesDatatables.init();
    });
</script>
