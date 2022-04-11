<?php

namespace PHPMaker2022\civichub2;

// Page object
$SubmissionCommentsDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { submission_comments: currentTable } });
var currentForm, currentPageID;
var fsubmission_commentsdelete;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fsubmission_commentsdelete = new ew.Form("fsubmission_commentsdelete", "delete");
    currentPageID = ew.PAGE_ID = "delete";
    currentForm = fsubmission_commentsdelete;
    loadjs.done("fsubmission_commentsdelete");
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
<form name="fsubmission_commentsdelete" id="fsubmission_commentsdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="submission_comments">
<input type="hidden" name="action" id="action" value="delete">
<?php foreach ($Page->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode(Config("COMPOSITE_KEY_SEPARATOR"), $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="card ew-card ew-grid">
<div class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table class="table table-bordered table-hover table-sm ew-table">
    <thead>
    <tr class="ew-table-header">
<?php if ($Page->Submission_ID->Visible) { // Submission_ID ?>
        <th class="<?= $Page->Submission_ID->headerCellClass() ?>"><span id="elh_submission_comments_Submission_ID" class="submission_comments_Submission_ID"><?= $Page->Submission_ID->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Updated_Username->Visible) { // Updated_Username ?>
        <th class="<?= $Page->Updated_Username->headerCellClass() ?>"><span id="elh_submission_comments_Updated_Username" class="submission_comments_Updated_Username"><?= $Page->Updated_Username->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Comment->Visible) { // Comment ?>
        <th class="<?= $Page->Comment->headerCellClass() ?>"><span id="elh_submission_comments_Comment" class="submission_comments_Comment"><?= $Page->Comment->caption() ?></span></th>
<?php } ?>
    </tr>
    </thead>
    <tbody>
<?php
$Page->RecordCount = 0;
$i = 0;
while (!$Page->Recordset->EOF) {
    $Page->RecordCount++;
    $Page->RowCount++;

    // Set row properties
    $Page->resetAttributes();
    $Page->RowType = ROWTYPE_VIEW; // View

    // Get the field contents
    $Page->loadRowValues($Page->Recordset);

    // Render row
    $Page->renderRow();
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php if ($Page->Submission_ID->Visible) { // Submission_ID ?>
        <td<?= $Page->Submission_ID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_submission_comments_Submission_ID" class="el_submission_comments_Submission_ID">
<span<?= $Page->Submission_ID->viewAttributes() ?>>
<?= $Page->Submission_ID->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Updated_Username->Visible) { // Updated_Username ?>
        <td<?= $Page->Updated_Username->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_submission_comments_Updated_Username" class="el_submission_comments_Updated_Username">
<span<?= $Page->Updated_Username->viewAttributes() ?>>
<?= $Page->Updated_Username->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Comment->Visible) { // Comment ?>
        <td<?= $Page->Comment->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_submission_comments_Comment" class="el_submission_comments_Comment">
<span<?= $Page->Comment->viewAttributes() ?>>
<?= $Page->Comment->getViewValue() ?></span>
</span>
</td>
<?php } ?>
    </tr>
<?php
    $Page->Recordset->moveNext();
}
$Page->Recordset->close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("DeleteBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
</div>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
