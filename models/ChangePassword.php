<?php

namespace PHPMaker2022\civichub2;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class ChangePassword extends Users
{
    use MessagesTrait;

    // Page ID
    public $PageID = "change_password";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "ChangePassword";

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

        // Table object (users)
        if (!isset($GLOBALS["users"]) || get_class($GLOBALS["users"]) == PROJECT_NAMESPACE . "users") {
            $GLOBALS["users"] = &$this;
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

            // Handle modal response
            if ($this->IsModal) { // Show as modal
                $row = ["url" => $url];
                WriteJson($row);
            } else {
                SaveDebugMessage();
                Redirect(GetUrl($url));
            }
        }
        return; // Return to controller
    }
    public $IsModal = false;
    public $OldPassword;
    public $NewPassword;
    public $ConfirmPassword;

    /**
     * Page run
     *
     * @return void
     */
    public function run()
    {
        global $ExportType, $CustomExportType, $ExportFileName, $UserProfile, $Language, $Security, $CurrentForm,
            $UserTable, $Breadcrumb, $SkipHeaderFooter;
        $this->OffsetColumnClass = ""; // Override user table

        // Create Password fields object (used by validation only)
        $this->OldPassword = new DbField("users", "users", "opwd", "opwd", "opwd", "", 202, 255, -1, false, "", false, false, false);
        $this->OldPassword->EditAttrs->appendClass("form-control ew-form-control");
        $this->NewPassword = new DbField("users", "users", "npwd", "npwd", "npwd", "", 202, 255, -1, false, "", false, false, false);
        $this->NewPassword->EditAttrs->appendClass("form-control ew-form-control");
        $this->ConfirmPassword = new DbField("users", "users", "cpwd", "cpwd", "cpwd", "", 202, 255, -1, false, "", false, false, false);
        $this->ConfirmPassword->EditAttrs->appendClass("form-control ew-form-control");
        if (Config("ENCRYPTED_PASSWORD")) {
            $this->OldPassword->Raw = true;
            $this->NewPassword->Raw = true;
            $this->ConfirmPassword->Raw = true;
        }

// Is modal
        $this->IsModal = Param("modal") == "1";
        $this->UseLayout = $this->UseLayout && !$this->IsModal;

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

        // Check modal
        if ($this->IsModal) {
            $SkipHeaderFooter = true;
        }
        $Breadcrumb = new Breadcrumb("index");
        $Breadcrumb->add("change_password", "ChangePasswordPage", CurrentUrl(), "", "", true);
        $this->Heading = $Language->phrase("ChangePasswordPage");
        $postBack = IsPost();
        $validate = true;
        if ($postBack) {
            $this->OldPassword->setFormValue(Post($this->OldPassword->FieldVar));
            $this->NewPassword->setFormValue(Post($this->NewPassword->FieldVar));
            $this->ConfirmPassword->setFormValue(Post($this->ConfirmPassword->FieldVar));
            $validate = $this->validateForm();
        }
        $pwdUpdated = false;
        if ($postBack && $validate) {
            // Setup variables
            $userName = $Security->currentUserName();
            if (IsPasswordReset()) {
                $userName = Session(SESSION_USER_PROFILE_USER_NAME);
            }
            $filter = GetUserFilter(Config("LOGIN_USERNAME_FIELD_NAME"), $userName);

            // Set up filter (WHERE Clause)
            $this->CurrentFilter = $filter;
            $sql = $this->getCurrentSql();
            if ($rsold = Conn($UserTable->Dbid)->fetchAssociative($sql)) {
                if (IsPasswordReset() || ComparePassword(GetUserInfo(Config("LOGIN_PASSWORD_FIELD_NAME"), $rsold), $this->OldPassword->CurrentValue)) {
                    $validPwd = true;
                    if (!IsPasswordReset()) {
                        $validPwd = $this->userChangePassword($rsold, $userName, $this->OldPassword->CurrentValue, $this->NewPassword->CurrentValue);
                    }
                    if ($validPwd) {
                        $rsnew = [Config("LOGIN_PASSWORD_FIELD_NAME") => $this->NewPassword->CurrentValue]; // Change Password
                        $validPwd = $this->update($rsnew);
                        if ($validPwd) {
                            $pwdUpdated = true;
                        }
                    } else {
                        $this->setFailureMessage($Language->phrase("InvalidNewPassword"));
                    }
                } else {
                    $this->setFailureMessage($Language->phrase("InvalidPassword"));
                }
            }
        }
        if ($pwdUpdated) {
            if ($this->getSuccessMessage() == "") {
                $this->setSuccessMessage($Language->phrase("PasswordChanged")); // Set up success message
            }
            if (IsPasswordReset()) {
                $_SESSION[SESSION_STATUS] = "";
                $_SESSION[SESSION_USER_PROFILE_USER_NAME] = "";
            }
            $this->terminate("index"); // Return to default page
            return;
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

    // Validate form
    protected function validateForm()
    {
        global $Language;

        // Check if validation required
        if (!Config("SERVER_VALIDATE")) {
            return true;
        }
        $valid = true;
        if (!IsPasswordReset() && EmptyValue($this->OldPassword->CurrentValue)) {
            $this->OldPassword->addErrorMessage($Language->phrase("EnterOldPassword"));
            $valid = false;
        }
        if (EmptyValue($this->NewPassword->CurrentValue)) {
            $this->NewPassword->addErrorMessage($Language->phrase("EnterNewPassword"));
            $valid = false;
        }
        if (!$this->NewPassword->Raw && Config("REMOVE_XSS") && CheckPassword($this->NewPassword->CurrentValue)) {
            $this->NewPassword->addErrorMessage($Language->phrase("InvalidPasswordChars"));
        }
        if ($this->NewPassword->CurrentValue != $this->ConfirmPassword->CurrentValue) {
            $this->ConfirmPassword->addErrorMessage($Language->phrase("MismatchPassword"));
        }

        // Call Form CustomValidate event
        $formCustomError = "";
        $valid = $valid && $this->formCustomValidate($formCustomError);
        if ($formCustomError != "") {
            $this->setFailureMessage($formCustomError);
        }
        return $valid;
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

    // Email Sending event
    public function emailSending($email, &$args)
    {
        //var_dump($email, $args); exit();
        return true;
    }

    // Form Custom Validate event
    public function formCustomValidate(&$customError)
    {
        // Return error message in $customError
        return true;
    }

    // User ChangePassword event
    public function userChangePassword(&$rs, $usr, $oldpwd, &$newpwd)
    {
        // Return false to abort
        return true;
    }
}
