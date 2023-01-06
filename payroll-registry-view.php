<?php include 'inc/config.php'; ?>
<?php include 'inc/template_start.php'; ?>
<?php include 'inc/page_head.php'; ?>
<?php include 'inc/adjustments_check.php'; ?>
<?php
if (empty($_SESSION['hris_id'])) {
    header('Location: login');
}
?>


<!-- Page content -->
<div id="page-content">
    <!-- eCommerce Dashboard Header -->
    
    <?= $res ?>
    <div class="content-header">
        <div class="header-section">
            <h1>
                <i class="fa fa-building"></i><strong>Payroll View<strong>
            </h1>
            <ul class="nav-horizontal text-center">
                  <li class="active">
                  <a href="#tab_payslip"><i class="fa fa-money" style="margin-right: 3px;"></i> Payslip</a></li>
                  </li>
                  <li>
                      <a href="payroll-registry-adjust" name="load_table_adjustment"><i class="fa fa-cogs"></i> Adjustment</a>
                  </li>
                        
              </ul> 
        </div>
          
    </div>
       
             
    <div class="block full" >
        <!-- Working Tabs Title -->
        <div class="block-title">
           
        </div>
        <div class="tab-content">
          <div class="tab-pane active" id="tab_payslip" style="display:flex;">
            
            <table class="table table-striped table-bordered" style="width:40%; margin-left:8%;">
            
                <tbody>
                  <tr>
                    <td>Employee ID</td>
                    <td><?= $_SESSION['selected_empnum'] ?></td>
                  </tr>
                  <tr>
                    <td>Employee Name</td>
                    <td><?= $_SESSION['selected_empname'] ?></td>
                  </tr>
                  <tr>
                    <td>Job Title</td>
                    <td><?= $_SESSION['selected_jobtitle'] ?></td>
                  </tr>
                  <tr>
                    <td>Cutoff From</td>
                    <td><?= $_SESSION['selecteddate_from'] ?></td>
                  </tr>
                  <tr>
                    <td>Cutoff To</td>
                    <td><?= $_SESSION['selecteddate_to'] ?></td>
                  </tr>
                  <tr>
                    <td>Company Name</td>
                    <td><?= $_SESSION['selected_comp'] ?></td>
                  </tr>
                  <tr>
                    <td>Gross Pay</td>
                    <td><?= number_format($_SESSION['selected_gross_pay'], 2, ".", ",") ?></td>
                  </tr>
                  <tr>
                    <td>Taxable Income</td>
                    <td><?= number_format($_SESSION['selected_taxable_income'], 2, ".", ",") ?></td>
                  </tr>
                  <tr>
                    <td>SSS</td>
                    <td><?= number_format($_SESSION['selected_sss'], 2, ".", ",") ?></td>
                  </tr>
                  <tr>
                    <td>PhilHealth</td>
                    <td><?= number_format($_SESSION['selected_philhealth'], 2, ".", ",")  ?></td>
                  </tr>
                  <tr>
                    <td>Pagibig</td>
                    <td><?= number_format($_SESSION['selected_pagibig'], 2, ".", ",")  ?></td>
                  </tr>
                  <tr>
                    <td>Net Salary</td>
                    <td><?= number_format($_SESSION['selected_netsalary'], 2, ".", ",") ?></td>
                  </tr>
                  <tr>
                    <td>13th Month</td>
                    <td><?= number_format($_SESSION['13th_month'], 2, ".", ",")  ?></td>
                  </tr>
                </tbody>
            </table>
            <!-- <br> -->
            <table class="table table-striped table-bordered" style="width:40%; margin-left:3%;">
                <tbody>
                  <tr>
                    <td>W/H Tax</td>
                    <td><?= number_format($_SESSION['selected_tax'], 2, ".", ",") ?></td>
                  </tr>
                  <tr>
                    <td>Tardiness</td>
                    <td><?= number_format($_SESSION['selected_tardiness'], 2, ".", ",")  ?></td>
                  </tr>
                  <tr>
                    <td>Absent</td>
                    <td><?= number_format($_SESSION['selected_absent'], 2, ".", ",")  ?></td>
                  </tr>
                  <tr>
                  <tr>
                    <td>Paid Leaves</td>
                    <td><?= number_format($_SESSION['paid_leaves'], 2, ".", ",")  ?></td>
                  </tr>
                  <tr>
                    <td>CO. Loan</td>
                    <td><?= number_format($_SESSION['company_loan'], 2, ".", ",")  ?></td>
                  </tr>
                  <tr>
                    <td>SSS Loan</td>
                    <td><?= number_format($_SESSION['sss_loan'], 2, ".", ",")  ?></td>
                  </tr>
                  <tr>
                    <td>HDMF Loan</td>
                    <td><?= number_format($_SESSION['hdmf_loan'], 2, ".", ",")  ?></td>
                  </tr>
                  <tr>
                    <td>WIS - SSS</td>
                    <td><?= number_format($_SESSION['wis_sss'], 2, ".", ",")  ?></td>
                  </tr>
                  <tr>
                    <td>MPF - HDMF</td>
                    <td><?= number_format($_SESSION['mpf_hdmf'], 2, ".", ",")  ?></td>
                  </tr>
                  <tr>
                    <td>Holiday Pay</td>
                    <td><?= number_format($_SESSION['holiday_pay'], 2, ".", ",")  ?></td>
                  </tr>
                  <tr>
                    <td>Overtime</td>
                    <td><?= number_format($_SESSION['overtime'], 2, ".", ",")  ?></td>
                  </tr>
                  <tr>
                    <td>Incentive</td>
                    <td><?= number_format($_SESSION['incentive'], 2, ".", ",")  ?></td>
                  </tr>
                  <tr>
                    <td>De Minimis</td>
                    <td><?= number_format($_SESSION['selected_deminimis'], 2, ".", ",")  ?></td>
                  </tr>
                </tbody>
            </table>
          </div> <!--end of tab-payslip-->

          <div class="tab-pane" id="tab_adjustment">
            <div class="mb-3 row">
              <label for="staticID" class="col-sm-2 col-form-label">Employee ID</label>
              <div class="col-sm-10">
                <input type="text" readonly class="form-control-plaintext" id="staticId" value="<?= $_SESSION['selected_empnum'] ?>">
                </div>
            </div>
            <div class="mb-3 row">
              <label for="staticName" class="col-sm-2 col-form-label">Employee Name</label>
              <div class="col-sm-10">
                <input type="text" readonly class="form-control-plaintext" id="staticName" value="<?= $_SESSION['selected_empname'] ?>">
                </div>
            </div>
            
            <div class="mb-3 row">
              <label for="staticNetSalary" class="col-sm-2 col-form-label">Net Salary</label>
              <div class="col-sm-10">
                <input type="text" readonly class="form-control-plaintext" id="staticNetSalary" value="<?= $_SESSION['selected_netsalary'] ?>">
              </div>
            </div>
          
                
          
          
              <form method="post" enctype="multipart/form-data" class="form-horizontal form-bordered">
                <div class="content">
                    <ul class="nav-horizontal text-center">
                        <li>
                            <a href="edit-company?<?= md5('id') . '=' . $fid ?>"><i class="fa fa-minus"></i> Deductions</a>
                        </li>
                        <li>
                            <a href="edit-company-department?<?= md5('id') . '=' . $fid ?>"><i class="fa fa-plus"></i> Earnings</a>
                        </li>
                        
                    </ul>
                    <div class="tab-content">

                      <div class="container-fluid active" id="deduc_tab">
                          <div class="row">
                              <div class="col-md-6">
                                  <div class="form-group">
                                      <label>Description</label>
                                      <input type="number" name="deduc_description" class="form-control">
                                  </div>
                              </div>
                              <div class="col-md-6">
                                  <div class="form-group">
                                      <label>Amount</label>
                                      <input type="number" name="deduc_amount" class="form-control">
                                  </div>
                              </div>
                          </div>
                          <button name="btn_maintenance_loan" class="btn btn-primary">Update</button>
                      </div>
                      <div class="container-fluid" id="earn_tab">
                          <div class="row">
                              <div class="col-md-6">
                                  <div class="form-group">
                                      <label>Description</label>
                                      <input type="number" name="deduc_description" class="form-control">
                                  </div>
                              </div>
                              <div class="col-md-6">
                                  <div class="form-group">
                                      <label>Amount</label>
                                      <input type="number" name="deduc_amount" class="form-control">
                                  </div>
                              </div>
                          </div>
                          <button name="btn_maintenance_loan" class="btn btn-primary">Update</button>
                      </div>
                    </div> <!--end of tab content = deduc/earnings-->
                    
                </div> <!--end of content-div-->
                </form>
          </div> <!--end of tab_adjustment-->
        </div> <!--end of tab-content-->
    </div>
</div>
<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>
<?php include 'inc/template_end.php'; ?>
<script src="js/pages/tablesDatatables.js"></script>
