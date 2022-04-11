<?php

namespace PHPMaker2022\civichub2;

// Page object
$SubmissionCommentsEdit = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { submission_comments: currentTable } });
var currentForm, currentPageID;
var fsubmission_commentsedit;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fsubmission_commentsedit = new ew.Form("fsubmission_commentsedit", "edit");
    currentPageID = ew.PAGE_ID = "edit";
    currentForm = fsubmission_commentsedit;

    // Add fields
    var fields = currentTable.fields;
    fsubmission_commentsedit.addFields([
        ["Comment_ID", [fields.Comment_ID.visible && fields.Comment_ID.required ? ew.Validators.required(fields.Comment_ID.caption) : null], fields.Comment_ID.isInvalid],
        ["Comment", [fields.Comment.visible && fields.Comment.required ? ew.Validators.required(fields.Comment.caption) : null], fields.Comment.isInvalid]
    ]);

    // Form_CustomValidate
    fsubmission_commentsedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fsubmission_commentsedit.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    loadjs.done("fsubmission_commentsedit");
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
<form name="fsubmission_commentsedit" id="fsubmission_commentsedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="submission_comments">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php if ($Page->getCurrentMasterTable() == "submission") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="submission">
<input type="hidden" name="fk_Submission_ID" value="<?= HtmlEncode($Page->Submission_ID->getSessionValue()) ?>">
<?php } ?>
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->Comment_ID->Visible) { // Comment_ID ?>
    <div id="r_Comment_ID"<?= $Page->Comment_ID->rowAttributes() ?>>
        <label id="elh_submission_comments_Comment_ID" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Comment_ID->caption() ?><?= $Page->Comment_ID->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Comment_ID->cellAttributes() ?>>
<span id="el_submission_comments_Comment_ID">
<span<?= $Page->Comment_ID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->Comment_ID->getDisplayValue($Page->Comment_ID->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="submission_comments" data-field="x_Comment_ID" data-hidden="1" name="x_Comment_ID" id="x_Comment_ID" value="<?= HtmlEncode($Page->Comment_ID->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Comment->Visible) { // Comment ?>
    <div id="r_Comment"<?= $Page->Comment->rowAttributes() ?>>
        <label id="elh_submission_comments_Comment" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Comment->caption() ?><?= $Page->Comment->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Comment->cellAttributes() ?>>
<span id="el_submission_comments_Comment">
<?php $Page->Comment->EditAttrs->appendClass("editor"); ?>
<textarea data-table="submission_comments" data-field="x_Comment" name="x_Comment" id="x_Comment" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->Comment->getPlaceHolder()) ?>"<?= $Page->Comment->editAttributes() ?> aria-describedby="x_Comment_help"><?= $Page->Comment->EditValue ?></textarea>
<?= $Page->Comment->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Comment->getErrorMessage() ?></div>
<script>
loadjs.ready(["fsubmission_commentsedit", "editor"], function() {
    ew.createEditor("fsubmission_commentsedit", "x_Comment", 35, 4, <?= $Page->Comment->ReadOnly || false ? "true" : "false" ?>);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
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
    ew.addEventHandlers("submission_comments");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
