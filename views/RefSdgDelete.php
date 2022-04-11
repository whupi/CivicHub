<?php

namespace PHPMaker2022\civichub2;

// Page object
$RefSdgDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { ref_sdg: currentTable } });
var currentForm, currentPageID;
var fref_sdgdelete;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fref_sdgdelete = new ew.Form("fref_sdgdelete", "delete");
    currentPageID = ew.PAGE_ID = "delete";
    currentForm = fref_sdgdelete;
    loadjs.done("fref_sdgdelete");
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
<form name="fref_sdgdelete" id="fref_sdgdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="ref_sdg">
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
<?php if ($Page->Goal_Number->Visible) { // Goal_Number ?>
        <th class="<?= $Page->Goal_Number->headerCellClass() ?>"><span id="elh_ref_sdg_Goal_Number" class="ref_sdg_Goal_Number"><?= $Page->Goal_Number->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Goal_Title->Visible) { // Goal_Title ?>
        <th class="<?= $Page->Goal_Title->headerCellClass() ?>"><span id="elh_ref_sdg_Goal_Title" class="ref_sdg_Goal_Title"><?= $Page->Goal_Title->caption() ?></span></th>
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
<?php if ($Page->Goal_Number->Visible) { // Goal_Number ?>
        <td<?= $Page->Goal_Number->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_ref_sdg_Goal_Number" class="el_ref_sdg_Goal_Number">
<span<?= $Page->Goal_Number->viewAttributes() ?>>
<?= $Page->Goal_Number->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Goal_Title->Visible) { // Goal_Title ?>
        <td<?= $Page->Goal_Title->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_ref_sdg_Goal_Title" class="el_ref_sdg_Goal_Title">
<span<?= $Page->Goal_Title->viewAttributes() ?>>
<?= $Page->Goal_Title->getViewValue() ?></span>
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
