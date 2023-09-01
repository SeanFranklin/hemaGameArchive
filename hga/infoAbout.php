<?php 
/*******************************************************************************
	Event Selection
	
	Select which event to use
	Login:
		- SUPER ADMIN can see hidden events
	
*******************************************************************************/

// INITIALIZATION //////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

$pageName = "About";

include('includes/header.php');

	$stats = getArchiveStats();


// PAGE DISPLAY ////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
?>

	<div class='grid-x grid-margin-x'>
		<div class='cell'>
			<h3>Welcome to the HEMA Games Archive</h3>
		</div>

		
		<div class='cell shrink'>
			<img src='includes/images/GameOrDrill.png' size='100px'>
		</div>

		<div class='cell large-8' >
			<p>This is an online resource to help people store, and most importantly <u>find</u> training games for HEMA.</p>

			<p>The difference between a drill and a game is that in a game both participants are <b>actively trying to win</b>, and the rules are set in such a way that they explore the techniques and tactics that the curriculum is looking to develop. This means that any learning about how or when to do a technique takes into account incompliance and is not a result of someone feeding a stimulus (which may be more or less realistic/compliant).</p>

			<p>Games can be super simple:
			<br>- <i>We plant our feet, and stand stationary. If I can throw an attack faster than you can parry I win. If you parry you win.</i></p>

			<p>Or they can be full sparring scenarios with scoring tweaks to encourage a specific tactical or technical behavior:
			<br>- <i>'Deep' can only score with a hit to head or torso, 'Shallow' can only score with a hit to arms or leg.</i></p>

		</div>

		

		<div class='cell large-12' >
			<?=newsfeed()?>
		</div>



		<div class='cell callout large-3 medium-6'>
			# of games: <?=$stats['numGames']?>
		</div>
		
		<div class='cell callout large-3 medium-6'>
			# of game contributors: <?=$stats['numAuthors']?>
		</div>
		
		<div class='cell callout large-3 medium-6'>
			# of unique tags: <?=$stats['numTagTypes']?>
		</div>
		
		<div class='cell callout large-3 medium-6'>
			# of tags attached: <?=$stats['numTagsAttached']?>
		</div>


	</div>

	
<?php
include('includes/footer.php');


// FUNCTIONS ///////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
/******************************************************************************/

/******************************************************************************/

// END OF DOCUMENT /////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
