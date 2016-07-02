
<div id="participants" class="panel panel-primary">
	<div class="panel-heading">
		<h2>Participants: <?php echo (int)$n_participants ?> (<?php echo (int)$n_people ?>)</h2>
	</div>
	<div class="panel-body">
<?php if (is_array($my_participants) && sizeof($my_participants) > 0 ) 
	{
		$participant_to_edit = false;
?>
		<div id="div_participants">
<?php
		foreach($my_participants as $participant)
		{
			if($admin_mode 
			&& $edit_mode == 'participant' 
			&& $participant['hashid'] == $edit_hashid)
			{
				$participant_to_edit = $participant;
				continue;
			}
?>
			<div class="row">
				<?php
				//Check if the name takes all the width or not (edit/delete button ?)
				if(!($admin_mode && !$edit_mode))
				{	?>
				<div class="col-xs-12">
					<div class="fullwidth padding_bill_participant display_bill_participant" style="background-color:<?php echo '#'.$participant['color']?>">
			<?php	echo htmlspecialchars($participant['name']).' ('.(int)$participant['nb_of_people'].')';?>
					</div>
				</div>
<?php		}else{	
				$link_tmp = $link_to_account_admin.'/edit/participant/'.$participant['hashid'].'#edit_tag_'.$participant['hashid'];
?>
				<div class="col-xs-7">
					<div class="fullwidth padding_bill_participant display_bill_participant" style="background-color:<?php echo '#'.$participant['color']?>">	
	<?php 		echo htmlspecialchars($participant['name']).' ('.(int)$participant['nb_of_people'].')';	?>
					</div>
				</div>
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
<?php }//if/else admin
?>
			</div>
<?php }//Foreach
?>
		
<?php if($participant_to_edit !== false){ 
					//EDIT A PARTICIPANT
?>
			<h3 id="<?php echo 'edit_tag_'.$edit_hashid?>">Edit</h3>
			<form method="post"
				action="<?php echo ACTIONPATH.'/update_participant.php'?>"
				class="form-horizontal" role="form">
				<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>">
				<input type="hidden" name="p_hashid_participant" value="<?php echo $participant['hashid']?>">
				<div class="row">
					<input type="text" name="p_name_of_participant" class="form-control-inline col-xs-9"
						value="<?php echo htmlspecialchars($participant['name'])?>" required>
					<input type="number" name="p_nb_of_people" class="form-control-inline col-xs-3"
						min="1" step="1" value="<?php echo (int)$participant['nb_of_people']?>" required>
				</div>
				<div class="row">
					<div class="col-xs-1">
						<button type="submit" name="submit_update_participant" value="Submit"><span class="glyphicon glyphicon-ok"></span></button>
					</div>
					<div class="col-xs-1">
						<button type="submit" name="submit_cancel" value="Submit"
						form="form_cancel">
							<span class="glyphicon glyphicon-remove"></span>
						</button> 
					</div>
				</div>
			</form>
<?php 
			$participant_to_edit = false;
			} ?>
		</div>
<?php 
	}//if !empty(participants)
//==================================
	//Admin only: Add a participant
if($admin_mode && $edit_mode === false)
{ ?>
		<div id="div_add_participant">
			<p id="show_hide_add_participant"><a href="javascript:void(0)">(+) Add a participant</a></p>	
<!-- Add participant-->
			<form method="post" 
				action="<?php echo ACTIONPATH.'/new_participant.php'?>"
				id="show_hide_add_participant_target" 
				class="hidden_at_first">
				<fieldset>
					<legend>Add a participant:</legend>
					<p><em>Fields with asterisk <span class="glyphicon glyphicon-asterisk red"></span> are required</em></p>
					<input type="hidden" name="p_hashid_account" 
						value="<?php echo $my_account['hashid_admin']?>">
					<div class="row">
						<p class="col-xs-10 xs-offset-1">
							Name<span class="glyphicon glyphicon-asterisk red"></span> / Number of people<span class="glyphicon glyphicon-asterisk red"></span>
						</p>
					</div>
					<?php //the "inner_participant_form" is used to add row with JS
					?>
					<div id="inner_participant_form">
						<div class="row form-group">
							<label for="form_set_participant_name_0" class="sr-only">
								Name
							</label>
							<input type="text" name="p_new_participant[0][p_name]" 
								id="form_set_participant_name_0" class="form-control-inline col-xs-9" placeholder="Name" required>
							<label for="form_set_participant_nbpeople_0" class="sr-only">
								Nb. of people
							</label>		
							<input type="number" name="p_new_participant[0][p_nb_of_people]" value="1" 
								id="form_set_participant_nbpeople_0" class="form-control-inline col-xs-3" required>
						</div>
					</div>
					<p><a href="#" onclick="AddParticipantLine();return false;">(+) Add a row</a></p>		
					<button type="submit" name="submit_new_participant" class="btn btn-primary" value="Submit">
						Submit
					</button> 
				</fieldset>
			</form>
		</div>
<?php } //admin mode
?>
	</div>
</div>
