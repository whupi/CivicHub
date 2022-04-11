<?php

namespace PHPMaker2022\civichub2;

// Page object
$RefCategoryAddopt = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { ref_category: currentTable } });
var currentForm, currentPageID;
var fref_categoryaddopt;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fref_categoryaddopt = new ew.Form("fref_categoryaddopt", "addopt");
    currentPageID = ew.PAGE_ID = "addopt";
    currentForm = fref_categoryaddopt;

    // Add fields
    var fields = currentTable.fields;
    fref_categoryaddopt.addFields([
        ["Category", [fields.Category.visible && fields.Category.required ? ew.Validators.required(fields.Category.caption) : null], fields.Category.isInvalid],
        ["Category_Description", [fields.Category_Description.visible && fields.Category_Description.required ? ew.Validators.required(fields.Category_Description.caption) : null], fields.Category_Description.isInvalid]
    ]);

    // Form_CustomValidate
    fref_categoryaddopt.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fref_categoryaddopt.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    loadjs.done("fref_categoryaddopt");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php $Page->showPageHeader(); ?>
<form name="fref_categoryaddopt" id="fref_categoryaddopt" class="ew-form" action="<?= HtmlEncode(GetUrl(Config("API_URL"))) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="<?= Config("API_ACTION_NAME") ?>" id="<?= Config("API_ACTION_NAME") ?>" value="<?= Config("API_ADD_ACTION") ?>">
<input type="hidden" name="<?= Config("API_OBJECT_NAME") ?>" id="<?= Config("API_OBJECT_NAME") ?>" value="ref_category">
<input type="hidden" name="addopt" id="addopt" value="1">
<?php if ($Page->Category->Visible) { // Category ?>
    <div<?= $Page->Category->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_Category"><?= $Page->Category->caption() ?><?= $Page->Category->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->Category->cellAttributes() ?>>
<input type="<?= $Page->Category->getInputTextType() ?>" name="x_Category" id="x_Category" data-table="ref_category" data-field="x_Category" value="<?= $Page->Category->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->Category->getPlaceHolder()) ?>"<?= $Page->Category->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Category->getErrorMessage() ?></div>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Category_Description->Visible) { // Category_Description ?>
    <div<?= $Page->Category_Description->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label"><?= $Page->Category_Description->caption() ?><?= $Page->Category_Description->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->Category_Description->cellAttributes() ?>>
<?php $Page->Category_Description->EditAttrs->appendClass("editor"); ?>
<textarea data-table="ref_category" data-field="x_Category_Description" name="x_Category_Description" id="x_Category_Description" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->Category_Description->getPlaceHolder()) ?>"<?= $Page->Category_Description->editAttributes() ?>><?= $Page->Category_Description->EditValue ?></textarea>
<div class="invalid-feedback"><?= $Page->Category_Description->getErrorMessage() ?></div>
<script>
loadjs.ready(["fref_categoryaddopt", "editor"], function() {
    ew.createEditor("fref_categoryaddopt", "x_Category_Description", 0, 0, <?= $Page->Category_Description->ReadOnly || false ? "true" : "false" ?>);
});
</script>
</div></div>
    </div>
<?php } ?>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("ref_category");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
