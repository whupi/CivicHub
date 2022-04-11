<?php

namespace PHPMaker2022\civichub2;

// Page object
$Breadcrumblinksaddsp = &$Page;
?>
<script type="text/javascript">
var faddbreadcrumblink, currentPageID;
loadjs.ready("head", function() {
	// Form object
	currentPageID = ew.PAGE_ID = "custom";
	faddbreadcrumblink = currentForm = new ew.Form("faddbreadcrumblink", "custom");
	// Add fields
    faddbreadcrumblink.addFields([
        ["PageTitleParent", [ew.Validators.required("<?php echo $Language->phrase("Page Title Parent"); ?>")], false],
        ["PageTitle", [ew.Validators.required("<?php echo $Language->phrase("Page Title"); ?>")], false]
    ]);

	// Form_CustomValidate
    faddbreadcrumblink.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }
    loadjs.done("faddbreadcrumblink");
});
</script>
<?php
		global $Security, $Language;
		global $PageTitle, $PageTitleParent;
		$PageTitleParent = Get("PageTitleParent");
		$PageTitle = Get("PageTitle");
		$PageURL = Get("PageURL");
		if ( isset($_GET["PageTitleParent"]) && isset($_GET["PageTitle"]) ) {
			if ( ($PageTitleParent != "") && ($PageTitle != "") ) {	
			  $value = ExecuteScalar("SELECT COUNT(*) FROM ".Config("MS_MASINO_BREADCRUMBLINKS_TABLE")."");
			  if ( $value==0 ) {
				  $rsb = ExecuteStatement("CALL ".MS_BREADCRUMB_LINKS_ADD_SP."('".$PageTitleParent."','".$PageTitle."','".$PageURL."')");
				  if (!empty($rsb)) {  

					//CurrentPage()->setSuccessMessage("Breadcrumb link has been successfully added.");
					CurrentPage()->setSuccessMessage(sprintf($Language->phrase("AddBreadcrumbLinksSuccess"), $PageTitle));
				  } else {
					//CurrentPage()->setFailureMessage("Failed. Please check your data.");
					CurrentPage()->setFailureMessage($Language->phrase("AddBreadcrumbLinksFailed"));
				  }
			  } else {
				  $vParent = ExecuteScalar("SELECT Page_Title FROM ".Config("MS_MASINO_BREADCRUMBLINKS_TABLE")." WHERE Page_Title = '".$PageTitleParent."'");
				  if ($vParent != "") {		   
					$value = ExecuteScalar("SELECT Page_Title FROM ".Config("MS_MASINO_BREADCRUMBLINKS_TABLE")." WHERE Page_Title = '".$PageTitle."'");
					if ($value != "") {
					  //CurrentPage()->setFailureMessage("Failed. Page Title <strong>".$PageTitle."</strong> already exists in breadcrumblinks table.");
					  CurrentPage()->setFailureMessage(sprintf($Language->phrase("AddBreadcrumbLinksDuplicate"), $PageTitle));
				   } else {
					  $rsb = ExecuteStatement("CALL ".MS_BREADCRUMB_LINKS_ADD_SP."('".$PageTitleParent."','".$PageTitle."','".$PageURL."')");
					  if (!empty($rsb)) {  
						//CurrentPage()->setSuccessMessage("Breadcrumb link <strong>".$PageTitle."</strong> has been successfully added.");
						CurrentPage()->setSuccessMessage(sprintf($Language->phrase("AddBreadcrumbLinksSuccess"), $PageTitle));
					  } else {
						//CurrentPage()->setFailureMessage("Failed. Please check your data.");
						CurrentPage()->setFailureMessage($Language->phrase("AddBreadcrumbLinksFailed"));
					  }
					}
				  } else {
					//CurrentPage()->setFailureMessage("Failed. Page Title Parent does not exist in breadcrumblinks table.");
					CurrentPage()->setFailureMessage($Language->phrase("AddBreadcrumbLinksNoParent"));
				  }
			  }
			} else {
			  //CurrentPage()->setFailureMessage("Please input the required fields.");
			  CurrentPage()->setFailureMessage($Language->phrase("AddBreadcrumbLinksNoDetails"));
			}
		}
?>
<?php 
global $Language;
?>
<?php if (IsAdmin()) { ?>
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<div class="card shadow-sm ms-breadcrumblinks-box mx-auto">
	<div class="card-header">
	  <h5 class="card-title"><?php echo $Language->phrase("AddBreadcrumbLinksNote") ?></h5>
	  <div class="card-tools"></div>
    </div>
<div class="card-body">
<form name="faddbreadcrumblink" id="faddbreadcrumblink" method="get" action="<?php echo CurrentPageName() ?>" class="ms-breadcrumblinks-form form-horizontal ew-form ew-add-form">
	<div class="form-group row">
		<label class="col-sm-12 control-label ew-label" for="pagetitleparent">Page Title Parent <?php echo $Language->phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-12">
		<?php
		$sSql = "SELECT Page_Title FROM ".Config("MS_MASINO_BREADCRUMBLINKS_TABLE")." ORDER BY Page_Title ASC";
		$rs = ExecuteQuery($sSql);
		$cntRec = $rs->rowCount();
		if ($cntRec > 0) {
		  echo '<select id="PageTitleParent" name="PageTitleParent" class="form-select form-control">';
		  echo '<option value="" selected="selected">'.$Language->phrase("PleaseSelect").'</option>';
		  while ($row = $rs->fetch()) { // loop
			$selected = ($row["Page_Title"] == Get("PageTitleParent")) ? 'selected' : '';
			echo "<option value='".$row["Page_Title"]."' ". $selected. ">".$row["Page_Title"]."</option>";
		  }
		  echo "</select>";
		  echo "<div class='invalid-feedback'></div>";
		} else {
		  echo '<input type="text" name="PageTitleParent" maxlength="100" class="form-control ew-control" placeholder="Page Title Parent">';
		  echo "<div class='invalid-feedback'></div>";
		}
		?>
		</div>
	</div>
	<div class="form-group row">
		<label class="col-sm-12 control-label ew-label" for="pagetitleparent">Page Title <?php echo $Language->phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-12">
			<input type="text" name="PageTitle" size="50" maxlength="100" class="form-control ew-control" placeholder="Page Title">
			<div class="invalid-feedback"></div>
		</div>
	</div>
	<div class="form-group row">
		<label class="col-sm-12 control-label ew-label" for="pagetitleparent">Page URL</label>
		<div class="col-sm-12">
			<input type="text" name="PageURL" size="50" maxlength="100" class="form-control ew-control" placeholder="Page URL">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-sm-12 control-label ew-label" for="pagetitleparent">&nbsp;</label>
		<div class="col-sm-12">
			<button class="btn btn-primary ew-button" name="btn-action" id="btn-action" type="submit"><?php echo $Language->phrase("AddBreadcrumbLinks") ?></button>
		</div>
	</div>
	<div class="form-group row">
		<div class="col-sm-12"><a href="breadcrumblinkschecksp"><?php echo $Language->phrase("CheckBreadcrumbLinks") ?></a> | <a href="breadcrumblinksmovesp"><?php echo $Language->phrase("MoveBreadcrumbLinks") ?></a> | <a href="breadcrumblinksdeletesp"><?php echo $Language->phrase("DeleteBreadcrumbLinks") ?></a></div>
	</div>
	</form>
</div>
</div>
<?php } else { ?>
<?php CurrentPage()->setFailureMessage($Language->phrase("YouMustBeAdministratorToAccessPage")) ?>
<?php } ?>
<script type="text/javascript">
loadjs.ready(["wrapper", "head", "swal"],function(){$('#btn-action').on('click',function(){var $ = jQuery; if(faddbreadcrumblink.validate()==true){ew.prompt({html: ew.language.phrase("MessageAddConfirm"),icon:'question',showCancelButton:true},result=>{if(result) $("#faddbreadcrumblink").submit();});return false;}});});
</script>
<?php CurrentPage()->showMessage(); ?>
<?php echo GetDebugMessage(); ?>
