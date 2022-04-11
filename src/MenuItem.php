<?php

namespace PHPMaker2022\civichub2;

/**
 * Menu item class
 */
class MenuItem
{
    public $Id = "";
    public $Name = "";
    public $Text = "";
    public $Url = "";
    public $ParentId = -1;
    public $SubMenu = null; // Data type = Menu
    public $Allowed = true;
    public $Target = "";
	public $OnClick = ""; // Modified by Masino Sinaga, October 7, 2021
	public $IsJsMenu = false; // Modified by Masino Sinaga, October 7, 2021
    public $IsHeader = false;
    public $IsCustomUrl = false;
    public $Href = ""; // Href attribute
    public $Active = false;
    public $Icon = "";
    public $Attrs; // HTML attributes
    public $Label = ""; // HTML (for vertical menu only)
    public $IsNavbarItem;
    public $IsSidebarItem;
    public $Level = 0;

    // Constructor
    public function __construct($id, $name, $text, $url, $parentId = -1, $allowed = true, $isHeader = false, $isCustomUrl = false, $icon = "", $label = "", $isNavbarItem = false, $isSidebarItem = false)
    {
        $this->Id = $id;
        $this->Name = $name;
        $this->Text = $text;
        $this->Url = $url;
        $this->ParentId = $parentId;
        $this->Allowed = $allowed;
        $this->IsHeader = $isHeader;
        $this->IsCustomUrl = $isCustomUrl;
        $this->Icon = $icon;
        $this->Label = $label;
        $this->IsNavbarItem = $isNavbarItem;
		$this->IsSidebarItem = $isSidebarItem;
        $this->IsJsMenu = false; // Modified by Masino Sinaga; by default always false, October 7, 2021
        $this->Attrs = new Attributes();
		// Begin of modification by Masino Sinaga, April 23, 2012, in order to support _blank target in URL if it contains the prefix http
		if (strpos($this->Url, "http://") !== false) {
		   $this->Target = "_blank";
		}
		// End of modification by Masino Sinaga, April 23, 2012, in order to support _blank target in URL if it contains the prefix http
		// Begin of modification by Masino Sinaga, June 3, 2014, in order to support onclick in URL if it contains the separator |||
		if (strpos($this->Url, "|||") !== false) {
		   list($this->Url, $this->OnClick) = explode("|||", $this->Url);
		   $this->IsJsMenu = true;
		}
		// End of modification by Masino Sinaga, June 3, 2014, in order to support onclick in URL if it contains the separator |||
    }

    // Set property case-insensitively (for backward compatibility) // PHP
    public function __set($name, $value)
    {
        $vars = get_class_vars(get_class($this));
        foreach ($vars as $key => $val) {
            if (SameText($name, $key)) {
                $this->$key = $value;
                break;
            }
        }
    }

    // Get property case-insensitively (for backward compatibility) // PHP
    public function __get($name)
    {
        $vars = get_class_vars(get_class($this));
        foreach ($vars as $key => $val) {
            if (SameText($name, $key)) {
                return $this->$key;
                break;
            }
        }
        return null;
    }

    // Add submenu item
    public function addItem($item)
    {
        if ($this->SubMenu === null) {
            $this->SubMenu = new Menu($this->Id);
        }
        $this->SubMenu->Level = $this->Level + 1;
        $this->SubMenu->addItem($item);
    }

    // Set attribute
    public function setAttribute($name, $value)
    {
        if (is_string($this->Attrs) && !preg_match('/\b' . preg_quote($name, '/') . '\s*=/', $this->Attrs)) { // Only set if attribute does not already exist
            $this->Attrs .= ' ' . $name . '="' . $value . '"';
        } elseif ($this->Attrs instanceof Attributes) {
            if (StartsText("on", $name)) { // Events
                $this->Attrs->append($name, $value, ";");
            } elseif (SameText("class", $name)) { // Class
                $this->Attrs->appendClass($value);
            } else {
                $this->Attrs->append($name, $value);
            }
        }
    }

    // Render
    public function render($deep = true)
    {
        $url = GetUrl($this->Url);
        if (IsMobile() && !$this->IsCustomUrl && $url != "#") {
            $url = str_replace("#", (ContainsString($url, "?") ? "&" : "?") . "hash=", $url);
        }
        if ($url == "") {
            $this->setAttribute("data-ew-action", "none");
        }
        $icon = trim($this->Icon);
        if ($icon) {
            $ar = explode(" ", $icon);
            foreach ($ar as $name) {
                if (
                    StartsString("fa-", $name) &&
                    !in_array("fa", $ar) &&
                    !in_array("fas", $ar) &&
                    !in_array("fab", $ar) &&
                    !in_array("far", $ar) &&
                    !in_array("fal", $ar)
                ) {
                    $ar[] = "fas";
                    break;
                }
            }
            $icon = implode(" ", $ar);
        }
        $hasItems = $deep && $this->SubMenu !== null;
        $isOpened = $hasItems && $this->SubMenu->isOpened();
        $class = "";
        if ($this->IsNavbarItem) {
            AppendClass($class, SameString($this->ParentId, "-1") || $this->IsSidebarItem ? "nav-link" : "dropdown-item");
            if ($this->Active) {
                AppendClass($class, "active");
            }
            if ($hasItems && !$this->IsSidebarItem) {
                AppendClass($class, "dropdown-toggle ew-dropdown");
            }
        } else {
            AppendClass($class, "nav-link");
            if ($this->Active || $isOpened) {
                AppendClass($class, "active");
            }
        }
        AppendClass($class, @$this->Attrs["class"]); // Move all user classes at end
        $this->Attrs["class"] = $class; // Save classes to Attrs
        $attrs = is_string($this->Attrs) ? $this->Attrs : $this->Attrs->toString();
        return [
            "id" => $this->Id,
            "name" => $this->Name,
            "text" => $this->Text,
            "parentId" => $this->ParentId,
            "level" => $this->Level,
            "href" => $url,
            "attrs" => $attrs,
            "target" => $this->Target,
            "isHeader" => $this->IsHeader,
            "active" => $this->Active,
            "icon" => $icon,
            "label" => $this->Label,
            "isNavbarItem" => $this->IsNavbarItem,
            "items" => $hasItems ? $this->SubMenu->render() : null,
            "open" => $isOpened
        ];
    }
}
