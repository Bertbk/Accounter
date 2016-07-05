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
Lib: Return an array of the accounts associated with the email parameter.
An account is here all the values contained in accounts SQL table
 */
 
 include_once(__DIR__.'/../get_db.php');

function get_accounts_by_email($email_arg)
{
	$db = get_db();
	
	$my_email = filter_var($email_arg, FILTER_VALIDATE_EMAIL);
	
	//Check is email is "valid"
	if(!$my_email)
	{		return array();	}

	try
	{
		$myquery = 'SELECT * 
		FROM '.TABLE_ACCOUNTS.' 
		WHERE email=:my_email';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':my_email', $my_email, PDO::PARAM_STR);
		$prepare_query->execute();
		$reply = $prepare_query->fetchAll();
	}
	catch (Exception $e)
	{
	//	echo 'Fail to connect : ' . $e->getMessage();
	}
	$prepare_query->closeCursor();
	return $reply;
}
