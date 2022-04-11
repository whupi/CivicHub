<?php

namespace PHPMaker2022\civichub2;

// Set up and run Grid object
$Grid = Container("SubmissionMonitorGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var fsubmission_monitorgrid;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fsubmission_monitorgrid = new ew.Form("fsubmission_monitorgrid", "grid");
    fsubmission_monitorgrid.formKeyCountName = "<?= $Grid->FormKeyCountName ?>";

    // Add fields
    var currentTable = <?= JsonEncode($Grid->toClientVar()) ?>;
    ew.deepAssign(ew.vars, { tables: { submission_monitor: currentTable } });
    var fields = currentTable.fields;
    fsubmission_monitorgrid.addFields([
        ["Monitor_ID", [fields.Monitor_ID.visible && fields.Monitor_ID.required ? ew.Validators.required(fields.Monitor_ID.caption) : null], fields.Monitor_ID.isInvalid],
        ["Status", [fields.Status.visible && fields.Status.required ? ew.Validators.required(fields.Status.caption) : null], fields.Status.isInvalid],
        ["Taskings", [fields.Taskings.visible && fields.Taskings.required ? ew.Validators.required(fields.Taskings.caption) : null], fields.Taskings.isInvalid],
        ["Start_Date", [fields.Start_Date.visible && fields.Start_Date.required ? ew.Validators.required(fields.Start_Date.caption) : null, ew.Validators.datetime(fields.Start_Date.clientFormatPattern)], fields.Start_Date.isInvalid],
        ["Finish_Date", [fields.Finish_Date.visible && fields.Finish_Date.required ? ew.Validators.required(fields.Finish_Date.caption) : null, ew.Validators.datetime(fields.Finish_Date.clientFormatPattern)], fields.Finish_Date.isInvalid],
        ["Uploads", [fields.Uploads.visible && fields.Uploads.required ? ew.Validators.fileRequired(fields.Uploads.caption) : null], fields.Uploads.isInvalid],
        ["Updated_Username", [fields.Updated_Username.visible && fields.Updated_Username.required ? ew.Validators.required(fields.Updated_Username.caption) : null], fields.Updated_Username.isInvalid]
    ]);

    // Check empty row
    fsubmission_monitorgrid.emptyRow = function (rowIndex) {
        var fobj = this.getForm(),
            fields = [["Status",false],["Taskings",false],["Start_Date",false],["Finish_Date",false],["Uploads",false]];
        if (fields.some(field => ew.valueChanged(fobj, rowIndex, ...field)))
            return false;
        return true;
    }

    // Form_CustomValidate
    fsubmission_monitorgrid.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fsubmission_monitorgrid.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    fsubmission_monitorgrid.lists.Status = <?= $Grid->Status->toClientList($Grid) ?>;
    loadjs.done("fsubmission_monitorgrid");
});
</script>
<?php } ?>
<?php
$Grid->renderOtherOptions();
?>
<?php if ($Grid->TotalRecords > 0 || $Grid->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($Grid->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> submission_monitor">
<div id="fsubmission_monitorgrid" class="ew-form ew-list-form">
<div id="gmp_submission_monitor" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table id="tbl_submission_monitorgrid" class="table table-bordered table-hover table-sm ew-table"><!-- .ew-table -->
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
<?php if ($Grid->Monitor_ID->Visible) { // Monitor_ID ?>
        <th data-name="Monitor_ID" class="<?= $Grid->Monitor_ID->headerCellClass() ?>"><div id="elh_submission_monitor_Monitor_ID" class="submission_monitor_Monitor_ID"><?= $Grid->renderFieldHeader($Grid->Monitor_ID) ?></div></th>
<?php } ?>
<?php if ($Grid->Status->Visible) { // Status ?>
        <th data-name="Status" class="<?= $Grid->Status->headerCellClass() ?>"><div id="elh_submission_monitor_Status" class="submission_monitor_Status"><?= $Grid->renderFieldHeader($Grid->Status) ?></div></th>
<?php } ?>
<?php if ($Grid->Taskings->Visible) { // Taskings ?>
        <th data-name="Taskings" class="<?= $Grid->Taskings->headerCellClass() ?>"><div id="elh_submission_monitor_Taskings" class="submission_monitor_Taskings"><?= $Grid->renderFieldHeader($Grid->Taskings) ?></div></th>
<?php } ?>
<?php if ($Grid->Start_Date->Visible) { // Start_Date ?>
        <th data-name="Start_Date" class="<?= $Grid->Start_Date->headerCellClass() ?>"><div id="elh_submission_monitor_Start_Date" class="submission_monitor_Start_Date"><?= $Grid->renderFieldHeader($Grid->Start_Date) ?></div></th>
<?php } ?>
<?php if ($Grid->Finish_Date->Visible) { // Finish_Date ?>
        <th data-name="Finish_Date" class="<?= $Grid->Finish_Date->headerCellClass() ?>"><div id="elh_submission_monitor_Finish_Date" class="submission_monitor_Finish_Date"><?= $Grid->renderFieldHeader($Grid->Finish_Date) ?></div></th>
<?php } ?>
<?php if ($Grid->Uploads->Visible) { // Uploads ?>
        <th data-name="Uploads" class="<?= $Grid->Uploads->headerCellClass() ?>"><div id="elh_submission_monitor_Uploads" class="submission_monitor_Uploads"><?= $Grid->renderFieldHeader($Grid->Uploads) ?></div></th>
<?php } ?>
<?php if ($Grid->Updated_Username->Visible) { // Updated_Username ?>
        <th data-name="Updated_Username" class="<?= $Grid->Updated_Username->headerCellClass() ?>"><div id="elh_submission_monitor_Updated_Username" class="submission_monitor_Updated_Username"><?= $Grid->renderFieldHeader($Grid->Updated_Username) ?></div></th>
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
            "id" => "r" . $Grid->RowCount . "_submission_monitor",
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
    <?php if ($Grid->Monitor_ID->Visible) { // Monitor_ID ?>
        <td data-name="Monitor_ID"<?= $Grid->Monitor_ID->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_submission_monitor_Monitor_ID" class="el_submission_monitor_Monitor_ID"></span>
<input type="hidden" data-table="submission_monitor" data-field="x_Monitor_ID" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Monitor_ID" id="o<?= $Grid->RowIndex ?>_Monitor_ID" value="<?= HtmlEncode($Grid->Monitor_ID->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_submission_monitor_Monitor_ID" class="el_submission_monitor_Monitor_ID">
<span<?= $Grid->Monitor_ID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->Monitor_ID->getDisplayValue($Grid->Monitor_ID->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="submission_monitor" data-field="x_Monitor_ID" data-hidden="1" name="x<?= $Grid->RowIndex ?>_Monitor_ID" id="x<?= $Grid->RowIndex ?>_Monitor_ID" value="<?= HtmlEncode($Grid->Monitor_ID->CurrentValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_submission_monitor_Monitor_ID" class="el_submission_monitor_Monitor_ID">
<span<?= $Grid->Monitor_ID->viewAttributes() ?>>
<?= $Grid->Monitor_ID->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="submission_monitor" data-field="x_Monitor_ID" data-hidden="1" name="fsubmission_monitorgrid$x<?= $Grid->RowIndex ?>_Monitor_ID" id="fsubmission_monitorgrid$x<?= $Grid->RowIndex ?>_Monitor_ID" value="<?= HtmlEncode($Grid->Monitor_ID->FormValue) ?>">
<input type="hidden" data-table="submission_monitor" data-field="x_Monitor_ID" data-hidden="1" name="fsubmission_monitorgrid$o<?= $Grid->RowIndex ?>_Monitor_ID" id="fsubmission_monitorgrid$o<?= $Grid->RowIndex ?>_Monitor_ID" value="<?= HtmlEncode($Grid->Monitor_ID->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } else { ?>
            <input type="hidden" data-table="submission_monitor" data-field="x_Monitor_ID" data-hidden="1" name="x<?= $Grid->RowIndex ?>_Monitor_ID" id="x<?= $Grid->RowIndex ?>_Monitor_ID" value="<?= HtmlEncode($Grid->Monitor_ID->CurrentValue) ?>">
    <?php } ?>
    <?php if ($Grid->Status->Visible) { // Status ?>
        <td data-name="Status"<?= $Grid->Status->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_submission_monitor_Status" class="el_submission_monitor_Status">
    <select
        id="x<?= $Grid->RowIndex ?>_Status"
        name="x<?= $Grid->RowIndex ?>_Status"
        class="form-select ew-select<?= $Grid->Status->isInvalidClass() ?>"
        data-select2-id="fsubmission_monitorgrid_x<?= $Grid->RowIndex ?>_Status"
        data-table="submission_monitor"
        data-field="x_Status"
        data-value-separator="<?= $Grid->Status->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->Status->getPlaceHolder()) ?>"
        <?= $Grid->Status->editAttributes() ?>>
        <?= $Grid->Status->selectOptionListHtml("x{$Grid->RowIndex}_Status") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->Status->getErrorMessage() ?></div>
<script>
loadjs.ready("fsubmission_monitorgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_Status", selectId: "fsubmission_monitorgrid_x<?= $Grid->RowIndex ?>_Status" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fsubmission_monitorgrid.lists.Status.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_Status", form: "fsubmission_monitorgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_Status", form: "fsubmission_monitorgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.submission_monitor.fields.Status.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<input type="hidden" data-table="submission_monitor" data-field="x_Status" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Status" id="o<?= $Grid->RowIndex ?>_Status" value="<?= HtmlEncode($Grid->Status->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_submission_monitor_Status" class="el_submission_monitor_Status">
    <select
        id="x<?= $Grid->RowIndex ?>_Status"
        name="x<?= $Grid->RowIndex ?>_Status"
        class="form-select ew-select<?= $Grid->Status->isInvalidClass() ?>"
        data-select2-id="fsubmission_monitorgrid_x<?= $Grid->RowIndex ?>_Status"
        data-table="submission_monitor"
        data-field="x_Status"
        data-value-separator="<?= $Grid->Status->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->Status->getPlaceHolder()) ?>"
        <?= $Grid->Status->editAttributes() ?>>
        <?= $Grid->Status->selectOptionListHtml("x{$Grid->RowIndex}_Status") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->Status->getErrorMessage() ?></div>
<script>
loadjs.ready("fsubmission_monitorgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_Status", selectId: "fsubmission_monitorgrid_x<?= $Grid->RowIndex ?>_Status" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fsubmission_monitorgrid.lists.Status.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_Status", form: "fsubmission_monitorgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_Status", form: "fsubmission_monitorgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.submission_monitor.fields.Status.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_submission_monitor_Status" class="el_submission_monitor_Status">
<span<?= $Grid->Status->viewAttributes() ?>>
<?= $Grid->Status->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="submission_monitor" data-field="x_Status" data-hidden="1" name="fsubmission_monitorgrid$x<?= $Grid->RowIndex ?>_Status" id="fsubmission_monitorgrid$x<?= $Grid->RowIndex ?>_Status" value="<?= HtmlEncode($Grid->Status->FormValue) ?>">
<input type="hidden" data-table="submission_monitor" data-field="x_Status" data-hidden="1" name="fsubmission_monitorgrid$o<?= $Grid->RowIndex ?>_Status" id="fsubmission_monitorgrid$o<?= $Grid->RowIndex ?>_Status" value="<?= HtmlEncode($Grid->Status->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->Taskings->Visible) { // Taskings ?>
        <td data-name="Taskings"<?= $Grid->Taskings->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_submission_monitor_Taskings" class="el_submission_monitor_Taskings">
<?php $Grid->Taskings->EditAttrs->appendClass("editor"); ?>
<textarea data-table="submission_monitor" data-field="x_Taskings" name="x<?= $Grid->RowIndex ?>_Taskings" id="x<?= $Grid->RowIndex ?>_Taskings" cols="35" rows="4" placeholder="<?= HtmlEncode($Grid->Taskings->getPlaceHolder()) ?>"<?= $Grid->Taskings->editAttributes() ?>><?= $Grid->Taskings->EditValue ?></textarea>
<div class="invalid-feedback"><?= $Grid->Taskings->getErrorMessage() ?></div>
<script>
loadjs.ready(["fsubmission_monitorgrid", "editor"], function() {
    ew.createEditor("fsubmission_monitorgrid", "x<?= $Grid->RowIndex ?>_Taskings", 0, 0, <?= $Grid->Taskings->ReadOnly || false ? "true" : "false" ?>);
});
</script>
</span>
<input type="hidden" data-table="submission_monitor" data-field="x_Taskings" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Taskings" id="o<?= $Grid->RowIndex ?>_Taskings" value="<?= HtmlEncode($Grid->Taskings->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_submission_monitor_Taskings" class="el_submission_monitor_Taskings">
<?php $Grid->Taskings->EditAttrs->appendClass("editor"); ?>
<textarea data-table="submission_monitor" data-field="x_Taskings" name="x<?= $Grid->RowIndex ?>_Taskings" id="x<?= $Grid->RowIndex ?>_Taskings" cols="35" rows="4" placeholder="<?= HtmlEncode($Grid->Taskings->getPlaceHolder()) ?>"<?= $Grid->Taskings->editAttributes() ?>><?= $Grid->Taskings->EditValue ?></textarea>
<div class="invalid-feedback"><?= $Grid->Taskings->getErrorMessage() ?></div>
<script>
loadjs.ready(["fsubmission_monitorgrid", "editor"], function() {
    ew.createEditor("fsubmission_monitorgrid", "x<?= $Grid->RowIndex ?>_Taskings", 0, 0, <?= $Grid->Taskings->ReadOnly || false ? "true" : "false" ?>);
});
</script>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_submission_monitor_Taskings" class="el_submission_monitor_Taskings">
<span<?= $Grid->Taskings->viewAttributes() ?>>
<?= $Grid->Taskings->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="submission_monitor" data-field="x_Taskings" data-hidden="1" name="fsubmission_monitorgrid$x<?= $Grid->RowIndex ?>_Taskings" id="fsubmission_monitorgrid$x<?= $Grid->RowIndex ?>_Taskings" value="<?= HtmlEncode($Grid->Taskings->FormValue) ?>">
<input type="hidden" data-table="submission_monitor" data-field="x_Taskings" data-hidden="1" name="fsubmission_monitorgrid$o<?= $Grid->RowIndex ?>_Taskings" id="fsubmission_monitorgrid$o<?= $Grid->RowIndex ?>_Taskings" value="<?= HtmlEncode($Grid->Taskings->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->Start_Date->Visible) { // Start_Date ?>
        <td data-name="Start_Date"<?= $Grid->Start_Date->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_submission_monitor_Start_Date" class="el_submission_monitor_Start_Date">
<input type="<?= $Grid->Start_Date->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_Start_Date" id="x<?= $Grid->RowIndex ?>_Start_Date" data-table="submission_monitor" data-field="x_Start_Date" value="<?= $Grid->Start_Date->EditValue ?>" placeholder="<?= HtmlEncode($Grid->Start_Date->getPlaceHolder()) ?>"<?= $Grid->Start_Date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->Start_Date->getErrorMessage() ?></div>
<?php if (!$Grid->Start_Date->ReadOnly && !$Grid->Start_Date->Disabled && !isset($Grid->Start_Date->EditAttrs["readonly"]) && !isset($Grid->Start_Date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fsubmission_monitorgrid", "datetimepicker"], function () {
    let format = "<?= DateFormat(0) ?>",
        options = {
            localization: {
                locale: ew.LANGUAGE_ID
            },
            display: {
                components: {
                    hours: !!format.match(/h/i),
                    minutes: !!format.match(/m/),
                    seconds: !!format.match(/s/i)
                },
                icons: {
                    previous: ew.IS_RTL ? "fas fa-chevron-right" : "fas fa-chevron-left",
                    next: ew.IS_RTL ? "fas fa-chevron-left" : "fas fa-chevron-right"
                }
            },
            meta: {
                format,
                numberingSystem: ew.getNumberingSystem()
            }
        };
    ew.createDateTimePicker("fsubmission_monitorgrid", "x<?= $Grid->RowIndex ?>_Start_Date", jQuery.extend(true, {"useCurrent":false}, options));
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="submission_monitor" data-field="x_Start_Date" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Start_Date" id="o<?= $Grid->RowIndex ?>_Start_Date" value="<?= HtmlEncode($Grid->Start_Date->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_submission_monitor_Start_Date" class="el_submission_monitor_Start_Date">
<input type="<?= $Grid->Start_Date->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_Start_Date" id="x<?= $Grid->RowIndex ?>_Start_Date" data-table="submission_monitor" data-field="x_Start_Date" value="<?= $Grid->Start_Date->EditValue ?>" placeholder="<?= HtmlEncode($Grid->Start_Date->getPlaceHolder()) ?>"<?= $Grid->Start_Date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->Start_Date->getErrorMessage() ?></div>
<?php if (!$Grid->Start_Date->ReadOnly && !$Grid->Start_Date->Disabled && !isset($Grid->Start_Date->EditAttrs["readonly"]) && !isset($Grid->Start_Date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fsubmission_monitorgrid", "datetimepicker"], function () {
    let format = "<?= DateFormat(0) ?>",
        options = {
            localization: {
                locale: ew.LANGUAGE_ID
            },
            display: {
                components: {
                    hours: !!format.match(/h/i),
                    minutes: !!format.match(/m/),
                    seconds: !!format.match(/s/i)
                },
                icons: {
                    previous: ew.IS_RTL ? "fas fa-chevron-right" : "fas fa-chevron-left",
                    next: ew.IS_RTL ? "fas fa-chevron-left" : "fas fa-chevron-right"
                }
            },
            meta: {
                format,
                numberingSystem: ew.getNumberingSystem()
            }
        };
    ew.createDateTimePicker("fsubmission_monitorgrid", "x<?= $Grid->RowIndex ?>_Start_Date", jQuery.extend(true, {"useCurrent":false}, options));
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_submission_monitor_Start_Date" class="el_submission_monitor_Start_Date">
<span<?= $Grid->Start_Date->viewAttributes() ?>>
<?= $Grid->Start_Date->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="submission_monitor" data-field="x_Start_Date" data-hidden="1" name="fsubmission_monitorgrid$x<?= $Grid->RowIndex ?>_Start_Date" id="fsubmission_monitorgrid$x<?= $Grid->RowIndex ?>_Start_Date" value="<?= HtmlEncode($Grid->Start_Date->FormValue) ?>">
<input type="hidden" data-table="submission_monitor" data-field="x_Start_Date" data-hidden="1" name="fsubmission_monitorgrid$o<?= $Grid->RowIndex ?>_Start_Date" id="fsubmission_monitorgrid$o<?= $Grid->RowIndex ?>_Start_Date" value="<?= HtmlEncode($Grid->Start_Date->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->Finish_Date->Visible) { // Finish_Date ?>
        <td data-name="Finish_Date"<?= $Grid->Finish_Date->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_submission_monitor_Finish_Date" class="el_submission_monitor_Finish_Date">
<input type="<?= $Grid->Finish_Date->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_Finish_Date" id="x<?= $Grid->RowIndex ?>_Finish_Date" data-table="submission_monitor" data-field="x_Finish_Date" value="<?= $Grid->Finish_Date->EditValue ?>" placeholder="<?= HtmlEncode($Grid->Finish_Date->getPlaceHolder()) ?>"<?= $Grid->Finish_Date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->Finish_Date->getErrorMessage() ?></div>
<?php if (!$Grid->Finish_Date->ReadOnly && !$Grid->Finish_Date->Disabled && !isset($Grid->Finish_Date->EditAttrs["readonly"]) && !isset($Grid->Finish_Date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fsubmission_monitorgrid", "datetimepicker"], function () {
    let format = "<?= DateFormat(0) ?>",
        options = {
            localization: {
                locale: ew.LANGUAGE_ID
            },
            display: {
                components: {
                    hours: !!format.match(/h/i),
                    minutes: !!format.match(/m/),
                    seconds: !!format.match(/s/i)
                },
                icons: {
                    previous: ew.IS_RTL ? "fas fa-chevron-right" : "fas fa-chevron-left",
                    next: ew.IS_RTL ? "fas fa-chevron-left" : "fas fa-chevron-right"
                }
            },
            meta: {
                format,
                numberingSystem: ew.getNumberingSystem()
            }
        };
    ew.createDateTimePicker("fsubmission_monitorgrid", "x<?= $Grid->RowIndex ?>_Finish_Date", jQuery.extend(true, {"useCurrent":false}, options));
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="submission_monitor" data-field="x_Finish_Date" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Finish_Date" id="o<?= $Grid->RowIndex ?>_Finish_Date" value="<?= HtmlEncode($Grid->Finish_Date->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_submission_monitor_Finish_Date" class="el_submission_monitor_Finish_Date">
<input type="<?= $Grid->Finish_Date->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_Finish_Date" id="x<?= $Grid->RowIndex ?>_Finish_Date" data-table="submission_monitor" data-field="x_Finish_Date" value="<?= $Grid->Finish_Date->EditValue ?>" placeholder="<?= HtmlEncode($Grid->Finish_Date->getPlaceHolder()) ?>"<?= $Grid->Finish_Date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->Finish_Date->getErrorMessage() ?></div>
<?php if (!$Grid->Finish_Date->ReadOnly && !$Grid->Finish_Date->Disabled && !isset($Grid->Finish_Date->EditAttrs["readonly"]) && !isset($Grid->Finish_Date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fsubmission_monitorgrid", "datetimepicker"], function () {
    let format = "<?= DateFormat(0) ?>",
        options = {
            localization: {
                locale: ew.LANGUAGE_ID
            },
            display: {
                components: {
                    hours: !!format.match(/h/i),
                    minutes: !!format.match(/m/),
                    seconds: !!format.match(/s/i)
                },
                icons: {
                    previous: ew.IS_RTL ? "fas fa-chevron-right" : "fas fa-chevron-left",
                    next: ew.IS_RTL ? "fas fa-chevron-left" : "fas fa-chevron-right"
                }
            },
            meta: {
                format,
                numberingSystem: ew.getNumberingSystem()
            }
        };
    ew.createDateTimePicker("fsubmission_monitorgrid", "x<?= $Grid->RowIndex ?>_Finish_Date", jQuery.extend(true, {"useCurrent":false}, options));
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_submission_monitor_Finish_Date" class="el_submission_monitor_Finish_Date">
<span<?= $Grid->Finish_Date->viewAttributes() ?>>
<?= $Grid->Finish_Date->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="submission_monitor" data-field="x_Finish_Date" data-hidden="1" name="fsubmission_monitorgrid$x<?= $Grid->RowIndex ?>_Finish_Date" id="fsubmission_monitorgrid$x<?= $Grid->RowIndex ?>_Finish_Date" value="<?= HtmlEncode($Grid->Finish_Date->FormValue) ?>">
<input type="hidden" data-table="submission_monitor" data-field="x_Finish_Date" data-hidden="1" name="fsubmission_monitorgrid$o<?= $Grid->RowIndex ?>_Finish_Date" id="fsubmission_monitorgrid$o<?= $Grid->RowIndex ?>_Finish_Date" value="<?= HtmlEncode($Grid->Finish_Date->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->Uploads->Visible) { // Uploads ?>
        <td data-name="Uploads"<?= $Grid->Uploads->cellAttributes() ?>>
<?php if ($Grid->RowAction == "insert") { // Add record ?>
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_submission_monitor_Uploads" class="el_submission_monitor_Uploads">
<div id="fd_x<?= $Grid->RowIndex ?>_Uploads" class="fileinput-button ew-file-drop-zone">
    <input type="file" class="form-control ew-file-input" title="<?= $Grid->Uploads->title() ?>" data-table="submission_monitor" data-field="x_Uploads" name="x<?= $Grid->RowIndex ?>_Uploads" id="x<?= $Grid->RowIndex ?>_Uploads" lang="<?= CurrentLanguageID() ?>" multiple<?= $Grid->Uploads->editAttributes() ?><?= ($Grid->Uploads->ReadOnly || $Grid->Uploads->Disabled) ? " disabled" : "" ?>>
    <div class="text-muted ew-file-text"><?= $Language->phrase("ChooseFiles") ?></div>
</div>
<div class="invalid-feedback"><?= $Grid->Uploads->getErrorMessage() ?></div>
<input type="hidden" name="fn_x<?= $Grid->RowIndex ?>_Uploads" id= "fn_x<?= $Grid->RowIndex ?>_Uploads" value="<?= $Grid->Uploads->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Grid->RowIndex ?>_Uploads" id= "fa_x<?= $Grid->RowIndex ?>_Uploads" value="0">
<input type="hidden" name="fs_x<?= $Grid->RowIndex ?>_Uploads" id= "fs_x<?= $Grid->RowIndex ?>_Uploads" value="255">
<input type="hidden" name="fx_x<?= $Grid->RowIndex ?>_Uploads" id= "fx_x<?= $Grid->RowIndex ?>_Uploads" value="<?= $Grid->Uploads->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?= $Grid->RowIndex ?>_Uploads" id= "fm_x<?= $Grid->RowIndex ?>_Uploads" value="<?= $Grid->Uploads->UploadMaxFileSize ?>">
<input type="hidden" name="fc_x<?= $Grid->RowIndex ?>_Uploads" id= "fc_x<?= $Grid->RowIndex ?>_Uploads" value="<?= $Grid->Uploads->UploadMaxFileCount ?>">
<table id="ft_x<?= $Grid->RowIndex ?>_Uploads" class="table table-sm float-start ew-upload-table"><tbody class="files"></tbody></table>
</span>
<?php } else { ?>
<span id="el$rowindex$_submission_monitor_Uploads" class="el_submission_monitor_Uploads">
<div id="fd_x<?= $Grid->RowIndex ?>_Uploads">
    <input type="file" class="form-control ew-file-input d-none" title="<?= $Grid->Uploads->title() ?>" data-table="submission_monitor" data-field="x_Uploads" name="x<?= $Grid->RowIndex ?>_Uploads" id="x<?= $Grid->RowIndex ?>_Uploads" lang="<?= CurrentLanguageID() ?>" multiple<?= $Grid->Uploads->editAttributes() ?>>
</div>
<div class="invalid-feedback"><?= $Grid->Uploads->getErrorMessage() ?></div>
<input type="hidden" name="fn_x<?= $Grid->RowIndex ?>_Uploads" id= "fn_x<?= $Grid->RowIndex ?>_Uploads" value="<?= $Grid->Uploads->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Grid->RowIndex ?>_Uploads" id= "fa_x<?= $Grid->RowIndex ?>_Uploads" value="0">
<input type="hidden" name="fs_x<?= $Grid->RowIndex ?>_Uploads" id= "fs_x<?= $Grid->RowIndex ?>_Uploads" value="255">
<input type="hidden" name="fx_x<?= $Grid->RowIndex ?>_Uploads" id= "fx_x<?= $Grid->RowIndex ?>_Uploads" value="<?= $Grid->Uploads->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?= $Grid->RowIndex ?>_Uploads" id= "fm_x<?= $Grid->RowIndex ?>_Uploads" value="<?= $Grid->Uploads->UploadMaxFileSize ?>">
<input type="hidden" name="fc_x<?= $Grid->RowIndex ?>_Uploads" id= "fc_x<?= $Grid->RowIndex ?>_Uploads" value="<?= $Grid->Uploads->UploadMaxFileCount ?>">
<table id="ft_x<?= $Grid->RowIndex ?>_Uploads" class="table table-sm float-start ew-upload-table"><tbody class="files"></tbody></table>
</span>
<?php } ?>
<input type="hidden" data-table="submission_monitor" data-field="x_Uploads" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Uploads" id="o<?= $Grid->RowIndex ?>_Uploads" value="<?= HtmlEncode($Grid->Uploads->OldValue) ?>">
<?php } elseif ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_submission_monitor_Uploads" class="el_submission_monitor_Uploads">
<span<?= $Grid->Uploads->viewAttributes() ?>>
<?= GetFileViewTag($Grid->Uploads, $Grid->Uploads->getViewValue(), false) ?>
</span>
</span>
<?php } else  { // Edit record ?>
<?php if (!$Grid->isConfirm()) { ?>
<span id="el<?= $Grid->RowCount ?>_submission_monitor_Uploads" class="el_submission_monitor_Uploads">
<div id="fd_x<?= $Grid->RowIndex ?>_Uploads">
    <input type="file" class="form-control ew-file-input d-none" title="<?= $Grid->Uploads->title() ?>" data-table="submission_monitor" data-field="x_Uploads" name="x<?= $Grid->RowIndex ?>_Uploads" id="x<?= $Grid->RowIndex ?>_Uploads" lang="<?= CurrentLanguageID() ?>" multiple<?= $Grid->Uploads->editAttributes() ?>>
</div>
<div class="invalid-feedback"><?= $Grid->Uploads->getErrorMessage() ?></div>
<input type="hidden" name="fn_x<?= $Grid->RowIndex ?>_Uploads" id= "fn_x<?= $Grid->RowIndex ?>_Uploads" value="<?= $Grid->Uploads->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Grid->RowIndex ?>_Uploads" id= "fa_x<?= $Grid->RowIndex ?>_Uploads" value="<?= (Post("fa_x<?= $Grid->RowIndex ?>_Uploads") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x<?= $Grid->RowIndex ?>_Uploads" id= "fs_x<?= $Grid->RowIndex ?>_Uploads" value="255">
<input type="hidden" name="fx_x<?= $Grid->RowIndex ?>_Uploads" id= "fx_x<?= $Grid->RowIndex ?>_Uploads" value="<?= $Grid->Uploads->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?= $Grid->RowIndex ?>_Uploads" id= "fm_x<?= $Grid->RowIndex ?>_Uploads" value="<?= $Grid->Uploads->UploadMaxFileSize ?>">
<input type="hidden" name="fc_x<?= $Grid->RowIndex ?>_Uploads" id= "fc_x<?= $Grid->RowIndex ?>_Uploads" value="<?= $Grid->Uploads->UploadMaxFileCount ?>">
<table id="ft_x<?= $Grid->RowIndex ?>_Uploads" class="table table-sm float-start ew-upload-table"><tbody class="files"></tbody></table>
</span>
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_submission_monitor_Uploads" class="el_submission_monitor_Uploads">
<div id="fd_x<?= $Grid->RowIndex ?>_Uploads">
    <input type="file" class="form-control ew-file-input d-none" title="<?= $Grid->Uploads->title() ?>" data-table="submission_monitor" data-field="x_Uploads" name="x<?= $Grid->RowIndex ?>_Uploads" id="x<?= $Grid->RowIndex ?>_Uploads" lang="<?= CurrentLanguageID() ?>" multiple<?= $Grid->Uploads->editAttributes() ?>>
</div>
<div class="invalid-feedback"><?= $Grid->Uploads->getErrorMessage() ?></div>
<input type="hidden" name="fn_x<?= $Grid->RowIndex ?>_Uploads" id= "fn_x<?= $Grid->RowIndex ?>_Uploads" value="<?= $Grid->Uploads->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Grid->RowIndex ?>_Uploads" id= "fa_x<?= $Grid->RowIndex ?>_Uploads" value="<?= (Post("fa_x<?= $Grid->RowIndex ?>_Uploads") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x<?= $Grid->RowIndex ?>_Uploads" id= "fs_x<?= $Grid->RowIndex ?>_Uploads" value="255">
<input type="hidden" name="fx_x<?= $Grid->RowIndex ?>_Uploads" id= "fx_x<?= $Grid->RowIndex ?>_Uploads" value="<?= $Grid->Uploads->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?= $Grid->RowIndex ?>_Uploads" id= "fm_x<?= $Grid->RowIndex ?>_Uploads" value="<?= $Grid->Uploads->UploadMaxFileSize ?>">
<input type="hidden" name="fc_x<?= $Grid->RowIndex ?>_Uploads" id= "fc_x<?= $Grid->RowIndex ?>_Uploads" value="<?= $Grid->Uploads->UploadMaxFileCount ?>">
<table id="ft_x<?= $Grid->RowIndex ?>_Uploads" class="table table-sm float-start ew-upload-table"><tbody class="files"></tbody></table>
</span>
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->Updated_Username->Visible) { // Updated_Username ?>
        <td data-name="Updated_Username"<?= $Grid->Updated_Username->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="submission_monitor" data-field="x_Updated_Username" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Updated_Username" id="o<?= $Grid->RowIndex ?>_Updated_Username" value="<?= HtmlEncode($Grid->Updated_Username->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_submission_monitor_Updated_Username" class="el_submission_monitor_Updated_Username">
<span<?= $Grid->Updated_Username->viewAttributes() ?>>
<?= $Grid->Updated_Username->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="submission_monitor" data-field="x_Updated_Username" data-hidden="1" name="fsubmission_monitorgrid$x<?= $Grid->RowIndex ?>_Updated_Username" id="fsubmission_monitorgrid$x<?= $Grid->RowIndex ?>_Updated_Username" value="<?= HtmlEncode($Grid->Updated_Username->FormValue) ?>">
<input type="hidden" data-table="submission_monitor" data-field="x_Updated_Username" data-hidden="1" name="fsubmission_monitorgrid$o<?= $Grid->RowIndex ?>_Updated_Username" id="fsubmission_monitorgrid$o<?= $Grid->RowIndex ?>_Updated_Username" value="<?= HtmlEncode($Grid->Updated_Username->OldValue) ?>">
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
loadjs.ready(["fsubmission_monitorgrid","load"], () => fsubmission_monitorgrid.updateLists(<?= $Grid->RowIndex ?>));
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
    $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowIndex, "id" => "r0_submission_monitor", "data-rowtype" => ROWTYPE_ADD]);
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
    <?php if ($Grid->Monitor_ID->Visible) { // Monitor_ID ?>
        <td data-name="Monitor_ID">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_submission_monitor_Monitor_ID" class="el_submission_monitor_Monitor_ID"></span>
<?php } else { ?>
<span id="el$rowindex$_submission_monitor_Monitor_ID" class="el_submission_monitor_Monitor_ID">
<span<?= $Grid->Monitor_ID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->Monitor_ID->getDisplayValue($Grid->Monitor_ID->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="submission_monitor" data-field="x_Monitor_ID" data-hidden="1" name="x<?= $Grid->RowIndex ?>_Monitor_ID" id="x<?= $Grid->RowIndex ?>_Monitor_ID" value="<?= HtmlEncode($Grid->Monitor_ID->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="submission_monitor" data-field="x_Monitor_ID" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Monitor_ID" id="o<?= $Grid->RowIndex ?>_Monitor_ID" value="<?= HtmlEncode($Grid->Monitor_ID->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->Status->Visible) { // Status ?>
        <td data-name="Status">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_submission_monitor_Status" class="el_submission_monitor_Status">
    <select
        id="x<?= $Grid->RowIndex ?>_Status"
        name="x<?= $Grid->RowIndex ?>_Status"
        class="form-select ew-select<?= $Grid->Status->isInvalidClass() ?>"
        data-select2-id="fsubmission_monitorgrid_x<?= $Grid->RowIndex ?>_Status"
        data-table="submission_monitor"
        data-field="x_Status"
        data-value-separator="<?= $Grid->Status->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->Status->getPlaceHolder()) ?>"
        <?= $Grid->Status->editAttributes() ?>>
        <?= $Grid->Status->selectOptionListHtml("x{$Grid->RowIndex}_Status") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->Status->getErrorMessage() ?></div>
<script>
loadjs.ready("fsubmission_monitorgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_Status", selectId: "fsubmission_monitorgrid_x<?= $Grid->RowIndex ?>_Status" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fsubmission_monitorgrid.lists.Status.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_Status", form: "fsubmission_monitorgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_Status", form: "fsubmission_monitorgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.submission_monitor.fields.Status.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } else { ?>
<span id="el$rowindex$_submission_monitor_Status" class="el_submission_monitor_Status">
<span<?= $Grid->Status->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Grid->Status->getDisplayValue($Grid->Status->ViewValue) ?></span></span>
</span>
<input type="hidden" data-table="submission_monitor" data-field="x_Status" data-hidden="1" name="x<?= $Grid->RowIndex ?>_Status" id="x<?= $Grid->RowIndex ?>_Status" value="<?= HtmlEncode($Grid->Status->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="submission_monitor" data-field="x_Status" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Status" id="o<?= $Grid->RowIndex ?>_Status" value="<?= HtmlEncode($Grid->Status->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->Taskings->Visible) { // Taskings ?>
        <td data-name="Taskings">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_submission_monitor_Taskings" class="el_submission_monitor_Taskings">
<?php $Grid->Taskings->EditAttrs->appendClass("editor"); ?>
<textarea data-table="submission_monitor" data-field="x_Taskings" name="x<?= $Grid->RowIndex ?>_Taskings" id="x<?= $Grid->RowIndex ?>_Taskings" cols="35" rows="4" placeholder="<?= HtmlEncode($Grid->Taskings->getPlaceHolder()) ?>"<?= $Grid->Taskings->editAttributes() ?>><?= $Grid->Taskings->EditValue ?></textarea>
<div class="invalid-feedback"><?= $Grid->Taskings->getErrorMessage() ?></div>
<script>
loadjs.ready(["fsubmission_monitorgrid", "editor"], function() {
    ew.createEditor("fsubmission_monitorgrid", "x<?= $Grid->RowIndex ?>_Taskings", 0, 0, <?= $Grid->Taskings->ReadOnly || false ? "true" : "false" ?>);
});
</script>
</span>
<?php } else { ?>
<span id="el$rowindex$_submission_monitor_Taskings" class="el_submission_monitor_Taskings">
<span<?= $Grid->Taskings->viewAttributes() ?>>
<?= $Grid->Taskings->ViewValue ?></span>
</span>
<input type="hidden" data-table="submission_monitor" data-field="x_Taskings" data-hidden="1" name="x<?= $Grid->RowIndex ?>_Taskings" id="x<?= $Grid->RowIndex ?>_Taskings" value="<?= HtmlEncode($Grid->Taskings->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="submission_monitor" data-field="x_Taskings" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Taskings" id="o<?= $Grid->RowIndex ?>_Taskings" value="<?= HtmlEncode($Grid->Taskings->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->Start_Date->Visible) { // Start_Date ?>
        <td data-name="Start_Date">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_submission_monitor_Start_Date" class="el_submission_monitor_Start_Date">
<input type="<?= $Grid->Start_Date->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_Start_Date" id="x<?= $Grid->RowIndex ?>_Start_Date" data-table="submission_monitor" data-field="x_Start_Date" value="<?= $Grid->Start_Date->EditValue ?>" placeholder="<?= HtmlEncode($Grid->Start_Date->getPlaceHolder()) ?>"<?= $Grid->Start_Date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->Start_Date->getErrorMessage() ?></div>
<?php if (!$Grid->Start_Date->ReadOnly && !$Grid->Start_Date->Disabled && !isset($Grid->Start_Date->EditAttrs["readonly"]) && !isset($Grid->Start_Date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fsubmission_monitorgrid", "datetimepicker"], function () {
    let format = "<?= DateFormat(0) ?>",
        options = {
            localization: {
                locale: ew.LANGUAGE_ID
            },
            display: {
                components: {
                    hours: !!format.match(/h/i),
                    minutes: !!format.match(/m/),
                    seconds: !!format.match(/s/i)
                },
                icons: {
                    previous: ew.IS_RTL ? "fas fa-chevron-right" : "fas fa-chevron-left",
                    next: ew.IS_RTL ? "fas fa-chevron-left" : "fas fa-chevron-right"
                }
            },
            meta: {
                format,
                numberingSystem: ew.getNumberingSystem()
            }
        };
    ew.createDateTimePicker("fsubmission_monitorgrid", "x<?= $Grid->RowIndex ?>_Start_Date", jQuery.extend(true, {"useCurrent":false}, options));
});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_submission_monitor_Start_Date" class="el_submission_monitor_Start_Date">
<span<?= $Grid->Start_Date->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->Start_Date->getDisplayValue($Grid->Start_Date->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="submission_monitor" data-field="x_Start_Date" data-hidden="1" name="x<?= $Grid->RowIndex ?>_Start_Date" id="x<?= $Grid->RowIndex ?>_Start_Date" value="<?= HtmlEncode($Grid->Start_Date->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="submission_monitor" data-field="x_Start_Date" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Start_Date" id="o<?= $Grid->RowIndex ?>_Start_Date" value="<?= HtmlEncode($Grid->Start_Date->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->Finish_Date->Visible) { // Finish_Date ?>
        <td data-name="Finish_Date">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_submission_monitor_Finish_Date" class="el_submission_monitor_Finish_Date">
<input type="<?= $Grid->Finish_Date->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_Finish_Date" id="x<?= $Grid->RowIndex ?>_Finish_Date" data-table="submission_monitor" data-field="x_Finish_Date" value="<?= $Grid->Finish_Date->EditValue ?>" placeholder="<?= HtmlEncode($Grid->Finish_Date->getPlaceHolder()) ?>"<?= $Grid->Finish_Date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->Finish_Date->getErrorMessage() ?></div>
<?php if (!$Grid->Finish_Date->ReadOnly && !$Grid->Finish_Date->Disabled && !isset($Grid->Finish_Date->EditAttrs["readonly"]) && !isset($Grid->Finish_Date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fsubmission_monitorgrid", "datetimepicker"], function () {
    let format = "<?= DateFormat(0) ?>",
        options = {
            localization: {
                locale: ew.LANGUAGE_ID
            },
            display: {
                components: {
                    hours: !!format.match(/h/i),
                    minutes: !!format.match(/m/),
                    seconds: !!format.match(/s/i)
                },
                icons: {
                    previous: ew.IS_RTL ? "fas fa-chevron-right" : "fas fa-chevron-left",
                    next: ew.IS_RTL ? "fas fa-chevron-left" : "fas fa-chevron-right"
                }
            },
            meta: {
                format,
                numberingSystem: ew.getNumberingSystem()
            }
        };
    ew.createDateTimePicker("fsubmission_monitorgrid", "x<?= $Grid->RowIndex ?>_Finish_Date", jQuery.extend(true, {"useCurrent":false}, options));
});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_submission_monitor_Finish_Date" class="el_submission_monitor_Finish_Date">
<span<?= $Grid->Finish_Date->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->Finish_Date->getDisplayValue($Grid->Finish_Date->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="submission_monitor" data-field="x_Finish_Date" data-hidden="1" name="x<?= $Grid->RowIndex ?>_Finish_Date" id="x<?= $Grid->RowIndex ?>_Finish_Date" value="<?= HtmlEncode($Grid->Finish_Date->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="submission_monitor" data-field="x_Finish_Date" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Finish_Date" id="o<?= $Grid->RowIndex ?>_Finish_Date" value="<?= HtmlEncode($Grid->Finish_Date->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->Uploads->Visible) { // Uploads ?>
        <td data-name="Uploads">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_submission_monitor_Uploads" class="el_submission_monitor_Uploads">
<div id="fd_x<?= $Grid->RowIndex ?>_Uploads" class="fileinput-button ew-file-drop-zone">
    <input type="file" class="form-control ew-file-input" title="<?= $Grid->Uploads->title() ?>" data-table="submission_monitor" data-field="x_Uploads" name="x<?= $Grid->RowIndex ?>_Uploads" id="x<?= $Grid->RowIndex ?>_Uploads" lang="<?= CurrentLanguageID() ?>" multiple<?= $Grid->Uploads->editAttributes() ?><?= ($Grid->Uploads->ReadOnly || $Grid->Uploads->Disabled) ? " disabled" : "" ?>>
    <div class="text-muted ew-file-text"><?= $Language->phrase("ChooseFiles") ?></div>
</div>
<div class="invalid-feedback"><?= $Grid->Uploads->getErrorMessage() ?></div>
<input type="hidden" name="fn_x<?= $Grid->RowIndex ?>_Uploads" id= "fn_x<?= $Grid->RowIndex ?>_Uploads" value="<?= $Grid->Uploads->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Grid->RowIndex ?>_Uploads" id= "fa_x<?= $Grid->RowIndex ?>_Uploads" value="0">
<input type="hidden" name="fs_x<?= $Grid->RowIndex ?>_Uploads" id= "fs_x<?= $Grid->RowIndex ?>_Uploads" value="255">
<input type="hidden" name="fx_x<?= $Grid->RowIndex ?>_Uploads" id= "fx_x<?= $Grid->RowIndex ?>_Uploads" value="<?= $Grid->Uploads->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?= $Grid->RowIndex ?>_Uploads" id= "fm_x<?= $Grid->RowIndex ?>_Uploads" value="<?= $Grid->Uploads->UploadMaxFileSize ?>">
<input type="hidden" name="fc_x<?= $Grid->RowIndex ?>_Uploads" id= "fc_x<?= $Grid->RowIndex ?>_Uploads" value="<?= $Grid->Uploads->UploadMaxFileCount ?>">
<table id="ft_x<?= $Grid->RowIndex ?>_Uploads" class="table table-sm float-start ew-upload-table"><tbody class="files"></tbody></table>
</span>
<?php } else { ?>
<span id="el$rowindex$_submission_monitor_Uploads" class="el_submission_monitor_Uploads">
<div id="fd_x<?= $Grid->RowIndex ?>_Uploads">
    <input type="file" class="form-control ew-file-input d-none" title="<?= $Grid->Uploads->title() ?>" data-table="submission_monitor" data-field="x_Uploads" name="x<?= $Grid->RowIndex ?>_Uploads" id="x<?= $Grid->RowIndex ?>_Uploads" lang="<?= CurrentLanguageID() ?>" multiple<?= $Grid->Uploads->editAttributes() ?>>
</div>
<div class="invalid-feedback"><?= $Grid->Uploads->getErrorMessage() ?></div>
<input type="hidden" name="fn_x<?= $Grid->RowIndex ?>_Uploads" id= "fn_x<?= $Grid->RowIndex ?>_Uploads" value="<?= $Grid->Uploads->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Grid->RowIndex ?>_Uploads" id= "fa_x<?= $Grid->RowIndex ?>_Uploads" value="0">
<input type="hidden" name="fs_x<?= $Grid->RowIndex ?>_Uploads" id= "fs_x<?= $Grid->RowIndex ?>_Uploads" value="255">
<input type="hidden" name="fx_x<?= $Grid->RowIndex ?>_Uploads" id= "fx_x<?= $Grid->RowIndex ?>_Uploads" value="<?= $Grid->Uploads->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?= $Grid->RowIndex ?>_Uploads" id= "fm_x<?= $Grid->RowIndex ?>_Uploads" value="<?= $Grid->Uploads->UploadMaxFileSize ?>">
<input type="hidden" name="fc_x<?= $Grid->RowIndex ?>_Uploads" id= "fc_x<?= $Grid->RowIndex ?>_Uploads" value="<?= $Grid->Uploads->UploadMaxFileCount ?>">
<table id="ft_x<?= $Grid->RowIndex ?>_Uploads" class="table table-sm float-start ew-upload-table"><tbody class="files"></tbody></table>
</span>
<?php } ?>
<input type="hidden" data-table="submission_monitor" data-field="x_Uploads" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Uploads" id="o<?= $Grid->RowIndex ?>_Uploads" value="<?= HtmlEncode($Grid->Uploads->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->Updated_Username->Visible) { // Updated_Username ?>
        <td data-name="Updated_Username">
<?php if (!$Grid->isConfirm()) { ?>
<?php } else { ?>
<span id="el$rowindex$_submission_monitor_Updated_Username" class="el_submission_monitor_Updated_Username">
<span<?= $Grid->Updated_Username->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->Updated_Username->getDisplayValue($Grid->Updated_Username->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="submission_monitor" data-field="x_Updated_Username" data-hidden="1" name="x<?= $Grid->RowIndex ?>_Updated_Username" id="x<?= $Grid->RowIndex ?>_Updated_Username" value="<?= HtmlEncode($Grid->Updated_Username->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="submission_monitor" data-field="x_Updated_Username" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Updated_Username" id="o<?= $Grid->RowIndex ?>_Updated_Username" value="<?= HtmlEncode($Grid->Updated_Username->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowIndex);
?>
<script>
loadjs.ready(["fsubmission_monitorgrid","load"], () => fsubmission_monitorgrid.updateLists(<?= $Grid->RowIndex ?>, true));
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
<input type="hidden" name="detailpage" value="fsubmission_monitorgrid">
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
