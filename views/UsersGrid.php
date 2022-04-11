<?php

namespace PHPMaker2022\civichub2;

// Set up and run Grid object
$Grid = Container("UsersGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var fusersgrid;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fusersgrid = new ew.Form("fusersgrid", "grid");
    fusersgrid.formKeyCountName = "<?= $Grid->FormKeyCountName ?>";

    // Add fields
    var currentTable = <?= JsonEncode($Grid->toClientVar()) ?>;
    ew.deepAssign(ew.vars, { tables: { users: currentTable } });
    var fields = currentTable.fields;
    fusersgrid.addFields([
        ["_Username", [fields._Username.visible && fields._Username.required ? ew.Validators.required(fields._Username.caption) : null], fields._Username.isInvalid],
        ["First_Name", [fields.First_Name.visible && fields.First_Name.required ? ew.Validators.required(fields.First_Name.caption) : null], fields.First_Name.isInvalid],
        ["Last_Name", [fields.Last_Name.visible && fields.Last_Name.required ? ew.Validators.required(fields.Last_Name.caption) : null], fields.Last_Name.isInvalid],
        ["_Email", [fields._Email.visible && fields._Email.required ? ew.Validators.required(fields._Email.caption) : null], fields._Email.isInvalid],
        ["User_Level", [fields.User_Level.visible && fields.User_Level.required ? ew.Validators.required(fields.User_Level.caption) : null], fields.User_Level.isInvalid],
        ["Report_To", [fields.Report_To.visible && fields.Report_To.required ? ew.Validators.required(fields.Report_To.caption) : null], fields.Report_To.isInvalid],
        ["Activated", [fields.Activated.visible && fields.Activated.required ? ew.Validators.required(fields.Activated.caption) : null], fields.Activated.isInvalid],
        ["Locked", [fields.Locked.visible && fields.Locked.required ? ew.Validators.required(fields.Locked.caption) : null], fields.Locked.isInvalid]
    ]);

    // Check empty row
    fusersgrid.emptyRow = function (rowIndex) {
        var fobj = this.getForm(),
            fields = [["_Username",false],["First_Name",false],["Last_Name",false],["_Email",false],["User_Level",false],["Report_To",false],["Activated[]",true],["Locked[]",true]];
        if (fields.some(field => ew.valueChanged(fobj, rowIndex, ...field)))
            return false;
        return true;
    }

    // Form_CustomValidate
    fusersgrid.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fusersgrid.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    fusersgrid.lists.User_Level = <?= $Grid->User_Level->toClientList($Grid) ?>;
    fusersgrid.lists.Report_To = <?= $Grid->Report_To->toClientList($Grid) ?>;
    fusersgrid.lists.Activated = <?= $Grid->Activated->toClientList($Grid) ?>;
    fusersgrid.lists.Locked = <?= $Grid->Locked->toClientList($Grid) ?>;
    loadjs.done("fusersgrid");
});
</script>
<?php } ?>
<?php
$Grid->renderOtherOptions();
?>
<?php if ($Grid->TotalRecords > 0 || $Grid->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($Grid->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> users">
<div id="fusersgrid" class="ew-form ew-list-form">
<div id="gmp_users" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table id="tbl_usersgrid" class="table table-bordered table-hover table-sm ew-table"><!-- .ew-table -->
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
<?php if ($Grid->_Username->Visible) { // Username ?>
        <th data-name="_Username" class="<?= $Grid->_Username->headerCellClass() ?>"><div id="elh_users__Username" class="users__Username"><?= $Grid->renderFieldHeader($Grid->_Username) ?></div></th>
<?php } ?>
<?php if ($Grid->First_Name->Visible) { // First_Name ?>
        <th data-name="First_Name" class="<?= $Grid->First_Name->headerCellClass() ?>"><div id="elh_users_First_Name" class="users_First_Name"><?= $Grid->renderFieldHeader($Grid->First_Name) ?></div></th>
<?php } ?>
<?php if ($Grid->Last_Name->Visible) { // Last_Name ?>
        <th data-name="Last_Name" class="<?= $Grid->Last_Name->headerCellClass() ?>"><div id="elh_users_Last_Name" class="users_Last_Name"><?= $Grid->renderFieldHeader($Grid->Last_Name) ?></div></th>
<?php } ?>
<?php if ($Grid->_Email->Visible) { // Email ?>
        <th data-name="_Email" class="<?= $Grid->_Email->headerCellClass() ?>"><div id="elh_users__Email" class="users__Email"><?= $Grid->renderFieldHeader($Grid->_Email) ?></div></th>
<?php } ?>
<?php if ($Grid->User_Level->Visible) { // User_Level ?>
        <th data-name="User_Level" class="<?= $Grid->User_Level->headerCellClass() ?>"><div id="elh_users_User_Level" class="users_User_Level"><?= $Grid->renderFieldHeader($Grid->User_Level) ?></div></th>
<?php } ?>
<?php if ($Grid->Report_To->Visible) { // Report_To ?>
        <th data-name="Report_To" class="<?= $Grid->Report_To->headerCellClass() ?>"><div id="elh_users_Report_To" class="users_Report_To"><?= $Grid->renderFieldHeader($Grid->Report_To) ?></div></th>
<?php } ?>
<?php if ($Grid->Activated->Visible) { // Activated ?>
        <th data-name="Activated" class="<?= $Grid->Activated->headerCellClass() ?>"><div id="elh_users_Activated" class="users_Activated"><?= $Grid->renderFieldHeader($Grid->Activated) ?></div></th>
<?php } ?>
<?php if ($Grid->Locked->Visible) { // Locked ?>
        <th data-name="Locked" class="<?= $Grid->Locked->headerCellClass() ?>"><div id="elh_users_Locked" class="users_Locked"><?= $Grid->renderFieldHeader($Grid->Locked) ?></div></th>
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
            "id" => "r" . $Grid->RowCount . "_users",
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
    <?php if ($Grid->_Username->Visible) { // Username ?>
        <td data-name="_Username"<?= $Grid->_Username->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<?php if (!$Security->isAdmin() && $Security->isLoggedIn() && !$Grid->userIDAllow("grid")) { // Non system admin ?>
<span id="el<?= $Grid->RowCount ?>_users__Username" class="el_users__Username">
    <select
        id="x<?= $Grid->RowIndex ?>__Username"
        name="x<?= $Grid->RowIndex ?>__Username"
        class="form-select ew-select<?= $Grid->_Username->isInvalidClass() ?>"
        data-select2-id="fusersgrid_x<?= $Grid->RowIndex ?>__Username"
        data-table="users"
        data-field="x__Username"
        data-value-separator="<?= $Grid->_Username->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->_Username->getPlaceHolder()) ?>"
        <?= $Grid->_Username->editAttributes() ?>>
        <?= $Grid->_Username->selectOptionListHtml("x{$Grid->RowIndex}__Username") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->_Username->getErrorMessage() ?></div>
<script>
loadjs.ready("fusersgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>__Username", selectId: "fusersgrid_x<?= $Grid->RowIndex ?>__Username" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fusersgrid.lists._Username.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>__Username", form: "fusersgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>__Username", form: "fusersgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.users.fields._Username.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_users__Username" class="el_users__Username">
<input type="<?= $Grid->_Username->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>__Username" id="x<?= $Grid->RowIndex ?>__Username" data-table="users" data-field="x__Username" value="<?= $Grid->_Username->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->_Username->getPlaceHolder()) ?>"<?= $Grid->_Username->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->_Username->getErrorMessage() ?></div>
</span>
<?php } ?>
<input type="hidden" data-table="users" data-field="x__Username" data-hidden="1" name="o<?= $Grid->RowIndex ?>__Username" id="o<?= $Grid->RowIndex ?>__Username" value="<?= HtmlEncode($Grid->_Username->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<input type="<?= $Grid->_Username->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>__Username" id="x<?= $Grid->RowIndex ?>__Username" data-table="users" data-field="x__Username" value="<?= $Grid->_Username->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->_Username->getPlaceHolder()) ?>"<?= $Grid->_Username->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->_Username->getErrorMessage() ?></div>
<input type="hidden" data-table="users" data-field="x__Username" data-hidden="1" name="o<?= $Grid->RowIndex ?>__Username" id="o<?= $Grid->RowIndex ?>__Username" value="<?= HtmlEncode($Grid->_Username->OldValue ?? $Grid->_Username->CurrentValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_users__Username" class="el_users__Username">
<span<?= $Grid->_Username->viewAttributes() ?>>
<?= $Grid->_Username->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="users" data-field="x__Username" data-hidden="1" name="fusersgrid$x<?= $Grid->RowIndex ?>__Username" id="fusersgrid$x<?= $Grid->RowIndex ?>__Username" value="<?= HtmlEncode($Grid->_Username->FormValue) ?>">
<input type="hidden" data-table="users" data-field="x__Username" data-hidden="1" name="fusersgrid$o<?= $Grid->RowIndex ?>__Username" id="fusersgrid$o<?= $Grid->RowIndex ?>__Username" value="<?= HtmlEncode($Grid->_Username->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } else { ?>
            <input type="hidden" data-table="users" data-field="x__Username" data-hidden="1" name="x<?= $Grid->RowIndex ?>__Username" id="x<?= $Grid->RowIndex ?>__Username" value="<?= HtmlEncode($Grid->_Username->CurrentValue) ?>">
    <?php } ?>
    <?php if ($Grid->First_Name->Visible) { // First_Name ?>
        <td data-name="First_Name"<?= $Grid->First_Name->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_users_First_Name" class="el_users_First_Name">
<input type="<?= $Grid->First_Name->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_First_Name" id="x<?= $Grid->RowIndex ?>_First_Name" data-table="users" data-field="x_First_Name" value="<?= $Grid->First_Name->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->First_Name->getPlaceHolder()) ?>"<?= $Grid->First_Name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->First_Name->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="users" data-field="x_First_Name" data-hidden="1" name="o<?= $Grid->RowIndex ?>_First_Name" id="o<?= $Grid->RowIndex ?>_First_Name" value="<?= HtmlEncode($Grid->First_Name->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_users_First_Name" class="el_users_First_Name">
<input type="<?= $Grid->First_Name->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_First_Name" id="x<?= $Grid->RowIndex ?>_First_Name" data-table="users" data-field="x_First_Name" value="<?= $Grid->First_Name->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->First_Name->getPlaceHolder()) ?>"<?= $Grid->First_Name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->First_Name->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_users_First_Name" class="el_users_First_Name">
<span<?= $Grid->First_Name->viewAttributes() ?>>
<?= $Grid->First_Name->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="users" data-field="x_First_Name" data-hidden="1" name="fusersgrid$x<?= $Grid->RowIndex ?>_First_Name" id="fusersgrid$x<?= $Grid->RowIndex ?>_First_Name" value="<?= HtmlEncode($Grid->First_Name->FormValue) ?>">
<input type="hidden" data-table="users" data-field="x_First_Name" data-hidden="1" name="fusersgrid$o<?= $Grid->RowIndex ?>_First_Name" id="fusersgrid$o<?= $Grid->RowIndex ?>_First_Name" value="<?= HtmlEncode($Grid->First_Name->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->Last_Name->Visible) { // Last_Name ?>
        <td data-name="Last_Name"<?= $Grid->Last_Name->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_users_Last_Name" class="el_users_Last_Name">
<input type="<?= $Grid->Last_Name->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_Last_Name" id="x<?= $Grid->RowIndex ?>_Last_Name" data-table="users" data-field="x_Last_Name" value="<?= $Grid->Last_Name->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->Last_Name->getPlaceHolder()) ?>"<?= $Grid->Last_Name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->Last_Name->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="users" data-field="x_Last_Name" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Last_Name" id="o<?= $Grid->RowIndex ?>_Last_Name" value="<?= HtmlEncode($Grid->Last_Name->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_users_Last_Name" class="el_users_Last_Name">
<input type="<?= $Grid->Last_Name->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_Last_Name" id="x<?= $Grid->RowIndex ?>_Last_Name" data-table="users" data-field="x_Last_Name" value="<?= $Grid->Last_Name->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->Last_Name->getPlaceHolder()) ?>"<?= $Grid->Last_Name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->Last_Name->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_users_Last_Name" class="el_users_Last_Name">
<span<?= $Grid->Last_Name->viewAttributes() ?>>
<?= $Grid->Last_Name->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="users" data-field="x_Last_Name" data-hidden="1" name="fusersgrid$x<?= $Grid->RowIndex ?>_Last_Name" id="fusersgrid$x<?= $Grid->RowIndex ?>_Last_Name" value="<?= HtmlEncode($Grid->Last_Name->FormValue) ?>">
<input type="hidden" data-table="users" data-field="x_Last_Name" data-hidden="1" name="fusersgrid$o<?= $Grid->RowIndex ?>_Last_Name" id="fusersgrid$o<?= $Grid->RowIndex ?>_Last_Name" value="<?= HtmlEncode($Grid->Last_Name->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->_Email->Visible) { // Email ?>
        <td data-name="_Email"<?= $Grid->_Email->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_users__Email" class="el_users__Email">
<input type="<?= $Grid->_Email->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>__Email" id="x<?= $Grid->RowIndex ?>__Email" data-table="users" data-field="x__Email" value="<?= $Grid->_Email->EditValue ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Grid->_Email->getPlaceHolder()) ?>"<?= $Grid->_Email->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->_Email->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="users" data-field="x__Email" data-hidden="1" name="o<?= $Grid->RowIndex ?>__Email" id="o<?= $Grid->RowIndex ?>__Email" value="<?= HtmlEncode($Grid->_Email->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_users__Email" class="el_users__Email">
<input type="<?= $Grid->_Email->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>__Email" id="x<?= $Grid->RowIndex ?>__Email" data-table="users" data-field="x__Email" value="<?= $Grid->_Email->EditValue ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Grid->_Email->getPlaceHolder()) ?>"<?= $Grid->_Email->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->_Email->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_users__Email" class="el_users__Email">
<span<?= $Grid->_Email->viewAttributes() ?>>
<?= $Grid->_Email->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="users" data-field="x__Email" data-hidden="1" name="fusersgrid$x<?= $Grid->RowIndex ?>__Email" id="fusersgrid$x<?= $Grid->RowIndex ?>__Email" value="<?= HtmlEncode($Grid->_Email->FormValue) ?>">
<input type="hidden" data-table="users" data-field="x__Email" data-hidden="1" name="fusersgrid$o<?= $Grid->RowIndex ?>__Email" id="fusersgrid$o<?= $Grid->RowIndex ?>__Email" value="<?= HtmlEncode($Grid->_Email->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->User_Level->Visible) { // User_Level ?>
        <td data-name="User_Level"<?= $Grid->User_Level->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<?php if ($Grid->User_Level->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowCount ?>_users_User_Level" class="el_users_User_Level">
<span<?= $Grid->User_Level->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Grid->User_Level->getDisplayValue($Grid->User_Level->ViewValue) ?></span></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_User_Level" name="x<?= $Grid->RowIndex ?>_User_Level" value="<?= HtmlEncode(FormatNumber($Grid->User_Level->CurrentValue, $Grid->User_Level->formatPattern())) ?>" data-hidden="1">
<?php } elseif (!$Security->isAdmin() && $Security->isLoggedIn()) { // Non system admin ?>
<span id="el<?= $Grid->RowCount ?>_users_User_Level" class="el_users_User_Level">
<span class="form-control-plaintext"><?= $Grid->User_Level->getDisplayValue($Grid->User_Level->EditValue) ?></span>
</span>
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_users_User_Level" class="el_users_User_Level">
    <select
        id="x<?= $Grid->RowIndex ?>_User_Level"
        name="x<?= $Grid->RowIndex ?>_User_Level"
        class="form-select ew-select<?= $Grid->User_Level->isInvalidClass() ?>"
        data-select2-id="fusersgrid_x<?= $Grid->RowIndex ?>_User_Level"
        data-table="users"
        data-field="x_User_Level"
        data-value-separator="<?= $Grid->User_Level->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->User_Level->getPlaceHolder()) ?>"
        <?= $Grid->User_Level->editAttributes() ?>>
        <?= $Grid->User_Level->selectOptionListHtml("x{$Grid->RowIndex}_User_Level") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->User_Level->getErrorMessage() ?></div>
<?= $Grid->User_Level->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_User_Level") ?>
<script>
loadjs.ready("fusersgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_User_Level", selectId: "fusersgrid_x<?= $Grid->RowIndex ?>_User_Level" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fusersgrid.lists.User_Level.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_User_Level", form: "fusersgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_User_Level", form: "fusersgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.users.fields.User_Level.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<input type="hidden" data-table="users" data-field="x_User_Level" data-hidden="1" name="o<?= $Grid->RowIndex ?>_User_Level" id="o<?= $Grid->RowIndex ?>_User_Level" value="<?= HtmlEncode($Grid->User_Level->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<?php if ($Grid->User_Level->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowCount ?>_users_User_Level" class="el_users_User_Level">
<span<?= $Grid->User_Level->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Grid->User_Level->getDisplayValue($Grid->User_Level->ViewValue) ?></span></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_User_Level" name="x<?= $Grid->RowIndex ?>_User_Level" value="<?= HtmlEncode(FormatNumber($Grid->User_Level->CurrentValue, $Grid->User_Level->formatPattern())) ?>" data-hidden="1">
<?php } elseif (!$Security->isAdmin() && $Security->isLoggedIn()) { // Non system admin ?>
<span id="el<?= $Grid->RowCount ?>_users_User_Level" class="el_users_User_Level">
<span class="form-control-plaintext"><?= $Grid->User_Level->getDisplayValue($Grid->User_Level->EditValue) ?></span>
</span>
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_users_User_Level" class="el_users_User_Level">
    <select
        id="x<?= $Grid->RowIndex ?>_User_Level"
        name="x<?= $Grid->RowIndex ?>_User_Level"
        class="form-select ew-select<?= $Grid->User_Level->isInvalidClass() ?>"
        data-select2-id="fusersgrid_x<?= $Grid->RowIndex ?>_User_Level"
        data-table="users"
        data-field="x_User_Level"
        data-value-separator="<?= $Grid->User_Level->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->User_Level->getPlaceHolder()) ?>"
        <?= $Grid->User_Level->editAttributes() ?>>
        <?= $Grid->User_Level->selectOptionListHtml("x{$Grid->RowIndex}_User_Level") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->User_Level->getErrorMessage() ?></div>
<?= $Grid->User_Level->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_User_Level") ?>
<script>
loadjs.ready("fusersgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_User_Level", selectId: "fusersgrid_x<?= $Grid->RowIndex ?>_User_Level" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fusersgrid.lists.User_Level.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_User_Level", form: "fusersgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_User_Level", form: "fusersgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.users.fields.User_Level.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_users_User_Level" class="el_users_User_Level">
<span<?= $Grid->User_Level->viewAttributes() ?>>
<?= $Grid->User_Level->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="users" data-field="x_User_Level" data-hidden="1" name="fusersgrid$x<?= $Grid->RowIndex ?>_User_Level" id="fusersgrid$x<?= $Grid->RowIndex ?>_User_Level" value="<?= HtmlEncode($Grid->User_Level->FormValue) ?>">
<input type="hidden" data-table="users" data-field="x_User_Level" data-hidden="1" name="fusersgrid$o<?= $Grid->RowIndex ?>_User_Level" id="fusersgrid$o<?= $Grid->RowIndex ?>_User_Level" value="<?= HtmlEncode($Grid->User_Level->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->Report_To->Visible) { // Report_To ?>
        <td data-name="Report_To"<?= $Grid->Report_To->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<?php if (!$Security->isAdmin() && $Security->isLoggedIn()) { // Non system admin ?>
<span id="el<?= $Grid->RowCount ?>_users_Report_To" class="el_users_Report_To">
    <select
        id="x<?= $Grid->RowIndex ?>_Report_To"
        name="x<?= $Grid->RowIndex ?>_Report_To"
        class="form-select ew-select<?= $Grid->Report_To->isInvalidClass() ?>"
        data-select2-id="fusersgrid_x<?= $Grid->RowIndex ?>_Report_To"
        data-table="users"
        data-field="x_Report_To"
        data-value-separator="<?= $Grid->Report_To->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->Report_To->getPlaceHolder()) ?>"
        <?= $Grid->Report_To->editAttributes() ?>>
        <?= $Grid->Report_To->selectOptionListHtml("x{$Grid->RowIndex}_Report_To") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->Report_To->getErrorMessage() ?></div>
<?= $Grid->Report_To->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_Report_To") ?>
<script>
loadjs.ready("fusersgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_Report_To", selectId: "fusersgrid_x<?= $Grid->RowIndex ?>_Report_To" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fusersgrid.lists.Report_To.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_Report_To", form: "fusersgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_Report_To", form: "fusersgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.users.fields.Report_To.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_users_Report_To" class="el_users_Report_To">
    <select
        id="x<?= $Grid->RowIndex ?>_Report_To"
        name="x<?= $Grid->RowIndex ?>_Report_To"
        class="form-select ew-select<?= $Grid->Report_To->isInvalidClass() ?>"
        data-select2-id="fusersgrid_x<?= $Grid->RowIndex ?>_Report_To"
        data-table="users"
        data-field="x_Report_To"
        data-value-separator="<?= $Grid->Report_To->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->Report_To->getPlaceHolder()) ?>"
        <?= $Grid->Report_To->editAttributes() ?>>
        <?= $Grid->Report_To->selectOptionListHtml("x{$Grid->RowIndex}_Report_To") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->Report_To->getErrorMessage() ?></div>
<?= $Grid->Report_To->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_Report_To") ?>
<script>
loadjs.ready("fusersgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_Report_To", selectId: "fusersgrid_x<?= $Grid->RowIndex ?>_Report_To" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fusersgrid.lists.Report_To.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_Report_To", form: "fusersgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_Report_To", form: "fusersgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.users.fields.Report_To.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<input type="hidden" data-table="users" data-field="x_Report_To" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Report_To" id="o<?= $Grid->RowIndex ?>_Report_To" value="<?= HtmlEncode($Grid->Report_To->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<?php if (!$Security->isAdmin() && $Security->isLoggedIn()) { // Non system admin ?>
<?php if (SameString($Grid->_Username->CurrentValue, CurrentUserID())) { ?>
    <span id="el<?= $Grid->RowCount ?>_users_Report_To" class="el_users_Report_To">
    <span<?= $Grid->Report_To->viewAttributes() ?>>
    <span class="form-control-plaintext"><?= $Grid->Report_To->getDisplayValue($Grid->Report_To->EditValue) ?></span></span>
    </span>
    <input type="hidden" data-table="users" data-field="x_Report_To" data-hidden="1" name="x<?= $Grid->RowIndex ?>_Report_To" id="x<?= $Grid->RowIndex ?>_Report_To" value="<?= HtmlEncode($Grid->Report_To->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_users_Report_To" class="el_users_Report_To">
    <select
        id="x<?= $Grid->RowIndex ?>_Report_To"
        name="x<?= $Grid->RowIndex ?>_Report_To"
        class="form-select ew-select<?= $Grid->Report_To->isInvalidClass() ?>"
        data-select2-id="fusersgrid_x<?= $Grid->RowIndex ?>_Report_To"
        data-table="users"
        data-field="x_Report_To"
        data-value-separator="<?= $Grid->Report_To->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->Report_To->getPlaceHolder()) ?>"
        <?= $Grid->Report_To->editAttributes() ?>>
        <?= $Grid->Report_To->selectOptionListHtml("x{$Grid->RowIndex}_Report_To") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->Report_To->getErrorMessage() ?></div>
<?= $Grid->Report_To->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_Report_To") ?>
<script>
loadjs.ready("fusersgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_Report_To", selectId: "fusersgrid_x<?= $Grid->RowIndex ?>_Report_To" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fusersgrid.lists.Report_To.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_Report_To", form: "fusersgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_Report_To", form: "fusersgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.users.fields.Report_To.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_users_Report_To" class="el_users_Report_To">
    <select
        id="x<?= $Grid->RowIndex ?>_Report_To"
        name="x<?= $Grid->RowIndex ?>_Report_To"
        class="form-select ew-select<?= $Grid->Report_To->isInvalidClass() ?>"
        data-select2-id="fusersgrid_x<?= $Grid->RowIndex ?>_Report_To"
        data-table="users"
        data-field="x_Report_To"
        data-value-separator="<?= $Grid->Report_To->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->Report_To->getPlaceHolder()) ?>"
        <?= $Grid->Report_To->editAttributes() ?>>
        <?= $Grid->Report_To->selectOptionListHtml("x{$Grid->RowIndex}_Report_To") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->Report_To->getErrorMessage() ?></div>
<?= $Grid->Report_To->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_Report_To") ?>
<script>
loadjs.ready("fusersgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_Report_To", selectId: "fusersgrid_x<?= $Grid->RowIndex ?>_Report_To" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fusersgrid.lists.Report_To.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_Report_To", form: "fusersgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_Report_To", form: "fusersgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.users.fields.Report_To.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_users_Report_To" class="el_users_Report_To">
<span<?= $Grid->Report_To->viewAttributes() ?>>
<?= $Grid->Report_To->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="users" data-field="x_Report_To" data-hidden="1" name="fusersgrid$x<?= $Grid->RowIndex ?>_Report_To" id="fusersgrid$x<?= $Grid->RowIndex ?>_Report_To" value="<?= HtmlEncode($Grid->Report_To->FormValue) ?>">
<input type="hidden" data-table="users" data-field="x_Report_To" data-hidden="1" name="fusersgrid$o<?= $Grid->RowIndex ?>_Report_To" id="fusersgrid$o<?= $Grid->RowIndex ?>_Report_To" value="<?= HtmlEncode($Grid->Report_To->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->Activated->Visible) { // Activated ?>
        <td data-name="Activated"<?= $Grid->Activated->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_users_Activated" class="el_users_Activated">
<div class="form-check d-inline-block">
    <input type="checkbox" class="form-check-input<?= $Grid->Activated->isInvalidClass() ?>" data-table="users" data-field="x_Activated" name="x<?= $Grid->RowIndex ?>_Activated[]" id="x<?= $Grid->RowIndex ?>_Activated_939072" value="1"<?= ConvertToBool($Grid->Activated->CurrentValue) ? " checked" : "" ?><?= $Grid->Activated->editAttributes() ?>>
    <div class="invalid-feedback"><?= $Grid->Activated->getErrorMessage() ?></div>
</div>
</span>
<input type="hidden" data-table="users" data-field="x_Activated" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Activated[]" id="o<?= $Grid->RowIndex ?>_Activated[]" value="<?= HtmlEncode($Grid->Activated->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_users_Activated" class="el_users_Activated">
<div class="form-check d-inline-block">
    <input type="checkbox" class="form-check-input<?= $Grid->Activated->isInvalidClass() ?>" data-table="users" data-field="x_Activated" name="x<?= $Grid->RowIndex ?>_Activated[]" id="x<?= $Grid->RowIndex ?>_Activated_841189" value="1"<?= ConvertToBool($Grid->Activated->CurrentValue) ? " checked" : "" ?><?= $Grid->Activated->editAttributes() ?>>
    <div class="invalid-feedback"><?= $Grid->Activated->getErrorMessage() ?></div>
</div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_users_Activated" class="el_users_Activated">
<span<?= $Grid->Activated->viewAttributes() ?>>
<div class="form-check d-inline-block">
    <input type="checkbox" id="x_Activated_<?= $Grid->RowCount ?>" class="form-check-input" value="<?= $Grid->Activated->getViewValue() ?>" disabled<?php if (ConvertToBool($Grid->Activated->CurrentValue)) { ?> checked<?php } ?>>
    <label class="form-check-label" for="x_Activated_<?= $Grid->RowCount ?>"></label>
</div></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="users" data-field="x_Activated" data-hidden="1" name="fusersgrid$x<?= $Grid->RowIndex ?>_Activated" id="fusersgrid$x<?= $Grid->RowIndex ?>_Activated" value="<?= HtmlEncode($Grid->Activated->FormValue) ?>">
<input type="hidden" data-table="users" data-field="x_Activated" data-hidden="1" name="fusersgrid$o<?= $Grid->RowIndex ?>_Activated[]" id="fusersgrid$o<?= $Grid->RowIndex ?>_Activated[]" value="<?= HtmlEncode($Grid->Activated->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->Locked->Visible) { // Locked ?>
        <td data-name="Locked"<?= $Grid->Locked->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_users_Locked" class="el_users_Locked">
<div class="form-check d-inline-block">
    <input type="checkbox" class="form-check-input<?= $Grid->Locked->isInvalidClass() ?>" data-table="users" data-field="x_Locked" name="x<?= $Grid->RowIndex ?>_Locked[]" id="x<?= $Grid->RowIndex ?>_Locked_473069" value="1"<?= ConvertToBool($Grid->Locked->CurrentValue) ? " checked" : "" ?><?= $Grid->Locked->editAttributes() ?>>
    <div class="invalid-feedback"><?= $Grid->Locked->getErrorMessage() ?></div>
</div>
</span>
<input type="hidden" data-table="users" data-field="x_Locked" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Locked[]" id="o<?= $Grid->RowIndex ?>_Locked[]" value="<?= HtmlEncode($Grid->Locked->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_users_Locked" class="el_users_Locked">
<div class="form-check d-inline-block">
    <input type="checkbox" class="form-check-input<?= $Grid->Locked->isInvalidClass() ?>" data-table="users" data-field="x_Locked" name="x<?= $Grid->RowIndex ?>_Locked[]" id="x<?= $Grid->RowIndex ?>_Locked_412538" value="1"<?= ConvertToBool($Grid->Locked->CurrentValue) ? " checked" : "" ?><?= $Grid->Locked->editAttributes() ?>>
    <div class="invalid-feedback"><?= $Grid->Locked->getErrorMessage() ?></div>
</div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_users_Locked" class="el_users_Locked">
<span<?= $Grid->Locked->viewAttributes() ?>>
<div class="form-check d-inline-block">
    <input type="checkbox" id="x_Locked_<?= $Grid->RowCount ?>" class="form-check-input" value="<?= $Grid->Locked->getViewValue() ?>" disabled<?php if (ConvertToBool($Grid->Locked->CurrentValue)) { ?> checked<?php } ?>>
    <label class="form-check-label" for="x_Locked_<?= $Grid->RowCount ?>"></label>
</div></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="users" data-field="x_Locked" data-hidden="1" name="fusersgrid$x<?= $Grid->RowIndex ?>_Locked" id="fusersgrid$x<?= $Grid->RowIndex ?>_Locked" value="<?= HtmlEncode($Grid->Locked->FormValue) ?>">
<input type="hidden" data-table="users" data-field="x_Locked" data-hidden="1" name="fusersgrid$o<?= $Grid->RowIndex ?>_Locked[]" id="fusersgrid$o<?= $Grid->RowIndex ?>_Locked[]" value="<?= HtmlEncode($Grid->Locked->OldValue) ?>">
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
loadjs.ready(["fusersgrid","load"], () => fusersgrid.updateLists(<?= $Grid->RowIndex ?>));
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
    $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowIndex, "id" => "r0_users", "data-rowtype" => ROWTYPE_ADD]);
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
    <?php if ($Grid->_Username->Visible) { // Username ?>
        <td data-name="_Username">
<?php if (!$Grid->isConfirm()) { ?>
<?php if (!$Security->isAdmin() && $Security->isLoggedIn() && !$Grid->userIDAllow("grid")) { // Non system admin ?>
<span id="el$rowindex$_users__Username" class="el_users__Username">
    <select
        id="x<?= $Grid->RowIndex ?>__Username"
        name="x<?= $Grid->RowIndex ?>__Username"
        class="form-select ew-select<?= $Grid->_Username->isInvalidClass() ?>"
        data-select2-id="fusersgrid_x<?= $Grid->RowIndex ?>__Username"
        data-table="users"
        data-field="x__Username"
        data-value-separator="<?= $Grid->_Username->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->_Username->getPlaceHolder()) ?>"
        <?= $Grid->_Username->editAttributes() ?>>
        <?= $Grid->_Username->selectOptionListHtml("x{$Grid->RowIndex}__Username") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->_Username->getErrorMessage() ?></div>
<script>
loadjs.ready("fusersgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>__Username", selectId: "fusersgrid_x<?= $Grid->RowIndex ?>__Username" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fusersgrid.lists._Username.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>__Username", form: "fusersgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>__Username", form: "fusersgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.users.fields._Username.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } else { ?>
<span id="el$rowindex$_users__Username" class="el_users__Username">
<input type="<?= $Grid->_Username->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>__Username" id="x<?= $Grid->RowIndex ?>__Username" data-table="users" data-field="x__Username" value="<?= $Grid->_Username->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->_Username->getPlaceHolder()) ?>"<?= $Grid->_Username->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->_Username->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_users__Username" class="el_users__Username">
<span<?= $Grid->_Username->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->_Username->getDisplayValue($Grid->_Username->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="users" data-field="x__Username" data-hidden="1" name="x<?= $Grid->RowIndex ?>__Username" id="x<?= $Grid->RowIndex ?>__Username" value="<?= HtmlEncode($Grid->_Username->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="users" data-field="x__Username" data-hidden="1" name="o<?= $Grid->RowIndex ?>__Username" id="o<?= $Grid->RowIndex ?>__Username" value="<?= HtmlEncode($Grid->_Username->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->First_Name->Visible) { // First_Name ?>
        <td data-name="First_Name">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_users_First_Name" class="el_users_First_Name">
<input type="<?= $Grid->First_Name->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_First_Name" id="x<?= $Grid->RowIndex ?>_First_Name" data-table="users" data-field="x_First_Name" value="<?= $Grid->First_Name->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->First_Name->getPlaceHolder()) ?>"<?= $Grid->First_Name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->First_Name->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_users_First_Name" class="el_users_First_Name">
<span<?= $Grid->First_Name->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->First_Name->getDisplayValue($Grid->First_Name->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="users" data-field="x_First_Name" data-hidden="1" name="x<?= $Grid->RowIndex ?>_First_Name" id="x<?= $Grid->RowIndex ?>_First_Name" value="<?= HtmlEncode($Grid->First_Name->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="users" data-field="x_First_Name" data-hidden="1" name="o<?= $Grid->RowIndex ?>_First_Name" id="o<?= $Grid->RowIndex ?>_First_Name" value="<?= HtmlEncode($Grid->First_Name->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->Last_Name->Visible) { // Last_Name ?>
        <td data-name="Last_Name">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_users_Last_Name" class="el_users_Last_Name">
<input type="<?= $Grid->Last_Name->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_Last_Name" id="x<?= $Grid->RowIndex ?>_Last_Name" data-table="users" data-field="x_Last_Name" value="<?= $Grid->Last_Name->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->Last_Name->getPlaceHolder()) ?>"<?= $Grid->Last_Name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->Last_Name->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_users_Last_Name" class="el_users_Last_Name">
<span<?= $Grid->Last_Name->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->Last_Name->getDisplayValue($Grid->Last_Name->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="users" data-field="x_Last_Name" data-hidden="1" name="x<?= $Grid->RowIndex ?>_Last_Name" id="x<?= $Grid->RowIndex ?>_Last_Name" value="<?= HtmlEncode($Grid->Last_Name->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="users" data-field="x_Last_Name" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Last_Name" id="o<?= $Grid->RowIndex ?>_Last_Name" value="<?= HtmlEncode($Grid->Last_Name->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->_Email->Visible) { // Email ?>
        <td data-name="_Email">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_users__Email" class="el_users__Email">
<input type="<?= $Grid->_Email->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>__Email" id="x<?= $Grid->RowIndex ?>__Email" data-table="users" data-field="x__Email" value="<?= $Grid->_Email->EditValue ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Grid->_Email->getPlaceHolder()) ?>"<?= $Grid->_Email->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->_Email->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_users__Email" class="el_users__Email">
<span<?= $Grid->_Email->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->_Email->getDisplayValue($Grid->_Email->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="users" data-field="x__Email" data-hidden="1" name="x<?= $Grid->RowIndex ?>__Email" id="x<?= $Grid->RowIndex ?>__Email" value="<?= HtmlEncode($Grid->_Email->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="users" data-field="x__Email" data-hidden="1" name="o<?= $Grid->RowIndex ?>__Email" id="o<?= $Grid->RowIndex ?>__Email" value="<?= HtmlEncode($Grid->_Email->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->User_Level->Visible) { // User_Level ?>
        <td data-name="User_Level">
<?php if (!$Grid->isConfirm()) { ?>
<?php if ($Grid->User_Level->getSessionValue() != "") { ?>
<span id="el$rowindex$_users_User_Level" class="el_users_User_Level">
<span<?= $Grid->User_Level->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Grid->User_Level->getDisplayValue($Grid->User_Level->ViewValue) ?></span></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_User_Level" name="x<?= $Grid->RowIndex ?>_User_Level" value="<?= HtmlEncode(FormatNumber($Grid->User_Level->CurrentValue, $Grid->User_Level->formatPattern())) ?>" data-hidden="1">
<?php } elseif (!$Security->isAdmin() && $Security->isLoggedIn()) { // Non system admin ?>
<span id="el$rowindex$_users_User_Level" class="el_users_User_Level">
<span class="form-control-plaintext"><?= $Grid->User_Level->getDisplayValue($Grid->User_Level->EditValue) ?></span>
</span>
<?php } else { ?>
<span id="el$rowindex$_users_User_Level" class="el_users_User_Level">
    <select
        id="x<?= $Grid->RowIndex ?>_User_Level"
        name="x<?= $Grid->RowIndex ?>_User_Level"
        class="form-select ew-select<?= $Grid->User_Level->isInvalidClass() ?>"
        data-select2-id="fusersgrid_x<?= $Grid->RowIndex ?>_User_Level"
        data-table="users"
        data-field="x_User_Level"
        data-value-separator="<?= $Grid->User_Level->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->User_Level->getPlaceHolder()) ?>"
        <?= $Grid->User_Level->editAttributes() ?>>
        <?= $Grid->User_Level->selectOptionListHtml("x{$Grid->RowIndex}_User_Level") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->User_Level->getErrorMessage() ?></div>
<?= $Grid->User_Level->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_User_Level") ?>
<script>
loadjs.ready("fusersgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_User_Level", selectId: "fusersgrid_x<?= $Grid->RowIndex ?>_User_Level" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fusersgrid.lists.User_Level.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_User_Level", form: "fusersgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_User_Level", form: "fusersgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.users.fields.User_Level.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_users_User_Level" class="el_users_User_Level">
<span<?= $Grid->User_Level->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Grid->User_Level->getDisplayValue($Grid->User_Level->ViewValue) ?></span></span>
</span>
<input type="hidden" data-table="users" data-field="x_User_Level" data-hidden="1" name="x<?= $Grid->RowIndex ?>_User_Level" id="x<?= $Grid->RowIndex ?>_User_Level" value="<?= HtmlEncode($Grid->User_Level->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="users" data-field="x_User_Level" data-hidden="1" name="o<?= $Grid->RowIndex ?>_User_Level" id="o<?= $Grid->RowIndex ?>_User_Level" value="<?= HtmlEncode($Grid->User_Level->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->Report_To->Visible) { // Report_To ?>
        <td data-name="Report_To">
<?php if (!$Grid->isConfirm()) { ?>
<?php if (!$Security->isAdmin() && $Security->isLoggedIn()) { // Non system admin ?>
<span id="el$rowindex$_users_Report_To" class="el_users_Report_To">
    <select
        id="x<?= $Grid->RowIndex ?>_Report_To"
        name="x<?= $Grid->RowIndex ?>_Report_To"
        class="form-select ew-select<?= $Grid->Report_To->isInvalidClass() ?>"
        data-select2-id="fusersgrid_x<?= $Grid->RowIndex ?>_Report_To"
        data-table="users"
        data-field="x_Report_To"
        data-value-separator="<?= $Grid->Report_To->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->Report_To->getPlaceHolder()) ?>"
        <?= $Grid->Report_To->editAttributes() ?>>
        <?= $Grid->Report_To->selectOptionListHtml("x{$Grid->RowIndex}_Report_To") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->Report_To->getErrorMessage() ?></div>
<?= $Grid->Report_To->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_Report_To") ?>
<script>
loadjs.ready("fusersgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_Report_To", selectId: "fusersgrid_x<?= $Grid->RowIndex ?>_Report_To" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fusersgrid.lists.Report_To.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_Report_To", form: "fusersgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_Report_To", form: "fusersgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.users.fields.Report_To.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } else { ?>
<span id="el$rowindex$_users_Report_To" class="el_users_Report_To">
    <select
        id="x<?= $Grid->RowIndex ?>_Report_To"
        name="x<?= $Grid->RowIndex ?>_Report_To"
        class="form-select ew-select<?= $Grid->Report_To->isInvalidClass() ?>"
        data-select2-id="fusersgrid_x<?= $Grid->RowIndex ?>_Report_To"
        data-table="users"
        data-field="x_Report_To"
        data-value-separator="<?= $Grid->Report_To->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->Report_To->getPlaceHolder()) ?>"
        <?= $Grid->Report_To->editAttributes() ?>>
        <?= $Grid->Report_To->selectOptionListHtml("x{$Grid->RowIndex}_Report_To") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->Report_To->getErrorMessage() ?></div>
<?= $Grid->Report_To->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_Report_To") ?>
<script>
loadjs.ready("fusersgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_Report_To", selectId: "fusersgrid_x<?= $Grid->RowIndex ?>_Report_To" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fusersgrid.lists.Report_To.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_Report_To", form: "fusersgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_Report_To", form: "fusersgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.users.fields.Report_To.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_users_Report_To" class="el_users_Report_To">
<span<?= $Grid->Report_To->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Grid->Report_To->getDisplayValue($Grid->Report_To->ViewValue) ?></span></span>
</span>
<input type="hidden" data-table="users" data-field="x_Report_To" data-hidden="1" name="x<?= $Grid->RowIndex ?>_Report_To" id="x<?= $Grid->RowIndex ?>_Report_To" value="<?= HtmlEncode($Grid->Report_To->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="users" data-field="x_Report_To" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Report_To" id="o<?= $Grid->RowIndex ?>_Report_To" value="<?= HtmlEncode($Grid->Report_To->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->Activated->Visible) { // Activated ?>
        <td data-name="Activated">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_users_Activated" class="el_users_Activated">
<div class="form-check d-inline-block">
    <input type="checkbox" class="form-check-input<?= $Grid->Activated->isInvalidClass() ?>" data-table="users" data-field="x_Activated" name="x<?= $Grid->RowIndex ?>_Activated[]" id="x<?= $Grid->RowIndex ?>_Activated_170402" value="1"<?= ConvertToBool($Grid->Activated->CurrentValue) ? " checked" : "" ?><?= $Grid->Activated->editAttributes() ?>>
    <div class="invalid-feedback"><?= $Grid->Activated->getErrorMessage() ?></div>
</div>
</span>
<?php } else { ?>
<span id="el$rowindex$_users_Activated" class="el_users_Activated">
<span<?= $Grid->Activated->viewAttributes() ?>>
<div class="form-check d-inline-block">
    <input type="checkbox" id="x_Activated_<?= $Grid->RowCount ?>" class="form-check-input" value="<?= $Grid->Activated->ViewValue ?>" disabled<?php if (ConvertToBool($Grid->Activated->CurrentValue)) { ?> checked<?php } ?>>
    <label class="form-check-label" for="x_Activated_<?= $Grid->RowCount ?>"></label>
</div></span>
</span>
<input type="hidden" data-table="users" data-field="x_Activated" data-hidden="1" name="x<?= $Grid->RowIndex ?>_Activated" id="x<?= $Grid->RowIndex ?>_Activated" value="<?= HtmlEncode($Grid->Activated->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="users" data-field="x_Activated" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Activated[]" id="o<?= $Grid->RowIndex ?>_Activated[]" value="<?= HtmlEncode($Grid->Activated->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->Locked->Visible) { // Locked ?>
        <td data-name="Locked">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_users_Locked" class="el_users_Locked">
<div class="form-check d-inline-block">
    <input type="checkbox" class="form-check-input<?= $Grid->Locked->isInvalidClass() ?>" data-table="users" data-field="x_Locked" name="x<?= $Grid->RowIndex ?>_Locked[]" id="x<?= $Grid->RowIndex ?>_Locked_487908" value="1"<?= ConvertToBool($Grid->Locked->CurrentValue) ? " checked" : "" ?><?= $Grid->Locked->editAttributes() ?>>
    <div class="invalid-feedback"><?= $Grid->Locked->getErrorMessage() ?></div>
</div>
</span>
<?php } else { ?>
<span id="el$rowindex$_users_Locked" class="el_users_Locked">
<span<?= $Grid->Locked->viewAttributes() ?>>
<div class="form-check d-inline-block">
    <input type="checkbox" id="x_Locked_<?= $Grid->RowCount ?>" class="form-check-input" value="<?= $Grid->Locked->ViewValue ?>" disabled<?php if (ConvertToBool($Grid->Locked->CurrentValue)) { ?> checked<?php } ?>>
    <label class="form-check-label" for="x_Locked_<?= $Grid->RowCount ?>"></label>
</div></span>
</span>
<input type="hidden" data-table="users" data-field="x_Locked" data-hidden="1" name="x<?= $Grid->RowIndex ?>_Locked" id="x<?= $Grid->RowIndex ?>_Locked" value="<?= HtmlEncode($Grid->Locked->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="users" data-field="x_Locked" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Locked[]" id="o<?= $Grid->RowIndex ?>_Locked[]" value="<?= HtmlEncode($Grid->Locked->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowIndex);
?>
<script>
loadjs.ready(["fusersgrid","load"], () => fusersgrid.updateLists(<?= $Grid->RowIndex ?>, true));
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
<input type="hidden" name="detailpage" value="fusersgrid">
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
