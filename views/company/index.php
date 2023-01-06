<!-- Page content -->
<div id="page-content">
    <!-- Datatables Header -->
    <div class="content-header">
        <div class="header-section">
            <h1>
                <i class="fa fa-building"></i>Company Management
            </h1>
        </div>
    </div>
    <!-- END Datatables Header -->
    <div class="block">
        <!-- Input Groups - Buttons Title -->
        <div class="block-title">
            <h2><strong>Add</strong> Company</h2>
            <?=flash()?>
        </div>
        <!-- END Input Groups - Buttons Title -->

        <!-- Input Groups - Buttons Content -->
        <form method="post" class="form-horizontal form-bordered" action="company">
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <span class="input-group-btn">
                            <button class="btn btn-primary">Submit</button>
                        </span>
                        <input type="text" name="company_name" required class="form-control"
                               placeholder="Enter Company Name...">
                    </div>
                </div>
            </div>
        </form>
        <!-- END Input Groups - Buttons Content -->
    </div>
    <!-- Datatables Content -->
    <div class="block full">
        <div class="block-title">
            <h2><strong>Company</strong> List</h2>
        </div>
        <div class="table-responsive">
            <table id="company-management" class="table table-vcenter table-condensed table-bordered">
                <thead>
                <tr>
                    <th class="text-center">ID</th>
                    <th>Company Name</th>
                    <th>Date Created</th>
                    <th class="text-center">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($companies as $company) { ?>
                    <tr>
                        <td class="text-center"><?= $company->ID ?></td>
                        <td><?= $company->company_name ?></td>
                        <td><?= $company->date_created ?></td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a
                                        href="company/<?=$company->ID?>/edit"
                                        data-toggle="tooltip" title="Edit"
                                        class="btn btn-xs btn-default"><i class="fa fa-pencil"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
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

