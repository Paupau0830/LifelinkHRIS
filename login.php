<?php include 'inc/config.php'; ?>
<?php include 'inc/template_start.php'; ?>
<?php
if (empty($_GET['i'])) {
    if (isset($_COOKIE['email'])) {
        $email = $_COOKIE['email'];
        $password = $_COOKIE['password'];

        $sql = mysqli_query($db, "SELECT t.*, t1.employee_number
        FROM tbl_users t
        INNER JOIN tbl_personal_information t1
        ON t.email = t1.company_email
        WHERE t.email = '$email' AND t.`password` = '$password'");
        if (mysqli_num_rows($sql) > 0) {
            if ($row = mysqli_fetch_assoc($sql)) {
                $_SESSION['hris_id'] = $row['ID'];
                $_SESSION['hris_role'] = $row['role'];
                $_SESSION['hris_email'] = $row['email'];
                $_SESSION['pending_task'] = $row['pending_task'];
                $_SESSION['hris_company_id'] = $row['company_id'];
                $_SESSION['hris_account_name'] = $row['account_name'];
                $_SESSION['hris_employee_number'] = $row['employee_number'];
                $_SESSION['holiday_maintenance'] = $row['holiday_maintenance'];
                header('Location:index');
            }
        } else {
            $res = '<div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="fa fa-times"></i> Invalid Email or Password</h4>
                    </div>
            ';
        }
    }
} elseif ($_GET['i'] == '1') {
    removeCookie();
    header('Location: login');
}
?>
<style>
    .bg {
        background: url('img/placeholders/headers/bgg.jpg');
        background-repeat: no-repeat;
        background-size: cover;
        height: 100vh;
    }

    body {
        overflow-y: hidden;
        overflow-x: hidden;
    }
</style>
<div class="bg animation-pulseSlow"></div>
<div id="login-container" class="animation-fadeIn">
    <div class="login-title text-center">
        <h1><i class="fa fa-globe"></i> <strong><?php echo $template['name']; ?></strong><br></h1>
    </div>
    <div class="block push-bit">
        <?= $res ?>
        <form method="post" id="form-login" class="form-horizontal form-bordered form-control-borderless">
            <div class="form-group">
                <div class="col-xs-12">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="gi gi-envelope"></i></span>
                        <input type="text" id="login-email" name="email" class="form-control input-lg" placeholder="Email">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-12">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="gi gi-asterisk"></i></span>
                        <input type="password" id="login-password" name="password" class="form-control input-lg" placeholder="Password">
                    </div>
                </div>
            </div>
            <div class="form-group form-actions">
                <div class="col-xs-4">
                    <label class="switch switch-primary" data-toggle="tooltip" title="Remember Me?">
                        <input type="checkbox" id="login-remember-me" name="remember_me" checked value="1">
                        <span></span>
                    </label>
                </div>
                <div class="col-xs-8 text-right">
                    <button type="submit" class="btn btn-sm btn-primary" name="btn_login"><i class="fa fa-angle-right"></i> Login to Dashboard</button>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-12 text-center">
                    <a href="javascript:void(0)" id="link-reminder-login"><small>Forgot password?</small></a>
                </div>
            </div>
        </form>
        <form action="login.php#reminder" method="post" id="form-reminder" class="form-horizontal form-bordered form-control-borderless display-none">
            <div class="form-group">
                <div class="col-xs-12">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="gi gi-envelope"></i></span>
                        <input type="text" id="reminder-email" name="reminder-email" class="form-control input-lg" placeholder="Email">
                    </div>
                </div>
            </div>
            <div class="form-group form-actions">
                <div class="col-xs-12 text-right">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-angle-right"></i> Reset Password</button>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-12 text-center">
                    <small>Did you remember your password?</small> <a href="javascript:void(0)" id="link-reminder"><small>Login</small></a>
                </div>
            </div>
        </form>
    </div>
    <!-- <footer class="text-muted text-center" style="color:#fff">
        <small><span>2021</span> &copy; Metro Pacific Investments Corporation</small>
    </footer> -->
</div>

<?php include 'inc/template_scripts.php'; ?>

<script src="js/pages/login.js"></script>
<script>
    $(function() {
        Login.init();
    });
</script>
<?php include 'inc/template_end.php'; ?>
