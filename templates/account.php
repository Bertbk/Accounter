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
Template of a particular account page, that is :
- Partipants
- Bills
- Solution(s)
...

 */
 ?>
 
<!DOCTYPE html>

<html>
<head>
<title>Account</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="<?php echo BASEURL.'/bootstrap/css/bootstrap.min.css'?>" rel="stylesheet">
<!--<link rel="stylesheet" type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/smoothness/jquery-ui.css" media="all">-->
<link rel="stylesheet" type="text/css" href="<?php echo BASEURL.'/jquery/jquery-ui.css'?>" media="all">
<link rel="stylesheet" type="text/css" href="<?php echo BASEURL.'/css/spinner.css'?>" media="all">

<!-- Selectpicker: Latest compiled and minified CSS -->
<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css">-->
<link rel="stylesheet" href="<?php echo BASEURL.'/bootstrap/css/bootstrap-select.min.css'?>">

<link rel="stylesheet" type="text/css" href="<?php echo BASEURL.'/css/global.css'?>">
<link rel="stylesheet" type="text/css" href="<?php echo BASEURL.'/css/participant.css'?>">
<link rel="stylesheet" type="text/css" href="<?php echo BASEURL.'/css/bill.css'?>">
<link rel="stylesheet" type="text/css" href="<?php echo BASEURL.'/css/bill_participant.css'?>">
<link rel="stylesheet" type="text/css" href="<?php echo BASEURL.'/css/payment.css'?>">
<link rel="stylesheet" type="text/css" href="<?php echo BASEURL.'/css/account.css'?>">
<link rel="stylesheet" type="text/css" href="<?php echo BASEURL.'/css/solution.css'?>">
<link rel="stylesheet" type="text/css" href="<?php echo BASEURL.'/css/receipt.css'?>">
<link rel="stylesheet" type="text/css" href="<?php echo BASEURL.'/css/receipt_payer.css'?>">
<link rel="stylesheet" type="text/css" href="<?php echo BASEURL.'/css/receipt_receiver.css'?>">

<script type="text/javascript" src="<?php echo BASEURL.'/js/account.js'?>"></script>

<!--<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>-->
<script type="text/javascript" src="<?php echo BASEURL.'/jquery/jquery.min.js'?>"></script>

<!--<script src="https://code.jquery.com/jquery-migrate-1.2.1.js"></script>-->
<script src="<?php echo BASEURL.'/jquery/jquery-migrate-1.2.1.js'?>"></script>

<!--<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>-->
<script type="text/javascript" src="<?php echo BASEURL.'/jquery/jquery-ui.min.js'?>"></script>

<!--<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>-->
<script src="<?php echo BASEURL.'/bootstrap/js/bootstrap.min.js'?>"></script>
<!-- Selectpicker:  Latest compiled and minified JavaScript -->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>-->
<script src="<?php echo BASEURL.'/bootstrap/js/bootstrap-select.min.js'?>"></script>


<script type="text/javascript" src="<?php echo BASEURL.'/js/jquery_accounter.jquery'?>"></script>

</head>
<body>
	<div id="content">
		<div class="container">		
			<?php
			if($admin_mode == true
			&&$edit_mode !== false){
			?>
			<div id="overlay"></div>
			<?php } ?>
			
			<div class="row">
				<header>
					<?php include(__DIR__.'/header/header.php'); ?>
				</header>
			</div>
			<?php include(__DIR__.'/messages/messages.php');?>
			<?php include(__DIR__.'/account/cancel_form.php');?>
			<?php include(__DIR__.'/account/description_panel.php');?>
			<div class="row">
				<?php //include(__DIR__.'/account/solution.php');?>
			</div>
			<div class="row">
				<div class="col-md-3">
					<?php include(__DIR__.'/account/members.php');?>
					<?php include(__DIR__.'/account/add_spreadsheet_panel.php');?>
				</div>
				<div class="col-md-9">
					<?php //include(__DIR__.'/account/spreadsheet.php');?>
				</div>
			</div>
		</div>
	</div> <!-- content -->
</body>
</html>