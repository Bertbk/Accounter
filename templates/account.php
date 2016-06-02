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
<script src="<?php echo BASEURL.'/js/hide_show_add_participant.jquery'?>"></script>

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
<?php
if($admin_mode && $what_to_edit['participant'])
{
?>
<form method="post">
<?php 
}
?>
<div id="div_participants">
<?php
	foreach($my_participants as $participant)
	{
?>
	<span class='bill_participant' style="background-color:<?php echo '#'.$participant['color']?>">
<?php
if($admin_mode && $what_to_edit['participant'] && $participant['id'] == $participant_id_to_edit)
{
?>
			<input type="text" name="name_of_participant" class="input_name"
			value="<?php echo $participant['name']?>" required />
			(<input type="number" name="nb_of_people" class="input_number"
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
	$link = BASEURL.'/account/'.$hashid.'/admin/edit_participant/'.$participant['hashid'];
?>
	<a href="<?php echo $link?>">
	<img src="<?php echo BASEURL.'/img/pencil_white.png'?>" alt='Edit participant' class="editicon" >
	</a>
<?php
	$link = BASEURL.'/account/'.$hashid.'/admin/delete_participant/'.$participant['hashid'];
?>
	<a href="<?php echo $link?>" class="confirmation">
	<img src="<?php echo BASEURL.'/img/delete_white.png'?>" alt='Delete participant' class="deleteicon" >
	</a>
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
if($admin_mode && $what_to_edit['participant'])
{
?>
<div>
<button type="submit" name="submit_edit_participant" value="Submit">Submit change</button>
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
if($admin_mode && !$edit_mode)
{
	?>
	<div id="div_add_participant">
	<p id="show_hide_add_participant"><a href="javascript:void(0)">(+) Add a participant</a></p>	
<!-- Add participant-->
	<form method="post" id="show_hide_add_participant_target" class="hidden_at_first">
	  <fieldset>
		<legend>Add a participant:</legend>
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
		 <button type="submit" name="submit_participant" value="Submit">Submit</button> 
		 </div>
	  </fieldset>
	</form>
</div>
<?php } //admin mode
?>



<!-- BILLS -->
<h1>Bills</h1>

<?php
//Admin only
if($admin_mode && !$edit_mode)
{
?>
<!-- Add bill-->
<p  id="show_hide_add_bill"><a href="javascript:void(0)" >(+) Add a bill</a></p>
<div id="div_add_bill">
	<form method="post" id="show_hide_add_bill_target" class="hidden_at_first">
	  <fieldset>
		<legend>Add a bill</legend>
		<span>
		<label for="form_set_bill_name">Name: </label>
		<input type="text" name="p_name_of_bill" 
		id="form_set_bill_name" class="input_bill_name" required />
		</span><span>
		<label for="form_set_bill_description">Description: </label>
		 <input type="text" name="p_description" 
		 id="form_set_bill_description" class="input_bill_desc" />
		</span><div>
		 <button type="submit" name="submit_bill" value="Submit">Submit</button> 
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
?>
<div class="bill 
<?php echo 'bill-'.$cpt_bill?> 
"style="background-color:<?php echo '#'.$bill['color']?>"
>
	<?php if($admin_mode && $what_to_edit['bill'] 
	&& $bill_id_to_edit == $bill['id'])
	{
	?>
	<form method="post">
	<h2>
	<label for="form_edit_bill_name">Title: </label>
	<input type="text" name="p_title" id="form_edit_bill_name"
	class="input_bill_name"	value="<?php echo $bill['title']?>" required />
	</h2>
	<label for="form_edit_bill_description">Description: </label>
	 <input type="text" name="p_description" id="form_edit_bill_description" 
	 class="input_bill_desc" value="<?php echo $bill['description']?>"/>
	 <div>
		<button type="submit" name="submit_edit_bill" value="Submit">Submit</button> 
		<button type="submit" name="submit_cancel" value="Submit">Cancel</button> 
	</div>
	</form>
	<?php	
	}
	else{
?>

	<h2><a href="javascript:void(0)" id="<?php echo 'show_hide_bill'.$cpt_bill?>">
	<?php echo $bill['title'] ?>
	</a>
	<?php
	if($admin_mode && !$edit_mode)
	{
		$link = BASEURL.'/account/'.$hashid.'/admin/edit_bill/'.$bill['hashid'];
		?>
		<a href='<?php echo $link?>'>
		<img src="<?php echo BASEURL.'/img/pencil.png'?>" alt='Edit bill' class="editicon" />
		</a>
<?php
		$link = BASEURL.'/account/'.$hashid.'/admin/delete_bill/'.$bill['hashid'];
		?>
		<a href='<?php echo $link?>' class="confirmation">
		<img src="<?php echo BASEURL.'/img/delete.png'?>" alt='Delete bill' class="deleteicon" />
		</a>
		
<?php }	?>
	</h2>
	<div  id="<?php echo 'show_hide_bill'.$cpt_bill.'_target'?>">
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
			class="<?php echo 'bill_participant'?>" style="background-color:<?php echo '#'.$bill_participant['color']?>">
			<?php
			echo $bill_participant['name'].'('.$bill_participant['percent_of_usage'].'%)';
			if($admin_mode && !$edit_mode){
				?><a href="<?php echo BASEURL.'/account/'.$hashid.'/admin/edit_bill_part/'.$bill_participant['hashid']?>">
				<img src="<?php echo BASEURL.'/img/pencil_white.png'?>" alt='Edit this participation' class="editicon" />
				</a>
				<a href="<?php echo BASEURL.'/account/'.$hashid.'/admin/delete_bill_part/'.$bill_participant['hashid']?>" 
				class="confirmation">
				<img class="confirmation deleteicon" 
					src="<?php echo BASEURL.'/img/delete_white.png'?>"
					alt='Remove this participation'/>
				</a>

		<?php	} ?>
			</span>
	<?php }
		else
		{ //Edit activated on THIS bill_participant
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
				class="input_percent"
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
	<p id="<?php echo 'show_hide_bill_add_part_'.$cpt_bill?>"><a href="javascript:void(0)">(+) Assign a participant to this bill</a></p>
		<form method="post" class="hidden_at_first" 
		enctype="multipart/form-data"
		id=<?php echo 'show_hide_bill_add_part_'.$cpt_bill.'_target'?>
		>
		  <fieldset>
			<legend>Assign a participant to this bill:</legend>
			<?php
			$cpt = -1;
			foreach($my_free_bill_participants[$bill['id']] as $participant)
			{
				$cpt++;
	?>
			<div class="Assign_participant_<?php echo $cpt_bill?>_<?php echo $cpt?>">
			  <span><input name="p_participant['<?php echo $cpt?>'][hashid]]" 
				id="<?php echo "form_available_part_".$participant['id']?>"
				value="<?php echo $participant['hashid']?>" type="checkbox">
			  <label for="<?php echo "form_available_part_".$participant['id']?>">
				<?php echo $participant['name']?>
			  </label>
			  </span>
				<span><input name="p_participant['<?php echo $cpt?>'][percent]]" type="number"
						class="input_percent" step="0.01" min="0" max="100" size="5" 
						value="100"></span>
			</div>
	<?php
			}//for each participant
	?>
			<div>
				<span><input type="hidden" name="p_bill_hashid" value="<?php echo $bill['hashid']?>"></span>
				<span><button type="submit" name="submit_assign_participant" value="Submit">Submit</button></span>
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
		$current_payment = $my_payments_per_bill[$bill['id']];
	?>
	<ul>
	<?php
		foreach($current_payment as $payment)
		{
	?><li><?php
			if($admin_mode && $what_to_edit['payment'] 
			&& $payment['id'] === $payment_id_to_edit)
			{ //!!!! Edit mode  !!!!
?>
		<form method="post" id="form_edit_payment_send">
			<select name="p_bill_hashid" id="form_set_payment_bill"> 
	<?php //list of bills
			foreach($my_bills as $bill)
				{
	?>
					<option value="<?php echo $bill['hashid']?>"
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
			<input type="number" step="0.01" min="0" name="p_cost" 
				class="input_paymt_cost"
				value="<?php echo $payment_to_edit['cost']?>" required />
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
			<input type="text" name="p_description" class="input_paymt_desc"
			value="<?php echo $payment_to_edit['description']?>" />
			<input type="date" class="date_picker" name="p_date_payment" 
				class="input_paymt_date"
				value="<?php echo $payment_to_edit['date_of_payment']?>"/>
			<div>
			<span><button type="submit" name="submit_edit_payment" value="Submit">Submit</button> </span>
			<span><button type="submit" name="submit_cancel" value="Submit">Cancel</button> </span>
			</div>
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
		<img src="<?php echo BASEURL.'/img/pencil.png'?>" alt='Edit payment' class="editicon" />
		</a>
<?php
		$link = BASEURL.'/account/'.$hashid.'/admin/delete_payment/'.$payment['hashid'];
	?>
		<a class="confirmation" href="<?php echo $link?>">
		<img src="<?php echo BASEURL.'/img/delete.png'?>" alt='Delete payment' class="deleteicon" />
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
<?php
		$this_bill_participants = $my_bill_participants[$bill['id']];
	?>
		<form method="post" id="<?php echo 'show_hide_bill_add_paymt_'.$cpt_bill.'_target'?>" 
			class="hidden_at_first">
		  <fieldset>
			<legend>Add a payment:</legend>
		<div id="<?php echo 'div_option_add_payment_'.$cpt_bill?>">
			<div><input type="hidden" name="p_bill_hashid" value = <?php echo $bill['hashid']?>> </div>
			<div class="div_set_payment_<?php echo $cpt_bill?>">
			<span>
			<label for="<?php echo 'form_set_payment_payer_'.$cpt_bill?>_0">Payer</label>
				<select name="p_payer_hashid" id="form_set_payment_payer_<?php echo $cpt_bill?>_0" 
				onchange="configureDropDownLists(this, document.getElementById('<?php echo 'form_set_payment_recv_'.$cpt_bill.'_0'?>'))"> 
				<option disabled selected value="null"> -- select a payer -- </option>
	<?php

				foreach($this_bill_participants as $participant)
				{
	?>
					<option value="<?php echo $participant['participant_hashid']?>"><?php echo $participant['name']?></option>
	<?php
				}
	?>
				</select>
			</span><span>
			<label for="<?php echo 'form_set_payment_cost_'.$cpt_bill?>_0">Cost</label>
			<input type="number" step="0.01" min="0" name="p_cost" 
				id="<?php echo 'form_set_payment_cost_'.$cpt_bill?>_0" required 
				class="input_paymt_cost"/>
			</span><span>
			<label for="<?php echo 'form_set_payment_recv_'.$cpt_bill?>_0">Receiver</label>
			<select name="p_receiver_id" id="<?php echo 'form_set_payment_recv_'.$cpt_bill?>_0"> 
			<option value="-1" selected="selected">Group</option>
			</select>
			</span><span>
			<label for="<?php echo 'form_set_payment_desc_'.$cpt_bill?>_0">Description</label>
			<input type="text" name="p_description" 
				id="<?php echo 'form_set_payment_desc_'.$cpt_bill?>_0" 
				class="input_paymt_desc"/>
			</span><span>
			<label for="<?php echo 'form_set_payment_date_'.$cpt_bill?>_0">Date of payment</label>
			<input type="date" class="date_picker" name="p_date_payment" 
					id="<?php echo 'form_set_payment_date_'.$cpt_bill?>_0"
					class="input_paymt_date"/>
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
			Test me
			</a>
		</p>
		
			<div>
				<button type="submit" name="submit_payment" value="Submit">Submit</button>
			</div>
			</fieldset>
		</form>
<?php
		}//if bill_participants not empty (ie: payment possible)
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
	<p>Total money: <?php echo $local_solution['-1']['total']?>&euro;<br>
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
	}//if exists(solution)
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
			$refund = $solution[$uid][$vid];
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

<!--Menu -->

<h1>Menu</h1>
<ul>
<li><a href='<?php echo BASEURL.''?>'>Main Menu</a></li>
<li><a href='<?php echo BASEURL.'/create.php'?>'>Create a new account</a></li>
</ul>

</body>
</html>