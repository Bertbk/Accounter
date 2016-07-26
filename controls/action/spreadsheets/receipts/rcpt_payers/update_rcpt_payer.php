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
Check the data before asking the SQL to update a rcpt_payer (= participation)
 */


require_once __DIR__.'/../../../../../config-app.php';

include_once(LIBPATH.'/accounts/get_account_admin.php');

include_once(LIBPATH.'/spreadsheets/get_spreadsheet_by_hashid.php');

include_once(LIBPATH.'/spreadsheets/receipts/get_rcpt_percents.php');

include_once(LIBPATH.'/spreadsheets/receipts/rcpt_payers/get_rcpt_payer_by_hashid.php');
include_once(LIBPATH.'/spreadsheets/receipts/rcpt_payers/get_rcpt_payers_by_spreadsheet_id.php');
include_once(LIBPATH.'/spreadsheets/receipts/rcpt_payers/update_rcpt_payer.php');

include_once(LIBPATH.'/hashid/validate_hashid.php');


//Session is used to send back errors to account.php (if any)
session_start();

$errArray = array(); //error messages
$warnArray = array(); //warning messages
$successArray = array(); //success messages
$redirect_link ="" ;

$ErrorEmptyMessage = array(
		'p_hashid_account' => 'Please provide an acount',
		'p_hashid_spreadsheet' => 'Please provide a spreadsheet',
		'p_hashid_rcpt_payer' => 'Please provide a payer',
		'p_percent_of_payment' => 'Please provide a percentage'
   );
	 
$ErrorMessage = array(
	'p_hashid_account' => 'Account is not valid',
	'p_hashid_spreadsheet' => 'Spreadsheet is not valid',
	'p_hashid_rcpt_payer' => 'Payer is not valid',
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

if(isset($_POST['submit_update_rcpt_payer']))
{
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
	
	// rcpt_payer
	$key = 'p_hashid_rcpt_payer';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		if(validate_hashid($_POST[$key])== false)
		{
			array_push($errArray, $ErrorMessage[$key]);
		}
		else{
			$hashid_rcpt_payer = $_POST[$key];
			}
	}
	//Get the rcpt_payer
	if(empty($errArray))
	{		
		$rcpt_payer = get_rcpt_payer_by_hashid($account['id'], $hashid_rcpt_payer);
		if(empty($rcpt_payer))
		{	array_push($errArray, $ErrorMessage[$key]); }
	}
	
	//Check if the accounts match between receipt and account
	if(empty($errArray))
	{
		if($rcpt_payer['account_id'] !== $account['id'])
		{
			array_push($errArray, 'This payer does not belong to this account.');
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
			array_push($errArray, $ErrorMessage[$key]);
		}
	}
	
	//Check if the sum of percentage of payment is still acceptable
	if(empty($errArray))
	{
		$current_percent = (float)get_rcpt_percents($account['id'], $spreadsheet['id']) - (float)$rcpt_payer['percent_of_payment'];
		
		if(($current_percent + $new_percent_of_payment) > 100)
		{
			array_push($errArray, 'Total percentage is higher than 100%');
		}
	}
	
	//Update the rcpt_payer
	if(empty($errArray))
	{
		$success = update_rcpt_payer($account['id'], $spreadsheet['id'], $rcpt_payer['id'], $new_percent_of_payment);	
		if($success !== true)
		{array_push($errArray, 'Server error: Problem while attempting to update a payer'); 	}
	else
		{
			array_push($successArray, 'Payer has been successfully updated');
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