<?php
include_once('/lib/get_account.php');
include_once('/lib/get_account_admin.php');

$isdone = true;

//Get Hashid
$hashid_url = "";
empty($_GET['hash']) ? $hashid_url = "" : $hashid_url = htmlspecialchars($_GET['hash']);

$hashid_admin_url = "";
empty($_GET['hash_admin']) ? $hashid_admin_url = "" : $hashid_admin_url = htmlspecialchars($_GET['hash_admin']);

$hashid = $hashid_url;
$hashid_admin = $hashid_admin_url;

//If empty, go back home.
if($hashid_url == "" || strlen($hashid_url) != 16 || $hashid_admin_url == "" || strlen($hashid_admin_url) != 16 )
{
	$isdone = false;
	header ("location:/DivideTheBill/index.php");
}

$my_account = array();
$my_account = get_account($hashid);
$my_account_admin = get_account_admin($hashid_admin);

if(empty($my_account) ||empty($my_account_admin))
{
	$isdone = false;
	header ("location:/DivideTheBill/index.php");
}

if($isdone)
{
	$link_contrib = '/DivideTheBill/account/'.$hashid;
	$link_admin = '/DivideTheBill/account/'.$hashid_admin.'/admin';	
}	
include_once('/templates/account_created.php');
?>