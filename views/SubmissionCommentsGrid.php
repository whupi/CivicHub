<?php

namespace PHPMaker2022\civichub2;

// Set up and run Grid object
$Grid = Container("SubmissionCommentsGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var fsubmission_commentsgrid;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fsubmission_commentsgrid = new ew.Form("fsubmission_commentsgrid", "grid");
    fsubmission_commentsgrid.formKeyCountName = "<?= $Grid->FormKeyCountName ?>";

    // Add fields
    var currentTable = <?= JsonEncode($Grid->toClientVar()) ?>;
    ew.deepAssign(ew.vars, { tables: { submission_comments: currentTable } });
    var fields = currentTable.fields;
    fsubmission_commentsgrid.addFields([
        ["Submission_ID", [fields.Submission_ID.visible && fields.Submission_ID.required ? ew.Validators.required(fields.Submission_ID.caption) : null], fields.Submission_ID.isInvalid],
        ["Updated_Username", [fields.Updated_Username.visible && fields.Updated_Username.required ? ew.Validators.required(fields.Updated_Username.caption) : null], fields.Updated_Username.isInvalid],
        ["Comment", [fields.Comment.visible && fields.Comment.required ? ew.Validators.required(fields.Comment.caption) : null], fields.Comment.isInvalid]
    ]);

    // Check empty row
    fsubmission_commentsgrid.emptyRow = function (rowIndex) {
        var fobj = this.getForm(),
            fields = [["Submission_ID",false],["Comment",false]];
        if (fields.some(field => ew.valueChanged(fobj, rowIndex, ...field)))
            return false;
        return true;
    }

    // Form_CustomValidate
    fsubmission_commentsgrid.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fsubmission_commentsgrid.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    fsubmission_commentsgrid.lists.Submission_ID = <?= $Grid->Submission_ID->toClientList($Grid) ?>;
    fsubmission_commentsgrid.lists.Updated_Username = <?= $Grid->Updated_Username->toClientList($Grid) ?>;
    loadjs.done("fsubmission_commentsgrid");
});
</script>
<?php } ?>
<?php
$Grid->renderOtherOptions();
?>
<?php if ($Grid->TotalRecords > 0 || $Grid->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($Grid->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> submission_comments">
<div id="fsubmission_commentsgrid" class="ew-form ew-list-form">
<div id="gmp_submission_comments" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table id="tbl_submission_commentsgrid" class="table table-bordered table-hover table-sm ew-table"><!-- .ew-table -->
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
        <th data-name="Submission_ID" class="<?= $Grid->Submission_ID->headerCellClass() ?>"><div id="elh_submission_comments_Submission_ID" class="submission_comments_Submission_ID"><?= $Grid->renderFieldHeader($Grid->Submission_ID) ?></div></th>
<?php } ?>
<?php if ($Grid->Updated_Username->Visible) { // Updated_Username ?>
        <th data-name="Updated_Username" class="<?= $Grid->Updated_Username->headerCellClass() ?>"><div id="elh_submission_comments_Updated_Username" class="submission_comments_Updated_Username"><?= $Grid->renderFieldHeader($Grid->Updated_Username) ?></div></th>
<?php } ?>
<?php if ($Grid->Comment->Visible) { // Comment ?>
        <th data-name="Comment" class="<?= $Grid->Comment->headerCellClass() ?>"><div id="elh_submission_comments_Comment" class="submission_comments_Comment"><?= $Grid->renderFieldHeader($Grid->Comment) ?></div></th>
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
            "id" => "r" . $Grid->RowCount . "_submission_comments",
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
<span id="el<?= $Grid->RowCount ?>_submission_comments_Submission_ID" class="el_submission_comments_Submission_ID">
<span<?= $Grid->Submission_ID->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Grid->Submission_ID->getDisplayValue($Grid->Submission_ID->ViewValue) ?></span></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_Submission_ID" name="x<?= $Grid->RowIndex ?>_Submission_ID" value="<?= HtmlEncode($Grid->Submission_ID->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_submission_comments_Submission_ID" class="el_submission_comments_Submission_ID">
    <select
        id="x<?= $Grid->RowIndex ?>_Submission_ID"
        name="x<?= $Grid->RowIndex ?>_Submission_ID"
        class="form-select ew-select<?= $Grid->Submission_ID->isInvalidClass() ?>"
        data-select2-id="fsubmission_commentsgrid_x<?= $Grid->RowIndex ?>_Submission_ID"
        data-table="submission_comments"
        data-field="x_Submission_ID"
        data-value-separator="<?= $Grid->Submission_ID->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->Submission_ID->getPlaceHolder()) ?>"
        <?= $Grid->Submission_ID->editAttributes() ?>>
        <?= $Grid->Submission_ID->selectOptionListHtml("x{$Grid->RowIndex}_Submission_ID") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->Submission_ID->getErrorMessage() ?></div>
<?= $Grid->Submission_ID->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_Submission_ID") ?>
<script>
loadjs.ready("fsubmission_commentsgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_Submission_ID", selectId: "fsubmission_commentsgrid_x<?= $Grid->RowIndex ?>_Submission_ID" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fsubmission_commentsgrid.lists.Submission_ID.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_Submission_ID", form: "fsubmission_commentsgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_Submission_ID", form: "fsubmission_commentsgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.submission_comments.fields.Submission_ID.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<input type="hidden" data-table="submission_comments" data-field="x_Submission_ID" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Submission_ID" id="o<?= $Grid->RowIndex ?>_Submission_ID" value="<?= HtmlEncode($Grid->Submission_ID->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<?php if ($Grid->Submission_ID->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowCount ?>_submission_comments_Submission_ID" class="el_submission_comments_Submission_ID">
<span<?= $Grid->Submission_ID->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Grid->Submission_ID->getDisplayValue($Grid->Submission_ID->ViewValue) ?></span></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_Submission_ID" name="x<?= $Grid->RowIndex ?>_Submission_ID" value="<?= HtmlEncode($Grid->Submission_ID->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_submission_comments_Submission_ID" class="el_submission_comments_Submission_ID">
    <select
        id="x<?= $Grid->RowIndex ?>_Submission_ID"
        name="x<?= $Grid->RowIndex ?>_Submission_ID"
        class="form-select ew-select<?= $Grid->Submission_ID->isInvalidClass() ?>"
        data-select2-id="fsubmission_commentsgrid_x<?= $Grid->RowIndex ?>_Submission_ID"
        data-table="submission_comments"
        data-field="x_Submission_ID"
        data-value-separator="<?= $Grid->Submission_ID->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->Submission_ID->getPlaceHolder()) ?>"
        <?= $Grid->Submission_ID->editAttributes() ?>>
        <?= $Grid->Submission_ID->selectOptionListHtml("x{$Grid->RowIndex}_Submission_ID") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->Submission_ID->getErrorMessage() ?></div>
<?= $Grid->Submission_ID->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_Submission_ID") ?>
<script>
loadjs.ready("fsubmission_commentsgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_Submission_ID", selectId: "fsubmission_commentsgrid_x<?= $Grid->RowIndex ?>_Submission_ID" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fsubmission_commentsgrid.lists.Submission_ID.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_Submission_ID", form: "fsubmission_commentsgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_Submission_ID", form: "fsubmission_commentsgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.submission_comments.fields.Submission_ID.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_submission_comments_Submission_ID" class="el_submission_comments_Submission_ID">
<span<?= $Grid->Submission_ID->viewAttributes() ?>>
<?= $Grid->Submission_ID->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="submission_comments" data-field="x_Submission_ID" data-hidden="1" name="fsubmission_commentsgrid$x<?= $Grid->RowIndex ?>_Submission_ID" id="fsubmission_commentsgrid$x<?= $Grid->RowIndex ?>_Submission_ID" value="<?= HtmlEncode($Grid->Submission_ID->FormValue) ?>">
<input type="hidden" data-table="submission_comments" data-field="x_Submission_ID" data-hidden="1" name="fsubmission_commentsgrid$o<?= $Grid->RowIndex ?>_Submission_ID" id="fsubmission_commentsgrid$o<?= $Grid->RowIndex ?>_Submission_ID" value="<?= HtmlEncode($Grid->Submission_ID->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->Updated_Username->Visible) { // Updated_Username ?>
        <td data-name="Updated_Username"<?= $Grid->Updated_Username->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="submission_comments" data-field="x_Updated_Username" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Updated_Username" id="o<?= $Grid->RowIndex ?>_Updated_Username" value="<?= HtmlEncode($Grid->Updated_Username->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_submission_comments_Updated_Username" class="el_submission_comments_Updated_Username">
<span<?= $Grid->Updated_Username->viewAttributes() ?>>
<?= $Grid->Updated_Username->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="submission_comments" data-field="x_Updated_Username" data-hidden="1" name="fsubmission_commentsgrid$x<?= $Grid->RowIndex ?>_Updated_Username" id="fsubmission_commentsgrid$x<?= $Grid->RowIndex ?>_Updated_Username" value="<?= HtmlEncode($Grid->Updated_Username->FormValue) ?>">
<input type="hidden" data-table="submission_comments" data-field="x_Updated_Username" data-hidden="1" name="fsubmission_commentsgrid$o<?= $Grid->RowIndex ?>_Updated_Username" id="fsubmission_commentsgrid$o<?= $Grid->RowIndex ?>_Updated_Username" value="<?= HtmlEncode($Grid->Updated_Username->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->Comment->Visible) { // Comment ?>
        <td data-name="Comment"<?= $Grid->Comment->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_submission_comments_Comment" class="el_submission_comments_Comment">
<?php $Grid->Comment->EditAttrs->appendClass("editor"); ?>
<textarea data-table="submission_comments" data-field="x_Comment" name="x<?= $Grid->RowIndex ?>_Comment" id="x<?= $Grid->RowIndex ?>_Comment" cols="35" rows="4" placeholder="<?= HtmlEncode($Grid->Comment->getPlaceHolder()) ?>"<?= $Grid->Comment->editAttributes() ?>><?= $Grid->Comment->EditValue ?></textarea>
<div class="invalid-feedback"><?= $Grid->Comment->getErrorMessage() ?></div>
<script>
loadjs.ready(["fsubmission_commentsgrid", "editor"], function() {
    ew.createEditor("fsubmission_commentsgrid", "x<?= $Grid->RowIndex ?>_Comment", 35, 4, <?= $Grid->Comment->ReadOnly || false ? "true" : "false" ?>);
});
</script>
</span>
<input type="hidden" data-table="submission_comments" data-field="x_Comment" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Comment" id="o<?= $Grid->RowIndex ?>_Comment" value="<?= HtmlEncode($Grid->Comment->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_submission_comments_Comment" class="el_submission_comments_Comment">
<?php $Grid->Comment->EditAttrs->appendClass("editor"); ?>
<textarea data-table="submission_comments" data-field="x_Comment" name="x<?= $Grid->RowIndex ?>_Comment" id="x<?= $Grid->RowIndex ?>_Comment" cols="35" rows="4" placeholder="<?= HtmlEncode($Grid->Comment->getPlaceHolder()) ?>"<?= $Grid->Comment->editAttributes() ?>><?= $Grid->Comment->EditValue ?></textarea>
<div class="invalid-feedback"><?= $Grid->Comment->getErrorMessage() ?></div>
<script>
loadjs.ready(["fsubmission_commentsgrid", "editor"], function() {
    ew.createEditor("fsubmission_commentsgrid", "x<?= $Grid->RowIndex ?>_Comment", 35, 4, <?= $Grid->Comment->ReadOnly || false ? "true" : "false" ?>);
});
</script>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_submission_comments_Comment" class="el_submission_comments_Comment">
<span<?= $Grid->Comment->viewAttributes() ?>>
<?= $Grid->Comment->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="submission_comments" data-field="x_Comment" data-hidden="1" name="fsubmission_commentsgrid$x<?= $Grid->RowIndex ?>_Comment" id="fsubmission_commentsgrid$x<?= $Grid->RowIndex ?>_Comment" value="<?= HtmlEncode($Grid->Comment->FormValue) ?>">
<input type="hidden" data-table="submission_comments" data-field="x_Comment" data-hidden="1" name="fsubmission_commentsgrid$o<?= $Grid->RowIndex ?>_Comment" id="fsubmission_commentsgrid$o<?= $Grid->RowIndex ?>_Comment" value="<?= HtmlEncode($Grid->Comment->OldValue) ?>">
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
loadjs.ready(["fsubmission_commentsgrid","load"], () => fsubmission_commentsgrid.updateLists(<?= $Grid->RowIndex ?>));
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
    $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowIndex, "id" => "r0_submission_comments", "data-rowtype" => ROWTYPE_ADD]);
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
<span id="el$rowindex$_submission_comments_Submission_ID" class="el_submission_comments_Submission_ID">
<span<?= $Grid->Submission_ID->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Grid->Submission_ID->getDisplayValue($Grid->Submission_ID->ViewValue) ?></span></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_Submission_ID" name="x<?= $Grid->RowIndex ?>_Submission_ID" value="<?= HtmlEncode($Grid->Submission_ID->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el$rowindex$_submission_comments_Submission_ID" class="el_submission_comments_Submission_ID">
    <select
        id="x<?= $Grid->RowIndex ?>_Submission_ID"
        name="x<?= $Grid->RowIndex ?>_Submission_ID"
        class="form-select ew-select<?= $Grid->Submission_ID->isInvalidClass() ?>"
        data-select2-id="fsubmission_commentsgrid_x<?= $Grid->RowIndex ?>_Submission_ID"
        data-table="submission_comments"
        data-field="x_Submission_ID"
        data-value-separator="<?= $Grid->Submission_ID->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->Submission_ID->getPlaceHolder()) ?>"
        <?= $Grid->Submission_ID->editAttributes() ?>>
        <?= $Grid->Submission_ID->selectOptionListHtml("x{$Grid->RowIndex}_Submission_ID") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->Submission_ID->getErrorMessage() ?></div>
<?= $Grid->Submission_ID->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_Submission_ID") ?>
<script>
loadjs.ready("fsubmission_commentsgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_Submission_ID", selectId: "fsubmission_commentsgrid_x<?= $Grid->RowIndex ?>_Submission_ID" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fsubmission_commentsgrid.lists.Submission_ID.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_Submission_ID", form: "fsubmission_commentsgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_Submission_ID", form: "fsubmission_commentsgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.submission_comments.fields.Submission_ID.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_submission_comments_Submission_ID" class="el_submission_comments_Submission_ID">
<span<?= $Grid->Submission_ID->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Grid->Submission_ID->getDisplayValue($Grid->Submission_ID->ViewValue) ?></span></span>
</span>
<input type="hidden" data-table="submission_comments" data-field="x_Submission_ID" data-hidden="1" name="x<?= $Grid->RowIndex ?>_Submission_ID" id="x<?= $Grid->RowIndex ?>_Submission_ID" value="<?= HtmlEncode($Grid->Submission_ID->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="submission_comments" data-field="x_Submission_ID" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Submission_ID" id="o<?= $Grid->RowIndex ?>_Submission_ID" value="<?= HtmlEncode($Grid->Submission_ID->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->Updated_Username->Visible) { // Updated_Username ?>
        <td data-name="Updated_Username">
<?php if (!$Grid->isConfirm()) { ?>
<?php } else { ?>
<span id="el$rowindex$_submission_comments_Updated_Username" class="el_submission_comments_Updated_Username">
<span<?= $Grid->Updated_Username->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Grid->Updated_Username->getDisplayValue($Grid->Updated_Username->ViewValue) ?></span></span>
</span>
<input type="hidden" data-table="submission_comments" data-field="x_Updated_Username" data-hidden="1" name="x<?= $Grid->RowIndex ?>_Updated_Username" id="x<?= $Grid->RowIndex ?>_Updated_Username" value="<?= HtmlEncode($Grid->Updated_Username->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="submission_comments" data-field="x_Updated_Username" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Updated_Username" id="o<?= $Grid->RowIndex ?>_Updated_Username" value="<?= HtmlEncode($Grid->Updated_Username->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->Comment->Visible) { // Comment ?>
        <td data-name="Comment">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_submission_comments_Comment" class="el_submission_comments_Comment">
<?php $Grid->Comment->EditAttrs->appendClass("editor"); ?>
<textarea data-table="submission_comments" data-field="x_Comment" name="x<?= $Grid->RowIndex ?>_Comment" id="x<?= $Grid->RowIndex ?>_Comment" cols="35" rows="4" placeholder="<?= HtmlEncode($Grid->Comment->getPlaceHolder()) ?>"<?= $Grid->Comment->editAttributes() ?>><?= $Grid->Comment->EditValue ?></textarea>
<div class="invalid-feedback"><?= $Grid->Comment->getErrorMessage() ?></div>
<script>
loadjs.ready(["fsubmission_commentsgrid", "editor"], function() {
    ew.createEditor("fsubmission_commentsgrid", "x<?= $Grid->RowIndex ?>_Comment", 35, 4, <?= $Grid->Comment->ReadOnly || false ? "true" : "false" ?>);
});
</script>
</span>
<?php } else { ?>
<span id="el$rowindex$_submission_comments_Comment" class="el_submission_comments_Comment">
<span<?= $Grid->Comment->viewAttributes() ?>>
<?= $Grid->Comment->ViewValue ?></span>
</span>
<input type="hidden" data-table="submission_comments" data-field="x_Comment" data-hidden="1" name="x<?= $Grid->RowIndex ?>_Comment" id="x<?= $Grid->RowIndex ?>_Comment" value="<?= HtmlEncode($Grid->Comment->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="submission_comments" data-field="x_Comment" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Comment" id="o<?= $Grid->RowIndex ?>_Comment" value="<?= HtmlEncode($Grid->Comment->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowIndex);
?>
<script>
loadjs.ready(["fsubmission_commentsgrid","load"], () => fsubmission_commentsgrid.updateLists(<?= $Grid->RowIndex ?>, true));
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
<input type="hidden" name="detailpage" value="fsubmission_commentsgrid">
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
    ew.addEventHandlers("submission_comments");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php if (!$submission_comments->isExport()) { ?>
<script>
loadjs.ready("jqueryjs", function() {
	var expires = new Date(new Date().getTime() + 525600 * 60 * 1000); // expire in 525600 
	var SearchToggle = $('.ew-search-toggle'); 
	var SearchPanel = $('.ew-search-panel'); 
	if (Cookies.get('submission_comments_searchpanel')=="active") { 
		SearchToggle.addClass(Cookies.get('submission_comments_searchpanel')); 
		SearchPanel.removeClass('collapse'); 
		SearchPanel.addClass('show'); 
		SearchToggle.addClass('active'); 
		SearchToggle.attr('aria-pressed', 'true'); 
	} else if (Cookies.get('submission_comments_searchpanel')=="notactive") { 
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
			Cookies.set("submission_comments_searchpanel", "notactive", { path: '', expires: expires }); 
		} else { 
			SearchToggle.addClass('active'); 		
			SearchToggle.attr('aria-pressed', 'false'); 
			Cookies.set("submission_comments_searchpanel", "active", { path: '', expires: expires }); 
		} 
	});
});
</script>
<?php } ?>
<?php } ?>
