<?php
/*******************************************************************************
	AJAX Functions
	
	Database queries requested by Javascrip.
	Sorted into a giant select case based on the value passed through $_REQUEST['mode']
	
*******************************************************************************/

define('BASE_URL' , $_SERVER['DOCUMENT_ROOT'].'/hga/');
include_once(BASE_URL.'includes/config.php');

// SWITCH CASE /////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

switch ($_REQUEST['mode']){
	
/******************************************************************************/

case 'xxxx': {
/*
	$systemRosterID = (int)$_REQUEST['systemRosterID'];

	$sql = "SELECT systemRosterID, firstName, lastName, schoolID, HemaRatingsID
			FROM systemRoster
			WHERE systemRosterID = {$systemRosterID}";

	$res = mysqlQuery($sql, SINGLE);
	echo json_encode($res);*/
} break;


/******************************************************************************/
}


// END OF FILE /////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
