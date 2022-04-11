<?php

namespace PHPMaker2022\civichub2;

// Page object
$UserlevelpermissionsDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { userlevelpermissions: currentTable } });
var currentForm, currentPageID;
var fuserlevelpermissionsdelete;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fuserlevelpermissionsdelete = new ew.Form("fuserlevelpermissionsdelete", "delete");
    currentPageID = ew.PAGE_ID = "delete";
    currentForm = fuserlevelpermissionsdelete;
    loadjs.done("fuserlevelpermissionsdelete");
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
<form name="fuserlevelpermissionsdelete" id="fuserlevelpermissionsdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="userlevelpermissions">
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
<?php if ($Page->User_Level_ID->Visible) { // User_Level_ID ?>
        <th class="<?= $Page->User_Level_ID->headerCellClass() ?>"><span id="elh_userlevelpermissions_User_Level_ID" class="userlevelpermissions_User_Level_ID"><?= $Page->User_Level_ID->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Table_Name->Visible) { // Table_Name ?>
        <th class="<?= $Page->Table_Name->headerCellClass() ?>"><span id="elh_userlevelpermissions_Table_Name" class="userlevelpermissions_Table_Name"><?= $Page->Table_Name->caption() ?></span></th>
<?php } ?>
<?php if ($Page->_Permission->Visible) { // Permission ?>
        <th class="<?= $Page->_Permission->headerCellClass() ?>"><span id="elh_userlevelpermissions__Permission" class="userlevelpermissions__Permission"><?= $Page->_Permission->caption() ?></span></th>
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
<?php if ($Page->User_Level_ID->Visible) { // User_Level_ID ?>
        <td<?= $Page->User_Level_ID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_userlevelpermissions_User_Level_ID" class="el_userlevelpermissions_User_Level_ID">
<span<?= $Page->User_Level_ID->viewAttributes() ?>>
<?= $Page->User_Level_ID->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Table_Name->Visible) { // Table_Name ?>
        <td<?= $Page->Table_Name->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_userlevelpermissions_Table_Name" class="el_userlevelpermissions_Table_Name">
<span<?= $Page->Table_Name->viewAttributes() ?>>
<?= $Page->Table_Name->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->_Permission->Visible) { // Permission ?>
        <td<?= $Page->_Permission->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_userlevelpermissions__Permission" class="el_userlevelpermissions__Permission">
<span<?= $Page->_Permission->viewAttributes() ?>>
<?= $Page->_Permission->getViewValue() ?></span>
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
