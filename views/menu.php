<?php

namespace PHPMaker2022\civichub2;

// Menu Language
if ($Language && function_exists(PROJECT_NAMESPACE . "Config") && $Language->LanguageFolder == Config("LANGUAGE_FOLDER")) {
    $MenuRelativePath = "";
    $MenuLanguage = &$Language;
} else { // Compat reports
    $LANGUAGE_FOLDER = "../lang/";
    $MenuRelativePath = "../";
    $MenuLanguage = Container("language");
}

// Navbar menu
$topMenu = new Menu("navbar", true, true);
$topMenu->addMenuItem(58, "mci_Discussions", $MenuLanguage->MenuPhrase("58", "MenuText"), $MenuRelativePath . "../discuss/public/", -1, "", true, false, true, "fa-comment", "", true, false);
echo $topMenu->toScript();

// Sidebar menu
$sideMenu = new Menu("menu", true, false);
$sideMenu->addMenuItem(31, "mi_submission", $MenuLanguage->MenuPhrase("31", "MenuText"), $MenuRelativePath . "submissionlist?cmd=resetall", -1, "", AllowListMenu('{01067D53-82C5-4C0E-9885-03D00C81A59F}submission'), false, false, "fa-list", "", false, true);
$sideMenu->addMenuItem(47, "mci_create_submission", $MenuLanguage->MenuPhrase("47", "MenuText"), $MenuRelativePath . "submissionview2add", -1, "", true, false, true, "fa-plus-square", "", false, true);
$sideMenu->addMenuItem(23, "mci_my_profile", $MenuLanguage->MenuPhrase("23", "MenuText"), "", -1, "", true, false, true, "fa-user", "", false, true);
$sideMenu->addMenuItem(3, "mi_submission_view2", $MenuLanguage->MenuPhrase("3", "MenuText"), $MenuRelativePath . "submissionview2list", 23, "", AllowListMenu('{01067D53-82C5-4C0E-9885-03D00C81A59F}submission_view'), false, false, "", "", false, true);
$sideMenu->addMenuItem(19, "mi_users", $MenuLanguage->MenuPhrase("19", "MenuText"), $MenuRelativePath . "userslist?cmd=resetall", 23, "", AllowListMenu('{01067D53-82C5-4C0E-9885-03D00C81A59F}users'), false, false, "", "", false, true);
$sideMenu->addMenuItem(20, "mci_settings", $MenuLanguage->MenuPhrase("20", "MenuText"), "", -1, "", true, false, true, "fa-cog", "", false, true);
$sideMenu->addMenuItem(4, "mi_ref_category", $MenuLanguage->MenuPhrase("4", "MenuText"), $MenuRelativePath . "refcategorylist", 20, "", AllowListMenu('{01067D53-82C5-4C0E-9885-03D00C81A59F}ref_category'), false, false, "", "", false, true);
$sideMenu->addMenuItem(26, "mi_ref_country", $MenuLanguage->MenuPhrase("26", "MenuText"), $MenuRelativePath . "refcountrylist", 20, "", AllowListMenu('{01067D53-82C5-4C0E-9885-03D00C81A59F}ref_country'), false, false, "", "", false, true);
$sideMenu->addMenuItem(18, "mi_userlevels", $MenuLanguage->MenuPhrase("18", "MenuText"), $MenuRelativePath . "userlevelslist", 20, "", AllowListMenu('{01067D53-82C5-4C0E-9885-03D00C81A59F}userlevels'), false, false, "", "", false, true);
$sideMenu->addMenuItem(28, "mi_ref_sdg", $MenuLanguage->MenuPhrase("28", "MenuText"), $MenuRelativePath . "refsdglist", 20, "", AllowListMenu('{01067D53-82C5-4C0E-9885-03D00C81A59F}ref_sdg'), false, false, "", "", false, true);
$sideMenu->addMenuItem(27, "mi_ref_organisation", $MenuLanguage->MenuPhrase("27", "MenuText"), $MenuRelativePath . "reforganisationlist?cmd=resetall", 20, "", AllowListMenu('{01067D53-82C5-4C0E-9885-03D00C81A59F}ref_organisation'), false, false, "", "", false, true);
$sideMenu->addMenuItem(58, "mci_Discussions", $MenuLanguage->MenuPhrase("58", "MenuText"), $MenuRelativePath . "../discuss/public/", -1, "", true, false, true, "fa-comment", "", true, true);
echo $sideMenu->toScript();
