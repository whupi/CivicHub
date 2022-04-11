<?php

namespace PHPMaker2022\civichub2;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Table class for users
 */
class Users extends DbTable
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
    public $_Username;
    public $_Password;
    public $First_Name;
    public $Last_Name;
    public $_Email;
    public $User_Level;
    public $Report_To;
    public $Activated;
    public $Locked;
    public $_Profile;
    public $Photo;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage, $CurrentLocale;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'users';
        $this->TableName = 'users';
        $this->TableType = 'TABLE';

        // Update Table
        $this->UpdateTable = "`users`";
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

        // Username
        $this->_Username = new DbField(
            'users',
            'users',
            'x__Username',
            'Username',
            '`Username`',
            '`Username`',
            200,
            50,
            -1,
            false,
            '`Username`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->_Username->InputTextType = "text";
        $this->_Username->IsPrimaryKey = true; // Primary key field
        $this->_Username->Nullable = false; // NOT NULL field
        $this->_Username->Required = true; // Required field
        $this->Fields['Username'] = &$this->_Username;

        // Password
        $this->_Password = new DbField(
            'users',
            'users',
            'x__Password',
            'Password',
            '`Password`',
            '`Password`',
            200,
            64,
            -1,
            false,
            '`Password`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'PASSWORD'
        );
        $this->_Password->InputTextType = "text";
        if (Config("ENCRYPTED_PASSWORD")) {
            $this->_Password->Raw = true;
        }
        $this->_Password->Nullable = false; // NOT NULL field
        $this->_Password->Required = true; // Required field
        $this->Fields['Password'] = &$this->_Password;

        // First_Name
        $this->First_Name = new DbField(
            'users',
            'users',
            'x_First_Name',
            'First_Name',
            '`First_Name`',
            '`First_Name`',
            200,
            50,
            -1,
            false,
            '`First_Name`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->First_Name->InputTextType = "text";
        $this->Fields['First_Name'] = &$this->First_Name;

        // Last_Name
        $this->Last_Name = new DbField(
            'users',
            'users',
            'x_Last_Name',
            'Last_Name',
            '`Last_Name`',
            '`Last_Name`',
            200,
            50,
            -1,
            false,
            '`Last_Name`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->Last_Name->InputTextType = "text";
        $this->Fields['Last_Name'] = &$this->Last_Name;

        // Email
        $this->_Email = new DbField(
            'users',
            'users',
            'x__Email',
            'Email',
            '`Email`',
            '`Email`',
            200,
            100,
            -1,
            false,
            '`Email`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->_Email->InputTextType = "text";
        $this->_Email->Required = true; // Required field
        $this->Fields['Email'] = &$this->_Email;

        // User_Level
        $this->User_Level = new DbField(
            'users',
            'users',
            'x_User_Level',
            'User_Level',
            '`User_Level`',
            '`User_Level`',
            3,
            11,
            -1,
            false,
            '`User_Level`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'SELECT'
        );
        $this->User_Level->InputTextType = "text";
        $this->User_Level->IsForeignKey = true; // Foreign key field
        $this->User_Level->Nullable = false; // NOT NULL field
        $this->User_Level->Required = true; // Required field
        $this->User_Level->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->User_Level->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->User_Level->Lookup = new Lookup('User_Level', 'userlevels', false, 'User_Level_ID', ["User_Level_Name","","",""], [], [], [], [], [], [], '`User_Level_Name`', '', "`User_Level_Name`");
        $this->User_Level->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['User_Level'] = &$this->User_Level;

        // Report_To
        $this->Report_To = new DbField(
            'users',
            'users',
            'x_Report_To',
            'Report_To',
            '`Report_To`',
            '`Report_To`',
            200,
            50,
            -1,
            false,
            '`Report_To`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'SELECT'
        );
        $this->Report_To->InputTextType = "text";
        $this->Report_To->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->Report_To->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->Report_To->Lookup = new Lookup('Report_To', 'users', false, 'Username', ["First_Name","Last_Name","",""], [], [], [], [], [], [], '`First_Name`', '', "CONCAT(COALESCE(`First_Name`, ''),'" . ValueSeparator(1, $this->Report_To) . "',COALESCE(`Last_Name`,''))");
        $this->Fields['Report_To'] = &$this->Report_To;

        // Activated
        $this->Activated = new DbField(
            'users',
            'users',
            'x_Activated',
            'Activated',
            '`Activated`',
            '`Activated`',
            202,
            1,
            -1,
            false,
            '`Activated`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'CHECKBOX'
        );
        $this->Activated->InputTextType = "text";
        $this->Activated->Nullable = false; // NOT NULL field
        $this->Activated->DataType = DATATYPE_BOOLEAN;
        $this->Activated->TrueValue = "Y";
        $this->Activated->FalseValue = "N";
        $this->Activated->Lookup = new Lookup('Activated', 'users', false, '', ["","","",""], [], [], [], [], [], [], '', '', "");
        $this->Activated->OptionCount = 2;
        $this->Fields['Activated'] = &$this->Activated;

        // Locked
        $this->Locked = new DbField(
            'users',
            'users',
            'x_Locked',
            'Locked',
            '`Locked`',
            '`Locked`',
            202,
            1,
            -1,
            false,
            '`Locked`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'CHECKBOX'
        );
        $this->Locked->InputTextType = "text";
        $this->Locked->DataType = DATATYPE_BOOLEAN;
        $this->Locked->TrueValue = "Y";
        $this->Locked->FalseValue = "N";
        $this->Locked->Lookup = new Lookup('Locked', 'users', false, '', ["","","",""], [], [], [], [], [], [], '', '', "");
        $this->Locked->OptionCount = 2;
        $this->Fields['Locked'] = &$this->Locked;

        // Profile
        $this->_Profile = new DbField(
            'users',
            'users',
            'x__Profile',
            'Profile',
            '`Profile`',
            '`Profile`',
            201,
            65535,
            -1,
            false,
            '`Profile`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXTAREA'
        );
        $this->_Profile->InputTextType = "text";
        $this->Fields['Profile'] = &$this->_Profile;

        // Photo
        $this->Photo = new DbField(
            'users',
            'users',
            'x_Photo',
            'Photo',
            '`Photo`',
            '`Photo`',
            200,
            100,
            -1,
            true,
            '`Photo`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'FILE'
        );
        $this->Photo->InputTextType = "text";
        $this->Fields['Photo'] = &$this->Photo;

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
        if ($this->getCurrentMasterTable() == "userlevels") {
            if ($this->User_Level->getSessionValue() != "") {
                $masterFilter .= "" . GetForeignKeySql("`User_Level_ID`", $this->User_Level->getSessionValue(), DATATYPE_NUMBER, "DB");
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
        if ($this->getCurrentMasterTable() == "userlevels") {
            if ($this->User_Level->getSessionValue() != "") {
                $detailFilter .= "" . GetForeignKeySql("`User_Level`", $this->User_Level->getSessionValue(), DATATYPE_NUMBER, "DB");
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
            case "userlevels":
                $key = $keys["User_Level"] ?? "";
                if (EmptyValue($key)) {
                    if ($masterTable->User_Level_ID->Required) { // Required field and empty value
                        return ""; // Return empty filter
                    }
                    $validKeys = false;
                } elseif (!$validKeys) { // Already has empty key
                    return ""; // Return empty filter
                }
                if ($validKeys) {
                    return "`User_Level_ID`=" . QuotedValue($keys["User_Level"], $masterTable->User_Level_ID->DataType, $masterTable->Dbid);
                }
                break;
        }
        return null; // All null values and no required fields
    }

    // Get detail filter
    public function getDetailFilter($masterTable)
    {
        switch ($masterTable->TableVar) {
            case "userlevels":
                return "`User_Level`=" . QuotedValue($masterTable->User_Level_ID->DbValue, $this->User_Level->DataType, $this->Dbid);
        }
        return "";
    }

    // Table level SQL
    public function getSqlFrom() // From
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`users`";
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
            if (Config("ENCRYPTED_PASSWORD") && $name == Config("LOGIN_PASSWORD_FIELD_NAME")) {
                $value = Config("CASE_SENSITIVE_PASSWORD") ? EncryptPassword($value) : EncryptPassword(strtolower($value));
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
            if (Config("ENCRYPTED_PASSWORD") && $name == Config("LOGIN_PASSWORD_FIELD_NAME")) {
                if ($value == $this->Fields[$name]->OldValue) { // No need to update hashed password if not changed
                    continue;
                }
                $value = Config("CASE_SENSITIVE_PASSWORD") ? EncryptPassword($value) : EncryptPassword(strtolower($value));
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
            if (array_key_exists('Username', $rs)) {
                AddFilter($where, QuotedName('Username', $this->Dbid) . '=' . QuotedValue($rs['Username'], $this->_Username->DataType, $this->Dbid));
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
        $this->_Username->DbValue = $row['Username'];
        $this->_Password->DbValue = $row['Password'];
        $this->First_Name->DbValue = $row['First_Name'];
        $this->Last_Name->DbValue = $row['Last_Name'];
        $this->_Email->DbValue = $row['Email'];
        $this->User_Level->DbValue = $row['User_Level'];
        $this->Report_To->DbValue = $row['Report_To'];
        $this->Activated->DbValue = $row['Activated'];
        $this->Locked->DbValue = $row['Locked'];
        $this->_Profile->DbValue = $row['Profile'];
        $this->Photo->Upload->DbValue = $row['Photo'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
        $oldFiles = EmptyValue($row['Photo']) ? [] : [$row['Photo']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->Photo->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->Photo->oldPhysicalUploadPath() . $oldFile);
            }
        }
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "`Username` = '@_Username@'";
    }

    // Get Key
    public function getKey($current = false)
    {
        $keys = [];
        $val = $current ? $this->_Username->CurrentValue : $this->_Username->OldValue;
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
                $this->_Username->CurrentValue = $keys[0];
            } else {
                $this->_Username->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('Username', $row) ? $row['Username'] : null;
        } else {
            $val = $this->_Username->OldValue !== null ? $this->_Username->OldValue : $this->_Username->CurrentValue;
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@_Username@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
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
        return $_SESSION[$name] ?? GetUrl("userslist");
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
        if ($pageName == "usersview") {
            return $Language->phrase("View");
        } elseif ($pageName == "usersedit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "usersadd") {
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
                return "UsersView";
            case Config("API_ADD_ACTION"):
                return "UsersAdd";
            case Config("API_EDIT_ACTION"):
                return "UsersEdit";
            case Config("API_DELETE_ACTION"):
                return "UsersDelete";
            case Config("API_LIST_ACTION"):
                return "UsersList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "userslist";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("usersview", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("usersview", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "usersadd?" . $this->getUrlParm($parm);
        } else {
            $url = "usersadd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("usersedit", $this->getUrlParm($parm));
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
        $url = $this->keyUrl("usersadd", $this->getUrlParm($parm));
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
        return $this->keyUrl("usersdelete", $this->getUrlParm());
    }

    // Add master url
    public function addMasterUrl($url)
    {
        if ($this->getCurrentMasterTable() == "userlevels" && !ContainsString($url, Config("TABLE_SHOW_MASTER") . "=")) {
            $url .= (ContainsString($url, "?") ? "&" : "?") . Config("TABLE_SHOW_MASTER") . "=" . $this->getCurrentMasterTable();
            $url .= "&" . GetForeignKeyUrl("fk_User_Level_ID", $this->User_Level->CurrentValue);
        }
        return $url;
    }

    public function keyToJson($htmlEncode = false)
    {
        $json = "";
        $json .= "\"_Username\":" . JsonEncode($this->_Username->CurrentValue, "string");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->_Username->CurrentValue !== null) {
            $url .= "/" . $this->encodeKeyValue($this->_Username->CurrentValue);
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
            if (($keyValue = Param("_Username") ?? Route("_Username")) !== null) {
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
                $this->_Username->CurrentValue = $key;
            } else {
                $this->_Username->OldValue = $key;
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
    }

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

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

        // Profile
        $this->_Profile->ViewValue = $this->_Profile->CurrentValue;
        $this->_Profile->ViewCustomAttributes = "";

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

        // Password
        $this->_Password->LinkCustomAttributes = "";
        $this->_Password->HrefValue = "";
        $this->_Password->TooltipValue = "";

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

        // Profile
        $this->_Profile->LinkCustomAttributes = "";
        $this->_Profile->HrefValue = "";
        $this->_Profile->TooltipValue = "";

        // Photo
        $this->Photo->LinkCustomAttributes = "";
        $this->Photo->HrefValue = "";
        $this->Photo->ExportHrefValue = $this->Photo->UploadPath . $this->Photo->Upload->DbValue;
        $this->Photo->TooltipValue = "";

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

        // Username
        $this->_Username->setupEditAttributes();
        $this->_Username->EditCustomAttributes = "";
        if (!$this->_Username->Raw) {
            $this->_Username->CurrentValue = HtmlDecode($this->_Username->CurrentValue);
        }
        $this->_Username->EditValue = $this->_Username->CurrentValue;
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
        $this->First_Name->EditValue = $this->First_Name->CurrentValue;
        $this->First_Name->PlaceHolder = RemoveHtml($this->First_Name->caption());

        // Last_Name
        $this->Last_Name->setupEditAttributes();
        $this->Last_Name->EditCustomAttributes = "";
        if (!$this->Last_Name->Raw) {
            $this->Last_Name->CurrentValue = HtmlDecode($this->Last_Name->CurrentValue);
        }
        $this->Last_Name->EditValue = $this->Last_Name->CurrentValue;
        $this->Last_Name->PlaceHolder = RemoveHtml($this->Last_Name->caption());

        // Email
        $this->_Email->setupEditAttributes();
        $this->_Email->EditCustomAttributes = "";
        if (!$this->_Email->Raw) {
            $this->_Email->CurrentValue = HtmlDecode($this->_Email->CurrentValue);
        }
        $this->_Email->EditValue = $this->_Email->CurrentValue;
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
            }
        } else {
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

        // Profile
        $this->_Profile->setupEditAttributes();
        $this->_Profile->EditCustomAttributes = "";
        $this->_Profile->EditValue = $this->_Profile->CurrentValue;
        $this->_Profile->PlaceHolder = RemoveHtml($this->_Profile->caption());

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
                    $doc->exportCaption($this->_Username);
                    $doc->exportCaption($this->_Password);
                    $doc->exportCaption($this->First_Name);
                    $doc->exportCaption($this->Last_Name);
                    $doc->exportCaption($this->_Email);
                    $doc->exportCaption($this->User_Level);
                    $doc->exportCaption($this->Report_To);
                    $doc->exportCaption($this->Activated);
                    $doc->exportCaption($this->Locked);
                    $doc->exportCaption($this->Photo);
                } else {
                    $doc->exportCaption($this->_Username);
                    $doc->exportCaption($this->_Password);
                    $doc->exportCaption($this->First_Name);
                    $doc->exportCaption($this->Last_Name);
                    $doc->exportCaption($this->_Email);
                    $doc->exportCaption($this->User_Level);
                    $doc->exportCaption($this->Report_To);
                    $doc->exportCaption($this->Activated);
                    $doc->exportCaption($this->Locked);
                    $doc->exportCaption($this->Photo);
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
                        $doc->exportField($this->_Username);
                        $doc->exportField($this->_Password);
                        $doc->exportField($this->First_Name);
                        $doc->exportField($this->Last_Name);
                        $doc->exportField($this->_Email);
                        $doc->exportField($this->User_Level);
                        $doc->exportField($this->Report_To);
                        $doc->exportField($this->Activated);
                        $doc->exportField($this->Locked);
                        $doc->exportField($this->Photo);
                    } else {
                        $doc->exportField($this->_Username);
                        $doc->exportField($this->_Password);
                        $doc->exportField($this->First_Name);
                        $doc->exportField($this->Last_Name);
                        $doc->exportField($this->_Email);
                        $doc->exportField($this->User_Level);
                        $doc->exportField($this->Report_To);
                        $doc->exportField($this->Activated);
                        $doc->exportField($this->Locked);
                        $doc->exportField($this->Photo);
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

    // User ID filter
    public function getUserIDFilter($userId)
    {
        global $Security;
        $userIdFilter = '`Username` = ' . QuotedValue($userId, DATATYPE_STRING, Config("USER_TABLE_DBID"));
        $parentUserIdFilter = '`Username` IN (SELECT `Username` FROM ' . "`users`" . ' WHERE ' . GetMultiSearchSql($this->Report_To, "=", $userId, Config("USER_TABLE_DBID")) . ')';
        $userIdFilter = "(" . $userIdFilter . ") OR (" . $parentUserIdFilter . ")";
        return $userIdFilter;
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
                $filterWrk = '`Username` IN (' . $filterWrk . ')';
            }
        }

        // Call User ID Filtering event
        $this->userIdFiltering($filterWrk);
        AddFilter($filter, $filterWrk);
        return $filter;
    }

    // Add Parent User ID filter
    public function addParentUserIDFilter($userId)
    {
        global $Security;
        if (!$Security->isAdmin()) {
            $result = $Security->parentUserIDList($userId);
            if ($result != "") {
                $result = '`Username` IN (' . $result . ')';
            }
            return $result;
        }
        return "";
    }

    // User ID subquery
    public function getUserIDSubquery(&$fld, &$masterfld)
    {
        global $UserTable;
        $wrk = "";
        $sql = "SELECT " . $masterfld->Expression . " FROM `users`";
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
        if ($fldparm == 'Photo') {
            $fldName = "Photo";
            $fileNameFld = "Photo";
        } else {
            return false; // Incorrect field
        }

        // Set up key values
        $ar = explode(Config("COMPOSITE_KEY_SEPARATOR"), $key);
        if (count($ar) == 1) {
            $this->_Username->CurrentValue = $ar[0];
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
