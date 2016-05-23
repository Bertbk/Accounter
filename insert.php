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
	try{
	$isok = $db->exec('INSERT INTO test_sha(id, sha) VALUES(NULL, '.(int)htmlspecialchars($_POST['sha_to_sql']).')');
	}
	catch (Exception $e)
	{
			die('Erreur : ' . $e->getMessage());
	}
	$sha_to_sql = htmlspecialchars($_POST['sha_to_sql']);
	if($isok)
	{
  	 echo '<p> Ajouté dans la BDD : '.$sha_to_sql.'</p>';
	}
	 else
	 {
  	 echo '<p>'.$sha_to_sql.' is already in the db</p>';		 
	 }
		 
}
?>

</body>
</html>