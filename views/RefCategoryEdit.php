<?php

namespace PHPMaker2022\civichub2;

// Page object
$RefCategoryEdit = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { ref_category: currentTable } });
var currentForm, currentPageID;
var fref_categoryedit;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fref_categoryedit = new ew.Form("fref_categoryedit", "edit");
    currentPageID = ew.PAGE_ID = "edit";
    currentForm = fref_categoryedit;

    // Add fields
    var fields = currentTable.fields;
    fref_categoryedit.addFields([
        ["Category_ID", [fields.Category_ID.visible && fields.Category_ID.required ? ew.Validators.required(fields.Category_ID.caption) : null], fields.Category_ID.isInvalid],
        ["Category", [fields.Category.visible && fields.Category.required ? ew.Validators.required(fields.Category.caption) : null], fields.Category.isInvalid],
        ["Category_Description", [fields.Category_Description.visible && fields.Category_Description.required ? ew.Validators.required(fields.Category_Description.caption) : null], fields.Category_Description.isInvalid]
    ]);

    // Form_CustomValidate
    fref_categoryedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fref_categoryedit.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    loadjs.done("fref_categoryedit");
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
<form name="fref_categoryedit" id="fref_categoryedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="ref_category">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->Category_ID->Visible) { // Category_ID ?>
    <div id="r_Category_ID"<?= $Page->Category_ID->rowAttributes() ?>>
        <label id="elh_ref_category_Category_ID" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Category_ID->caption() ?><?= $Page->Category_ID->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Category_ID->cellAttributes() ?>>
<span id="el_ref_category_Category_ID">
<span<?= $Page->Category_ID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->Category_ID->getDisplayValue($Page->Category_ID->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="ref_category" data-field="x_Category_ID" data-hidden="1" name="x_Category_ID" id="x_Category_ID" value="<?= HtmlEncode($Page->Category_ID->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Category->Visible) { // Category ?>
    <div id="r_Category"<?= $Page->Category->rowAttributes() ?>>
        <label id="elh_ref_category_Category" for="x_Category" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Category->caption() ?><?= $Page->Category->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Category->cellAttributes() ?>>
<span id="el_ref_category_Category">
<input type="<?= $Page->Category->getInputTextType() ?>" name="x_Category" id="x_Category" data-table="ref_category" data-field="x_Category" value="<?= $Page->Category->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->Category->getPlaceHolder()) ?>"<?= $Page->Category->editAttributes() ?> aria-describedby="x_Category_help">
<?= $Page->Category->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Category->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Category_Description->Visible) { // Category_Description ?>
    <div id="r_Category_Description"<?= $Page->Category_Description->rowAttributes() ?>>
        <label id="elh_ref_category_Category_Description" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Category_Description->caption() ?><?= $Page->Category_Description->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Category_Description->cellAttributes() ?>>
<span id="el_ref_category_Category_Description">
<?php $Page->Category_Description->EditAttrs->appendClass("editor"); ?>
<textarea data-table="ref_category" data-field="x_Category_Description" name="x_Category_Description" id="x_Category_Description" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->Category_Description->getPlaceHolder()) ?>"<?= $Page->Category_Description->editAttributes() ?> aria-describedby="x_Category_Description_help"><?= $Page->Category_Description->EditValue ?></textarea>
<?= $Page->Category_Description->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Category_Description->getErrorMessage() ?></div>
<script>
loadjs.ready(["fref_categoryedit", "editor"], function() {
    ew.createEditor("fref_categoryedit", "x_Category_Description", 0, 0, <?= $Page->Category_Description->ReadOnly || false ? "true" : "false" ?>);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?php
    if (in_array("submission", explode(",", $Page->getCurrentDetailTable())) && $submission->DetailEdit) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("submission", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "SubmissionGrid.php" ?>
<?php } ?>
<?php if (!$Page->IsModal) { ?>
<div class="row"><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("SaveBtn") ?></button>
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
    ew.addEventHandlers("ref_category");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
