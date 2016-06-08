<!DOCTYPE html>

<html>
<head>
</head>
<body>


<h1>Create a new account</h1>

<?php 
//if Error
if(!empty($errArray))
{
?>
<div class="error">
<h2>Error: </h2>
<ul>
<?php 
foreach($errArray as $err)
{
?>
<li>
<?php  echo $err ?>
</li>
<?php	
}
?>
</ul>
</div>
<?php	
}
?>


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


</body>
</html>