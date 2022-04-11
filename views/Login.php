<?php namespace PHPMaker2022\civichub2; ?>
<?php

namespace PHPMaker2022\civichub2;

// Page object
$Login = &$Page;
?>
<script>
loadjs.ready("head", function () {
    // Write your client script here, no need to add script tags.
});
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<?php if (!$Page->IsModal) { ?>
<div class="ew-login-box shadow-sm">
<?php } else { ?>
<div class="ew-login-box">
<?php } ?>
    <div class="login-logo"></div>
<?php if (!$Page->IsModal) { ?>
    <div class="card ew-login-card">
        <div class="card-body">
<?php } ?>
<script>
// Script inside .card-body
var flogin;
loadjs.ready(["wrapper", "head"], function() {
    var $ = jQuery;
    flogin = new ew.Form("flogin");
	ew.PAGE_ID ||= "login";
    window.currentPageID ||= "login";
    window.currentForm ||= flogin;

    // Add fields
    flogin.addFields([
        ["username", ew.Validators.required(ew.language.phrase("UserName")), <?= $Page->Username->IsInvalid ? "true" : "false" ?>],
        ["password", ew.Validators.required(ew.language.phrase("Password")), <?= $Page->Password->IsInvalid ? "true" : "false" ?>]
    ]);

    // Captcha
    <?= Captcha()->getScript("flogin") ?>

    // Validate
    flogin.validate = function() {
        if (!this.validateRequired)
            return true; // Ignore validation
        var fobj = this.getForm();

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
    flogin.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation
    flogin.validateRequired = ew.CLIENT_VALIDATE;
    loadjs.done("flogin");
});
</script>
<form name="flogin" id="flogin" class="ew-form ew-login-form" action="<?= CurrentPageUrl(false) ?>" method="post">
    <?php if (Config("CHECK_TOKEN")) { ?>
    <input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
    <input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
    <?php } ?>
    <p class="login-box-msg"><?= $Language->phrase("LoginMsg") ?></p>
    <div class="row gx-0">
        <input type="text" name="<?= $Page->Username->FieldVar ?>" id="<?= $Page->Username->FieldVar ?>" autocomplete="username" value="<?= HtmlEncode($Page->Username->CurrentValue) ?>" placeholder="<?= HtmlEncode($Language->phrase("Username")) ?>"<?= $Page->Username->editAttributes() ?>>
        <div class="invalid-feedback"><?= $Page->Username->getErrorMessage() ?></div>
    </div>
    <div class="row gx-0">
        <div class="input-group px-0">
            <input type="password" name="<?= $Page->Password->FieldVar ?>" id="<?= $Page->Password->FieldVar ?>" autocomplete="current-password" placeholder="<?= HtmlEncode($Language->phrase("Password")) ?>"<?= $Page->Password->editAttributes() ?>>
            <button type="button" class="btn btn-default ew-toggle-password rounded-end" data-ew-action="password"><i class="fas fa-eye"></i></button>
        </div>
        <div class="invalid-feedback"><?= $Page->Password->getErrorMessage() ?></div>
    </div>
    <div class="row gx-0">
        <div class="form-check form-switch d-inline-block" style="vertical-align: middle;">
            <input type="checkbox" class="form-check-input" name="<?= $Page->LoginType->FieldVar ?>" id="<?= $Page->LoginType->FieldVar ?>" class="form-check-input" value="a"<?php if ($Page->LoginType->CurrentValue == "a") { ?> checked<?php } ?>>
            <label class="form-check-label" for="<?= $Page->LoginType->FieldVar ?>"><?= $Language->phrase("RememberMe") ?></label>
        </div>
    </div>
    <div class="d-grid">
        <button class="btn btn-primary ew-btn" name="btn-submit" id="btn-submit" type="submit" formaction="<?= CurrentPageUrl(false) ?>"><?= $Language->phrase("Login", true) ?></button>
    </div>
<?php
// OAuth login
$providers = Config("AUTH_CONFIG.providers");
$cntProviders = 0;
foreach ($providers as $id => $provider) {
    if ($provider["enabled"]) {
        $cntProviders++;
    }
}
if ($cntProviders > 0) {
?>
    <div class="social-auth-links text-center mt-3 d-grid gap-2">
        <p><?= $Language->phrase("LoginOr") ?></p>
<?php
        foreach ($providers as $id => $provider) {
            if ($provider["enabled"]) {
?>
            <a href="<?= CurrentPageUrl(false) ?>?provider=<?= $id ?>" class="btn btn-<?= strtolower($provider["color"]) ?>"><i class="fab fa-<?= strtolower($id) ?> me-2"></i><?= $Language->phrase("Login" . $id) ?></a>
<?php
            }
        }
?>
    </div>
<?php
}
?>
<div class="login-page-links text-center mt-3"></div>
<script type="text/html" class="ew-js-template"<?php if (!$Page->IsModal) { ?> data-name="login-page" data-seq="10"<?php } ?> data-data="login" data-target=".login-page-links">
{{if canResetPassword && resetPassword}}
<a class="card-link me-2"{{props resetPassword}} data-{{:key}}="{{>prop}}"{{/props}}>{{:resetPasswordText}}</a>
{{/if}}
{{if canRegister && register}}
<a class="card-link me-2"{{props register}} data-{{:key}}="{{>prop}}"{{/props}}>{{:registerText}}</a>
{{/if}}
</script>
</form>
<?php if (!$Page->IsModal) { ?>
        </div><!-- ./card-body -->
    </div><!-- ./card -->
<?php } ?>
</div><!-- ./ew-login-box -->
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
loadjs.ready("load", function(){
  setTimeout(function (){
    $("#username").focus();
  }, 500);
<?php if (!$Page->IsModal) { ?>
	$("div.ew-login-box").css({"width":"350px"});
<?php } else { ?>
	$("div.ew-login-box").css({"width":"auto"});
<?php } ?>
});
</script>
<?php if (Config("MS_ENTER_MOVING_CURSOR_TO_NEXT_FIELD")) { ?>
<script>
loadjs.ready("head", function() { $("#flogin:first *:input[type!=hidden]:first").focus(),$("input").keydown(function(i){if(13==i.which){var e=$(this).closest("form").find(":input:visible:enabled"),n=e.index(this);n==e.length-1||(e.eq(e.index(this)+1).focus(),i.preventDefault())}else 113==i.which&&$("#btn-submit").click()}),$("select").keydown(function(i){if(13==i.which){var e=$(this).closest("form").find(":input:visible:enabled"),n=e.index(this);n==e.length-1||(e.eq(e.index(this)+1).focus(),i.preventDefault())}else 113==i.which&&$("#btn-submit").click()}),$("radio").keydown(function(i){if(13==i.which){var e=$(this).closest("form").find(":input:visible:enabled"),n=e.index(this);n==e.length-1||(e.eq(e.index(this)+1).focus(),i.preventDefault())}else 113==i.which&&$("#btn-submit").click()})});
</script>
<?php } ?>
<script>
loadjs.ready("load", function () {
    // Write your startup script here, no need to add script tags.
});
</script>
