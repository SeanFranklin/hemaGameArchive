<?php
/*******************************************************************************
	Display Functions

	Functions to display information to the screen which may be called from
	multiple pages.
	Also performs database access in some cases

*******************************************************************************/

/******************************************************************************/

function displayPageAlerts(){
// This function will display any messages which have been added to
// $_SESSION['alertMessages']. These alert messages are written to by many
// data processing functions, either as errors or completion confirmations.
// This function is called at the top of every page by the header
// to display error messages created in processing POST data.

// Only displays diagnostic errors for the Software Administrator
	if(ALLOW['ADMIN'] == true){
		foreach($_SESSION['alertMessages']['systemErrors'] as $message){
			displayAlert("<strong>Error: </strong>".$message, 'alert');
		}
	} else {
		// If it is a normal user, alert them that there was an error.
		if(sizeof($_SESSION['alertMessages']['systemErrors']) > 0){
			displayAlert("Appologies, but it seems we have encountered some sort of internal error.",'alert');
		}
	}
	$_SESSION['alertMessages']['systemErrors'] = [];

// Error messages for the user.

	foreach((array)$_SESSION['alertMessages']['userErrors'] as $message){
		displayAlert("<strong>Error: </strong>".$message,'alert');
	}
	$_SESSION['alertMessages']['userErrors'] = [];

	foreach((array)$_SESSION['alertMessages']['userWarnings'] as $message){
		displayAlert("<strong>Warning: </strong>".$message, 'warning');
	}
	$_SESSION['alertMessages']['userWarnings'] = [];

// Alert messages for the user (ie confirmation messages)
	$alertMessage = '';
	if(sizeof($_SESSION['alertMessages']['userAlerts']) == 1){
		$alertMessage = $_SESSION['alertMessages']['userAlerts'][0];
	} elseif(sizeof($_SESSION['alertMessages']['userAlerts']) > 1) {
		$alertMessage = "<ul>";
		foreach($_SESSION['alertMessages']['userAlerts'] as $message){
			$alertMessage .= "<li>{$message}</li>";
		}
		$alertMessage .= "</ul>";
	}
	displayAlert($alertMessage);
	$_SESSION['alertMessages']['userAlerts'] = [];

}

/******************************************************************************/

function displayAlert ($text = null, $class = 'secondary'){
// Displays a large callout box containing the text parameter


	if($text == null){
		return;
	}

	echo"
	<div class='cell callout {$class} text-center' data-closable>
		<button class='close-button' aria-label='Dismiss alert' type='button' data-close>
			<span aria-hidden='true'>&times;</span>
		</button>

		{$text}
	</div>";

}

/******************************************************************************/

function changeTagMeta($tagInfo, $tagMetaTypes, $name, $class=''){
?>

	<select class='no-bottom <?=$class?>' name='<?=$name?>[tagMetaID]'>
		<?php foreach($tagMetaTypes as $id => $tType):?>
			<option <?=optionValue($id, $tagInfo['tagMetaID'])?>>
				<?=$tType['tagMetaName']?>
			</option>
		<?php endforeach ?>
	</select>

<?php
}

/******************************************************************************/

function askForTagMeta(){


	if(    (isset($_SESSION['newTagsWithNoMeta'][$_SESSION['userID']]) == false)
		|| (ALLOW['ADD'] == false)){

		unset($_SESSION['newTagsWithNoMeta']);
		return;
	}

	$tagMetaTypes = getTagMetaTypes();

?>
	<div class='callout alert grid-x grid-margin-x'>

	<div class='large-12'>
		You have created the following new tags. Please classify them:
		<BR><BR>
	</div>

	<form method="POST" class='large-5'>

		<?php foreach($_SESSION['newTagsWithNoMeta'][$_SESSION['userID']] as $tagID):

			$tagInfo = getTagInfo($tagID);
			?>
			<input type='hidden' name='updateTagMetas[<?=$tagID?>][tagID]' value=<?=$tagID?>>
			<div class='input-group'>
				<span class='input-group-label '>
					<?=$tagInfo['tagName']?>
				</span>

				<?=changeTagMeta($tagInfo, $tagMetaTypes, "updateTagMetas[{$tagID}]", 'input-group-field')?>
			</div>
		<?php endforeach ?>

		<button class='button success' name='formName' value='updateTagMetas'>Assign Types</button>
	</form>

	<form method="POST" class='large-12'>

		<HR>

		<button class='button secondary' name='formName' value='clearNewTagsWithNoMeta'>
			No, I'm inconsiderate and not going to do it.
		</button>

	</form>

	</div>

<?php

}

/******************************************************************************/

function tagListInput($inputName, $nameList = false, $defaultData = null){
	$tagList = getAllTagNames();
	$tagIdsFromName = getAllTagIdsFromName();
	$gameNameList = getGameNameList();
?>

	<script>
		var tagList = <?=json_encode($tagList)?>;
		var tagIdsFromName = <?=json_encode($tagIdsFromName)?>;
		var gameNameList = <?=json_encode($gameNameList)?>;
		<?php if($defaultData != null):?>
			document.addEventListener('DOMContentLoaded', function() {
				tagTest();
			},);
		<?php endif ?>
	</script>

	<div class="tag-input-container input-group grid-x input-mode">
		<span class='input-group-label large-1 medium-2 small-12 inline'>Tags:</span>
		<input class='input-group-field' id='tag-input'
		type='text' name='<?=$inputName?>' required
		autocomplete="off"  value='<?=$defaultData?>'>

		<?php if($nameList == true): ?>
		<span class='input-group-label large-2 medium-3 small-12 inline align-right'>
			<a onclick="$('.input-mode').toggleClass('hidden')"><i>Name Mode (?)</i></a>
		</span>
		<?php endif ?>

		<div class="tag-suggestions">
			<ul></ul>
		</div>
	</div>

	<?php if($nameList == true): ?>
	<div class="tag-input-container input-group grid-x input-mode hidden">
		<span class='input-group-label large-2 medium-3 small-12 inline'>
			Name:
		</span>

		<input class='input-group-field' id='name-filter' type='text'
			name='<?=$inputName?>-name-filt' required
			autocomplete="off" value='<?=$defaultData?>'>

		<span class='input-group-label large-2 medium-3 small-12 inline align-right'>
			<a onclick="$('.input-mode').toggleClass('hidden')"><i> Tag Mode (?)</i></a>
		</span>
	</div>
	<?php else: ?>
		<input class='hidden'id='name-filter'>
	<?php endif ?>

<?php }

/******************************************************************************/

function checkboxPaddle($name, $onVal, $isOn = false, $offVal = 0, $class1 = null, $class2 = null){

	// This is explicityly designed to catch a value of 0 as false
	$checked = '';
	if($isOn != false){
		$checked = 'checked';
	}
?>

	<div class='switch text-center no-bottom'>

		<input type='hidden' name='<?=$name?>' value='<?=$offVal?>' class='<?=$class2?>' >

		<input class='switch-input <?=$class1?>' type='checkbox'
			id='<?=$name?>'  <?=$checked?>
			name='<?=$name?>' value='<?=$onVal?>'>

		<label class='switch-paddle' for='<?=$name?>'>
		</label>
	</div>
<?php
}

/*********************************************************(********************/

function tooltip($text, $tip = "<img src='includes/images/help.png'>", $dir='bottom'){
// Creates a tooltip that displays as $tip containing $text
// Defaults to displaying a help icon
	?>

	<?php if($tip == null): ?>
		<img src='includes/images/help.png'>
	<?php endif ?>


	<span data-tooltip aria-haspopup='true' class='has-tip'
		data-disable-hover='false' tabindex='2' title="<?=$text?>"
		data-position='<?=$dir?>' data-allow-html='true' >

		<?=$tip?>

	</span>

<?php }


/******************************************************************************/

function hasPermisionForPage($allowType){

	$has_permission = ALLOW[$allowType];

	if($has_permission == false){
		$str = "You do not have permision to view this page.<BR>
				<strong>Click here to <a href='adminLogIn.php'>Login</a></strong>";
		displayAlert($str);
	}

	return($has_permission);

}

/******************************************************************************/

function infoMetaDescriptionBox($infoMetaTypes){

?>


	<div class='reveal medium' id='infoMetaDescriptionBox' data-reveal>
		<?php foreach($infoMetaTypes as $m): ?>
			<h4><?=$m['infoMetaName']?></h4>
			<?=$m['infoMetaDescription']?>
			<hr>
		<?php endforeach ?>

		<!-- Reveal close button -->
		<button class='close-button' data-close aria-label='Close modal' type='button'>
			<span aria-hidden='true'>&times;</span>
		</button>
	</div>
<?php
}

/******************************************************************************/

function infoMetaCalloutColorClass($infoMetaID){

	$class = '';

	switch($infoMetaID){
		case INFO_META_RULES: {$class = 'warning'; break;}
		case INFO_META_DESIGN: {$class = 'primary'; break;}
		case INFO_META_NOTES: {$class = 'secondary'; break;}
		defaut: {$class = 'alert'; break;}
	}

	return ($class);

}

/******************************************************************************/

function newsfeed($numInFeed = 10){

	$recent = getNewsfeedInfo($numInFeed);

?>
	<div class='grid-x grid-margin-x'>

	<div class='callout large-7 cell warning'>
		<h4>Latest Games:</h4>
		<?php foreach($recent['games'] as $game):?>
			<li><b><a href="gameInfo.php?g=<?=$game['gameID']?>"><?=$game['gameName']?></a></b>
				by <i><?=getUserName($game['userID'])?></i>
				on <?=$game['gameDatestamp']?></li>
		<?php endforeach ?>
	</div>

	<div class='callout large-5 cell primary'>
		<h4>Information Updates/Comments:</h4>
		<?php foreach($recent['info'] as $game):?>
			<li><b><a href="gameInfo.php?g=<?=$game['gameID']?>"><?=$game['gameName']?></a></b>
				by <i><?=getUserName($game['userID'])?></i>
				on <?=$game['infoDate']?></li>
		<?php endforeach ?>
	</div>


	</div>


<?php
}


/******************************************************************************/

// END OF DOCUMENT /////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
