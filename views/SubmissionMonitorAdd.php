<?php

namespace PHPMaker2022\civichub2;

// Page object
$SubmissionMonitorAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { submission_monitor: currentTable } });
var currentForm, currentPageID;
var fsubmission_monitoradd;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fsubmission_monitoradd = new ew.Form("fsubmission_monitoradd", "add");
    currentPageID = ew.PAGE_ID = "add";
    currentForm = fsubmission_monitoradd;

    // Add fields
    var fields = currentTable.fields;
    fsubmission_monitoradd.addFields([
        ["Submission_ID", [fields.Submission_ID.visible && fields.Submission_ID.required ? ew.Validators.required(fields.Submission_ID.caption) : null], fields.Submission_ID.isInvalid],
        ["Status", [fields.Status.visible && fields.Status.required ? ew.Validators.required(fields.Status.caption) : null], fields.Status.isInvalid],
        ["Taskings", [fields.Taskings.visible && fields.Taskings.required ? ew.Validators.required(fields.Taskings.caption) : null], fields.Taskings.isInvalid],
        ["Organisations", [fields.Organisations.visible && fields.Organisations.required ? ew.Validators.required(fields.Organisations.caption) : null], fields.Organisations.isInvalid],
        ["Start_Date", [fields.Start_Date.visible && fields.Start_Date.required ? ew.Validators.required(fields.Start_Date.caption) : null, ew.Validators.datetime(fields.Start_Date.clientFormatPattern)], fields.Start_Date.isInvalid],
        ["Finish_Date", [fields.Finish_Date.visible && fields.Finish_Date.required ? ew.Validators.required(fields.Finish_Date.caption) : null, ew.Validators.datetime(fields.Finish_Date.clientFormatPattern)], fields.Finish_Date.isInvalid],
        ["Uploads", [fields.Uploads.visible && fields.Uploads.required ? ew.Validators.fileRequired(fields.Uploads.caption) : null], fields.Uploads.isInvalid]
    ]);

    // Form_CustomValidate
    fsubmission_monitoradd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fsubmission_monitoradd.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    fsubmission_monitoradd.lists.Submission_ID = <?= $Page->Submission_ID->toClientList($Page) ?>;
    fsubmission_monitoradd.lists.Status = <?= $Page->Status->toClientList($Page) ?>;
    fsubmission_monitoradd.lists.Organisations = <?= $Page->Organisations->toClientList($Page) ?>;
    loadjs.done("fsubmission_monitoradd");
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
<form name="fsubmission_monitoradd" id="fsubmission_monitoradd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="submission_monitor">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php if ($Page->getCurrentMasterTable() == "submission") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="submission">
<input type="hidden" name="fk_Submission_ID" value="<?= HtmlEncode($Page->Submission_ID->getSessionValue()) ?>">
<?php } ?>
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->Submission_ID->Visible) { // Submission_ID ?>
    <div id="r_Submission_ID"<?= $Page->Submission_ID->rowAttributes() ?>>
        <label id="elh_submission_monitor_Submission_ID" for="x_Submission_ID" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Submission_ID->caption() ?><?= $Page->Submission_ID->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Submission_ID->cellAttributes() ?>>
<?php if ($Page->Submission_ID->getSessionValue() != "") { ?>
<span id="el_submission_monitor_Submission_ID">
<span<?= $Page->Submission_ID->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Page->Submission_ID->getDisplayValue($Page->Submission_ID->ViewValue) ?></span></span>
</span>
<input type="hidden" id="x_Submission_ID" name="x_Submission_ID" value="<?= HtmlEncode(FormatNumber($Page->Submission_ID->CurrentValue, $Page->Submission_ID->formatPattern())) ?>" data-hidden="1">
<?php } else { ?>
<span id="el_submission_monitor_Submission_ID">
    <select
        id="x_Submission_ID"
        name="x_Submission_ID"
        class="form-select ew-select<?= $Page->Submission_ID->isInvalidClass() ?>"
        data-select2-id="fsubmission_monitoradd_x_Submission_ID"
        data-table="submission_monitor"
        data-field="x_Submission_ID"
        data-value-separator="<?= $Page->Submission_ID->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->Submission_ID->getPlaceHolder()) ?>"
        <?= $Page->Submission_ID->editAttributes() ?>>
        <?= $Page->Submission_ID->selectOptionListHtml("x_Submission_ID") ?>
    </select>
    <?= $Page->Submission_ID->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->Submission_ID->getErrorMessage() ?></div>
<?= $Page->Submission_ID->Lookup->getParamTag($Page, "p_x_Submission_ID") ?>
<script>
loadjs.ready("fsubmission_monitoradd", function() {
    var options = { name: "x_Submission_ID", selectId: "fsubmission_monitoradd_x_Submission_ID" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fsubmission_monitoradd.lists.Submission_ID.lookupOptions.length) {
        options.data = { id: "x_Submission_ID", form: "fsubmission_monitoradd" };
    } else {
        options.ajax = { id: "x_Submission_ID", form: "fsubmission_monitoradd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.submission_monitor.fields.Submission_ID.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Status->Visible) { // Status ?>
    <div id="r_Status"<?= $Page->Status->rowAttributes() ?>>
        <label id="elh_submission_monitor_Status" for="x_Status" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Status->caption() ?><?= $Page->Status->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Status->cellAttributes() ?>>
<span id="el_submission_monitor_Status">
    <select
        id="x_Status"
        name="x_Status"
        class="form-select ew-select<?= $Page->Status->isInvalidClass() ?>"
        data-select2-id="fsubmission_monitoradd_x_Status"
        data-table="submission_monitor"
        data-field="x_Status"
        data-value-separator="<?= $Page->Status->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->Status->getPlaceHolder()) ?>"
        <?= $Page->Status->editAttributes() ?>>
        <?= $Page->Status->selectOptionListHtml("x_Status") ?>
    </select>
    <?= $Page->Status->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->Status->getErrorMessage() ?></div>
<script>
loadjs.ready("fsubmission_monitoradd", function() {
    var options = { name: "x_Status", selectId: "fsubmission_monitoradd_x_Status" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fsubmission_monitoradd.lists.Status.lookupOptions.length) {
        options.data = { id: "x_Status", form: "fsubmission_monitoradd" };
    } else {
        options.ajax = { id: "x_Status", form: "fsubmission_monitoradd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.submission_monitor.fields.Status.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Taskings->Visible) { // Taskings ?>
    <div id="r_Taskings"<?= $Page->Taskings->rowAttributes() ?>>
        <label id="elh_submission_monitor_Taskings" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Taskings->caption() ?><?= $Page->Taskings->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Taskings->cellAttributes() ?>>
<span id="el_submission_monitor_Taskings">
<?php $Page->Taskings->EditAttrs->appendClass("editor"); ?>
<textarea data-table="submission_monitor" data-field="x_Taskings" name="x_Taskings" id="x_Taskings" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->Taskings->getPlaceHolder()) ?>"<?= $Page->Taskings->editAttributes() ?> aria-describedby="x_Taskings_help"><?= $Page->Taskings->EditValue ?></textarea>
<?= $Page->Taskings->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Taskings->getErrorMessage() ?></div>
<script>
loadjs.ready(["fsubmission_monitoradd", "editor"], function() {
    ew.createEditor("fsubmission_monitoradd", "x_Taskings", 0, 0, <?= $Page->Taskings->ReadOnly || false ? "true" : "false" ?>);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Organisations->Visible) { // Organisations ?>
    <div id="r_Organisations"<?= $Page->Organisations->rowAttributes() ?>>
        <label id="elh_submission_monitor_Organisations" for="x_Organisations" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Organisations->caption() ?><?= $Page->Organisations->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Organisations->cellAttributes() ?>>
<span id="el_submission_monitor_Organisations">
<div class="input-group flex-nowrap">
    <select
        id="x_Organisations[]"
        name="x_Organisations[]"
        class="form-select ew-select<?= $Page->Organisations->isInvalidClass() ?>"
        data-select2-id="fsubmission_monitoradd_x_Organisations[]"
        data-table="submission_monitor"
        data-field="x_Organisations"
        multiple
        size="1"
        data-value-separator="<?= $Page->Organisations->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->Organisations->getPlaceHolder()) ?>"
        <?= $Page->Organisations->editAttributes() ?>>
        <?= $Page->Organisations->selectOptionListHtml("x_Organisations[]") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "ref_organisation") && !$Page->Organisations->ReadOnly) { ?>
    <button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x_Organisations" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Page->Organisations->caption() ?>" data-title="<?= $Page->Organisations->caption() ?>" data-ew-action="add-option" data-el="x_Organisations[]" data-url="<?= GetUrl("reforganisationaddopt") ?>"><i class="fas fa-plus ew-icon"></i></button>
    <?php } ?>
</div>
<?= $Page->Organisations->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Organisations->getErrorMessage() ?></div>
<?= $Page->Organisations->Lookup->getParamTag($Page, "p_x_Organisations") ?>
<script>
loadjs.ready("fsubmission_monitoradd", function() {
    var options = { name: "x_Organisations[]", selectId: "fsubmission_monitoradd_x_Organisations[]" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.multiple = true;
    options.closeOnSelect = false;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fsubmission_monitoradd.lists.Organisations.lookupOptions.length) {
        options.data = { id: "x_Organisations[]", form: "fsubmission_monitoradd" };
    } else {
        options.ajax = { id: "x_Organisations[]", form: "fsubmission_monitoradd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.submission_monitor.fields.Organisations.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Start_Date->Visible) { // Start_Date ?>
    <div id="r_Start_Date"<?= $Page->Start_Date->rowAttributes() ?>>
        <label id="elh_submission_monitor_Start_Date" for="x_Start_Date" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Start_Date->caption() ?><?= $Page->Start_Date->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Start_Date->cellAttributes() ?>>
<span id="el_submission_monitor_Start_Date">
<input type="<?= $Page->Start_Date->getInputTextType() ?>" name="x_Start_Date" id="x_Start_Date" data-table="submission_monitor" data-field="x_Start_Date" value="<?= $Page->Start_Date->EditValue ?>" placeholder="<?= HtmlEncode($Page->Start_Date->getPlaceHolder()) ?>"<?= $Page->Start_Date->editAttributes() ?> aria-describedby="x_Start_Date_help">
<?= $Page->Start_Date->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Start_Date->getErrorMessage() ?></div>
<?php if (!$Page->Start_Date->ReadOnly && !$Page->Start_Date->Disabled && !isset($Page->Start_Date->EditAttrs["readonly"]) && !isset($Page->Start_Date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fsubmission_monitoradd", "datetimepicker"], function () {
    let format = "<?= DateFormat(0) ?>",
        options = {
            localization: {
                locale: ew.LANGUAGE_ID
            },
            display: {
                components: {
                    hours: !!format.match(/h/i),
                    minutes: !!format.match(/m/),
                    seconds: !!format.match(/s/i)
                },
                icons: {
                    previous: ew.IS_RTL ? "fas fa-chevron-right" : "fas fa-chevron-left",
                    next: ew.IS_RTL ? "fas fa-chevron-left" : "fas fa-chevron-right"
                }
            },
            meta: {
                format,
                numberingSystem: ew.getNumberingSystem()
            }
        };
    ew.createDateTimePicker("fsubmission_monitoradd", "x_Start_Date", jQuery.extend(true, {"useCurrent":false}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Finish_Date->Visible) { // Finish_Date ?>
    <div id="r_Finish_Date"<?= $Page->Finish_Date->rowAttributes() ?>>
        <label id="elh_submission_monitor_Finish_Date" for="x_Finish_Date" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Finish_Date->caption() ?><?= $Page->Finish_Date->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Finish_Date->cellAttributes() ?>>
<span id="el_submission_monitor_Finish_Date">
<input type="<?= $Page->Finish_Date->getInputTextType() ?>" name="x_Finish_Date" id="x_Finish_Date" data-table="submission_monitor" data-field="x_Finish_Date" value="<?= $Page->Finish_Date->EditValue ?>" placeholder="<?= HtmlEncode($Page->Finish_Date->getPlaceHolder()) ?>"<?= $Page->Finish_Date->editAttributes() ?> aria-describedby="x_Finish_Date_help">
<?= $Page->Finish_Date->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Finish_Date->getErrorMessage() ?></div>
<?php if (!$Page->Finish_Date->ReadOnly && !$Page->Finish_Date->Disabled && !isset($Page->Finish_Date->EditAttrs["readonly"]) && !isset($Page->Finish_Date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fsubmission_monitoradd", "datetimepicker"], function () {
    let format = "<?= DateFormat(0) ?>",
        options = {
            localization: {
                locale: ew.LANGUAGE_ID
            },
            display: {
                components: {
                    hours: !!format.match(/h/i),
                    minutes: !!format.match(/m/),
                    seconds: !!format.match(/s/i)
                },
                icons: {
                    previous: ew.IS_RTL ? "fas fa-chevron-right" : "fas fa-chevron-left",
                    next: ew.IS_RTL ? "fas fa-chevron-left" : "fas fa-chevron-right"
                }
            },
            meta: {
                format,
                numberingSystem: ew.getNumberingSystem()
            }
        };
    ew.createDateTimePicker("fsubmission_monitoradd", "x_Finish_Date", jQuery.extend(true, {"useCurrent":false}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Uploads->Visible) { // Uploads ?>
    <div id="r_Uploads"<?= $Page->Uploads->rowAttributes() ?>>
        <label id="elh_submission_monitor_Uploads" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Uploads->caption() ?><?= $Page->Uploads->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Uploads->cellAttributes() ?>>
<span id="el_submission_monitor_Uploads">
<div id="fd_x_Uploads" class="fileinput-button ew-file-drop-zone">
    <input type="file" class="form-control ew-file-input" title="<?= $Page->Uploads->title() ?>" data-table="submission_monitor" data-field="x_Uploads" name="x_Uploads" id="x_Uploads" lang="<?= CurrentLanguageID() ?>" multiple<?= $Page->Uploads->editAttributes() ?> aria-describedby="x_Uploads_help"<?= ($Page->Uploads->ReadOnly || $Page->Uploads->Disabled) ? " disabled" : "" ?>>
    <div class="text-muted ew-file-text"><?= $Language->phrase("ChooseFiles") ?></div>
</div>
<?= $Page->Uploads->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Uploads->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_Uploads" id= "fn_x_Uploads" value="<?= $Page->Uploads->Upload->FileName ?>">
<input type="hidden" name="fa_x_Uploads" id= "fa_x_Uploads" value="0">
<input type="hidden" name="fs_x_Uploads" id= "fs_x_Uploads" value="255">
<input type="hidden" name="fx_x_Uploads" id= "fx_x_Uploads" value="<?= $Page->Uploads->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_Uploads" id= "fm_x_Uploads" value="<?= $Page->Uploads->UploadMaxFileSize ?>">
<input type="hidden" name="fc_x_Uploads" id= "fc_x_Uploads" value="<?= $Page->Uploads->UploadMaxFileCount ?>">
<table id="ft_x_Uploads" class="table table-sm float-start ew-upload-table"><tbody class="files"></tbody></table>
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
    ew.addEventHandlers("submission_monitor");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
