<!DOCTYPE html>

<html>
<head>
<title>Account created</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="<?php echo BASEURL.'/bootstrap/css/bootstrap.min.css'?>" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?php echo BASEURL.'/css/global.css'?>">
<link rel="stylesheet" type="text/css" href="<?php echo BASEURL.'/css/account_created.css'?>">
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
				<div class="col-lg-8 col-lg-offset-2">
					<div class="panel panel-success">
						<div class="panel-heading">
							<h1>Account created</h1>
						</div>
						<div class="panel-body">
							<p>Your account <?php htmlspecialchars($my_account['title'])?> has been created!</p>
							<div class="row">
								<div class="col-xs-12 col-md-6 form-group">
									<div class="row">
										<div class="col-xs-12">
											<p class="form-group"><a href="<?php echo $link_contrib?>">Public link to the account <span class="btn-link glyphicon glyphicon-link"></span></p> 
											<input class="form-control" readonly="readonly" value="<?php echo $link_contrib?>" onclick="select();"
												type="text">
										</div>
									</div>
								</div>
								<div class="col-xs-12 col-md-6 form-group">
									<div class="row">
										<div class="col-xs-12">
											<p class="form-group"><a href="<?php echo $link_admin?>">Administrator link to the account <span class="btn-link glyphicon glyphicon-link"></span></p> 
											<input class="form-control" readonly="readonly" value="<?php echo $link_admin?>" onclick="select();"
												type="text">							
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div> <!-- content -->
</body>
</html>