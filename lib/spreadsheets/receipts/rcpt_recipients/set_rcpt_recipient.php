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
Lib: Add a row in the rcpt_recipients SQL table
 */
include_once(__DIR__.'/../../../get_db.php');

include_once(LIBPATH.'/spreadsheets/get_spreadsheet_by_id.php');
include_once(LIBPATH.'/members/get_member_by_id.php');
include_once(LIBPATH.'/spreadsheets/receipts/rcpt_articles/get_rcpt_article_by_id.php');
include_once(LIBPATH.'/spreadsheets/receipts/rcpt_recipients/get_rcpt_recipients_by_article_id.php');

include_once(LIBPATH.'/hashid/validate_hashid.php');


function set_rcpt_recipient($account_id_arg, $hashid_arg, $spreadsheet_id_arg, $member_id_arg, $article_id_arg, $quantity_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$hashid = $hashid_arg;
	$spreadsheet_id = (int)$spreadsheet_id_arg;
	$member_id = (int)$member_id_arg;	
	$article_id = (int)$article_id_arg;
	$quantity = (float)$quantity_arg;
	
	//Check
	$the_spreadsheet = get_spreadsheet_by_id($account_id, $spreadsheet_id);
	if(empty($the_spreadsheet)){return false;}
	$the_member = get_member_by_id($account_id, $member_id);
	if(empty($the_member)){return false;}
	$the_article = get_rcpt_article_by_id($account_id, $article_id);
	if(empty($the_article)){return false;}
	//Same account ? (double check)
	if($the_member['account_id'] != $the_spreadsheet['account_id'])
	{return 'accounts mismatch';}
	if($the_member['account_id'] != $the_article['account_id'])
	{return 'accounts mismatch';}
	if($the_article['account_id'] != $the_spreadsheet['account_id'])
	{return 'accounts mismatch';}
	if($the_article['account_id'] != $account_id)
	{return 'accounts mismatch';}
	if($the_member['account_id'] != $account_id)
	{return 'accounts mismatch';}
	if($the_spreadsheet['account_id'] != $account_id)
	{return 'accounts mismatch';}


	if(validate_hashid($hashid) === false)
	{return 'Hashid wrong';}

	//check that the entry is not already existant
	/*$rcpt_recipients = get_rcpt_recipients_by_article_id($account_id, $spreadsheet_id, $article_id);
	$total_quantity = $quantity;
	
	foreach ($rcpt_recipients as $spreadsheet_part)
	{
			if($spreadsheet_part['member_id'] == $member_id)
			{
				return 'member already registered';
			}
			$total_quantity += $spreadsheet_part['quantity'];
	}
	if($total_quantity > $the_article['quantity'])
	{return 'too much quantity';}
	*/
	if(is_null(quantity) || (float)$quantity < 0)
	{
		return 'quantity not valid';
	}
	
	$isgood= false;
	try
	{
		$myquery = 'INSERT INTO '.TABLE_RCPT_RECIPIENTS.'(id, hashid, account_id, spreadsheet_id, article_id, member_id, quantity) 
		VALUES(NULL, :hashid, :account_id, :spreadsheet_id, :article_id, :member_id, :quantity)';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':hashid', $hashid, PDO::PARAM_STR);
		$prepare_query->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':spreadsheet_id', $spreadsheet_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':article_id', $article_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':member_id', $member_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':quantity', $quantity, PDO::PARAM_STR);
		$isgood = $prepare_query->execute();
		$prepare_query->closeCursor();
	}
	catch (Exception $e)
	{
		return 'Fail to connect: ' . $e->getMessage();
	}
	return $isgood;
}