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
Template to display a budget sheet :
- Participants
- Payments
 */
 ?>
 
<?php // PARTICIPANTS ?>
	<h3 id="<?php echo 'bdgt_participants_'.$cpt_spreadsheet?>">
		Participants (% of beneficit)
	</h3>
<?php // Display the current participant of this spreadsheet
$this_bdgt_participants = $my_budget_participants[$spreadsheet['id']];
if(!empty($this_bdgt_participants))
{
?>
	<div class="row">		
<?php
	$participant_to_edit = false; // if editing, place a button after the list
	$cpt_bdgt_participant = -1;
	foreach($this_bdgt_participants as $key => $bdgt_participant)
	{
		$cpt_bdgt_participant++;
		if($admin_mode === true
			&& $edit_mode === 'bdgt_participant' 
			&& $edit_hashid === $bdgt_participant['hashid'])
		{
			//We found the bdgt_participant to be edited. Will be displayed after the other.
			$participant_to_edit = $key;
			continue;
		}
		?>
		<div class="col-xs-12 col-sm-6 col-lg-4 bdgt_participant">
			<div class="floatleft width60 padding_member display_member" style="background-color:<?php echo '#'.$bdgt_participant['color']?>">
				<?php
					echo htmlspecialchars($bdgt_participant['name']).' ('.(float)$bdgt_participant['percent_of_benefit'].'%)';
				?>
			</div>
			<?php
				if($admin_mode === true
				&& $edit_mode === false){
					$link_tmp = $link_to_account_admin.'/edit/bdgt_participant/'.$bdgt_participant['hashid'].'#edit_tag_'.$bdgt_participant['hashid'];
					?>
			<div class="zeromargin floatleft">
						<form action="<?php echo $link_tmp?>">
							<button type="submit" value="" class="btn btn-default" title="Edit participant">
									<span class="glyphicon glyphicon-pencil"></span>
							</button>
						</form>
			</div>
			<div class="bdgt_participant_button">
				<form method="post" 
				class="deleteicon"
				action="<?php echo ACTIONPATH.'/spreadsheets/budgets/bdgt_participants/delete_bdgt_participant.php'?>">		
					<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>">
					<input type="hidden" name="p_hashid_bdgt_participant" value="<?php echo $bdgt_participant['hashid']?>">
					<input type="hidden" name="p_anchor" value="<?php echo '#spreadsheet-'.$cpt_spreadsheet?>">
					<button type="submit" class="btn btn-default confirmation" 
						name="submit_delete_bdgt_participant" title="Delete participant">
						<span class="glyphicon glyphicon-trash"></span>
					</button>
				</form>
			</div>
	<?php	} ?>
		</div>
<?php
}//foreach participant in this spreadsheet
?>
	</div> <?php //row ?>
<?php
	
	if($participant_to_edit !== false)
	{
		$bdgt_participant_tmp = $this_bdgt_participants[$participant_to_edit];
	//Edit activated on a bdgt_participant of THIS spreadsheet :
	?>
				<div class="highlight"  id="<?php echo 'edit_tag_'.$edit_hashid?>"
				style="background-color: rgba(<?php echo $cred.','.$cgreen.','.$cblue?>, 0.5);">
					<h3>Edit participant of <?php echo htmlspecialchars($bdgt_participant_tmp['name']);?></h3>
					<form method="post" action="<?php echo ACTIONPATH.'/spreadsheets/budgets/bdgt_participants/update_bdgt_participant.php'?>">

						<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>">
						<input type="hidden" name="p_hashid_bdgt_participant" value="<?php echo $bdgt_participant_tmp['hashid']?>">
						<input type="hidden" name="p_anchor" value="<?php echo '#spreadsheet-'.$cpt_spreadsheet?>">

						<div class="row form-group row-no-padding">
							<div class="col-xs-6 col-sm-5 col-md-4">
								<div class="fullwidth padding_bdgt_participant display_bdgt_participant" style="background-color:<?php echo '#'.$bdgt_participant_tmp['color']?>">
									<?php echo htmlspecialchars($bdgt_participant_tmp['name']);?>
								</div>
							</div>
							<div class="col-xs-6 col-sm-5 col-md-4">
								<div class="input-group">
									<input type="number" step="0.01" min="0" max="100" name="p_percent_of_use"
										class="form-control" value="<?php echo (float)$bdgt_participant_tmp['percent_of_benefit']?>" required>
									<span class="input-group-addon">%</span>
								</div>
							</div>
						</div>
						<button type="submit" name="submit_update_bdgt_participant" 
							value="Submit" class="btn btn-primary" title="Submit changes">
							Submit changes
						</button> 
						<button type="submit" name="submit_cancel" value="<?php echo '#spreadsheet-'.$cpt_spreadsheet?>" 
							form="form_cancel" class="btn btn-primary" title="Cancel">
						 Cancel
						</button>
					</form>
				</div>
<?php	
//reset temporary variables
$participant_to_edit = false;
$bdgt_participant_tmp  = null;
	}
	?>
<?php }//if my_bdgt_participants != empty ?>

	<?php
			if($admin_mode && !$edit_mode)
			{ //Display possibilities
				//Assign a participant (if there are free guys)
				$this_available_bdgt_members = $my_available_bdgt_members[$spreadsheet['id']];
				if(!empty($this_available_bdgt_members))
				{
	?>
					<form method="post"	enctype="multipart/form-data"
						action="<?php echo ACTIONPATH.'/spreadsheets/budgets/bdgt_participants/new_bdgt_participant.php'?>">
						<fieldset>
							<legend id="<?php echo 'show_hide_spreadsheet_add_part_'.$cpt_spreadsheet?>"
								class="cursorpointer">
								(+) Add a participant
							</legend>
							<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>">
							<input type="hidden" name="p_hashid_spreadsheet" value="<?php echo $spreadsheet['hashid']?>">
							<input type="hidden" name="p_anchor" value="<?php echo '#spreadsheet-'.$cpt_spreadsheet?>">
							<div class="hidden_at_first"
							id=<?php echo 'show_hide_spreadsheet_add_part_'.$cpt_spreadsheet.'_target'?>>

<?php
							$cpt = -1;
							foreach($this_available_bdgt_members as $member)
							{
								$cpt++;
		?>
								<div class="row form-group assign_bdgt_participant">
									<div class="col-xs-12 col-md-6 col-lg-4 ">
										<div>
											<input type="checkbox" name="p_participant['<?php echo $cpt?>'][p_hashid_member]" 
												value="<?php echo $member['hashid']?>" title="Member"
												id="<?php echo'assign_participant_'.$cpt_spreadsheet.'_'.$cpt?>" >
											<div class="[ btn-group ] fullwidth" style="overflow:hidden">
												<label for="<?php echo 'assign_participant_'.$cpt_spreadsheet.'_'.$cpt?>"
													class="[ btn btn-default ] btn-assign_bdgt_participant">
													<span class="[ glyphicon glyphicon-ok ]"></span>
													<span> </span>
												</label>
												<span class="span-assign_bdgt_participant" >
													<label for="<?php echo 'assign_participant_'.$cpt_spreadsheet.'_'.$cpt?>" 
														class="[ btn btn-default active ] btn-assign_bdgt_participant2"
														style="background-color:<?php echo '#'.$member['color']?>">
															<?php echo htmlspecialchars($member['name'])?>
													</label>
												</span>
											</div>
										</div>
									</div>
									<div class="col-xs-12 col-md-6 col-lg-4">
										<label for="<?php echo 'form_available_percent_'.$cpt_spreadsheet.'_'.$member['id']?>" 
											class="sr-only">
											Percentage of use
										</label>
										<div class="input-group">
											<input name="p_participant['<?php echo $cpt?>'][p_percent_of_benefit]" type="number"
														class="form-control" step="0.01" min="0" max="100"	value="100" 
														id="<?php echo 'form_available_percent_'.$cpt_spreadsheet.'_'.(int)$member['id']?>"
														title="Percentage of benefit">
											<span class="input-group-addon">%</span>
										</div>
									</div>
								</div>
		<?php
				}//for each member
		?>
								<div class="row form-group assign_bdgt_participant">
									<div class="col-xs-6 col-md-4 col-lg-3 ">
										<div>
											<input type="checkbox" name="" 
												id="<?php echo'form_select_all_participant_'.$cpt_spreadsheet?>"
												onchange="SelectAllparticipant(this, '<?php echo "assign_participant_".$cpt_spreadsheet."_"?>')">
											<div class="[ btn-group ] fullwidth" style="overflow:hidden">
												<label for="<?php echo 'form_select_all_participant_'.$cpt_spreadsheet?>"
													class="[ btn btn-default ] btn-assign_bdgt_participant">
													<span class="[ glyphicon glyphicon-ok ]"></span>
													<span> </span>
												</label>
												<span class="span-assign_bdgt_participant" >
													<label for="<?php echo 'form_select_all_participant_'.$cpt_spreadsheet?>" 
														class="[ btn btn-default active ] btn-select_all_participant">
															Select all
													</label>
												</span>
											</div>
										</div>
									</div>
									<div class="col-xs-12 col-md-6 col-lg-5 ">
										<div class="input-group">
											<span class="input-group-addon btn btn-default"
											onclick="SetAllValue('<?php echo 'form_set_all_percent_'.$cpt_spreadsheet?>', '<?php echo "form_available_percent_".$cpt_spreadsheet."_"?>')">
												Set to all
											</span>
											<input name="" type="number"
												class="form-control"
												step="0.01" min="0" max="100"	value="100" 
												title="Percentage of usage"
												id="<?php echo 'form_set_all_percent_'.$cpt_spreadsheet?>">
											<span class="input-group-addon">%</span>
										</div>
									</div>
								</div>
								<div class="row form-group">
									<div class="col-xs-12">
										<button type="submit" name="submit_new_bdgt_participant" 
											value="Submit" class="btn btn-primary" title="Submit new participant">
											Submit
										</button>
									</div>
								</div>
							</div>
						</fieldset>
					</form>
<?php
		} //if empty free_participants
	}//if admin
?>




