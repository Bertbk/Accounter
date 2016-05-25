<!DOCTYPE html>

<html>
<head>
</head>
<body>


<h1>Welcome to the account: <?php echo $my_account['title']?></h1>
<p>Associated email adress : <?php echo $my_account['email']?></p>
	
<?php if (is_array($my_contributors) && sizeof($my_contributors) > 0 )
{
?>
<h1>Contributors (<?php echo $n_contributors ?>) / Parts (<?php echo $n_parts ?>)</h1>
<ul>
<?php
	foreach($my_contributors as $contrib)
	{
?>
		<li><?php echo $contrib['name']?> (<?php echo $contrib['number_of_parts']?>)</li>
<?php
	}
?>
</ul>	
<?php
}
?>

<?php if (is_array($my_payments) && sizeof($my_payments) > 0 )
{
?>
<h1>Payments</h1>
<ul>
<?php
	foreach($my_payments as $payment)
	{
?>
		<li><?php echo $payment['payer_id']?> paid <?php echo $payment['cost']?> to <?php echo $payment['receiver_id']?></li>
<?php
	}
?>
</ul>
<?php
}
?>


<?php
//If admin mode
if($admin_mode)
{
?>
	<h1>Administration section</h1>

	<form method="post">
	  <fieldset>
		<legend>Add a contributor:</legend>
		Name: <input type="text" name="name_of_contributor" required /><br>
		Nb. of parts: <input type="number" name="number_of_parts" value="1" required /><br>
		 <button type="submit" name="submit_contrib" value="Submit">Submit</button> 
	  </fieldset>
	</form>

	<form method="post">
	  <fieldset>
		<legend>Add a payment:</legend>
		<select name="p_payer_id"> 
<?php
		foreach($my_contributors as $contrib)
		{
?>
			<option value="<?php echo $contrib['id']?>" selected><?php echo $contrib['name']?></option>
<?php
		}
?>
		</select>
		<input type="number" step="0.01" min="0" name="p_cost" required />	
		<select name="p_receiver_id"> 
		<option value="-1" selected>Group</option>
<?php
		foreach($my_contributors as $contrib)
		{
?>
			<option value="<?php echo $contrib['id']?>" selected><?php echo $contrib['name']?></option>
<?php
		}
?>
		</select>
		<input type="text" name="p_description"  />
		<input type="date" name="p_date_creation" />
		<br><button type="submit" name="submit_payment" value="Submit">Submit</button> 
	  </fieldset>
	</form>

	
<?php
}
?>


<h1>Menu</h1>
<ul>
<li><a href='/DivideTheBill'>Main Menu</a></li>
<li><a href='/DivideTheBill/create.php'>Create a new account</a></li>
</ul>

</body>
</html>