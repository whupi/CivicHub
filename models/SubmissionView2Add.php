<?php

namespace PHPMaker2022\civichub2;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class SubmissionView2Add extends SubmissionView2
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'submission_view';

    // Page object name
    public $PageObjName = "SubmissionView2Add";

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

        // Table object (submission_view2)
        if (!isset($GLOBALS["submission_view2"]) || get_class($GLOBALS["submission_view2"]) == PROJECT_NAMESPACE . "submission_view2") {
            $GLOBALS["submission_view2"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'submission_view');
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
                $tbl = Container("submission_view2");
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
                    if ($pageName == "submissionview2view") {
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
            $key .= @$ar['Submission_ID'];
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
        if ($this->isAdd() || $this->isCopy() || $this->isGridAdd()) {
            $this->Submission_ID->Visible = false;
        }
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
    public $FormClassName = "ew-form ew-add-form";
    public $IsModal = false;
    public $IsMobileOrModal = false;
    public $DbMasterFilter = "";
    public $DbDetailFilter = "";
    public $StartRecord;
    public $Priv = 0;
    public $OldRecordset;
    public $CopyRecord;

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
        $this->Submission_ID->Visible = false;
        $this->_Title->setVisibility();
        $this->Category_ID->setVisibility();
        $this->Status->setVisibility();
        $this->_Abstract->setVisibility();
        $this->Tags->setVisibility();
        $this->Uploads->setVisibility();
        $this->Cover->setVisibility();
        $this->Updated_Username->Visible = false;
        $this->Updated_Last->Visible = false;
        $this->Updated_IP->Visible = false;
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
        $this->setupLookupOptions($this->Category_ID);
        $this->setupLookupOptions($this->Status);
        $this->setupLookupOptions($this->Tags);
        $this->setupLookupOptions($this->Updated_IP);

        // Load default values for add
        $this->loadDefaultValues();

        // Check modal
        if ($this->IsModal) {
            $SkipHeaderFooter = true;
        }
        $this->IsMobileOrModal = IsMobile() || $this->IsModal;
        $this->FormClassName = "ew-form ew-add-form";
        $postBack = false;

        // Set up current action
        if (IsApi()) {
            $this->CurrentAction = "insert"; // Add record directly
            $postBack = true;
        } elseif (Post("action") !== null) {
            $this->CurrentAction = Post("action"); // Get form action
            $this->setKey(Post($this->OldKeyName));
            $postBack = true;
        } else {
            // Load key values from QueryString
            if (($keyValue = Get("Submission_ID") ?? Route("Submission_ID")) !== null) {
                $this->Submission_ID->setQueryStringValue($keyValue);
            }
            $this->OldKey = $this->getKey(true); // Get from CurrentValue
            $this->CopyRecord = !EmptyValue($this->OldKey);
            if ($this->CopyRecord) {
                $this->CurrentAction = "copy"; // Copy record
            } else {
                $this->CurrentAction = "show"; // Display blank record
            }
        }

        // Load old record / default values
        $loaded = $this->loadOldRecord();

        // Load form values
        if ($postBack) {
            $this->loadFormValues(); // Load form values
        }

        // Validate form if post back
        if ($postBack) {
            if (!$this->validateForm()) {
                $this->EventCancelled = true; // Event cancelled
                $this->restoreFormValues(); // Restore form values
                if (IsApi()) {
                    $this->terminate();
                    return;
                } else {
                    $this->CurrentAction = "show"; // Form error, reset action
                }
            }
        }

        // Perform current action
        switch ($this->CurrentAction) {
            case "copy": // Copy an existing record
                if (!$loaded) { // Record not loaded
                    if ($this->getFailureMessage() == "") {
                        $this->setFailureMessage($Language->phrase("NoRecord")); // No record found
                    }
                    $this->terminate("submissionview2list"); // No matching record, return to list
                    return;
                }
                break;
            case "insert": // Add new record
                $this->SendEmail = true; // Send email on add success
                if ($this->addRow($this->OldRecordset)) { // Add successful
                    if ($this->getSuccessMessage() == "" && Post("addopt") != "1") { // Skip success message for addopt (done in JavaScript)
                        $this->setSuccessMessage($Language->phrase("AddSuccess")); // Set up success message
                    }
                    $returnUrl = $this->GetViewUrl();
                    if (GetPageName($returnUrl) == "submissionview2list") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "submissionview2view") {
                        $returnUrl = $this->getViewUrl(); // View page, return to View page with keyurl directly
                    }
                    if (IsApi()) { // Return to caller
                        $this->terminate(true);
                        return;
                    } else {
                        $this->terminate($returnUrl);
                        return;
                    }
                } elseif (IsApi()) { // API request, return
                    $this->terminate();
                    return;
                } else {
                    $this->EventCancelled = true; // Event cancelled
                    $this->restoreFormValues(); // Add failed, restore form values
                }
        }

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Render row based on row type
        $this->RowType = ROWTYPE_ADD; // Render add type

        // Render row
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
        $this->Uploads->Upload->Index = $CurrentForm->Index;
        $this->Uploads->Upload->uploadFile();
        $this->Uploads->CurrentValue = $this->Uploads->Upload->FileName;
        $this->Cover->Upload->Index = $CurrentForm->Index;
        $this->Cover->Upload->uploadFile();
        $this->Cover->CurrentValue = $this->Cover->Upload->FileName;
    }

    // Load default values
    protected function loadDefaultValues()
    {
        $this->Status->DefaultValue = "Pending";
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $validate = !Config("SERVER_VALIDATE");

        // Check field name 'Title' first before field var 'x__Title'
        $val = $CurrentForm->hasValue("Title") ? $CurrentForm->getValue("Title") : $CurrentForm->getValue("x__Title");
        if (!$this->_Title->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->_Title->Visible = false; // Disable update for API request
            } else {
                $this->_Title->setFormValue($val);
            }
        }

        // Check field name 'Category_ID' first before field var 'x_Category_ID'
        $val = $CurrentForm->hasValue("Category_ID") ? $CurrentForm->getValue("Category_ID") : $CurrentForm->getValue("x_Category_ID");
        if (!$this->Category_ID->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Category_ID->Visible = false; // Disable update for API request
            } else {
                $this->Category_ID->setFormValue($val);
            }
        }

        // Check field name 'Status' first before field var 'x_Status'
        $val = $CurrentForm->hasValue("Status") ? $CurrentForm->getValue("Status") : $CurrentForm->getValue("x_Status");
        if (!$this->Status->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Status->Visible = false; // Disable update for API request
            } else {
                $this->Status->setFormValue($val);
            }
        }

        // Check field name 'Abstract' first before field var 'x__Abstract'
        $val = $CurrentForm->hasValue("Abstract") ? $CurrentForm->getValue("Abstract") : $CurrentForm->getValue("x__Abstract");
        if (!$this->_Abstract->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->_Abstract->Visible = false; // Disable update for API request
            } else {
                $this->_Abstract->setFormValue($val);
            }
        }

        // Check field name 'Tags' first before field var 'x_Tags'
        $val = $CurrentForm->hasValue("Tags") ? $CurrentForm->getValue("Tags") : $CurrentForm->getValue("x_Tags");
        if (!$this->Tags->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Tags->Visible = false; // Disable update for API request
            } else {
                $this->Tags->setFormValue($val);
            }
        }

        // Check field name 'Submission_ID' first before field var 'x_Submission_ID'
        $val = $CurrentForm->hasValue("Submission_ID") ? $CurrentForm->getValue("Submission_ID") : $CurrentForm->getValue("x_Submission_ID");
        $this->getUploadFiles(); // Get upload files
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->_Title->CurrentValue = $this->_Title->FormValue;
        $this->Category_ID->CurrentValue = $this->Category_ID->FormValue;
        $this->Status->CurrentValue = $this->Status->FormValue;
        $this->_Abstract->CurrentValue = $this->_Abstract->FormValue;
        $this->Tags->CurrentValue = $this->Tags->FormValue;
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
            $res = $this->showOptionLink("add");
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
        $this->Submission_ID->setDbValue($row['Submission_ID']);
        $this->_Title->setDbValue($row['Title']);
        $this->Category_ID->setDbValue($row['Category_ID']);
        if (array_key_exists('EV__Category_ID', $row)) {
            $this->Category_ID->VirtualValue = $row['EV__Category_ID']; // Set up virtual field value
        } else {
            $this->Category_ID->VirtualValue = ""; // Clear value
        }
        $this->Status->setDbValue($row['Status']);
        $this->_Abstract->setDbValue($row['Abstract']);
        $this->Tags->setDbValue($row['Tags']);
        $this->Uploads->Upload->DbValue = $row['Uploads'];
        $this->Uploads->setDbValue($this->Uploads->Upload->DbValue);
        $this->Cover->Upload->DbValue = $row['Cover'];
        $this->Cover->setDbValue($this->Cover->Upload->DbValue);
        $this->Updated_Username->setDbValue($row['Updated_Username']);
        $this->Updated_Last->setDbValue($row['Updated_Last']);
        $this->Updated_IP->setDbValue($row['Updated_IP']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['Submission_ID'] = $this->Submission_ID->DefaultValue;
        $row['Title'] = $this->_Title->DefaultValue;
        $row['Category_ID'] = $this->Category_ID->DefaultValue;
        $row['Status'] = $this->Status->DefaultValue;
        $row['Abstract'] = $this->_Abstract->DefaultValue;
        $row['Tags'] = $this->Tags->DefaultValue;
        $row['Uploads'] = $this->Uploads->DefaultValue;
        $row['Cover'] = $this->Cover->DefaultValue;
        $row['Updated_Username'] = $this->Updated_Username->DefaultValue;
        $row['Updated_Last'] = $this->Updated_Last->DefaultValue;
        $row['Updated_IP'] = $this->Updated_IP->DefaultValue;
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

        // Submission_ID
        $this->Submission_ID->RowCssClass = "row";

        // Title
        $this->_Title->RowCssClass = "row";

        // Category_ID
        $this->Category_ID->RowCssClass = "row";

        // Status
        $this->Status->RowCssClass = "row";

        // Abstract
        $this->_Abstract->RowCssClass = "row";

        // Tags
        $this->Tags->RowCssClass = "row";

        // Uploads
        $this->Uploads->RowCssClass = "row";

        // Cover
        $this->Cover->RowCssClass = "row";

        // Updated_Username
        $this->Updated_Username->RowCssClass = "row";

        // Updated_Last
        $this->Updated_Last->RowCssClass = "row";

        // Updated_IP
        $this->Updated_IP->RowCssClass = "row";

        // View row
        if ($this->RowType == ROWTYPE_VIEW) {
            // Submission_ID
            $this->Submission_ID->ViewValue = $this->Submission_ID->CurrentValue;
            $this->Submission_ID->ViewCustomAttributes = "";

            // Title
            $this->_Title->ViewValue = $this->_Title->CurrentValue;
            $this->_Title->ViewCustomAttributes = "";

            // Category_ID
            if ($this->Category_ID->VirtualValue != "") {
                $this->Category_ID->ViewValue = $this->Category_ID->VirtualValue;
            } else {
                $curVal = strval($this->Category_ID->CurrentValue);
                if ($curVal != "") {
                    $this->Category_ID->ViewValue = $this->Category_ID->lookupCacheOption($curVal);
                    if ($this->Category_ID->ViewValue === null) { // Lookup from database
                        $filterWrk = "`Category_ID`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                        $sqlWrk = $this->Category_ID->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                        $conn = Conn();
                        $config = $conn->getConfiguration();
                        $config->setResultCacheImpl($this->Cache);
                        $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                        $ari = count($rswrk);
                        if ($ari > 0) { // Lookup values found
                            $arwrk = $this->Category_ID->Lookup->renderViewRow($rswrk[0]);
                            $this->Category_ID->ViewValue = $this->Category_ID->displayValue($arwrk);
                        } else {
                            $this->Category_ID->ViewValue = FormatNumber($this->Category_ID->CurrentValue, $this->Category_ID->formatPattern());
                        }
                    }
                } else {
                    $this->Category_ID->ViewValue = null;
                }
            }
            $this->Category_ID->ViewCustomAttributes = "";

            // Status
            if (strval($this->Status->CurrentValue) != "") {
                $this->Status->ViewValue = $this->Status->optionCaption($this->Status->CurrentValue);
            } else {
                $this->Status->ViewValue = null;
            }
            $this->Status->ViewCustomAttributes = "";

            // Abstract
            $this->_Abstract->ViewValue = $this->_Abstract->CurrentValue;
            $this->_Abstract->ViewCustomAttributes = "";

            // Tags
            $curVal = strval($this->Tags->CurrentValue);
            if ($curVal != "") {
                $this->Tags->ViewValue = $this->Tags->lookupCacheOption($curVal);
                if ($this->Tags->ViewValue === null) { // Lookup from database
                    $arwrk = explode(",", $curVal);
                    $filterWrk = "";
                    foreach ($arwrk as $wrk) {
                        if ($filterWrk != "") {
                            $filterWrk .= " OR ";
                        }
                        $filterWrk .= "`Goal_Title`" . SearchString("=", trim($wrk), DATATYPE_STRING, "");
                    }
                    $sqlWrk = $this->Tags->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $this->Tags->ViewValue = new OptionValues();
                        foreach ($rswrk as $row) {
                            $arwrk = $this->Tags->Lookup->renderViewRow($row);
                            $this->Tags->ViewValue->add($this->Tags->displayValue($arwrk));
                        }
                    } else {
                        $this->Tags->ViewValue = $this->Tags->CurrentValue;
                    }
                }
            } else {
                $this->Tags->ViewValue = null;
            }
            $this->Tags->ViewCustomAttributes = "";

            // Uploads
            if (!EmptyValue($this->Uploads->Upload->DbValue)) {
                $this->Uploads->ViewValue = $this->Uploads->Upload->DbValue;
            } else {
                $this->Uploads->ViewValue = "";
            }
            $this->Uploads->ViewCustomAttributes = "";

            // Cover
            if (!EmptyValue($this->Cover->Upload->DbValue)) {
                $this->Cover->ViewValue = $this->Cover->Upload->DbValue;
            } else {
                $this->Cover->ViewValue = "";
            }
            $this->Cover->ViewCustomAttributes = "";

            // Updated_Username
            $this->Updated_Username->ViewValue = $this->Updated_Username->CurrentValue;
            $this->Updated_Username->ViewCustomAttributes = "";

            // Updated_Last
            $this->Updated_Last->ViewValue = $this->Updated_Last->CurrentValue;
            $this->Updated_Last->ViewValue = FormatDateTime($this->Updated_Last->ViewValue, $this->Updated_Last->formatPattern());
            $this->Updated_Last->ViewCustomAttributes = "";

            // Updated_IP
            $curVal = strval($this->Updated_IP->CurrentValue);
            if ($curVal != "") {
                $this->Updated_IP->ViewValue = $this->Updated_IP->lookupCacheOption($curVal);
                if ($this->Updated_IP->ViewValue === null) { // Lookup from database
                    $filterWrk = "`Username`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                    $sqlWrk = $this->Updated_IP->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->Updated_IP->Lookup->renderViewRow($rswrk[0]);
                        $this->Updated_IP->ViewValue = $this->Updated_IP->displayValue($arwrk);
                    } else {
                        $this->Updated_IP->ViewValue = $this->Updated_IP->CurrentValue;
                    }
                }
            } else {
                $this->Updated_IP->ViewValue = null;
            }
            $this->Updated_IP->ViewCustomAttributes = "";

            // Title
            $this->_Title->LinkCustomAttributes = "";
            $this->_Title->HrefValue = "";

            // Category_ID
            $this->Category_ID->LinkCustomAttributes = "";
            $this->Category_ID->HrefValue = "";

            // Status
            $this->Status->LinkCustomAttributes = "";
            $this->Status->HrefValue = "";

            // Abstract
            $this->_Abstract->LinkCustomAttributes = "";
            $this->_Abstract->HrefValue = "";

            // Tags
            $this->Tags->LinkCustomAttributes = "";
            $this->Tags->HrefValue = "";

            // Uploads
            $this->Uploads->LinkCustomAttributes = "";
            $this->Uploads->HrefValue = "";
            $this->Uploads->ExportHrefValue = $this->Uploads->UploadPath . $this->Uploads->Upload->DbValue;

            // Cover
            $this->Cover->LinkCustomAttributes = "";
            $this->Cover->HrefValue = "";
            $this->Cover->ExportHrefValue = $this->Cover->UploadPath . $this->Cover->Upload->DbValue;
        } elseif ($this->RowType == ROWTYPE_ADD) {
            // Title
            $this->_Title->setupEditAttributes();
            $this->_Title->EditCustomAttributes = "";
            if (!$this->_Title->Raw) {
                $this->_Title->CurrentValue = HtmlDecode($this->_Title->CurrentValue);
            }
            $this->_Title->EditValue = HtmlEncode($this->_Title->CurrentValue);
            $this->_Title->PlaceHolder = RemoveHtml($this->_Title->caption());

            // Category_ID
            $this->Category_ID->setupEditAttributes();
            $this->Category_ID->EditCustomAttributes = "";
            $curVal = trim(strval($this->Category_ID->CurrentValue));
            if ($curVal != "") {
                $this->Category_ID->ViewValue = $this->Category_ID->lookupCacheOption($curVal);
            } else {
                $this->Category_ID->ViewValue = $this->Category_ID->Lookup !== null && is_array($this->Category_ID->lookupOptions()) ? $curVal : null;
            }
            if ($this->Category_ID->ViewValue !== null) { // Load from cache
                $this->Category_ID->EditValue = array_values($this->Category_ID->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`Category_ID`" . SearchString("=", $this->Category_ID->CurrentValue, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->Category_ID->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->Category_ID->EditValue = $arwrk;
            }
            $this->Category_ID->PlaceHolder = RemoveHtml($this->Category_ID->caption());

            // Status
            $this->Status->setupEditAttributes();
            $this->Status->EditCustomAttributes = "";
            $this->Status->EditValue = $this->Status->options(true);
            $this->Status->PlaceHolder = RemoveHtml($this->Status->caption());

            // Abstract
            $this->_Abstract->setupEditAttributes();
            $this->_Abstract->EditCustomAttributes = "";
            $this->_Abstract->EditValue = HtmlEncode($this->_Abstract->CurrentValue);
            $this->_Abstract->PlaceHolder = RemoveHtml($this->_Abstract->caption());

            // Tags
            $this->Tags->setupEditAttributes();
            $this->Tags->EditCustomAttributes = "";
            $curVal = trim(strval($this->Tags->CurrentValue));
            if ($curVal != "") {
                $this->Tags->ViewValue = $this->Tags->lookupCacheOption($curVal);
            } else {
                $this->Tags->ViewValue = $this->Tags->Lookup !== null && is_array($this->Tags->lookupOptions()) ? $curVal : null;
            }
            if ($this->Tags->ViewValue !== null) { // Load from cache
                $this->Tags->EditValue = array_values($this->Tags->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $arwrk = explode(",", $curVal);
                    $filterWrk = "";
                    foreach ($arwrk as $wrk) {
                        if ($filterWrk != "") {
                            $filterWrk .= " OR ";
                        }
                        $filterWrk .= "`Goal_Title`" . SearchString("=", trim($wrk), DATATYPE_STRING, "");
                    }
                }
                $sqlWrk = $this->Tags->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->Tags->EditValue = $arwrk;
            }
            $this->Tags->PlaceHolder = RemoveHtml($this->Tags->caption());

            // Uploads
            $this->Uploads->setupEditAttributes();
            $this->Uploads->EditCustomAttributes = "";
            if (!EmptyValue($this->Uploads->Upload->DbValue)) {
                $this->Uploads->EditValue = $this->Uploads->Upload->DbValue;
            } else {
                $this->Uploads->EditValue = "";
            }
            if (!EmptyValue($this->Uploads->CurrentValue)) {
                $this->Uploads->Upload->FileName = $this->Uploads->CurrentValue;
            }
            if ($this->isShow() || $this->isCopy()) {
                RenderUploadField($this->Uploads);
            }

            // Cover
            $this->Cover->setupEditAttributes();
            $this->Cover->EditCustomAttributes = "";
            if (!EmptyValue($this->Cover->Upload->DbValue)) {
                $this->Cover->EditValue = $this->Cover->Upload->DbValue;
            } else {
                $this->Cover->EditValue = "";
            }
            if (!EmptyValue($this->Cover->CurrentValue)) {
                $this->Cover->Upload->FileName = $this->Cover->CurrentValue;
            }
            if ($this->isShow() || $this->isCopy()) {
                RenderUploadField($this->Cover);
            }

            // Add refer script

            // Title
            $this->_Title->LinkCustomAttributes = "";
            $this->_Title->HrefValue = "";

            // Category_ID
            $this->Category_ID->LinkCustomAttributes = "";
            $this->Category_ID->HrefValue = "";

            // Status
            $this->Status->LinkCustomAttributes = "";
            $this->Status->HrefValue = "";

            // Abstract
            $this->_Abstract->LinkCustomAttributes = "";
            $this->_Abstract->HrefValue = "";

            // Tags
            $this->Tags->LinkCustomAttributes = "";
            $this->Tags->HrefValue = "";

            // Uploads
            $this->Uploads->LinkCustomAttributes = "";
            $this->Uploads->HrefValue = "";
            $this->Uploads->ExportHrefValue = $this->Uploads->UploadPath . $this->Uploads->Upload->DbValue;

            // Cover
            $this->Cover->LinkCustomAttributes = "";
            $this->Cover->HrefValue = "";
            $this->Cover->ExportHrefValue = $this->Cover->UploadPath . $this->Cover->Upload->DbValue;
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
        if ($this->_Title->Required) {
            if (!$this->_Title->IsDetailKey && EmptyValue($this->_Title->FormValue)) {
                $this->_Title->addErrorMessage(str_replace("%s", $this->_Title->caption(), $this->_Title->RequiredErrorMessage));
            }
        }
        if ($this->Category_ID->Required) {
            if (!$this->Category_ID->IsDetailKey && EmptyValue($this->Category_ID->FormValue)) {
                $this->Category_ID->addErrorMessage(str_replace("%s", $this->Category_ID->caption(), $this->Category_ID->RequiredErrorMessage));
            }
        }
        if ($this->Status->Required) {
            if (!$this->Status->IsDetailKey && EmptyValue($this->Status->FormValue)) {
                $this->Status->addErrorMessage(str_replace("%s", $this->Status->caption(), $this->Status->RequiredErrorMessage));
            }
        }
        if ($this->_Abstract->Required) {
            if (!$this->_Abstract->IsDetailKey && EmptyValue($this->_Abstract->FormValue)) {
                $this->_Abstract->addErrorMessage(str_replace("%s", $this->_Abstract->caption(), $this->_Abstract->RequiredErrorMessage));
            }
        }
        if ($this->Tags->Required) {
            if ($this->Tags->FormValue == "") {
                $this->Tags->addErrorMessage(str_replace("%s", $this->Tags->caption(), $this->Tags->RequiredErrorMessage));
            }
        }
        if ($this->Uploads->Required) {
            if ($this->Uploads->Upload->FileName == "" && !$this->Uploads->Upload->KeepFile) {
                $this->Uploads->addErrorMessage(str_replace("%s", $this->Uploads->caption(), $this->Uploads->RequiredErrorMessage));
            }
        }
        if ($this->Cover->Required) {
            if ($this->Cover->Upload->FileName == "" && !$this->Cover->Upload->KeepFile) {
                $this->Cover->addErrorMessage(str_replace("%s", $this->Cover->caption(), $this->Cover->RequiredErrorMessage));
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

    // Add record
    protected function addRow($rsold = null)
    {
        global $Language, $Security;

        // Set new row
        $rsnew = [];

        // Title
        $this->_Title->setDbValueDef($rsnew, $this->_Title->CurrentValue, "", false);

        // Category_ID
        $this->Category_ID->setDbValueDef($rsnew, $this->Category_ID->CurrentValue, 0, false);

        // Status
        $this->Status->setDbValueDef($rsnew, $this->Status->CurrentValue, "", strval($this->Status->CurrentValue ?? "") == "");

        // Abstract
        $this->_Abstract->setDbValueDef($rsnew, $this->_Abstract->CurrentValue, "", false);

        // Tags
        $this->Tags->setDbValueDef($rsnew, $this->Tags->CurrentValue, "", false);

        // Uploads
        if ($this->Uploads->Visible && !$this->Uploads->Upload->KeepFile) {
            $this->Uploads->Upload->DbValue = ""; // No need to delete old file
            if ($this->Uploads->Upload->FileName == "") {
                $rsnew['Uploads'] = null;
            } else {
                $rsnew['Uploads'] = $this->Uploads->Upload->FileName;
            }
        }

        // Cover
        if ($this->Cover->Visible && !$this->Cover->Upload->KeepFile) {
            $this->Cover->Upload->DbValue = ""; // No need to delete old file
            if ($this->Cover->Upload->FileName == "") {
                $rsnew['Cover'] = null;
            } else {
                $rsnew['Cover'] = $this->Cover->Upload->FileName;
            }
        }

        // Updated_Username
        if (!$Security->isAdmin() && $Security->isLoggedIn()) { // Non system admin
            $rsnew['Updated_Username'] = CurrentUserID();
        }
        if ($this->Uploads->Visible && !$this->Uploads->Upload->KeepFile) {
            $oldFiles = EmptyValue($this->Uploads->Upload->DbValue) ? [] : explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $this->Uploads->htmlDecode(strval($this->Uploads->Upload->DbValue)));
            if (!EmptyValue($this->Uploads->Upload->FileName)) {
                $newFiles = explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), strval($this->Uploads->Upload->FileName));
                $NewFileCount = count($newFiles);
                for ($i = 0; $i < $NewFileCount; $i++) {
                    if ($newFiles[$i] != "") {
                        $file = $newFiles[$i];
                        $tempPath = UploadTempPath($this->Uploads, $this->Uploads->Upload->Index);
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
                            $file1 = UniqueFilename($this->Uploads->physicalUploadPath(), $file); // Get new file name
                            if ($file1 != $file) { // Rename temp file
                                while (file_exists($tempPath . $file1) || file_exists($this->Uploads->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                    $file1 = UniqueFilename([$this->Uploads->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                }
                                rename($tempPath . $file, $tempPath . $file1);
                                $newFiles[$i] = $file1;
                            }
                        }
                    }
                }
                $this->Uploads->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                $this->Uploads->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                $this->Uploads->setDbValueDef($rsnew, $this->Uploads->Upload->FileName, null, false);
            }
        }
        if ($this->Cover->Visible && !$this->Cover->Upload->KeepFile) {
            $oldFiles = EmptyValue($this->Cover->Upload->DbValue) ? [] : [$this->Cover->htmlDecode($this->Cover->Upload->DbValue)];
            if (!EmptyValue($this->Cover->Upload->FileName)) {
                $newFiles = [$this->Cover->Upload->FileName];
                $NewFileCount = count($newFiles);
                for ($i = 0; $i < $NewFileCount; $i++) {
                    if ($newFiles[$i] != "") {
                        $file = $newFiles[$i];
                        $tempPath = UploadTempPath($this->Cover, $this->Cover->Upload->Index);
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
                            $file1 = UniqueFilename($this->Cover->physicalUploadPath(), $file); // Get new file name
                            if ($file1 != $file) { // Rename temp file
                                while (file_exists($tempPath . $file1) || file_exists($this->Cover->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                    $file1 = UniqueFilename([$this->Cover->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                }
                                rename($tempPath . $file, $tempPath . $file1);
                                $newFiles[$i] = $file1;
                            }
                        }
                    }
                }
                $this->Cover->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                $this->Cover->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                $this->Cover->setDbValueDef($rsnew, $this->Cover->Upload->FileName, null, false);
            }
        }

        // Update current values
        $this->setCurrentValues($rsnew);
        if ($this->_Abstract->CurrentValue != "") { // Check field with unique index
            $filter = "(`Abstract` = '" . AdjustSql($this->_Abstract->CurrentValue, $this->Dbid) . "')";
            $rsChk = $this->loadRs($filter)->fetch();
            if ($rsChk !== false) {
                $idxErrMsg = str_replace("%f", $this->_Abstract->caption(), $Language->phrase("DupIndex"));
                $idxErrMsg = str_replace("%v", $this->_Abstract->CurrentValue, $idxErrMsg);
                $this->setFailureMessage($idxErrMsg);
                return false;
            }
        }
        $conn = $this->getConnection();

        // Load db values from old row
        $this->loadDbValues($rsold);
        if ($rsold) {
        }

        // Call Row Inserting event
        $insertRow = $this->rowInserting($rsold, $rsnew);
        if ($insertRow) {
            $addRow = $this->insert($rsnew);
            if ($addRow) {
                if ($this->Uploads->Visible && !$this->Uploads->Upload->KeepFile) {
                    $oldFiles = EmptyValue($this->Uploads->Upload->DbValue) ? [] : explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $this->Uploads->htmlDecode(strval($this->Uploads->Upload->DbValue)));
                    if (!EmptyValue($this->Uploads->Upload->FileName)) {
                        $newFiles = explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $this->Uploads->Upload->FileName);
                        $newFiles2 = explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $this->Uploads->htmlDecode($rsnew['Uploads']));
                        $newFileCount = count($newFiles);
                        for ($i = 0; $i < $newFileCount; $i++) {
                            if ($newFiles[$i] != "") {
                                $file = UploadTempPath($this->Uploads, $this->Uploads->Upload->Index) . $newFiles[$i];
                                if (file_exists($file)) {
                                    if (@$newFiles2[$i] != "") { // Use correct file name
                                        $newFiles[$i] = $newFiles2[$i];
                                    }
                                    if (!$this->Uploads->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                @unlink($this->Uploads->oldPhysicalUploadPath() . $oldFile);
                            }
                        }
                    }
                }
                if ($this->Cover->Visible && !$this->Cover->Upload->KeepFile) {
                    $oldFiles = EmptyValue($this->Cover->Upload->DbValue) ? [] : [$this->Cover->htmlDecode($this->Cover->Upload->DbValue)];
                    if (!EmptyValue($this->Cover->Upload->FileName)) {
                        $newFiles = [$this->Cover->Upload->FileName];
                        $newFiles2 = [$this->Cover->htmlDecode($rsnew['Cover'])];
                        $newFileCount = count($newFiles);
                        for ($i = 0; $i < $newFileCount; $i++) {
                            if ($newFiles[$i] != "") {
                                $file = UploadTempPath($this->Cover, $this->Cover->Upload->Index) . $newFiles[$i];
                                if (file_exists($file)) {
                                    if (@$newFiles2[$i] != "") { // Use correct file name
                                        $newFiles[$i] = $newFiles2[$i];
                                    }
                                    if (!$this->Cover->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                @unlink($this->Cover->oldPhysicalUploadPath() . $oldFile);
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
            // Uploads
            CleanUploadTempPath($this->Uploads, $this->Uploads->Upload->Index);

            // Cover
            CleanUploadTempPath($this->Cover, $this->Cover->Upload->Index);
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
            return $Security->isValidUserID($this->Updated_Username->CurrentValue);
        }
        return true;
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("index");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("submissionview2list"), "", $this->TableVar, true);
        $pageId = ($this->isCopy()) ? "Copy" : "Add";
        $Breadcrumb->add("add", $pageId, $url);
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
                case "x_Category_ID":
                    break;
                case "x_Status":
                    break;
                case "x_Tags":
                    break;
                case "x_Updated_IP":
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
}
