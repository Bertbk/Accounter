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
Control page launched when an account has been created.
This page get as argument (GET) the hashid and hashid_admin of a page.
If they correspond to the same account, show them successfully.
Otherwise: go back home.
 */

require_once __DIR__.'/../config-app.php';
include_once(LIBPATH.'/accounts/get_account.php');
include_once(LIBPATH.'/accounts/get_account_admin.php');

include_once(LIBPATH.'/hashid/validate_hashid.php');

//Get Hashid
$hashid_url = "";
empty($_GET['hash']) ? $hashid_url = "" : $hashid_url = htmlspecialchars($_GET['hash']);

$hashid_admin_url = "";
empty($_GET['hash_admin']) ? $hashid_admin_url = "" : $hashid_admin_url = htmlspecialchars($_GET['hash_admin']);

$hashid = $hashid_url;
$hashid_admin = $hashid_admin_url;

//If empty, go back home.
if(!validate_hashid($hashid)
	|| !validate_hashid_admin($hashid_admin)
	)
{
	header ('location:'.BASEURL);
	exit;
}

//check if the hashids link to the right account (same and existant)
$my_account = array();
$my_account = get_account($hashid);
$my_account_admin = array();
$my_account_admin = get_account_admin($hashid_admin);

if(empty($my_account) 
	|| empty($my_account_admin)
  || $my_account['id'] !== $my_account_admin['id'])
{
	header ('location:'.BASEURL);
	exit;
}

$link_contrib = BASEURL.'/account/'.$hashid;
$link_admin = BASEURL.'/account/'.$hashid_admin.'/admin';	

include_once(ABSPATH.'/templates/account_created.php');
