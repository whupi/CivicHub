<?php

namespace PHPMaker2022\civichub2;

// Page object
$RefCategoryDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { ref_category: currentTable } });
var currentForm, currentPageID;
var fref_categorydelete;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fref_categorydelete = new ew.Form("fref_categorydelete", "delete");
    currentPageID = ew.PAGE_ID = "delete";
    currentForm = fref_categorydelete;
    loadjs.done("fref_categorydelete");
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
<form name="fref_categorydelete" id="fref_categorydelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="ref_category">
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
<?php if ($Page->Category_ID->Visible) { // Category_ID ?>
        <th class="<?= $Page->Category_ID->headerCellClass() ?>"><span id="elh_ref_category_Category_ID" class="ref_category_Category_ID"><?= $Page->Category_ID->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Category->Visible) { // Category ?>
        <th class="<?= $Page->Category->headerCellClass() ?>"><span id="elh_ref_category_Category" class="ref_category_Category"><?= $Page->Category->caption() ?></span></th>
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
<?php if ($Page->Category_ID->Visible) { // Category_ID ?>
        <td<?= $Page->Category_ID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_ref_category_Category_ID" class="el_ref_category_Category_ID">
<span<?= $Page->Category_ID->viewAttributes() ?>>
<?= $Page->Category_ID->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Category->Visible) { // Category ?>
        <td<?= $Page->Category->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_ref_category_Category" class="el_ref_category_Category">
<span<?= $Page->Category->viewAttributes() ?>>
<?= $Page->Category->getViewValue() ?></span>
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
