<?php

namespace PHPMaker2022\civichub2;

// Table
$userlevels = Container("userlevels");
?>
<?php if ($userlevels->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_userlevelsmaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($userlevels->User_Level_ID->Visible) { // User_Level_ID ?>
        <tr id="r_User_Level_ID"<?= $userlevels->User_Level_ID->rowAttributes() ?>>
            <td class="<?= $userlevels->TableLeftColumnClass ?>"><?= $userlevels->User_Level_ID->caption() ?></td>
            <td<?= $userlevels->User_Level_ID->cellAttributes() ?>>
<span id="el_userlevels_User_Level_ID">
<span<?= $userlevels->User_Level_ID->viewAttributes() ?>>
<?= $userlevels->User_Level_ID->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($userlevels->User_Level_Name->Visible) { // User_Level_Name ?>
        <tr id="r_User_Level_Name"<?= $userlevels->User_Level_Name->rowAttributes() ?>>
            <td class="<?= $userlevels->TableLeftColumnClass ?>"><?= $userlevels->User_Level_Name->caption() ?></td>
            <td<?= $userlevels->User_Level_Name->cellAttributes() ?>>
<span id="el_userlevels_User_Level_Name">
<span<?= $userlevels->User_Level_Name->viewAttributes() ?>>
<?= $userlevels->User_Level_Name->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
