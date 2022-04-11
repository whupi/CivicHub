<?php

namespace PHPMaker2022\civichub2;

// Page object
$UserlevelpermissionsAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { userlevelpermissions: currentTable } });
var currentForm, currentPageID;
var fuserlevelpermissionsadd;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fuserlevelpermissionsadd = new ew.Form("fuserlevelpermissionsadd", "add");
    currentPageID = ew.PAGE_ID = "add";
    currentForm = fuserlevelpermissionsadd;

    // Add fields
    var fields = currentTable.fields;
    fuserlevelpermissionsadd.addFields([
        ["User_Level_ID", [fields.User_Level_ID.visible && fields.User_Level_ID.required ? ew.Validators.required(fields.User_Level_ID.caption) : null, ew.Validators.integer], fields.User_Level_ID.isInvalid],
        ["Table_Name", [fields.Table_Name.visible && fields.Table_Name.required ? ew.Validators.required(fields.Table_Name.caption) : null], fields.Table_Name.isInvalid],
        ["_Permission", [fields._Permission.visible && fields._Permission.required ? ew.Validators.required(fields._Permission.caption) : null, ew.Validators.integer], fields._Permission.isInvalid]
    ]);

    // Form_CustomValidate
    fuserlevelpermissionsadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fuserlevelpermissionsadd.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    loadjs.done("fuserlevelpermissionsadd");
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
<form name="fuserlevelpermissionsadd" id="fuserlevelpermissionsadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="userlevelpermissions">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->User_Level_ID->Visible) { // User_Level_ID ?>
    <div id="r_User_Level_ID"<?= $Page->User_Level_ID->rowAttributes() ?>>
        <label id="elh_userlevelpermissions_User_Level_ID" for="x_User_Level_ID" class="<?= $Page->LeftColumnClass ?>"><?= $Page->User_Level_ID->caption() ?><?= $Page->User_Level_ID->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->User_Level_ID->cellAttributes() ?>>
<span id="el_userlevelpermissions_User_Level_ID">
<input type="<?= $Page->User_Level_ID->getInputTextType() ?>" name="x_User_Level_ID" id="x_User_Level_ID" data-table="userlevelpermissions" data-field="x_User_Level_ID" value="<?= $Page->User_Level_ID->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->User_Level_ID->getPlaceHolder()) ?>"<?= $Page->User_Level_ID->editAttributes() ?> aria-describedby="x_User_Level_ID_help">
<?= $Page->User_Level_ID->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->User_Level_ID->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Table_Name->Visible) { // Table_Name ?>
    <div id="r_Table_Name"<?= $Page->Table_Name->rowAttributes() ?>>
        <label id="elh_userlevelpermissions_Table_Name" for="x_Table_Name" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Table_Name->caption() ?><?= $Page->Table_Name->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Table_Name->cellAttributes() ?>>
<span id="el_userlevelpermissions_Table_Name">
<input type="<?= $Page->Table_Name->getInputTextType() ?>" name="x_Table_Name" id="x_Table_Name" data-table="userlevelpermissions" data-field="x_Table_Name" value="<?= $Page->Table_Name->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->Table_Name->getPlaceHolder()) ?>"<?= $Page->Table_Name->editAttributes() ?> aria-describedby="x_Table_Name_help">
<?= $Page->Table_Name->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Table_Name->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->_Permission->Visible) { // Permission ?>
    <div id="r__Permission"<?= $Page->_Permission->rowAttributes() ?>>
        <label id="elh_userlevelpermissions__Permission" for="x__Permission" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_Permission->caption() ?><?= $Page->_Permission->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->_Permission->cellAttributes() ?>>
<span id="el_userlevelpermissions__Permission">
<input type="<?= $Page->_Permission->getInputTextType() ?>" name="x__Permission" id="x__Permission" data-table="userlevelpermissions" data-field="x__Permission" value="<?= $Page->_Permission->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->_Permission->getPlaceHolder()) ?>"<?= $Page->_Permission->editAttributes() ?> aria-describedby="x__Permission_help">
<?= $Page->_Permission->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_Permission->getErrorMessage() ?></div>
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
    ew.addEventHandlers("userlevelpermissions");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
