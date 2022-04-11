<?php

namespace PHPMaker2022\civichub2;

// Page object
$Breadcrumblinksmovesp = &$Page;
?>
<script type="text/javascript">
var fmovebreadcrumblink, currentPageID;
loadjs.ready("head", function() {
	// Form object
	currentPageID = ew.PAGE_ID = "custom";
	fmovebreadcrumblink = currentForm = new ew.Form("fmovebreadcrumblink", "custom");
	// Add fields
    fmovebreadcrumblink.addFields([
        ["CurrentRoot", [ew.Validators.required("<?php echo $Language->phrase("Current Page Title"); ?>")], false],
        ["NewRoot", [ew.Validators.required("<?php echo $Language->phrase("New Root (Page Title)"); ?>")], false]
    ]);

	// Form_CustomValidate
    fmovebreadcrumblink.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
		if ($("#CurrentRoot").val() == $("#NewRoot").val())
			return this.addCustomError("CurrentRoot", "Current and new root cannot be the same.");
        return true;
    }
    loadjs.done("fmovebreadcrumblink");
});
</script>
<?php
	global $Security, $Language;
	global $NewRoot, $CurrentRoot;
	$CurrentRoot = Get("CurrentRoot");
	$NewRoot = Get("NewRoot");
	if ($NewRoot == $CurrentRoot && !empty($CurrentRoot)) {
		CurrentPage()->setFailureMessage($Language->phrase("MoveBreadcrumbLinksSame"));
	} else {
		if ( ($CurrentRoot != "") && ($NewRoot != "") ) {	
		  $value = ExecuteScalar("SELECT COUNT(*) FROM ".Config("MS_MASINO_BREADCRUMBLINKS_TABLE")."");
		  if ( $value > 0 ) {
			  $vParent = ExecuteScalar("SELECT Page_Title FROM ".Config("MS_MASINO_BREADCRUMBLINKS_TABLE")." WHERE Page_Title = '".$CurrentRoot."'");
			  if ($vParent != "") {		   
				$value = ExecuteScalar("SELECT Page_Title FROM ".Config("MS_MASINO_BREADCRUMBLINKS_TABLE")." WHERE Page_Title = '".$NewRoot."'");
				if ($value == "") {
				  CurrentPage()->setFailureMessage($Language->phrase("MoveBreadcrumbLinksNoRoot"));
			   } else {
				 $rsb = ExecuteUpdate("CALL ".MS_BREADCRUMB_LINKS_MOVE_SP."('".$CurrentRoot."','".$NewRoot."')");
				  if (!empty($rsb)) {  
					CurrentPage()->setSuccessMessage(sprintf($Language->phrase("MoveBreadcrumbLinksSuccess"), $CurrentRoot, $NewRoot));
				  } else {
					CurrentPage()->setFailureMessage($Language->phrase("MoveBreadcrumbLinksFailed"));
				  }
				}
			  } else {
				CurrentPage()->setFailureMessage($Language->phrase("MoveBreadcrumbLinksNoTitle"));
			  }				  
		  } else {
				CurrentPage()->setFailureMessage($Language->phrase("MoveBreadcrumbLinksNoData"));	  
		  }
		} else {
		  //CurrentPage()->setFailureMessage($Language->phrase("MoveBreadcrumbLinksNoDetails"));
		}		
	  }
?>
<?php if (IsAdmin()) { ?>
<div class="card shadow-sm ms-breadcrumblinks-box mx-auto">
<div class="card-header">
  <h5 class="card-title"><?php echo $Language->phrase("MoveBreadcrumbLinksNote") ?></h5>
	<div class="card-tools"></div>
</div>
<div class="card-body">
<form name="fmovebreadcrumblink" id="fmovebreadcrumblink" method="get" action="<?php echo CurrentPageName() ?>" class="ms-breadcrumblinks-form form-horizontal ew-form ew-add-form">
	<div class="form-group row">
		<label class="col-sm-12 control-label ew-label" for="CurrentRoot">Current Page Title <?php echo $Language->phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-12">
		<?php
		$sSql = "SELECT Page_Title FROM ".Config("MS_MASINO_BREADCRUMBLINKS_TABLE")." ORDER BY Page_Title ASC";
		$rs = ExecuteQuery($sSql);
		$cntRec = $rs->rowCount();
		echo '<select id="CurrentRoot" name="CurrentRoot" class="form-select form-control">';
		echo '<option value="" selected="selected">'.$Language->phrase("PleaseSelect").'</option>';
		if ($cntRec > 0) {
		  while ($row = $rs->fetch()) {
			$selected = ($row["Page_Title"] == @$_GET["CurrentRoot"]) ? 'selected' : '';
			echo "<option value='".$row["Page_Title"]."' ". $selected. ">".$row["Page_Title"]."</option>";
		  }
		}
		echo "</select>";
		echo "<div class='invalid-feedback'></div>";
		?>	
		</div>
	</div>
	<div class="form-group row">
		<label class="col-sm-12 control-label ew-label" for="NewRoot">New Root (Page Title) <?php echo $Language->phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-12">
		<?php
		$sSql = "SELECT Page_Title FROM ".Config("MS_MASINO_BREADCRUMBLINKS_TABLE")." ORDER BY Page_Title ASC";
		$rs = ExecuteQuery($sSql);
		$cntRec = $rs->rowCount();
		echo '<select id="NewRoot" name="NewRoot" class="form-select form-control">';
		echo '<option value="" selected="selected">'.$Language->phrase("PleaseSelect").'</option>';
		if ($cntRec > 0) {
		  while ($row = $rs->fetch()) {
			$selected = ($row["Page_Title"] == @$_GET["NewRoot"]) ? 'selected' : '';
			echo "<option value='".$row["Page_Title"]."' ". $selected. ">".$row["Page_Title"]."</option>";
		  }
		}
		echo "</select>";
		echo "<div class='invalid-feedback'></div>";
		?>    
		</div>
	</div>
	<div class="form-group row">
		<label class="col-sm-12 control-label ew-label" for="btn-action">&nbsp;</label>
		<div class="col-sm-12">
			<button class="btn btn-primary ew-button" name="btn-action" id="btn-action" type="submit"><?php echo $Language->phrase("MoveBreadcrumbLinks") ?></button>
		</div>
	</div>
	<div class="form-group row">
		<div class="col-sm-12"><a href="breadcrumblinksaddsp"><?php echo $Language->phrase("AddBreadcrumbLinks") ?></a> | <a href="breadcrumblinkschecksp"><?php echo $Language->phrase("CheckBreadcrumbLinks") ?></a> | <a href="breadcrumblinksdeletesp"><?php echo $Language->phrase("DeleteBreadcrumbLinks") ?></a></div>
	</div>
	</form>
</div>
</div>
<?php } else { ?>
<?php CurrentPage()->setFailureMessage("You must be login as Administrator to access this page.") ?>
<?php } ?>
<script type="text/javascript">
loadjs.ready(["wrapper", "head", "swal"],function(){$('#btn-action').on('click',function(){var $ = jQuery; if(fmovebreadcrumblink.validate()==true){ew.prompt({html: ew.language.phrase("MessageEditConfirm"),icon:'question',showCancelButton:true},result=>{if(result) $("#fmovebreadcrumblink").submit();});return false;}});});
</script>
<?php CurrentPage()->showMessage(); ?>
<?php echo GetDebugMessage(); ?>
