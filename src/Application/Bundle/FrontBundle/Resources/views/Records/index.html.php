<?php if (!$isAjax): ?>
    <?php $view->extend('FOSUserBundle::layout.html.php') ?>
    <?php $view['slots']->start('body') ?>
    <div class="grid">
        <div class="row" id="recordsContainer">
        <?php endif; ?>
        <div class="span3">
            <?php echo $view->render('ApplicationFrontBundle::Records/_facets.html.php', array('facets' => $facets)) ?>
        </div>
        <div class="span11">
            <div id="div-select-all-records" style="display:none;"></div>
            <div class="clearfix"></div>
            <?php if ($view['security']->isGranted('ROLE_CATALOGER')): ?>
                <div class="button-dropdown place-left">
                    <button class="dropdown-toggle">Operations</button>
                    <ul class="dropdown-menu" data-role="dropdown">

                        <li>
                            <a href="<?php echo $view['router']->generate('record_new') ?>">Add Record</a>
                        </li>
                        <li>
                            <a class="dropdown-toggle" href="#">Export</a>
                            <ul class="dropdown-menu" data-role="dropdown">
                                <li><a href="javascript://" class="export" data-type="csv">CSV</a></li>
                                <li><a href="javascript://" class="export" data-type="xlsx">XLSX</a></li>
                            </ul>
                        </li>
                        <li>
                            <a class="dropdown-toggle" href="#">Export and Merge</a>
                            <ul class="dropdown-menu" data-role="dropdown">
                                <li><a href="javascript://" class="exportMerge" data-type="csv">CSV</a></li>
                                <li><a href="javascript://" class="exportMerge" data-type="xlsx">XLSX</a></li>
                            </ul>
                        </li>
                        <li>
                            <a class="dropdown-toggle" href="#">Import</a>
                            <ul class="dropdown-menu" data-role="dropdown">
                                <li><a href="javascript://" class="import" data-type="csv">CSV</a></li>
                                <li><a href="javascript://" class="import" data-type="xlsx">XLSX</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript://" id="bulkEdit">Bulk Edit</a>
                        </li>
                    </ul>
                </div>
                <div style="font-size: 20px; margin-left: 111px;"><a href="<?php echo $view['router']->generate('record_new') ?>"><i class="icon-plus"></i> New</a></div>
            <?php endif; ?>
            <?php echo $view->render('ApplicationFrontBundle::Records/_modal.html.php', array("organizations" => $organizations)) ?>
            <div class="table-responsive">
                <table class="table hovered bordered" id="records">
                    <thead>
                        <tr>
                            <?php
                            foreach ($columns as $column => $value) {
                                ?>
                                <?php
                                if ($column == 'checkbox_Col') {
                                    ?>
                                    <th id="<?php echo $value . '_th' ?>"><input type="checkbox" name="selectAll" id="selectAll" /></th>
                                    <?php
                                } else {
                                    ?>
                                    <th id="<?php echo $value . '_th' ?>"><?php echo str_replace('_', ' ', $column) ?></th>
                                <?php } ?>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <?php
            $recordsIds = "";
            if ($app->getSession()->has('saveRecords'))
                $recordsIds = implode(',', $app->getSession()->get('saveRecords'));
            ?>
            <input type="hidden" name="selectedrecords" id="selectedrecords" value="<?php echo $recordsIds; ?>" />
            <input type="hidden" name="exportType" id="exportType" value="" />
        </div>
        <?php $heading = null;
        $successPopupMsg = null;
        ?>
        <?php foreach ($view['session']->getFlash("report_success") as $message): ?>
            <?php $successPopupMsg = $message['message'];
            $heading = $message['heading'];
            ?>
            <?php
        endforeach;
        $app->getSession()->remove('report_success');
        ?>
        <?php $errorPopupMsg = null; ?>
        <?php foreach ($view['session']->getFlash("report_error") as $message): ?>
            <?php $errorPopupMsg = $message['message'];
            $heading = $message['heading'];
            ?>
            <?php
        endforeach;
        $app->getSession()->remove('report_error');
        ?>
<?php if (!$isAjax): ?>
    <?php $view['slots']->start('view_javascripts') ?>

            <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.js"></script>
            <script type="text/javascript" src="<?php echo $view['assets']->getUrl('js/records.js') ?>"></script>
            <script type="text/javascript" src="<?php echo $view['assets']->getUrl('js/tristate-0.9.2.js') ?>"></script>
            <script type="text/javascript" src="<?php echo $view['assets']->getUrl('js/jquery.blockUI.js') ?>"></script>
            <script type="text/javascript">
                var record = new Records();
                record.setAjaxSource('<?php echo $view['router']->generate('record_dataTable') ?>');
                record.setAjaxSaveStateUrl('<?php echo $view['router']->generate('record_saveState') ?>');
                record.initDataTable();
                record.setAjaxExportUrl('<?php echo $view['router']->generate('record_export') ?>');
                record.setPageUrl('<?php echo $view['router']->generate('record_list') ?>');
                record.setSuccessMsg('<?php echo $successPopupMsg; ?>');
                record.setErrorMsg('<?php echo $errorPopupMsg; ?>');
                record.setPopupHeading('<?php echo $heading; ?>');
                record.bindEvents();
            </script>
    <?php
    $view['slots']->stop();
    ?>
        </div>
    </div>
    <?php
    $view['slots']->stop();

endif;
?>
