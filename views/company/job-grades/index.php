<div id="page-content">
    <!-- eCommerce Dashboard Header -->
    <?php
    require_once 'views/company/partials/navigation.php'
    ?>

    <div class="block full">
        <div class="block-title">
            <div class="block-options pull-right">
                <a href="javascript:void(0)" class="btn btn-alt btn-sm btn-default"
                   onclick="$('#modal-add-job-grade').modal('show');">Add Job Grade</a>
            </div>
            <h2><strong>Company</strong> Job Grades - <?= $company->company_name ?></h2>
        </div>
        <div class="container-fluid">
            <?= flash() ?>
            <div class="table-responsive">
                <table id="company-job-grade" class="table table-vcenter table-condensed table-bordered">
                    <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Job Grade</th>
                        <th>Date Created</th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($jobGrades as $jobGrade) {
                        ?>
                        <tr>
                            <td class="text-center"><?= $jobGrade->ID ?></td>
                            <td><?= $jobGrade->job_grade ?></td>
                            <td><?= $jobGrade->date_created ?></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="javascript:void(0)"
                                       data-toggle="tooltip" title="Edit" data-company-id="<?= $jobGrade->company_id ?>"
                                       data-job-grade-id="<?= $jobGrade->ID ?>"
                                       data-job-grade="<?= $jobGrade->job_grade ?>"
                                       data-action="<?=asset('/company/' . $id . '/job-grades/' . $jobGrade->ID . '/update')?>"
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
                <h2 class="modal-title"><i class="fa fa-square"></i> Add Job Grade</h2>
            </div>
            <div class="modal-body">
                <form method="POST" action="<?= asset('/company/' . $id . '/job-grades') ?>"
                      enctype="multipart/form-data" class="form-horizontal form-bordered">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="text" name="job_grade" required class="form-control"
                                       placeholder="Enter Job Grade...">
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
<div id="modal-update-job-grade" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="modal-job-grade-content">
            <div class="modal-content" id="modal-job-grade-content">
                <div class="modal-header text-center">
                    <h2 class="modal-title"><i class="fa fa-square"></i> Update Job Grade</h2>
                </div>
                <div class="modal-body">
                    <form id="updateJobGrade" method="POST" enctype="multipart/form-data" class="form-horizontal form-bordered">
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <input type="text" id="jobGrade" name="job_grade" required="" class="form-control"
                                           placeholder="Enter Job Grade Name..." value="abc">
                                    <span class="input-group-btn">
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
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
            $('*[data-job-grade-id]').on('click', function () {
                var jobGrade = $(this).data("job-grade");
                $('#jobGrade').val(jobGrade);
                $('#updateJobGrade').attr('action', $(this).data("action"));
                $('#modal-update-job-grade').modal("show");
            });
        }, 1000);
    });
</script>
