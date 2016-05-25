<!DOCTYPE html>

<html>
<head>
<script type="text/javascript" src="/DivideTheBill/js/account.js">
</script>
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
		<li><?php echo $payment['payer_name']?> paid <?php echo $payment['cost']?>&euro; to 
		<?php echo (is_null($payment['receiver_name']))?'all':$payment['receiver_name']?>
		<?php if(!empty($payment['date_creation'])){echo ', the '.str_replace('-', '/',$payment['date_creation']);}?>
		<?php if(!empty($payment['description'])){echo 'for '.$payment['description'];}?></li>
<?php
	}
?>
</ul>
<?php
}
?>

<?php if (is_array($solution) && sizeof($solution) > 0 )
{
?>
<h1>A solution</h1>
<p>Total money: <?php echo $solution['-1']['total']?></p>
<p>Nb. parts  : <?php echo $solution['-1']['nparts']?></p>
<p>Single part: <?php echo $solution['-1']['single']?></p>
<ul>
<?php
foreach($my_contributors as $payer)
	{
		$uid = $payer['id'];
		foreach($my_contributors as $receiver)
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
//If admin mode
if($admin_mode)
{
?>
<!-- Admin mode-->
	<h1>Administration section</h1>
<!-- Add contributor-->

	<form method="post">
	  <fieldset>
		<legend>Add a contributor:</legend>
		Name: <input type="text" name="name_of_contributor" required /><br>
		Nb. of parts: <input type="number" name="number_of_parts" value="1" required /><br>
		 <button type="submit" name="submit_contrib" value="Submit">Submit</button> 
	  </fieldset>
	</form>
	
<!-- Add payment -->
	<form method="post" id="form_payment_send">
	  <fieldset>
		<legend>Add a payment:</legend>
		<select name="p_payer_id" onchange="configureDropDownLists(this, document.getElementById('form_payment_recv'))"> 
<option disabled selected value="null"> -- select a payer -- </option>
<?php
		foreach($my_contributors as $contrib)
		{
?>
			<option value="<?php echo $contrib['id']?>"><?php echo $contrib['name']?></option>
<?php
		}
?>
		</select>
		<input type="number" step="0.01" min="0" name="p_cost" required />
		<select name="p_receiver_id" id="form_payment_recv"> 
		<option value="-1" selected="selected">Group</option>
<?php
	//	foreach($my_contributors as $contrib)
	//	{
?>
	<!--		<option value="<?php echo $contrib['id']?>"><?php echo $contrib['name']?></option>-->
<?php
//		}
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

<!--Menu -->

<h1>Menu</h1>
<ul>
<li><a href='/DivideTheBill'>Main Menu</a></li>
<li><a href='/DivideTheBill/create.php'>Create a new account</a></li>
</ul>

</body>
</html>