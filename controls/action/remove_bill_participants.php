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
Check the data before asking the SQL to delete every payments of a bill
 */

 require_once __DIR__.'/../../config-app.php';

include_once(LIBPATH.'/accounts/get_account_admin.php');
include_once(LIBPATH.'/accounts/delete_account.php');

include_once(LIBPATH.'/bills/get_bill_by_hashid.php');

include_once(LIBPATH.'/bill_participants/get_bill_participants_by_bill_id.php');
include_once(LIBPATH.'/bill_participants/delete_bill_participant.php');

include_once(LIBPATH.'/hashid/validate_hashid.php');


//Session is used to send back errors to account.php (if any)
session_start();

$errArray = array(); //error messages
$warnArray = array(); //warning messages
$successArray = array(); //success messages
$redirect_link ="" ;

if(isset($_POST['submit_remove_all_participations']))
{
	$ErrorEmptyMessage = array(
		'p_hashid_account' => 'No acount provided',
		'p_hashid_bill' => 'No bill provided'
   );
	 
	$ErrorMessage = array(
		'p_hashid_account' => 'Account not valid',
		'p_hashid_bill' => 'Bill not valid',
		'p_cpt_bill' => 'Bill counter not valid'
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
		{	array_push($errArray, $ErrorMessage[$key]); }
	}

	//BILL
	$key = 'p_hashid_bill';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		if(validate_hashid($_POST[$key])== false)
		{array_push($errArray, $ErrorMessage[$key]);}
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
	
	//Check if bill belongs to the account
	if(empty($errArray))
	{
		if($bill['account_id'] !== $account['id'])
		{
			array_push($errArray, "This bill does not belong to this account!");
		}		
	}
		
	if(empty($errArray))
	{
		$bill_participants = get_bill_participants_by_bill_id($account['id'], $bill['id']);
		//Delete the participants
		foreach($bill_participants as $bill_part)
		{
			$success = delete_bill_participant($account['id'], $bill_part['id']);	
			if($success === true)
				{	array_push($successArray, 'Participation has been successfully deleted');}
			else
				{array_push($errArray, 'Server error: Problem while attempting to delete a participation: '.$success); 	}
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

if(!isset($account) || empty($account))
{
	$redirect_link = BASEURL;
}
else{
	$redirect_link = BASEURL.'/account/'.$account['hashid_admin'].'/admin';
	//Anchor
	$key='p_cpt_bill';
	if(empty($errArray) && isset($_POST[$key]))
	{
		$cpt_bill = (int)$_POST[$key];
		$redirect_link = $redirect_link.'#bill-'.$cpt_bill;		
	}
}

header('location: '.$redirect_link);
exit;