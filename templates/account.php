<!DOCTYPE html>

<html>
<head>
<title>Account</title>
<link rel="stylesheet" type="text/css" href="<?php echo BASEURL.'/css/global.css'?>">
<link rel="stylesheet" type="text/css" href="<?php echo BASEURL.'/css/participant.css'?>">
<link rel="stylesheet" type="text/css" href="<?php echo BASEURL.'/css/bill.css'?>">
<link rel="stylesheet" type="text/css" href="<?php echo BASEURL.'/css/bill_participant.css'?>">
<link rel="stylesheet" type="text/css" href="<?php echo BASEURL.'/css/account.css'?>">

<script type="text/javascript" src="<?php echo BASEURL.'/js/account.js'?>"></script>
<link rel="stylesheet" type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/smoothness/jquery-ui.css" media="all">
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo BASEURL.'/js/hide_show_add_participant.jquery'?>"></script>

</head>


<body>

<div id="content">
<header>
<?php include(__DIR__.'/header/header.php'); ?>
</header>

<?php include(__DIR__.'/account/admin_panel.php');?>

<?php include(__DIR__.'/account/solution.php');?>

<?php include(__DIR__.'/account/participant.php');?>

<?php include(__DIR__.'/account/bill.php');?>



</div> <!-- content -->
</body>
</html>