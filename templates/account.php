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

<?php if($admin_mode && $edit_mode)
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
if($admin_mode && $edit_mode === 'participant' && $participant['hashid'] === $edit_hashid)
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
<?php
	$link =$link_to_account_admin.'/delete/participant/'.$participant['hashid'];
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
if($admin_mode && !$edit_mode)
{
	?>
	<div id="div_add_participant">
	<p id="show_hide_add_participant"><a href="javascript:void(0)">(+) Add a participant</a></p>	
<!-- Add participant-->
	<form method="post" 
	action="../../controls/action/new_participant.php"
	id="show_hide_add_participant_target" 
	class="hidden_at_first">
	  <fieldset>
		<legend>Add a participant:</legend>
		<span>
		<label for="form_set_participant_name">Name: </label>
		<input type="hidden" name="p_hashid_account" 
		value="<?php echo $my_account['hashid_admin']?>" />
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



</body>
</html>