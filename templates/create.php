<!DOCTYPE html>

<html>
<head>
<title>Create a new account</title>
<link rel="stylesheet" type="text/css" href="<?php echo BASEURL.'/css/global.css'?>">
</head>
<body>

<div id="content">
<header>
<?php include(__DIR__.'/header/header.php'); ?>
</header>


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
	<label for="form_title_account">Title</label>
    <input type="text" name="p_title_of_account" id="form_title_account" required /><br>
	<label for="form_email">Email</label>
	<input type="email" name="p_contact_email" id="form_email" required/><br>
    <label for="form_description">Detailed description</label>
	<input type="text" name="p_description" id="form_description"/><br>
	 <button type="submit" name="submit" value="Submit">Submit</button> 
  </fieldset>
</form>

</div> <!-- content -->
</body>
</html>