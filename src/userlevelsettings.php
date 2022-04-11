<?php
/**
 * PHPMaker 2022 user level settings
 */
namespace PHPMaker2022\civichub2;

// User level info
$USER_LEVELS = [["-2","Anonymous"],
    ["0","Default"]];

// User level priv info
$USER_LEVEL_PRIVS_1 = [["{01067D53-82C5-4C0E-9885-03D00C81A59F}submission_view","-2","0"],
    ["{01067D53-82C5-4C0E-9885-03D00C81A59F}submission_view","0","0"],
    ["{01067D53-82C5-4C0E-9885-03D00C81A59F}submission_comments","-2","72"],
    ["{01067D53-82C5-4C0E-9885-03D00C81A59F}submission_comments","0","0"],
    ["{01067D53-82C5-4C0E-9885-03D00C81A59F}submission_vote","-2","72"],
    ["{01067D53-82C5-4C0E-9885-03D00C81A59F}submission_vote","0","0"],
    ["{01067D53-82C5-4C0E-9885-03D00C81A59F}submission_monitor","-2","72"],
    ["{01067D53-82C5-4C0E-9885-03D00C81A59F}submission_monitor","0","0"],
    ["{01067D53-82C5-4C0E-9885-03D00C81A59F}userlevelpermissions","-2","0"],
    ["{01067D53-82C5-4C0E-9885-03D00C81A59F}userlevelpermissions","0","0"],
    ["{01067D53-82C5-4C0E-9885-03D00C81A59F}userlevels","-2","0"],
    ["{01067D53-82C5-4C0E-9885-03D00C81A59F}userlevels","0","0"],
    ["{01067D53-82C5-4C0E-9885-03D00C81A59F}users","-2","0"],
    ["{01067D53-82C5-4C0E-9885-03D00C81A59F}users","0","0"],
    ["{01067D53-82C5-4C0E-9885-03D00C81A59F}ref_category","-2","72"],
    ["{01067D53-82C5-4C0E-9885-03D00C81A59F}ref_category","0","0"],
    ["{01067D53-82C5-4C0E-9885-03D00C81A59F}ref_country","-2","72"],
    ["{01067D53-82C5-4C0E-9885-03D00C81A59F}ref_country","0","0"],
    ["{01067D53-82C5-4C0E-9885-03D00C81A59F}ref_organisation","-2","72"],
    ["{01067D53-82C5-4C0E-9885-03D00C81A59F}ref_organisation","0","0"],
    ["{01067D53-82C5-4C0E-9885-03D00C81A59F}ref_sdg","-2","72"],
    ["{01067D53-82C5-4C0E-9885-03D00C81A59F}ref_sdg","0","0"],
    ["{01067D53-82C5-4C0E-9885-03D00C81A59F}vote_tally","-2","0"],
    ["{01067D53-82C5-4C0E-9885-03D00C81A59F}vote_tally","0","0"],
    ["{01067D53-82C5-4C0E-9885-03D00C81A59F}Voting","-2","0"],
    ["{01067D53-82C5-4C0E-9885-03D00C81A59F}Voting","0","0"],
    ["{01067D53-82C5-4C0E-9885-03D00C81A59F}submission","-2","72"],
    ["{01067D53-82C5-4C0E-9885-03D00C81A59F}submission","0","0"]];
$USER_LEVEL_PRIVS_2 = [["{01067D53-82C5-4C0E-9885-03D00C81A59F}breadcrumblinksaddsp","-1","8"],
					["{01067D53-82C5-4C0E-9885-03D00C81A59F}breadcrumblinkschecksp","-1","8"],
					["{01067D53-82C5-4C0E-9885-03D00C81A59F}breadcrumblinksdeletesp","-1","8"],
					["{01067D53-82C5-4C0E-9885-03D00C81A59F}breadcrumblinksmovesp","-1","8"],
					["{01067D53-82C5-4C0E-9885-03D00C81A59F}calendarscheduler","-2","8"],
					["{01067D53-82C5-4C0E-9885-03D00C81A59F}loadhelponline","-2","8"],
					["{01067D53-82C5-4C0E-9885-03D00C81A59F}loadaboutus","-2","8"],
					["{01067D53-82C5-4C0E-9885-03D00C81A59F}loadtermsconditions","-2","8"],
					["{01067D53-82C5-4C0E-9885-03D00C81A59F}printtermsconditions","-2","8"]];
$USER_LEVEL_PRIVS = array_merge($USER_LEVEL_PRIVS_1, $USER_LEVEL_PRIVS_2);

// User level table info
$USER_LEVEL_TABLES_1 = [["submission_view","submission_view2","manage submission",true,"{01067D53-82C5-4C0E-9885-03D00C81A59F}","submissionview2list"],
    ["submission_comments","submission_comments","1. comments",true,"{01067D53-82C5-4C0E-9885-03D00C81A59F}","submissioncommentslist"],
    ["submission_vote","submission_vote","cast vote",true,"{01067D53-82C5-4C0E-9885-03D00C81A59F}","submissionvotelist"],
    ["submission_monitor","submission_monitor","3. monitoring",true,"{01067D53-82C5-4C0E-9885-03D00C81A59F}","submissionmonitorlist"],
    ["userlevelpermissions","userlevelpermissions","permission",true,"{01067D53-82C5-4C0E-9885-03D00C81A59F}","userlevelpermissionslist"],
    ["userlevels","userlevels","user levels",true,"{01067D53-82C5-4C0E-9885-03D00C81A59F}","userlevelslist"],
    ["users","users","users",true,"{01067D53-82C5-4C0E-9885-03D00C81A59F}","userslist"],
    ["ref_category","ref_category","category",true,"{01067D53-82C5-4C0E-9885-03D00C81A59F}","refcategorylist"],
    ["ref_country","ref_country","country",true,"{01067D53-82C5-4C0E-9885-03D00C81A59F}","refcountrylist"],
    ["ref_organisation","ref_organisation","organisation",true,"{01067D53-82C5-4C0E-9885-03D00C81A59F}","reforganisationlist"],
    ["ref_sdg","ref_sdg","goals",true,"{01067D53-82C5-4C0E-9885-03D00C81A59F}","refsdglist"],
    ["vote_tally","vote_tally","2. votes",true,"{01067D53-82C5-4C0E-9885-03D00C81A59F}","votetallylist"],
    ["Voting","Voting","chart vote",true,"{01067D53-82C5-4C0E-9885-03D00C81A59F}","voting"],
    ["submission","submission","submissions",true,"{01067D53-82C5-4C0E-9885-03D00C81A59F}","submissionlist"]];
$USER_LEVEL_TABLES_2 = [["breadcrumblinksaddsp","breadcrumblinksaddsp","System - Breadcrumb Links - Add",true,"{01067D53-82C5-4C0E-9885-03D00C81A59F}","breadcrumblinksaddsp"],
						["breadcrumblinkschecksp","breadcrumblinkschecksp","System - Breadcrumb Links - Check",true,"{01067D53-82C5-4C0E-9885-03D00C81A59F}","breadcrumblinkschecksp"],
						["breadcrumblinksdeletesp","breadcrumblinksdeletesp","System - Breadcrumb Links - Delete",true,"{01067D53-82C5-4C0E-9885-03D00C81A59F}","breadcrumblinksdeletesp"],
						["breadcrumblinksmovesp","breadcrumblinksmovesp","System - Breadcrumb Links - Move",true,"{01067D53-82C5-4C0E-9885-03D00C81A59F}","breadcrumblinksmovesp"],
						["calendarscheduler","calendarscheduler","System - Calendar Scheduler",true,"{01067D53-82C5-4C0E-9885-03D00C81A59F}","calendarscheduler"],
						["loadhelponline","loadhelponline","System - Load Help Online",true,"{01067D53-82C5-4C0E-9885-03D00C81A59F}","loadhelponline"],
						["loadaboutus","loadaboutus","System - Load About Us",true,"{01067D53-82C5-4C0E-9885-03D00C81A59F}","loadaboutus"],
						["loadtermsconditions","loadtermsconditions","System - Load Terms and Conditions",true,"{01067D53-82C5-4C0E-9885-03D00C81A59F}","loadtermsconditions"],
						["printtermsconditions","printtermsconditions","System - Print Terms and Conditions",true,"{01067D53-82C5-4C0E-9885-03D00C81A59F}","printtermsconditions"]];
$USER_LEVEL_TABLES = array_merge($USER_LEVEL_TABLES_1, $USER_LEVEL_TABLES_2);
