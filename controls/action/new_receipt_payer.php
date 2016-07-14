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
Check the data before asking the SQL to assign a participant to a receipt
 */
 
require_once __DIR__.'/../../config-app.php';

include_once(LIBPATH.'/accounts/get_account_admin.php');

include_once(LIBPATH.'/participants/get_participant_by_hashid.php');

include_once(LIBPATH.'/receipts/get_receipt_by_hashid.php');

include_once(LIBPATH.'/receipt_payers/get_receipt_payers_by_receipt_id.php');
include_once(LIBPATH.'/receipt_payers/set_receipt_payer.php');

include_once(LIBPATH.'/hashid/validate_hashid.php');
include_once(LIBPATH.'/hashid/create_hashid.php');


//Session is used to send back errors to account.php (if any)
session_start();

$errArray = array(); //error messages
$warnArray = array(); //warning messages
$successArray = array(); //success messages
$redirect_link ="" ;

if(isset($_POST['submit_new_receipt_payer']))
{
	$ErrorEmptyMessage = array(
		'p_hashid_account' => 'Please provide an acount',
		'p_hashid_receipt' => 'Please provide a receipt',
		'p_participant' => 'Please provide a participant',
		'p_hashid_participant' => 'Please provide a participant',
		'p_percent_of_payment' => 'Please provide a percentage'
   );
	 
	$ErrorMessage = array(
		'p_hashid_account' => 'Account is not valid',
		'p_hashid_receipt' => 'Receipt is not valid',
		'p_participant' => 'Participant is not valid',
		'p_hashid_participant' => 'Participant is not valid',
		'p_percent_of_payment' => 'Percent is not valid',
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

	// Receipt
	$key = 'p_hashid_receipt';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		if(validate_hashid($_POST[$key])== false)
		{
			array_push($errArray, $ErrorMessage[$key]);
		}
		else{
			$hashid_receipt = $_POST[$key];
			}
	}
	//Get the receipt
	if(empty($errArray))
	{		
		$receipt = get_receipt_by_hashid($account['id'], $hashid_receipt);
		if(empty($receipt))
		{	array_push($errArray, $ErrorMessage['p_hashid_receipt']); }
	}
	
	//Check if the accounts match between receipt and account
	if(empty($errArray))
	{
		if($receipt['account_id'] !== $account['id'])
		{
			array_push($errArray, 'This receipt does not belong to this account.');
		}
	}
	
	// PARTICIPANT (possibly multiples !)
	$key = 'p_participant';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	
	if(empty($errArray))
	{
//Loop now on every participants
	 foreach ($_POST['p_participant'] as $particip)
	 {
		$errArray2 = array(); // Error array for each participant
		$key = 'p_hashid_participant';
		 if(empty($particip[$key])) { //If empty
			continue;
			//array_push($errArray2, $ErrorEmptyMessage[$key]);
		}
		else{
			if(validate_hashid($particip[$key])== false)
			{
				array_push($errArray2, $ErrorMessage[$key]);
			}
			else{
				$hashid_participant = $particip[$key];
				}
		}
		//Get the participant
		if(empty($errArray2))
		{		
			$participant = get_participant_by_hashid($account['id'], $hashid_participant);
			if(empty($participant))
			{	array_push($errArray2, $ErrorMessage['p_hashid_participant']); }
		}
		
		// PERCENT OF PAYMENT
		$key = 'p_percent_of_payment';
		if(!isset($particip[$key])) { //If empty
			array_push($errArray2, $ErrorEmptyMessage[$key]);
		}
		else{
			$percent_of_payment = (float)$particip[$key];
			if($percent_of_payment < 0 
				|| $percent_of_payment > 100)
			{
				array_push($errArray2, $ErrorMessage[$key].': '.$percent_of_payment);
			}
		}
		
		//Hash id for the new receipt_payer
		$hashid_receipt_payer = "";
		if(empty($errArray2))
		{	
			$hashid_receipt_payer = create_hashid();
			if(is_null($hashid_receipt_payer))
				{ array_push($errArray2, "Server error: problem while creating hashid.");}
		}

		
		//Check if the accounts match
		if(empty($errArray2))
		{
			if($participant['account_id'] !== $account['id'])
			{
				array_push($errArray2, 'This participant does not belong to this account.');
			}
			if($participant['account_id'] !== $receipt['account_id'])
			{
				array_push($errArray2, 'Participant and receipt do not belong to the same account');
			}
		}
		
		//Check if the receipt_payer is not already affected to the receipt
		if(empty($errArray2))
		{
			$registred_receipt_part = get_receipt_payers_by_receipt_id($account['id'], $receipt['id']);
			$current_percent = $percent_of_payment;
			foreach ($registred_receipt_part as $receipt_part)
			{
					if($receipt_part['participant_id'] == $participant['id'])
					{
						{array_push($errArray2, 'Payer already registred!'); 	}
					}
					$current_percent += $receipt_part['percent_of_payment'];
			}
		}
		
		if(empty($errArray2))
		{
			if($current_percent > 100)
			{
				array_push($errArray2, 'Percent of payment > 100% !');
			}
			if($current_percent <= 0)
			{
				array_push($errArray2, 'Percent of payment <= 0% !');
		}
		
		//Save the receipt_payer
		if(empty($errArray2))
		{
			$success = set_receipt_payer($account['id'], $hashid_receipt_payer, $receipt['id'], $participant['id'], $percent_of_payment);	
			if($success !== true)
			{array_push($errArray2, 'Server error: Problem while attempting to add a payer'); 	}
			else
			{
				array_push($successArray, 'Payer has been successfully added');
			}
		}
		//Merge the errors
		if(!empty($errArray2))
		{
			$errArray = array_merge($errArray, $errArray2);
		}
	 }//Loop on participant

 }//If statement
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
		$key = 'p_anchor';
		if(isset($_POST[$key])) {
			$anchor = htmlspecialchars($_POST[$key]);
			$redirect_link = $redirect_link.$anchor ;
		}
	}
}
header('location: '.$redirect_link);
exit;