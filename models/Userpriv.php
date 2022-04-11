<?php

namespace PHPMaker2022\civichub2;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class Userpriv extends Userlevels
{
    use MessagesTrait;

    // Page ID
    public $PageID = "userpriv";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "Userpriv";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // Page headings
    public $Heading = "";
    public $Subheading = "";
    public $PageHeader;
    public $PageFooter;

    // Page layout
    public $UseLayout = true;

    // Page terminated
    private $terminated = false;

    // Page heading
    public function pageHeading()
    {
        global $Language;
        if ($this->Heading != "") {
            return $this->Heading;
        }
        if (method_exists($this, "tableCaption")) {
            return $this->tableCaption();
        }
        return "";
    }

    // Page subheading
    public function pageSubheading()
    {
        global $Language;
        if ($this->Subheading != "") {
            return $this->Subheading;
        }
        return "";
    }

    // Page name
    public function pageName()
    {
        return CurrentPageName();
    }

    // Page URL
    public function pageUrl($withArgs = true)
    {
        $route = GetRoute();
        $args = $route->getArguments();
        if (!$withArgs) {
            foreach ($args as $key => &$val) {
                $val = "";
            }
            unset($val);
        }
        $url = rtrim(UrlFor($route->getName(), $args), "/") . "?";
        return $url;
    }

    // Show Page Header
    public function showPageHeader()
    {
        $header = $this->PageHeader;
        $this->pageDataRendering($header);
        if ($header != "") { // Header exists, display
            echo '<p id="ew-page-header">' . $header . '</p>';
        }
    }

    // Show Page Footer
    public function showPageFooter()
    {
        $footer = $this->PageFooter;
        $this->pageDataRendered($footer);
        if ($footer != "") { // Footer exists, display
            echo '<p id="ew-page-footer">' . $footer . '</p>';
        }
    }

    // Validate page request
    protected function isPageRequest()
    {
        return true;
    }

    // Constructor
    public function __construct()
    {
        global $Language, $DashboardReport, $DebugTimer;
        global $UserTable;

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("language");

        // Parent constuctor
        parent::__construct();

        // Table object (userlevels)
        if (!isset($GLOBALS["userlevels"]) || get_class($GLOBALS["userlevels"]) == PROJECT_NAMESPACE . "userlevels") {
            $GLOBALS["userlevels"] = &$this;
        }

        // Start timer
        $DebugTimer = Container("timer");

        // Debug message
        LoadDebugMessage();

        // Open connection
        $GLOBALS["Conn"] = $GLOBALS["Conn"] ?? $this->getConnection();

        // User table object
        $UserTable = Container("usertable");
    }

    // Get content from stream
    public function getContents($stream = null): string
    {
        global $Response;
        return is_object($Response) ? $Response->getBody() : ob_get_clean();
    }

    // Is lookup
    public function isLookup()
    {
        return SameText(Route(0), Config("API_LOOKUP_ACTION"));
    }

    // Is AutoFill
    public function isAutoFill()
    {
        return $this->isLookup() && SameText(Post("ajax"), "autofill");
    }

    // Is AutoSuggest
    public function isAutoSuggest()
    {
        return $this->isLookup() && SameText(Post("ajax"), "autosuggest");
    }

    // Is modal lookup
    public function isModalLookup()
    {
        return $this->isLookup() && SameText(Post("ajax"), "modal");
    }

    // Is terminated
    public function isTerminated()
    {
        return $this->terminated;
    }

    /**
     * Terminate page
     *
     * @param string $url URL for direction
     * @return void
     */
    public function terminate($url = "")
    {
        if ($this->terminated) {
            return;
        }
        global $ExportFileName, $TempImages, $DashboardReport, $Response;

        // Page is terminated
        $this->terminated = true;

         // Page Unload event
        if (method_exists($this, "pageUnload")) {
            $this->pageUnload();
        }

        // Global Page Unloaded event (in userfn*.php)
        Page_Unloaded();

        // Export
        if (!IsApi() && method_exists($this, "pageRedirecting")) {
            $this->pageRedirecting($url);
        }

        // Close connection
        CloseConnections();

        // Return for API
        if (IsApi()) {
            $res = $url === true;
            if (!$res) { // Show error
                WriteJson(array_merge(["success" => false], $this->getMessages()));
            }
            return;
        } else { // Check if response is JSON
            if (StartsString("application/json", $Response->getHeaderLine("Content-type")) && $Response->getBody()->getSize()) { // With JSON response
                $this->clearMessages();
                return;
            }
        }

        // Go to URL if specified
        if ($url != "") {
            if (!Config("DEBUG") && ob_get_length()) {
                ob_end_clean();
            }
            SaveDebugMessage();
            Redirect(GetUrl($url));
        }
        return; // Return to controller
    }
    public $Disabled;
    public $TableNameCount;
    public $Privileges = [];
    public $UserLevelList = [];
    public $UserLevelPrivList = [];
    public $TableList = [];

    /**
     * Page run
     *
     * @return void
     */
    public function run()
    {
        global $ExportType, $CustomExportType, $ExportFileName, $UserProfile, $Language, $Security, $CurrentForm,
            $Breadcrumb;

// Use layout
        $this->UseLayout = $this->UseLayout && ConvertToBool(Param("layout", true));
        $this->CurrentAction = Param("action"); // Set up current action

		// Call this new function from userfn*.php file
		My_Global_Check(); // Modified by Masino Sinaga, October 6, 2021

        // Global Page Loading event (in userfn*.php)
        Page_Loading();

        // Page Load event
        if (method_exists($this, "pageLoad")) {
            $this->pageLoad();
        }
		if (MS_ALWAYS_COMPARE_ROOT_URL == TRUE) {
			if (isset($_SESSION['civichub2_Root_URL'])) {
				if ($_SESSION['civichub2_Root_URL'] == MS_OTHER_COMPARED_ROOT_URL && $_SESSION['civichub2_Root_URL'] <> "") {
					$this->setFailureMessage(str_replace("%s", MS_OTHER_COMPARED_ROOT_URL, Container("language")->phrase("NoPermission")));
					header("Location: " . $_SESSION['civichub2_Root_URL']);
				}
			}
		}
        $Breadcrumb = new Breadcrumb("index");
        $Breadcrumb->add("list", "userlevels", "userlevelslist", "", "userlevels");
        $Breadcrumb->add("userpriv", "UserLevelPermission", CurrentUrl());
        $this->Heading = $Language->phrase("UserLevelPermission");

        // Load user level settings
        $this->UserLevelList = $GLOBALS["USER_LEVELS"];
        $this->UserLevelPrivList = $GLOBALS["USER_LEVEL_PRIVS"];
        $ar = $GLOBALS["USER_LEVEL_TABLES"];

        // Set up allowed table list
        foreach ($ar as $t) {
            if ($t[3]) { // Allowed
                $tempPriv = $Security->getUserLevelPrivEx($t[4] . $t[0], $Security->CurrentUserLevelID);
                if (($tempPriv & ALLOW_ADMIN) == ALLOW_ADMIN) { // Allow Admin
                    $this->TableList[] = array_merge($t, [$tempPriv]);
                }
            }
        }
        $this->TableNameCount = count($this->TableList);

        // Get action
        if (Post("action") == "") {
            $this->CurrentAction = "show"; // Display with input box
            // Load key from QueryString
            if (Get("User_Level_ID") !== null) {
                $this->User_Level_ID->setQueryStringValue(Get("User_Level_ID"));
            } else {
                $this->terminate("userlevelslist"); // Return to list
                return;
            }
            if ($this->User_Level_ID->QueryStringValue == "-1") {
                $this->Disabled = " disabled";
            } else {
                $this->Disabled = "";
            }
        } else {
            $this->CurrentAction = Post("action");
            // Get fields from form
            $this->User_Level_ID->setFormValue(Post("x_User_Level_ID"));
            for ($i = 0; $i < $this->TableNameCount; $i++) {
                if (Post("table_" . $i) !== null) {
                    $this->Privileges[$i] = (int)Post("add_" . $i) +
                        (int)Post("delete_" . $i) + (int)Post("edit_" . $i) +
                        (int)Post("list_" . $i) + (int)Post("view_" . $i) +
                        (int)Post("search_" . $i) + (int)Post("admin_" . $i) +
                        (int)Post("import_" . $i) + (int)Post("lookup_" . $i);
                }
            }
        }

        // Should not edit own permissions
        if ($Security->hasUserLevelID($this->User_Level_ID->CurrentValue)) {
            $this->terminate("userlevelslist"); // Return to list
            return;
        }
        switch ($this->CurrentAction) {
            case "show": // Display
                if (!$Security->setupUserLevelEx()) { // Get all User Level info
                    $this->terminate("userlevelslist"); // Return to list
                    return;
                }
                $ar = [];
                for ($i = 0; $i < $this->TableNameCount; $i++) {
                    $table = $this->TableList[$i];
                    $cnt = count($table);
                    $tempPriv = $Security->getUserLevelPrivEx($table[4] . $table[0], $this->User_Level_ID->CurrentValue);
                    $ar[] = ["table" => ConvertToUtf8($this->getTableCaption($i)), "index" => $i, "permission" => $tempPriv, "allowed" => $table[$cnt - 1]];
                }
                $this->Privileges["disabled"] = $this->Disabled;
                $this->Privileges["permissions"] = $ar;
                $this->Privileges["add"] = 1; // Add
                $this->Privileges["delete"] = 2; // Delete
                $this->Privileges["edit"] = 4; // Edit
                $this->Privileges["list"] = 8; // List
                $this->Privileges["report"] = 8; // Report
                $this->Privileges["admin"] = 16; // Admin
                $this->Privileges["view"] = 32; // View
                $this->Privileges["search"] = 64; // Search
                $this->Privileges["import"] = 128; // Import
                $this->Privileges["lookup"] = 256; // Lookup
                break;
            case "update": // Update
                if ($this->editRow()) { // Update record based on key
                    if ($this->getSuccessMessage() == "") {
                        $this->setSuccessMessage($Language->phrase("UpdateSuccess")); // Set up update success message
                    }
                    // Alternatively, comment out the following line to go back to this page
                    $this->terminate("userlevelslist"); // Return to list
                    return;
                }
        }

        // Set LoginStatus / Page_Rendering / Page_Render
        if (!IsApi() && !$this->isTerminated()) {
            // Setup login status
            SetupLoginStatus();

            // Pass login status to client side
            SetClientVar("login", LoginStatus());

            // Global Page Rendering event (in userfn*.php)
            Page_Rendering();

            // Page Render event
            if (method_exists($this, "pageRender")) {
                $this->pageRender();
            }

            // Render search option
            if (method_exists($this, "renderSearchOptions")) {
                $this->renderSearchOptions();
            }
        }
    }

    // Update privileges
    protected function editRow()
    {
        global $Security;
        $c = Conn(Config("USER_LEVEL_PRIV_DBID"));
        foreach ($this->Privileges as $i => $privilege) {
            $table = $this->TableList[$i];
            $cnt = count($table);
            $sql = "SELECT COUNT(*) FROM " . Config("USER_LEVEL_PRIV_TABLE") . " WHERE " .
                Config("USER_LEVEL_PRIV_TABLE_NAME_FIELD") . " = '" . AdjustSql($table[4] . $table[0], Config("USER_LEVEL_PRIV_DBID")) . "' AND " .
                Config("USER_LEVEL_PRIV_USER_LEVEL_ID_FIELD") . " = " . $this->User_Level_ID->CurrentValue;
            $privilege = $privilege & $table[$cnt - 1]; // Set maximum allowed privilege (protect from hacking)
            $rs = $c->fetchOne($sql);
            if ($rs > 0) {
                $sql = "UPDATE " . Config("USER_LEVEL_PRIV_TABLE") . " SET " . Config("USER_LEVEL_PRIV_PRIV_FIELD") . " = " . $privilege . " WHERE " .
                    Config("USER_LEVEL_PRIV_TABLE_NAME_FIELD") . " = '" . AdjustSql($table[4] . $table[0], Config("USER_LEVEL_PRIV_DBID")) . "' AND " .
                    Config("USER_LEVEL_PRIV_USER_LEVEL_ID_FIELD") . " = " . $this->User_Level_ID->CurrentValue;
                $c->executeStatement($sql);
            } else {
                $sql = "INSERT INTO " . Config("USER_LEVEL_PRIV_TABLE") . " (" . Config("USER_LEVEL_PRIV_TABLE_NAME_FIELD") . ", " . Config("USER_LEVEL_PRIV_USER_LEVEL_ID_FIELD") . ", " . Config("USER_LEVEL_PRIV_PRIV_FIELD") . ") VALUES ('" . AdjustSql($table[4] . $table[0], Config("USER_LEVEL_PRIV_DBID")) . "', " . $this->User_Level_ID->CurrentValue . ", " . $privilege . ")";
                $c->executeStatement($sql);
            }
        }
        $Security->setupUserLevel();
        return true;
    }

    // Get table caption
    protected function getTableCaption($i)
    {
        global $Language;
        $caption = "";
        if ($i < $this->TableNameCount) {
            $caption = $Language->TablePhrase($this->TableList[$i][1], "TblCaption");
            if ($caption == "") {
                $caption = $this->TableList[$i][2];
            }
            if ($caption == "") {
                $caption = $this->TableList[$i][0];
                $caption = preg_replace('/^\{\w{8}-\w{4}-\w{4}-\w{4}-\w{12}\}/', '', $caption); // Remove project id
            }
        }
        return $caption;
    }

    // Page Load event
    public function pageLoad()
    {
        //Log("Page Load");
    }

    // Page Unload event
    public function pageUnload()
    {
        //Log("Page Unload");
    }

    // Page Redirecting event
    public function pageRedirecting(&$url)
    {
        // Example:
        //$url = "your URL";
    }

    // Message Showing event
    // $type = ''|'success'|'failure'
    public function messageShowing(&$msg, $type)
    {
        // Example:
        //if ($type == 'success') $msg = "your success message";
    }

    // Page Render event
    public function pageRender()
    {
        //Log("Page Render");
    }

    // Page Data Rendering event
    public function pageDataRendering(&$header)
    {
        // Example:
        //$header = "your header";
    }

    // Page Data Rendered event
    public function pageDataRendered(&$footer)
    {
        // Example:
        //$footer = "your footer";
    }
}
