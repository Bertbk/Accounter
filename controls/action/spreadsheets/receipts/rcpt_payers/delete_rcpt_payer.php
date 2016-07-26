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
Check the data before asking the SQL to delete a rcpt_payer
The SQL should be done so that every dependent payments are also deleted
 */

require_once __DIR__.'/../../../../../config-app.php';

include_once(LIBPATH.'/accounts/get_account_admin.php');

include_once(LIBPATH.'/spreadsheets/receipts/rcpt_payers/get_rcpt_payer_by_hashid.php');
include_once(LIBPATH.'/spreadsheets/receipts/rcpt_payers/delete_rcpt_payer.php');

include_once(LIBPATH.'/hashid/validate_hashid.php');


//Session is used to send back errors to account.php (if any)
session_start();

$errArray = array(); //error messages
$warnArray = array(); //warning messages
$successArray = array(); //success messages
$redirect_link ="" ;

if(isset($_POST['submit_delete_rcpt_payer']))
{
	$ErrorEmptyMessage = array(
		'p_hashid_account' => 'No acount provided',
		'p_hashid_rcpt_payer' => 'No payer provided'
   );
	 
	$ErrorMessage = array(
		'p_hashid_account' => 'Account not valid',
		'p_hashid_rcpt_payer' => 'Payer not valid',
		'p_anchor' => 'Anchor not valid'
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

	//RECEIPT PAYER
	$key = 'p_hashid_rcpt_payer';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		if(validate_hashid($_POST[$key]) == false)
		{array_push($errArray, $ErrorMessage[$key]);}
	else{
		$hashid_rcpt_payer = $_POST[$key];		
		}
	}
	//Get the receipt payer
	if(empty($errArray))
	{		
		$rcpt_payer = get_rcpt_payer_by_hashid($account['id'], $hashid_rcpt_payer);
		if(empty($rcpt_payer))
		{	array_push($errArray, $ErrorMessage['p_hashid_rcpt_payer']); }
	}

	//Check if accounts match
	if(empty($errArray))
	{		
		if($rcpt_payer['account_id'] !== $account['id'])
		{	array_push($errArray, $ErrorMessage['Accounts mismatch']); }
	}
			
	//Delete the bill participant
	if(empty($errArray))
	{
		$success = delete_rcpt_payer($account['id'], $rcpt_payer['id']);	
		if($success !== true)
		{array_push($errArray, 'Server error: Problem while attempting to delete a payer'); 	}
		else
		{
			array_push($successArray, 'Payer has been successfully deleted');
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
		$key = 'p_anchor';
		if(isset($_POST[$key])) {
			$anchor = htmlspecialchars($_POST[$key]);
			$redirect_link = $redirect_link.$anchor ;
		}
	}
}

header('location: '.$redirect_link);
exit;