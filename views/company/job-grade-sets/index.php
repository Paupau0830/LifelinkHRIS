<div id="page-content">
    <!-- eCommerce Dashboard Header -->
    <?php
    require_once 'views/company/partials/navigation.php'
    ?>

    <div class="block full">
        <div class="block-title">
            <div class="block-options pull-right">
                <a href="javascript:void(0)" class="btn btn-alt btn-sm btn-default"
                   onclick="$('#modal-add-job-grade').modal('show');">Add Job Grade Set</a>
            </div>
            <h2><strong>Company</strong> Job Grade Set - <?= $company->company_name ?></h2>
        </div>
        <div class="container-fluid">
            <?= flash() ?>
            <div class="table-responsive">
                <table id="company-job-grade-set" class="table table-vcenter table-condensed table-bordered">
                    <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Job Grade Set</th>
                        <th>Date Created</th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($jobGradeSets as $jobGradeSet) {
                        ?>
                        <tr>
                            <td class="text-center"><?= $jobGradeSet->ID ?></td>
                            <td><?= $jobGradeSet->job_grade_set ?></td>
                            <td><?= $jobGradeSet->date_created ?></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a
                                            data-action="<?= asset('/company/' . $id . '/job-grade-sets/' . $jobGradeSet->ID . '/update') ?>"
                                            href="javascript:void(0)" data-toggle="tooltip" title="Edit"
                                            data-company-id="<?= $jobGradeSet->company_id ?>"
                                            data-job-grade-set-id="<?= $jobGradeSet->ID ?>"
                                            data-job-grade-set="<?= $jobGradeSet->job_grade_set ?>"
                                            class="btn btn-xs btn-default"><i class="fa fa-pencil"></i></a>
                                </div>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div id="modal-add-job-grade" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h2 class="modal-title"><i class="gi gi-show_thumbnails"></i> Add Job Grade Set</h2>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" class="form-horizontal form-bordered">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="text" name="job_grade_set" required class="form-control"
                                       placeholder="Enter Job Grade Set...">
                                <span class="input-group-btn">
                                    <button class="btn btn-primary">Submit</button>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div id="modal-update-job-grade-set" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="modal-job-grade-set-content">
            <div class="modal-header text-center">
                <h2 class="modal-title"><i class="gi gi-show_thumbnails"></i> Update Job Grade Set</h2>
            </div>
            <div class="modal-body">
                <form method="POST" id="updateJobGrade" enctype="multipart/form-data" class="form-horizontal form-bordered">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="text" name="job_grade_set" required="" class="form-control" id="jobGradeSet"
                                       placeholder="Enter Job Grade Set..." value="">
                                <span class="input-group-btn">
                            <button class="btn btn-primary">Update</button>
                        </span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Load and execute javascript code used only in this page -->
<script src="<?= asset('/js/pages/tablesDatatables.js') ?>"></script>
<script>
    $(function () {
        TablesDatatables.init();
    });
    $(document).ready(function () {
        setInterval(function () {
            $('*[data-job-grade-set-id]').on('click', function () {
                var job_grade_set = $(this).data("job-grade-set");
                $('#jobGradeSet').val(job_grade_set);
                $('#updateJobGrade').attr('action', $(this).data("action"));
                $('#modal-update-job-grade-set').modal("show");
            });
        }, 1000);
    });
</script>
