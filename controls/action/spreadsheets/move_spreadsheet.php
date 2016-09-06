<?php 
/**
 * This software is governed by the CeCILL-B license. If a copy of this license
 * is not distributed with this file, you can obtain one at
 * http://www.cecill.info/licences/Licence_CeCILL-B_V1-en.txt
 *
 * Author of Accounter: Bertrand THIERRY (bertrand.thierry1@gmail.com)
 *
 */
 
 /*
Move the spreadsheet
 */

 
require_once __DIR__.'/../../../config-app.php';

require_once(LIBPATH.'/accounts/get_account_admin.php');

require_once(LIBPATH.'/spreadsheets/get_spreadsheets.php');
require_once(LIBPATH.'/spreadsheets/get_spreadsheet_by_hashid.php');
require_once(LIBPATH.'/spreadsheets/upgrade_spreadsheet_rank.php');
require_once(LIBPATH.'/spreadsheets/downgrade_spreadsheet_rank.php');


require_once(LIBPATH.'/hashid/validate_hashid.php');


//Session is used to send back errors to account.php (if any)
session_start();

$errArray = array(); //error messages
$warnArray = array(); //warning messages
$successArray = array(); //success messages
$redirect_link ="" ;


$ErrorEmptyMessage = array(
	'p_hashid_account' => 'Please provide an acount',
	'p_hashid_spreadsheet' => 'Please provide a spreadsheet',
	'p_move' => 'Please provide a move',
 );
 
$ErrorMessage = array(
	'p_hashid_account' => 'Account is not valid',
	'p_hashid_spreadsheet' => 'Participant is not valid',
	'p_move' => 'Move is not valid'
 );

//ACCOUNT
$key = 'p_hashid_account';
if(empty($_POST[$key])) { //If empty
	array_push($errArray, $ErrorEmptyMessage[$key]);
}
else{
	if(validate_hashid_admin($_POST[$key])== false)
	{
		array_push($errArray, $ErrorMessage[$key]);
	}
	else{
		$hashid_admin = $_POST[$key];
		}
}
//Get the account
if(empty($errArray))
{		
	$account = get_account_admin($hashid_admin);
	if(empty($account))
	{	array_push($errArray, $ErrorMessage[$key]); }
}

if(empty($account))
{
	$redirect_link = BASEURL;
}
else{
	$redirect_link = BASEURL.'/account/'.$account['hashid_admin'].'/admin';
}

if(isset($_POST['submit_cancel']))
{
	header('location:'.$link_to_account_admin);
	exit;
}
else if(isset($_POST['submit_move']))
{
	//spreadsheet
	$key = 'p_hashid_spreadsheet';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		if(validate_hashid($_POST[$key])== false)
		{
			array_push($errArray, $ErrorMessage[$key]);
		}
		else{
			$hashid_spreadsheet = $_POST[$key];
			}
	}
	//Get the spreadsheet
	if(empty($errArray))
	{		
		$spreadsheet = get_spreadsheet_by_hashid($account['id'], $hashid_spreadsheet);
		if(empty($spreadsheet))
		{	array_push($errArray, $ErrorMessage[$key]); }
	}
	
	//Check if accounts match
	if(empty($errArray))
	{		
		if($spreadsheet['account_id'] !== $account['id'])
		{	array_push($errArray, 'Accounts mismatch.'); }
	}

	//Get the move (up or down)
	$key = 'p_move';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		if($_POST[$key] == 'up'
		|| $_POST[$key] == 'down')
		{
			$move = $_POST[$key];
		}
		else{
			array_push($errArray, $ErrorMessage[$key]);
		}
	}
	
	//Is it ok to move ?
	if(empty($errArray))
	{	
		$my_rank = (int)$spreadsheet['rank'];
		$all_spreadsheets = get_spreadsheets($account['id']);
		$max_rank = (int)count($all_spreadsheets) - 1;
		if($move == 'up')
		{
			if($my_rank > 0)
			{
				$my_rank = (int)$my_rank - 1;
				upgrade_spreadsheet_rank($account['id'], $spreadsheet['id']);
			}
			else{
				array_push($errArray, $ErrorMessage[$key]);		
			}
		}
		if($move == 'down')
		{
			if($my_rank < $max_rank)
			{
				$my_rank = (int)$my_rank + 1;
				downgrade_spreadsheet_rank($account['id'], $spreadsheet['id']);
			}
			else{
				array_push($errArray, $ErrorMessage[$key]);		
			}
		}
		$redirect_link = $redirect_link.'#spreadsheet-'.(int)$my_rank;
	}
}

		
if(!(empty($errArray)))
{
	$_SESSION['errors'] = $errArray;
}
if(!(empty($warnArray)))
{
	$_SESSION['warnings'] = $warnArray;
}
if(!(empty($successArray)))
{
	$_SESSION['success'] = $successArray;
}

header('location: '.$redirect_link);
exit;