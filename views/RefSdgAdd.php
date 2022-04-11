<?php

namespace PHPMaker2022\civichub2;

// Page object
$RefSdgAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { ref_sdg: currentTable } });
var currentForm, currentPageID;
var fref_sdgadd;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fref_sdgadd = new ew.Form("fref_sdgadd", "add");
    currentPageID = ew.PAGE_ID = "add";
    currentForm = fref_sdgadd;

    // Add fields
    var fields = currentTable.fields;
    fref_sdgadd.addFields([
        ["Goal_Number", [fields.Goal_Number.visible && fields.Goal_Number.required ? ew.Validators.required(fields.Goal_Number.caption) : null, ew.Validators.integer], fields.Goal_Number.isInvalid],
        ["Goal_Title", [fields.Goal_Title.visible && fields.Goal_Title.required ? ew.Validators.required(fields.Goal_Title.caption) : null], fields.Goal_Title.isInvalid]
    ]);

    // Form_CustomValidate
    fref_sdgadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fref_sdgadd.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    loadjs.done("fref_sdgadd");
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
<form name="fref_sdgadd" id="fref_sdgadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="ref_sdg">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->Goal_Number->Visible) { // Goal_Number ?>
    <div id="r_Goal_Number"<?= $Page->Goal_Number->rowAttributes() ?>>
        <label id="elh_ref_sdg_Goal_Number" for="x_Goal_Number" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Goal_Number->caption() ?><?= $Page->Goal_Number->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Goal_Number->cellAttributes() ?>>
<span id="el_ref_sdg_Goal_Number">
<input type="<?= $Page->Goal_Number->getInputTextType() ?>" name="x_Goal_Number" id="x_Goal_Number" data-table="ref_sdg" data-field="x_Goal_Number" value="<?= $Page->Goal_Number->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->Goal_Number->getPlaceHolder()) ?>"<?= $Page->Goal_Number->editAttributes() ?> aria-describedby="x_Goal_Number_help">
<?= $Page->Goal_Number->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Goal_Number->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Goal_Title->Visible) { // Goal_Title ?>
    <div id="r_Goal_Title"<?= $Page->Goal_Title->rowAttributes() ?>>
        <label id="elh_ref_sdg_Goal_Title" for="x_Goal_Title" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Goal_Title->caption() ?><?= $Page->Goal_Title->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Goal_Title->cellAttributes() ?>>
<span id="el_ref_sdg_Goal_Title">
<input type="<?= $Page->Goal_Title->getInputTextType() ?>" name="x_Goal_Title" id="x_Goal_Title" data-table="ref_sdg" data-field="x_Goal_Title" value="<?= $Page->Goal_Title->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->Goal_Title->getPlaceHolder()) ?>"<?= $Page->Goal_Title->editAttributes() ?> aria-describedby="x_Goal_Title_help">
<?= $Page->Goal_Title->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Goal_Title->getErrorMessage() ?></div>
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
    ew.addEventHandlers("ref_sdg");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
