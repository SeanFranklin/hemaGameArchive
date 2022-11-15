<?php 
/*******************************************************************************
	Event Selection
	
	Select which event to use
	Login:
		- SUPER ADMIN can see hidden events
	
*******************************************************************************/

// INITIALIZATION //////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

$pageName = "";

include('includes/header.php');

if($_SESSION['gameID'] == 0){
	displayAlert("No game selected. <a href='gameList.php'>Choose a game</a>.");
} else {

	$gameID = (int)$_SESSION['gameID'];
	$gameInfo = getGameInfo($gameID);

	$nameToDisplay = $gameInfo['gameName'];
	if(ALLOW['ADMIN'] == true){
		$nameToDisplay .= " ({$gameID})";
	}

	$infoMetaTypes = getInfoMetaTypes();
	infoMetaDescriptionBox($infoMetaTypes);
	$rulesCalloutClass = infoMetaCalloutColorClass(INFO_META_RULES);

// PAGE DISPLAY ////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
?>

	<h3 style='display:inline'><?=$nameToDisplay?></h3> 
	<i>Uploaded by <?=$gameInfo['userName']?> on <?=$gameInfo['gameDatestamp']?></i>
	
	<div class='cell large-12'>
		<b>Tags: </b>
		<?=addTagsBox()?>
		<?=removeTagsBox($gameInfo['tags'])?>
		<span class='monospace'>
			&nbsp;
			<?php foreach($gameInfo['tags'] as $tag):?>
				<span class='tag-color-<?=$tag['tagMetaID']?>'><?=$tag['tagName']?></span> |
			<?php endforeach ?>
		</span>
		
	</div>

	<div class='callout <?=$rulesCalloutClass?>'>
		<h4 class='no-bottom'>Rules</h4>
		<?=$gameInfo['gameRules']?>
		<?=editNameBox($gameInfo)?>
	</div>

	<?php foreach($gameInfo['info'] as $i):?>
		<?=displayInfo($i, $infoMetaTypes)?>
	<?php endforeach ?>

	<?=addInfoBox($infoMetaTypes)?>
	<?=deleteGameBox($nameToDisplay)?>

	
<? }
include('includes/footer.php');

// FUNCTIONS ///////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

/******************************************************************************/

function addTagsBox(){

	if(ALLOW['ADD'] == false){
		return;
	}
?>
	<a data-open="addTagsBox">[Add Tags]</a>

<!------------------------------------------------------------->

	<div class='reveal medium' id='addTagsBox' data-reveal>
		
		<form method="POST">
		<div class='grid-x grid-margin-x'>

			<h3 class='cell large-12'>Add New Tags:</h3>

			<input type='hidden' name='addTags[gameID]' value=<?=$_SESSION['gameID']?>>

			<div class='cell large-12'>
				<?=tagListInput("addTags[tagString]")?>
			</div>

			<BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR>

			<button class='button success cell large-6' name='formName' value='addTags'>
				Add Tags
			</button>

			<a class='button cell large-6 secondary align-middle' data-close>
				Nope, keep it as-is
			</a>

		</div>
		</form>

		<!-- Reveal close button -->
		<button class='close-button' data-close aria-label='Close modal' type='button'>
			<span aria-hidden='true'>&times;</span>
		</button>

	</div>

<?
}

/******************************************************************************/
function removeTagsBox($tags){

	if(ALLOW['EDIT'] == false){
		return;
	}
?>
	<a data-open="removeTagsBox">[Remove Tags]</a>

<!------------------------------------------------------------->

	<div class='reveal tiny' id='removeTagsBox' data-reveal>
		
		<form method="POST">
		<div class='grid-x grid-margin-x'>

			<h3 class='cell large-12'>Remove Tags:</h3>

			<div class='cell large-12'>
				<table>
				<?php foreach($tags as $t):?>
					<tr>
						<td>
							<?=checkboxPaddle("removeGameTags[{$t['gameTagID']}]",$t['gameTagID'],null,null)?>
						</td>
						<td><?=$t['tagName']?></td>
					</tr>
				<?php endforeach ?>
			</table>
			</div>

			<button class='button alert cell large-6' name='formName' value='removeGameTags'>
				Remove Tags
			</button>

			<a class='button cell large-6 secondary align-middle' data-close>
				Nope, keep it as-is
			</a>

		</div>
		</form>

		<!-- Reveal close button -->
		<button class='close-button' data-close aria-label='Close modal' type='button'>
			<span aria-hidden='true'>&times;</span>
		</button>

	</div>

<?
}

/******************************************************************************/

function editNameBox($gameInfo){

	if(ALLOW['EDIT'] == false){
		return;
	}

	$nameToDisplay = $gameInfo['gameName'];
	if(ALLOW['ADMIN'] == true){
		$nameToDisplay .= " ({$gameInfo['gameID']})";
	}

?>

	<div class='text-right'>
		<a data-open="editGameBox"><i>[edit game]</i></a>
	</div>

	

<!------------------------------------------------------------->

	<div class='reveal full' id='editGameBox' data-reveal>
		
		<form method="POST">
		<input type='hidden' name='editGame[gameID]' value=<?=$_SESSION['gameID']?>>

		<div class='grid-x grid-margin-x'>
			<div class='cell large-12'>
				<h4>Editing <?=$nameToDisplay?></h4>
				<hr>
			</div>

			<div class='cell large-12'>
				<h5 class='no-bottom'>Game Name</h5>
			</div>

			<div class='cell large-4'>
				<input type='text' name='editGame[gameName]' required value='<?=$gameInfo['gameName']?>'>
			</div>
		
			<div class='cell large-12'>
				<h5 class='no-bottom'>Rules</h5>
				How the game is played/scored. This should be information about WHAT to do, but please save WHY for the "Design" field.
				<BR>
				<i>(If you need to explain weird edge cases put it in the bottom after the main description.)
				</i>
		 		<textarea  class="summernote" name='editGame[gameRules]'>
		 			<?=$gameInfo['gameRules']?>
		 		</textarea>
		 	</div>

			<button class='button success cell large-6' name='formName' value='editGame'>
				Update game
			</button>

			<a class='button cell large-6 secondary align-middle' data-close>
				Nope, keep it as-is
			</a>

		</div>
		</form>

		<!-- Reveal close button -->
		<button class='close-button' data-close aria-label='Close modal' type='button'>
			<span aria-hidden='true'>&times;</span>
		</button>
	</div>
<?
}

/******************************************************************************/

function addInfoBox($infoMetaTypes){


	if(ALLOW['ADD'] == false){
		return;
	}
?>

	<div  style='border-top: 3px solid black; margin-top: 20px; margin-bottom: 20px;'>
	</div>

	
	<div class='callout'>
	<h5 class='no-bottom'>Add additional information:</h5>
	
	
	<form method="POST">

		<input type='hidden' name='newGameInfo[gameID]' value=<?=$_SESSION['gameID']?>>

		<textarea  class="summernote" name='newGameInfo[infoText]' required></textarea>

		<div class='grid-x grid-margin-x'>
			
			<div class='large-3 medium-6 cell input-group'>
				<span class='input-group-label'>Type:</span>
				<select class='input-group-field' name='newGameInfo[infoMetaID]' required>
					<option selected disabled></option>
					<?php foreach($infoMetaTypes as $m): ?>
						<option value=<?=$m['infoMetaID']?>>
							<?=$m['infoMetaName']?>
						</option>
					<?php endforeach ?>
				</select>
				<span class='input-group-label'>
					<a data-open='infoMetaDescriptionBox'><i>(huh?)</i></a>
				</span>
			</div>

			<button class='button success cell large-2 medium-4' name='formName' value='newGameInfo'>
				Attach to game
			</button>
		</div>
	</form>
	</div>

<?
}

/******************************************************************************/

function displayInfo($info, $infoMetaTypes){

	$class = infoMetaCalloutColorClass($info['infoMetaID']);
?>
	<div class='callout <?=$class?>'>
		<b><?=$info['infoMetaName']?></b> 
		<i>(added <?=$info['infoDate']?> 
		by <?=getUserName($info['userID'])?>)</i><BR>

		<?=$info['infoText']?>

		<?=editInfoBox($info, $infoMetaTypes)?>
	</div>


<?
}

/******************************************************************************/

function editInfoBox($info, $infoMetaTypes){

	if(ALLOW['EDIT'] == false){
		return;
	}

?>

	<div class='text-right'>
		<a data-open="editInfoBox-<?=$info['infoID']?>"><i>[edit]</i></a>
	</div>

<!------------------------------------------------------------->

	<div class='reveal large' id='editInfoBox-<?=$info['infoID']?>' data-reveal>
		
		<form method="POST">
		<input type='hidden' name='editGameInfo[infoID]' value=<?=$info['infoID']?>>

		<div class='grid-x grid-margin-x'>

			<div class='cell large-12'>
				<h4>Edit Information</h4>
				<b><?=$info['infoMetaName']?></b> <i>(added <?=$info['infoDate']?> by <?=getUserName($info['userID'])?>)</i>
				<hr>
			</div>

			<div class='large-3 medium-6 cell input-group'>
				<span class='input-group-label'>Type:</span>
				<select class='input-group-field' name='editGameInfo[infoMetaID]' required>
					<?php foreach($infoMetaTypes as $m): ?>
						<option <?=optionValue($m['infoMetaID'],$info['infoMetaID'])?>>
							<?=$m['infoMetaName']?>
						</option>
					<?php endforeach ?>
				</select>
			</div>

			<div class='cell large-12'>
		 		<textarea  class="summernote" name='editGameInfo[infoText]'>
		 			<?=$info['infoText']?>
		 		</textarea>
		 	</div>

			<button class='button success cell large-6' name='formName' value='editGameInfo'>
				Update Information
			</button>

			<a class='button cell large-6 secondary align-middle' data-close>
				Nope, keep it as-is
			</a>

		</div>
		</form>

		<!-- Reveal close button -->
		<button class='close-button' data-close aria-label='Close modal' type='button'>
			<span aria-hidden='true'>&times;</span>
		</button>
	</div>
<?
}

/******************************************************************************/

function deleteGameBox($nameToDisplay){

	if(ALLOW['ADMIN'] == false){
		return;
	}
?>
	<hr>
	<a class='button alert' data-open="deleteGameBox">Delete Game</a>

	<div class='reveal medium' id='deleteGameBox' data-reveal>
		
		<form method="POST">
		<div class='grid-x grid-margin-x'>

			<div class='cell large-12'>
				<h4>You're sure about deleting</h4>
				<h3><?=$nameToDisplay?></h3>
				<hr>
			</div>

			<input type='hidden' name='gameID' value=<?=$_SESSION['gameID']?>>

			<button class='button alert cell large-6' name='formName' value='deleteGame'>
				I'm very sure I want to do this for real.<BR>
				<i>(No going back on this)</i>
			</button>

			<a class='button cell large-6 secondary align-middle' data-close>
				No, that's a bad idea.
			</a>

		</div>
		</form>

		<!-- Reveal close button -->
		<button class='close-button' data-close aria-label='Close modal' type='button'>
			<span aria-hidden='true'>&times;</span>
		</button>
	</div>

<?
}

/******************************************************************************/

// END OF DOCUMENT /////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
