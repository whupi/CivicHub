<?php

namespace PHPMaker2022\civichub2;

// Page object
$UsersList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { users: currentTable } });
var currentForm, currentPageID;
var fuserslist;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fuserslist = new ew.Form("fuserslist", "list");
    currentPageID = ew.PAGE_ID = "list";
    currentForm = fuserslist;
    fuserslist.formKeyCountName = "<?= $Page->FormKeyCountName ?>";
    loadjs.done("fuserslist");
});
var fuserssrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object for search
    fuserssrch = new ew.Form("fuserssrch", "list");
    currentSearchForm = fuserssrch;

    // Dynamic selection lists

    // Filters
    fuserssrch.filterList = <?= $Page->getFilterList() ?>;
    loadjs.done("fuserssrch");
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
if ($Page->DbMasterFilter != "" && $Page->getCurrentMasterTable() == "userlevels") {
    if ($Page->MasterRecordExists) {
        include_once "views/UserlevelsMaster.php";
    }
}
?>
<?php } ?>
<?php
$Page->renderOtherOptions();
?>
<?php if ($Security->canSearch()) { ?>
<?php if (!$Page->isExport() && !$Page->CurrentAction && $Page->hasSearchFields()) { ?>
<form name="fuserssrch" id="fuserssrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="fuserssrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="users">
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
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="fuserssrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="fuserssrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="fuserssrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="fuserssrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> users">
<form name="fuserslist" id="fuserslist" class="ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="users">
<?php if ($Page->getCurrentMasterTable() == "userlevels" && $Page->CurrentAction) { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="userlevels">
<input type="hidden" name="fk_User_Level_ID" value="<?= HtmlEncode($Page->User_Level->getSessionValue()) ?>">
<?php } ?>
<div id="gmp_users" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_userslist" class="table table-bordered table-hover table-sm ew-table"><!-- .ew-table -->
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
<?php if ($Page->_Username->Visible) { // Username ?>
        <th data-name="_Username" class="<?= $Page->_Username->headerCellClass() ?>"><div id="elh_users__Username" class="users__Username"><?= $Page->renderFieldHeader($Page->_Username) ?></div></th>
<?php } ?>
<?php if ($Page->First_Name->Visible) { // First_Name ?>
        <th data-name="First_Name" class="<?= $Page->First_Name->headerCellClass() ?>"><div id="elh_users_First_Name" class="users_First_Name"><?= $Page->renderFieldHeader($Page->First_Name) ?></div></th>
<?php } ?>
<?php if ($Page->Last_Name->Visible) { // Last_Name ?>
        <th data-name="Last_Name" class="<?= $Page->Last_Name->headerCellClass() ?>"><div id="elh_users_Last_Name" class="users_Last_Name"><?= $Page->renderFieldHeader($Page->Last_Name) ?></div></th>
<?php } ?>
<?php if ($Page->_Email->Visible) { // Email ?>
        <th data-name="_Email" class="<?= $Page->_Email->headerCellClass() ?>"><div id="elh_users__Email" class="users__Email"><?= $Page->renderFieldHeader($Page->_Email) ?></div></th>
<?php } ?>
<?php if ($Page->User_Level->Visible) { // User_Level ?>
        <th data-name="User_Level" class="<?= $Page->User_Level->headerCellClass() ?>"><div id="elh_users_User_Level" class="users_User_Level"><?= $Page->renderFieldHeader($Page->User_Level) ?></div></th>
<?php } ?>
<?php if ($Page->Report_To->Visible) { // Report_To ?>
        <th data-name="Report_To" class="<?= $Page->Report_To->headerCellClass() ?>"><div id="elh_users_Report_To" class="users_Report_To"><?= $Page->renderFieldHeader($Page->Report_To) ?></div></th>
<?php } ?>
<?php if ($Page->Activated->Visible) { // Activated ?>
        <th data-name="Activated" class="<?= $Page->Activated->headerCellClass() ?>"><div id="elh_users_Activated" class="users_Activated"><?= $Page->renderFieldHeader($Page->Activated) ?></div></th>
<?php } ?>
<?php if ($Page->Locked->Visible) { // Locked ?>
        <th data-name="Locked" class="<?= $Page->Locked->headerCellClass() ?>"><div id="elh_users_Locked" class="users_Locked"><?= $Page->renderFieldHeader($Page->Locked) ?></div></th>
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
            "id" => "r" . $Page->RowCount . "_users",
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
    <?php if ($Page->_Username->Visible) { // Username ?>
        <td data-name="_Username"<?= $Page->_Username->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_users__Username" class="el_users__Username">
<span<?= $Page->_Username->viewAttributes() ?>>
<?= $Page->_Username->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->First_Name->Visible) { // First_Name ?>
        <td data-name="First_Name"<?= $Page->First_Name->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_users_First_Name" class="el_users_First_Name">
<span<?= $Page->First_Name->viewAttributes() ?>>
<?= $Page->First_Name->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Last_Name->Visible) { // Last_Name ?>
        <td data-name="Last_Name"<?= $Page->Last_Name->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_users_Last_Name" class="el_users_Last_Name">
<span<?= $Page->Last_Name->viewAttributes() ?>>
<?= $Page->Last_Name->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->_Email->Visible) { // Email ?>
        <td data-name="_Email"<?= $Page->_Email->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_users__Email" class="el_users__Email">
<span<?= $Page->_Email->viewAttributes() ?>>
<?= $Page->_Email->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->User_Level->Visible) { // User_Level ?>
        <td data-name="User_Level"<?= $Page->User_Level->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_users_User_Level" class="el_users_User_Level">
<span<?= $Page->User_Level->viewAttributes() ?>>
<?= $Page->User_Level->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Report_To->Visible) { // Report_To ?>
        <td data-name="Report_To"<?= $Page->Report_To->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_users_Report_To" class="el_users_Report_To">
<span<?= $Page->Report_To->viewAttributes() ?>>
<?= $Page->Report_To->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Activated->Visible) { // Activated ?>
        <td data-name="Activated"<?= $Page->Activated->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_users_Activated" class="el_users_Activated">
<span<?= $Page->Activated->viewAttributes() ?>>
<div class="form-check d-inline-block">
    <input type="checkbox" id="x_Activated_<?= $Page->RowCount ?>" class="form-check-input" value="<?= $Page->Activated->getViewValue() ?>" disabled<?php if (ConvertToBool($Page->Activated->CurrentValue)) { ?> checked<?php } ?>>
    <label class="form-check-label" for="x_Activated_<?= $Page->RowCount ?>"></label>
</div></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Locked->Visible) { // Locked ?>
        <td data-name="Locked"<?= $Page->Locked->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_users_Locked" class="el_users_Locked">
<span<?= $Page->Locked->viewAttributes() ?>>
<div class="form-check d-inline-block">
    <input type="checkbox" id="x_Locked_<?= $Page->RowCount ?>" class="form-check-input" value="<?= $Page->Locked->getViewValue() ?>" disabled<?php if (ConvertToBool($Page->Locked->CurrentValue)) { ?> checked<?php } ?>>
    <label class="form-check-label" for="x_Locked_<?= $Page->RowCount ?>"></label>
</div></span>
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
    ew.addEventHandlers("users");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php if (!$users->isExport()) { ?>
<script>
loadjs.ready("jqueryjs", function() {
	var expires = new Date(new Date().getTime() + 525600 * 60 * 1000); // expire in 525600 
	var SearchToggle = $('.ew-search-toggle'); 
	var SearchPanel = $('.ew-search-panel'); 
	if (Cookies.get('users_searchpanel')=="active") { 
		SearchToggle.addClass(Cookies.get('users_searchpanel')); 
		SearchPanel.removeClass('collapse'); 
		SearchPanel.addClass('show'); 
		SearchToggle.addClass('active'); 
		SearchToggle.attr('aria-pressed', 'true'); 
	} else if (Cookies.get('users_searchpanel')=="notactive") { 
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
			Cookies.set("users_searchpanel", "notactive", { path: '', expires: expires }); 
		} else { 
			SearchToggle.addClass('active'); 		
			SearchToggle.attr('aria-pressed', 'false'); 
			Cookies.set("users_searchpanel", "active", { path: '', expires: expires }); 
		} 
	});
});
</script>
<?php } ?>
<?php } ?>
