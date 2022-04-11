<?php

namespace PHPMaker2022\civichub2;

// Page object
$SubmissionVoteAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { submission_vote: currentTable } });
var currentForm, currentPageID;
var fsubmission_voteadd;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fsubmission_voteadd = new ew.Form("fsubmission_voteadd", "add");
    currentPageID = ew.PAGE_ID = "add";
    currentForm = fsubmission_voteadd;

    // Add fields
    var fields = currentTable.fields;
    fsubmission_voteadd.addFields([
        ["Submission_ID", [fields.Submission_ID.visible && fields.Submission_ID.required ? ew.Validators.required(fields.Submission_ID.caption) : null], fields.Submission_ID.isInvalid],
        ["Vote", [fields.Vote.visible && fields.Vote.required ? ew.Validators.required(fields.Vote.caption) : null], fields.Vote.isInvalid]
    ]);

    // Form_CustomValidate
    fsubmission_voteadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fsubmission_voteadd.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    fsubmission_voteadd.lists.Submission_ID = <?= $Page->Submission_ID->toClientList($Page) ?>;
    fsubmission_voteadd.lists.Vote = <?= $Page->Vote->toClientList($Page) ?>;
    loadjs.done("fsubmission_voteadd");
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
<form name="fsubmission_voteadd" id="fsubmission_voteadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="submission_vote">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php if ($Page->getCurrentMasterTable() == "Voting") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="Voting">
<input type="hidden" name="fk_Submission_ID" value="<?= HtmlEncode($Page->Submission_ID->getSessionValue()) ?>">
<?php } ?>
<?php if ($Page->getCurrentMasterTable() == "vote_tally") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="vote_tally">
<input type="hidden" name="fk_Submission_ID" value="<?= HtmlEncode($Page->Submission_ID->getSessionValue()) ?>">
<?php } ?>
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->Submission_ID->Visible) { // Submission_ID ?>
    <div id="r_Submission_ID"<?= $Page->Submission_ID->rowAttributes() ?>>
        <label id="elh_submission_vote_Submission_ID" for="x_Submission_ID" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Submission_ID->caption() ?><?= $Page->Submission_ID->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Submission_ID->cellAttributes() ?>>
<?php if ($Page->Submission_ID->getSessionValue() != "") { ?>
<span id="el_submission_vote_Submission_ID">
<span<?= $Page->Submission_ID->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Page->Submission_ID->getDisplayValue($Page->Submission_ID->ViewValue) ?></span></span>
</span>
<input type="hidden" id="x_Submission_ID" name="x_Submission_ID" value="<?= HtmlEncode(FormatNumber($Page->Submission_ID->CurrentValue, $Page->Submission_ID->formatPattern())) ?>" data-hidden="1">
<?php } else { ?>
<span id="el_submission_vote_Submission_ID">
    <select
        id="x_Submission_ID"
        name="x_Submission_ID"
        class="form-select ew-select<?= $Page->Submission_ID->isInvalidClass() ?>"
        data-select2-id="fsubmission_voteadd_x_Submission_ID"
        data-table="submission_vote"
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
loadjs.ready("fsubmission_voteadd", function() {
    var options = { name: "x_Submission_ID", selectId: "fsubmission_voteadd_x_Submission_ID" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fsubmission_voteadd.lists.Submission_ID.lookupOptions.length) {
        options.data = { id: "x_Submission_ID", form: "fsubmission_voteadd" };
    } else {
        options.ajax = { id: "x_Submission_ID", form: "fsubmission_voteadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.submission_vote.fields.Submission_ID.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Vote->Visible) { // Vote ?>
    <div id="r_Vote"<?= $Page->Vote->rowAttributes() ?>>
        <label id="elh_submission_vote_Vote" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Vote->caption() ?><?= $Page->Vote->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Vote->cellAttributes() ?>>
<span id="el_submission_vote_Vote">
<template id="tp_x_Vote">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="submission_vote" data-field="x_Vote" name="x_Vote" id="x_Vote"<?= $Page->Vote->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_Vote" class="ew-item-list"></div>
<selection-list hidden
    id="x_Vote"
    name="x_Vote"
    value="<?= HtmlEncode($Page->Vote->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_Vote"
    data-bs-target="dsl_x_Vote"
    data-repeatcolumn="5"
    class="form-control<?= $Page->Vote->isInvalidClass() ?>"
    data-table="submission_vote"
    data-field="x_Vote"
    data-value-separator="<?= $Page->Vote->displayValueSeparatorAttribute() ?>"
    <?= $Page->Vote->editAttributes() ?>></selection-list>
<?= $Page->Vote->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Vote->getErrorMessage() ?></div>
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
    ew.addEventHandlers("submission_vote");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
