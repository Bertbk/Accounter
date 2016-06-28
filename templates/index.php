<!DOCTYPE html>

<html>
<head>
<title>Accounter</title>
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


			<nav id="main_menu">
				<div class="row form-group">
					<div class="col-xs-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
						<img class="screenshot" src="<?php echo BASEURL.'/img/screenshot.png'?>" alt="Screenshot">
					</div>
				</div>
				<div class="row form-group">
					<a href="<?php echo BASEURL.'/create.php'?>">
						<div class="col-xs-12 col-md-4 col-md-offset-1 col-lg-3 col-lg-offset-2 main-menu"  id="create_account">
							<span class="glyphicon glyphicon-piggy-bank"></span> Create a new account
						</div>
					</a>
					<a href="<?php echo BASEURL.'/retrieve_accounts.php'?>">
						<div class="col-xs-12 col-md-4 col-md-offset-1 col-lg-3 col-lg-offset-2 main-menu"  id="retrieve_account">
							<span class="glyphicon glyphicon-search"></span> Retrieve your accounts
						</div>
					</a>
				</div>
			</nav>
			<h1>Table of the accounts</h1>

			<table style="width:100%" border="1">
			 <tr>
			<td> ID </td>
			<td> hashid </td> 
			<td> hashid_admin </td>
			<td> Title </td> 
			<td> Email </td>
				</tr>
			<?php 
			foreach ($accounts as $account)
			{
			?>
				<tr>
					<td><?php echo $account['id']?></td>
					<td> <a href="account/<?php echo $account['hashid']?>"><?php echo $account['hashid']?></a></td>
					<td> <a href="account/<?php echo $account['hashid_admin']?>/admin"><?php echo $account['hashid_admin']?></a></td>
					<td><?php echo htmlspecialchars($account['title'])?></td>
					<td><?php echo htmlspecialchars($account['email'])?></td>
					</tr>
			<?php
			}
			?>
			</table>
		</div>
	</div> <!-- content -->
</body>
</html>