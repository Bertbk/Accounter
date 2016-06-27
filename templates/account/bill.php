
<!-- BILLS -->
<div class="row">
<div class="col-xs-12">
<div class="panel panel-primary">
<div class="panel-heading">
	<h2>My bills</h2>
</div>
<div class="panel-body">
<?php
//Admin only
if($admin_mode && $edit_mode == false)
{
?>
<!-- Add bill-->
<p  id="show_hide_add_bill"><a href="javascript:void(0)" >(+) Add a bill</a></p>
<div id="div_add_bill">
	<form method="post" 
		id="show_hide_add_bill_target" 
		class="hidden_at_first"
		action="<?php echo ACTIONPATH.'/new_bill.php'?>"
	>
	  <fieldset>
			<legend>Add a bill</legend>
			<input type="hidden" name="p_hashid_account" 
			value="<?php echo $my_account['hashid_admin']?>" />
			<div class="form-group">
				<label for="form_set_bill_name" class="sr-only">Title: </label>
				<input type="text" name="p_title_of_bill" 
				id="form_set_bill_name" class="form-control" required 
				placeholder="Title"/>
			</div>
			<div class="form-group">
				<label for="form_set_bill_description" class="sr-only">Description: </label>
				 <textarea name="p_description" id="form_set_bill_description" class="form-control" 
				 placeholder="Description"></textarea>
			</div>

			 <button type="submit" name="submit_new_bill" value="Submit"
			 class="btn btn-primary">Submit</button> 
	  </fieldset>
	</form>
</div>
<?php } //admin mode
?>
</div>
</div>
</div>
</div>


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
<div class="row bill <?php echo 'bill-'.$cpt_bill?>">
<div class="col-xs-12">
	<div class="panel panel-primary">
<?php 
//Edit the Bill (name, description, ...)
if($admin_mode 
				&& $edit_mode === 'bill' 
				&& $edit_hashid === $bill['hashid'])
				{
?>
		<div class="panel-heading">
		<div class="row">
			<div class="col-xs-12">
			<form method="post" id="<?php echo "form_update_bill_".$cpt_bill?>"
				action="<?php echo ACTIONPATH.'/update_bill.php'?>">
				<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>"/>
				<input type="hidden" name="p_hashid_bill" value="<?php echo $bill['hashid']?>" />
				<h3>
					<label for="form_edit_bill_name" class="sr-only">Title: </label>
					<input type="text" name="p_title_of_bill" id="form_edit_bill_name"
					class="form-control"	value="<?php echo htmlspecialchars($bill['title'])?>" required />
				</h3>
			</form>
			</div>
		</div>
		</div>
<?php } else{
?>
		<div class="panel-heading cursor_pointer" data-toggle="collapse" data-target="#<?php echo 'panel-body_bill'.$cpt_bill?>">
		<div class="row">
			<div class="col-xs-12 col-sm-10">
			<h3>
				<?php echo ($cpt_bill+1).'. '.htmlspecialchars($bill['title']) ?>
			</h3>	
			</div>
<?php
			if($admin_mode && $edit_mode === false)
			{
				$link_tmp = $link_to_account_admin.'/edit/bill/'.$bill['hashid'];
?>
			<div class="col-xs-2 col-sm-1">
			<form action="<?php echo $link_tmp?>">
					<button type="submit" value="" class="btn btn-default">
							<span class="glyphicon glyphicon-pencil"></span>
					</button>
			</form>
			</div>
			<div class="col-xs-2 col-md-1">
			<form method="post" action="<?php echo ACTIONPATH.'/delete_bill.php'?>">
				<input type="hidden" name="p_hashid_account" 
					value="<?php echo $my_account['hashid_admin']?>">
				<input type="hidden" name="p_hashid_bill" 
					value="<?php echo $bill['hashid']?>">
				<button type="submit" class="btn btn-default confirmation" name="submit_delete_participant">
					<span class="glyphicon glyphicon-trash"></span>
				</button>
			</form>
			</div>
		</div>
		</div>
<?php 
			}
		}
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
		class="btn btn-primary">
			Submit
		</button> 
		<button type="submit" name="submit_cancel" value="Submit" 
				form="<?php echo "form_update_bill_".$cpt_bill?>"
				class="btn btn-primary">
				Cancel
		</button> 
<?php	
	}	else{
	//Display only
	if(!empty($bill['description']) && !is_null($bill['description']))
	{
?>
	<p><?php echo htmlspecialchars($bill['description'])?></p>
<?php }
	}?>


<?php // Display the current participant of this bill
	if(!empty($this_bill_participants))
	{
?>
		<h4>Participants</h4>
<?php
	$place_submit_button = false; // if editing, place a button after the list
	$cpt_bill_participant = -1;
	foreach($this_bill_participants as $bill_participant)
	{
		$cpt_bill_participant++;
			if($admin_mode === true
			&& $edit_mode === 'bill_participant' 
			&& $edit_hashid === $bill_participant['hashid'])
		{
			//Edit activated on THIS bill_participant
			$place_submit_button = true;
	?>
			<form method="post"
			action="<?php echo ACTIONPATH.'/update_bill_participant.php'?>">
			<div class="bill_participant" style="background-color:<?php echo '#'.$bill_participant['color']?>">
			<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>">
			<input type="hidden" name="p_hashid_bill_participant" value="<?php echo $bill_participant['hashid']?>">
			<span 
			class="<?php echo 'bill_participant'?>" style="background-color:<?php echo '#'.$bill_participant['color']?>"
			>
			<?php
			echo htmlspecialchars($bill_participant['name']);?>		
			 (<input type="number" step="0.01" min="0" max="100" name="p_percent_of_use"
				class="input_percent"
			 value="<?php echo (float)$bill_participant['percent_of_usage']?>" required />%)
 			</span>
		</div>
	<?php }
		else
		{
			?>
			<div class="bill_participant" style="background-color:<?php echo '#'.$bill_participant['color']?>">
			<?php
			echo htmlspecialchars($bill_participant['name']).' ('.(float)$bill_participant['percent_of_usage'].'%)';
			if($admin_mode === true
			&& $edit_mode === false){
				?>
				<a href="<?php echo $link_to_account_admin.'/edit/bill_participant/'.$bill_participant['hashid']?>"
				class="btn btn-default glyphicon glyphicon-pencil"></a>
	<form method="post" 
	class="deleteicon"
	action="<?php echo ACTIONPATH.'/delete_bill_participant.php'?>"
		>		
		<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>"/>
		<input type="hidden" name="p_hashid_bill_participant" value="<?php echo $bill_participant['hashid']?>"	/>
		<button type="submit" class="btn btn-default confirmation" name="submit_delete_bill_participant">
			<span class="glyphicon glyphicon-trash"></span>
		</button>
	</form>		
		<?php	} ?>
		</div>
			<?php
		}//else edit mode
		?>
			<?php
	}//foreach participant in this bill
	//Submit button for editing
	if($place_submit_button)
	{
	?>
		<br><button type="submit" name="submit_update_bill_participant" value="Submit">Submit</button> 
		<button type="submit" name="submit_cancel" value="Submit">Cancel</button> 
		</form>
		
	<?php
		$place_submit_button = false;
	} //if place button
	?>
<?php }//if my_bill_participants != empty ?>

<?php
	if($admin_mode && !$edit_mode)
	{ //Display possibilities
		//Assign a participant (if there are free guys)
		if(!empty($this_free_bill_participants))
		{
	?>
	<p id="<?php echo 'show_hide_bill_add_part_'.$cpt_bill?>"><a href="javascript:void(0)">(+) Assign a participant to this bill</a></p>
		<form method="post" class="hidden_at_first" 
		enctype="multipart/form-data"
		id=<?php echo 'show_hide_bill_add_part_'.$cpt_bill.'_target'?>
		action="<?php echo ACTIONPATH.'/new_bill_participant.php'?>"
		>
		  <fieldset>
			<legend>Assign a participant to this bill:</legend>
			<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>">
			<input type="hidden" name="p_hashid_bill" value="<?php echo $bill['hashid']?>">
			<input type="hidden" name="p_bill_hashid" value="<?php echo $bill['hashid']?>">
			<?php
			$cpt = -1;
			foreach($this_free_bill_participants as $participant)
			{
				$cpt++;
	?>
			<div class="row form-group">
				<div class="col-xs-7 col-lg-3">
					<label class="checkbox-inline" for="<?php echo 'form_available_part_'.$cpt_bill.'_'.(int)$participant['id']?>">
						<?php echo htmlspecialchars($participant['name'])?>
					</label>
					<input type="checkbox" name="p_participant['<?php echo $cpt?>'][p_hashid_participant]" 
						id="<?php echo 'form_available_part_'.$cpt_bill.'_'.(int)$participant['id']?>"
						value="<?php echo $participant['hashid']?>"
						class="pull-left">
				
				</div>
				<div class="col-xs-3 col-lg-2">
					<label for="<?php echo 'form_available_percent_'.$cpt_bill.'_'.$participant['id']?>" 
						class="sr-only">
						Percentage of use
					</label>
					<input name="p_participant['<?php echo $cpt?>'][p_percent_of_use]" type="number"
						class="" step="0.01" min="0" max="100" size="5" 
						value="100" id="<?php echo 'form_available_percent_'.$cpt_bill.'_'.(int)$participant['id']?>">
				</div>
				
			</div>
	<?php
			}//for each participant
	?>
				<button type="submit" name="submit_new_bill_participant" 
					value="Submit" class="btn btn-primary">
				Submit
				</button>
		  </fieldset>
		</form>
<?php 
		} //if empty free_participants
	}//if admin
?>


<h4>Payments</h4>

<?php // List of the payments
	if(isset($my_payments_per_bill[$bill['id']]) && is_array($my_payments_per_bill[$bill['id']])
		&& count($my_payments_per_bill[$bill['id']]) > 0)
	{
		$this_payment = $my_payments_per_bill[$bill['id']];
		$cpt_paymt = -1;
	?>
<div class="table-responsive">
	<table>
	  <thead>
			<tr>
				<th>Payer</th>
				<th>Amount</th>
				<th>Receiver</th>
				<th>Designation</th>
				<th>Date</th>
<?php if($admin_mode && !$edit_mode){?>
				<th>Edit</th>
<?php }if($admin_mode && !$edit_mode){?>
				<th>Delete</th>
<?php }?>
			</tr>
		</thead>
		<tbody>
	<?php
		foreach($this_payment as $payment)
		{
			$cpt_paymt++;
	?>
			<tr>
	<?php
			if($admin_mode && $edit_mode === 'payment' 
			&& $payment['hashid'] === $edit_hashid)
			{ //!!!! Edit mode  !!!!
?>
		<form method="post" id="form_edit_payment_send"
		action="<?php echo ACTIONPATH.'/update_payment.php'?>">
			<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>"/>
		<input type="hidden" name="p_hashid_payment" value="<?php echo $payment['hashid']?>" />
		<label for="form_edit_payment_bill_<?php echo $cpt_bill?>">
				Move to another bill
			</label>
			<select name="p_hashid_bill" id="form_edit_payment_bill_<?php echo $cpt_bill?>"
			onchange="CreatePossiblePayersLists(this, document.getElementById('form_edit_payment_payer_<?php echo $cpt_bill?>'),	
			<?php echo htmlspecialchars(json_encode($list_of_possible_payers, 3))?>)"> 
	<?php //list of bills
			foreach($my_bills as $sub_bill)
				{
	?>
					<option value="<?php echo $sub_bill['hashid']?>"
					<?php if($sub_bill['id']==$payment['bill_id']){echo ' selected';}?>
					><?php echo htmlspecialchars($sub_bill['title'])?></option>
	<?php
				}
	?>
			</select>
			
			<label for="form_edit_payment_payer_<?php echo $cpt_bill?>">
			Payer
			</label>
			<select name="p_hashid_payer" 
			onchange="DropDownListsBetweenParticipants(this, document.getElementById('form_edit_payment_recv_<?php echo $bill['id']?>'))"
			id="form_edit_payment_payer_<?php echo $cpt_bill?>"
			>
	<?php
				foreach($this_bill_participants as $bill_participant)
				{
	?>
					<option value="<?php echo $bill_participant['hashid']?>"
					<?php if($bill_participant['id']==$payment['payer_id']){echo ' selected';}?>
					>
					<?php echo htmlspecialchars($bill_participant['name'])?></option>
	<?php
				}
	?>
			</select>
			
			<label for="form_edit_payment_cost_<?php echo $cpt_bill?>">
			Cost
			</label>
			<input type="number" step="0.01" min="0" name="p_cost" 
				class="input_paymt_cost"
				id="form_edit_payment_cost_<?php echo $cpt_bill?>"
				value="<?php echo (float)$payment['cost']?>" required />
			
			<label for="form_edit_payment_recv_<?php echo $cpt_bill?>">
				Receiver
			</label>
			<select name="p_hashid_recv" 
			id="form_edit_payment_recv_<?php echo $cpt_bill?>"
			>
			<option value="-1" >Group</option>
	<?php
			foreach($this_bill_participants as $bill_participant)
				{
					if($bill_participant['id'] == $payment['payer_id']){continue;}
	?>
					<option value="<?php echo $bill_participant['hashid']?>"
					<?php if($bill_participant['participant_id']==$payment['receiver_id']){echo ' selected';}?>
					>
					<?php echo htmlspecialchars($bill_participant['name'])?></option>
	<?php
				}
	?>
			</select>
			
			<label for='form_edit_payment_desc_<?php echo $bill['id']?>'>
			Description
			</label>
			<input type="text" name="p_description" class="input_paymt_desc"
			id="form_edit_payment_desc_<?php echo $bill['id']?>"
			value="<?php echo htmlspecialchars($payment['description'])?>" />
			
			<label for="form_edit_payment_date_<?php echo $bill['id']?>">
			Date of payment
			</label>
			<input type="date" name="p_date_of_payment" 
				class="input_paymt_date date_picker"
				id="form_edit_payment_date_<?php echo $bill['id']?>"
				value="<?php echo $payment['date_of_payment']?>"/>
			<div>
			<span><button type="submit" name="submit_update_payment" value="Submit">Submit</button> </span>
			<span><button type="submit" name="submit_cancel" value="Submit">Cancel</button> </span>
			</div>
		</form>
	<?php
			}
			else{//Read only
		?>
			<td>
			<span class='bill_participant' style="background-color:<?php echo '#'.$payment['payer_color']?>">
			<?php echo htmlspecialchars($payment['payer_name'])?>
			</span>
			</td>
<!--			 &nbsp;paid -->
 			<td>
			 <?php echo (float)$payment['cost']?>&euro;
			</td>
<!--	 to -->
			<td>
				<?php if(is_null($payment['receiver_name'])) {?>
					<span class="bill_participant group_color">
					Group
					</span>
			<?php }else{ ?>
				<span class="bill_participant" style="background-color:<?php echo '#'.$payment['receiver_color']?>">			
				<?php echo htmlspecialchars($payment['receiver_name'])?></span>
				<?php }?>
				</td>
				<td>
			<?php if(!empty($payment['description']))
			{
				?>
					<?php echo htmlspecialchars($payment['description']);?>
			<?php 
			}
			?>
			</td>
			<td>
			<?php
			if(!empty($payment['date_of_payment']))
			{
				?>
				<?php echo date("d/m/Y", strtotime($payment['date_of_payment']));?>
				<?php
				}?>
			</td>
	<?php //EDIT BUTTON
			if($admin_mode && !$edit_mode)
				{
	?>
			<td>
				<a href="<?php echo $link_to_account_admin.'/edit/payment/'.$payment['hashid']?>" 
				class="btn btn-default glyphicon glyphicon-pencil"></a>
				</td>
				<td>
			<form method="post" 
				class="deleteicon"
				action="<?php echo ACTIONPATH.'/delete_payment.php'?>"
					>
					<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>"/>
					<input type="hidden" name="p_hashid_payment" value="<?php echo $payment['hashid']?>" />
					<button type="submit" class="btn btn-default confirmation" name="submit_delete_participant">
						<span class="glyphicon glyphicon-trash"></span>
					</button>
				</form>
				</td>
		<?php
				}
			}//end else admin mode 
			?>
			</tr>
<?php	}//foreach current payment 
?>
	</tbody>
	</table></div>
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
		<p id="<?php echo 'show_hide_bill_add_paymt_'.$cpt_bill?>"><a href="javascript:void(0)">
		(+) Add a payment</a></p>
		<form method="post" id="<?php echo 'show_hide_bill_add_paymt_'.$cpt_bill.'_target'?>" 
			class="hidden_at_first" action="<?php echo ACTIONPATH.'/new_payment.php'?>">
		  <fieldset>
			<legend>Add a payment:</legend>
		<div id="<?php echo 'div_option_add_payment_'.$cpt_bill?>">
			<div>
				<input type="hidden" name="p_hashid_account" value ="<?php echo $my_account['hashid_admin']?>">
				<input type="hidden" name="p_hashid_bill" value ="<?php echo $bill['hashid']?>">
			</div>
			<div class="div_set_payment_<?php echo $cpt_bill?>">
				<span>
					<label for="<?php echo 'form_set_payment_payer_'.$cpt_bill?>_0">Payer</label>
						<select name="p_payment[0][p_hashid_payer]" 
						id="form_set_payment_payer_<?php echo $cpt_bill?>_0" 
						onchange="DropDownListsBetweenParticipants(this, document.getElementById('<?php echo 'form_set_payment_recv_'.$cpt_bill.'_0'?>'))"> 
						<option disabled selected value="null"> -- select a payer -- </option>
			<?php

						foreach($this_bill_participants as $bill_participant)
						{
			?>
							<option value="<?php echo $bill_participant['hashid']?>"><?php echo htmlspecialchars($bill_participant['name'])?></option>
			<?php
						}
			?>
						</select>
				</span><span>
					<label for="<?php echo 'form_set_payment_cost_'.$cpt_bill?>_0">Cost</label>
					<input type="number" step="0.01" min="0" name="p_payment[0][p_cost]" 
						id="<?php echo 'form_set_payment_cost_'.$cpt_bill?>_0" required 
						class="input_paymt_cost"/>
				</span><span>
					<label for="<?php echo 'form_set_payment_recv_'.$cpt_bill?>_0">Receiver</label>
					<select name="p_payment[0][p_hashid_recv]" id="<?php echo 'form_set_payment_recv_'.$cpt_bill?>_0"> 
					<option value="-1" selected="selected">Group</option>
					</select>
				</span><span>
					<label for="<?php echo 'form_set_payment_desc_'.$cpt_bill?>_0">Description</label>
					<input type="text" name="p_payment[0][p_description]" 
						id="<?php echo 'form_set_payment_desc_'.$cpt_bill?>_0" 
						class="input_paymt_desc" />
				</span><span>
				<label for="<?php echo 'form_set_payment_date_'.$cpt_bill?>_0">Date of payment</label>
				<input type="date" name="p_payment[0][p_date_of_payment]" 
						id="<?php echo 'form_set_payment_date_'.$cpt_bill?>_0"
						class="date_picker input_paymt_date"/>
				</span>
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
				<button type="submit" name="submit_new_payment" value="Submit">Submit</button>
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
