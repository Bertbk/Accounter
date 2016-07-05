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
Check the data before asking the SQL to update a bill
 */

 
require_once __DIR__.'/../../config-app.php';

include_once(LIBPATH.'/accounts/get_account_admin.php');

include_once(LIBPATH.'/bills/get_bill_by_hashid.php');
include_once(LIBPATH.'/bills/get_bill_by_title.php');
include_once(LIBPATH.'/bills/update_bill.php');

include_once(LIBPATH.'/hashid/validate_hashid.php');


//Session is used to send back errors to account.php (if any)
session_start();

$errArray = array(); //error messages
$warnArray = array(); //warning messages
$successArray = array(); //success messages
$redirect_link ="" ;


$ErrorEmptyMessage = array(
	'p_hashid_account' => 'Please provide an acount',
	'p_hashid_bill' => 'Please provide a bill',
	'p_title_of_bill' => 'Please provide a title',
 );
 
$ErrorMessage = array(
	'p_hashid_account' => 'Account is not valid',
	'p_hashid_bill' => 'Participant is not valid',
	'p_title_of_bill' => 'Title is not valid',
	'p_description' => 'Description is not valid',
	'p_cpt_bill' => 'Counter of bill not valid'
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
else if(isset($_POST['submit_update_bill']))
{
	//BILL
	$key = 'p_hashid_bill';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		if(validate_hashid($_POST[$key])== false)
		{
			array_push($errArray, $ErrorMessage[$key]);
		}
		else{
			$hashid_bill = $_POST[$key];
			}
	}
	//Get the bill
	if(empty($errArray))
	{		
		$bill = get_bill_by_hashid($account['id'], $hashid_bill);
		if(empty($bill))
		{	array_push($errArray, $ErrorMessage[$key]); }
	}
	
	//Check if accounts match
	if(empty($errArray))
	{		
		if($bill['account_id'] !== $account['id'])
		{	array_push($errArray, 'Accounts mismatch.'); }
	}

	//Get the (new) title of bill
	$key = 'p_title_of_bill';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		$new_title_of_bill = $_POST[$key];
	}

	//New description
	$key = 'p_description';
	if(!empty($_POST[$key]))
	{
		$new_description = $_POST[$key];
	}
	else{$new_description = null;}

	
	//Check if two bills have the same name
	if(empty($errArray) && $new_title_of_bill !== $bill['name'])
	{
		$does_this_bill__exists = get_bill_by_title($account['id'], $new_title_of_bill);
		if(!empty($does_this_guy_exists))
		{array_push($errArray, 'Another bill has the same title'); 	}
	}
	
	//Anchor
	$key='p_cpt_bill';
	if(empty($errArray) && !empty($_POST[$key]))
	{
		$cpt_bill = (int)$_POST[$key];
		$redirect_link = $redirect_link.'#bill-'.$cpt_bill;		
	}
	
	//Save the bill
	if(empty($errArray))
	{
		$success = update_bill($account['id'], $bill['id'], $new_title_of_bill, $new_description);	
		if(!$success)
		{array_push($errArray, 'Server error: Problem while attempting to update a bill'); 	}
	else
		{
			array_push($successArray, 'Bill has been successfully updated');
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