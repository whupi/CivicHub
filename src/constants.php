<?php

/**
 * PHPMaker 2022 constants
 */

namespace PHPMaker2022\civichub2;

/**
 * Constants
 */
define(__NAMESPACE__ . "\PROJECT_NAMESPACE", __NAMESPACE__ . "\\");

// System
define(PROJECT_NAMESPACE . "IS_WINDOWS", strtolower(substr(PHP_OS, 0, 3)) === "win"); // Is Windows OS
define(PROJECT_NAMESPACE . "PATH_DELIMITER", IS_WINDOWS ? "\\" : "/"); // Physical path delimiter

// Data types
define(PROJECT_NAMESPACE . "DATATYPE_NUMBER", 1);
define(PROJECT_NAMESPACE . "DATATYPE_DATE", 2);
define(PROJECT_NAMESPACE . "DATATYPE_STRING", 3);
define(PROJECT_NAMESPACE . "DATATYPE_BOOLEAN", 4);
define(PROJECT_NAMESPACE . "DATATYPE_MEMO", 5);
define(PROJECT_NAMESPACE . "DATATYPE_BLOB", 6);
define(PROJECT_NAMESPACE . "DATATYPE_TIME", 7);
define(PROJECT_NAMESPACE . "DATATYPE_GUID", 8);
define(PROJECT_NAMESPACE . "DATATYPE_XML", 9);
define(PROJECT_NAMESPACE . "DATATYPE_BIT", 10);
define(PROJECT_NAMESPACE . "DATATYPE_OTHER", 11);

// Row types
define(PROJECT_NAMESPACE . "ROWTYPE_HEADER", 0); // Row type header
define(PROJECT_NAMESPACE . "ROWTYPE_VIEW", 1); // Row type view
define(PROJECT_NAMESPACE . "ROWTYPE_ADD", 2); // Row type add
define(PROJECT_NAMESPACE . "ROWTYPE_EDIT", 3); // Row type edit
define(PROJECT_NAMESPACE . "ROWTYPE_SEARCH", 4); // Row type search
define(PROJECT_NAMESPACE . "ROWTYPE_MASTER", 5); // Row type master record
define(PROJECT_NAMESPACE . "ROWTYPE_AGGREGATEINIT", 6); // Row type aggregate init
define(PROJECT_NAMESPACE . "ROWTYPE_AGGREGATE", 7); // Row type aggregate
define(PROJECT_NAMESPACE . "ROWTYPE_DETAIL", 8); // Row type detail
define(PROJECT_NAMESPACE . "ROWTYPE_TOTAL", 9); // Row type group summary
define(PROJECT_NAMESPACE . "ROWTYPE_PREVIEW", 10); // Preview record
define(PROJECT_NAMESPACE . "ROWTYPE_PREVIEW_FIELD", 11); // Preview field

// Row total types
define(PROJECT_NAMESPACE . "ROWTOTAL_GROUP", 1); // Page summary
define(PROJECT_NAMESPACE . "ROWTOTAL_PAGE", 2); // Page summary
define(PROJECT_NAMESPACE . "ROWTOTAL_GRAND", 3); // Grand summary

// Row total sub types
define(PROJECT_NAMESPACE . "ROWTOTAL_HEADER", 0); // Header
define(PROJECT_NAMESPACE . "ROWTOTAL_FOOTER", 1); // Footer
define(PROJECT_NAMESPACE . "ROWTOTAL_SUM", 2); // SUM
define(PROJECT_NAMESPACE . "ROWTOTAL_AVG", 3); // AVG
define(PROJECT_NAMESPACE . "ROWTOTAL_MIN", 4); // MIN
define(PROJECT_NAMESPACE . "ROWTOTAL_MAX", 5); // MAX
define(PROJECT_NAMESPACE . "ROWTOTAL_CNT", 6); // CNT

// List actions
define(PROJECT_NAMESPACE . "ACTION_POSTBACK", "P"); // Post back
define(PROJECT_NAMESPACE . "ACTION_AJAX", "A"); // Ajax
define(PROJECT_NAMESPACE . "ACTION_MULTIPLE", "M"); // Multiple records
define(PROJECT_NAMESPACE . "ACTION_SINGLE", "S"); // Single record

// User level constants
define(PROJECT_NAMESPACE . "ALLOW_ADD", 1); // Add
define(PROJECT_NAMESPACE . "ALLOW_DELETE", 2); // Delete
define(PROJECT_NAMESPACE . "ALLOW_EDIT", 4); // Edit
define(PROJECT_NAMESPACE . "ALLOW_LIST", 8); // List
define(PROJECT_NAMESPACE . "ALLOW_REPORT", 8); // Report
define(PROJECT_NAMESPACE . "ALLOW_ADMIN", 16); // Admin
define(PROJECT_NAMESPACE . "ALLOW_VIEW", 32); // View
define(PROJECT_NAMESPACE . "ALLOW_SEARCH", 64); // Search
define(PROJECT_NAMESPACE . "ALLOW_IMPORT", 128); // Import
define(PROJECT_NAMESPACE . "ALLOW_LOOKUP", 256); // Lookup
define(PROJECT_NAMESPACE . "ALLOW_PUSH", 512); // Push

// Begin of modification Permission Access for Export To Feature, by Masino Sinaga, May 5, 2012
define(PROJECT_NAMESPACE . "MS_ENABLE_PERMISSION_FOR_EXPORT_DATA", TRUE); // Enable this to allow dynamic permission of export data to media below // OK
define(PROJECT_NAMESPACE . "MS_ALLOW_PRINT", 1024); // Printer Friendly
define(PROJECT_NAMESPACE . "MS_ALLOW_EXCEL", 2048); // Export to Excel
define(PROJECT_NAMESPACE . "MS_ALLOW_WORD", 4096); // Export to Word
define(PROJECT_NAMESPACE . "MS_ALLOW_HTML", 8192); // Export to HTML
define(PROJECT_NAMESPACE . "MS_ALLOW_XML", 16384); // Export to XML
define(PROJECT_NAMESPACE . "MS_ALLOW_CSV", 32768); // Export to CSV
define(PROJECT_NAMESPACE . "MS_ALLOW_PDF", 65536); // Export to PDF
define(PROJECT_NAMESPACE . "MS_ALLOW_EMAIL", 131072); // Export to Email

// User level constants
// Allow All = 1023 (1 + 2 + 4 + 8 + 16 + 32 + 64 + 128 + 256 + 512)
if (MS_ENABLE_PERMISSION_FOR_EXPORT_DATA == TRUE) {
define(PROJECT_NAMESPACE . "ALLOW_ALL", 262143); // All (newest: 1023 + 1024 + 2048 + 4096 + 8192 + 16384 + 32768 + 65536 + 131072 = 262143) 
} else {
define(PROJECT_NAMESPACE . "ALLOW_ALL", 1023); // All (1 + 2 + 4 + 8 + 16 + 32 + 64 + 128 + 256 + 512 = 1023)
}
// End of modification Permission Access for Export To Feature, by Masino Sinaga, May 5, 2012
define(PROJECT_NAMESPACE . "MS_SHOW_TERMS_CONDITIONS_ON_FOOTER", TRUE); // Terms of Condition link
define(PROJECT_NAMESPACE . "MS_SHOW_ABOUT_US_ON_FOOTER", TRUE); // About Us link
define(PROJECT_NAMESPACE . "MS_SHOW_EMPTY_TABLE_ON_LIST_PAGE", TRUE); // Whether to show empty table in the List page if no records found

// Product version
define(PROJECT_NAMESPACE . "PRODUCT_VERSION", "18.11.0");

// Project
define(PROJECT_NAMESPACE . "PROJECT_NAME", "civichub2"); // Project name
define(PROJECT_NAMESPACE . "PROJECT_ID", "{01067D53-82C5-4C0E-9885-03D00C81A59F}"); // Project ID

/**
 * Character encoding
 * Note: If you use non English languages, you need to set character encoding
 * for some features. Make sure either iconv functions or multibyte string
 * functions are enabled and your encoding is supported. See PHP manual for
 * details.
 */
define(PROJECT_NAMESPACE . "PROJECT_ENCODING", "UTF-8"); // Character encoding
define(PROJECT_NAMESPACE . "IS_DOUBLE_BYTE", in_array(PROJECT_ENCODING, ["GBK", "BIG5", "SHIFT_JIS"])); // Double-byte character encoding
define(PROJECT_NAMESPACE . "FILE_SYSTEM_ENCODING", ""); // File system encoding

// Session
define(PROJECT_NAMESPACE . "SESSION_STATUS", PROJECT_NAME . "_status"); // Login status
define(PROJECT_NAMESPACE . "SESSION_USER_PROFILE", SESSION_STATUS . "_UserProfile"); // User profile
define(PROJECT_NAMESPACE . "SESSION_USER_NAME", SESSION_STATUS . "_UserName"); // User name
define(PROJECT_NAMESPACE . "SESSION_USER_LOGIN_TYPE", SESSION_STATUS . "_UserLoginType"); // User login type
define(PROJECT_NAMESPACE . "SESSION_USER_ID", SESSION_STATUS . "_UserID"); // User ID
define(PROJECT_NAMESPACE . "SESSION_USER_PROFILE_USER_NAME", SESSION_USER_PROFILE . "_UserName");
define(PROJECT_NAMESPACE . "SESSION_USER_PROFILE_PASSWORD", SESSION_USER_PROFILE . "_Password");
define(PROJECT_NAMESPACE . "SESSION_USER_PROFILE_LOGIN_TYPE", SESSION_USER_PROFILE . "_LoginType");
define(PROJECT_NAMESPACE . "SESSION_USER_PROFILE_SECRET", SESSION_USER_PROFILE . "_Secret");
define(PROJECT_NAMESPACE . "SESSION_USER_LEVEL_ID", SESSION_STATUS . "_UserLevel"); // User Level ID
define(PROJECT_NAMESPACE . "SESSION_USER_LEVEL_LIST", SESSION_STATUS . "_UserLevelList"); // User Level List
define(PROJECT_NAMESPACE . "SESSION_USER_LEVEL_LIST_LOADED", SESSION_STATUS . "_UserLevelListLoaded"); // User Level List Loaded
define(PROJECT_NAMESPACE . "SESSION_USER_LEVEL", SESSION_STATUS . "_UserLevelValue"); // User Level
define(PROJECT_NAMESPACE . "SESSION_PARENT_USER_ID", SESSION_STATUS . "_ParentUserId"); // Parent User ID
define(PROJECT_NAMESPACE . "SESSION_SYS_ADMIN", PROJECT_NAME . "_SysAdmin"); // System admin
define(PROJECT_NAMESPACE . "SESSION_PROJECT_ID", PROJECT_NAME . "_ProjectId"); // User Level project ID
define(PROJECT_NAMESPACE . "SESSION_AR_USER_LEVEL", PROJECT_NAME . "_arUserLevel"); // User Level array
define(PROJECT_NAMESPACE . "SESSION_AR_USER_LEVEL_PRIV", PROJECT_NAME . "_arUserLevelPriv"); // User Level privilege array
define(PROJECT_NAMESPACE . "SESSION_USER_LEVEL_MSG", PROJECT_NAME . "_UserLevelMessage"); // User Level Message
define(PROJECT_NAMESPACE . "SESSION_MESSAGE", PROJECT_NAME . "_Message"); // System message
define(PROJECT_NAMESPACE . "SESSION_FAILURE_MESSAGE", PROJECT_NAME . "_FailureMessage"); // System error message
define(PROJECT_NAMESPACE . "SESSION_SUCCESS_MESSAGE", PROJECT_NAME . "_SuccessMessage"); // System message
define(PROJECT_NAMESPACE . "SESSION_WARNING_MESSAGE", PROJECT_NAME . "_WarningMessage"); // Warning message
define(PROJECT_NAMESPACE . "SESSION_INLINE_MODE", PROJECT_NAME . "_InlineMode"); // Inline mode
define(PROJECT_NAMESPACE . "SESSION_BREADCRUMB", PROJECT_NAME . "_Breadcrumb"); // Breadcrumb
define(PROJECT_NAMESPACE . "SESSION_TEMP_IMAGES", PROJECT_NAME . "_TempImages"); // Temp images
define(PROJECT_NAMESPACE . "SESSION_CAPTCHA_CODE", PROJECT_NAME . "_Captcha"); // Captcha code
define(PROJECT_NAMESPACE . "SESSION_LANGUAGE_ID", PROJECT_NAME . "_LanguageId"); // Language ID

// Begin of modification Always Compare Root URL, by Masino Sinaga, October 18, 2015
define(PROJECT_NAMESPACE . "MS_ALWAYS_COMPARE_ROOT_URL", FALSE);
define(PROJECT_NAMESPACE . "MS_OTHER_COMPARED_ROOT_URL", "http://www.mydomain.com/dev");
// End of modification Always Compare Root URL, by Masino Sinaga, October 18, 2015
define(PROJECT_NAMESPACE . "MS_TABLE_MAXIMUM_SELECTED_RECORDS", 50); // Maximum selected records per page

// Begin of modification Enable Help Online, by Masino Sinaga, September 19, 2014
define(PROJECT_NAMESPACE . "MS_SHOW_HELP_ONLINE", TRUE); 
// End of modification Enable Help Online, by Masino Sinaga, September 19, 2014

// Begin of modification use SweetAlert instead of Bootstrap Toast, by Masino Sinaga, September 25, 2021
define(PROJECT_NAMESPACE . "MS_USE_MESSAGE_BOX_INSTEAD_OF_TOAST", TRUE); 
// End of modification use SweetAlert instead of Bootstrap Toast, by Masino Sinaga, September 25, 2021

// Begin of modification by Masino Sinaga, for saving the registered, last login, and last logout date time, November 6, 2011
define(PROJECT_NAMESPACE . "MS_USER_PROFILE_LAST_LOGIN_DATE_TIME", "LastLoginDateTime");
define(PROJECT_NAMESPACE . "MS_USER_PROFILE_LAST_LOGOUT_DATE_TIME", "LastLogoutDateTime");
// End of modification by Masino Sinaga, for saving the registered, last login, and last logout date time, November 6, 2011
define(PROJECT_NAMESPACE . "MS_USER_REGISTRATION", TRUE);
define(PROJECT_NAMESPACE . "MS_USER_PROFILE_REGISTERED_DATE_TIME", "RegisteredDateTime");
define(PROJECT_NAMESPACE . "MS_TERMS_AND_CONDITION_CHECKBOX_ON_REGISTER_PAGE", true);
define(PROJECT_NAMESPACE . "MS_SHOW_TERMS_AND_CONDITIONS_ON_REGISTRATION_PAGE", true);

// Begin of modification Displaying Breadcrumb Links, by Masino Sinaga, October 5, 2013
define(PROJECT_NAMESPACE . "MS_SHOW_PHPMAKER_BREADCRUMBLINKS", TRUE);
define(PROJECT_NAMESPACE . "MS_SHOW_MASINO_BREADCRUMBLINKS", FALSE);
define(PROJECT_NAMESPACE . "MS_BREADCRUMBLINKS_NO_LINKS", FALSE);
define(PROJECT_NAMESPACE . "MS_BREADCRUMBLINKS_DIVIDER", "/"); // in addition to "/" character, you may also use this: "»"
// End of modification Displaying Breadcrumb Links, by Masino Sinaga, October 5, 2013
// Begin of modification Breadcrumb Links SP, October 29, 2013
define(PROJECT_NAMESPACE . "MS_BREADCRUMB_LINKS_ADD_SP", "breadcrumblinksaddsp");
define(PROJECT_NAMESPACE . "MS_BREADCRUMB_LINKS_CHECK_SP", "breadcrumblinkschecksp");
define(PROJECT_NAMESPACE . "MS_BREADCRUMB_LINKS_MOVE_SP", "breadcrumblinksmovesp");
define(PROJECT_NAMESPACE . "MS_BREADCRUMB_LINKS_DELETE_SP", "breadcrumblinksdeletesp");
// End of modification Breadcrumb Links SP, October 29, 2013
