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

include_once(LIBPATH.'/bills/get_bills.php');
include_once(LIBPATH.'/bills/get_bill_by_id.php');
include_once(LIBPATH.'/bills/set_bill.php');
include_once(LIBPATH.'/bills/update_bill.php');

include_once(LIBPATH.'/bill_participants/set_bill_participant.php');
include_once(LIBPATH.'/bill_participants/get_bill_participants.php');
include_once(LIBPATH.'/bill_participants/get_bill_participant_by_hashid.php');
include_once(LIBPATH.'/bill_participants/update_bill_participant.php');

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
//Edit...
$what_to_edit = array (
    "participant"  => false,
    "payment" => false,
    "bill_participant" => false
);
$hashid_edit = array(
    "participant"  => "",
    "payment" => "",
    "bill_participant" => ""
);
//Edit a participant ?
$participant_hashid = "";
empty($_GET['edit_participant']) ? $participant_hashid = "" : $participant_hashid = htmlspecialchars($_GET['edit_participant']);
$hashid_edit['participant'] = (strlen($participant_hashid)==16)? $participant_hashid : "";
$what_to_edit['participant'] = !(empty($participant_hashid));
$participant_hashid = "";
//Edit a payment ?
$payment_hashid = "";
empty($_GET['edit_payment']) ? $payment_hashid = "" : $payment_hashid = htmlspecialchars($_GET['edit_payment']);
$hashid_edit['payment'] = (strlen($payment_hashid)==16)? $payment_hashid : "";
$what_to_edit['payment'] = (!empty($payment_hashid));
$payment_hashid = "";
//Edit a bill_participant ?
$bill_part_hashid = "";
empty($_GET['edit_bill_part']) ? $bill_part_hashid = "" : $bill_part_hashid = htmlspecialchars($_GET['edit_bill_part']);
$hashid_edit['bill_participant'] = (strlen($bill_part_hashid)==16)? $bill_part_hashid : "";
$what_to_edit['bill_participant'] = (!empty($bill_part_hashid));
$bill_part_hashid = "";

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
	//else: try but fail
}

if(empty($my_account))
{
	header ('location: '.BASEURL);
}

//If not admin, then no edit priviledges
if(!$admin_mode)
{
	$payment_hashid = "";
	$participant_hashid = "";
	foreach($what_to_edit as $possiblemode)
	{
		$what_to_edit[$possiblemode] = false;
	}
	foreach($hashid_edit as $possiblemode)
	{
		$hashid_edit[$possiblemode] = "";
	}
}

$edit_mode = false;
foreach($what_to_edit as $possiblemode=>$key)
{if($what_to_edit[$possiblemode]==true){$edit_mode = true;} }

/* Here, we have an account and we know if we are admin or not.*/
$account_id = $my_account['id'];
$bill_id_to_edit = "";

/* PARTICIPANT*/
//New participant
if($admin_mode && isset($_POST['submit_participant']))
{
	$p_name_of_participant = filter_input(INPUT_POST, 'p_name_of_participant', FILTER_SANITIZE_STRING);
	$p_nb_of_people = filter_input(INPUT_POST, 'p_nb_of_people', FILTER_SANITIZE_NUMBER_INT);
	$p_email = filter_input(INPUT_POST, 'p_email', FILTER_SANITIZE_EMAIL, FILTER_VALIDATE_EMAIL);
	if(!empty($p_name_of_participant)){
		$p_participant_recorded = set_participant($account_id, $p_name_of_participant, $p_nb_of_people, $p_email);
	}
	if(!$p_participant_recorded)
	{
		echo '<p>participant couldn\'t be added.</p>';
	}
}
//Edit participant
$participant_id_to_edit = null;
if($admin_mode && $what_to_edit['participant'])
{
	$participant_to_edit = get_participant_by_hashid($account_id, $hashid_edit['participant']);
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

/*BILL*/
//New bill
if($admin_mode && isset($_POST['submit_bill']))
{
	$p_name_of_bill = filter_input(INPUT_POST, 'p_name_of_bill', FILTER_SANITIZE_STRING);
	$p_description = filter_input(INPUT_POST, 'p_description', FILTER_SANITIZE_STRING);
	$p_bill_recorded = set_bill($account_id, $p_name_of_bill, $p_description);
	if(!$p_bill_recorded)
	{
		echo '<p>bill couldn\'t be added.</p>';
	}
}
//FIXME : edit bill

/* PAYMENT */
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
		$p_date_payment  = filter_input(INPUT_POST, 'p_date_payment', FILTER_SANITIZE_STRING);
		$p_receiver_id = ($p_receiver_id == -1) ? null:$p_receiver_id;
		$p_date_payment = (empty($p_date_payment))?null:$p_date_payment;
		$p_payment_added = set_payment($account_id, $p_bill_id, 
		$p_payer_id, $p_cost, $p_receiver_id, $p_description, $p_date_payment);
		if(!$p_payment_added)
		{
			echo '<p>payment couldn\'t be added.</p>';
		}
	}
}
//Edit payment
$payment_id_to_edit = null;
if($admin_mode && $what_to_edit['payment'])
{
	$payment_to_edit = get_payment_by_hashid($account_id, $hashid_edit['payment']);
	if(!empty(payment_to_edit))
	{
		$payment_id_to_edit = $payment_to_edit['id'];
		$bill_id_to_edit = $payment_to_edit['bill_id'];
	}
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
		$p_date_payment  = filter_input(INPUT_POST, 'p_date_payment', FILTER_SANITIZE_STRING);
		$p_receiver_id = ($p_receiver_id == -1) ? null:$p_receiver_id;
		$payment_edited = update_payment($account_id, $p_bill_id, $payment_id_to_edit, 
		$p_payer_id, $p_cost, $p_receiver_id, $p_description, $p_date_payment);
		if($payment_edited)
		{
			$redirect_url = 'location:'.BASEURL.'/account/'.$hashid.'/admin';
			header($redirect_url);
		}
	}
}

/* BILL_PARTICIPANT */
//New one = Assign a participant to a bill
if($admin_mode && isset($_POST['submit_assign_participant']))
{
	$p_bill_id = filter_input(INPUT_POST, 'p_bill_id', FILTER_SANITIZE_NUMBER_INT);
	$p_participant_id = filter_input(INPUT_POST, 'p_participant_id', FILTER_SANITIZE_NUMBER_INT);
	$p_percent_of_use = filter_input(INPUT_POST, 'p_percent_of_use', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);	
	$association_ok = set_bill_participant($account_id, $p_bill_id, $p_participant_id, $p_percent_of_use);
	if(!$association_ok)
	{
		echo '<p>Association couldn\'t be made.</p>';
	}
}
//Edit bill_participant
$bill_participant_id_to_edit = null;
if($admin_mode && $what_to_edit['bill_participant'])
{
	$bill_participant_to_edit = get_bill_participant_by_hashid($account_id, $hashid_edit['bill_participant']);
	if(!empty($bill_participant_to_edit))
	{
		$bill_participant_id_to_edit = $bill_participant_to_edit['id'];
		$bill_id_to_edit = $bill_participant_to_edit['bill_id'];
	}
}
if($admin_mode && isset($_POST['submit_edit_bill_participant']))
{

	$p_participant_id = filter_input(INPUT_POST, 'p_participant_id', FILTER_SANITIZE_NUMBER_INT);
	$p_percent_of_use = filter_input(INPUT_POST, 'p_percent_of_use', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);	
	$bill_participant_edited = update_bill_participant($account_id, $bill_participant_id_to_edit, $p_participant_id, $p_percent_of_use);
	if($bill_participant_edited)
	{
		$redirect_url = 'location:'.BASEURL.'/account/'.$hashid.'/admin';
		header($redirect_url);
	}
}

/* CANCEL EDIT */
if($admin_mode && isset($_POST['submit_cancel']))
{
	$redirect_url = 'location:'.BASEURL.'/account/'.$hashid.'/admin';
	header($redirect_url);
}

/* Computations and values used in display */
$my_bills = get_bills($account_id);
$my_participants = get_participants($account_id);
$my_bill_participants = get_bill_participants($account_id);

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
//$solution = compute_solution($account_id);

include_once('/templates/account.php');
?>