<?php

namespace PHPMaker2022\civichub2;

// Page object
$RefCountryAddopt = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { ref_country: currentTable } });
var currentForm, currentPageID;
var fref_countryaddopt;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fref_countryaddopt = new ew.Form("fref_countryaddopt", "addopt");
    currentPageID = ew.PAGE_ID = "addopt";
    currentForm = fref_countryaddopt;

    // Add fields
    var fields = currentTable.fields;
    fref_countryaddopt.addFields([
        ["Country", [fields.Country.visible && fields.Country.required ? ew.Validators.required(fields.Country.caption) : null], fields.Country.isInvalid]
    ]);

    // Form_CustomValidate
    fref_countryaddopt.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fref_countryaddopt.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    loadjs.done("fref_countryaddopt");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php $Page->showPageHeader(); ?>
<form name="fref_countryaddopt" id="fref_countryaddopt" class="ew-form" action="<?= HtmlEncode(GetUrl(Config("API_URL"))) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="<?= Config("API_ACTION_NAME") ?>" id="<?= Config("API_ACTION_NAME") ?>" value="<?= Config("API_ADD_ACTION") ?>">
<input type="hidden" name="<?= Config("API_OBJECT_NAME") ?>" id="<?= Config("API_OBJECT_NAME") ?>" value="ref_country">
<input type="hidden" name="addopt" id="addopt" value="1">
<?php if ($Page->Country->Visible) { // Country ?>
    <div<?= $Page->Country->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_Country"><?= $Page->Country->caption() ?><?= $Page->Country->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->Country->cellAttributes() ?>>
<input type="<?= $Page->Country->getInputTextType() ?>" name="x_Country" id="x_Country" data-table="ref_country" data-field="x_Country" value="<?= $Page->Country->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->Country->getPlaceHolder()) ?>"<?= $Page->Country->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Country->getErrorMessage() ?></div>
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
    ew.addEventHandlers("ref_country");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
