<!DOCTYPE html>

<html>
<head>
<title>Accounts have been sent</title>
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

				<div class="row">
				<div class="col-xs-12">
						<p>Your accounts have been successfully sent to your email address</p>
						<p><a href="<?php echo BASEURL?>">Come back to the main page</a></p>
				</div>
			</div>
		</div>
	</div> <!-- content -->
</body>
</html>
