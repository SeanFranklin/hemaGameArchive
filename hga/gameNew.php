<?php 
/*******************************************************************************
	Event Selection
	
	Select which event to use
	Login:
		- SUPER ADMIN can see hidden events
	
*******************************************************************************/

// INITIALIZATION //////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

$pageName = "Newly Added";

define("DAYS_TO_LIST",182);

include('includes/header.php');



	$gameList = getGameCreationHistory();
	$infoList = getInfoCreationHistory();

	$date = new DateTime(date('Y-m-d'));


	$gameIndex = 0;
	$gameNextDate = $gameList[0]['gameDatestamp'];
	$infoIndex = 0;
	$infoNextDate = $infoList[0]['infoDate'];

	echo "<table class='stack'>";
	for($day = 0; $day<DAYS_TO_LIST; $day++){

		$dateString = (string)($date->format('Y-m-d'));

		if($dateString == $gameNextDate || $dateString == $infoNextDate){
			echo "<tr><td colspan='100%'><h4 class='no-bottom'>{$dateString}</td></h4 >";
			echo "<div style='font-size:1.3em;'>";

			if($gameIndex < sizeof($gameList)){

				while($gameList[$gameIndex]['gameDatestamp'] == $dateString){
					$g = $gameList[$gameIndex]; 
					echo "<tr><td class='show-for-large-only'><td>";
					echo "<td><b>New Game</br></td> ";
					echo "<td><a href='gameInfo.php?g={$g['gameID']}'>";
					echo getGameName($g['gameID']);
					echo "</a></td><td><i>".getUserName($g['userID'])."</i></td></tr>";
					$gameIndex++;

					if($gameIndex >= sizeof($gameList)){
						break;
					}
				}

				if($gameIndex < sizeof($gameList)){
					$gameNextDate = $gameList[$gameIndex]['gameDatestamp'];
				}

			} else {
				$gameNextDate = null;
			}

			if($infoIndex < sizeof($infoList)){
				while($infoList[$infoIndex]['infoDate'] == $dateString){
					$i = $infoList[$infoIndex]; 

					echo "<tr><td class='show-for-large-only'><td>";
					echo "<td><i>Info added</i></td> ";
					echo "<td><i><a href='gameInfo.php?g={$i['gameID']}'>";
					echo getGameName($i['gameID']);
					echo "</a></i></td><td><i>".getUserName($i['userID'])."</i></td></tr>";

					$infoIndex++;

					if($infoIndex >= sizeof($infoList)){
						break;
					}
				}

				if($infoIndex < sizeof($infoList)){
					$infoNextDate = $infoList[$infoIndex]['infoDate'];
				}
			} else {
				$infoNextDate = null;
			}

			echo "</div>";

		}

		
		$date->modify('-1 day');
	}
	echo "</table>";


// PAGE DISPLAY ////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
?>

	

<?php
include('includes/footer.php');

// FUNCTIONS ///////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

/******************************************************************************/

/******************************************************************************/

// END OF DOCUMENT /////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
