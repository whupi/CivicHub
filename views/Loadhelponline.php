<?php

namespace PHPMaker2022\civichub2;

// Page object
$Loadhelponline = &$Page;
?>
<?php
if (!empty(Get("page"))) {
	echo "" . Language()->phrase('Help') . "~~~" . Language()->phrase('HelpNotAvailable');
} else {
	echo "" . Language()->phrase('Help') . "~~~" . Language()->phrase('HelpNotAvailable');
}
?>
<?php
echo GetDebugMessage();
?>
