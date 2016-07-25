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
		$myquery = 'CREATE TABLE IF NOT EXISTS '.TABLE_SPREADSHEETS.'(
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    hashid VARCHAR(16) NOT NULL UNIQUE,
    account_id INT UNSIGNED NOT NULL,
    type_of_sheet VARCHAR(255) NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    color VARCHAR(6) NOT NULL,
    PRIMARY KEY (id),
		INDEX ind_spreadsheet_account_id (account_id),
		CONSTRAINT fk_spreadsheet_account_id
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
	
	// MEMBERS
		try
	{
		$myquery = 'CREATE TABLE IF NOT EXISTS '.TABLE_MEMBERS.'(
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    hashid VARCHAR(16) NOT NULL UNIQUE,
    account_id INT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    nb_of_people SMALLINT UNSIGNED NOT NULL DEFAULT 1,
    color VARCHAR(6) NOT NULL,
    PRIMARY KEY (id),
		INDEX ind_members_account_id (account_id),
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

	
	// (BUDGET) PARTICIPANT
		try
	{
		$myquery = 'CREATE TABLE IF NOT EXISTS '.TABLE_BDGT_PARTICIPANTS.'(
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    hashid VARCHAR(16) NOT NULL UNIQUE,
    account_id INT UNSIGNED NOT NULL,
    spreadsheet_id INT UNSIGNED NOT NULL,
    member_id INT UNSIGNED NOT NULL,
    percent_of_benefit DECIMAL(5,2) NOT NULL DEFAULT 100.00,
    PRIMARY KEY (id),
		INDEX ind_bdgt_participant_account_id (account_id),
		INDEX ind_bdgt_participant_spreadsheet_id (spreadsheet_id),
		INDEX ind_bdgt_participant_member_id (member_id),
		CONSTRAINT fk_bdgt_participant_account_id
        FOREIGN KEY (account_id)
        REFERENCES '.TABLE_ACCOUNTS.'(id)
				ON DELETE CASCADE
				ON UPDATE CASCADE,
		CONSTRAINT fk_bdgt_participant_spreadsheet_id
        FOREIGN KEY (spreadsheet_id)
        REFERENCES '.TABLE_SPREADSHEETS.'(id)
				ON DELETE CASCADE
				ON UPDATE CASCADE,
		CONSTRAINT fk_bdgt_participant_member_id
        FOREIGN KEY (member_id)
        REFERENCES '.TABLE_MEMBERS.'(id)
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
	
	// (BUDGET) PAYMENTS
	try
	{
		$myquery = 'CREATE TABLE IF NOT EXISTS '.TABLE_PAYMENTS.'(
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    hashid VARCHAR(16) NOT NULL UNIQUE,
    account_id INT UNSIGNED NOT NULL,
    budget_id INT UNSIGNED NOT NULL,
    creditor_id INT UNSIGNED NOT NULL,
    amount DECIMAL(12,2) NOT NULL,
    debtor_id INT UNSIGNED,
		description VARCHAR(255),
		date_of_payment DATE,
    PRIMARY KEY (id),
		INDEX ind_bdgt_paymt_account_id (account_id),
		INDEX ind_bdgt_paymt_budget_id (budget_id),
		INDEX ind_bdgt_paymt_payer_id (creditor_id),
		INDEX ind_bdgt_paymt_recipient_id (debtor_id),
		CONSTRAINT fk_paymt_account_id
        FOREIGN KEY (account_id)
        REFERENCES '.TABLE_ACCOUNTS.'(id)
				ON DELETE CASCADE
				ON UPDATE CASCADE,
		CONSTRAINT fk_paymt_budget_id
        FOREIGN KEY (budget_id)
        REFERENCES '.TABLE_SPREADSHEETS.'(id)
				ON DELETE CASCADE
				ON UPDATE CASCADE,
		CONSTRAINT fk_paymt_payer_id
        FOREIGN KEY (payer_id)
        REFERENCES '.TABLE_BUDGET_PARTICIPANTS.'(id)
				ON DELETE CASCADE
				ON UPDATE CASCADE,
		CONSTRAINT fk_paymt_recipient_id
        FOREIGN KEY (debtor_id)
        REFERENCES '.TABLE_BUDGET_PARTICIPANTS.'(id)
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
	
	// (RECEIPT) PAYERS
	try
	{
		$myquery = 'CREATE TABLE IF NOT EXISTS '.TABLE_RCPT_PAYERS.'(
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    hashid VARCHAR(16) NOT NULL UNIQUE,
    account_id INT UNSIGNED NOT NULL,
    spreadsheet_id INT UNSIGNED NOT NULL,
    member_id INT UNSIGNED NOT NULL,
    percent_of_payment DECIMAL(5,2) NOT NULL DEFAULT 100.00,
    PRIMARY KEY (id),
		INDEX ind_rcpt_payer_account_id (account_id),
		INDEX ind_rcpt_payer_spreadsheet_id (spreadsheet_id),
		INDEX ind_rcpt_payer_member_id (member_id),
		CONSTRAINT fk_rcpt_payer_account_id
        FOREIGN KEY (account_id)
        REFERENCES '.TABLE_ACCOUNTS.'(id)
				ON DELETE CASCADE
				ON UPDATE CASCADE,
		CONSTRAINT fk_rcpt_payer_spreadsheet_id
        FOREIGN KEY (spreadsheet_id)
        REFERENCES '.TABLE_SPREADSHEETS.'(id)
				ON DELETE CASCADE
				ON UPDATE CASCADE,
		CONSTRAINT fk_rcpt_payer_member_id
        FOREIGN KEY (member_id)
        REFERENCES '.TABLE_MEMBERS.'(id)
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

	// (RECEIPT) ARTICLES
	try
	{
		$myquery = 'CREATE TABLE IF NOT EXISTS '.TABLE_RCPT_ARTICLES.'(
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    hashid VARCHAR(16) NOT NULL UNIQUE,
    account_id INT UNSIGNED NOT NULL,
    spreadsheet_id INT UNSIGNED NOT NULL,
    member_id INT UNSIGNED NOT NULL,
    description VARCHAR(255) NOT NULL,
    price DECIMAL(12,2) NOT NULL,
    quantity DECIMAL(12,2) NOT NULL,
    PRIMARY KEY (id),
		INDEX ind_rcpt_article_account_id (account_id),
		INDEX ind_rcpt_article_spreadsheet_id (spreadsheet_id),
		CONSTRAINT fk_rcpt_article_account_id
			FOREIGN KEY (account_id)
			REFERENCES '.TABLE_ACCOUNTS.'(id)
			ON DELETE CASCADE
			ON UPDATE CASCADE,
		CONSTRAINT fk_rcpt_article_spreadsheet_id
			FOREIGN KEY (spreadsheet_id)
			REFERENCES '.TABLE_SPREADSHEETS.'(id)
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

	// (RECEIPT) BENEFICIARIES
	try
	{
		$myquery = 'CREATE TABLE IF NOT EXISTS '.TABLE_RCPT_RECIPIENTS.'(
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    hashid VARCHAR(16) NOT NULL UNIQUE,
    account_id INT UNSIGNED NOT NULL,
    spreadsheet_id INT UNSIGNED NOT NULL,
    member_id INT UNSIGNED NOT NULL,
    article_id INT UNSIGNED NOT NULL,
    quantity DECIMAL(12,2) NOT NULL,
    PRIMARY KEY (id),
		INDEX ind_rcpt_recipient_account_id (account_id),
		INDEX ind_rcpt_recipient_spreadsheet_id (spreadsheet_id),
		INDEX ind_rcpt_recipient_member_id (member_id),
		INDEX ind_rcpt_recipient_article_id (article_id),
		CONSTRAINT fk_rcpt_recipient_account_id
        FOREIGN KEY (account_id)
        REFERENCES '.TABLE_ACCOUNTS.'(id)
				ON DELETE CASCADE
				ON UPDATE CASCADE,
		CONSTRAINT fk_rcpt_recipient_spreadsheet_id
        FOREIGN KEY (spreadsheet_id)
        REFERENCES '.TABLE_SPREADSHEETS.'(id)
				ON DELETE CASCADE
				ON UPDATE CASCADE,
		CONSTRAINT fk_rcpt_recipient_member_id
        FOREIGN KEY (member_id)
        REFERENCES '.TABLE_MEMBERS.'(id)
				ON DELETE CASCADE
				ON UPDATE CASCADE,
		CONSTRAINT fk_rcpt_recipient_article_id
        FOREIGN KEY (article_id)
        REFERENCES '.TABLE_RCPT_ARTICLES.'(id)
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