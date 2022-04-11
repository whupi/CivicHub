<?php

namespace PHPMaker2022\civichub2;

// Table
$submission = Container("submission");
?>
<?php if ($submission->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_submissionmaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($submission->Submission_ID->Visible) { // Submission_ID ?>
        <tr id="r_Submission_ID"<?= $submission->Submission_ID->rowAttributes() ?>>
            <td class="<?= $submission->TableLeftColumnClass ?>"><?= $submission->Submission_ID->caption() ?></td>
            <td<?= $submission->Submission_ID->cellAttributes() ?>>
<span id="el_submission_Submission_ID">
<span<?= $submission->Submission_ID->viewAttributes() ?>>
<?= $submission->Submission_ID->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($submission->_Title->Visible) { // Title ?>
        <tr id="r__Title"<?= $submission->_Title->rowAttributes() ?>>
            <td class="<?= $submission->TableLeftColumnClass ?>"><?= $submission->_Title->caption() ?></td>
            <td<?= $submission->_Title->cellAttributes() ?>>
<span id="el_submission__Title">
<span<?= $submission->_Title->viewAttributes() ?>>
<?php if (!EmptyString($submission->_Title->getViewValue()) && $submission->_Title->linkAttributes() != "") { ?>
<a<?= $submission->_Title->linkAttributes() ?>><?= $submission->_Title->getViewValue() ?></a>
<?php } else { ?>
<?= $submission->_Title->getViewValue() ?>
<?php } ?>
</span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($submission->Category->Visible) { // Category ?>
        <tr id="r_Category"<?= $submission->Category->rowAttributes() ?>>
            <td class="<?= $submission->TableLeftColumnClass ?>"><?= $submission->Category->caption() ?></td>
            <td<?= $submission->Category->cellAttributes() ?>>
<span id="el_submission_Category">
<span<?= $submission->Category->viewAttributes() ?>>
<?= $submission->Category->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($submission->Status->Visible) { // Status ?>
        <tr id="r_Status"<?= $submission->Status->rowAttributes() ?>>
            <td class="<?= $submission->TableLeftColumnClass ?>"><?= $submission->Status->caption() ?></td>
            <td<?= $submission->Status->cellAttributes() ?>>
<span id="el_submission_Status">
<span<?= $submission->Status->viewAttributes() ?>>
<?= $submission->Status->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
