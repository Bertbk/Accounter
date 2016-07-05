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
Template of the create an account page.
 */
 ?>
 
 <!DOCTYPE html>

<html>
<head>
<title>Create an account</title>
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

			<h1>Create a new account</h1>

			<form method="post" action="<?php echo ACTIONPATH.'/new_account.php'?>">
				<fieldset>
					<legend class="sr-only">Create a new account</legend>
					<p><em>Fields with asterisk <span class="glyphicon glyphicon-asterisk red"></span> are required</em></p>
					<div class="form-group">
						<label for="form_title_account">Title<span class="glyphicon glyphicon-asterisk red"></span></label>
						<input type="text" name="p_title_of_account" id="form_title_account" required
							class="form-control" placeholder="Title" title="Title">
					</div>
					<div class="form-group">
						<label for="form_author">Author<span class="glyphicon glyphicon-asterisk red"></span></label>
						<input type="text" name="p_author" id="form_author" required class="form-control"
							placeholder="Author" title="Author">
					</div>
					<div class="form-group">
						<label for="form_email">Email address<span class="glyphicon glyphicon-asterisk red"></span></label>
						<input type="email" name="p_contact_email" id="form_email" required class="form-control"
							placeholder="Email address" title="Email address">
					</div>
					<div class="form-group">
						<label for="form_description">Description</label>
						 <textarea name="p_description" id="form_description" class="form-control" 
						 placeholder="Description" title="Description"></textarea>
					</div>

					<button type="submit" name="submit_new_account" value="Submit"
						class="btn btn-primary" title="Submit new account">
						Submit
					</button> 
				</fieldset>
			</form>
		</div>
	</div> <!-- content -->
</body>
</html>