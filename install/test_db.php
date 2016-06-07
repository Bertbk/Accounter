<?php

function test_db($host_arg, $username_arg, $password_arg, $dbname_arg)
{
	$host = htmlspecialchars($host_arg);
	$username = htmlspecialchars($username_arg);
	$password = htmlspecialchars($password_arg);
	$password = is_null($password)?'':$password;
	$dbname = htmlspecialchars($dbname_arg);
	
	try
	{
	$query_db = 'mysql:host='.$host.'; dbname='.$dbname.'; charset=utf8';
	$db = new PDO($query_db, $username, $password);
	}
	catch (Exception $e)
	{
			//die('Fail to connect : ' . $e->getMessage());
			return false;
	}
	return true;
}