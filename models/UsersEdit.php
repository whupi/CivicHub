<?php

namespace PHPMaker2022\civichub2;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class UsersEdit extends Users
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'users';

    // Page object name
    public $PageObjName = "UsersEdit";

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
        if ($this->TableName) {
            return $Language->phrase($this->PageID);
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
        if ($this->UseTokenInUrl) {
            $url .= "t=" . $this->TableVar . "&"; // Add page token
        }
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
        global $CurrentForm;
        if ($this->UseTokenInUrl) {
            if ($CurrentForm) {
                return $this->TableVar == $CurrentForm->getValue("t");
            }
            if (Get("t") !== null) {
                return $this->TableVar == Get("t");
            }
        }
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

        // Table object (users)
        if (!isset($GLOBALS["users"]) || get_class($GLOBALS["users"]) == PROJECT_NAMESPACE . "users") {
            $GLOBALS["users"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'users');
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
        if ($this->CustomExport && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, Config("EXPORT_CLASSES"))) {
            $content = $this->getContents();
            if ($ExportFileName == "") {
                $ExportFileName = $this->TableVar;
            }
            $class = PROJECT_NAMESPACE . Config("EXPORT_CLASSES." . $this->CustomExport);
            if (class_exists($class)) {
                $tbl = Container("users");
                $doc = new $class($tbl);
                $doc->Text = @$content;
                if ($this->isExport("email")) {
                    echo $this->exportEmail($doc->Text);
                } else {
                    $doc->export();
                }
                DeleteTempImages(); // Delete temp images
                return;
            }
        }
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

            // Handle modal response
            if ($this->IsModal) { // Show as modal
                $row = ["url" => GetUrl($url), "modal" => "1"];
                $pageName = GetPageName($url);
                if ($pageName != $this->getListUrl()) { // Not List page
                    $row["caption"] = $this->getModalCaption($pageName);
                    if ($pageName == "usersview") {
                        $row["view"] = "1";
                    }
                } else { // List page should not be shown as modal => error
                    $row["error"] = $this->getFailureMessage();
                    $this->clearFailureMessage();
                }
                WriteJson($row);
            } else {
                SaveDebugMessage();
                Redirect(GetUrl($url));
            }
        }
        return; // Return to controller
    }

    // Get records from recordset
    protected function getRecordsFromRecordset($rs, $current = false)
    {
        $rows = [];
        if (is_object($rs)) { // Recordset
            while ($rs && !$rs->EOF) {
                $this->loadRowValues($rs); // Set up DbValue/CurrentValue
                $row = $this->getRecordFromArray($rs->fields);
                if ($current) {
                    return $row;
                } else {
                    $rows[] = $row;
                }
                $rs->moveNext();
            }
        } elseif (is_array($rs)) {
            foreach ($rs as $ar) {
                $row = $this->getRecordFromArray($ar);
                if ($current) {
                    return $row;
                } else {
                    $rows[] = $row;
                }
            }
        }
        return $rows;
    }

    // Get record from array
    protected function getRecordFromArray($ar)
    {
        $row = [];
        if (is_array($ar)) {
            foreach ($ar as $fldname => $val) {
                if (array_key_exists($fldname, $this->Fields) && ($this->Fields[$fldname]->Visible || $this->Fields[$fldname]->IsPrimaryKey)) { // Primary key or Visible
                    $fld = &$this->Fields[$fldname];
                    if ($fld->HtmlTag == "FILE") { // Upload field
                        if (EmptyValue($val)) {
                            $row[$fldname] = null;
                        } else {
                            if ($fld->DataType == DATATYPE_BLOB) {
                                $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                    "/" . $fld->TableVar . "/" . $fld->Param . "/" . rawurlencode($this->getRecordKeyValue($ar))));
                                $row[$fldname] = ["type" => ContentType($val), "url" => $url, "name" => $fld->Param . ContentExtension($val)];
                            } elseif (!$fld->UploadMultiple || !ContainsString($val, Config("MULTIPLE_UPLOAD_SEPARATOR"))) { // Single file
                                $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                    "/" . $fld->TableVar . "/" . Encrypt($fld->physicalUploadPath() . $val)));
                                $row[$fldname] = ["type" => MimeContentType($val), "url" => $url, "name" => $val];
                            } else { // Multiple files
                                $files = explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $val);
                                $ar = [];
                                foreach ($files as $file) {
                                    $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                        "/" . $fld->TableVar . "/" . Encrypt($fld->physicalUploadPath() . $file)));
                                    if (!EmptyValue($file)) {
                                        $ar[] = ["type" => MimeContentType($file), "url" => $url, "name" => $file];
                                    }
                                }
                                $row[$fldname] = $ar;
                            }
                        }
                    } else {
                        $row[$fldname] = $val;
                    }
                }
            }
        }
        return $row;
    }

    // Get record key value from array
    protected function getRecordKeyValue($ar)
    {
        $key = "";
        if (is_array($ar)) {
            $key .= @$ar['Username'];
        }
        return $key;
    }

    /**
     * Hide fields for add/edit
     *
     * @return void
     */
    protected function hideFieldsForAddEdit()
    {
    }

    // Lookup data
    public function lookup($ar = null)
    {
        global $Language, $Security;

        // Get lookup object
        $fieldName = $ar["field"] ?? Post("field");
        $lookup = $this->Fields[$fieldName]->Lookup;

        // Get lookup parameters
        $lookupType = $ar["ajax"] ?? Post("ajax", "unknown");
        $pageSize = -1;
        $offset = -1;
        $searchValue = "";
        if (SameText($lookupType, "modal") || SameText($lookupType, "filter")) {
            $searchValue = $ar["q"] ?? Param("q") ?? $ar["sv"] ?? Post("sv", "");
            $pageSize = $ar["n"] ?? Param("n") ?? $ar["recperpage"] ?? Post("recperpage", 10);
        } elseif (SameText($lookupType, "autosuggest")) {
            $searchValue = $ar["q"] ?? Param("q", "");
            $pageSize = $ar["n"] ?? Param("n", -1);
            $pageSize = is_numeric($pageSize) ? (int)$pageSize : -1;
            if ($pageSize <= 0) {
                $pageSize = Config("AUTO_SUGGEST_MAX_ENTRIES");
            }
        }
        $start = $ar["start"] ?? Param("start", -1);
        $start = is_numeric($start) ? (int)$start : -1;
        $page = $ar["page"] ?? Param("page", -1);
        $page = is_numeric($page) ? (int)$page : -1;
        $offset = $start >= 0 ? $start : ($page > 0 && $pageSize > 0 ? ($page - 1) * $pageSize : 0);
        $userSelect = Decrypt($ar["s"] ?? Post("s", ""));
        $userFilter = Decrypt($ar["f"] ?? Post("f", ""));
        $userOrderBy = Decrypt($ar["o"] ?? Post("o", ""));
        $keys = $ar["keys"] ?? Post("keys");
        $lookup->LookupType = $lookupType; // Lookup type
        $lookup->FilterValues = []; // Clear filter values first
        if ($keys !== null) { // Selected records from modal
            if (is_array($keys)) {
                $keys = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $keys);
            }
            $lookup->FilterFields = []; // Skip parent fields if any
            $lookup->FilterValues[] = $keys; // Lookup values
            $pageSize = -1; // Show all records
        } else { // Lookup values
            $lookup->FilterValues[] = $ar["v0"] ?? $ar["lookupValue"] ?? Post("v0", Post("lookupValue", ""));
        }
        $cnt = is_array($lookup->FilterFields) ? count($lookup->FilterFields) : 0;
        for ($i = 1; $i <= $cnt; $i++) {
            $lookup->FilterValues[] = $ar["v" . $i] ?? Post("v" . $i, "");
        }
        $lookup->SearchValue = $searchValue;
        $lookup->PageSize = $pageSize;
        $lookup->Offset = $offset;
        if ($userSelect != "") {
            $lookup->UserSelect = $userSelect;
        }
        if ($userFilter != "") {
            $lookup->UserFilter = $userFilter;
        }
        if ($userOrderBy != "") {
            $lookup->UserOrderBy = $userOrderBy;
        }
        return $lookup->toJson($this, !is_array($ar)); // Use settings from current page
    }

    // Properties
    public $FormClassName = "ew-form ew-edit-form";
    public $IsModal = false;
    public $IsMobileOrModal = false;
    public $DbMasterFilter;
    public $DbDetailFilter;
    public $HashValue; // Hash Value
    public $DisplayRecords = 1;
    public $StartRecord;
    public $StopRecord;
    public $TotalRecords = 0;
    public $RecordRange = 10;
    public $RecordCount;

    /**
     * Page run
     *
     * @return void
     */
    public function run()
    {
        global $ExportType, $CustomExportType, $ExportFileName, $UserProfile, $Language, $Security, $CurrentForm,
            $SkipHeaderFooter;

// Is modal
        $this->IsModal = Param("modal") == "1";
        $this->UseLayout = $this->UseLayout && !$this->IsModal;

        // Use layout
        $this->UseLayout = $this->UseLayout && ConvertToBool(Param("layout", true));

        // Create form object
        $CurrentForm = new HttpForm();
        $this->CurrentAction = Param("action"); // Set up current action
        $this->_Username->setVisibility();
        $this->_Password->setVisibility();
        $this->First_Name->setVisibility();
        $this->Last_Name->setVisibility();
        $this->_Email->setVisibility();
        $this->User_Level->setVisibility();
        $this->Report_To->setVisibility();
        $this->Activated->setVisibility();
        $this->Locked->setVisibility();
        $this->_Profile->Visible = false;
        $this->Photo->setVisibility();
        $this->hideFieldsForAddEdit();

        // Set lookup cache
        if (!in_array($this->PageID, Config("LOOKUP_CACHE_PAGE_IDS"))) {
            $this->setUseLookupCache(false);
        }

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

        // Set up lookup cache
        $this->setupLookupOptions($this->User_Level);
        $this->setupLookupOptions($this->Report_To);
        $this->setupLookupOptions($this->Activated);
        $this->setupLookupOptions($this->Locked);

        // Check modal
        if ($this->IsModal) {
            $SkipHeaderFooter = true;
        }
        $this->IsMobileOrModal = IsMobile() || $this->IsModal;
        $this->FormClassName = "ew-form ew-edit-form";
        $loaded = false;
        $postBack = false;

        // Set up current action and primary key
        if (IsApi()) {
            // Load key values
            $loaded = true;
            if (($keyValue = Get("_Username") ?? Key(0) ?? Route(2)) !== null) {
                $this->_Username->setQueryStringValue($keyValue);
                $this->_Username->setOldValue($this->_Username->QueryStringValue);
            } elseif (Post("_Username") !== null) {
                $this->_Username->setFormValue(Post("_Username"));
                $this->_Username->setOldValue($this->_Username->FormValue);
            } else {
                $loaded = false; // Unable to load key
            }

            // Load record
            if ($loaded) {
                $loaded = $this->loadRow();
            }
            if (!$loaded) {
                $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
                $this->terminate();
                return;
            }
            $this->CurrentAction = "update"; // Update record directly
            $this->OldKey = $this->getKey(true); // Get from CurrentValue
            $postBack = true;
        } else {
            if (Post("action") !== null) {
                $this->CurrentAction = Post("action"); // Get action code
                if (!$this->isShow()) { // Not reload record, handle as postback
                    $postBack = true;
                }

                // Get key from Form
                $this->setKey(Post($this->OldKeyName), $this->isShow());
            } else {
                $this->CurrentAction = "show"; // Default action is display

                // Load key from QueryString
                $loadByQuery = false;
                if (($keyValue = Get("_Username") ?? Route("_Username")) !== null) {
                    $this->_Username->setQueryStringValue($keyValue);
                    $loadByQuery = true;
                } else {
                    $this->_Username->CurrentValue = null;
                }
            }

            // Set up master detail parameters
            $this->setupMasterParms();

            // Load recordset
            if ($this->isShow()) {
                    // Load current record
                    $loaded = $this->loadRow();
                $this->OldKey = $loaded ? $this->getKey(true) : ""; // Get from CurrentValue
            }
        }

        // Process form if post back
        if ($postBack) {
            $this->loadFormValues(); // Get form values
        }

        // Validate form if post back
        if ($postBack) {
            if (!$this->validateForm()) {
                $this->EventCancelled = true; // Event cancelled
                $this->restoreFormValues();
                if (IsApi()) {
                    $this->terminate();
                    return;
                } else {
                    $this->CurrentAction = ""; // Form error, reset action
                }
            }
        }

        // Perform current action
        switch ($this->CurrentAction) {
            case "show": // Get a record to display
                    if (!$loaded) { // Load record based on key
                        if ($this->getFailureMessage() == "") {
                            $this->setFailureMessage($Language->phrase("NoRecord")); // No record found
                        }
                        $this->terminate("userslist"); // No matching record, return to list
                        return;
                    }
                break;
            case "update": // Update
                $returnUrl = $this->getReturnUrl();
                if (GetPageName($returnUrl) == "userslist") {
                    $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                }
                $this->SendEmail = true; // Send email on update success
                if ($this->editRow()) { // Update record based on key
                    if ($this->getSuccessMessage() == "") {
                        $this->setSuccessMessage($Language->phrase("UpdateSuccess")); // Update success
                    }
                    if (IsApi()) {
                        $this->terminate(true);
                        return;
                    } else {
                        $this->terminate($returnUrl); // Return to caller
                        return;
                    }
                } elseif (IsApi()) { // API request, return
                    $this->terminate();
                    return;
                } elseif ($this->getFailureMessage() == $Language->phrase("NoRecord")) {
                    $this->terminate($returnUrl); // Return to caller
                    return;
                } else {
                    $this->EventCancelled = true; // Event cancelled
                    $this->restoreFormValues(); // Restore form values if update failed
                }
        }

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Render the record
        $this->RowType = ROWTYPE_EDIT; // Render as Edit
        $this->resetAttributes();
        $this->renderRow();

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

// Get upload files
    protected function getUploadFiles()
    {
        global $CurrentForm, $Language;
        $this->Photo->Upload->Index = $CurrentForm->Index;
        $this->Photo->Upload->uploadFile();
        $this->Photo->CurrentValue = $this->Photo->Upload->FileName;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $validate = !Config("SERVER_VALIDATE");

        // Check field name 'Username' first before field var 'x__Username'
        $val = $CurrentForm->hasValue("Username") ? $CurrentForm->getValue("Username") : $CurrentForm->getValue("x__Username");
        if (!$this->_Username->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->_Username->Visible = false; // Disable update for API request
            } else {
                $this->_Username->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o__Username")) {
            $this->_Username->setOldValue($CurrentForm->getValue("o__Username"));
        }

        // Check field name 'Password' first before field var 'x__Password'
        $val = $CurrentForm->hasValue("Password") ? $CurrentForm->getValue("Password") : $CurrentForm->getValue("x__Password");
        if (!$this->_Password->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->_Password->Visible = false; // Disable update for API request
            } else {
                $this->_Password->setFormValue($val);
            }
        }

        // Check field name 'First_Name' first before field var 'x_First_Name'
        $val = $CurrentForm->hasValue("First_Name") ? $CurrentForm->getValue("First_Name") : $CurrentForm->getValue("x_First_Name");
        if (!$this->First_Name->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->First_Name->Visible = false; // Disable update for API request
            } else {
                $this->First_Name->setFormValue($val);
            }
        }

        // Check field name 'Last_Name' first before field var 'x_Last_Name'
        $val = $CurrentForm->hasValue("Last_Name") ? $CurrentForm->getValue("Last_Name") : $CurrentForm->getValue("x_Last_Name");
        if (!$this->Last_Name->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Last_Name->Visible = false; // Disable update for API request
            } else {
                $this->Last_Name->setFormValue($val);
            }
        }

        // Check field name 'Email' first before field var 'x__Email'
        $val = $CurrentForm->hasValue("Email") ? $CurrentForm->getValue("Email") : $CurrentForm->getValue("x__Email");
        if (!$this->_Email->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->_Email->Visible = false; // Disable update for API request
            } else {
                $this->_Email->setFormValue($val);
            }
        }

        // Check field name 'User_Level' first before field var 'x_User_Level'
        $val = $CurrentForm->hasValue("User_Level") ? $CurrentForm->getValue("User_Level") : $CurrentForm->getValue("x_User_Level");
        if (!$this->User_Level->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->User_Level->Visible = false; // Disable update for API request
            } else {
                $this->User_Level->setFormValue($val);
            }
        }

        // Check field name 'Report_To' first before field var 'x_Report_To'
        $val = $CurrentForm->hasValue("Report_To") ? $CurrentForm->getValue("Report_To") : $CurrentForm->getValue("x_Report_To");
        if (!$this->Report_To->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Report_To->Visible = false; // Disable update for API request
            } else {
                $this->Report_To->setFormValue($val);
            }
        }

        // Check field name 'Activated' first before field var 'x_Activated'
        $val = $CurrentForm->hasValue("Activated") ? $CurrentForm->getValue("Activated") : $CurrentForm->getValue("x_Activated");
        if (!$this->Activated->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Activated->Visible = false; // Disable update for API request
            } else {
                $this->Activated->setFormValue($val);
            }
        }

        // Check field name 'Locked' first before field var 'x_Locked'
        $val = $CurrentForm->hasValue("Locked") ? $CurrentForm->getValue("Locked") : $CurrentForm->getValue("x_Locked");
        if (!$this->Locked->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Locked->Visible = false; // Disable update for API request
            } else {
                $this->Locked->setFormValue($val);
            }
        }
        $this->getUploadFiles(); // Get upload files
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->_Username->CurrentValue = $this->_Username->FormValue;
        $this->_Password->CurrentValue = $this->_Password->FormValue;
        $this->First_Name->CurrentValue = $this->First_Name->FormValue;
        $this->Last_Name->CurrentValue = $this->Last_Name->FormValue;
        $this->_Email->CurrentValue = $this->_Email->FormValue;
        $this->User_Level->CurrentValue = $this->User_Level->FormValue;
        $this->Report_To->CurrentValue = $this->Report_To->FormValue;
        $this->Activated->CurrentValue = $this->Activated->FormValue;
        $this->Locked->CurrentValue = $this->Locked->FormValue;
    }

    /**
     * Load row based on key values
     *
     * @return void
     */
    public function loadRow()
    {
        global $Security, $Language;
        $filter = $this->getRecordFilter();

        // Call Row Selecting event
        $this->rowSelecting($filter);

        // Load SQL based on filter
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        $res = false;
        $row = $conn->fetchAssociative($sql);
        if ($row) {
            $res = true;
            $this->loadRowValues($row); // Load row values
        }

        // Check if valid User ID
        if ($res) {
            $res = $this->showOptionLink("edit");
            if (!$res) {
                $userIdMsg = DeniedMessage();
                $this->setFailureMessage($userIdMsg);
            }
        }
        return $res;
    }

    /**
     * Load row values from recordset or record
     *
     * @param Recordset|array $rs Record
     * @return void
     */
    public function loadRowValues($rs = null)
    {
        if (is_array($rs)) {
            $row = $rs;
        } elseif ($rs && property_exists($rs, "fields")) { // Recordset
            $row = $rs->fields;
        } else {
            $row = $this->newRow();
        }
        if (!$row) {
            return;
        }

        // Call Row Selected event
        $this->rowSelected($row);
        $this->_Username->setDbValue($row['Username']);
        $this->_Password->setDbValue($row['Password']);
        $this->First_Name->setDbValue($row['First_Name']);
        $this->Last_Name->setDbValue($row['Last_Name']);
        $this->_Email->setDbValue($row['Email']);
        $this->User_Level->setDbValue($row['User_Level']);
        $this->Report_To->setDbValue($row['Report_To']);
        $this->Activated->setDbValue($row['Activated']);
        $this->Locked->setDbValue($row['Locked']);
        $this->_Profile->setDbValue($row['Profile']);
        $this->Photo->Upload->DbValue = $row['Photo'];
        $this->Photo->setDbValue($this->Photo->Upload->DbValue);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['Username'] = $this->_Username->DefaultValue;
        $row['Password'] = $this->_Password->DefaultValue;
        $row['First_Name'] = $this->First_Name->DefaultValue;
        $row['Last_Name'] = $this->Last_Name->DefaultValue;
        $row['Email'] = $this->_Email->DefaultValue;
        $row['User_Level'] = $this->User_Level->DefaultValue;
        $row['Report_To'] = $this->Report_To->DefaultValue;
        $row['Activated'] = $this->Activated->DefaultValue;
        $row['Locked'] = $this->Locked->DefaultValue;
        $row['Profile'] = $this->_Profile->DefaultValue;
        $row['Photo'] = $this->Photo->DefaultValue;
        return $row;
    }

    // Load old record
    protected function loadOldRecord()
    {
        // Load old record
        $this->OldRecordset = null;
        $validKey = $this->OldKey != "";
        if ($validKey) {
            $this->CurrentFilter = $this->getRecordFilter();
            $sql = $this->getCurrentSql();
            $conn = $this->getConnection();
            $this->OldRecordset = LoadRecordset($sql, $conn);
        }
        $this->loadRowValues($this->OldRecordset); // Load row values
        return $validKey;
    }

    // Render row values based on field settings
    public function renderRow()
    {
        global $Security, $Language, $CurrentLanguage;

        // Initialize URLs

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // Username
        $this->_Username->RowCssClass = "row";

        // Password
        $this->_Password->RowCssClass = "row";

        // First_Name
        $this->First_Name->RowCssClass = "row";

        // Last_Name
        $this->Last_Name->RowCssClass = "row";

        // Email
        $this->_Email->RowCssClass = "row";

        // User_Level
        $this->User_Level->RowCssClass = "row";

        // Report_To
        $this->Report_To->RowCssClass = "row";

        // Activated
        $this->Activated->RowCssClass = "row";

        // Locked
        $this->Locked->RowCssClass = "row";

        // Profile
        $this->_Profile->RowCssClass = "row";

        // Photo
        $this->Photo->RowCssClass = "row";

        // View row
        if ($this->RowType == ROWTYPE_VIEW) {
            // Username
            $this->_Username->ViewValue = $this->_Username->CurrentValue;
            $this->_Username->ViewCustomAttributes = "";

            // Password
            $this->_Password->ViewValue = $Language->phrase("PasswordMask");
            $this->_Password->ViewCustomAttributes = "";

            // First_Name
            $this->First_Name->ViewValue = $this->First_Name->CurrentValue;
            $this->First_Name->ViewCustomAttributes = "";

            // Last_Name
            $this->Last_Name->ViewValue = $this->Last_Name->CurrentValue;
            $this->Last_Name->ViewCustomAttributes = "";

            // Email
            $this->_Email->ViewValue = $this->_Email->CurrentValue;
            $this->_Email->ViewCustomAttributes = "";

            // User_Level
            if ($Security->canAdmin()) { // System admin
                $curVal = strval($this->User_Level->CurrentValue);
                if ($curVal != "") {
                    $this->User_Level->ViewValue = $this->User_Level->lookupCacheOption($curVal);
                    if ($this->User_Level->ViewValue === null) { // Lookup from database
                        $filterWrk = "`User_Level_ID`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                        $sqlWrk = $this->User_Level->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                        $conn = Conn();
                        $config = $conn->getConfiguration();
                        $config->setResultCacheImpl($this->Cache);
                        $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                        $ari = count($rswrk);
                        if ($ari > 0) { // Lookup values found
                            $arwrk = $this->User_Level->Lookup->renderViewRow($rswrk[0]);
                            $this->User_Level->ViewValue = $this->User_Level->displayValue($arwrk);
                        } else {
                            $this->User_Level->ViewValue = FormatNumber($this->User_Level->CurrentValue, $this->User_Level->formatPattern());
                        }
                    }
                } else {
                    $this->User_Level->ViewValue = null;
                }
            } else {
                $this->User_Level->ViewValue = $Language->phrase("PasswordMask");
            }
            $this->User_Level->ViewCustomAttributes = "";

            // Report_To
            $curVal = strval($this->Report_To->CurrentValue);
            if ($curVal != "") {
                $this->Report_To->ViewValue = $this->Report_To->lookupCacheOption($curVal);
                if ($this->Report_To->ViewValue === null) { // Lookup from database
                    $filterWrk = "`Username`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                    $sqlWrk = $this->Report_To->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->Report_To->Lookup->renderViewRow($rswrk[0]);
                        $this->Report_To->ViewValue = $this->Report_To->displayValue($arwrk);
                    } else {
                        $this->Report_To->ViewValue = $this->Report_To->CurrentValue;
                    }
                }
            } else {
                $this->Report_To->ViewValue = null;
            }
            $this->Report_To->ViewCustomAttributes = "";

            // Activated
            if (ConvertToBool($this->Activated->CurrentValue)) {
                $this->Activated->ViewValue = $this->Activated->tagCaption(2) != "" ? $this->Activated->tagCaption(2) : "Y";
            } else {
                $this->Activated->ViewValue = $this->Activated->tagCaption(1) != "" ? $this->Activated->tagCaption(1) : "N";
            }
            $this->Activated->ViewCustomAttributes = "";

            // Locked
            if (ConvertToBool($this->Locked->CurrentValue)) {
                $this->Locked->ViewValue = $this->Locked->tagCaption(1) != "" ? $this->Locked->tagCaption(1) : "Y";
            } else {
                $this->Locked->ViewValue = $this->Locked->tagCaption(2) != "" ? $this->Locked->tagCaption(2) : "N";
            }
            $this->Locked->ViewCustomAttributes = "";

            // Photo
            if (!EmptyValue($this->Photo->Upload->DbValue)) {
                $this->Photo->ViewValue = $this->Photo->Upload->DbValue;
            } else {
                $this->Photo->ViewValue = "";
            }
            $this->Photo->ViewCustomAttributes = "";

            // Username
            $this->_Username->LinkCustomAttributes = "";
            $this->_Username->HrefValue = "";

            // Password
            $this->_Password->LinkCustomAttributes = "";
            $this->_Password->HrefValue = "";

            // First_Name
            $this->First_Name->LinkCustomAttributes = "";
            $this->First_Name->HrefValue = "";

            // Last_Name
            $this->Last_Name->LinkCustomAttributes = "";
            $this->Last_Name->HrefValue = "";

            // Email
            $this->_Email->LinkCustomAttributes = "";
            $this->_Email->HrefValue = "";

            // User_Level
            $this->User_Level->LinkCustomAttributes = "";
            $this->User_Level->HrefValue = "";

            // Report_To
            $this->Report_To->LinkCustomAttributes = "";
            $this->Report_To->HrefValue = "";

            // Activated
            $this->Activated->LinkCustomAttributes = "";
            $this->Activated->HrefValue = "";

            // Locked
            $this->Locked->LinkCustomAttributes = "";
            $this->Locked->HrefValue = "";

            // Photo
            $this->Photo->LinkCustomAttributes = "";
            $this->Photo->HrefValue = "";
            $this->Photo->ExportHrefValue = $this->Photo->UploadPath . $this->Photo->Upload->DbValue;
        } elseif ($this->RowType == ROWTYPE_EDIT) {
            // Username
            $this->_Username->setupEditAttributes();
            $this->_Username->EditCustomAttributes = "";
            if (!$this->_Username->Raw) {
                $this->_Username->CurrentValue = HtmlDecode($this->_Username->CurrentValue);
            }
            $this->_Username->EditValue = HtmlEncode($this->_Username->CurrentValue);
            $this->_Username->PlaceHolder = RemoveHtml($this->_Username->caption());

            // Password
            $this->_Password->setupEditAttributes();
            $this->_Password->EditCustomAttributes = "";
            $this->_Password->EditValue = $Language->phrase("PasswordMask"); // Show as masked password
            $this->_Password->PlaceHolder = RemoveHtml($this->_Password->caption());

            // First_Name
            $this->First_Name->setupEditAttributes();
            $this->First_Name->EditCustomAttributes = "";
            if (!$this->First_Name->Raw) {
                $this->First_Name->CurrentValue = HtmlDecode($this->First_Name->CurrentValue);
            }
            $this->First_Name->EditValue = HtmlEncode($this->First_Name->CurrentValue);
            $this->First_Name->PlaceHolder = RemoveHtml($this->First_Name->caption());

            // Last_Name
            $this->Last_Name->setupEditAttributes();
            $this->Last_Name->EditCustomAttributes = "";
            if (!$this->Last_Name->Raw) {
                $this->Last_Name->CurrentValue = HtmlDecode($this->Last_Name->CurrentValue);
            }
            $this->Last_Name->EditValue = HtmlEncode($this->Last_Name->CurrentValue);
            $this->Last_Name->PlaceHolder = RemoveHtml($this->Last_Name->caption());

            // Email
            $this->_Email->setupEditAttributes();
            $this->_Email->EditCustomAttributes = "";
            if (!$this->_Email->Raw) {
                $this->_Email->CurrentValue = HtmlDecode($this->_Email->CurrentValue);
            }
            $this->_Email->EditValue = HtmlEncode($this->_Email->CurrentValue);
            $this->_Email->PlaceHolder = RemoveHtml($this->_Email->caption());

            // User_Level
            $this->User_Level->setupEditAttributes();
            $this->User_Level->EditCustomAttributes = "";
            if ($this->User_Level->getSessionValue() != "") {
                $this->User_Level->CurrentValue = GetForeignKeyValue($this->User_Level->getSessionValue());
                if ($Security->canAdmin()) { // System admin
                    $curVal = strval($this->User_Level->CurrentValue);
                    if ($curVal != "") {
                        $this->User_Level->ViewValue = $this->User_Level->lookupCacheOption($curVal);
                        if ($this->User_Level->ViewValue === null) { // Lookup from database
                            $filterWrk = "`User_Level_ID`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                            $sqlWrk = $this->User_Level->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                            $conn = Conn();
                            $config = $conn->getConfiguration();
                            $config->setResultCacheImpl($this->Cache);
                            $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                            $ari = count($rswrk);
                            if ($ari > 0) { // Lookup values found
                                $arwrk = $this->User_Level->Lookup->renderViewRow($rswrk[0]);
                                $this->User_Level->ViewValue = $this->User_Level->displayValue($arwrk);
                            } else {
                                $this->User_Level->ViewValue = FormatNumber($this->User_Level->CurrentValue, $this->User_Level->formatPattern());
                            }
                        }
                    } else {
                        $this->User_Level->ViewValue = null;
                    }
                } else {
                    $this->User_Level->ViewValue = $Language->phrase("PasswordMask");
                }
                $this->User_Level->ViewCustomAttributes = "";
            } elseif (!$Security->canAdmin()) { // System admin
                $this->User_Level->EditValue = $Language->phrase("PasswordMask");
            } else {
                $curVal = trim(strval($this->User_Level->CurrentValue));
                if ($curVal != "") {
                    $this->User_Level->ViewValue = $this->User_Level->lookupCacheOption($curVal);
                } else {
                    $this->User_Level->ViewValue = $this->User_Level->Lookup !== null && is_array($this->User_Level->lookupOptions()) ? $curVal : null;
                }
                if ($this->User_Level->ViewValue !== null) { // Load from cache
                    $this->User_Level->EditValue = array_values($this->User_Level->lookupOptions());
                } else { // Lookup from database
                    if ($curVal == "") {
                        $filterWrk = "0=1";
                    } else {
                        $filterWrk = "`User_Level_ID`" . SearchString("=", $this->User_Level->CurrentValue, DATATYPE_NUMBER, "");
                    }
                    $sqlWrk = $this->User_Level->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    $arwrk = $rswrk;
                    $this->User_Level->EditValue = $arwrk;
                }
                $this->User_Level->PlaceHolder = RemoveHtml($this->User_Level->caption());
            }

            // Report_To
            $this->Report_To->setupEditAttributes();
            $this->Report_To->EditCustomAttributes = "";
            if (!$Security->isAdmin() && $Security->isLoggedIn()) { // Non system admin
                if (SameString($this->_Username->CurrentValue, CurrentUserID())) {
                    $curVal = strval($this->Report_To->CurrentValue);
                    if ($curVal != "") {
                        $this->Report_To->EditValue = $this->Report_To->lookupCacheOption($curVal);
                        if ($this->Report_To->EditValue === null) { // Lookup from database
                            $filterWrk = "`Username`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                            $sqlWrk = $this->Report_To->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                            $conn = Conn();
                            $config = $conn->getConfiguration();
                            $config->setResultCacheImpl($this->Cache);
                            $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                            $ari = count($rswrk);
                            if ($ari > 0) { // Lookup values found
                                $arwrk = $this->Report_To->Lookup->renderViewRow($rswrk[0]);
                                $this->Report_To->EditValue = $this->Report_To->displayValue($arwrk);
                            } else {
                                $this->Report_To->EditValue = $this->Report_To->CurrentValue;
                            }
                        }
                    } else {
                        $this->Report_To->EditValue = null;
                    }
                    $this->Report_To->ViewCustomAttributes = "";
                } else {
                    if (trim(strval($this->Report_To->CurrentValue)) == "") {
                        $filterWrk = "0=1";
                    } else {
                        $filterWrk = "`Username`" . SearchString("=", $this->Report_To->CurrentValue, DATATYPE_STRING, "");
                    }
                    AddFilter($filterWrk, Container("users")->addParentUserIDFilter($this->_Username->CurrentValue));
                    $sqlWrk = $this->Report_To->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll();
                    $arwrk = $rswrk;
                    $this->Report_To->EditValue = $arwrk;
                }
            } else {
                $curVal = trim(strval($this->Report_To->CurrentValue));
                if ($curVal != "") {
                    $this->Report_To->ViewValue = $this->Report_To->lookupCacheOption($curVal);
                } else {
                    $this->Report_To->ViewValue = $this->Report_To->Lookup !== null && is_array($this->Report_To->lookupOptions()) ? $curVal : null;
                }
                if ($this->Report_To->ViewValue !== null) { // Load from cache
                    $this->Report_To->EditValue = array_values($this->Report_To->lookupOptions());
                } else { // Lookup from database
                    if ($curVal == "") {
                        $filterWrk = "0=1";
                    } else {
                        $filterWrk = "`Username`" . SearchString("=", $this->Report_To->CurrentValue, DATATYPE_STRING, "");
                    }
                    $sqlWrk = $this->Report_To->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    $arwrk = $rswrk;
                    $this->Report_To->EditValue = $arwrk;
                }
                $this->Report_To->PlaceHolder = RemoveHtml($this->Report_To->caption());
            }

            // Activated
            $this->Activated->EditCustomAttributes = "";
            $this->Activated->EditValue = $this->Activated->options(false);
            $this->Activated->PlaceHolder = RemoveHtml($this->Activated->caption());

            // Locked
            $this->Locked->EditCustomAttributes = "";
            $this->Locked->EditValue = $this->Locked->options(false);
            $this->Locked->PlaceHolder = RemoveHtml($this->Locked->caption());

            // Photo
            $this->Photo->setupEditAttributes();
            $this->Photo->EditAttrs["accept"] = "PNG,JPEG,JPG";
            $this->Photo->EditCustomAttributes = "";
            if (!EmptyValue($this->Photo->Upload->DbValue)) {
                $this->Photo->EditValue = $this->Photo->Upload->DbValue;
            } else {
                $this->Photo->EditValue = "";
            }
            if (!EmptyValue($this->Photo->CurrentValue)) {
                $this->Photo->Upload->FileName = $this->Photo->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->Photo);
            }

            // Edit refer script

            // Username
            $this->_Username->LinkCustomAttributes = "";
            $this->_Username->HrefValue = "";

            // Password
            $this->_Password->LinkCustomAttributes = "";
            $this->_Password->HrefValue = "";

            // First_Name
            $this->First_Name->LinkCustomAttributes = "";
            $this->First_Name->HrefValue = "";

            // Last_Name
            $this->Last_Name->LinkCustomAttributes = "";
            $this->Last_Name->HrefValue = "";

            // Email
            $this->_Email->LinkCustomAttributes = "";
            $this->_Email->HrefValue = "";

            // User_Level
            $this->User_Level->LinkCustomAttributes = "";
            $this->User_Level->HrefValue = "";

            // Report_To
            $this->Report_To->LinkCustomAttributes = "";
            $this->Report_To->HrefValue = "";

            // Activated
            $this->Activated->LinkCustomAttributes = "";
            $this->Activated->HrefValue = "";

            // Locked
            $this->Locked->LinkCustomAttributes = "";
            $this->Locked->HrefValue = "";

            // Photo
            $this->Photo->LinkCustomAttributes = "";
            $this->Photo->HrefValue = "";
            $this->Photo->ExportHrefValue = $this->Photo->UploadPath . $this->Photo->Upload->DbValue;
        }
        if ($this->RowType == ROWTYPE_ADD || $this->RowType == ROWTYPE_EDIT || $this->RowType == ROWTYPE_SEARCH) { // Add/Edit/Search row
            $this->setupFieldTitles();
        }

        // Call Row Rendered event
        if ($this->RowType != ROWTYPE_AGGREGATEINIT) {
            $this->rowRendered();
        }
    }

    // Validate form
    protected function validateForm()
    {
        global $Language;

        // Check if validation required
        if (!Config("SERVER_VALIDATE")) {
            return true;
        }
        $validateForm = true;
        if ($this->_Username->Required) {
            if (!$this->_Username->IsDetailKey && EmptyValue($this->_Username->FormValue)) {
                $this->_Username->addErrorMessage(str_replace("%s", $this->_Username->caption(), $this->_Username->RequiredErrorMessage));
            }
        }
        if ($this->_Password->Required) {
            if (!$this->_Password->IsDetailKey && EmptyValue($this->_Password->FormValue)) {
                $this->_Password->addErrorMessage(str_replace("%s", $this->_Password->caption(), $this->_Password->RequiredErrorMessage));
            }
        }
        if (!$this->_Password->Raw && Config("REMOVE_XSS") && CheckPassword($this->_Password->FormValue)) {
            $this->_Password->addErrorMessage($Language->phrase("InvalidPasswordChars"));
        }
        if ($this->First_Name->Required) {
            if (!$this->First_Name->IsDetailKey && EmptyValue($this->First_Name->FormValue)) {
                $this->First_Name->addErrorMessage(str_replace("%s", $this->First_Name->caption(), $this->First_Name->RequiredErrorMessage));
            }
        }
        if ($this->Last_Name->Required) {
            if (!$this->Last_Name->IsDetailKey && EmptyValue($this->Last_Name->FormValue)) {
                $this->Last_Name->addErrorMessage(str_replace("%s", $this->Last_Name->caption(), $this->Last_Name->RequiredErrorMessage));
            }
        }
        if ($this->_Email->Required) {
            if (!$this->_Email->IsDetailKey && EmptyValue($this->_Email->FormValue)) {
                $this->_Email->addErrorMessage(str_replace("%s", $this->_Email->caption(), $this->_Email->RequiredErrorMessage));
            }
        }
        if (!$this->_Email->Raw && Config("REMOVE_XSS") && CheckUsername($this->_Email->FormValue)) {
            $this->_Email->addErrorMessage($Language->phrase("InvalidUsernameChars"));
        }
        if ($this->User_Level->Required) {
            if (!$this->User_Level->IsDetailKey && EmptyValue($this->User_Level->FormValue)) {
                $this->User_Level->addErrorMessage(str_replace("%s", $this->User_Level->caption(), $this->User_Level->RequiredErrorMessage));
            }
        }
        if ($this->Report_To->Required) {
            if (!$this->Report_To->IsDetailKey && EmptyValue($this->Report_To->FormValue)) {
                $this->Report_To->addErrorMessage(str_replace("%s", $this->Report_To->caption(), $this->Report_To->RequiredErrorMessage));
            }
        }
        if ($this->Activated->Required) {
            if ($this->Activated->FormValue == "") {
                $this->Activated->addErrorMessage(str_replace("%s", $this->Activated->caption(), $this->Activated->RequiredErrorMessage));
            }
        }
        if ($this->Locked->Required) {
            if ($this->Locked->FormValue == "") {
                $this->Locked->addErrorMessage(str_replace("%s", $this->Locked->caption(), $this->Locked->RequiredErrorMessage));
            }
        }
        if ($this->Photo->Required) {
            if ($this->Photo->Upload->FileName == "" && !$this->Photo->Upload->KeepFile) {
                $this->Photo->addErrorMessage(str_replace("%s", $this->Photo->caption(), $this->Photo->RequiredErrorMessage));
            }
        }

        // Return validate result
        $validateForm = $validateForm && !$this->hasInvalidFields();

        // Call Form_CustomValidate event
        $formCustomError = "";
        $validateForm = $validateForm && $this->formCustomValidate($formCustomError);
        if ($formCustomError != "") {
            $this->setFailureMessage($formCustomError);
        }
        return $validateForm;
    }

    // Update record based on key values
    protected function editRow()
    {
        global $Security, $Language;
        $oldKeyFilter = $this->getRecordFilter();
        $filter = $this->applyUserIDFilters($oldKeyFilter);
        $conn = $this->getConnection();

		// Load old row
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $rsold = $conn->fetchAssociative($sql);
        if (!$rsold) {
            $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
            return false; // Update Failed
        } else {
            // Save old values
            $this->loadDbValues($rsold);
        }

		// Set new row
        $rsnew = [];

        // Username
        $this->_Username->setDbValueDef($rsnew, $this->_Username->CurrentValue, "", $this->_Username->ReadOnly);

        // Password
        if (!IsMaskedPassword($this->_Password->CurrentValue)) {
            $this->_Password->setDbValueDef($rsnew, $this->_Password->CurrentValue, "", $this->_Password->ReadOnly || Config("ENCRYPTED_PASSWORD") && $rsold['Password'] == $this->_Password->CurrentValue);
        }

        // First_Name
        $this->First_Name->setDbValueDef($rsnew, $this->First_Name->CurrentValue, null, $this->First_Name->ReadOnly);

        // Last_Name
        $this->Last_Name->setDbValueDef($rsnew, $this->Last_Name->CurrentValue, null, $this->Last_Name->ReadOnly);

        // Email
        $this->_Email->setDbValueDef($rsnew, $this->_Email->CurrentValue, null, $this->_Email->ReadOnly);

        // User_Level
        if ($Security->canAdmin()) { // System admin
            if ($this->User_Level->getSessionValue() != "") {
                $this->User_Level->ReadOnly = true;
            }
            $this->User_Level->setDbValueDef($rsnew, $this->User_Level->CurrentValue, 0, $this->User_Level->ReadOnly);
        }

        // Report_To
        $this->Report_To->setDbValueDef($rsnew, $this->Report_To->CurrentValue, null, $this->Report_To->ReadOnly);

        // Activated
        $tmpBool = $this->Activated->CurrentValue;
        if ($tmpBool != "Y" && $tmpBool != "N") {
            $tmpBool = !empty($tmpBool) ? "Y" : "N";
        }
        $this->Activated->setDbValueDef($rsnew, $tmpBool, "N", $this->Activated->ReadOnly);

        // Locked
        $tmpBool = $this->Locked->CurrentValue;
        if ($tmpBool != "Y" && $tmpBool != "N") {
            $tmpBool = !empty($tmpBool) ? "Y" : "N";
        }
        $this->Locked->setDbValueDef($rsnew, $tmpBool, null, $this->Locked->ReadOnly);

        // Photo
        if ($this->Photo->Visible && !$this->Photo->ReadOnly && !$this->Photo->Upload->KeepFile) {
            $this->Photo->Upload->DbValue = $rsold['Photo']; // Get original value
            if ($this->Photo->Upload->FileName == "") {
                $rsnew['Photo'] = null;
            } else {
                $rsnew['Photo'] = $this->Photo->Upload->FileName;
            }
        }

        // Update current values
        $this->setCurrentValues($rsnew);

        // Check referential integrity for master table 'userlevels'
        $detailKeys = [];
        $keyValue = $rsnew['User_Level'] ?? $rsold['User_Level'];
        $detailKeys['User_Level'] = $keyValue;
        $masterTable = Container("userlevels");
        $masterFilter = $this->getMasterFilter($masterTable, $detailKeys);
        if (!EmptyValue($masterFilter)) {
            $rsmaster = $masterTable->loadRs($masterFilter)->fetch();
            $validMasterRecord = $rsmaster !== false;
        } else { // Allow null value if not required field
            $validMasterRecord = $masterFilter === null;
        }
        if (!$validMasterRecord) {
            $relatedRecordMsg = str_replace("%t", "userlevels", $Language->phrase("RelatedRecordRequired"));
            $this->setFailureMessage($relatedRecordMsg);
            return false;
        }
        if ($this->Photo->Visible && !$this->Photo->Upload->KeepFile) {
            $oldFiles = EmptyValue($this->Photo->Upload->DbValue) ? [] : [$this->Photo->htmlDecode($this->Photo->Upload->DbValue)];
            if (!EmptyValue($this->Photo->Upload->FileName)) {
                $newFiles = [$this->Photo->Upload->FileName];
                $NewFileCount = count($newFiles);
                for ($i = 0; $i < $NewFileCount; $i++) {
                    if ($newFiles[$i] != "") {
                        $file = $newFiles[$i];
                        $tempPath = UploadTempPath($this->Photo, $this->Photo->Upload->Index);
                        if (file_exists($tempPath . $file)) {
                            if (Config("DELETE_UPLOADED_FILES")) {
                                $oldFileFound = false;
                                $oldFileCount = count($oldFiles);
                                for ($j = 0; $j < $oldFileCount; $j++) {
                                    $oldFile = $oldFiles[$j];
                                    if ($oldFile == $file) { // Old file found, no need to delete anymore
                                        array_splice($oldFiles, $j, 1);
                                        $oldFileFound = true;
                                        break;
                                    }
                                }
                                if ($oldFileFound) { // No need to check if file exists further
                                    continue;
                                }
                            }
                            $file1 = UniqueFilename($this->Photo->physicalUploadPath(), $file); // Get new file name
                            if ($file1 != $file) { // Rename temp file
                                while (file_exists($tempPath . $file1) || file_exists($this->Photo->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                    $file1 = UniqueFilename([$this->Photo->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                }
                                rename($tempPath . $file, $tempPath . $file1);
                                $newFiles[$i] = $file1;
                            }
                        }
                    }
                }
                $this->Photo->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                $this->Photo->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                $this->Photo->setDbValueDef($rsnew, $this->Photo->Upload->FileName, null, $this->Photo->ReadOnly);
            }
        }

        // Call Row Updating event
        $updateRow = $this->rowUpdating($rsold, $rsnew);

        // Check for duplicate key when key changed
        if ($updateRow) {
            $newKeyFilter = $this->getRecordFilter($rsnew);
            if ($newKeyFilter != $oldKeyFilter) {
                $rsChk = $this->loadRs($newKeyFilter)->fetch();
                if ($rsChk !== false) {
                    $keyErrMsg = str_replace("%f", $newKeyFilter, $Language->phrase("DupKey"));
                    $this->setFailureMessage($keyErrMsg);
                    $updateRow = false;
                }
            }
        }
        if ($updateRow) {
            if (count($rsnew) > 0) {
                $this->CurrentFilter = $filter; // Set up current filter
                $editRow = $this->update($rsnew, "", $rsold);
            } else {
                $editRow = true; // No field to update
            }
            if ($editRow) {
                if ($this->Photo->Visible && !$this->Photo->Upload->KeepFile) {
                    $oldFiles = EmptyValue($this->Photo->Upload->DbValue) ? [] : [$this->Photo->htmlDecode($this->Photo->Upload->DbValue)];
                    if (!EmptyValue($this->Photo->Upload->FileName)) {
                        $newFiles = [$this->Photo->Upload->FileName];
                        $newFiles2 = [$this->Photo->htmlDecode($rsnew['Photo'])];
                        $newFileCount = count($newFiles);
                        for ($i = 0; $i < $newFileCount; $i++) {
                            if ($newFiles[$i] != "") {
                                $file = UploadTempPath($this->Photo, $this->Photo->Upload->Index) . $newFiles[$i];
                                if (file_exists($file)) {
                                    if (@$newFiles2[$i] != "") { // Use correct file name
                                        $newFiles[$i] = $newFiles2[$i];
                                    }
                                    if (!$this->Photo->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
                                        $this->setFailureMessage($Language->phrase("UploadErrMsg7"));
                                        return false;
                                    }
                                }
                            }
                        }
                    } else {
                        $newFiles = [];
                    }
                    if (Config("DELETE_UPLOADED_FILES")) {
                        foreach ($oldFiles as $oldFile) {
                            if ($oldFile != "" && !in_array($oldFile, $newFiles)) {
                                @unlink($this->Photo->oldPhysicalUploadPath() . $oldFile);
                            }
                        }
                    }
                }
            }
        } else {
            if ($this->getSuccessMessage() != "" || $this->getFailureMessage() != "") {
                // Use the message, do nothing
            } elseif ($this->CancelMessage != "") {
                $this->setFailureMessage($this->CancelMessage);
                $this->CancelMessage = "";
            } else {
                $this->setFailureMessage($Language->phrase("UpdateCancelled"));
            }
            $editRow = false;
        }

        // Call Row_Updated event
        if ($editRow) {
            $this->rowUpdated($rsold, $rsnew);
        }

        // Clean upload path if any
        if ($editRow) {
            // Photo
            CleanUploadTempPath($this->Photo, $this->Photo->Upload->Index);
        }

        // Write JSON for API request
        if (IsApi() && $editRow) {
            $row = $this->getRecordsFromRecordset([$rsnew], true);
            WriteJson(["success" => true, $this->TableVar => $row]);
        }
        return $editRow;
    }

    // Show link optionally based on User ID
    protected function showOptionLink($id = "")
    {
        global $Security;
        if ($Security->isLoggedIn() && !$Security->isAdmin() && !$this->userIDAllow($id)) {
            return $Security->isValidUserID($this->_Username->CurrentValue);
        }
        return true;
    }

    // Set up master/detail based on QueryString
    protected function setupMasterParms()
    {
        $validMaster = false;
        // Get the keys for master table
        if (($master = Get(Config("TABLE_SHOW_MASTER"), Get(Config("TABLE_MASTER")))) !== null) {
            $masterTblVar = $master;
            if ($masterTblVar == "") {
                $validMaster = true;
                $this->DbMasterFilter = "";
                $this->DbDetailFilter = "";
            }
            if ($masterTblVar == "userlevels") {
                $validMaster = true;
                $masterTbl = Container("userlevels");
                if (($parm = Get("fk_User_Level_ID", Get("User_Level"))) !== null) {
                    $masterTbl->User_Level_ID->setQueryStringValue($parm);
                    $this->User_Level->setQueryStringValue($masterTbl->User_Level_ID->QueryStringValue);
                    $this->User_Level->setSessionValue($this->User_Level->QueryStringValue);
                    if (!is_numeric($masterTbl->User_Level_ID->QueryStringValue)) {
                        $validMaster = false;
                    }
                } else {
                    $validMaster = false;
                }
            }
        } elseif (($master = Post(Config("TABLE_SHOW_MASTER"), Post(Config("TABLE_MASTER")))) !== null) {
            $masterTblVar = $master;
            if ($masterTblVar == "") {
                    $validMaster = true;
                    $this->DbMasterFilter = "";
                    $this->DbDetailFilter = "";
            }
            if ($masterTblVar == "userlevels") {
                $validMaster = true;
                $masterTbl = Container("userlevels");
                if (($parm = Post("fk_User_Level_ID", Post("User_Level"))) !== null) {
                    $masterTbl->User_Level_ID->setFormValue($parm);
                    $this->User_Level->setFormValue($masterTbl->User_Level_ID->FormValue);
                    $this->User_Level->setSessionValue($this->User_Level->FormValue);
                    if (!is_numeric($masterTbl->User_Level_ID->FormValue)) {
                        $validMaster = false;
                    }
                } else {
                    $validMaster = false;
                }
            }
        }
        if ($validMaster) {
            // Save current master table
            $this->setCurrentMasterTable($masterTblVar);
            $this->setSessionWhere($this->getDetailFilterFromSession());

            // Reset start record counter (new master key)
            if (!$this->isAddOrEdit()) {
                $this->StartRecord = 1;
                $this->setStartRecordNumber($this->StartRecord);
            }

            // Clear previous master key from Session
            if ($masterTblVar != "userlevels") {
                if ($this->User_Level->CurrentValue == "") {
                    $this->User_Level->setSessionValue("");
                }
            }
        }
        $this->DbMasterFilter = $this->getMasterFilterFromSession(); // Get master filter from session
        $this->DbDetailFilter = $this->getDetailFilterFromSession(); // Get detail filter from session
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("index");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("userslist"), "", $this->TableVar, true);
        $pageId = "edit";
        $Breadcrumb->add("edit", $pageId, $url);
    }

    // Setup lookup options
    public function setupLookupOptions($fld)
    {
        if ($fld->Lookup !== null && $fld->Lookup->Options === null) {
            // Get default connection and filter
            $conn = $this->getConnection();
            $lookupFilter = "";

            // No need to check any more
            $fld->Lookup->Options = [];

            // Set up lookup SQL and connection
            switch ($fld->FieldVar) {
                case "x_User_Level":
                    break;
                case "x_Report_To":
                    break;
                case "x_Activated":
                    break;
                case "x_Locked":
                    break;
                default:
                    $lookupFilter = "";
                    break;
            }

            // Always call to Lookup->getSql so that user can setup Lookup->Options in Lookup_Selecting server event
            $sql = $fld->Lookup->getSql(false, "", $lookupFilter, $this);

            // Set up lookup cache
            if (!$fld->hasLookupOptions() && $fld->UseLookupCache && $sql != "" && count($fld->Lookup->Options) == 0) {
                $totalCnt = $this->getRecordCount($sql, $conn);
                if ($totalCnt > $fld->LookupCacheCount) { // Total count > cache count, do not cache
                    return;
                }
                $rows = $conn->executeQuery($sql)->fetchAll();
                $ar = [];
                foreach ($rows as $row) {
                    $row = $fld->Lookup->renderViewRow($row, Container($fld->Lookup->LinkTable));
                    $ar[strval($row["lf"])] = $row;
                }
                $fld->Lookup->Options = $ar;
            }
        }
    }

    // Set up starting record parameters
    public function setupStartRecord()
    {
        if ($this->DisplayRecords == 0) {
            return;
        }
        if ($this->isPageRequest()) { // Validate request
            $startRec = Get(Config("TABLE_START_REC"));
            if ($startRec !== null && is_numeric($startRec)) { // Check for "start" parameter
                $this->StartRecord = $startRec;
                $this->setStartRecordNumber($this->StartRecord);
            }
        }
        $this->StartRecord = $this->getStartRecordNumber();

        // Check if correct start record counter
        if (!is_numeric($this->StartRecord) || $this->StartRecord == "") { // Avoid invalid start record counter
            $this->StartRecord = 1; // Reset start record counter
            $this->setStartRecordNumber($this->StartRecord);
        } elseif ($this->StartRecord > $this->TotalRecords) { // Avoid starting record > total records
            $this->StartRecord = (int)(($this->TotalRecords - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1; // Point to last page first record
            $this->setStartRecordNumber($this->StartRecord);
        } elseif (($this->StartRecord - 1) % $this->DisplayRecords != 0) {
            $this->StartRecord = (int)(($this->StartRecord - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1; // Point to page boundary
            $this->setStartRecordNumber($this->StartRecord);
        }
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
    // $type = ''|'success'|'failure'|'warning'
    public function messageShowing(&$msg, $type)
    {
        if ($type == 'success') {
            //$msg = "your success message";
        } elseif ($type == 'failure') {
            //$msg = "your failure message";
        } elseif ($type == 'warning') {
            //$msg = "your warning message";
        } else {
            //$msg = "your message";
        }
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

    // Form Custom Validate event
    public function formCustomValidate(&$customError)
    {
        // Return error message in $customError
        return true;
    }
}
