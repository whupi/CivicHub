<?php

namespace PHPMaker2022\civichub2;

// Page object
$SubmissionView2List = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { submission_view2: currentTable } });
var currentForm, currentPageID;
var fsubmission_view2list;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fsubmission_view2list = new ew.Form("fsubmission_view2list", "list");
    currentPageID = ew.PAGE_ID = "list";
    currentForm = fsubmission_view2list;
    fsubmission_view2list.formKeyCountName = "<?= $Page->FormKeyCountName ?>";
    loadjs.done("fsubmission_view2list");
});
var fsubmission_view2srch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object for search
    fsubmission_view2srch = new ew.Form("fsubmission_view2srch", "list");
    currentSearchForm = fsubmission_view2srch;

    // Add fields
    var fields = currentTable.fields;
    fsubmission_view2srch.addFields([
        ["Submission_ID", [], fields.Submission_ID.isInvalid],
        ["_Title", [], fields._Title.isInvalid],
        ["Category_ID", [], fields.Category_ID.isInvalid],
        ["Status", [], fields.Status.isInvalid],
        ["Updated_Username", [], fields.Updated_Username.isInvalid]
    ]);

    // Validate form
    fsubmission_view2srch.validate = function () {
        if (!this.validateRequired)
            return true; // Ignore validation
        var fobj = this.getForm();

        // Validate fields
        if (!this.validateFields())
            return false;

        // Call Form_CustomValidate event
        if (!this.customValidate(fobj)) {
            this.focus();
            return false;
        }
        return true;
    }

    // Form_CustomValidate
    fsubmission_view2srch.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fsubmission_view2srch.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    fsubmission_view2srch.lists.Category_ID = <?= $Page->Category_ID->toClientList($Page) ?>;
    fsubmission_view2srch.lists.Status = <?= $Page->Status->toClientList($Page) ?>;

    // Filters
    fsubmission_view2srch.filterList = <?= $Page->getFilterList() ?>;
    loadjs.done("fsubmission_view2srch");
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
<?php
$Page->renderOtherOptions();
?>
<?php if ($Security->canSearch()) { ?>
<?php if (!$Page->isExport() && !$Page->CurrentAction && $Page->hasSearchFields()) { ?>
<form name="fsubmission_view2srch" id="fsubmission_view2srch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="fsubmission_view2srch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="submission_view2">
<div class="ew-extended-search container-fluid">
<div class="row mb-0<?= ($Page->SearchFieldsPerRow > 0) ? " row-cols-sm-" . $Page->SearchFieldsPerRow : "" ?>">
<?php
// Render search row
$Page->RowType = ROWTYPE_SEARCH;
$Page->resetAttributes();
$Page->renderRow();
?>
<?php if ($Page->Category_ID->Visible) { // Category_ID ?>
<?php
if (!$Page->Category_ID->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_Category_ID" class="col-sm-auto d-sm-flex mb-3 px-0 pe-sm-2<?= $Page->Category_ID->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_Category_ID" class="ew-search-caption ew-label"><?= $Page->Category_ID->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_Category_ID" id="z_Category_ID" value="=">
</div>
        </div>
        <div id="el_submission_view2_Category_ID" class="ew-search-field">
    <select
        id="x_Category_ID"
        name="x_Category_ID"
        class="form-select ew-select<?= $Page->Category_ID->isInvalidClass() ?>"
        data-select2-id="fsubmission_view2srch_x_Category_ID"
        data-table="submission_view2"
        data-field="x_Category_ID"
        data-value-separator="<?= $Page->Category_ID->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->Category_ID->getPlaceHolder()) ?>"
        <?= $Page->Category_ID->editAttributes() ?>>
        <?= $Page->Category_ID->selectOptionListHtml("x_Category_ID") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->Category_ID->getErrorMessage(false) ?></div>
<?= $Page->Category_ID->Lookup->getParamTag($Page, "p_x_Category_ID") ?>
<script>
loadjs.ready("fsubmission_view2srch", function() {
    var options = { name: "x_Category_ID", selectId: "fsubmission_view2srch_x_Category_ID" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fsubmission_view2srch.lists.Category_ID.lookupOptions.length) {
        options.data = { id: "x_Category_ID", form: "fsubmission_view2srch" };
    } else {
        options.ajax = { id: "x_Category_ID", form: "fsubmission_view2srch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.submission_view2.fields.Category_ID.selectOptions);
    ew.createSelect(options);
});
</script>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->Status->Visible) { // Status ?>
<?php
if (!$Page->Status->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_Status" class="col-sm-auto d-sm-flex mb-3 px-0 pe-sm-2<?= $Page->Status->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_Status" class="ew-search-caption ew-label"><?= $Page->Status->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_Status" id="z_Status" value="=">
</div>
        </div>
        <div id="el_submission_view2_Status" class="ew-search-field">
    <select
        id="x_Status"
        name="x_Status"
        class="form-select ew-select<?= $Page->Status->isInvalidClass() ?>"
        data-select2-id="fsubmission_view2srch_x_Status"
        data-table="submission_view2"
        data-field="x_Status"
        data-value-separator="<?= $Page->Status->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->Status->getPlaceHolder()) ?>"
        <?= $Page->Status->editAttributes() ?>>
        <?= $Page->Status->selectOptionListHtml("x_Status") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->Status->getErrorMessage(false) ?></div>
<script>
loadjs.ready("fsubmission_view2srch", function() {
    var options = { name: "x_Status", selectId: "fsubmission_view2srch_x_Status" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fsubmission_view2srch.lists.Status.lookupOptions.length) {
        options.data = { id: "x_Status", form: "fsubmission_view2srch" };
    } else {
        options.ajax = { id: "x_Status", form: "fsubmission_view2srch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.submission_view2.fields.Status.selectOptions);
    ew.createSelect(options);
});
</script>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
</div><!-- /.row -->
<div class="row mb-0">
    <div class="col-sm-auto px-0 pe-sm-2">
        <div class="ew-basic-search input-group">
            <input type="search" name="<?= Config("TABLE_BASIC_SEARCH") ?>" id="<?= Config("TABLE_BASIC_SEARCH") ?>" class="form-control ew-basic-search-keyword" value="<?= HtmlEncode($Page->BasicSearch->getKeyword()) ?>" placeholder="<?= HtmlEncode($Language->phrase("Search")) ?>" aria-label="<?= HtmlEncode($Language->phrase("Search")) ?>">
            <input type="hidden" name="<?= Config("TABLE_BASIC_SEARCH_TYPE") ?>" id="<?= Config("TABLE_BASIC_SEARCH_TYPE") ?>" class="ew-basic-search-type" value="<?= HtmlEncode($Page->BasicSearch->getType()) ?>">
            <button type="button" data-bs-toggle="dropdown" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" aria-haspopup="true" aria-expanded="false">
                <span id="searchtype"><?= $Page->BasicSearch->getTypeNameShort() ?></span>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="fsubmission_view2srch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="fsubmission_view2srch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="fsubmission_view2srch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="fsubmission_view2srch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> submission_view2">
<form name="fsubmission_view2list" id="fsubmission_view2list" class="ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="submission_view2">
<div id="gmp_submission_view2" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_submission_view2list" class="table table-bordered table-hover table-sm ew-table"><!-- .ew-table -->
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
<?php if ($Page->Submission_ID->Visible) { // Submission_ID ?>
        <th data-name="Submission_ID" class="<?= $Page->Submission_ID->headerCellClass() ?>"><div id="elh_submission_view2_Submission_ID" class="submission_view2_Submission_ID"><?= $Page->renderFieldHeader($Page->Submission_ID) ?></div></th>
<?php } ?>
<?php if ($Page->_Title->Visible) { // Title ?>
        <th data-name="_Title" class="<?= $Page->_Title->headerCellClass() ?>"><div id="elh_submission_view2__Title" class="submission_view2__Title"><?= $Page->renderFieldHeader($Page->_Title) ?></div></th>
<?php } ?>
<?php if ($Page->Category_ID->Visible) { // Category_ID ?>
        <th data-name="Category_ID" class="<?= $Page->Category_ID->headerCellClass() ?>"><div id="elh_submission_view2_Category_ID" class="submission_view2_Category_ID"><?= $Page->renderFieldHeader($Page->Category_ID) ?></div></th>
<?php } ?>
<?php if ($Page->Status->Visible) { // Status ?>
        <th data-name="Status" class="<?= $Page->Status->headerCellClass() ?>"><div id="elh_submission_view2_Status" class="submission_view2_Status"><?= $Page->renderFieldHeader($Page->Status) ?></div></th>
<?php } ?>
<?php if ($Page->Updated_Username->Visible) { // Updated_Username ?>
        <th data-name="Updated_Username" class="<?= $Page->Updated_Username->headerCellClass() ?>"><div id="elh_submission_view2_Updated_Username" class="submission_view2_Updated_Username"><?= $Page->renderFieldHeader($Page->Updated_Username) ?></div></th>
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
            "id" => "r" . $Page->RowCount . "_submission_view2",
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
    <?php if ($Page->Submission_ID->Visible) { // Submission_ID ?>
        <td data-name="Submission_ID"<?= $Page->Submission_ID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_submission_view2_Submission_ID" class="el_submission_view2_Submission_ID">
<span<?= $Page->Submission_ID->viewAttributes() ?>>
<?= $Page->Submission_ID->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->_Title->Visible) { // Title ?>
        <td data-name="_Title"<?= $Page->_Title->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_submission_view2__Title" class="el_submission_view2__Title">
<span<?= $Page->_Title->viewAttributes() ?>>
<?= $Page->_Title->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Category_ID->Visible) { // Category_ID ?>
        <td data-name="Category_ID"<?= $Page->Category_ID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_submission_view2_Category_ID" class="el_submission_view2_Category_ID">
<span<?= $Page->Category_ID->viewAttributes() ?>>
<?= $Page->Category_ID->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Status->Visible) { // Status ?>
        <td data-name="Status"<?= $Page->Status->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_submission_view2_Status" class="el_submission_view2_Status">
<span<?= $Page->Status->viewAttributes() ?>>
<?= $Page->Status->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Updated_Username->Visible) { // Updated_Username ?>
        <td data-name="Updated_Username"<?= $Page->Updated_Username->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_submission_view2_Updated_Username" class="el_submission_view2_Updated_Username">
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
    ew.addEventHandlers("submission_view2");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php if (!$submission_view2->isExport()) { ?>
<script>
loadjs.ready("jqueryjs", function() {
	var expires = new Date(new Date().getTime() + 525600 * 60 * 1000); // expire in 525600 
	var SearchToggle = $('.ew-search-toggle'); 
	var SearchPanel = $('.ew-search-panel'); 
	if (Cookies.get('submission_view2_searchpanel')=="active") { 
		SearchToggle.addClass(Cookies.get('submission_view2_searchpanel')); 
		SearchPanel.removeClass('collapse'); 
		SearchPanel.addClass('show'); 
		SearchToggle.addClass('active'); 
		SearchToggle.attr('aria-pressed', 'true'); 
	} else if (Cookies.get('submission_view2_searchpanel')=="notactive") { 
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
			Cookies.set("submission_view2_searchpanel", "notactive", { path: '', expires: expires }); 
		} else { 
			SearchToggle.addClass('active'); 		
			SearchToggle.attr('aria-pressed', 'false'); 
			Cookies.set("submission_view2_searchpanel", "active", { path: '', expires: expires }); 
		} 
	});
});
</script>
<?php } ?>
<?php } ?>
