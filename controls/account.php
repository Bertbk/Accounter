<?php 
include_once(LIBPATH.'/accounts/get_account.php');
include_once(LIBPATH.'/accounts/get_account_admin.php');

include_once(LIBPATH.'/participants/get_participants.php');
include_once(LIBPATH.'/participants/get_participant_by_name.php');
include_once(LIBPATH.'/participants/get_participant_by_hashid.php');
include_once(LIBPATH.'/participants/set_participant.php');
include_once(LIBPATH.'/participants/update_participant.php');

include_once(LIBPATH.'/payments/get_payments.php');
include_once(LIBPATH.'/payments/get_payment_by_hashid.php');
include_once(LIBPATH.'/payments/set_payment.php');
include_once(LIBPATH.'/payments/update_payment.php');

include_once(LIBPATH.'/compute_solution.php');

/* Get arguments */
//Get if admin mode is asked to be activated 
$admin_mode_url = false;
if(!empty($_GET['admin']))
{
	$admin_mode_url  = (boolean)$_GET['admin'];
}

//Get Hashid
$hashid = "";
empty($_GET['hash']) ? $hashid = "" : $hashid = htmlspecialchars($_GET['hash']);
//If no hashid then go back home
if($hashid == "" || (strlen($hashid) != 16 && !$admin_mode_url) 
	||(strlen($hashid) != 32 && $admin_mode_url))
{
	header ('location: '.BASEURL);
}
//Edit a participant ?
$participant_hashid = "";
empty($_GET['edit_participant']) ? $participant_hashid = "" : $participant_hashid = htmlspecialchars($_GET['edit_participant']);
$participant_hashid = (strlen($participant_hashid)==16)? $participant_hashid : "";
$edit_participant = !(empty($participant_hashid));
//Edit a payment ?
$payment_hashid = "";
empty($_GET['edit_payment']) ? $payment_hashid = "" : $payment_hashid = htmlspecialchars($_GET['edit_payment']);
$payment_hashid = (strlen($payment_hashid)==16)? $payment_hashid : "";
$edit_payment = (!empty($payment_hashid));

/* Treat arguments */
$my_account = array();
$admin_mode = false;
$edit_mode = false;
if(!$admin_mode_url)
{
	//Simple search
	$my_account = get_account($hashid);
}
else
{
	//Admin search
	$my_account = get_account_admin($hashid);
	//If result, then admin mode activated
	if(!empty($my_account))
	{
		$admin_mode = true;
	}
	//else: lier
}

if(empty($my_account))
{
	header ('location: '.BASEURL);
}

//If not admin, then no edit priviledges
if(!$admin_mode)
{
	$payment_hashid = "";
	$edit_payment = false;
	$participant_hashid = "";
	$edit_participant = false;
}
$edit_mode = $edit_participant || $edit_payment;

/* Here, we have an account and we know if we are admin or not.*/
$account_id = $my_account['id'];

//New participant
if($admin_mode && isset($_POST['submit_participant']))
{
	$p_name_of_participant = filter_input(INPUT_POST, 'p_name_of_participant', FILTER_SANITIZE_STRING);
	$p_nb_of_people = filter_input(INPUT_POST, 'p_nb_of_people', FILTER_SANITIZE_NUMBER_INT);
	$p_email = filter_input(INPUT_POST, 'p_email', FILTER_SANITIZE_EMAIL, FILTER_VALIDATE_EMAIL);
	$p_participant_recorded = set_participant($account_id, $p_name_of_participant, $p_nb_of_people, $p_email);
	if(!$p_participant_recorded)
	{
		echo '<p>participant couldn\'t be added.</p>';
	}
}

//New Payment
if($admin_mode && isset($_POST['submit_payment']))
{
	$p_bill_id = filter_input(INPUT_POST, 'p_bill_id', FILTER_SANITIZE_NUMBER_INT);
	$p_payer_id = filter_input(INPUT_POST, 'p_payer_id', FILTER_SANITIZE_NUMBER_INT);
	if(!is_null($p_payer_id))
	{
		$p_cost = filter_input(INPUT_POST, 'p_cost', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
		$p_receiver_id = filter_input(INPUT_POST, 'p_receiver_id', FILTER_SANITIZE_NUMBER_INT);
		$p_description = filter_input(INPUT_POST, 'p_description', FILTER_SANITIZE_STRING);
		$p_date_creation  = filter_input(INPUT_POST, 'p_date_creation', FILTER_SANITIZE_STRING);
		$p_receiver_id = ($p_receiver_id == -1) ? null:$p_receiver_id;
		$p_payment_added = set_payment($account_id, $p_bill_id, 
		$p_payer_id, $p_cost, $p_receiver_id, $p_description, $p_date_creation);
		if(!$p_payment_added)
		{
			echo '<p>payment couldn\'t be added.</p>';
		}
	}
}

//Edit participant
$participant_id_to_edit = null;
if($admin_mode && $edit_participant)
{
	$participant_to_edit = get_participant_by_hashid($account_id, $participant_hashid);
	if(!empty($participant_to_edit))
	{
		$participant_id_to_edit = $participant_to_edit['id'];
	}
}
if($admin_mode && isset($_POST['submit_edit_participant']))
{
	$name_of_participant = filter_input(INPUT_POST, 'name_of_participant', FILTER_SANITIZE_STRING);
	$nb_of_people = filter_input(INPUT_POST, 'nb_of_people', FILTER_SANITIZE_NUMBER_INT);
	$participant_edited = update_participant($account_id, $participant_id_to_edit, $name_of_participant, $nb_of_people);
	if($participant_edited)
	{
		$redirect_url = 'location:'.BASEURL.'/account/'.$hashid.'/admin';
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
	$p_bill_id = filter_input(INPUT_POST, 'p_bill_id', FILTER_SANITIZE_NUMBER_INT);
	$p_payer_id = filter_input(INPUT_POST, 'p_payer_id', FILTER_SANITIZE_NUMBER_INT);
	if(!is_null($p_payer_id))
	{
		$p_cost = filter_input(INPUT_POST, 'p_cost', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
		$p_receiver_id = filter_input(INPUT_POST, 'p_receiver_id', FILTER_SANITIZE_NUMBER_INT);
		$p_description = filter_input(INPUT_POST, 'p_description', FILTER_SANITIZE_STRING);
		$p_date_creation  = filter_input(INPUT_POST, 'p_date_creation', FILTER_SANITIZE_STRING);
		$p_receiver_id = ($p_receiver_id == -1) ? null:$p_receiver_id;
		$payment_edited = update_payment($account_id, $p_bill_id, $payment_id_to_edit, 
		$p_payer_id, $p_cost, $p_receiver_id, $p_description, $p_date_creation);
		if($payment_edited)
		{
			$redirect_url = 'location:'.BASEURL.'/account/'.$hashid.'/admin';
			header($redirect_url);
		}
	}
}

//Cancel edit
if($admin_mode && isset($_POST['submit_cancel']))
{
	$redirect_url = 'location:'.BASEURL.'/account/'.$hashid.'/admin';
	header($redirect_url);
}

//Computations and values used in display
$my_participants = get_participants($account_id);
$n_participants = 0;
$n_people = 0;
foreach($my_participants  as $participant)
{
	$n_participants += 1 ;
	$n_people += (int)$participant['nb_of_people'] ;
}
//Payments
$my_payments = get_payments($account_id);
//solution
$solution = array();
$solution = compute_solution($account_id);

include_once('/templates/account.php');
?>