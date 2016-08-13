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
Check the data before asking the SQL to create a new spreadsheet
 */

 
require_once __DIR__.'/../../../config-app.php';

include_once(LIBPATH.'/accounts/get_account_admin.php');

include_once(LIBPATH.'/spreadsheets/set_spreadsheet.php');
include_once(LIBPATH.'/spreadsheets/get_spreadsheets.php');
include_once(LIBPATH.'/spreadsheets/get_spreadsheet_by_title.php');

include_once(LIBPATH.'/hashid/validate_hashid.php');
include_once(LIBPATH.'/hashid/create_hashid.php');


//Session is used to send back errors to account.php (if any)
session_start();

$errArray = array(); //error messages
$warnArray = array(); //warning messages
$successArray = array(); //success messages
$redirect_link ="" ;

if(isset($_POST['submit_new_spreadsheet']))
{
	$ErrorEmptyMessage = array(
		'p_hashid_account' => 'No acount provided',
		'p_title_of_spreadsheet' => 'Please provide a title',
		'p_type' => 'Please provide a type'
		);
	 
	$ErrorMessage = array(
		'p_hashid_account' => 'Account is not valid',
		'p_title_of_spreadsheet' => 'Title is not valid',
		'p_description' => 'Description is not valid',
		'p_type' => 'Type is not valid',
		'p_anchor' => 'Anchor not valid'
   );

	//Manual treatments of arguments
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
		{	array_push($errArray, $ErrorMessage['p_hashid_account']); }
	}

	
	$key = 'p_title_of_spreadsheet';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		$title_of_spreadsheet = $_POST[$key];
	}
	
	$key = 'p_type';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		if($_POST[$key] !== "budget"
		&& $_POST[$key] !== "receipt")
		{
			array_push($errArray, $ErrorMessage[$key]);
		}
		else{
			$type_of_spreadsheet = $_POST[$key];
		}
	}

	$key = 'p_description';
	if(!empty($_POST[$key]))
	{
		$desc = $_POST[$key];
	}
	else{$desc = null;}
	
	//Hash id for the new spreadsheet
	$hashid_spreadsheet = "";
	if(empty($errArray))
	{	
		$hashid_spreadsheet = create_hashid();
		if(is_null($hashid_spreadsheet))
			{ array_push($errArray, "Server error: problem while creating hashid.");}
	}

	//Check if two spreadsheets have the same title
	if(empty($errArray))
	{
		$does_this_spreadsheet_exists = get_spreadsheet_by_title($account['id'], $title_of_spreadsheet);
		if(!empty($does_this_spreadsheet_exists))
		{array_push($errArray, 'Another spreadsheet has the same title'); 	}
		}

	//Save the spreadsheet
	if(empty($errArray))
	{
		$success = set_spreadsheet($account['id'], $hashid_spreadsheet, $type_of_spreadsheet, $title_of_spreadsheet, $desc);
		if($success !== true)
		{array_push($errArray, 'Server error: Problem while attempting to add a spreadsheet'); 	}
		else
			{
				array_push($successArray, 'spreadsheet has been successfully added');
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

if(!isset($account) ||empty($account))
{
	$redirect_link = BASEURL;
}
else{
	$redirect_link = BASEURL.'/account/'.$account['hashid_admin'].'/admin';
	//Anchor
	if(empty($errArray))
	{
		$n_spreadsheets = count(get_spreadsheets($account['id'])) - 1;
		$anchor = '#spreadsheet-'.$n_spreadsheets;
		$redirect_link = $redirect_link.$anchor;
	}
}

header('location: '.$redirect_link);
exit;