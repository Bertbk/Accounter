<!DOCTYPE html>

<html>

<body>


<?php 
// on se connecte à MySQL 
try
{
$db = new PDO('mysql:host=localhost;dbname=dividethebill;charset=utf8', 'root', '');
}
catch (Exception $e)
{
        die('Erreur : ' . $e->getMessage());
}

if(isset($_POST['submit_sha']))
{
	$input_int = filter_input(INPUT_POST, 'sha_to_sql', FILTER_VALIDATE_INT);
	if(!$input_int)
	{
	 echo '<p> SHA not valide</p>';		 
	}
	else
	{
		try{
		$isok = $db->exec('INSERT INTO test_sha(id, sha) VALUES(NULL, '.$input_int.')');
		}
		catch (Exception $e)
		{
				die('Erreur : ' . $e->getMessage());
		}
		if($isok)
		{
		 echo '<p> Ajouté dans la BDD : '.$input_int.'</p>';
		}
		 else
		 {
		 echo '<p>'.$input_int.' is already in the db</p>';		 
		 }
	}
}
?>

</body>
</html>