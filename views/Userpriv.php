<?php

namespace PHPMaker2022\civichub2;

// Page object
$Userpriv = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { userlevels: currentTable } });
var currentForm, currentPageID;
var fuserpriv;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fuserpriv = new ew.Form("fuserpriv", "userpriv");
    currentPageID = ew.PAGE_ID = "userpriv";
    currentForm = fuserpriv;
    loadjs.done("fuserpriv");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your client script here, no need to add script tags.
});
</script>
<?php
$Page->showMessage();
?>
<form name="fuserpriv" id="fuserpriv" class="ew-form ew-user-priv-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="userlevels">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="x_User_Level_ID" id="x_User_Level_ID" value="<?= $Page->User_Level_ID->CurrentValue ?>">
<div class="ew-desktop">
<div class="card ew-card ew-user-priv">
<div class="card-header">
    <h3 class="card-title"><?= $Language->phrase("UserLevel") ?><?= $Security->getUserLevelName((int)$Page->User_Level_ID->CurrentValue) ?> (<?= $Page->User_Level_ID->CurrentValue ?>)</h3>
    <div class="card-tools">
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
            <input type="text" name="table-name" id="table-name" class="form-control form-control-sm" placeholder="<?= HtmlEncode($Language->phrase("Search")) ?>">
        </div>
    </div>
</div>
<div class="<?= ResponsiveTableClass() ?>card-body ew-card-body p-0"></div>
</div>
<div class="ew-desktop-button">
<button class="btn btn-primary ew-btn" name="btn-submit" id="btn-submit" type="submit"<?= $Page->Disabled ?>><?= $Language->phrase("Update") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
</div>
</div>
</form>
<script>
var useFixedHeaderTable = false,
    tableHeight = "",
    priv = <?= JsonEncode($Page->Privileges) ?>;
ew.ready("makerjs", [
    ew.PATH_BASE + "jquery/jquery.jtable.min.js",
    ew.PATH_BASE + "js/userpriv.min.js"
]);
</script>
<script>
loadjs.ready("load", function () {
    // Write your startup script here, no need to add script tags.
});
</script>
