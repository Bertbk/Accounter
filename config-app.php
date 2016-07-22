<?php
/**
 * This software is governed by the CeCILL-B license. If a copy of this license
 * is not distributed with this file, you can obtain one at
 * http://www.cecill.info/licences/Licence_CeCILL-B_V1-en.txt
 *
 * Author of Accounter: Bertrand THIERRY (bertrand.thierry1@gmail.com)
 *
*/

if ( !defined('ABSPATH') )
		define('ABSPATH', dirname(__FILE__));
if ( !defined('LIBPATH') )
		define('LIBPATH', ABSPATH . '/lib');
if ( !defined('ACCOUNTPATH') )
		define('ACCOUNTPATH', ABSPATH . '/account');
if ( !defined('SITEPATH') )
		define('SITEPATH', ABSPATH . '/site');

//Config file
$config_exists = file_exists(__DIR__.'/site/config.php');
if(!$config_exists)
{
	header('location: error.php');
	exit;
}
else
{
	$config_array = include(__DIR__.'/site/config.php');
	if ( !defined('PREFIX') )
			define('PREFIX',$config_array['prefix_table']);
	if ( !defined('TABLE_ACCOUNTS') )
		define('TABLE_ACCOUNTS', PREFIX.'accounts');
	if ( !defined('TABLE_SPREADSHEETS') )
		define('TABLE_SPREADSHEETS', PREFIX.'spreadsheets');
	if ( !defined('TABLE_BILL_PARTICIPANTS') )
		define('TABLE_BILL_PARTICIPANTS', PREFIX.'bill_participants');
	if ( !defined('TABLE_PARTICIPANTS') )
		define('TABLE_PARTICIPANTS', PREFIX.'participants');
	if ( !defined('TABLE_PAYMENTS') )
		define('TABLE_PAYMENTS', PREFIX.'payments');
	if ( !defined('TABLE_RECEIPT_PAYERS') )
		define('TABLE_RECEIPT_PAYERS', PREFIX.'receipt_payers');
	if ( !defined('TABLE_RECEIPT_RECEIVERS') )
		define('TABLE_RECEIPT_RECEIVERS', PREFIX.'receipt_receivers');
	if ( !defined('TABLE_RECEIPT_ARTICLES') )
		define('TABLE_RECEIPT_ARTICLES', PREFIX.'receipt_articles');
	if ( !defined('TABLE_RECEIPT_TRANSACTIONS') )
		define('TABLE_RECEIPT_TRANSACTIONS', PREFIX.'receipt_transactions');
	if ( !defined('BASEURL') )
			define('BASEURL',$config_array['baseurl']);
	if ( !defined('ACTIONPATH') )
			define('ACTIONPATH', BASEURL.'/controls/action');

	unset($config_array);
}