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
Check the data before asking the SQL to create a new receipt
 */

 
require_once __DIR__.'/../../config-app.php';

include_once(LIBPATH.'/accounts/get_account_admin.php');

include_once(LIBPATH.'/receipts/set_receipt.php');
include_once(LIBPATH.'/receipts/get_receipt_by_title.php');

include_once(LIBPATH.'/hashid/validate_hashid.php');
include_once(LIBPATH.'/hashid/create_hashid.php');


//Session is used to send back errors to account.php (if any)
session_start();

$errArray = array(); //error messages
$warnArray = array(); //warning messages
$successArray = array(); //success messages
$redirect_link ="" ;

if(isset($_POST['submit_new_receipt']))
{
	$ErrorEmptyMessage = array(
		'p_hashid_account' => 'No acount provided',
		'p_title_of_receipt' => 'Please provide a title'
		);
	 
	$ErrorMessage = array(
		'p_hashid_account' => 'Account is not valid',
		'p_title_of_receipt' => 'Title is not valid',
		'p_description' => 'Description is not valid',
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

	
	$key = 'p_title_of_receipt';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		$title_of_receipt = $_POST[$key];
	}
	
	$key = 'p_description';
	if(!empty($_POST[$key]))
	{
		$desc = $_POST[$key];
	}
	else{$desc = null;}
	
	//Hash id for the new receipt
	$hashid_receipt = "";
	if(empty($errArray))
	{	
		$hashid_receipt = create_hashid();
		if(is_null($hashid_receipt))
			{ array_push($errArray, "Server error: problem while creating hashid.");}
	}

	//Check if two receipts have the same title
	if(empty($errArray))
	{
		$does_this_receipt_exists = get_receipt_by_title($account['id'], $title_of_receipt);
		if(!empty($does_this_receipt_exists))
		{array_push($errArray, 'Another receipt has the same title'); 	}
		}

	//Save the receipt
	if(empty($errArray))
	{
		$success = set_receipt($account['id'], $hashid_receipt, $title_of_receipt, $desc);	
		if($success !== true)
		{array_push($errArray, 'Server error: Problem while attempting to add a receipt '.$success); 	}
		else
			{
				array_push($successArray, 'Receipt has been successfully added');
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
	$key = 'p_anchor';
	if(isset($_POST[$key])) {
		$anchor = htmlspecialchars($_POST[$key]);
		$redirect_link = $redirect_link.$anchor ;
	}
}

header('location: '.$redirect_link);
exit;