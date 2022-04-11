<?php

namespace PHPMaker2022\civichub2;

// Page object
$UserlevelsView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { userlevels: currentTable } });
var currentForm, currentPageID;
var fuserlevelsview;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fuserlevelsview = new ew.Form("fuserlevelsview", "view");
    currentPageID = ew.PAGE_ID = "view";
    currentForm = fuserlevelsview;
    loadjs.done("fuserlevelsview");
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
<form name="fuserlevelsview" id="fuserlevelsview" class="ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="userlevels">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-bordered table-hover table-sm ew-view-table">
<?php if ($Page->User_Level_ID->Visible) { // User_Level_ID ?>
    <tr id="r_User_Level_ID"<?= $Page->User_Level_ID->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_userlevels_User_Level_ID"><?= $Page->User_Level_ID->caption() ?></span></td>
        <td data-name="User_Level_ID"<?= $Page->User_Level_ID->cellAttributes() ?>>
<span id="el_userlevels_User_Level_ID">
<span<?= $Page->User_Level_ID->viewAttributes() ?>>
<?= $Page->User_Level_ID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->User_Level_Name->Visible) { // User_Level_Name ?>
    <tr id="r_User_Level_Name"<?= $Page->User_Level_Name->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_userlevels_User_Level_Name"><?= $Page->User_Level_Name->caption() ?></span></td>
        <td data-name="User_Level_Name"<?= $Page->User_Level_Name->cellAttributes() ?>>
<span id="el_userlevels_User_Level_Name">
<span<?= $Page->User_Level_Name->viewAttributes() ?>>
<?= $Page->User_Level_Name->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php
    if (in_array("users", explode(",", $Page->getCurrentDetailTable())) && $users->DetailView) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("users", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "UsersGrid.php" ?>
<?php } ?>
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
