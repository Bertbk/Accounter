<?php
include_once(__DIR__.'/../get_db.php');

include_once(LIBPATH.'/bills/get_bill_by_id.php');
include_once(LIBPATH.'/participants/get_participant_by_id.php');
include_once(LIBPATH.'/bill_participants/get_bill_participants_by_bill_id.php');


function set_bill_participant($account_id_arg, $bill_id_arg, $participant_id_arg, $percent_of_use_arg = "")
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$bill_id = (int)$bill_id_arg;
	$participant_id = (int)$participant_id_arg;
	$percent_of_use = (float)$percent_of_use_arg;
	
	//Check
	$the_bill = get_bill_by_id($account_id, $bill_id);
	if(empty($the_bill)){return false;}
	$the_participant = get_participant_by_id($account_id, $participant_id);
	if(empty($the_participant)){return false;}
	//Same account ? (double check)
	if($the_participant['account_id'] != $the_bill['account_id'])
	{return false;}

	//check that the entry is not already existant
	$bill_participants = get_bill_participants_by_bill_id($account_id, $bill_id);
	foreach ($bill_participants as $bill_part)
	{
			if($bill_part['participant_id'] == $participant_id)
			{
?>
<script type="text/javascript">
  alert('Thi person is already a participant!');
</script>
<?php
				return false;
			}
	}
		
	//Hashid
	do {
		$hashid = bin2hex(openssl_random_pseudo_bytes(8));
	}
	while(!$hashid);

	$percent_of_use = is_null($percent_of_use)?100:$percent_of_use;
	$percent_of_use = empty($percent_of_use)?100:$percent_of_use;
	
	if($percent_of_use > 100 || $percent_of_use < 0)
	{
		return false;
	}
	
	$isgood= false;
	try
	{
		$myquery = 'INSERT INTO '.TABLE_BILL_PARTICIPANTS.'(id, account_id, bill_id, hashid, participant_id, percent_of_usage) 
		VALUES(NULL, :account_id, :bill_id, :hashid, :participant_id, :percent_of_use)';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':bill_id', $bill_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':hashid', $hashid, PDO::PARAM_STR);
		$prepare_query->bindValue(':participant_id', $participant_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':percent_of_use', $percent_of_use, PDO::PARAM_STR);
		$isgood = $prepare_query->execute();
		$prepare_query->closeCursor();
	}
	catch (Exception $e)
	{
		echo 'Fail to connect: ' . $e->getMessage();
	}
	return $isgood;
}