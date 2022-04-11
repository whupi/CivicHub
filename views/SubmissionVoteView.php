<?php

namespace PHPMaker2022\civichub2;

// Page object
$SubmissionVoteView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { submission_vote: currentTable } });
var currentForm, currentPageID;
var fsubmission_voteview;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fsubmission_voteview = new ew.Form("fsubmission_voteview", "view");
    currentPageID = ew.PAGE_ID = "view";
    currentForm = fsubmission_voteview;
    loadjs.done("fsubmission_voteview");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<?php if (!$Page->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php $Page->ExportOptions->render("body") ?>
<?php $Page->OtherOptions->render("body") ?>
</div>
<?php } ?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fsubmission_voteview" id="fsubmission_voteview" class="ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="submission_vote">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-bordered table-hover table-sm ew-view-table">
<?php if ($Page->Submission_ID->Visible) { // Submission_ID ?>
    <tr id="r_Submission_ID"<?= $Page->Submission_ID->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_submission_vote_Submission_ID"><?= $Page->Submission_ID->caption() ?></span></td>
        <td data-name="Submission_ID"<?= $Page->Submission_ID->cellAttributes() ?>>
<span id="el_submission_vote_Submission_ID">
<span<?= $Page->Submission_ID->viewAttributes() ?>>
<?= $Page->Submission_ID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Vote->Visible) { // Vote ?>
    <tr id="r_Vote"<?= $Page->Vote->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_submission_vote_Vote"><?= $Page->Vote->caption() ?></span></td>
        <td data-name="Vote"<?= $Page->Vote->cellAttributes() ?>>
<span id="el_submission_vote_Vote">
<span<?= $Page->Vote->viewAttributes() ?>>
<?= $Page->Vote->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Updated_Username->Visible) { // Updated_Username ?>
    <tr id="r_Updated_Username"<?= $Page->Updated_Username->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_submission_vote_Updated_Username"><?= $Page->Updated_Username->caption() ?></span></td>
        <td data-name="Updated_Username"<?= $Page->Updated_Username->cellAttributes() ?>>
<span id="el_submission_vote_Updated_Username">
<span<?= $Page->Updated_Username->viewAttributes() ?>>
<?= $Page->Updated_Username->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Updated_Date->Visible) { // Updated_Date ?>
    <tr id="r_Updated_Date"<?= $Page->Updated_Date->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_submission_vote_Updated_Date"><?= $Page->Updated_Date->caption() ?></span></td>
        <td data-name="Updated_Date"<?= $Page->Updated_Date->cellAttributes() ?>>
<span id="el_submission_vote_Updated_Date">
<span<?= $Page->Updated_Date->viewAttributes() ?>>
<?= $Page->Updated_Date->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Updated_IP->Visible) { // Updated_IP ?>
    <tr id="r_Updated_IP"<?= $Page->Updated_IP->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_submission_vote_Updated_IP"><?= $Page->Updated_IP->caption() ?></span></td>
        <td data-name="Updated_IP"<?= $Page->Updated_IP->cellAttributes() ?>>
<span id="el_submission_vote_Updated_IP">
<span<?= $Page->Updated_IP->viewAttributes() ?>>
<?= $Page->Updated_IP->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php if (!$Page->isExport()) { ?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
