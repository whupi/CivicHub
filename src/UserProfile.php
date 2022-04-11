<?php

namespace PHPMaker2022\civichub2;

/**
 * User Profile Class
 */
class UserProfile
{
    public $Username = "";
    public $Profile = [];
    public $Provider = "";
    public $Auth = "";
    public $MaxRetryCount;
    public $RetryLockoutTime;
    protected $BackupUsername = "";
    protected $BackupProfile = [];
    protected $Excluded = []; // Excluded data (not to be saved to database)

    // Constructor
    public function __construct()
    {
        $this->MaxRetryCount = Config("USER_PROFILE_MAX_RETRY");
        $this->RetryLockoutTime = Config("USER_PROFILE_RETRY_LOCKOUT");
        $this->load();

        // Max login retry
        $this->set(Config("USER_PROFILE_LOGIN_RETRY_COUNT"), 0);
        $this->set(Config("USER_PROFILE_LAST_BAD_LOGIN_DATE_TIME"), "");
    }

    // Has value
    public function has($name)
    {
        return array_key_exists($name, $this->Profile);
    }

    // Get value
    public function getValue($name)
    {
        return $this->Profile[$name] ?? null;
    }

    // Get all values
    public function getValues()
    {
        return $this->Profile;
    }

    // Get value (alias)
    public function get($name)
    {
        return $this->getValue($name);
    }

    // Set value
    public function setValue($name, $value)
    {
        $this->Profile[$name] = $value;
    }

    // Set value (alias)
    public function set($name, $value)
    {
        $this->setValue($name, $value);
    }

    // Set property // PHP
    public function __set($name, $value)
    {
        $this->setValue($name, $value);
    }

    // Get property // PHP
    public function __get($name)
    {
        return $this->getValue($name);
    }

    // Delete property
    public function delete($name)
    {
        if (array_key_exists($name, $this->Profile)) {
            unset($this->Profile[$name]);
        }
    }

    // Assign properties
    public function assign($input, $save = true)
    {
        if (is_array($input) && !$save) {
            $this->Excluded = array_merge($this->Excluded, $input);
        }
        if (is_object($input)) {
            $this->assign(get_object_vars($input), $save);
        } elseif (is_array($input)) {
            foreach ($input as $key => $value) { // Remove integer keys
                if (is_int($key)) {
                    unset($input[$key]);
                }
            }
            $input = array_filter($input, function ($val) {
                if (is_bool($val) || is_float($val) || is_int($val) || $val === null || is_string($val) && strlen($val) <= Config("DATA_STRING_MAX_LENGTH")) {
                    return true;
                }
                return false;
            });
            $this->Profile = array_merge($this->Profile, $input);
        }
    }

    // Check if System Admin
    protected function isSystemAdmin($usr)
    {
        $adminUserName = Config("ENCRYPTION_ENABLED") ? PhpDecrypt(Config("ADMIN_USER_NAME")) : Config("ADMIN_USER_NAME");
        return $usr == "" || $usr == $adminUserName;
    }

    // Backup user profile if user is different from existing user
    protected function backup($usr)
    {
        if ($this->Username != "" && $usr != $this->Username) {
            $this->BackupUsername = $this->Username;
            $this->BackupProfile = $this->Profile;
        }
    }

    // Restore user profile if user is different from backup user
    protected function restore($usr)
    {
        if ($this->BackupUsername != "" && $usr != $this->BackupUsername) {
            $this->Username = $this->BackupUsername;
            $this->Profile = $this->BackupProfile;
        }
    }

    // Get language id
    public function getLanguageId($usr)
    {
        try {
            if ($this->loadProfileFromDatabase($usr)) {
                return $this->get(Config("USER_PROFILE_LANGUAGE_ID"));
            }
        } catch (\Throwable $e) {
            if (Config("DEBUG")) {
                throw $e;
            }
        } finally {
            $this->restore($usr); // Restore current profile
        }
        return "";
    }

    // Set language id
    public function setLanguageId($usr, $langid)
    {
        try {
            if ($this->loadProfileFromDatabase($usr)) {
                $this->set(Config("USER_PROFILE_LANGUAGE_ID"), $langid);
                return $this->saveProfileToDatabase($usr);
            }
        } catch (\Throwable $e) {
            if (Config("DEBUG")) {
                throw $e;
            }
        } finally {
            $this->restore($usr); // Restore current profile
        }
        return false;
    }

    // Get search filters
    public function getSearchFilters($usr, $pageid)
    {
        try {
            if ($this->loadProfileFromDatabase($usr)) {
                $allfilters = @unserialize($this->get(Config("USER_PROFILE_SEARCH_FILTERS")));
                return @$allfilters[$pageid];
            }
        } catch (\Throwable $e) {
            if (Config("DEBUG")) {
                throw $e;
            }
        } finally {
            $this->restore($usr); // Restore current profile
        }
        return "";
    }

    // Set search filters
    public function setSearchFilters($usr, $pageid, $filters)
    {
        try {
            if ($this->loadProfileFromDatabase($usr)) {
                $allfilters = @unserialize($this->get(Config("USER_PROFILE_SEARCH_FILTERS")));
                if (!is_array($allfilters)) {
                    $allfilters = [];
                }
                $allfilters[$pageid] = $filters;
                $this->set(Config("USER_PROFILE_SEARCH_FILTERS"), serialize($allfilters));
                return $this->saveProfileToDatabase($usr);
            }
        } catch (\Throwable $e) {
            if (Config("DEBUG")) {
                throw $e;
            }
        } finally {
            $this->restore($usr); // Restore current profile
        }
        return false;
    }

    // Load profile from database
    public function loadProfileFromDatabase($usr)
    {
        global $UserTable;
        if ($this->isSystemAdmin($usr)) { // Ignore system admin
            return false;
        } elseif ($usr == $this->Username) { // Already loaded, skip
            return true;
        }
        $filter = GetUserFilter(Config("LOGIN_USERNAME_FIELD_NAME"), $usr);
        // Get SQL from getSql() method in <UserTable> class
        $sql = "SELECT " . QuotedName(Config("USER_PROFILE_FIELD_NAME"), Config("USER_TABLE_DBID")) . " FROM " . Config("USER_TABLE") . " WHERE " . $filter;
        $data = $UserTable->getConnection()->fetchNumeric($sql);
        if ($data !== false) {
            $this->backup($usr); // Backup user profile if exists
            $this->clear();
            $this->loadProfile(HtmlDecode($data[0]));
            $this->Username = $usr; // Set current profile username
            return true;
        }
        return false;
    }

    // Save profile to database
    public function saveProfileToDatabase($usr)
    {
        global $UserTable;
        if ($this->isSystemAdmin($usr)) { // Ignore system admin
            return false;
        }
        $filter = GetUserFilter(Config("LOGIN_USERNAME_FIELD_NAME"), $usr);
        $rs = [Config("USER_PROFILE_FIELD_NAME") => $this->profileToString()];
        return $UserTable->update($rs, $filter);
    }

    // Load profile from session
    public function load()
    {
        if (isset($_SESSION[SESSION_USER_PROFILE])) {
            $this->loadProfile($_SESSION[SESSION_USER_PROFILE]);
        }
    }

    // Save profile to session
    public function save()
    {
        $_SESSION[SESSION_USER_PROFILE] = $this->profileToString();
    }

    // Load profile from string
    protected function loadProfile($profile)
    {
        $ar = unserialize(strval($profile));
        if (is_array($ar)) {
            $this->Profile = array_merge($this->Profile, $ar);
        }
    }

    // Write (var_dump) profile
    public function writeProfile()
    {
        var_dump($this->Profile);
    }

    // Clear profile
    protected function clearProfile()
    {
        $this->Profile = [];
    }

    // Clear profile (alias)
    public function clear()
    {
        $this->clearProfile();
    }

    // Profile to string
    protected function profileToString()
    {
        $data = array_diff_assoc($this->Profile, $this->Excluded);
        return serialize($data);
    }

    // Exceed login retry
    public function exceedLoginRetry($usr)
    {
        if ($this->isSystemAdmin($usr)) { // Ignore system admin
            return false;
        }
        try {
            if ($this->loadProfileFromDatabase($usr)) {
                $retrycount = $this->get(Config("USER_PROFILE_LOGIN_RETRY_COUNT"));
                $dt = $this->get(Config("USER_PROFILE_LAST_BAD_LOGIN_DATE_TIME"));
                if ((int)$retrycount >= (int)$this->MaxRetryCount) {
                    if (DateDiff($dt, StdCurrentDateTime(), "n") < $this->RetryLockoutTime) {
                        return true;
                    } else {
                        $this->set(Config("USER_PROFILE_LOGIN_RETRY_COUNT"), 0);
                        $this->saveProfileToDatabase($usr);
                    }
                }
            }
        } catch (\Throwable $e) {
            if (Config("DEBUG")) {
                throw $e;
            }
        } finally {
            $this->restore($usr); // Restore current profile
        }
        return false;
    }

    // Reset login retry
    public function resetLoginRetry($usr)
    {
        try {
            if ($this->loadProfileFromDatabase($usr)) {
                $this->set(Config("USER_PROFILE_LOGIN_RETRY_COUNT"), 0);
                return $this->saveProfileToDatabase($usr);
            }
        } catch (\Throwable $e) {
            if (Config("DEBUG")) {
                throw $e;
            }
        } finally {
            $this->restore($usr); // Restore current profile
        }
        return false;
    }

    // User has 2FA secret
    public function hasUserSecret($usr, $verified = false)
    {
        try {
            if ($this->loadProfileFromDatabase($usr)) {
                $secret = $this->get(Config("USER_PROFILE_SECRET"));
                $valid = $secret !== null && $secret !== ""; // Secret is not empty
                if ($valid && $verified) {
                    $verifyDateTime = $this->get(Config("USER_PROFILE_SECRET_VERIFY_DATE_TIME"));
                    $verifyCode = $this->get(Config("USER_PROFILE_SECRET_LAST_VERIFY_CODE"));
                    $valid = !empty($verifyDateTime) && !empty($verifyCode);
                }
                return $valid;
            }
        } catch (\Throwable $e) {
            if (Config("DEBUG")) {
                throw $e;
            }
        } finally {
            $this->restore($usr); // Restore current profile
        }
        return false;
    }

    // Get User 2FA secret
    public function getUserSecret($usr)
    {
        try {
            if ($this->loadProfileFromDatabase($usr)) {
                $secret = $this->get(Config("USER_PROFILE_SECRET"));
                // Create new secret and save to profile
                if (EmptyString($secret)) {
                    $secret = TwoFactorAuthentication::generateSecret();
                    $backupCodes = TwoFactorAuthentication::generateBackupCodes();
                    $this->set(Config("USER_PROFILE_SECRET"), $secret);
                    $this->set(Config("USER_PROFILE_SECRET_CREATE_DATE_TIME"), DbCurrentDateTime());
                    $this->setBackupCodes($backupCodes);
                    $this->saveProfileToDatabase($usr);
                }
                return $secret;
            }
        } catch (\Throwable $e) {
            if (Config("DEBUG")) {
                throw $e;
            }
        } finally {
            $this->restore($usr); // Restore current profile
        }
        return "";
    }

    // Get backup codes
    public function getBackupCodes($usr = "")
    {
        try {
            if (EmptyValue($usr) || $this->loadProfileFromDatabase($usr)) {
                $codes = $this->get(Config("USER_PROFILE_BACKUP_CODES"));
                $decryptedCodes = is_array($codes) ? array_map(function ($code) {
                    return strlen($code) == Config("TWO_FACTOR_AUTHENTICATION_BACKUP_CODE_LENGTH") ? $code : PhpDecrypt(strval($code)); // Encrypt backup codes if necessary
                }, $codes) : [];
                return $decryptedCodes;
            }
        } catch (\Throwable $e) {
            if (Config("DEBUG")) {
                throw $e;
            }
        } finally {
            $this->restore($usr); // Restore current profile
        }
    }

    // Set backup codes to profile
    protected function setBackupCodes(array $codes)
    {
        try {
            $encryptedCodes = array_map(function ($code) {
                return strlen($code) == Config("TWO_FACTOR_AUTHENTICATION_BACKUP_CODE_LENGTH") ? PhpEncrypt(strval($code)) : $code; // Encrypt backup codes if necessary
            }, $codes);
            $this->set(Config("USER_PROFILE_BACKUP_CODES"), $encryptedCodes);
        } catch (\Throwable $e) {
            if (Config("DEBUG")) {
                throw $e;
            }
        }
    }

    // Get new set of backup codes
    public function getNewBackupCodes($usr): array
    {
        try {
            if ($this->loadProfileFromDatabase($usr)) {
                $codes = TwoFactorAuthentication::generateBackupCodes();
                $this->setBackupCodes($codes);
                $this->saveProfileToDatabase($usr);
                return $codes;
            }
        } catch (\Throwable $e) {
            if (Config("DEBUG")) {
                throw $e;
            }
        } finally {
            $this->restore($usr); // Restore current profile
        }
        return [];
    }

    // Verify 2FA code
    public function verify2FACode($usr, $code)
    {
        try {
            if ($this->loadProfileFromDatabase($usr)) {
                $secret = $this->get(Config("USER_PROFILE_SECRET"));
                if ($secret !== "") { // Secret is not empty
                    $valid = TwoFactorAuthentication::checkCode($secret, $code);
                    if (!$valid && strlen($code) == Config("TWO_FACTOR_AUTHENTICATION_BACKUP_CODE_LENGTH")) { // Not valid, check if $code is backup code
                        $backupCodes = $this->getBackupCodes();
                        $valid = array_search($code, $backupCodes);
                        if ($valid !== false) {
                            array_splice($backupCodes, $valid, 1); // Remove used backup code
                            $this->setBackupCodes($backupCodes);
                            $valid = true;
                        }
                    }
                    if ($valid) { // Update verify date/time
                        $this->set(Config("USER_PROFILE_SECRET_VERIFY_DATE_TIME"), DbCurrentDateTime());
                        $this->set(Config("USER_PROFILE_SECRET_LAST_VERIFY_CODE"), $code);
                        $this->saveProfileToDatabase($usr);
                    }
                    return $valid;
                }
            }
        } catch (\Throwable $e) {
            if (Config("DEBUG")) {
                throw $e;
            }
        } finally {
            $this->restore($usr); // Restore current profile
        }
        return false;
    }

    // Reset user secret
    public function resetUserSecret($usr)
    {
        try {
            if ($this->loadProfileFromDatabase($usr)) {
                $this->delete(Config("USER_PROFILE_SECRET"));
                $this->delete(Config("USER_PROFILE_SECRET_CREATE_DATE_TIME"));
                $this->delete(Config("USER_PROFILE_SECRET_VERIFY_DATE_TIME"));
                $this->delete(Config("USER_PROFILE_SECRET_LAST_VERIFY_CODE"));
                $this->delete(Config("USER_PROFILE_BACKUP_CODES"));
                return $this->saveProfileToDatabase($usr);
            }
        } catch (\Throwable $e) {
            if (Config("DEBUG")) {
                throw $e;
            }
        } finally {
            $this->restore($usr); // Restore current profile
        }
        return false;
    }
}
