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
 
<?php
//======================
//     PARTICIPANTS
//======================
?>
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
								<div class="fullwidth padding_member display_member" style="background-color:<?php echo '#'.$bdgt_participant_tmp['color']?>">
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



					<h3>Payments</h3>

<?php // List of the payments
	if(isset($my_payments_per_budget[$spreadsheet['id']]) 
		&& is_array($my_payments_per_budget[$spreadsheet['id']])
		&& count($my_payments_per_budget[$spreadsheet['id']]) > 0)
	{
		$this_payment = $my_payments_per_budget[$spreadsheet['id']];
		$cpt_paymt = -1;
	?>
	
					<div class="row text-center">
						<div class="col-xs-4 col-md-2">
							<strong>Creditor</strong>
						</div>
						<div class="col-xs-4 col-md-2">
							<strong>Amount</strong>
						</div>
						<div class="col-xs-4 col-md-2">
							<strong>Debtor</strong>
						</div>
						<div class="hidden-xs hidden-sm hidden-md col-lg-2">
							<strong>Description</strong>
						</div>
						<div class="hidden-xs hidden-sm hidden-md col-lg-2">
							<strong>Date</strong>
						</div>
				<?php if($admin_mode && !$edit_mode){?>
						<div class="hidden-xs hidden-sm col-xs-1">
							<strong>Edit</strong>
						</div>
				<?php }if($admin_mode && !$edit_mode){?>
						<div class="hidden-xs hidden-sm col-xs-1">
							<strong>Delete</strong>
						</div>
				<?php }?>
					</div>
	
	<?php
	
$payment_to_edit = false; // if editing, place the payment after the other
foreach($this_payment as $payment)
{
	$cpt_paymt++;
		if($admin_mode && $edit_mode === 'bdgt_payment' 
		&& $payment['hashid'] === $edit_hashid)
		{ 
			$payment_to_edit = $payment;
			continue;
		}
?>
					<div class="row payment_table">
						<div class="col-xs-5 col-md-2">
							<div class="fullwidth display_member padding_member" style="background-color:<?php echo '#'.$payment['creditor_color']?>">
							<?php echo htmlspecialchars($payment['creditor_name'])?>
							</div>
						</div>
						<div class="col-xs-2 col-md-2">
							 <?php echo (float)$payment['amount']?>&euro;
						</div>
						<div class="col-xs-5 col-md-2">
							<?php if(is_null($payment['debtor_name'])) {?>
							<div class="padding_member display_member group_color">
								Group
							</div>
							<?php }else{ ?>
							<div class="fullwidth display_member padding_member" style="background-color:<?php echo '#'.$payment['debtor_color']?>">			
								<?php echo htmlspecialchars($payment['debtor_name'])?>
							</div>
							<?php }?>
						</div>
						<div class="hidden-xs hidden-sm hidden-md col-lg-2 <?php echo 'description_collapse_'.$cpt_spreadsheet.'_'.$cpt_paymt?>">
							<?php if(!empty($payment['description']))
							{
									echo htmlspecialchars($payment['description']);
							}
							?>
						</div>
						<div class="hidden-xs hidden-sm hidden-md col-lg-2 <?php echo 'description_collapse_'.$cpt_spreadsheet.'_'.$cpt_paymt?>">
							<?php
							if(!empty($payment['date_of_payment']))
							{
								echo date("d/m/Y", strtotime($payment['date_of_payment']));
							}?>
						</div>
						<?php //Collapse button (for mobile>) ?>
						<div class="visible-xs visible-sm col-xs-2">
							<button type="submit" class="btn btn-default" title="Collapse/Expand"
								data-toggle="collapse" data-target=".<?php echo 'description_collapse_'.$cpt_spreadsheet.'_'.$cpt_paymt?>">
								<span class="glyphicon glyphicon-plus"></span>
							</button>
						</div>
		
	<?php //EDIT BUTTON
			if($admin_mode && !$edit_mode)
				{
	?>
						<div class="col-xs-2 col-md-1">
		<?php 
		$link_tmp = $link_to_account_admin.'/edit/bdgt_payment/'.$payment['hashid'].'#edit_tag_'.$payment['hashid'];
		?>
							<form action="<?php echo $link_tmp ?>">
								<button type="submit" class="btn btn-default" title="Edit payment">
									<span class="glyphicon glyphicon-pencil"></span>
								</button>
							</form>
						</div>
						<div class="col-xs-2 col-md-1">
							<form method="post" 
								class="deleteicon"
								action="<?php echo ACTIONPATH.'/spreadsheets/budgets/bdgt_payments/delete_bdgt_payment.php'?>"
									>
									<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>">
									<input type="hidden" name="p_hashid_payment" value="<?php echo $payment['hashid']?>">
									<input type="hidden" name="p_anchor" value="<?php echo '#spreadsheet-'.$cpt_spreadsheet?>">
									<button type="submit" class="btn btn-default confirmation" 
										name="submit_delete_payment" title="Delete payment">
										<span class="glyphicon glyphicon-trash"></span>
									</button>
								</form>
						</div>
		<?php
			}//end if admin + non edit 
			?>
					</div>
<?php	}//foreach current payment 

//Display payment to edit (if exists)
if($payment_to_edit !== false)
{
?>
					<div class="highlight" id="<?php echo 'edit_tag_'.$edit_hashid?>"
					style="background-color: rgba(<?php echo $cred.','.$cgreen.','.$cblue?>, 0.5);">
						<h3>Edit payment</h3>
						<form method="post" id="form_edit_payment_send"
							action="<?php echo ACTIONPATH.'/spreadsheets/budgets/bdgt_payments/update_bdgt_payment.php'?>">
							<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>">
							<input type="hidden" name="p_hashid_spreadsheet" value="<?php echo $spreadsheet['hashid']?>">
							<input type="hidden" name="p_hashid_payment" value="<?php echo $payment_to_edit['hashid']?>">
							<input type="hidden" name="p_anchor" value="<?php echo '#spreadsheet-'.$cpt_spreadsheet?>">
							
							<div class="row form-group">
								<div class="col-xs-12 col-lg-4">
									<label for="form_edit_payment_creditor_<?php echo $cpt_spreadsheet?>">
										Creditor
									</label>
									<div class="input-group">
										<select name="p_hashid_creditor" 
											onchange="DropDownListsBetweenParticipants(this, document.getElementById('form_edit_payment_debtor_<?php echo $spreadsheet['id']?>'))"
											id="form_edit_payment_creditor_<?php echo $cpt_spreadsheet?>" class="form-control selectpicker">
								<?php
											foreach($this_bdgt_participants as $bdgt_participant)
											{
								?>
												<option value="<?php echo $bdgt_participant['hashid']?>"
												<?php if($bdgt_participant['id']==$payment_to_edit['creditor_id']){echo ' selected';}?>>
												<?php echo htmlspecialchars($bdgt_participant['name'])?></option>
								<?php
											}
								?>
										</select>
										<span class="input-group-addon glyphicon glyphicon-user"></span>
									</div>
								</div>
								<div class="col-xs-12 col-lg-4">
									<label for="form_edit_payment_amount_<?php echo $cpt_spreadsheet?>">
										Amount
									</label>
									<div class="input-group">
										<input type="number" step="0.01" min="0" name="p_amount" 
											class="form-control"
											id="form_edit_payment_amount_<?php echo $cpt_spreadsheet?>"
											value="<?php echo (float)$payment_to_edit['amount']?>" required>
										<span class="input-group-addon glyphicon glyphicon-euro"></span>
									</div>
								</div>
								<div class="col-xs-12 col-lg-4">
									<label for="form_edit_payment_debtor_<?php echo $cpt_spreadsheet?>">
										Debtor
									</label>
									<div class="input-group">
										<select name="p_hashid_debtor" 
											id="form_edit_payment_debtor_<?php echo $cpt_spreadsheet?>"
											class="form-control selectpicker">
											<option value="-1" >Group</option>
									<?php
											foreach($this_bdgt_participants as $bdgt_participant)
												{
													if($bdgt_participant['id'] == $payment_to_edit['creditor_id']){continue;}
									?>
													<option value="<?php echo $bdgt_participant['hashid']?>"
													<?php if($bdgt_participant['id']==$payment_to_edit['debtor_id']){echo ' selected';}?>>
													<?php echo htmlspecialchars($bdgt_participant['name'])?></option>
									<?php
												}
									?>
										</select>
										<span class="input-group-addon glyphicon glyphicon-user"></span>
									</div>
								</div>
							</div>
							<div class="row form-group">
								<div class="col-xs-12 col-lg-6">
									<label for="form_edit_payment_desc_<?php echo $spreadsheet['id']?>">
										Description
									</label>
									<div class="input-group">
										<input type="text" name="p_description" class="form-control"
											id="form_edit_payment_desc_<?php echo $spreadsheet['id']?>"
											value="<?php echo htmlspecialchars($payment_to_edit['description'])?>"
											placeholder="Description">
										<span class="input-group-addon glyphicon glyphicon-tag"></span>
									</div>
								</div>
								<?php
									$tmp_date_parsed = date_parse($payment_to_edit['date_of_payment']);
									if ($tmp_date_parsed == false 
									|| !checkdate($tmp_date_parsed['month'], $tmp_date_parsed['day'], $tmp_date_parsed['year'])) 
									{
										$tmp_date_parsed = null;
									}else{
										$tmp_date_parsed=$tmp_date_parsed['day'].'/'.$tmp_date_parsed['month'].'/'.$tmp_date_parsed['year'];
									}
								?>
								<div class="col-xs-12 col-lg-6">
									<label for="form_edit_payment_date_<?php echo $spreadsheet['id']?>">
										Date of payment (dd/mm/yyyy)
									</label>
									<div class="input-group">
										<input type="text" name="p_date_of_payment" 
											class="form-control date_zindex"
											id="form_edit_payment_date_<?php echo $spreadsheet['id']?>"
											value="<?php echo $tmp_date_parsed?>">
										<span class="input-group-addon glyphicon glyphicon-calendar"></span>
									</div>
								</div>
								<?php $tmp_date_parsed = null;?>
							</div>
							<div>
								<button type="submit" name="submit_update_payment" value="Submit" 
									class="btn btn-primary" title="Update payment">
									Submit changes
								</button>
								<button type="submit" name="submit_cancel" 
									value="<?php echo '#spreadsheet-'.$cpt_spreadsheet?>" class="btn btn-primary"
									form="form_cancel" title="Cancel">
									Cancel
								</button>
							</div>
						</form>
					</div>
<?php
$payment_to_edit = false;
} //edit this payment
?>


<?php
//======================
//      PAYMENTS
//======================

}//if payment exist
else
{ 
	if(empty($this_payments))
	{?>
					<p>No payments recorded.</p>		
<?php
	}
	else
	{
	?>
					<p>Please provide participants first.</p>			
<?php
		}
	}//end else payment exists
	if($admin_mode && !$edit_mode)
	{
	//Add a payment
		if(!empty($this_bdgt_participants))
		{
?>
					<form method="post" 
						action="<?php echo ACTIONPATH.'/spreadsheets/budgets/bdgt_payments/new_bdgt_payment.php'?>"
						role="form">
						<fieldset>								
							<legend id="<?php echo 'show_hide_bdgt_add_paymt_'.$cpt_spreadsheet?>"
								class="cursorpointer">
								(+) Add a payment
							</legend>
							<div  class="hidden_at_first"	id="<?php echo 'show_hide_bdgt_add_paymt_'.$cpt_spreadsheet.'_target'?>">
								<div id="<?php echo 'div_option_add_payment_'.$cpt_spreadsheet?>">
									<p><em>Fields with asterisk <span class="glyphicon glyphicon-asterisk red"></span> are required</em></p>
									<input type="hidden" name="p_hashid_account" value ="<?php echo $my_account['hashid_admin']?>">
									<input type="hidden" name="p_hashid_spreadsheet" value ="<?php echo $spreadsheet['hashid']?>">
									<input type="hidden" name="p_anchor" value="<?php echo '#spreadsheet-'.$cpt_spreadsheet?>">
									<div id="div_set_payment_<?php echo $cpt_spreadsheet?>">
										<div class="row form-group">
											<div class="col-xs-12 col-lg-4">
												<label for="<?php echo 'form_set_payment_creditor_'.$cpt_spreadsheet?>_0">Creditor<span class="glyphicon glyphicon-asterisk red"></span></label>
												<div class="input-group">
													<select name="p_payment[0][p_hashid_creditor]" 
														id="form_set_payment_creditor_<?php echo $cpt_spreadsheet?>_0" 
														class="form-control selectpicker" title="Creditor"> 
															<option disabled selected value="null" data-hidden="true">
																Choose a creditor
															</option>
														<?php
															foreach($this_bdgt_participants as $bdgt_participant)
															{ ?>
																<option value="<?php echo $bdgt_participant['hashid']?>">
																	<?php echo htmlspecialchars($bdgt_participant['name'])?>
																</option>
											<?php	} ?>
													</select>
													<span class="input-group-addon glyphicon glyphicon-user"></span>
												</div>
											</div>

											<div class="col-xs-12 col-lg-4">
												<label for="<?php echo 'form_set_payment_amount_'.$cpt_spreadsheet?>_0">
													Amount<span class="glyphicon glyphicon-asterisk red"></span>
												</label>
												<div class="input-group">
													<input type="number" step="0.01" min="0" placeholder="Amount" name="p_payment[0][p_amount]" 
														id="<?php echo 'form_set_payment_amount_'.$cpt_spreadsheet?>_0" required 
														class="form-control" title="Amount">
													<span class="input-group-addon glyphicon glyphicon-euro"></span>
												</div>
											</div>
											<div class="col-xs-12 col-lg-4">
												<label for="<?php echo 'form_set_payment_type_'.$cpt_spreadsheet?>_0">
													Debtor(s)<span class="glyphicon glyphicon-asterisk red"></span>
												</label>
												<div class="input-group">
													<select name="p_payment[0][p_type]" id="<?php echo 'form_set_payment_type_'.$cpt_spreadsheet?>_0"	
														class="form-control selectpicker" title="Group or member to member payment?"
														onchange="DisableEnableElement(this, document.getElementById('<?php echo 'form_set_payment_debtor_'.$cpt_spreadsheet?>_0'))"> 
														<option value="group" selected="selected">Entire Group</option>
														<option value="p2p">People to people</option>
													</select>
													<select name="p_payment[0][p_hashid_debtor][]" id="<?php echo 'form_set_payment_debtor_'.$cpt_spreadsheet?>_0"	
														class="form-control selectpicker" title="Debtor" multiple="multiple" disabled="disabled"> 
														<?php
															foreach($this_bdgt_participants as $bdgt_participant)
															{ ?>
																<option value="<?php echo htmlspecialchars($bdgt_participant['hashid'])?>">
																	<?php echo htmlspecialchars($bdgt_participant['name'])?>
																</option>
												<?php	} ?>
													</select>
													<span class="input-group-addon glyphicon glyphicon-user"></span>
												</div>
											</div>
										</div>
										<div class="row form-group">
											<div class="col-xs-12 col-lg-6">
												<label for="<?php echo 'form_set_payment_desc_'.$cpt_spreadsheet?>_0">
													Description
												</label>
												<div class="input-group">
													<input type="text" name="p_payment[0][p_description]" 
														id="<?php echo 'form_set_payment_desc_'.$cpt_spreadsheet?>_0" 
														class="form-control" placeholder="Description" title="Description">
													<span class="input-group-addon glyphicon glyphicon-tag"></span>
												</div>
											</div>
											<div class="col-xs-12 col-lg-6">
												<label for="<?php echo 'form_set_payment_date_'.$cpt_spreadsheet?>_0">
													Date of payment (dd/mm/yyyy)
												</label>
												<div class="input-group">
													<input type="text" name="p_payment[0][p_date_of_payment]" 
														id="<?php echo 'form_set_payment_date_'.$cpt_spreadsheet?>_0" 
														class="form-control" title="Date of payment">
													<span class="input-group-addon glyphicon glyphicon-calendar"></span>
												</div>
											</div>
										</div>
									</div>
								</div>
	<?php
		$name_of_people = array_column($this_bdgt_participants, 'name');
		$hashid_of_people = array_column($this_bdgt_participants, 'hashid');
	?>
								
								<div>
									<button type="submit" name="submit_new_payment" value="Submit" 
										title="Submit new payment" class="btn btn-primary">
										Submit
									</button>
									<button type="button" name="add_row"
										title="Add a row" class="btn btn-primary" 
										onclick="AddPaymentLine(<?php echo htmlspecialchars(json_encode($name_of_people)) ?>, 
										<?php echo htmlspecialchars(json_encode($hashid_of_people)) ?>,
										<?php echo $cpt_spreadsheet?>);return false;">
										Add a row
									</button>
								</div>
							</div>
						</fieldset>
					</form>
<?php
		}//if bdgt_participants not empty (ie: payment possible)
?>
	<?php
	} //if for displaying possibilities
?>


