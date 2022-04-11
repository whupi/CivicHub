<?php

namespace PHPMaker2022\civichub2;

// Page object
$Breadcrumblinkschecksp = &$Page;
?>
<script type="text/javascript">
var fcheckbreadcrumblink, currentPageID;
loadjs.ready("head", function() {
	// Form object
	currentPageID = ew.PAGE_ID = "custom";
	fcheckbreadcrumblink = currentForm = new ew.Form("fcheckbreadcrumblink", "custom");
	// Add fields
    fcheckbreadcrumblink.addFields([
        ["PageTitle", [ew.Validators.required("<?php echo $Language->phrase("Page Title"); ?>")], false]
    ]);

	// Form_CustomValidate
    fcheckbreadcrumblink.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }
    loadjs.done("fcheckbreadcrumblink");
});
</script>
<?php
	global $Language;
	global $PageTitle;

	function GetBreadcrumbLinks($pt) {
		global $Language;
		if ($pt != "") {
			$nav = "<div class='col-sm-12'>".$Language->phrase("CheckBreadcrumbLinksAdvice")."</div>";
			$nav .= "<br>";
			$nav .= "<div class='col-sm-12'><ol class=\"breadcrumb float-sm-left ew-breadcrumbs\">";
			$sSql = "SELECT C.* FROM ".Config("MS_MASINO_BREADCRUMBLINKS_TABLE")." AS B, ". Config("MS_MASINO_BREADCRUMBLINKS_TABLE")." AS C WHERE (B.Lft BETWEEN C.Lft AND C.Rgt) AND (B.Page_Title LIKE '".$pt."') ORDER BY C.Lft";
			$rs = ExecuteQuery($sSql); 
			$i = 1;
			while ($row = $rs->fetch()) {
				if ($i < $rs->rowCount()) {
					if ($i==1) {
						$nav .= "<li><a href='home' title='".$Language->BreadcrumbPhrase("Home")."' alt='".$Language->BreadcrumbPhrase("Home")."' class='ew-home' /><span class='fa fa-home ew-icon'></span></a>&nbsp;<span class=\"divider\">".MS_BREADCRUMBLINKS_DIVIDER."</span>&nbsp;</li>";
					} else {
						$nav .= "<li><a href='". $row["Page_URL"]."'>".$Language->BreadcrumbPhrase($row["Page_Title"])."</a>&nbsp;<span class=\"divider\">".MS_BREADCRUMBLINKS_DIVIDER."</span>&nbsp;</li>";
					}
				} else {
					$nav .= "<li class=\"active\"><strong>".$Language->BreadcrumbPhrase($row["Page_Title"])."</strong></li>";          
				}
				$i++;
			}
			$nav .= "</ol></div>";
		} else {
			$nav = "";
		}
		return $nav;
	}
	if ( Get("PageTitle") ) {
		$PageTitle = Get("PageTitle");
		$value = ExecuteScalar("SELECT COUNT(*) FROM ".Config("MS_MASINO_BREADCRUMBLINKS_TABLE")."");
		if ( $value > 0 ) {
			$pt = ExecuteScalar("SELECT Page_Title FROM ".Config("MS_MASINO_BREADCRUMBLINKS_TABLE")." WHERE Page_Title = '".$PageTitle."'");
			if ($pt == "") {
				CurrentPage()->setFailureMessage($Language->phrase("CheckBreadcrumbLinksNotFound"));
			} else {
				$rs1 = ExecuteQuery("SELECT C.* FROM ".Config("MS_MASINO_BREADCRUMBLINKS_TABLE")." AS B, ".Config("MS_MASINO_BREADCRUMBLINKS_TABLE")." AS C WHERE (B.Lft BETWEEN C.Lft AND C.Rgt) AND (B.Page_Title LIKE '".$PageTitle."') ORDER BY C.Lft");
				$recCount = $rs1->rowCount();
				if ($recCount > 0) {
					if ($pt != $Language->BreadcrumbPhrase($pt)) {
						CurrentPage()->setFailureMessage($Language->phrase("CheckBreadcrumbLinksNotDefinedInXML"));
					}
				} else {
					CurrentPage()->setFailureMessage($Language->phrase("CheckBreadcrumbLinksFailed"));
				}
			}
		} else {
			CurrentPage()->setFailureMessage($Language->phrase("CheckBreadcrumbLinksNoData"));
		}
	} 
?>
<?php if (IsAdmin()) { ?>
<div class="card shadow-sm ms-breadcrumblinks-box mx-auto">
<div class="card-header">
	  <h5 class="card-title"><?php echo $Language->phrase("CheckBreadcrumbLinksNote") ?></h5>
	  <div class="card-tools"></div>
    </div>
<div class="card-body">
<form name="fcheckbreadcrumblink" id="fcheckbreadcrumblink" method="get" action="<?php echo CurrentPageName() ?>" class="ms-breadcrumblinks-form form-horizontal ew-form ew-add-form">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
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
			$selected = ($row["Page_Title"] == Get("PageTitle")) ? 'selected' : '';
			echo "<option value='".$row["Page_Title"]."' ". $selected. ">".$row["Page_Title"]."</option>";
		  }
		}
		echo "</select>";
		echo "<div class='invalid-feedback'></div>";
		?>  
		</div>
	</div>
	<div class="form-group row">
		<label class="col-sm-12 control-label ew-label" for="btnAction"></label>
		<div class="col-sm-12">
			<button class="btn btn-primary ew-button" name="btnAction" id="btnAction" type="submit"><?php echo $Language->phrase("CheckBreadcrumbLinks") ?></button>
		</div>
	</div>
	<div class="form-group row">
		<div class="col-sm-12">
			<?php echo GetBreadcrumbLinks($PageTitle); ?>
		</div>
	</div>
	<div class="form-group row">
		<div class="col-sm-12"><a href="breadcrumblinksaddsp"><?php echo $Language->phrase("AddBreadcrumbLinks") ?></a> | <a href="breadcrumblinksmovesp"><?php echo $Language->phrase("MoveBreadcrumbLinks") ?></a> | <a href="breadcrumblinksdeletesp"><?php echo $Language->phrase("DeleteBreadcrumbLinks") ?></a></div>
	</div>
	</form>
</div>
</div>
<?php } else { ?>
<?php CurrentPage()->setFailureMessage($Language->phrase("YouMustBeAdministratorToAccessPage")); ?>
<?php } ?>
<?php CurrentPage()->showMessage(); ?>
<?php echo GetDebugMessage(); ?>
