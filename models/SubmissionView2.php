<?php

namespace PHPMaker2022\civichub2;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Table class for submission_view
 */
class SubmissionView2 extends DbTable
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
    public $_Title;
    public $Category_ID;
    public $Status;
    public $_Abstract;
    public $Tags;
    public $Uploads;
    public $Cover;
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
        $this->TableVar = 'submission_view2';
        $this->TableName = 'submission_view';
        $this->TableType = 'TABLE';

        // Update Table
        $this->UpdateTable = "`submission_view`";
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
        $this->BasicSearch = new BasicSearch($this->TableVar);

        // Submission_ID
        $this->Submission_ID = new DbField(
            'submission_view2',
            'submission_view',
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
        $this->Submission_ID->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['Submission_ID'] = &$this->Submission_ID;

        // Title
        $this->_Title = new DbField(
            'submission_view2',
            'submission_view',
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

        // Category_ID
        $this->Category_ID = new DbField(
            'submission_view2',
            'submission_view',
            'x_Category_ID',
            'Category_ID',
            '`Category_ID`',
            '`Category_ID`',
            3,
            11,
            -1,
            false,
            '`EV__Category_ID`',
            true,
            true,
            false,
            'FORMATTED TEXT',
            'SELECT'
        );
        $this->Category_ID->InputTextType = "text";
        $this->Category_ID->Nullable = false; // NOT NULL field
        $this->Category_ID->Required = true; // Required field
        $this->Category_ID->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->Category_ID->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->Category_ID->Lookup = new Lookup('Category_ID', 'ref_category', false, 'Category_ID', ["Category","","",""], [], [], [], [], [], [], '`Category`', '', "`Category`");
        $this->Category_ID->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['Category_ID'] = &$this->Category_ID;

        // Status
        $this->Status = new DbField(
            'submission_view2',
            'submission_view',
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
            'SELECT'
        );
        $this->Status->InputTextType = "text";
        $this->Status->Nullable = false; // NOT NULL field
        $this->Status->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->Status->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->Status->Lookup = new Lookup('Status', 'submission_view2', false, '', ["","","",""], [], [], [], [], [], [], '', '', "");
        $this->Status->OptionCount = 3;
        $this->Fields['Status'] = &$this->Status;

        // Abstract
        $this->_Abstract = new DbField(
            'submission_view2',
            'submission_view',
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
            'submission_view2',
            'submission_view',
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
            'SELECT'
        );
        $this->Tags->InputTextType = "text";
        $this->Tags->Nullable = false; // NOT NULL field
        $this->Tags->Required = true; // Required field
        $this->Tags->SelectMultiple = true; // Multiple select
        $this->Tags->Lookup = new Lookup('Tags', 'ref_sdg', true, 'Goal_Title', ["Goal_Title","","",""], [], [], [], [], [], [], '`Goal_Title`', '', "`Goal_Title`");
        $this->Fields['Tags'] = &$this->Tags;

        // Uploads
        $this->Uploads = new DbField(
            'submission_view2',
            'submission_view',
            'x_Uploads',
            'Uploads',
            '`Uploads`',
            '`Uploads`',
            201,
            65535,
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
        $this->Uploads->UploadMultiple = true;
        $this->Uploads->Upload->UploadMultiple = true;
        $this->Uploads->UploadMaxFileCount = 0;
        $this->Fields['Uploads'] = &$this->Uploads;

        // Cover
        $this->Cover = new DbField(
            'submission_view2',
            'submission_view',
            'x_Cover',
            'Cover',
            '`Cover`',
            '`Cover`',
            200,
            255,
            -1,
            true,
            '`Cover`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'FILE'
        );
        $this->Cover->InputTextType = "text";
        $this->Fields['Cover'] = &$this->Cover;

        // Updated_Username
        $this->Updated_Username = new DbField(
            'submission_view2',
            'submission_view',
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
            'submission_view2',
            'submission_view',
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
            'submission_view2',
            'submission_view',
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
            'SELECT'
        );
        $this->Updated_IP->InputTextType = "text";
        $this->Updated_IP->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->Updated_IP->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->Updated_IP->Lookup = new Lookup('Updated_IP', 'users', false, 'Username', ["First_Name","Last_Name","",""], [], [], [], [], [], [], '`First_Name`', '', "CONCAT(COALESCE(`First_Name`, ''),'" . ValueSeparator(1, $this->Updated_IP) . "',COALESCE(`Last_Name`,''))");
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
            $sortFieldList = ($fld->VirtualExpression != "") ? $fld->VirtualExpression : $sortField;
            $orderBy = in_array($curSort, ["ASC", "DESC"]) ? $sortFieldList . " " . $curSort : "";
            $this->setSessionOrderByList($orderBy); // Save to Session
        }
    }

    // Update field sort
    public function updateFieldSort()
    {
        $orderBy = $this->useVirtualFields() ? $this->getSessionOrderByList() : $this->getSessionOrderBy(); // Get ORDER BY from Session
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

    // Session ORDER BY for List page
    public function getSessionOrderByList()
    {
        return Session(PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_ORDER_BY_LIST"));
    }

    public function setSessionOrderByList($v)
    {
        $_SESSION[PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_ORDER_BY_LIST")] = $v;
    }

    // Table level SQL
    public function getSqlFrom() // From
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`submission_view`";
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

    public function getSqlSelectList() // Select for List page
    {
        if ($this->SqlSelectList) {
            return $this->SqlSelectList;
        }
        $from = "(SELECT *, (SELECT `Category` FROM `ref_category` `TMP_LOOKUPTABLE` WHERE `TMP_LOOKUPTABLE`.`Category_ID` = `submission_view`.`Category_ID` LIMIT 1) AS `EV__Category_ID` FROM `submission_view`)";
        return $from . " `TMP_TABLE`";
    }

    public function sqlSelectList() // For backward compatibility
    {
        return $this->getSqlSelectList();
    }

    public function setSqlSelectList($v)
    {
        $this->SqlSelectList = $v;
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
        if ($this->useVirtualFields()) {
            $select = "*";
            $from = $this->getSqlSelectList();
            $sort = $this->UseSessionForListSql ? $this->getSessionOrderByList() : "";
        } else {
            $select = $this->getSqlSelect();
            $from = $this->getSqlFrom();
            $sort = $this->UseSessionForListSql ? $this->getSessionOrderBy() : "";
        }
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
        $sort = ($this->useVirtualFields()) ? $this->getSessionOrderByList() : $this->getSessionOrderBy();
        if ($orderBy != "" && $sort != "") {
            $orderBy .= ", " . $sort;
        } elseif ($sort != "") {
            $orderBy = $sort;
        }
        return $orderBy;
    }

    // Check if virtual fields is used in SQL
    protected function useVirtualFields()
    {
        $where = $this->UseSessionForListSql ? $this->getSessionWhere() : $this->CurrentFilter;
        $orderBy = $this->UseSessionForListSql ? $this->getSessionOrderByList() : "";
        if ($where != "") {
            $where = " " . str_replace(["(", ")"], ["", ""], $where) . " ";
        }
        if ($orderBy != "") {
            $orderBy = " " . str_replace(["(", ")"], ["", ""], $orderBy) . " ";
        }
        if (ContainsString($orderBy, " " . $this->Category_ID->VirtualExpression . " ")) {
            return true;
        }
        return false;
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
        if ($this->useVirtualFields()) {
            $sql = $this->buildSelectSql("*", $this->getSqlSelectList(), $this->getSqlWhere(), $groupBy, $having, "", $filter, "");
        } else {
            $sql = $this->buildSelectSql($select, $this->getSqlFrom(), $this->getSqlWhere(), $groupBy, $having, "", $filter, "");
        }
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
        $this->_Title->DbValue = $row['Title'];
        $this->Category_ID->DbValue = $row['Category_ID'];
        $this->Status->DbValue = $row['Status'];
        $this->_Abstract->DbValue = $row['Abstract'];
        $this->Tags->DbValue = $row['Tags'];
        $this->Uploads->Upload->DbValue = $row['Uploads'];
        $this->Cover->Upload->DbValue = $row['Cover'];
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
        $oldFiles = EmptyValue($row['Cover']) ? [] : [$row['Cover']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->Cover->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->Cover->oldPhysicalUploadPath() . $oldFile);
            }
        }
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
        return $_SESSION[$name] ?? GetUrl("submissionview2list");
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
        if ($pageName == "submissionview2view") {
            return $Language->phrase("View");
        } elseif ($pageName == "submissionview2edit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "submissionview2add") {
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
                return "SubmissionView2View";
            case Config("API_ADD_ACTION"):
                return "SubmissionView2Add";
            case Config("API_EDIT_ACTION"):
                return "SubmissionView2Edit";
            case Config("API_DELETE_ACTION"):
                return "SubmissionView2Delete";
            case Config("API_LIST_ACTION"):
                return "SubmissionView2List";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "submissionview2list";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("submissionview2view", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("submissionview2view", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "submissionview2add?" . $this->getUrlParm($parm);
        } else {
            $url = "submissionview2add";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("submissionview2edit", $this->getUrlParm($parm));
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
        $url = $this->keyUrl("submissionview2add", $this->getUrlParm($parm));
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
        return $this->keyUrl("submissionview2delete", $this->getUrlParm());
    }

    // Add master url
    public function addMasterUrl($url)
    {
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
        $this->_Title->setDbValue($row['Title']);
        $this->Category_ID->setDbValue($row['Category_ID']);
        $this->Status->setDbValue($row['Status']);
        $this->_Abstract->setDbValue($row['Abstract']);
        $this->Tags->setDbValue($row['Tags']);
        $this->Uploads->Upload->DbValue = $row['Uploads'];
        $this->Cover->Upload->DbValue = $row['Cover'];
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

        // Title

        // Category_ID

        // Status

        // Abstract

        // Tags

        // Uploads

        // Cover

        // Updated_Username

        // Updated_Last

        // Updated_IP

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

        // Submission_ID
        $this->Submission_ID->LinkCustomAttributes = "";
        $this->Submission_ID->HrefValue = "";
        $this->Submission_ID->TooltipValue = "";

        // Title
        $this->_Title->LinkCustomAttributes = "";
        $this->_Title->HrefValue = "";
        $this->_Title->TooltipValue = "";

        // Category_ID
        $this->Category_ID->LinkCustomAttributes = "";
        $this->Category_ID->HrefValue = "";
        $this->Category_ID->TooltipValue = "";

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

        // Uploads
        $this->Uploads->LinkCustomAttributes = "";
        $this->Uploads->HrefValue = "";
        $this->Uploads->ExportHrefValue = $this->Uploads->UploadPath . $this->Uploads->Upload->DbValue;
        $this->Uploads->TooltipValue = "";

        // Cover
        $this->Cover->LinkCustomAttributes = "";
        $this->Cover->HrefValue = "";
        $this->Cover->ExportHrefValue = $this->Cover->UploadPath . $this->Cover->Upload->DbValue;
        $this->Cover->TooltipValue = "";

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

        // Title
        $this->_Title->setupEditAttributes();
        $this->_Title->EditCustomAttributes = "";
        if (!$this->_Title->Raw) {
            $this->_Title->CurrentValue = HtmlDecode($this->_Title->CurrentValue);
        }
        $this->_Title->EditValue = $this->_Title->CurrentValue;
        $this->_Title->PlaceHolder = RemoveHtml($this->_Title->caption());

        // Category_ID
        $this->Category_ID->setupEditAttributes();
        $this->Category_ID->EditCustomAttributes = "";
        $this->Category_ID->PlaceHolder = RemoveHtml($this->Category_ID->caption());

        // Status
        $this->Status->setupEditAttributes();
        $this->Status->EditCustomAttributes = "";
        $this->Status->EditValue = $this->Status->options(true);
        $this->Status->PlaceHolder = RemoveHtml($this->Status->caption());

        // Abstract
        $this->_Abstract->setupEditAttributes();
        $this->_Abstract->EditCustomAttributes = "";
        $this->_Abstract->EditValue = $this->_Abstract->CurrentValue;
        $this->_Abstract->PlaceHolder = RemoveHtml($this->_Abstract->caption());

        // Tags
        $this->Tags->setupEditAttributes();
        $this->Tags->EditCustomAttributes = "";
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
                    $doc->exportCaption($this->Submission_ID);
                    $doc->exportCaption($this->_Title);
                    $doc->exportCaption($this->Category_ID);
                    $doc->exportCaption($this->Status);
                    $doc->exportCaption($this->_Abstract);
                    $doc->exportCaption($this->Tags);
                    $doc->exportCaption($this->Uploads);
                    $doc->exportCaption($this->Cover);
                    $doc->exportCaption($this->Updated_Username);
                    $doc->exportCaption($this->Updated_Last);
                    $doc->exportCaption($this->Updated_IP);
                } else {
                    $doc->exportCaption($this->Submission_ID);
                    $doc->exportCaption($this->_Title);
                    $doc->exportCaption($this->Category_ID);
                    $doc->exportCaption($this->Status);
                    $doc->exportCaption($this->Tags);
                    $doc->exportCaption($this->Cover);
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
                        $doc->exportField($this->Category_ID);
                        $doc->exportField($this->Status);
                        $doc->exportField($this->_Abstract);
                        $doc->exportField($this->Tags);
                        $doc->exportField($this->Uploads);
                        $doc->exportField($this->Cover);
                        $doc->exportField($this->Updated_Username);
                        $doc->exportField($this->Updated_Last);
                        $doc->exportField($this->Updated_IP);
                    } else {
                        $doc->exportField($this->Submission_ID);
                        $doc->exportField($this->_Title);
                        $doc->exportField($this->Category_ID);
                        $doc->exportField($this->Status);
                        $doc->exportField($this->Tags);
                        $doc->exportField($this->Cover);
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
        $sql = "SELECT " . $masterfld->Expression . " FROM `submission_view`";
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
        } elseif ($fldparm == 'Cover') {
            $fldName = "Cover";
            $fileNameFld = "Cover";
        } else {
            return false; // Incorrect field
        }

        // Set up key values
        $ar = explode(Config("COMPOSITE_KEY_SEPARATOR"), $key);
        if (count($ar) == 1) {
            $this->Submission_ID->CurrentValue = $ar[0];
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
