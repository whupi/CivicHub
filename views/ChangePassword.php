<?php

namespace PHPMaker2022\civichub2;

// Page object
$ChangePassword = &$Page;
?>
<script>
loadjs.ready("head", function () {
    // Write your client script here, no need to add script tags.
});
</script>
<script>
var fchange_password;
loadjs.ready(["wrapper", "head"], function() {
    var $ = jQuery;
    fchange_password = new ew.Form("fchange_password");
    ew.PAGE_ID ||= "change_password";
    window.currentPageID ||= "change_password";
    window.currentForm ||= fchange_password;

    // Add fields
    fchange_password.addFields([
    <?php if (!IsPasswordReset()) { ?>
        ["opwd", ew.Validators.required(ew.language.phrase("OldPassword"))],
    <?php } ?>
        ["npwd", [ew.Validators.required(ew.language.phrase("NewPassword")), ew.Validators.password(<?= $Page->NewPassword->Raw ? "true" : "false" ?>)]],
        ["cpwd", [ew.Validators.required(ew.language.phrase("ConfirmPassword")), ew.Validators.mismatchPassword]]
    ]);

    // Validate
    fchange_password.validate = function() {
        if (!this.validateRequired)
            return true; // Ignore validation
        var $ = jQuery,
            fobj = this.getForm();

        // Validate fields
        if (!this.validateFields())
            return false;

        // Call Form_CustomValidate event
        if (!this.customValidate(fobj)) {
            this.focus();
            return false;
        }
        return true;
    }

    // Form_CustomValidate
    fchange_password.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation
    fchange_password.validateRequired = ew.CLIENT_VALIDATE;
    loadjs.done("fchange_password");
});
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fchange_password" id="fchange_password" class="ew-form ew-change-pwd-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<div class="ew-change-pwd-box">
<div class="card">
<div class="card-body">
<p class="login-box-msg"><?= $Language->phrase("ChangePasswordMessage") ?></p>
<?php if (!IsPasswordReset()) { ?>
    <div class="row">
        <div class="input-group">
            <input type="password" name="<?= $Page->OldPassword->FieldVar ?>" id="<?= $Page->OldPassword->FieldVar ?>" autocomplete="current-password" placeholder="<?= HtmlEncode($Language->phrase("OldPassword")) ?>"<?= $Page->OldPassword->editAttributes() ?>>
            <button type="button" class="btn btn-default ew-toggle-password rounded-end" data-ew-action="password"><i class="fas fa-eye"></i></button>
        </div>
        <div class="invalid-feedback"><?= $Page->OldPassword->getErrorMessage() ?></div>
    </div>
<?php } ?>
    <div class="row gx-0">
        <div class="input-group px-0">
            <input type="password" name="<?= $Page->NewPassword->FieldVar ?>" id="<?= $Page->NewPassword->FieldVar ?>" autocomplete="new-password" placeholder="<?= HtmlEncode($Language->phrase("NewPassword")) ?>"<?= $Page->NewPassword->editAttributes() ?>>
            <button type="button" class="btn btn-default ew-toggle-password rounded-end" data-ew-action="password"><i class="fas fa-eye"></i></button>
        </div>
        <div class="invalid-feedback"><?= $Page->NewPassword->getErrorMessage() ?></div>
    </div>
    <div class="row gx-0">
        <div class="input-group px-0">
            <input type="password" name="<?= $Page->ConfirmPassword->FieldVar ?>" id="<?= $Page->ConfirmPassword->FieldVar ?>" autocomplete="new-password" placeholder="<?= HtmlEncode($Language->phrase("ConfirmPassword")) ?>"<?= $Page->ConfirmPassword->editAttributes() ?>>
            <button type="button" class="btn btn-default ew-toggle-password rounded-end" data-ew-action="password"><i class="fas fa-eye"></i></button>
        </div>
        <div class="invalid-feedback"><?= $Page->ConfirmPassword->getErrorMessage() ?></div>
    </div>
<div class="d-grid mb-3">
    <button class="btn btn-primary ew-btn" name="btn-submit" id="btn-submit" type="submit" formaction="<?= CurrentPageUrl(false) ?>"><?= $Language->phrase("ChangePasswordBtn") ?></button>
</div>
</div>
</div>
</div>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
loadjs.ready("load", function () {
    // Write your startup script here, no need to add script tags.
});
</script>
