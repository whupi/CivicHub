<?php

namespace PHPMaker2022\civichub2;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Table class for submission
 */
class Submission extends DbTable
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
    public $Submission_ID;
    public $Category_ID;
    public $_Title;
    public $Category;
    public $Status;
    public $_Abstract;
    public $Tags;
    public $Cover;
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
        $this->TableVar = 'submission';
        $this->TableName = 'submission';
        $this->TableType = 'VIEW';

        // Update Table
        $this->UpdateTable = "`submission`";
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

        // Submission_ID
        $this->Submission_ID = new DbField(
            'submission',
            'submission',
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
            'NO'
        );
        $this->Submission_ID->InputTextType = "text";
        $this->Submission_ID->IsAutoIncrement = true; // Autoincrement field
        $this->Submission_ID->IsPrimaryKey = true; // Primary key field
        $this->Submission_ID->IsForeignKey = true; // Foreign key field
        $this->Submission_ID->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['Submission_ID'] = &$this->Submission_ID;

        // Category_ID
        $this->Category_ID = new DbField(
            'submission',
            'submission',
            'x_Category_ID',
            'Category_ID',
            '`Category_ID`',
            '`Category_ID`',
            3,
            11,
            -1,
            false,
            '`Category_ID`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->Category_ID->InputTextType = "text";
        $this->Category_ID->IsForeignKey = true; // Foreign key field
        $this->Category_ID->Nullable = false; // NOT NULL field
        $this->Category_ID->Required = true; // Required field
        $this->Category_ID->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['Category_ID'] = &$this->Category_ID;

        // Title
        $this->_Title = new DbField(
            'submission',
            'submission',
            'x__Title',
            'Title',
            '`Title`',
            '`Title`',
            200,
            255,
            -1,
            false,
            '`Title`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->_Title->InputTextType = "text";
        $this->_Title->Nullable = false; // NOT NULL field
        $this->_Title->Required = true; // Required field
        $this->Fields['Title'] = &$this->_Title;

        // Category
        $this->Category = new DbField(
            'submission',
            'submission',
            'x_Category',
            'Category',
            '`Category`',
            '`Category`',
            200,
            255,
            -1,
            false,
            '`Category`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->Category->InputTextType = "text";
        $this->Category->Nullable = false; // NOT NULL field
        $this->Category->Required = true; // Required field
        $this->Fields['Category'] = &$this->Category;

        // Status
        $this->Status = new DbField(
            'submission',
            'submission',
            'x_Status',
            'Status',
            '`Status`',
            '`Status`',
            202,
            8,
            -1,
            false,
            '`Status`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'RADIO'
        );
        $this->Status->InputTextType = "text";
        $this->Status->Nullable = false; // NOT NULL field
        $this->Status->Required = true; // Required field
        $this->Status->Lookup = new Lookup('Status', 'submission', false, '', ["","","",""], [], [], [], [], [], [], '', '', "");
        $this->Status->OptionCount = 3;
        $this->Fields['Status'] = &$this->Status;

        // Abstract
        $this->_Abstract = new DbField(
            'submission',
            'submission',
            'x__Abstract',
            'Abstract',
            '`Abstract`',
            '`Abstract`',
            201,
            65535,
            -1,
            false,
            '`Abstract`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXTAREA'
        );
        $this->_Abstract->InputTextType = "text";
        $this->_Abstract->Nullable = false; // NOT NULL field
        $this->_Abstract->Required = true; // Required field
        $this->Fields['Abstract'] = &$this->_Abstract;

        // Tags
        $this->Tags = new DbField(
            'submission',
            'submission',
            'x_Tags',
            'Tags',
            '`Tags`',
            '`Tags`',
            200,
            255,
            -1,
            false,
            '`Tags`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->Tags->InputTextType = "text";
        $this->Tags->Nullable = false; // NOT NULL field
        $this->Tags->Required = true; // Required field
        $this->Fields['Tags'] = &$this->Tags;

        // Cover
        $this->Cover = new DbField(
            'submission',
            'submission',
            'x_Cover',
            'Cover',
            '`Cover`',
            '`Cover`',
            200,
            255,
            -1,
            false,
            '`Cover`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->Cover->InputTextType = "text";
        $this->Fields['Cover'] = &$this->Cover;

        // Uploads
        $this->Uploads = new DbField(
            'submission',
            'submission',
            'x_Uploads',
            'Uploads',
            '`Uploads`',
            '`Uploads`',
            201,
            65535,
            -1,
            false,
            '`Uploads`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXTAREA'
        );
        $this->Uploads->InputTextType = "text";
        $this->Fields['Uploads'] = &$this->Uploads;

        // Updated_Username
        $this->Updated_Username = new DbField(
            'submission',
            'submission',
            'x_Updated_Username',
            'Updated_Username',
            '`Updated_Username`',
            '`Updated_Username`',
            200,
            20,
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
            'submission',
            'submission',
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
            'TEXT'
        );
        $this->Updated_Last->InputTextType = "text";
        $this->Updated_Last->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->Fields['Updated_Last'] = &$this->Updated_Last;

        // Updated_IP
        $this->Updated_IP = new DbField(
            'submission',
            'submission',
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
            'TEXT'
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
        if ($this->getCurrentMasterTable() == "ref_category") {
            if ($this->Category_ID->getSessionValue() != "") {
                $masterFilter .= "" . GetForeignKeySql("`Category_ID`", $this->Category_ID->getSessionValue(), DATATYPE_NUMBER, "DB");
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
        if ($this->getCurrentMasterTable() == "ref_category") {
            if ($this->Category_ID->getSessionValue() != "") {
                $detailFilter .= "" . GetForeignKeySql("`Category_ID`", $this->Category_ID->getSessionValue(), DATATYPE_NUMBER, "DB");
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
            case "ref_category":
                $key = $keys["Category_ID"] ?? "";
                if (EmptyValue($key)) {
                    if ($masterTable->Category_ID->Required) { // Required field and empty value
                        return ""; // Return empty filter
                    }
                    $validKeys = false;
                } elseif (!$validKeys) { // Already has empty key
                    return ""; // Return empty filter
                }
                if ($validKeys) {
                    return "`Category_ID`=" . QuotedValue($keys["Category_ID"], $masterTable->Category_ID->DataType, $masterTable->Dbid);
                }
                break;
        }
        return null; // All null values and no required fields
    }

    // Get detail filter
    public function getDetailFilter($masterTable)
    {
        switch ($masterTable->TableVar) {
            case "ref_category":
                return "`Category_ID`=" . QuotedValue($masterTable->Category_ID->DbValue, $this->Category_ID->DataType, $this->Dbid);
        }
        return "";
    }

    // Current detail table name
    public function getCurrentDetailTable()
    {
        return Session(PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_DETAIL_TABLE"));
    }

    public function setCurrentDetailTable($v)
    {
        $_SESSION[PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_DETAIL_TABLE")] = $v;
    }

    // Get detail url
    public function getDetailUrl()
    {
        // Detail url
        $detailUrl = "";
        if ($this->getCurrentDetailTable() == "submission_comments") {
            $detailUrl = Container("submission_comments")->getListUrl() . "?" . Config("TABLE_SHOW_MASTER") . "=" . $this->TableVar;
            $detailUrl .= "&" . GetForeignKeyUrl("fk_Submission_ID", $this->Submission_ID->CurrentValue);
        }
        if ($this->getCurrentDetailTable() == "vote_tally") {
            $detailUrl = Container("vote_tally")->getListUrl() . "?" . Config("TABLE_SHOW_MASTER") . "=" . $this->TableVar;
            $detailUrl .= "&" . GetForeignKeyUrl("fk_Submission_ID", $this->Submission_ID->CurrentValue);
        }
        if ($this->getCurrentDetailTable() == "submission_monitor") {
            $detailUrl = Container("submission_monitor")->getListUrl() . "?" . Config("TABLE_SHOW_MASTER") . "=" . $this->TableVar;
            $detailUrl .= "&" . GetForeignKeyUrl("fk_Submission_ID", $this->Submission_ID->CurrentValue);
        }
        if ($detailUrl == "") {
            $detailUrl = "submissionlist";
        }
        return $detailUrl;
    }

    // Table level SQL
    public function getSqlFrom() // From
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`submission`";
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
            $this->Submission_ID->setDbValue($conn->lastInsertId());
            $rs['Submission_ID'] = $this->Submission_ID->DbValue;
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
            if (array_key_exists('Submission_ID', $rs)) {
                AddFilter($where, QuotedName('Submission_ID', $this->Dbid) . '=' . QuotedValue($rs['Submission_ID'], $this->Submission_ID->DataType, $this->Dbid));
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
        $this->Submission_ID->DbValue = $row['Submission_ID'];
        $this->Category_ID->DbValue = $row['Category_ID'];
        $this->_Title->DbValue = $row['Title'];
        $this->Category->DbValue = $row['Category'];
        $this->Status->DbValue = $row['Status'];
        $this->_Abstract->DbValue = $row['Abstract'];
        $this->Tags->DbValue = $row['Tags'];
        $this->Cover->DbValue = $row['Cover'];
        $this->Uploads->DbValue = $row['Uploads'];
        $this->Updated_Username->DbValue = $row['Updated_Username'];
        $this->Updated_Last->DbValue = $row['Updated_Last'];
        $this->Updated_IP->DbValue = $row['Updated_IP'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "`Submission_ID` = @Submission_ID@";
    }

    // Get Key
    public function getKey($current = false)
    {
        $keys = [];
        $val = $current ? $this->Submission_ID->CurrentValue : $this->Submission_ID->OldValue;
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
                $this->Submission_ID->CurrentValue = $keys[0];
            } else {
                $this->Submission_ID->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('Submission_ID', $row) ? $row['Submission_ID'] : null;
        } else {
            $val = $this->Submission_ID->OldValue !== null ? $this->Submission_ID->OldValue : $this->Submission_ID->CurrentValue;
        }
        if (!is_numeric($val)) {
            return "0=1"; // Invalid key
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@Submission_ID@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
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
        return $_SESSION[$name] ?? GetUrl("submissionlist");
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
        if ($pageName == "submissionview") {
            return $Language->phrase("View");
        } elseif ($pageName == "submissionedit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "submissionadd") {
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
                return "SubmissionView";
            case Config("API_ADD_ACTION"):
                return "SubmissionAdd";
            case Config("API_EDIT_ACTION"):
                return "SubmissionEdit";
            case Config("API_DELETE_ACTION"):
                return "SubmissionDelete";
            case Config("API_LIST_ACTION"):
                return "SubmissionList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "submissionlist";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("submissionview", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("submissionview", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "submissionadd?" . $this->getUrlParm($parm);
        } else {
            $url = "submissionadd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("submissionedit", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("submissionedit", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
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
        if ($parm != "") {
            $url = $this->keyUrl("submissionadd", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("submissionadd", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
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
        return $this->keyUrl("submissiondelete", $this->getUrlParm());
    }

    // Add master url
    public function addMasterUrl($url)
    {
        if ($this->getCurrentMasterTable() == "ref_category" && !ContainsString($url, Config("TABLE_SHOW_MASTER") . "=")) {
            $url .= (ContainsString($url, "?") ? "&" : "?") . Config("TABLE_SHOW_MASTER") . "=" . $this->getCurrentMasterTable();
            $url .= "&" . GetForeignKeyUrl("fk_Category_ID", $this->Category_ID->CurrentValue);
        }
        return $url;
    }

    public function keyToJson($htmlEncode = false)
    {
        $json = "";
        $json .= "\"Submission_ID\":" . JsonEncode($this->Submission_ID->CurrentValue, "number");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->Submission_ID->CurrentValue !== null) {
            $url .= "/" . $this->encodeKeyValue($this->Submission_ID->CurrentValue);
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
            if (($keyValue = Param("Submission_ID") ?? Route("Submission_ID")) !== null) {
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
                $this->Submission_ID->CurrentValue = $key;
            } else {
                $this->Submission_ID->OldValue = $key;
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
        $this->Submission_ID->setDbValue($row['Submission_ID']);
        $this->Category_ID->setDbValue($row['Category_ID']);
        $this->_Title->setDbValue($row['Title']);
        $this->Category->setDbValue($row['Category']);
        $this->Status->setDbValue($row['Status']);
        $this->_Abstract->setDbValue($row['Abstract']);
        $this->Tags->setDbValue($row['Tags']);
        $this->Cover->setDbValue($row['Cover']);
        $this->Uploads->setDbValue($row['Uploads']);
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

        // Submission_ID

        // Category_ID

        // Title

        // Category

        // Status

        // Abstract

        // Tags

        // Cover

        // Uploads

        // Updated_Username

        // Updated_Last

        // Updated_IP

        // Submission_ID
        $this->Submission_ID->ViewValue = $this->Submission_ID->CurrentValue;
        $this->Submission_ID->ViewCustomAttributes = "";

        // Category_ID
        $this->Category_ID->ViewValue = $this->Category_ID->CurrentValue;
        $this->Category_ID->ViewValue = FormatNumber($this->Category_ID->ViewValue, $this->Category_ID->formatPattern());
        $this->Category_ID->ViewCustomAttributes = "";

        // Title
        $this->_Title->ViewValue = $this->_Title->CurrentValue;
        $this->_Title->ViewCustomAttributes = "";

        // Category
        $this->Category->ViewValue = $this->Category->CurrentValue;
        $this->Category->ViewCustomAttributes = "";

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
        $this->Tags->ViewValue = $this->Tags->CurrentValue;
        $this->Tags->ViewCustomAttributes = "";

        // Cover
        $this->Cover->ViewValue = $this->Cover->CurrentValue;
        $this->Cover->ViewCustomAttributes = "";

        // Uploads
        $this->Uploads->ViewValue = $this->Uploads->CurrentValue;
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

        // Submission_ID
        $this->Submission_ID->LinkCustomAttributes = "";
        $this->Submission_ID->HrefValue = "";
        $this->Submission_ID->TooltipValue = "";

        // Category_ID
        $this->Category_ID->LinkCustomAttributes = "";
        $this->Category_ID->HrefValue = "";
        $this->Category_ID->TooltipValue = "";

        // Title
        $this->_Title->LinkCustomAttributes = "";
        if (!EmptyValue($this->Submission_ID->CurrentValue)) {
            $this->_Title->HrefValue = "submissionview/" . $this->Submission_ID->CurrentValue . "?showdetail="; // Add prefix/suffix
            $this->_Title->LinkAttrs["target"] = ""; // Add target
            if ($this->isExport()) {
                $this->_Title->HrefValue = FullUrl($this->_Title->HrefValue, "href");
            }
        } else {
            $this->_Title->HrefValue = "";
        }
        $this->_Title->TooltipValue = "";

        // Category
        $this->Category->LinkCustomAttributes = "";
        $this->Category->HrefValue = "";
        $this->Category->TooltipValue = "";

        // Status
        $this->Status->LinkCustomAttributes = "";
        $this->Status->HrefValue = "";
        $this->Status->TooltipValue = "";

        // Abstract
        $this->_Abstract->LinkCustomAttributes = "";
        $this->_Abstract->HrefValue = "";
        $this->_Abstract->TooltipValue = "";

        // Tags
        $this->Tags->LinkCustomAttributes = "";
        $this->Tags->HrefValue = "";
        $this->Tags->TooltipValue = "";

        // Cover
        $this->Cover->LinkCustomAttributes = "";
        $this->Cover->HrefValue = "";
        $this->Cover->TooltipValue = "";

        // Uploads
        $this->Uploads->LinkCustomAttributes = "";
        $this->Uploads->HrefValue = "";
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

        // Submission_ID
        $this->Submission_ID->setupEditAttributes();
        $this->Submission_ID->EditCustomAttributes = "";
        $this->Submission_ID->EditValue = $this->Submission_ID->CurrentValue;
        $this->Submission_ID->ViewCustomAttributes = "";

        // Category_ID
        $this->Category_ID->setupEditAttributes();
        $this->Category_ID->EditCustomAttributes = "";
        if ($this->Category_ID->getSessionValue() != "") {
            $this->Category_ID->CurrentValue = GetForeignKeyValue($this->Category_ID->getSessionValue());
            $this->Category_ID->ViewValue = $this->Category_ID->CurrentValue;
            $this->Category_ID->ViewValue = FormatNumber($this->Category_ID->ViewValue, $this->Category_ID->formatPattern());
            $this->Category_ID->ViewCustomAttributes = "";
        } else {
            $this->Category_ID->EditValue = $this->Category_ID->CurrentValue;
            $this->Category_ID->PlaceHolder = RemoveHtml($this->Category_ID->caption());
            if (strval($this->Category_ID->EditValue) != "" && is_numeric($this->Category_ID->EditValue)) {
                $this->Category_ID->EditValue = FormatNumber($this->Category_ID->EditValue, null);
            }
        }

        // Title
        $this->_Title->setupEditAttributes();
        $this->_Title->EditCustomAttributes = "";
        if (!$this->_Title->Raw) {
            $this->_Title->CurrentValue = HtmlDecode($this->_Title->CurrentValue);
        }
        $this->_Title->EditValue = $this->_Title->CurrentValue;
        $this->_Title->PlaceHolder = RemoveHtml($this->_Title->caption());

        // Category
        $this->Category->setupEditAttributes();
        $this->Category->EditCustomAttributes = "";
        if (!$this->Category->Raw) {
            $this->Category->CurrentValue = HtmlDecode($this->Category->CurrentValue);
        }
        $this->Category->EditValue = $this->Category->CurrentValue;
        $this->Category->PlaceHolder = RemoveHtml($this->Category->caption());

        // Status
        $this->Status->EditCustomAttributes = "";
        $this->Status->EditValue = $this->Status->options(false);
        $this->Status->PlaceHolder = RemoveHtml($this->Status->caption());

        // Abstract
        $this->_Abstract->setupEditAttributes();
        $this->_Abstract->EditCustomAttributes = "";
        $this->_Abstract->EditValue = $this->_Abstract->CurrentValue;
        $this->_Abstract->PlaceHolder = RemoveHtml($this->_Abstract->caption());

        // Tags
        $this->Tags->setupEditAttributes();
        $this->Tags->EditCustomAttributes = "";
        if (!$this->Tags->Raw) {
            $this->Tags->CurrentValue = HtmlDecode($this->Tags->CurrentValue);
        }
        $this->Tags->EditValue = $this->Tags->CurrentValue;
        $this->Tags->PlaceHolder = RemoveHtml($this->Tags->caption());

        // Cover
        $this->Cover->setupEditAttributes();
        $this->Cover->EditCustomAttributes = "";
        if (!$this->Cover->Raw) {
            $this->Cover->CurrentValue = HtmlDecode($this->Cover->CurrentValue);
        }
        $this->Cover->EditValue = $this->Cover->CurrentValue;
        $this->Cover->PlaceHolder = RemoveHtml($this->Cover->caption());

        // Uploads
        $this->Uploads->setupEditAttributes();
        $this->Uploads->EditCustomAttributes = "";
        $this->Uploads->EditValue = $this->Uploads->CurrentValue;
        $this->Uploads->PlaceHolder = RemoveHtml($this->Uploads->caption());

        // Updated_Username
        $this->Updated_Username->setupEditAttributes();
        $this->Updated_Username->EditCustomAttributes = "";
        if (!$this->Updated_Username->Raw) {
            $this->Updated_Username->CurrentValue = HtmlDecode($this->Updated_Username->CurrentValue);
        }
        $this->Updated_Username->EditValue = $this->Updated_Username->CurrentValue;
        $this->Updated_Username->PlaceHolder = RemoveHtml($this->Updated_Username->caption());

        // Updated_Last
        $this->Updated_Last->setupEditAttributes();
        $this->Updated_Last->EditCustomAttributes = "";
        $this->Updated_Last->EditValue = FormatDateTime($this->Updated_Last->CurrentValue, $this->Updated_Last->formatPattern());
        $this->Updated_Last->PlaceHolder = RemoveHtml($this->Updated_Last->caption());

        // Updated_IP
        $this->Updated_IP->setupEditAttributes();
        $this->Updated_IP->EditCustomAttributes = "";
        if (!$this->Updated_IP->Raw) {
            $this->Updated_IP->CurrentValue = HtmlDecode($this->Updated_IP->CurrentValue);
        }
        $this->Updated_IP->EditValue = $this->Updated_IP->CurrentValue;
        $this->Updated_IP->PlaceHolder = RemoveHtml($this->Updated_IP->caption());

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
                    $doc->exportCaption($this->Submission_ID);
                    $doc->exportCaption($this->_Title);
                    $doc->exportCaption($this->Category);
                    $doc->exportCaption($this->Status);
                    $doc->exportCaption($this->_Abstract);
                    $doc->exportCaption($this->Tags);
                    $doc->exportCaption($this->Cover);
                    $doc->exportCaption($this->Uploads);
                    $doc->exportCaption($this->Updated_Username);
                    $doc->exportCaption($this->Updated_Last);
                    $doc->exportCaption($this->Updated_IP);
                } else {
                    $doc->exportCaption($this->Submission_ID);
                    $doc->exportCaption($this->Category_ID);
                    $doc->exportCaption($this->_Title);
                    $doc->exportCaption($this->Category);
                    $doc->exportCaption($this->Status);
                    $doc->exportCaption($this->_Abstract);
                    $doc->exportCaption($this->Tags);
                    $doc->exportCaption($this->Cover);
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
                        $doc->exportField($this->Submission_ID);
                        $doc->exportField($this->_Title);
                        $doc->exportField($this->Category);
                        $doc->exportField($this->Status);
                        $doc->exportField($this->_Abstract);
                        $doc->exportField($this->Tags);
                        $doc->exportField($this->Cover);
                        $doc->exportField($this->Uploads);
                        $doc->exportField($this->Updated_Username);
                        $doc->exportField($this->Updated_Last);
                        $doc->exportField($this->Updated_IP);
                    } else {
                        $doc->exportField($this->Submission_ID);
                        $doc->exportField($this->Category_ID);
                        $doc->exportField($this->_Title);
                        $doc->exportField($this->Category);
                        $doc->exportField($this->Status);
                        $doc->exportField($this->_Abstract);
                        $doc->exportField($this->Tags);
                        $doc->exportField($this->Cover);
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

    // Get file data
    public function getFileData($fldparm, $key, $resize, $width = 0, $height = 0, $plugins = [])
    {
        global $DownloadFileName;

        // No binary fields
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
