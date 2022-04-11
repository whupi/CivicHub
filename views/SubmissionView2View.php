<?php

namespace PHPMaker2022\civichub2;

// Page object
$SubmissionView2View = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { submission_view2: currentTable } });
var currentForm, currentPageID;
var fsubmission_view2view;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fsubmission_view2view = new ew.Form("fsubmission_view2view", "view");
    currentPageID = ew.PAGE_ID = "view";
    currentForm = fsubmission_view2view;
    loadjs.done("fsubmission_view2view");
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
<form name="fsubmission_view2view" id="fsubmission_view2view" class="ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="submission_view2">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-bordered table-hover table-sm ew-view-table">
<?php if ($Page->Submission_ID->Visible) { // Submission_ID ?>
    <tr id="r_Submission_ID"<?= $Page->Submission_ID->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_submission_view2_Submission_ID"><?= $Page->Submission_ID->caption() ?></span></td>
        <td data-name="Submission_ID"<?= $Page->Submission_ID->cellAttributes() ?>>
<span id="el_submission_view2_Submission_ID">
<span<?= $Page->Submission_ID->viewAttributes() ?>>
<?= $Page->Submission_ID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->_Title->Visible) { // Title ?>
    <tr id="r__Title"<?= $Page->_Title->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_submission_view2__Title"><?= $Page->_Title->caption() ?></span></td>
        <td data-name="_Title"<?= $Page->_Title->cellAttributes() ?>>
<span id="el_submission_view2__Title">
<span<?= $Page->_Title->viewAttributes() ?>>
<?= $Page->_Title->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Category_ID->Visible) { // Category_ID ?>
    <tr id="r_Category_ID"<?= $Page->Category_ID->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_submission_view2_Category_ID"><?= $Page->Category_ID->caption() ?></span></td>
        <td data-name="Category_ID"<?= $Page->Category_ID->cellAttributes() ?>>
<span id="el_submission_view2_Category_ID">
<span<?= $Page->Category_ID->viewAttributes() ?>>
<?= $Page->Category_ID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Status->Visible) { // Status ?>
    <tr id="r_Status"<?= $Page->Status->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_submission_view2_Status"><?= $Page->Status->caption() ?></span></td>
        <td data-name="Status"<?= $Page->Status->cellAttributes() ?>>
<span id="el_submission_view2_Status">
<span<?= $Page->Status->viewAttributes() ?>>
<?= $Page->Status->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->_Abstract->Visible) { // Abstract ?>
    <tr id="r__Abstract"<?= $Page->_Abstract->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_submission_view2__Abstract"><?= $Page->_Abstract->caption() ?></span></td>
        <td data-name="_Abstract"<?= $Page->_Abstract->cellAttributes() ?>>
<span id="el_submission_view2__Abstract">
<span<?= $Page->_Abstract->viewAttributes() ?>>
<?= $Page->_Abstract->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Tags->Visible) { // Tags ?>
    <tr id="r_Tags"<?= $Page->Tags->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_submission_view2_Tags"><?= $Page->Tags->caption() ?></span></td>
        <td data-name="Tags"<?= $Page->Tags->cellAttributes() ?>>
<span id="el_submission_view2_Tags">
<span<?= $Page->Tags->viewAttributes() ?>>
<?= $Page->Tags->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Uploads->Visible) { // Uploads ?>
    <tr id="r_Uploads"<?= $Page->Uploads->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_submission_view2_Uploads"><?= $Page->Uploads->caption() ?></span></td>
        <td data-name="Uploads"<?= $Page->Uploads->cellAttributes() ?>>
<span id="el_submission_view2_Uploads">
<span<?= $Page->Uploads->viewAttributes() ?>>
<?= GetFileViewTag($Page->Uploads, $Page->Uploads->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Cover->Visible) { // Cover ?>
    <tr id="r_Cover"<?= $Page->Cover->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_submission_view2_Cover"><?= $Page->Cover->caption() ?></span></td>
        <td data-name="Cover"<?= $Page->Cover->cellAttributes() ?>>
<span id="el_submission_view2_Cover">
<span<?= $Page->Cover->viewAttributes() ?>>
<?= GetFileViewTag($Page->Cover, $Page->Cover->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Updated_Username->Visible) { // Updated_Username ?>
    <tr id="r_Updated_Username"<?= $Page->Updated_Username->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_submission_view2_Updated_Username"><?= $Page->Updated_Username->caption() ?></span></td>
        <td data-name="Updated_Username"<?= $Page->Updated_Username->cellAttributes() ?>>
<span id="el_submission_view2_Updated_Username">
<span<?= $Page->Updated_Username->viewAttributes() ?>>
<?= $Page->Updated_Username->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Updated_Last->Visible) { // Updated_Last ?>
    <tr id="r_Updated_Last"<?= $Page->Updated_Last->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_submission_view2_Updated_Last"><?= $Page->Updated_Last->caption() ?></span></td>
        <td data-name="Updated_Last"<?= $Page->Updated_Last->cellAttributes() ?>>
<span id="el_submission_view2_Updated_Last">
<span<?= $Page->Updated_Last->viewAttributes() ?>>
<?= $Page->Updated_Last->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Updated_IP->Visible) { // Updated_IP ?>
    <tr id="r_Updated_IP"<?= $Page->Updated_IP->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_submission_view2_Updated_IP"><?= $Page->Updated_IP->caption() ?></span></td>
        <td data-name="Updated_IP"<?= $Page->Updated_IP->cellAttributes() ?>>
<span id="el_submission_view2_Updated_IP">
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
