<!DOCTYPE html>

<html>
<head>
<title>Account</title>
<link rel="stylesheet" type="text/css" href="<?php echo BASEURL.'/css/bill.css'?>">
<link rel="stylesheet" type="text/css" href="<?php echo BASEURL.'/css/bill_participant.css'?>">
<link rel="stylesheet" type="text/css" href="<?php echo BASEURL.'/css/account.css'?>">

<script type="text/javascript" src="<?php echo BASEURL.'/js/account.js'?>"></script>
<link rel="stylesheet" type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/smoothness/jquery-ui.css" media="all">
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo BASEURL.'/js/hide_show_add_participant.jquery'?>"></script>

</head>
<body>

<?php include(__DIR__.'/error.php'); ?>

<?php if($admin_mode && $edit_mode !== false)
{
?>
<div><p>Edit mode activated </p><form method="post"><button type="submit" name="submit_cancel" value="Submit">Cancel</button></form></div>
<?php } ?>

<!--Menu -->

<h1>Home</h1>

<p><a href='<?php echo BASEURL.''?>'>Come back to the main menu</a></p>

<h1>Welcome to the account: <?php echo htmlspecialchars($my_account['title'])?></h1>
	
<?php if (is_array($my_participants) && sizeof($my_participants) > 0 ) 
	{
?>
<h1><?php echo (int)$n_participants ?> participants for <?php echo (int)$n_people ?> people</h1>
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
	<span class='bill_participant' style="background-color:<?php echo '#'.$participant['color']?>">
<?php
if($admin_mode && $edit_mode == 'participant' && $participant['hashid'] == $edit_hashid)
{
?>
			<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>">
			<input type="hidden" name="p_hashid_participant" value="<?php echo $participant['hashid']?>">
			<input type="text" name="p_name_of_participant" class="input_name"
			value="<?php echo $participant['name']?>" required />
			(<input type="number" name="p_nb_of_people" class="input_number"
			min="1" step="1" value="<?php echo $participant['nb_of_people']?>" required />)
			<input type="email" name="email" class="input_email"
			value="<?php echo $participant['email']?>"/>
<?php
}//if
else{ // READ Only
?>
		<?php echo $participant['name']?> 
		(<?php echo $participant['nb_of_people'];if(!empty($participant['email'])){echo ', '.$participant['email'];}?>)

<?php //Edit link
if($admin_mode && !$edit_mode)
{
	$link_tmp = $link_to_account_admin.'/edit/participant/'.$participant['hashid'];
?>
	<a href="<?php echo $link_tmp?>">
	<img src="<?php echo BASEURL.'/img/pencil_white.png'?>" alt='Edit participant' class="editicon" >
	</a>
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
			border="0" 
			class="confirmation deleteicon"
			alt="Delete participant" 
			value="Submit">
		</span>
	</form>
<?php
}
?>		
<?php
}//if/else admin
?>
	</span>
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

<!-- BILLS -->
<h1>The bills</h1>
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
		<span>
		<label for="form_set_bill_name">Name: </label>
		<input type="text" name="p_title_of_bill" 
		id="form_set_bill_name" class="input_bill_name" required />
		</span><span>
		<label for="form_set_bill_description">Description: </label>
		 <input type="text" name="p_description" 
		 id="form_set_bill_description" class="input_bill_desc" />
		</span><div>
		 <button type="submit" name="submit_new_bill" value="Submit">Submit</button> 
		 </div>
	  </fieldset>
	</form>
</div>
<?php } //admin mode
?>


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
<div class="bill 
<?php echo 'bill-'.$cpt_bill?>" style="background-color:<?php echo '#'.$bill['color']?>"
>
	<?php 
	//Edit the Bill (name, description, ...)
	if($admin_mode 
					&& $edit_mode === 'bill' 
					&& $edit_hashid === $bill['hashid'])
					{
	?>
	<form method="post"
	action="<?php echo ACTIONPATH.'/update_bill.php'?>">
		<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>"/>
		<input type="hidden" name="p_hashid_bill" value="<?php echo $bill['hashid']?>" />
		<h2>
	<label for="form_edit_bill_name">Title: </label>
	<input type="text" name="p_title_of_bill" id="form_edit_bill_name"
	class="input_bill_name"	value="<?php echo htmlspecialchars($bill['title'])?>" required />
	</h2>
	<label for="form_edit_bill_description">Description: </label>
	 <input type="text" name="p_description" id="form_edit_bill_description" 
	 class="input_bill_desc" value="<?php echo htmlspecialchars($bill['description'])?>"/>
	 <div>
		<button type="submit" name="submit_update_bill" value="Submit">Submit</button> 
		<button type="submit" name="submit_cancel" value="Submit">Cancel</button> 
	</div>
	</form>
	<?php	
	}
	else{
	//Display only
	?>
	<h2><a href="javascript:void(0)" id="<?php echo 'show_hide_bill'.$cpt_bill?>">
	<?php echo htmlspecialchars($bill['title']) ?>
	</a>
	<?php
	if($admin_mode && $edit_mode === false)
	{
		$link_tmp = $link_to_account_admin.'/edit/bill/'.$bill['hashid'];
		?>
		<a href='<?php echo $link_tmp?>'>
		<img src="<?php echo BASEURL.'/img/pencil.png'?>" alt='Edit bill' class="editicon" />
		</a>
	<form method="post" 
	class="deleteicon"
	action="<?php echo ACTIONPATH.'/delete_bill.php'?>"
		>
		<input type="hidden" 
		name="p_hashid_account" 
		value="<?php echo $my_account['hashid_admin']?>"
		/>
		<input type="hidden"  
		name="p_hashid_bill" 
		value="<?php echo $bill['hashid']?>"
		/>
		<span>
		<input type="image" 
			name="submit_delete_bill"
			src="<?php echo BASEURL.'/img/delete.png'?>" 
			border="0" 
			class="confirmation deleteicon"
			alt="Delete bill" 
			value="Submit">
		</span>
	</form>		
<?php }	?>
	</h2>
	<div  id="<?php echo 'show_hide_bill'.$cpt_bill.'_target'?>">
	<?php if(!empty($bill['description']) && !is_null($bill['description']))
	{
?>
	<p><?php echo htmlspecialchars($bill['description'])?></p>
<?php }?>
<?php }//if/else admin 
?>

<?php // Display the current participant of this bill
	if(!empty($this_bill_participants))
	{
?>
		<h3>Participants</h3>
<?php
	$place_submit_button = false; // if editing, place a button after the list
	$cpt_bill_participant = -1;
	foreach($this_bill_participants as $bill_participant)
	{
		$cpt_bill_participant++;
		if(  $admin_mode === true
			&& $edit_mode === 'bill_participant' 
			&& $edit_hashid === $bill_participant['hashid'])
		{
			//Edit activated on THIS bill_participant
			$place_submit_button = true;
	?>
			<form method="post"
			action="<?php echo ACTIONPATH.'/update_bill_participant.php'?>"
			>
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
	<?php }
		else
		{ 
?>
		<span 
			class="<?php echo 'bill_participant'?>" style="background-color:<?php echo '#'.$bill_participant['color']?>">
			<?php
			echo htmlspecialchars($bill_participant['name']).' ('.(float)$bill_participant['percent_of_usage'].'%)';
			if($admin_mode === true
			&& $edit_mode === false){
				?><a href="<?php echo $link_to_account_admin.'/edit/bill_participant/'.$bill_participant['hashid']?>">
				<img src="<?php echo BASEURL.'/img/pencil_white.png'?>" alt='Edit this participation' class="editicon" />
				</a>				
	<form method="post" 
	class="deleteicon"
	action="<?php echo ACTIONPATH.'/delete_bill_participant.php'?>"
		>
		<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>"/>
		<input type="hidden" name="p_hashid_bill_participant" value="<?php echo $bill_participant['hashid']?>"	/>
		<span>
		<input type="image" 
			name="submit_delete_bill_participant"
			src="<?php echo BASEURL.'/img/delete_white.png'?>" 
			border="0" 
			class="confirmation deleteicon"
			alt="Delete this participation" 
			value="Submit">
		</span>
	</form>		

		<?php	} ?>
			</span>
			<?php
		}//else admin mode
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
			<?php
			$cpt = -1;
			foreach($this_free_bill_participants as $participant)
			{
				$cpt++;
	?>
			<div class="Assign_participant_<?php echo $cpt_bill?>_<?php echo $cpt?>">
			<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>">
			<input type="hidden" name="p_hashid_bill" value="<?php echo $bill['hashid']?>">
			  <span><input name="p_participant['<?php echo $cpt?>'][p_hashid_participant]" 
				id="<?php echo "form_available_part_".$participant['id']?>"
				value="<?php echo $participant['hashid']?>" type="checkbox">
			  <label for="<?php echo "form_available_part_".$participant['id']?>">
				<?php echo htmlspecialchars($participant['name'])?>
			  </label>
			  </span>
				<span><input name="p_participant['<?php echo $cpt?>'][p_percent_of_use]" type="number"
						class="input_percent" step="0.01" min="0" max="100" size="5" 
						value="100"></span>
			</div>
	<?php
			}//for each participant
	?>
			<div>
				<span><input type="hidden" name="p_bill_hashid" value="<?php echo $bill['hashid']?>"></span>
				<span><button type="submit" name="submit_new_bill_participant" value="Submit">Submit</button></span>
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
	<ul>
	<?php
		foreach($this_payment as $payment)
		{
			$cpt_paymt++;
	?><li>
			<div id="div_payment_<?php echo $cpt_bill.'_'.$cpt_paymt?>">
	<?php
			if($admin_mode && $edit_mode === 'payment' 
			&& $payment['hashid'] === $edit_hashid)
			{ //!!!! Edit mode  !!!!
?>
		<form method="post" id="form_edit_payment_send"
		action="<?php echo ACTIONPATH.'/update_payment.php'?>">
			<label for="form_edit_payment_bill_<?php echo $cpt_bill?>">
				Move to another bill
			</label>
			<select name="p_bill_hashid" id="form_edit_payment_bill_<?php echo $cpt_bill?>"
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
			
			<label for="form_edit_payment_payer_<?php echo $bill['id']?>">
			Payer
			</label>
			<select name="p_payer_hashid" 
			onchange="DropDownListsBetweenParticipants(this, document.getElementById('form_edit_payment_recv_<?php echo $bill['id']?>'))"
			id="form_edit_payment_payer_<?php echo $bill['id']?>"
			>
	<?php
				foreach($this_bill_participants as $bill_participant)
				{
	?>
					<option value="<?php echo $bill_participant['participant_hashid']?>"
					<?php if($bill_participant['participant_id']==$payment['payer_id']){echo ' selected';}?>
					>
					<?php echo htmlspecialchars($bill_participant['name'])?></option>
	<?php
				}
	?>
			</select>
			
			<label for="form_edit_payment_cost_<?php echo $bill['id']?>">
			Cost
			</label>
			<input type="number" step="0.01" min="0" name="p_cost" 
				class="input_paymt_cost"
				id="form_edit_payment_cost_<?php echo $bill['id']?>"
				value="<?php echo (float)$payment['cost']?>" required />
			
			<label for="form_edit_payment_recv_<?php echo $bill['id']?>">
				Receiver
			</label>
			<select name="p_receiver_id" 
			id="form_edit_payment_recv_<?php echo $bill['id']?>"
			>
			<option value="-1" >Group</option>
	<?php
			foreach($this_bill_participants as $bill_participant)
				{
					if($bill_participant['participant_id'] == $payment['payer_id']){continue;}
	?>
					<option value="<?php echo $bill_participant['participant_hashid']?>"
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
			<input type="date" name="p_date_payment" 
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
			<span class='bill_participant' style="background-color:<?php echo '#'.$payment['payer_color']?>">
			<?php echo $payment['payer_name']?>
			</span>
			paid <?php echo (float)$payment['cost']?>&euro; to 
			<?php if(is_null($payment['receiver_name'])) {?>
				<span class="bill_participant group_color">
				Group
				</span>
			<?php }else{ ?>
			<span class="bill_participant" style="background-color:<?php echo '#'.$payment['receiver_color']?>">			
			<?php echo htmlspecialchars($payment['receiver_name'])?></span>
			<?php }?>
			<?php if(!empty($payment['date_creation'])){echo ', the '.str_replace('-', '/',$payment['date_creation']);}?>
			<?php if(!empty($payment['description'])){echo 'for '.htmlspecialchars($payment['description']);}?>
	<?php //EDIT BUTTON
			if($admin_mode && !$edit_mode)
				{
	?>
		<a href="<?php echo $link_to_account_admin.'/edit/payment/'.$payment['hashid']?>">
		<img src="<?php echo BASEURL.'/img/pencil.png'?>" alt='Edit payment' class="editicon" />
		</a>

			<form method="post" 
	class="deleteicon"
	action="<?php echo ACTIONPATH.'/delete_payment.php'?>"
		>
		<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>"/>
		<input type="hidden" name="p_hashid_payment" value="<?php echo $payment['hashid']?>" />
		<span>
		<input type="image" 
			name="submit_delete_payment"
			src="<?php echo BASEURL.'/img/delete.png'?>" 
			border="0" 
			class="confirmation deleteicon"
			alt="Delete payment" 
			value="Submit">
		</span>
	</form>
		
		


		<?php
				}
			}//end else admin mode 
			?>
			</div>
		</li>
<?php	}//foreach current payment 
?>
	</ul>
	<?php
	}//if payment exist
	else
	{ ?>
		<p>No payments recorded.</p>		
<?php
	}//end else payment exists
?>	


		<?php // PAYMENTS
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
						<select name="p_payment[0][p_hashid_payer]]" 
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
					<input type="number" step="0.01" min="0" name="p_payment[0][p_cost]]" 
						id="<?php echo 'form_set_payment_cost_'.$cpt_bill?>_0" required 
						class="input_paymt_cost"/>
				</span><span>
					<label for="<?php echo 'form_set_payment_recv_'.$cpt_bill?>_0">Receiver</label>
					<select name="p_payment[0][p_hashid_recv]]" id="<?php echo 'form_set_payment_recv_'.$cpt_bill?>_0"> 
					<option value="-1" selected="selected">Group</option>
					</select>
				</span><span>
					<label for="<?php echo 'form_set_payment_desc_'.$cpt_bill?>_0">Description</label>
					<input type="text" name="p_payment[0][p_description]]" 
						id="<?php echo 'form_set_payment_desc_'.$cpt_bill?>_0" 
						class="input_paymt_desc" />
				</span><span>
				<label for="<?php echo 'form_set_payment_date_'.$cpt_bill?>_0">Date of payment</label>
				<input type="date" name="p_payment[0][p_date_payment]]" 
						id="<?php echo 'form_set_payment_date_'.$cpt_bill?>_0"
						class="date_picker input_paymt_date"/>
				</span>
			</div>
		</div>
<?php
	$name_of_people = array_column($this_bill_participants, 'name');
	$hashid_of_people = array_column($this_bill_participants, 'participant_hashid');
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
<?php
}//foreach bill
}//if bills exist
?>

<!-- SOLUTION -->
<?php if (isset($solution) && is_array($solution) && sizeof($solution) > 0 )
{
?>
	<h1>A solution</h1>
	<p>Total money: <?php echo $solution['-1']['total']?>&euro;</p>
	<ul>
<?php
	foreach($my_participants as $payer)
	{
		$uid = $payer['id'];
		if(!isset($solution[$uid])){continue;}
		foreach($my_participants as $receiver)
		{
			$vid = $receiver['id'];
			if(!isset($solution[$uid][$vid])){continue;}
							$refund = number_format((float)$solution[$uid][$vid], 2, '.', '');
			if($refund > 0)
			{
?>
<li>
	<span class='bill_participant' style="background-color:<?php echo '#'.$payer['color']?>"><?php echo $payer['name']?></span> 
	must refund <?php echo $refund?> &euro; to 
	<span class='bill_participant' style="background-color:<?php echo '#'.$receiver['color']?>"><?php echo $receiver['name']?></span>
</li>
<?php
			}
		}
	}
?>
</ul>
<?php
} //if there is a solution
?>

</body>
</html>