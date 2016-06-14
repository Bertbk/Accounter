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
<form method="post">
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
	$link = $link_to_account_admin.'/edit/participant/'.$participant['hashid'];
?>
	<a href="<?php echo $link?>">
	<img src="<?php echo BASEURL.'/img/pencil_white.png'?>" alt='Edit participant' class="editicon" >
	</a>
	<form method="post" 
	class="deleteicon"
	action="<?php echo ACTIONPATH.'/delete_participant.php'?>"
		>
		<input type="hidden" 
		name="p_hashid_account" 
		value=<?php echo $my_account['hashid_admin']?>
		/>
		<input type="hidden"  
		name="p_hashid_participant" 
		value=<?php echo $participant['hashid']?> 
		/>
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
<h1>Our bills</h1>
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
	if(!empty($my_bill_participants[$bill['id']]))
	{$this_bill_participants = $my_bill_participants[$bill['id']];}
	if(!empty($my_free_bill_participants[$bill['id']]))
	{	$this_free_bill_participants = $my_free_bill_participants[$bill['id']];}
?>
<div class="bill 
<?php echo 'bill-'.$cpt_bill?>" style="background-color:<?php echo '#'.$bill['color']?>"
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
		<button type="submit" name="submit_update_bill" value="Submit">Submit</button> 
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
				<span><button type="submit" name="submit_new_bill_participant" value="Submit">Submit</button></span>
			</div>
		  </fieldset>
		</form>
<?php 
		} //if empty free_participants
	}//if admin
?>


</div> 
</div> 
<?php
}//foreach bill
}//if bills exist
?>


</body>
</html>