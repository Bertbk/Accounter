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
Check the data before asking the SQL to update a rcpt_recipient (= participation)
 */


require_once __DIR__.'/../../../../../config-app.php';

include_once(LIBPATH.'/accounts/get_account_admin.php');

include_once(LIBPATH.'/spreadsheets/get_spreadsheet_by_hashid.php');

include_once(LIBPATH.'/spreadsheets/receipts/rcpt_articles/get_rcpt_article_by_hashid.php');
include_once(LIBPATH.'/spreadsheets/receipts/rcpt_articles/get_rcpt_article_quantities_taken.php');

include_once(LIBPATH.'/spreadsheets/receipts/rcpt_recipients/get_rcpt_recipient_by_hashid.php');
include_once(LIBPATH.'/spreadsheets/receipts/rcpt_recipients/update_rcpt_recipient.php');

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
		'p_hashid_article' => 'Please provide an article',
		'p_hashid_recipient' => 'Please provide a recipient',
		'p_quantity' => 'Please provide a percentage'
   );
	 
$ErrorMessage = array(
	'p_hashid_account' => 'Account is not valid',
	'p_hashid_spreadsheet' => 'spreadsheet is not valid',
	'p_hashid_article' => 'Article is not valid',
	'p_hashid_recipient' => 'Recipient is not valid',
	'p_quantity' => 'Quantity is not valid',
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

if(isset($_POST['submit_update_rcpt_recipient']))
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
	
	// CURRENT article
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
	
	//Check if the accounts match between article and account
	if(empty($errArray))
	{
		if($article['account_id'] !== $account['id'])
		{
			array_push($errArray, 'This article does not belong to this account.');
		}
		if($article['spreadsheet_id'] !== $spreadsheet['id'])
		{
			array_push($errArray, 'This article does not belong to this spreadsheet.');
		}
	}
	
	// rcpt_recipient
	$key = 'p_hashid_recipient';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		if(validate_hashid($_POST[$key])== false)
		{
			array_push($errArray, $ErrorMessage[$key]);
		}
		else{
			$hashid_rcpt_recipient = $_POST[$key];
			}
	}
	//Get the rcpt_recipient
	if(empty($errArray))
	{		
		$rcpt_recipient = get_rcpt_recipient_by_hashid($account['id'], $hashid_rcpt_recipient);
		if(empty($rcpt_recipient))
		{	array_push($errArray, $ErrorMessage[$key]); }
	}
	
	//Check if the accounts match between spreadsheet and account
	if(empty($errArray))
	{
		if($rcpt_recipient['account_id'] !== $account['id'])
		{
			array_push($errArray, 'This recipient does not belong to this account.');
		}
		if($rcpt_recipient['spreadsheet_id'] !== $spreadsheet['id'])
		{
			array_push($errArray, 'This recipient does not belong to this spreadsheet.');
		}
		if($rcpt_recipient['article_id'] !== $article['id'])
		{
			array_push($errArray, 'This recipient does not belong to this article.');
		}
	}
			
	// NEW QUANTITY
	$key = 'p_quantity';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		$new_quantity = (float)$_POST[$key];
		if($new_quantity < 0)
		{
			array_push($errArray, $ErrorMessage[$key]);
		}
	}
	//Check the total quantity
	if(empty($errArray))
	{
		$total_quantity = (float)get_rcpt_article_quantities_taken($account['id'], $spreadsheet['id'], $article['id']);
		$total_quantity -= (float)$rcpt_recipient['quantity'];
		if($total_quantity + $new_quantity > $article['quantity'])
		{
			array_push($errArray, $ErrorMessage[$key]);
		}
	}	
	
	//Update the rcpt_recipient
	if(empty($errArray))
	{
		$success = update_rcpt_recipient($account['id'], $rcpt_recipient['id'], $new_quantity);	
		if($success !== true)
		{array_push($errArray, 'Server error: Problem while attempting to update a recipient'); 	}
	else
		{
			array_push($successArray, 'Recipient has been successfully updated');
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