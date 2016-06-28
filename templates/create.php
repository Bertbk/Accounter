<!DOCTYPE html>

<html>
<head>
<title>Create an account</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="<?php echo BASEURL.'/bootstrap/css/bootstrap.min.css'?>" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?php echo BASEURL.'/css/global.css'?>">
<link rel="stylesheet" type="text/css" href="<?php echo BASEURL.'/css/index.css'?>">
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

			<form method="post">
				<fieldset>
					<legend class="sr-only">Create a new account</legend>
					<div class="form-group">
						<label for="form_title_account">Title*</label>
						<input type="text" name="p_title_of_account" id="form_title_account" required
							class="form-control" placeholder="Title">
					</div>
					<div class="form-group">
						<label for="form_email">Email address*</label>
						<input type="email" name="p_contact_email" id="form_email" required class="form-control"
							placeholder="Email address">
					</div>
					<div class="form-group">
						<label for="form_description">Description: </label>
						 <textarea name="p_description" id="form_description" class="form-control" 
						 placeholder="Description"></textarea>
					</div>

					<button type="submit" name="submit" value="Submit"
						class="btn btn-primary">
						Submit
					</button> 
				</fieldset>
			</form>
		</div>
	</div> <!-- content -->
</body>
</html>