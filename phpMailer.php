<?php

require_once("PHPMailer/src/Exception.php");
require_once('PHPMailer/src/PHPMailer.php');
require_once('PHPMailer/src/SMTP.php');

function otApplication($q_id, $req_email, $name, $approver, $month_of_ot, $total_hours, $employee_number)
{
  $ot_id = $q_id;
  $q_id = sprintf("%04d", $q_id);
  $db = mysqli_connect('localhost', 'root', '', 'hrisv2');
  $email = "jhcarlos@mpic.com.ph";
  $pass = "Mpic8888!!";
  $company_name = '';
  $company_logo = '';
  $sql = mysqli_query($db, "SELECT * FROM tbl_personal_information WHERE employee_number = '$approver'");
  $approver_name = mysqli_fetch_assoc($sql);
  $app = $approver_name['account_name'];
  $approver_email = $approver_name['company_email'];

  $get_maintenance = mysqli_query($db, "SELECT t.company, t1.*, t2.company_name FROM tbl_employment_information t
  INNER JOIN tbl_maintenance t1
  ON t.company = t1.company_id
  INNER JOIN tbl_companies t2
  ON t.company = t2.ID
  WHERE t.employee_number = '$employee_number'");
  if ($r = mysqli_fetch_assoc($get_maintenance)) {
    $company_name = $r['company_name'];
    $company_logo = $r['logo'];
  }

  $mail = new PHPMailer\PHPMailer\PHPMailer(true);
  $mail->IsSMTP();
  $mail->SMTPOptions = array(
    'ssl' => array(
      'verify_peer' => false,
      'verify_peer_name' => false,
      'allow_self_signed' => true
    )
  );
  $mail->SMTPDebug  = 0;
  $mail->SMTPAuth   = true;
  $mail->SMTPSecure = "tls";
  $mail->Host       = "smtp.office365.com";
  $mail->Port       = 587;
  $mail->Username   = $email;
  $mail->Password   = $pass;

  $mail->AddAddress($req_email);
  $mail->addCC($approver_email);
  $mail->AddEmbeddedImage('uploads/' . $company_logo, 'logo');
  $mail->SetFrom($email, $company_name);
  $mail->Subject = $company_name . ' - Overtime Application OT-' . $q_id;

  $body = '
      Hi ' . $name . ',<br><br>
      Your overtime application has been received from the system.<br><br>
      OT Number - OT-' . $q_id . '<br>
      Month: <b>' . $month_of_ot . '</b><br>
      Total hours: <b>' . $total_hours . '</b><br><br>
      Please wait for ' . $app . ' to approve your applied overtime.<br><br>
      To view your application, please click <a href="https://104.215.137.52/ot-details?' . md5('id') . '=' . md5($ot_id) . '">here</a><br><br>
      Thank you,<br>
      ' . $company_name . ' HR<br><br>
      <img src="cid:logo" height="50" width="200"><br>
      <p style="color:red">This is a system-generated message, please do not reply.</p>
      ';
  $mail->MsgHTML($body);
  $mail->Send();
}
function overtimeResponse($em, $name, $remarks, $id, $status, $employee_number)
{
  $ot_id = $id;
  $id = sprintf("%04d", $id);
  $db = mysqli_connect('localhost', 'root', '', 'hrisv2');
  $company_name = '';
  $company_logo = '';
  $get_maintenance = mysqli_query($db, "SELECT t.company, t1.*, t2.company_name FROM tbl_employment_information t
  INNER JOIN tbl_maintenance t1
  ON t.company = t1.company_id
  INNER JOIN tbl_companies t2
  ON t.company = t2.ID
  WHERE t.employee_number = '$employee_number'");
  if ($r = mysqli_fetch_assoc($get_maintenance)) {
    $company_name = $r['company_name'];
    $company_logo = $r['logo'];
  }

  $email = "jhcarlos@mpic.com.ph";
  $pass = "Mpic8888!!";

  $mail = new PHPMailer\PHPMailer\PHPMailer(true);
  $mail->IsSMTP();
  $mail->SMTPOptions = array(
    'ssl' => array(
      'verify_peer' => false,
      'verify_peer_name' => false,
      'allow_self_signed' => true
    )
  );
  $mail->SMTPDebug  = 0;
  $mail->SMTPAuth   = true;
  $mail->SMTPSecure = "tls";
  $mail->Host       = "smtp.office365.com";
  $mail->Port       = 587;
  $mail->Username   = $email;
  $mail->Password   = $pass;

  $mail->AddAddress($em);
  $mail->AddEmbeddedImage('uploads/' . $company_logo, 'logo');
  $mail->SetFrom($email, $company_name);
  $mail->Subject = $company_name . ' - Overtime Application Status OT-' . $id;

  $body = '
      Hi ' . $name . ',<br><br>
      Your overtime application number OT-' . $id . ' has been ' . $status . '<br>
      Approver remarks: <i>"' . $remarks . '"</i>.<br><br>
      To view your application, please click <a href="https://104.215.137.52/ot-details?' . md5('id') . '=' . md5($ot_id) . '">here</a><br><br>
      Thank you,<br>
      ' . $company_name . ' HR<br><br>
      <img src="cid:logo" height="50" width="200"><br>
      <p style="color:red">This is a system-generated message, please do not reply.</p>
      ';
  $mail->MsgHTML($body);
  $mail->Send();
}

function certificateRequest($cert_id, $req_email, $name, $cert_type, $employee_number)
{
  $db = mysqli_connect('localhost', 'root', '', 'hrisv2');
  $cid = sprintf("%04d", $cert_id);
  $company_name = '';
  $company_logo = '';
  $company_id = '';
  $get_maintenance = mysqli_query($db, "SELECT t.company, t1.*, t2.company_name FROM tbl_employment_information t
  INNER JOIN tbl_maintenance t1
  ON t.company = t1.company_id
  INNER JOIN tbl_companies t2
  ON t.company = t2.ID
  WHERE t.employee_number = '$employee_number'");
  if ($r = mysqli_fetch_assoc($get_maintenance)) {
    $company_name = $r['company_name'];
    $company_logo = $r['logo'];
    $company_id = $r['company'];
  }
  $email = "jhcarlos@mpic.com.ph";
  $pass = "Mpic8888!!";

  $approvers = get_certificate_request_approvers($company_id);

  $mail = new PHPMailer\PHPMailer\PHPMailer(true);
  $mail->IsSMTP();
  $mail->SMTPOptions = array(
    'ssl' => array(
      'verify_peer' => false,
      'verify_peer_name' => false,
      'allow_self_signed' => true
    )
  );
  $mail->SMTPDebug  = 0;
  $mail->SMTPAuth   = true;
  $mail->SMTPSecure = "tls";
  $mail->Host       = "smtp.office365.com";
  $mail->Port       = 587;
  $mail->Username   = $email;
  $mail->Password   = $pass;

  $mail->AddAddress($req_email);
  foreach ($approvers as $k => $v) {
    $app_info = get_user_details($v['user_id']);

    $mail->AddCC($app_info['email']);
  }
  $mail->AddEmbeddedImage('uploads/' . $company_logo, 'logo');
  $mail->SetFrom($email, $company_name);
  $mail->Subject = $company_name . ' - Certificate Request CR-' . $cid;

  $body = '
      Hi ' . $name . ',<br><br>
      Your certificate request, ' . $cert_type . ', has been received from the system. Please wait for HR to acknowledge your request.<br><br>
      Certificate Request number: <b>CR-' . $cid . '</b><br><br>
      To view your request, please click <a href="https://104.215.137.52/certificate-request-details?' . md5('id') . '=' . md5($cert_id) . '">here</a><br><br>
      Thank you,<br>
      ' . $company_name . ' HR<br><br>
      <img src="cid:logo" height="50" width="200"><br>
      <p style="color:red">This is a system-generated message, please do not reply.</p><br>
      ';
  $mail->MsgHTML($body);
  $mail->Send();
}
function initiateTraining($tid, $assigned_employee, $subject, $target_date, $description)
{
  $db = mysqli_connect('localhost', 'root', '', 'hrisv2');

  $company_name = '';
  $company_logo = '';
  $company_id = '';
  $get_maintenance = mysqli_query($db, "SELECT t.company, t1.*, t2.company_name FROM tbl_employment_information t
  INNER JOIN tbl_maintenance t1
  ON t.company = t1.company_id
  INNER JOIN tbl_companies t2
  ON t.company = t2.ID
  WHERE t.employee_number = '$assigned_employee'");
  if ($r = mysqli_fetch_assoc($get_maintenance)) {
    $company_name = $r['company_name'];
    $company_logo = $r['logo'];
    $company_id = $r['company'];
  }
  $email = "jhcarlos@mpic.com.ph";
  $pass = "Mpic8888!!";

  $approvers = get_training_approvers($company_id);

  $mail = new PHPMailer\PHPMailer\PHPMailer(true);
  $mail->IsSMTP();
  $mail->SMTPOptions = array(
    'ssl' => array(
      'verify_peer' => false,
      'verify_peer_name' => false,
      'allow_self_signed' => true
    )
  );
  $mail->SMTPDebug  = 0;
  $mail->SMTPAuth   = true;
  $mail->SMTPSecure = "tls";
  $mail->Host       = "smtp.office365.com";
  $mail->Port       = 587;
  $mail->Username   = $email;
  $mail->Password   = $pass;

  $mail->AddAddress(get_personal_information($assigned_employee)['company_email']);
  foreach ($approvers as $k => $v) {
    $app_user_id = $v['user_id'];
    $app_info = get_user_details($app_user_id);

    $mail->AddCC($app_info['email']);
  }
  $mail->AddEmbeddedImage('uploads/' . $company_logo, 'logo');
  $mail->SetFrom($email, $company_name);
  $mail->Subject = $company_name . ' - Training T-' . $tid;

  $body = '
      Hi ' . get_personal_information($assigned_employee)['account_name'] . ',<br><br>
      ' . $company_name . ' HR has assigned a training on your behalf, below are the details:<br><br>
      Training number: <b>T-' . $tid . '</b><br>
      Subject: <b>' . $subject . '</b><br>
      Target Date: <b>' . $target_date . '</b><br>
      Description: <b>' . $description . '</b>
      <br><br>
      To view your request, please click <a href="https://104.215.137.52/training">here</a><br><br>
      Thank you,<br>
      ' . $company_name . ' HR<br><br>
      <img src="cid:logo" height="50" width="200"><br>
      <p style="color:red">This is a system-generated message, please do not reply.</p><br>
      ';
  $mail->MsgHTML($body);
  $mail->Send();
}
function initiateTrainingComplete($tid, $assigned_employee, $subject, $target_date, $description)
{
  $db = mysqli_connect('localhost', 'root', '', 'hrisv2');

  $company_name = '';
  $company_logo = '';
  $company_id = '';
  $get_maintenance = mysqli_query($db, "SELECT t.company, t1.*, t2.company_name FROM tbl_employment_information t
  INNER JOIN tbl_maintenance t1
  ON t.company = t1.company_id
  INNER JOIN tbl_companies t2
  ON t.company = t2.ID
  WHERE t.employee_number = '$assigned_employee'");
  if ($r = mysqli_fetch_assoc($get_maintenance)) {
    $company_name = $r['company_name'];
    $company_logo = $r['logo'];
    $company_id = $r['company'];
  }
  $email = "jhcarlos@mpic.com.ph";
  $pass = "Mpic8888!!";

  $approvers = get_training_approvers($company_id);

  $mail = new PHPMailer\PHPMailer\PHPMailer(true);
  $mail->IsSMTP();
  $mail->SMTPOptions = array(
    'ssl' => array(
      'verify_peer' => false,
      'verify_peer_name' => false,
      'allow_self_signed' => true
    )
  );
  $mail->SMTPDebug  = 0;
  $mail->SMTPAuth   = true;
  $mail->SMTPSecure = "tls";
  $mail->Host       = "smtp.office365.com";
  $mail->Port       = 587;
  $mail->Username   = $email;
  $mail->Password   = $pass;

  $mail->AddAddress(get_personal_information($assigned_employee)['company_email']);
  foreach ($approvers as $k => $v) {
    $app_user_id = $v['user_id'];
    $app_info = get_user_details($app_user_id);

    $mail->AddCC($app_info['email']);
  }
  $mail->AddEmbeddedImage('uploads/' . $company_logo, 'logo');
  $mail->SetFrom($email, $company_name);
  $mail->Subject = $company_name . ' - Training T-' . $tid;

  $body = '
      Hi ' . get_personal_information($assigned_employee)['account_name'] . ',<br><br>
      You successfully submitted a Certificate of Completion. Please wait for HR to review your form.<br><br>
      Training number: <b>T-' . $tid . '</b><br>
      Subject: <b>' . $subject . '</b><br>
      Target Date: <b>' . $target_date . '</b><br>
      Description: <b>' . $description . '</b>
      <br><br>
      To view your request, please click <a href="https://104.215.137.52/training">here</a><br><br>
      Thank you,<br>
      ' . $company_name . ' HR<br><br>
      <img src="cid:logo" height="50" width="200"><br>
      <p style="color:red">This is a system-generated message, please do not reply.</p><br>
      ';
  $mail->MsgHTML($body);
  $mail->Send();
}
function initiateTrainingApproved($tid, $assigned_employee, $subject, $target_date, $description)
{
  $db = mysqli_connect('localhost', 'root', '', 'hrisv2');

  $company_name = '';
  $company_logo = '';
  $company_id = '';
  $get_maintenance = mysqli_query($db, "SELECT t.company, t1.*, t2.company_name FROM tbl_employment_information t
  INNER JOIN tbl_maintenance t1
  ON t.company = t1.company_id
  INNER JOIN tbl_companies t2
  ON t.company = t2.ID
  WHERE t.employee_number = '$assigned_employee'");
  if ($r = mysqli_fetch_assoc($get_maintenance)) {
    $company_name = $r['company_name'];
    $company_logo = $r['logo'];
    $company_id = $r['company'];
  }
  $email = "jhcarlos@mpic.com.ph";
  $pass = "Mpic8888!!";

  $approvers = get_training_approvers($company_id);

  $mail = new PHPMailer\PHPMailer\PHPMailer(true);
  $mail->IsSMTP();
  $mail->SMTPOptions = array(
    'ssl' => array(
      'verify_peer' => false,
      'verify_peer_name' => false,
      'allow_self_signed' => true
    )
  );
  $mail->SMTPDebug  = 0;
  $mail->SMTPAuth   = true;
  $mail->SMTPSecure = "tls";
  $mail->Host       = "smtp.office365.com";
  $mail->Port       = 587;
  $mail->Username   = $email;
  $mail->Password   = $pass;

  $mail->AddAddress(get_personal_information($assigned_employee)['company_email']);
  foreach ($approvers as $k => $v) {
    $app_user_id = $v['user_id'];
    $app_info = get_user_details($app_user_id);

    $mail->AddCC($app_info['email']);
  }
  $mail->AddEmbeddedImage('uploads/' . $company_logo, 'logo');
  $mail->SetFrom($email, $company_name);
  $mail->Subject = $company_name . ' - Training T-' . $tid;

  $body = '
      Hi ' . get_personal_information($assigned_employee)['account_name'] . ',<br><br>
      Your Training has been completed.<br><br>
      Training number: <b>T-' . $tid . '</b><br>
      Subject: <b>' . $subject . '</b><br>
      Target Date: <b>' . $target_date . '</b><br>
      Description: <b>' . $description . '</b>
      <br><br>
      To view your request, please click <a href="https://104.215.137.52/training">here</a><br><br>
      Thank you,<br>
      ' . $company_name . ' HR<br><br>
      <img src="cid:logo" height="50" width="200"><br>
      <p style="color:red">This is a system-generated message, please do not reply.</p><br>
      ';
  $mail->MsgHTML($body);
  $mail->Send();
}
function initiateTrainingDecline($tid, $assigned_employee, $subject, $target_date, $description)
{
  $db = mysqli_connect('localhost', 'root', '', 'hrisv2');

  $company_name = '';
  $company_logo = '';
  $company_id = '';
  $get_maintenance = mysqli_query($db, "SELECT t.company, t1.*, t2.company_name FROM tbl_employment_information t
  INNER JOIN tbl_maintenance t1
  ON t.company = t1.company_id
  INNER JOIN tbl_companies t2
  ON t.company = t2.ID
  WHERE t.employee_number = '$assigned_employee'");
  if ($r = mysqli_fetch_assoc($get_maintenance)) {
    $company_name = $r['company_name'];
    $company_logo = $r['logo'];
    $company_id = $r['company'];
  }
  $email = "jhcarlos@mpic.com.ph";
  $pass = "Mpic8888!!";

  $approvers = get_training_approvers($company_id);

  $mail = new PHPMailer\PHPMailer\PHPMailer(true);
  $mail->IsSMTP();
  $mail->SMTPOptions = array(
    'ssl' => array(
      'verify_peer' => false,
      'verify_peer_name' => false,
      'allow_self_signed' => true
    )
  );
  $mail->SMTPDebug  = 0;
  $mail->SMTPAuth   = true;
  $mail->SMTPSecure = "tls";
  $mail->Host       = "smtp.office365.com";
  $mail->Port       = 587;
  $mail->Username   = $email;
  $mail->Password   = $pass;

  $mail->AddAddress(get_personal_information($assigned_employee)['company_email']);
  foreach ($approvers as $k => $v) {
    $app_user_id = $v['user_id'];
    $app_info = get_user_details($app_user_id);

    $mail->AddCC($app_info['email']);
  }
  $mail->AddEmbeddedImage('uploads/' . $company_logo, 'logo');
  $mail->SetFrom($email, $company_name);
  $mail->Subject = $company_name . ' - Training T-' . $tid;

  $body = '
      Hi ' . get_personal_information($assigned_employee)['account_name'] . ',<br><br>
      Your Training has been declined. Please contact HR if you have any question.<br><br>
      Training number: <b>T-' . $tid . '</b><br>
      Subject: <b>' . $subject . '</b><br>
      Target Date: <b>' . $target_date . '</b><br>
      Description: <b>' . $description . '</b>
      <br><br>
      To view your request, please click <a href="https://104.215.137.52/training">here</a><br><br>
      Thank you,<br>
      ' . $company_name . ' HR<br><br>
      <img src="cid:logo" height="50" width="200"><br>
      <p style="color:red">This is a system-generated message, please do not reply.</p><br>
      ';
  $mail->MsgHTML($body);
  $mail->Send();
}
function initiateTrainingUpdate($tid, $assigned_employee, $subject, $target_date, $description)
{
  $db = mysqli_connect('localhost', 'root', '', 'hrisv2');

  $company_name = '';
  $company_logo = '';
  $company_id = '';
  $get_maintenance = mysqli_query($db, "SELECT t.company, t1.*, t2.company_name FROM tbl_employment_information t
  INNER JOIN tbl_maintenance t1
  ON t.company = t1.company_id
  INNER JOIN tbl_companies t2
  ON t.company = t2.ID
  WHERE t.employee_number = '$assigned_employee'");
  if ($r = mysqli_fetch_assoc($get_maintenance)) {
    $company_name = $r['company_name'];
    $company_logo = $r['logo'];
    $company_id = $r['company'];
  }
  $email = "jhcarlos@mpic.com.ph";
  $pass = "Mpic8888!!";

  $approvers = get_training_approvers($company_id);

  $mail = new PHPMailer\PHPMailer\PHPMailer(true);
  $mail->IsSMTP();
  $mail->SMTPOptions = array(
    'ssl' => array(
      'verify_peer' => false,
      'verify_peer_name' => false,
      'allow_self_signed' => true
    )
  );
  $mail->SMTPDebug  = 0;
  $mail->SMTPAuth   = true;
  $mail->SMTPSecure = "tls";
  $mail->Host       = "smtp.office365.com";
  $mail->Port       = 587;
  $mail->Username   = $email;
  $mail->Password   = $pass;

  $mail->AddAddress(get_personal_information($assigned_employee)['company_email']);
  foreach ($approvers as $k => $v) {
    $app_user_id = $v['user_id'];
    $app_info = get_user_details($app_user_id);

    $mail->AddCC($app_info['email']);
  }
  $mail->AddEmbeddedImage('uploads/' . $company_logo, 'logo');
  $mail->SetFrom($email, $company_name);
  $mail->Subject = $company_name . ' - Training T-' . $tid;

  $body = '
      Hi ' . get_personal_information($assigned_employee)['account_name'] . ',<br><br>
      ' . $company_name . ' HR has requested an update to your training. Please make sure that your Certificate of Completion is valid.<br><br>
      Training number: <b>T-' . $tid . '</b><br>
      Subject: <b>' . $subject . '</b><br>
      Target Date: <b>' . $target_date . '</b><br>
      Description: <b>' . $description . '</b>
      <br><br>
      To view your request, please click <a href="https://104.215.137.52/training">here</a><br><br>
      Thank you,<br>
      ' . $company_name . ' HR<br><br>
      <img src="cid:logo" height="50" width="200"><br>
      <p style="color:red">This is a system-generated message, please do not reply.</p><br>
      ';
  $mail->MsgHTML($body);
  $mail->Send();
}
function certificateAcknowledge($id, $em, $name, $cert_type, $remarks, $employee_number)
{
  $cert_id = $id;
  $id = sprintf("%04d", $id);
  $db = mysqli_connect('localhost', 'root', '', 'hrisv2');
  $company_name = '';
  $company_logo = '';
  $get_maintenance = mysqli_query($db, "SELECT t.company, t1.*, t2.company_name FROM tbl_employment_information t
  INNER JOIN tbl_maintenance t1
  ON t.company = t1.company_id
  INNER JOIN tbl_companies t2
  ON t.company = t2.ID
  WHERE t.employee_number = '$employee_number'");
  if ($r = mysqli_fetch_assoc($get_maintenance)) {
    $company_name = $r['company_name'];
    $company_logo = $r['logo'];
  }
  $email = "jhcarlos@mpic.com.ph";
  $pass = "Mpic8888!!";

  $mail = new PHPMailer\PHPMailer\PHPMailer(true);
  $mail->IsSMTP();
  $mail->SMTPOptions = array(
    'ssl' => array(
      'verify_peer' => false,
      'verify_peer_name' => false,
      'allow_self_signed' => true
    )
  );
  $mail->SMTPDebug  = 0;
  $mail->SMTPAuth   = true;
  $mail->SMTPSecure = "tls";
  $mail->Host       = "smtp.office365.com";
  $mail->Port       = 587;
  $mail->Username   = $email;
  $mail->Password   = $pass;

  $mail->AddAddress($em);
  $mail->AddEmbeddedImage('uploads/' . $company_logo, 'logo');
  $mail->SetFrom($email, $company_name);
  $mail->Subject = $company_name . ' - Certificate Request CR-' . $id;

  $body = '
      Hi ' . $name . ',<br><br>
      Your certificate request CR-' . $id . ' has been acknowledged.<br><br>
      An assigned Personnel will send you an email along with your requested certificate.<br>
      Personnel remarks: <i>"' . $remarks . '"</i><br><br>
      To view your request, please click <a href="https://104.215.137.52/certificate-request-details?' . md5('id') . '=' . md5($cert_id) . '">here</a><br><br>
      Thank you,<br>
      ' . $company_name . ' HR<br><br>
      <img src="cid:logo" height="50" width="200"><br>
      <p style="color:red">This is a system-generated message, please do not reply.</p>
      ';
  $mail->MsgHTML($body);
  $mail->Send();
}
function leaveApplication($leave_id)
{
  $db = mysqli_connect('localhost', 'root', '', 'hrisv2');
  $lid = sprintf("%04d", $leave_id);
  $leave_info = get_leave_details($leave_id);
  $employee_number = $leave_info['delegated_emp_number'];
  $personal_info = get_personal_information($employee_number);
  $employment_info = get_employment_information($employee_number);

  $approver_info = get_personal_information($employment_info['approver']);

  $company_name = '';
  $company_logo = '';
  $get_maintenance = mysqli_query($db, "SELECT t.company, t1.*, t2.company_name FROM tbl_employment_information t
  INNER JOIN tbl_maintenance t1
  ON t.company = t1.company_id
  INNER JOIN tbl_companies t2
  ON t.company = t2.ID
  WHERE t.employee_number = '$employee_number'");
  if ($r = mysqli_fetch_assoc($get_maintenance)) {
    $company_name = $r['company_name'];
    $company_logo = $r['logo'];
  }
  $email = "jhcarlos@mpic.com.ph";
  $pass = "Mpic8888!!";

  $mail = new PHPMailer\PHPMailer\PHPMailer(true);
  $mail->IsSMTP();
  $mail->SMTPOptions = array(
    'ssl' => array(
      'verify_peer' => false,
      'verify_peer_name' => false,
      'allow_self_signed' => true
    )
  );
  $mail->SMTPDebug  = 0;
  $mail->SMTPAuth   = true;
  $mail->SMTPSecure = "tls";
  $mail->Host       = "smtp.office365.com";
  $mail->Port       = 587;
  $mail->Username   = $email;
  $mail->Password   = $pass;

  $mail->AddCC($approver_info['company_email']);
  $mail->AddAddress($personal_info['company_email']);
  $mail->AddEmbeddedImage('uploads/' . $company_logo, 'logo');
  $mail->SetFrom($email, $company_name);
  $mail->Subject = $company_name . ' - Leave Application LA-' . $lid;
  $body = '
      Hi ' . $personal_info['account_name'] . ',<br><br>
      Your leave application has been received from our system.<br><br>
      Leave Number: <b>LA-' . $lid . '</b></br>
      Leave Type: <b>' . strtoupper($leave_info['leave_type']) . '</b><br>
      From: <b>' . $leave_info['startDate'] . '</b><br>
      To: <b>' . $leave_info['endDate'] . '</b> <br>
      Duration: <b>' . $leave_info['duration'] . '</b> <br>
      Total days: <b>' . $leave_info['total_day'] . '</b><br><br>
      Please wait for <b>' . $approver_info['account_name'] . '</b> to approve/decline your application.</b><br><br>
      To view your application, please click <a href="https://104.215.137.52/leave-details?' . md5('id') . '=' . md5($leave_id) . '">here</a><br><br>
      Thank you,<br>
      ' . $company_name . ' HR<br><br>
      <img src="cid:logo" height="50" width="200"><br>
      <p style="color:red">This is a system-generated message, please do not reply.</p>
      ';
  $mail->MsgHTML($body);
  $mail->Send();
}
function leaveApplicationStatus($leave_id, $name, $em, $leave_type, $startDate, $endDate, $total_days, $status, $employee_number)
{
  $db = mysqli_connect('localhost', 'root', '', 'hrisv2');
  $company_name = '';
  $company_logo = '';
  $leave_id = sprintf("%04d", $leave_id);

  $get_maintenance = mysqli_query($db, "SELECT t.company, t1.*, t2.company_name FROM tbl_employment_information t
  INNER JOIN tbl_maintenance t1
  ON t.company = t1.company_id
  INNER JOIN tbl_companies t2
  ON t.company = t2.ID
  WHERE t.employee_number = '$employee_number'");
  if ($r = mysqli_fetch_assoc($get_maintenance)) {
    $company_name = $r['company_name'];
    $company_logo = $r['logo'];
  }
  $email = "jhcarlos@mpic.com.ph";
  $pass = "Mpic8888!!";

  $mail = new PHPMailer\PHPMailer\PHPMailer(true);
  $mail->IsSMTP();
  $mail->SMTPOptions = array(
    'ssl' => array(
      'verify_peer' => false,
      'verify_peer_name' => false,
      'allow_self_signed' => true
    )
  );
  $mail->SMTPDebug  = 0;
  $mail->SMTPAuth   = true;
  $mail->SMTPSecure = "tls";
  $mail->Host       = "smtp.office365.com";
  $mail->Port       = 587;
  $mail->Username   = $email;
  $mail->Password   = $pass;

  $mail->AddAddress($em);
  $mail->AddEmbeddedImage('uploads/' . $company_logo, 'logo');
  $mail->SetFrom($email, $company_name);
  $mail->Subject = $company_name . ' - Leave Application Status - LA-' . $leave_id;

  $body = '
      Hi ' . $name . ',<br><br>
      Your Leave Application LA-' . $leave_id . ' has been ' . $status . '<br><br>
      Leave Number: <b>LA-' . $leave_id . '</b><br>
      Leave Type: <b>' . strtoupper($leave_type) . '</b><br>
      From: <b>' . $startDate . '</b> <br>
      To: <b>' . $endDate . '</b><br>
      Total days: <b>' . $total_days . '</b><br><br>
      To view your application, please click <a href="https://104.215.137.52/leave-list">here</a><br><br>
      Thank you,<br>
      ' . $company_name . ' HR<br><br>
      <img src="cid:logo" height="50" width="200"><br>
      <p style="color:red">This is a system-generated message, please do not reply.</p>
      ';
  $mail->MsgHTML($body);
  $mail->Send();
}
function leaveApplicationCancelApproved($leave_id, $em, $name, $status, $leave_type, $startDate, $endDate, $total_days, $employee_number, $approver)
{
  $db = mysqli_connect('localhost', 'root', '', 'hrisv2');
  $company_name = '';
  $company_logo = '';
  $leave_id = sprintf("%04d", $leave_id);

  $get_maintenance = mysqli_query($db, "SELECT t.company, t1.*, t2.company_name FROM tbl_employment_information t
  INNER JOIN tbl_maintenance t1
  ON t.company = t1.company_id
  INNER JOIN tbl_companies t2
  ON t.company = t2.ID
  WHERE t.employee_number = '$employee_number'");
  if ($r = mysqli_fetch_assoc($get_maintenance)) {
    $company_name = $r['company_name'];
    $company_logo = $r['logo'];
  }
  $get_approver_info = mysqli_query($db, "SELECT * FROM tbl_personal_information WHERE employee_number = '$approver'");
  if ($r = mysqli_fetch_assoc($get_approver_info)) {
    $approver_name = $r['account_name'];
    $approver_em = $r['company_email'];
  }

  $email = "jhcarlos@mpic.com.ph";
  $pass = "Mpic8888!!";

  $mail = new PHPMailer\PHPMailer\PHPMailer(true);
  $mail->IsSMTP();
  $mail->SMTPOptions = array(
    'ssl' => array(
      'verify_peer' => false,
      'verify_peer_name' => false,
      'allow_self_signed' => true
    )
  );
  $mail->SMTPDebug  = 0;
  $mail->SMTPAuth   = true;
  $mail->SMTPSecure = "tls";
  $mail->Host       = "smtp.office365.com";
  $mail->Port       = 587;
  $mail->Username   = $email;
  $mail->Password   = $pass;

  $mail->AddAddress($em);
  $mail->AddCC($approver_em);
  $mail->AddEmbeddedImage('uploads/' . $company_logo, 'logo');
  $mail->SetFrom($email, $company_name);
  $mail->Subject = $company_name . ' - Leave Application Status - LA-' . $leave_id;

  $body = '
      Hi ' . $name . ',<br><br>
      Your Leave Application LA-' . $leave_id . ' has been requested for cancellation<br><br>
      Leave Number: <b>LA-' . $leave_id . '</b><br>
      Leave Type: <b>' . strtoupper($leave_type) . '</b><br>
      From: <b>' . $startDate . '</b> <br>
      To: <b>' . $endDate . '</b><br>
      Total days: <b>' . $total_days . ' day(s)</b><br><br>
      To view your application, please click <a href="https://104.215.137.52/leave-list">here</a><br><br>
      Thank you,<br>
      ' . $company_name . '<br><br>
      <img src="cid:logo" height="50" width="200"><br>
      <p style="color:red">This is a system-generated message, please do not reply.</p>
      ';
  $mail->MsgHTML($body);
  $mail->Send();
}
// Pending Leaves
function leaveApplicationCancelPending($leave_id, $em, $name, $status, $leave_type, $startDate, $endDate, $total_days, $approver, $employee_number)
{
  $db = mysqli_connect('localhost', 'root', '', 'hrisv2');
  $approver_name = "";
  $approver_em = "";

  $company_name = '';
  $company_logo = '';

  $get_maintenance = mysqli_query($db, "SELECT t.company, t1.*, t2.company_name FROM tbl_employment_information t
  INNER JOIN tbl_maintenance t1
  ON t.company = t1.company_id
  INNER JOIN tbl_companies t2
  ON t.company = t2.ID
  WHERE t.employee_number = '$employee_number'");
  if ($r = mysqli_fetch_assoc($get_maintenance)) {
    $company_name = $r['company_name'];
    $company_logo = $r['logo'];
  }

  $get_approver_info = mysqli_query($db, "SELECT * FROM tbl_personal_information WHERE employee_number = '$approver'");
  if ($r = mysqli_fetch_assoc($get_approver_info)) {
    $approver_name = $r['account_name'];
    $approver_em = $r['company_email'];
  }
  $email = "jhcarlos@mpic.com.ph";
  $pass = "Mpic8888!!";

  $mail = new PHPMailer\PHPMailer\PHPMailer(true);
  $mail->IsSMTP();
  $mail->SMTPOptions = array(
    'ssl' => array(
      'verify_peer' => false,
      'verify_peer_name' => false,
      'allow_self_signed' => true
    )
  );
  $mail->SMTPDebug  = 0;
  $mail->SMTPAuth   = true;
  $mail->SMTPSecure = "tls";
  $mail->Host       = "smtp.office365.com";
  $mail->Port       = 587;
  $mail->Username   = $email;
  $mail->Password   = $pass;

  $mail->AddAddress($em);
  $mail->AddCC($approver_em);
  $mail->AddEmbeddedImage('uploads/' . $company_logo, 'logo');
  $mail->SetFrom($email, $company_name);
  $mail->Subject = $company_name . ' - Leave Application Status - LA-' . sprintf("%04d", $leave_id);

  $body = '
      Hi ' . $name . ',<br><br>
      Your Leave Application LA-' . sprintf("%04d", $leave_id) . ' has been cancelled<br><br>
      Leave Number: <b>LA-' . sprintf("%04d", $leave_id) . '</b><br>
      Leave Type: <b>' . strtoupper($leave_type) . '</b><br>
      From: <b>' . $startDate . '</b> <br>
      To: <b>' . $endDate . '</b><br>
      Total days: <b>' . $total_days . ' day(s)</b><br><br>
      To view your application, please click <a href="https://104.215.137.52/leave-list">here</a><br><br>
      Thank you,<br>
      ' . $company_name . ' HR<br><br>
      <img src="cid:logo" height="50" width="200"><br>
      <p style="color:red">This is a system-generated message, please do not reply.</p>
      ';
  $mail->MsgHTML($body);
  $mail->Send();
}
function overtimeResponseCancel($em, $account_name, $ot_id, $employee_number)
{
  $db = mysqli_connect('localhost', 'root', '', 'hrisv2');
  $email = "jhcarlos@mpic.com.ph";
  $pass = "Mpic8888!!";
  $ot_id = sprintf("%04d", $ot_id);

  $company_name = '';
  $company_logo = '';

  $get_maintenance = mysqli_query($db, "SELECT t.company, t1.*, t2.company_name FROM tbl_employment_information t
  INNER JOIN tbl_maintenance t1
  ON t.company = t1.company_id
  INNER JOIN tbl_companies t2
  ON t.company = t2.ID
  WHERE t.employee_number = '$employee_number'");
  if ($r = mysqli_fetch_assoc($get_maintenance)) {
    $company_name = $r['company_name'];
    $company_logo = $r['logo'];
  }
  $mail = new PHPMailer\PHPMailer\PHPMailer(true);
  $mail->IsSMTP();
  $mail->SMTPOptions = array(
    'ssl' => array(
      'verify_peer' => false,
      'verify_peer_name' => false,
      'allow_self_signed' => true
    )
  );
  $mail->SMTPDebug  = 0;
  $mail->SMTPAuth   = true;
  $mail->SMTPSecure = "tls";
  $mail->Host       = "smtp.office365.com";
  $mail->Port       = 587;
  $mail->Username   = $email;
  $mail->Password   = $pass;

  $mail->AddAddress($em);
  $mail->AddEmbeddedImage('uploads/' . $company_logo, 'logo');
  $mail->SetFrom($email, 'Metro Pacific Water');
  $mail->Subject = 'MPW - Overtime Application Status OT-' . $ot_id;

  $body = '
      Hi ' . $account_name . ',<br><br>
      Your OT Application OT-' . $ot_id . ' has been Cancelled<br><br>
      Thank you,<br>
      To view your application, please click <a href="https://104.215.137.52/ot-list">here</a><br><br>
      ' . $company_name . ' HR<br><br>
      <img src="cid:logo" height="50" width="200"><br>
      <p style="color:red">This is a system-generated message, please do not reply.</p>
      ';
  $mail->MsgHTML($body);
  $mail->Send();
}
function loanApplication($loan_id)
{

  $db = mysqli_connect('localhost', 'root', '', 'hrisv2');
  $lid = $loan_id;
  $loan_id = format_transaction_id($loan_id);
  $loan_info = get_salary_loan_info($loan_id);
  $employee_number = $loan_info['employee_number'];
  $personal_info = get_personal_information($employee_number);
  $em = $personal_info['company_email'];

  $name = get_personal_information($employee_number);
  $name = $name['account_name'];

  $company_id = $loan_info['company_id'];
  $company_name = '';
  $company_logo = '';

  $get_maintenance = mysqli_query($db, "SELECT t.company, t1.*, t2.company_name FROM tbl_employment_information t
  INNER JOIN tbl_maintenance t1
  ON t.company = t1.company_id
  INNER JOIN tbl_companies t2
  ON t.company = t2.ID
  WHERE t.employee_number = '$employee_number'");
  if ($r = mysqli_fetch_assoc($get_maintenance)) {
    $company_name = $r['company_name'];
    $company_logo = $r['logo'];
  }
  $approver_role_info = get_approvers_from_role_loan(1, $company_id);
  $approvers = get_salary_loan_approvers($company_id, $approver_role_info['ID']);
  $approve_role_name = $approver_role_info['role'];
  if ($approver_role_info['cc'] != '') {
    $recipients_cc = $approver_role_info['cc'];
    $recipients_cc = explode(',', $recipients_cc);
  }

  $email = "jhcarlos@mpic.com.ph";
  $pass = "Mpic8888!!";

  $mail = new PHPMailer\PHPMailer\PHPMailer(true);
  $mail->IsSMTP();
  $mail->SMTPOptions = array(
    'ssl' => array(
      'verify_peer' => false,
      'verify_peer_name' => false,
      'allow_self_signed' => true
    )
  );
  $mail->SMTPDebug  = 0;
  $mail->SMTPAuth   = true;
  $mail->SMTPSecure = "tls";
  $mail->Host       = "smtp.office365.com";
  $mail->Port       = 587;
  $mail->Username   = $email;
  $mail->Password   = $pass;

  $mail->AddAddress($em);
  foreach ($approvers as $k => $v) {
    $app_email = get_user_details($v['user_id']);
    $mail->AddAddress($app_email['email']);
  }
  if ($approver_role_info['cc'] != '') {
    foreach ($recipients_cc as $em => $n) {
      $mail->AddCC($n);
    }
  }
  $mail->AddEmbeddedImage('uploads/' . $company_logo, 'logo');
  $mail->SetFrom($email, $company_name);
  $mail->Subject = $company_name . ' - Salary Loan Application SL-' . $loan_id;

  $body = '
      Hi ' . $name . ',<br><br>
      Your Salary Loan Application has been received from the system. Please periodically check your email for any updates on the progress of your request.<br><br>
      Reference Number: <b>SL-' . $loan_id . '</b><br>
      Amount applied: <b>' . number_format($loan_info['amount'], 2) . '</b><br>
      Type of Loan: <b>' . $loan_info['type'] . '</b><br>
      Payment Term: <b>' . $loan_info['terms'] . ' month(s)</b><br>
      Status: <b>Pending - ' . $approve_role_name . '</b><br><br>
      To view your application, please click <a href="https://104.215.137.52/loan-details?' . md5('id') . '=' . md5($lid) . '">here</a><br><br>
      Thank you,<br>
      System Admin<br><br>
      <img src="cid:logo" height="50" width="200"><br>
      <p style="color:red">This is a system-generated message, please do not reply.</p>
      ';
  $mail->MsgHTML($body);
  $mail->Send();
}
function loanApplicationCancel($loan_id)
{
  $db = mysqli_connect('localhost', 'root', '', 'hrisv2');
  $lid = $loan_id;
  $loan_id = format_transaction_id($loan_id);
  $loan_info = get_salary_loan_info($loan_id);
  $employee_number = $loan_info['employee_number'];
  $personal_info = get_personal_information($employee_number);
  $em = $personal_info['company_email'];

  $name = get_personal_information($employee_number);
  $name = $name['account_name'];

  $company_id = $loan_info['company_id'];
  $company_name = '';
  $company_logo = '';

  $get_maintenance = mysqli_query($db, "SELECT t.company, t1.*, t2.company_name FROM tbl_employment_information t
  INNER JOIN tbl_maintenance t1
  ON t.company = t1.company_id
  INNER JOIN tbl_companies t2
  ON t.company = t2.ID
  WHERE t.employee_number = '$employee_number'");
  if ($r = mysqli_fetch_assoc($get_maintenance)) {
    $company_name = $r['company_name'];
    $company_logo = $r['logo'];
  }
  $email = "jhcarlos@mpic.com.ph";
  $pass = "Mpic8888!!";

  $mail = new PHPMailer\PHPMailer\PHPMailer(true);
  $mail->IsSMTP();
  $mail->SMTPOptions = array(
    'ssl' => array(
      'verify_peer' => false,
      'verify_peer_name' => false,
      'allow_self_signed' => true
    )
  );
  $mail->SMTPDebug  = 0;
  $mail->SMTPAuth   = true;
  $mail->SMTPSecure = "tls";
  $mail->Host       = "smtp.office365.com";
  $mail->Port       = 587;
  $mail->Username   = $email;
  $mail->Password   = $pass;

  $mail->AddAddress($em);
  $mail->AddEmbeddedImage('uploads/' . $company_logo, 'logo');
  $mail->SetFrom($email, $company_name);
  $mail->Subject = $company_name . ' - Salary Loan Application Status SL-' . $loan_id;

  $body = '
      Hi ' . $personal_info['account_name'] . ',<br><br>
      Your Salary Loan Application SL-' . $loan_id . ' has been Cancelled<br><br>
      To view your application, please click <a href="https://104.215.137.52/loan-details?' . md5('id') . '=' . md5($lid) . '">here</a><br><br>
      Thank you,<br>
      ' . $company_name . ' HR<br><br>
      <img src="cid:logo" height="50" width="200"><br>
      <p style="color:red">This is a system-generated message, please do not reply.</p>
      ';
  $mail->MsgHTML($body);
  $mail->Send();
}
function loanApplicationStatus($loan_id)
{
  $db = mysqli_connect('localhost', 'root', '', 'hrisv2');
  $lid = $loan_id;
  $loan_id = format_transaction_id($loan_id);
  $loan_info = get_salary_loan_info($loan_id);
  $employee_number = $loan_info['employee_number'];
  $personal_info = get_personal_information($employee_number);
  $em = $personal_info['company_email'];

  $name = get_personal_information($employee_number);
  $name = $name['account_name'];

  $company_id = $loan_info['company_id'];
  $company_name = '';
  $company_logo = '';

  $last_approver = get_last_approver_loan($company_id);
  $current_approver = $loan_info['status'];

  $get_maintenance = mysqli_query($db, "SELECT t.company, t1.*, t2.company_name FROM tbl_employment_information t
  INNER JOIN tbl_maintenance t1
  ON t.company = t1.company_id
  INNER JOIN tbl_companies t2
  ON t.company = t2.ID
  WHERE t.employee_number = '$employee_number'");
  if ($r = mysqli_fetch_assoc($get_maintenance)) {
    $company_name = $r['company_name'];
    $company_logo = $r['logo'];
  }
  if ($current_approver != $last_approver) {
    $next_approver = get_next_benefits_approver_loan($company_id, $loan_id);
    $approver_role_info = get_approvers_from_role_loan($next_approver, $company_id);
    $approvers = get_salary_loan_approvers($company_id, $approver_role_info['ID']);
    $approver_role_name = $approver_role_info['role'];
    if ($approver_role_info['cc'] != '') {
      $recipients_cc = $approver_role_info['cc'];
      $recipients_cc = explode(',', $recipients_cc);
    }
    $user_details = get_user_details($approvers[0]['user_id']);
  }

  $email = "jhcarlos@mpic.com.ph";
  $pass = "Mpic8888!!";

  $mail = new PHPMailer\PHPMailer\PHPMailer(true);
  $mail->IsSMTP();
  $mail->SMTPOptions = array(
    'ssl' => array(
      'verify_peer' => false,
      'verify_peer_name' => false,
      'allow_self_signed' => true
    )
  );
  $mail->SMTPDebug  = 0;
  $mail->SMTPAuth   = true;
  $mail->SMTPSecure = "tls";
  $mail->Host       = "smtp.office365.com";
  $mail->Port       = 587;
  $mail->Username   = $email;
  $mail->Password   = $pass;

  $mail->AddAddress($em);
  if ($current_approver != $last_approver) {
    foreach ($approvers as $k => $v) {
      $mail->AddAddress($user_details['email']);
    }
    if ($approver_role_info['cc'] != '') {
      foreach ($recipients_cc as $em => $n) {
        $mail->AddCC($n);
      }
    }
  }
  $mail->AddEmbeddedImage('uploads/' . $company_logo, 'logo');
  $mail->SetFrom($email, $company_name);
  $mail->Subject = $company_name . ' - Salary Loan Application SL-' . $loan_id;

  if ($current_approver == $last_approver) {
    $stat = 'Approved';
    $body = '
    Dear ' . $personal_info['account_name'] . ',<br><br>
    Your expense request has been fully authorized.<br><br>
    ';
  } else {
    $stat = 'Pending - ' . $approver_role_name;
    $body =
      'Dear ' . $personal_info['account_name'] . ',<br><br>
      Your Salary Loan Application has been successfully submitted to ' . $approver_role_name . ' for review and approval. Please periodically check your email for any updates on the progress of your request.<br><br>';
  }
  $body .= '
      Reference Number: <b>SL-' . $loan_id . '</b><br>
      Amount applied: <b>' . number_format($loan_info['amount'], 2) . '</b><br>
      Type of Loan: <b>' . $loan_info['type'] . '</b><br>
      Payment Term: <b>' . $loan_info['terms'] . ' month(s)</b><br>
      Status: <b>' . $stat . '</b><br><br>
      To view your application, please click <a href="https://104.215.137.52/loan-details?' . md5('id') . '=' . md5($lid) . '">here</a><br><br>
      Thank you,<br>
      System Admin<br><br>
      <img src="cid:logo" height="50" width="200"><br>
      <p style="color:red">This is a system-generated message, please do not reply.</p>
      ';
  $mail->MsgHTML($body);
  $mail->Send();
}
function reimbursementApplication($benefits_id)
{
  $db = mysqli_connect('localhost', 'root', '', 'hrisv2');
  $bid = $benefits_id;
  $benefits_id = format_transaction_id($benefits_id);
  $benefits_info = get_benefits_info($benefits_id);
  $employee_number = $benefits_info['payee'];
  $personal_info = get_personal_information($employee_number);
  $em = $personal_info['company_email'];

  $requestor = $benefits_info['requestor'];
  $requestor = get_personal_information($requestor);
  $requestor = $requestor['account_name'];

  $company_id = $benefits_info['company_id'];
  $company_name = '';
  $company_logo = '';

  $get_maintenance = mysqli_query($db, "SELECT t.company, t1.*, t2.company_name FROM tbl_employment_information t
  INNER JOIN tbl_maintenance t1
  ON t.company = t1.company_id
  INNER JOIN tbl_companies t2
  ON t.company = t2.ID
  WHERE t.employee_number = '$employee_number'");
  if ($r = mysqli_fetch_assoc($get_maintenance)) {
    $company_name = $r['company_name'];
    $company_logo = $r['logo'];
  }
  $approver_role_info = get_approvers_from_role(1, $company_id);
  $approvers = get_benefits_approvers($company_id, $approver_role_info['ID']);
  $approve_role_name = $approver_role_info['role'];
  if ($approver_role_info['cc'] != '') {
    $recipients_cc = $approver_role_info['cc'];
    $recipients_cc = explode(',', $recipients_cc);
  }

  $email = "jhcarlos@mpic.com.ph";
  $pass = "Mpic8888!!";

  $mail = new PHPMailer\PHPMailer\PHPMailer(true);
  $mail->IsSMTP();
  $mail->SMTPOptions = array(
    'ssl' => array(
      'verify_peer' => false,
      'verify_peer_name' => false,
      'allow_self_signed' => true
    )
  );
  $mail->SMTPDebug  = 0;
  $mail->SMTPAuth   = true;
  $mail->SMTPSecure = "tls";
  $mail->Host       = "smtp.office365.com";
  $mail->Port       = 587;
  $mail->Username   = $email;
  $mail->Password   = $pass;

  $mail->AddAddress($em);
  foreach ($approvers as $k => $v) {
    $app_email = get_user_details($v['user_id']);
    $mail->AddAddress($app_email['email']);
  }
  if ($approver_role_info['cc'] != '') {
    foreach ($recipients_cc as $em => $n) {
      $mail->AddCC($n);
    }
  }
  $mail->AddEmbeddedImage('uploads/' . $company_logo, 'logo');
  $mail->SetFrom($email, $company_name);
  $mail->Subject = $company_name . ' - Benefits Reimbursement Application BR-' . $benefits_id;

  $body = '
      Dear ' . $personal_info['account_name'] . ',<br><br>
      Your expense request has been successfully submitted to ' . $approve_role_name . ' for review and approval. Please periodically check your email for any updates on the progress of your request.<br><br>
      Requestor: <b>' . $requestor . '</b><br>
      Reference Number: <b>BR-' . $benefits_id . '</b><br>
      Status: <b>Pending - HR Benefits Admin</b><br><br>

      Benefit Expense: <b>' . rtrim($benefits_info["categories_applied"], ", ") . '</b><br>
      Amount: <b>' . number_format($benefits_info["amount"], 2) . '</b><br>
      Comments: <b>' . $benefits_info["special_instruction"] . '</b><br><br>

      To view your application, please click <a href="https://104.215.137.52/reimbursement-details?' . md5('id') . '=' . md5($bid) . '">here</a><br><br>
      Thank you,<br>
      System Admin<br><br>
      <img src="cid:logo" height="50" width="200"><br>
      <p style="color:red">This is a system-generated message, please do not reply.</p>
      ';
  $mail->MsgHTML($body);
  $mail->Send();
}
function reimbursementApplicationStatus($benefits_id)
{
  $db = mysqli_connect('localhost', 'root', '', 'hrisv2');
  $ben_id = $benefits_id;
  $benefits_id = format_transaction_id($benefits_id);
  $benefits_info = get_benefits_info($benefits_id);
  $employee_number = $benefits_info['payee'];
  $personal_info = get_personal_information($employee_number);
  $em = $personal_info['company_email'];

  $requestor = $benefits_info['requestor'];
  $requestor = get_personal_information($requestor);
  $requestor = $requestor['account_name'];

  $company_id = $benefits_info['company_id'];
  $company_name = '';
  $company_logo = '';

  $last_approver = get_last_approver($company_id);
  $current_approver = $benefits_info['status'];

  $get_maintenance = mysqli_query($db, "SELECT t.company, t1.*, t2.company_name FROM tbl_employment_information t
  INNER JOIN tbl_maintenance t1
  ON t.company = t1.company_id
  INNER JOIN tbl_companies t2
  ON t.company = t2.ID
  WHERE t.employee_number = '$employee_number'");
  if ($r = mysqli_fetch_assoc($get_maintenance)) {
    $company_name = $r['company_name'];
    $company_logo = $r['logo'];
  }

  if ($current_approver != $last_approver) {
    $next_approver = get_next_benefits_approver($company_id, $ben_id);
    $approver_role_info = get_approvers_from_role($next_approver, $company_id);
    $approvers = get_benefits_approvers($company_id, $approver_role_info['ID']);
    $approver_role_name = $approver_role_info['role'];
    if ($approver_role_info['cc'] != '') {
      $recipients_cc = $approver_role_info['cc'];
      $recipients_cc = explode(',', $recipients_cc);
    }
    $user_details = get_user_details($approvers[0]['user_id']);
  }

  $email = "jhcarlos@mpic.com.ph";
  $pass = "Mpic8888!!";

  $mail = new PHPMailer\PHPMailer\PHPMailer(true);
  $mail->IsSMTP();
  $mail->SMTPOptions = array(
    'ssl' => array(
      'verify_peer' => false,
      'verify_peer_name' => false,
      'allow_self_signed' => true
    )
  );
  $mail->SMTPDebug  = 0;
  $mail->SMTPAuth   = true;
  $mail->SMTPSecure = "tls";
  $mail->Host       = "smtp.office365.com";
  $mail->Port       = 587;
  $mail->Username   = $email;
  $mail->Password   = $pass;

  $mail->AddAddress($em);
  if ($current_approver != $last_approver) {
    foreach ($approvers as $k => $v) {
      $mail->AddAddress($user_details['email']);
    }
    if ($approver_role_info['cc'] != '') {
      foreach ($recipients_cc as $em => $n) {
        $mail->AddCC($n);
      }
    }
  }
  $mail->AddEmbeddedImage('uploads/' . $company_logo, 'logo');
  $mail->SetFrom($email, $company_name);
  $mail->Subject = $company_name . ' - Benefits Reimbursement Application BR-' . $benefits_id;
  if ($current_approver == $last_approver) {
    $stat = 'Approved';
    $body = '
    Dear ' . $personal_info['account_name'] . ',<br><br>
    Your expense request has been fully authorized.<br><br>
    ';
  } else {
    $stat = 'Pending - ' . $approver_role_name;
    $body =
      'Dear ' . $personal_info['account_name'] . ',<br><br>
      Your expense request has been successfully submitted to ' . $approver_role_name . ' for review and approval. Please periodically check your email for any updates on the progress of your request.<br><br>';
  }
  $body .= '
      Requestor: <b>' . $requestor . '</b><br>
      Reference Number: <b>BR-' . $benefits_id . '</b><br>
      Status: <b>' . $stat . '</b><br><br>

      Benefit Expense: <b>' . rtrim($benefits_info["categories_applied"], ", ") . '</b><br>
      Amount: <b>' . number_format($benefits_info["amount"], 2) . '</b><br>
      Comments: <b>' . $benefits_info["special_instruction"] . '</b><br><br>

      To view your application, please click <a href="https://104.215.137.52/reimbursement-details?' . md5('id') . '=' . md5($ben_id) . '">here</a><br><br>
      Thank you,<br>
      System Admin<br><br>
      <img src="cid:logo" height="50" width="200"><br>
      <p style="color:red">This is a system-generated message, please do not reply.</p>
      ';
  $mail->MsgHTML($body);
  $mail->Send();
}
function reimbursementApplicationCancel($benefits_id)
{
  $db = mysqli_connect('localhost', 'root', '', 'hrisv2');
  $bid = $benefits_id;;
  $benefits_id = format_transaction_id($benefits_id);
  $benefits_info = get_benefits_info($benefits_id);
  $employee_number = $benefits_info['payee'];
  $personal_info = get_personal_information($employee_number);
  $em = $personal_info['company_email'];

  $requestor = $benefits_info['requestor'];
  $requestor = get_personal_information($requestor);
  $requestor = $requestor['account_name'];

  $company_id = $benefits_info['company_id'];
  $company_name = '';
  $company_logo = '';

  $get_maintenance = mysqli_query($db, "SELECT t.company, t1.*, t2.company_name FROM tbl_employment_information t
  INNER JOIN tbl_maintenance t1
  ON t.company = t1.company_id
  INNER JOIN tbl_companies t2
  ON t.company = t2.ID
  WHERE t.employee_number = '$employee_number'");
  if ($r = mysqli_fetch_assoc($get_maintenance)) {
    $company_name = $r['company_name'];
    $company_logo = $r['logo'];
  }

  $email = "jhcarlos@mpic.com.ph";
  $pass = "Mpic8888!!";

  $mail = new PHPMailer\PHPMailer\PHPMailer(true);
  $mail->IsSMTP();
  $mail->SMTPOptions = array(
    'ssl' => array(
      'verify_peer' => false,
      'verify_peer_name' => false,
      'allow_self_signed' => true
    )
  );
  $mail->SMTPDebug  = 0;
  $mail->SMTPAuth   = true;
  $mail->SMTPSecure = "tls";
  $mail->Host       = "smtp.office365.com";
  $mail->Port       = 587;
  $mail->Username   = $email;
  $mail->Password   = $pass;

  $mail->AddAddress($em);

  $mail->AddEmbeddedImage('uploads/' . $company_logo, 'logo');
  $mail->SetFrom($email, $company_name);
  $mail->Subject = $company_name . ' - Benefits Reimbursement Application BR-' . $benefits_id;

  $body = '
      Dear ' . $personal_info['account_name'] . ',<br><br>
      Your expense request has been cancelled.<br><br>
      Requestor: <b>' . $requestor . '</b><br>
      Reference Number: <b>BR-' . $benefits_id . '</b><br>
      Status: <b>Cancelled</b><br><br>

      Benefit Expense: <b>' . rtrim($benefits_info["categories_applied"], ", ") . '</b><br>
      Amount: <b>' . number_format($benefits_info["amount"], 2) . '</b><br>
      Comments: <b>' . $benefits_info["special_instruction"] . '</b><br><br>

      To view your application, please click <a href="https://104.215.137.52/reimbursement-details?' . md5('id') . '=' . md5($bid) . '">here</a><br><br>
      Thank you,<br>
      System Admin<br><br>
      <img src="cid:logo" height="50" width="200"><br>
      <p style="color:red">This is a system-generated message, please do not reply.</p>
      ';
  $mail->MsgHTML($body);
  $mail->Send();
}
function reimbursementApplicationDecline($benefits_id)
{
  $db = mysqli_connect('localhost', 'root', '', 'hrisv2');
  $bid = $benefits_id;
  $benefits_id = format_transaction_id($benefits_id);
  $benefits_info = get_benefits_info($benefits_id);
  $employee_number = $benefits_info['payee'];
  $personal_info = get_personal_information($employee_number);
  $em = $personal_info['company_email'];

  $requestor = $benefits_info['requestor'];
  $requestor = get_personal_information($requestor);
  $requestor = $requestor['account_name'];

  $company_id = $benefits_info['company_id'];
  $company_name = '';
  $company_logo = '';

  $get_maintenance = mysqli_query($db, "SELECT t.company, t1.*, t2.company_name FROM tbl_employment_information t
  INNER JOIN tbl_maintenance t1
  ON t.company = t1.company_id
  INNER JOIN tbl_companies t2
  ON t.company = t2.ID
  WHERE t.employee_number = '$employee_number'");
  if ($r = mysqli_fetch_assoc($get_maintenance)) {
    $company_name = $r['company_name'];
    $company_logo = $r['logo'];
  }

  $email = "jhcarlos@mpic.com.ph";
  $pass = "Mpic8888!!";

  $mail = new PHPMailer\PHPMailer\PHPMailer(true);
  $mail->IsSMTP();
  $mail->SMTPOptions = array(
    'ssl' => array(
      'verify_peer' => false,
      'verify_peer_name' => false,
      'allow_self_signed' => true
    )
  );
  $mail->SMTPDebug  = 0;
  $mail->SMTPAuth   = true;
  $mail->SMTPSecure = "tls";
  $mail->Host       = "smtp.office365.com";
  $mail->Port       = 587;
  $mail->Username   = $email;
  $mail->Password   = $pass;

  $mail->AddAddress($em);

  $mail->AddEmbeddedImage('uploads/' . $company_logo, 'logo');
  $mail->SetFrom($email, $company_name);
  $mail->Subject = $company_name . ' - Benefits Reimbursement Application BR-' . $benefits_id;

  $body = '
      Dear ' . $personal_info['account_name'] . ',<br><br>
      Your expense request has been declined.<br><br>
      Requestor: <b>' . $requestor . '</b><br>
      Reference Number: <b>BR-' . $benefits_id . '</b><br>
      Status: <b>Declined</b><br><br>

      Benefit Expense: <b>' . rtrim($benefits_info["categories_applied"], ", ") . '</b><br>
      Amount: <b>' . number_format($benefits_info["amount"], 2) . '</b><br>
      Comments: <b>' . $benefits_info["special_instruction"] . '</b><br><br>

      To view your application, please click <a href="https://104.215.137.52/reimbursement-details?' . md5('id') . '=' . md5($bid) . '">here</a><br><br>
      Thank you,<br>
      System Admin<br><br>
      <img src="cid:logo" height="50" width="200"><br>
      <p style="color:red">This is a system-generated message, please do not reply.</p>
      ';
  $mail->MsgHTML($body);
  $mail->Send();
}
function reimbursementApplicationRequestUpdate($benefits_id, $requested_by)
{
  $db = mysqli_connect('localhost', 'root', '', 'hrisv2');
  $bid = $benefits_id;
  $benefits_id = format_transaction_id($benefits_id);
  $benefits_info = get_benefits_info($benefits_id);
  $employee_number = $benefits_info['payee'];
  $personal_info = get_personal_information($employee_number);
  $em = $personal_info['company_email'];

  $requestor = $benefits_info['requestor'];
  $requestor = get_personal_information($requestor);
  $requestor = $requestor['account_name'];

  $company_id = $benefits_info['company_id'];
  $company_name = '';
  $company_logo = '';

  $get_maintenance = mysqli_query($db, "SELECT t.company, t1.*, t2.company_name FROM tbl_employment_information t
  INNER JOIN tbl_maintenance t1
  ON t.company = t1.company_id
  INNER JOIN tbl_companies t2
  ON t.company = t2.ID
  WHERE t.employee_number = '$employee_number'");
  if ($r = mysqli_fetch_assoc($get_maintenance)) {
    $company_name = $r['company_name'];
    $company_logo = $r['logo'];
  }

  $email = "jhcarlos@mpic.com.ph";
  $pass = "Mpic8888!!";

  $mail = new PHPMailer\PHPMailer\PHPMailer(true);
  $mail->IsSMTP();
  $mail->SMTPOptions = array(
    'ssl' => array(
      'verify_peer' => false,
      'verify_peer_name' => false,
      'allow_self_signed' => true
    )
  );
  $mail->SMTPDebug  = 0;
  $mail->SMTPAuth   = true;
  $mail->SMTPSecure = "tls";
  $mail->Host       = "smtp.office365.com";
  $mail->Port       = 587;
  $mail->Username   = $email;
  $mail->Password   = $pass;

  $mail->AddAddress($em);

  $mail->AddEmbeddedImage('uploads/' . $company_logo, 'logo');
  $mail->SetFrom($email, $company_name);
  $mail->Subject = $company_name . ' - Benefits Reimbursement Application BR-' . $benefits_id;

  $body = '
      Dear ' . $personal_info['account_name'] . ',<br><br>
      Your expense request has been reviewed by ' . $requested_by . ' and requested an update.
      Requestor: <b>' . $requestor . '</b><br>
      Reference Number: <b>BR-' . $benefits_id . '</b><br>
      Status: <b>Update Requested</b><br><br>

      Benefit Expense: <b>' . rtrim($benefits_info["categories_applied"], ", ") . '</b><br>
      Amount: <b>' . number_format($benefits_info["amount"], 2) . '</b><br>
      Comments: <b>' . $benefits_info["special_instruction"] . '</b><br>
      HR Remarks: <b>' . $benefits_info["hr_remarks"] . '</b><br><br>

      To view your application, please click <a href="https://104.215.137.52/reimbursement-details?' . md5('id') . '=' . md5($bid) . '">here</a><br><br>
      Thank you,<br>
      System Admin<br><br>
      <img src="cid:logo" height="50" width="200"><br>
      <p style="color:red">This is a system-generated message, please do not reply.</p>
      ';
  $mail->MsgHTML($body);
  $mail->Send();
}
function forgot_password_code($UserEmail, $code)
{
  $db = mysqli_connect('localhost', 'root', '', 'hrisv2');

  $sql = mysqli_query($db, "SELECT * FROM tbl_users WHERE email LIKE '$UserEmail'");
  $name = "";
  while ($row = mysqli_fetch_assoc($sql)) {
    $name = $row['first_name'];
  }

  $email = "jhcarlos@mpic.com.ph";
  $pass = "Mpic8888!!";

  $mail = new PHPMailer\PHPMailer\PHPMailer(true);
  $mail->IsSMTP();
  $mail->SMTPOptions = array(
    'ssl' => array(
      'verify_peer' => false,
      'verify_peer_name' => false,
      'allow_self_signed' => true
    )
  );
  $mail->SMTPDebug  = 2;
  $mail->SMTPAuth   = true;
  $mail->SMTPSecure = "tls";
  $mail->Host       = "smtp.office365.com";
  $mail->Port       = 587;
  $mail->Username   = $email;
  $mail->Password   = $pass;

  $mail->AddAddress($UserEmail);
  $mail->SetFrom($email, 'HRIS - Forgot Password Code');
  $mail->Subject = 'HRIS - Forgot Password';

  $body = '
      Hi <b>' . $name . '</b>,<br>
      <br>Your forgot password confirmation code is : <b>' . $code . '</b>
      <br><br>
      Thank you,<br>
      MPW HR
      ';
  $mail->MsgHTML($body);
  if (!$mail->Send()) {
    echo "Error sending";
  } else {
    $c = md5($code);
    header("Location: forgot_password_code?" . md5('code') . '=' . $c . '&' . md5('email') . "=" . $UserEmail);
  }
}
