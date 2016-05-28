<?php

function display_payment($payment_arg ,$admin_mode_arg, $edit_participant_arg, $participant_id_to_edit_arg)
{
	$payment = htmlspecialchars($payment_arg);	
	if(is_null($payment_arg)||empty($payment_arg))
	{
		return;
	}
	$admin_mode = (boolean)$admin_mode_arg;
	$edit_participant = (boolean)$edit_participant_arg;
	$participant_id_to_edit_arg = (int)$participant_id_to_edit_arg;
	
if($admin_mode && $edit_participant && $participant['id'] === $participant_id_to_edit)
{
?>
	<form method="post">
		<input type="text" name="name_of_participant" value="<?php echo $participant_to_edit['name']?>" required />
		(<input type="number" name="nb_of_people" value="<?php echo $participant_to_edit['nb_of_people']?>" required />)
		<input type="email" name="email" value="<?php echo $participant_to_edit['email']?>"/>
		<button type="submit" name="submit_edit_participant" value="Submit">Edit</button>
		<button type="submit" name="submit_cancel" value="Submit">Cancel</button> 
	</form>
<?php
}//if
else{ // READ Only
?>
		<?php echo $participant['name']?> 
		(<?php echo $participant['nb_of_people'];if(!empty($participant['email'])){echo ', '.$participant['email'];}?>)

<?php //Edit link
if($admin_mode && !$edit_mode)
{
	$link = BASEURL.'/account/'.$hashid.'/admin/edit_participant/'.$participant['hashid'];
?>
	<a href="<?php echo $link?>">edit me</a>
<?php
}
}
return;
}	
