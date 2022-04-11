<?php

namespace PHPMaker2022\civichub2;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Table class for submission_monitor
 */
class SubmissionMonitor extends DbTable
{
    protected $SqlFrom = "";
    protected $SqlSelect = null;
    protected $SqlSelectList = null;
    protected $SqlWhere = "";
    protected $SqlGroupBy = "";
    protected $SqlHaving = "";
    protected $SqlOrderBy = "";
    public $UseSessionForListSql = true;

    // Column CSS classes
    public $LeftColumnClass = "col-sm-2 col-form-label ew-label";
    public $RightColumnClass = "col-sm-10";
    public $OffsetColumnClass = "col-sm-10 offset-sm-2";
    public $TableLeftColumnClass = "w-col-2";

    // Export
    public $ExportDoc;

    // Fields
    public $Monitor_ID;
    public $Submission_ID;
    public $Status;
    public $Taskings;
    public $Organisations;
    public $Start_Date;
    public $Finish_Date;
    public $Uploads;
    public $Updated_Username;
    public $Updated_Last;
    public $Updated_IP;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage, $CurrentLocale;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'submission_monitor';
        $this->TableName = 'submission_monitor';
        $this->TableType = 'TABLE';

        // Update Table
        $this->UpdateTable = "`submission_monitor`";
        $this->Dbid = 'DB';
        $this->ExportAll = true;
        $this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
        $this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
        $this->ExportPageSize = "a4"; // Page size (PDF only)
        $this->ExportExcelPageOrientation = ""; // Page orientation (PhpSpreadsheet only)
        $this->ExportExcelPageSize = ""; // Page size (PhpSpreadsheet only)
        $this->ExportWordVersion = 12; // Word version (PHPWord only)
        $this->ExportWordPageOrientation = "portrait"; // Page orientation (PHPWord only)
        $this->ExportWordPageSize = "A4"; // Page orientation (PHPWord only)
        $this->ExportWordColumnWidth = null; // Cell width (PHPWord only)
        $this->DetailAdd = false; // Allow detail add
        $this->DetailEdit = false; // Allow detail edit
        $this->DetailView = false; // Allow detail view
        $this->ShowMultipleDetails = false; // Show multiple details
        $this->GridAddRowCount = 5;
        $this->AllowAddDeleteRow = true; // Allow add/delete row
        $this->UserIDAllowSecurity = Config("DEFAULT_USER_ID_ALLOW_SECURITY"); // Default User ID allowed permissions
        $this->BasicSearch = new BasicSearch($this->TableVar);

        // Monitor_ID
        $this->Monitor_ID = new DbField(
            'submission_monitor',
            'submission_monitor',
            'x_Monitor_ID',
            'Monitor_ID',
            '`Monitor_ID`',
            '`Monitor_ID`',
            19,
            11,
            -1,
            false,
            '`Monitor_ID`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'NO'
        );
        $this->Monitor_ID->InputTextType = "text";
        $this->Monitor_ID->IsAutoIncrement = true; // Autoincrement field
        $this->Monitor_ID->IsPrimaryKey = true; // Primary key field
        $this->Monitor_ID->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['Monitor_ID'] = &$this->Monitor_ID;

        // Submission_ID
        $this->Submission_ID = new DbField(
            'submission_monitor',
            'submission_monitor',
            'x_Submission_ID',
            'Submission_ID',
            '`Submission_ID`',
            '`Submission_ID`',
            3,
            11,
            -1,
            false,
            '`Submission_ID`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'SELECT'
        );
        $this->Submission_ID->InputTextType = "text";
        $this->Submission_ID->IsForeignKey = true; // Foreign key field
        $this->Submission_ID->Nullable = false; // NOT NULL field
        $this->Submission_ID->Required = true; // Required field
        $this->Submission_ID->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->Submission_ID->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->Submission_ID->Lookup = new Lookup('Submission_ID', 'submission_view2', false, 'Submission_ID', ["Title","","",""], [], [], [], [], [], [], '`Title`', '', "`Title`");
        $this->Submission_ID->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['Submission_ID'] = &$this->Submission_ID;

        // Status
        $this->Status = new DbField(
            'submission_monitor',
            'submission_monitor',
            'x_Status',
            'Status',
            '`Status`',
            '`Status`',
            202,
            11,
            -1,
            false,
            '`Status`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'SELECT'
        );
        $this->Status->InputTextType = "text";
        $this->Status->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->Status->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->Status->Lookup = new Lookup('Status', 'submission_monitor', false, '', ["","","",""], [], [], [], [], [], [], '', '', "");
        $this->Status->OptionCount = 4;
        $this->Fields['Status'] = &$this->Status;

        // Taskings
        $this->Taskings = new DbField(
            'submission_monitor',
            'submission_monitor',
            'x_Taskings',
            'Taskings',
            '`Taskings`',
            '`Taskings`',
            201,
            65535,
            -1,
            false,
            '`Taskings`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXTAREA'
        );
        $this->Taskings->InputTextType = "text";
        $this->Fields['Taskings'] = &$this->Taskings;

        // Organisations
        $this->Organisations = new DbField(
            'submission_monitor',
            'submission_monitor',
            'x_Organisations',
            'Organisations',
            '`Organisations`',
            '`Organisations`',
            200,
            255,
            -1,
            false,
            '`Organisations`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'SELECT'
        );
        $this->Organisations->InputTextType = "text";
        $this->Organisations->SelectMultiple = true; // Multiple select
        $this->Organisations->Lookup = new Lookup('Organisations', 'ref_organisation', false, 'Organisation', ["Country","Organisation","",""], [], [], [], [], [], [], '`Organisation`', '', "CONCAT(COALESCE(`Country`, ''),'" . ValueSeparator(1, $this->Organisations) . "',COALESCE(`Organisation`,''))");
        $this->Fields['Organisations'] = &$this->Organisations;

        // Start_Date
        $this->Start_Date = new DbField(
            'submission_monitor',
            'submission_monitor',
            'x_Start_Date',
            'Start_Date',
            '`Start_Date`',
            CastDateFieldForLike("`Start_Date`", 0, "DB"),
            133,
            10,
            0,
            false,
            '`Start_Date`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->Start_Date->InputTextType = "text";
        $this->Start_Date->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->Fields['Start_Date'] = &$this->Start_Date;

        // Finish_Date
        $this->Finish_Date = new DbField(
            'submission_monitor',
            'submission_monitor',
            'x_Finish_Date',
            'Finish_Date',
            '`Finish_Date`',
            CastDateFieldForLike("`Finish_Date`", 0, "DB"),
            133,
            10,
            0,
            false,
            '`Finish_Date`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->Finish_Date->InputTextType = "text";
        $this->Finish_Date->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->Fields['Finish_Date'] = &$this->Finish_Date;

        // Uploads
        $this->Uploads = new DbField(
            'submission_monitor',
            'submission_monitor',
            'x_Uploads',
            'Uploads',
            '`Uploads`',
            '`Uploads`',
            200,
            255,
            -1,
            true,
            '`Uploads`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'FILE'
        );
        $this->Uploads->InputTextType = "text";
        $this->Uploads->UploadAllowedFileExt = "CSV, XLS, XLSX, DOC, DOCX. PDF, PPT, PPTX";
        $this->Uploads->UploadMultiple = true;
        $this->Uploads->Upload->UploadMultiple = true;
        $this->Uploads->UploadMaxFileCount = 0;
        $this->Fields['Uploads'] = &$this->Uploads;

        // Updated_Username
        $this->Updated_Username = new DbField(
            'submission_monitor',
            'submission_monitor',
            'x_Updated_Username',
            'Updated_Username',
            '`Updated_Username`',
            '`Updated_Username`',
            200,
            50,
            -1,
            false,
            '`Updated_Username`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->Updated_Username->InputTextType = "text";
        $this->Fields['Updated_Username'] = &$this->Updated_Username;

        // Updated_Last
        $this->Updated_Last = new DbField(
            'submission_monitor',
            'submission_monitor',
            'x_Updated_Last',
            'Updated_Last',
            '`Updated_Last`',
            CastDateFieldForLike("`Updated_Last`", 0, "DB"),
            135,
            19,
            0,
            false,
            '`Updated_Last`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'HIDDEN'
        );
        $this->Updated_Last->InputTextType = "text";
        $this->Updated_Last->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->Fields['Updated_Last'] = &$this->Updated_Last;

        // Updated_IP
        $this->Updated_IP = new DbField(
            'submission_monitor',
            'submission_monitor',
            'x_Updated_IP',
            'Updated_IP',
            '`Updated_IP`',
            '`Updated_IP`',
            200,
            50,
            -1,
            false,
            '`Updated_IP`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'HIDDEN'
        );
        $this->Updated_IP->InputTextType = "text";
        $this->Fields['Updated_IP'] = &$this->Updated_IP;

        // Add Doctrine Cache
        $this->Cache = new ArrayCache();
        $this->CacheProfile = new \Doctrine\DBAL\Cache\QueryCacheProfile(0, $this->TableVar);
    }

    // Field Visibility
    public function getFieldVisibility($fldParm)
    {
        global $Security;
        return $this->$fldParm->Visible; // Returns original value
    }

    // Set left column class (must be predefined col-*-* classes of Bootstrap grid system)
    public function setLeftColumnClass($class)
    {
        if (preg_match('/^col\-(\w+)\-(\d+)$/', $class, $match)) {
            $this->LeftColumnClass = $class . " col-form-label ew-label";
            $this->RightColumnClass = "col-" . $match[1] . "-" . strval(12 - (int)$match[2]);
            $this->OffsetColumnClass = $this->RightColumnClass . " " . str_replace("col-", "offset-", $class);
            $this->TableLeftColumnClass = preg_replace('/^col-\w+-(\d+)$/', "w-col-$1", $class); // Change to w-col-*
        }
    }

    // Single column sort
    public function updateSort(&$fld)
    {
        if ($this->CurrentOrder == $fld->Name) {
            $sortField = $fld->Expression;
            $lastSort = $fld->getSort();
            if (in_array($this->CurrentOrderType, ["ASC", "DESC", "NO"])) {
                $curSort = $this->CurrentOrderType;
            } else {
                $curSort = $lastSort;
            }
            $orderBy = in_array($curSort, ["ASC", "DESC"]) ? $sortField . " " . $curSort : "";
            $this->setSessionOrderBy($orderBy); // Save to Session
        }
    }

    // Update field sort
    public function updateFieldSort()
    {
        $orderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
        $flds = GetSortFields($orderBy);
        foreach ($this->Fields as $field) {
            $fldSort = "";
            foreach ($flds as $fld) {
                if ($fld[0] == $field->Expression || $fld[0] == $field->VirtualExpression) {
                    $fldSort = $fld[1];
                }
            }
            $field->setSort($fldSort);
        }
    }

    // Current master table name
    public function getCurrentMasterTable()
    {
        return Session(PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_MASTER_TABLE"));
    }

    public function setCurrentMasterTable($v)
    {
        $_SESSION[PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_MASTER_TABLE")] = $v;
    }

    // Get master WHERE clause from session values
    public function getMasterFilterFromSession()
    {
        // Master filter
        $masterFilter = "";
        if ($this->getCurrentMasterTable() == "submission") {
            if ($this->Submission_ID->getSessionValue() != "") {
                $masterFilter .= "" . GetForeignKeySql("`Submission_ID`", $this->Submission_ID->getSessionValue(), DATATYPE_NUMBER, "DB");
            } else {
                return "";
            }
        }
        return $masterFilter;
    }

    // Get detail WHERE clause from session values
    public function getDetailFilterFromSession()
    {
        // Detail filter
        $detailFilter = "";
        if ($this->getCurrentMasterTable() == "submission") {
            if ($this->Submission_ID->getSessionValue() != "") {
                $detailFilter .= "" . GetForeignKeySql("`Submission_ID`", $this->Submission_ID->getSessionValue(), DATATYPE_NUMBER, "DB");
            } else {
                return "";
            }
        }
        return $detailFilter;
    }

    /**
     * Get master filter
     *
     * @param object $masterTable Master Table
     * @param array $keys Detail Keys
     * @return mixed NULL is returned if all keys are empty, Empty string is returned if some keys are empty and is required
     */
    public function getMasterFilter($masterTable, $keys)
    {
        $validKeys = true;
        switch ($masterTable->TableVar) {
            case "submission":
                $key = $keys["Submission_ID"] ?? "";
                if (EmptyValue($key)) {
                    if ($masterTable->Submission_ID->Required) { // Required field and empty value
                        return ""; // Return empty filter
                    }
                    $validKeys = false;
                } elseif (!$validKeys) { // Already has empty key
                    return ""; // Return empty filter
                }
                if ($validKeys) {
                    return "`Submission_ID`=" . QuotedValue($keys["Submission_ID"], $masterTable->Submission_ID->DataType, $masterTable->Dbid);
                }
                break;
        }
        return null; // All null values and no required fields
    }

    // Get detail filter
    public function getDetailFilter($masterTable)
    {
        switch ($masterTable->TableVar) {
            case "submission":
                return "`Submission_ID`=" . QuotedValue($masterTable->Submission_ID->DbValue, $this->Submission_ID->DataType, $this->Dbid);
        }
        return "";
    }

    // Table level SQL
    public function getSqlFrom() // From
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`submission_monitor`";
    }

    public function sqlFrom() // For backward compatibility
    {
        return $this->getSqlFrom();
    }

    public function setSqlFrom($v)
    {
        $this->SqlFrom = $v;
    }

    public function getSqlSelect() // Select
    {
        return $this->SqlSelect ?? $this->getQueryBuilder()->select("*");
    }

    public function sqlSelect() // For backward compatibility
    {
        return $this->getSqlSelect();
    }

    public function setSqlSelect($v)
    {
        $this->SqlSelect = $v;
    }

    public function getSqlWhere() // Where
    {
        $where = ($this->SqlWhere != "") ? $this->SqlWhere : "";
        $this->DefaultFilter = "";
        AddFilter($where, $this->DefaultFilter);
        return $where;
    }

    public function sqlWhere() // For backward compatibility
    {
        return $this->getSqlWhere();
    }

    public function setSqlWhere($v)
    {
        $this->SqlWhere = $v;
    }

    public function getSqlGroupBy() // Group By
    {
        return ($this->SqlGroupBy != "") ? $this->SqlGroupBy : "";
    }

    public function sqlGroupBy() // For backward compatibility
    {
        return $this->getSqlGroupBy();
    }

    public function setSqlGroupBy($v)
    {
        $this->SqlGroupBy = $v;
    }

    public function getSqlHaving() // Having
    {
        return ($this->SqlHaving != "") ? $this->SqlHaving : "";
    }

    public function sqlHaving() // For backward compatibility
    {
        return $this->getSqlHaving();
    }

    public function setSqlHaving($v)
    {
        $this->SqlHaving = $v;
    }

    public function getSqlOrderBy() // Order By
    {
        return ($this->SqlOrderBy != "") ? $this->SqlOrderBy : "";
    }

    public function sqlOrderBy() // For backward compatibility
    {
        return $this->getSqlOrderBy();
    }

    public function setSqlOrderBy($v)
    {
        $this->SqlOrderBy = $v;
    }

    // Apply User ID filters
    public function applyUserIDFilters($filter, $id = "")
    {
        global $Security;
        // Add User ID filter
        if ($Security->currentUserID() != "" && !$Security->isAdmin()) { // Non system admin
            $filter = $this->addUserIDFilter($filter, $id);
        }
        return $filter;
    }

    // Check if User ID security allows view all
    public function userIDAllow($id = "")
    {
        $allow = $this->UserIDAllowSecurity;
        switch ($id) {
            case "add":
            case "copy":
            case "gridadd":
            case "register":
            case "addopt":
                return (($allow & 1) == 1);
            case "edit":
            case "gridedit":
            case "update":
            case "changepassword":
            case "resetpassword":
                return (($allow & 4) == 4);
            case "delete":
                return (($allow & 2) == 2);
            case "view":
                return (($allow & 32) == 32);
            case "search":
                return (($allow & 64) == 64);
            case "lookup":
                return (($allow & 256) == 256);
            default:
                return (($allow & 8) == 8);
        }
    }

    /**
     * Get record count
     *
     * @param string|QueryBuilder $sql SQL or QueryBuilder
     * @param mixed $c Connection
     * @return int
     */
    public function getRecordCount($sql, $c = null)
    {
        $cnt = -1;
        $rs = null;
        if ($sql instanceof QueryBuilder) { // Query builder
            $sqlwrk = clone $sql;
            $sqlwrk = $sqlwrk->resetQueryPart("orderBy")->getSQL();
        } else {
            $sqlwrk = $sql;
        }
        $pattern = '/^SELECT\s([\s\S]+)\sFROM\s/i';
        // Skip Custom View / SubQuery / SELECT DISTINCT / ORDER BY
        if (
            ($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') &&
            preg_match($pattern, $sqlwrk) && !preg_match('/\(\s*(SELECT[^)]+)\)/i', $sqlwrk) &&
            !preg_match('/^\s*select\s+distinct\s+/i', $sqlwrk) && !preg_match('/\s+order\s+by\s+/i', $sqlwrk)
        ) {
            $sqlwrk = "SELECT COUNT(*) FROM " . preg_replace($pattern, "", $sqlwrk);
        } else {
            $sqlwrk = "SELECT COUNT(*) FROM (" . $sqlwrk . ") COUNT_TABLE";
        }
        $conn = $c ?? $this->getConnection();
        $cnt = $conn->fetchOne($sqlwrk);
        if ($cnt !== false) {
            return (int)$cnt;
        }

        // Unable to get count by SELECT COUNT(*), execute the SQL to get record count directly
        return ExecuteRecordCount($sql, $conn);
    }

    // Get SQL
    public function getSql($where, $orderBy = "")
    {
        return $this->buildSelectSql(
            $this->getSqlSelect(),
            $this->getSqlFrom(),
            $this->getSqlWhere(),
            $this->getSqlGroupBy(),
            $this->getSqlHaving(),
            $this->getSqlOrderBy(),
            $where,
            $orderBy
        )->getSQL();
    }

    // Table SQL
    public function getCurrentSql()
    {
        $filter = $this->CurrentFilter;
        $filter = $this->applyUserIDFilters($filter);
        $sort = $this->getSessionOrderBy();
        return $this->getSql($filter, $sort);
    }

    /**
     * Table SQL with List page filter
     *
     * @return QueryBuilder
     */
    public function getListSql()
    {
        $filter = $this->UseSessionForListSql ? $this->getSessionWhere() : "";
        AddFilter($filter, $this->CurrentFilter);
        $filter = $this->applyUserIDFilters($filter);
        $this->recordsetSelecting($filter);
        $select = $this->getSqlSelect();
        $from = $this->getSqlFrom();
        $sort = $this->UseSessionForListSql ? $this->getSessionOrderBy() : "";
        $this->Sort = $sort;
        return $this->buildSelectSql(
            $select,
            $from,
            $this->getSqlWhere(),
            $this->getSqlGroupBy(),
            $this->getSqlHaving(),
            $this->getSqlOrderBy(),
            $filter,
            $sort
        );
    }

    // Get ORDER BY clause
    public function getOrderBy()
    {
        $orderBy = $this->getSqlOrderBy();
        $sort = $this->getSessionOrderBy();
        if ($orderBy != "" && $sort != "") {
            $orderBy .= ", " . $sort;
        } elseif ($sort != "") {
            $orderBy = $sort;
        }
        return $orderBy;
    }

    // Get record count based on filter (for detail record count in master table pages)
    public function loadRecordCount($filter)
    {
        $origFilter = $this->CurrentFilter;
        $this->CurrentFilter = $filter;
        $this->recordsetSelecting($this->CurrentFilter);
        $select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : $this->getQueryBuilder()->select("*");
        $groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
        $having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
        $sql = $this->buildSelectSql($select, $this->getSqlFrom(), $this->getSqlWhere(), $groupBy, $having, "", $this->CurrentFilter, "");
        $cnt = $this->getRecordCount($sql);
        $this->CurrentFilter = $origFilter;
        return $cnt;
    }

    // Get record count (for current List page)
    public function listRecordCount()
    {
        $filter = $this->getSessionWhere();
        AddFilter($filter, $this->CurrentFilter);
        $filter = $this->applyUserIDFilters($filter);
        $this->recordsetSelecting($filter);
        $select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : $this->getQueryBuilder()->select("*");
        $groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
        $having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
        $sql = $this->buildSelectSql($select, $this->getSqlFrom(), $this->getSqlWhere(), $groupBy, $having, "", $filter, "");
        $cnt = $this->getRecordCount($sql);
        return $cnt;
    }

    /**
     * INSERT statement
     *
     * @param mixed $rs
     * @return QueryBuilder
     */
    public function insertSql(&$rs)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->insert($this->UpdateTable);
        foreach ($rs as $name => $value) {
            if (!isset($this->Fields[$name]) || $this->Fields[$name]->IsCustom) {
                continue;
            }
            $type = GetParameterType($this->Fields[$name], $value, $this->Dbid);
            $queryBuilder->setValue($this->Fields[$name]->Expression, $queryBuilder->createPositionalParameter($value, $type));
        }
        return $queryBuilder;
    }

    // Insert
    public function insert(&$rs)
    {
        $conn = $this->getConnection();
        $success = $this->insertSql($rs)->execute();
        if ($success) {
            // Get insert id if necessary
            $this->Monitor_ID->setDbValue($conn->lastInsertId());
            $rs['Monitor_ID'] = $this->Monitor_ID->DbValue;
        }
        return $success;
    }

    /**
     * UPDATE statement
     *
     * @param array $rs Data to be updated
     * @param string|array $where WHERE clause
     * @param string $curfilter Filter
     * @return QueryBuilder
     */
    public function updateSql(&$rs, $where = "", $curfilter = true)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->update($this->UpdateTable);
        foreach ($rs as $name => $value) {
            if (!isset($this->Fields[$name]) || $this->Fields[$name]->IsCustom || $this->Fields[$name]->IsAutoIncrement) {
                continue;
            }
            $type = GetParameterType($this->Fields[$name], $value, $this->Dbid);
            $queryBuilder->set($this->Fields[$name]->Expression, $queryBuilder->createPositionalParameter($value, $type));
        }
        $filter = ($curfilter) ? $this->CurrentFilter : "";
        if (is_array($where)) {
            $where = $this->arrayToFilter($where);
        }
        AddFilter($filter, $where);
        if ($filter != "") {
            $queryBuilder->where($filter);
        }
        return $queryBuilder;
    }

    // Update
    public function update(&$rs, $where = "", $rsold = null, $curfilter = true)
    {
        // If no field is updated, execute may return 0. Treat as success
        $success = $this->updateSql($rs, $where, $curfilter)->execute();
        $success = ($success > 0) ? $success : true;
        return $success;
    }

    /**
     * DELETE statement
     *
     * @param array $rs Key values
     * @param string|array $where WHERE clause
     * @param string $curfilter Filter
     * @return QueryBuilder
     */
    public function deleteSql(&$rs, $where = "", $curfilter = true)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->delete($this->UpdateTable);
        if (is_array($where)) {
            $where = $this->arrayToFilter($where);
        }
        if ($rs) {
            if (array_key_exists('Monitor_ID', $rs)) {
                AddFilter($where, QuotedName('Monitor_ID', $this->Dbid) . '=' . QuotedValue($rs['Monitor_ID'], $this->Monitor_ID->DataType, $this->Dbid));
            }
        }
        $filter = ($curfilter) ? $this->CurrentFilter : "";
        AddFilter($filter, $where);
        return $queryBuilder->where($filter != "" ? $filter : "0=1");
    }

    // Delete
    public function delete(&$rs, $where = "", $curfilter = false)
    {
        $success = true;
        if ($success) {
            $success = $this->deleteSql($rs, $where, $curfilter)->execute();
        }
        return $success;
    }

    // Load DbValue from recordset or array
    protected function loadDbValues($row)
    {
        if (!is_array($row)) {
            return;
        }
        $this->Monitor_ID->DbValue = $row['Monitor_ID'];
        $this->Submission_ID->DbValue = $row['Submission_ID'];
        $this->Status->DbValue = $row['Status'];
        $this->Taskings->DbValue = $row['Taskings'];
        $this->Organisations->DbValue = $row['Organisations'];
        $this->Start_Date->DbValue = $row['Start_Date'];
        $this->Finish_Date->DbValue = $row['Finish_Date'];
        $this->Uploads->Upload->DbValue = $row['Uploads'];
        $this->Updated_Username->DbValue = $row['Updated_Username'];
        $this->Updated_Last->DbValue = $row['Updated_Last'];
        $this->Updated_IP->DbValue = $row['Updated_IP'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
        $oldFiles = EmptyValue($row['Uploads']) ? [] : explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $row['Uploads']);
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->Uploads->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->Uploads->oldPhysicalUploadPath() . $oldFile);
            }
        }
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "`Monitor_ID` = @Monitor_ID@";
    }

    // Get Key
    public function getKey($current = false)
    {
        $keys = [];
        $val = $current ? $this->Monitor_ID->CurrentValue : $this->Monitor_ID->OldValue;
        if (EmptyValue($val)) {
            return "";
        } else {
            $keys[] = $val;
        }
        return implode(Config("COMPOSITE_KEY_SEPARATOR"), $keys);
    }

    // Set Key
    public function setKey($key, $current = false)
    {
        $this->OldKey = strval($key);
        $keys = explode(Config("COMPOSITE_KEY_SEPARATOR"), $this->OldKey);
        if (count($keys) == 1) {
            if ($current) {
                $this->Monitor_ID->CurrentValue = $keys[0];
            } else {
                $this->Monitor_ID->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('Monitor_ID', $row) ? $row['Monitor_ID'] : null;
        } else {
            $val = $this->Monitor_ID->OldValue !== null ? $this->Monitor_ID->OldValue : $this->Monitor_ID->CurrentValue;
        }
        if (!is_numeric($val)) {
            return "0=1"; // Invalid key
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@Monitor_ID@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
        }
        return $keyFilter;
    }

    // Return page URL
    public function getReturnUrl()
    {
        $referUrl = ReferUrl();
        $referPageName = ReferPageName();
        $name = PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_RETURN_URL");
        // Get referer URL automatically
        if ($referUrl != "" && $referPageName != CurrentPageName() && $referPageName != "login") { // Referer not same page or login page
            $_SESSION[$name] = $referUrl; // Save to Session
        }
        return $_SESSION[$name] ?? GetUrl("submissionmonitorlist");
    }

    // Set return page URL
    public function setReturnUrl($v)
    {
        $_SESSION[PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_RETURN_URL")] = $v;
    }

    // Get modal caption
    public function getModalCaption($pageName)
    {
        global $Language;
        if ($pageName == "submissionmonitorview") {
            return $Language->phrase("View");
        } elseif ($pageName == "submissionmonitoredit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "submissionmonitoradd") {
            return $Language->phrase("Add");
        } else {
            return "";
        }
    }

    // API page name
    public function getApiPageName($action)
    {
        switch (strtolower($action)) {
            case Config("API_VIEW_ACTION"):
                return "SubmissionMonitorView";
            case Config("API_ADD_ACTION"):
                return "SubmissionMonitorAdd";
            case Config("API_EDIT_ACTION"):
                return "SubmissionMonitorEdit";
            case Config("API_DELETE_ACTION"):
                return "SubmissionMonitorDelete";
            case Config("API_LIST_ACTION"):
                return "SubmissionMonitorList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "submissionmonitorlist";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("submissionmonitorview", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("submissionmonitorview", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "submissionmonitoradd?" . $this->getUrlParm($parm);
        } else {
            $url = "submissionmonitoradd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("submissionmonitoredit", $this->getUrlParm($parm));
        return $this->addMasterUrl($url);
    }

    // Inline edit URL
    public function getInlineEditUrl()
    {
        $url = $this->keyUrl(CurrentPageName(), $this->getUrlParm("action=edit"));
        return $this->addMasterUrl($url);
    }

    // Copy URL
    public function getCopyUrl($parm = "")
    {
        $url = $this->keyUrl("submissionmonitoradd", $this->getUrlParm($parm));
        return $this->addMasterUrl($url);
    }

    // Inline copy URL
    public function getInlineCopyUrl()
    {
        $url = $this->keyUrl(CurrentPageName(), $this->getUrlParm("action=copy"));
        return $this->addMasterUrl($url);
    }

    // Delete URL
    public function getDeleteUrl()
    {
        return $this->keyUrl("submissionmonitordelete", $this->getUrlParm());
    }

    // Add master url
    public function addMasterUrl($url)
    {
        if ($this->getCurrentMasterTable() == "submission" && !ContainsString($url, Config("TABLE_SHOW_MASTER") . "=")) {
            $url .= (ContainsString($url, "?") ? "&" : "?") . Config("TABLE_SHOW_MASTER") . "=" . $this->getCurrentMasterTable();
            $url .= "&" . GetForeignKeyUrl("fk_Submission_ID", $this->Submission_ID->CurrentValue);
        }
        return $url;
    }

    public function keyToJson($htmlEncode = false)
    {
        $json = "";
        $json .= "\"Monitor_ID\":" . JsonEncode($this->Monitor_ID->CurrentValue, "number");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->Monitor_ID->CurrentValue !== null) {
            $url .= "/" . $this->encodeKeyValue($this->Monitor_ID->CurrentValue);
        } else {
            return "javascript:ew.alert(ew.language.phrase('InvalidRecord'));";
        }
        if ($parm != "") {
            $url .= "?" . $parm;
        }
        return $url;
    }

    // Render sort
    public function renderFieldHeader($fld)
    {
        global $Security, $Language;
        $sortUrl = "";
        $attrs = "";
        if ($fld->Sortable) {
            $sortUrl = $this->sortUrl($fld);
            $attrs = ' role="button" data-sort-url="' . $sortUrl . '" data-sort-type="1"';
        }
        $html = '<div class="ew-table-header-caption"' . $attrs . '>' . $fld->caption() . '</div>';
        if ($sortUrl) {
            $html .= '<div class="ew-table-header-sort">' . $fld->getSortIcon() . '</div>';
        }
        if ($fld->UseFilter && $Security->canSearch()) {
            $html .= '<div class="ew-filter-dropdown-btn" data-ew-action="filter" data-table="' . $fld->TableVar . '" data-field="' . $fld->FieldVar .
                '"><div class="ew-table-header-filter" role="button" aria-haspopup="true">' . $Language->phrase("Filter") . '</div></div>';
        }
        $html = '<div class="ew-table-header-btn">' . $html . '</div>';
        if ($this->UseCustomTemplate) {
            $scriptId = str_replace("{id}", $fld->TableVar . "_" . $fld->Param, "tpc_{id}");
            $html = '<template id="' . $scriptId . '">' . $html . '</template>';
        }
        return $html;
    }

    // Sort URL
    public function sortUrl($fld)
    {
        if (
            $this->CurrentAction || $this->isExport() ||
            in_array($fld->Type, [128, 204, 205])
        ) { // Unsortable data type
                return "";
        } elseif ($fld->Sortable) {
            $urlParm = $this->getUrlParm("order=" . urlencode($fld->Name) . "&amp;ordertype=" . $fld->getNextSort());
            return $this->addMasterUrl(CurrentPageName() . "?" . $urlParm);
        } else {
            return "";
        }
    }

    // Get record keys from Post/Get/Session
    public function getRecordKeys()
    {
        $arKeys = [];
        $arKey = [];
        if (Param("key_m") !== null) {
            $arKeys = Param("key_m");
            $cnt = count($arKeys);
        } else {
            if (($keyValue = Param("Monitor_ID") ?? Route("Monitor_ID")) !== null) {
                $arKeys[] = $keyValue;
            } elseif (IsApi() && (($keyValue = Key(0) ?? Route(2)) !== null)) {
                $arKeys[] = $keyValue;
            } else {
                $arKeys = null; // Do not setup
            }

            //return $arKeys; // Do not return yet, so the values will also be checked by the following code
        }
        // Check keys
        $ar = [];
        if (is_array($arKeys)) {
            foreach ($arKeys as $key) {
                if (!is_numeric($key)) {
                    continue;
                }
                $ar[] = $key;
            }
        }
        return $ar;
    }

    // Get filter from record keys
    public function getFilterFromRecordKeys($setCurrent = true)
    {
        $arKeys = $this->getRecordKeys();
        $keyFilter = "";
        foreach ($arKeys as $key) {
            if ($keyFilter != "") {
                $keyFilter .= " OR ";
            }
            if ($setCurrent) {
                $this->Monitor_ID->CurrentValue = $key;
            } else {
                $this->Monitor_ID->OldValue = $key;
            }
            $keyFilter .= "(" . $this->getRecordFilter() . ")";
        }
        return $keyFilter;
    }

    // Load recordset based on filter
    public function loadRs($filter)
    {
        $sql = $this->getSql($filter); // Set up filter (WHERE Clause)
        $conn = $this->getConnection();
        return $conn->executeQuery($sql);
    }

    // Load row values from record
    public function loadListRowValues(&$rs)
    {
        if (is_array($rs)) {
            $row = $rs;
        } elseif ($rs && property_exists($rs, "fields")) { // Recordset
            $row = $rs->fields;
        } else {
            return;
        }
        $this->Monitor_ID->setDbValue($row['Monitor_ID']);
        $this->Submission_ID->setDbValue($row['Submission_ID']);
        $this->Status->setDbValue($row['Status']);
        $this->Taskings->setDbValue($row['Taskings']);
        $this->Organisations->setDbValue($row['Organisations']);
        $this->Start_Date->setDbValue($row['Start_Date']);
        $this->Finish_Date->setDbValue($row['Finish_Date']);
        $this->Uploads->Upload->DbValue = $row['Uploads'];
        $this->Updated_Username->setDbValue($row['Updated_Username']);
        $this->Updated_Last->setDbValue($row['Updated_Last']);
        $this->Updated_IP->setDbValue($row['Updated_IP']);
    }

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

        // Monitor_ID

        // Submission_ID

        // Status

        // Taskings

        // Organisations

        // Start_Date

        // Finish_Date

        // Uploads

        // Updated_Username

        // Updated_Last

        // Updated_IP

        // Monitor_ID
        $this->Monitor_ID->ViewValue = $this->Monitor_ID->CurrentValue;
        $this->Monitor_ID->ViewCustomAttributes = "";

        // Submission_ID
        $curVal = strval($this->Submission_ID->CurrentValue);
        if ($curVal != "") {
            $this->Submission_ID->ViewValue = $this->Submission_ID->lookupCacheOption($curVal);
            if ($this->Submission_ID->ViewValue === null) { // Lookup from database
                $filterWrk = "`Submission_ID`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                $sqlWrk = $this->Submission_ID->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->Submission_ID->Lookup->renderViewRow($rswrk[0]);
                    $this->Submission_ID->ViewValue = $this->Submission_ID->displayValue($arwrk);
                } else {
                    $this->Submission_ID->ViewValue = FormatNumber($this->Submission_ID->CurrentValue, $this->Submission_ID->formatPattern());
                }
            }
        } else {
            $this->Submission_ID->ViewValue = null;
        }
        $this->Submission_ID->ViewCustomAttributes = "";

        // Status
        if (strval($this->Status->CurrentValue) != "") {
            $this->Status->ViewValue = $this->Status->optionCaption($this->Status->CurrentValue);
        } else {
            $this->Status->ViewValue = null;
        }
        $this->Status->ViewCustomAttributes = "";

        // Taskings
        $this->Taskings->ViewValue = $this->Taskings->CurrentValue;
        $this->Taskings->ViewCustomAttributes = "";

        // Organisations
        $curVal = strval($this->Organisations->CurrentValue);
        if ($curVal != "") {
            $this->Organisations->ViewValue = $this->Organisations->lookupCacheOption($curVal);
            if ($this->Organisations->ViewValue === null) { // Lookup from database
                $arwrk = explode(",", $curVal);
                $filterWrk = "";
                foreach ($arwrk as $wrk) {
                    if ($filterWrk != "") {
                        $filterWrk .= " OR ";
                    }
                    $filterWrk .= "`Organisation`" . SearchString("=", trim($wrk), DATATYPE_STRING, "");
                }
                $sqlWrk = $this->Organisations->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $this->Organisations->ViewValue = new OptionValues();
                    foreach ($rswrk as $row) {
                        $arwrk = $this->Organisations->Lookup->renderViewRow($row);
                        $this->Organisations->ViewValue->add($this->Organisations->displayValue($arwrk));
                    }
                } else {
                    $this->Organisations->ViewValue = $this->Organisations->CurrentValue;
                }
            }
        } else {
            $this->Organisations->ViewValue = null;
        }
        $this->Organisations->ViewCustomAttributes = "";

        // Start_Date
        $this->Start_Date->ViewValue = $this->Start_Date->CurrentValue;
        $this->Start_Date->ViewValue = FormatDateTime($this->Start_Date->ViewValue, $this->Start_Date->formatPattern());
        $this->Start_Date->ViewCustomAttributes = "";

        // Finish_Date
        $this->Finish_Date->ViewValue = $this->Finish_Date->CurrentValue;
        $this->Finish_Date->ViewValue = FormatDateTime($this->Finish_Date->ViewValue, $this->Finish_Date->formatPattern());
        $this->Finish_Date->ViewCustomAttributes = "";

        // Uploads
        if (!EmptyValue($this->Uploads->Upload->DbValue)) {
            $this->Uploads->ViewValue = $this->Uploads->Upload->DbValue;
        } else {
            $this->Uploads->ViewValue = "";
        }
        $this->Uploads->ViewCustomAttributes = "";

        // Updated_Username
        $this->Updated_Username->ViewValue = $this->Updated_Username->CurrentValue;
        $this->Updated_Username->ViewCustomAttributes = "";

        // Updated_Last
        $this->Updated_Last->ViewValue = $this->Updated_Last->CurrentValue;
        $this->Updated_Last->ViewValue = FormatDateTime($this->Updated_Last->ViewValue, $this->Updated_Last->formatPattern());
        $this->Updated_Last->ViewCustomAttributes = "";

        // Updated_IP
        $this->Updated_IP->ViewValue = $this->Updated_IP->CurrentValue;
        $this->Updated_IP->ViewCustomAttributes = "";

        // Monitor_ID
        $this->Monitor_ID->LinkCustomAttributes = "";
        $this->Monitor_ID->HrefValue = "";
        $this->Monitor_ID->TooltipValue = "";

        // Submission_ID
        $this->Submission_ID->LinkCustomAttributes = "";
        $this->Submission_ID->HrefValue = "";
        $this->Submission_ID->TooltipValue = "";

        // Status
        $this->Status->LinkCustomAttributes = "";
        $this->Status->HrefValue = "";
        $this->Status->TooltipValue = "";

        // Taskings
        $this->Taskings->LinkCustomAttributes = "";
        $this->Taskings->HrefValue = "";
        $this->Taskings->TooltipValue = "";

        // Organisations
        $this->Organisations->LinkCustomAttributes = "";
        $this->Organisations->HrefValue = "";
        $this->Organisations->TooltipValue = "";

        // Start_Date
        $this->Start_Date->LinkCustomAttributes = "";
        $this->Start_Date->HrefValue = "";
        $this->Start_Date->TooltipValue = "";

        // Finish_Date
        $this->Finish_Date->LinkCustomAttributes = "";
        $this->Finish_Date->HrefValue = "";
        $this->Finish_Date->TooltipValue = "";

        // Uploads
        $this->Uploads->LinkCustomAttributes = "";
        $this->Uploads->HrefValue = "";
        $this->Uploads->ExportHrefValue = $this->Uploads->UploadPath . $this->Uploads->Upload->DbValue;
        $this->Uploads->TooltipValue = "";

        // Updated_Username
        $this->Updated_Username->LinkCustomAttributes = "";
        $this->Updated_Username->HrefValue = "";
        $this->Updated_Username->TooltipValue = "";

        // Updated_Last
        $this->Updated_Last->LinkCustomAttributes = "";
        $this->Updated_Last->HrefValue = "";
        $this->Updated_Last->TooltipValue = "";

        // Updated_IP
        $this->Updated_IP->LinkCustomAttributes = "";
        $this->Updated_IP->HrefValue = "";
        $this->Updated_IP->TooltipValue = "";

        // Call Row Rendered event
        $this->rowRendered();

        // Save data for Custom Template
        $this->Rows[] = $this->customTemplateFieldValues();
    }

    // Render edit row values
    public function renderEditRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Monitor_ID
        $this->Monitor_ID->setupEditAttributes();
        $this->Monitor_ID->EditCustomAttributes = "";
        $this->Monitor_ID->EditValue = $this->Monitor_ID->CurrentValue;
        $this->Monitor_ID->ViewCustomAttributes = "";

        // Submission_ID
        $this->Submission_ID->setupEditAttributes();
        $this->Submission_ID->EditCustomAttributes = "";
        if ($this->Submission_ID->getSessionValue() != "") {
            $this->Submission_ID->CurrentValue = GetForeignKeyValue($this->Submission_ID->getSessionValue());
            $curVal = strval($this->Submission_ID->CurrentValue);
            if ($curVal != "") {
                $this->Submission_ID->ViewValue = $this->Submission_ID->lookupCacheOption($curVal);
                if ($this->Submission_ID->ViewValue === null) { // Lookup from database
                    $filterWrk = "`Submission_ID`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->Submission_ID->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->Submission_ID->Lookup->renderViewRow($rswrk[0]);
                        $this->Submission_ID->ViewValue = $this->Submission_ID->displayValue($arwrk);
                    } else {
                        $this->Submission_ID->ViewValue = FormatNumber($this->Submission_ID->CurrentValue, $this->Submission_ID->formatPattern());
                    }
                }
            } else {
                $this->Submission_ID->ViewValue = null;
            }
            $this->Submission_ID->ViewCustomAttributes = "";
        } else {
            $this->Submission_ID->PlaceHolder = RemoveHtml($this->Submission_ID->caption());
        }

        // Status
        $this->Status->setupEditAttributes();
        $this->Status->EditCustomAttributes = "";
        $this->Status->EditValue = $this->Status->options(true);
        $this->Status->PlaceHolder = RemoveHtml($this->Status->caption());

        // Taskings
        $this->Taskings->setupEditAttributes();
        $this->Taskings->EditCustomAttributes = "";
        $this->Taskings->EditValue = $this->Taskings->CurrentValue;
        $this->Taskings->PlaceHolder = RemoveHtml($this->Taskings->caption());

        // Organisations
        $this->Organisations->setupEditAttributes();
        $this->Organisations->EditCustomAttributes = "";
        $this->Organisations->PlaceHolder = RemoveHtml($this->Organisations->caption());

        // Start_Date
        $this->Start_Date->setupEditAttributes();
        $this->Start_Date->EditCustomAttributes = "";
        $this->Start_Date->EditValue = FormatDateTime($this->Start_Date->CurrentValue, $this->Start_Date->formatPattern());
        $this->Start_Date->PlaceHolder = RemoveHtml($this->Start_Date->caption());

        // Finish_Date
        $this->Finish_Date->setupEditAttributes();
        $this->Finish_Date->EditCustomAttributes = "";
        $this->Finish_Date->EditValue = FormatDateTime($this->Finish_Date->CurrentValue, $this->Finish_Date->formatPattern());
        $this->Finish_Date->PlaceHolder = RemoveHtml($this->Finish_Date->caption());

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

        // Updated_Username

        // Updated_Last

        // Updated_IP

        // Call Row Rendered event
        $this->rowRendered();
    }

    // Aggregate list row values
    public function aggregateListRowValues()
    {
    }

    // Aggregate list row (for rendering)
    public function aggregateListRow()
    {
        // Call Row Rendered event
        $this->rowRendered();
    }

    // Export data in HTML/CSV/Word/Excel/Email/PDF format
    public function exportDocument($doc, $recordset, $startRec = 1, $stopRec = 1, $exportPageType = "")
    {
        if (!$recordset || !$doc) {
            return;
        }
        if (!$doc->ExportCustom) {
            // Write header
            $doc->exportTableHeader();
            if ($doc->Horizontal) { // Horizontal format, write header
                $doc->beginExportRow();
                if ($exportPageType == "view") {
                    $doc->exportCaption($this->Monitor_ID);
                    $doc->exportCaption($this->Status);
                    $doc->exportCaption($this->Taskings);
                    $doc->exportCaption($this->Organisations);
                    $doc->exportCaption($this->Start_Date);
                    $doc->exportCaption($this->Finish_Date);
                    $doc->exportCaption($this->Uploads);
                    $doc->exportCaption($this->Updated_Username);
                    $doc->exportCaption($this->Updated_Last);
                    $doc->exportCaption($this->Updated_IP);
                } else {
                    $doc->exportCaption($this->Monitor_ID);
                    $doc->exportCaption($this->Submission_ID);
                    $doc->exportCaption($this->Status);
                    $doc->exportCaption($this->Taskings);
                    $doc->exportCaption($this->Organisations);
                    $doc->exportCaption($this->Start_Date);
                    $doc->exportCaption($this->Finish_Date);
                    $doc->exportCaption($this->Uploads);
                    $doc->exportCaption($this->Updated_Username);
                    $doc->exportCaption($this->Updated_Last);
                    $doc->exportCaption($this->Updated_IP);
                }
                $doc->endExportRow();
            }
        }

        // Move to first record
        $recCnt = $startRec - 1;
        $stopRec = ($stopRec > 0) ? $stopRec : PHP_INT_MAX;
        while (!$recordset->EOF && $recCnt < $stopRec) {
            $row = $recordset->fields;
            $recCnt++;
            if ($recCnt >= $startRec) {
                $rowCnt = $recCnt - $startRec + 1;

                // Page break
                if ($this->ExportPageBreakCount > 0) {
                    if ($rowCnt > 1 && ($rowCnt - 1) % $this->ExportPageBreakCount == 0) {
                        $doc->exportPageBreak();
                    }
                }
                $this->loadListRowValues($row);

                // Render row
                $this->RowType = ROWTYPE_VIEW; // Render view
                $this->resetAttributes();
                $this->renderListRow();
                if (!$doc->ExportCustom) {
                    $doc->beginExportRow($rowCnt); // Allow CSS styles if enabled
                    if ($exportPageType == "view") {
                        $doc->exportField($this->Monitor_ID);
                        $doc->exportField($this->Status);
                        $doc->exportField($this->Taskings);
                        $doc->exportField($this->Organisations);
                        $doc->exportField($this->Start_Date);
                        $doc->exportField($this->Finish_Date);
                        $doc->exportField($this->Uploads);
                        $doc->exportField($this->Updated_Username);
                        $doc->exportField($this->Updated_Last);
                        $doc->exportField($this->Updated_IP);
                    } else {
                        $doc->exportField($this->Monitor_ID);
                        $doc->exportField($this->Submission_ID);
                        $doc->exportField($this->Status);
                        $doc->exportField($this->Taskings);
                        $doc->exportField($this->Organisations);
                        $doc->exportField($this->Start_Date);
                        $doc->exportField($this->Finish_Date);
                        $doc->exportField($this->Uploads);
                        $doc->exportField($this->Updated_Username);
                        $doc->exportField($this->Updated_Last);
                        $doc->exportField($this->Updated_IP);
                    }
                    $doc->endExportRow($rowCnt);
                }
            }

            // Call Row Export server event
            if ($doc->ExportCustom) {
                $this->rowExport($row);
            }
            $recordset->moveNext();
        }
        if (!$doc->ExportCustom) {
            $doc->exportTableFooter();
        }
    }

    // Add User ID filter
    public function addUserIDFilter($filter = "", $id = "")
    {
        global $Security;
        $filterWrk = "";
        if ($id == "")
            $id = (CurrentPageID() == "list") ? $this->CurrentAction : CurrentPageID();
        if (!$this->userIDAllow($id) && !$Security->isAdmin()) {
            $filterWrk = $Security->userIdList();
            if ($filterWrk != "") {
                $filterWrk = '`Updated_Username` IN (' . $filterWrk . ')';
            }
        }

        // Call User ID Filtering event
        $this->userIdFiltering($filterWrk);
        AddFilter($filter, $filterWrk);
        return $filter;
    }

    // User ID subquery
    public function getUserIDSubquery(&$fld, &$masterfld)
    {
        global $UserTable;
        $wrk = "";
        $sql = "SELECT " . $masterfld->Expression . " FROM `submission_monitor`";
        $filter = $this->addUserIDFilter("");
        if ($filter != "") {
            $sql .= " WHERE " . $filter;
        }

        // List all values
        $conn = Conn($UserTable->Dbid);
        $config = $conn->getConfiguration();
        $config->setResultCacheImpl($this->Cache);
        if ($rs = $conn->executeCacheQuery($sql, [], [], $this->CacheProfile)->fetchAllNumeric()) {
            foreach ($rs as $row) {
                if ($wrk != "") {
                    $wrk .= ",";
                }
                $wrk .= QuotedValue($row[0], $masterfld->DataType, Config("USER_TABLE_DBID"));
            }
        }
        if ($wrk != "") {
            $wrk = $fld->Expression . " IN (" . $wrk . ")";
        } else { // No User ID value found
            $wrk = "0=1";
        }
        return $wrk;
    }

    // Get file data
    public function getFileData($fldparm, $key, $resize, $width = 0, $height = 0, $plugins = [])
    {
        global $DownloadFileName;
        $width = ($width > 0) ? $width : Config("THUMBNAIL_DEFAULT_WIDTH");
        $height = ($height > 0) ? $height : Config("THUMBNAIL_DEFAULT_HEIGHT");

        // Set up field name / file name field / file type field
        $fldName = "";
        $fileNameFld = "";
        $fileTypeFld = "";
        if ($fldparm == 'Uploads') {
            $fldName = "Uploads";
            $fileNameFld = "Uploads";
        } else {
            return false; // Incorrect field
        }

        // Set up key values
        $ar = explode(Config("COMPOSITE_KEY_SEPARATOR"), $key);
        if (count($ar) == 1) {
            $this->Monitor_ID->CurrentValue = $ar[0];
        } else {
            return false; // Incorrect key
        }

        // Set up filter (WHERE Clause)
        $filter = $this->getRecordFilter();
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        $dbtype = GetConnectionType($this->Dbid);
        if ($row = $conn->fetchAssociative($sql)) {
            $val = $row[$fldName];
            if (!EmptyValue($val)) {
                $fld = $this->Fields[$fldName];

                // Binary data
                if ($fld->DataType == DATATYPE_BLOB) {
                    if ($dbtype != "MYSQL") {
                        if (is_resource($val) && get_resource_type($val) == "stream") { // Byte array
                            $val = stream_get_contents($val);
                        }
                    }
                    if ($resize) {
                        ResizeBinary($val, $width, $height, $plugins);
                    }

                    // Write file type
                    if ($fileTypeFld != "" && !EmptyValue($row[$fileTypeFld])) {
                        AddHeader("Content-type", $row[$fileTypeFld]);
                    } else {
                        AddHeader("Content-type", ContentType($val));
                    }

                    // Write file name
                    $downloadPdf = !Config("EMBED_PDF") && Config("DOWNLOAD_PDF_FILE");
                    if ($fileNameFld != "" && !EmptyValue($row[$fileNameFld])) {
                        $fileName = $row[$fileNameFld];
                        $pathinfo = pathinfo($fileName);
                        $ext = strtolower(@$pathinfo["extension"]);
                        $isPdf = SameText($ext, "pdf");
                        if ($downloadPdf || !$isPdf) { // Skip header if not download PDF
                            AddHeader("Content-Disposition", "attachment; filename=\"" . $fileName . "\"");
                        }
                    } else {
                        $ext = ContentExtension($val);
                        $isPdf = SameText($ext, ".pdf");
                        if ($isPdf && $downloadPdf) { // Add header if download PDF
                            AddHeader("Content-Disposition", "attachment" . ($DownloadFileName ? "; filename=\"" . $DownloadFileName . "\"" : ""));
                        }
                    }

                    // Write file data
                    if (
                        StartsString("PK", $val) &&
                        ContainsString($val, "[Content_Types].xml") &&
                        ContainsString($val, "_rels") &&
                        ContainsString($val, "docProps")
                    ) { // Fix Office 2007 documents
                        if (!EndsString("\0\0\0", $val)) { // Not ends with 3 or 4 \0
                            $val .= "\0\0\0\0";
                        }
                    }

                    // Clear any debug message
                    if (ob_get_length()) {
                        ob_end_clean();
                    }

                    // Write binary data
                    Write($val);

                // Upload to folder
                } else {
                    if ($fld->UploadMultiple) {
                        $files = explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $val);
                    } else {
                        $files = [$val];
                    }
                    $data = [];
                    $ar = [];
                    foreach ($files as $file) {
                        if (!EmptyValue($file)) {
                            if (Config("ENCRYPT_FILE_PATH")) {
                                $ar[$file] = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                    "/" . $this->TableVar . "/" . Encrypt($fld->physicalUploadPath() . $file)));
                            } else {
                                $ar[$file] = FullUrl($fld->hrefPath() . $file);
                            }
                        }
                    }
                    $data[$fld->Param] = $ar;
                    WriteJson($data);
                }
            }
            return true;
        }
        return false;
    }

    // Table level events

    // Recordset Selecting event
    public function recordsetSelecting(&$filter)
    {
        // Enter your code here
    }

    // Recordset Selected event
    public function recordsetSelected(&$rs)
    {
        //Log("Recordset Selected");
    }

    // Recordset Search Validated event
    public function recordsetSearchValidated()
    {
        // Example:
        //$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value
    }

    // Recordset Searching event
    public function recordsetSearching(&$filter)
    {
        // Enter your code here
    }

    // Row_Selecting event
    public function rowSelecting(&$filter)
    {
        // Enter your code here
    }

    // Row Selected event
    public function rowSelected(&$rs)
    {
        //Log("Row Selected");
    }

    // Row Inserting event
    public function rowInserting($rsold, &$rsnew)
    {
        // Enter your code here
        // To cancel, set return value to false
        return true;
    }

    // Row Inserted event
    public function rowInserted($rsold, &$rsnew)
    {
        //Log("Row Inserted");
    }

    // Row Updating event
    public function rowUpdating($rsold, &$rsnew)
    {
        // Enter your code here
        // To cancel, set return value to false
        return true;
    }

    // Row Updated event
    public function rowUpdated($rsold, &$rsnew)
    {
        //Log("Row Updated");
    }

    // Row Update Conflict event
    public function rowUpdateConflict($rsold, &$rsnew)
    {
        // Enter your code here
        // To ignore conflict, set return value to false
        return true;
    }

    // Grid Inserting event
    public function gridInserting()
    {
        // Enter your code here
        // To reject grid insert, set return value to false
        return true;
    }

    // Grid Inserted event
    public function gridInserted($rsnew)
    {
        //Log("Grid Inserted");
    }

    // Grid Updating event
    public function gridUpdating($rsold)
    {
        // Enter your code here
        // To reject grid update, set return value to false
        return true;
    }

    // Grid Updated event
    public function gridUpdated($rsold, $rsnew)
    {
        //Log("Grid Updated");
    }

    // Row Deleting event
    public function rowDeleting(&$rs)
    {
        // Enter your code here
        // To cancel, set return value to False
        return true;
    }

    // Row Deleted event
    public function rowDeleted(&$rs)
    {
        //Log("Row Deleted");
    }

    // Email Sending event
    public function emailSending($email, &$args)
    {
        //var_dump($email, $args); exit();
        return true;
    }

    // Lookup Selecting event
    public function lookupSelecting($fld, &$filter)
    {
        //var_dump($fld->Name, $fld->Lookup, $filter); // Uncomment to view the filter
        // Enter your code here
    }

    // Row Rendering event
    public function rowRendering()
    {
        // Enter your code here
    }

    // Row Rendered event
    public function rowRendered()
    {
        // To view properties of field class, use:
        //var_dump($this-><FieldName>);
    }

    // User ID Filtering event
    public function userIdFiltering(&$filter)
    {
        // Enter your code here
    }
}
