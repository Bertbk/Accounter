<?php
include_once(__DIR__.'/../get_db.php');
include_once(LIBPATH.'/participants/get_participant_by_name.php');
include_once(LIBPATH.'/participants/get_participants.php');

include_once(LIBPATH.'/colors/give_me_next_color.php');

include_once(LIBPATH.'/hashid/validate_hashid.php');

function set_participant($account_id_arg, $hashid_arg, $name_of_participant_arg, $nb_of_people_arg, $email_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$hashid = $hashid_arg;
	$name_of_participant = $name_of_participant_arg;
	$nb_of_people = (int)$nb_of_people_arg;
	$email = $email_arg;
	/*
	if(!is_null($email))
	{
		$email = filter_var($email, FILTER_SANITIZE_EMAIL);
		$email = filter_var($email, FILTER_VALIDATE_EMAIL);
		if($email == false)
		{return false;}
	}
	
	if(validate_hashid($hash_id_arg) == false)
	{return false;}
	
	//Check if a participant with the same name already exists
	$does_this_guy_exists = get_participant_by_name($account_id, $name_of_participant);
	if(!empty($does_this_guy_exists))
	{		return false;	}
*/	
	$the_participants = get_participants($account_id);
	$my_color = give_me_next_color(end($the_participants)['color'], 'participant');
	//When color will come from users, check the reg ex
	
	try
	{
		$myquery = 'INSERT INTO  '.TABLE_PARTICIPANTS.' (id, account_id, hashid, name, nb_of_people, email, color) 
		VALUES(NULL, :account_id, :hashid, :name_of_participant, :nb_of_people, :email, :my_color)';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$prepare_query->bindValue(':hashid', $hashid, PDO::PARAM_STR);
		$prepare_query->bindValue(':name_of_participant', $name_of_participant, PDO::PARAM_STR);
		$prepare_query->bindValue(':nb_of_people', $nb_of_people, PDO::PARAM_INT);
		$prepare_query->bindValue(':email', $email, (is_null($email))?(PDO::PARAM_NULL):(PDO::PARAM_STR));
		$prepare_query->bindValue(':my_color', $my_color, PDO::PARAM_STR);
		$isgood = $prepare_query->execute();
		$prepare_query->closeCursor();
	}
	catch (Exception $e)
	{
		//echo 'Fail to connect: ' . $e->getMessage();
	}
	return $isgood;
}