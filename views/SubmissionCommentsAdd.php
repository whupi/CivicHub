<?php

namespace PHPMaker2022\civichub2;

// Page object
$SubmissionCommentsAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { submission_comments: currentTable } });
var currentForm, currentPageID;
var fsubmission_commentsadd;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fsubmission_commentsadd = new ew.Form("fsubmission_commentsadd", "add");
    currentPageID = ew.PAGE_ID = "add";
    currentForm = fsubmission_commentsadd;

    // Add fields
    var fields = currentTable.fields;
    fsubmission_commentsadd.addFields([
        ["Comment", [fields.Comment.visible && fields.Comment.required ? ew.Validators.required(fields.Comment.caption) : null], fields.Comment.isInvalid]
    ]);

    // Form_CustomValidate
    fsubmission_commentsadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fsubmission_commentsadd.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    loadjs.done("fsubmission_commentsadd");
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
<form name="fsubmission_commentsadd" id="fsubmission_commentsadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="submission_comments">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php if ($Page->getCurrentMasterTable() == "submission") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="submission">
<input type="hidden" name="fk_Submission_ID" value="<?= HtmlEncode($Page->Submission_ID->getSessionValue()) ?>">
<?php } ?>
<div class="ew-add-div"><!-- page* -->
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
loadjs.ready(["fsubmission_commentsadd", "editor"], function() {
    ew.createEditor("fsubmission_commentsadd", "x_Comment", 35, 4, <?= $Page->Comment->ReadOnly || false ? "true" : "false" ?>);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <?php if (strval($Page->Submission_ID->getSessionValue() ?? "") != "") { ?>
    <input type="hidden" name="x_Submission_ID" id="x_Submission_ID" value="<?= HtmlEncode(strval($Page->Submission_ID->getSessionValue() ?? "")) ?>">
    <?php } ?>
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
    ew.addEventHandlers("submission_comments");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
