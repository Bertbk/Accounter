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

function create_tables()
{
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
		$myquery = 'CREATE TABLE IF NOT EXISTS '.TABLE_ACCOUNTS.' (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    hashid VARCHAR(16) NOT NULL UNIQUE,
    hashid_admin VARCHAR(32) NOT NULL UNIQUE,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
		date_of_creation DATE,
		date_of_expiration DATE,
    description TEXT,
    PRIMARY KEY (id)
		)
		ENGINE=INNODB DEFAULT CHARSET=utf8;';
		$prepare_query = $db->prepare($myquery);
		$isgood = $prepare_query->execute();
		$prepare_query->closeCursor();
	}
	catch (Exception $e)
	{
			return 'Fail to connect: ' . $e->getMessage();
	}
	
	// BILLS
		try
	{
		$myquery = 'CREATE TABLE IF NOT EXISTS '.TABLE_BILLS.'(
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    hashid VARCHAR(16) NOT NULL UNIQUE,
    account_id INT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    color VARCHAR(6) NOT NULL,
    PRIMARY KEY (id),
		INDEX ind_bill_account_id (account_id),
		CONSTRAINT fk_bill_account_id
        FOREIGN KEY (account_id)
        REFERENCES '.TABLE_ACCOUNTS.'(id)
				ON DELETE CASCADE
				ON UPDATE CASCADE
		)
		ENGINE=INNODB DEFAULT CHARSET=utf8;';
		$prepare_query = $db->prepare($myquery);
		$isgood = $prepare_query->execute();
		$prepare_query->closeCursor();
	}
	catch (Exception $e)
	{
			return 'Fail to connect: ' . $e->getMessage();
	}
	
	// PARTICIPANTS
		try
	{
		$myquery = 'CREATE TABLE IF NOT EXISTS '.TABLE_PARTICIPANTS.'(
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    hashid VARCHAR(16) NOT NULL UNIQUE,
    account_id INT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    nb_of_people SMALLINT UNSIGNED NOT NULL DEFAULT 1,
    color VARCHAR(6) NOT NULL,
    PRIMARY KEY (id),
		INDEX ind_part_account_id (account_id),
		CONSTRAINT fk_part_account_id
        FOREIGN KEY (account_id)
        REFERENCES '.TABLE_ACCOUNTS.'(id)
				ON DELETE CASCADE
				ON UPDATE CASCADE
		)
		ENGINE=INNODB DEFAULT CHARSET=utf8;';
		$prepare_query = $db->prepare($myquery);
		$isgood = $prepare_query->execute();
		$prepare_query->closeCursor();
	}
	catch (Exception $e)
	{
			return 'Fail to connect: ' . $e->getMessage();
	}

	
	// BILL_PARTICIPANTS
		try
	{
		$myquery = 'CREATE TABLE IF NOT EXISTS '.TABLE_BILL_PARTICIPANTS.'(
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    hashid VARCHAR(16) NOT NULL UNIQUE,
    account_id INT UNSIGNED NOT NULL,
    bill_id INT UNSIGNED NOT NULL,
    participant_id INT UNSIGNED NOT NULL,
    percent_of_usage DECIMAL(5,2) NOT NULL DEFAULT 100.00,
    PRIMARY KEY (id),
		INDEX ind_billpart_account_id (account_id),
		INDEX ind_billpart_bill_id (bill_id),
		INDEX ind_billpart_participant_id (participant_id),
		CONSTRAINT fk_billpart_account_id
        FOREIGN KEY (account_id)
        REFERENCES '.TABLE_ACCOUNTS.'(id)
				ON DELETE CASCADE
				ON UPDATE CASCADE,
		CONSTRAINT fk_billpart_bill_id
        FOREIGN KEY (bill_id)
        REFERENCES '.TABLE_BILLS.'(id)
				ON DELETE CASCADE
				ON UPDATE CASCADE,
		CONSTRAINT fk_billpart_participant_id
        FOREIGN KEY (participant_id)
        REFERENCES '.TABLE_PARTICIPANTS.'(id)
				ON DELETE CASCADE
				ON UPDATE CASCADE
			)
		ENGINE=INNODB DEFAULT CHARSET=utf8;';
		$prepare_query = $db->prepare($myquery);
		$isgood = $prepare_query->execute();
		$prepare_query->closeCursor();
	}
	catch (Exception $e)
	{
			return 'Fail to connect: ' . $e->getMessage();
	}
	
	// PAYMENTS
	try
	{
		$myquery = 'CREATE TABLE IF NOT EXISTS '.TABLE_PAYMENTS.'(
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    hashid VARCHAR(16) NOT NULL UNIQUE,
    account_id INT UNSIGNED NOT NULL,
    bill_id INT UNSIGNED NOT NULL,
    payer_id INT UNSIGNED NOT NULL,
    cost DECIMAL(12,2) NOT NULL,
    receiver_id INT UNSIGNED,
		description VARCHAR(255),
		date_of_payment DATE,
    PRIMARY KEY (id),
		INDEX ind_paymt_account_id (account_id),
		INDEX ind_paymt_bill_id (bill_id),
		INDEX ind_paymt_payer_id (payer_id),
		INDEX ind_paymt_receiver_id (receiver_id),
		CONSTRAINT fk_paymt_account_id
        FOREIGN KEY (account_id)
        REFERENCES '.TABLE_ACCOUNTS.'(id)
				ON DELETE CASCADE
				ON UPDATE CASCADE,
		CONSTRAINT fk_paymt_bill_id
        FOREIGN KEY (bill_id)
        REFERENCES '.TABLE_BILLS.'(id)
				ON DELETE CASCADE
				ON UPDATE CASCADE,
		CONSTRAINT fk_paymt_payer_id
        FOREIGN KEY (payer_id)
        REFERENCES '.TABLE_BILL_PARTICIPANTS.'(id)
				ON DELETE CASCADE
				ON UPDATE CASCADE,
		CONSTRAINT fk_paymt_receiver_id
        FOREIGN KEY (receiver_id)
        REFERENCES '.TABLE_BILL_PARTICIPANTS.'(id)
				ON DELETE CASCADE
				ON UPDATE CASCADE
		)
		ENGINE=INNODB DEFAULT CHARSET=utf8;';
		$prepare_query = $db->prepare($myquery);
		$isgood = $prepare_query->execute();
		$prepare_query->closeCursor();
	}
	catch (Exception $e)
	{
			return 'Fail to connect: ' . $e->getMessage();
	}
	
	return '';
}