<!DOCTYPE html>

<html>
<head>
<title>Account</title>
<link rel="stylesheet" type="text/css" href="<?php echo BASEURL.'/css/bill.css'?>">
<link rel="stylesheet" type="text/css" href="<?php echo BASEURL.'/css/bill_participant.css'?>">
<script type="text/javascript" src="<?php echo BASEURL.'/js/account.js'?>"></script>
</head>
<body>
<?php if($admin_mode && $edit_mode)
{
?>
<p>Edit mode activated <form method="post"><button type="submit" name="submit_cancel" value="Submit">Cancel</button></form></p>
<?php } ?>

<h1>Welcome to the account: <?php echo $my_account['title']?></h1>
<p>Associated email adress : <?php echo $my_account['email']?></p>
	
<?php if (is_array($my_participants) && sizeof($my_participants) > 0 ) 
	{
?>
<h1>Participants (<?php echo $n_participants ?>) / People (<?php echo $n_people ?>)</h1>
<ul>
<?php
	foreach($my_participants as $participant)
	{
?>
<?php
if($admin_mode && $what_to_edit['participant'] && $participant['id'] === $participant_id_to_edit)
{
?>
		<li>
		<form method="post">
		<input type="text" name="name_of_participant" value="<?php echo $participant_to_edit['name']?>" required />
		(<input type="number" name="nb_of_people" value="<?php echo $participant_to_edit['nb_of_people']?>" required />)
		<input type="email" name="email" value="<?php echo $participant_to_edit['email']?>"/>
		<button type="submit" name="submit_edit_participant" value="Submit">Edit</button>
		<button type="submit" name="submit_cancel" value="Submit">Cancel</button> 
	</form>

		</li>
<?php
}//if
else{ // READ Only
?>
		<li>
		<?php echo $participant['name']?> 
		(<?php echo $participant['nb_of_people'];if(!empty($participant['email'])){echo ', '.$participant['email'];}?>)

<?php //Edit link
if($admin_mode && !$edit_mode)
{
	$link = BASEURL.'/account/'.$hashid.'/admin/edit_participant/'.$participant['hashid'];
?>
	<a href="<?php echo $link?>">
	<img src="<?php echo BASEURL.'/img/pencil.png'?>" alt='Edit me' width="20px" />
	</a>
<?php
}
?>		
		</li>
<?php
}//if/else admin
} //foreach participants
?>
</ul>	
<?php }//if !empty(participants)
?>

<?php
//Admin only
if($admin_mode && !$edit_mode)
{
?>
<!-- Add participant-->
	<form method="post">
	  <fieldset>
		<legend>Add a participant:</legend>
		<label for="form_set_participant_name">Name: </label>
		<input type="text" name="p_name_of_participant" id="form_set_participant_name" required /><br>
		<label for="form_set_participant_nbpeople">Nb. of people: </label>
		 <input type="number" name="p_nb_of_people" value="1" id="form_set_participant_nbpeople" required /><br>
		<label for="form_set_participant_email">Email adress: </label>
		 <input type="email" name="p_email" id="form_set_participant_email"  /><br>
		 <button type="submit" name="submit_participant" value="Submit">Submit</button> 
	  </fieldset>
	</form>

<?php } //admin mode
?>



<!-- BILLS -->
<?php if (is_array($my_bills) && sizeof($my_bills) > 0 )
{
?>
<h1>Bills</h1>

<?php
//Admin only
if($admin_mode && !$edit_mode)
{
?>
<!-- Add bill-->

	<form method="post">
	  <fieldset>
		<legend>Add a bill:</legend>
		<label for="form_set_bill_name">Name: </label>
		<input type="text" name="p_name_of_bill" id="form_set_bill_name" required /><br>
		<label for="form_set_bill_description">Description: </label>
		 <input type="text" name="p_description" id="form_set_bill_description" /><br>
		 <button type="submit" name="submit_bill" value="Submit">Submit</button> 
	  </fieldset>
	</form>

<?php } //admin mode
?>



<?php 
$cpt_bill = -1;
foreach($my_bills as $bill)
{
	$cpt_bill ++;
?>
<div class="bill 
<?php echo 'bill-'.$cpt_bill?> 
<?php echo 'bgColor'.$cpt_bill?>
">
	<?php if($admin_mode && $what_to_edit['bill'] 
	&& $bill_id_to_edit == $bill['id'])
	{
	?>
	<form method="post">
	<h2>
	<label for="form_edit_bill_name">Title: </label>
	<input type="text" name="p_title" id="form_edit_bill_name"  
	value="<?php echo $bill['title']?>" required />
	</h2>
	<label for="form_edit_bill_description">Description: </label>
	 <input type="text" name="p_description" id="form_edit_bill_description" 
	 value="<?php echo $bill['description']?>"/><br>
	<button type="submit" name="submit_edit_bill" value="Submit">Submit</button> 
	<button type="submit" name="submit_cancel" value="Submit">Cancel</button> 
	</form>
	<?php	
	}
	else{
?>
	<h2><?php echo $bill['title'] ?>
	<?php
	if($admin_mode && !$edit_mode)
	{
		$link = BASEURL.'/account/'.$hashid.'/admin/edit_bill/'.$bill['hashid'];
		?>
		<a href='<?php echo $link?>'>
		<img src="<?php echo BASEURL.'/img/pencil.png'?>" alt='Edit me' width="20px" />
		</a>
<?php }	?>
	</h2>
	<?php if(!empty($bill['description']) && !is_null($bill['description']))
	{
?>
	<p><?php echo $bill['description']?></p>
<?php }?>
<?php }//if/else admin 
?>

<?php // Display the current participant of this bill
	$current_bill_participants =array();
	if(!empty($my_bill_participants[$bill['id']]))
	{
?>
		<h3>Participants</h3>
<?php
	$current_bill_participants = $my_bill_participants[$bill['id']];
	$place_submit_button = false; // if editing, place a button after the list
	$cpt_bill_participant = -1;
	foreach($current_bill_participants as $bill_participant)
	{
		$cpt_bill_participant++;
		if(!$admin_mode || !$what_to_edit['bill_participant'] 
		|| $bill_id_to_edit != $bill['id'] || $bill_participant_id_to_edit != $bill_participant['id'])
		{
			?><span 
			class="<?php echo 'bill_participant billpartColor'.$cpt_bill_participant?>">
			<?php
			echo $bill_participant['name'].'('.$bill_participant['percent_of_usage'].'%)';
			if($admin_mode && !$edit_mode){
				?><a href="<?php echo BASEURL.'/account/'.$hashid.'/admin/edit_bill_part/'.$bill_participant['hashid']?>">
				<img src="<?php echo BASEURL.'/img/pencil.png'?>" alt='Edit me' width="20px" />
				</a>
		<?php	} ?>
			</span>
	<?php }
		else
		{ //Edit activated of THIS bill_participant
			$place_submit_button = true;
	?>
			<form method="post">
			<select name="p_participant_id" selected="<?php echo $bill_participant['participant_id']?>">
	<?php
			foreach($my_participants as $participant)
			{
	?>
				<option value="<?php echo $participant['id']?>" 
				<?php if($participant['id']==$bill_participant['participant_id']){echo ' selected';}?>
				><?php echo $participant['name']?></option>
	<?php
			}
	?>
			</select>
			 (<input type="number" step="0.01" min="0" max="100" name="p_percent_of_use" 
			 value="<?php echo $bill_participant['percent_of_usage']?>" required />%)		 
	<?php
		}//else admin mode
	}//foreach participant in this bill
	//Submit button for editing
	if($place_submit_button)
	{
	?>
		<br><button type="submit" name="submit_edit_bill_participant" value="Submit">Submit</button> 
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
		if(!empty($my_free_bill_participants[$bill['id']]))
		{
	?>
		<form method="post">
		  <fieldset>
			<legend>Assign a participant to this bill:</legend>
			<label for="<?php echo 'form_assign_participant_id'.$bill['id']?>">Participant available</label>
			<select name="p_participant_id" id="<?php echo 'form_assign_participant_id'.$bill['id']?>"> 
	<option disabled selected value="null"> -- select a participant -- </option>
	<?php
			foreach($my_participants as $participant)
			{
				$isfree = true;
				foreach($current_bill_participants as $curbill)
				{
					if($participant['id'] == $curbill['participant_id'])
					{
						$isfree = false;
						break;
					}
				}
				if(!$isfree){continue;}
	?>
				<option value="<?php echo $participant['id']?>"><?php echo $participant['name']?></option>
	<?php
			}
	?>
			</select><br>		
			<label for="<?php echo 'form_assign_participant_percent'.$bill['id']?>">Percentage of use: </label>
			 <input type="number" step="0.01" min="0" max="100" name="p_percent_of_use" 
			 value="100.00" id="<?php echo 'form_assign_participant_percent'.$bill['id']?>" required /><br>
			<input type="hidden" name="p_bill_id" value="<?php echo $bill['id']?>">
			 <button type="submit" name="submit_assign_participant" value="Submit">Submit</button> 
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
		$current_payment = $my_payments_per_bill[$bill['id']];
	?>
	<ul>
	<?php
		foreach($current_payment as $payment)
		{
	?><li><?php
			if($admin_mode && $what_to_edit['payment'] 
			&& $payment['id'] === $payment_id_to_edit)
			{ //Edit mode 
?>
		<form method="post" id="form_edit_payment_send">
			<select name="p_bill_id" id="form_set_payment_bill"> 
	<?php //list of bills
			foreach($my_bills as $bill)
				{
	?>
					<option value="<?php echo $bill['id']?>"
					<?php if($bill['id']==$payment_to_edit['bill_id']){echo ' selected';}?>
					><?php echo $bill['title']?></option>
	<?php
				}
	?>
			</select>
			<select name="p_payer_id" onchange="configureDropDownLists(this, document.getElementById('form_edit_payment_recv'))" > 
	<?php
				foreach($my_participants as $participant)
				{
	?>
					<option value="<?php echo $participant['id']?>"
					<?php if($participant['id']==$payment_to_edit['payer_id']){echo ' selected';}?>
					>
					<?php echo $participant['name']?></option>
	<?php
				}
	?>
			</select>
			<input type="number" step="0.01" min="0" name="p_cost" value="<?php echo $payment_to_edit['cost']?>" required />
			<select name="p_receiver_id" id="form_edit_payment_recv" selected="<?php echo $payment_to_edit['receiver_id']?>"> 
			<option value="-1" >Group</option>
	<?php
			foreach($my_participants as $participant)
				{
					if($participant['id'] == $payment_to_edit['payer_id']){continue;}
	?>
					<option value="<?php echo $participant['id']?>"
					<?php if($participant['id']==$payment_to_edit['receiver_id']){echo ' selected';}?>
					>
					<?php echo $participant['name']?></option>
	<?php
				}
	?>
			</select>
			<input type="text" name="p_description" value="<?php echo $payment_to_edit['description']?>" />
			<input type="date" name="p_date_payment" value="<?php echo $payment_to_edit['date_of_payment']?>"/>
			<br><button type="submit" name="submit_edit_payment" value="Submit">Submit</button> 
			<button type="submit" name="submit_cancel" value="Submit">Cancel</button> 
		</form>
	<?php
			}
			else{//Read only
		?>
			<?php echo $payment['payer_name']?> paid <?php echo $payment['cost']?>&euro; to 
			<?php echo (is_null($payment['receiver_name']))?'all':$payment['receiver_name']?>
			<?php if(!empty($payment['date_creation'])){echo ', the '.str_replace('-', '/',$payment['date_creation']);}?>
			<?php if(!empty($payment['description'])){echo 'for '.$payment['description'];}?>
	<?php //EDIT BUTTON
			if($admin_mode && !$edit_mode)
				{
		$link = BASEURL.'/account/'.$hashid.'/admin/edit_payment/'.$payment['hashid'];
	?>
		<a href="<?php echo $link?>">
		<img src="<?php echo BASEURL.'/img/pencil.png'?>" alt='Edit me' width="20px" />
		</a>
	<?php
				}
			}//end else admin mode 
			?>
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
		<?php
	if($admin_mode && !$edit_mode)
	{?>
	<!-- Add payment -->
	<?php
		if(!empty($my_bill_participants[$bill['id']]))
		{
			$this_bill_participants = $my_bill_participants[$bill['id']];
	?>
		<form method="post" id="form_payment_send">
		  <fieldset>
			<legend>Add a payment:</legend>
			<input type="hidden" name="p_bill_id" value = <?php echo $bill['id']?>> 
			<label for="<?php echo 'form_set_payment_payer-'.$cpt_bill?>">Payer</label>
			<select name="p_payer_id" id="<?php echo 'form_set_payment_payer-'.$cpt_bill?>" 
			onchange="configureDropDownLists(this, document.getElementById('<?php echo 'form_set_payment_recv-'.$cpt_bill?>'))"> 
	<option disabled selected value="null"> -- select a payer -- </option>
	<?php

			foreach($this_bill_participants as $participant)
			{
	?>
				<option value="<?php echo $participant['participant_id']?>"><?php echo $participant['name']?></option>
	<?php
			}
	?>
			</select><br>
			<label for="<?php echo 'form_set_payment_cost-'.$cpt_bill?>">Cost</label>
			<input type="number" step="0.01" min="0" name="p_cost" id="<?php echo 'form_set_payment_cost-'.$cpt_bill?>" required /><br>
			<label for="<?php echo 'form_set_payment_recv-'.$cpt_bill?>">Receiver</label>
			<select name="p_receiver_id" id="<?php echo 'form_set_payment_recv-'.$cpt_bill?>"> 
			<option value="-1" selected="selected">Group</option>
			</select><br>
			<label for="<?php echo 'form_set_payment_desc-'.$cpt_bill?>">Description</label>
			<input type="text" name="p_description" id="<?php echo 'form_set_payment_desc-'.$cpt_bill?>" /><br>
			<label for="<?php echo 'form_set_payment_date-'.$cpt_bill?>">Date of payment</label>
			<input type="date" name="p_date_payment" id="<?php echo 'form_set_payment_date-'.$cpt_bill?>"/><br>
			<br><button type="submit" name="submit_payment" value="Submit">Submit</button> 
			</fieldset>
		</form>
<?php
		}//if empty
?>
	<?php
	} //if for displaying possibilities
?>
<?php //Solution
	if (isset($bill_solutions[$bill['id']]) && is_array($bill_solutions[$bill['id']])
		&& !empty($bill_solutions[$bill['id']]))
		{
		$local_solution = $bill_solutions[$bill['id']];
	?>
	<h3>A solution for this bill (see at the end of the page for global solution)</h3>
	<p>Total money: <?php echo $local_solution['-1']['total']?><br>
	Nb. parts  : <?php echo $local_solution['-1']['nb_of_parts']?><br>
	Single part: <?php echo $local_solution['-1']['single']?><br>
	Nb. people  : <?php echo $local_solution['-1']['nb_of_people']?></p>
	<ul>
	<?php
	foreach($my_participants as $payer)
		{
			$uid = $payer['id'];
			if(!isset($local_solution[$uid])){continue;}
			foreach($my_participants as $receiver)
			{
				$vid = $receiver['id'];
				if(!isset($local_solution[$uid][$vid])){continue;}
				$refund = $local_solution[$uid][$vid];
				if($refund > 0)
				{
	?>
	<li><?php echo $payer['name']?> must refund <?php echo $refund?> &euro; to <?php echo $receiver['name']?></li>
	<?php
				}
			}
		}
	?>
	</ul>	
	<?php
	}//if exists(solution)
?>
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
	<p>Total money: <?php echo $solution['-1']['total']?></p>
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
			$refund = $solution[$uid][$vid];
			if($refund > 0)
			{
?>
<li><?php echo $payer['name']?> must refund <?php echo $refund?> &euro; to <?php echo $receiver['name']?></li>
<?php
			}
		}
	}
?>
</ul>
<?php
} //if there is a solution
?>

<!--Menu -->

<h1>Menu</h1>
<ul>
<li><a href='<?php echo BASEURL.''?>'>Main Menu</a></li>
<li><a href='<?php echo BASEURL.'/create.php'?>'>Create a new account</a></li>
</ul>

</body>
</html>