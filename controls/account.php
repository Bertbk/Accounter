<?php 
include_once('/lib/get_account.php');
include_once('/lib/get_account_admin.php');
include_once('/lib/get_contributors.php');
include_once('/lib/set_contributor.php');

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

//Is the form completed ?
if(isset($_POST['submit']))
{
	$name_of_contrib = filter_input(INPUT_POST, 'name_of_contributor', FILTER_SANITIZE_STRING);
	$nb_of_parts = filter_input(INPUT_POST, 'number_of_parts', FILTER_SANITIZE_NUMBER_INT);
	$contrib_recorded = set_contributor($account_id, $name_of_contrib, $nb_of_parts);
}

$my_contributors = get_contributors($account_id);
$n_contributors = 0;
$n_parts = 0;
foreach($my_contributors  as $contrib)
{
	$n_contributors += 1 ;
	$n_parts += (int)$contrib['number_of_parts'] ;
}

include_once('/templates/account.php');
?>