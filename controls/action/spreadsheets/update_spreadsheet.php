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
Check the data before asking the SQL to update a spreadsheet
 */

 
require_once __DIR__.'/../../config-app.php';

include_once(LIBPATH.'/accounts/get_account_admin.php');

include_once(LIBPATH.'/spreadsheets/get_spreadsheet_by_hashid.php');
include_once(LIBPATH.'/spreadsheets/get_spreadsheet_by_title.php');
include_once(LIBPATH.'/spreadsheets/update_spreadsheet.php');

include_once(LIBPATH.'/hashid/validate_hashid.php');


//Session is used to send back errors to account.php (if any)
session_start();

$errArray = array(); //error messages
$warnArray = array(); //warning messages
$successArray = array(); //success messages
$redirect_link ="" ;


$ErrorEmptyMessage = array(
	'p_hashid_account' => 'Please provide an acount',
	'p_hashid_spreadsheet' => 'Please provide a spreadsheet',
	'p_title_of_spreadsheet' => 'Please provide a title',
 );
 
$ErrorMessage = array(
	'p_hashid_account' => 'Account is not valid',
	'p_hashid_spreadsheet' => 'Participant is not valid',
	'p_title_of_spreadsheet' => 'Title is not valid',
	'p_description' => 'Description is not valid',
	'p_anchor' => 'Anchor not valid'
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
else if(isset($_POST['submit_update_spreadsheet']))
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

	//Get the (new) title of spreadsheet
	$key = 'p_title_of_spreadsheet';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		$new_title_of_spreadsheet = $_POST[$key];
	}

	//New description
	$key = 'p_description';
	if(!empty($_POST[$key]))
	{
		$new_description = $_POST[$key];
	}
	else{$new_description = null;}

	
	//Check if two spreadsheets have the same name
	if(empty($errArray) && $new_title_of_spreadsheet !== $spreadsheet['name'])
	{
		$does_this_spreadsheet__exists = get_spreadsheet_by_title($account['id'], $new_title_of_spreadsheet);
		if(!empty($does_this_guy_exists))
		{array_push($errArray, 'Another spreadsheet has the same title'); 	}
	}
	
	//Anchor
	if(empty($errArray))
	{		
		$key = 'p_anchor';
		if(isset($_POST[$key])) {
			$anchor = htmlspecialchars($_POST[$key]);
			$redirect_link = $redirect_link.$anchor ;
		}
	}
	
	//Save the spreadsheet
	if(empty($errArray))
	{
		$success = update_spreadsheet($account['id'], $spreadsheet['id'], $new_title_of_spreadsheet, $new_description);	
		if($success !== true)
		{array_push($errArray, 'Server error: Problem while attempting to update a spreadsheet'); 	}
	else
		{
			array_push($successArray, 'spreadsheet has been successfully updated');
		}
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