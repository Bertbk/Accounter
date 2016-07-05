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
Lib: Create a new row in the accounts SQL table
Every parameter are assumed to have been checked.
 */
 
include_once(__DIR__.'/../get_db.php');
include_once(LIBPATH.'/hashid/validate_hashid.php');

function create_new_account($hashid_arg, $hashid_admin_arg, $title_of_account_arg, $author_arg, $contact_email_arg, $description_arg, $date_of_creation_arg, $date_of_expiration_arg)
{
	$db = get_db();

	$hashid = $hashid_arg;
	if(validate_hashid($hashid)== false)
	{
		return array();
	}
	
	$hashid_admin = $hashid_admin_arg;
	if(validate_hashid_admin($hashid_admin)== false)
	{
		return array();
	}

	$title_of_account = $title_of_account_arg;
	$author = $author_arg;
	$contact_email = $contact_email_arg;
	$description = $description_arg;
	$description = (empty($description))?null:$description;
	
	//date : check
	$date_of_creation = $date_of_creation_arg;
	$date_parsed = date_parse($date_of_creation);
	if ($date_parsed == false || !checkdate($date_parsed['month'], $date_parsed['day'], $date_parsed['year'])) {
		$date_of_creation_tmp = date();
		$date_of_creation = date_format($date_of_creation_tmp, 'Y-m-d');
	}
	
	$date_of_expiration = $date_of_expiration_arg;
	$date_parsed = date_parse($date_of_expiration);
	if ($date_parsed == false || !checkdate($date_parsed['month'], $date_parsed['day'], $date_parsed['year'])) {
		$date_of_expiration_tmp = new DateTime();
		$date_of_expiration_tmp->modify('+6 months');
		$date_of_expiration = date_format($date_of_expiration_tmp, 'Y-m-d');
	}

	
	try
	{
		$myquery = 'INSERT INTO '.TABLE_ACCOUNTS.'(id, hashid, hashid_admin, title, author, email, description, date_of_creation, date_of_expiration) 
		VALUES(NULL, :hashid, :hashid_admin, :title, :author, :email, :description, :date_of_creation, :date_of_expiration)';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':hashid', $hashid, PDO::PARAM_STR);
		$prepare_query->bindValue(':hashid_admin', $hashid_admin, PDO::PARAM_STR);
		$prepare_query->bindValue(':title', $title_of_account, PDO::PARAM_STR);
		$prepare_query->bindValue(':author', $author, PDO::PARAM_STR);
		$prepare_query->bindValue(':email', $contact_email, PDO::PARAM_STR);
		$prepare_query->bindValue(':description', $description, (is_null($description))?(PDO::PARAM_NULL):(PDO::PARAM_STR));
		$prepare_query->bindValue(':date_of_creation', $date_of_creation, PDO::PARAM_STR);
		$prepare_query->bindValue(':date_of_expiration', $date_of_expiration, PDO::PARAM_STR);
		$isgood = $prepare_query->execute();
		$prepare_query->closeCursor();
	}
	catch (Exception $e)
	{
//		echo 'Fail to connect: ' . $e->getMessage();
	}
	
	return $isgood;
}
