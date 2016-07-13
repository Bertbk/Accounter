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
Action launched when the form "Cancel" has been called (when editing).
Redirect to the admin page of an account.
 */
 
require_once __DIR__.'/../../config-app.php';

include_once(LIBPATH.'/accounts/get_account_admin.php');

include_once(LIBPATH.'/receipts/get_receipt_by_hashid.php');

include_once(LIBPATH.'/receipt_participants/get_receipt_participant_by_hashid.php');

include_once(LIBPATH.'/articles/get_article_by_hashid.php');
include_once(LIBPATH.'/articles/update_article.php');


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
		'p_hashid_receipt' => 'Please provide a receipt',
		'p_article' => 'Please provide an article',
		'p_price' => 'Please provide a price',
		'p_product' => 'Please provide a product',
		'p_quantity' => 'Please provide a quantity'
   );
	 
$ErrorMessage = array(
'p_hashid_account' => 'Account is not valid',
'p_hashid_receipt' => 'receipt is not valid',
'p_article' => 'Article is not valid',
'p_price' => 'Price is not valid',
'p_product' => 'Product is not valid',
'p_quantity' => 'Quantity is not valid',
'p_anchor' => 'Anchor not valid'
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
 

if(isset($_POST['submit_cancel']))
{
	header('location: '.$redirect_link);
	exit;
}
else if(isset($_POST['submit_update_article']))
{
	// receipt
	$key = 'p_hashid_receipt';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		if(validate_hashid($_POST[$key])== false)
		{
			array_push($errArray, $ErrorMessage[$key]);
		}
		else{
			$hashid_receipt = $_POST[$key];
			}
	}
	//Get the receipt
	if(empty($errArray))
	{		
		$receipt = get_receipt_by_hashid($account['id'], $hashid_receipt);
		if(empty($receipt))
		{	array_push($errArray, $ErrorMessage[$key]); }
	}
	
	//Check if the accounts match between receipt and account
	if(empty($errArray))
	{
		if($receipt['account_id'] !== $account['id'])
		{
			array_push($errArray, 'This receipt does not belong to this account.');
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
		$article = get_article_by_hashid($account['id'], $hashid_article);
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
		if($article['receipt_id'] !== $receipt['id'])
		{
			array_push($errArray, 'This article does not belong to this receipt.');
		}
	}	
	
	//PRODUCT
	$key = 'p_product';
	 if(empty($article[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		$product = $article[$key];
	}
	
	// PRICE
	$key = 'p_price';
	if(empty($article[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		$price = (float)$article[$key];
		if($price <= 0)
		{
			array_push($errArray, $ErrorMessage[$key]);
		}
	}
	
	// PRICE
	$key = 'p_quantity';
	if(empty($article[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	else{
		$quantity = (float)$article[$key];
		if($quantity <= 0)
		{
			array_push($errArray, $ErrorMessage[$key]);
		}
	}

	//Update the article
	if(empty($errArray))
	{
		$success = update_article($account['id'], $article['id'], $receipt['id'], $price, $product, $quantity);	
		if(!$success)
		{array_push($errArray, 'Server error: Problem while attempting to update a article'); 	}
	else
		{
			array_push($successArray, 'Article has been successfully updated');
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