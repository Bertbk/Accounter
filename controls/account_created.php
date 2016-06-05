<?php
require_once __DIR__.'/../site/config-app.php';
include_once(LIBPATH.'/accounts/get_account.php');
include_once(LIBPATH.'/accounts/get_account_admin.php');

$isdone = true;

//Get Hashid
$hashid_url = "";
empty($_GET['hash']) ? $hashid_url = "" : $hashid_url = htmlspecialchars($_GET['hash']);

$hashid_admin_url = "";
empty($_GET['hash_admin']) ? $hashid_admin_url = "" : $hashid_admin_url = htmlspecialchars($_GET['hash_admin']);

$hashid = $hashid_url;
$hashid_admin = $hashid_admin_url;

//If empty, go back home.
if($hashid_url == "" || strlen($hashid_url) != 16 || $hashid_admin_url == "" || strlen($hashid_admin_url) != 32 )
{
	$isdone = false;
	header ('location:'.BASEURL);
}

$my_account = array();
$my_account = get_account($hashid);
$my_account_admin = get_account_admin($hashid_admin);

if(empty($my_account) ||empty($my_account_admin))
{
	$isdone = false;
	header ('location:'.BASEURL);
}

if($isdone)
{
	$link_contrib = BASEURL.'/account/'.$hashid;
	$link_admin = BASEURL.'/account/'.$hashid_admin.'/admin';	
}	
include_once(ABSPATH.'/templates/account_created.php');
?>