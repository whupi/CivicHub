<?php

namespace PHPMaker2022\civichub2;

// Page object
$Loadaboutus = &$Page;
?>
<?php
$val = "";
if (!empty($val)) {
	echo $val;
} else {
	echo Language()->phrase('AboutUsNotAvailable');
}
?>
<?php
echo GetDebugMessage();
?>
