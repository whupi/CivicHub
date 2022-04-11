<?php

namespace PHPMaker2022\civichub2;

// Page object
$UserlevelsEdit = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { userlevels: currentTable } });
var currentForm, currentPageID;
var fuserlevelsedit;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fuserlevelsedit = new ew.Form("fuserlevelsedit", "edit");
    currentPageID = ew.PAGE_ID = "edit";
    currentForm = fuserlevelsedit;

    // Add fields
    var fields = currentTable.fields;
    fuserlevelsedit.addFields([
        ["User_Level_ID", [fields.User_Level_ID.visible && fields.User_Level_ID.required ? ew.Validators.required(fields.User_Level_ID.caption) : null], fields.User_Level_ID.isInvalid],
        ["User_Level_Name", [fields.User_Level_Name.visible && fields.User_Level_Name.required ? ew.Validators.required(fields.User_Level_Name.caption) : null, ew.Validators.userLevelName('User_Level_ID')], fields.User_Level_Name.isInvalid]
    ]);

    // Form_CustomValidate
    fuserlevelsedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fuserlevelsedit.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    loadjs.done("fuserlevelsedit");
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
<form name="fuserlevelsedit" id="fuserlevelsedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="userlevels">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->User_Level_ID->Visible) { // User_Level_ID ?>
    <div id="r_User_Level_ID"<?= $Page->User_Level_ID->rowAttributes() ?>>
        <label id="elh_userlevels_User_Level_ID" class="<?= $Page->LeftColumnClass ?>"><?= $Page->User_Level_ID->caption() ?><?= $Page->User_Level_ID->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->User_Level_ID->cellAttributes() ?>>
<span id="el_userlevels_User_Level_ID">
<span<?= $Page->User_Level_ID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->User_Level_ID->getDisplayValue($Page->User_Level_ID->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="userlevels" data-field="x_User_Level_ID" data-hidden="1" name="x_User_Level_ID" id="x_User_Level_ID" value="<?= HtmlEncode($Page->User_Level_ID->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->User_Level_Name->Visible) { // User_Level_Name ?>
    <div id="r_User_Level_Name"<?= $Page->User_Level_Name->rowAttributes() ?>>
        <label id="elh_userlevels_User_Level_Name" for="x_User_Level_Name" class="<?= $Page->LeftColumnClass ?>"><?= $Page->User_Level_Name->caption() ?><?= $Page->User_Level_Name->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->User_Level_Name->cellAttributes() ?>>
<span id="el_userlevels_User_Level_Name">
<input type="<?= $Page->User_Level_Name->getInputTextType() ?>" name="x_User_Level_Name" id="x_User_Level_Name" data-table="userlevels" data-field="x_User_Level_Name" value="<?= $Page->User_Level_Name->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->User_Level_Name->getPlaceHolder()) ?>"<?= $Page->User_Level_Name->editAttributes() ?> aria-describedby="x_User_Level_Name_help">
<?= $Page->User_Level_Name->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->User_Level_Name->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?php
    if (in_array("users", explode(",", $Page->getCurrentDetailTable())) && $users->DetailEdit) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("users", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "UsersGrid.php" ?>
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
    ew.addEventHandlers("userlevels");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
