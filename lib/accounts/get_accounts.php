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
Lib: Return an array of every rows of the accounts SQL table
 */
 
include_once(__DIR__.'/../get_db.php');

function get_accounts()
{
	$db = get_db();

	try
	{
		$myquery = 'SELECT * FROM '.TABLE_ACCOUNTS;
		$prepare_query = $db->prepare($myquery);
		$prepare_query->execute();
		$reponse = $prepare_query->fetchAll();
	}
	catch (Exception $e)
	{
//		echo 'Fail to connect : ' . $e->getMessage();
	}
	$prepare_query->closeCursor();
	return $reponse;
}
