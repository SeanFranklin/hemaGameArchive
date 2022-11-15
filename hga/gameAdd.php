<?php 
/*******************************************************************************
	Event Selection
	
	Select which event to use
	Login:
		- SUPER ADMIN can see hidden events
	
*******************************************************************************/

// INITIALIZATION //////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

$pageName = "Add New Game";
$summernote[] = 1;
$summernote[] = 2;
$summernote[] = 3;

include('includes/header.php');

if(hasPermisionForPage("ADD") == true){
 


// PAGE DISPLAY ////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
?>

	
	<form method="POST">
	<div class='grid-x grid-margin-x'>

		<div class='cell large-4'>
			<h5 class='no-bottom'>Game Name</h5>
			<input type='text' name='addNewGame[gameName]' required>
		</div>

		<div class='cell large-8 callout primary'>
			<i>Remember this is the HEMA <b>Game</b> Archive. All games have win conditions for both roles and work on the assumption <u>both participants are trying to win</u> by exploring all the different options afforded by the rules.</i>
		</div>

		<div class='cell large-12'>
			<h5 class='no-bottom'>Tags</h5>
			<?=tagListInput('addNewGame[tagString]')?>
		</div>
		
		<div class='cell large-12'>
			<h5 class='no-bottom'>Rules</h5>
			How the game is played/scored. This should be information about WHAT to do, but please save WHY for the "Design" field.
			<BR>
			<i>(If you need to explain weird edge cases put it in the bottom after the main description.)
			</i>
	 		<textarea  class="summernote" name='addNewGame[gameRules]' required></textarea>

	 		<h5 class='no-bottom'>Design (optional)</h5>
	 		Why the rules are designed the way they are. What are the core skills targeted, and possible weaknesses.
	 		<BR>
	 		<i>(Descriptions of the design iterations, failed attempts, or changes in thoughts are also helpful to other coaches.)</i>
	 		<textarea  class="summernote" name='addNewGame[gameDesign]'></textarea>

	 		<h5 class='no-bottom'>Notes (optional)</h5>
	 		Any additional notes on what you have noticed playing this game with your students.
	 		<textarea  class="summernote" name='addNewGame[gameNote]'></textarea>
	
			<button class='button success' name='formName' value='addNewGame'>
				Add New Game!
			</button>
		</div>

	</div>
	</form>
	

<? }
include('includes/footer.php');

// FUNCTIONS ///////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

/******************************************************************************/



/******************************************************************************/

// END OF DOCUMENT /////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
