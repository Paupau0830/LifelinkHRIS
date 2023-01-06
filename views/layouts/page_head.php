<div id="page-wrapper"
    <?php
    //    if ($template['page_preloader']) {
    //                            echo ' class="page-loading"';
    //                        }
    ?>
>
    <!-- Preloader -->
    <!-- Preloader functionality (initialized in js/app.js) - pageLoading() -->
    <!-- Used only if page preloader is enabled from inc/config (PHP version) or the class 'page-loading' is added in #page-wrapper element (HTML version) -->
    <div class="preloader themed-background">
        <h1 class="push-top-bottom text-light text-center"><strong>HRIS</strong></h1>
        <div class="inner">
            <h3 class="text-light visible-lt-ie10"><strong>Loading..</strong></h3>
            <div class="preloader-spinner hidden-lt-ie10"></div>
        </div>
    </div>
    <!-- END Preloader -->

    <!-- Page Container -->
    <!-- In the PHP version you can set the following options from inc/config file -->
    <!--
        Available #page-container classes:

        '' (None)                                       for a full main and alternative sidebar hidden by default (> 991px)

        'sidebar-visible-lg'                            for a full main sidebar visible by default (> 991px)
        'sidebar-partial'                               for a partial main sidebar which opens on mouse hover, hidden by default (> 991px)
        'sidebar-partial sidebar-visible-lg'            for a partial main sidebar which opens on mouse hover, visible by default (> 991px)
        'sidebar-mini sidebar-visible-lg-mini'          for a mini main sidebar with a flyout menu, enabled by default (> 991px + Best with static layout)
        'sidebar-mini sidebar-visible-lg'               for a mini main sidebar with a flyout menu, disabled by default (> 991px + Best with static layout)

        'sidebar-alt-visible-lg'                        for a full alternative sidebar visible by default (> 991px)
        'sidebar-alt-partial'                           for a partial alternative sidebar which opens on mouse hover, hidden by default (> 991px)
        'sidebar-alt-partial sidebar-alt-visible-lg'    for a partial alternative sidebar which opens on mouse hover, visible by default (> 991px)

        'sidebar-partial sidebar-alt-partial'           for both sidebars partial which open on mouse hover, hidden by default (> 991px)

        'sidebar-no-animations'                         add this as extra for disabling sidebar animations on large screens (> 991px) - Better performance with heavy pages!

        'style-alt'                                     for an alternative main style (without it: the default style)
        'footer-fixed'                                  for a fixed footer (without it: a static footer)

        'disable-menu-autoscroll'                       add this to disable the main menu auto scrolling when opening a submenu

        'header-fixed-top'                              has to be added only if the class 'navbar-fixed-top' was added on header.navbar
        'header-fixed-bottom'                           has to be added only if the class 'navbar-fixed-bottom' was added on header.navbar

        'enable-cookies'                                enables cookies for remembering active color theme when changed from the sidebar links
    -->
    <?php
    $page_classes = '';

    if ($template['header'] == 'navbar-fixed-top') {
        $page_classes = 'header-fixed-top';
    } else if ($template['header'] == 'navbar-fixed-bottom') {
        $page_classes = 'header-fixed-bottom';
    }

    if ($template['sidebar']) {
        $page_classes .= (($page_classes == '') ? '' : ' ') . $template['sidebar'];
    }

    if ($template['main_style'] == 'style-alt') {
        $page_classes .= (($page_classes == '') ? '' : ' ') . 'style-alt';
    }

    if ($template['footer'] == 'footer-fixed') {
        $page_classes .= (($page_classes == '') ? '' : ' ') . 'footer-fixed';
    }

    if (!$template['menu_scroll']) {
        $page_classes .= (($page_classes == '') ? '' : ' ') . 'disable-menu-autoscroll';
    }

    if ($template['cookies'] === 'enable-cookies') {
        $page_classes .= (($page_classes == '') ? '' : ' ') . 'enable-cookies';
    }
    ?>
    <div id="page-container" <?php if ($page_classes) {
        echo ' class="' . $page_classes . '"';
    } ?>>
        <!-- Alternative Sidebar -->
        <div id="sidebar-alt">
            <!-- Wrapper for scrolling functionality -->
            <div id="sidebar-alt-scroll">
                <!-- Sidebar Content -->
                <div class="sidebar-content">
                    <!-- Chat -->
                    <!-- Chat demo functionality initialized in js/app.js -> chatUi() -->
                    <a href="<?=asset('/page_ready_chat')?>" class="sidebar-title">
                        <i class="gi gi-comments pull-right"></i> <strong>Chat</strong>UI
                    </a>
                    <!-- Chat Users -->
                    <ul class="chat-users clearfix">
                        <li>
                            <a href="javascript:void(0)" class="chat-user-online">
                                <span></span>
                                <img src="<?= asset('/img/placeholders/avatars/avatar12.jpg') ?>" alt="avatar"
                                     class="img-circle">
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="chat-user-online">
                                <span></span>
                                <img src="<?= asset('/img/placeholders/avatars/avatar15.jpg') ?>" alt="avatar"
                                     class="img-circle">
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="chat-user-online">
                                <span></span>
                                <img src="<?= asset('/img/placeholders/avatars/avatar10.jpg') ?>" alt="avatar"
                                     class="img-circle">
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="chat-user-online">
                                <span></span>
                                <img src="<?= asset('/img/placeholders/avatars/avatar4.jpg') ?>" alt="avatar"
                                     class="img-circle">
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="chat-user-away">
                                <span></span>
                                <img src="<?= asset('/img/placeholders/avatars/avatar7.jpg') ?>" alt="avatar"
                                     class="img-circle">
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="chat-user-away">
                                <span></span>
                                <img src="<?= asset('/img/placeholders/avatars/avatar9.jpg') ?>" alt="avatar"
                                     class="img-circle">
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="chat-user-busy">
                                <span></span>
                                <img src="<?= asset('/img/placeholders/avatars/avatar16.jpg') ?>" alt="avatar"
                                     class="img-circle">
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <span></span>
                                <img src="<?= asset('/img/placeholders/avatars/avatar1.jpg') ?>" alt="avatar"
                                     class="img-circle">
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <span></span>
                                <img src="<?= asset('/img/placeholders/avatars/avatar4.jpg') ?>" alt="avatar"
                                     class="img-circle">
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <span></span>
                                <img src="<?= asset('/img/placeholders/avatars/avatar3.jpg') ?>" alt="avatar"
                                     class="img-circle">
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <span></span>
                                <img src="<?= asset('/img/placeholders/avatars/avatar13.jpg') ?>" alt="avatar"
                                     class="img-circle">
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <span></span>
                                <img src="<?= asset('/img/placeholders/avatars/avatar5.jpg') ?>" alt="avatar"
                                     class="img-circle">
                            </a>
                        </li>
                    </ul>
                    <!-- END Chat Users -->

                    <!-- Chat Talk -->
                    <div class="chat-talk display-none">
                        <!-- Chat Info -->
                        <div class="chat-talk-info sidebar-section">
                            <button id="chat-talk-close-btn" class="btn btn-xs btn-default pull-right">
                                <i class="fa fa-times"></i>
                            </button>
                            <img src="<?= asset('/img/placeholders/avatars/avatar5.jpg') ?>" alt="avatar"
                                 class="img-circle pull-left">
                            <strong>John</strong> Doe
                        </div>
                        <!-- END Chat Info -->

                        <!-- Chat Messages -->
                        <ul class="chat-talk-messages">
                            <li class="text-center"><small>Yesterday, 18:35</small></li>
                            <li class="chat-talk-msg animation-slideRight">Hey admin?</li>
                            <li class="chat-talk-msg animation-slideRight">How are you?</li>
                            <li class="text-center"><small>Today, 7:10</small></li>
                            <li class="chat-talk-msg chat-talk-msg-highlight themed-border animation-slideLeft">I'm
                                fine, thanks!
                            </li>
                        </ul>
                        <!-- END Chat Messages -->

                        <!-- Chat Input -->
                        <form action="index.php" method="post" id="sidebar-chat-form" class="chat-form">
                            <input type="text" id="sidebar-chat-message" name="sidebar-chat-message"
                                   class="form-control form-control-borderless" placeholder="Type a message..">
                        </form>
                        <!-- END Chat Input -->
                    </div>
                    <!--  END Chat Talk -->
                    <!-- END Chat -->

                    <!-- Activity -->
                    <a href="javascript:void(0)" class="sidebar-title">
                        <i class="fa fa-globe pull-right"></i> <strong>Activity</strong>UI
                    </a>
                    <div class="sidebar-section">
                        <div class="alert alert-danger alert-alt">
                            <small>just now</small><br>
                            <i class="fa fa-thumbs-up fa-fw"></i> Upgraded to Pro plan
                        </div>
                        <div class="alert alert-info alert-alt">
                            <small>2 hours ago</small><br>
                            <i class="gi gi-coins fa-fw"></i> You had a new sale!
                        </div>
                        <div class="alert alert-success alert-alt">
                            <small>3 hours ago</small><br>
                            <i class="fa fa-plus fa-fw"></i> <a href="page_ready_user_profile.php"><strong>John
                                    Doe</strong></a> would like to become friends!<br>
                            <a href="javascript:void(0)" class="btn btn-xs btn-primary"><i class="fa fa-check"></i>
                                Accept</a>
                            <a href="javascript:void(0)" class="btn btn-xs btn-default"><i class="fa fa-times"></i>
                                Ignore</a>
                        </div>
                        <div class="alert alert-warning alert-alt">
                            <small>2 days ago</small><br>
                            Running low on space<br><strong>18GB in use</strong> 2GB left<br>
                            <a href="page_ready_pricing_tables.php" class="btn btn-xs btn-primary"><i
                                        class="fa fa-arrow-up"></i> Upgrade Plan</a>
                        </div>
                    </div>
                    <!-- END Activity -->

                    <!-- Messages -->
                    <a href="page_ready_inbox.php" class="sidebar-title">
                        <i class="fa fa-envelope pull-right"></i> <strong>Messages</strong>UI (5)
                    </a>
                    <div class="sidebar-section">
                        <div class="alert alert-alt">
                            Debra Stanley<small class="pull-right">just now</small><br>
                            <a href="page_ready_inbox_message.php"><strong>New Follower</strong></a>
                        </div>
                        <div class="alert alert-alt">
                            Sarah Cole<small class="pull-right">2 min ago</small><br>
                            <a href="page_ready_inbox_message.php"><strong>Your subscription was updated</strong></a>
                        </div>
                        <div class="alert alert-alt">
                            Bryan Porter<small class="pull-right">10 min ago</small><br>
                            <a href="page_ready_inbox_message.php"><strong>A great opportunity</strong></a>
                        </div>
                        <div class="alert alert-alt">
                            Jose Duncan<small class="pull-right">30 min ago</small><br>
                            <a href="page_ready_inbox_message.php"><strong>Account Activation</strong></a>
                        </div>
                        <div class="alert alert-alt">
                            Henry Ellis<small class="pull-right">40 min ago</small><br>
                            <a href="page_ready_inbox_message.php"><strong>You reached 10.000 Followers!</strong></a>
                        </div>
                    </div>
                    <!-- END Messages -->
                </div>
                <!-- END Sidebar Content -->
            </div>
            <!-- END Wrapper for scrolling functionality -->
        </div>
        <!-- END Alternative Sidebar -->

        <!-- Main Sidebar -->
        <div id="sidebar">
            <!-- Wrapper for scrolling functionality -->
            <div id="sidebar-scroll">
                <!-- Sidebar Content -->
                <div class="sidebar-content">
                    <!-- Brand -->

                    <?php
                    if ($_SESSION['hris_role'] == "Processor") {
                        ?>
                        <select id="selected_company" class="form-control"
                                style="background: #394263;border-color: #394263;color: #fff;font-size: 14px;">
                            <?php
                            $pid = $_SESSION['hris_id'];
                            $get_companies_handled = mysqli_query($db, "SELECT * FROM tbl_users WHERE ID = '$pid'");
                            $r_companies = mysqli_fetch_assoc($get_companies_handled);
                            foreach ($r_companies as $v) {
                                echo '<option ' . $v['id'] === $_SESSION['hris_company_id'] ? 'selected' : '' . 'value="' . $v['ID'] . '">' . $r_companies($v['company_name']) . '</option>';
                            }
                            ?>
                        </select>
                        <?php
                    } else {
                        ?>
                        <a href="index.php" class="sidebar-brand" style="line-height: normal;height:auto;padding:10px;">
                            <i class="gi gi-flash"></i><span
                                    class="sidebar-nav-mini-hide"><strong><?= get_company($_SESSION['hris_company_id']) ?></strong></span>
                        </a>
                        <?php
                    }
                    ?>
                    <!-- END Brand -->

                    <!-- User Info -->
                    <div class="sidebar-section clearfix sidebar-nav-mini-hide">
                        <!-- <div class="sidebar-user-avatar">
                            <a href="page_ready_user_profile.php">
                                <img src="img/placeholders/avatars/avatar2.jpg" alt="avatar">
                            </a>
                        </div> -->
                        <div class="sidebar-user-name"><?= $_SESSION['hris_account_name'] ?></div>
                        <div class="sidebar-user-links">
                            <a href="my-account" data-toggle="tooltip" data-placement="bottom" title="My Account"><i
                                        class="gi gi-user"></i></a>
                            <a href="logout" data-toggle="tooltip" data-placement="bottom" title="Logout"><i
                                        class="gi gi-exit"></i></a>
                        </div>
                    </div>
                    <!-- END User Info -->

                    <!-- Theme Colors -->
                    <!-- Change Color Theme functionality can be found in js/app.js - templateOptions() -->
                    <ul class="sidebar-section sidebar-themes clearfix sidebar-nav-mini-hide">
                        <!-- You can also add the default color theme
                        <li class="active">
                            <a href="javascript:void(0)" class="themed-background-dark-default themed-border-default" data-theme="default" data-toggle="tooltip" title="Default Blue"></a>
                        </li>
                        -->
                        <li>
                            <a href="javascript:void(0)" class="themed-background-dark-night themed-border-night"
                               data-theme="css/themes/night.css" data-toggle="tooltip" title="Night"></a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="themed-background-dark-amethyst themed-border-amethyst"
                               data-theme="css/themes/amethyst.css" data-toggle="tooltip" title="Amethyst"></a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="themed-background-dark-modern themed-border-modern"
                               data-theme="css/themes/modern.css" data-toggle="tooltip" title="Modern"></a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="themed-background-dark-autumn themed-border-autumn"
                               data-theme="css/themes/autumn.css" data-toggle="tooltip" title="Autumn"></a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="themed-background-dark-flatie themed-border-flatie"
                               data-theme="css/themes/flatie.css" data-toggle="tooltip" title="Flatie"></a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="themed-background-dark-spring themed-border-spring"
                               data-theme="css/themes/spring.css" data-toggle="tooltip" title="Spring"></a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="themed-background-dark-fancy themed-border-fancy"
                               data-theme="css/themes/fancy.css" data-toggle="tooltip" title="Fancy"></a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="themed-background-dark-fire themed-border-fire"
                               data-theme="css/themes/fire.css" data-toggle="tooltip" title="Fire"></a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="themed-background-dark-coral themed-border-coral"
                               data-theme="css/themes/coral.css" data-toggle="tooltip" title="Coral"></a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="themed-background-dark-lake themed-border-lake"
                               data-theme="css/themes/lake.css" data-toggle="tooltip" title="Lake"></a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="themed-background-dark-forest themed-border-forest"
                               data-theme="css/themes/forest.css" data-toggle="tooltip" title="Forest"></a>
                        </li>
                        <li>
                            <a href="javascript:void(0)"
                               class="themed-background-dark-waterlily themed-border-waterlily"
                               data-theme="css/themes/waterlily.css" data-toggle="tooltip" title="Waterlily"></a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="themed-background-dark-emerald themed-border-emerald"
                               data-theme="css/themes/emerald.css" data-toggle="tooltip" title="Emerald"></a>
                        </li>
                        <li>
                            <a href="javascript:void(0)"
                               class="themed-background-dark-blackberry themed-border-blackberry"
                               data-theme="css/themes/blackberry.css" data-toggle="tooltip" title="Blackberry"></a>
                        </li>
                    </ul>
                    <!-- END Theme Colors -->
                    <?php
                    $userid = $_SESSION['hris_id'];
                    $userrole = $_SESSION['hris_role'];

                    ?>
                    <ul class="sidebar-nav">
                        <?php
                        if ($userrole != "Processor") {
                            ?>
                            <li>
                                <a href="index"><i class="gi gi-stopwatch sidebar-nav-icon"></i><span
                                            class="sidebar-nav-mini-hide">Dashboard</span></a>
                            </li>
                            <?php
                        }
                        if ($_SESSION['pending_task'] == '1') {
                            $badge = '';
                            if (isset($_SESSION['hris_employee_number'])) {
                                $badge = '<span class="badge badge-danger badge-pill">' . get_all_request_count() . '</span>';
                            } else {
                                $badge = '<span class="badge badge-danger badge-pill">' . get_all_request_count_processor() . '</span>';
                            }
                            ?>
<!--                            <li>-->
<!--                                <a href="pending-tasks"><i class="gi gi-list sidebar-nav-icon"></i><span-->
<!--                                            class="sidebar-nav-mini-hide">Pending Tasks --><!--</span></a>-->
<!--                            </li>-->
                            <?php
                        }
                        if ($userrole != "Processor") {
                            if ($_SESSION['file201'] == '1') {
                                ?>
                                <li>
                                    <a href="#" class="sidebar-nav-menu"><i
                                                class="fa fa-angle-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i
                                                class="gi gi-folder_open sidebar-nav-icon"></i><span
                                                class="sidebar-nav-mini-hide">201 File</span></a>
                                    <ul>
                                        <li>
                                            <a href="<?= asset('/employee') ?>">Employee List</a>
                                        </li>
                                        <li>
                                            <a href="<?= asset('/employee/create') ?>">Onboarding</a>
                                        </li>
                                        <li>
                                            <a href="#">Offboarding</a>
                                        </li>
                                    </ul>
                                </li>
                                <?php
                            }
                        }
                        ?>
                        <li>
                            <a href="<?=asset('/payroll')?>">
                                <i
                                        class="gi gi-folder_open sidebar-nav-icon"></i><span
                                        class="sidebar-nav-mini-hide">Payroll</span></a>
                        </li>
                        <?php
                        if ($userrole != "Processor") {
                            ?>
                            <li>
                                <a href="#" class="sidebar-nav-menu"><i
                                            class="fa fa-angle-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i
                                            class="fa fa-user-times sidebar-nav-icon"></i><span
                                            class="sidebar-nav-mini-hide">Leave Management</span></a>
                                <ul>
                                    <li>
                                        <a href="<?= asset('/leave-application') ?>">Leave Application</a>
                                    </li>
                                    <li>
                                        <a href="<?= asset('/leave-list') ?>">Leave Application List</a>
                                    </li>
                                    <?php
                                    if ($userrole != 'User' or $userrole != "Processor") {
                                        ?>
                                        <li>
                                            <a href="<?= asset('/leave-balances') ?>">Leave Balances</a>
                                        </li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                            </li>
                            <?php
                        }
//                        if ($userrole != "Processor") {
//                            ?>
<!--                            <li>-->
<!--                                <a href="#" class="sidebar-nav-menu"><i-->
<!--                                            class="fa fa-angle-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i-->
<!--                                            class="fa fa-hourglass sidebar-nav-icon"></i><span-->
<!--                                            class="sidebar-nav-mini-hide">OT Management</span></a>-->
<!--                                <ul>-->
<!--                                    <li>-->
<!--                                        <a href="--><?//= asset('/ot-application') ?><!--">OT Application</a>-->
<!--                                    </li>-->
<!--                                    <li>-->
<!--                                        <a href="--><?//= asset('/ot-list') ?><!--">OT Application List</a>-->
<!--                                    </li>-->
<!--                                </ul>-->
<!--                            </li>-->
<!--                            --><?php
//                        }
//                        ?>
                        <li>
                            <a href="#" class="sidebar-nav-menu"><i
                                        class="fa fa-angle-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i
                                        class="fa fa-file sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Certificate Requests</span></a>
                            <ul>
                                <?php
                                if ($userrole != "Processor") {
                                    ?>
                                    <li>
                                        <a href="<?= asset('/certificate-request') ?>">Request Certificate</a>
                                    </li>
                                    <?php
                                }
                                ?>
                                <li>
                                    <a href="<?= asset('/certificate-request-list') ?>">Certificate Request List</a>
                                </li>
                                <?php
                                if ($userrole != 'User') {
                                    if ($userrole != "Processor") {
                                        ?>
                                        <li>
                                            <a href="<?= asset('/certificate-request-approvers') ?>">Certificate Request
                                                Approvers</a>
                                        </li>
                                        <?php
                                    }
                                }
                                ?>
                            </ul>
                        </li>
<!--                        <li>-->
<!--                            <a href="#" class="sidebar-nav-menu"><i-->
<!--                                        class="fa fa-angle-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i-->
<!--                                        class="fa fa-money sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Salary Loan Management</span></a>-->
<!--                            <ul>-->
<!--                                --><?php
//                                if ($userrole != "Processor") {
//                                    if ($userrole != "Admin") {
//                                        ?>
<!--                                        <li>-->
<!--                                            <a href="--><?//= asset('/loan-application') ?><!--">Loan Application</a>-->
<!--                                        </li>-->
<!--                                        --><?php
//                                    }
//                                }
//                                ?>
<!--                                <li>-->
<!--                                    <a href="--><?//= asset('/loan-application-list') ?><!--">Loan Application List</a>-->
<!--                                </li>-->
<!--                                --><?php
//                                if ($userrole != 'User') {
//                                    if ($userrole != "Processor") {
//                                        ?>
<!--                                        <li>-->
<!--                                            <a href="--><?//= asset('/loan-approvers') ?><!--">Loan Approvers</a>-->
<!--                                        </li>-->
<!--                                        --><?php
//                                    }
//                                }
//                                ?>
<!--                            </ul>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <a href="#" class="sidebar-nav-menu"><i-->
<!--                                        class="fa fa-angle-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i-->
<!--                                        class="fa fa-exchange sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Benefits Reimbursement</span></a>-->
<!--                            <ul>-->
<!--                                --><?php
//                                if ($userrole != "Processor") {
//                                    ?>
<!--                                    <li>-->
<!--                                        <a href="--><?//= asset('/reimbursement-application') ?><!--">Reimbursement-->
<!--                                            Application</a>-->
<!--                                    </li>-->
<!--                                    --><?php
//                                }
//                                ?>
<!--                                <li>-->
<!--                                    <a href="--><?//= asset('/reimbursement-list') ?><!--">Reimbursement List</a>-->
<!--                                </li>-->
<!--                                --><?php
//                                if ($userrole != 'User') {
//                                    if ($userrole != "Processor") {
//                                        ?>
<!--                                        <li>-->
<!--                                            <a href="--><?//= asset('/bond-management') ?><!--">Bond Management</a>-->
<!--                                        </li>-->
<!--                                        <li>-->
<!--                                            <a href="--><?//= asset('/car-maintenance') ?><!--">Car Maintenance</a>-->
<!--                                        </li>-->
<!--                                        <li>-->
<!--                                            <a href="--><?//= asset('/benefits-balances') ?><!--">Benefits Balances</a>-->
<!--                                        </li>-->
<!--                                        <li>-->
<!--                                            <a href="--><?//= asset('/benefits-approvers') ?><!--">Benefits Approvers</a>-->
<!--                                        </li>-->
<!--                                        --><?php
//                                    }
//                                }
//                                ?>
<!--                            </ul>-->
<!--                        </li>-->
                        <?php
                        if ($userrole != 'User') {
                            if ($userrole != "Processor") {
//                                if ($_SESSION['timekeeping'] == '1') {
//                                    ?>
<!--                                    <li>-->
<!--                                        <a href="timekeeping"><i class="fa fa-clock-o sidebar-nav-icon"></i><span-->
<!--                                                    class="sidebar-nav-mini-hide">Timekeeping</span></a>-->
<!--                                    </li>-->
<!--                                    --><?php
//                                }
                                if ($_SESSION['training'] == '1') {
                                    ?>
<!--                                    <li>-->
<!--                                        <a href="#" class="sidebar-nav-menu"><i-->
<!--                                                    class="fa fa-angle-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i-->
<!--                                                    class="fa fa-heart sidebar-nav-icon"></i><span-->
<!--                                                    class="sidebar-nav-mini-hide">Training</span></a>-->
<!--                                        <ul>-->
<!--                                            <li>-->
<!--                                                <a href="--><?//= asset('/training') ?><!--">Training List</a>-->
<!--                                            </li>-->
<!--                                            --><?php
//                                            if ($userrole != "Processor") {
//                                                ?>
<!--                                                <li>-->
<!--                                                    <a href="--><?//= asset('/initiate-training') ?><!--">Initiate Training</a>-->
<!--                                                </li>-->
<!--                                                --><?php
//                                            }
//                                            ?>
<!--                                            <li>-->
<!--                                                <a href="--><?//= asset('/training-approvers') ?><!--">Training Approvers</a>-->
<!--                                            </li>-->
<!--                                        </ul>-->
<!--                                    </li>-->
                                    <?php
                                }
                                if ($_SESSION['performance'] == '1') { ?>
                                    <!-- <li>
                                        <a href="performance-management"><i class="fa fa-bar-chart sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Performance Management</span></a>
                                    </li> -->
                                    <?php
                                }
                                if ($_SESSION['holiday_maintenance'] == '1') {
                                    ?>
                                    <li>
                                        <a href="<?= asset('/holiday-maintenance') ?>"><i
                                                    class="fa fa-calendar sidebar-nav-icon"></i><span
                                                    class="sidebar-nav-mini-hide">Holiday Maintenance</span></a>
                                    </li>
                                    <?php
                                }
                                if ($_SESSION['generate_reports'] == '1') {
                                    ?>
                                    <li>
                                        <a href="<?= asset('/generate-reports') ?>"><i
                                                    class="fa fa-files-o sidebar-nav-icon"></i><span
                                                    class="sidebar-nav-mini-hide">Generate Reports</span></a>
                                    </li>
                                    <?php
                                }
                                if ($userrole == 'Admin') {
                                    ?>
                                    <li>
                                        <a href="<?= asset('/audit-trail') ?>"><i
                                                    class="fa fa-sliders sidebar-nav-icon"></i><span
                                                    class="sidebar-nav-mini-hide">Audit Trail</span></a>
                                    </li>
                                    <?php
                                }
                                ?>
                                <li>
                                    <a style="font-size: 13px;" href="#" class="sidebar-nav-menu"><i
                                                class="fa fa-angle-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i
                                                class="fa fa-users sidebar-nav-icon"></i><span
                                                class="sidebar-nav-mini-hide">Account Management</span></a>
                                    <ul>
                                        <li>
                                            <a href="<?= asset('/create-account') ?>">Create an Account</a>
                                        </li>
                                        <li>
                                            <a href="<?= asset('/account-list') ?>">Account List</a>
                                        </li>
                                    </ul>
                                </li>
                                <?php
                            }
                        }
                        ?>
                        <li>
                            <a href="<?= asset('/my-account') ?>"><i class="fa fa-user sidebar-nav-icon"></i><span
                                        class="sidebar-nav-mini-hide">My account</span></a>
                        </li>
                        <?php
                        if ($userrole == 'Admin') {
                            ?>
                            <li>
                                <a href="<?= asset('/company') ?>"><i class="fa fa-cogs sidebar-nav-icon"></i><span
                                            class="sidebar-nav-mini-hide">Company Management</span></a>
                            </li>
                            <?php
                        }
                        if ($userrole == "Site Admin") {
                            $compid = $_SESSION['hris_company_id'];
                            ?>
                            <li>
                                <a href="<?= asset('/company/' . $compid . '/edit') ?>"><i
                                            class="fa fa-cogs sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Company Management</span></a>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
        <div id="main-container">
            <header class="navbar<?php if ($template['header_navbar']) {
                echo ' ' . $template['header_navbar'];
            } ?><?php if ($template['header']) {
                echo ' ' . $template['header'];
            } ?>">
                <?php if ($template['header_content'] == 'horizontal-menu') { // Horizontal Menu Header Content
                    ?>
                    <!-- Navbar Header -->
                    <div class="navbar-header">
                        <!-- Horizontal Menu Toggle + Alternative Sidebar Toggle Button, Visible only in small screens (< 768px) -->
                        <ul class="nav navbar-nav-custom pull-right visible-xs">
                            <li>
                                <a href="javascript:void(0)" data-toggle="collapse"
                                   data-target="#horizontal-menu-collapse">Menu</a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" onclick="App.sidebar('toggle-sidebar-alt');">
                                    <i class="gi gi-share_alt"></i>
                                    <span class="label label-primary label-indicator animation-floating">4</span>
                                </a>
                            </li>
                        </ul>
                        <!-- END Horizontal Menu Toggle + Alternative Sidebar Toggle Button -->

                        <!-- Main Sidebar Toggle Button -->
                        <ul class="nav navbar-nav-custom">
                            <li>
                                <a href="javascript:void(0)" onclick="App.sidebar('toggle-sidebar');this.blur();">
                                    <i class="fa fa-bars fa-fw"></i>
                                </a>
                            </li>
                        </ul>
                        <!-- END Main Sidebar Toggle Button -->
                    </div>
                    <!-- END Navbar Header -->

                    <!-- Alternative Sidebar Toggle Button, Visible only in large screens (> 767px) -->
                    <ul class="nav navbar-nav-custom pull-right hidden-xs">
                        <li>
                            <!-- If you do not want the main sidebar to open when the alternative sidebar is closed, just remove the second parameter: App.sidebar('toggle-sidebar-alt'); -->
                            <a href="javascript:void(0)"
                               onclick="App.sidebar('toggle-sidebar-alt', 'toggle-other');this.blur();">
                                <i class="gi gi-share_alt"></i>
                                <span class="label label-primary label-indicator animation-floating">4</span>
                            </a>
                        </li>
                    </ul>
                    <!-- END Alternative Sidebar Toggle Button -->

                    <!-- Horizontal Menu + Search -->
                    <div id="horizontal-menu-collapse" class="collapse navbar-collapse">
                        <ul class="nav navbar-nav">
                            <li>
                                <a href="javascript:void(0)">Home</a>
                            </li>
                            <li>
                                <a href="javascript:void(0)">Profile</a>
                            </li>
                            <li class="dropdown">
                                <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">Settings <i
                                            class="fa fa-angle-down"></i></a>
                                <ul class="dropdown-menu">
                                    <li><a href="javascript:void(0)"><i class="fa fa-asterisk fa-fw pull-right"></i>
                                            General</a></li>
                                    <li><a href="javascript:void(0)"><i class="fa fa-lock fa-fw pull-right"></i>
                                            Security</a></li>
                                    <li><a href="javascript:void(0)"><i class="fa fa-user fa-fw pull-right"></i> Account</a>
                                    </li>
                                    <li><a href="javascript:void(0)"><i class="fa fa-magnet fa-fw pull-right"></i>
                                            Subscription</a></li>
                                    <li class="divider"></li>
                                    <li class="dropdown-submenu">
                                        <a href="javascript:void(0)" tabindex="-1"><i
                                                    class="fa fa-chevron-right fa-fw pull-right"></i> More Settings</a>
                                        <ul class="dropdown-menu">
                                            <li><a href="javascript:void(0)" tabindex="-1">Second level</a></li>
                                            <li><a href="javascript:void(0)">Second level</a></li>
                                            <li><a href="javascript:void(0)">Second level</a></li>
                                            <li class="divider"></li>
                                            <li class="dropdown-submenu">
                                                <a href="javascript:void(0)" tabindex="-1"><i
                                                            class="fa fa-chevron-right fa-fw pull-right"></i> More
                                                    Settings</a>
                                                <ul class="dropdown-menu">
                                                    <li><a href="javascript:void(0)">Third level</a></li>
                                                    <li><a href="javascript:void(0)">Third level</a></li>
                                                    <li><a href="javascript:void(0)">Third level</a></li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <li class="dropdown">
                                <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">Contact <i
                                            class="fa fa-angle-down"></i></a>
                                <ul class="dropdown-menu">
                                    <li><a href="javascript:void(0)"><i class="fa fa-envelope-o fa-fw pull-right"></i>
                                            By Email</a></li>
                                    <li><a href="javascript:void(0)"><i class="fa fa-phone fa-fw pull-right"></i> By
                                            Phone</a></li>
                                </ul>
                            </li>
                        </ul>
                        <!-- <form action="page_ready_search_results.php" class="navbar-form navbar-left">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Search..">
                            </div>
                        </form> -->
                    </div>
                    <!-- END Horizontal Menu + Search -->
                <?php } else { // Default Header Content
                    ?>
                    <!-- Left Header Navigation -->
                    <ul class="nav navbar-nav-custom">
                        <!-- Main Sidebar Toggle Button -->
                        <li>
                            <a href="javascript:void(0)" onclick="App.sidebar('toggle-sidebar');this.blur();">
                                <i class="fa fa-bars fa-fw"></i>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav-custom pull-right">

                        <!-- User Dropdown -->
                        <li class="dropdown">
                            <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="<?= asset('/img/placeholders/avatars/avatar2.jpg') ?>" alt="avatar"> <i
                                        class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-custom dropdown-menu-right">
                                <li class="dropdown-header text-center">Account</li>
                                <li>
                                    <a href="my-account">
                                        <i class="fa fa-user fa-fw pull-right"></i>
                                        My Account
                                    </a>
                                </li>
                                <li>
                                    <a href="logout"><i class="fa fa-ban fa-fw pull-right"></i> Logout</a>
                                </li>
                            </ul>
                        </li>
                        <!-- END User Dropdown -->
                    </ul>
                    <!-- END Right Header Navigation -->
                <?php } ?>
            </header>
            <!-- END Header -->
