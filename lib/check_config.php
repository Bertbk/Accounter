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
Lib: check if the config file exists and is "working" (ie: SQL database reachable)
 */

 
function check_config()
{
	$config_exists = file_exists(__DIR__.'../site/config.php');
	if(!$config_exists)
	{return false}
	
	$configs = include(SITEPATH.'/config.php');
	// Connexion to the database
	try
	{
	
	$query_db = 'mysql:host='.$configs['host'].'; dbname='.$configs['dbname'].'; charset=utf8';
	$db = new PDO($query_db, $configs['username'], $configs['password']);
	}
	catch (Exception $e)
	{
			return $e->getMessage();
	}

	return true;
}

