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
Control page of the Account page.
This is the main control page.

GET argument : hashid or hashid_admin and editing mode

This page checks if... :
- The hashid corresponds to an account.
- The admin mode is enabled (ie: hashid_admin + admin as argument)
- Something is being edited (ie: admin mode + edit argument)
 */

 require_once __DIR__.'/../config-app.php';

include_once(LIBPATH.'/accounts/get_account.php');
include_once(LIBPATH.'/accounts/get_account_admin.php');

include_once(LIBPATH.'/participants/get_participants.php');
include_once(LIBPATH.'/participants/get_participant_by_name.php');
include_once(LIBPATH.'/participants/get_participant_by_hashid.php');
include_once(LIBPATH.'/participants/set_participant.php');
include_once(LIBPATH.'/participants/update_participant.php');
include_once(LIBPATH.'/participants/delete_participant.php');

include_once(LIBPATH.'/payments/get_payments.php');
include_once(LIBPATH.'/payments/get_payment_by_hashid.php');
include_once(LIBPATH.'/payments/set_payment.php');
include_once(LIBPATH.'/payments/update_payment.php');
include_once(LIBPATH.'/payments/get_payments_by_bills.php');
include_once(LIBPATH.'/payments/delete_payment.php');

include_once(LIBPATH.'/bills/get_bills.php');
include_once(LIBPATH.'/bills/get_bill_by_id.php');
include_once(LIBPATH.'/bills/get_bill_by_hashid.php');
include_once(LIBPATH.'/bills/set_bill.php');
include_once(LIBPATH.'/bills/update_bill.php');
include_once(LIBPATH.'/bills/delete_bill.php');

include_once(LIBPATH.'/bill_participants/set_bill_participant.php');
include_once(LIBPATH.'/bill_participants/get_bill_participants.php');
include_once(LIBPATH.'/bill_participants/get_bill_participant_by_hashid.php');
include_once(LIBPATH.'/bill_participants/update_bill_participant.php');
include_once(LIBPATH.'/bill_participants/get_free_bill_participants.php');
include_once(LIBPATH.'/bill_participants/delete_bill_participant.php');

include_once(LIBPATH.'/receipts/get_receipts.php');
include_once(LIBPATH.'/receipts/get_receipt_by_id.php');
include_once(LIBPATH.'/receipts/get_receipt_by_hashid.php');
include_once(LIBPATH.'/receipts/set_receipt.php');
include_once(LIBPATH.'/receipts/update_receipt.php');
include_once(LIBPATH.'/receipts/delete_receipt.php');
/*
include_once(LIBPATH.'/articles/get_articles.php');
include_once(LIBPATH.'/articles/get_article_by_id.php');
include_once(LIBPATH.'/articles/get_article_by_hashid.php');
include_once(LIBPATH.'/articles/set_article.php');
include_once(LIBPATH.'/articles/update_article.php');
include_once(LIBPATH.'/articles/delete_article.php');
*/

/*
include_once(LIBPATH.'/receipt_payers/get_receipt_payers.php');
include_once(LIBPATH.'/receipt_payers/get_receipt_payer_by_id.php');
include_once(LIBPATH.'/receipt_payers/get_receipt_payer_by_hashid.php');
include_once(LIBPATH.'/receipt_payers/get_free_receipt_payers.php');
include_once(LIBPATH.'/receipt_payers/set_receipt_payer.php');
include_once(LIBPATH.'/receipt_payers/update_receipt_payer.php');
include_once(LIBPATH.'/receipt_payers/delete_receipt_payer.php');
*/
include_once(LIBPATH.'/solutions/compute_bill_solutions.php');
include_once(LIBPATH.'/solutions/compute_solution.php');
include_once(LIBPATH.'/solutions/compute_opt_solution.php');

include_once(LIBPATH.'/hashid/validate_hashid.php');


$my_account = array();
$admin_mode = false; //validates the admin mode or not
$edit_mode = false; //validates the edit mode or not (in edit mode, display changes for a particular data)
$edit_type = ""; // which type of data are edited ?
$edit_hashid = ""; //

/* Get arguments */
//Get Hashid of the account
$hashid_url = "";
empty($_GET['hash']) ? $hashid_url = "" : $hashid_url = $_GET['hash'];
if($hashid_url== "")
{
	header ('location: '.BASEURL);
	exit;
}

//If no or bad hashid then go back home
if(validate_hashid($hashid_url))
{
	//Reset admin values because non admin visitor
	$admin_mode = false;
	$my_account = get_account($hashid_url);
}
else if(validate_hashid_admin($hashid_url))
{
	//Admin search
	$my_account = get_account_admin($hashid_url);
	$admin_mode_url = (empty($_GET['admin'])?false:(boolean)$_GET['admin']);
	if($admin_mode_url == false)
	{
		//Strange : good hashid but not admin mode enable.
		header ('location: '.BASEURL);
		exit;
	}
	//Admin mode enable
	if(!empty($my_account) && $admin_mode_url == true)
	{	$admin_mode = true; }
}
else{
	header ('location: '.BASEURL);
	exit;
	}

//Go back home if it's a failure
if(empty($my_account))
{
	header ('location: '.BASEURL);
	exit;
}

$my_account_id = $my_account['id'];
$link_to_account = BASEURL.'/account/'.$my_account['hashid'];
$link_to_account_admin = BASEURL.'/account/'.$my_account['hashid_admin'].'/admin';


/* If cancel*/
if($admin_mode)
{
	//Cancel ?
	if(isset($_POST['submit_cancel']))
	{
		header('location:'.$link_to_account_admin);
		exit;
	}
}

/* If Edit mode:
- Detect the type of data to be edited
- And its hashid
*/
if($admin_mode && !empty($_GET['edit']) && !empty($_GET['edit_hashid']))
{
	$edit_mode = $_GET['edit'];
	$edit_hashid = $_GET['edit_hashid'];
	
	if(validate_hashid($edit_hashid) == false
	||
	($edit_mode !== "account"
	&& $edit_mode !== "participant"
	&& $edit_mode !== "bill"
	&& $edit_mode !== "bill_participant"
	&& $edit_mode !== "payment"
	&& $edit_mode !== "receipt"
	))
	{		//Wrong id or action
		header('location:'.$link_to_account_admin);
		exit;
	}
}

/* Computations and values used in display */

//=== PARTICIPANTS ===
$my_participants = get_participants($my_account_id); //All person

//=== BILLS ===
$my_bills = get_bills($my_account_id); // All bills
$my_bill_participants = get_bill_participants($my_account_id); // Participation for each bill
$my_free_bill_participants = get_free_bill_participants($my_account_id); // Possible participation for each bill
//Number of bills
$n_bills = count($my_bills);
//Payments of each bill
$my_payments_per_bill = get_payments_by_bills($my_account_id); //All payments per bill
//For JS : create the list of payer to send to JS
$list_of_possible_payers= Array(Array(Array()));
foreach($my_bills as $bill)
{
	$cpt = -1;
	foreach ($my_bill_participants[$bill['id']] as $bill_participant)
	{
		$cpt ++;
		$list_of_possible_payers[$bill['hashid']][$cpt] = 
		Array(
			'part_name' => $bill_participant['name'],
			'part_hashid' => $bill_participant['hashid']
		);
	}
}

//=== RECEIPTS ===
$my_receipts = get_receipts($my_account_id); // All receipts
$my_receipt_payers = get_receipt_payers($my_account_id); // Payers per receipts
$my_receipt_articles = get_receipt_articles($my_account_id); // Articles per receipts
$my_free_receipt_payers = get_free_receipt_payers($my_account_id); // Possible payer for each receipt
//Number of receipts
$n_receipts = count($my_receipts);

// === SOLUTION === 
$solution = compute_solution($my_account_id);
$solution_opt = compute_opt_solution($my_account_id, $solution);
//nb. of money transfert
$n_transfer = 0;
$n_transfer_opt = 0;
foreach($my_participants as $payer)
{
	$uid = $payer['id'];
	foreach($my_participants as $receiver)
	{
		$vid = $receiver['id'];
		if(isset($solution[$uid][$vid])
			&& $solution[$uid][$vid] !== 0)
			{$n_transfer++;}
		if(isset($solution_opt[$uid][$vid])
			&& $solution_opt[$uid][$vid] !== 0)
			{$n_transfer_opt++;}
	}
}
							
$n_participants = 0;
$n_people = 0;
foreach($my_participants  as $participant)
{
	$n_participants += 1 ;
	$n_people += (int)$participant['nb_of_people'] ;
}

if(empty($my_account['description'])
	||is_null($my_account['description']))
	{ $description_account = "";
	}else{
		$description_account = $my_account['description'];
}


if(!empty($my_account['date_of_creation']))
{
	$account_date_of_creation = date("d/m/Y", strtotime($my_account['date_of_creation']));
}
else{
	$account_date_of_creation = "";
}

if(!empty($my_account['date_of_expiration']))
{
	$account_date_of_expiration = date("d/m/Y", strtotime($my_account['date_of_expiration']));
}
else{
	$account_date_of_expiration = "";
}


include_once(ABSPATH.'/templates/account.php');
?>