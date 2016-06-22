<div id="participants">
<?php if (is_array($my_participants) && sizeof($my_participants) > 0 ) 
	{
?>
<h2>Participants: <?php echo (int)$n_participants ?> (<?php echo (int)$n_people ?>)</h2>
<?php
if($admin_mode && $edit_mode === 'participant')
{ ?>
<form method="post"
action="<?php echo ACTIONPATH.'/update_participant.php'?>"
>
<?php  } ?>
<div id="div_participants">
<?php
	foreach($my_participants as $participant)
	{
?>
<div class="wrapper_participant">
	<div class='participant' style="background-color:<?php echo '#'.$participant['color']?>">
<?php
if($admin_mode && $edit_mode == 'participant' && $participant['hashid'] == $edit_hashid)
{
?>
			<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>">
			<input type="hidden" name="p_hashid_participant" value="<?php echo $participant['hashid']?>">
			<input type="text" name="p_name_of_participant" class="input_name"
			value="<?php echo htmlspecialchars($participant['name'])?>" required />
			(<input type="number" name="p_nb_of_people" class="input_number"
			min="1" step="1" value="<?php echo (int)$participant['nb_of_people']?>" required />)
			<input type="email" name="email" class="input_email"
			value="<?php echo htmlspecialchars($participant['email'])?>"/>
<?php
}//if
else{ // READ Only
?>
		<?php echo htmlspecialchars($participant['name'])?> 
		(<?php echo (int)$participant['nb_of_people'];if(!empty($participant['email'])){echo ', '.htmlspecialchars($participant['email']);}?>)

<?php //Edit link
if($admin_mode && !$edit_mode)
{
	$link_tmp = $link_to_account_admin.'/edit/participant/'.$participant['hashid'];
?>
	<a href="<?php echo $link_tmp?>"><img src="<?php echo BASEURL.'/img/pencil_white.png'?>" alt='Edit participant' class="editicon"></a>
	<form method="post" 
	class="deleteicon"
	action="<?php echo ACTIONPATH.'/delete_participant.php'?>"
		>
		<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>"/>
		<input type="hidden" name="p_hashid_participant" value="<?php echo $participant['hashid']?>"/>
		<span>
		<input type="image" 
			name="submit_delete_participant"
			src="<?php echo BASEURL.'/img/delete_white.png'?>" 
			class="confirmation deleteicon"
			alt="Delete participant"
			>
		</span>
	</form>
<?php
}
?>		
<?php
}//if/else admin
?>
	</div>
	</div>

<?php
} //foreach participants
?>
<?php 
if($admin_mode && $edit_mode === 'participant')
{
?>
<div>
<button type="submit" name="submit_update_participant" value="Submit">Submit change</button>
<button type="submit" name="submit_cancel" value="Submit">Cancel</button> 
</div>
</form>
<?php 
}
?>
</div>
<?php }//if !empty(participants)
?>

<?php
//Admin only
if($admin_mode && $edit_mode===false)
{
	?>
	<div id="div_add_participant">
	<p id="show_hide_add_participant"><a href="javascript:void(0)">(+) Add a participant</a></p>	
<!-- Add participant-->
	<form method="post" 
	action="<?php echo ACTIONPATH.'/new_participant.php'?>"
	id="show_hide_add_participant_target" 
	class="hidden_at_first">
	  <fieldset>
		<legend>Add a participant:</legend>
		<input type="hidden" name="p_hashid_account" 
		value="<?php echo $my_account['hashid_admin']?>" />
		<span>
		<label for="form_set_participant_name">Name: </label>
		<input type="text" name="p_name_of_participant" 
		id="form_set_participant_name" class="input_name" required />
		</span><span>
		<label for="form_set_participant_nbpeople">Nb. of people: </label>
		 <input type="number" name="p_nb_of_people" value="1" 
		 id="form_set_participant_nbpeople" class="input_number" required />
		</span><span>
		<label for="form_set_participant_email">Email adress: </label>
		 <input type="email" name="p_email" 
		 id="form_set_participant_email" class="input_email" />
		 <?php /*
		<label for="form_set_participant_color">Color: </label>
		 <input type="text" name="p_color" id="form_set_participant_color"  /><br> */?>
		 </span>
		 <div>
		 <button type="submit" name="submit_new_participant" value="Submit">Submit</button> 
		 </div>
	  </fieldset>
	</form>
</div>
<?php } //admin mode
?>
</div>
