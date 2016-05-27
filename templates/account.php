<!DOCTYPE html>

<html>
<head>
<script type="text/javascript" src="/js/account.js">
</script>
</head>
<body>


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
if($admin_mode && $edit_participant && $participant['id'] === $participant_id_to_edit)
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
{
?>
		<li>
		<?php echo $participant['name']?> 
		(<?php echo $participant['nb_of_people'];if(!empty($participant['email'])){echo ', '.$participant['email'];}?>)

<?php //Edit link
if($admin_mode && !$edit_mode)
{
	$link = BASEURL.'/account/'.$hashid.'/admin/edit_participant/'.$participant['hashid'];
?>
	<a href="<?php echo $link?>">edit me</a>
<?php
}
?>		
		</li>
<?php
}//inner else
} //outer else
} //foreach
?>
</ul>	
<?php
}
?>

<!-- PAYMENTS -->
<?php if (is_array($my_payments) && sizeof($my_payments) > 0 )
{
?>
<h1>Payments</h1>
<ul>
<?php
	foreach($my_payments as $payment)
	{
		if($admin_mode && $edit_payment && $payment['id'] === $payment_id_to_edit)
		{
			//Edit mode
?>
<li>
	<form method="post" id="form_edit_payment_send">
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
		<input type="date" name="p_date_creation" value="<?php echo $payment_to_edit['date_creation']?>"/>
		<br><button type="submit" name="submit_edit_payment" value="Submit">Submit</button> 
		<button type="submit" name="submit_cancel" value="Submit">Cancel</button> 
	</form>
	</li>
<?php
		}
		else{//Read only
	?>
		<li><?php echo $payment['payer_name']?> paid <?php echo $payment['cost']?>&euro; to 
		<?php echo (is_null($payment['receiver_name']))?'all':$payment['receiver_name']?>
		<?php if(!empty($payment['date_creation'])){echo ', the '.str_replace('-', '/',$payment['date_creation']);}?>
		<?php if(!empty($payment['description'])){echo 'for '.$payment['description'];}?>
<?php //EDIT BUTTON
		if($admin_mode && !$edit_mode)
			{
	$link = BASEURL.'/account/'.$hashid_url.'/admin/edit_payment/'.$payment['hashid'];
?>
	<a href="<?php echo $link?>">edit me</a>
<?php
	}//inner if/else
	}//outer else
}//foreach
?>
</ul>
<?php
}//if payment exist
?>

<?php if (is_array($solution) && sizeof($solution) > 0 )
{
?>
<h1>A solution</h1>
<p>Total money: <?php echo $solution['-1']['total']?><br>
Nb. parts  : <?php echo $solution['-1']['nparts']?><br>
Single part: <?php echo $solution['-1']['single']?></p>
<ul>
<?php
foreach($my_participants as $payer)
	{
		$uid = $payer['id'];
		foreach($my_participants as $receiver)
		{
			$vid = $receiver['id'];
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
}
?>

<?php
//Admin only
if($admin_mode)
{
?>
<!-- Admin mode-->
	<h1>Administration section</h1>
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
	
<!-- Add payment -->
	<form method="post" id="form_payment_send">
	  <fieldset>
		<legend>Add a payment:</legend>
		<label for="form_set_payment_payer">Payer</label>
		<select name="p_payer_id" id=="form_set_payment_payer" onchange="configureDropDownLists(this, document.getElementById('form_set_payment_recv'))"> 
<option disabled selected value="null"> -- select a payer -- </option>
<?php
		foreach($my_participants as $participant)
		{
?>
			<option value="<?php echo $participant['id']?>"><?php echo $participant['name']?></option>
<?php
		}
?>
		</select>
		<label for="form_set_payment_cost">Cost</label>
		<input type="number" step="0.01" min="0" name="p_cost" id="form_set_payment_cost" required />
		<label for="form_set_payment_recv">Receiver</label>
		<select name="p_receiver_id" id="form_set_payment_recv"> 
		<option value="-1" selected="selected">Group</option>
		</select>
		<label for="form_set_payment_desc">Description</label>
		<input type="text" name="p_description" id="form_set_payment_desc" />
		<label for="form_set_payment_date">Date of payment</label>
		<input type="date" name="p_date_creation" id="form_set_payment_date"/>
		<br><button type="submit" name="submit_payment" value="Submit">Submit</button> 
	  </fieldset>
	</form>

	
<?php
}
?>

<!--Menu -->

<h1>Menu</h1>
<ul>
<li><a href='<?php echo BASEURL.''?>'>Main Menu</a></li>
<li><a href='<?php echo BASEURL.'/create.php'?>'>Create a new account</a></li>
</ul>

</body>
</html>