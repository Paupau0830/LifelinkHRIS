
<div id="page-content">
    <div class="content-header">
        <div class="header-section">
            <h1>
                <i class="fa fa-users"></i>Account List
            </h1>
        </div>
    </div>
    <!-- Datatables Content -->
    <div class="block full">
        <div class="block-title">
            <div class="block-options pull-right">
                <a href="create-account" class="btn btn-alt btn-sm btn-default">Create an account</a>
            </div>
            <h2><strong>Account</strong> List</h2>
        </div>
        <div class="table-responsive">
            <table id="account-list" class="table table-vcenter table-condensed table-bordered">
                <thead>
                <tr>
                    <th class="text-center">ID</th>
                    <th>Email Address</th>
                    <th>Account Name</th>
                    <th class="text-center">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                    foreach($users as $user) {
                ?>

                    <tr>
                        <td class="text-center"><?= $user->ID ?></td>
                        <td><?= $user->email ?></td>
                        <td><?= $user->account_name ?></td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a href="<?=asset('/account/' . $user->ID . '/edit')?>" data-toggle="tooltip" title="Edit" class="btn btn-xs btn-default"><i class="fa fa-pencil"></i></a>
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
<script src="<?= asset('/js/pages/tablesDatatables.js') ?>"></script>
<script>
    $(function() {
        TablesDatatables.init();
    });
</script>
