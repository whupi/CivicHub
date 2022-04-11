<?php

namespace PHPMaker2022\civichub2;

// Page object
$RefCategoryView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { ref_category: currentTable } });
var currentForm, currentPageID;
var fref_categoryview;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fref_categoryview = new ew.Form("fref_categoryview", "view");
    currentPageID = ew.PAGE_ID = "view";
    currentForm = fref_categoryview;
    loadjs.done("fref_categoryview");
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
<form name="fref_categoryview" id="fref_categoryview" class="ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="ref_category">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-bordered table-hover table-sm ew-view-table">
<?php if ($Page->Category_ID->Visible) { // Category_ID ?>
    <tr id="r_Category_ID"<?= $Page->Category_ID->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_ref_category_Category_ID"><?= $Page->Category_ID->caption() ?></span></td>
        <td data-name="Category_ID"<?= $Page->Category_ID->cellAttributes() ?>>
<span id="el_ref_category_Category_ID">
<span<?= $Page->Category_ID->viewAttributes() ?>>
<?= $Page->Category_ID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Category->Visible) { // Category ?>
    <tr id="r_Category"<?= $Page->Category->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_ref_category_Category"><?= $Page->Category->caption() ?></span></td>
        <td data-name="Category"<?= $Page->Category->cellAttributes() ?>>
<span id="el_ref_category_Category">
<span<?= $Page->Category->viewAttributes() ?>>
<?= $Page->Category->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Category_Description->Visible) { // Category_Description ?>
    <tr id="r_Category_Description"<?= $Page->Category_Description->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_ref_category_Category_Description"><?= $Page->Category_Description->caption() ?></span></td>
        <td data-name="Category_Description"<?= $Page->Category_Description->cellAttributes() ?>>
<span id="el_ref_category_Category_Description">
<span<?= $Page->Category_Description->viewAttributes() ?>>
<?= $Page->Category_Description->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php
    if (in_array("submission", explode(",", $Page->getCurrentDetailTable())) && $submission->DetailView) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("submission", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "SubmissionGrid.php" ?>
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
