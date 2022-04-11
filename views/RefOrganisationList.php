<?php

namespace PHPMaker2022\civichub2;

// Page object
$RefOrganisationList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { ref_organisation: currentTable } });
var currentForm, currentPageID;
var fref_organisationlist;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fref_organisationlist = new ew.Form("fref_organisationlist", "list");
    currentPageID = ew.PAGE_ID = "list";
    currentForm = fref_organisationlist;
    fref_organisationlist.formKeyCountName = "<?= $Page->FormKeyCountName ?>";

    // Add fields
    var fields = currentTable.fields;
    fref_organisationlist.addFields([
        ["Organisation", [fields.Organisation.visible && fields.Organisation.required ? ew.Validators.required(fields.Organisation.caption) : null], fields.Organisation.isInvalid],
        ["Country", [fields.Country.visible && fields.Country.required ? ew.Validators.required(fields.Country.caption) : null], fields.Country.isInvalid],
        ["Organisation_Type", [fields.Organisation_Type.visible && fields.Organisation_Type.required ? ew.Validators.required(fields.Organisation_Type.caption) : null], fields.Organisation_Type.isInvalid]
    ]);

    // Check empty row
    fref_organisationlist.emptyRow = function (rowIndex) {
        var fobj = this.getForm(),
            fields = [["Organisation",false],["Country",false],["Organisation_Type",false]];
        if (fields.some(field => ew.valueChanged(fobj, rowIndex, ...field)))
            return false;
        return true;
    }

    // Form_CustomValidate
    fref_organisationlist.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fref_organisationlist.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    fref_organisationlist.lists.Country = <?= $Page->Country->toClientList($Page) ?>;
    fref_organisationlist.lists.Organisation_Type = <?= $Page->Organisation_Type->toClientList($Page) ?>;
    loadjs.done("fref_organisationlist");
});
var fref_organisationsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object for search
    fref_organisationsrch = new ew.Form("fref_organisationsrch", "list");
    currentSearchForm = fref_organisationsrch;

    // Dynamic selection lists

    // Filters
    fref_organisationsrch.filterList = <?= $Page->getFilterList() ?>;
    loadjs.done("fref_organisationsrch");
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
if ($Page->DbMasterFilter != "" && $Page->getCurrentMasterTable() == "ref_country") {
    if ($Page->MasterRecordExists) {
        include_once "views/RefCountryMaster.php";
    }
}
?>
<?php } ?>
<?php
$Page->renderOtherOptions();
?>
<?php if ($Security->canSearch()) { ?>
<?php if (!$Page->isExport() && !$Page->CurrentAction && $Page->hasSearchFields()) { ?>
<form name="fref_organisationsrch" id="fref_organisationsrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="fref_organisationsrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="ref_organisation">
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
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="fref_organisationsrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="fref_organisationsrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="fref_organisationsrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="fref_organisationsrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> ref_organisation">
<form name="fref_organisationlist" id="fref_organisationlist" class="ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="ref_organisation">
<?php if ($Page->getCurrentMasterTable() == "ref_country" && $Page->CurrentAction) { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="ref_country">
<input type="hidden" name="fk_Country" value="<?= HtmlEncode($Page->Country->getSessionValue()) ?>">
<?php } ?>
<div id="gmp_ref_organisation" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_ref_organisationlist" class="table table-bordered table-hover table-sm ew-table"><!-- .ew-table -->
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
<?php if ($Page->Organisation->Visible) { // Organisation ?>
        <th data-name="Organisation" class="<?= $Page->Organisation->headerCellClass() ?>"><div id="elh_ref_organisation_Organisation" class="ref_organisation_Organisation"><?= $Page->renderFieldHeader($Page->Organisation) ?></div></th>
<?php } ?>
<?php if ($Page->Country->Visible) { // Country ?>
        <th data-name="Country" class="<?= $Page->Country->headerCellClass() ?>"><div id="elh_ref_organisation_Country" class="ref_organisation_Country"><?= $Page->renderFieldHeader($Page->Country) ?></div></th>
<?php } ?>
<?php if ($Page->Organisation_Type->Visible) { // Organisation_Type ?>
        <th data-name="Organisation_Type" class="<?= $Page->Organisation_Type->headerCellClass() ?>"><div id="elh_ref_organisation_Organisation_Type" class="ref_organisation_Organisation_Type"><?= $Page->renderFieldHeader($Page->Organisation_Type) ?></div></th>
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

        // Set up row attributes
        $Page->RowAttrs->merge([
            "data-rowindex" => $Page->RowCount,
            "id" => "r" . $Page->RowCount . "_ref_organisation",
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
    <?php if ($Page->Organisation->Visible) { // Organisation ?>
        <td data-name="Organisation"<?= $Page->Organisation->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowCount ?>_ref_organisation_Organisation" class="el_ref_organisation_Organisation">
<input type="<?= $Page->Organisation->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_Organisation" id="x<?= $Page->RowIndex ?>_Organisation" data-table="ref_organisation" data-field="x_Organisation" value="<?= $Page->Organisation->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->Organisation->getPlaceHolder()) ?>"<?= $Page->Organisation->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Organisation->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="ref_organisation" data-field="x_Organisation" data-hidden="1" name="o<?= $Page->RowIndex ?>_Organisation" id="o<?= $Page->RowIndex ?>_Organisation" value="<?= HtmlEncode($Page->Organisation->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowCount ?>_ref_organisation_Organisation" class="el_ref_organisation_Organisation">
<span<?= $Page->Organisation->viewAttributes() ?>>
<?= $Page->Organisation->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->Country->Visible) { // Country ?>
        <td data-name="Country"<?= $Page->Country->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<?php if ($Page->Country->getSessionValue() != "") { ?>
<span id="el<?= $Page->RowCount ?>_ref_organisation_Country" class="el_ref_organisation_Country">
<span<?= $Page->Country->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Page->Country->getDisplayValue($Page->Country->ViewValue) ?></span></span>
</span>
<input type="hidden" id="x<?= $Page->RowIndex ?>_Country" name="x<?= $Page->RowIndex ?>_Country" value="<?= HtmlEncode($Page->Country->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Page->RowCount ?>_ref_organisation_Country" class="el_ref_organisation_Country">
<div class="input-group flex-nowrap">
    <select
        id="x<?= $Page->RowIndex ?>_Country"
        name="x<?= $Page->RowIndex ?>_Country"
        class="form-select ew-select<?= $Page->Country->isInvalidClass() ?>"
        data-select2-id="fref_organisationlist_x<?= $Page->RowIndex ?>_Country"
        data-table="ref_organisation"
        data-field="x_Country"
        data-value-separator="<?= $Page->Country->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->Country->getPlaceHolder()) ?>"
        <?= $Page->Country->editAttributes() ?>>
        <?= $Page->Country->selectOptionListHtml("x{$Page->RowIndex}_Country") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "ref_country") && !$Page->Country->ReadOnly) { ?>
    <button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x<?= $Page->RowIndex ?>_Country" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Page->Country->caption() ?>" data-title="<?= $Page->Country->caption() ?>" data-ew-action="add-option" data-el="x<?= $Page->RowIndex ?>_Country" data-url="<?= GetUrl("refcountryaddopt") ?>"><i class="fas fa-plus ew-icon"></i></button>
    <?php } ?>
</div>
<div class="invalid-feedback"><?= $Page->Country->getErrorMessage() ?></div>
<?= $Page->Country->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_Country") ?>
<script>
loadjs.ready("fref_organisationlist", function() {
    var options = { name: "x<?= $Page->RowIndex ?>_Country", selectId: "fref_organisationlist_x<?= $Page->RowIndex ?>_Country" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fref_organisationlist.lists.Country.lookupOptions.length) {
        options.data = { id: "x<?= $Page->RowIndex ?>_Country", form: "fref_organisationlist" };
    } else {
        options.ajax = { id: "x<?= $Page->RowIndex ?>_Country", form: "fref_organisationlist", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.ref_organisation.fields.Country.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<input type="hidden" data-table="ref_organisation" data-field="x_Country" data-hidden="1" name="o<?= $Page->RowIndex ?>_Country" id="o<?= $Page->RowIndex ?>_Country" value="<?= HtmlEncode($Page->Country->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowCount ?>_ref_organisation_Country" class="el_ref_organisation_Country">
<span<?= $Page->Country->viewAttributes() ?>>
<?= $Page->Country->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->Organisation_Type->Visible) { // Organisation_Type ?>
        <td data-name="Organisation_Type"<?= $Page->Organisation_Type->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowCount ?>_ref_organisation_Organisation_Type" class="el_ref_organisation_Organisation_Type">
<template id="tp_x<?= $Page->RowIndex ?>_Organisation_Type">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="ref_organisation" data-field="x_Organisation_Type" name="x<?= $Page->RowIndex ?>_Organisation_Type" id="x<?= $Page->RowIndex ?>_Organisation_Type"<?= $Page->Organisation_Type->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x<?= $Page->RowIndex ?>_Organisation_Type" class="ew-item-list"></div>
<selection-list hidden
    id="x<?= $Page->RowIndex ?>_Organisation_Type"
    name="x<?= $Page->RowIndex ?>_Organisation_Type"
    value="<?= HtmlEncode($Page->Organisation_Type->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x<?= $Page->RowIndex ?>_Organisation_Type"
    data-bs-target="dsl_x<?= $Page->RowIndex ?>_Organisation_Type"
    data-repeatcolumn="5"
    class="form-control<?= $Page->Organisation_Type->isInvalidClass() ?>"
    data-table="ref_organisation"
    data-field="x_Organisation_Type"
    data-value-separator="<?= $Page->Organisation_Type->displayValueSeparatorAttribute() ?>"
    <?= $Page->Organisation_Type->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->Organisation_Type->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="ref_organisation" data-field="x_Organisation_Type" data-hidden="1" name="o<?= $Page->RowIndex ?>_Organisation_Type" id="o<?= $Page->RowIndex ?>_Organisation_Type" value="<?= HtmlEncode($Page->Organisation_Type->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowCount ?>_ref_organisation_Organisation_Type" class="el_ref_organisation_Organisation_Type">
<span<?= $Page->Organisation_Type->viewAttributes() ?>>
<?= $Page->Organisation_Type->getViewValue() ?></span>
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
loadjs.ready(["fref_organisationlist","load"], () => fref_organisationlist.updateLists(<?= $Page->RowIndex ?>));
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
    $Page->RowAttrs->merge(["data-rowindex" => $Page->RowIndex, "id" => "r0_ref_organisation", "data-rowtype" => ROWTYPE_ADD]);
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
    <?php if ($Page->Organisation->Visible) { // Organisation ?>
        <td data-name="Organisation">
<span id="el$rowindex$_ref_organisation_Organisation" class="el_ref_organisation_Organisation">
<input type="<?= $Page->Organisation->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_Organisation" id="x<?= $Page->RowIndex ?>_Organisation" data-table="ref_organisation" data-field="x_Organisation" value="<?= $Page->Organisation->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->Organisation->getPlaceHolder()) ?>"<?= $Page->Organisation->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Organisation->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="ref_organisation" data-field="x_Organisation" data-hidden="1" name="o<?= $Page->RowIndex ?>_Organisation" id="o<?= $Page->RowIndex ?>_Organisation" value="<?= HtmlEncode($Page->Organisation->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->Country->Visible) { // Country ?>
        <td data-name="Country">
<?php if ($Page->Country->getSessionValue() != "") { ?>
<span id="el$rowindex$_ref_organisation_Country" class="el_ref_organisation_Country">
<span<?= $Page->Country->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Page->Country->getDisplayValue($Page->Country->ViewValue) ?></span></span>
</span>
<input type="hidden" id="x<?= $Page->RowIndex ?>_Country" name="x<?= $Page->RowIndex ?>_Country" value="<?= HtmlEncode($Page->Country->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el$rowindex$_ref_organisation_Country" class="el_ref_organisation_Country">
<div class="input-group flex-nowrap">
    <select
        id="x<?= $Page->RowIndex ?>_Country"
        name="x<?= $Page->RowIndex ?>_Country"
        class="form-select ew-select<?= $Page->Country->isInvalidClass() ?>"
        data-select2-id="fref_organisationlist_x<?= $Page->RowIndex ?>_Country"
        data-table="ref_organisation"
        data-field="x_Country"
        data-value-separator="<?= $Page->Country->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->Country->getPlaceHolder()) ?>"
        <?= $Page->Country->editAttributes() ?>>
        <?= $Page->Country->selectOptionListHtml("x{$Page->RowIndex}_Country") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "ref_country") && !$Page->Country->ReadOnly) { ?>
    <button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x<?= $Page->RowIndex ?>_Country" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Page->Country->caption() ?>" data-title="<?= $Page->Country->caption() ?>" data-ew-action="add-option" data-el="x<?= $Page->RowIndex ?>_Country" data-url="<?= GetUrl("refcountryaddopt") ?>"><i class="fas fa-plus ew-icon"></i></button>
    <?php } ?>
</div>
<div class="invalid-feedback"><?= $Page->Country->getErrorMessage() ?></div>
<?= $Page->Country->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_Country") ?>
<script>
loadjs.ready("fref_organisationlist", function() {
    var options = { name: "x<?= $Page->RowIndex ?>_Country", selectId: "fref_organisationlist_x<?= $Page->RowIndex ?>_Country" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fref_organisationlist.lists.Country.lookupOptions.length) {
        options.data = { id: "x<?= $Page->RowIndex ?>_Country", form: "fref_organisationlist" };
    } else {
        options.ajax = { id: "x<?= $Page->RowIndex ?>_Country", form: "fref_organisationlist", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.ref_organisation.fields.Country.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<input type="hidden" data-table="ref_organisation" data-field="x_Country" data-hidden="1" name="o<?= $Page->RowIndex ?>_Country" id="o<?= $Page->RowIndex ?>_Country" value="<?= HtmlEncode($Page->Country->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->Organisation_Type->Visible) { // Organisation_Type ?>
        <td data-name="Organisation_Type">
<span id="el$rowindex$_ref_organisation_Organisation_Type" class="el_ref_organisation_Organisation_Type">
<template id="tp_x<?= $Page->RowIndex ?>_Organisation_Type">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="ref_organisation" data-field="x_Organisation_Type" name="x<?= $Page->RowIndex ?>_Organisation_Type" id="x<?= $Page->RowIndex ?>_Organisation_Type"<?= $Page->Organisation_Type->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x<?= $Page->RowIndex ?>_Organisation_Type" class="ew-item-list"></div>
<selection-list hidden
    id="x<?= $Page->RowIndex ?>_Organisation_Type"
    name="x<?= $Page->RowIndex ?>_Organisation_Type"
    value="<?= HtmlEncode($Page->Organisation_Type->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x<?= $Page->RowIndex ?>_Organisation_Type"
    data-bs-target="dsl_x<?= $Page->RowIndex ?>_Organisation_Type"
    data-repeatcolumn="5"
    class="form-control<?= $Page->Organisation_Type->isInvalidClass() ?>"
    data-table="ref_organisation"
    data-field="x_Organisation_Type"
    data-value-separator="<?= $Page->Organisation_Type->displayValueSeparatorAttribute() ?>"
    <?= $Page->Organisation_Type->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->Organisation_Type->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="ref_organisation" data-field="x_Organisation_Type" data-hidden="1" name="o<?= $Page->RowIndex ?>_Organisation_Type" id="o<?= $Page->RowIndex ?>_Organisation_Type" value="<?= HtmlEncode($Page->Organisation_Type->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Page->ListOptions->render("body", "right", $Page->RowIndex);
?>
<script>
loadjs.ready(["fref_organisationlist","load"], () => fref_organisationlist.updateLists(<?= $Page->RowIndex ?>, true));
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
    ew.addEventHandlers("ref_organisation");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php if (!$ref_organisation->isExport()) { ?>
<script>
loadjs.ready("jqueryjs", function() {
	var expires = new Date(new Date().getTime() + 525600 * 60 * 1000); // expire in 525600 
	var SearchToggle = $('.ew-search-toggle'); 
	var SearchPanel = $('.ew-search-panel'); 
	if (Cookies.get('ref_organisation_searchpanel')=="active") { 
		SearchToggle.addClass(Cookies.get('ref_organisation_searchpanel')); 
		SearchPanel.removeClass('collapse'); 
		SearchPanel.addClass('show'); 
		SearchToggle.addClass('active'); 
		SearchToggle.attr('aria-pressed', 'true'); 
	} else if (Cookies.get('ref_organisation_searchpanel')=="notactive") { 
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
			Cookies.set("ref_organisation_searchpanel", "notactive", { path: '', expires: expires }); 
		} else { 
			SearchToggle.addClass('active'); 		
			SearchToggle.attr('aria-pressed', 'false'); 
			Cookies.set("ref_organisation_searchpanel", "active", { path: '', expires: expires }); 
		} 
	});
});
</script>
<?php } ?>
<?php } ?>
