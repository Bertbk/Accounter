<?php 
require_once __DIR__.'/../../config-app.php';

include_once(LIBPATH.'/accounts/get_account_admin.php');

include_once(LIBPATH.'/bills/get_bill_by_hashid.php');

include_once(LIBPATH.'/bill_participants/get_bill_participant_by_hashid.php');

include_once(LIBPATH.'/payments/set_payment.php');


include_once(LIBPATH.'/hashid/validate_hashid.php');
include_once(LIBPATH.'/hashid/create_hashid.php');


//Session is used to send back errors to account.php (if any)
session_start();

$errArray = array(); //error messages
$warnArray = array(); //error messages
$redirect_link ="" ;

if(isset($_POST['submit_new_payment']))
{
	$ErrorEmptyMessage = array(
		'p_hashid_account' => 'Please provide an acount',
		'p_hashid_bill' => 'Please provide a bill',
		'p_payment' => 'Please provide a payment',
		'p_hashid_payer' => 'Please provide a payer',
		'p_hashid_recv' => 'Please provide a receiver',
		'p_cost' => 'Please provide a cost',
		'p_description' => 'Please provide a description',
		'p_date_of_payment' => 'Please provide a date of payment'
   );
	 
	$ErrorMessage = array(
		'p_hashid_account' => 'Account is not valid',
		'p_hashid_bill' => 'Bill is not valid',
		'p_payment' => 'Payment is not valid',
		'p_hashid_payer' => 'Payer is not valid',
		'p_hashid_recv' => 'Receiver is not valid',
		'p_cost' => 'Cost is not valid',
		'p_description' => 'Description is not valid',
		'p_date_of_payment' => 'Date of payment is not valid'
   );
	 
	$WaningMessage = array(
		'p_date_of_payment' => 'Date of payment is not valid'
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
		{	array_push($errArray, $ErrorMessage[$key]); }
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
	
	// BILL_PARTICIPANT (possibly multiples !)
	$key = 'p_payment';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	
	if(empty($errArray))
	{
//Loop now on every payments
	 foreach ($_POST['p_payment'] as $payment)
	 {
		$errArray2 = array(); // Error array for each participant
		//PAYER
		$key = 'p_hashid_payer';
		 if(empty($payment[$key])) { //If empty
			array_push($errArray2, $ErrorEmptyMessage[$key]);
		}
		else{
			if(validate_hashid($payment[$key])== false)
			{
				array_push($errArray2, $ErrorMessage[$key]);
			}
			else{
				$hashid_payer = $payment[$key];
				}
		}
		//Get the payer
		if(empty($errArray2))
		{		
			$payer = get_bill_participant_by_hashid($account['id'], $hashid_payer);
			if(empty($payer))
			{	array_push($errArray2, $ErrorMessage[$key]); }
		}
		
		//RECEIVER
		$key = 'p_hashid_recv';
		 if(empty($payment[$key])) { //If empty
			array_push($errArray2, $ErrorEmptyMessage[$key]);
		}
		else{
			if($payment[$key] == -1 || is_null($payment[$key]))
			{
				$receiver_id = null; //Group
			}
			else {
				if(validate_hashid($payment[$key])== false)
				{
					array_push($errArray2, $ErrorMessage[$key]);
				}
				else{
					$hashid_recv = $payment[$key];
				}
			}
		}
		//Get the receiver
		if(empty($errArray2) && ($payment[$key] != -1 || !is_null($payment[$key])))
		{		
			$receiver = get_bill_participant_by_hashid($account['id'], $hashid_recv);
			if(empty($receiver))
			{	array_push($errArray2, $ErrorMessage[$key]); }
			$receiver_id = $receiver['id'];
		}
		
		// COST
		$key = 'p_cost';
		if(empty($payment[$key])) { //If empty
			array_push($errArray2, $ErrorEmptyMessage[$key]);
		}
		else{
			$cost = (float)$payment[$key];
			if($cost <= 0)
			{
				array_push($errArray2, $ErrorMessage[$key]);
			}
		}
		
		// DESCRIPTION
		$key = 'p_description';
		if(empty($payment[$key])) { //If empty
			$description = null;
		}
		else{
			$description = $payment[$key];
		}
		
		// DATE
		$key = 'p_date_of_payment';
		if(empty($payment[$key])) { //If empty
			$date_of_payment = null;
		}
		else{
			$date_of_payment = $payment[$key];
			$date_of_payment = str_replace('/', '-',$date_of_payment);
			$date_parsed = date_parse($date_of_payment);
			if ($date_parsed == false || !checkdate($date_parsed['month'], $date_parsed['day'], $date_parsed['year'])) {
				array_push($warnArray, $WarningMessage[$key]);
				$date_of_payment = null;
			}
		}
		
		//Hash id for the new payment
		$hashid_payment = "";
		if(empty($errArray2))
		{	
			$hashid_payment = create_hashid();
			if(is_null($hashid_payment))
				{ array_push($errArray2, "Server error: problem while creating hashid.");}
		}

		
		//Check if the accounts and bills match
		if(empty($errArray2))
		{
			if($payer['account_id'] !== $account['id'])
			{
				array_push($errArray2, 'This payer does not belong to this account.');
			}
			if($payer['bill_id'] !== $bill['id'])
			{
				array_push($errArray2, 'This payer does not belong to this bill.');
			}
			if(!is_null($receiver['id']) && $receiver['account_id'] !== $account['id'])
			{
				array_push($errArray2, 'This receiver does not belong to this account.');
			}
			if(!is_null($receiver['id']) && $receiver['bill_id'] !== $bill['id'])
			{
				array_push($errArray2, 'This receiver does not belong to this bill.');
			}
			if(!is_null($receiver['id']) && $receiver['bill_id'] !== $payer['bill_id'])
			{
				array_push($errArray2, 'Payer and receiver do not belong to the same bill.');
			}
		}

		//Save the payment
		if(empty($errArray2))
		{
			$success = set_payment($account['id'], $hashid_payment, $bill['id'], $payer['id'], $cost, $receiver_id, $description, $date_of_payment);	
			if(!$success)
			{array_push($errArray2, 'Server error: Problem while attempting to add a payment'); 	}
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
	$_SESSION['warnings'] = $warnArray;
}

if(empty($account))
{
	$redirect_link = BASEURL;
}
else{
	$redirect_link = BASEURL.'/account/'.$account['hashid_admin'].'/admin';
}
header('location: '.$redirect_link);