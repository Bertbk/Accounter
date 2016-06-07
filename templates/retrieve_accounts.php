<!DOCTYPE html>

<html>
<head>
<title>Account</title>
<link rel="stylesheet" type="text/css" href="<?php echo BASEURL.'/css/bill.css'?>">
<link rel="stylesheet" type="text/css" href="<?php echo BASEURL.'/css/bill_participant.css'?>">
<link rel="stylesheet" type="text/css" href="<?php echo BASEURL.'/css/account.css'?>">

</head>
<body>

<h1>Retrieve your accounts</h1>

<?php 
if($problem)
{
	?>
		<p>Sorry, no account associated with this email address has been found. <br>
		Please try again with another email address.</p>
<?php	
}
?>

<div id="form_retrieve_accounts">
<form method="post" action="<?php echo BASEURL.'/accounts_retrieved.php'?>">
<span>
<label for="form_retrieve_accounts_email">Email address</label>
<input type="email" name="p_email" class="input_email" 
id="form_retrieve_accounts_email" 
required />
</span>
<div>
<button type="submit" name="submit_email" value="Submit">Submit</button> 
</div>
</form>
</div>

</body>