<?php 
/**
 * This software is governed by the CeCILL-B license. If a copy of this license
 * is not distributed with this file, you can obtain one at
 * http://www.cecill.info/licences/Licence_CeCILL-B_V1-en.txt
 *
 * Author of Accounter: Bertrand THIERRY (bertrand.thierry1@gmail.com)
 *
 */
 
 /*
Template of the retrieve accounts page.
 */
 ?>
 
 <!DOCTYPE html>

<html>
<head>
<title>Retrieve your accounts</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="<?php echo BASEURL.'/bootstrap/css/bootstrap.min.css'?>" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?php echo BASEURL.'/css/global.css'?>">
</head>
<body>

	<div id="content">
		<div class="container">
			<div class="row">
				<header>
					<?php include(__DIR__.'/header/header.php'); ?>
				</header>
			</div>
	<?php include(__DIR__.'/messages/messages.php');?>

			<h1>Retrieve your accounts</h1>
			
			<form method="post" action="<?php echo BASEURL.'/controls/search_accounts.php'?>">
				<fieldset>
					<legend class="sr-only">Retrieve your accounts</legend>
					<p><em>Fields with asterisk <span class="glyphicon glyphicon-asterisk red"></span> are required</em></p>
					<div class="form-group">
						<label for="form_retrieve_accounts_email">Email address<span class="glyphicon glyphicon-asterisk red"></span></label>
						<input type="email" name="p_email" id="form_email" required class="form-control"
							placeholder="Email address">
					</div>
					<button type="submit" name="submit_email" value="Submit"
						class="btn btn-primary">
						Submit
					</button> 
				</fieldset>
			</form>
		</div>
	</div> <!-- content -->
</body>
</html>