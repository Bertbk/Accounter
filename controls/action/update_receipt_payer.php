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
Check the data before asking the SQL to update a receipt_payer (= participation)
 */


require_once __DIR__.'/../../config-app.php';

include_once(LIBPATH.'/accounts/get_account_admin.php');

include_once(LIBPATH.'/participants/get_participant_by_hashid.php');
include_once(LIBPATH.'/participants/get_participant_by_id.php');

include_once(LIBPATH.'/receipts/get_receipt_by_id.php');

include_once(LIBPATH.'/receipt_payers/get_receipt_payer_by_hashid.php');
include_once(LIBPATH.'/receipt_payers/get_receipt_payers_by_receipt_id.php');
include_once(LIBPATH.'/receipt_payers/update_receipt_payer.php');

include_once(LIBPATH.'/hashid/validate_hashid.php');


//Session is used to send back errors to account.php (if any)
session_start();

$errArray = array(); //error messages
$warnArray = array(); //warning messages
$successArray = array(); //success messages
$redirect_link ="" ;

$ErrorEmptyMessage = array(
		'p_hashid_account' => 'Please provide an acount',
		'p_hashid_receipt_payer' => 'Please provide a payer',
		'p_participant' => 'Please provide a participant',
		'p_percent_of_payment' => 'Please provide a percentage'
   );
	 
$ErrorMessage = array(
	'p_hashid_account' => 'Account is not valid',
	'p_hashid_receipt_payer' => 'Payer is not valid',
	'p_participant' => 'Participant is not valid',
	'p_percent_of_payment' => 'Percent is not valid',
	'p_anchor' => 'Anchor not valid'
 );


//Get the account
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

//REDIRECTION LINK
if(!isset($account) ||empty($account))
{
	$redirect_link = BASEURL;
}
else{
	$redirect_link = BASEURL.'/account/'.$account['hashid_admin'].'/admin';
	//Anchor
	if(empty($errArray))
	{		
		$key = 'p_anchor';
		if(isset($_POST[$key])) {
			$anchor = htmlspecialchars($_POST[$key]);
			$redirect_link = $redirect_link.$anchor ;
		}
	}
}

if(isset($_POST['submit_update_receipt_payer']))
{
	// receipt_payer
	$key = 'p_hashid_receipt_payer';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		if(validate_hashid($_POST[$key])== false)
		{
			array_push($errArray, $ErrorMessage[$key]);
		}
		else{
			$hashid_receipt_payer = $_POST[$key];
			}
	}
	//Get the receipt_payer
	if(empty($errArray))
	{		
		$receipt_payer = get_receipt_payer_by_hashid($account['id'], $hashid_receipt_payer);
		if(empty($receipt_payer))
		{	array_push($errArray, $ErrorMessage[$key]); }
	}
	
	//Check if the accounts match between receipt and account
	if(empty($errArray))
	{
		if($receipt_payer['account_id'] !== $account['id'])
		{
			array_push($errArray, 'This participation does not belong to this account.');
		}
	}
			
	// NEW PERCENT OF PAYMENT
	$key = 'p_percent_of_payment';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		$new_percent_of_payment = (float)$_POST[$key];
		if($new_percent_of_payment < 0 ||$new_percent_of_payment > 100)
		{
			array_push($errArray, $ErrorMessage['p_percent_of_payment']);
		}
	}
	
	//Check if the sum of percentage of payment is still acceptable
	if(empty($errArray))
	{
		$registred_receipt_part = get_receipt_payers_by_receipt_id($account['id'], $receipt['id']);
		$current_percent = $new_percent_of_payment;
		foreach ($registred_receipt_part as $receipt_part)
		{
			if($receipt_part['participant_id'] == $participant['id'])
			{
				continue;
			}
			$current_percent += $receipt_part['percent_of_payment'];
		}
	}

	if(empty($errArray))
	{
		if($current_percent > 100)
		{
			array_push($errArray, 'Percent of payment > 100% !');
		}
		if($current_percent <= 0)
		{
			array_push($errArray, 'Percent of payment <= 0% !');
		}
	}

	
	//Update the receipt_payer
	if(empty($errArray))
	{
		$success = update_receipt_payer($account['id'], $receipt_payer['id'], $new_percent_of_payment);	
		if($success !== true)
		{array_push($errArray, 'Server error: Problem while attempting to update a participation'); 	}
	else
		{
			array_push($successArray, 'Participant has been successfully updated');
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