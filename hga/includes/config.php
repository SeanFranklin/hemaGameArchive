<?php
/*******************************************************************************
	Configuration File

	Defines constants
	Includes function libraries
	Connects to database
	Establishes proper session values
	Runs the POST processing function

*******************************************************************************/

// Initialize Session //////////////////////////////////////////////////////////

	// ini_set('display_errors', 1);
	// ini_set('display_startup_errors', 1);
	// error_reporting(E_ALL);

	initializeSession();

// System Constants ////////////////////////////////////////////////////////////

	define("DEBUGGING", 0);
	date_default_timezone_set("UTC");

	define("DEPLOYMENT_UNKNOWN",0);
	define("DEPLOYMENT_PRODUCTION",1);
	define("DEPLOYMENT_TEST",2);
	define("DEPLOYMENT_LOCAL",3);

// Database Connection
	if(!defined('BASE_URL')){
		define('BASE_URL' , $_SERVER['DOCUMENT_ROOT'].'/hga/');
	}
	include(BASE_URL.'includes/database.php');

	if(!defined('DEPLOYMENT')){
		define('DEPLOYMENT' , DEPLOYMENT_UNKNOWN);
	}


// Program Related Constants

	// User Types
	define("NO_LOGIN",0);

	// Alert Codes
	define("SYSTEM",1);
	define("USER_ERROR",2);
	define("USER_ALERT",3);
	define("USER_WARNING",4);

	// mysqlQuery() function codes
	define("SEND",0);
	define("INDEX",1);
	define("RAW",2);
	define("NUM_ROWS",3);
	define("ASSOC",4);
	define("SINGLE",5);
	define("KEY",6);
	define("KEY_SINGLES",7);
	define("SINGLES",8);

	define("SQL_FALSE",0);
	define("SQL_TRUE",1);

	define("STATUS_UNKNOWN",0);
	define("STATUS_RUNNING",1);
	define("STATUS_SUCCESS",2);
	define("STATUS_FAIL",3);

// Database Indexes

	define("INFO_META_RULES",1);
	define("INFO_META_DESIGN",2);
	define("INFO_META_NOTES",3);
	define("INFO_META_MEDIA",4);

// Includes ////////////////////////////////////////////////////////////////////

require_once(BASE_URL.'includes/function_lib.php');

// Database Connection /////////////////////////////////////////////////////////

$conn = connectToDB();

// Set Session Values //////////////////////////////////////////////////////////

// Set the Permissions
	setPermissions();

// Process POST Data ///////////////////////////////////////////////////////////

	processUrlParams();
	processPostData();

// Define Constants Based on DB ////////////////////////////////////////////////

	$sql = "SELECT tagMetaID, tagColor
			FROM tagMeta";
	$colorList = mysqlQuery($sql, ASSOC);

	echo "<style>";
		foreach($colorList as $c){
			echo ".tag-color-{$c['tagMetaID']} { color: {$c['tagColor']} }";
		}

	echo "</style>";

// FUNCTIONS ///////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

/******************************************************************************/

function setPermissions(){
// Intialize the permissions constant with what the current user can and can't do.


	$permissionsList = ['ADD','EDIT','ADMIN'];

	foreach($permissionsList as $permisionType){
		$permissionsArray[$permisionType] = false;
	}

	if($_SESSION['userID'] != 0){

		$userInfo = getUserInfo($_SESSION['userID']);

		$permissionsArray['ADD'] = (bool)((int)$userInfo['CAN_ADD']);
		$permissionsArray['EDIT'] = (bool)((int)$userInfo['CAN_EDIT']);
		$permissionsArray['ADMIN'] = (bool)((int)$userInfo['CAN_ADMIN']);
	}

	define("ALLOW",$permissionsArray);

}

/******************************************************************************/

function initializeSession(){
// Starts the session and initializes any session variables
// that are not set to null values.

	session_start();

	if(isset($_SESSION['init']) == false){

		$_SESSION['gameID'] = 0;
		$_SESSION['userID'] = 0;

		$_SESSION['alertMessages']['systemErrors'] = [];
		$_SESSION['alertMessages']['userErrors'] = [];
		$_SESSION['alertMessages']['userWarnings'] = [];
		$_SESSION['alertMessages']['userAlerts'] = [];

		$_SESSION['init'] = TRUE;
	}

}

/******************************************************************************/


// END OF FILE /////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////


