<?php

namespace PHPMaker2022\civichub2;

// Page object
$SubmissionMonitorView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { submission_monitor: currentTable } });
var currentForm, currentPageID;
var fsubmission_monitorview;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fsubmission_monitorview = new ew.Form("fsubmission_monitorview", "view");
    currentPageID = ew.PAGE_ID = "view";
    currentForm = fsubmission_monitorview;
    loadjs.done("fsubmission_monitorview");
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
<form name="fsubmission_monitorview" id="fsubmission_monitorview" class="ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="submission_monitor">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-bordered table-hover table-sm ew-view-table">
<?php if ($Page->Monitor_ID->Visible) { // Monitor_ID ?>
    <tr id="r_Monitor_ID"<?= $Page->Monitor_ID->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_submission_monitor_Monitor_ID"><?= $Page->Monitor_ID->caption() ?></span></td>
        <td data-name="Monitor_ID"<?= $Page->Monitor_ID->cellAttributes() ?>>
<span id="el_submission_monitor_Monitor_ID">
<span<?= $Page->Monitor_ID->viewAttributes() ?>>
<?= $Page->Monitor_ID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Status->Visible) { // Status ?>
    <tr id="r_Status"<?= $Page->Status->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_submission_monitor_Status"><?= $Page->Status->caption() ?></span></td>
        <td data-name="Status"<?= $Page->Status->cellAttributes() ?>>
<span id="el_submission_monitor_Status">
<span<?= $Page->Status->viewAttributes() ?>>
<?= $Page->Status->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Taskings->Visible) { // Taskings ?>
    <tr id="r_Taskings"<?= $Page->Taskings->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_submission_monitor_Taskings"><?= $Page->Taskings->caption() ?></span></td>
        <td data-name="Taskings"<?= $Page->Taskings->cellAttributes() ?>>
<span id="el_submission_monitor_Taskings">
<span<?= $Page->Taskings->viewAttributes() ?>>
<?= $Page->Taskings->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Organisations->Visible) { // Organisations ?>
    <tr id="r_Organisations"<?= $Page->Organisations->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_submission_monitor_Organisations"><?= $Page->Organisations->caption() ?></span></td>
        <td data-name="Organisations"<?= $Page->Organisations->cellAttributes() ?>>
<span id="el_submission_monitor_Organisations">
<span<?= $Page->Organisations->viewAttributes() ?>>
<?= $Page->Organisations->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Start_Date->Visible) { // Start_Date ?>
    <tr id="r_Start_Date"<?= $Page->Start_Date->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_submission_monitor_Start_Date"><?= $Page->Start_Date->caption() ?></span></td>
        <td data-name="Start_Date"<?= $Page->Start_Date->cellAttributes() ?>>
<span id="el_submission_monitor_Start_Date">
<span<?= $Page->Start_Date->viewAttributes() ?>>
<?= $Page->Start_Date->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Finish_Date->Visible) { // Finish_Date ?>
    <tr id="r_Finish_Date"<?= $Page->Finish_Date->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_submission_monitor_Finish_Date"><?= $Page->Finish_Date->caption() ?></span></td>
        <td data-name="Finish_Date"<?= $Page->Finish_Date->cellAttributes() ?>>
<span id="el_submission_monitor_Finish_Date">
<span<?= $Page->Finish_Date->viewAttributes() ?>>
<?= $Page->Finish_Date->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Uploads->Visible) { // Uploads ?>
    <tr id="r_Uploads"<?= $Page->Uploads->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_submission_monitor_Uploads"><?= $Page->Uploads->caption() ?></span></td>
        <td data-name="Uploads"<?= $Page->Uploads->cellAttributes() ?>>
<span id="el_submission_monitor_Uploads">
<span<?= $Page->Uploads->viewAttributes() ?>>
<?= GetFileViewTag($Page->Uploads, $Page->Uploads->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Updated_Username->Visible) { // Updated_Username ?>
    <tr id="r_Updated_Username"<?= $Page->Updated_Username->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_submission_monitor_Updated_Username"><?= $Page->Updated_Username->caption() ?></span></td>
        <td data-name="Updated_Username"<?= $Page->Updated_Username->cellAttributes() ?>>
<span id="el_submission_monitor_Updated_Username">
<span<?= $Page->Updated_Username->viewAttributes() ?>>
<?= $Page->Updated_Username->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Updated_Last->Visible) { // Updated_Last ?>
    <tr id="r_Updated_Last"<?= $Page->Updated_Last->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_submission_monitor_Updated_Last"><?= $Page->Updated_Last->caption() ?></span></td>
        <td data-name="Updated_Last"<?= $Page->Updated_Last->cellAttributes() ?>>
<span id="el_submission_monitor_Updated_Last">
<span<?= $Page->Updated_Last->viewAttributes() ?>>
<?= $Page->Updated_Last->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Updated_IP->Visible) { // Updated_IP ?>
    <tr id="r_Updated_IP"<?= $Page->Updated_IP->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_submission_monitor_Updated_IP"><?= $Page->Updated_IP->caption() ?></span></td>
        <td data-name="Updated_IP"<?= $Page->Updated_IP->cellAttributes() ?>>
<span id="el_submission_monitor_Updated_IP">
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
