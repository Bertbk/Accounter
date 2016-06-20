<!DOCTYPE html>

<html>
<head>

<title>Retrieve your accounts</title>
<link rel="stylesheet" type="text/css" href="<?php echo BASEURL.'/css/global.css'?>">

</head>
<body>

<div id="content">
<header>
<?php include(__DIR__.'/header/header.php'); ?>
</header>

<h1>Retrieve your accounts</h1>


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

</div> <!-- content -->
</body>