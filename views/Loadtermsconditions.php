<?php

namespace PHPMaker2022\civichub2;

// Page object
$Loadtermsconditions = &$Page;
?>
<?php
$val = "";
if (!empty($val)) {
	echo $val;
} else {
	echo $Language->phrase('TermsConditionsNotAvailable');
}
?>
<?php
echo GetDebugMessage();
?>
