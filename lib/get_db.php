<?php
require_once __DIR__.'/../config-app.php';

function get_db()
{
	$configs = include(ABSPATH.'/config.php');
	// Connexion to the database
	try
	{
	
	$query_db = 'mysql:host='.$configs['host'].'; dbname='.$configs['dbname'].'; charset=utf8';
	$db = new PDO($query_db, $configs['username'], $configs['password']);
	}
	catch (Exception $e)
	{
			die('Fail to connect : ' . $e->getMessage());
	}
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $db;
}
