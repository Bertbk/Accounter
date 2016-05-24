<!DOCTYPE html>

<html>
<head>
<script>
</script>
</head>
<body>

<h1>Create a new account</h1>
<?php
//$hashid = base_convert(microtime(false), 10, 36);
?>

<form method="post" action="create_bis.php" onsubmit="return validate()">
  <fieldset>
    <legend>Create a new account:</legend>
    Name: <input type="text" name="name_of_account" id="name_of_account" required /><br>
    Email: <input type="email" name="contact_email" id="contact_email" /><br>
<!--    <input type="hidden" name="hashid" id="hashid" value=<?php echo $hashid ?>/><br>-->
	<input type="submit" name="new_account" value="Submit" />
  </fieldset>
</form>


</body>
</html>