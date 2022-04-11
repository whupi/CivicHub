<?php

namespace PHPMaker2022\civichub2;

// Set up and run Grid object
$Grid = Container("VoteTallyGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var fvote_tallygrid;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fvote_tallygrid = new ew.Form("fvote_tallygrid", "grid");
    fvote_tallygrid.formKeyCountName = "<?= $Grid->FormKeyCountName ?>";

    // Add fields
    var currentTable = <?= JsonEncode($Grid->toClientVar()) ?>;
    ew.deepAssign(ew.vars, { tables: { vote_tally: currentTable } });
    var fields = currentTable.fields;
    fvote_tallygrid.addFields([
        ["Submission_ID", [fields.Submission_ID.visible && fields.Submission_ID.required ? ew.Validators.required(fields.Submission_ID.caption) : null, ew.Validators.integer], fields.Submission_ID.isInvalid],
        ["_Title", [fields._Title.visible && fields._Title.required ? ew.Validators.required(fields._Title.caption) : null], fields._Title.isInvalid],
        ["Votes", [fields.Votes.visible && fields.Votes.required ? ew.Validators.required(fields.Votes.caption) : null], fields.Votes.isInvalid],
        ["Count", [fields.Count.visible && fields.Count.required ? ew.Validators.required(fields.Count.caption) : null, ew.Validators.integer], fields.Count.isInvalid]
    ]);

    // Check empty row
    fvote_tallygrid.emptyRow = function (rowIndex) {
        var fobj = this.getForm(),
            fields = [["Submission_ID",false],["_Title",false],["Votes",false],["Count",false]];
        if (fields.some(field => ew.valueChanged(fobj, rowIndex, ...field)))
            return false;
        return true;
    }

    // Form_CustomValidate
    fvote_tallygrid.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fvote_tallygrid.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    fvote_tallygrid.lists.Votes = <?= $Grid->Votes->toClientList($Grid) ?>;
    loadjs.done("fvote_tallygrid");
});
</script>
<?php } ?>
<?php
$Grid->renderOtherOptions();
?>
<?php if ($Grid->TotalRecords > 0 || $Grid->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($Grid->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> vote_tally">
<div id="fvote_tallygrid" class="ew-form ew-list-form">
<div id="gmp_vote_tally" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table id="tbl_vote_tallygrid" class="table table-bordered table-hover table-sm ew-table"><!-- .ew-table -->
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
        <th data-name="Submission_ID" class="<?= $Grid->Submission_ID->headerCellClass() ?>"><div id="elh_vote_tally_Submission_ID" class="vote_tally_Submission_ID"><?= $Grid->renderFieldHeader($Grid->Submission_ID) ?></div></th>
<?php } ?>
<?php if ($Grid->_Title->Visible) { // Title ?>
        <th data-name="_Title" class="<?= $Grid->_Title->headerCellClass() ?>"><div id="elh_vote_tally__Title" class="vote_tally__Title"><?= $Grid->renderFieldHeader($Grid->_Title) ?></div></th>
<?php } ?>
<?php if ($Grid->Votes->Visible) { // Votes ?>
        <th data-name="Votes" class="<?= $Grid->Votes->headerCellClass() ?>"><div id="elh_vote_tally_Votes" class="vote_tally_Votes"><?= $Grid->renderFieldHeader($Grid->Votes) ?></div></th>
<?php } ?>
<?php if ($Grid->Count->Visible) { // Count ?>
        <th data-name="Count" class="<?= $Grid->Count->headerCellClass() ?>"><div id="elh_vote_tally_Count" class="vote_tally_Count"><?= $Grid->renderFieldHeader($Grid->Count) ?></div></th>
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
            "id" => "r" . $Grid->RowCount . "_vote_tally",
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
<?php if ($Grid->Submission_ID->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowCount ?>_vote_tally_Submission_ID" class="el_vote_tally_Submission_ID">
<span<?= $Grid->Submission_ID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->Submission_ID->getDisplayValue($Grid->Submission_ID->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_Submission_ID" name="x<?= $Grid->RowIndex ?>_Submission_ID" value="<?= HtmlEncode(FormatNumber($Grid->Submission_ID->CurrentValue, $Grid->Submission_ID->formatPattern())) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_vote_tally_Submission_ID" class="el_vote_tally_Submission_ID">
<input type="<?= $Grid->Submission_ID->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_Submission_ID" id="x<?= $Grid->RowIndex ?>_Submission_ID" data-table="vote_tally" data-field="x_Submission_ID" value="<?= $Grid->Submission_ID->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->Submission_ID->getPlaceHolder()) ?>"<?= $Grid->Submission_ID->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->Submission_ID->getErrorMessage() ?></div>
</span>
<?php } ?>
<input type="hidden" data-table="vote_tally" data-field="x_Submission_ID" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Submission_ID" id="o<?= $Grid->RowIndex ?>_Submission_ID" value="<?= HtmlEncode($Grid->Submission_ID->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<?php if ($Grid->Submission_ID->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowCount ?>_vote_tally_Submission_ID" class="el_vote_tally_Submission_ID">
<span<?= $Grid->Submission_ID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->Submission_ID->getDisplayValue($Grid->Submission_ID->EditValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_Submission_ID" name="x<?= $Grid->RowIndex ?>_Submission_ID" value="<?= HtmlEncode($Grid->Submission_ID->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<input type="<?= $Grid->Submission_ID->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_Submission_ID" id="x<?= $Grid->RowIndex ?>_Submission_ID" data-table="vote_tally" data-field="x_Submission_ID" value="<?= $Grid->Submission_ID->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->Submission_ID->getPlaceHolder()) ?>"<?= $Grid->Submission_ID->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->Submission_ID->getErrorMessage() ?></div>
<?php } ?>
<input type="hidden" data-table="vote_tally" data-field="x_Submission_ID" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Submission_ID" id="o<?= $Grid->RowIndex ?>_Submission_ID" value="<?= HtmlEncode($Grid->Submission_ID->OldValue ?? $Grid->Submission_ID->CurrentValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_vote_tally_Submission_ID" class="el_vote_tally_Submission_ID">
<span<?= $Grid->Submission_ID->viewAttributes() ?>>
<?= $Grid->Submission_ID->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="vote_tally" data-field="x_Submission_ID" data-hidden="1" name="fvote_tallygrid$x<?= $Grid->RowIndex ?>_Submission_ID" id="fvote_tallygrid$x<?= $Grid->RowIndex ?>_Submission_ID" value="<?= HtmlEncode($Grid->Submission_ID->FormValue) ?>">
<input type="hidden" data-table="vote_tally" data-field="x_Submission_ID" data-hidden="1" name="fvote_tallygrid$o<?= $Grid->RowIndex ?>_Submission_ID" id="fvote_tallygrid$o<?= $Grid->RowIndex ?>_Submission_ID" value="<?= HtmlEncode($Grid->Submission_ID->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } else { ?>
            <input type="hidden" data-table="vote_tally" data-field="x_Submission_ID" data-hidden="1" name="x<?= $Grid->RowIndex ?>_Submission_ID" id="x<?= $Grid->RowIndex ?>_Submission_ID" value="<?= HtmlEncode($Grid->Submission_ID->CurrentValue) ?>">
    <?php } ?>
    <?php if ($Grid->_Title->Visible) { // Title ?>
        <td data-name="_Title"<?= $Grid->_Title->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_vote_tally__Title" class="el_vote_tally__Title">
<input type="<?= $Grid->_Title->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>__Title" id="x<?= $Grid->RowIndex ?>__Title" data-table="vote_tally" data-field="x__Title" value="<?= $Grid->_Title->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Grid->_Title->getPlaceHolder()) ?>"<?= $Grid->_Title->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->_Title->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="vote_tally" data-field="x__Title" data-hidden="1" name="o<?= $Grid->RowIndex ?>__Title" id="o<?= $Grid->RowIndex ?>__Title" value="<?= HtmlEncode($Grid->_Title->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_vote_tally__Title" class="el_vote_tally__Title">
<input type="<?= $Grid->_Title->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>__Title" id="x<?= $Grid->RowIndex ?>__Title" data-table="vote_tally" data-field="x__Title" value="<?= $Grid->_Title->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Grid->_Title->getPlaceHolder()) ?>"<?= $Grid->_Title->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->_Title->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_vote_tally__Title" class="el_vote_tally__Title">
<span<?= $Grid->_Title->viewAttributes() ?>>
<?= $Grid->_Title->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="vote_tally" data-field="x__Title" data-hidden="1" name="fvote_tallygrid$x<?= $Grid->RowIndex ?>__Title" id="fvote_tallygrid$x<?= $Grid->RowIndex ?>__Title" value="<?= HtmlEncode($Grid->_Title->FormValue) ?>">
<input type="hidden" data-table="vote_tally" data-field="x__Title" data-hidden="1" name="fvote_tallygrid$o<?= $Grid->RowIndex ?>__Title" id="fvote_tallygrid$o<?= $Grid->RowIndex ?>__Title" value="<?= HtmlEncode($Grid->_Title->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->Votes->Visible) { // Votes ?>
        <td data-name="Votes"<?= $Grid->Votes->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_vote_tally_Votes" class="el_vote_tally_Votes">
<template id="tp_x<?= $Grid->RowIndex ?>_Votes">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="vote_tally" data-field="x_Votes" name="x<?= $Grid->RowIndex ?>_Votes" id="x<?= $Grid->RowIndex ?>_Votes"<?= $Grid->Votes->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x<?= $Grid->RowIndex ?>_Votes" class="ew-item-list"></div>
<selection-list hidden
    id="x<?= $Grid->RowIndex ?>_Votes"
    name="x<?= $Grid->RowIndex ?>_Votes"
    value="<?= HtmlEncode($Grid->Votes->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x<?= $Grid->RowIndex ?>_Votes"
    data-bs-target="dsl_x<?= $Grid->RowIndex ?>_Votes"
    data-repeatcolumn="5"
    class="form-control<?= $Grid->Votes->isInvalidClass() ?>"
    data-table="vote_tally"
    data-field="x_Votes"
    data-value-separator="<?= $Grid->Votes->displayValueSeparatorAttribute() ?>"
    <?= $Grid->Votes->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Grid->Votes->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="vote_tally" data-field="x_Votes" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Votes" id="o<?= $Grid->RowIndex ?>_Votes" value="<?= HtmlEncode($Grid->Votes->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_vote_tally_Votes" class="el_vote_tally_Votes">
<template id="tp_x<?= $Grid->RowIndex ?>_Votes">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="vote_tally" data-field="x_Votes" name="x<?= $Grid->RowIndex ?>_Votes" id="x<?= $Grid->RowIndex ?>_Votes"<?= $Grid->Votes->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x<?= $Grid->RowIndex ?>_Votes" class="ew-item-list"></div>
<selection-list hidden
    id="x<?= $Grid->RowIndex ?>_Votes"
    name="x<?= $Grid->RowIndex ?>_Votes"
    value="<?= HtmlEncode($Grid->Votes->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x<?= $Grid->RowIndex ?>_Votes"
    data-bs-target="dsl_x<?= $Grid->RowIndex ?>_Votes"
    data-repeatcolumn="5"
    class="form-control<?= $Grid->Votes->isInvalidClass() ?>"
    data-table="vote_tally"
    data-field="x_Votes"
    data-value-separator="<?= $Grid->Votes->displayValueSeparatorAttribute() ?>"
    <?= $Grid->Votes->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Grid->Votes->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_vote_tally_Votes" class="el_vote_tally_Votes">
<span<?= $Grid->Votes->viewAttributes() ?>>
<?= $Grid->Votes->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="vote_tally" data-field="x_Votes" data-hidden="1" name="fvote_tallygrid$x<?= $Grid->RowIndex ?>_Votes" id="fvote_tallygrid$x<?= $Grid->RowIndex ?>_Votes" value="<?= HtmlEncode($Grid->Votes->FormValue) ?>">
<input type="hidden" data-table="vote_tally" data-field="x_Votes" data-hidden="1" name="fvote_tallygrid$o<?= $Grid->RowIndex ?>_Votes" id="fvote_tallygrid$o<?= $Grid->RowIndex ?>_Votes" value="<?= HtmlEncode($Grid->Votes->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->Count->Visible) { // Count ?>
        <td data-name="Count"<?= $Grid->Count->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_vote_tally_Count" class="el_vote_tally_Count">
<input type="<?= $Grid->Count->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_Count" id="x<?= $Grid->RowIndex ?>_Count" data-table="vote_tally" data-field="x_Count" value="<?= $Grid->Count->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->Count->getPlaceHolder()) ?>"<?= $Grid->Count->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->Count->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="vote_tally" data-field="x_Count" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Count" id="o<?= $Grid->RowIndex ?>_Count" value="<?= HtmlEncode($Grid->Count->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_vote_tally_Count" class="el_vote_tally_Count">
<input type="<?= $Grid->Count->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_Count" id="x<?= $Grid->RowIndex ?>_Count" data-table="vote_tally" data-field="x_Count" value="<?= $Grid->Count->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->Count->getPlaceHolder()) ?>"<?= $Grid->Count->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->Count->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_vote_tally_Count" class="el_vote_tally_Count">
<span<?= $Grid->Count->viewAttributes() ?>>
<?= $Grid->Count->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="vote_tally" data-field="x_Count" data-hidden="1" name="fvote_tallygrid$x<?= $Grid->RowIndex ?>_Count" id="fvote_tallygrid$x<?= $Grid->RowIndex ?>_Count" value="<?= HtmlEncode($Grid->Count->FormValue) ?>">
<input type="hidden" data-table="vote_tally" data-field="x_Count" data-hidden="1" name="fvote_tallygrid$o<?= $Grid->RowIndex ?>_Count" id="fvote_tallygrid$o<?= $Grid->RowIndex ?>_Count" value="<?= HtmlEncode($Grid->Count->OldValue) ?>">
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
loadjs.ready(["fvote_tallygrid","load"], () => fvote_tallygrid.updateLists(<?= $Grid->RowIndex ?>));
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
    $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowIndex, "id" => "r0_vote_tally", "data-rowtype" => ROWTYPE_ADD]);
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
<?php if ($Grid->Submission_ID->getSessionValue() != "") { ?>
<span id="el$rowindex$_vote_tally_Submission_ID" class="el_vote_tally_Submission_ID">
<span<?= $Grid->Submission_ID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->Submission_ID->getDisplayValue($Grid->Submission_ID->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_Submission_ID" name="x<?= $Grid->RowIndex ?>_Submission_ID" value="<?= HtmlEncode(FormatNumber($Grid->Submission_ID->CurrentValue, $Grid->Submission_ID->formatPattern())) ?>" data-hidden="1">
<?php } else { ?>
<span id="el$rowindex$_vote_tally_Submission_ID" class="el_vote_tally_Submission_ID">
<input type="<?= $Grid->Submission_ID->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_Submission_ID" id="x<?= $Grid->RowIndex ?>_Submission_ID" data-table="vote_tally" data-field="x_Submission_ID" value="<?= $Grid->Submission_ID->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->Submission_ID->getPlaceHolder()) ?>"<?= $Grid->Submission_ID->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->Submission_ID->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_vote_tally_Submission_ID" class="el_vote_tally_Submission_ID">
<span<?= $Grid->Submission_ID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->Submission_ID->getDisplayValue($Grid->Submission_ID->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="vote_tally" data-field="x_Submission_ID" data-hidden="1" name="x<?= $Grid->RowIndex ?>_Submission_ID" id="x<?= $Grid->RowIndex ?>_Submission_ID" value="<?= HtmlEncode($Grid->Submission_ID->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="vote_tally" data-field="x_Submission_ID" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Submission_ID" id="o<?= $Grid->RowIndex ?>_Submission_ID" value="<?= HtmlEncode($Grid->Submission_ID->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->_Title->Visible) { // Title ?>
        <td data-name="_Title">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_vote_tally__Title" class="el_vote_tally__Title">
<input type="<?= $Grid->_Title->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>__Title" id="x<?= $Grid->RowIndex ?>__Title" data-table="vote_tally" data-field="x__Title" value="<?= $Grid->_Title->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Grid->_Title->getPlaceHolder()) ?>"<?= $Grid->_Title->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->_Title->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_vote_tally__Title" class="el_vote_tally__Title">
<span<?= $Grid->_Title->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->_Title->getDisplayValue($Grid->_Title->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="vote_tally" data-field="x__Title" data-hidden="1" name="x<?= $Grid->RowIndex ?>__Title" id="x<?= $Grid->RowIndex ?>__Title" value="<?= HtmlEncode($Grid->_Title->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="vote_tally" data-field="x__Title" data-hidden="1" name="o<?= $Grid->RowIndex ?>__Title" id="o<?= $Grid->RowIndex ?>__Title" value="<?= HtmlEncode($Grid->_Title->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->Votes->Visible) { // Votes ?>
        <td data-name="Votes">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_vote_tally_Votes" class="el_vote_tally_Votes">
<template id="tp_x<?= $Grid->RowIndex ?>_Votes">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="vote_tally" data-field="x_Votes" name="x<?= $Grid->RowIndex ?>_Votes" id="x<?= $Grid->RowIndex ?>_Votes"<?= $Grid->Votes->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x<?= $Grid->RowIndex ?>_Votes" class="ew-item-list"></div>
<selection-list hidden
    id="x<?= $Grid->RowIndex ?>_Votes"
    name="x<?= $Grid->RowIndex ?>_Votes"
    value="<?= HtmlEncode($Grid->Votes->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x<?= $Grid->RowIndex ?>_Votes"
    data-bs-target="dsl_x<?= $Grid->RowIndex ?>_Votes"
    data-repeatcolumn="5"
    class="form-control<?= $Grid->Votes->isInvalidClass() ?>"
    data-table="vote_tally"
    data-field="x_Votes"
    data-value-separator="<?= $Grid->Votes->displayValueSeparatorAttribute() ?>"
    <?= $Grid->Votes->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Grid->Votes->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_vote_tally_Votes" class="el_vote_tally_Votes">
<span<?= $Grid->Votes->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Grid->Votes->getDisplayValue($Grid->Votes->ViewValue) ?></span></span>
</span>
<input type="hidden" data-table="vote_tally" data-field="x_Votes" data-hidden="1" name="x<?= $Grid->RowIndex ?>_Votes" id="x<?= $Grid->RowIndex ?>_Votes" value="<?= HtmlEncode($Grid->Votes->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="vote_tally" data-field="x_Votes" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Votes" id="o<?= $Grid->RowIndex ?>_Votes" value="<?= HtmlEncode($Grid->Votes->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->Count->Visible) { // Count ?>
        <td data-name="Count">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_vote_tally_Count" class="el_vote_tally_Count">
<input type="<?= $Grid->Count->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_Count" id="x<?= $Grid->RowIndex ?>_Count" data-table="vote_tally" data-field="x_Count" value="<?= $Grid->Count->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->Count->getPlaceHolder()) ?>"<?= $Grid->Count->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->Count->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_vote_tally_Count" class="el_vote_tally_Count">
<span<?= $Grid->Count->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->Count->getDisplayValue($Grid->Count->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="vote_tally" data-field="x_Count" data-hidden="1" name="x<?= $Grid->RowIndex ?>_Count" id="x<?= $Grid->RowIndex ?>_Count" value="<?= HtmlEncode($Grid->Count->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="vote_tally" data-field="x_Count" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Count" id="o<?= $Grid->RowIndex ?>_Count" value="<?= HtmlEncode($Grid->Count->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowIndex);
?>
<script>
loadjs.ready(["fvote_tallygrid","load"], () => fvote_tallygrid.updateLists(<?= $Grid->RowIndex ?>, true));
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
<input type="hidden" name="detailpage" value="fvote_tallygrid">
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
    ew.addEventHandlers("vote_tally");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php if (!$vote_tally->isExport()) { ?>
<script>
loadjs.ready("jqueryjs", function() {
	var expires = new Date(new Date().getTime() + 525600 * 60 * 1000); // expire in 525600 
	var SearchToggle = $('.ew-search-toggle'); 
	var SearchPanel = $('.ew-search-panel'); 
	if (Cookies.get('vote_tally_searchpanel')=="active") { 
		SearchToggle.addClass(Cookies.get('vote_tally_searchpanel')); 
		SearchPanel.removeClass('collapse'); 
		SearchPanel.addClass('show'); 
		SearchToggle.addClass('active'); 
		SearchToggle.attr('aria-pressed', 'true'); 
	} else if (Cookies.get('vote_tally_searchpanel')=="notactive") { 
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
			Cookies.set("vote_tally_searchpanel", "notactive", { path: '', expires: expires }); 
		} else { 
			SearchToggle.addClass('active'); 		
			SearchToggle.attr('aria-pressed', 'false'); 
			Cookies.set("vote_tally_searchpanel", "active", { path: '', expires: expires }); 
		} 
	});
});
</script>
<?php } ?>
<?php } ?>
