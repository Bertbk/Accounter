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
	<legend for="form_title_account">Title</legend>
    <input type="text" name="p_title_of_account" id="form_title_account" required /><br>
	<legend for="form_email">Email</legend>
	<input type="email" name="p_contact_email" id="form_email" required/><br>
    <legend for="form_description">Detailed description</legend>
	<input type="text" name="p_description" id="form_description"/><br>
	 <button type="submit" name="submit" value="Submit">Submit</button> 
  </fieldset>
</form>

<h1>Menu</h1>
<ul>
<li><a href=<?php echo BASEURL?>>Main Menu</a></li>
</ul>

<?php
}
?>

</body>
</html>