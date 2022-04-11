<?php

namespace PHPMaker2022\civichub2;

// Set up and run Grid object
$Grid = Container("RefOrganisationGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var fref_organisationgrid;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fref_organisationgrid = new ew.Form("fref_organisationgrid", "grid");
    fref_organisationgrid.formKeyCountName = "<?= $Grid->FormKeyCountName ?>";

    // Add fields
    var currentTable = <?= JsonEncode($Grid->toClientVar()) ?>;
    ew.deepAssign(ew.vars, { tables: { ref_organisation: currentTable } });
    var fields = currentTable.fields;
    fref_organisationgrid.addFields([
        ["Organisation", [fields.Organisation.visible && fields.Organisation.required ? ew.Validators.required(fields.Organisation.caption) : null], fields.Organisation.isInvalid],
        ["Country", [fields.Country.visible && fields.Country.required ? ew.Validators.required(fields.Country.caption) : null], fields.Country.isInvalid],
        ["Organisation_Type", [fields.Organisation_Type.visible && fields.Organisation_Type.required ? ew.Validators.required(fields.Organisation_Type.caption) : null], fields.Organisation_Type.isInvalid]
    ]);

    // Check empty row
    fref_organisationgrid.emptyRow = function (rowIndex) {
        var fobj = this.getForm(),
            fields = [["Organisation",false],["Country",false],["Organisation_Type",false]];
        if (fields.some(field => ew.valueChanged(fobj, rowIndex, ...field)))
            return false;
        return true;
    }

    // Form_CustomValidate
    fref_organisationgrid.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fref_organisationgrid.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    fref_organisationgrid.lists.Country = <?= $Grid->Country->toClientList($Grid) ?>;
    fref_organisationgrid.lists.Organisation_Type = <?= $Grid->Organisation_Type->toClientList($Grid) ?>;
    loadjs.done("fref_organisationgrid");
});
</script>
<?php } ?>
<?php
$Grid->renderOtherOptions();
?>
<?php if ($Grid->TotalRecords > 0 || $Grid->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($Grid->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> ref_organisation">
<div id="fref_organisationgrid" class="ew-form ew-list-form">
<div id="gmp_ref_organisation" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table id="tbl_ref_organisationgrid" class="table table-bordered table-hover table-sm ew-table"><!-- .ew-table -->
<thead>
    <tr class="ew-table-header">
<?php
// Header row
$Grid->RowType = ROWTYPE_HEADER;

// Render list options
$Grid->renderListOptions();

// Render list options (header, left)
$Grid->ListOptions->render("header", "left");
?>
<?php if ($Grid->Organisation->Visible) { // Organisation ?>
        <th data-name="Organisation" class="<?= $Grid->Organisation->headerCellClass() ?>"><div id="elh_ref_organisation_Organisation" class="ref_organisation_Organisation"><?= $Grid->renderFieldHeader($Grid->Organisation) ?></div></th>
<?php } ?>
<?php if ($Grid->Country->Visible) { // Country ?>
        <th data-name="Country" class="<?= $Grid->Country->headerCellClass() ?>"><div id="elh_ref_organisation_Country" class="ref_organisation_Country"><?= $Grid->renderFieldHeader($Grid->Country) ?></div></th>
<?php } ?>
<?php if ($Grid->Organisation_Type->Visible) { // Organisation_Type ?>
        <th data-name="Organisation_Type" class="<?= $Grid->Organisation_Type->headerCellClass() ?>"><div id="elh_ref_organisation_Organisation_Type" class="ref_organisation_Organisation_Type"><?= $Grid->renderFieldHeader($Grid->Organisation_Type) ?></div></th>
<?php } ?>
<?php
// Render list options (header, right)
$Grid->ListOptions->render("header", "right");
?>
    </tr>
</thead>
<tbody>
<?php
$Grid->StartRecord = 1;
$Grid->StopRecord = $Grid->TotalRecords; // Show all records

// Restore number of post back records
if ($CurrentForm && ($Grid->isConfirm() || $Grid->EventCancelled)) {
    $CurrentForm->Index = -1;
    if ($CurrentForm->hasValue($Grid->FormKeyCountName) && ($Grid->isGridAdd() || $Grid->isGridEdit() || $Grid->isConfirm())) {
        $Grid->KeyCount = $CurrentForm->getValue($Grid->FormKeyCountName);
        $Grid->StopRecord = $Grid->StartRecord + $Grid->KeyCount - 1;
    }
}
$Grid->RecordCount = $Grid->StartRecord - 1;
if ($Grid->Recordset && !$Grid->Recordset->EOF) {
    // Nothing to do
} elseif ($Grid->isGridAdd() && !$Grid->AllowAddDeleteRow && $Grid->StopRecord == 0) {
    $Grid->StopRecord = $Grid->GridAddRowCount;
}

// Initialize aggregate
$Grid->RowType = ROWTYPE_AGGREGATEINIT;
$Grid->resetAttributes();
$Grid->renderRow();
while ($Grid->RecordCount < $Grid->StopRecord) {
    $Grid->RecordCount++;
    if ($Grid->RecordCount >= $Grid->StartRecord) {
        $Grid->RowCount++;
        if ($Grid->isAdd() || $Grid->isGridAdd() || $Grid->isGridEdit() || $Grid->isConfirm()) {
            $Grid->RowIndex++;
            $CurrentForm->Index = $Grid->RowIndex;
            if ($CurrentForm->hasValue($Grid->FormActionName) && ($Grid->isConfirm() || $Grid->EventCancelled)) {
                $Grid->RowAction = strval($CurrentForm->getValue($Grid->FormActionName));
            } elseif ($Grid->isGridAdd()) {
                $Grid->RowAction = "insert";
            } else {
                $Grid->RowAction = "";
            }
        }

        // Set up key count
        $Grid->KeyCount = $Grid->RowIndex;

        // Init row class and style
        $Grid->resetAttributes();
        $Grid->CssClass = "";
        if ($Grid->isGridAdd()) {
            if ($Grid->CurrentMode == "copy") {
                $Grid->loadRowValues($Grid->Recordset); // Load row values
                $Grid->OldKey = $Grid->getKey(true); // Get from CurrentValue
            } else {
                $Grid->loadRowValues(); // Load default values
                $Grid->OldKey = "";
            }
        } else {
            $Grid->loadRowValues($Grid->Recordset); // Load row values
            $Grid->OldKey = $Grid->getKey(true); // Get from CurrentValue
        }
        $Grid->setKey($Grid->OldKey);
        $Grid->RowType = ROWTYPE_VIEW; // Render view
        if ($Grid->isGridAdd()) { // Grid add
            $Grid->RowType = ROWTYPE_ADD; // Render add
        }
        if ($Grid->isGridAdd() && $Grid->EventCancelled && !$CurrentForm->hasValue("k_blankrow")) { // Insert failed
            $Grid->restoreCurrentRowFormValues($Grid->RowIndex); // Restore form values
        }
        if ($Grid->isGridEdit()) { // Grid edit
            if ($Grid->EventCancelled) {
                $Grid->restoreCurrentRowFormValues($Grid->RowIndex); // Restore form values
            }
            if ($Grid->RowAction == "insert") {
                $Grid->RowType = ROWTYPE_ADD; // Render add
            } else {
                $Grid->RowType = ROWTYPE_EDIT; // Render edit
            }
        }
        if ($Grid->isGridEdit() && ($Grid->RowType == ROWTYPE_EDIT || $Grid->RowType == ROWTYPE_ADD) && $Grid->EventCancelled) { // Update failed
            $Grid->restoreCurrentRowFormValues($Grid->RowIndex); // Restore form values
        }
        if ($Grid->RowType == ROWTYPE_EDIT) { // Edit row
            $Grid->EditRowCount++;
        }
        if ($Grid->isConfirm()) { // Confirm row
            $Grid->restoreCurrentRowFormValues($Grid->RowIndex); // Restore form values
        }

        // Set up row attributes
        $Grid->RowAttrs->merge([
            "data-rowindex" => $Grid->RowCount,
            "id" => "r" . $Grid->RowCount . "_ref_organisation",
            "data-rowtype" => $Grid->RowType,
            "class" => ($Grid->RowCount % 2 != 1) ? "ew-table-alt-row" : "",
        ]);
        if ($Grid->isAdd() && $Grid->RowType == ROWTYPE_ADD || $Grid->isEdit() && $Grid->RowType == ROWTYPE_EDIT) { // Inline-Add/Edit row
            $Grid->RowAttrs->appendClass("table-active");
        }

        // Render row
        $Grid->renderRow();

        // Render list options
        $Grid->renderListOptions();

        // Skip delete row / empty row for confirm page
        if (
            $Page->RowAction != "delete" &&
            $Page->RowAction != "insertdelete" &&
            !($Page->RowAction == "insert" && $Page->isConfirm() && $Page->emptyRow())
        ) {
?>
    <tr <?= $Grid->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Grid->ListOptions->render("body", "left", $Grid->RowCount);
?>
    <?php if ($Grid->Organisation->Visible) { // Organisation ?>
        <td data-name="Organisation"<?= $Grid->Organisation->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_ref_organisation_Organisation" class="el_ref_organisation_Organisation">
<input type="<?= $Grid->Organisation->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_Organisation" id="x<?= $Grid->RowIndex ?>_Organisation" data-table="ref_organisation" data-field="x_Organisation" value="<?= $Grid->Organisation->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Grid->Organisation->getPlaceHolder()) ?>"<?= $Grid->Organisation->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->Organisation->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="ref_organisation" data-field="x_Organisation" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Organisation" id="o<?= $Grid->RowIndex ?>_Organisation" value="<?= HtmlEncode($Grid->Organisation->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_ref_organisation_Organisation" class="el_ref_organisation_Organisation">
<input type="<?= $Grid->Organisation->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_Organisation" id="x<?= $Grid->RowIndex ?>_Organisation" data-table="ref_organisation" data-field="x_Organisation" value="<?= $Grid->Organisation->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Grid->Organisation->getPlaceHolder()) ?>"<?= $Grid->Organisation->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->Organisation->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_ref_organisation_Organisation" class="el_ref_organisation_Organisation">
<span<?= $Grid->Organisation->viewAttributes() ?>>
<?= $Grid->Organisation->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="ref_organisation" data-field="x_Organisation" data-hidden="1" name="fref_organisationgrid$x<?= $Grid->RowIndex ?>_Organisation" id="fref_organisationgrid$x<?= $Grid->RowIndex ?>_Organisation" value="<?= HtmlEncode($Grid->Organisation->FormValue) ?>">
<input type="hidden" data-table="ref_organisation" data-field="x_Organisation" data-hidden="1" name="fref_organisationgrid$o<?= $Grid->RowIndex ?>_Organisation" id="fref_organisationgrid$o<?= $Grid->RowIndex ?>_Organisation" value="<?= HtmlEncode($Grid->Organisation->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->Country->Visible) { // Country ?>
        <td data-name="Country"<?= $Grid->Country->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<?php if ($Grid->Country->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowCount ?>_ref_organisation_Country" class="el_ref_organisation_Country">
<span<?= $Grid->Country->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Grid->Country->getDisplayValue($Grid->Country->ViewValue) ?></span></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_Country" name="x<?= $Grid->RowIndex ?>_Country" value="<?= HtmlEncode($Grid->Country->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_ref_organisation_Country" class="el_ref_organisation_Country">
<div class="input-group flex-nowrap">
    <select
        id="x<?= $Grid->RowIndex ?>_Country"
        name="x<?= $Grid->RowIndex ?>_Country"
        class="form-select ew-select<?= $Grid->Country->isInvalidClass() ?>"
        data-select2-id="fref_organisationgrid_x<?= $Grid->RowIndex ?>_Country"
        data-table="ref_organisation"
        data-field="x_Country"
        data-value-separator="<?= $Grid->Country->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->Country->getPlaceHolder()) ?>"
        <?= $Grid->Country->editAttributes() ?>>
        <?= $Grid->Country->selectOptionListHtml("x{$Grid->RowIndex}_Country") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "ref_country") && !$Grid->Country->ReadOnly) { ?>
    <button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x<?= $Grid->RowIndex ?>_Country" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Grid->Country->caption() ?>" data-title="<?= $Grid->Country->caption() ?>" data-ew-action="add-option" data-el="x<?= $Grid->RowIndex ?>_Country" data-url="<?= GetUrl("refcountryaddopt") ?>"><i class="fas fa-plus ew-icon"></i></button>
    <?php } ?>
</div>
<div class="invalid-feedback"><?= $Grid->Country->getErrorMessage() ?></div>
<?= $Grid->Country->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_Country") ?>
<script>
loadjs.ready("fref_organisationgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_Country", selectId: "fref_organisationgrid_x<?= $Grid->RowIndex ?>_Country" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fref_organisationgrid.lists.Country.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_Country", form: "fref_organisationgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_Country", form: "fref_organisationgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.ref_organisation.fields.Country.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<input type="hidden" data-table="ref_organisation" data-field="x_Country" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Country" id="o<?= $Grid->RowIndex ?>_Country" value="<?= HtmlEncode($Grid->Country->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<?php if ($Grid->Country->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowCount ?>_ref_organisation_Country" class="el_ref_organisation_Country">
<span<?= $Grid->Country->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Grid->Country->getDisplayValue($Grid->Country->ViewValue) ?></span></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_Country" name="x<?= $Grid->RowIndex ?>_Country" value="<?= HtmlEncode($Grid->Country->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_ref_organisation_Country" class="el_ref_organisation_Country">
<div class="input-group flex-nowrap">
    <select
        id="x<?= $Grid->RowIndex ?>_Country"
        name="x<?= $Grid->RowIndex ?>_Country"
        class="form-select ew-select<?= $Grid->Country->isInvalidClass() ?>"
        data-select2-id="fref_organisationgrid_x<?= $Grid->RowIndex ?>_Country"
        data-table="ref_organisation"
        data-field="x_Country"
        data-value-separator="<?= $Grid->Country->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->Country->getPlaceHolder()) ?>"
        <?= $Grid->Country->editAttributes() ?>>
        <?= $Grid->Country->selectOptionListHtml("x{$Grid->RowIndex}_Country") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "ref_country") && !$Grid->Country->ReadOnly) { ?>
    <button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x<?= $Grid->RowIndex ?>_Country" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Grid->Country->caption() ?>" data-title="<?= $Grid->Country->caption() ?>" data-ew-action="add-option" data-el="x<?= $Grid->RowIndex ?>_Country" data-url="<?= GetUrl("refcountryaddopt") ?>"><i class="fas fa-plus ew-icon"></i></button>
    <?php } ?>
</div>
<div class="invalid-feedback"><?= $Grid->Country->getErrorMessage() ?></div>
<?= $Grid->Country->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_Country") ?>
<script>
loadjs.ready("fref_organisationgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_Country", selectId: "fref_organisationgrid_x<?= $Grid->RowIndex ?>_Country" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fref_organisationgrid.lists.Country.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_Country", form: "fref_organisationgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_Country", form: "fref_organisationgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.ref_organisation.fields.Country.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_ref_organisation_Country" class="el_ref_organisation_Country">
<span<?= $Grid->Country->viewAttributes() ?>>
<?= $Grid->Country->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="ref_organisation" data-field="x_Country" data-hidden="1" name="fref_organisationgrid$x<?= $Grid->RowIndex ?>_Country" id="fref_organisationgrid$x<?= $Grid->RowIndex ?>_Country" value="<?= HtmlEncode($Grid->Country->FormValue) ?>">
<input type="hidden" data-table="ref_organisation" data-field="x_Country" data-hidden="1" name="fref_organisationgrid$o<?= $Grid->RowIndex ?>_Country" id="fref_organisationgrid$o<?= $Grid->RowIndex ?>_Country" value="<?= HtmlEncode($Grid->Country->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->Organisation_Type->Visible) { // Organisation_Type ?>
        <td data-name="Organisation_Type"<?= $Grid->Organisation_Type->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_ref_organisation_Organisation_Type" class="el_ref_organisation_Organisation_Type">
<template id="tp_x<?= $Grid->RowIndex ?>_Organisation_Type">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="ref_organisation" data-field="x_Organisation_Type" name="x<?= $Grid->RowIndex ?>_Organisation_Type" id="x<?= $Grid->RowIndex ?>_Organisation_Type"<?= $Grid->Organisation_Type->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x<?= $Grid->RowIndex ?>_Organisation_Type" class="ew-item-list"></div>
<selection-list hidden
    id="x<?= $Grid->RowIndex ?>_Organisation_Type"
    name="x<?= $Grid->RowIndex ?>_Organisation_Type"
    value="<?= HtmlEncode($Grid->Organisation_Type->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x<?= $Grid->RowIndex ?>_Organisation_Type"
    data-bs-target="dsl_x<?= $Grid->RowIndex ?>_Organisation_Type"
    data-repeatcolumn="5"
    class="form-control<?= $Grid->Organisation_Type->isInvalidClass() ?>"
    data-table="ref_organisation"
    data-field="x_Organisation_Type"
    data-value-separator="<?= $Grid->Organisation_Type->displayValueSeparatorAttribute() ?>"
    <?= $Grid->Organisation_Type->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Grid->Organisation_Type->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="ref_organisation" data-field="x_Organisation_Type" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Organisation_Type" id="o<?= $Grid->RowIndex ?>_Organisation_Type" value="<?= HtmlEncode($Grid->Organisation_Type->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_ref_organisation_Organisation_Type" class="el_ref_organisation_Organisation_Type">
<template id="tp_x<?= $Grid->RowIndex ?>_Organisation_Type">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="ref_organisation" data-field="x_Organisation_Type" name="x<?= $Grid->RowIndex ?>_Organisation_Type" id="x<?= $Grid->RowIndex ?>_Organisation_Type"<?= $Grid->Organisation_Type->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x<?= $Grid->RowIndex ?>_Organisation_Type" class="ew-item-list"></div>
<selection-list hidden
    id="x<?= $Grid->RowIndex ?>_Organisation_Type"
    name="x<?= $Grid->RowIndex ?>_Organisation_Type"
    value="<?= HtmlEncode($Grid->Organisation_Type->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x<?= $Grid->RowIndex ?>_Organisation_Type"
    data-bs-target="dsl_x<?= $Grid->RowIndex ?>_Organisation_Type"
    data-repeatcolumn="5"
    class="form-control<?= $Grid->Organisation_Type->isInvalidClass() ?>"
    data-table="ref_organisation"
    data-field="x_Organisation_Type"
    data-value-separator="<?= $Grid->Organisation_Type->displayValueSeparatorAttribute() ?>"
    <?= $Grid->Organisation_Type->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Grid->Organisation_Type->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_ref_organisation_Organisation_Type" class="el_ref_organisation_Organisation_Type">
<span<?= $Grid->Organisation_Type->viewAttributes() ?>>
<?= $Grid->Organisation_Type->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="ref_organisation" data-field="x_Organisation_Type" data-hidden="1" name="fref_organisationgrid$x<?= $Grid->RowIndex ?>_Organisation_Type" id="fref_organisationgrid$x<?= $Grid->RowIndex ?>_Organisation_Type" value="<?= HtmlEncode($Grid->Organisation_Type->FormValue) ?>">
<input type="hidden" data-table="ref_organisation" data-field="x_Organisation_Type" data-hidden="1" name="fref_organisationgrid$o<?= $Grid->RowIndex ?>_Organisation_Type" id="fref_organisationgrid$o<?= $Grid->RowIndex ?>_Organisation_Type" value="<?= HtmlEncode($Grid->Organisation_Type->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowCount);
?>
    </tr>
<?php if ($Grid->RowType == ROWTYPE_ADD || $Grid->RowType == ROWTYPE_EDIT) { ?>
<script>
loadjs.ready(["fref_organisationgrid","load"], () => fref_organisationgrid.updateLists(<?= $Grid->RowIndex ?>));
</script>
<?php } ?>
<?php
    }
    } // End delete row checking
    if (!$Grid->isGridAdd() || $Grid->CurrentMode == "copy")
        if (!$Grid->Recordset->EOF) {
            $Grid->Recordset->moveNext();
        }
}
?>
<?php
if ($Grid->CurrentMode == "add" || $Grid->CurrentMode == "copy" || $Grid->CurrentMode == "edit") {
    $Grid->RowIndex = '$rowindex$';
    $Grid->loadRowValues();

    // Set row properties
    $Grid->resetAttributes();
    $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowIndex, "id" => "r0_ref_organisation", "data-rowtype" => ROWTYPE_ADD]);
    $Grid->RowAttrs->appendClass("ew-template");

    // Reset previous form error if any
    $Grid->resetFormError();

    // Render row
    $Grid->RowType = ROWTYPE_ADD;
    $Grid->renderRow();

    // Render list options
    $Grid->renderListOptions();
    $Grid->StartRowCount = 0;
?>
    <tr <?= $Grid->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Grid->ListOptions->render("body", "left", $Grid->RowIndex);
?>
    <?php if ($Grid->Organisation->Visible) { // Organisation ?>
        <td data-name="Organisation">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_ref_organisation_Organisation" class="el_ref_organisation_Organisation">
<input type="<?= $Grid->Organisation->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_Organisation" id="x<?= $Grid->RowIndex ?>_Organisation" data-table="ref_organisation" data-field="x_Organisation" value="<?= $Grid->Organisation->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Grid->Organisation->getPlaceHolder()) ?>"<?= $Grid->Organisation->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->Organisation->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_ref_organisation_Organisation" class="el_ref_organisation_Organisation">
<span<?= $Grid->Organisation->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->Organisation->getDisplayValue($Grid->Organisation->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="ref_organisation" data-field="x_Organisation" data-hidden="1" name="x<?= $Grid->RowIndex ?>_Organisation" id="x<?= $Grid->RowIndex ?>_Organisation" value="<?= HtmlEncode($Grid->Organisation->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="ref_organisation" data-field="x_Organisation" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Organisation" id="o<?= $Grid->RowIndex ?>_Organisation" value="<?= HtmlEncode($Grid->Organisation->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->Country->Visible) { // Country ?>
        <td data-name="Country">
<?php if (!$Grid->isConfirm()) { ?>
<?php if ($Grid->Country->getSessionValue() != "") { ?>
<span id="el$rowindex$_ref_organisation_Country" class="el_ref_organisation_Country">
<span<?= $Grid->Country->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Grid->Country->getDisplayValue($Grid->Country->ViewValue) ?></span></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_Country" name="x<?= $Grid->RowIndex ?>_Country" value="<?= HtmlEncode($Grid->Country->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el$rowindex$_ref_organisation_Country" class="el_ref_organisation_Country">
<div class="input-group flex-nowrap">
    <select
        id="x<?= $Grid->RowIndex ?>_Country"
        name="x<?= $Grid->RowIndex ?>_Country"
        class="form-select ew-select<?= $Grid->Country->isInvalidClass() ?>"
        data-select2-id="fref_organisationgrid_x<?= $Grid->RowIndex ?>_Country"
        data-table="ref_organisation"
        data-field="x_Country"
        data-value-separator="<?= $Grid->Country->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->Country->getPlaceHolder()) ?>"
        <?= $Grid->Country->editAttributes() ?>>
        <?= $Grid->Country->selectOptionListHtml("x{$Grid->RowIndex}_Country") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "ref_country") && !$Grid->Country->ReadOnly) { ?>
    <button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x<?= $Grid->RowIndex ?>_Country" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Grid->Country->caption() ?>" data-title="<?= $Grid->Country->caption() ?>" data-ew-action="add-option" data-el="x<?= $Grid->RowIndex ?>_Country" data-url="<?= GetUrl("refcountryaddopt") ?>"><i class="fas fa-plus ew-icon"></i></button>
    <?php } ?>
</div>
<div class="invalid-feedback"><?= $Grid->Country->getErrorMessage() ?></div>
<?= $Grid->Country->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_Country") ?>
<script>
loadjs.ready("fref_organisationgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_Country", selectId: "fref_organisationgrid_x<?= $Grid->RowIndex ?>_Country" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fref_organisationgrid.lists.Country.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_Country", form: "fref_organisationgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_Country", form: "fref_organisationgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.ref_organisation.fields.Country.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_ref_organisation_Country" class="el_ref_organisation_Country">
<span<?= $Grid->Country->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Grid->Country->getDisplayValue($Grid->Country->ViewValue) ?></span></span>
</span>
<input type="hidden" data-table="ref_organisation" data-field="x_Country" data-hidden="1" name="x<?= $Grid->RowIndex ?>_Country" id="x<?= $Grid->RowIndex ?>_Country" value="<?= HtmlEncode($Grid->Country->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="ref_organisation" data-field="x_Country" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Country" id="o<?= $Grid->RowIndex ?>_Country" value="<?= HtmlEncode($Grid->Country->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->Organisation_Type->Visible) { // Organisation_Type ?>
        <td data-name="Organisation_Type">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_ref_organisation_Organisation_Type" class="el_ref_organisation_Organisation_Type">
<template id="tp_x<?= $Grid->RowIndex ?>_Organisation_Type">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="ref_organisation" data-field="x_Organisation_Type" name="x<?= $Grid->RowIndex ?>_Organisation_Type" id="x<?= $Grid->RowIndex ?>_Organisation_Type"<?= $Grid->Organisation_Type->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x<?= $Grid->RowIndex ?>_Organisation_Type" class="ew-item-list"></div>
<selection-list hidden
    id="x<?= $Grid->RowIndex ?>_Organisation_Type"
    name="x<?= $Grid->RowIndex ?>_Organisation_Type"
    value="<?= HtmlEncode($Grid->Organisation_Type->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x<?= $Grid->RowIndex ?>_Organisation_Type"
    data-bs-target="dsl_x<?= $Grid->RowIndex ?>_Organisation_Type"
    data-repeatcolumn="5"
    class="form-control<?= $Grid->Organisation_Type->isInvalidClass() ?>"
    data-table="ref_organisation"
    data-field="x_Organisation_Type"
    data-value-separator="<?= $Grid->Organisation_Type->displayValueSeparatorAttribute() ?>"
    <?= $Grid->Organisation_Type->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Grid->Organisation_Type->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_ref_organisation_Organisation_Type" class="el_ref_organisation_Organisation_Type">
<span<?= $Grid->Organisation_Type->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Grid->Organisation_Type->getDisplayValue($Grid->Organisation_Type->ViewValue) ?></span></span>
</span>
<input type="hidden" data-table="ref_organisation" data-field="x_Organisation_Type" data-hidden="1" name="x<?= $Grid->RowIndex ?>_Organisation_Type" id="x<?= $Grid->RowIndex ?>_Organisation_Type" value="<?= HtmlEncode($Grid->Organisation_Type->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="ref_organisation" data-field="x_Organisation_Type" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Organisation_Type" id="o<?= $Grid->RowIndex ?>_Organisation_Type" value="<?= HtmlEncode($Grid->Organisation_Type->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowIndex);
?>
<script>
loadjs.ready(["fref_organisationgrid","load"], () => fref_organisationgrid.updateLists(<?= $Grid->RowIndex ?>, true));
</script>
    </tr>
<?php
}
?>
</tbody>
</table><!-- /.ew-table -->
</div><!-- /.ew-grid-middle-panel -->
<?php if ($Grid->CurrentMode == "add" || $Grid->CurrentMode == "copy") { ?>
<input type="hidden" name="<?= $Grid->FormKeyCountName ?>" id="<?= $Grid->FormKeyCountName ?>" value="<?= $Grid->KeyCount ?>">
<?= $Grid->MultiSelectKey ?>
<?php } ?>
<?php if ($Grid->CurrentMode == "edit") { ?>
<input type="hidden" name="<?= $Grid->FormKeyCountName ?>" id="<?= $Grid->FormKeyCountName ?>" value="<?= $Grid->KeyCount ?>">
<?= $Grid->MultiSelectKey ?>
<?php } ?>
<?php if ($Grid->CurrentMode == "") { ?>
<input type="hidden" name="action" id="action" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fref_organisationgrid">
</div><!-- /.ew-list-form -->
<?php
// Close recordset
if ($Grid->Recordset) {
    $Grid->Recordset->close();
}
?>
<?php if ($Grid->ShowOtherOptions) { ?>
<div class="card-footer ew-grid-lower-panel">
<?php $Grid->OtherOptions->render("body", "bottom") ?>
</div>
<?php } ?>
</div><!-- /.ew-grid -->
<?php } else { ?>
<div class="ew-list-other-options">
<?php $Grid->OtherOptions->render("body") ?>
</div>
<?php } ?>
<?php if (!$Grid->isExport()) { ?>
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
