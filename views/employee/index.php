<div id="page-content">
    <div class="content-header">
        <div class="header-section">
            <h1>
                <i class="fa fa-users"></i>Employee List
            </h1>
        </div>
    </div>
    <!-- Datatables Content -->
    <?=flash()?>
    <div class="block full">
        <div class="block-title">
            <div class="block-options pull-right">
                <a href="onboarding" class="btn btn-alt btn-sm btn-default">Onboarding</a>
            </div>
            <h2><strong>Employee</strong> List</h2>
        </div>
        <div class="table-responsive">
            <table id="employee-list" class="table table-vcenter table-condensed table-bordered">
                <thead>
                <tr>
                    <th class="text-center">ID</th>
                    <th>Employee Number</th>
                    <th>Employee Name</th>
                    <th class="text-center">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php

                foreach ($employees as $employee) {
                    ?>
                    <tr>
                        <td class="text-center"><?= $employee->ID ?></td>
                        <td><?= $employee->employee_number ?></td>
                        <td><?= $employee->account_name ?></td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a href="edit-employee?<?= md5('id') . '=' . md5($employee->ID) ?>"
                                   data-toggle="tooltip"
                                   title="Edit" class="btn btn-xs btn-default"><i class="fa fa-pencil"></i></a>
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
    <!-- END Datatables Content -->
</div>
<!-- END Page Content -->

<!-- Load and execute javascript code used only in this page -->
<script src="<?= asset('/js/pages/tablesDatatables.js') ?>"></script>
<script>
    $(function () {
        TablesDatatables.init();
    });
</script>

