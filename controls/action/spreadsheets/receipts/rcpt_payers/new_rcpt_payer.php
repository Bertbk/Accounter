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
Check the data before asking the SQL to assign a member to a spreadsheet
 */
 
require_once __DIR__.'/../../../../../config-app.php';

include_once(LIBPATH.'/accounts/get_account_admin.php');

include_once(LIBPATH.'/members/get_member_by_hashid.php');

include_once(LIBPATH.'/spreadsheets/get_spreadsheet_by_hashid.php');

include_once(LIBPATH.'/spreadsheets/receipts/get_rcpt_percents.php');

include_once(LIBPATH.'/spreadsheets/receipts/rcpt_payers/get_rcpt_payers_by_spreadsheet_id.php');
include_once(LIBPATH.'/spreadsheets/receipts/rcpt_payers/get_rcpt_payer_by_member_id.php');
include_once(LIBPATH.'/spreadsheets/receipts/rcpt_payers/set_rcpt_payer.php');

include_once(LIBPATH.'/hashid/validate_hashid.php');
include_once(LIBPATH.'/hashid/create_hashid.php');

//Session is used to send back errors to account.php (if any)
session_start();

$errArray = array(); //error messages
$warnArray = array(); //warning messages
$successArray = array(); //success messages
$redirect_link ="" ;

if(isset($_POST['submit_new_rcpt_payer']))
{
	$ErrorEmptyMessage = array(
		'p_hashid_account' => 'Please provide an acount',
		'p_hashid_spreadsheet' => 'Please provide a spreadsheet',
		'p_payer' => 'Please provide a payer',
		'p_hashid_member' => 'Please provide a member',
		'p_percent_of_payment' => 'Please provide a percentage'
   );
	 
	$ErrorMessage = array(
		'p_hashid_account' => 'Account is not valid',
		'p_hashid_spreadsheet' => 'Spreadsheet is not valid',
		'p_payer' => 'Payer is not valid',
		'p_hashid_member' => 'Member is not valid',
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
	
	// member (possibly multiples !)
	$key = 'p_payer';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	
	if(empty($errArray))
	{
//Loop now on every members
	 foreach ($_POST['p_payer'] as $particip)
	 {
		$errArray2 = array(); // Error array for each member
		$key = 'p_hashid_member';
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
				$hashid_member = $particip[$key];
				}
		}
		//Get the member
		if(empty($errArray2))
		{		
			$member = get_member_by_hashid($account['id'], $hashid_member);
			if(empty($member))
			{	array_push($errArray2, $ErrorMessage[$key]); }
		}
		
		// PERCENT OF PAYMENT
		$key = 'p_percent_of_payment';
		if(!isset($particip[$key])) { //If empty
			array_push($errArray2, $ErrorEmptyMessage[$key]);
		}
		else{
			$percent_of_payment = (float)$particip[$key];
			$spreadsheet_percent = (float)get_rcpt_percents($account['id'], $spreadsheet['id']);
			if($percent_of_payment < 0 
				|| ($percent_of_payment + $spreadsheet_percent) > 100)
			{
				array_push($errArray2, $ErrorMessage[$key]);
			}
		}
		
		//Hash id for the new rcpt_payer
		$hashid_rcpt_payer = "";
		if(empty($errArray2))
		{	
			$hashid_rcpt_payer = create_hashid();
			if(is_null($hashid_rcpt_payer))
				{ array_push($errArray2, "Server error: problem while creating hashid.");}
		}

		
		//Check if the accounts match
		if(empty($errArray2))
		{
			if($member['account_id'] !== $account['id'])
			{
				array_push($errArray2, 'This member does not belong to this account.');
			}
			if($member['account_id'] !== $spreadsheet['account_id'])
			{
				array_push($errArray2, 'member and spreadsheet do not belong to the same account');
			}
		}
		
		//Check if the rcpt_payer is not already affected to the spreadsheet
		if(empty($errArray2))
		{
			$registred_spreadsheet_part = get_rcpt_payer_by_member_id($account['id'], $spreadsheet['id'], $member['id']);
			if(!empty($registred_spreadsheet_part))
			{
				{array_push($errArray2, 'Payer already registred!'); 	}
			}
		}
		
		//Save the rcpt_payer
		if(empty($errArray2))
		{
			$success = set_rcpt_payer($account['id'], $hashid_rcpt_payer, $spreadsheet['id'], $member['id'], $percent_of_payment);	
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
	 }//Loop on member
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