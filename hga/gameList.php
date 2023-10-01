<?php
/*******************************************************************************
	Event Selection

	Select which event to use
	Login:
		- SUPER ADMIN can see hidden events

*******************************************************************************/

// INITIALIZATION //////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

$pageName = "Game List";

include('includes/header.php');

	$gameList = getGameListAndTags();
	$tagList = getTagList();

	if(isset($_SESSION['urlTagList']) == true){
		$tagsFromUrl = "";
		foreach((array)$_SESSION['urlTagList'] as $tag){
			$tagsFromUrl .= $tag." ";
		}
		unset($_SESSION['urlTagList']);
	} else {
		$tagsFromUrl = null;
	}


// PAGE DISPLAY ////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
?>

	<script>
		var gameListWithTags = <?=json_encode($gameList)?>;
	</script>

	<div class='grid-x grid-margin-x'>

	<div class='cell large-4 hide-for-large'>
		<?=displayTagList($tagList)?>
	</div>

	<div class='cell large-8'>
		<?=tagListInput("a", true, $tagsFromUrl)?>


		<table class='stack'>

		<?php foreach($gameList as $g): ?>
			<tr id='game-table-row-<?=$g['gameID']?>'>
				<td class='no-wrap'>
					<a href='gameInfo.php?g=<?=$g['gameID']?>'>
						<?=$g['gameName']?>
						<?php if(ALLOW['ADD'] == true): ?>
							<BR><i>(<?=getUserName($g['userID'])?>)</i>
						<?php endif ?>
					</a>

				</td>
				<td class='monospace'>
					<?php foreach($g['tags'] as $tag):?>
						<span class='tag-color-<?=$tag['tagMetaID']?>'><?=$tag['tagName']?></span>,
					<?php endforeach ?>
				</td>

			</tr>
		<?php endforeach ?>

		</table>

		<span id='tag-url'></span>

	</div>

	<div class='cell large-3 show-for-large'>
		<?=displayTagList($tagList)?>
	</div>


	</div>

<?php
include('includes/footer.php');

// FUNCTIONS ///////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

/******************************************************************************/

function displayTagList($tagList){
	$currentMetaID = 0;
?>

	<div>
	<h4 class='no-bottom'>
		Tag List
	</h4>

	<ul>
	<?php foreach($tagList as $t):
		if($currentMetaID != $t['tagMetaID']){
			$currentMetaID = $t['tagMetaID'];
			?>
			</ul></div>
			<h5 class='no-bottom'>


				<a onclick="$('.metaID-<?=$currentMetaID?>').toggle()">
						<?=getTagMetaName($currentMetaID)?> ↓
				</a>


			</h5>
			<div style='display:none' class='metaID-<?=$currentMetaID?>'>
			<ul>
			<?php
		}

		?>

		<li class='monospace'>
			<a class='tag-color-<?=$t['tagMetaID']?>' onclick="appendToTagEntry('<?=$t['tagName']?>')">
				<?=$t['tagName']?> (<?=$t['numUses']?>)
			</a>
		</li>

	<?php endforeach ?>
	</ul>
	</div>
<?php
}

/******************************************************************************/

// END OF DOCUMENT /////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
