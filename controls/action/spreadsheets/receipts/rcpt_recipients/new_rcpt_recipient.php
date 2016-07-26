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

include_once(LIBPATH.'/spreadsheets/receipts/rcpt_articles/get_rcpt_article_by_hashid.php');
include_once(LIBPATH.'/spreadsheets/receipts/rcpt_articles/get_rcpt_article_quantities_taken.php');

include_once(LIBPATH.'/spreadsheets/receipts/rcpt_recipients/get_rcpt_recipients_by_article_id.php');
include_once(LIBPATH.'/spreadsheets/receipts/rcpt_recipients/set_rcpt_recipient.php');
include_once(LIBPATH.'/spreadsheets/receipts/rcpt_recipients/get_rcpt_recipient_by_member_id.php');


include_once(LIBPATH.'/hashid/validate_hashid.php');
include_once(LIBPATH.'/hashid/create_hashid.php');


//Session is used to send back errors to account.php (if any)
session_start();

$errArray = array(); //error messages
$warnArray = array(); //warning messages
$successArray = array(); //success messages
$redirect_link ="" ;

if(isset($_POST['submit_new_rcpt_recipient']))
{
	$ErrorEmptyMessage = array(
		'p_hashid_account' => 'Please provide an acount',
		'p_hashid_spreadsheet' => 'Please provide a spreadsheet',
		'p_recipient' => 'Please provide a recipient',
		'p_hashid_member' => 'Please provide a member',
		'p_hashid_article' => 'Please provide an article',
		'p_quantity' => 'Please provide a quantity'
   );
	 
	$ErrorMessage = array(
		'p_hashid_account' => 'Account is not valid',
		'p_hashid_spreadsheet' => 'spreadsheet is not valid',
		'p_recipient' => 'Recipient is not valid',
		'p_hashid_member' => 'Member is not valid',
		'p_hashid_article' => 'Article is not valid',
		'p_quantity' => 'Quantity is not valid',
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
		{	array_push($errArray, $ErrorMessage['p_hashid_spreadsheet']); }
	}
	
	//Check if the accounts match between spreadsheet and account
	if(empty($errArray))
	{
		if($spreadsheet['account_id'] !== $account['id'])
		{
			array_push($errArray, 'This spreadsheet does not belong to this account.');
		}
	}
	
	//ARTICLE
	$key = 'p_hashid_article';
	 if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		if(validate_hashid($_POST[$key])== false)
		{
			array_push($errArray, $ErrorMessage[$key]);
		}
		else{
			$hashid_article = $_POST[$key];
			}
	}
	//Get the article
	if(empty($errArray))
	{		
		$article = get_rcpt_article_by_hashid($account['id'], $hashid_article);
		if(empty($article))
		{	array_push($errArray, $ErrorMessage[$key]); }
	}
	

	// member (possibly multiples !)
	$key = 'p_recipient';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	
	if(empty($errArray))
	{
//Loop now on every members
	 foreach ($_POST['p_recipient'] as $particip)
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
		
		// QUANTITY BOUGH BY member
		$key = 'p_quantity';
		if(!isset($particip[$key])) { //If empty
			array_push($errArray2, $ErrorEmptyMessage[$key]);
		}
		else{
			$quantity = (float)$particip[$key];
			if($quantity < 0)
			{
				array_push($errArray2, $ErrorMessage[$key]);
			}
		}
		
		//Hash id for the new rcpt_recipient
		$hashid_rcpt_recipient = "";
		if(empty($errArray2))
		{	
			$hashid_rcpt_recipient = create_hashid();
			if(is_null($hashid_rcpt_recipient))
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
			if($article['account_id'] !== $spreadsheet['account_id'])
			{
				array_push($errArray2, 'Article and spreadsheet do not belong to the same account');
			}			
			if($article['account_id'] !== $account['id'])
			{
				array_push($errArray2, 'This article does not belong to this account.');
			}
		}
		
		//Check the quantity available
		if(empty($errArray2))
		{
			$quantity_used = (float)get_rcpt_article_quantities_taken($account['id'], $spreadsheet['id'], $article['id']);
			if($quantity_used + (float)$quantity > $article['quantity'])
			{
				array_push($errArray, 'There are not enough quantity.');
			}
		}
		
		//Check if the rcpt_recipient is not already affected to the article
		if(empty($errArray2))
		{
			$registred_spreadsheet_recv = get_rcpt_recipient_by_member_id($account['id'], $spreadsheet['id'], $article['id'], $member['id']);
			if(!empty($registred_spreadsheet_recv))
			{
				array_push($errArray2, 'Payer already registred!');
			}
		}
		
		//Save the rcpt_recipient
		if(empty($errArray2))
		{
			$success = set_rcpt_recipient($account['id'], $hashid_rcpt_recipient, $spreadsheet['id'], $member['id'], $article['id'], $quantity);	
			if($success !== true)
			{array_push($errArray2, 'Server error: Problem while attempting to add a recipient: '.$success); 	}
			else
			{
				array_push($successArray, 'Recipient has been successfully added');
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