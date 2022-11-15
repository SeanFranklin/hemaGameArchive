<?php 
/*******************************************************************************


*******************************************************************************/

// INITIALIZATION //////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

$pageName = "Tag List";

include('includes/header.php');

if(hasPermisionForPage('EDIT') == true){

	$tagsByType = getTagListByType();
	$tagMetaTypes = getTagMetaTypes();

// PAGE DISPLAY ////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
?>
	<div class='grid-x grid-margin-x'>


	<div class='large-12 cell '>

		<table class='stack'>
		<?php foreach($tagsByType as $tagMetaID => $tType):?>
			<tr >
			<td colspan="100%">
				<h3 >
				<a onclick="$('.metaID-<?=$tagMetaID?>').toggle()" class='tag-color-<?=$tagMetaID?>'>
						<?=$tType['tagMetaName']?> ↓
				</a>
				</h3>
				<i><?=$tType['tagMetaDescription']?></i>
			</td>
			</tr>
			
			<?php foreach($tType['tags'] as $t):?>
				
				<tr style='display:none' class='metaID-<?=$tagMetaID?>'>
				<form method="POST">
					<input type='hidden' name='updateTagInfo[tagID]' value=<?=$t['tagID']?>>
					<input type='hidden' name='deleteTag[tagID]' value=<?=$t['tagID']?>>
					<td>
						<input class='text' name='updateTagInfo[tagName]' value='<?=$t['tagName']?>' required>
					</td>
					<td>
						<?=changeTagMeta($t, $tagMetaTypes,'updateTagInfo')?>
					</td>
					<td><button class='button success small no-bottom' name='formName' value='updateTagInfo'>Update</button></td>
					<td>Added by: <?=getUserName($t['userID'])?></td>
					<td><button class='button alert small no-bottom' name='formName' value='deleteTag'>Delete</button></td>
				</form>
				</tr>
				
			<?php endforeach ?>	
			
		<?php endforeach ?>
		</table>

	</form>

	</div>
	</div>

<? }
include('includes/footer.php');

// FUNCTIONS ///////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

/******************************************************************************/


/******************************************************************************/

// END OF DOCUMENT /////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////