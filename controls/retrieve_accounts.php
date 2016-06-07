<?php 
require_once __DIR__.'/../config-app.php';

$problem = 0;
empty($_GET['pb']) ? $problem = 0 : $problem = (int)$_GET['pb'];

include_once(ABSPATH.'/templates/retrieve_accounts.php');