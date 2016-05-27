<?php 
function get_db()
{
	// Connexion to the database
	try
	{
	$db = new PDO('mysql:host=localhost; dbname=accounter; charset=utf8', 'root', '');
	}
	catch (Exception $e)
	{
			die('Fail to connect : ' . $e->getMessage());
	}
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $db;
}
?>