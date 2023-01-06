<div id="page-content">
    <!-- eCommerce Dashboard Header -->
    <?php
        require_once 'partials/navigation.php'
    ?>

    <div class="block full">
        <div class="block-title">
            <h2><strong>Company</strong> Information</h2>
        </div>
        <?=flash()?>
        <div class="container-fluid">
            <form method="post" class="form-horizontal form-bordered" action="<?=asset('/company/' . $id . '/update')?>">
                <div class="form-group">
                    <label>Company Name</label>
                    <div class="input-group">
                        <span class="input-group-btn">
                            <button class="btn btn-primary">Update</button>
                        </span>
                        <input type="text" name="company_name" required class="form-control" placeholder="Enter Company Name..." value="<?=$company->company_name?>">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Load and execute javascript code used only in this page -->
<script src="<?=asset('/js/pages/tablesDatatables.js')?>"></script>
<script>
    $(function () {
        TablesDatatables.init();
    });
</script>
