<?php

namespace PHPMaker2022\civichub2;

// Page object
$SubmissionView2Add = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { submission_view2: currentTable } });
var currentForm, currentPageID;
var fsubmission_view2add;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fsubmission_view2add = new ew.Form("fsubmission_view2add", "add");
    currentPageID = ew.PAGE_ID = "add";
    currentForm = fsubmission_view2add;

    // Add fields
    var fields = currentTable.fields;
    fsubmission_view2add.addFields([
        ["_Title", [fields._Title.visible && fields._Title.required ? ew.Validators.required(fields._Title.caption) : null], fields._Title.isInvalid],
        ["Category_ID", [fields.Category_ID.visible && fields.Category_ID.required ? ew.Validators.required(fields.Category_ID.caption) : null], fields.Category_ID.isInvalid],
        ["Status", [fields.Status.visible && fields.Status.required ? ew.Validators.required(fields.Status.caption) : null], fields.Status.isInvalid],
        ["_Abstract", [fields._Abstract.visible && fields._Abstract.required ? ew.Validators.required(fields._Abstract.caption) : null], fields._Abstract.isInvalid],
        ["Tags", [fields.Tags.visible && fields.Tags.required ? ew.Validators.required(fields.Tags.caption) : null], fields.Tags.isInvalid],
        ["Uploads", [fields.Uploads.visible && fields.Uploads.required ? ew.Validators.fileRequired(fields.Uploads.caption) : null], fields.Uploads.isInvalid],
        ["Cover", [fields.Cover.visible && fields.Cover.required ? ew.Validators.fileRequired(fields.Cover.caption) : null], fields.Cover.isInvalid]
    ]);

    // Form_CustomValidate
    fsubmission_view2add.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fsubmission_view2add.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    fsubmission_view2add.lists.Category_ID = <?= $Page->Category_ID->toClientList($Page) ?>;
    fsubmission_view2add.lists.Status = <?= $Page->Status->toClientList($Page) ?>;
    fsubmission_view2add.lists.Tags = <?= $Page->Tags->toClientList($Page) ?>;
    loadjs.done("fsubmission_view2add");
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
<form name="fsubmission_view2add" id="fsubmission_view2add" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="submission_view2">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->_Title->Visible) { // Title ?>
    <div id="r__Title"<?= $Page->_Title->rowAttributes() ?>>
        <label id="elh_submission_view2__Title" for="x__Title" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_Title->caption() ?><?= $Page->_Title->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->_Title->cellAttributes() ?>>
<span id="el_submission_view2__Title">
<input type="<?= $Page->_Title->getInputTextType() ?>" name="x__Title" id="x__Title" data-table="submission_view2" data-field="x__Title" value="<?= $Page->_Title->EditValue ?>" size="60" maxlength="255" placeholder="<?= HtmlEncode($Page->_Title->getPlaceHolder()) ?>"<?= $Page->_Title->editAttributes() ?> aria-describedby="x__Title_help">
<?= $Page->_Title->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_Title->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Category_ID->Visible) { // Category_ID ?>
    <div id="r_Category_ID"<?= $Page->Category_ID->rowAttributes() ?>>
        <label id="elh_submission_view2_Category_ID" for="x_Category_ID" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Category_ID->caption() ?><?= $Page->Category_ID->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Category_ID->cellAttributes() ?>>
<span id="el_submission_view2_Category_ID">
<div class="input-group flex-nowrap">
    <select
        id="x_Category_ID"
        name="x_Category_ID"
        class="form-select ew-select<?= $Page->Category_ID->isInvalidClass() ?>"
        data-select2-id="fsubmission_view2add_x_Category_ID"
        data-table="submission_view2"
        data-field="x_Category_ID"
        data-value-separator="<?= $Page->Category_ID->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->Category_ID->getPlaceHolder()) ?>"
        <?= $Page->Category_ID->editAttributes() ?>>
        <?= $Page->Category_ID->selectOptionListHtml("x_Category_ID") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "ref_category") && !$Page->Category_ID->ReadOnly) { ?>
    <button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x_Category_ID" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Page->Category_ID->caption() ?>" data-title="<?= $Page->Category_ID->caption() ?>" data-ew-action="add-option" data-el="x_Category_ID" data-url="<?= GetUrl("refcategoryaddopt") ?>"><i class="fas fa-plus ew-icon"></i></button>
    <?php } ?>
</div>
<?= $Page->Category_ID->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Category_ID->getErrorMessage() ?></div>
<?= $Page->Category_ID->Lookup->getParamTag($Page, "p_x_Category_ID") ?>
<script>
loadjs.ready("fsubmission_view2add", function() {
    var options = { name: "x_Category_ID", selectId: "fsubmission_view2add_x_Category_ID" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fsubmission_view2add.lists.Category_ID.lookupOptions.length) {
        options.data = { id: "x_Category_ID", form: "fsubmission_view2add" };
    } else {
        options.ajax = { id: "x_Category_ID", form: "fsubmission_view2add", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.submission_view2.fields.Category_ID.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Status->Visible) { // Status ?>
    <div id="r_Status"<?= $Page->Status->rowAttributes() ?>>
        <label id="elh_submission_view2_Status" for="x_Status" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Status->caption() ?><?= $Page->Status->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Status->cellAttributes() ?>>
<span id="el_submission_view2_Status">
    <select
        id="x_Status"
        name="x_Status"
        class="form-select ew-select<?= $Page->Status->isInvalidClass() ?>"
        data-select2-id="fsubmission_view2add_x_Status"
        data-table="submission_view2"
        data-field="x_Status"
        data-value-separator="<?= $Page->Status->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->Status->getPlaceHolder()) ?>"
        <?= $Page->Status->editAttributes() ?>>
        <?= $Page->Status->selectOptionListHtml("x_Status") ?>
    </select>
    <?= $Page->Status->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->Status->getErrorMessage() ?></div>
<script>
loadjs.ready("fsubmission_view2add", function() {
    var options = { name: "x_Status", selectId: "fsubmission_view2add_x_Status" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fsubmission_view2add.lists.Status.lookupOptions.length) {
        options.data = { id: "x_Status", form: "fsubmission_view2add" };
    } else {
        options.ajax = { id: "x_Status", form: "fsubmission_view2add", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.submission_view2.fields.Status.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->_Abstract->Visible) { // Abstract ?>
    <div id="r__Abstract"<?= $Page->_Abstract->rowAttributes() ?>>
        <label id="elh_submission_view2__Abstract" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_Abstract->caption() ?><?= $Page->_Abstract->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->_Abstract->cellAttributes() ?>>
<span id="el_submission_view2__Abstract">
<?php $Page->_Abstract->EditAttrs->appendClass("editor"); ?>
<textarea data-table="submission_view2" data-field="x__Abstract" name="x__Abstract" id="x__Abstract" cols="60" rows="4" placeholder="<?= HtmlEncode($Page->_Abstract->getPlaceHolder()) ?>"<?= $Page->_Abstract->editAttributes() ?> aria-describedby="x__Abstract_help"><?= $Page->_Abstract->EditValue ?></textarea>
<?= $Page->_Abstract->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_Abstract->getErrorMessage() ?></div>
<script>
loadjs.ready(["fsubmission_view2add", "editor"], function() {
    ew.createEditor("fsubmission_view2add", "x__Abstract", 60, 4, <?= $Page->_Abstract->ReadOnly || false ? "true" : "false" ?>);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Tags->Visible) { // Tags ?>
    <div id="r_Tags"<?= $Page->Tags->rowAttributes() ?>>
        <label id="elh_submission_view2_Tags" for="x_Tags" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Tags->caption() ?><?= $Page->Tags->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Tags->cellAttributes() ?>>
<span id="el_submission_view2_Tags">
    <select
        id="x_Tags[]"
        name="x_Tags[]"
        class="form-select ew-select<?= $Page->Tags->isInvalidClass() ?>"
        data-select2-id="fsubmission_view2add_x_Tags[]"
        data-table="submission_view2"
        data-field="x_Tags"
        multiple
        size="1"
        data-value-separator="<?= $Page->Tags->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->Tags->getPlaceHolder()) ?>"
        <?= $Page->Tags->editAttributes() ?>>
        <?= $Page->Tags->selectOptionListHtml("x_Tags[]") ?>
    </select>
    <?= $Page->Tags->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->Tags->getErrorMessage() ?></div>
<?= $Page->Tags->Lookup->getParamTag($Page, "p_x_Tags") ?>
<script>
loadjs.ready("fsubmission_view2add", function() {
    var options = { name: "x_Tags[]", selectId: "fsubmission_view2add_x_Tags[]" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.multiple = true;
    options.closeOnSelect = false;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fsubmission_view2add.lists.Tags.lookupOptions.length) {
        options.data = { id: "x_Tags[]", form: "fsubmission_view2add" };
    } else {
        options.ajax = { id: "x_Tags[]", form: "fsubmission_view2add", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.submission_view2.fields.Tags.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Uploads->Visible) { // Uploads ?>
    <div id="r_Uploads"<?= $Page->Uploads->rowAttributes() ?>>
        <label id="elh_submission_view2_Uploads" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Uploads->caption() ?><?= $Page->Uploads->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Uploads->cellAttributes() ?>>
<span id="el_submission_view2_Uploads">
<div id="fd_x_Uploads" class="fileinput-button ew-file-drop-zone">
    <input type="file" class="form-control ew-file-input" title="<?= $Page->Uploads->title() ?>" data-table="submission_view2" data-field="x_Uploads" name="x_Uploads" id="x_Uploads" lang="<?= CurrentLanguageID() ?>" multiple<?= $Page->Uploads->editAttributes() ?> aria-describedby="x_Uploads_help"<?= ($Page->Uploads->ReadOnly || $Page->Uploads->Disabled) ? " disabled" : "" ?>>
    <div class="text-muted ew-file-text"><?= $Language->phrase("ChooseFiles") ?></div>
</div>
<?= $Page->Uploads->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Uploads->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_Uploads" id= "fn_x_Uploads" value="<?= $Page->Uploads->Upload->FileName ?>">
<input type="hidden" name="fa_x_Uploads" id= "fa_x_Uploads" value="0">
<input type="hidden" name="fs_x_Uploads" id= "fs_x_Uploads" value="65535">
<input type="hidden" name="fx_x_Uploads" id= "fx_x_Uploads" value="<?= $Page->Uploads->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_Uploads" id= "fm_x_Uploads" value="<?= $Page->Uploads->UploadMaxFileSize ?>">
<input type="hidden" name="fc_x_Uploads" id= "fc_x_Uploads" value="<?= $Page->Uploads->UploadMaxFileCount ?>">
<table id="ft_x_Uploads" class="table table-sm float-start ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Cover->Visible) { // Cover ?>
    <div id="r_Cover"<?= $Page->Cover->rowAttributes() ?>>
        <label id="elh_submission_view2_Cover" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Cover->caption() ?><?= $Page->Cover->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Cover->cellAttributes() ?>>
<span id="el_submission_view2_Cover">
<div id="fd_x_Cover" class="fileinput-button ew-file-drop-zone">
    <input type="file" class="form-control ew-file-input" title="<?= $Page->Cover->title() ?>" data-table="submission_view2" data-field="x_Cover" name="x_Cover" id="x_Cover" lang="<?= CurrentLanguageID() ?>"<?= $Page->Cover->editAttributes() ?> aria-describedby="x_Cover_help"<?= ($Page->Cover->ReadOnly || $Page->Cover->Disabled) ? " disabled" : "" ?>>
    <div class="text-muted ew-file-text"><?= $Language->phrase("ChooseFile") ?></div>
</div>
<?= $Page->Cover->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Cover->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_Cover" id= "fn_x_Cover" value="<?= $Page->Cover->Upload->FileName ?>">
<input type="hidden" name="fa_x_Cover" id= "fa_x_Cover" value="0">
<input type="hidden" name="fs_x_Cover" id= "fs_x_Cover" value="255">
<input type="hidden" name="fx_x_Cover" id= "fx_x_Cover" value="<?= $Page->Cover->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_Cover" id= "fm_x_Cover" value="<?= $Page->Cover->UploadMaxFileSize ?>">
<table id="ft_x_Cover" class="table table-sm float-start ew-upload-table"><tbody class="files"></tbody></table>
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
    ew.addEventHandlers("submission_view2");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
