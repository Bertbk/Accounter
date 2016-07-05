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
Check the data before asking the SQL to create a new bill
 */

 
require_once __DIR__.'/../../config-app.php';

include_once(LIBPATH.'/accounts/get_account_admin.php');

include_once(LIBPATH.'/bills/set_bill.php');
include_once(LIBPATH.'/bills/get_bill_by_title.php');

include_once(LIBPATH.'/hashid/validate_hashid.php');
include_once(LIBPATH.'/hashid/create_hashid.php');


//Session is used to send back errors to account.php (if any)
session_start();

$errArray = array(); //error messages
$warnArray = array(); //warning messages
$successArray = array(); //success messages
$redirect_link ="" ;

if(isset($_POST['submit_new_bill']))
{
	$ErrorEmptyMessage = array(
		'p_hashid_account' => 'No acount provided',
		'p_title_of_bill' => 'Please provide a title'
		);
	 
	$ErrorMessage = array(
		'p_hashid_account' => 'Account is not valid',
		'p_title_of_bill' => 'Title is not valid',
		'p_description' => 'Description is not valid',
		'p_cpt_bill' => 'Counter of bill not valid'
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

	
	$key = 'p_title_of_bill';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		$title_of_bill = $_POST[$key];
	}
	
	$key = 'p_description';
	if(!empty($_POST[$key]))
	{
		$desc = $_POST[$key];
	}
	else{$desc = null;}
	
	//Hash id for the new bill
	$hashid_bill = "";
	if(empty($errArray))
	{	
		$hashid_bill = create_hashid();
		if(is_null($hashid_bill))
			{ array_push($errArray, "Server error: problem while creating hashid.");}
	}

	//Check if two bills have the same title
	if(empty($errArray))
	{
		$does_this_bill_exists = get_bill_by_title($account['id'], $title_of_bill);
		if(!empty($does_this_bill_exists))
		{array_push($errArray, 'Another bill has the same title'); 	}
		}

	//Save the bill
	if(empty($errArray))
	{
		$success = set_bill($account['id'], $hashid_bill, $title_of_bill, $desc);	
		if(!$success)
		{array_push($errArray, 'Server error: Problem while attempting to add a bill'); 	}
		else
			{
				array_push($successArray, 'Bill has been successfully added');
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
		$key = 'p_cpt_bill';
		if(!empty($_POST[$key])) {
			$cpt_bill = (int) $_POST[$key];
			$redirect_link = $redirect_link.'#bill-'.$cpt_bill ;
		}
	}
}

header('location: '.$redirect_link);
exit;