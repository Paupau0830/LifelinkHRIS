<?php include 'inc/config.php'; ?>
<?php include 'inc/template_start.php'; ?>
<?php include 'inc/page_head.php'; ?>
<?php
if (empty($_SESSION['hris_id'])) {
    header('Location: login');
}
?>


<!-- Page content -->
<div id="page-content">
    <!-- eCommerce Dashboard Header -->
    
    
    <div class="content-header">
        <div class="header-section">
          
            <h1>
                <i class="fa fa-building"></i><strong>Payroll Adjustment</strong>
            </h1><br>
            <?= $res ?><br>
        </div>
              <ul class="nav-horizontal text-center">
                  <li>
                  <a href="payroll-registry-view"><i class="fa fa-money" style="margin-right: 3px;"></i> Payslip</a></li>
                  </li>
                  <li class="active">
                      <a href="#"><i class="fa fa-cogs"></i> Adjustment</a>
                  </li>
                        
              </ul> 
    </div>

    <div class="block full">
        <!-- Working Tabs Title -->
        <div class="block-title">
            <ul class="nav nav-tabs push" data-toggle="tabs" id="payroll_cats">
                <li class="active"><a href="#tab_deduc"><i class="fa fa-minus" style="margin-right: 3px;"></i> Deduction</a></li>
                <li><a href="#tab_earn"><i class="fa fa-plus" style="margin-right: 3px;"></i> Earnings</a></li>
                <li><a href="#tab_history"><i class="fa fa-plus" style="margin-right: 3px;"></i> History</a></li>
            </ul>
        </div>
        <div class="tab-content">
          <div class="tab-pane active" id="tab_deduc" >
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
                  <input type="text" readonly class="form-control-plaintext" id="staticNetSalary" value="<?= number_format($_SESSION['selected_netsalary'], 2, ".", ",")  ?>">
                </div>
              </div>
              <div class="mb-3 row">
                <label for="staticNetSalary" class="col-sm-2 col-form-label">Net Salary before adjustments</label>
                <div class="col-sm-10">
                  <input type="text" readonly class="form-control-plaintext" id="staticNetSalary" value="<?= number_format($_SESSION['selected_netsalary_beforeadjustment'], 2, ".", ",")  ?>">
                </div>
              </div>
              <!-- <div class="mb-3 row">
              <label for="staticNetSalary" class="col-sm-2 col-form-label">Current Net Salary</label>
              <div class="col-sm-10">
                
                <input type="text" readonly class="form-control-plaintext" id="staticNetSalary" value="<?= $_SESSION['selected_current_netsalary'] ?>">
              </div>
            </div> -->
              
      
<br><hr>      
      <div class="block full">
        <div class="block-title">
            <h2><strong>Deduction</strong> List</h2>
        </div>
        <div class="table-responsive">
            <table id="three-column" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">Employee Number</th>
                        <th class="text-center">Employee Name</th>
                        <th class="text-center">Description</th>
                        <th class="text-center">Alt Desc</th>
                        <th class="text-center">Amount</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    $selected_emp_num = $_SESSION['selected_empnum'];
                    $sql = mysqli_query($db, "SELECT * FROM tbl_payroll_adjustments WHERE type_adjustment='Deduction' AND emp_num='$selected_emp_num'");
                    while ($row = mysqli_fetch_assoc($sql)) {
                    ?>
                        <tr>
                            <td class="text-center"><?= $row['emp_num'] ?></td>
                            <td class="text-center"><?= $row['emp_name'] ?></td>
                            <td class="text-center"><?= $row['a_description'] ?></td>
                            <td class="text-center"><?= $row['alt_description'] ?></td>
                            <td class="text-center"><?= number_format($row['amount'], 2, ".", ",")  ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

              <hr>
              <form method="post" enctype="multipart/form-data" class="form-horizontal form-bordered">
                <div class="content">
                  
                    <div class="tab-content">

                      <div class="container-fluid">
                          <div class="row">
                              <div class="col-md-6">
                                  <div class="form-group">
                                      <label>Deduction Description</label>
                                      <select name="deduc_description" required class="select-chosen">
                                          <option value="co. loan">Company Loan</option>
                                          <option value="sss loan">SSS Loan</option>
                                          <option value="hdmf loan">HDMF Loan</option>
                                          <option value="wis sss">WIS - SSS</option>
                                          <option value="mpf hdmf">MPF - HDMF</option>
                                          <option value="others">Others</option>
                                      </select>
                                  </div>
                              </div>
                              <div class="col-md-6">
                                  <div class="form-group">
                                      <label>Amount</label>
                                      <input type="number" name="deduc_amount" class="form-control" step="any">
                                  </div>
                              </div>
                              <div class="col-md-6">
                                  <div class="form-group">
                                      <label>Other description</label>
                                      <input type="text" name="alt_description_deduct" class="form-control" step="any">
                                  </div>
                              </div>
                          </div>
                          <div style="display:flex;">
                            <button name="minus_deduction" class="btn btn-primary">Update Deduction</button>
                            
                          </div>
                      </div>
                    </div> <!--end of tab content = deduc/earnings-->
                    
                </div> <!--end of content-div-->
                </form>
          </div> <!--end of tab-payslip-->

          <div class="tab-pane" id="tab_earn">
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
                <input type="text" readonly class="form-control-plaintext" id="staticNetSalary" value="<?= number_format($_SESSION['selected_netsalary'], 2, ".", ",")  ?>">
              </div>
            </div>
            <div class="mb-3 row">
                <label for="staticNetSalary" class="col-sm-2 col-form-label">Net Salary before adjustments</label>
                <div class="col-sm-10">
                  <input type="text" readonly class="form-control-plaintext" id="staticNetSalary" value="<?= number_format($_SESSION['selected_netsalary_beforeadjustment'], 2, ".", ",")  ?>">
                </div>
              </div>
            <!-- <div class="mb-3 row">
              <label for="staticNetSalary" class="col-sm-2 col-form-label">Current Net Salary</label>
              <div class="col-sm-10">
                
                <input type="text" readonly class="form-control-plaintext" id="staticNetSalary" value="<?= $_SESSION['selected_current_netsalary'] ?>">
              </div>
            </div> -->
          
                
          
            <br><hr>      
          <div class="block full">
              <div class="block-title">
                  <h2><strong>Earning</strong> List</h2>
              </div>
              <div class="table-responsive">
                  <table id="two-column" class="table table-vcenter table-condensed table-bordered">
                      <thead>
                          <tr>
                              <th class="text-center">Employee Number</th>
                              <th class="text-center">Employee Name</th>
                              <th class="text-center">Description</th>
                              <th class="text-center">Alt Desc</th>
                              <th class="text-center">Amount</th>

                          </tr>
                      </thead>
                      <tbody>
                          <?php
                          $selected_emp_num = $_SESSION['selected_empnum'];
                          $sql = mysqli_query($db, "SELECT * FROM tbl_payroll_adjustments WHERE type_adjustment='Earning' AND emp_num='$selected_emp_num'");
                          // $sql = mysqli_query($db, "SELECT emp_num, emp_name, a_description, amount  FROM sample_earn WHERE type_adjustment='Earning'");
                          while ($row = mysqli_fetch_assoc($sql)) {
                          ?>
                              <tr>
                                  <td class="text-center"><?= $row['emp_num']?></td>
                                  <td class="text-center"><?= $row['emp_name'] ?></td>
                                  <td class="text-center"><?= $row['a_description'] ?></td>
                                  <td class="text-center"><?= $row['alt_description'] ?></td>
                                  <td class="text-center"><?= number_format($row['amount'], 2, ".", ",")  ?></td>
                              </tr>
                          <?php
                          }
                          ?>
                      </tbody>
                  </table>
              </div>
            </div>

              <hr>
              <form method="post" enctype="multipart/form-data" class="form-horizontal form-bordered">
                <div class="content">
                  
                    <div class="tab-content">

                      <div class="container-fluid" id="earn_tab">
                          <div class="row">
                              <div class="col-md-6">
                                  <div class="form-group">
                                      <label>Earning Description</label>
                                      <select name="earn_description" required class="select-chosen">
                                          <option value="incentive">Incentive</option>
                                          <option value="paid leaves">Paid Leaves</option>
                                          <option value="13th month">13th Month</option>
                                          <option value="others">Others</option>
                                      </select>
                                  </div>
                              </div>
                              <div class="col-md-6">
                                  <div class="form-group">
                                      <label>Amount</label>
                                      <input type="number" name="earn_amount" class="form-control" step="any">
                                  </div>
                              </div>
                              <div class="col-md-6">
                                  <div class="form-group">
                                      <label>Other description</label>
                                      <input type="text" name="alt_description_earning" class="form-control" step="any">
                                  </div>
                              </div>
                          </div>
                          <div style="display:flex;">
                            <button name="add_earnings" class="btn btn-primary">Update Earnings
                              
                            </button>
                            
                          </div>
                      </div>
                    </div> <!--end of tab content = deduc/earnings-->
                    
                </div> <!--end of content-div-->
                </form>
          </div> <!--end of tab_earn-->

                          <!-- ----------------------------------------------------------------------------- -->

          <div class="tab-pane" id="tab_history">
          <br><hr>      
          <div class="block full">
              <div class="block-title">
                  <h2><strong>History</strong> of Adjustments</h2>
              </div>
              <div class="table-responsive">
                  <table id="one-column" class="table table-vcenter table-condensed table-bordered">
                      <thead>
                          <tr>
                          <th class="text-center">ID</th>
                              <th class="text-center">Before Adjustment</th>
                              <th class="text-center">Adjustment Type</th>
                              <th class="text-center">Adjustment Description</th>
                              <th class="text-center">Amount</th>
                              <th class="text-center">After Adjustment</th>

                          </tr>
                      </thead>
                      <tbody>
                          <?php
                          $selected_emp_num = $_SESSION['selected_empnum'];
                          $date_from = $_SESSION['selecteddate_from'];
                          $date_to = $_SESSION['selecteddate_to'];
                          $sql = mysqli_query($db, "SELECT * FROM tbl_adjustment_history WHERE (emp_num = '$selected_emp_num') AND (date_from = '$date_from' AND date_to = '$date_to')");
                          while ($row = mysqli_fetch_assoc($sql)) {
                          ?>
                              <tr>
                              <td class="text-center"><?= $row['id']?></td>
                                  <td class="text-center"><?= number_format($row['before_adjustment'], 2, ".", ",")  ?></td>
                                  <td class="text-center"><?= $row['adjustment_type'] ?></td>
                                  <td class="text-center"><?= $row['adjustment_description'] ?></td>
                                  <td class="text-center"><?= number_format($row['amount'], 2, ".", ",")  ?></td>
                                  <td class="text-center"><?= number_format($row['after_adjustment'], 2, ".", ",")  ?></td>
                              </tr>
                          <?php
                          }
                          ?>
                      </tbody>
                  </table>
              </div>
            </div>
          
        </div> <!--end of tab-content-->
    </div>
</div>
<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>
<?php include 'inc/template_end.php'; ?>
<script src="js/pages/pagination.js"></script>
<script>
    $(function() {
        TablesDatatables.init();
    });
</script>