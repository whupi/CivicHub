<?php

namespace PHPMaker2022\civichub2;

/**
 * Langauge class
 */
class Language
{
    protected $Phrases = null;
    public $LanguageId;
    public $LanguageFolder;
    public $Template = ""; // JsRender template
    public $Method = "prependTo"; // JsRender template method
    public $Target = ".navbar-nav.ms-auto"; // JsRender template target
    // public $Type = "LI"; // LI/DROPDOWN (for used with top Navbar) or SELECT/RADIO (NOT for used with top Navbar)
	public $Type = "DROPDOWN"; // available: LI, DROPDOWN, SELECT, or RADIO

    // Constructor
    public function __construct()
    {
        global $CurrentLanguage;
        $this->LanguageFolder = Config("LANGUAGE_FOLDER");
        $this->loadFileList(); // Set up file list
        if (Param("language", "") != "") {
            $this->LanguageId = Param("language");
            $_SESSION[SESSION_LANGUAGE_ID] = $this->LanguageId;
        } elseif (Session(SESSION_LANGUAGE_ID) != "") {
            $this->LanguageId = Session(SESSION_LANGUAGE_ID);
        } else {
            $this->LanguageId = Config("LANGUAGE_DEFAULT_ID");
        }
        $CurrentLanguage = $this->LanguageId;
        $this->loadLanguage($this->LanguageId);

        // Call Language Load event
        $this->languageLoad();
        SetClientVar("languages", ["languages" => $this->getLanguages()]);
    }

    // Load language file list
    protected function loadFileList()
    {
        global $LANGUAGES;
        if (is_array($LANGUAGES)) {
            $cnt = count($LANGUAGES);
            for ($i = 0; $i < $cnt; $i++) {
                $LANGUAGES[$i][1] = $this->loadFileDesc($this->LanguageFolder . $LANGUAGES[$i][2]);
            }
        }
    }

    // Load language file description
    protected function loadFileDesc($file)
    {
        $ar = Xml2Array(substr(file_get_contents($file), 0, 512)); // Just read the first part
        return (is_array($ar)) ? @$ar["ew-language"]["attr"]["desc"] : "";
    }

    // Load language file
    protected function loadLanguage($id)
    {
        global $CURRENCY_CODE, $CURRENCY_SYMBOL, $DECIMAL_SEPARATOR, $GROUPING_SEPARATOR, 
            $NUMBER_FORMAT, $CURRENCY_FORMAT, $PERCENT_SYMBOL, $PERCENT_FORMAT, $NUMBERING_SYSTEM,
            $DATE_FORMAT, $TIME_FORMAT, $DATE_SEPARATOR, $TIME_SEPARATOR, $TIME_ZONE;
        $fileName = $this->getFileName($id) ?: $this->getFileName(Config("LANGUAGE_DEFAULT_ID"));
        if ($fileName == "") {
            return;
        }
        $phrases = Session(PROJECT_NAME . "_" . $fileName);
        if (is_array($phrases)) {
            $this->Phrases = $phrases;
        } else {
            $this->Phrases = Xml2Array(file_get_contents($fileName));
        }

        // Set up locale for the language
        $locale = LocaleConvert();
        $CURRENCY_CODE = $locale["currency_code"];
        $CURRENCY_SYMBOL = $locale["currency_symbol"];
        $DECIMAL_SEPARATOR = $locale["decimal_separator"];
        $GROUPING_SEPARATOR = $locale["grouping_separator"];
        $NUMBER_FORMAT = $locale["number"];
        $CURRENCY_FORMAT = $locale["currency"];
        $PERCENT_SYMBOL = $locale["percent_symbol"];
        $PERCENT_FORMAT = $locale["percent"];
		$NUMBERING_SYSTEM = $locale["numbering_system"];
        $DATE_FORMAT = $locale["date"];
        $TIME_FORMAT = $locale["time"];
        $DATE_SEPARATOR = $locale["date_separator"];
        $TIME_SEPARATOR = $locale["time_separator"];
        $TIME_ZONE = $locale["time_zone"];

        // Set up time zone from locale file (see https://www.php.net/timezones for supported time zones)
        if (!empty($TIME_ZONE)) {
            date_default_timezone_set($TIME_ZONE);
        }
    }

    // Get language file name
    protected function getFileName($id)
    {
        global $LANGUAGES;
        if (is_array($LANGUAGES)) {
            $cnt = count($LANGUAGES);
            for ($i = 0; $i < $cnt; $i++) {
                if ($LANGUAGES[$i][0] == $id) {
                    return $this->LanguageFolder . $LANGUAGES[$i][2];
                }
            }
        }
        return "";
    }

// Begin of modification Displaying Breadcrumbs in All Pages, by Masino Sinaga, May 25, 2012   
    // Get breadcrumb phrase
    function BreadcrumbPhrase($Id) {
        if (is_object($this->Phrases)) {
            return $this->GetNodeAtt($this->Phrases->selectSingleNode("//breadcrumb/phrase[@id='" . ($Id) . "']"), "value");
        } elseif (is_array($this->Phrases)) {
            return ConvertFromUtf8(@$this->Phrases['ew-language']['breadcrumb']['phrase'][($Id)]['attr']['value']);
        }
    }
    // Set breadcrumb phrase
    function setBreadcrumbPhrase($Id, $Value) {
        if (is_array($this->Phrases)) {
            $this->Phrases['ew-language']['breadcrumb']['phrase'][($Id)]['attr']['value'] = $Value;
        }
    }  
// End of modification Displaying Breadcrumbs in All Pages, by Masino Sinaga, May 25, 2012

    /**
     * Get phrase
     *
     * @param string $id Phrase ID
     * @param mixed $useText (true => text only, false => icon only, null => both)
     * @return string
     */
    public function phrase($id, $useText = false)
    {
        $className = ConvertFromUtf8(@$this->Phrases["ew-language"]["global"]["phrase"][strtolower($id)]["attr"]["class"]);
        if (isset($this->Phrases["ew-language"]["global"]["phrase"][strtolower($id)])) {
            $text = ConvertFromUtf8(@$this->Phrases["ew-language"]["global"]["phrase"][strtolower($id)]["attr"]["value"]);
        } else {
            $text = $id;
        }
        $res = $text;
        if ($useText !== true && $className != "") {
            if ($useText === null && $text !== "") { // Use both icon and text
                AppendClass($className, "me-2");
            }
            if (preg_match('/\bspinner\b/', $className)) { // Spinner
                $res = '<div class="' . $className . '" role="status"><span class="visually-hidden">' . $text . '</span></div>';
            } else { // Icon
                $res = '<i data-phrase="' . $id . '" class="' . $className . '"><span class="visually-hidden">' . $text . '</span></i>';
            }
            if ($useText === null && $text !== "") { // Use both icon and text
                $res .= $text;
            }
        }
        return $res;
    }

    // Set phrase
    public function setPhrase($id, $value, $client = false)
    {
        $this->setPhraseAttr($id, "value", $value);
        if ($client === true) {
            $this->setPhraseAttr($id, "client", true);
        }
    }

    // Get project phrase
    public function projectPhrase($id)
    {
        return ConvertFromUtf8(@$this->Phrases["ew-language"]["project"]["phrase"][strtolower($id)]["attr"]["value"]);
    }

    // Set project phrase
    public function setProjectPhrase($id, $value)
    {
        $this->Phrases["ew-language"]["project"]["phrase"][strtolower($id)]["attr"]["value"] = $value;
    }

    // Get menu phrase
    public function menuPhrase($menuId, $id)
    {
        return ConvertFromUtf8(@$this->Phrases["ew-language"]["project"]["menu"][$menuId]["phrase"][strtolower($id)]["attr"]["value"]);
    }

    // Set menu phrase
    public function setMenuPhrase($menuId, $id, $value)
    {
        $this->Phrases["ew-language"]["project"]["menu"][$menuId]["phrase"][strtolower($id)]["attr"]["value"] = $value;
    }

    // Get table phrase
    public function tablePhrase($tblVar, $id)
    {
        return ConvertFromUtf8(@$this->Phrases["ew-language"]["project"]["table"][strtolower($tblVar)]["phrase"][strtolower($id)]["attr"]["value"]);
    }

    // Set table phrase
    public function setTablePhrase($tblVar, $id, $value)
    {
        $this->Phrases["ew-language"]["project"]["table"][strtolower($tblVar)]["phrase"][strtolower($id)]["attr"]["value"] = $value;
    }

    // Get chart phrase
    public function chartPhrase($tblVar, $chtVar, $id)
    {
        return ConvertFromUtf8(@$this->Phrases["ew-language"]["project"]["table"][strtolower($tblVar)]["chart"][strtolower($chtVar)]["phrase"][strtolower($id)]["attr"]["value"]);
    }

    // Set chart phrase
    public function setChartPhrase($tblVar, $chtVar, $id, $value)
    {
        $this->Phrases["ew-language"]["project"]["table"][strtolower($tblVar)]["chart"][strtolower($chtVar)]["phrase"][strtolower($id)]["attr"]["value"] = $value;
    }

    // Get field phrase
    public function fieldPhrase($tblVar, $fldVar, $id)
    {
        return ConvertFromUtf8(@$this->Phrases["ew-language"]["project"]["table"][strtolower($tblVar)]["field"][strtolower($fldVar)]["phrase"][strtolower($id)]["attr"]["value"]);
    }

    // Set field phrase
    public function setFieldPhrase($tblVar, $fldVar, $id, $value)
    {
        $this->Phrases["ew-language"]["project"]["table"][strtolower($tblVar)]["field"][strtolower($fldVar)]["phrase"][strtolower($id)]["attr"]["value"] = $value;
    }

    // Get phrase attribute
    protected function phraseAttr($id, $name)
    {
        return ConvertFromUtf8(@$this->Phrases["ew-language"]["global"]["phrase"][strtolower($id)]["attr"][strtolower($name)]);
    }

    // Set phrase attribute
    protected function setPhraseAttr($id, $name, $value)
    {
        $this->Phrases["ew-language"]["global"]["phrase"][strtolower($id)]["attr"][strtolower($name)] = $value;
    }

    // Get phrase class
    public function phraseClass($id)
    {
        return $this->PhraseAttr($id, "class");
    }

    // Set phrase attribute
    public function setPhraseClass($id, $value)
    {
        $this->setPhraseAttr($id, "class", $value);
    }

    // Output XML as JSON
    public function xmlToJson($xpath)
    {
        $nodeList = $this->Phrases->selectNodes($xpath);
        $res = [];
        foreach ($nodeList as $node) {
            $id = $this->getNodeAtt($node, "id");
            $res[$id] = $this->phrase($id);
        }
        return JsonEncode($res);
    }

    // Output array as JSON
    public function arrayToJson($client)
    {
        $ar = @$this->Phrases["ew-language"]["global"]["phrase"];
        $res = [];
        if (is_array($ar)) {
            foreach ($ar as $id => $node) {
                $isClient = @$node["attr"]["client"] == '1';
                if (!$client || $isClient) {
                    $res[$id] = $this->phrase($id, true);
                }
            }
        }
        return JsonEncode($res);
    }

    // Output all phrases as JSON
    public function allToJson()
    {
        return "ew.language = new ew.Language(" . $this->arrayToJson(false) . ");";
    }

    // Output client phrases as JSON
    public function toJson()
    {
        return "ew.language = new ew.Language(" . $this->arrayToJson(true) . ");";
    }

    // Output languages as array
    // changed "private" to "public" by Masino Sinaga, September 11, 2020
    public function getLanguages() 
    {
        global $LANGUAGES, $CurrentLanguage;
        $ar = [];
        if (is_array($LANGUAGES)) {
            $cnt = count($LANGUAGES);
            if ($cnt > 1) {
                for ($i = 0; $i < $cnt; $i++) {
                    $langId = $LANGUAGES[$i][0];
                    $phrase = $this->phrase($langId);
                    if ($phrase == $langId && $LANGUAGES[$i][1]) {
                        $phrase = $LANGUAGES[$i][1];
                    }
					$desc = $LANGUAGES[$i][1]; // Modified by Masino Sinaga, January 31, 2019
					$ar[] = ["id" => $langId, "desc" => ConvertFromUtf8($desc), "selected" => $langId == $CurrentLanguage];
                    // $ar[] = ["id" => $langId, "desc" => $phrase, "selected" => $langId == $CurrentLanguage];
                }
            }
        }
        return $ar;
    }

    // Get template
    public function getTemplate()
    {
		global $basePath;
		$basePath = BasePath(true);
        if ($this->Template == "") {
            if (SameText($this->Type, "LI")) { // LI (one line desc and flag)
                return '{{for languages}}<li class="nav-item"><a class="nav-link{{if selected}} active{{/if}} " data-ew-action="language" data-language="{{:id}}"><img src="'.$basePath.'assets/media/flags/{{:id}}.svg" width="24px" height="17px" alt="" /> <span class="ew-language-option">{{:desc}}</span></a></li>{{/for}}';
            } elseif (SameText($this->Type, "DROPDOWN")) { // DROPDOWN (dropdown menu)        
				return '<li class="nav-item ew-language-option dropdown" style="cursor: pointer;"><a class="nav-link" data-bs-toggle="dropdown">{{for languages}}{{if selected}}<img src="'.$basePath.'assets/media/flags/{{:id}}.svg" width="24px" height="17px"  alt="" />{{/if}}{{/for}}</a><div class="dropdown-menu dropdown-menu-end p0">{{for languages}}<a class="dropdown-item nav-link{{if selected}} active{{/if}}" data-ew-action="language" data-language="{{:id}}"><img class="" src="'.$basePath.'assets/media/flags/{{:id}}.svg" width="24px" height="17px" alt="" />{{>desc}}</a>{{/for}}</div></li>';
            } elseif (SameText($this->Type, "SELECT")) { // COMBOBOX (select option)
				if (Language()->phrase("dir") != "rtl") {
					return '<div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround kt-hidden-desktop" style="position: absolute;will-change: transform;top: 0px;left: 0px;transform: translate3d(64px, 75px, 0px);right: auto;"><ul class="kt-nav kt-margin-t-10 kt-margin-b-10 user-profile">{{for languages}}<li class="kt-nav__item {{if selected}} kt-nav__item--active{{/if}}"><a href="javascript:void(0);" class="kt-nav__link" onclick="ew.setLanguage(this);" data-language="{{:id}}"><span class="kt-header__topbar-icon"><img class="" src="'.$basePath.'assets/media/flags/{{:id}}.svg" alt="" /></span>&nbsp;<span class="kt-nav__link-text"> {{:desc}}</span></a>{{/for}}</li></ul></div>
					<div class="ew-language-option kt-margin-t-20 kt-margin-b-10 user-profile kt-hidden-tablet-and-mobile" style="vertical-align:middle;"><select class="form-control" id="ew-language" name="ew-language" onchange="ew.setLanguage(this);" style="width:150px;">{{for languages}}<option value="{{:id}}"{{if selected}} selected{{/if}} data-image="'.$basePath.'plugins/language-selector-combobox/images/msdropdown/icons/blank.gif" data-imagecss="flag {{:id}}" data-title="{{:desc}}">&nbsp;{{:desc}}</option>{{/for}}</select></div>';
				} else {
					return '<div class="ew-language-option"><select class="form-control" id="ew-language" name="ew-language" onchange="ew.setLanguage(this);" style="width:150px;">{{for languages}}<option value="{{:id}}"{{if selected}} selected{{/if}} data-image="'.$basePath.'plugins/language-selector-combobox/images/msdropdown/icons/blank.gif" data-imagecss="flag {{:id}}" data-title="{{:desc}}">&nbsp;&nbsp;{{:desc}}</option>{{/for}}</select></div>';
				}
            } elseif (SameText($this->Type, "RADIO")) { // RADIO (radio button)
                return '<div class="ew-language-option"><div class="btn-group" data-bs-toggle="buttons">{{for languages}}<input type="radio" name="ew-language" id="ew-Language-{{:id}}" onclick="ew.setLanguage(this);" {{if selected}} checked{{/if}} value="{{:id}}"><span class="ew-tooltip" for="ew-language-{{:id}}" data-container="body" data-bs-placement="middle" title="{{>desc}}"> <img class="" src="'.$basePath.'assets/media/flags/{{:id}}.svg" width="24px" height="17px" alt="" /></span>{{/for}}</div></div>';
            }
        }
        return $this->Template;
    }

    // Language Load event
    public function languageLoad()
    {
        // Example:
        //$this->setPhrase("MyID", "MyValue"); // Refer to language file for the actual phrase id
        //$this->setPhraseClass("MyID", "fas fa-xxx ew-icon"); // Refer to https://fontawesome.com/icons?d=gallery&m=free [^] for icon name
    }
}
