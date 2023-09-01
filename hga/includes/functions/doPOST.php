<?php
/*******************************************************************************
	doPOST
	
	Landing platform for POST form submissions.
	Every form submision contains a 'formName' which directs the 
	appropriate action to handle POST data.
	
*******************************************************************************/

/******************************************************************************/

function processPostData(){

	///////////////////////////////////////////////////////////////////////
	/* For debugging, commented out for regular use ///
	if(ALLOW['ADMIN'] == true){
		$refreshPage = false;
		define('SHOW_POST', true);
		define('SHOW_URL_NAV', false);
		$_SESSION['post'] = $_POST;
	}
	//define('SHOW_SESSION', true);
	//////////////////////////////////////////////////////////////////////*/

// Evaluate POST form submitted
	if(isset($_POST['formName']) != false){

		// Refresh page after POST processing complete to prevent resubmits
		if(!isset($refreshPage)){
			$refreshPage = true;
		}
	
		$formName = $_POST['formName'];

		switch($formName){

		// Game Management
			case 'addNewGame':
				addNewGame($_POST['addNewGame']);
				break;
			case 'deleteGame':
				deleteGame($_POST['gameID']);
				break;
			case 'editGame':
				editGame($_POST['editGame']);
				break;
			case 'newGameInfo':
				$i = $_POST['newGameInfo'];
				addGameInfo($i['gameID'], $i['infoText'], $i['infoMetaID']);
				break;
			case 'editGameInfo':
				$i = $_POST['editGameInfo'];
				editGameInfo($i['infoID'], $i['infoText'], $i['infoMetaID']);
				break;
			case 'deleteGameInfo':
				deleteGameInfo($_POST['deleteGameInfo']);
				break;
			case 'addTags':
				addTagsToGame($_POST['addTags']);
				break;
			case 'removeGameTags':
				removeGameTags($_POST['removeGameTags']);
				break;
			case 'updateTagInfo':
				updateTagInfo($_POST['updateTagInfo']);
				break;
			case 'deleteTag':
				deleteTag($_POST['deleteTag']);
				break;
			case 'clearNewTagsWithNoMeta':
				unset($_SESSION['newTagsWithNoMeta']);
				setAlert(USER_ALERT, "Boooooooo");
				break;
			case 'updateTagMetas':
				updateTagMetas($_POST['updateTagMetas']);
				break;

		// Users
			case 'logIn':
				logUserIn($_POST['logIn']);
				break;
			case 'logOut':
				logUserOut();
				break;
			case 'changePassword':
				changeUserPassword($_POST['changePassword']);
				break;
			case 'addNewUser':
				addNewUser($_POST['addNewUser']);
				break;
			case null:
				break;
			default:
				break;
		}

	}

	unset($_POST);

	if(empty($refreshPage) == false || isset($_SESSION['refreshPage']) == true){
		refreshPage();
	}
	
}

// FUNCTIONS ///////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

/******************************************************************************/

function processUrlParams(){

	$urlComponents = $_SERVER['QUERY_STRING'];
	parse_str($urlComponents, $urlParams);

	$gameID = (int)$_SESSION['gameID'];

// Process gameID param
	if(isset($urlParams['g']) == true){
		$gameID = (int)$urlParams['g'];
	}

// Check that the gameID from the URL is a valid game ID
	$sql = "SELECT gameID
			FROM gameList
			WHERE gameID = {$gameID}";
	$gameID = (int)mysqlQuery($sql, SINGLE, 'gameID');

	if($gameID == 0 && $_SESSION['gameID'] != 0){
		$_SESSION['refreshPage'] = true;
	}

	(int)$_SESSION['gameID'] = $gameID;

}

/******************************************************************************/

function logUserIn($input){

	if($_SESSION['userID'] != 0){
		$name = getUserName($_SESSION['userID']);
		setAlert(USER_WARNING,"Already logged in as <b>{$name}</b>. Please log out before logging in again. <BR><i>(Why do you have two accounts?)</i>");
		return;
	}

	$userID = getUserIdFromAccount($input['userAccount']);

	if($userID == 0){

		setAlert(USER_ERROR,"Invalid Account Name & Password combination.");

	} elseif(isPasswordCorrect($userID, $input['password']) == true){

		$_SESSION['userID'] = $userID;
		$name = getUserName($_SESSION['userID']);

		//setAlert(USER_ALERT,"Logged in as <b>{$name}</b>");
		// Alert isn't needed because log-in page will show them immediately the change.

	} else {

		setAlert(USER_ERROR,"Invalid Account Name & Password combination.");

	}

}

/******************************************************************************/

function logUserOut(){
	
	$_SESSION['userID'] = 0;
}

/******************************************************************************/

// END OF DOCUMENT /////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

