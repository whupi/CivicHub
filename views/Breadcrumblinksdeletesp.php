<?php

namespace PHPMaker2022\civichub2;

// Page object
$Breadcrumblinksdeletesp = &$Page;
?>
<script type="text/javascript">
var fdeletebreadcrumblink, currentPageID;
loadjs.ready("head", function() {
	// Form object
	currentPageID = ew.PAGE_ID = "custom";
	fdeletebreadcrumblink = currentForm = new ew.Form("fdeletebreadcrumblink", "custom");
	// Add fields
    fdeletebreadcrumblink.addFields([
        ["PageTitle", [ew.Validators.required("<?php echo $Language->phrase("Page Title"); ?>")], false]
    ]);

	// Form_CustomValidate
    fdeletebreadcrumblink.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }
    loadjs.done("fcheckbreadcrumblink");
});
</script>
<?php
	global $Security, $Language;
	global $PageTitle;
	$PageTitle = Get("PageTitle");

	// CurrentPage()->setWarningMessage($Language->phrase("DeleteBreadcrumbLinksWarning"));
	if ( $PageTitle != "" ) {	
	  $value = ExecuteScalar("SELECT COUNT(*) FROM ".Config("MS_MASINO_BREADCRUMBLINKS_TABLE")."");
	  if ( $value > 0 ) {
			$pt = ExecuteScalar("SELECT Page_Title FROM ".Config("MS_MASINO_BREADCRUMBLINKS_TABLE")." WHERE Page_Title = '".$PageTitle."'");
			if ($pt == "") {
			  CurrentPage()->setFailureMessage($Language->phrase("DeleteBreadcrumbLinksNotFOund"));
		   } else {
			 $rsb = ExecuteUpdate("CALL ".MS_BREADCRUMB_LINKS_DELETE_SP."('".$PageTitle."')");
			  if (!empty($rsb)) {  
				CurrentPage()->setSuccessMessage("\n\n\n" . sprintf($Language->phrase("DeleteBreadcrumbLinksSuccess"), $PageTitle));
			  } else {
				CurrentPage()->setFailureMessage($Language->phrase("DeleteBreadcrumbLinksFailed"));
			  }
			}		  
	  } else {
		CurrentPage()->setFailureMessage($Language->phrase("DeleteBreadcrumbLinksNoData"));	  
	  }
	}
?>
<?php if (IsAdmin()) { ?>
<div class="card shadow-sm ms-breadcrumblinks-box mx-auto">
<div class="card-header">
  <h5 class="card-title"><?php echo $Language->phrase("DeleteBreadcrumbLinksNote") ?></h5>
	<div class="card-tools"></div>
</div>
<div class="card-body">
<form name="fdeletebreadcrumblink" id="fdeletebreadcrumblink" method="get" action="<?php echo CurrentPageName() ?>" class="ms-breadcrumblinks-form form-horizontal ew-form ew-add-form">
	<div class="form-group row">
		<label class="col-sm-12 control-label ew-label" for="pagetitle">Page Title <?php echo $Language->phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-12">
		<?php
		$sSql = "SELECT Page_Title FROM ".Config("MS_MASINO_BREADCRUMBLINKS_TABLE")." ORDER BY Page_Title ASC";
		$rs = ExecuteQuery($sSql);
		$cntRec = $rs->rowCount();
		echo '<select id="PageTitle" name="PageTitle" class="form-select form-control">';
		echo '<option value="" selected="selected">'.$Language->phrase("PleaseSelect").'</option>';
		if ($cntRec > 0) {
		  while ($row = $rs->fetch()) {
			$selected = ($row["Page_Title"] == @$_GET["PageTitle"]) ? 'selected' : '';
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
			<button class="btn btn-primary ew-button" name="btn-action" id="btn-action" type="submit"><?php echo $Language->phrase("DeleteBreadcrumbLinks") ?></button>
		</div>
	</div>
	<div class="form-group row">
		<div class="col-sm-12"><a href="breadcrumblinksaddsp"><?php echo $Language->phrase("AddBreadcrumbLinks") ?></a> | <a href="breadcrumblinkschecksp"><?php echo $Language->phrase("CheckBreadcrumbLinks") ?></a> | <a href="breadcrumblinksmovesp"><?php echo $Language->phrase("MoveBreadcrumbLinks") ?></a></div>
	</div>
	</form>
</div>
</div>
<?php } else { ?>
<?php CurrentPage()->setFailureMessage($Language->phrase("YouMustBeAdministratorToAccessPage")); ?>
<?php } ?>
<script type="text/javascript">
<?php 
$warning_delete = $Language->phrase("DeleteBreadcrumbLinksWarning"); 
$warning_delete.= '<br><br>'. $Language->phrase('MessageDeleteConfirm'); 
?>
loadjs.ready(["wrapper", "head", "swal"],function(){$('#btn-action').on('click',function(){var $ = jQuery; if(fdeletebreadcrumblink.validate()==true){ew.prompt({html: "<?php echo $warning_delete; ?>",icon:'question',showCancelButton:true},result=>{if(result) $("#fdeletebreadcrumblink").submit();});return false;}});});
</script>
<?php CurrentPage()->showMessage(); ?>
<?php echo GetDebugMessage(); ?>
