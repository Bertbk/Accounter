<?php 
include_once('/lib/get_account.php');
include_once('/lib/get_account_admin.php');
include_once('/lib/get_contributors.php');
include_once('/lib/set_contributor.php');
include_once('/lib/set_payment.php');
include_once('/lib/get_payments.php');


//Get Hashid
$hashid_url = "";
empty($_GET['hash']) ? $hashid_url = "" : $hashid_url = htmlspecialchars($_GET['hash']);

//If empty, go back home.
if($hashid_url == "" || strlen($hashid_url) != 16 )
{
	header ("location:/DivideTheBill/index.php");
}

//Get if admin mode is asked to be activated 
$admin_mode_url = false;
if(!empty($_GET['admin']))
{
	$admin_mode_url  = (boolean)$_GET['admin'];
}

$my_account = array();
$admin_mode = false;
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

// No result, go back home
if(empty($my_account))
{
	header ("location:/DivideTheBill/index.php");
}

//Now everything is fine. Let us extract some information.
$account_id = $my_account['id'];

//New contributor ?
if(isset($_POST['submit_contrib']))
{
	$name_of_contrib = filter_input(INPUT_POST, 'name_of_contributor', FILTER_SANITIZE_STRING);
	$nb_of_parts = filter_input(INPUT_POST, 'number_of_parts', FILTER_SANITIZE_NUMBER_INT);
	$contrib_recorded = set_contributor($account_id, $name_of_contrib, $nb_of_parts);
}

//New Payment?
if(isset($_POST['submit_payment']))
{
	$p_payer_id = filter_input(INPUT_POST, 'p_payer_id', FILTER_SANITIZE_NUMBER_INT);
	$p_cost = filter_input(INPUT_POST, 'p_cost', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
	$p_receiver_id = filter_input(INPUT_POST, 'p_receiver_id', FILTER_SANITIZE_NUMBER_INT);
	$p_description = filter_input(INPUT_POST, 'p_description', FILTER_SANITIZE_STRING);
	$p_date_creation  = filter_input(INPUT_POST, 'p_date_creation', FILTER_SANITIZE_STRING);
	echo '<p>DATE :'.$p_date_creation.'</p>';
	$p_payment_added = set_payment($account_id, $p_payer_id, $p_cost, $p_receiver_id, $p_description, $p_date_creation);
	if(!$p_payment_added)
	{
		echo '<p>payment couldn\'t be added.</p>';
	}
}

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

include_once('/templates/account.php');
?>