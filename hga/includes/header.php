<?php
/*******************************************************************************

		
*******************************************************************************/

include_once('includes/config.php');

$vJ = '?=0.0.1'; // Javascript Version
$vC = '?=0.0.2'; // CSS Version
?>

<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<script>
	<?php
		// Output base URL of site for Javascript use
		$b = BASE_URL;
		echo "var BASE_URL = '$b';";
	?>
</script>

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="
		HEMA Games Archive
	">
	<meta name="keywords" content="stuff">
    <title>HEMA Game Archive</title>
    
<link href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.4.3/css/foundation.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.foundation.min.css">
    
    <link href="https://fonts.googleapis.com/css?family=Chivo:300,400,700" rel="stylesheet">
    <link rel="stylesheet" href="includes/foundation/css/app.css">
    <link rel="stylesheet" href="includes/foundation/css/custom.css<?=$vC?>">

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script>google.charts.load('current', {'packages':['corechart']});</script>

    <link rel='icon' href='includes\images\favicon.png'>
    
    <!-- Jumps to section on page if $_SESSION['jumpTo'] is set -->
	<?php if(isset($_SESSION['jumpTo'])): ?>
		<script>window.onload = window.location.hash='<?=$_SESSION['jumpTo']?>';</script>
		<?php unset($_SESSION['jumpTo']); ?>
	<?php endif ?>
    
    <?php if(isset($refreshPageTimer) == true && (int)$refreshPageTimer != 0):?>
    	<meta http-equiv="refresh" content="<?=(int)$refreshPageTimer?>">
    <?php endif ?>
</head>

<!---------------------------------------------------------------------------------------------------------->
<!---------------------------------------------------------------------------------------------------------->
<!---------------------------------------------------------------------------------------------------------->
	
<body>
 <!-- START Upper Navigation ------------------------------------------>
 
	<?php debugging(); ?>

	<!-- Mobile Navigation -->
    <div class="title-bar" data-responsive-toggle="tourney-animated-menu" data-hide-for="large" style='display:none'>
		<form method='POST' name='logOutForm1' id='logOutForm1'>
        <button class="menu-icon" type="button" data-toggle></button>
        <div class="title-bar-title">Menu</div>
        <?php if($_SESSION['userID'] == 0): ?>
			<a href='adminLogIn.php' class='login-link'>Login</a>
		<?php else: ?>
			<input type='hidden' name='formName' value='logOut'>
			<a onclick="$('#logOutForm1').submit()" class='login-link'>Log Out</a>	
		<?php endif ?>
		</form>
    </div>
    
    <!-- Full Navigation -->
    <div class="top-bar" id="tourney-animated-menu" data-animate="hinge-in-from-top hinge-out-from-top" style='display:none'>
        <div class="top-bar-left">
            <ul class="dropdown menu vertical medium-horizontal" data-dropdown-menu>
            	<?=navigationList()?>
				
            </ul>
           
        </div>
        <div class="top-bar-right show-for-large">
			<?php if($_SESSION['userID'] == 0): ?>
				<a href='adminLogIn.php' style='color:white'>Login</a>
			<?php else: ?>
				<form method='POST' name='logOutForm2' id='logOutForm2'>
				<input type='hidden' name='formName' value='logOut'>
				<a onclick="$('#logOutForm2').submit()" style='color:white'>Log Out</a>
				</form>	
			<?php endif ?>
		</div>
        
    </div>
    <!-- END Upper Navigation ----------------------------------------->	
	
	<!-- START Page Title --------------------------------------------->
	<div class='hero-title'>
		<h1 class='no-bottom'>HEMA Game Archive</h1>
		<h2 class='no-bottom'><?=$pageName?></h2>
	</div>
	<BR>
	<!-- END Page Title ----------------------------------------------->
	
	<div id='page-wrapper' class='grid-container'>
		
	<?php
	
	displayPageAlerts();
	askForTagMeta();


// FUNCTIONS //////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/******************************************************************************/

function navigationList(){
?>
	<li><a href='gameList.php'>Game List</a></li>

	<?php if(ALLOW['ADD'] == true):?>
		<li><a href='gameAdd.php'>Add Game</a></li>
	<?php endif ?>

	<?php if(ALLOW['EDIT'] == true):?>
		<li><a href='tagList.php'>Edit Tags</a></li>
	<?php endif ?>

	<li><a href='infoAbout.php'>Help/About</a></li>
<?
}

/******************************************************************************/

function debugging(){

	if(defined("SHOW_POST") && SHOW_POST === true){

		if(isset($_SESSION['urlNav']) && defined("SHOW_URL_NAV") && SHOW_URL_NAV === true){

			echo "---- URL_NAV ----------------------------------------------------";
			show($_SESSION['urlNav']);

		} else {
			echo "---- POST -------------------------------------------------------";
			show($_SESSION['post']);
		}
	}

	unset($_SESSION['post']);
	unset($_SESSION['urlNav']);

	if(defined("SHOW_SESSION") && SHOW_SESSION === true){
		echo "---- SESSION ----------------------------------------------------";
		show($_SESSION);
	}
	
}

/******************************************************************************/

// END OF FILE /////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
