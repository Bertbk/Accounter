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
Check the data before asking the SQL to create a new article
 */

 
require_once __DIR__.'/../../config-app.php';

include_once(LIBPATH.'/accounts/get_account_admin.php');

include_once(LIBPATH.'/receipts/get_receipt_by_hashid.php');

include_once(LIBPATH.'/receipt_payers/get_receipt_payer_by_hashid.php');

include_once(LIBPATH.'/articles/set_article.php');


include_once(LIBPATH.'/hashid/validate_hashid.php');
include_once(LIBPATH.'/hashid/create_hashid.php');


//Session is used to send back errors to account.php (if any)
session_start();

$errArray = array(); //error messages
$warnArray = array(); //warning messages
$successArray = array(); //success messages
$redirect_link ="" ;

if(isset($_POST['submit_new_article']))
{
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
	
	// ARTICLES (possibly multiples !)
	$key = 'p_article';
	if(empty($_POST[$key])) { //If empty
		array_push($errArray, $ErrorEmptyMessage[$key]);
	}
	
	if(empty($errArray))
	{
	//Loop now on every articles
	 foreach ($_POST['p_article'] as $article)
	 {
		$errArray2 = array(); // Error array for each article
		//PRODUCT
		$key = 'p_product';
		 if(empty($article[$key])) { //If empty
			array_push($errArray2, $ErrorEmptyMessage[$key]);
		}
		else{
			$product = $article[$key];
		}
		
		// PRICE
		$key = 'p_price';
		if(empty($article[$key])) { //If empty
			array_push($errArray2, $ErrorEmptyMessage[$key]);
		}
		else{
			$price = (float)$article[$key];
			if($price <= 0)
			{
				array_push($errArray2, $ErrorMessage[$key]);
			}
		}
		
		// QUANTITY
		$key = 'p_quantity';
		if(empty($article[$key])) { //If empty
			array_push($errArray2, $ErrorEmptyMessage[$key]);
		}
		else{
			$quantity = (float)$article[$key];
			if($quantity <= 0)
			{
				array_push($errArray2, $ErrorMessage[$key]);
			}
		}

		//Hash id for the new article
		$hashid_article = "";
		if(empty($errArray2))
		{
			$hashid_article = create_hashid();
			if(is_null($hashid_article))
				{ array_push($errArray2, "Server error: problem while creating hashid.");}
		}
		
		//Save the article
		if(empty($errArray2))
		{
			$success = set_article($account['id'], $hashid_article, $receipt['id'], $price, $product, $quantity);	
			if($success !== true)
			{array_push($errArray2, 'Server error: Problem while attempting to add an article: '.$success); 	}
		else
			{
				array_push($successArray, 'Article has been successfully added');
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