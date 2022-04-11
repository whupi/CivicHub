<?php

namespace PHPMaker2022\civichub2;

// Page object
$UserlevelpermissionsView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { userlevelpermissions: currentTable } });
var currentForm, currentPageID;
var fuserlevelpermissionsview;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fuserlevelpermissionsview = new ew.Form("fuserlevelpermissionsview", "view");
    currentPageID = ew.PAGE_ID = "view";
    currentForm = fuserlevelpermissionsview;
    loadjs.done("fuserlevelpermissionsview");
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
<?php $Page->ExportOptions->render("body") ?>
<?php $Page->OtherOptions->render("body") ?>
</div>
<?php } ?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fuserlevelpermissionsview" id="fuserlevelpermissionsview" class="ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="userlevelpermissions">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-bordered table-hover table-sm ew-view-table">
<?php if ($Page->User_Level_ID->Visible) { // User_Level_ID ?>
    <tr id="r_User_Level_ID"<?= $Page->User_Level_ID->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_userlevelpermissions_User_Level_ID"><?= $Page->User_Level_ID->caption() ?></span></td>
        <td data-name="User_Level_ID"<?= $Page->User_Level_ID->cellAttributes() ?>>
<span id="el_userlevelpermissions_User_Level_ID">
<span<?= $Page->User_Level_ID->viewAttributes() ?>>
<?= $Page->User_Level_ID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Table_Name->Visible) { // Table_Name ?>
    <tr id="r_Table_Name"<?= $Page->Table_Name->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_userlevelpermissions_Table_Name"><?= $Page->Table_Name->caption() ?></span></td>
        <td data-name="Table_Name"<?= $Page->Table_Name->cellAttributes() ?>>
<span id="el_userlevelpermissions_Table_Name">
<span<?= $Page->Table_Name->viewAttributes() ?>>
<?= $Page->Table_Name->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->_Permission->Visible) { // Permission ?>
    <tr id="r__Permission"<?= $Page->_Permission->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_userlevelpermissions__Permission"><?= $Page->_Permission->caption() ?></span></td>
        <td data-name="_Permission"<?= $Page->_Permission->cellAttributes() ?>>
<span id="el_userlevelpermissions__Permission">
<span<?= $Page->_Permission->viewAttributes() ?>>
<?= $Page->_Permission->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php if (!$Page->isExport()) { ?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
