<?php 
require_once __DIR__.'/../../config-app.php';

include_once(LIBPATH.'/accounts/get_account_admin.php');

include_once(LIBPATH.'/bills/get_bill_by_hashid.php');

include_once(LIBPATH.'/bill_participants/get_bill_participant_by_hashid.php');

include_once(LIBPATH.'/payments/get_payment_by_hashid.php');
include_once(LIBPATH.'/payments/update_payment.php');


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
	'p_hashid_bill' => 'Please provide a bill',
	'p_hashid_payment' => 'Please provide a payment',
	'p_hashid_payer' => 'Please provide a payer',
	'p_hashid_recv' => 'Please provide a receiver',
	'p_cost' => 'Please provide a cost',
	'p_description' => 'Please provide a description',
	'p_date_of_payment' => 'Please provide a date of payment'
 );
 
$ErrorMessage = array(
	'p_hashid_account' => 'Account is not valid',
	'p_hashid_bill' => 'Bill is not valid',
	'p_hashid_payment' => 'Payment is not valid',
	'p_hashid_payer' => 'Payer is not valid',
	'p_hashid_recv' => 'Receiver is not valid',
	'p_cost' => 'Cost is not valid',
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
 
if(empty($account))
{
	$redirect_link = BASEURL;
}
else{
	$redirect_link = BASEURL.'/account/'.$account['hashid_admin'].'/admin';
} 
 

if(isset($_POST['submit_cancel']))
{
	header('location: '.$redirect_link);
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
		$payment = get_payment_by_hashid($account['id'], $hashid_payment);
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
	
	//Check if the accounts match between bill and account
	if(empty($errArray))
	{
		if($bill['account_id'] !== $account['id'])
		{
			array_push($errArray, 'This bill does not belong to this account.');
		}
	}
	
	//PAYER
	$key = 'p_hashid_payer';
	 if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		if(validate_hashid($_POST[$key])== false)
		{
			array_push($errArray, $ErrorMessage[$key]);
		}
		else{
			$hashid_payer = $_POST[$key];
			}
	}
	//Get the payer
	if(empty($errArray))
	{		
		$payer = get_bill_participant_by_hashid($account['id'], $hashid_payer);
		if(empty($payer))
		{	array_push($errArray, $ErrorMessage[$key]); }
	}
	
	//RECEIVER
	$key = 'p_hashid_recv';
	 if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		if($_POST[$key] == -1 || is_null($_POST[$key]))
		{
			$receiver_id = null; //Group
		}
		else {
			if(validate_hashid($_POST[$key])== false)
			{
				array_push($errArray, $ErrorMessage[$key]);
			}
			else{
				$hashid_recv = $_POST[$key];
			}
		}
	}
	//Get the receiver
	if(empty($errArray) && ($_POST[$key] != -1 && !is_null($_POST[$key])))
	{		
		$receiver = get_bill_participant_by_hashid($account['id'], $hashid_recv);
		if(empty($receiver))
		{	array_push($errArray, $ErrorMessage[$key]); }
		$receiver_id = $receiver['id'];
	}
	
	// COST
	$key = 'p_cost';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		$cost = (float)$_POST[$key];
		if($cost <= 0)
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
	
	//Check if the accounts and bills match
	if(empty($errArray))
	{
		if($payer['account_id'] !== $account['id'])
		{
			array_push($errArray, 'This payer does not belong to this account.');
		}
		if($payer['bill_id'] !== $bill['id'])
		{
			array_push($errArray, 'This payer does not belong to this bill.');
		}
		if(!is_null($receiver_id) && $receiver['account_id'] !== $account['id'])
		{
			array_push($errArray, 'This receiver does not belong to this account.');
		}
		if(!is_null($receiver_id) && $receiver['bill_id'] !== $bill['id'])
		{
			array_push($errArray, 'This receiver does not belong to this bill.');
		}
		if(!is_null($receiver_id) && $receiver['bill_id'] !== $payer['bill_id'])
		{
			array_push($errArray, 'Payer and receiver do not belong to the same bill.');
		}
	}
	
	//Update the payment
	if(empty($errArray))
	{
		$success = update_payment($account['id'], $bill['id'], $payment['id'], $payer['id'], $cost, $receiver_id, $description, $date_of_payment);	
		if(!$success)
		{array_push($errArray, 'Server error: Problem while attempting to update a payment'); 	}
	else
		{
			array_push($successArray, 'Payment has been successfully updated');
		}
	}
	//Merge the errors
	if(!empty($errArray))
	{
		$errArray = array_merge($errArray, $errArray);
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