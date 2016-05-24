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
<h1>Contributors</h1>
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

<?php
//If admin mode
if($admin_mode)
{
?>
	<h1>Administration section</h1>

	<form method="post">
	  <fieldset>
		<legend>Add a contributor:</legend>
		Name: <input type="text" name="name_of_contributor" id="name_of_contributor" required /><br>
		Nb. of parts: <input type="number" name="number_of_parts" id="number_of_parts" value="1" required /><br>
		 <button type="submit" name="submit" value="Submit">Submit</button> 
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