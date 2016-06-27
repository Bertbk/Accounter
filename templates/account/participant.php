
<div id="participants" class="panel panel-primary">
<?php if (is_array($my_participants) && sizeof($my_participants) > 0 ) 
	{
?>
<div class="panel-heading">
	<h2>Participants: <?php echo (int)$n_participants ?> (<?php echo (int)$n_people ?>)</h2>
</div>
<div class="panel-body">

<div id="div_participants">
<?php
	foreach($my_participants as $participant)
	{
?>
<div class="row">
<div class="participant">
<?php
if($admin_mode && $edit_mode == 'participant' && $participant['hashid'] == $edit_hashid)
{
?>
			<form method="post"
			action="<?php echo ACTIONPATH.'/update_participant.php'?>"
			class="form-horizontal" role="form"
			id="<?php echo 'edit_tag_'.$edit_hashid?>">
				<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>">
				<input type="hidden" name="p_hashid_participant" value="<?php echo $participant['hashid']?>">
<?php } ?>
<?php 
if($admin_mode && $edit_mode == 'participant' && $participant['hashid'] == $edit_hashid)
{
	?>
	<div class="col-xs-9 name" style="background-color:<?php echo '#'.$participant['color']?>">
				<input type="text" name="p_name_of_participant" class="form-control"
			value="<?php echo htmlspecialchars($participant['name'])?>" required />
<?php }else{
	//Read only
	?>
	<div class="col-xs-7 name" style="background-color:<?php echo '#'.$participant['color']?>">
	<?php
			echo htmlspecialchars($participant['name']);
	}?>
<?php if($admin_mode && $edit_mode == 'participant' && $participant['hashid'] == $edit_hashid){ 
?>
			<input type="number" name="p_nb_of_people" class="form-control"
			min="1" step="1" value="<?php echo (int)$participant['nb_of_people']?>" required />
<?php }else{?>
		(<?php echo (int)$participant['nb_of_people'];?>)
<?php }?>
	</div>

<?php  //if currently editing
if($admin_mode && $edit_mode == 'participant' && $participant['hashid'] == $edit_hashid)
{
?>
<div class="col-xs-1">
<button type="submit" name="submit_update_participant" value="Submit"><span class="glyphicon glyphicon-ok"></span></button>
</div>
<div class="col-xs-1">
<button type="submit" name="submit_cancel" value="Submit"><span class="glyphicon glyphicon-remove"></span></button> 
</div>
</form>
<?php 
}
//Edit link
else if($admin_mode && !$edit_mode)
{
	$link_tmp = $link_to_account_admin.'/edit/participant/'.$participant['hashid'].'#edit_tag_'.$participant['hashid'];
?>
<div class="col-xs-2">
<form action="<?php echo $link_tmp?>">
    <button type="submit" value="" class="btn btn-default">
				<span class="glyphicon glyphicon-pencil"></span>
		</button>
</form>
</div>
<div class="col-xs-2">
	<form method="post" 
	class="deleteicon"
	action="<?php echo ACTIONPATH.'/delete_participant.php'?>">
		<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>"/>
		<input type="hidden" name="p_hashid_participant" value="<?php echo $participant['hashid']?>"/>
		<button type="submit" class="btn btn-default confirmation" name="submit_delete_participant" value="Submit">
			<span class="glyphicon glyphicon-trash"></span>
		</button>
	</form>
</div>
<?php
}//if/else admin
?>
</div>
</div>

<?php
} //foreach participants
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
		
				
		<div class="row">
			<p class="col-xs-10">
				Name / Number of people
			</p>
			</div>
		
		<div id="inner_participant_form">
			<div  class="row">
				<label for="form_set_participant_name_0" class="sr-only">
					Name
				</label>
					<input type="text" name="p_new_participant[0][p_name]" 
						id="form_set_participant_name_0" class="form-control-inline col-xs-9" placeholder="Name" required />

				<label for="form_set_participant_nbpeople_0" class="sr-only">Nb. of people</label>		
					<input type="number" name="p_new_participant[0][p_nb_of_people]" value="1" 
					 id="form_set_participant_nbpeople_0" class="form-control-inline col-xs-3" required />
			</div>
		</div>
		 <?php /*
		<label for="form_set_participant_color">Color: </label>
		 <input type="text" name="p_color" id="form_set_participant_color"  /><br> */?>
		<p>
			<a href="#" onclick="AddParticipantLine();return false;">
			(+) Add a row
			</a>
		</p>
		
		 <div>
		 <button type="submit" name="submit_new_participant" class="btn btn-primary" value="Submit">Submit</button> 
		 </div>
	  </fieldset>
	</form>
</div>
<?php } //admin mode
?>
</div>
</div>
