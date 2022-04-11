<?php

namespace PHPMaker2022\civichub2;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;
use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;
if (@$_COOKIE['aside_toggle_state'] == 'collapsed') {
	Config("BODY_CLASS", Config("BODY_CLASS") . " sidebar-collapse");
} elseif (@$_COOKIE['aside_toggle_state'] == 'closed') {
	Config("BODY_CLASS", Config("BODY_CLASS") . " sidebar-closed");
} elseif (@$_COOKIE['aside_toggle_state'] == 'expanded') {
	Config("BODY_CLASS", Config("BODY_CLASS") . " sidebar-open");
}

function AutoVersion($url){
	$dirfile = realpath($url);
	$ver = filemtime($dirfile);
	$file_ext = ".".substr(strtolower(strrchr($url, ".")), 1);
	$file_ext = $file_ext;
	$result = str_replace($file_ext, $file_ext."?v=".$ver, $url);
	echo $result;
}

function getCurrentPageTitle($pt) {
    global $CurrentPageTitle, $Language;
	$CurrentPageTitle = "";
	if (empty($CurrentPageTitle)) {
		if ( @CurrentPage()->PageID != "custom") {
			if (@CurrentPage()->TableName == trim(@CurrentPage()->TableName) && strpos(@CurrentPage()->TableName, ' ') !== false) {
				$CurrentPageTitle = ($Language->TablePhrase(str_replace(' ', '', @CurrentPage()->TableName), "TblCaption") != "") ? $Language->TablePhrase(str_replace(' ', '', @CurrentPage()->TableName), "TblCaption") : str_replace(' ', '', @CurrentPage()->TableName);
			} else {
				$CurrentPageTitle = ($Language->TablePhrase(@CurrentPage()->TableName, "TblCaption") != "") ? $Language->TablePhrase(@CurrentPage()->TableName, "TblCaption") : ucwords(@CurrentPage()->TableName);
			}
		} elseif ( @CurrentPage()->PageID == "custom") { // support for Custom Files
			$CurrentPageTitle = $Language->TablePhrase(str_replace(".php", "", @CurrentPage()->TableName), "TblCaption"); // Modified by Masino Sinaga, August 31, 2021
		}			
		$CurrentPageTitle = str_replace("_list", "", $CurrentPageTitle);
		$CurrentPageTitle = str_replace("_php", "", $CurrentPageTitle);
		$CurrentPageTitle = str_replace("_htm", "", $CurrentPageTitle);
		$CurrentPageTitle = str_replace("_html", "", $CurrentPageTitle);
		$CurrentPageTitle = str_replace("_", " ", $CurrentPageTitle);
		$CurrentPageTitle = ucwords($CurrentPageTitle);
	}
	if ($CurrentPageTitle == "") {
		$Language->ProjectPhrase("BodyTitle");
	}
	return $CurrentPageTitle;
}

/**
 * Application Root URL
 *
 * @return the url of application root
 */
function AppRootURL() {
	return str_replace(substr(strrchr(CurrentUrl(), "/"), 1), "", DomainUrl().CurrentUrl());
}

// Begin of modification LoadApplicationSettings, by Masino Sinaga, September 22, 2014
function LoadApplicationSettings() {
	$conn = Conn();
	$_SESSION["civichub2_views"] = 1; // reset the global counter
	// Parent array of all items, initialized if not already...
	if (!isset($_SESSION["civichub2_appset"])) {
		$_SESSION["civichub2_appset"] = array();
	}
	$sSql = "SELECT * FROM ".Config("MS_SETTINGS_TABLE")." WHERE Option_Default = 'Y'";
	$stmt = $conn->executeQuery($sSql);
	if ($stmt->rowCount() > 0) {
		while ($row = $stmt->fetch()) {
			$x = array_keys($row);
			for ($i=0; $i<count($x); $i++) {
				if (is_string($x[$i])) {
					$sfieldname = $x[$i];
					$_SESSION["civichub2_appset"][0][$sfieldname] = $row[$x[$i]];
				}
			}
		}
		if (!isset($_SESSION["civichub2_errordb"]))
			$_SESSION["civichub2_errordb"] = "";
	} else {
		if (!isset($_SESSION["civichub2_errordb"]))
			$_SESSION["civichub2_errordb"] = Config("MS_SETTINGS_TABLE");
	}
}
// End of modification LoadApplicationSettings, by Masino Sinaga, September 22, 2014

// Begin of modification My_Global_Check, by Masino Sinaga, July 3, 2013
function My_Global_Check() {
	global $Language, $Security, $page_type, $conn;
    $page_type = "TABLE"; 
	$dbid = 0;	
	if (!isset($_SESSION["civichub2_Root_URL"])) { 
		$_SESSION["civichub2_Root_URL"] = AppRootURL();
	}
	if (IsLoggedIn()) {
        if (!IsAdmin()) {
            Config("MS_USER_CARD_USER_NAME", CurrentUserName());
            Config("MS_USER_CARD_COMPLETE_NAME", CurrentUserInfo("First_Name") . " " .  CurrentUserInfo("Last_Name"));
		    Config("MS_USER_CARD_POSITION", Security()->currentUserLevelName());
        } else {
            Config("MS_USER_CARD_USER_NAME", CurrentUserName());
		    Config("MS_USER_CARD_COMPLETE_NAME", "Administrator");
		    Config("MS_USER_CARD_POSITION", Security()->currentUserLevelName());
        }
	}
	if (!isset($_SESSION["civichub2_views"])) { 
		$_SESSION["civichub2_views"] = 0;
	}
	$_SESSION["civichub2_views"] = $_SESSION["civichub2_views"]+1;
}

// Begin of modification How Long User Should be Allowed Login in the Messages When Failed Login Exceeds the Maximum Limit, by Masino Sinaga, May 12, 2012
function CurrentDateTime_Add_Minutes($currentdate, $minute) {
  $timestamp = strtotime("$currentdate");
  $addtime = strtotime("+$minute minutes", $timestamp);
  $next_time = date('Y-m-d H:i:s', $addtime);
  return $next_time;
}

function DurationFromSeconds($iSeconds) {
	/**
	* Convert number of seconds into years, days, hours, minutes and seconds
	* and return an string containing those values
	*
	* @param integer $seconds Number of seconds to parse
	* @return string
	*/
	global $Language;
	$y = floor($iSeconds / (86400*365.25));
	$d = floor(($iSeconds - ($y*(86400*365.25))) / 86400);
	$h = gmdate('H', $iSeconds);
	$m = gmdate('i', $iSeconds);
	$s = gmdate('s', $iSeconds);
	$string = '';
	if($y > 0)
		$string .= intval($y) . " " . $Language->phrase("years")." ";
	if($d > 0) 
		$string .= intval($d) . " " . $Language->phrase("days")." ";
	if($h > 0) 
		$string .= intval($h) . " " . $Language->phrase("hours")." ";
	if($m > 0) 
		$string .= intval($m) . " " . $Language->phrase("minutes")." ";
	if($s > 0) 
		$string .= intval($s) . " " . $Language->phrase("seconds")." ";
	return preg_replace('/\s+/',' ',$string);
}

function Duration($parambegindate, $paramenddate) {
  global $Language;
  $begindate = strtotime($parambegindate);  
  $enddate = strtotime($paramenddate);
  $diff = intval($enddate) - intval($begindate);
  $diffday = intval(floor($diff/86400));                                      
  $modday = ($diff%86400);  
  $diffhour = intval(floor($modday/3600));  
  $diffminute = intval(floor(($modday%3600)/60));  
  $diffsecond = ($modday%60);  
  if ($diffday!=0 && $diffhour!=0 && $diffminute!=0 && $diffsecond==0) {
    return round($diffday)." ".$Language->phrase('days').        
    ", ".round($diffhour)." ".$Language->phrase('hours').        
    ", ".round($diffminute,0)." ".$Language->phrase('minutes');
  } elseif ($diffday!=0 && $diffhour==0 && $diffminute!=0 && $diffsecond!=0) {
    return round($diffday)." ".$Language->phrase('days').        
    ", ".round($diffminute)." ".$Language->phrase('minutes').        
    ", ".round($diffsecond,0)." ".$Language->phrase('seconds');
  } elseif ($diffday!=0 && $diffhour!=0 && $diffminute==0 && $diffsecond==0) {
    return round($diffday)." ".$Language->phrase('days').        
    ", ".round($diffhour)." ".$Language->phrase('hours');
  } elseif ($diffday!=0 && $diffhour==0 && $diffminute!=0 && $diffsecond==0) {
    return round($diffday)." ".$Language->phrase('days').        
    ", ".round($diffminute,0)." ".$Language->phrase('minutes');
  } elseif ($diffday!=0 && $diffhour==0 && $diffminute==0 && $diffsecond!=0) {
    return round($diffday)." ".$Language->phrase('days').        
    ", ".round($diffsecond,0)." ".$Language->phrase('seconds');	
  } elseif ($diffday!=0 && $diffhour==0 && $diffminute==0 && $diffsecond==0) {
    return round($diffday)." ".$Language->phrase('days');
  }	elseif ($diffday==0 && $diffhour!=0 && $diffminute!=0 && $diffsecond!=0) {
    return round($diffhour)." ".$Language->phrase('hours').
    ", ".round($diffminute,0)." ".$Language->phrase('minutes').
    ", ".round($diffsecond,0)." ".$Language->phrase('seconds')."";
  } elseif ($diffday==0 && $diffhour!=0 && $diffminute==0 && $diffsecond==0) {
    return round($diffhour)." ".$Language->phrase('hours');
  } elseif ($diffday==0 && $diffhour!=0 && $diffminute!=0 && $diffsecond==0) {
    return round($diffhour)." ".$Language->phrase('hours').
    ", ".round($diffminute,0)." ".$Language->phrase('minutes');
  } elseif ($diffday==0 && $diffhour==0 && $diffminute!=0 && $diffsecond==0) {   
    return round($diffminute,0)." ".$Language->phrase('minutes');	
  } elseif ($diffday==0 && $diffhour==0 && $diffminute!=0 && $diffsecond!=0) {   
    return round($diffminute,0)." ".$Language->phrase('minutes').
    ", ".round($diffsecond,0)." ".$Language->phrase('seconds')."";
  } elseif ($diffday==0 && $diffhour==0 && $diffminute==0 && $diffsecond!=0) {   
    return round($diffsecond,0)." ".$Language->phrase('seconds')."";   
  } else {
    return round($diffday)." ".$Language->phrase('days').        
    ", ".round($diffhour)." ".$Language->phrase('hours').        
    ", ".round($diffminute,0)." ".$Language->phrase('minutes').        
    ", ".round($diffsecond,0)." ".$Language->phrase('seconds')."";
  }
}

// End of modification How Long User Should be Allowed Login in the Messages When Failed Login Exceeds the Maximum Limit, by Masino Sinaga, May 12, 2012
function GetIntersectTwoDatesEditMode($iID, $sDateCheckBegin, $sDateCheckEnd, $sLang) {
	$sResult = "";
	$sSql = "SELECT Announcement_ID, Date_Start, Date_End
			FROM " . Config("MS_ANNOUNCEMENT_TABLE") . " 
			WHERE Date_Start <> '' 
			AND Date_End <> '' 
			AND Announcement_ID <> ".$iID." 
			AND Language = '".$sLang."'";
	$rs = ExecuteQuery($sSql, "DB");
	if ($rs->rowCount() > 0) {
		while ($row = $rs->fetch()) {
			$sDateCheckBegin = substr($sDateCheckBegin, 0, 10);
			$sDateCheckEnd = substr($sDateCheckEnd, 0, 10);
			$arrDates1 = GetAllDatesFromTwoDates($sDateCheckBegin, $sDateCheckEnd); 
			$sDateBegin = substr($row["Date_Start"], 0, 10);
			$sDateEnd = substr($row["Date_End"], 0, 10);
			$arrDates2 = GetAllDatesFromTwoDates($sDateBegin, $sDateEnd);
			$result = array_intersect($arrDates1, $arrDates2);
			if ( (count($result)>0) && ($row["Announcement_ID"] != $iID) ) {
				$sResult .= $row["Announcement_ID"]."#";
				foreach($result as $key => $value){ 
					$sResult .= $value.", ";
				} 
				unset($value);
				$sResult = trim($sResult, ", ");
				return $sResult;
			}
		}
	}
    return $sResult;
}

function UpdateDatesInOtherLanguage($sDateBegin, $sDateEnd, $iID) {
	$sResult = "";
	$sSql = "UPDATE " . Config("MS_ANNOUNCEMENT_TABLE") . " 
			SET Date_Start = '".$sDateBegin."',
			Date_End = '".$sDateEnd."' 
			WHERE Translated_ID = ".$iID;
	ExecuteUpdate($sSql, "DB");
}

function GetAllDatesFromTwoDates($fromDate, $toDate)
{
    if(!$fromDate || !$toDate ) {return false;}
    $dateMonthYearArr = array();
    $fromDateTS = strtotime($fromDate);
    $toDateTS = strtotime($toDate);
    for ($currentDateTS = $fromDateTS; $currentDateTS <= $toDateTS; $currentDateTS += (60 * 60 * 24))
    {
        $currentDateStr = date("Y-m-d",$currentDateTS);
        $dateMonthYearArr[] = $currentDateStr;
    }
    return $dateMonthYearArr;
}

function IsDateBetweenTwoDates($sDateCheckBegin, $sDateCheckEnd, $sDateBegin, $sDateEnd) {
    $dDate1 = strtotime($sDateCheckBegin);
    $dDate2 = strtotime($sDateCheckEnd);
    if ( ($dDate1 >= strtotime($sDateBegin)) && ($dDate2 <= strtotime($sDateEnd)) ) {
        return TRUE;    
    } else {
        return FALSE;    
    }  
} 

// Filter for 'Last Month' (example)
function GetLastMonthFilter($FldExpression, $dbid = 0)
{
    $today = getdate();
    $lastmonth = mktime(0, 0, 0, $today['mon'] - 1, 1, $today['year']);
    $val = date("Y|m", $lastmonth);
    $wrk = $FldExpression . " BETWEEN " .
        QuotedValue(DateValue("month", $val, 1, $dbid), DATATYPE_DATE, $dbid) .
        " AND " .
        QuotedValue(DateValue("month", $val, 2, $dbid), DATATYPE_DATE, $dbid);
    return $wrk;
}

// Filter for 'Starts With A' (example)
function GetStartsWithAFilter($FldExpression, $dbid = 0)
{
    return $FldExpression . Like("'A%'", $dbid);
}

// Global user functions

// Database Connecting event
function Database_Connecting(&$info)
{
    // Example:
    //var_dump($info);
    //if ($info["id"] == "DB" && IsLocal()) { // Testing on local PC
    //    $info["host"] = "locahost";
    //    $info["user"] = "root";
    //    $info["pass"] = "";
    //}
}

// Database Connected event
function Database_Connected(&$conn)
{
    // Example:
    //if ($conn->info["id"] == "DB") {
    //    $conn->executeQuery("Your SQL");
    //}
}

function MenuItem_Adding($item)
{
    //var_dump($item);
    // Return false if menu item not allowed
    return true;
}

function Menu_Rendering($menu)
{
    // Change menu items here
}

function Menu_Rendered($menu)
{
    // Clean up here
}

// Page Loading event
function Page_Loading()
{
    //Log("Page Loading");
}

// Page Rendering event
function Page_Rendering()
{
    //Log("Page Rendering");
}

// Page Unloaded event
function Page_Unloaded()
{
    //Log("Page Unloaded");
}

// AuditTrail Inserting event
function AuditTrail_Inserting(&$rsnew)
{
    //var_dump($rsnew);
    return true;
}

// Personal Data Downloading event
function PersonalData_Downloading(&$row)
{
    //Log("PersonalData Downloading");
}

// Personal Data Deleted event
function PersonalData_Deleted($row)
{
    //Log("PersonalData Deleted");
}

// Route Action event
function Route_Action($app)
{
    // Example:
    // $app->get('/myaction', function ($request, $response, $args) {
    //    return $response->withJson(["name" => "myaction"]); // Note: Always return Psr\Http\Message\ResponseInterface object
    // });
    // $app->get('/myaction2', function ($request, $response, $args) {
    //    return $response->withJson(["name" => "myaction2"]); // Note: Always return Psr\Http\Message\ResponseInterface object
    // });
}

// API Action event
function Api_Action($app)
{
    // Example:
    // $app->get('/myaction', function ($request, $response, $args) {
    //    return $response->withJson(["name" => "myaction"]); // Note: Always return Psr\Http\Message\ResponseInterface object
    // });
    // $app->get('/myaction2', function ($request, $response, $args) {
    //    return $response->withJson(["name" => "myaction2"]); // Note: Always return Psr\Http\Message\ResponseInterface object
    // });
}

// Container Build event
function Container_Build($builder)
{
    // Example:
    // $builder->addDefinitions([
    //    "myservice" => function (ContainerInterface $c) {
    //        // your code to provide the service, e.g.
    //        return new MyService();
    //    },
    //    "myservice2" => function (ContainerInterface $c) {
    //        // your code to provide the service, e.g.
    //        return new MyService2();
    //    }
    // ]);
}
