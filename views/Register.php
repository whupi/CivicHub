<?php

namespace PHPMaker2022\civichub2;

// Page object
$Register = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { users: currentTable } });
var currentForm, currentPageID;
var fregister;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fregister = new ew.Form("fregister", "register");
    currentPageID = ew.PAGE_ID = "register";
    currentForm = fregister;

    // Add fields
    var fields = currentTable.fields;
    fregister.addFields([
        ["_Username", [fields._Username.visible && fields._Username.required ? ew.Validators.required(fields._Username.caption) : null], fields._Username.isInvalid],
        ["c__Password", [ew.Validators.required(ew.language.phrase("ConfirmPassword")), ew.Validators.mismatchPassword], fields._Password.isInvalid],
        ["_Password", [fields._Password.visible && fields._Password.required ? ew.Validators.required(fields._Password.caption) : null, ew.Validators.password(fields._Password.raw)], fields._Password.isInvalid],
        ["First_Name", [fields.First_Name.visible && fields.First_Name.required ? ew.Validators.required(fields.First_Name.caption) : null], fields.First_Name.isInvalid],
        ["Last_Name", [fields.Last_Name.visible && fields.Last_Name.required ? ew.Validators.required(fields.Last_Name.caption) : null], fields.Last_Name.isInvalid],
        ["_Email", [fields._Email.visible && fields._Email.required ? ew.Validators.required(fields._Email.caption) : null, ew.Validators.username(fields._Email.raw)], fields._Email.isInvalid],
        ["User_Level", [fields.User_Level.visible && fields.User_Level.required ? ew.Validators.required(fields.User_Level.caption) : null], fields.User_Level.isInvalid],
        ["Activated", [fields.Activated.visible && fields.Activated.required ? ew.Validators.required(fields.Activated.caption) : null], fields.Activated.isInvalid]
    ]);

    // Form_CustomValidate
    fregister.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fregister.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    fregister.lists.User_Level = <?= $Page->User_Level->toClientList($Page) ?>;
    fregister.lists.Activated = <?= $Page->Activated->toClientList($Page) ?>;
    loadjs.done("fregister");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your client script here, no need to add script tags.
});
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<?php if (!$Page->IsModal) { ?>
<div class="col-md-12">
  <div class="card shadow-sm">
    <div class="card-header">
	  <h4 class="card-title"><?php echo Language()->phrase("RegisterCaption"); ?></h4>
	  <div class="card-tools">
	  <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
	  </button>
	  </div>
	  <!-- /.card-tools -->
    </div>
    <!-- /.card-header -->
    <div class="card-body">
<?php } ?>
<form name="fregister" id="fregister" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" style="width: 100% !important;">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="t" value="users">
<?php if ($Page->isConfirm()) { // Confirm page ?>
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="confirm" id="confirm" value="confirm">
<?php } else { ?>
<input type="hidden" name="action" id="action" value="confirm">
<?php } ?>
<?php // Begin of modification Terms and Conditions, by Masino Sinaga, July 14, 2014 ?>
<?php if (MS_SHOW_TERMS_AND_CONDITIONS_ON_REGISTRATION_PAGE == TRUE) { ?>
<?php if (!$Page->isConfirm()) { // Confirm page ?>
<?php
global $Language;
$val = "";
if (!empty($val)) {
	$taccontent = $val;
} else {
	$taccontent = $Language->phrase('TermsConditionsNotAvailable');
}
$taccontent = str_replace("<br>", "\n", $taccontent);
$taccontent = str_replace("<br />", "\n", $taccontent);
$taccontent = strip_tags($taccontent);
?>
<div class="form-group" id="r_Title">
	<div class="col-sm-12">
	<?php echo "<h4>" . $Language->phrase('TermsConditionsTitle') . "</h4>" ?>
	</div>
</div>
<div class="form-group" id="r_TAC">
	<div class="col-sm-12">
		<textarea class="form-control ew-control" id="tactextarea" readonly style="min-width:100%; max-width: 400px; max-height:400px; min-height:300px;"><?php echo $taccontent; ?></textarea>
	</div>
</div>
<?php if (MS_TERMS_AND_CONDITION_CHECKBOX_ON_REGISTER_PAGE == TRUE) { ?>
<div class="form-group" id="r_ChkTerms">
	<div class="col-sm-12">
		<label>
			<span class="kt-switch">
				<label class="col-form-label" for="chkterms">
				&nbsp;<?php echo $Language->phrase("IAgreeWith"); ?>&nbsp;<a href="javascript:void(0);" id="tac" onclick="getTermsConditions();return false;"><?php echo $Language->phrase("TermsConditionsTitle"); ?></a>&nbsp;<a href="printtermsconditions" title="<?php echo $Language->phrase("Print"); ?>&nbsp;<?php echo $Language->phrase("TermsConditionsTitle"); ?>"><?php echo Language()->phrase("Print"); ?></a>
				</label>
				<?php $selwrk = (@isset($_POST["chkterms"])) ? " checked='checked'" : ""; ?>
				<div class="form-check form-switch d-inline-block" style="vertical-align: middle;">
				<input type="checkbox" class="form-check-input" name="chkterms" id="chkterms" value="<?php echo @$_POST["chkterms"]; ?>" <?php echo $selwrk; ?>>
				</div>
			</span>
		</label>
	</div>
</div>
<?php } ?>
<div class="form-group" id="r_btnAction">
	<div class="col-sm-12">
	</div>
</div>
<?php } ?>
<?php } // MS_SHOW_TERMS_AND_CONDITIONS_ON_REGISTRATION_PAGE ?>
<?php // End of modification Terms and Conditions, by Masino Sinaga, July 14, 2014 ?>
<div class="ew-register-div"><!-- page* -->
<?php if ($Page->_Username->Visible) { // Username ?>
    <div id="r__Username"<?= $Page->_Username->rowAttributes() ?>>
        <label id="elh_users__Username" for="x__Username" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_Username->caption() ?><?= $Page->_Username->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->_Username->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_users__Username">
<input type="<?= $Page->_Username->getInputTextType() ?>" name="x__Username" id="x__Username" data-table="users" data-field="x__Username" value="<?= $Page->_Username->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->_Username->getPlaceHolder()) ?>"<?= $Page->_Username->editAttributes() ?> aria-describedby="x__Username_help">
<?= $Page->_Username->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_Username->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el_users__Username">
<span<?= $Page->_Username->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->_Username->getDisplayValue($Page->_Username->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="users" data-field="x__Username" data-hidden="1" name="x__Username" id="x__Username" value="<?= HtmlEncode($Page->_Username->FormValue) ?>">
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->_Password->Visible) { // Password ?>
    <div id="r__Password"<?= $Page->_Password->rowAttributes() ?>>
        <label id="elh_users__Password" for="x__Password" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_Password->caption() ?><?= $Page->_Password->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->_Password->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_users__Password">
<div class="input-group">
    <input type="password" name="x__Password" id="x__Password" autocomplete="new-password" data-field="x__Password" size="30" maxlength="64" placeholder="<?= HtmlEncode($Page->_Password->getPlaceHolder()) ?>"<?= $Page->_Password->editAttributes() ?> aria-describedby="x__Password_help">
    <button type="button" class="btn btn-default ew-toggle-password rounded-end" data-ew-action="password"><i class="fas fa-eye"></i></button>
</div>
<?= $Page->_Password->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_Password->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el_users__Password">
<span<?= $Page->_Password->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->_Password->getDisplayValue($Page->_Password->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="users" data-field="x__Password" data-hidden="1" name="x__Password" id="x__Password" value="<?= HtmlEncode($Page->_Password->FormValue) ?>">
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->_Password->Visible) { // Password ?>
    <div id="r_c__Password" class="row">
        <label id="elh_c_users__Password" for="c__Password" class="<?= $Page->LeftColumnClass ?>"><?= $Language->phrase("Confirm") ?> <?= $Page->_Password->caption() ?><?= $Page->_Password->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->_Password->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_c_users__Password">
<div class="input-group">
    <input type="password" name="c__Password" id="c__Password" autocomplete="new-password" data-field="x__Password" size="30" maxlength="64" placeholder="<?= HtmlEncode($Page->_Password->getPlaceHolder()) ?>"<?= $Page->_Password->editAttributes() ?> aria-describedby="x__Password_help">
    <button type="button" class="btn btn-default ew-toggle-password rounded-end" data-ew-action="password"><i class="fas fa-eye"></i></button>
</div>
<?= $Page->_Password->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_Password->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el_c_users__Password">
<span<?= $Page->_Password->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->_Password->getDisplayValue($Page->_Password->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="users" data-field="x__Password" data-hidden="1" name="c__Password" id="c__Password" value="<?= HtmlEncode($Page->_Password->FormValue) ?>">
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->First_Name->Visible) { // First_Name ?>
    <div id="r_First_Name"<?= $Page->First_Name->rowAttributes() ?>>
        <label id="elh_users_First_Name" for="x_First_Name" class="<?= $Page->LeftColumnClass ?>"><?= $Page->First_Name->caption() ?><?= $Page->First_Name->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->First_Name->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_users_First_Name">
<input type="<?= $Page->First_Name->getInputTextType() ?>" name="x_First_Name" id="x_First_Name" data-table="users" data-field="x_First_Name" value="<?= $Page->First_Name->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->First_Name->getPlaceHolder()) ?>"<?= $Page->First_Name->editAttributes() ?> aria-describedby="x_First_Name_help">
<?= $Page->First_Name->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->First_Name->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el_users_First_Name">
<span<?= $Page->First_Name->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->First_Name->getDisplayValue($Page->First_Name->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="users" data-field="x_First_Name" data-hidden="1" name="x_First_Name" id="x_First_Name" value="<?= HtmlEncode($Page->First_Name->FormValue) ?>">
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Last_Name->Visible) { // Last_Name ?>
    <div id="r_Last_Name"<?= $Page->Last_Name->rowAttributes() ?>>
        <label id="elh_users_Last_Name" for="x_Last_Name" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Last_Name->caption() ?><?= $Page->Last_Name->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->Last_Name->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_users_Last_Name">
<input type="<?= $Page->Last_Name->getInputTextType() ?>" name="x_Last_Name" id="x_Last_Name" data-table="users" data-field="x_Last_Name" value="<?= $Page->Last_Name->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->Last_Name->getPlaceHolder()) ?>"<?= $Page->Last_Name->editAttributes() ?> aria-describedby="x_Last_Name_help">
<?= $Page->Last_Name->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Last_Name->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el_users_Last_Name">
<span<?= $Page->Last_Name->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->Last_Name->getDisplayValue($Page->Last_Name->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="users" data-field="x_Last_Name" data-hidden="1" name="x_Last_Name" id="x_Last_Name" value="<?= HtmlEncode($Page->Last_Name->FormValue) ?>">
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->_Email->Visible) { // Email ?>
    <div id="r__Email"<?= $Page->_Email->rowAttributes() ?>>
        <label id="elh_users__Email" for="x__Email" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_Email->caption() ?><?= $Page->_Email->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->_Email->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_users__Email">
<input type="<?= $Page->_Email->getInputTextType() ?>" name="x__Email" id="x__Email" data-table="users" data-field="x__Email" value="<?= $Page->_Email->EditValue ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->_Email->getPlaceHolder()) ?>"<?= $Page->_Email->editAttributes() ?> aria-describedby="x__Email_help">
<?= $Page->_Email->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_Email->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el_users__Email">
<span<?= $Page->_Email->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->_Email->getDisplayValue($Page->_Email->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="users" data-field="x__Email" data-hidden="1" name="x__Email" id="x__Email" value="<?= HtmlEncode($Page->_Email->FormValue) ?>">
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Activated->Visible) { // Activated ?>
    <div id="r_Activated"<?= $Page->Activated->rowAttributes() ?>>
        <label id="elh_users_Activated" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Activated->caption() ?><?= $Page->Activated->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->Activated->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_users_Activated">
<div class="form-check d-inline-block">
    <input type="checkbox" class="form-check-input<?= $Page->Activated->isInvalidClass() ?>" data-table="users" data-field="x_Activated" name="x_Activated[]" id="x_Activated_925964" value="1"<?= ConvertToBool($Page->Activated->CurrentValue) ? " checked" : "" ?><?= $Page->Activated->editAttributes() ?> aria-describedby="x_Activated_help">
    <div class="invalid-feedback"><?= $Page->Activated->getErrorMessage() ?></div>
</div>
<?= $Page->Activated->getCustomMessage() ?>
</span>
<?php } else { ?>
<span id="el_users_Activated">
<span<?= $Page->Activated->viewAttributes() ?>>
<div class="form-check d-inline-block">
    <input type="checkbox" id="x_Activated_<?= $Page->RowCount ?>" class="form-check-input" value="<?= $Page->Activated->ViewValue ?>" disabled<?php if (ConvertToBool($Page->Activated->CurrentValue)) { ?> checked<?php } ?>>
    <label class="form-check-label" for="x_Activated_<?= $Page->RowCount ?>"></label>
</div></span>
</span>
<input type="hidden" data-table="users" data-field="x_Activated" data-hidden="1" name="x_Activated" id="x_Activated" value="<?= HtmlEncode($Page->Activated->FormValue) ?>">
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if (!$Page->IsModal) { ?>
<div class="row"><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<?php if (!$Page->isConfirm()) { // Confirm page ?>
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" data-ew-action="set-action" data-value="confirm"><?= $Language->phrase("RegisterBtn") ?></button>
<?php } else { ?>
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("ConfirmBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="submit" data-ew-action="set-action" data-value="cancel"><?= $Language->phrase("CancelBtn") ?></button>
<?php } ?>
    </div><!-- /buttons offset -->
</div><!-- /buttons .row -->
<?php } ?>
</div><!-- /page* -->
<?php // } // MS_SHOW_TERMS_AND_CONDITIONS_ON_REGISTRATION_PAGE ?>
<?php // End of modification Terms and Conditions, by Masino Sinaga, July 14, 2014 ?>
</form>
<?php if (!$Page->IsModal) { ?>
		</div>
     <!-- /.card-body -->
     </div>
  <!-- /.card -->
</div>
<?php } ?>
<div class="clearfix">&nbsp;</div>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("users");
});
</script>
<script>
<?php // Begin of modification Terms and Conditions, by Masino Sinaga, July 14, 2014 ?>
<?php if (MS_SHOW_TERMS_AND_CONDITIONS_ON_REGISTRATION_PAGE == TRUE && !CurrentPage()->isConfirm()) { ?>
<?php if (MS_TERMS_AND_CONDITION_CHECKBOX_ON_REGISTER_PAGE == TRUE && !CurrentPage()->isConfirm()) { ?>
loadjs.ready("load", function() {
    $('#btn-action').click(function() {
	if (!$('#chkterms').is(":checked")) {
        Swal.fire({html: ew.language.phrase("TermsConditionsNotSelected"), icon: "error"});
        return false;
        }
    });
});
<?php } // MS_TERMS_AND_CONDITION_CHECKBOX_ON_REGISTER_PAGE ?>
<?php } // MS_SHOW_TERMS_AND_CONDITIONS_ON_REGISTRATION_PAGE ?>
<?php // End of modification Terms and Conditions, by Masino Sinaga, July 14, 2014 ?>
</script>
<?php if (Config("MS_ENTER_MOVING_CURSOR_TO_NEXT_FIELD")) { ?>
<script>
loadjs.ready("head", function() { $("#fregister:first *:input[type!=hidden]:first").focus(),$("input").keydown(function(i){if(13==i.which){var e=$(this).closest("form").find(":input:visible:enabled"),n=e.index(this);n==e.length-1||(e.eq(e.index(this)+1).focus(),i.preventDefault())}else 113==i.which&&$("#btn-action").click()}),$("select").keydown(function(i){if(13==i.which){var e=$(this).closest("form").find(":input:visible:enabled"),n=e.index(this);n==e.length-1||(e.eq(e.index(this)+1).focus(),i.preventDefault())}else 113==i.which&&$("#btn-action").click()}),$("radio").keydown(function(i){if(13==i.which){var e=$(this).closest("form").find(":input:visible:enabled"),n=e.index(this);n==e.length-1||(e.eq(e.index(this)+1).focus(),i.preventDefault())}else 113==i.which&&$("#btn-action").click()})});
</script>
<?php } ?>
<script>
loadjs.ready("load", function () {
    // Write your startup script here, no need to add script tags.
});
</script>
