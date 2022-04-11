<?php

namespace PHPMaker2022\civichub2;

// Page object
$RefSdgView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { ref_sdg: currentTable } });
var currentForm, currentPageID;
var fref_sdgview;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fref_sdgview = new ew.Form("fref_sdgview", "view");
    currentPageID = ew.PAGE_ID = "view";
    currentForm = fref_sdgview;
    loadjs.done("fref_sdgview");
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
<form name="fref_sdgview" id="fref_sdgview" class="ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="ref_sdg">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-bordered table-hover table-sm ew-view-table">
<?php if ($Page->Goal_Number->Visible) { // Goal_Number ?>
    <tr id="r_Goal_Number"<?= $Page->Goal_Number->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_ref_sdg_Goal_Number"><?= $Page->Goal_Number->caption() ?></span></td>
        <td data-name="Goal_Number"<?= $Page->Goal_Number->cellAttributes() ?>>
<span id="el_ref_sdg_Goal_Number">
<span<?= $Page->Goal_Number->viewAttributes() ?>>
<?= $Page->Goal_Number->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Goal_Title->Visible) { // Goal_Title ?>
    <tr id="r_Goal_Title"<?= $Page->Goal_Title->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_ref_sdg_Goal_Title"><?= $Page->Goal_Title->caption() ?></span></td>
        <td data-name="Goal_Title"<?= $Page->Goal_Title->cellAttributes() ?>>
<span id="el_ref_sdg_Goal_Title">
<span<?= $Page->Goal_Title->viewAttributes() ?>>
<?= $Page->Goal_Title->getViewValue() ?></span>
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
