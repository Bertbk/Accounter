<?php
/**
 * This software is governed by the CeCILL-B license. If a copy of this license
 * is not distributed with this file, you can obtain one at
 * http://www.cecill.info/licences/Licence_CeCILL-B_V1-en.txt
 *
 * Author of Accounter: Bertrand THIERRY (bertrand.thierry1@gmail.com)
 *
*/

//Accounter version
const VERSION = '0.9';

if ( !defined('ABSPATH') )
		define('ABSPATH', dirname(__FILE__).'/../..');
	if ( !defined('LIBPATH') )
		define('LIBPATH', ABSPATH . '/lib');
if ( !defined('ACCOUNTPATH') )
		define('ACCOUNTPATH', ABSPATH . '/account');
if ( !defined('SITEPATH') )
		define('SITEPATH', ABSPATH . '/site');


//Config file
$config_exists = file_exists(SITEPATH.'/config.php');
if(!$config_exists)
{
	header('location: error.php');
	exit;
}
else
{
	$config_array = include(__DIR__.'/site/config.php');
	if ( !defined('BASEURL') )
			define('BASEURL',$config_array['baseurl']);
	if ( !defined('ACTIONPATH') )
			define('ACTIONPATH', BASEURL.'/controls/action');
	//TABLES
	if ( !defined('PREFIX') )
			define('PREFIX',$config_array['prefix_table']);
	if ( !defined('TABLE_ACCOUNTS') )
		define('TABLE_ACCOUNTS', PREFIX.'accounts');
	if ( !defined('TABLE_MEMBERS') )
		define('TABLE_MEMBERS', PREFIX.'members');
	if ( !defined('TABLE_SPREADSHEETS') )
		define('TABLE_SPREADSHEETS', PREFIX.'spreadsheets');
	//- BUDGET
	if ( !defined('TABLE_BDGT_PARTICIPANTS') )
		define('TABLE_BDGT_PARTICIPANTS', PREFIX.'bdgt_participants');
	if ( !defined('TABLE_BDGT_PAYMENTS') )
		define('TABLE_BDGT_PAYMENTS', PREFIX.'bdgt_payments');
	//- RECEIPT
	if ( !defined('TABLE_RCPT_PAYERS') )
		define('TABLE_RCPT_PAYERS', PREFIX.'rcpt_payers');
	if ( !defined('TABLE_RCPT_RECIPIENTS') )
		define('TABLE_RCPT_RECIPIENTS', PREFIX.'rcpt_recipients');
	if ( !defined('TABLE_RCPT_ARTICLES') )
		define('TABLE_RCPT_ARTICLES', PREFIX.'rcpt_articles');

	unset($config_array);
}