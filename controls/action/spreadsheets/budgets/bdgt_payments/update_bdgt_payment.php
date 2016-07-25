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
Action launched when the form "Cancel" has been called (when editing).
Redirect to the admin page of an account.
 */
 
 require_once __DIR__.'/../../../../../config-app.php';

include_once(LIBPATH.'/accounts/get_account_admin.php');

include_once(LIBPATH.'/spreadsheets/get_spreadsheet_by_hashid.php');

include_once(LIBPATH.'/spreadsheets/budgets/bdgt_participants/get_bdgt_participant_by_hashid.php');
include_once(LIBPATH.'/spreadsheets/budgets/bdgt_payments/get_bdgt_payment_by_hashid.php');
include_once(LIBPATH.'/spreadsheets/budgets/bdgt_payments/update_bdgt_payment.php');

include_once(LIBPATH.'/hashid/validate_hashid.php');
include_once(LIBPATH.'/hashid/create_hashid.php');


//Session is used to send back errors to account.php (if any)
session_start();

$errArray = array(); //error messages
$warnArray = array(); //warning messages
$successArray = array(); //success messages
$redirect_link ="" ;

$ErrorEmptyMessage = array(
	'p_hashid_account' => 'Please provide an acount',
	'p_hashid_spreadsheet' => 'Please provide a spreadsheet',
	'p_hashid_payment' => 'Please provide a payment',
	'p_hashid_creditor' => 'Please provide a creditor',
	'p_hashid_debtor' => 'Please provide a debtor',
	'p_amount' => 'Please provide a amount',
	'p_description' => 'Please provide a description',
	'p_date_of_payment' => 'Please provide a date of payment',
	'p_anchor' => 'Anchor not valid'
 );
 
$ErrorMessage = array(
	'p_hashid_account' => 'Account is not valid',
	'p_hashid_payment' => 'Payment is not valid',
	'p_hashid_creditor' => 'Creditor is not valid',
	'p_hashid_debtor' => 'Debtor is not valid',
	'p_amount' => 'Amount is not valid',
	'p_description' => 'Description is not valid',
	'p_date_of_payment' => 'Date of payment is not valid'
 );
 
$WaningMessage = array(
	'p_date_of_payment' => 'Date of payment is not valid'
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
 

if(isset($_POST['submit_cancel']))
{
	header('location: '.$redirect_link);
	exit;
}
else if(isset($_POST['submit_update_payment']))
{
	// CURRENT PAYMENT
	$key = 'p_hashid_payment';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		if(validate_hashid($_POST[$key])== false)
		{
			array_push($errArray, $ErrorMessage[$key]);
		}
		else{
			$hashid_payment = $_POST[$key];
			}
	}
	//Get the payment
	if(empty($errArray))
	{		
		$payment = get_bdgt_payment_by_hashid($account['id'], $hashid_payment);
		if(empty($payment))
		{	array_push($errArray, $ErrorMessage[$key]); }
	}
	
	//Check if the accounts match between payment and account
	if(empty($errArray))
	{
		if($payment['account_id'] !== $account['id'])
		{
			array_push($errArray, 'This payment does not belong to this account.');
		}
	}	
	
	// BILL
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
	
	//Check if the accounts match between spreadsheet and account
	if(empty($errArray))
	{
		if($spreadsheet['account_id'] !== $account['id'])
		{
			array_push($errArray, 'This spreadsheet does not belong to this account.');
		}
	}
	
	//Creditor
	$key = 'p_hashid_creditor';
	 if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		if(validate_hashid($_POST[$key])== false)
		{
			array_push($errArray, $ErrorMessage[$key]);
		}
		else{
			$hashid_creditor = $_POST[$key];
			}
	}
	//Get the creditor
	if(empty($errArray))
	{		
		$creditor = get_bdgt_participant_by_hashid($account['id'], $hashid_creditor);
		if(empty($creditor))
		{	array_push($errArray, $ErrorMessage[$key]); }
	}
	
	//Debtor
	$key = 'p_hashid_debtor';
	 if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		if($_POST[$key] == -1 || is_null($_POST[$key]))
		{
			$debtor_id = null; //Group
		}
		else {
			if(validate_hashid($_POST[$key])== false)
			{
				array_push($errArray, $ErrorMessage[$key]);
			}
			else{
				$hashid_debtor = $_POST[$key];
			}
		}
	}
	//Get the debtor
	if(empty($errArray) && ($_POST[$key] != -1 && !is_null($_POST[$key])))
	{		
		$debtor = get_bdgt_participant_by_hashid($account['id'], $hashid_debtor);
		if(empty($debtor))
		{	array_push($errArray, $ErrorMessage[$key]); }
		$debtor_id = $debtor['id'];
	}
	
	// COST
	$key = 'p_amount';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		$amount = (float)$_POST[$key];
		if($amount <= 0)
		{
			array_push($errArray, $ErrorMessage[$key]);
		}
	}
	
	// DESCRIPTION
	$key = 'p_description';
	if(empty($_POST[$key])) { //If empty
		$description = null;
	}
	else{
		$description = $_POST[$key];
	}
	
	// DATE
	$key = 'p_date_of_payment';
	if(empty($_POST[$key])) { //If empty
		$date_of_payment = null;
	}
	else{
		$date_of_payment = $_POST[$key];
		$myDateTime = DateTime::createFromFormat('d/m/Y', $date_of_payment);
		$date_of_payment = $myDateTime->format('Y-m-d');
		$date_parsed = date_parse($date_of_payment);
		if ($date_parsed == false || !checkdate($date_parsed['month'], $date_parsed['day'], $date_parsed['year'])) {
			array_push($warnArray, $WarningMessage[$key]);
			$date_of_payment = null;
		}
	}
	
	//Check if the accounts and spreadsheets match
	if(empty($errArray))
	{
		if($creditor['account_id'] !== $account['id'])
		{
			array_push($errArray, 'This creditor does not belong to this account.');
		}
		if($creditor['spreadsheet_id'] !== $spreadsheet['id'])
		{
			array_push($errArray, 'This creditor does not belong to this spreadsheet.');
		}
		if(!is_null($debtor_id) && $debtor['account_id'] !== $account['id'])
		{
			array_push($errArray, 'This debtor does not belong to this account.');
		}
		if(!is_null($debtor_id) && $debtor['spreadsheet_id'] !== $spreadsheet['id'])
		{
			array_push($errArray, 'This debtor does not belong to this spreadsheet.');
		}
		if(!is_null($debtor_id) && $debtor['spreadsheet_id'] !== $creditor['spreadsheet_id'])
		{
			array_push($errArray, 'Creditor and debtor do not belong to the same spreadsheet.');
		}
	}
	
	//Update the payment
	if(empty($errArray))
	{
		$success = update_bdgt_payment($account['id'], $spreadsheet['id'], $payment['id'], $creditor['id'], $amount, $debtor_id, $description, $date_of_payment);	
		if($success !== true)
		{array_push($errArray, 'Server error: Problem while attempting to update a payment'); 	}
	else
		{
			array_push($successArray, 'Payment has been successfully updated');
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