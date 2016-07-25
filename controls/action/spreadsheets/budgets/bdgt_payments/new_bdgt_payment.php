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
Check the data before asking the SQL to create a new payment
 */

 
require_once __DIR__.'/../../../../../config-app.php';

include_once(LIBPATH.'/accounts/get_account_admin.php');
include_once(LIBPATH.'/spreadsheets/get_spreadsheet_by_hashid.php');
include_once(LIBPATH.'/spreadsheets/budgets/bdgt_participants/get_bdgt_participant_by_hashid.php');
include_once(LIBPATH.'/spreadsheets/budgets/bdgt_payments/set_bdgt_payment.php');

include_once(LIBPATH.'/hashid/validate_hashid.php');
include_once(LIBPATH.'/hashid/create_hashid.php');


//Session is used to send back errors to account.php (if any)
session_start();

$errArray = array(); //error messages
$warnArray = array(); //warning messages
$successArray = array(); //success messages
$redirect_link ="" ;

if(isset($_POST['submit_new_payment']))
{
	$ErrorEmptyMessage = array(
		'p_hashid_account' => 'Please provide an acount',
		'p_hashid_spreadsheet' => 'Please provide a spreadsheet',
		'p_payment' => 'Please provide a payment',
		'p_hashid_creditor' => 'Please provide a creditor',
		'p_hashid_debtor' => 'Please provide a debtor',
		'p_amount' => 'Please provide an amount',
		'p_description' => 'Please provide a description',
		'p_type' => 'Please provide a type of payment',
		'p_date_of_payment' => 'Please provide a date of payment'
   );
	 
	$ErrorMessage = array(
		'p_hashid_account' => 'Account is not valid',
		'p_hashid_spreadsheet' => 'spreadsheet is not valid',
		'p_payment' => 'Payment is not valid',
		'p_hashid_creditor' => 'creditor is not valid',
		'p_hashid_debtor' => 'debtor is not valid',
		'p_amount' => 'Amount is not valid',
		'p_description' => 'Description is not valid',
		'p_date_of_payment' => 'Date of payment is not valid',
		'p_type' => 'Type of payment not valid',
		'p_anchor' => 'Anchor not valid'
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

	// spreadsheet
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
	
	// PAYMENTS (possibly multiples !)
	$key = 'p_payment';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	
	if(empty($errArray))
	{
//Loop now on every payments
	 foreach ($_POST['p_payment'] as $payment)
	 {
		$errArray2 = array(); // Error array for each payment
		//CREDITOR
		$key = 'p_hashid_creditor';
		 if(empty($payment[$key])) { //If empty
			array_push($errArray2, $ErrorEmptyMessage[$key]);
		}
		else{
			if(validate_hashid($payment[$key])== false)
			{
				array_push($errArray2, $ErrorMessage[$key]);
			}
			else{
				$hashid_creditor = $payment[$key];
				}
		}
		//Get the creditor
		if(empty($errArray2))
		{		
			$creditor = get_bdgt_participant_by_hashid($account['id'], $hashid_creditor);
			if(empty($creditor))
			{	array_push($errArray2, $ErrorMessage[$key]); }
		}
		
		//Number of real payment
		$n_debtors = (int)0;
		$debtors_id = Array();
		//TYPE OF PAYMENT (GROUP OR PARTICULAR)
		$key = 'p_type';
		if(empty($payment[$key])) { //If empty
			array_push($errArray2, $ErrorEmptyMessage[$key]);
		}
		else{
			if($payment[$key] == "group" || is_null($payment[$key]))
			{
				$type_payment = "group";
				$n_debtors = 1;
				$debtors_id[0] = null; //null if group (in SQL)
			}
			else if($payment[$key] == "p2p"){
				$type_payment = "p2p";
			}
			else{
				array_push($errArray2, $ErrorMessage[$key]);
			}
		}
		
		//debtorS (if payment from people to people)
		if(empty($errArray2)
			&& $type_payment == "p2p")
		{
			$key = 'p_hashid_debtor';
			foreach ($payment[$key] as $hashid_debtor)
			{
				if(validate_hashid($hashid_debtor)== false)
				{
					array_push($errArray2, $ErrorMessage[$key]);
				}
				else{
					$debtor = get_bdgt_participant_by_hashid($account['id'], $hashid_debtor);
					if(empty($debtor))
					{	array_push($errArray2, $ErrorMessage[$key]); }
					else{
						//This is a valid debtor !
						array_push($debtors_id, $debtor['id']);
						$n_debtors ++;
					}
				}
			}
		}

		// AMOUNT / amount
		if(empty($errArray2))
		{
			$key = 'p_amount';
			if(empty($payment[$key])) { //If empty
				array_push($errArray2, $ErrorEmptyMessage[$key]);
			}
			else{
				//Amount is divided by the number of debtors (=1 if total GROUP !)
				$amount = (float)((float)$payment[$key] / (float)$n_debtors);
				if($amount <= 0)
				{
					array_push($errArray2, $ErrorMessage[$key].' n_debtors = '.$n_debtors);
				}
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
			$myDateTime = DateTime::createFromFormat('d/m/Y', $date_of_payment);
			$date_of_payment = $myDateTime->format('Y-m-d');
			$date_parsed = date_parse($date_of_payment);
			if ($date_parsed == false || !checkdate($date_parsed['month'], $date_parsed['day'], $date_parsed['year'])) {
				array_push($warnArray, $WarningMessage[$key]);
				$date_of_payment = null;
			}
		}
		
		//For each local payment
		foreach ($debtors_id as $debtor_id)
		{
			if($debtor_id == $creditor['id'])
			{ continue;}
		
			$errArray3 = Array();

			//Hash id for the new payment
			$hashid_payment = "";
			if(empty($errArray3))
			{	
				$hashid_payment = create_hashid();
				if(is_null($hashid_payment))
					{ array_push($errArray3, "Server error: problem while creating hashid.");}
			}
			
			//Check if the accounts and spreadsheets match
			if(empty($errArray3))
			{
				if($creditor['account_id'] !== $account['id'])
				{
					array_push($errArray3, 'This creditor does not belong to this account.');
				}
				if($creditor['spreadsheet_id'] !== $spreadsheet['id'])
				{
					array_push($errArray3, 'This creditor does not belong to this spreadsheet.');
				}
				if(!is_null($debtor_id) && $debtor['account_id'] !== $account['id'])
				{
					array_push($errArray3, 'This debtor does not belong to this account.');
				}
				if(!is_null($debtor_id) && $debtor['spreadsheet_id'] !== $spreadsheet['id'])
				{
					array_push($errArray3, 'This debtor does not belong to this spreadsheet.');
				}
				if(!is_null($debtor_id) && $debtor['spreadsheet_id'] !== $creditor['spreadsheet_id'])
				{
					array_push($errArray3, 'creditor and debtor do not belong to the same spreadsheet.');
				}
			}

			//Save the payment
			if(empty($errArray3))
			{
				$success = set_payment($account['id'], $hashid_payment, $spreadsheet['id'], $creditor['id'], $amount, $debtor_id, $description, $date_of_payment);	
				if($success !== true)
				{array_push($errArray3, 'Server error: Problem while attempting to add a payment'); 	}
			else
				{
					array_push($successArray, 'Payment has been successfully added');
				}
			}
			//Merge the errors
			if(!empty($errArray3))
			{
				$errArray2 = array_merge($errArray2, $errArray3);
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