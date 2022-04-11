<?php

namespace PHPMaker2022\civichub2;

// Page object
$UsersDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { users: currentTable } });
var currentForm, currentPageID;
var fusersdelete;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fusersdelete = new ew.Form("fusersdelete", "delete");
    currentPageID = ew.PAGE_ID = "delete";
    currentForm = fusersdelete;
    loadjs.done("fusersdelete");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fusersdelete" id="fusersdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="users">
<input type="hidden" name="action" id="action" value="delete">
<?php foreach ($Page->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode(Config("COMPOSITE_KEY_SEPARATOR"), $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="card ew-card ew-grid">
<div class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table class="table table-bordered table-hover table-sm ew-table">
    <thead>
    <tr class="ew-table-header">
<?php if ($Page->_Username->Visible) { // Username ?>
        <th class="<?= $Page->_Username->headerCellClass() ?>"><span id="elh_users__Username" class="users__Username"><?= $Page->_Username->caption() ?></span></th>
<?php } ?>
<?php if ($Page->First_Name->Visible) { // First_Name ?>
        <th class="<?= $Page->First_Name->headerCellClass() ?>"><span id="elh_users_First_Name" class="users_First_Name"><?= $Page->First_Name->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Last_Name->Visible) { // Last_Name ?>
        <th class="<?= $Page->Last_Name->headerCellClass() ?>"><span id="elh_users_Last_Name" class="users_Last_Name"><?= $Page->Last_Name->caption() ?></span></th>
<?php } ?>
<?php if ($Page->_Email->Visible) { // Email ?>
        <th class="<?= $Page->_Email->headerCellClass() ?>"><span id="elh_users__Email" class="users__Email"><?= $Page->_Email->caption() ?></span></th>
<?php } ?>
<?php if ($Page->User_Level->Visible) { // User_Level ?>
        <th class="<?= $Page->User_Level->headerCellClass() ?>"><span id="elh_users_User_Level" class="users_User_Level"><?= $Page->User_Level->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Report_To->Visible) { // Report_To ?>
        <th class="<?= $Page->Report_To->headerCellClass() ?>"><span id="elh_users_Report_To" class="users_Report_To"><?= $Page->Report_To->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Activated->Visible) { // Activated ?>
        <th class="<?= $Page->Activated->headerCellClass() ?>"><span id="elh_users_Activated" class="users_Activated"><?= $Page->Activated->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Locked->Visible) { // Locked ?>
        <th class="<?= $Page->Locked->headerCellClass() ?>"><span id="elh_users_Locked" class="users_Locked"><?= $Page->Locked->caption() ?></span></th>
<?php } ?>
    </tr>
    </thead>
    <tbody>
<?php
$Page->RecordCount = 0;
$i = 0;
while (!$Page->Recordset->EOF) {
    $Page->RecordCount++;
    $Page->RowCount++;

    // Set row properties
    $Page->resetAttributes();
    $Page->RowType = ROWTYPE_VIEW; // View

    // Get the field contents
    $Page->loadRowValues($Page->Recordset);

    // Render row
    $Page->renderRow();
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php if ($Page->_Username->Visible) { // Username ?>
        <td<?= $Page->_Username->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_users__Username" class="el_users__Username">
<span<?= $Page->_Username->viewAttributes() ?>>
<?= $Page->_Username->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->First_Name->Visible) { // First_Name ?>
        <td<?= $Page->First_Name->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_users_First_Name" class="el_users_First_Name">
<span<?= $Page->First_Name->viewAttributes() ?>>
<?= $Page->First_Name->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Last_Name->Visible) { // Last_Name ?>
        <td<?= $Page->Last_Name->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_users_Last_Name" class="el_users_Last_Name">
<span<?= $Page->Last_Name->viewAttributes() ?>>
<?= $Page->Last_Name->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->_Email->Visible) { // Email ?>
        <td<?= $Page->_Email->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_users__Email" class="el_users__Email">
<span<?= $Page->_Email->viewAttributes() ?>>
<?= $Page->_Email->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->User_Level->Visible) { // User_Level ?>
        <td<?= $Page->User_Level->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_users_User_Level" class="el_users_User_Level">
<span<?= $Page->User_Level->viewAttributes() ?>>
<?= $Page->User_Level->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Report_To->Visible) { // Report_To ?>
        <td<?= $Page->Report_To->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_users_Report_To" class="el_users_Report_To">
<span<?= $Page->Report_To->viewAttributes() ?>>
<?= $Page->Report_To->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Activated->Visible) { // Activated ?>
        <td<?= $Page->Activated->cellAttributes() ?>>
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
        <td<?= $Page->Locked->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_users_Locked" class="el_users_Locked">
<span<?= $Page->Locked->viewAttributes() ?>>
<div class="form-check d-inline-block">
    <input type="checkbox" id="x_Locked_<?= $Page->RowCount ?>" class="form-check-input" value="<?= $Page->Locked->getViewValue() ?>" disabled<?php if (ConvertToBool($Page->Locked->CurrentValue)) { ?> checked<?php } ?>>
    <label class="form-check-label" for="x_Locked_<?= $Page->RowCount ?>"></label>
</div></span>
</span>
</td>
<?php } ?>
    </tr>
<?php
    $Page->Recordset->moveNext();
}
$Page->Recordset->close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("DeleteBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
</div>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
