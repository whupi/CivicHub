<?php

namespace PHPMaker2022\civichub2;

// Set up and run Grid object
$Grid = Container("SubmissionGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var fsubmissiongrid;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fsubmissiongrid = new ew.Form("fsubmissiongrid", "grid");
    fsubmissiongrid.formKeyCountName = "<?= $Grid->FormKeyCountName ?>";

    // Add fields
    var currentTable = <?= JsonEncode($Grid->toClientVar()) ?>;
    ew.deepAssign(ew.vars, { tables: { submission: currentTable } });
    var fields = currentTable.fields;
    fsubmissiongrid.addFields([
        ["Submission_ID", [fields.Submission_ID.visible && fields.Submission_ID.required ? ew.Validators.required(fields.Submission_ID.caption) : null], fields.Submission_ID.isInvalid],
        ["_Title", [fields._Title.visible && fields._Title.required ? ew.Validators.required(fields._Title.caption) : null], fields._Title.isInvalid],
        ["Category", [fields.Category.visible && fields.Category.required ? ew.Validators.required(fields.Category.caption) : null], fields.Category.isInvalid],
        ["Status", [fields.Status.visible && fields.Status.required ? ew.Validators.required(fields.Status.caption) : null], fields.Status.isInvalid]
    ]);

    // Check empty row
    fsubmissiongrid.emptyRow = function (rowIndex) {
        var fobj = this.getForm(),
            fields = [["_Title",false],["Category",false],["Status",false]];
        if (fields.some(field => ew.valueChanged(fobj, rowIndex, ...field)))
            return false;
        return true;
    }

    // Form_CustomValidate
    fsubmissiongrid.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fsubmissiongrid.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    fsubmissiongrid.lists.Status = <?= $Grid->Status->toClientList($Grid) ?>;
    loadjs.done("fsubmissiongrid");
});
</script>
<?php } ?>
<?php
$Grid->renderOtherOptions();
?>
<?php if ($Grid->TotalRecords > 0 || $Grid->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($Grid->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> submission">
<div id="fsubmissiongrid" class="ew-form ew-list-form">
<div id="gmp_submission" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table id="tbl_submissiongrid" class="table table-bordered table-hover table-sm ew-table"><!-- .ew-table -->
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
<?php if ($Grid->Submission_ID->Visible) { // Submission_ID ?>
        <th data-name="Submission_ID" class="<?= $Grid->Submission_ID->headerCellClass() ?>"><div id="elh_submission_Submission_ID" class="submission_Submission_ID"><?= $Grid->renderFieldHeader($Grid->Submission_ID) ?></div></th>
<?php } ?>
<?php if ($Grid->_Title->Visible) { // Title ?>
        <th data-name="_Title" class="<?= $Grid->_Title->headerCellClass() ?>"><div id="elh_submission__Title" class="submission__Title"><?= $Grid->renderFieldHeader($Grid->_Title) ?></div></th>
<?php } ?>
<?php if ($Grid->Category->Visible) { // Category ?>
        <th data-name="Category" class="<?= $Grid->Category->headerCellClass() ?>"><div id="elh_submission_Category" class="submission_Category"><?= $Grid->renderFieldHeader($Grid->Category) ?></div></th>
<?php } ?>
<?php if ($Grid->Status->Visible) { // Status ?>
        <th data-name="Status" class="<?= $Grid->Status->headerCellClass() ?>"><div id="elh_submission_Status" class="submission_Status"><?= $Grid->renderFieldHeader($Grid->Status) ?></div></th>
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
            "id" => "r" . $Grid->RowCount . "_submission",
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
    <?php if ($Grid->Submission_ID->Visible) { // Submission_ID ?>
        <td data-name="Submission_ID"<?= $Grid->Submission_ID->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_submission_Submission_ID" class="el_submission_Submission_ID"></span>
<input type="hidden" data-table="submission" data-field="x_Submission_ID" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Submission_ID" id="o<?= $Grid->RowIndex ?>_Submission_ID" value="<?= HtmlEncode($Grid->Submission_ID->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_submission_Submission_ID" class="el_submission_Submission_ID">
<span<?= $Grid->Submission_ID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->Submission_ID->getDisplayValue($Grid->Submission_ID->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="submission" data-field="x_Submission_ID" data-hidden="1" name="x<?= $Grid->RowIndex ?>_Submission_ID" id="x<?= $Grid->RowIndex ?>_Submission_ID" value="<?= HtmlEncode($Grid->Submission_ID->CurrentValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_submission_Submission_ID" class="el_submission_Submission_ID">
<span<?= $Grid->Submission_ID->viewAttributes() ?>>
<?= $Grid->Submission_ID->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="submission" data-field="x_Submission_ID" data-hidden="1" name="fsubmissiongrid$x<?= $Grid->RowIndex ?>_Submission_ID" id="fsubmissiongrid$x<?= $Grid->RowIndex ?>_Submission_ID" value="<?= HtmlEncode($Grid->Submission_ID->FormValue) ?>">
<input type="hidden" data-table="submission" data-field="x_Submission_ID" data-hidden="1" name="fsubmissiongrid$o<?= $Grid->RowIndex ?>_Submission_ID" id="fsubmissiongrid$o<?= $Grid->RowIndex ?>_Submission_ID" value="<?= HtmlEncode($Grid->Submission_ID->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } else { ?>
            <input type="hidden" data-table="submission" data-field="x_Submission_ID" data-hidden="1" name="x<?= $Grid->RowIndex ?>_Submission_ID" id="x<?= $Grid->RowIndex ?>_Submission_ID" value="<?= HtmlEncode($Grid->Submission_ID->CurrentValue) ?>">
    <?php } ?>
    <?php if ($Grid->_Title->Visible) { // Title ?>
        <td data-name="_Title"<?= $Grid->_Title->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_submission__Title" class="el_submission__Title">
<input type="<?= $Grid->_Title->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>__Title" id="x<?= $Grid->RowIndex ?>__Title" data-table="submission" data-field="x__Title" value="<?= $Grid->_Title->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Grid->_Title->getPlaceHolder()) ?>"<?= $Grid->_Title->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->_Title->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="submission" data-field="x__Title" data-hidden="1" name="o<?= $Grid->RowIndex ?>__Title" id="o<?= $Grid->RowIndex ?>__Title" value="<?= HtmlEncode($Grid->_Title->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_submission__Title" class="el_submission__Title">
<input type="<?= $Grid->_Title->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>__Title" id="x<?= $Grid->RowIndex ?>__Title" data-table="submission" data-field="x__Title" value="<?= $Grid->_Title->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Grid->_Title->getPlaceHolder()) ?>"<?= $Grid->_Title->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->_Title->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_submission__Title" class="el_submission__Title">
<span<?= $Grid->_Title->viewAttributes() ?>>
<?php if (!EmptyString($Grid->_Title->getViewValue()) && $Grid->_Title->linkAttributes() != "") { ?>
<a<?= $Grid->_Title->linkAttributes() ?>><?= $Grid->_Title->getViewValue() ?></a>
<?php } else { ?>
<?= $Grid->_Title->getViewValue() ?>
<?php } ?>
</span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="submission" data-field="x__Title" data-hidden="1" name="fsubmissiongrid$x<?= $Grid->RowIndex ?>__Title" id="fsubmissiongrid$x<?= $Grid->RowIndex ?>__Title" value="<?= HtmlEncode($Grid->_Title->FormValue) ?>">
<input type="hidden" data-table="submission" data-field="x__Title" data-hidden="1" name="fsubmissiongrid$o<?= $Grid->RowIndex ?>__Title" id="fsubmissiongrid$o<?= $Grid->RowIndex ?>__Title" value="<?= HtmlEncode($Grid->_Title->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->Category->Visible) { // Category ?>
        <td data-name="Category"<?= $Grid->Category->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_submission_Category" class="el_submission_Category">
<input type="<?= $Grid->Category->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_Category" id="x<?= $Grid->RowIndex ?>_Category" data-table="submission" data-field="x_Category" value="<?= $Grid->Category->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Grid->Category->getPlaceHolder()) ?>"<?= $Grid->Category->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->Category->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="submission" data-field="x_Category" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Category" id="o<?= $Grid->RowIndex ?>_Category" value="<?= HtmlEncode($Grid->Category->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_submission_Category" class="el_submission_Category">
<input type="<?= $Grid->Category->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_Category" id="x<?= $Grid->RowIndex ?>_Category" data-table="submission" data-field="x_Category" value="<?= $Grid->Category->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Grid->Category->getPlaceHolder()) ?>"<?= $Grid->Category->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->Category->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_submission_Category" class="el_submission_Category">
<span<?= $Grid->Category->viewAttributes() ?>>
<?= $Grid->Category->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="submission" data-field="x_Category" data-hidden="1" name="fsubmissiongrid$x<?= $Grid->RowIndex ?>_Category" id="fsubmissiongrid$x<?= $Grid->RowIndex ?>_Category" value="<?= HtmlEncode($Grid->Category->FormValue) ?>">
<input type="hidden" data-table="submission" data-field="x_Category" data-hidden="1" name="fsubmissiongrid$o<?= $Grid->RowIndex ?>_Category" id="fsubmissiongrid$o<?= $Grid->RowIndex ?>_Category" value="<?= HtmlEncode($Grid->Category->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->Status->Visible) { // Status ?>
        <td data-name="Status"<?= $Grid->Status->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_submission_Status" class="el_submission_Status">
<template id="tp_x<?= $Grid->RowIndex ?>_Status">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="submission" data-field="x_Status" name="x<?= $Grid->RowIndex ?>_Status" id="x<?= $Grid->RowIndex ?>_Status"<?= $Grid->Status->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x<?= $Grid->RowIndex ?>_Status" class="ew-item-list"></div>
<selection-list hidden
    id="x<?= $Grid->RowIndex ?>_Status"
    name="x<?= $Grid->RowIndex ?>_Status"
    value="<?= HtmlEncode($Grid->Status->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x<?= $Grid->RowIndex ?>_Status"
    data-bs-target="dsl_x<?= $Grid->RowIndex ?>_Status"
    data-repeatcolumn="5"
    class="form-control<?= $Grid->Status->isInvalidClass() ?>"
    data-table="submission"
    data-field="x_Status"
    data-value-separator="<?= $Grid->Status->displayValueSeparatorAttribute() ?>"
    <?= $Grid->Status->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Grid->Status->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="submission" data-field="x_Status" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Status" id="o<?= $Grid->RowIndex ?>_Status" value="<?= HtmlEncode($Grid->Status->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_submission_Status" class="el_submission_Status">
<template id="tp_x<?= $Grid->RowIndex ?>_Status">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="submission" data-field="x_Status" name="x<?= $Grid->RowIndex ?>_Status" id="x<?= $Grid->RowIndex ?>_Status"<?= $Grid->Status->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x<?= $Grid->RowIndex ?>_Status" class="ew-item-list"></div>
<selection-list hidden
    id="x<?= $Grid->RowIndex ?>_Status"
    name="x<?= $Grid->RowIndex ?>_Status"
    value="<?= HtmlEncode($Grid->Status->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x<?= $Grid->RowIndex ?>_Status"
    data-bs-target="dsl_x<?= $Grid->RowIndex ?>_Status"
    data-repeatcolumn="5"
    class="form-control<?= $Grid->Status->isInvalidClass() ?>"
    data-table="submission"
    data-field="x_Status"
    data-value-separator="<?= $Grid->Status->displayValueSeparatorAttribute() ?>"
    <?= $Grid->Status->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Grid->Status->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_submission_Status" class="el_submission_Status">
<span<?= $Grid->Status->viewAttributes() ?>>
<?= $Grid->Status->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="submission" data-field="x_Status" data-hidden="1" name="fsubmissiongrid$x<?= $Grid->RowIndex ?>_Status" id="fsubmissiongrid$x<?= $Grid->RowIndex ?>_Status" value="<?= HtmlEncode($Grid->Status->FormValue) ?>">
<input type="hidden" data-table="submission" data-field="x_Status" data-hidden="1" name="fsubmissiongrid$o<?= $Grid->RowIndex ?>_Status" id="fsubmissiongrid$o<?= $Grid->RowIndex ?>_Status" value="<?= HtmlEncode($Grid->Status->OldValue) ?>">
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
loadjs.ready(["fsubmissiongrid","load"], () => fsubmissiongrid.updateLists(<?= $Grid->RowIndex ?>));
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
    $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowIndex, "id" => "r0_submission", "data-rowtype" => ROWTYPE_ADD]);
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
    <?php if ($Grid->Submission_ID->Visible) { // Submission_ID ?>
        <td data-name="Submission_ID">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_submission_Submission_ID" class="el_submission_Submission_ID"></span>
<?php } else { ?>
<span id="el$rowindex$_submission_Submission_ID" class="el_submission_Submission_ID">
<span<?= $Grid->Submission_ID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->Submission_ID->getDisplayValue($Grid->Submission_ID->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="submission" data-field="x_Submission_ID" data-hidden="1" name="x<?= $Grid->RowIndex ?>_Submission_ID" id="x<?= $Grid->RowIndex ?>_Submission_ID" value="<?= HtmlEncode($Grid->Submission_ID->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="submission" data-field="x_Submission_ID" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Submission_ID" id="o<?= $Grid->RowIndex ?>_Submission_ID" value="<?= HtmlEncode($Grid->Submission_ID->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->_Title->Visible) { // Title ?>
        <td data-name="_Title">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_submission__Title" class="el_submission__Title">
<input type="<?= $Grid->_Title->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>__Title" id="x<?= $Grid->RowIndex ?>__Title" data-table="submission" data-field="x__Title" value="<?= $Grid->_Title->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Grid->_Title->getPlaceHolder()) ?>"<?= $Grid->_Title->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->_Title->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_submission__Title" class="el_submission__Title">
<span<?= $Grid->_Title->viewAttributes() ?>>
<?php if (!EmptyString($Grid->_Title->ViewValue) && $Grid->_Title->linkAttributes() != "") { ?>
<a<?= $Grid->_Title->linkAttributes() ?>><input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->_Title->getDisplayValue($Grid->_Title->ViewValue))) ?>"></a>
<?php } else { ?>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->_Title->getDisplayValue($Grid->_Title->ViewValue))) ?>">
<?php } ?>
</span>
</span>
<input type="hidden" data-table="submission" data-field="x__Title" data-hidden="1" name="x<?= $Grid->RowIndex ?>__Title" id="x<?= $Grid->RowIndex ?>__Title" value="<?= HtmlEncode($Grid->_Title->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="submission" data-field="x__Title" data-hidden="1" name="o<?= $Grid->RowIndex ?>__Title" id="o<?= $Grid->RowIndex ?>__Title" value="<?= HtmlEncode($Grid->_Title->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->Category->Visible) { // Category ?>
        <td data-name="Category">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_submission_Category" class="el_submission_Category">
<input type="<?= $Grid->Category->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_Category" id="x<?= $Grid->RowIndex ?>_Category" data-table="submission" data-field="x_Category" value="<?= $Grid->Category->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Grid->Category->getPlaceHolder()) ?>"<?= $Grid->Category->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->Category->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_submission_Category" class="el_submission_Category">
<span<?= $Grid->Category->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->Category->getDisplayValue($Grid->Category->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="submission" data-field="x_Category" data-hidden="1" name="x<?= $Grid->RowIndex ?>_Category" id="x<?= $Grid->RowIndex ?>_Category" value="<?= HtmlEncode($Grid->Category->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="submission" data-field="x_Category" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Category" id="o<?= $Grid->RowIndex ?>_Category" value="<?= HtmlEncode($Grid->Category->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->Status->Visible) { // Status ?>
        <td data-name="Status">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_submission_Status" class="el_submission_Status">
<template id="tp_x<?= $Grid->RowIndex ?>_Status">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="submission" data-field="x_Status" name="x<?= $Grid->RowIndex ?>_Status" id="x<?= $Grid->RowIndex ?>_Status"<?= $Grid->Status->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x<?= $Grid->RowIndex ?>_Status" class="ew-item-list"></div>
<selection-list hidden
    id="x<?= $Grid->RowIndex ?>_Status"
    name="x<?= $Grid->RowIndex ?>_Status"
    value="<?= HtmlEncode($Grid->Status->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x<?= $Grid->RowIndex ?>_Status"
    data-bs-target="dsl_x<?= $Grid->RowIndex ?>_Status"
    data-repeatcolumn="5"
    class="form-control<?= $Grid->Status->isInvalidClass() ?>"
    data-table="submission"
    data-field="x_Status"
    data-value-separator="<?= $Grid->Status->displayValueSeparatorAttribute() ?>"
    <?= $Grid->Status->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Grid->Status->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_submission_Status" class="el_submission_Status">
<span<?= $Grid->Status->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Grid->Status->getDisplayValue($Grid->Status->ViewValue) ?></span></span>
</span>
<input type="hidden" data-table="submission" data-field="x_Status" data-hidden="1" name="x<?= $Grid->RowIndex ?>_Status" id="x<?= $Grid->RowIndex ?>_Status" value="<?= HtmlEncode($Grid->Status->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="submission" data-field="x_Status" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Status" id="o<?= $Grid->RowIndex ?>_Status" value="<?= HtmlEncode($Grid->Status->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowIndex);
?>
<script>
loadjs.ready(["fsubmissiongrid","load"], () => fsubmissiongrid.updateLists(<?= $Grid->RowIndex ?>, true));
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
<input type="hidden" name="detailpage" value="fsubmissiongrid">
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
    ew.addEventHandlers("submission");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php if (!$submission->isExport()) { ?>
<script>
loadjs.ready("jqueryjs", function() {
	var expires = new Date(new Date().getTime() + 525600 * 60 * 1000); // expire in 525600 
	var SearchToggle = $('.ew-search-toggle'); 
	var SearchPanel = $('.ew-search-panel'); 
	if (Cookies.get('submission_searchpanel')=="active") { 
		SearchToggle.addClass(Cookies.get('submission_searchpanel')); 
		SearchPanel.removeClass('collapse'); 
		SearchPanel.addClass('show'); 
		SearchToggle.addClass('active'); 
		SearchToggle.attr('aria-pressed', 'true'); 
	} else if (Cookies.get('submission_searchpanel')=="notactive") { 
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
			Cookies.set("submission_searchpanel", "notactive", { path: '', expires: expires }); 
		} else { 
			SearchToggle.addClass('active'); 		
			SearchToggle.attr('aria-pressed', 'false'); 
			Cookies.set("submission_searchpanel", "active", { path: '', expires: expires }); 
		} 
	});
});
</script>
<?php } ?>
<?php } ?>
