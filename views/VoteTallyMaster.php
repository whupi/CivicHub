<?php

namespace PHPMaker2022\civichub2;

// Table
$vote_tally = Container("vote_tally");
?>
<?php if ($vote_tally->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_vote_tallymaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($vote_tally->Submission_ID->Visible) { // Submission_ID ?>
        <tr id="r_Submission_ID"<?= $vote_tally->Submission_ID->rowAttributes() ?>>
            <td class="<?= $vote_tally->TableLeftColumnClass ?>"><?= $vote_tally->Submission_ID->caption() ?></td>
            <td<?= $vote_tally->Submission_ID->cellAttributes() ?>>
<span id="el_vote_tally_Submission_ID">
<span<?= $vote_tally->Submission_ID->viewAttributes() ?>>
<?= $vote_tally->Submission_ID->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($vote_tally->_Title->Visible) { // Title ?>
        <tr id="r__Title"<?= $vote_tally->_Title->rowAttributes() ?>>
            <td class="<?= $vote_tally->TableLeftColumnClass ?>"><?= $vote_tally->_Title->caption() ?></td>
            <td<?= $vote_tally->_Title->cellAttributes() ?>>
<span id="el_vote_tally__Title">
<span<?= $vote_tally->_Title->viewAttributes() ?>>
<?= $vote_tally->_Title->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($vote_tally->Votes->Visible) { // Votes ?>
        <tr id="r_Votes"<?= $vote_tally->Votes->rowAttributes() ?>>
            <td class="<?= $vote_tally->TableLeftColumnClass ?>"><?= $vote_tally->Votes->caption() ?></td>
            <td<?= $vote_tally->Votes->cellAttributes() ?>>
<span id="el_vote_tally_Votes">
<span<?= $vote_tally->Votes->viewAttributes() ?>>
<?= $vote_tally->Votes->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($vote_tally->Count->Visible) { // Count ?>
        <tr id="r_Count"<?= $vote_tally->Count->rowAttributes() ?>>
            <td class="<?= $vote_tally->TableLeftColumnClass ?>"><?= $vote_tally->Count->caption() ?></td>
            <td<?= $vote_tally->Count->cellAttributes() ?>>
<span id="el_vote_tally_Count">
<span<?= $vote_tally->Count->viewAttributes() ?>>
<?= $vote_tally->Count->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
