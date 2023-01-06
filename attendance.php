<?php include 'inc/config.php'; ?>
<?php
if (empty($_SESSION['hris_id'])) {
    header('Location: login');
}
?>
<?php include 'inc/template_start.php'; ?>
<?php include 'inc/page_head.php'; ?>
<?php
if ($_SESSION['hris_role'] == "Processor") {
    header('Location: pending-tasks');
}
?>
<?php
$cid = $_SESSION['hris_company_id'];
$banner = 'default-banner.png';
$get_maintenance = mysqli_query($db, "SELECT * FROM tbl_maintenance WHERE company_id = '$cid'");
$img = mysqli_fetch_assoc($get_maintenance);

$account_name = $_SESSION['hris_account_name'];
$employee_num = $_SESSION['hris_employee_number'];
$date_now = date('y-m-d');
$time_ined = true;
$sql = mysqli_query($db, "SELECT * FROM tbl_attendance WHERE emp_name = '$account_name' AND (statusnow = 'Time In' AND datenow = '$date_now')");
if (mysqli_num_rows($sql) <= 0) {
    $_SESSION['status'] = "Time In";
    $_SESSION['btn_disabled'] = false;
    $time_ined = false;
}

$sql = mysqli_query($db, "SELECT * FROM tbl_attendance WHERE emp_name = '$account_name' AND (statusnow = 'Time Out' AND datenow = '$date_now')");
if ($time_ined == true) {
    if (mysqli_num_rows($sql) <= 0) {
        $_SESSION['status'] = "Time Out";
        $_SESSION['btn_disabled'] = false;
    } else {
        $_SESSION['status'] = "Time Out";
        $_SESSION['btn_disabled'] = true;
    }
}



?>
<div id="page-content" onLoad="renderTime();">
    <!-- Datatables Header -->
    <div class="content-header">
        <div class="header-section">
            <h1>
                <i class="fa fa-user"></i><strong>Attendance</strong>
            </h1>
        </div>
        <div class="row" style="margin-top: 1%;">
            <center>
                <div id="timeDisplay" name="timenow" class="date-container" style="font-size:30px; font-weight:800;">
                </div>
            </center>
        </div>
        <form method="POST" enctype="multipart/form-data">
            <div class="row" style="margin-top: 1%;">
                <center>
                    <div id="clockDisplay" name="datenow" class="date-container" style="font-size:30px; font-weight:800;"></div>
                </center>
                <center>
                    <br><br>
                    <!-- <button type="submit" id="btntimein" name="btn_timein" class="btn btn-lg btn-primary" onclick="enabletimeout()">Time In</button> -->
                    <button type="submit" id="btntimein" name="btn_timeinout" class="btn btn-lg btn-primary" <?php if ($_SESSION['btn_disabled'] == true) {
                                                                                                                    echo 'disabled';
                                                                                                                }
                                                                                                                ?>><?= $_SESSION['status'] ?>

                    </button>
                    <!-- <button type="submit" id="btntimeout" name="btn_timeout"class="btn btn-lg btn-primary " onclick="enabletimein()">Time Out</button> -->
                </center>
            </div>
            <!-- Time -->
            <!-- <div class="row"> -->
            <br>
            <hr>
            <center>


                <div class="col-6 col-md-4">

                </div>

                <!-- </div> -->
            </center>
            <input type="hidden" name="status" class="form-control" value="<?= $_SESSION['status'] ?>">
            <input type="hidden" name="emp_num" class="form-control" value="<?= $_SESSION['hris_employee_number'] ?>">
            <input type="hidden" name="emp_name" class="form-control" value="<?= $_SESSION['hris_account_name'] ?>">
        </form>
    </div>
    <!-- END Datatables Header -->

    <!-- Date -->

    <br><br><br>
    <div class="block full">
        <div class="block-title">
            <h2><strong><?= $_SESSION['hris_account_name'] ?></strong> Timesheet</h2>
        </div>
        <?= $res ?>
        <div class="table-responsive">
            <table id="two-column" class="table table-vcenter table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">Employee Number</th>
                        <th class="text-center">Employee Name</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Time</th>
                        <th class="text-center">Duration</th>


                    </tr>
                </thead>
                <tbody>
                    <?php
                    // $account_name = $_SESSION['hris_account_name'];
                    $sql = mysqli_query($db, "SELECT * FROM tbl_attendance WHERE emp_num = '$employee_num'");
                    while ($row = mysqli_fetch_assoc($sql)) {
                    ?>
                        <tr>
                            <td class="text-center"><?= $row['emp_num'] ?></td>
                            <td class="text-center"><?= $row['emp_name'] ?></td>
                            <td class="text-center"><?= $row['statusnow'] ?></td>
                            <td class="text-center"><?= $row['datenow'] ?></td>
                            <td class="text-center"><?= $row['timenow'] ?></td>
                            <td class="text-center"><?= $row['total_duration'] ?></td>

                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div><br><br>
        <div style="text-align: center; display:flex;">
            <div style="width: 100%; text-align: right;">
                <form method="POST">
                    <a href="javascript:void(0)" class="btn btn-primary" onclick="$('#modal-choose-cutoff').modal('show');">Download Summary</a>
                </form>
            </div>

        </div>
    </div>

</div>

<!-- END Widgets Row -->


<div id="modal-choose-cutoff" class="modal fade" tabindex="-2" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h2 class="modal-title"><i class="fa fa-list"></i> Choose Cutoff</h2>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form method="POST" class="form-horizontal form-bordered">
                        <input type="hidden" name="id" value="<?= $rid ?>">
                        <div class="form-group">
                            <label>Date From:</label>
                            <input type="date" name="date_from" required class="form-control">
                            <label>Date To:</label>
                            <input type="date" name="date_to" required class="form-control">
                        </div><br>
                        <center>
                            <?php
                            if ($_SESSION['hris_role'] != "User") {
                            ?>
                                <button class="btn btn-primary" name="print_overall_attendance_summary">Overall Attendance Summary</button> &nbsp;&nbsp;
                            <?php
                            }
                            ?>
                            <button class="btn btn-primary" name="print_attendance_summary"> Attendance Summary</button>
                        </center>
                        <br>
                        <br>
                    </form>
                </div>
            </div>
        </div>
    </div>




</div>
<!-- END Page Content -->

<?php include 'inc/template_scripts.php'; ?>

<!-- Google Maps API Key (you will have to obtain a Google Maps API key to use Google Maps) -->
<!-- For more info please have a look at https://developers.google.com/maps/documentation/javascript/get-api-key#key -->
<script src="https://maps.googleapis.com/maps/api/js?key="></script>
<script src="js/helpers/gmaps.min.js"></script>

<!-- Load and execute javascript code used only in this page -->
<script src="js/pages/index.js"></script>
<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>
<script>
    function realtimeClock() {
        // for time
        var rtClock = new Date();
        var rthours = rtClock.getHours();
        var rtminutes = rtClock.getMinutes();
        var rtseconds = rtClock.getSeconds();

        var amPm = (rthours < 12) ? "AM" : "PM";

        rthours = (rthours > 12) ? rthours - 12 : rthours;

        rthours = ("0" + rthours).slice(-2);
        rtminutes = ("0" + rtminutes).slice(-2);
        rtseconds = ("0" + rtseconds).slice(-2);

        document.getElementById('timeDisplay').innerHTML = rthours + " : " + rtminutes + " : " + rtseconds + " " + amPm;
        var t = setTimeout("realtimeClock()", 500);

        // for DATE

        var mydate = new Date();
        var year = mydate.getYear();
        if (year < 1000) {
            year += 1900
        }

        var day = mydate.getDay();
        var month = mydate.getMonth();
        var daym = mydate.getDate();
        var dayarray = new Array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
        var montharray = new Array("January", "February", "March", "April", "May", "June", "July", "August", "Sepptember", "October", "November", "December");
        var pe = "AM"
        var myClock = document.getElementById("clockDisplay");
        myClock.textContent = "" + dayarray[day] + " | " + montharray[month] + " " + daym + ", " + year;
        myClock.innerText = "" + dayarray[day] + " | " + montharray[month] + " " + daym + ", " + year;
    }
    realtimeClock();
</script>
<script>
    function enabletimein() {
        document.getElementById('btntimeout').disabled = true;
        document.getElementById('btntimein').disabled = false;

    }


    function enabletimeout() {

        document.getElementById('btntimein').disabled = true;
        document.getElementById('btntimeout').disabled = false;
    }
</script>

<?php include 'inc/template_end.php'; ?>
<script src="js/pages/pagination.js"></script>
<script>
    $(function() {
        TablesDatatables.init();
    });
</script>