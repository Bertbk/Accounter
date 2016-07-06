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
Template to display all the bills with their participants and payments
 */
 ?>
 
<!-- BILLS -->
<!-- Loop on the bills -->
<?php if (is_array($my_bills) && sizeof($my_bills) > 0 )
{
$cpt_bill = -1;
foreach($my_bills as $bill)
{
	$cpt_bill ++;
	$this_bill_participants = array();
	$this_free_bill_participants = array();
	if(!empty($my_bill_participants[$bill['id']]))
	{$this_bill_participants = $my_bill_participants[$bill['id']];}
	if(!empty($my_free_bill_participants[$bill['id']]))
	{	$this_free_bill_participants = $my_free_bill_participants[$bill['id']];}
?>
<div class="row bill <?php echo 'bill-'.$cpt_bill?>" 
	id="<?php echo 'bill-'.$cpt_bill?>">
	<div class="col-xs-12">
		<div class="panel panel-primary">
			<div class="panel-heading cursorpointer" data-toggle="collapse" data-target="#<?php echo 'panel-body_bill'.$cpt_bill?>">
				<div class="row">
	<?php 
//Edit the Bill (name, description, ...)
if($admin_mode 
				&& $edit_mode === 'bill' 
				&& $edit_hashid === $bill['hashid'])
				{
?>
					<div class="col-xs-12" id="<?php echo 'edit_tag_'.$edit_hashid?>">
						<form method="post" id="<?php echo "form_update_bill_".$cpt_bill?>"
							action="<?php echo ACTIONPATH.'/update_bill.php'?>">
							<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>">
							<input type="hidden" name="p_hashid_bill" value="<?php echo $bill['hashid']?>">
							<input type="hidden" name="p_anchor" value="<?php echo '#bill-'.$cpt_bill?>">
							<h2>
								<label for="form_edit_bill_name">Title:</label>
								<input type="text" name="p_title_of_bill" id="form_edit_bill_name"
								class="form-control"	value="<?php echo htmlspecialchars($bill['title'])?>" required 
								title="Title">
							</h2>
						</form>
					</div>
<?php } else{
?>

					<div class="col-md-9 ">
						<h2 class="bill_title">
							<?php echo ($cpt_bill+1).'. '.htmlspecialchars($bill['title']) ?>
						</h2>	
					</div>
					<div class="col-md-3">
		<?php
					if($admin_mode && $edit_mode === false)
					{
						$link_tmp = $link_to_account_admin.'/edit/bill/'.$bill['hashid'].'#edit_tag_'.$bill['hashid'];
		?>
						<div class="button_account_title">
							<button type="submit" class="btn btn-danger dropdown-toggle" 
								data-toggle="dropdown" title="Delete...">
								<span class="glyphicon glyphicon-trash"></span>
								<span class="sr-only">Delete...</span>
								<span class="caret"></span>
							</button>
							<ul class="dropdown-menu" role="menu">
								<li>
									<form method="post" action="<?php echo ACTIONPATH.'/remove_bill_participants.php'?>">
										<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>">
										<input type="hidden" name="p_hashid_bill" value="<?php echo $bill['hashid']?>">
										<input type="hidden" name="p_anchor" value="<?php echo '#bill-'.$cpt_bill?>">
										<button type="submit" class="btn btn-link confirmation" 
											name="submit_remove_all_participations" onclick="event.stopPropagation();">
											Remove all participations
										</button>
									</form>
								</li>
								<li>
									<form method="post" action="<?php echo ACTIONPATH.'/remove_payments.php'?>">
										<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>">
										<input type="hidden" name="p_hashid_bill" value="<?php echo $bill['hashid']?>">
										<input type="hidden" name="p_anchor" value="<?php echo '#bill-'.$cpt_bill?>">
										<button type="submit" class="btn btn-link confirmation" 
											name="submit_remove_all_payments" onclick="event.stopPropagation();">
											Remove all payments
										</button>
									</form>
								</li>
								<li class="li_margin_top">
									<form method="post" action="<?php echo ACTIONPATH.'/delete_bill.php'?>">
											<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>">
											<input type="hidden" name="p_hashid_bill" value="<?php echo $bill['hashid']?>">
											<button type="submit" class="btn btn-link confirmation" 
												name="submit_delete_bill" onclick="event.stopPropagation();">
												Delete the bill
											</button>
									</form>
								</li>
							</ul>
						</div>
						<div class="button_bill_title">
							<form action="<?php echo $link_tmp?>">
									<button type="submit" value="" class="btn btn-default" 
										title="Edit bill" onclick="event.stopPropagation();">
											<span class="glyphicon glyphicon-pencil"></span>
									</button>
							</form>
						</div>
			<?php 
					}
					?>
						<div class="button_bill_title">
							<button type="submit" value="" class="btn btn-default" title="Collapse/Expand"
							data-toggle="collapse" data-target="#<?php echo 'panel-body_bill'.$cpt_bill?>">
								<span class="glyphicon glyphicon-plus"></span>
							</button>							
						</div>
					</div>
	<?php
		}
?>
				</div>
			</div>
<?php //PANEL BODY OF BILL
?>
			<div id="<?php echo 'panel-body_bill'.$cpt_bill?>" class="panel-collapse collapse in">
				<div  class="panel-body">
<?php 
//Edit the Bill (name, description, ...)
if($admin_mode 
				&& $edit_mode === 'bill' 
				&& $edit_hashid === $bill['hashid'])
				{
?>
					<div class="form-group">
						<label for="form_edit_bill_description">Description: </label>
						<textarea name="p_description" class="form-control"
						 form="<?php echo "form_update_bill_".$cpt_bill?>"><?php if(!empty($bill['description'])){echo htmlspecialchars($bill['description']);}?></textarea>
					 </div>
					<button type="submit" name="submit_update_bill" value="Submit"
						form="<?php echo "form_update_bill_".$cpt_bill?>"
						class="btn btn-primary" title="Submit changes">
							Submit changes
					</button> 
					<button type="submit" name="submit_cancel" value="<?php echo '#bill-'.$cpt_bill?>" 
						form="form_cancel" class="btn btn-primary" title="Cancel">
						Cancel
					</button> 
<?php	
	}	else{
	//Display only
	if(!empty($bill['description']) && !is_null($bill['description']))
	{
?>
					<h3>Description</h3>
					<p><?php echo htmlspecialchars($bill['description'])?></p>
<?php }
	}?>

	<?php // PARTICIPANTS ?>
					<h3 id="<?php echo 'bill_participants_'.$cpt_bill?>">Participants</h3>
	<?php // Display the current participant of this bill
	if(!empty($this_bill_participants))
	{
?>
					<div class="row">		
<?php
	$participation_to_edit = false; // if editing, place a button after the list
	$cpt_bill_participant = -1;
	foreach($this_bill_participants as $key => $bill_participant)
	{
		$cpt_bill_participant++;
		if($admin_mode === true
			&& $edit_mode === 'bill_participant' 
			&& $edit_hashid === $bill_participant['hashid'])
		{
			//We found the bill_participant to be edited. Will be displayed after the other.
			$participation_to_edit = $key;
			continue;
		}
		?>
					<div class="col-xs-12 col-sm-6 col-lg-4 bill_participant">
						<div class="floatleft width60 padding_bill_participant display_bill_participant" style="background-color:<?php echo '#'.$bill_participant['color']?>">
							<?php
								echo htmlspecialchars($bill_participant['name']).' ('.(float)$bill_participant['percent_of_usage'].'%)';
							?>
						</div>
						<?php
							if($admin_mode === true
							&& $edit_mode === false){
								$link_tmp = $link_to_account_admin.'/edit/bill_participant/'.$bill_participant['hashid'].'#edit_tag_'.$bill_participant['hashid'];
								?>
						<div class="zeromargin floatleft">
									<form action="<?php echo $link_tmp?>">
										<button type="submit" value="" class="btn btn-default" title="Edit participation">
												<span class="glyphicon glyphicon-pencil"></span>
										</button>
									</form>
						</div>
						<div class="bill_participant_button">
							<form method="post" 
							class="deleteicon"
							action="<?php echo ACTIONPATH.'/delete_bill_participant.php'?>">		
								<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>">
								<input type="hidden" name="p_hashid_bill_participant" value="<?php echo $bill_participant['hashid']?>">
								<input type="hidden" name="p_anchor" value="<?php echo '#bill-'.$cpt_bill?>">
								<button type="submit" class="btn btn-default confirmation" 
									name="submit_delete_bill_participant" title="Delete participation">
									<span class="glyphicon glyphicon-trash"></span>
								</button>
							</form>
						</div>
				<?php	} ?>
					</div>
			<?php
	}//foreach participant in this bill
	?>
				</div> <?php //row ?>
	<?php
	
	if($participation_to_edit !== false)
	{
		$bill_participant_tmp = $this_bill_participants[$participation_to_edit];
	//Edit activated on a bill_participant of THIS bill :
	?>
				<h3 id="<?php echo 'edit_tag_'.$edit_hashid?>">Edit participation</h3>
					<form method="post" action="<?php echo ACTIONPATH.'/update_bill_participant.php'?>">

						<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>">
						<input type="hidden" name="p_hashid_bill_participant" value="<?php echo $bill_participant_tmp['hashid']?>">
						<input type="hidden" name="p_anchor" value="<?php echo '#bill-'.$cpt_bill?>">

						<div class="row form-group row-no-padding">
							<div class="col-xs-6 col-sm-5 col-md-4">
								<div class="fullwidth padding_bill_participant display_bill_participant" style="background-color:<?php echo '#'.$bill_participant_tmp['color']?>">
									<?php echo htmlspecialchars($bill_participant_tmp['name']);?>
								</div>
							</div>
							<div class="col-xs-6 col-sm-5 col-md-4">
								<div class="input-group">
									<input type="number" step="0.01" min="0" max="100" name="p_percent_of_use"
											class="form-control" value="<?php echo (float)$bill_participant_tmp['percent_of_usage']?>" required>
										<span class="input-group-addon">%</span>
								</div>
							</div>
						</div>
						<button type="submit" name="submit_update_bill_participant" 
							value="Submit" class="btn btn-primary" title="Submit changes">
							Submit changes
						</button> 
						<button type="submit" name="submit_cancel" value="<?php echo '#bill-'.$cpt_bill?>" 
							form="form_cancel" class="btn btn-primary" title="Cancel">
						 Cancel
						</button>
					</form>
<?php	
//reset temporary variables
$participation_to_edit=false;
$bill_participant_tmp=null;
	}
	?>
<?php }//if my_bill_participants != empty ?>

	<?php
if($admin_mode && !$edit_mode)
{ //Display possibilities
	//Assign a participant (if there are free guys)
	if(!empty($this_free_bill_participants))
	{
	?>
					<form method="post"	enctype="multipart/form-data"
						action="<?php echo ACTIONPATH.'/new_bill_participant.php'?>">
						<fieldset>
							<legend id="<?php echo 'show_hide_bill_add_part_'.$cpt_bill?>"
								class="cursorpointer">
								(+) Assign a participant to this bill
							</legend>
							<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>">
							<input type="hidden" name="p_hashid_bill" value="<?php echo $bill['hashid']?>">
							<input type="hidden" name="p_anchor" value="<?php echo '#bill-'.$cpt_bill?>">
							<div class="hidden_at_first"
							id=<?php echo 'show_hide_bill_add_part_'.$cpt_bill.'_target'?>>
<?php
			$cpt = -1;
			foreach($this_free_bill_participants as $participant)
			{
				$cpt++;
		?>
								<div class="row row-no-padding form-group assign_bill_participant">
									<div class="col-xs-12 col-md-6 col-lg-4 lg-offset-2">
										<div>
											<input type="checkbox" name="p_participant['<?php echo $cpt?>'][p_hashid_participant]" 
												value="<?php echo $participant['hashid']?>" title="Participant"
												id="<?php echo'assign_participant_'.$cpt_bill.'_'.$cpt?>" >
											<div class="[ btn-group ] fullwidth" style="overflow:hidden">
												<label for="<?php echo 'assign_participant_'.$cpt_bill.'_'.$cpt?>"
													class="[ btn btn-default ] btn-assign_bill_participant">
													<span class="[ glyphicon glyphicon-ok ]"></span>
													<span> </span>
												</label>
												<span class="span-assign_bill_participant" >
													<label for="<?php echo 'assign_participant_'.$cpt_bill.'_'.$cpt?>" 
														class="[ btn btn-default active ] btn-assign_bill_participant2"
														style="background-color:<?php echo '#'.$participant['color']?>">
															<?php echo htmlspecialchars($participant['name'])?>
													</label>
												</span>
											</div>
										</div>
									</div>
									<div class="col-xs-12 col-md-6 col-lg-4">
										<div class="">
											<label for="<?php echo 'form_available_percent_'.$cpt_bill.'_'.$participant['id']?>" 
												class="sr-only">
												Percentage of use
											</label>
											<div class="input-group">
												<input name="p_participant['<?php echo $cpt?>'][p_percent_of_use]" type="number"
															class="form-control" step="0.01" min="0" max="100"	value="100" 
															id="<?php echo 'form_available_percent_'.$cpt_bill.'_'.(int)$participant['id']?>"
															title="Percentage of usage">
													<span class="input-group-addon">%</span>
											</div>
										</div>
									</div>
								</div>
		<?php
				}//for each participant
		?>
								<button type="submit" name="submit_new_bill_participant" 
									value="Submit" class="btn btn-primary" title="Submit new participation">
									Submit
								</button>
							</div>
						</fieldset>
					</form>
<?php
		} //if empty free_participants
	}//if admin
?>

					<h3>Payments</h3>

<?php // List of the payments
	if(isset($my_payments_per_bill[$bill['id']]) && is_array($my_payments_per_bill[$bill['id']])
		&& count($my_payments_per_bill[$bill['id']]) > 0)
	{
		$this_payment = $my_payments_per_bill[$bill['id']];
		$cpt_paymt = -1;
	?>
	
					<div class="row text-center">
						<div class="col-xs-4 col-md-2">
							<strong>Payer</strong>
						</div>
						<div class="col-xs-4 col-md-2">
							<strong>Amount</strong>
						</div>
						<div class="col-xs-4 col-md-2">
							<strong>Receiver</strong>
						</div>
						<div class="hidden-xs hidden-sm hidden-md col-lg-2">
							<strong>Designation</strong>
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
		if($admin_mode && $edit_mode === 'payment' 
		&& $payment['hashid'] === $edit_hashid)
		{ 
			$payment_to_edit = $payment;
			continue;
		}
?>
					<div class="row payment_table">
						<div class="col-xs-5 col-md-2">
							<div class="fullwidth display_bill_participant padding_bill_participant" style="background-color:<?php echo '#'.$payment['payer_color']?>">
							<?php echo htmlspecialchars($payment['payer_name'])?>
							</div>
						</div>
						<div class="col-xs-2 col-md-2">
							 <?php echo (float)$payment['cost']?>&euro;
						</div>
						<div class="col-xs-5 col-md-2">
							<?php if(is_null($payment['receiver_name'])) {?>
							<div class="padding_bill_participant display_bill_participant group_color">
								Group
							</div>
							<?php }else{ ?>
							<div class="fullwidth display_bill_participant padding_bill_participant" style="background-color:<?php echo '#'.$payment['receiver_color']?>">			
								<?php echo htmlspecialchars($payment['receiver_name'])?>
							</div>
							<?php }?>
						</div>
						<div class="hidden-xs hidden-sm hidden-md col-lg-2 <?php echo 'description_collapse_'.$cpt_bill.'_'.$cpt_paymt?>">
							<?php if(!empty($payment['description']))
							{
									echo htmlspecialchars($payment['description']);
							}
							?>
						</div>
						<div class="hidden-xs hidden-sm hidden-md col-lg-2 <?php echo 'description_collapse_'.$cpt_bill.'_'.$cpt_paymt?>">
							<?php
							if(!empty($payment['date_of_payment']))
							{
								echo date("d/m/Y", strtotime($payment['date_of_payment']));
							}?>
						</div>
						<?php //Collapse button (for mobile>) ?>
						<div class="visible-xs visible-sm col-xs-2">
							<button type="submit" class="btn btn-default" title="Collapse/Expand"
								data-toggle="collapse" data-target=".<?php echo 'description_collapse_'.$cpt_bill.'_'.$cpt_paymt?>">
								<span class="glyphicon glyphicon-plus"></span>
							</button>
						</div>
		
	<?php //EDIT BUTTON
			if($admin_mode && !$edit_mode)
				{
	?>
						<div class="col-xs-2 col-md-1">
		<?php 
		$link_tmp = $link_to_account_admin.'/edit/payment/'.$payment['hashid'].'#edit_tag_'.$payment['hashid'];
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
								action="<?php echo ACTIONPATH.'/delete_payment.php'?>"
									>
									<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>">
									<input type="hidden" name="p_hashid_payment" value="<?php echo $payment['hashid']?>">
									<input type="hidden" name="p_anchor" value="<?php echo '#bill-'.$cpt_bill?>">
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
					<h3 id="<?php echo 'edit_tag_'.$edit_hashid?>">Edit payment</h3>
					<form method="post" id="form_edit_payment_send"
						action="<?php echo ACTIONPATH.'/update_payment.php'?>">
						<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>">
						<input type="hidden" name="p_hashid_payment" value="<?php echo $payment_to_edit['hashid']?>">
						
						<div class="row form-group">
							<div class="col-xs-12">
								<label for="form_edit_payment_bill_<?php echo $cpt_bill?>">
									Move to another bill
								</label>
								<select name="p_hashid_bill" id="form_edit_payment_bill_<?php echo $cpt_bill?>"
									onchange="CreatePossiblePayersLists(this, document.getElementById('form_edit_payment_payer_<?php echo $cpt_bill?>'),	
									<?php echo htmlspecialchars(json_encode($list_of_possible_payers, 3))?>)"
									class="form-control"> 
						<?php //list of bills
								foreach($my_bills as $sub_bill)
									{
						?>
										<option value="<?php echo $sub_bill['hashid']?>"
										<?php if($sub_bill['id']==$payment_to_edit['bill_id']){echo ' selected';}?>
										><?php echo htmlspecialchars($sub_bill['title'])?></option>
						<?php
									}
						?>
								</select>
							</div>
						</div>
						<div class="row form-group">
							<div class="col-xs-12 col-lg-4">
								<label for="form_edit_payment_payer_<?php echo $cpt_bill?>">
									Payer
								</label>
								<select name="p_hashid_payer" 
									onchange="DropDownListsBetweenParticipants(this, document.getElementById('form_edit_payment_recv_<?php echo $bill['id']?>'))"
									id="form_edit_payment_payer_<?php echo $cpt_bill?>" class="form-control">
						<?php
									foreach($this_bill_participants as $bill_participant)
									{
						?>
										<option value="<?php echo $bill_participant['hashid']?>"
										<?php if($bill_participant['id']==$payment_to_edit['payer_id']){echo ' selected';}?>>
										<?php echo htmlspecialchars($bill_participant['name'])?></option>
						<?php
									}
						?>
								</select>
							</div>
							<div class="col-xs-12 col-lg-4">
								<label for="form_edit_payment_cost_<?php echo $cpt_bill?>">
									Amount
								</label>
								<div class="input-group">
									<input type="number" step="0.01" min="0" name="p_cost" 
										class="form-control"
										id="form_edit_payment_cost_<?php echo $cpt_bill?>"
										value="<?php echo (float)$payment_to_edit['cost']?>" required>
									<span class="input-group-addon glyph glyphicon-euro"></span>
								</div>
							</div>
							<div class="col-xs-12 col-lg-4">
								<label for="form_edit_payment_recv_<?php echo $cpt_bill?>">
									Receiver
								</label>
								<select name="p_hashid_recv" 
									id="form_edit_payment_recv_<?php echo $cpt_bill?>"
									class="form-control">
									<option value="-1" >Group</option>
							<?php
									foreach($this_bill_participants as $bill_participant)
										{
											if($bill_participant['id'] == $payment_to_edit['payer_id']){continue;}
							?>
											<option value="<?php echo $bill_participant['hashid']?>"
											<?php if($bill_participant['id']==$payment_to_edit['receiver_id']){echo ' selected';}?>>
											<?php echo htmlspecialchars($bill_participant['name'])?></option>
							<?php
										}
							?>
								</select>
							</div>
						</div>
						<div class="row form-group">
							<div class="col-xs-12 col-lg-6">
								<label for="form_edit_payment_desc_<?php echo $bill['id']?>">
									Description
								</label>
								<input type="text" name="p_description" class="form-control"
									id="form_edit_payment_desc_<?php echo $bill['id']?>"
									value="<?php echo htmlspecialchars($payment_to_edit['description'])?>"
									placeholder="Description">
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
								<label for="form_edit_payment_date_<?php echo $bill['id']?>">
									Date of payment (dd/mm/yyyy)
								</label>
								<div class="input-group">
									<input type="date" name="p_date_of_payment" 
										class="form-control"
										id="form_edit_payment_date_<?php echo $bill['id']?>"
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
								value="<?php echo '#bill-'.$cpt_bill?>" class="btn btn-primary"
								form="form_cancel" title="Cancel">
								Cancel
							</button>
						</div>
					</form>
<?php
$payment_to_edit = false;
} //edit this payment
?>


<?php
}//if payment exist
else
{ 
	if(!empty($my_bill_participants[$bill['id']]))
	{?>
					<p>No payments recorded.</p>		
<?php
	}
	else
	{
	?>
					<p>Please provide participations to add payments.</p>			
<?php
		}
	}//end else payment exists
 // PAYMENTS
	if($admin_mode && !$edit_mode)
	{?>
	<!-- Add payment -->
	<?php
		if(!empty($my_bill_participants[$bill['id']]))
		{
?>
					<form method="post" action="<?php echo ACTIONPATH.'/new_payment.php'?>"
						role="form">
						<fieldset>								
							<legend id="<?php echo 'show_hide_bill_add_paymt_'.$cpt_bill?>"
								class="cursorpointer">
								(+) Add a payment
							</legend>
							<div  class="hidden_at_first"	id="<?php echo 'show_hide_bill_add_paymt_'.$cpt_bill.'_target'?>">
								<div id="<?php echo 'div_option_add_payment_'.$cpt_bill?>">
									<p><em>Fields with asterisk <span class="glyphicon glyphicon-asterisk red"></span> are required</em></p>
									<input type="hidden" name="p_hashid_account" value ="<?php echo $my_account['hashid_admin']?>">
									<input type="hidden" name="p_hashid_bill" value ="<?php echo $bill['hashid']?>">
									<input type="hidden" name="p_anchor" value="<?php echo '#bill-'.$cpt_bill?>">
									<div id="div_set_payment_<?php echo $cpt_bill?>">
										<div class="row form-group">
											<div class="col-xs-12 col-lg-4">
												<label for="<?php echo 'form_set_payment_payer_'.$cpt_bill?>_0">Payer<span class="glyphicon glyphicon-asterisk red"></span></label>
												<div class="input-group">
													<select name="p_payment[0][p_hashid_payer]" 
														id="form_set_payment_payer_<?php echo $cpt_bill?>_0" 
														onchange="DropDownListsBetweenParticipants(this, document.getElementById('<?php echo 'form_set_payment_recv_'.$cpt_bill.'_0'?>'))"
														class="form-control selectpicker" title="Payer"> 
															<option disabled selected value="null"> -- select a payer -- </option>
														<?php
															foreach($this_bill_participants as $bill_participant)
															{ ?>
																<option value="<?php echo $bill_participant['hashid']?>"><?php echo htmlspecialchars($bill_participant['name'])?></option>
											<?php	} ?>
													</select>
													<span class="input-group-addon glyphicon glyphicon-user"></span>
												</div>
											</div>

											<div class="col-xs-12 col-lg-4">
												<label for="<?php echo 'form_set_payment_cost_'.$cpt_bill?>_0">Amount<span class="glyphicon glyphicon-asterisk red"></span></label>
												<div class="input-group">
													<input type="number" step="0.01" min="0" name="p_payment[0][p_cost]" 
														id="<?php echo 'form_set_payment_cost_'.$cpt_bill?>_0" required 
														class="form-control" title="Amount">
														<span class="input-group-addon glyph glyphicon-euro"></span>
												</div>
											</div>

											<div class="col-xs-12 col-lg-4">
												<label for="<?php echo 'form_set_payment_recv_'.$cpt_bill?>_0">
													Receiver<span class="glyphicon glyphicon-asterisk red"></span>
												</label>
												<div class="input-group">
													<select name="p_payment[0][p_hashid_recv]" id="<?php echo 'form_set_payment_recv_'.$cpt_bill?>_0"	class="form-control selectpicker" title="Receiver"> 
														<option value="-1" selected="selected">Group</option>
													</select>
													<span class="input-group-addon glyphicon glyphicon-user"></span>
												</div>
											</div>
										</div>
										<div class="row form-group">
											<div class="col-xs-12 col-lg-6">
												<label for="<?php echo 'form_set_payment_desc_'.$cpt_bill?>_0">
													Description
												</label>
												<div class="input-group">
													<input type="text" name="p_payment[0][p_description]" 
														id="<?php echo 'form_set_payment_desc_'.$cpt_bill?>_0" 
														class="form-control" placeholder="Description" title="Description">
													<span class="input-group-addon glyphicon glyphicon-tag"></span>
												</div>
											</div>
											<div class="col-xs-12 col-lg-6">
												<label for="<?php echo 'form_set_payment_date_'.$cpt_bill?>_0">
													Date of payment (dd/mm/yyyy)
												</label>
												<div class="input-group">
													<input type="date" name="p_payment[0][p_date_of_payment]" 
														id="<?php echo 'form_set_payment_date_'.$cpt_bill?>_0" 
														class="form-control" title="Date of payment">
													<span class="input-group-addon glyphicon glyphicon-calendar"></span>
												</div>
											</div>
										</div>
									</div>
								</div>
	<?php
		$name_of_people = array_column($this_bill_participants, 'name');
		$hashid_of_people = array_column($this_bill_participants, 'hashid');
	?>
								<p>
									<a href="#" onclick="AddPaymentLine(<?php echo htmlspecialchars(json_encode($name_of_people)) ?>, 
										<?php echo htmlspecialchars(json_encode($hashid_of_people)) ?>,
										<?php echo $cpt_bill?>);
										return false;">
									(+) Add a row
									</a>
								</p>
								
								<div>
									<button type="submit" name="submit_new_payment" value="Submit" 
										title="Submit new payment" class="btn btn-primary">
										Submit
									</button>
								</div>
							</div>
						</fieldset>
					</form>
<?php
		}//if bill_participants not empty (ie: payment possible)
?>
	<?php
	} //if for displaying possibilities
?>

				</div> 
			</div> 
		</div> 
	</div> 
</div> 

<?php
}//foreach bill
}//if bills exist
?>
