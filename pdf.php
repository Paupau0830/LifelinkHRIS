<?php

function fetch_data()
{
  $conn_info = array("Database" => "db_hris");
  $db = sqlsrv_connect('LAPTOP-UIDM7J4N\MSSQLSERVER02', $conn_info);
  $benefits_id = $_POST['benefits_id'];
  $output = '';
  $sql1 = "SELECT * FROM dbo.tbl_benefits_reimbursement 
  WHERE ID LIKE '$benefits_id'";
  $stmt1 = sqlsrv_query($db, $sql1);
  $approved_by = "";

  while ($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
    $output .= '<div align="center"><img height="90px" width="200px" src="assets/img/logo.png"></div>';
    $output .= '
    <h1>Request Summary</h1>
<table border="1" style="padding:3px;">
  <tr>
    <th>Reference Number</th>
    <th>BR-' . $row['ID'] . '</th>
  </tr>
  <tr>
    <td>Payee</td>
    <td>' . $row['payee'] . '</td>
  </tr>
  <tr>
    <td>Payee Employee Number</td>
    <td>' . $row['payee_employee_number'] . '</td>
  </tr>
  <tr>
    <td>Requestor</td>
    <td>' . $row['requestor'] . '</td>
  </tr>
  <tr>
    <td>Total Amount in Figures</td>
    <td>' . $row['amount_in_figures'] . '.00</td>
  </tr>
  <tr>
    <td>Payment For</td>
    <td>' . $row['payment_for'] . '</td>
  </tr>
  <tr>
    <td>Special Instructions</td>
    <td>' . $row['special_instruction'] . '</td>
  </tr>
  <tr>
    <td>Categories Applied</td>
    <td>' . implode(',', array_unique(str_word_count(rtrim($row['categories_applied'], ", "), 1))) . '</td>
  </tr>
  <tr>
    <td>Date Created</td>
    <td>' . date_format($row['date_applied'], "m-d-Y H:i:s") . '</td>
  </tr>
  <tr>
    <td>Status</td>
    <td>' . $row['status'] . '</td>
  </tr>
</table>
  ';
  }
  $sql2 = "SELECT * FROM dbo.tbl_benefits_approver 
  WHERE benefits_id LIKE '$benefits_id'
  ORDER BY ID DESC";
  $stmt2 = sqlsrv_query($db, $sql2);
  $approved_by .= '<p>Approved by:</p>';
  $approved_by .= '<table border="1" style="padding:3px;">';
  $approved_by .= '
  <tr>
    <th><b>Position</b></th>
    <th><b>Approver Name</b></th>
    <th><b>Date Approved</b></th>
  </tr>
  ';
  while ($row = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
    // $approved_by .= '
    // <p>'.$row['position'].' - '.$row['approver'].' - '. date_format($row['date_approved'], "m-d-Y H:i:s") .'</p>
    $approved_by .= '
    <tr>
      <td>' . $row['position'] . '</td>
      <td>' . $row['approver'] . '</td>
      <td>' . date_format($row['date_approved'], "m-d-Y H:i:s") . '</td>
    </tr>
  ';
  }
  $approved_by .= '</table>';
  $output .= '
  <h1>Request Details</h1>';

  $sql = sqlsrv_query($db, "SELECT * FROM dbo.tbl_benefits_request_details 
  WHERE cat LIKE 'Parking' AND benefits_id LIKE '$benefits_id' ORDER BY ID DESC", array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
  if (sqlsrv_num_rows($sql) !== 0) {
    $output .= '
  <p><u>Parking</u></p>
  <table border="1" style="padding:3px;">
    <tr>
      <th><b>Requested Amount</b></th>
      <th><b>Attachment name</b></th>
      <th><b>Remarks</b></th>
    </tr>
  ';
    while ($r = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC)) {
      $output .= '
  <tr>
    <td>' . number_format($r['requested_amount'], 2) . '</td>
    <td>' . $r['attachment'] . '</td>
    <td>' . $r['remarks'] . '</td>
  </tr>
      ';
    }
    $output .= '</table>';
  }
  $sql = sqlsrv_query($db, "SELECT * FROM dbo.tbl_benefits_request_details 
  WHERE cat LIKE 'Gas' AND benefits_id LIKE '$benefits_id' ORDER BY ID DESC", array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
  if (sqlsrv_num_rows($sql) !== 0) {
    $output .= '
  <p><u>Gasoline</u></p>
  <table border="1" style="padding:3px;">
  <tr>
      <th><b>Requested Amount</b></th>
      <th><b>Attachment name</b></th>
      <th><b>Remarks</b></th>
    </tr>
  ';
    while ($r = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC)) {
      $output .= '
      <tr>
      <td>' . number_format($r['requested_amount'], 2) . '</td>
      <td>' . $r['attachment'] . '</td>
      <td>' . $r['remarks'] . '</td>
    </tr>
      ';
    }
    $output .= '</table>';
  }
  $sql = sqlsrv_query($db, "SELECT * FROM dbo.tbl_benefits_request_details 
  WHERE cat LIKE 'Car Maintenance' AND benefits_id LIKE '$benefits_id' ORDER BY ID DESC", array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
  if (sqlsrv_num_rows($sql) !== 0) {
    $output .= '
  <p><u>Car Maintenance</u></p>
  <table border="1" style="padding:3px;">
  <tr>
      <th><b>Requested Amount</b></th>
      <th><b>Attachment name</b></th>
      <th><b>Remarks</b></th>
    </tr>
  ';
    while ($r = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC)) {
      $output .= '
      <tr>
      <td>' . number_format($r['requested_amount'], 2) . '</td>
      <td>' . $r['attachment'] . '</td>
      <td>' . $r['remarks'] . '</td>
    </tr>
      ';
    }
    $output .= '</table>';
  }
  $sql = sqlsrv_query($db, "SELECT * FROM dbo.tbl_benefits_request_details 
  WHERE cat LIKE 'Medical' AND benefits_id LIKE '$benefits_id' ORDER BY ID DESC", array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
  if (sqlsrv_num_rows($sql) !== 0) {
    $output .= '
  <p><u>Medical</u></p>
  <table border="1" style="padding:3px;">
  <tr>
      <th><b>Requested Amount</b></th>
      <th><b>Attachment name</b></th>
      <th><b>Remarks</b></th>
    </tr>
  ';
    while ($r = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC)) {
      $output .= '
      <tr>
      <td>' . number_format($r['requested_amount'], 2) . '</td>
      <td>' . $r['attachment'] . '</td>
      <td>' . $r['remarks'] . '</td>
    </tr>
      ';
    }
    $output .= '</table>';
  }
  $sql = sqlsrv_query($db, "SELECT * FROM dbo.tbl_benefits_request_details 
  WHERE cat LIKE 'Gym' AND benefits_id LIKE '$benefits_id' ORDER BY ID DESC", array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
  if (sqlsrv_num_rows($sql) !== 0) {
    $output .= '
  <p><u>Gym</u></p>
  <table border="1" style="padding:3px;">
  <tr>
      <th><b>Requested Amount</b></th>
      <th><b>Attachment name</b></th>
      <th><b>Remarks</b></th>
    </tr>
  ';
    $gym_sum = 0;
    while ($r = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC)) {
      $gym_sum += (int) str_replace(',', '', number_format($r['requested_amount'], 2));
      $output .= '
      <tr>
      <td>' . number_format($r['requested_amount'], 2) . '</td>
      <td>' . $r['attachment'] . '</td>
      <td>' . $r['remarks'] . '</td>
    </tr>
      ';
    }
    $output .= '</table>';
  }
  $sql = sqlsrv_query($db, "SELECT * FROM dbo.tbl_benefits_request_details 
  WHERE cat LIKE 'Optical' AND benefits_id LIKE '$benefits_id' ORDER BY ID DESC", array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
  if (sqlsrv_num_rows($sql) !== 0) {
    $output .= '
  <p><u>Optical Allowance</u></p>
  <table border="1" style="padding:3px;">
  <tr>
      <th><b>Requested Amount</b></th>
      <th><b>Attachment name</b></th>
      <th><b>Remarks</b></th>
    </tr>
  ';
    while ($r = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC)) {
      $output .= '
      <tr>
      <td>' . number_format($r['requested_amount'], 2) . '</td>
      <td>' . $r['attachment'] . '</td>
      <td>' . $r['remarks'] . '</td>
    </tr>
      ';
    }
    $output .= '</table>';
  }
  $sql = sqlsrv_query($db, "SELECT * FROM dbo.tbl_benefits_request_details 
  WHERE cat LIKE 'CEP' AND benefits_id LIKE '$benefits_id' ORDER BY ID DESC", array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
  if (sqlsrv_num_rows($sql) !== 0) {
    $output .= '
  <p><u>CEP</u></p>
  <table border="1" style="padding:3px;">
  <tr>
      <th><b>Requested Amount</b></th>
      <th><b>Attachment name</b></th>
      <th><b>Remarks</b></th>
    </tr>
  ';
    while ($r = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC)) {
      $output .= '
      <tr>
      <td>' . number_format($r['requested_amount'], 2) . '</td>
      <td>' . $r['attachment'] . '</td>
      <td>' . $r['remarks'] . '</td>
    </tr>
      ';
    }
    $output .= '</table>';
  }
  $sql = sqlsrv_query($db, "SELECT * FROM dbo.tbl_benefits_request_details 
  WHERE cat LIKE 'Club' AND benefits_id LIKE '$benefits_id' ORDER BY ID DESC", array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
  if (sqlsrv_num_rows($sql) !== 0) {
    $output .= '
  <p><u>Club Membership</u></p>
  <table border="1" style="padding:3px;">
  <tr>
      <th><b>Requested Amount</b></th>
      <th><b>Attachment name</b></th>
      <th><b>Remarks</b></th>
    </tr>
  ';
    while ($r = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC)) {
      $output .= '
      <tr>
      <td>' . number_format($r['requested_amount'], 2) . '</td>
      <td>' . $r['attachment'] . '</td>
      <td>' . $r['remarks'] . '</td>
    </tr>
      ';
    }
    $output .= '</table>';
  }
  $sql = sqlsrv_query($db, "SELECT * FROM dbo.tbl_benefits_request_details 
  WHERE cat LIKE '%Maternity%' AND benefits_id LIKE '$benefits_id' ORDER BY ID DESC", array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
  if (sqlsrv_num_rows($sql) !== 0) {
    $output .= '
  <p><u>Maternity</u></p>
  <table border="1" style="padding:3px;">
  <tr>
      <th><b>Requested Amount</b></th>
      <th><b>Attachment name</b></th>
      <th><b>Remarks</b></th>
    </tr>
  ';
    while ($r = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC)) {
      $output .= '
      <tr>
      <td>' . number_format($r['requested_amount'], 2) . '</td>
      <td>' . $r['attachment'] . '</td>
      <td>' . $r['remarks'] . '</td>
    </tr>
      ';
    }
    $output .= '</table>';
  }
  $sql = sqlsrv_query($db, "SELECT * FROM dbo.tbl_benefits_request_details 
  WHERE cat LIKE 'Others' AND benefits_id LIKE '$benefits_id' ORDER BY ID DESC", array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
  if (sqlsrv_num_rows($sql) !== 0) {
    $output .= '
  <p><u>Others</u></p>
  <table border="1" style="padding:3px;">
  <tr>
      <th><b>Requested Amount</b></th>
      <th><b>Attachment name</b></th>
      <th><b>Remarks</b></th>
    </tr>
  ';
    while ($r = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC)) {
      $output .= '
      <tr>
      <td>' . number_format($r['requested_amount'], 2) . '</td>
      <td>' . $r['attachment'] . '</td>
      <td>' . $r['remarks'] . '</td>
    </tr>
      ';
    }
    $output .= '</table>';
  }

  $output .= $approved_by;
  return $output;
}
if (isset($_POST["pdf_benefits"])) {
  $benefits_id = $_POST['benefits_id'];
  require_once('tcpdf/tcpdf.php');
  $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $obj_pdf->SetCreator(PDF_CREATOR);
  $obj_pdf->SetTitle('BR-' . $benefits_id . ' - Report');
  $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
  $obj_pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
  $obj_pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
  $obj_pdf->SetDefaultMonospacedFont('helvetica');
  $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
  $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);
  $obj_pdf->setPrintHeader(false);
  $obj_pdf->setPrintFooter(false);
  $obj_pdf->SetAutoPageBreak(TRUE, 10);
  $obj_pdf->SetFont('helvetica', '', 8);
  $obj_pdf->AddPage();
  $content = '';
  $content .= fetch_data();
  // $content .= '</table>';
  $obj_pdf->writeHTML($content);
  ob_end_clean();
  $obj_pdf->Output('BR-' . $benefits_id . '-summary.pdf', 'I');
}
if (isset($_POST['btn_cat_report'])) {
  $cat = $_POST['cat'];
  require_once('tcpdf/tcpdf.php');
  $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $obj_pdf->SetCreator(PDF_CREATOR);
  $obj_pdf->SetTitle($cat . ' - Summary');
  $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
  $obj_pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
  $obj_pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
  $obj_pdf->SetDefaultMonospacedFont('helvetica');
  $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
  $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);
  $obj_pdf->setPrintHeader(false);
  $obj_pdf->setPrintFooter(false);
  $obj_pdf->SetAutoPageBreak(TRUE, 10);
  $obj_pdf->SetFont('helvetica', '', 8);
  $obj_pdf->AddPage();
  if($cat == "Benefits Reimbursement"){
    $content .= benefitsSummary();
  }else{
    $content .= "ok";
  }
  $obj_pdf->writeHTML($content);
  ob_end_clean();
  $obj_pdf->Output($cat . ' - summary.pdf', 'I');
}
function benefitsSummary()
{
  $conn_info = array("Database" => "db_hris");
  $db = sqlsrv_connect('LAPTOP-UIDM7J4N\MSSQLSERVER02', $conn_info);
  $cat = $_POST['cat'];
  $startDate = $_POST['startDate'];
  $endDate = $_POST['endDate'];
  $output = '';
  $sql = "";
  $sql = "SELECT * FROM dbo.tbl_benefits_reimbursement 
  WHERE date_applied BETWEEN '$startDate' AND '$endDate' 
  ORDER BY ID DESC";
  $stmt = sqlsrv_query($db, $sql);
  $output .= '<div align="center"><img height="90px" width="200px" src="assets/img/logo.png"></div>';
  $output .= '
  <h1 style="text-align:center">' . $cat . ' Summary</h1>
  <table border="1" style="padding:3px;">
  <tr>
    <th><b>Reference Number</b></th>
    <th><b>Payee Name</b></th>
    <th><b>Payee Employee Number</b></th>
    <th><b>Amount</b></th>
    <th><b>Payment for</b></th>
    <th><b>Status</b></th>
    <th><b>Date Applied</b></th>
  </tr>
  ';
  while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $output .= '
    <tr>
    <td>BR-' . $row['ID'] . '</td>
    <td>' . $row['payee'] . '</td>
    <td>' . $row['payee_employee_number'] . '</td>
    <td>' . $row['amount_in_figures'] . '.00</td>
    <td>' . implode(',', array_unique(str_word_count(rtrim($row['categories_applied'], ", "), 1))) . '</td>
    <td>' . $row['status'] . '</td>
    <td>' . date_format($row['date_applied'], "m-d-Y H:i:s") . '</td>
  </tr>
    ';
  }
  $output .= '</table>';
  return $output;
}
