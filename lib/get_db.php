<?php 
function get_db()
{
	// Connexion to the database
	try
	{
	$db = new PDO('mysql:host=localhost; dbname=dividethebill; charset=utf8', 'root', '');
	}
	catch (Exception $e)
	{
			die('Fail to connect : ' . $e->getMessage());
	}
	return $db;
}
?>