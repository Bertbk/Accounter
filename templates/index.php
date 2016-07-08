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
Template of the home page
 */
 ?>
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


			<div class="row form-group">
				<div class="col-xs-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h2>Welcome to accounter: your best friend to share the bills!</h2>
						</div>
						<div class="panel-body">
							<h3>Why?</h3>
								<p>You just had a wonderful and relaxing week-end with your friends or your family. But it's time to do the account.</p>
								You remember that the house rental was advanced by a couple of friends while two other dudes have advanced money for the nice restaurant you had on saturday. And of course, there is Uncle Bob that borrow money from everyone with the hidden hope they will forget it&hellip; Holly cows, right?</p>
							<h3>Solution?</h3>
								<p>There is a solution: Accounter! It's a simple and web-responsive (= mobile friendly!) application to manage the bills! It's time for Uncle Bob to pay his duty! </p>
							<h3>Cool features</h3>
								<ul>
									<li>Free and Open-Source.</li>
									<li>Two access: public (read only) and adminitrator (modification possible).</li>
									<li>Multiple bills per account (eg: restaurant, car rental, activity...)</li>
									<li>Adjustable participation : Alice staid 7 nights but Bob only 2? No problem, Accounter will manage it</li>
									<li>Optimized solution: limit the number of money transfer</li>
									<li>Payment beween participants: Charly advanced 50$ to Bob ? No problem.</li>
								</ul>
						</div>
					</div>
				</div>
			</div>

			<nav id="main_menu">				
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
		</div>
	</div> <!-- content -->
</body>
</html>