<!DOCTYPE html>

<html>
<head>
</head>
<body>

<?php 
if($create_success)
{
?>
	<h1> Accoung created</h1>
	<ul>
	<li>Contributor link  :<a href="<?php echo $link_contrib?>"><?php echo $link_contrib?> </a></li>
	<li>Administrator link:<a href="<?php echo $link_admin?>"><?php echo $link_admin?> </a> </li>
	</ul>
<?php
}
else
{
?>
<h1>Create a new account</h1>

<form method="post">
  <fieldset>
    <legend>Create a new account:</legend>
    Title: <input type="text" name="title_of_account" id="title_of_account" required /><br>
    Email: <input type="email" name="contact_email" id="contact_email" required/><br>
	 <button type="submit" name="submit" value="Submit">Submit</button> 
  </fieldset>
</form>

<h1>Menu</h1>
<ul>
<li><a href='/DivideTheBill'>Main Menu</a></li>
</ul>

<?php
}
?>

</body>
</html>