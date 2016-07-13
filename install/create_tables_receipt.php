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

function create_tables_receipt()
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
	
	// RECEIPTS
		try
	{
		$myquery = 'CREATE TABLE IF NOT EXISTS '.TABLE_RECEIPTS.'(
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    hashid VARCHAR(16) NOT NULL UNIQUE,
    account_id INT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    color VARCHAR(6) NOT NULL,
    PRIMARY KEY (id),
		INDEX ind_receipt_account_id (account_id),
		CONSTRAINT fk_receipt_account_id
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
	
	// RECEIPT ARTICLE
		try
	{
		$myquery = 'CREATE TABLE IF NOT EXISTS '.TABLE_RECEIPT_ARTICLES.'(
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    hashid VARCHAR(16) NOT NULL UNIQUE,
    account_id INT UNSIGNED NOT NULL,
    receipt_id INT UNSIGNED NOT NULL,
		product VARCHAR(255) NOT NULL,
		quantity DECIMAL(10,2) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (id),
		INDEX ind_receipt_article_account_id (account_id),
		INDEX ind_receipt_article_receipt_id (receipt_id),
		CONSTRAINT fk_receipt_article_account_id
        FOREIGN KEY (account_id)
        REFERENCES '.TABLE_ACCOUNTS.'(id)
				ON DELETE CASCADE
				ON UPDATE CASCADE,
		CONSTRAINT fk_receipt_article_receipt_id
        FOREIGN KEY (receipt_id)
        REFERENCES '.TABLE_RECEIPTS.'(id)
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
	
	// RECEIPT PAYER
	try
	{
		$myquery = 'CREATE TABLE IF NOT EXISTS '.TABLE_RECEIPT_PAYERS.'(
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    hashid VARCHAR(16) NOT NULL UNIQUE,
    account_id INT UNSIGNED NOT NULL,
    receipt_id INT UNSIGNED NOT NULL,
    payer_id INT UNSIGNED NOT NULL,
    percent_of_payment DECIMAL(5,2) NOT NULL,
    PRIMARY KEY (id),
		INDEX ind_receipt_article_account_id (account_id),
		INDEX ind_receipt_article_receipt_id (receipt_id),
		INDEX ind_receipt_article_payer_id (payer_id),
		CONSTRAINT fk_receipt_payer_account_id
        FOREIGN KEY (account_id)
        REFERENCES '.TABLE_ACCOUNTS.'(id)
				ON DELETE CASCADE
				ON UPDATE CASCADE,
		CONSTRAINT fk_receipt_payer_receipt_id
        FOREIGN KEY (receipt_id)
        REFERENCES '.TABLE_RECEIPTS.'(id)
				ON DELETE CASCADE
				ON UPDATE CASCADE,
		CONSTRAINT fk_receipt_payer_payer_id
        FOREIGN KEY (payer_id)
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
	
	// RECEIPT RECEIVER
	try
	{
		$myquery = 'CREATE TABLE IF NOT EXISTS '.TABLE_RECEIPT_RECEIVERS.'(
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    hashid VARCHAR(16) NOT NULL UNIQUE,
    account_id INT UNSIGNED NOT NULL,
    receipt_id INT UNSIGNED NOT NULL,
    receiver_id INT UNSIGNED NOT NULL,
    article_id INT UNSIGNED NOT NULL,
    quantity DECIMAL(12,2) NOT NULL,
    PRIMARY KEY (id),
		INDEX ind_receipt_receiver_account_id (account_id),
		INDEX ind_receipt_receiver_receipt_id (receipt_id),
		INDEX ind_receipt_receiver_receiver_id (receiver_id),
		INDEX ind_receipt_receiver_article_id (article_id),
		CONSTRAINT fk_receipt_receiver_account_id
        FOREIGN KEY (account_id)
        REFERENCES '.TABLE_ACCOUNTS.'(id)
				ON DELETE CASCADE
				ON UPDATE CASCADE,
		CONSTRAINT fk_receipt_receiver_receipt_id
        FOREIGN KEY (receipt_id)
        REFERENCES '.TABLE_RECEIPTS.'(id)
				ON DELETE CASCADE
				ON UPDATE CASCADE,
		CONSTRAINT fk_receipt_receiver_receiver_id
        FOREIGN KEY (receiver_id)
        REFERENCES '.TABLE_PARTICIPANTS.'(id)
				ON DELETE CASCADE
				ON UPDATE CASCADE,
		CONSTRAINT fk_receipt_receiver_article_id
        FOREIGN KEY (article_id)
        REFERENCES '.TABLE_RECEIPT_ARTICLES.'(id)
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
	
	return 'Yahou';
}

create_tables_receipt();