<?php

namespace PHPMaker2022\civichub2;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class UsersGrid extends Users
{
    use MessagesTrait;

    // Page ID
    public $PageID = "grid";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'users';

    // Page object name
    public $PageObjName = "UsersGrid";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // Grid form hidden field names
    public $FormName = "fusersgrid";
    public $FormActionName = "k_action";
    public $FormBlankRowName = "k_blankrow";
    public $FormKeyCountName = "key_count";

    // Page URLs
    public $AddUrl;
    public $EditUrl;
    public $CopyUrl;
    public $DeleteUrl;
    public $ViewUrl;
    public $ListUrl;

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
        $this->FormActionName .= "_" . $this->FormName;
        $this->OldKeyName .= "_" . $this->FormName;
        $this->FormBlankRowName .= "_" . $this->FormName;
        $this->FormKeyCountName .= "_" . $this->FormName;
        $GLOBALS["Grid"] = &$this;

        // Language object
        $Language = Container("language");

        // Parent constuctor
        parent::__construct();

        // Table object (users)
        if (!isset($GLOBALS["users"]) || get_class($GLOBALS["users"]) == PROJECT_NAMESPACE . "users") {
            $GLOBALS["users"] = &$this;
        }
        $this->AddUrl = "usersadd";

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

        // List options
        $this->ListOptions = new ListOptions(["Tag" => "td", "TableVar" => $this->TableVar]);

        // Other options
        if (!$this->OtherOptions) {
            $this->OtherOptions = new ListOptionsArray();
        }

        // Grid-Add/Edit
        $this->OtherOptions["addedit"] = new ListOptions([
            "TagClassName" => "ew-add-edit-option",
            "UseDropDownButton" => false,
            "DropDownButtonPhrase" => $Language->phrase("ButtonAddEdit"),
            "UseButtonGroup" => true
        ]);
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
        unset($GLOBALS["Grid"]);
        if ($url === "") {
            return;
        }
        if (!IsApi() && method_exists($this, "pageRedirecting")) {
            $this->pageRedirecting($url);
        }

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

    // Class variables
    public $ListOptions; // List options
    public $ExportOptions; // Export options
    public $SearchOptions; // Search options
    public $OtherOptions; // Other options
    public $FilterOptions; // Filter options
    public $ImportOptions; // Import options
    public $ListActions; // List actions
    public $SelectedCount = 0;
    public $SelectedIndex = 0;
    public $ShowOtherOptions = false;
    public $DisplayRecords = 20;
    public $StartRecord;
    public $StopRecord;
    public $TotalRecords = 0;
    public $RecordRange = 10;
    public $PageSizes = "10,20,50,-1"; // Page sizes (comma separated)
    public $DefaultSearchWhere = ""; // Default search WHERE clause
    public $SearchWhere = ""; // Search WHERE clause
    public $SearchPanelClass = "ew-search-panel collapse show"; // Search Panel class
    public $SearchColumnCount = 0; // For extended search
    public $SearchFieldsPerRow = 1; // For extended search
    public $RecordCount = 0; // Record count
    public $EditRowCount;
    public $StartRowCount = 1;
    public $RowCount = 0;
    public $Attrs = []; // Row attributes and cell attributes
    public $RowIndex = 0; // Row index
    public $KeyCount = 0; // Key count
    public $MultiColumnGridClass = "row-cols-md";
    public $MultiColumnEditClass = "col-12 w-100";
    public $MultiColumnCardClass = "card h-100 ew-card";
    public $MultiColumnListOptionsPosition = "bottom-start";
    public $DbMasterFilter = ""; // Master filter
    public $DbDetailFilter = ""; // Detail filter
    public $MasterRecordExists;
    public $MultiSelectKey;
    public $Command;
    public $UserAction; // User action
    public $RestoreSearch = false;
    public $HashValue; // Hash value
    public $DetailPages;
    public $OldRecordset;

    /**
     * Page run
     *
     * @return void
     */
    public function run()
    {
        global $ExportType, $CustomExportType, $ExportFileName, $UserProfile, $Language, $Security, $CurrentForm;

        // Multi column button position
        $this->MultiColumnListOptionsPosition = Config("MULTI_COLUMN_LIST_OPTIONS_POSITION");

// Use layout
        $this->UseLayout = $this->UseLayout && ConvertToBool(Param("layout", true));

        // Get grid add count
        $gridaddcnt = Get(Config("TABLE_GRID_ADD_ROW_COUNT"), "");
        if (is_numeric($gridaddcnt) && $gridaddcnt > 0) {
            $this->GridAddRowCount = $gridaddcnt;
        }

        // Set up list options
        $this->setupListOptions();
        $this->_Username->setVisibility();
        $this->_Password->Visible = false;
        $this->First_Name->setVisibility();
        $this->Last_Name->setVisibility();
        $this->_Email->setVisibility();
        $this->User_Level->setVisibility();
        $this->Report_To->setVisibility();
        $this->Activated->setVisibility();
        $this->Locked->setVisibility();
        $this->_Profile->Visible = false;
        $this->Photo->Visible = false;
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

        // Set up master detail parameters
        $this->setupMasterParms();

        // Setup other options
        $this->setupOtherOptions();

        // Set up lookup cache
        $this->setupLookupOptions($this->User_Level);
        $this->setupLookupOptions($this->Report_To);
        $this->setupLookupOptions($this->Activated);
        $this->setupLookupOptions($this->Locked);

        // Load default values for add
        $this->loadDefaultValues();

        // Search filters
        $srchAdvanced = ""; // Advanced search filter
        $srchBasic = ""; // Basic search filter
        $filter = "";

        // Get command
        $this->Command = strtolower(Get("cmd", ""));
        if ($this->isPageRequest()) {
            // Set up records per page
            $this->setupDisplayRecords();

            // Handle reset command
            $this->resetCmd();

            // Hide list options
            if ($this->isExport()) {
                $this->ListOptions->hideAllOptions(["sequence"]);
                $this->ListOptions->UseDropDownButton = false; // Disable drop down button
                $this->ListOptions->UseButtonGroup = false; // Disable button group
            } elseif ($this->isGridAdd() || $this->isGridEdit()) {
                $this->ListOptions->hideAllOptions();
                $this->ListOptions->UseDropDownButton = false; // Disable drop down button
                $this->ListOptions->UseButtonGroup = false; // Disable button group
            }

            // Show grid delete link for grid add / grid edit
            if ($this->AllowAddDeleteRow) {
                if ($this->isGridAdd() || $this->isGridEdit()) {
                    $item = $this->ListOptions["griddelete"];
                    if ($item) {
                        $item->Visible = true;
                    }
                }
            }

            // Set up sorting order
            $this->setupSortOrder();
        }

        // Restore display records
        if ($this->Command != "json" && $this->getRecordsPerPage() != "") {
            $this->DisplayRecords = $this->getRecordsPerPage(); // Restore from Session
        } else {
            $this->DisplayRecords = 20; // Load default
            $this->setRecordsPerPage($this->DisplayRecords); // Save default to Session
        }

        // Build filter
        $filter = "";
        if (!$Security->canList()) {
            $filter = "(0=1)"; // Filter all records
        }

        // Restore master/detail filter from session
        $this->DbMasterFilter = $this->getMasterFilterFromSession(); // Restore master filter from session
        $this->DbDetailFilter = $this->getDetailFilterFromSession(); // Restore detail filter from session
        AddFilter($filter, $this->DbDetailFilter);
        AddFilter($filter, $this->SearchWhere);

        // Load master record
        if ($this->CurrentMode != "add" && $this->DbMasterFilter != "" && $this->getCurrentMasterTable() == "userlevels") {
            $masterTbl = Container("userlevels");
            $rsmaster = $masterTbl->loadRs($this->DbMasterFilter)->fetchAssociative();
            $this->MasterRecordExists = $rsmaster !== false;
            if (!$this->MasterRecordExists) {
                $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record found
                $this->terminate("userlevelslist"); // Return to master page
                return;
            } else {
                $masterTbl->loadListRowValues($rsmaster);
                $masterTbl->RowType = ROWTYPE_MASTER; // Master row
                $masterTbl->renderListRow();
            }
        }

        // Set up filter
        if ($this->Command == "json") {
            $this->UseSessionForListSql = false; // Do not use session for ListSQL
            $this->CurrentFilter = $filter;
        } else {
            $this->setSessionWhere($filter);
            $this->CurrentFilter = "";
        }
        if ($this->isGridAdd()) {
            if ($this->CurrentMode == "copy") {
                $this->TotalRecords = $this->listRecordCount();
                $this->StartRecord = 1;
                $this->DisplayRecords = $this->TotalRecords;
                $this->Recordset = $this->loadRecordset($this->StartRecord - 1, $this->DisplayRecords);
            } else {
                $this->CurrentFilter = "0=1";
                $this->StartRecord = 1;
                $this->DisplayRecords = $this->GridAddRowCount;
            }
            $this->TotalRecords = $this->DisplayRecords;
            $this->StopRecord = $this->DisplayRecords;
        } else {
            $this->TotalRecords = $this->listRecordCount();
            $this->StartRecord = 1;
            $this->DisplayRecords = $this->TotalRecords; // Display all records
            $this->Recordset = $this->loadRecordset($this->StartRecord - 1, $this->DisplayRecords);
        }

        // Normal return
        if (IsApi()) {
            $rows = $this->getRecordsFromRecordset($this->Recordset);
            $this->Recordset->close();
            WriteJson(["success" => true, $this->TableVar => $rows, "totalRecordCount" => $this->TotalRecords]);
            $this->terminate(true);
            return;
        }

        // Set up pager
        $this->Pager = new PrevNextPager($this->TableVar, $this->StartRecord, $this->getRecordsPerPage(), $this->TotalRecords, $this->PageSizes, $this->RecordRange, $this->AutoHidePager, $this->AutoHidePageSizeSelector);

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

    // Set up number of records displayed per page
    protected function setupDisplayRecords()
    {
        $wrk = Get(Config("TABLE_REC_PER_PAGE"), "");
        if ($wrk != "") {
            if (is_numeric($wrk)) {
                $this->DisplayRecords = (int)$wrk;
            } else {
                if (SameText($wrk, "all")) { // Display all records
                    $this->DisplayRecords = -1;
                } else {
                    $this->DisplayRecords = 20; // Non-numeric, load default
                }
            }
            $this->setRecordsPerPage($this->DisplayRecords); // Save to Session
            // Reset start position
            $this->StartRecord = 1;
            $this->setStartRecordNumber($this->StartRecord);
        }
    }

    // Exit inline mode
    protected function clearInlineMode()
    {
        $this->LastAction = $this->CurrentAction; // Save last action
        $this->CurrentAction = ""; // Clear action
        $_SESSION[SESSION_INLINE_MODE] = ""; // Clear inline mode
    }

    // Switch to Grid Add mode
    protected function gridAddMode()
    {
        global $Security, $Language;
        if (!$Security->canAdd()) { // No add permission
            $this->CurrentAction = "";
            $this->setFailureMessage($Language->phrase("NoAddPermission"));
            return false;
        }
        $this->CurrentAction = "gridadd";
        $_SESSION[SESSION_INLINE_MODE] = "gridadd";
        $this->hideFieldsForAddEdit();
    }

    // Switch to Grid Edit mode
    protected function gridEditMode()
    {
        global $Security, $Language;
        if (!$Security->canEdit()) { // No edit permission
            $this->CurrentAction = "";
            $this->setFailureMessage($Language->phrase("NoEditPermission"));
            return false;
        }
        $this->CurrentAction = "gridedit";
        $_SESSION[SESSION_INLINE_MODE] = "gridedit";
        $this->hideFieldsForAddEdit();
    }

    // Perform update to grid
    public function gridUpdate()
    {
        global $Language, $CurrentForm;
        $gridUpdate = true;

        // Get old recordset
        $this->CurrentFilter = $this->buildKeyFilter();
        if ($this->CurrentFilter == "") {
            $this->CurrentFilter = "0=1";
        }
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        if ($rs = $conn->executeQuery($sql)) {
            $rsold = $rs->fetchAllAssociative();
        }

        // Call Grid Updating event
        if (!$this->gridUpdating($rsold)) {
            if ($this->getFailureMessage() == "") {
                $this->setFailureMessage($Language->phrase("GridEditCancelled")); // Set grid edit cancelled message
            }
            return false;
        }
        $key = "";

        // Update row index and get row key
        $CurrentForm->Index = -1;
        $rowcnt = strval($CurrentForm->getValue($this->FormKeyCountName));
        if ($rowcnt == "" || !is_numeric($rowcnt)) {
            $rowcnt = 0;
        }

        // Update all rows based on key
        for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {
            $CurrentForm->Index = $rowindex;
            $this->setKey($CurrentForm->getValue($this->OldKeyName));
            $rowaction = strval($CurrentForm->getValue($this->FormActionName));

            // Load all values and keys
            if ($rowaction != "insertdelete") { // Skip insert then deleted rows
                $this->loadFormValues(); // Get form values
                if ($rowaction == "" || $rowaction == "edit" || $rowaction == "delete") {
                    $gridUpdate = $this->OldKey != ""; // Key must not be empty
                } else {
                    $gridUpdate = true;
                }

                // Skip empty row
                if ($rowaction == "insert" && $this->emptyRow()) {
                // Validate form and insert/update/delete record
                } elseif ($gridUpdate) {
                    if ($rowaction == "delete") {
                        $this->CurrentFilter = $this->getRecordFilter();
                        $gridUpdate = $this->deleteRows(); // Delete this row
                    //} elseif (!$this->validateForm()) { // Already done in validateGridForm
                    //    $gridUpdate = false; // Form error, reset action
                    } else {
                        if ($rowaction == "insert") {
                            $gridUpdate = $this->addRow(); // Insert this row
                        } else {
                            if ($this->OldKey != "") {
                                $this->SendEmail = false; // Do not send email on update success
                                $gridUpdate = $this->editRow(); // Update this row
                            }
                        } // End update
                    }
                }
                if ($gridUpdate) {
                    if ($key != "") {
                        $key .= ", ";
                    }
                    $key .= $this->OldKey;
                } else {
                    break;
                }
            }
        }
        if ($gridUpdate) {
            // Get new records
            $rsnew = $conn->fetchAllAssociative($sql);

            // Call Grid_Updated event
            $this->gridUpdated($rsold, $rsnew);
            $this->clearInlineMode(); // Clear inline edit mode
        } else {
            if ($this->getFailureMessage() == "") {
                $this->setFailureMessage($Language->phrase("UpdateFailed")); // Set update failed message
            }
        }
        return $gridUpdate;
    }

    // Build filter for all keys
    protected function buildKeyFilter()
    {
        global $CurrentForm;
        $wrkFilter = "";

        // Update row index and get row key
        $rowindex = 1;
        $CurrentForm->Index = $rowindex;
        $thisKey = strval($CurrentForm->getValue($this->OldKeyName));
        while ($thisKey != "") {
            $this->setKey($thisKey);
            if ($this->OldKey != "") {
                $filter = $this->getRecordFilter();
                if ($wrkFilter != "") {
                    $wrkFilter .= " OR ";
                }
                $wrkFilter .= $filter;
            } else {
                $wrkFilter = "0=1";
                break;
            }

            // Update row index and get row key
            $rowindex++; // Next row
            $CurrentForm->Index = $rowindex;
            $thisKey = strval($CurrentForm->getValue($this->OldKeyName));
        }
        return $wrkFilter;
    }

    // Perform Grid Add
    public function gridInsert()
    {
        global $Language, $CurrentForm;
        $rowindex = 1;
        $gridInsert = false;
        $conn = $this->getConnection();

        // Call Grid Inserting event
        if (!$this->gridInserting()) {
            if ($this->getFailureMessage() == "") {
                $this->setFailureMessage($Language->phrase("GridAddCancelled")); // Set grid add cancelled message
            }
            return false;
        }

        // Init key filter
        $wrkfilter = "";
        $addcnt = 0;
        $key = "";

        // Get row count
        $CurrentForm->Index = -1;
        $rowcnt = strval($CurrentForm->getValue($this->FormKeyCountName));
        if ($rowcnt == "" || !is_numeric($rowcnt)) {
            $rowcnt = 0;
        }

        // Insert all rows
        for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {
            // Load current row values
            $CurrentForm->Index = $rowindex;
            $rowaction = strval($CurrentForm->getValue($this->FormActionName));
            if ($rowaction != "" && $rowaction != "insert") {
                continue; // Skip
            }
            if ($rowaction == "insert") {
                $this->OldKey = strval($CurrentForm->getValue($this->OldKeyName));
                $this->loadOldRecord(); // Load old record
            }
            $this->loadFormValues(); // Get form values
            if (!$this->emptyRow()) {
                $addcnt++;
                $this->SendEmail = false; // Do not send email on insert success

                // Validate form // Already done in validateGridForm
                //if (!$this->validateForm()) {
                //    $gridInsert = false; // Form error, reset action
                //} else {
                    $gridInsert = $this->addRow($this->OldRecordset); // Insert this row
                //}
                if ($gridInsert) {
                    if ($key != "") {
                        $key .= Config("COMPOSITE_KEY_SEPARATOR");
                    }
                    $key .= $this->_Username->CurrentValue;

                    // Add filter for this record
                    $filter = $this->getRecordFilter();
                    if ($wrkfilter != "") {
                        $wrkfilter .= " OR ";
                    }
                    $wrkfilter .= $filter;
                } else {
                    break;
                }
            }
        }
        if ($addcnt == 0) { // No record inserted
            $this->clearInlineMode(); // Clear grid add mode and return
            return true;
        }
        if ($gridInsert) {
            // Get new records
            $this->CurrentFilter = $wrkfilter;
            $sql = $this->getCurrentSql();
            $rsnew = $conn->fetchAllAssociative($sql);

            // Call Grid_Inserted event
            $this->gridInserted($rsnew);
            $this->clearInlineMode(); // Clear grid add mode
        } else {
            if ($this->getFailureMessage() == "") {
                $this->setFailureMessage($Language->phrase("InsertFailed")); // Set insert failed message
            }
        }
        return $gridInsert;
    }

    // Check if empty row
    public function emptyRow()
    {
        global $CurrentForm;
        if ($CurrentForm->hasValue("x__Username") && $CurrentForm->hasValue("o__Username") && $this->_Username->CurrentValue != $this->_Username->DefaultValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_First_Name") && $CurrentForm->hasValue("o_First_Name") && $this->First_Name->CurrentValue != $this->First_Name->DefaultValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_Last_Name") && $CurrentForm->hasValue("o_Last_Name") && $this->Last_Name->CurrentValue != $this->Last_Name->DefaultValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x__Email") && $CurrentForm->hasValue("o__Email") && $this->_Email->CurrentValue != $this->_Email->DefaultValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_User_Level") && $CurrentForm->hasValue("o_User_Level") && $this->User_Level->CurrentValue != $this->User_Level->DefaultValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_Report_To") && $CurrentForm->hasValue("o_Report_To") && $this->Report_To->CurrentValue != $this->Report_To->DefaultValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_Activated") && $CurrentForm->hasValue("o_Activated") && ConvertToBool($this->Activated->CurrentValue) != ConvertToBool($this->Activated->DefaultValue)) {
            return false;
        }
        if ($CurrentForm->hasValue("x_Locked") && $CurrentForm->hasValue("o_Locked") && ConvertToBool($this->Locked->CurrentValue) != ConvertToBool($this->Locked->DefaultValue)) {
            return false;
        }
        return true;
    }

    // Validate grid form
    public function validateGridForm()
    {
        global $CurrentForm;
        // Get row count
        $CurrentForm->Index = -1;
        $rowcnt = strval($CurrentForm->getValue($this->FormKeyCountName));
        if ($rowcnt == "" || !is_numeric($rowcnt)) {
            $rowcnt = 0;
        }

        // Validate all records
        for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {
            // Load current row values
            $CurrentForm->Index = $rowindex;
            $rowaction = strval($CurrentForm->getValue($this->FormActionName));
            if ($rowaction != "delete" && $rowaction != "insertdelete") {
                $this->loadFormValues(); // Get form values
                if ($rowaction == "insert" && $this->emptyRow()) {
                    // Ignore
                } elseif (!$this->validateForm()) {
                    $this->EventCancelled = true;
                    return false;
                }
            }
        }
        return true;
    }

    // Get all form values of the grid
    public function getGridFormValues()
    {
        global $CurrentForm;
        // Get row count
        $CurrentForm->Index = -1;
        $rowcnt = strval($CurrentForm->getValue($this->FormKeyCountName));
        if ($rowcnt == "" || !is_numeric($rowcnt)) {
            $rowcnt = 0;
        }
        $rows = [];

        // Loop through all records
        for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {
            // Load current row values
            $CurrentForm->Index = $rowindex;
            $rowaction = strval($CurrentForm->getValue($this->FormActionName));
            if ($rowaction != "delete" && $rowaction != "insertdelete") {
                $this->loadFormValues(); // Get form values
                if ($rowaction == "insert" && $this->emptyRow()) {
                    // Ignore
                } else {
                    $rows[] = $this->getFieldValues("FormValue"); // Return row as array
                }
            }
        }
        return $rows; // Return as array of array
    }

    // Restore form values for current row
    public function restoreCurrentRowFormValues($idx)
    {
        global $CurrentForm;

        // Get row based on current index
        $CurrentForm->Index = $idx;
        $rowaction = strval($CurrentForm->getValue($this->FormActionName));
        $this->loadFormValues(); // Load form values
        // Set up invalid status correctly
        $this->resetFormError();
        if ($rowaction == "insert" && $this->emptyRow()) {
            // Ignore
        } else {
            $this->validateForm();
        }
    }

    // Reset form status
    public function resetFormError()
    {
        $this->_Username->clearErrorMessage();
        $this->First_Name->clearErrorMessage();
        $this->Last_Name->clearErrorMessage();
        $this->_Email->clearErrorMessage();
        $this->User_Level->clearErrorMessage();
        $this->Report_To->clearErrorMessage();
        $this->Activated->clearErrorMessage();
        $this->Locked->clearErrorMessage();
    }

    // Set up sort parameters
    protected function setupSortOrder()
    {
        // Load default Sorting Order
        if ($this->Command != "json") {
            $defaultSort = ""; // Set up default sort
            if ($this->getSessionOrderBy() == "" && $defaultSort != "") {
                $this->setSessionOrderBy($defaultSort);
            }
        }

        // Check for "order" parameter
        if (Get("order") !== null) {
            $this->CurrentOrder = Get("order");
            $this->CurrentOrderType = Get("ordertype", "");
            $this->setStartRecordNumber(1); // Reset start position
        }

        // Update field sort
        $this->updateFieldSort();
    }

    // Reset command
    // - cmd=reset (Reset search parameters)
    // - cmd=resetall (Reset search and master/detail parameters)
    // - cmd=resetsort (Reset sort parameters)
    protected function resetCmd()
    {
        // Check if reset command
        if (StartsString("reset", $this->Command)) {
            // Reset master/detail keys
            if ($this->Command == "resetall") {
                $this->setCurrentMasterTable(""); // Clear master table
                $this->DbMasterFilter = "";
                $this->DbDetailFilter = "";
                        $this->User_Level->setSessionValue("");
            }

            // Reset (clear) sorting order
            if ($this->Command == "resetsort") {
                $orderBy = "";
                $this->setSessionOrderBy($orderBy);
            }

            // Reset start position
            $this->StartRecord = 1;
            $this->setStartRecordNumber($this->StartRecord);
        }
    }

    // Set up list options
    protected function setupListOptions()
    {
        global $Security, $Language;

        // "griddelete"
        if ($this->AllowAddDeleteRow) {
            $item = &$this->ListOptions->add("griddelete");
            $item->CssClass = "text-nowrap";
            $item->OnLeft = false;
            $item->Visible = false; // Default hidden
        }

        // Add group option item ("button")
        $item = &$this->ListOptions->addGroupOption();
        $item->Body = "";
        $item->OnLeft = false;
        $item->Visible = false;

        // "view"
        $item = &$this->ListOptions->add("view");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->canView();
        $item->OnLeft = false;

        // "edit"
        $item = &$this->ListOptions->add("edit");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->canEdit();
        $item->OnLeft = false;

        // "copy"
        $item = &$this->ListOptions->add("copy");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->canAdd();
        $item->OnLeft = false;

        // "delete"
        $item = &$this->ListOptions->add("delete");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->canDelete();
        $item->OnLeft = false;

        // Drop down button for ListOptions
        $this->ListOptions->UseDropDownButton = false;
        $this->ListOptions->DropDownButtonPhrase = $Language->phrase("ButtonListOptions");
        $this->ListOptions->UseButtonGroup = false;
        if ($this->ListOptions->UseButtonGroup && IsMobile()) {
            $this->ListOptions->UseDropDownButton = true;
        }

        //$this->ListOptions->ButtonClass = ""; // Class for button group

        // Call ListOptions_Load event
        $this->listOptionsLoad();
        $item = $this->ListOptions[$this->ListOptions->GroupOptionName];
        $item->Visible = $this->ListOptions->groupOptionVisible();
    }

    // Set up list options (extensions)
    protected function setupListOptionsExt()
    {
            // Set up list options (to be implemented by extensions)
    }

    // Render list options
    public function renderListOptions()
    {
        global $Security, $Language, $CurrentForm, $UserProfile;
        $this->ListOptions->loadDefault();

        // Call ListOptions_Rendering event
        $this->listOptionsRendering();

        // Set up row action and key
        if ($CurrentForm && is_numeric($this->RowIndex) && $this->RowType != "view") {
            $CurrentForm->Index = $this->RowIndex;
            $actionName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormActionName);
            $oldKeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->OldKeyName);
            $blankRowName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormBlankRowName);
            if ($this->RowAction != "") {
                $this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $actionName . "\" id=\"" . $actionName . "\" value=\"" . $this->RowAction . "\">";
            }
            $oldKey = $this->getKey(false); // Get from OldValue
            if ($oldKeyName != "" && $oldKey != "") {
                $this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $oldKeyName . "\" id=\"" . $oldKeyName . "\" value=\"" . HtmlEncode($oldKey) . "\">";
            }
            if ($this->RowAction == "insert" && $this->isConfirm() && $this->emptyRow()) {
                $this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $blankRowName . "\" id=\"" . $blankRowName . "\" value=\"1\">";
            }
        }

        // "delete"
        if ($this->AllowAddDeleteRow) {
            if ($this->CurrentMode == "add" || $this->CurrentMode == "copy" || $this->CurrentMode == "edit") {
                $options = &$this->ListOptions;
                $options->UseButtonGroup = true; // Use button group for grid delete button
                $opt = $options["griddelete"];
                if (!$Security->canDelete() && is_numeric($this->RowIndex) && ($this->RowAction == "" || $this->RowAction == "edit")) { // Do not allow delete existing record
                    $opt->Body = "&nbsp;";
                } else {
                    $opt->Body = "<a class=\"ew-grid-link ew-grid-delete\" title=\"" . HtmlTitle($Language->phrase("DeleteLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("DeleteLink")) . "\" data-ew-action=\"delete-grid-row\" data-rowindex=\"" . $this->RowIndex . "\">" . $Language->phrase("DeleteLink") . "</a>";
                }
            }
        }
        if ($this->CurrentMode == "view") {
            // "view"
            $opt = $this->ListOptions["view"];
            $viewcaption = HtmlTitle($Language->phrase("ViewLink"));
            if ($Security->canView() && $this->showOptionLink("view")) {
                $opt->Body = "<a class=\"ew-row-link ew-view\" title=\"" . $viewcaption . "\" data-caption=\"" . $viewcaption . "\" href=\"" . HtmlEncode(GetUrl($this->ViewUrl)) . "\">" . $Language->phrase("ViewLink") . "</a>";
            } else {
                $opt->Body = "";
            }

            // "edit"
            $opt = $this->ListOptions["edit"];
            $editcaption = HtmlTitle($Language->phrase("EditLink"));
            if ($Security->canEdit() && $this->showOptionLink("edit")) {
                $opt->Body = "<a class=\"ew-row-link ew-edit\" title=\"" . HtmlTitle($Language->phrase("EditLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("EditLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->EditUrl)) . "\">" . $Language->phrase("EditLink") . "</a>";
            } else {
                $opt->Body = "";
            }

            // "copy"
            $opt = $this->ListOptions["copy"];
            $copycaption = HtmlTitle($Language->phrase("CopyLink"));
            if ($Security->canAdd() && $this->showOptionLink("add")) {
                $opt->Body = "<a class=\"ew-row-link ew-copy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . HtmlEncode(GetUrl($this->CopyUrl)) . "\">" . $Language->phrase("CopyLink") . "</a>";
            } else {
                $opt->Body = "";
            }

            // "delete"
            $opt = $this->ListOptions["delete"];
            if ($Security->canDelete() && $this->showOptionLink("delete")) {
                $opt->Body = "<a class=\"ew-row-link ew-delete\" data-ew-action=\"\" title=\"" . HtmlTitle($Language->phrase("DeleteLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("DeleteLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->DeleteUrl)) . "\">" . $Language->phrase("DeleteLink") . "</a>";
            } else {
                $opt->Body = "";
            }
        } // End View mode
        $this->renderListOptionsExt();

        // Call ListOptions_Rendered event
        $this->listOptionsRendered();
    }

    // Render list options (extensions)
    protected function renderListOptionsExt()
    {
        // Render list options (to be implemented by extensions)
        global $Security, $Language;
    }

    // Set up other options
    protected function setupOtherOptions()
    {
        global $Language, $Security;
        $option = $this->OtherOptions["addedit"];
        $item = &$option->addGroupOption();
        $item->Body = "";
        $item->Visible = false;

        // Add
        if ($this->CurrentMode == "view") { // Check view mode
            $item = &$option->add("add");
            $addcaption = HtmlTitle($Language->phrase("AddLink"));
            $this->AddUrl = $this->getAddUrl();
            $item->Body = "<a class=\"ew-add-edit ew-add\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . HtmlEncode(GetUrl($this->AddUrl)) . "\">" . $Language->phrase("AddLink") . "</a>";
            $item->Visible = $this->AddUrl != "" && $Security->canAdd();
        }
    }

    // Create new column option
    public function createColumnOption($name)
    {
        $field = $this->Fields[$name] ?? false;
        if ($field && $field->Visible) {
            $item = new ListOption($field->Name);
            $item->Body = '<button class="dropdown-item">' .
                '<div class="form-check ew-dropdown-checkbox">' .
                '<div class="form-check-input ew-dropdown-check-input" data-field="' . $field->Param . '"></div>' .
                '<label class="form-check-label ew-dropdown-check-label">' . $field->caption() . '</label></div></button>';
            return $item;
        }
        return null;
    }

    // Render other options
    public function renderOtherOptions()
    {
        global $Language, $Security;
        $options = &$this->OtherOptions;
            if (in_array($this->CurrentMode, ["add", "copy", "edit"]) && !$this->isConfirm()) { // Check add/copy/edit mode
                if ($this->AllowAddDeleteRow) {
                    $option = $options["addedit"];
                    $option->UseDropDownButton = false;
                    $item = &$option->add("addblankrow");
                    $item->Body = "<a class=\"ew-add-edit ew-add-blank-row\" title=\"" . HtmlTitle($Language->phrase("AddBlankRow")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("AddBlankRow")) . "\" data-ew-action=\"add-grid-row\">" . $Language->phrase("AddBlankRow") . "</a>";
                    $item->Visible = $Security->canAdd();
                    $this->ShowOtherOptions = $item->Visible;
                }
            }
            if ($this->CurrentMode == "view") { // Check view mode
                $option = $options["addedit"];
                $item = $option["add"];
                $this->ShowOtherOptions = $item && $item->Visible;
            }
    }

// Get upload files
    protected function getUploadFiles()
    {
        global $CurrentForm, $Language;
    }

    // Load default values
    protected function loadDefaultValues()
    {
        $this->User_Level->DefaultValue = 1;
        $this->Activated->DefaultValue = "N";
        $this->Locked->DefaultValue = "N";
        $this->Photo->Upload->Index = $this->RowIndex;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $CurrentForm->FormName = $this->FormName;
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

        // Check field name 'First_Name' first before field var 'x_First_Name'
        $val = $CurrentForm->hasValue("First_Name") ? $CurrentForm->getValue("First_Name") : $CurrentForm->getValue("x_First_Name");
        if (!$this->First_Name->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->First_Name->Visible = false; // Disable update for API request
            } else {
                $this->First_Name->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_First_Name")) {
            $this->First_Name->setOldValue($CurrentForm->getValue("o_First_Name"));
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
        if ($CurrentForm->hasValue("o_Last_Name")) {
            $this->Last_Name->setOldValue($CurrentForm->getValue("o_Last_Name"));
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
        if ($CurrentForm->hasValue("o__Email")) {
            $this->_Email->setOldValue($CurrentForm->getValue("o__Email"));
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
        if ($CurrentForm->hasValue("o_User_Level")) {
            $this->User_Level->setOldValue($CurrentForm->getValue("o_User_Level"));
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
        if ($CurrentForm->hasValue("o_Report_To")) {
            $this->Report_To->setOldValue($CurrentForm->getValue("o_Report_To"));
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
        if ($CurrentForm->hasValue("o_Activated")) {
            $this->Activated->setOldValue($CurrentForm->getValue("o_Activated"));
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
        if ($CurrentForm->hasValue("o_Locked")) {
            $this->Locked->setOldValue($CurrentForm->getValue("o_Locked"));
        }
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->_Username->CurrentValue = $this->_Username->FormValue;
        $this->First_Name->CurrentValue = $this->First_Name->FormValue;
        $this->Last_Name->CurrentValue = $this->Last_Name->FormValue;
        $this->_Email->CurrentValue = $this->_Email->FormValue;
        $this->User_Level->CurrentValue = $this->User_Level->FormValue;
        $this->Report_To->CurrentValue = $this->Report_To->FormValue;
        $this->Activated->CurrentValue = $this->Activated->FormValue;
        $this->Locked->CurrentValue = $this->Locked->FormValue;
    }

    // Load recordset
    public function loadRecordset($offset = -1, $rowcnt = -1)
    {
        // Load List page SQL (QueryBuilder)
        $sql = $this->getListSql();

        // Load recordset
        if ($offset > -1) {
            $sql->setFirstResult($offset);
        }
        if ($rowcnt > 0) {
            $sql->setMaxResults($rowcnt);
        }
        $result = $sql->execute();
        $rs = new Recordset($result, $sql);

        // Call Recordset Selected event
        $this->recordsetSelected($rs);
        return $rs;
    }

    // Load records as associative array
    public function loadRows($offset = -1, $rowcnt = -1)
    {
        // Load List page SQL (QueryBuilder)
        $sql = $this->getListSql();

        // Load recordset
        if ($offset > -1) {
            $sql->setFirstResult($offset);
        }
        if ($rowcnt > 0) {
            $sql->setMaxResults($rowcnt);
        }
        $result = $sql->execute();
        return $result->fetchAll(FetchMode::ASSOCIATIVE);
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
        $this->Photo->Upload->Index = $this->RowIndex;
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
        $this->ViewUrl = $this->getViewUrl();
        $this->EditUrl = $this->getEditUrl();
        $this->CopyUrl = $this->getCopyUrl();
        $this->DeleteUrl = $this->getDeleteUrl();

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // Username

        // Password

        // First_Name

        // Last_Name

        // Email

        // User_Level

        // Report_To

        // Activated

        // Locked

        // Profile

        // Photo

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
            $this->_Username->TooltipValue = "";

            // First_Name
            $this->First_Name->LinkCustomAttributes = "";
            $this->First_Name->HrefValue = "";
            $this->First_Name->TooltipValue = "";

            // Last_Name
            $this->Last_Name->LinkCustomAttributes = "";
            $this->Last_Name->HrefValue = "";
            $this->Last_Name->TooltipValue = "";

            // Email
            $this->_Email->LinkCustomAttributes = "";
            $this->_Email->HrefValue = "";
            $this->_Email->TooltipValue = "";

            // User_Level
            $this->User_Level->LinkCustomAttributes = "";
            $this->User_Level->HrefValue = "";
            $this->User_Level->TooltipValue = "";

            // Report_To
            $this->Report_To->LinkCustomAttributes = "";
            $this->Report_To->HrefValue = "";
            $this->Report_To->TooltipValue = "";

            // Activated
            $this->Activated->LinkCustomAttributes = "";
            $this->Activated->HrefValue = "";
            $this->Activated->TooltipValue = "";

            // Locked
            $this->Locked->LinkCustomAttributes = "";
            $this->Locked->HrefValue = "";
            $this->Locked->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_ADD) {
            // Username
            $this->_Username->setupEditAttributes();
            $this->_Username->EditCustomAttributes = "";
            if (!$Security->isAdmin() && $Security->isLoggedIn() && !$this->userIDAllow("grid")) { // Non system admin
            } else {
                if (!$this->_Username->Raw) {
                    $this->_Username->CurrentValue = HtmlDecode($this->_Username->CurrentValue);
                }
                $this->_Username->EditValue = HtmlEncode($this->_Username->CurrentValue);
                $this->_Username->PlaceHolder = RemoveHtml($this->_Username->caption());
            }

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
                $this->User_Level->OldValue = $this->User_Level->CurrentValue;
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
                if (trim(strval($this->Report_To->CurrentValue)) == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`Username`" . SearchString("=", $this->Report_To->CurrentValue, DATATYPE_STRING, "");
                }
                AddFilter($filterWrk, Container("users")->addParentUserIDFilter(""));
                $sqlWrk = $this->Report_To->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll();
                $arwrk = $rswrk;
                $this->Report_To->EditValue = $arwrk;
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

            // Add refer script

            // Username
            $this->_Username->LinkCustomAttributes = "";
            $this->_Username->HrefValue = "";

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
        } elseif ($this->RowType == ROWTYPE_EDIT) {
            // Username
            $this->_Username->setupEditAttributes();
            $this->_Username->EditCustomAttributes = "";
            if (!$this->_Username->Raw) {
                $this->_Username->CurrentValue = HtmlDecode($this->_Username->CurrentValue);
            }
            $this->_Username->EditValue = HtmlEncode($this->_Username->CurrentValue);
            $this->_Username->PlaceHolder = RemoveHtml($this->_Username->caption());

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
                $this->User_Level->OldValue = $this->User_Level->CurrentValue;
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

            // Edit refer script

            // Username
            $this->_Username->LinkCustomAttributes = "";
            $this->_Username->HrefValue = "";

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

    // Delete records based on current filter
    protected function deleteRows()
    {
        global $Language, $Security;
        if (!$Security->canDelete()) {
            $this->setFailureMessage($Language->phrase("NoDeletePermission")); // No delete permission
            return false;
        }
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        $rows = $conn->fetchAllAssociative($sql);
        if (count($rows) == 0) {
            $this->setFailureMessage($Language->phrase("NoRecord")); // No record found
            return false;
        }

        // Clone old rows
        $rsold = $rows;
        $successKeys = [];
        $failKeys = [];
        foreach ($rsold as $row) {
            $thisKey = "";
            if ($thisKey != "") {
                $thisKey .= Config("COMPOSITE_KEY_SEPARATOR");
            }
            $thisKey .= $row['Username'];

            // Call row deleting event
            $deleteRow = $this->rowDeleting($row);
            if ($deleteRow) { // Delete
                $deleteRow = $this->delete($row);
            }
            if ($deleteRow === false) {
                if ($this->UseTransaction) {
                    $successKeys = []; // Reset success keys
                    break;
                }
                $failKeys[] = $thisKey;
            } else {
                if (Config("DELETE_UPLOADED_FILES")) { // Delete old files
                    $this->deleteUploadedFiles($row);
                }

                // Call Row Deleted event
                $this->rowDeleted($row);
                $successKeys[] = $thisKey;
            }
        }

        // Any records deleted
        $deleteRows = count($successKeys) > 0;
        if (!$deleteRows) {
            // Set up error message
            if ($this->getSuccessMessage() != "" || $this->getFailureMessage() != "") {
                // Use the message, do nothing
            } elseif ($this->CancelMessage != "") {
                $this->setFailureMessage($this->CancelMessage);
                $this->CancelMessage = "";
            } else {
                $this->setFailureMessage($Language->phrase("DeleteCancelled"));
            }
        }

        // Write JSON for API request
        if (IsApi() && $deleteRows) {
            $row = $this->getRecordsFromRecordset($rsold);
            WriteJson(["success" => true, $this->TableVar => $row]);
        }
        return $deleteRows;
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
        }

        // Write JSON for API request
        if (IsApi() && $editRow) {
            $row = $this->getRecordsFromRecordset([$rsnew], true);
            WriteJson(["success" => true, $this->TableVar => $row]);
        }
        return $editRow;
    }

    // Add record
    protected function addRow($rsold = null)
    {
        global $Language, $Security;

        // Set up foreign key field value from Session
        if ($this->getCurrentMasterTable() == "userlevels") {
            $this->User_Level->CurrentValue = $this->User_Level->getSessionValue();
        }

        // Set new row
        $rsnew = [];

        // Username
        $this->_Username->setDbValueDef($rsnew, $this->_Username->CurrentValue, "", false);

        // First_Name
        $this->First_Name->setDbValueDef($rsnew, $this->First_Name->CurrentValue, null, false);

        // Last_Name
        $this->Last_Name->setDbValueDef($rsnew, $this->Last_Name->CurrentValue, null, false);

        // Email
        $this->_Email->setDbValueDef($rsnew, $this->_Email->CurrentValue, null, false);

        // User_Level
        if ($Security->canAdmin()) { // System admin
            $this->User_Level->setDbValueDef($rsnew, $this->User_Level->CurrentValue, 0, strval($this->User_Level->CurrentValue ?? "") == "");
        }

        // Report_To
        $this->Report_To->setDbValueDef($rsnew, $this->Report_To->CurrentValue, null, false);

        // Activated
        $tmpBool = $this->Activated->CurrentValue;
        if ($tmpBool != "Y" && $tmpBool != "N") {
            $tmpBool = !empty($tmpBool) ? "Y" : "N";
        }
        $this->Activated->setDbValueDef($rsnew, $tmpBool, "N", strval($this->Activated->CurrentValue ?? "") == "");

        // Locked
        $tmpBool = $this->Locked->CurrentValue;
        if ($tmpBool != "Y" && $tmpBool != "N") {
            $tmpBool = !empty($tmpBool) ? "Y" : "N";
        }
        $this->Locked->setDbValueDef($rsnew, $tmpBool, null, strval($this->Locked->CurrentValue ?? "") == "");

        // Update current values
        $this->setCurrentValues($rsnew);

        // Check if valid User ID
        $validUser = false;
        if ($Security->currentUserID() != "" && !EmptyValue($this->_Username->CurrentValue) && !$Security->isAdmin()) { // Non system admin
            $Security->addUserID($this->_Username->CurrentValue); // added by Masino Sinaga, in order to prevent message below
			$validUser = $Security->isValidUserID($this->_Username->CurrentValue);
            if (!$validUser) {
                $userIdMsg = str_replace("%c", CurrentUserID(), $Language->phrase("UnAuthorizedUserID"));
                $userIdMsg = str_replace("%u", $this->_Username->CurrentValue, $userIdMsg);
                $this->setFailureMessage($userIdMsg);
                return false;
            }
        }

        // Check if valid Parent User ID
        $validParentUser = false;
        if ($Security->currentUserID() != "" && !EmptyValue($this->Report_To->CurrentValue) && !$Security->isAdmin()) { // Non system admin
            $Security->addUserID($this->Report_To->CurrentValue); // added by Masino Sinaga, in order to prevent message below
			$validParentUser = $Security->isValidUserID($this->Report_To->CurrentValue);
            if (!$validParentUser) {
                $parentUserIdMsg = str_replace("%c", CurrentUserID(), $Language->phrase("UnAuthorizedParentUserID"));
                $parentUserIdMsg = str_replace("%p", $this->Report_To->CurrentValue, $parentUserIdMsg);
                $this->setFailureMessage($parentUserIdMsg);
                return false;
            }
        }

        // Check referential integrity for master table 'users'
        $validMasterRecord = true;
        $detailKeys = [];
        $detailKeys["User_Level"] = $this->User_Level->CurrentValue;
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
        $conn = $this->getConnection();

        // Load db values from old row
        $this->loadDbValues($rsold);
        if ($rsold) {
        }

        // Call Row Inserting event
        $insertRow = $this->rowInserting($rsold, $rsnew);

        // Check if key value entered
        if ($insertRow && $this->ValidateKey && strval($rsnew['Username']) == "") {
            $this->setFailureMessage($Language->phrase("InvalidKeyValue"));
            $insertRow = false;
        }

        // Check for duplicate key
        if ($insertRow && $this->ValidateKey) {
            $filter = $this->getRecordFilter($rsnew);
            $rsChk = $this->loadRs($filter)->fetch();
            if ($rsChk !== false) {
                $keyErrMsg = str_replace("%f", $filter, $Language->phrase("DupKey"));
                $this->setFailureMessage($keyErrMsg);
                $insertRow = false;
            }
        }
        if ($insertRow) {
            $addRow = $this->insert($rsnew);
            if ($addRow) {
            }
        } else {
            if ($this->getSuccessMessage() != "" || $this->getFailureMessage() != "") {
                // Use the message, do nothing
            } elseif ($this->CancelMessage != "") {
                $this->setFailureMessage($this->CancelMessage);
                $this->CancelMessage = "";
            } else {
                $this->setFailureMessage($Language->phrase("InsertCancelled"));
            }
            $addRow = false;
        }
        if ($addRow) {
            // Call Row Inserted event
            $this->rowInserted($rsold, $rsnew);
        }

        // Clean upload path if any
        if ($addRow) {
        }

        // Write JSON for API request
        if (IsApi() && $addRow) {
            $row = $this->getRecordsFromRecordset([$rsnew], true);
            WriteJson(["success" => true, $this->TableVar => $row]);
        }
        return $addRow;
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
        // Hide foreign keys
        $masterTblVar = $this->getCurrentMasterTable();
        if ($masterTblVar == "userlevels") {
            $masterTbl = Container("userlevels");
            $this->User_Level->Visible = false;
            if ($masterTbl->EventCancelled) {
                $this->EventCancelled = true;
            }
        }
        $this->DbMasterFilter = $this->getMasterFilterFromSession(); // Get master filter from session
        $this->DbDetailFilter = $this->getDetailFilterFromSession(); // Get detail filter from session
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

    // ListOptions Load event
    public function listOptionsLoad()
    {
        // Example:
        //$opt = &$this->ListOptions->Add("new");
        //$opt->Header = "xxx";
        //$opt->OnLeft = true; // Link on left
        //$opt->MoveTo(0); // Move to first column
    }

    // ListOptions Rendering event
    public function listOptionsRendering()
    {
        //Container("DetailTableGrid")->DetailAdd = (...condition...); // Set to true or false conditionally
        //Container("DetailTableGrid")->DetailEdit = (...condition...); // Set to true or false conditionally
        //Container("DetailTableGrid")->DetailView = (...condition...); // Set to true or false conditionally
    }

    // ListOptions Rendered event
    public function listOptionsRendered()
    {
        // Example:
        //$this->ListOptions["new"]->Body = "xxx";
    }
}
