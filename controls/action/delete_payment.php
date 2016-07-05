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
Check the data before asking the SQL to delete a payment
 */

 
require_once __DIR__.'/../../config-app.php';

include_once(LIBPATH.'/accounts/get_account_admin.php');

include_once(LIBPATH.'/payments/get_payment_by_hashid.php');
include_once(LIBPATH.'/payments/delete_payment.php');

include_once(LIBPATH.'/hashid/validate_hashid.php');


//Session is used to send back errors to account.php (if any)
session_start();

$errArray = array(); //error messages
$warnArray = array(); //warning messages
$successArray = array(); //success messages
$redirect_link ="" ;

if(isset($_POST['submit_delete_payment']))
{
	$ErrorEmptyMessage = array(
		'p_hashid_account' => 'No acount provided',
		'p_hashid_payment' => 'No payment provided'
   );
	 
	$ErrorMessage = array(
		'p_hashid_account' => 'Account not valid',
		'p_hashid_payment' => 'Payment not valid',
		'p_cpt_bill' => 'Counter of bill not valid'
   );

	//ACCOUNT
	$key = 'p_hashid_account';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		if(validate_hashid_admin($_POST[$key])== false)
		{array_push($errArray, $ErrorMessage[$key]);}
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

	//payment
	$key = 'p_hashid_payment';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		if(validate_hashid($_POST[$key]) == false)
		{array_push($errArray, $ErrorMessage[$key]);}
	else{
		$hashid_payment = $_POST[$key];		
		}
	}
	//Get the payment
	if(empty($errArray))
	{		
		$payment = get_payment_by_hashid($account['id'], $hashid_payment);
		if(empty($payment))
		{	array_push($errArray, $ErrorMessage['p_hashid_payment']); }
	}

	//Check if accounts match
	if(empty($errArray))
	{		
		if($payment['account_id'] !== $account['id'])
		{	array_push($errArray, $ErrorMessage['Accounts mismatch']); }
	}
	
	//Delete the participant
	if(empty($errArray))
	{
		$success = delete_payment($account['id'], $payment['id']);	
		if(!$success)
		{array_push($errArray, 'Server error: Problem while attempting to delete a payment'); 	}
			else
			{
				array_push($successArray, 'Payment has been successfully deleted');
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