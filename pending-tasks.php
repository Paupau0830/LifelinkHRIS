<?php include 'inc/config.php'; ?>
<?php include 'inc/template_start.php'; ?>
<?php include 'inc/page_head.php'; ?>
<?php
if (empty($_SESSION['hris_id'])) {
    header('Location: login');
}
$company_id = $_SESSION['hris_company_id'];
$userrole = $_SESSION['hris_role'];
$userid = $_SESSION['hris_id'];

$leave_count = '';
$benefits_count = '';
$ot_count = '';
$certificate_count = '';
$loan_count = '';

if (isset($_SESSION['hris_employee_number'])) {
    $empnum = $_SESSION['hris_employee_number'];
    $leave_count = '<span class="badge">' . get_pending_leave_count($empnum) . '</span>';
    $benefits_count = '<span class="badge">' . get_benefits_pending_count($userid, $company_id) . '</span>';
    $ot_count = '<span class="badge">' . get_pending_ot_count($empnum) . '</span>';
    $certificate_count = '<span class="badge">' . get_pending_certificate_count($userid, $company_id) . '</span>';
    $loan_count = '<span class="badge">' . get_loan_pending_count($userid, $company_id) . '</span>';
} else {
    $certificate_count = '<span class="badge">' . get_pending_certificate_count($userid, $company_id) . '</span>';
    $loan_count = '<span class="badge">' . get_loan_pending_count($userid, $company_id) . '</span>';
    $benefits_count = '<span class="badge">' . get_benefits_pending_count($userid, $company_id) . '</span>';
}
?>
<div id="page-content">
    <div class="content-header">
        <div class="header-section">
            <h1>
                <i class="fa fa-list"></i>Pending Tasks
            </h1>
        </div>
    </div>
    <div class="row">
        <?php
        if (isset($_SESSION['hris_employee_number'])) {
        ?>
            <div class="col-sm-6 col-lg-4">
                <a href="leave-list" class="widget widget-hover-effect1">
                    <div class="widget-simple">
                        <div class="widget-icon pull-left themed-background-autumn animation-fadeIn">
                            <i class="fa fa-user-times"></i>
                        </div>
                        <h3 class="widget-content text-right animation-pullDown">
                            <strong>Leave Applications <?= $leave_count ?></strong>
                        </h3>
                    </div>
                </a>
            </div>
        <?php
        }
        ?>
        <div class="col-sm-6 col-lg-4">
            <a href="reimbursement-list" class="widget widget-hover-effect1">
                <div class="widget-simple">
                    <div class="widget-icon pull-left themed-background-spring animation-fadeIn">
                        <i class="fa fa-exchange"></i>
                    </div>
                    <h3 class="widget-content text-right animation-pullDown">
                        <strong>Reimbursement Applications <?= $benefits_count ?></strong>
                    </h3>
                </div>
            </a>
        </div>
        <?php
        if (isset($_SESSION['hris_employee_number'])) {
        ?>
            <div class="col-sm-6 col-lg-4">
                <a href="ot-list" class="widget widget-hover-effect1">
                    <div class="widget-simple">
                        <div class="widget-icon pull-left themed-background-fire animation-fadeIn">
                            <i class="fa fa-hourglass"></i>
                        </div>
                        <h3 class="widget-content text-right animation-pullDown">
                            <strong>OT Applications <?= $ot_count ?></strong>
                        </h3>
                    </div>
                </a>
            </div>
        <?php
        }
        ?>
        <div class="col-sm-6 col-lg-4">
            <a href="certificate-request-list" class="widget widget-hover-effect1">
                <div class="widget-simple">
                    <div class="widget-icon pull-left themed-background-amethyst animation-fadeIn">
                        <i class="fa fa-file"></i>
                    </div>
                    <h3 class="widget-content text-right animation-pullDown">
                        <strong>Certificate Requests <?= $certificate_count ?></strong>
                    </h3>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-lg-4">
            <a href="loan-application-list" class="widget widget-hover-effect1">
                <div class="widget-simple">
                    <div class="widget-icon pull-left themed-background-amethyst animation-fadeIn">
                        <i class="fa fa-money"></i>
                    </div>
                    <h3 class="widget-content text-right animation-pullDown">
                        <strong>Salary Loan Requests <?= $loan_count ?></strong>
                    </h3>
                </div>
            </a>
        </div>
    </div>
    <!-- END Widgets Row -->
</div>
<!-- END Page Content -->

<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>
<script src="js/pages/index.js"></script>

<?php include 'inc/template_end.php'; ?>