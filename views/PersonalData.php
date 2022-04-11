<?php namespace PHPMaker2022\civichub2; ?>
<?php

namespace PHPMaker2022\civichub2;

// Page object
$PersonalData = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php if (SameText(Get("cmd"), "Delete")) { ?>
    <script>
        var fdeleteuser;
        loadjs.ready(["wrapper", "head"], function() {
            var $ = jQuery;
            fdeleteuser = new ew.Form("fdeleteuser");

            // Add field
            fdeleteuser.addFields([
                ["password", ew.Validators.required(ew.language.phrase("Password"))]
            ]);

            // Extend page with Validate function
            fdeleteuser.validate = function() {
                if (!this.validateRequired)
                    return true; // Ignore validation

                // Validate fields
                if (!this.validateFields())
                    return false;
                return true;
            }

            // Use JavaScript validation
            fdeleteuser.validateRequired = ew.CLIENT_VALIDATE;
            loadjs.done("fdeleteuser");
        });
    </script>
    <div class="alert alert-danger d-inline-block">
        <i class="icon fas fa-ban"></i><?= $Language->phrase("PersonalDataWarning") ?>
    </div>
    <?php if (!EmptyString($Page->getFailureMessage())) { ?>
    <div class="text-danger">
        <ul>
            <li><?= $Page->getFailureMessage() ?></li>
        </ul>
    </div>
    <?php } ?>
    <div class="container-fluid">
        <form name="fdeleteuser" class="ew-form ew-personaldata-form" id="fdeleteuser" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
            <div class="text-danger"></div>
            <div class="row">
                <div class="col-sm-auto">
                    <label id="label" class="control-label ew-label"><?= $Language->phrase("Password") ?></label>
                </div>
                <div class="col-sm-auto">
                    <div class="input-group">
                        <input type="password" name="<?= $Page->Password->FieldVar ?>" id="<?= $Page->Password->FieldVar ?>" autocomplete="current-password" placeholder="<?= HtmlEncode($Language->phrase("Password")) ?>"<?= $Page->Password->editAttributes() ?>>
                        <button type="button" class="btn btn-default ew-toggle-password rounded-end" data-ew-action="password"><i class="fas fa-eye"></i></button>
                        <div class="invalid-feedback"><?= $Page->Password->getErrorMessage() ?></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-auto">
                    <button class="btn btn-primary" type="submit"><?= $Language->phrase("CloseAccountBtn") ?></button>
                </div>
            </div>
        </form>
    </div>
<?php } else { ?>
    <div class="row">
        <div class="col">
            <p><?= $Language->phrase("PersonalDataContent") ?></p>
            <div class="alert alert-danger d-inline-block">
                <i class="icon fas fa-ban"></i><?= $Language->phrase("PersonalDataWarning") ?>
            </div>
            <p>
                <a id="download" href="<?= HtmlEncode(GetUrl(CurrentPageUrl(false) . "?cmd=download")) ?>" class="btn btn-default"><?= $Language->phrase("DownloadBtn") ?></a>
                <a id="delete" href="<?= HtmlEncode(GetUrl(CurrentPageUrl(false) . "?cmd=delete")) ?>" class="btn btn-default"><?= $Language->phrase("DeleteBtn") ?></a>
            </p>
        </div>
    </div>
<?php } ?>
<?php $Page->clearFailureMessage(); ?>
