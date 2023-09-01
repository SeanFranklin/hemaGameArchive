<?php 
/*******************************************************************************
	Event Selection
	
	Select which event to use
	Login:
		- SUPER ADMIN can see hidden events
	
*******************************************************************************/

// INITIALIZATION //////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

$pageName = "Log In";

include('includes/header.php');



// PAGE DISPLAY ////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
?>

	<?php if($_SESSION['userID'] == 0): ?>
		<?=logInForm()?>

	<?php else: ?>
		
		Logged in as: <b><?=getUserName($_SESSION['userID'])?>.</b><BR>
		<form method='POST' name='logOutForm2' id='page-log-out-form'>
			<input type='hidden' name='formName' value='logOut'>
			<a onclick="$('#page-log-out-form').submit()">Log Out</a>	
		</form>

		<HR>

		<?=newsfeed()?>

		<HR>

		<?=changePasswordList()?>
		<?=addNewUserInput()?>
	<?php endif ?>

<?php
include('includes/footer.php');

// FUNCTIONS ///////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

/******************************************************************************/

function logInForm(){
?>
	<div class='grid-x grid-margin-x'>
	<div id='login-form' class='small-12 medium-6 large-4 cell'>

	<form method="POST">
	

		<input type='hidden'>

		<label>
			<span>Account Name</span>
			<input type='text' class='large-6' name='logIn[userAccount]' required>
		</label>

		<label>
			<span>Password</span>
			<input type='password' name='logIn[password]'>
		</label>

		<button class='button success'  name='formName' value='logIn'>
			Log In
		</button>

	
	</form>

	</div>
	</div>

<?php
}

/******************************************************************************/

function changePasswordForm($userInfo){

	$userID = (int)$userInfo['userID'];

	if($_SESSION['userID'] == $userID){
		$currentPassText = "Old Password";
		$newPassText = "New Password";
	} elseif(ALLOW['ADMIN'] == true) {
		$currentPassText = "Password for ".getUserName($_SESSION['userID']);
		$newPassText = "New Password for ".getUserName($userID);
	} else {
		return;
	}

	$formClass = "changePasswordFor-".$userID;
	$name = getUserName($userID);
	$hasPassword = doesUserHavePassword($_SESSION['userID']);

?>

	<span class='cell <?=$formClass?>'>
		<?=$userInfo['userName']?> (<?=$userInfo['userAccount']?>) - 
		<a class='<?=$formClass?>' data-open="<?=$formClass?>">Change Password</a>
	</span>

	<div class='reveal medium' id='<?=$formClass?>' data-reveal>
		
		<h4><?=$userInfo['userName']?> (<?=$userInfo['userAccount']?>) </h4>
		<HR>

		<form method="POST" >
		<input type='hidden' name='changePassword[userID]' value=<?=$userID?>>

			<div class='grid-x grid-margin-x'>
		
			<?php if($hasPassword == true): ?>
			<div class='cell'>
				<span><?=$currentPassText?></span>
				<input type='password' name='changePassword[passwordOld]' required>
			</div>
			<?php else: ?>
				<input type='hidden' name='changePassword[passwordOld]' value=''>
			<?php endif ?>

			<div class='cell'>
				<span><?=$newPassText?>:</span>
				<input type='password' name='changePassword[password]' required>
			</div>

			<div class='cell'>
				<span><?=$newPassText?> (Again):</span>
				<input type='password' name='changePassword[password2]' required>
			</div>


			<button class='button success no-bottom cell large-6'  name='formName' value='changePassword'>
				Change Password
			</button>

			<a class='button secondary no-bottom  cell large-6' data-close>
				Nah, let's not
			</a>

		</div>
		</form>

		<!-- Reveal close button -->
		<button class='close-button' data-close aria-label='Close modal' type='button'>
			<span aria-hidden='true'>&times;</span>
		</button>
	</div>



<?php
}

/******************************************************************************/

function changePasswordList(){

	if(ALLOW['ADMIN'] == true){
		$userList = getUserList();
		$class = "hidden";
	} else {
		$userList[0] = getUserInfo($_SESSION['userID']);
		$class = "";
	}

?>

	<HR class="<?=$class?> manage-users">

	<div class='grid-x grid-margin-x'>
	<div class='large-7 cell'>

	<?php if(ALLOW['ADMIN'] == true):?>
		<h3><a onclick="$('.manage-users').toggleClass('hidden')">Manage Users</a></h3>
	<?php endif ?>

	<table class="<?=$class?> manage-users">

		<?php if(ALLOW['ADMIN'] == true):?>
		<tr>
			<td class='text-right' colspan="100%">
				hasPassword | CAN_ADD | CAN_EDIT | CAN_ADMIN
			</td>
		</tr>	
		<?php endif ?>

	<?php foreach($userList as $u):

		?>
		<tr>
			<td>
				<?=changePasswordForm($u)?>
			</td>
			<?php if(ALLOW['ADMIN'] == true): 
				$setupStr = '';
				$setupClass = '';
				if($u['hasPassword'] == true){ 
					$setupStr .= '✓'; 
					$setupClass ='bold';
				}else{ 
					$setupStr .= '_'; 
					$setupClass ='grey-text';
				}
				if($u['CAN_ADD'] == true){ $setupStr .= '✓'; }else{ $setupStr .= '_'; }
				if($u['CAN_EDIT'] == true){ $setupStr .= '✓'; }else{ $setupStr .= '_'; }
				if($u['CAN_ADMIN'] == true){ $setupStr .= '✓'; }else{ $setupStr .= '_'; }
				?>

				<td class='<?=$setupClass?>'>
					<?=$setupStr?>
				</td>

			<?php endif ?>
		</tr>
	<?php endforeach ?>

	</table>

	<HR class="<?=$class?> manage-users">

	</div>
	</div>

<?php
}

/******************************************************************************/

function addNewUserInput(){

	if(ALLOW['ADMIN'] == false){
		return;
	}

?>

	<div class='grid-x grid-margin-x'>
	<div class='large-7 cell'>

	<HR class="hidden add-user">

	<h3><a onclick="$('.add-user').toggleClass('hidden')">Add New User</a></h3>

	<form method="POST" class="hidden add-user">
	<table  >
	
		<tr>
			<td>userAccount</td>
			<td><input class='text' name='addNewUser[userAccount]' required></td>
		</tr><tr>
			<td>userName</td>
			<td><input class='text' name='addNewUser[userName]' required></td>
		</tr><tr>
			<td>ADD</td>
			<td><?=checkboxPaddle('addNewUser[CAN_ADD]',1,false,0)?></td>
		</tr><tr>
			<td>EDIT</td>
			<td><?=checkboxPaddle('addNewUser[CAN_EDIT]',1,false,0)?></td>
		</tr><tr>
			<td>ADMIN</td>
			<td><?=checkboxPaddle('addNewUser[CAN_ADMIN]',1,false,0)?></td>
		</tr>		
	</table>

	<button class='button success no-bottom' name='formName' value='addNewUser'>
		Create New User
	</button>
	
	</form>

	<HR class="hidden add-user">
	
	
	</div>
	</div>

<?php
}


/******************************************************************************/

// END OF DOCUMENT /////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
