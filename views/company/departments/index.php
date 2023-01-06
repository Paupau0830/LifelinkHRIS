<div id="page-content">
    <!-- eCommerce Dashboard Header -->
    <?php
    require_once 'views/company/partials/navigation.php'
    ?>

    <div class="block full">
        <div class="block-title">
            <div class="block-options pull-right">
                <a href="javascript:void(0)" class="btn btn-alt btn-sm btn-default" onclick="$('#modal-add-department').modal('show');">Add Department</a>
            </div>
            <h2><strong>Company</strong> Departments - <?= $company->company_name ?></h2>
        </div>
        <div class="container-fluid">
            <?=flash()?>
            <div class="table-responsive">
                <table id="company-departments" class="table table-vcenter table-condensed table-bordered">
                    <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Department Name</th>
                        <th>Date Created</th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        foreach($departments as $department) {
                    ?>
                        <tr>
                            <td class="text-center"><?=$department->ID?></td>
                            <td><?= $department->department ?></td>
                            <td><?= $department->date_created ?></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="javascript:void(0)"
                                       data-toggle="tooltip" title="Edit"
                                       data-action="<?=asset('/company/' . $id . '/departments/' . $department->ID . '/update')?>"
                                       data-company-id="<?= $department->company_id ?>" data-department-id="<?= $department->ID ?>" data-department="<?= $department->department ?>" class="btn btn-xs btn-default"><i class="fa fa-pencil"></i>
                                    </a>
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

<div id="modal-add-department" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h2 class="modal-title"><i class="fa fa-sitemap"></i> Add Department</h2>
            </div>
            <div class="modal-body">
                <form action="<?=asset('/company/' . $id . '/departments')?>" method="POST" enctype="multipart/form-data" class="form-horizontal form-bordered">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="text" name="department" required class="form-control" placeholder="Enter Department Name...">
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
<div id="modal-update-dept" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h2 class="modal-title"><i class="fa fa-sitemap"></i> Update Department</h2>
            </div>
            <div class="modal-body">
                <form id="updateDepartments" action="" method="POST" enctype="multipart/form-data" class="form-horizontal form-bordered">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input id="department" type="text" name="department" required class="form-control" placeholder="Enter Department Name...">
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


<!-- Load and execute javascript code used only in this page -->
<script src="<?=asset('/js/pages/tablesDatatables.js')?>"></script>
<script>
    $(function() {
        TablesDatatables.init();
    });
    $(document).ready(function() {
        setInterval(function() {
            $('*[data-department-id]').on('click', function() {
                var department = $(this).data("department");
                var action = $(this).data("action");
                $('#department').val(department);
                $('#updateDepartments').attr('action', action);
                $('#modal-update-dept').modal("show");
            });
        }, 1000);
    });
</script>
