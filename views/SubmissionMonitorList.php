<?php

namespace PHPMaker2022\civichub2;

// Page object
$SubmissionMonitorList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { submission_monitor: currentTable } });
var currentForm, currentPageID;
var fsubmission_monitorlist;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fsubmission_monitorlist = new ew.Form("fsubmission_monitorlist", "list");
    currentPageID = ew.PAGE_ID = "list";
    currentForm = fsubmission_monitorlist;
    fsubmission_monitorlist.formKeyCountName = "<?= $Page->FormKeyCountName ?>";
    loadjs.done("fsubmission_monitorlist");
});
var fsubmission_monitorsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object for search
    fsubmission_monitorsrch = new ew.Form("fsubmission_monitorsrch", "list");
    currentSearchForm = fsubmission_monitorsrch;

    // Dynamic selection lists

    // Filters
    fsubmission_monitorsrch.filterList = <?= $Page->getFilterList() ?>;
    loadjs.done("fsubmission_monitorsrch");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<?php if (!$Page->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php if ($Page->TotalRecords > 0 && $Page->ExportOptions->visible()) { ?>
<?php $Page->ExportOptions->render("body") ?>
<?php } ?>
<?php if ($Page->ImportOptions->visible()) { ?>
<?php $Page->ImportOptions->render("body") ?>
<?php } ?>
<?php if ($Page->SearchOptions->visible()) { ?>
<?php $Page->SearchOptions->render("body") ?>
<?php } ?>
<?php if ($Page->FilterOptions->visible()) { ?>
<?php $Page->FilterOptions->render("body") ?>
<?php } ?>
</div>
<?php } ?>
<?php if (!$Page->isExport() || Config("EXPORT_MASTER_RECORD") && $Page->isExport("print")) { ?>
<?php
if ($Page->DbMasterFilter != "" && $Page->getCurrentMasterTable() == "submission") {
    if ($Page->MasterRecordExists) {
        include_once "views/SubmissionMaster.php";
    }
}
?>
<?php } ?>
<?php
$Page->renderOtherOptions();
?>
<?php if ($Security->canSearch()) { ?>
<?php if (!$Page->isExport() && !$Page->CurrentAction && $Page->hasSearchFields()) { ?>
<form name="fsubmission_monitorsrch" id="fsubmission_monitorsrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="fsubmission_monitorsrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="submission_monitor">
<div class="ew-extended-search container-fluid">
<div class="row mb-0">
    <div class="col-sm-auto px-0 pe-sm-2">
        <div class="ew-basic-search input-group">
            <input type="search" name="<?= Config("TABLE_BASIC_SEARCH") ?>" id="<?= Config("TABLE_BASIC_SEARCH") ?>" class="form-control ew-basic-search-keyword" value="<?= HtmlEncode($Page->BasicSearch->getKeyword()) ?>" placeholder="<?= HtmlEncode($Language->phrase("Search")) ?>" aria-label="<?= HtmlEncode($Language->phrase("Search")) ?>">
            <input type="hidden" name="<?= Config("TABLE_BASIC_SEARCH_TYPE") ?>" id="<?= Config("TABLE_BASIC_SEARCH_TYPE") ?>" class="ew-basic-search-type" value="<?= HtmlEncode($Page->BasicSearch->getType()) ?>">
            <button type="button" data-bs-toggle="dropdown" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" aria-haspopup="true" aria-expanded="false">
                <span id="searchtype"><?= $Page->BasicSearch->getTypeNameShort() ?></span>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="fsubmission_monitorsrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="fsubmission_monitorsrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="fsubmission_monitorsrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="fsubmission_monitorsrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
            </div>
        </div>
    </div>
    <div class="col-sm-auto mb-3">
        <button class="btn btn-primary" name="btn-submit" id="btn-submit" type="submit"><?= $Language->phrase("SearchBtn") ?></button>
    </div>
</div>
</div><!-- /.ew-extended-search -->
</div><!-- /.ew-search-panel -->
</form>
<?php } ?>
<?php } ?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<?php if ($Page->TotalRecords > 0 || $Page->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> submission_monitor">
<form name="fsubmission_monitorlist" id="fsubmission_monitorlist" class="ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="submission_monitor">
<?php if ($Page->getCurrentMasterTable() == "submission" && $Page->CurrentAction) { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="submission">
<input type="hidden" name="fk_Submission_ID" value="<?= HtmlEncode($Page->Submission_ID->getSessionValue()) ?>">
<?php } ?>
<div id="gmp_submission_monitor" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_submission_monitorlist" class="table table-bordered table-hover table-sm ew-table"><!-- .ew-table -->
<thead>
    <tr class="ew-table-header">
<?php
// Header row
$Page->RowType = ROWTYPE_HEADER;

// Render list options
$Page->renderListOptions();

// Render list options (header, left)
$Page->ListOptions->render("header", "left");
?>
<?php if ($Page->Monitor_ID->Visible) { // Monitor_ID ?>
        <th data-name="Monitor_ID" class="<?= $Page->Monitor_ID->headerCellClass() ?>"><div id="elh_submission_monitor_Monitor_ID" class="submission_monitor_Monitor_ID"><?= $Page->renderFieldHeader($Page->Monitor_ID) ?></div></th>
<?php } ?>
<?php if ($Page->Status->Visible) { // Status ?>
        <th data-name="Status" class="<?= $Page->Status->headerCellClass() ?>"><div id="elh_submission_monitor_Status" class="submission_monitor_Status"><?= $Page->renderFieldHeader($Page->Status) ?></div></th>
<?php } ?>
<?php if ($Page->Taskings->Visible) { // Taskings ?>
        <th data-name="Taskings" class="<?= $Page->Taskings->headerCellClass() ?>"><div id="elh_submission_monitor_Taskings" class="submission_monitor_Taskings"><?= $Page->renderFieldHeader($Page->Taskings) ?></div></th>
<?php } ?>
<?php if ($Page->Start_Date->Visible) { // Start_Date ?>
        <th data-name="Start_Date" class="<?= $Page->Start_Date->headerCellClass() ?>"><div id="elh_submission_monitor_Start_Date" class="submission_monitor_Start_Date"><?= $Page->renderFieldHeader($Page->Start_Date) ?></div></th>
<?php } ?>
<?php if ($Page->Finish_Date->Visible) { // Finish_Date ?>
        <th data-name="Finish_Date" class="<?= $Page->Finish_Date->headerCellClass() ?>"><div id="elh_submission_monitor_Finish_Date" class="submission_monitor_Finish_Date"><?= $Page->renderFieldHeader($Page->Finish_Date) ?></div></th>
<?php } ?>
<?php if ($Page->Uploads->Visible) { // Uploads ?>
        <th data-name="Uploads" class="<?= $Page->Uploads->headerCellClass() ?>"><div id="elh_submission_monitor_Uploads" class="submission_monitor_Uploads"><?= $Page->renderFieldHeader($Page->Uploads) ?></div></th>
<?php } ?>
<?php if ($Page->Updated_Username->Visible) { // Updated_Username ?>
        <th data-name="Updated_Username" class="<?= $Page->Updated_Username->headerCellClass() ?>"><div id="elh_submission_monitor_Updated_Username" class="submission_monitor_Updated_Username"><?= $Page->renderFieldHeader($Page->Updated_Username) ?></div></th>
<?php } ?>
<?php
// Render list options (header, right)
$Page->ListOptions->render("header", "right");
?>
    </tr>
</thead>
<tbody>
<?php
if ($Page->ExportAll && $Page->isExport()) {
    $Page->StopRecord = $Page->TotalRecords;
} else {
    // Set the last record to display
    if ($Page->TotalRecords > $Page->StartRecord + $Page->DisplayRecords - 1) {
        $Page->StopRecord = $Page->StartRecord + $Page->DisplayRecords - 1;
    } else {
        $Page->StopRecord = $Page->TotalRecords;
    }
}
$Page->RecordCount = $Page->StartRecord - 1;
if ($Page->Recordset && !$Page->Recordset->EOF) {
    // Nothing to do
} elseif ($Page->isGridAdd() && !$Page->AllowAddDeleteRow && $Page->StopRecord == 0) {
    $Page->StopRecord = $Page->GridAddRowCount;
}

// Initialize aggregate
$Page->RowType = ROWTYPE_AGGREGATEINIT;
$Page->resetAttributes();
$Page->renderRow();
while ($Page->RecordCount < $Page->StopRecord) {
    $Page->RecordCount++;
    if ($Page->RecordCount >= $Page->StartRecord) {
        $Page->RowCount++;

        // Set up key count
        $Page->KeyCount = $Page->RowIndex;

        // Init row class and style
        $Page->resetAttributes();
        $Page->CssClass = "";
        if ($Page->isGridAdd()) {
            $Page->loadRowValues(); // Load default values
            $Page->OldKey = "";
            $Page->setKey($Page->OldKey);
        } else {
            $Page->loadRowValues($Page->Recordset); // Load row values
            if ($Page->isGridEdit()) {
                $Page->OldKey = $Page->getKey(true); // Get from CurrentValue
                $Page->setKey($Page->OldKey);
            }
        }
        $Page->RowType = ROWTYPE_VIEW; // Render view

        // Set up row attributes
        $Page->RowAttrs->merge([
            "data-rowindex" => $Page->RowCount,
            "id" => "r" . $Page->RowCount . "_submission_monitor",
            "data-rowtype" => $Page->RowType,
            "class" => ($Page->RowCount % 2 != 1) ? "ew-table-alt-row" : "",
        ]);
        if ($Page->isAdd() && $Page->RowType == ROWTYPE_ADD || $Page->isEdit() && $Page->RowType == ROWTYPE_EDIT) { // Inline-Add/Edit row
            $Page->RowAttrs->appendClass("table-active");
        }

        // Render row
        $Page->renderRow();

        // Render list options
        $Page->renderListOptions();
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Page->ListOptions->render("body", "left", $Page->RowCount);
?>
    <?php if ($Page->Monitor_ID->Visible) { // Monitor_ID ?>
        <td data-name="Monitor_ID"<?= $Page->Monitor_ID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_submission_monitor_Monitor_ID" class="el_submission_monitor_Monitor_ID">
<span<?= $Page->Monitor_ID->viewAttributes() ?>>
<?= $Page->Monitor_ID->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Status->Visible) { // Status ?>
        <td data-name="Status"<?= $Page->Status->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_submission_monitor_Status" class="el_submission_monitor_Status">
<span<?= $Page->Status->viewAttributes() ?>>
<?= $Page->Status->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Taskings->Visible) { // Taskings ?>
        <td data-name="Taskings"<?= $Page->Taskings->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_submission_monitor_Taskings" class="el_submission_monitor_Taskings">
<span<?= $Page->Taskings->viewAttributes() ?>>
<?= $Page->Taskings->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Start_Date->Visible) { // Start_Date ?>
        <td data-name="Start_Date"<?= $Page->Start_Date->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_submission_monitor_Start_Date" class="el_submission_monitor_Start_Date">
<span<?= $Page->Start_Date->viewAttributes() ?>>
<?= $Page->Start_Date->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Finish_Date->Visible) { // Finish_Date ?>
        <td data-name="Finish_Date"<?= $Page->Finish_Date->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_submission_monitor_Finish_Date" class="el_submission_monitor_Finish_Date">
<span<?= $Page->Finish_Date->viewAttributes() ?>>
<?= $Page->Finish_Date->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Uploads->Visible) { // Uploads ?>
        <td data-name="Uploads"<?= $Page->Uploads->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_submission_monitor_Uploads" class="el_submission_monitor_Uploads">
<span<?= $Page->Uploads->viewAttributes() ?>>
<?= GetFileViewTag($Page->Uploads, $Page->Uploads->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Updated_Username->Visible) { // Updated_Username ?>
        <td data-name="Updated_Username"<?= $Page->Updated_Username->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_submission_monitor_Updated_Username" class="el_submission_monitor_Updated_Username">
<span<?= $Page->Updated_Username->viewAttributes() ?>>
<?= $Page->Updated_Username->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Page->ListOptions->render("body", "right", $Page->RowCount);
?>
    </tr>
<?php
    }
    if (!$Page->isGridAdd()) {
        $Page->Recordset->moveNext();
    }
}
?>
</tbody>
</table><!-- /.ew-table -->
<?php } ?>
</div><!-- /.ew-grid-middle-panel -->
<?php if (!$Page->CurrentAction) { ?>
<input type="hidden" name="action" id="action" value="">
<?php } ?>
</form><!-- /.ew-list-form -->
<?php
// Close recordset
if ($Page->Recordset) {
    $Page->Recordset->close();
}
?>
<?php if (!$Page->isExport()) { ?>
<div class="card-footer ew-grid-lower-panel">
<?php if (!$Page->isGridAdd()) { ?>
<form name="ew-pager-form" class="ew-form ew-pager-form" action="<?= CurrentPageUrl(false) ?>">
<?= $Page->Pager->render() ?>
</form>
<?php } ?>
<div class="ew-list-other-options">
<?php $Page->OtherOptions->render("body", "bottom") ?>
</div>
</div>
<?php } ?>
</div><!-- /.ew-grid -->
<?php } else { ?>
<div class="ew-list-other-options">
<?php $Page->OtherOptions->render("body") ?>
</div>
<?php } ?>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php if (!$Page->isExport()) { ?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("submission_monitor");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php if (!$submission_monitor->isExport()) { ?>
<script>
loadjs.ready("jqueryjs", function() {
	var expires = new Date(new Date().getTime() + 525600 * 60 * 1000); // expire in 525600 
	var SearchToggle = $('.ew-search-toggle'); 
	var SearchPanel = $('.ew-search-panel'); 
	if (Cookies.get('submission_monitor_searchpanel')=="active") { 
		SearchToggle.addClass(Cookies.get('submission_monitor_searchpanel')); 
		SearchPanel.removeClass('collapse'); 
		SearchPanel.addClass('show'); 
		SearchToggle.addClass('active'); 
		SearchToggle.attr('aria-pressed', 'true'); 
	} else if (Cookies.get('submission_monitor_searchpanel')=="notactive") { 
		SearchPanel.removeClass('show'); 
		SearchPanel.addClass('collapse'); 
		SearchToggle.removeClass('active'); 
		SearchToggle.attr('aria-pressed', 'false'); 
	} else { 
		SearchPanel.removeClass('show'); 	
		SearchPanel.addClass('collapse'); 
		SearchToggle.removeClass('active'); 
		SearchToggle.attr('aria-pressed', 'false'); 
	} 
	SearchToggle.on('click', function(event) { 
		event.preventDefault(); 
		if (SearchToggle.hasClass('active')) { 
			SearchToggle.removeClass('active'); 
			SearchToggle.attr('aria-pressed', 'true');
			Cookies.set("submission_monitor_searchpanel", "notactive", { path: '', expires: expires }); 
		} else { 
			SearchToggle.addClass('active'); 		
			SearchToggle.attr('aria-pressed', 'false'); 
			Cookies.set("submission_monitor_searchpanel", "active", { path: '', expires: expires }); 
		} 
	});
});
</script>
<?php } ?>
<?php } ?>
