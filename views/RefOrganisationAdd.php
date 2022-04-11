<?php

namespace PHPMaker2022\civichub2;

// Page object
$RefOrganisationAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { ref_organisation: currentTable } });
var currentForm, currentPageID;
var fref_organisationadd;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fref_organisationadd = new ew.Form("fref_organisationadd", "add");
    currentPageID = ew.PAGE_ID = "add";
    currentForm = fref_organisationadd;

    // Add fields
    var fields = currentTable.fields;
    fref_organisationadd.addFields([
        ["Organisation", [fields.Organisation.visible && fields.Organisation.required ? ew.Validators.required(fields.Organisation.caption) : null], fields.Organisation.isInvalid],
        ["Country", [fields.Country.visible && fields.Country.required ? ew.Validators.required(fields.Country.caption) : null], fields.Country.isInvalid],
        ["Organisation_Type", [fields.Organisation_Type.visible && fields.Organisation_Type.required ? ew.Validators.required(fields.Organisation_Type.caption) : null], fields.Organisation_Type.isInvalid]
    ]);

    // Form_CustomValidate
    fref_organisationadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fref_organisationadd.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    fref_organisationadd.lists.Country = <?= $Page->Country->toClientList($Page) ?>;
    fref_organisationadd.lists.Organisation_Type = <?= $Page->Organisation_Type->toClientList($Page) ?>;
    loadjs.done("fref_organisationadd");
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
<form name="fref_organisationadd" id="fref_organisationadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="ref_organisation">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php if ($Page->getCurrentMasterTable() == "ref_country") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="ref_country">
<input type="hidden" name="fk_Country" value="<?= HtmlEncode($Page->Country->getSessionValue()) ?>">
<?php } ?>
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->Organisation->Visible) { // Organisation ?>
    <div id="r_Organisation"<?= $Page->Organisation->rowAttributes() ?>>
        <label id="elh_ref_organisation_Organisation" for="x_Organisation" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Organisation->caption() ?><?= $Page->Organisation->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Organisation->cellAttributes() ?>>
<span id="el_ref_organisation_Organisation">
<input type="<?= $Page->Organisation->getInputTextType() ?>" name="x_Organisation" id="x_Organisation" data-table="ref_organisation" data-field="x_Organisation" value="<?= $Page->Organisation->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->Organisation->getPlaceHolder()) ?>"<?= $Page->Organisation->editAttributes() ?> aria-describedby="x_Organisation_help">
<?= $Page->Organisation->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Organisation->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Country->Visible) { // Country ?>
    <div id="r_Country"<?= $Page->Country->rowAttributes() ?>>
        <label id="elh_ref_organisation_Country" for="x_Country" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Country->caption() ?><?= $Page->Country->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Country->cellAttributes() ?>>
<?php if ($Page->Country->getSessionValue() != "") { ?>
<span id="el_ref_organisation_Country">
<span<?= $Page->Country->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Page->Country->getDisplayValue($Page->Country->ViewValue) ?></span></span>
</span>
<input type="hidden" id="x_Country" name="x_Country" value="<?= HtmlEncode($Page->Country->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el_ref_organisation_Country">
<div class="input-group flex-nowrap">
    <select
        id="x_Country"
        name="x_Country"
        class="form-select ew-select<?= $Page->Country->isInvalidClass() ?>"
        data-select2-id="fref_organisationadd_x_Country"
        data-table="ref_organisation"
        data-field="x_Country"
        data-value-separator="<?= $Page->Country->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->Country->getPlaceHolder()) ?>"
        <?= $Page->Country->editAttributes() ?>>
        <?= $Page->Country->selectOptionListHtml("x_Country") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "ref_country") && !$Page->Country->ReadOnly) { ?>
    <button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x_Country" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Page->Country->caption() ?>" data-title="<?= $Page->Country->caption() ?>" data-ew-action="add-option" data-el="x_Country" data-url="<?= GetUrl("refcountryaddopt") ?>"><i class="fas fa-plus ew-icon"></i></button>
    <?php } ?>
</div>
<?= $Page->Country->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Country->getErrorMessage() ?></div>
<?= $Page->Country->Lookup->getParamTag($Page, "p_x_Country") ?>
<script>
loadjs.ready("fref_organisationadd", function() {
    var options = { name: "x_Country", selectId: "fref_organisationadd_x_Country" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fref_organisationadd.lists.Country.lookupOptions.length) {
        options.data = { id: "x_Country", form: "fref_organisationadd" };
    } else {
        options.ajax = { id: "x_Country", form: "fref_organisationadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.ref_organisation.fields.Country.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Organisation_Type->Visible) { // Organisation_Type ?>
    <div id="r_Organisation_Type"<?= $Page->Organisation_Type->rowAttributes() ?>>
        <label id="elh_ref_organisation_Organisation_Type" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Organisation_Type->caption() ?><?= $Page->Organisation_Type->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Organisation_Type->cellAttributes() ?>>
<span id="el_ref_organisation_Organisation_Type">
<template id="tp_x_Organisation_Type">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="ref_organisation" data-field="x_Organisation_Type" name="x_Organisation_Type" id="x_Organisation_Type"<?= $Page->Organisation_Type->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_Organisation_Type" class="ew-item-list"></div>
<selection-list hidden
    id="x_Organisation_Type"
    name="x_Organisation_Type"
    value="<?= HtmlEncode($Page->Organisation_Type->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_Organisation_Type"
    data-bs-target="dsl_x_Organisation_Type"
    data-repeatcolumn="5"
    class="form-control<?= $Page->Organisation_Type->isInvalidClass() ?>"
    data-table="ref_organisation"
    data-field="x_Organisation_Type"
    data-value-separator="<?= $Page->Organisation_Type->displayValueSeparatorAttribute() ?>"
    <?= $Page->Organisation_Type->editAttributes() ?>></selection-list>
<?= $Page->Organisation_Type->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Organisation_Type->getErrorMessage() ?></div>
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
    ew.addEventHandlers("ref_organisation");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
