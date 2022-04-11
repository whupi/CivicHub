<?php

namespace PHPMaker2022\civichub2;

// Table
$ref_country = Container("ref_country");
?>
<?php if ($ref_country->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_ref_countrymaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($ref_country->Country->Visible) { // Country ?>
        <tr id="r_Country"<?= $ref_country->Country->rowAttributes() ?>>
            <td class="<?= $ref_country->TableLeftColumnClass ?>"><?= $ref_country->Country->caption() ?></td>
            <td<?= $ref_country->Country->cellAttributes() ?>>
<span id="el_ref_country_Country">
<span<?= $ref_country->Country->viewAttributes() ?>>
<?= $ref_country->Country->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
