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
Create the SQL tables.
*/

require_once __DIR__.'/../config-app.php';

	$configs = include(SITEPATH.'/config.php');

	try
	{
	$query_db = 'mysql:host='.$configs['host'].'; dbname='.$configs['dbname'].'; charset=utf8';
	$db = new PDO($query_db, $configs['username'], $configs['password']);
	}
	catch (Exception $e)
	{
			die('Fail to connect : ' . $e->getMessage());
	}
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	try
	{
		$myquery = 'ALTER TABLE '.PREFIX.'bills
			RENAME TO '.TABLE_SPREADSHEETS;
		$prepare_query = $db->prepare($myquery);
		$isgood = $prepare_query->execute();
		$prepare_query->closeCursor();
	}
	catch (Exception $e)
	{
			return 'Fail to connect: ' . $e->getMessage();
	}
	
	return '';
