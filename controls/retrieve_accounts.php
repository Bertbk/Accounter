<?php 
require_once __DIR__.'/../config-app.php';

$problem = 0;
empty($_GET['pb']) ? $problem = 0 : $problem = (int)$_GET['pb'];

$errArray = array();

if($problem == 1)
{
	array_push($errArray, 'Sorry, no account associated with this email address has been found. 
	<br> Please try again with another email address');
}
else if($problem == 2)
{
		array_push($errArray, 'Sorry, the email couldn\'t be sent');
}

include_once(ABSPATH.'/templates/retrieve_accounts.php');