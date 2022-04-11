<?php

namespace PHPMaker2022\civichub2;

// Page object
$UserlevelsAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { userlevels: currentTable } });
var currentForm, currentPageID;
var fuserlevelsadd;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fuserlevelsadd = new ew.Form("fuserlevelsadd", "add");
    currentPageID = ew.PAGE_ID = "add";
    currentForm = fuserlevelsadd;

    // Add fields
    var fields = currentTable.fields;
    fuserlevelsadd.addFields([
        ["User_Level_Name", [fields.User_Level_Name.visible && fields.User_Level_Name.required ? ew.Validators.required(fields.User_Level_Name.caption) : null, ew.Validators.userLevelName('User_Level_ID')], fields.User_Level_Name.isInvalid]
    ]);

    // Form_CustomValidate
    fuserlevelsadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fuserlevelsadd.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    loadjs.done("fuserlevelsadd");
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
<form name="fuserlevelsadd" id="fuserlevelsadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="userlevels">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
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
    <!-- row for permission values -->
    <div id="rp_permission" class="row">
        <label id="elh_permission" class="<?= $Page->LeftColumnClass ?>"><?= HtmlTitle($Language->phrase("Permission")) ?></label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="x__AllowAdd" id="Add" value="<?= Config("ALLOW_ADD") ?>"><label class="form-check-label" for="Add"><?= $Language->phrase("PermissionAdd") ?></label>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="x__AllowDelete" id="Delete" value="<?= Config("ALLOW_DELETE") ?>"><label class="form-check-label" for="Delete"><?= $Language->phrase("PermissionDelete") ?></label>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="x__AllowEdit" id="Edit" value="<?= Config("ALLOW_EDIT") ?>"><label class="form-check-label" for="Edit"><?= $Language->phrase("PermissionEdit") ?></label>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="x__AllowList" id="List" value="<?= Config("ALLOW_LIST") ?>"><label class="form-check-label" for="List"><?= $Language->phrase("PermissionList") ?></label>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="x__AllowLookup" id="Lookup" value="<?= Config("ALLOW_LOOKUP") ?>"><label class="form-check-label" for="Lookup"><?= $Language->phrase("PermissionLookup") ?></label>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="x__AllowView" id="View" value="<?= Config("ALLOW_VIEW") ?>"><label class="form-check-label" for="View"><?= $Language->phrase("PermissionView") ?></label>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="x__AllowSearch" id="Search" value="<?= Config("ALLOW_SEARCH") ?>"><label class="form-check-label" for="Search"><?= $Language->phrase("PermissionSearch") ?></label>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="x__AllowImport" id="Import" value="<?= Config("ALLOW_IMPORT") ?>"><label class="form-check-label" for="Import"><?= $Language->phrase("PermissionImport") ?></label>
            </div>
<?php if (IsSysAdmin()) { ?>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="x__AllowAdmin" id="Admin" value="<?= Config("ALLOW_ADMIN") ?>"><label class="form-check-label" for="Admin"><?= $Language->phrase("PermissionAdmin") ?></label>
            </div>
<?php } ?>
        </div>
    </div>
</div><!-- /page* -->
<?php
    if (in_array("users", explode(",", $Page->getCurrentDetailTable())) && $users->DetailAdd) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("users", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "UsersGrid.php" ?>
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
    ew.addEventHandlers("userlevels");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
