<?php
/*******************************************************************************
	Database Read Functions
	
	Functions for reading from the HEMA Scorecard database
	
*******************************************************************************/

/******************************************************************************/

function getGameName($gameID){

	$gameID = (int)$gameID;

	if($gameID == 0){
		return 0;
	}

	$sql = "SELECT gameName
			FROM gameList
			WHERE gameID = {$gameID}";

	return (mysqlQuery($sql, SINGLE, 'gameName'));
}

/******************************************************************************/

function getGameList(){

	$sql = "SELECT gameID
			FROM gameList";
	return ((array)mysqlQuery($sql, KEY, 'gameID'));

}

/******************************************************************************/

function getGameListAndTags(){

	$sql = "SELECT gameID, gameName, userID
			FROM gameList
			ORDER BY gameName ASC";
	$allGames = (array)mysqlQuery($sql, ASSOC, 'gameID', 'gameName');

	foreach($allGames as $game){
		$gameList[$game['gameID']]['gameID'] = $game['gameID'];
		$gameList[$game['gameID']]['gameName'] = $game['gameName'];
		$gameList[$game['gameID']]['userID'] = $game['userID'];
		$gameList[$game['gameID']]['tags'] = [];
	}
	
	$sql = "SELECT gameID, tagID, tagName, tagMetaID
			FROM gameTag
			INNER JOIN tagList USING(tagID)
			INNER JOIN tagMeta USING(tagMetaID)
			ORDER BY sortOrder ASC, tagName ASC";
	$allGameTags = (array)mysqlQuery($sql, ASSOC);

	foreach($allGameTags as $gameTag){
		$gameList[$gameTag['gameID']]['tags'][] = $gameTag;
	}

	return ($gameList);
	

}

/******************************************************************************/

function getGameInfo($gameID){

	$gameID = (int)$gameID;

	if($gameID == 0){
		return [];
	}

	$sql = "SELECT gameID, gameName, userID, userName, gameRules, gameDatestamp
			FROM gameList
			INNER JOIN userList USING(userID)
			WHERE gameID = {$gameID}";
	$gameInfo = (array)mysqlQuery($sql, SINGLE);

	$sql = "SELECT infoID, infoMetaID, infoMetaName, infoText, infoDate, userID
			FROM infoList
			INNER JOIN infoMeta USING(infoMetaID)
			WHERE gameID = {$gameID}
			ORDER BY infoMetaID ASC, infoDate ASC";
	$infoItems = (array)mysqlQuery($sql, ASSOC);

	$gameInfo['info'] = [];
	$gameInfo['media'] = [];

	foreach($infoItems as $i){

		if($i['infoMetaID'] != INFO_META_MEDIA){
			$gameInfo['info'][$i['infoID']] = $i;
		} else {
			$gameInfo['media'][$i['infoID']] = $i;
		}
		
	}

	$gameInfo['tags'] = getGameTags($gameID);

	return $gameInfo;
}

/******************************************************************************/

function getGameTags($gameID){
	$gameID = (int)$gameID;

	$sql = "SELECT gameTagID, tagID, gT.userID, tagName, tagMetaID
			FROM gameTag AS gT
			INNER JOIN tagList USING(tagID)
			INNER JOIN tagMeta USING(tagMetaID)
			WHERE gameID = {$gameID}
			ORDER BY sortOrder ASC, tagName ASC";
	return ((array)mysqlQuery($sql, ASSOC));
}

/******************************************************************************/

function getTagName($tagID){
	$tagID = (int)$tagID;

	$sql = "SELECT tagName
			FROM tagList
			WHERE tagID = {$tagID}";
	return((string)mysqlQuery($sql,SINGLE,'tagName'));

}

/******************************************************************************/

function getTagInfo($tagID){
	$tagID = (int)$tagID;

	$sql = "SELECT tagID, tagName, tagDescription, tagMetaID, tagMetaName
			FROM tagList
			INNER JOIN tagMeta USING(tagMetaID)
			WHERE tagID = {$tagID}";
	return((array)mysqlQuery($sql,SINGLE));

}

/******************************************************************************/

function getAllTagNames(){
	$sql = "SELECT tagName
			FROM tagList
			ORDER BY tagName ASC";
	return ((array)mysqlQuery($sql, SINGLES, 'tagName'));
}

/******************************************************************************/

function getTagList(){
	$sql = "SELECT tagID, tagName, tagMetaID,
					(SELECT count(*)
					FROM gameTag AS gT
					WHERE gT.tagID = tL.tagID) AS numUses
			FROM tagList AS tL
			INNER JOIN tagMeta USING(tagMetaID)
			ORDER BY sortOrder ASC, tagName ASC";
	return ((array)mysqlQuery($sql, ASSOC));
}

/******************************************************************************/

function getTagMetaName($tagMetaID){
	$tagMetaID = (int)$tagMetaID;

	$sql = "SELECT tagMetaName
			FROM tagMeta
			WHERE tagMetaID = {$tagMetaID}";
	return((string)mysqlQuery($sql, SINGLE,'tagMetaName'));
}

/******************************************************************************/

function getTagMetaDescription($tagMetaID){
	$tagMetaID = (int)$tagMetaID;

	$sql = "SELECT tagMetaDescription
			FROM tagMeta
			WHERE tagMetaID = {$tagMetaID}";
	return((string)mysqlQuery($sql, SINGLE,'tagMetaDescription'));
}

/******************************************************************************/

function getTagMetaTypes(){

	$sql = "SELECT tagMetaID, tagMetaName, tagMetaDescription
			FROM tagMeta
			ORDER BY sortOrder ASC";
	return((array)mysqlQuery($sql, KEY, 'tagMetaID'));
}

/******************************************************************************/

function getTagListByType(){

	$sql = "SELECT tagMetaID, tagMetaName, tagMetaDescription, tagColor
			FROM tagMeta
			ORDER BY sortOrder ASC";
	$sorted = (array)mysqlQuery($sql, KEY, 'tagMetaID');

	foreach($sorted as $i => $tType){
		$sorted[$i]['tags'] = [];
	}

	$sql = "SELECT tagID, tagMetaID, tagName, userID
			FROM tagList AS tL
			INNER JOIN tagMeta USING(tagMetaID)
			ORDER BY sortOrder ASC, tagName ASC";
	$unsorted = (array)mysqlQuery($sql, ASSOC);


	foreach($unsorted as $t){
		$sorted[$t['tagMetaID']]['tags'][] = $t;
	}


	return ($sorted);
}

/******************************************************************************/

function getAllTagIdsFromName(){
	$sql = "SELECT tagID, tagName
			FROM tagList
			ORDER BY tagName ASC";
	return ((array)mysqlQuery($sql, KEY_SINGLES, 'tagName','tagID'));
}

/******************************************************************************/

function getTagID($tagName){

	$sql = "SELECT tagID
			FROM tagList
			WHERE tagName = ?";

	$stmt = $GLOBALS["___mysqli_ston"]->prepare($sql);
	$stmt->bind_param("s",$tagName);
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($tagID);
	$stmt->fetch();

	return ((int)$tagID);

}

/******************************************************************************/

function getUserName($userID){

	$userID = (int)$userID;

	$sql = "SELECT userName
			FROM userList
			WHERE userID = {$userID}";

	return (mysqlQuery($sql, SINGLE, 'userName'));

}

/******************************************************************************/

function getUserIdFromAccount($userAccount){

	$sql = "SELECT userID
			FROM userList
			WHERE userAccount = ?";

	$stmt = $GLOBALS["___mysqli_ston"]->prepare($sql);
	$stmt->bind_param("s",$userAccount);
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($userID);
	$stmt->fetch();

	return ((int)$userID);
}

/******************************************************************************/

function getUserInfo($userID){

	$userID = (int)$userID;

	$sql = "SELECT userID, userName, userAccount, CAN_ADD, CAN_EDIT, CAN_ADMIN
			FROM userList
			WHERE userID = {$userID}";
	return ((array)mysqlQuery($sql, SINGLE));
}

/******************************************************************************/

function getUserList(){

	$sql = "SELECT userID, userName, userAccount, CAN_ADD, CAN_EDIT, CAN_ADMIN,
				IF(password IS NOT NULL, TRUE, FALSE) AS hasPassword
			FROM userList
			ORDER BY userName ASC";
	return ((array)mysqlQuery($sql, ASSOC));

}

/******************************************************************************/

function doesUserHavePassword($userID){

	$userID = (int)$userID;


	$sql = "SELECT password
			FROM userList
			WHERE userID = {$userID}";
	$passwordDB = mysqlQuery($sql, SINGLE, 'password');

	if($passwordDB === NULL){
		$hasPass = false;
	} else {
		$hasPass = true;
	}

	return ($hasPass);

}

/******************************************************************************/

function isPasswordCorrect($userID, $passwordInput){

	$userID = (int)$userID;
	$passwordCorrect = false;

	if($userID == 0){
		return ($passwordCorrect);
	}

	$sql = "SELECT password
			FROM userList
			WHERE userID = {$userID}";
	$passwordDB = (string)mysqlQuery($sql, SINGLE, 'password');

	if(strlen($passwordDB) == 0){

		// If they don't have a password in the DB they can log-in without a password.
		$passwordCorrect = true;

	} elseif(password_verify($passwordInput, $passwordDB) == true){

		$passwordCorrect = true;

	} else {

		$passwordCorrect = false;
	}
	
	return ($passwordCorrect);
}

/******************************************************************************/

function getInfoMetaTypes(){

	$sql = "SELECT infoMetaID, infoMetaName, infoMetaDescription
			FROM infoMeta";
	return ((array)mysqlQuery($sql, ASSOC));
}

/******************************************************************************/

function getNewsfeedInfo($numInFeed){

	$numInFeed = (int)$numInFeed;

	$sql = "SELECT gameID, gameName, userID, gameDatestamp
			FROM gameList
			ORDER BY gameDatestamp DESC
			LIMIT {$numInFeed}";
	$recent['games'] = (array)mysqlQuery($sql, ASSOC);

	$sql = "SELECT gameID, gameName, iL.userID, infoDate
			FROM infoList AS iL
			INNER JOIN gameList USING(gameID)
			WHERE gameDatestamp != infoDate
			ORDER BY infoDate DESC
			LIMIT {$numInFeed}";
	$recent['info'] = (array)mysqlQuery($sql, ASSOC);

	return ($recent);
}

/******************************************************************************/

function getGameCreationHistory(){
	$sql = "SELECT gameID, gameName, userID, gameDatestamp
			FROM gameList
			ORDER BY gameDatestamp DESC";
	$history = (array)mysqlQuery($sql, ASSOC);
	return ($history);
}

/******************************************************************************/

function getInfoCreationHistory(){
	$sql = "SELECT gameID, gameName, iL.userID, infoDate
			FROM infoList AS iL
			INNER JOIN gameList USING(gameID)
			WHERE gameDatestamp != infoDate
			ORDER BY infoDate DESC";
	$history = (array)mysqlQuery($sql, ASSOC);
	return ($history);
}

/******************************************************************************/

function getArchiveStats(){

	$sql = "SELECT COUNT(*) AS numGames
			FROM gameList";
	$stats['numGames'] = (int)mysqlQuery($sql, SINGLE, 'numGames');

	$sql = "SELECT COUNT(DISTINCT userID) AS numAuthors
			FROM gameList";
	$stats['numAuthors'] = (int)mysqlQuery($sql, SINGLE, 'numAuthors');

	$sql = "SELECT COUNT(*) AS numTagTypes
			FROM  tagList";
	$stats['numTagTypes'] = (int)mysqlQuery($sql, SINGLE, 'numTagTypes');

	$sql = "SELECT COUNT(*) AS numTagsAttached
			FROM gameTag";
	$stats['numTagsAttached'] = (int)mysqlQuery($sql, SINGLE, 'numTagsAttached');

	return($stats);

}

/******************************************************************************/
// END OF FILE /////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
