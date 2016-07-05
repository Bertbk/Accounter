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
Try to connect to the datable providing the good parameters.
*/

function test_db($host_arg, $username_arg, $password_arg, $dbname_arg)
{
	$host = htmlspecialchars($host_arg);
	$username = htmlspecialchars($username_arg);
	$password = htmlspecialchars($password_arg);
	$password = is_null($password)?'':$password;
	$dbname = htmlspecialchars($dbname_arg);
	
	try
	{
	$query_db = 'mysql:host='.$host.'; dbname='.$dbname.'; charset=utf8';
	$db = new PDO($query_db, $username, $password);
	}
	catch (Exception $e)
	{
			//die('Fail to connect : ' . $e->getMessage());
			return false;
	}
	return true;
}