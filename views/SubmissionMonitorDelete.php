<?php

namespace PHPMaker2022\civichub2;

// Page object
$SubmissionMonitorDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { submission_monitor: currentTable } });
var currentForm, currentPageID;
var fsubmission_monitordelete;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fsubmission_monitordelete = new ew.Form("fsubmission_monitordelete", "delete");
    currentPageID = ew.PAGE_ID = "delete";
    currentForm = fsubmission_monitordelete;
    loadjs.done("fsubmission_monitordelete");
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
<form name="fsubmission_monitordelete" id="fsubmission_monitordelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="submission_monitor">
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
<?php if ($Page->Monitor_ID->Visible) { // Monitor_ID ?>
        <th class="<?= $Page->Monitor_ID->headerCellClass() ?>"><span id="elh_submission_monitor_Monitor_ID" class="submission_monitor_Monitor_ID"><?= $Page->Monitor_ID->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Status->Visible) { // Status ?>
        <th class="<?= $Page->Status->headerCellClass() ?>"><span id="elh_submission_monitor_Status" class="submission_monitor_Status"><?= $Page->Status->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Taskings->Visible) { // Taskings ?>
        <th class="<?= $Page->Taskings->headerCellClass() ?>"><span id="elh_submission_monitor_Taskings" class="submission_monitor_Taskings"><?= $Page->Taskings->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Start_Date->Visible) { // Start_Date ?>
        <th class="<?= $Page->Start_Date->headerCellClass() ?>"><span id="elh_submission_monitor_Start_Date" class="submission_monitor_Start_Date"><?= $Page->Start_Date->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Finish_Date->Visible) { // Finish_Date ?>
        <th class="<?= $Page->Finish_Date->headerCellClass() ?>"><span id="elh_submission_monitor_Finish_Date" class="submission_monitor_Finish_Date"><?= $Page->Finish_Date->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Uploads->Visible) { // Uploads ?>
        <th class="<?= $Page->Uploads->headerCellClass() ?>"><span id="elh_submission_monitor_Uploads" class="submission_monitor_Uploads"><?= $Page->Uploads->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Updated_Username->Visible) { // Updated_Username ?>
        <th class="<?= $Page->Updated_Username->headerCellClass() ?>"><span id="elh_submission_monitor_Updated_Username" class="submission_monitor_Updated_Username"><?= $Page->Updated_Username->caption() ?></span></th>
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
<?php if ($Page->Monitor_ID->Visible) { // Monitor_ID ?>
        <td<?= $Page->Monitor_ID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_submission_monitor_Monitor_ID" class="el_submission_monitor_Monitor_ID">
<span<?= $Page->Monitor_ID->viewAttributes() ?>>
<?= $Page->Monitor_ID->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Status->Visible) { // Status ?>
        <td<?= $Page->Status->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_submission_monitor_Status" class="el_submission_monitor_Status">
<span<?= $Page->Status->viewAttributes() ?>>
<?= $Page->Status->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Taskings->Visible) { // Taskings ?>
        <td<?= $Page->Taskings->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_submission_monitor_Taskings" class="el_submission_monitor_Taskings">
<span<?= $Page->Taskings->viewAttributes() ?>>
<?= $Page->Taskings->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Start_Date->Visible) { // Start_Date ?>
        <td<?= $Page->Start_Date->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_submission_monitor_Start_Date" class="el_submission_monitor_Start_Date">
<span<?= $Page->Start_Date->viewAttributes() ?>>
<?= $Page->Start_Date->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Finish_Date->Visible) { // Finish_Date ?>
        <td<?= $Page->Finish_Date->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_submission_monitor_Finish_Date" class="el_submission_monitor_Finish_Date">
<span<?= $Page->Finish_Date->viewAttributes() ?>>
<?= $Page->Finish_Date->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Uploads->Visible) { // Uploads ?>
        <td<?= $Page->Uploads->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_submission_monitor_Uploads" class="el_submission_monitor_Uploads">
<span<?= $Page->Uploads->viewAttributes() ?>>
<?= GetFileViewTag($Page->Uploads, $Page->Uploads->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->Updated_Username->Visible) { // Updated_Username ?>
        <td<?= $Page->Updated_Username->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_submission_monitor_Updated_Username" class="el_submission_monitor_Updated_Username">
<span<?= $Page->Updated_Username->viewAttributes() ?>>
<?= $Page->Updated_Username->getViewValue() ?></span>
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
