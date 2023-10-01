<?php
/*******************************************************************************
	Database Write Functions
	
	Functions for writing to the HEMA Scorecard database
	
*******************************************************************************/


/******************************************************************************/

function addNewGame($gameData){

	if(ALLOW['ADD'] == false){
		return;
	}

	$userID = (int)$_SESSION['userID']; ////TODO: check if user is valid
	$gameName = $gameData['gameName']; ////TODO: check if name is valid (not too short)
	$gameRules = $gameData['gameRules']; ////TODO: check if rules are valid (not too short)
	$date = date('Y-m-d');

	////TODO: Query name to make sure they are all unique

	$sql = "INSERT INTO gameList
			(gameName, userID, gameRules, gameDatestamp)
			VALUES
			(?,$userID,?,'{$date}')";

	$stmt = mysqli_prepare($GLOBALS["___mysqli_ston"], $sql);
	// "s" means the database expects a string
	$bind = mysqli_stmt_bind_param($stmt, "ss", $gameName, $gameRules);
	$exec = mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
	$gameID = (int)mysqli_insert_id($GLOBALS["___mysqli_ston"]);

	$tagInput['gameID'] = $gameID;
	$tagInput['tagString'] = $gameData['tagString'];

	if($gameID == 0){
		setAlert(SYSTEM, "Error inserting new game");
		return;
	} else {
		$insertName = getGameName($gameID);
		setAlert(USER_ALERT,"New game <b>{$insertName}</b> created.");
	}

	addTagsToGame($tagInput);

	if(strlen($gameData['gameDesign']) != 0){
		addGameInfo($gameID, $gameData['gameDesign'], INFO_META_DESIGN);
	}

	if(strlen($gameData['gameNote']) != 0){
		addGameInfo($gameID, $gameData['gameNote'], INFO_META_NOTES);
	}

	header("Location: gameInfo.php?g={$gameID}"); ////TODO: I think jumpTo is a better implementation
	exit;
}

/******************************************************************************/

function editGame($gameData){

	if(ALLOW['EDIT'] == false){
		return;
	}

	$gameID = (int)$gameData['gameID']; 
	$gameName = $gameData['gameName']; ////TODO: check if name is valid (not too short)
	$gameRules = $gameData['gameRules']; ////TODO: check if rules are valid (not too short)

	$sql = "UPDATE gameList
			SET gameName = ?, gameRules = ?
			WHERE gameID = {$gameID}";

	$stmt = mysqli_prepare($GLOBALS["___mysqli_ston"], $sql);
	// "s" means the database expects a string
	$bind = mysqli_stmt_bind_param($stmt, "ss", $gameName, $gameRules);
	$exec = mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);

}

/******************************************************************************/

function addGameInfo($gameID, $infoText, $infoMetaID){

	if(ALLOW['ADD'] == false){
		return;
	}


	$gameID = (int)$gameID;
	$userID = (int)$_SESSION['userID'];
	$infoMetaID = (int)$infoMetaID;
	$date = date('Y-m-d');

	$sql = "INSERT INTO infoList
			(gameID, infoMetaID, infoText, userID, infoDate)
			VALUES
			({$gameID},{$infoMetaID},?,{$userID},'{$date}')";

	$stmt = mysqli_prepare($GLOBALS["___mysqli_ston"], $sql);
	// "s" means the database expects a string
	$bind = mysqli_stmt_bind_param($stmt, "s", $infoText);
	$exec = mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);


}

/******************************************************************************/

function editGameInfo($infoID, $infoText, $infoMetaID){

	if(ALLOW['EDIT'] == false){
		return;
	}

	$infoID = (int)$infoID;
	$infoMetaID = (int)$infoMetaID;

	$sql = "UPDATE infoList
			SET infoText = ?, infoMetaID = {$infoMetaID}
			WHERE infoID = {$infoID}";

	$stmt = mysqli_prepare($GLOBALS["___mysqli_ston"], $sql);
	// "s" means the database expects a string
	$bind = mysqli_stmt_bind_param($stmt, "s", $infoText);
	$exec = mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);

}

/******************************************************************************/

function deleteGameInfo($deleteGameInfo){

	if(ALLOW['ADMIN'] == false){
		return;
	}

	$infoID = $deleteGameInfo['infoID'];
	$sql = "DELETE FROM infoList
			WHERE infoID = {$infoID}";
	mysqlQuery($sql, SEND);

}

/******************************************************************************/


function deleteGame($gameID){

	$gameID = (int)$gameID;

	if(ALLOW['ADMIN'] == false || $gameID == 0){
		return;
	}

	$gameName = getGameName($gameID);

	$sql = "DELETE FROM gameList
			WHERE gameID = {$gameID}";
	mysqlQuery($sql, SEND);

	setAlert(USER_ALERT,"Game <b>{$gameName}</b> has been deleted.");

	header("Location: gameList.php"); ////TODO: I think jumpTo is a better implementation
	exit;

}

/******************************************************************************/


function addTagsToGame($input){

	if(ALLOW['ADD'] == false){
		return;
	}

	$gameID = (int)$input['gameID'];
	$userID = (int)$_SESSION['userID'];

	$tags = array_filter((array)explode(" ",$input['tagString']));
	$tagAttached = false;

	foreach($tags as $tagName){

		$tagID = (int)getTagID($tagName);
		$alreadyAttached = false;

		if($tagID == 0){
			$tagID = (int)createNewTag($tagName);
		} else {
			$sql = "SELECT tagID
					FROM gameTag
					WHERE tagID = {$tagID}
					AND gameID = {$gameID}";
			$alreadyAttached = (bool)((int)mysqlQuery($sql, SINGLE, 'tagID'));
		}

		if($alreadyAttached == false){
			$sql = "INSERT INTO gameTag
					(gameID, tagID, userID)
					VALUES
					({$gameID}, {$tagID}, {$userID})";
			mysqlQuery($sql, SEND);

			$tagAttached = true;
		}
	
	}

	if($tagAttached == true){
		setAlert(USER_ALERT,"New tags added");
	} else {
		setAlert(USER_ALERT,"Invalid input, no tags added");
	}

}

/******************************************************************************/

function createNewTag($tagName){

	if(ALLOW['ADD'] == false){
		return;
	}

	$userID = (int)$_SESSION['userID'];

	$sql = "INSERT INTO tagList
			(tagMetaID, tagName, userID)
			VALUES
			(1, ?, {$userID})";

	$stmt = mysqli_prepare($GLOBALS["___mysqli_ston"], $sql);
	// "s" means the database expects a string
	$bind = mysqli_stmt_bind_param($stmt, "s", $tagName);
	$exec = mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);

	$tagID = (int)mysqli_insert_id($GLOBALS["___mysqli_ston"]);

	$_SESSION['newTagsWithNoMeta'][$_SESSION['userID']][] = $tagID;

	return($tagID);

}

/******************************************************************************/

function removeGameTags($input){

	$tagsToRemove = [];

	foreach($input as $gameTagID){
		if((int)$gameTagID != 0){
			$tagsToRemove[] = $gameTagID;
		}
	}

	if($tagsToRemove == []){
		return;
	}

	$tagsToRemove = implode2int($tagsToRemove);

	$sql = "DELETE FROM gameTag
			WHERE gameTagID IN ($tagsToRemove)";
	mysqlQuery($sql, SEND);

	setAlert(USER_ALERT, "Tags removed.");

}

/******************************************************************************/

function updateTagInfo($tagInfo){

	if(ALLOW['EDIT'] == false){
		return;
	}

	$tagID = (int)$tagInfo['tagID'];
	$tagMetaID = (int)$tagInfo['tagMetaID'];
	$tagName = $tagInfo['tagName'];
	$oldInfo = getTagInfo($tagID);
	$newTagMetaName = getTagMetaName($tagMetaID);

	$sql = "SELECT tagMetaID
			FROM tagMeta
			WHERE tagMetaID = {$tagMetaID}";
	$metaIDexists = (bool)((int)mysqlQuery($sql,SINGLE,'tagMetaID'));

	if($metaIDexists == false){
		setAlert(SYSTEM,'Invalied tagMetaID in updateTagInfo().');
		return;
	}

	$sql = "UPDATE tagList
			SET tagMetaID = {$tagMetaID}, tagName = ?
			WHERE tagID = {$tagID}"; show($tagName);

	$stmt = mysqli_prepare($GLOBALS["___mysqli_ston"], $sql);
	// "s" means the database expects a string
	$bind = mysqli_stmt_bind_param($stmt, "s", $tagName);
	$exec = mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);

	setAlert(USER_ALERT,"Tag <b>{$oldInfo['tagName']}</b>(<i>{$oldInfo['tagMetaName']}</i>) changed to <b>{$tagName}</b>(<i>{$newTagMetaName}</i>)");

}

/******************************************************************************/

function updateTagMetas($tagsToUpdate){

	if(ALLOW['ADD'] == false || $tagsToUpdate == []){
		return;
	}

	foreach($tagsToUpdate as $t){

		$tagID = (int)$t['tagID'];
		$tagMetaID = (int)$t['tagMetaID'];

		$sql = "UPDATE tagList
				SET tagMetaID = {$tagMetaID}
				WHERE tagID = {$tagID}";
		mysqlQuery($sql, SEND);
	}

	setAlert(USER_ALERT, "Tags types updated. <BR>Thank you. :) ");
	unset($_SESSION['newTagsWithNoMeta']);

}

/******************************************************************************/

function deleteTag($tagInfo){

	if(ALLOW['EDIT'] == false){
		return;
	}

	$tagID = (int)$tagInfo['tagID'];
	$tagName = getTagName($tagID);

	if($tagName == ''){
		setAlert(SYSTEM,'Invalied tagID in deleteTag().');
		return;
	}

	$sql = "DELETE FROM gameTag
			WHERE tagID = {$tagID}";
	mysqlQuery($sql, SEND);

	$sql = "DELETE FROM tagList
			WHERE tagID = {$tagID}";
	mysqlQuery($sql, SEND);

	setAlert(USER_ALERT,"Tag '<b>{$tagName}</b>' deleted.");
}

/******************************************************************************/

function changeUserPassword($input){

	$userID = (int)$input['userID'];

	if(isPasswordCorrect($userID, $input['passwordOld']) == false){
		setAlert(USER_ERROR,"Incorrect password for current account.<BR><b>Password not updated</b>");
		return;
	}

	if(strcmp($input['password'],$input['password2']) != 0){
		setAlert(USER_ERROR,"Passwords do not match.<BR><b>Password not updated</b>");
		return;
	}

	$hashedPassword = password_hash($input['password'], PASSWORD_DEFAULT);

	$sql = "UPDATE userList
			SET password = ?
			WHERE userID = {$userID}";

	$stmt = mysqli_prepare($GLOBALS["___mysqli_ston"], $sql);
	// "s" means the database expects a string
	$bind = mysqli_stmt_bind_param($stmt, "s", $hashedPassword);
	$exec = mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);

	setAlert(USER_ALERT,"<b>Password updated</b>");

}

/******************************************************************************/

function addNewUser($userInfo){

	if(ALLOW['ADMIN'] == false){
		return;
	}

	$userAccount = $userInfo['userAccount'];
	$userName = $userInfo['userName'];
	$add = (int)$userInfo['CAN_ADD'];
	$edit = (int)$userInfo['CAN_EDIT'];
	$admin = (int)$userInfo['CAN_ADMIN'];

	if($admin == 1){
		$edit = 1;
		$add = 1;
	}

	if($edit == 1){
		$add = 1;
	}
	
	$password = bin2hex(random_bytes(4));
	$passwordHash = password_hash($password, PASSWORD_DEFAULT);

	$sql = "INSERT INTO userList
			(userAccount, userName, CAN_ADD, CAN_EDIT, CAN_ADMIN, password)
			VALUES
			(?,?,{$add},{$edit},{$admin},?)";

	$stmt = mysqli_prepare($GLOBALS["___mysqli_ston"], $sql);
	// "s" means the database expects a string
	$bind = mysqli_stmt_bind_param($stmt, "sss", $userAccount, $userName, $passwordHash);
	$exec = mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);

	$confirmationStr = "HEMA Games Archive user created<BR>";
	$confirmationStr .= "Name: <b>{$userName}</b><BR>";
	$confirmationStr .= "Acount: <b>{$userAccount}</b><BR>";
	$confirmationStr .= "Password: <b>{$password}</b><BR>";
	$confirmationStr .= "Log in at:  www.seanfranklin.ca/hga/adminLogIn.php";
	setAlert(USER_ALERT, $confirmationStr);

}

/******************************************************************************/

// END OF FILE /////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
