<?php

namespace PHPMaker2022\civichub2;

// Page object
$RefSdgList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { ref_sdg: currentTable } });
var currentForm, currentPageID;
var fref_sdglist;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fref_sdglist = new ew.Form("fref_sdglist", "list");
    currentPageID = ew.PAGE_ID = "list";
    currentForm = fref_sdglist;
    fref_sdglist.formKeyCountName = "<?= $Page->FormKeyCountName ?>";

    // Add fields
    var fields = currentTable.fields;
    fref_sdglist.addFields([
        ["Goal_Number", [fields.Goal_Number.visible && fields.Goal_Number.required ? ew.Validators.required(fields.Goal_Number.caption) : null, ew.Validators.integer], fields.Goal_Number.isInvalid],
        ["Goal_Title", [fields.Goal_Title.visible && fields.Goal_Title.required ? ew.Validators.required(fields.Goal_Title.caption) : null], fields.Goal_Title.isInvalid]
    ]);

    // Check empty row
    fref_sdglist.emptyRow = function (rowIndex) {
        var fobj = this.getForm(),
            fields = [["Goal_Number",false],["Goal_Title",false]];
        if (fields.some(field => ew.valueChanged(fobj, rowIndex, ...field)))
            return false;
        return true;
    }

    // Form_CustomValidate
    fref_sdglist.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fref_sdglist.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    loadjs.done("fref_sdglist");
});
var fref_sdgsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object for search
    fref_sdgsrch = new ew.Form("fref_sdgsrch", "list");
    currentSearchForm = fref_sdgsrch;

    // Dynamic selection lists

    // Filters
    fref_sdgsrch.filterList = <?= $Page->getFilterList() ?>;
    loadjs.done("fref_sdgsrch");
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
<form name="fref_sdgsrch" id="fref_sdgsrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="fref_sdgsrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="ref_sdg">
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
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="fref_sdgsrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="fref_sdgsrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="fref_sdgsrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="fref_sdgsrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> ref_sdg">
<form name="fref_sdglist" id="fref_sdglist" class="ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="ref_sdg">
<div id="gmp_ref_sdg" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_ref_sdglist" class="table table-bordered table-hover table-sm ew-table"><!-- .ew-table -->
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
<?php if ($Page->Goal_Number->Visible) { // Goal_Number ?>
        <th data-name="Goal_Number" class="<?= $Page->Goal_Number->headerCellClass() ?>"><div id="elh_ref_sdg_Goal_Number" class="ref_sdg_Goal_Number"><?= $Page->renderFieldHeader($Page->Goal_Number) ?></div></th>
<?php } ?>
<?php if ($Page->Goal_Title->Visible) { // Goal_Title ?>
        <th data-name="Goal_Title" class="<?= $Page->Goal_Title->headerCellClass() ?>"><div id="elh_ref_sdg_Goal_Title" class="ref_sdg_Goal_Title"><?= $Page->renderFieldHeader($Page->Goal_Title) ?></div></th>
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

// Restore number of post back records
if ($CurrentForm && ($Page->isConfirm() || $Page->EventCancelled)) {
    $CurrentForm->Index = -1;
    if ($CurrentForm->hasValue($Page->FormKeyCountName) && ($Page->isGridAdd() || $Page->isGridEdit() || $Page->isConfirm())) {
        $Page->KeyCount = $CurrentForm->getValue($Page->FormKeyCountName);
        $Page->StopRecord = $Page->StartRecord + $Page->KeyCount - 1;
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
        if ($Page->isAdd() || $Page->isGridAdd() || $Page->isGridEdit() || $Page->isConfirm()) {
            $Page->RowIndex++;
            $CurrentForm->Index = $Page->RowIndex;
            if ($CurrentForm->hasValue($Page->FormActionName) && ($Page->isConfirm() || $Page->EventCancelled)) {
                $Page->RowAction = strval($CurrentForm->getValue($Page->FormActionName));
            } elseif ($Page->isGridAdd()) {
                $Page->RowAction = "insert";
            } else {
                $Page->RowAction = "";
            }
        }

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
        if ($Page->isGridAdd()) { // Grid add
            $Page->RowType = ROWTYPE_ADD; // Render add
        }
        if ($Page->isGridAdd() && $Page->EventCancelled && !$CurrentForm->hasValue("k_blankrow")) { // Insert failed
            $Page->restoreCurrentRowFormValues($Page->RowIndex); // Restore form values
        }
        if ($Page->isGridEdit()) { // Grid edit
            if ($Page->EventCancelled) {
                $Page->restoreCurrentRowFormValues($Page->RowIndex); // Restore form values
            }
            if ($Page->RowAction == "insert") {
                $Page->RowType = ROWTYPE_ADD; // Render add
            } else {
                $Page->RowType = ROWTYPE_EDIT; // Render edit
            }
        }
        if ($Page->isGridEdit() && ($Page->RowType == ROWTYPE_EDIT || $Page->RowType == ROWTYPE_ADD) && $Page->EventCancelled) { // Update failed
            $Page->restoreCurrentRowFormValues($Page->RowIndex); // Restore form values
        }
        if ($Page->RowType == ROWTYPE_EDIT) { // Edit row
            $Page->EditRowCount++;
        }

        // Set up row attributes
        $Page->RowAttrs->merge([
            "data-rowindex" => $Page->RowCount,
            "id" => "r" . $Page->RowCount . "_ref_sdg",
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

        // Skip delete row / empty row for confirm page
        if (
            $Page->RowAction != "delete" &&
            $Page->RowAction != "insertdelete" &&
            !($Page->RowAction == "insert" && $Page->isConfirm() && $Page->emptyRow())
        ) {
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Page->ListOptions->render("body", "left", $Page->RowCount);
?>
    <?php if ($Page->Goal_Number->Visible) { // Goal_Number ?>
        <td data-name="Goal_Number"<?= $Page->Goal_Number->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowCount ?>_ref_sdg_Goal_Number" class="el_ref_sdg_Goal_Number">
<input type="<?= $Page->Goal_Number->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_Goal_Number" id="x<?= $Page->RowIndex ?>_Goal_Number" data-table="ref_sdg" data-field="x_Goal_Number" value="<?= $Page->Goal_Number->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->Goal_Number->getPlaceHolder()) ?>"<?= $Page->Goal_Number->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Goal_Number->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="ref_sdg" data-field="x_Goal_Number" data-hidden="1" name="o<?= $Page->RowIndex ?>_Goal_Number" id="o<?= $Page->RowIndex ?>_Goal_Number" value="<?= HtmlEncode($Page->Goal_Number->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<input type="<?= $Page->Goal_Number->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_Goal_Number" id="x<?= $Page->RowIndex ?>_Goal_Number" data-table="ref_sdg" data-field="x_Goal_Number" value="<?= $Page->Goal_Number->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->Goal_Number->getPlaceHolder()) ?>"<?= $Page->Goal_Number->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Goal_Number->getErrorMessage() ?></div>
<input type="hidden" data-table="ref_sdg" data-field="x_Goal_Number" data-hidden="1" name="o<?= $Page->RowIndex ?>_Goal_Number" id="o<?= $Page->RowIndex ?>_Goal_Number" value="<?= HtmlEncode($Page->Goal_Number->OldValue ?? $Page->Goal_Number->CurrentValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowCount ?>_ref_sdg_Goal_Number" class="el_ref_sdg_Goal_Number">
<span<?= $Page->Goal_Number->viewAttributes() ?>>
<?= $Page->Goal_Number->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } else { ?>
            <input type="hidden" data-table="ref_sdg" data-field="x_Goal_Number" data-hidden="1" name="x<?= $Page->RowIndex ?>_Goal_Number" id="x<?= $Page->RowIndex ?>_Goal_Number" value="<?= HtmlEncode($Page->Goal_Number->CurrentValue) ?>">
    <?php } ?>
    <?php if ($Page->Goal_Title->Visible) { // Goal_Title ?>
        <td data-name="Goal_Title"<?= $Page->Goal_Title->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowCount ?>_ref_sdg_Goal_Title" class="el_ref_sdg_Goal_Title">
<input type="<?= $Page->Goal_Title->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_Goal_Title" id="x<?= $Page->RowIndex ?>_Goal_Title" data-table="ref_sdg" data-field="x_Goal_Title" value="<?= $Page->Goal_Title->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->Goal_Title->getPlaceHolder()) ?>"<?= $Page->Goal_Title->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Goal_Title->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="ref_sdg" data-field="x_Goal_Title" data-hidden="1" name="o<?= $Page->RowIndex ?>_Goal_Title" id="o<?= $Page->RowIndex ?>_Goal_Title" value="<?= HtmlEncode($Page->Goal_Title->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowCount ?>_ref_sdg_Goal_Title" class="el_ref_sdg_Goal_Title">
<input type="<?= $Page->Goal_Title->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_Goal_Title" id="x<?= $Page->RowIndex ?>_Goal_Title" data-table="ref_sdg" data-field="x_Goal_Title" value="<?= $Page->Goal_Title->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->Goal_Title->getPlaceHolder()) ?>"<?= $Page->Goal_Title->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Goal_Title->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowCount ?>_ref_sdg_Goal_Title" class="el_ref_sdg_Goal_Title">
<span<?= $Page->Goal_Title->viewAttributes() ?>>
<?= $Page->Goal_Title->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Page->ListOptions->render("body", "right", $Page->RowCount);
?>
    </tr>
<?php if ($Page->RowType == ROWTYPE_ADD || $Page->RowType == ROWTYPE_EDIT) { ?>
<script>
loadjs.ready(["fref_sdglist","load"], () => fref_sdglist.updateLists(<?= $Page->RowIndex ?>));
</script>
<?php } ?>
<?php
    }
    } // End delete row checking
    if (!$Page->isGridAdd())
        if (!$Page->Recordset->EOF) {
            $Page->Recordset->moveNext();
        }
}
?>
<?php
if ($Page->isGridAdd() || $Page->isGridEdit()) {
    $Page->RowIndex = '$rowindex$';
    $Page->loadRowValues();

    // Set row properties
    $Page->resetAttributes();
    $Page->RowAttrs->merge(["data-rowindex" => $Page->RowIndex, "id" => "r0_ref_sdg", "data-rowtype" => ROWTYPE_ADD]);
    $Page->RowAttrs->appendClass("ew-template");

    // Reset previous form error if any
    $Page->resetFormError();

    // Render row
    $Page->RowType = ROWTYPE_ADD;
    $Page->renderRow();

    // Render list options
    $Page->renderListOptions();
    $Page->StartRowCount = 0;
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Page->ListOptions->render("body", "left", $Page->RowIndex);
?>
    <?php if ($Page->Goal_Number->Visible) { // Goal_Number ?>
        <td data-name="Goal_Number">
<span id="el$rowindex$_ref_sdg_Goal_Number" class="el_ref_sdg_Goal_Number">
<input type="<?= $Page->Goal_Number->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_Goal_Number" id="x<?= $Page->RowIndex ?>_Goal_Number" data-table="ref_sdg" data-field="x_Goal_Number" value="<?= $Page->Goal_Number->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->Goal_Number->getPlaceHolder()) ?>"<?= $Page->Goal_Number->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Goal_Number->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="ref_sdg" data-field="x_Goal_Number" data-hidden="1" name="o<?= $Page->RowIndex ?>_Goal_Number" id="o<?= $Page->RowIndex ?>_Goal_Number" value="<?= HtmlEncode($Page->Goal_Number->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->Goal_Title->Visible) { // Goal_Title ?>
        <td data-name="Goal_Title">
<span id="el$rowindex$_ref_sdg_Goal_Title" class="el_ref_sdg_Goal_Title">
<input type="<?= $Page->Goal_Title->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_Goal_Title" id="x<?= $Page->RowIndex ?>_Goal_Title" data-table="ref_sdg" data-field="x_Goal_Title" value="<?= $Page->Goal_Title->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->Goal_Title->getPlaceHolder()) ?>"<?= $Page->Goal_Title->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Goal_Title->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="ref_sdg" data-field="x_Goal_Title" data-hidden="1" name="o<?= $Page->RowIndex ?>_Goal_Title" id="o<?= $Page->RowIndex ?>_Goal_Title" value="<?= HtmlEncode($Page->Goal_Title->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Page->ListOptions->render("body", "right", $Page->RowIndex);
?>
<script>
loadjs.ready(["fref_sdglist","load"], () => fref_sdglist.updateLists(<?= $Page->RowIndex ?>, true));
</script>
    </tr>
<?php
}
?>
</tbody>
</table><!-- /.ew-table -->
<?php } ?>
</div><!-- /.ew-grid-middle-panel -->
<?php if ($Page->isGridAdd()) { ?>
<input type="hidden" name="action" id="action" value="gridinsert">
<input type="hidden" name="<?= $Page->FormKeyCountName ?>" id="<?= $Page->FormKeyCountName ?>" value="<?= $Page->KeyCount ?>">
<?= $Page->MultiSelectKey ?>
<?php } ?>
<?php if ($Page->isGridEdit()) { ?>
<input type="hidden" name="action" id="action" value="gridupdate">
<input type="hidden" name="<?= $Page->FormKeyCountName ?>" id="<?= $Page->FormKeyCountName ?>" value="<?= $Page->KeyCount ?>">
<?= $Page->MultiSelectKey ?>
<?php } ?>
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
    ew.addEventHandlers("ref_sdg");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php if (!$ref_sdg->isExport()) { ?>
<script>
loadjs.ready("jqueryjs", function() {
	var expires = new Date(new Date().getTime() + 525600 * 60 * 1000); // expire in 525600 
	var SearchToggle = $('.ew-search-toggle'); 
	var SearchPanel = $('.ew-search-panel'); 
	if (Cookies.get('ref_sdg_searchpanel')=="active") { 
		SearchToggle.addClass(Cookies.get('ref_sdg_searchpanel')); 
		SearchPanel.removeClass('collapse'); 
		SearchPanel.addClass('show'); 
		SearchToggle.addClass('active'); 
		SearchToggle.attr('aria-pressed', 'true'); 
	} else if (Cookies.get('ref_sdg_searchpanel')=="notactive") { 
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
			Cookies.set("ref_sdg_searchpanel", "notactive", { path: '', expires: expires }); 
		} else { 
			SearchToggle.addClass('active'); 		
			SearchToggle.attr('aria-pressed', 'false'); 
			Cookies.set("ref_sdg_searchpanel", "active", { path: '', expires: expires }); 
		} 
	});
});
</script>
<?php } ?>
<?php } ?>
