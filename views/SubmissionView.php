<?php

namespace PHPMaker2022\civichub2;

// Page object
$SubmissionView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { submission: currentTable } });
var currentForm, currentPageID;
var fsubmissionview;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fsubmissionview = new ew.Form("fsubmissionview", "view");
    currentPageID = ew.PAGE_ID = "view";
    currentForm = fsubmissionview;
    loadjs.done("fsubmissionview");
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
<form name="fsubmissionview" id="fsubmissionview" class="ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="submission">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-bordered table-hover table-sm ew-view-table">
<?php if ($Page->Submission_ID->Visible) { // Submission_ID ?>
    <tr id="r_Submission_ID"<?= $Page->Submission_ID->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_submission_Submission_ID"><?= $Page->Submission_ID->caption() ?></span></td>
        <td data-name="Submission_ID"<?= $Page->Submission_ID->cellAttributes() ?>>
<span id="el_submission_Submission_ID">
<span<?= $Page->Submission_ID->viewAttributes() ?>>
<?= $Page->Submission_ID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->_Title->Visible) { // Title ?>
    <tr id="r__Title"<?= $Page->_Title->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_submission__Title"><?= $Page->_Title->caption() ?></span></td>
        <td data-name="_Title"<?= $Page->_Title->cellAttributes() ?>>
<span id="el_submission__Title">
<span<?= $Page->_Title->viewAttributes() ?>>
<?php if (!EmptyString($Page->_Title->getViewValue()) && $Page->_Title->linkAttributes() != "") { ?>
<a<?= $Page->_Title->linkAttributes() ?>><?= $Page->_Title->getViewValue() ?></a>
<?php } else { ?>
<?= $Page->_Title->getViewValue() ?>
<?php } ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Category->Visible) { // Category ?>
    <tr id="r_Category"<?= $Page->Category->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_submission_Category"><?= $Page->Category->caption() ?></span></td>
        <td data-name="Category"<?= $Page->Category->cellAttributes() ?>>
<span id="el_submission_Category">
<span<?= $Page->Category->viewAttributes() ?>>
<?= $Page->Category->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Status->Visible) { // Status ?>
    <tr id="r_Status"<?= $Page->Status->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_submission_Status"><?= $Page->Status->caption() ?></span></td>
        <td data-name="Status"<?= $Page->Status->cellAttributes() ?>>
<span id="el_submission_Status">
<span<?= $Page->Status->viewAttributes() ?>>
<?= $Page->Status->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->_Abstract->Visible) { // Abstract ?>
    <tr id="r__Abstract"<?= $Page->_Abstract->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_submission__Abstract"><?= $Page->_Abstract->caption() ?></span></td>
        <td data-name="_Abstract"<?= $Page->_Abstract->cellAttributes() ?>>
<span id="el_submission__Abstract">
<span<?= $Page->_Abstract->viewAttributes() ?>>
<?= $Page->_Abstract->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Tags->Visible) { // Tags ?>
    <tr id="r_Tags"<?= $Page->Tags->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_submission_Tags"><?= $Page->Tags->caption() ?></span></td>
        <td data-name="Tags"<?= $Page->Tags->cellAttributes() ?>>
<span id="el_submission_Tags">
<span<?= $Page->Tags->viewAttributes() ?>>
<?= $Page->Tags->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Cover->Visible) { // Cover ?>
    <tr id="r_Cover"<?= $Page->Cover->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_submission_Cover"><?= $Page->Cover->caption() ?></span></td>
        <td data-name="Cover"<?= $Page->Cover->cellAttributes() ?>>
<span id="el_submission_Cover">
<span<?= $Page->Cover->viewAttributes() ?>>
<?= $Page->Cover->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Uploads->Visible) { // Uploads ?>
    <tr id="r_Uploads"<?= $Page->Uploads->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_submission_Uploads"><?= $Page->Uploads->caption() ?></span></td>
        <td data-name="Uploads"<?= $Page->Uploads->cellAttributes() ?>>
<span id="el_submission_Uploads">
<span<?= $Page->Uploads->viewAttributes() ?>>
<?= $Page->Uploads->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Updated_Username->Visible) { // Updated_Username ?>
    <tr id="r_Updated_Username"<?= $Page->Updated_Username->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_submission_Updated_Username"><?= $Page->Updated_Username->caption() ?></span></td>
        <td data-name="Updated_Username"<?= $Page->Updated_Username->cellAttributes() ?>>
<span id="el_submission_Updated_Username">
<span<?= $Page->Updated_Username->viewAttributes() ?>>
<?= $Page->Updated_Username->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Updated_Last->Visible) { // Updated_Last ?>
    <tr id="r_Updated_Last"<?= $Page->Updated_Last->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_submission_Updated_Last"><?= $Page->Updated_Last->caption() ?></span></td>
        <td data-name="Updated_Last"<?= $Page->Updated_Last->cellAttributes() ?>>
<span id="el_submission_Updated_Last">
<span<?= $Page->Updated_Last->viewAttributes() ?>>
<?= $Page->Updated_Last->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Updated_IP->Visible) { // Updated_IP ?>
    <tr id="r_Updated_IP"<?= $Page->Updated_IP->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_submission_Updated_IP"><?= $Page->Updated_IP->caption() ?></span></td>
        <td data-name="Updated_IP"<?= $Page->Updated_IP->cellAttributes() ?>>
<span id="el_submission_Updated_IP">
<span<?= $Page->Updated_IP->viewAttributes() ?>>
<?= $Page->Updated_IP->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php
    if (in_array("submission_comments", explode(",", $Page->getCurrentDetailTable())) && $submission_comments->DetailView) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("submission_comments", "TblCaption") ?>&nbsp;<?= str_replace("%c", Container("submission_comments")->Count, $Language->phrase("DetailCount")) ?></h4>
<?php } ?>
<?php include_once "SubmissionCommentsGrid.php" ?>
<?php } ?>
<?php
    if (in_array("vote_tally", explode(",", $Page->getCurrentDetailTable())) && $vote_tally->DetailView) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("vote_tally", "TblCaption") ?>&nbsp;<?= str_replace("%c", Container("vote_tally")->Count, $Language->phrase("DetailCount")) ?></h4>
<?php } ?>
<?php include_once "VoteTallyGrid.php" ?>
<?php } ?>
<?php
    if (in_array("submission_monitor", explode(",", $Page->getCurrentDetailTable())) && $submission_monitor->DetailView) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("submission_monitor", "TblCaption") ?>&nbsp;<?= str_replace("%c", Container("submission_monitor")->Count, $Language->phrase("DetailCount")) ?></h4>
<?php } ?>
<?php include_once "SubmissionMonitorGrid.php" ?>
<?php } ?>
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
