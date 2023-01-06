<div class="content-header">
    <ul class="nav-horizontal text-center">
        <li class="active">
            <a href="<?=asset('/company/' . $id . '/edit')?>"><i class="fa fa-info"></i> Information</a>
        </li>
        <li>
            <a href="<?=asset('/company/' . $id . '/departments')?>"><i class="fa fa-sitemap"></i> Departments</a>
        </li>
        <li>
            <a href="<?=asset('/company/' . $id . '/job-grades')?>"><i class="fa fa-square"></i> Job Grade</a>
        </li>
        <li>
            <a href="<?=asset('/company/' . $id . '/job-grade-sets')?>"><i class="gi gi-show_thumbnails"></i> Job Grade Set</a>
        </li>
        <li>
            <a href="<?=asset('/company/' . $id . '/benefits')?>"><i class="fa fa-exchange"></i> Benefits</a>
        </li>
        <li>
            <a href="<?=asset('/company/' . $id . '/maintenance')?>"><i class="gi gi-settings"></i> Maintenance</a>
        </li>
    </ul>
</div>

<div class="row text-center">
    <div class="col-md-4">
        <a href="javascript:void(0)" class="widget widget-hover-effect2">
            <div class="widget-extra themed-background">
                <h4 class="widget-content-light"><strong>Departments</strong></h4>
            </div>
            <div class="widget-extra-full"><span class="h2 animation-expandOpen"><?= $totalDepartment ?></span></div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="javascript:void(0)" class="widget widget-hover-effect2">
            <div class="widget-extra themed-background-dark">
                <h4 class="widget-content-light"><strong>Job Grades</strong></h4>
            </div>
            <div class="widget-extra-full"><span class="h2 themed-color-dark animation-expandOpen"><?=$totalJobGrade?></span></div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="javascript:void(0)" class="widget widget-hover-effect2">
            <div class="widget-extra themed-background-dark">
                <h4 class="widget-content-light"><strong>Job Grade Sets</strong></h4>
            </div>
            <div class="widget-extra-full"><span class="h2 themed-color-dark animation-expandOpen"><?= $totalJobGradeSet ?></span></div>
        </a>
    </div>
</div>
