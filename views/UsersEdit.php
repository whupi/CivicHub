<?php

namespace PHPMaker2022\civichub2;

// Page object
$UsersEdit = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { users: currentTable } });
var currentForm, currentPageID;
var fusersedit;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fusersedit = new ew.Form("fusersedit", "edit");
    currentPageID = ew.PAGE_ID = "edit";
    currentForm = fusersedit;

    // Add fields
    var fields = currentTable.fields;
    fusersedit.addFields([
        ["_Username", [fields._Username.visible && fields._Username.required ? ew.Validators.required(fields._Username.caption) : null], fields._Username.isInvalid],
        ["_Password", [fields._Password.visible && fields._Password.required ? ew.Validators.required(fields._Password.caption) : null], fields._Password.isInvalid],
        ["First_Name", [fields.First_Name.visible && fields.First_Name.required ? ew.Validators.required(fields.First_Name.caption) : null], fields.First_Name.isInvalid],
        ["Last_Name", [fields.Last_Name.visible && fields.Last_Name.required ? ew.Validators.required(fields.Last_Name.caption) : null], fields.Last_Name.isInvalid],
        ["_Email", [fields._Email.visible && fields._Email.required ? ew.Validators.required(fields._Email.caption) : null], fields._Email.isInvalid],
        ["User_Level", [fields.User_Level.visible && fields.User_Level.required ? ew.Validators.required(fields.User_Level.caption) : null], fields.User_Level.isInvalid],
        ["Report_To", [fields.Report_To.visible && fields.Report_To.required ? ew.Validators.required(fields.Report_To.caption) : null], fields.Report_To.isInvalid],
        ["Activated", [fields.Activated.visible && fields.Activated.required ? ew.Validators.required(fields.Activated.caption) : null], fields.Activated.isInvalid],
        ["Locked", [fields.Locked.visible && fields.Locked.required ? ew.Validators.required(fields.Locked.caption) : null], fields.Locked.isInvalid],
        ["Photo", [fields.Photo.visible && fields.Photo.required ? ew.Validators.fileRequired(fields.Photo.caption) : null], fields.Photo.isInvalid]
    ]);

    // Form_CustomValidate
    fusersedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fusersedit.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    fusersedit.lists.User_Level = <?= $Page->User_Level->toClientList($Page) ?>;
    fusersedit.lists.Report_To = <?= $Page->Report_To->toClientList($Page) ?>;
    fusersedit.lists.Activated = <?= $Page->Activated->toClientList($Page) ?>;
    fusersedit.lists.Locked = <?= $Page->Locked->toClientList($Page) ?>;
    loadjs.done("fusersedit");
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
<form name="fusersedit" id="fusersedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="users">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php if ($Page->getCurrentMasterTable() == "userlevels") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="userlevels">
<input type="hidden" name="fk_User_Level_ID" value="<?= HtmlEncode($Page->User_Level->getSessionValue()) ?>">
<?php } ?>
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->_Username->Visible) { // Username ?>
    <div id="r__Username"<?= $Page->_Username->rowAttributes() ?>>
        <label id="elh_users__Username" for="x__Username" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_Username->caption() ?><?= $Page->_Username->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->_Username->cellAttributes() ?>>
<input type="<?= $Page->_Username->getInputTextType() ?>" name="x__Username" id="x__Username" data-table="users" data-field="x__Username" value="<?= $Page->_Username->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->_Username->getPlaceHolder()) ?>"<?= $Page->_Username->editAttributes() ?> aria-describedby="x__Username_help">
<?= $Page->_Username->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_Username->getErrorMessage() ?></div>
<input type="hidden" data-table="users" data-field="x__Username" data-hidden="1" name="o__Username" id="o__Username" value="<?= HtmlEncode($Page->_Username->OldValue ?? $Page->_Username->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->_Password->Visible) { // Password ?>
    <div id="r__Password"<?= $Page->_Password->rowAttributes() ?>>
        <label id="elh_users__Password" for="x__Password" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_Password->caption() ?><?= $Page->_Password->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->_Password->cellAttributes() ?>>
<span id="el_users__Password">
<div class="input-group">
    <input type="password" name="x__Password" id="x__Password" autocomplete="new-password" data-field="x__Password" value="<?= $Page->_Password->EditValue ?>" size="30" maxlength="64" placeholder="<?= HtmlEncode($Page->_Password->getPlaceHolder()) ?>"<?= $Page->_Password->editAttributes() ?> aria-describedby="x__Password_help">
    <button type="button" class="btn btn-default ew-toggle-password rounded-end" data-ew-action="password"><i class="fas fa-eye"></i></button>
</div>
<?= $Page->_Password->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_Password->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->First_Name->Visible) { // First_Name ?>
    <div id="r_First_Name"<?= $Page->First_Name->rowAttributes() ?>>
        <label id="elh_users_First_Name" for="x_First_Name" class="<?= $Page->LeftColumnClass ?>"><?= $Page->First_Name->caption() ?><?= $Page->First_Name->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->First_Name->cellAttributes() ?>>
<span id="el_users_First_Name">
<input type="<?= $Page->First_Name->getInputTextType() ?>" name="x_First_Name" id="x_First_Name" data-table="users" data-field="x_First_Name" value="<?= $Page->First_Name->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->First_Name->getPlaceHolder()) ?>"<?= $Page->First_Name->editAttributes() ?> aria-describedby="x_First_Name_help">
<?= $Page->First_Name->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->First_Name->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Last_Name->Visible) { // Last_Name ?>
    <div id="r_Last_Name"<?= $Page->Last_Name->rowAttributes() ?>>
        <label id="elh_users_Last_Name" for="x_Last_Name" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Last_Name->caption() ?><?= $Page->Last_Name->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Last_Name->cellAttributes() ?>>
<span id="el_users_Last_Name">
<input type="<?= $Page->Last_Name->getInputTextType() ?>" name="x_Last_Name" id="x_Last_Name" data-table="users" data-field="x_Last_Name" value="<?= $Page->Last_Name->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->Last_Name->getPlaceHolder()) ?>"<?= $Page->Last_Name->editAttributes() ?> aria-describedby="x_Last_Name_help">
<?= $Page->Last_Name->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Last_Name->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->_Email->Visible) { // Email ?>
    <div id="r__Email"<?= $Page->_Email->rowAttributes() ?>>
        <label id="elh_users__Email" for="x__Email" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_Email->caption() ?><?= $Page->_Email->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->_Email->cellAttributes() ?>>
<span id="el_users__Email">
<input type="<?= $Page->_Email->getInputTextType() ?>" name="x__Email" id="x__Email" data-table="users" data-field="x__Email" value="<?= $Page->_Email->EditValue ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->_Email->getPlaceHolder()) ?>"<?= $Page->_Email->editAttributes() ?> aria-describedby="x__Email_help">
<?= $Page->_Email->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_Email->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->User_Level->Visible) { // User_Level ?>
    <div id="r_User_Level"<?= $Page->User_Level->rowAttributes() ?>>
        <label id="elh_users_User_Level" for="x_User_Level" class="<?= $Page->LeftColumnClass ?>"><?= $Page->User_Level->caption() ?><?= $Page->User_Level->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->User_Level->cellAttributes() ?>>
<?php if ($Page->User_Level->getSessionValue() != "") { ?>
<span id="el_users_User_Level">
<span<?= $Page->User_Level->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Page->User_Level->getDisplayValue($Page->User_Level->ViewValue) ?></span></span>
</span>
<input type="hidden" id="x_User_Level" name="x_User_Level" value="<?= HtmlEncode(FormatNumber($Page->User_Level->CurrentValue, $Page->User_Level->formatPattern())) ?>" data-hidden="1">
<?php } elseif (!$Security->isAdmin() && $Security->isLoggedIn()) { // Non system admin ?>
<span id="el_users_User_Level">
<span class="form-control-plaintext"><?= $Page->User_Level->getDisplayValue($Page->User_Level->EditValue) ?></span>
</span>
<?php } else { ?>
<span id="el_users_User_Level">
    <select
        id="x_User_Level"
        name="x_User_Level"
        class="form-select ew-select<?= $Page->User_Level->isInvalidClass() ?>"
        data-select2-id="fusersedit_x_User_Level"
        data-table="users"
        data-field="x_User_Level"
        data-value-separator="<?= $Page->User_Level->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->User_Level->getPlaceHolder()) ?>"
        <?= $Page->User_Level->editAttributes() ?>>
        <?= $Page->User_Level->selectOptionListHtml("x_User_Level") ?>
    </select>
    <?= $Page->User_Level->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->User_Level->getErrorMessage() ?></div>
<?= $Page->User_Level->Lookup->getParamTag($Page, "p_x_User_Level") ?>
<script>
loadjs.ready("fusersedit", function() {
    var options = { name: "x_User_Level", selectId: "fusersedit_x_User_Level" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fusersedit.lists.User_Level.lookupOptions.length) {
        options.data = { id: "x_User_Level", form: "fusersedit" };
    } else {
        options.ajax = { id: "x_User_Level", form: "fusersedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.users.fields.User_Level.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Report_To->Visible) { // Report_To ?>
    <div id="r_Report_To"<?= $Page->Report_To->rowAttributes() ?>>
        <label id="elh_users_Report_To" for="x_Report_To" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Report_To->caption() ?><?= $Page->Report_To->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Report_To->cellAttributes() ?>>
<?php if (!$Security->isAdmin() && $Security->isLoggedIn()) { // Non system admin ?>
<?php if (SameString($Page->_Username->CurrentValue, CurrentUserID())) { ?>
    <span id="el_users_Report_To">
    <span<?= $Page->Report_To->viewAttributes() ?>>
    <span class="form-control-plaintext"><?= $Page->Report_To->getDisplayValue($Page->Report_To->EditValue) ?></span></span>
    </span>
    <input type="hidden" data-table="users" data-field="x_Report_To" data-hidden="1" name="x_Report_To" id="x_Report_To" value="<?= HtmlEncode($Page->Report_To->CurrentValue) ?>">
<?php } else { ?>
<span id="el_users_Report_To">
    <select
        id="x_Report_To"
        name="x_Report_To"
        class="form-select ew-select<?= $Page->Report_To->isInvalidClass() ?>"
        data-select2-id="fusersedit_x_Report_To"
        data-table="users"
        data-field="x_Report_To"
        data-value-separator="<?= $Page->Report_To->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->Report_To->getPlaceHolder()) ?>"
        <?= $Page->Report_To->editAttributes() ?>>
        <?= $Page->Report_To->selectOptionListHtml("x_Report_To") ?>
    </select>
    <?= $Page->Report_To->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->Report_To->getErrorMessage() ?></div>
<?= $Page->Report_To->Lookup->getParamTag($Page, "p_x_Report_To") ?>
<script>
loadjs.ready("fusersedit", function() {
    var options = { name: "x_Report_To", selectId: "fusersedit_x_Report_To" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fusersedit.lists.Report_To.lookupOptions.length) {
        options.data = { id: "x_Report_To", form: "fusersedit" };
    } else {
        options.ajax = { id: "x_Report_To", form: "fusersedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.users.fields.Report_To.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php } else { ?>
<span id="el_users_Report_To">
    <select
        id="x_Report_To"
        name="x_Report_To"
        class="form-select ew-select<?= $Page->Report_To->isInvalidClass() ?>"
        data-select2-id="fusersedit_x_Report_To"
        data-table="users"
        data-field="x_Report_To"
        data-value-separator="<?= $Page->Report_To->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->Report_To->getPlaceHolder()) ?>"
        <?= $Page->Report_To->editAttributes() ?>>
        <?= $Page->Report_To->selectOptionListHtml("x_Report_To") ?>
    </select>
    <?= $Page->Report_To->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->Report_To->getErrorMessage() ?></div>
<?= $Page->Report_To->Lookup->getParamTag($Page, "p_x_Report_To") ?>
<script>
loadjs.ready("fusersedit", function() {
    var options = { name: "x_Report_To", selectId: "fusersedit_x_Report_To" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fusersedit.lists.Report_To.lookupOptions.length) {
        options.data = { id: "x_Report_To", form: "fusersedit" };
    } else {
        options.ajax = { id: "x_Report_To", form: "fusersedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.users.fields.Report_To.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Activated->Visible) { // Activated ?>
    <div id="r_Activated"<?= $Page->Activated->rowAttributes() ?>>
        <label id="elh_users_Activated" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Activated->caption() ?><?= $Page->Activated->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Activated->cellAttributes() ?>>
<span id="el_users_Activated">
<div class="form-check d-inline-block">
    <input type="checkbox" class="form-check-input<?= $Page->Activated->isInvalidClass() ?>" data-table="users" data-field="x_Activated" name="x_Activated[]" id="x_Activated_448847" value="1"<?= ConvertToBool($Page->Activated->CurrentValue) ? " checked" : "" ?><?= $Page->Activated->editAttributes() ?> aria-describedby="x_Activated_help">
    <div class="invalid-feedback"><?= $Page->Activated->getErrorMessage() ?></div>
</div>
<?= $Page->Activated->getCustomMessage() ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Locked->Visible) { // Locked ?>
    <div id="r_Locked"<?= $Page->Locked->rowAttributes() ?>>
        <label id="elh_users_Locked" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Locked->caption() ?><?= $Page->Locked->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Locked->cellAttributes() ?>>
<span id="el_users_Locked">
<div class="form-check d-inline-block">
    <input type="checkbox" class="form-check-input<?= $Page->Locked->isInvalidClass() ?>" data-table="users" data-field="x_Locked" name="x_Locked[]" id="x_Locked_736940" value="1"<?= ConvertToBool($Page->Locked->CurrentValue) ? " checked" : "" ?><?= $Page->Locked->editAttributes() ?> aria-describedby="x_Locked_help">
    <div class="invalid-feedback"><?= $Page->Locked->getErrorMessage() ?></div>
</div>
<?= $Page->Locked->getCustomMessage() ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Photo->Visible) { // Photo ?>
    <div id="r_Photo"<?= $Page->Photo->rowAttributes() ?>>
        <label id="elh_users_Photo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Photo->caption() ?><?= $Page->Photo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Photo->cellAttributes() ?>>
<span id="el_users_Photo">
<div id="fd_x_Photo" class="fileinput-button ew-file-drop-zone">
    <input type="file" class="form-control ew-file-input" title="<?= $Page->Photo->title() ?>" data-table="users" data-field="x_Photo" name="x_Photo" id="x_Photo" lang="<?= CurrentLanguageID() ?>"<?= $Page->Photo->editAttributes() ?> aria-describedby="x_Photo_help"<?= ($Page->Photo->ReadOnly || $Page->Photo->Disabled) ? " disabled" : "" ?>>
    <div class="text-muted ew-file-text"><?= $Language->phrase("ChooseFile") ?></div>
</div>
<?= $Page->Photo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Photo->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_Photo" id= "fn_x_Photo" value="<?= $Page->Photo->Upload->FileName ?>">
<input type="hidden" name="fa_x_Photo" id= "fa_x_Photo" value="<?= (Post("fa_x_Photo") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_Photo" id= "fs_x_Photo" value="100">
<input type="hidden" name="fx_x_Photo" id= "fx_x_Photo" value="<?= $Page->Photo->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_Photo" id= "fm_x_Photo" value="<?= $Page->Photo->UploadMaxFileSize ?>">
<table id="ft_x_Photo" class="table table-sm float-start ew-upload-table"><tbody class="files"></tbody></table>
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
    ew.addEventHandlers("users");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
