<?php

namespace PHPMaker2022\civichub2;

// Page object
$RefCountryAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { ref_country: currentTable } });
var currentForm, currentPageID;
var fref_countryadd;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fref_countryadd = new ew.Form("fref_countryadd", "add");
    currentPageID = ew.PAGE_ID = "add";
    currentForm = fref_countryadd;

    // Add fields
    var fields = currentTable.fields;
    fref_countryadd.addFields([
        ["Country", [fields.Country.visible && fields.Country.required ? ew.Validators.required(fields.Country.caption) : null], fields.Country.isInvalid]
    ]);

    // Form_CustomValidate
    fref_countryadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fref_countryadd.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    loadjs.done("fref_countryadd");
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
<form name="fref_countryadd" id="fref_countryadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="ref_country">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->Country->Visible) { // Country ?>
    <div id="r_Country"<?= $Page->Country->rowAttributes() ?>>
        <label id="elh_ref_country_Country" for="x_Country" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Country->caption() ?><?= $Page->Country->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Country->cellAttributes() ?>>
<span id="el_ref_country_Country">
<input type="<?= $Page->Country->getInputTextType() ?>" name="x_Country" id="x_Country" data-table="ref_country" data-field="x_Country" value="<?= $Page->Country->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->Country->getPlaceHolder()) ?>"<?= $Page->Country->editAttributes() ?> aria-describedby="x_Country_help">
<?= $Page->Country->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Country->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$Page->IsModal) { ?>
<div class="row"><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("AddBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
    </div><!-- /buttons offset -->
</div><!-- /buttons .row -->
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
