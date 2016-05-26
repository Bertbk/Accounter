<?php 
include_once('/lib/get_account.php');
include_once('/lib/get_account_admin.php');
include_once('/lib/get_contributors.php');
include_once('/lib/get_contributor_by_name.php');
include_once('/lib/get_contributor_by_hashid.php');
include_once('/lib/get_payments.php');
include_once('/lib/get_payment_by_hashid.php');

include_once('/lib/set_contributor.php');
include_once('/lib/set_payment.php');

include_once('/lib/update_contributor.php');
include_once('/lib/update_payment.php');

include_once('/lib/compute_solution.php');


$my_account = array();
/* Get arguments */
//Get if admin mode is asked to be activated 
$admin_mode_url = false;
if(!empty($_GET['admin']))
{
	$admin_mode_url  = (boolean)$_GET['admin'];
}

//Get Hashid
$hashid_url = "";
empty($_GET['hash']) ? $hashid_url = "" : $hashid_url = htmlspecialchars($_GET['hash']);

//If empty, go back home.
if($hashid_url == "" || (strlen($hashid_url) != 16 && !$admin_mode_url) 
	||(strlen($hashid_url) != 32 && $admin_mode_url))
{
	header ("location:/DivideTheBill/index.php");
}

//Edit a contributor ?
$contrib_hashid = "";
empty($_GET['edit_contrib']) ? $contrib_hashid = "" : $contrib_hashid = htmlspecialchars($_GET['edit_contrib']);
(empty($contrib_hashid)) ? $edit_contrib = false : $edit_contrib=true;

//Edit a payment ?
$payment_hashid = "";
empty($_GET['edit_payment']) ? $payment_hashid = "" : $payment_hashid = htmlspecialchars($_GET['edit_payment']);
(empty($payment_hashid)) ? $edit_payment = false : $edit_payment=true;

/* Treat arguments */
//Check if admin mode is really activated
$admin_mode = false;
$edit_mode = false;
if(!$admin_mode_url)
{
	//Simple search
	$my_account = get_account($hashid_url);
}
else
{
	//Admin search
	$my_account = get_account_admin($hashid_url);
	//If result, then admin mode activated
	if(!empty($my_account))
	{
		$admin_mode = true;
	}
}

//If not admin, then do not edit anything
if(!$admin_mode)
{
	$payment_hashid = "";
	$edit_payment = false;

	$contrib_hashid = "";
	$edit_contrib = false;
	
	$edit_mode = false;
}

// There is no account here ... Go back home
if(empty($my_account))
{
	header ("location:/DivideTheBill/index.php");
}

//Now everything is fine. Let us extract some information.
$account_id = $my_account['id'];

//New contributor
if($admin_mode && isset($_POST['submit_contrib']))
{
	$name_of_contrib = filter_input(INPUT_POST, 'name_of_contributor', FILTER_SANITIZE_STRING);
	$nb_of_parts = filter_input(INPUT_POST, 'number_of_parts', FILTER_SANITIZE_NUMBER_INT);
	$contrib_recorded = set_contributor($account_id, $name_of_contrib, $nb_of_parts);
}

//New Payment
if($admin_mode && isset($_POST['submit_payment']))
{
	$p_payer_id = filter_input(INPUT_POST, 'p_payer_id', FILTER_SANITIZE_NUMBER_INT);
	if(!is_null($p_payer_id))
	{
		$p_cost = filter_input(INPUT_POST, 'p_cost', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
		$p_receiver_id = filter_input(INPUT_POST, 'p_receiver_id', FILTER_SANITIZE_NUMBER_INT);
		$p_description = filter_input(INPUT_POST, 'p_description', FILTER_SANITIZE_STRING);
		$p_date_creation  = filter_input(INPUT_POST, 'p_date_creation', FILTER_SANITIZE_STRING);
		$p_receiver_id = ($p_receiver_id == -1) ? null:$p_receiver_id;
		$p_payment_added = set_payment($account_id, $p_payer_id, $p_cost, $p_receiver_id, $p_description, $p_date_creation);
		if(!$p_payment_added)
		{
			echo '<p>payment couldn\'t be added.</p>';
		}
	}
}

//Edit contributor
$contrib_id_to_edit = null;
if($admin_mode && $edit_contrib)
{
	$contrib_to_edit = get_contributor_by_hashid($account_id, $contrib_hashid);
	if(!empty($contrib_to_edit))
	{
		$contrib_id_to_edit = $contrib_to_edit['id'];
	}
}
if($admin_mode && isset($_POST['submit_edit_contrib']))
{
	$name_of_contrib = filter_input(INPUT_POST, 'name_of_contributor', FILTER_SANITIZE_STRING);
	$nb_of_parts = filter_input(INPUT_POST, 'number_of_parts', FILTER_SANITIZE_NUMBER_INT);
	$contrib_edited = update_contributor($account_id, $contrib_id_to_edit, $name_of_contrib, $nb_of_parts);
	if($contrib_edited)
	{
		$redirect_url = 'location:/DivideTheBill/account/'.$hashid_url.'/admin';
		header($redirect_url);
	}
}


//Edit payment
$payment_id_to_edit = null;
if($admin_mode && $edit_payment)
{
	$payment_to_edit = get_payment_by_hashid($account_id, $payment_hashid);	
	$payment_id_to_edit = $payment_to_edit['id'];
}
if($admin_mode && isset($_POST['submit_edit_payment']))
{
	$p_payer_id = filter_input(INPUT_POST, 'p_payer_id', FILTER_SANITIZE_NUMBER_INT);
	if(!is_null($p_payer_id))
	{
		$p_cost = filter_input(INPUT_POST, 'p_cost', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
		$p_receiver_id = filter_input(INPUT_POST, 'p_receiver_id', FILTER_SANITIZE_NUMBER_INT);
		$p_description = filter_input(INPUT_POST, 'p_description', FILTER_SANITIZE_STRING);
		$p_date_creation  = filter_input(INPUT_POST, 'p_date_creation', FILTER_SANITIZE_STRING);
		$p_receiver_id = ($p_receiver_id == -1) ? null:$p_receiver_id;
		$payment_edited = update_payment($account_id, $payment_id_to_edit, $p_payer_id, $p_cost, $p_receiver_id, $p_description, $p_date_creation);
		if($payment_edited)
		{
			$redirect_url = 'location:/DivideTheBill/account/'.$hashid_url.'/admin';
			header($redirect_url);
		}
	}
}

//Cancel edit
if($admin_mode && isset($_POST['submit_cancel']))
{
	$redirect_url = 'location:/DivideTheBill/account/'.$hashid_url.'/admin';
	header($redirect_url);
}


//Computations and values used in display
$my_contributors = get_contributors($account_id);
$n_contributors = 0;
$n_parts = 0;
foreach($my_contributors  as $contrib)
{
	$n_contributors += 1 ;
	$n_parts += (int)$contrib['number_of_parts'] ;
}
//Payments
$my_payments = get_payments($account_id);
//solution
$solution = array();
$solution = compute_solution($account_id);

$edit_mode = ($edit_contrib || $edit_payment);

include_once('/templates/account.php');
?>