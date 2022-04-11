<?php

namespace PHPMaker2022\civichub2;

// Table
$ref_category = Container("ref_category");
?>
<?php if ($ref_category->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_ref_categorymaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($ref_category->Category_ID->Visible) { // Category_ID ?>
        <tr id="r_Category_ID"<?= $ref_category->Category_ID->rowAttributes() ?>>
            <td class="<?= $ref_category->TableLeftColumnClass ?>"><?= $ref_category->Category_ID->caption() ?></td>
            <td<?= $ref_category->Category_ID->cellAttributes() ?>>
<span id="el_ref_category_Category_ID">
<span<?= $ref_category->Category_ID->viewAttributes() ?>>
<?= $ref_category->Category_ID->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($ref_category->Category->Visible) { // Category ?>
        <tr id="r_Category"<?= $ref_category->Category->rowAttributes() ?>>
            <td class="<?= $ref_category->TableLeftColumnClass ?>"><?= $ref_category->Category->caption() ?></td>
            <td<?= $ref_category->Category->cellAttributes() ?>>
<span id="el_ref_category_Category">
<span<?= $ref_category->Category->viewAttributes() ?>>
<?= $ref_category->Category->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
