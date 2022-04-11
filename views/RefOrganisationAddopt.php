<?php

namespace PHPMaker2022\civichub2;

// Page object
$RefOrganisationAddopt = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { ref_organisation: currentTable } });
var currentForm, currentPageID;
var fref_organisationaddopt;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fref_organisationaddopt = new ew.Form("fref_organisationaddopt", "addopt");
    currentPageID = ew.PAGE_ID = "addopt";
    currentForm = fref_organisationaddopt;

    // Add fields
    var fields = currentTable.fields;
    fref_organisationaddopt.addFields([
        ["Organisation", [fields.Organisation.visible && fields.Organisation.required ? ew.Validators.required(fields.Organisation.caption) : null], fields.Organisation.isInvalid],
        ["Country", [fields.Country.visible && fields.Country.required ? ew.Validators.required(fields.Country.caption) : null], fields.Country.isInvalid],
        ["Organisation_Type", [fields.Organisation_Type.visible && fields.Organisation_Type.required ? ew.Validators.required(fields.Organisation_Type.caption) : null], fields.Organisation_Type.isInvalid]
    ]);

    // Form_CustomValidate
    fref_organisationaddopt.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fref_organisationaddopt.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    fref_organisationaddopt.lists.Country = <?= $Page->Country->toClientList($Page) ?>;
    fref_organisationaddopt.lists.Organisation_Type = <?= $Page->Organisation_Type->toClientList($Page) ?>;
    loadjs.done("fref_organisationaddopt");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php $Page->showPageHeader(); ?>
<form name="fref_organisationaddopt" id="fref_organisationaddopt" class="ew-form" action="<?= HtmlEncode(GetUrl(Config("API_URL"))) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="<?= Config("API_ACTION_NAME") ?>" id="<?= Config("API_ACTION_NAME") ?>" value="<?= Config("API_ADD_ACTION") ?>">
<input type="hidden" name="<?= Config("API_OBJECT_NAME") ?>" id="<?= Config("API_OBJECT_NAME") ?>" value="ref_organisation">
<input type="hidden" name="addopt" id="addopt" value="1">
<?php if ($Page->Organisation->Visible) { // Organisation ?>
    <div<?= $Page->Organisation->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_Organisation"><?= $Page->Organisation->caption() ?><?= $Page->Organisation->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->Organisation->cellAttributes() ?>>
<input type="<?= $Page->Organisation->getInputTextType() ?>" name="x_Organisation" id="x_Organisation" data-table="ref_organisation" data-field="x_Organisation" value="<?= $Page->Organisation->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->Organisation->getPlaceHolder()) ?>"<?= $Page->Organisation->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Organisation->getErrorMessage() ?></div>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Country->Visible) { // Country ?>
    <div<?= $Page->Country->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_Country"><?= $Page->Country->caption() ?><?= $Page->Country->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->Country->cellAttributes() ?>>
<div class="input-group flex-nowrap">
    <select
        id="x_Country"
        name="x_Country"
        class="form-select ew-select<?= $Page->Country->isInvalidClass() ?>"
        data-select2-id="fref_organisationaddopt_x_Country"
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
<div class="invalid-feedback"><?= $Page->Country->getErrorMessage() ?></div>
<?= $Page->Country->Lookup->getParamTag($Page, "p_x_Country") ?>
<script>
loadjs.ready("fref_organisationaddopt", function() {
    var options = { name: "x_Country", selectId: "fref_organisationaddopt_x_Country" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fref_organisationaddopt.lists.Country.lookupOptions.length) {
        options.data = { id: "x_Country", form: "fref_organisationaddopt" };
    } else {
        options.ajax = { id: "x_Country", form: "fref_organisationaddopt", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.ref_organisation.fields.Country.selectOptions);
    ew.createSelect(options);
});
</script>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Organisation_Type->Visible) { // Organisation_Type ?>
    <div<?= $Page->Organisation_Type->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label"><?= $Page->Organisation_Type->caption() ?><?= $Page->Organisation_Type->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->Organisation_Type->cellAttributes() ?>>
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
<div class="invalid-feedback"><?= $Page->Organisation_Type->getErrorMessage() ?></div>
</div></div>
    </div>
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
