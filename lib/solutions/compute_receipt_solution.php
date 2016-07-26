<?php
/**
 * This software is governed by the CeCILL-B license. If a copy of this license
 * is not distributed with this file, you can obtain one at
 * http://www.cecill.info/licences/Licence_CeCILL-B_V1-en.txt
 *
 * Author of Accounter: Bertrand THIERRY (bertrand.thierry1@gmail.com)
 *
 */
 

/* compute a (refund) solution for a particular receipt

	$Refunds is the returned value providing a refund solution between the members:
- Refunds[$uid][$vid] is the amount members of id $uid must give back to members of id $vid
- $uid and $vid belong to the SQL table members
- Refunds[-1][...] stores some usefull values. -1 cannot be an index, so there shouldn't be overlap
*/
 
include_once(__DIR__.'/../get_db.php');

include_once(LIBPATH.'/members/get_members.php');
include_once(LIBPATH.'/spreadsheets/get_spreadsheet_by_id.php');
include_once(LIBPATH.'/spreadsheets/receipts/rcpt_payers/get_rcpt_payers_by_spreadsheet_id.php');
include_once(LIBPATH.'/spreadsheets/receipts/rcpt_recipients/get_rcpt_recipients_by_article_id.php');
include_once(LIBPATH.'/spreadsheets/receipts/rcpt_articles/get_rcpt_articles_by_spreadsheet_id.php');

function compute_receipt_solution($account_id_arg, $receipt_id_arg)
{
	$db = get_db();
		
	$account_id = (int)$account_id_arg;
	$receipt_id = (int)$receipt_id_arg;

	$Refunds = array(array()); //who must give money to who ?
	
	//First, compare receipt and account
	$receipt = get_spreadsheet_by_id($account_id, $receipt_id);
	if(empty($receipt)){return $Refunds;}
	if($receipt['account_id'] != $account_id){return $Refunds;}
		
	//Now get the members
	$my_members = get_members($account_id);
	$my_articles = get_rcpt_articles_by_spreadsheet_id($account_id, $receipt_id);
	$my_receipt_payers = get_rcpt_payers_by_spreadsheet_id($account_id, $receipt_id);
	
	//Init Refunds to 0
	foreach($my_members as $member)
	{
		foreach($my_members as $member2)
		{
			$Refunds[$member['id']][$member2['id']] = 0;
		}
	}
	
	//Set the articles/payments
	foreach ($my_articles as $article)
	{
		$my_price = number_format((float)$article['price'], 2, '.', '');
		$my_recipients = get_rcpt_recipients_by_article_id($account_id, $receipt_id, $article['id']);
		$my_quantity = $article['quantity'];
		foreach($my_recipients as $recipient)
		{
			$uid = $recipient['participant_id'];
			$ratio = (float)$recipient['quantity'] / (float)$my_quantity;
			$price_to_pay = $ratio * $article['price'];
			//divide the price between payer
			foreach($my_receipt_payers as $payer)
			{
				$vid = $payer['participant_id'];
				if($uid == $vid){continue;}
				$member_part = $price_to_pay * $payer['percent_of_payment']/100;
				$Refunds[$uid][$vid] += $member_part;
				$Refunds[$vid][$uid] -= $member_part;
			}
		}
	}
	
	
	//Last loop to avoid 'two direction' refund (A must pay B and B must pay A)
	foreach($my_members as $member)
	{
		$uid = $member['id'];
		foreach($my_members as $other)
		{
			$vid = $other['id'];
			if($uid == $vid){continue;}
			$u_to_v = $Refunds[$uid][$vid];
			$v_to_u = $Refunds[$vid][$uid];
			if($u_to_v > 0 && $v_to_u > 0)
			{
				if($u_to_v > $v_to_u)
				{
					$Refunds[$uid][$vid] = $u_to_v - $v_to_u;
					$Refunds[$vid][$uid] = 0;
				}
				else
				{
					$Refunds[$vid][$uid] = $v_to_u - $u_to_v;
					$Refunds[$uid][$vid] = 0;
				}
			}
		}
	}
	
	return $Refunds;
}
?>