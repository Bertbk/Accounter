<!DOCTYPE html>

<html>
<head>

</head>
<body>

<?php
$sha_url = 0;
empty($_GET['sha']) ? $sha_url = 0 : $sha_url = htmlspecialchars($_GET['sha']);
$sha_url_b36 = base_convert($sha_url, 10, 36);
echo '<p>SHA FROM URL= '.$sha_url.' and '.$sha_url_b36.'</p>'
?>

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

echo '<h1>SHA = GOOD ? </h1>';


try
{
	$reponse = $db->query('SELECT * FROM accounts WHERE hashid='.$sha_url_b36);
}
catch (Exception $e)
{
    echo 'Échec lors de la connexion : ' . $e->getMessage();
}
// on fait une boucle qui va faire un tour pour chaque enregistrement 
if($data = $reponse->fetch()) 
    { 
    // on affiche les informations de l'enregistrement en cours 
    echo '<p>Welcome brother</p>';
    } 
	else
	{
    echo '<p>Go back home dude</p>';
	}	
$reponse->closeCursor();
?>
<h1>Next...</h1>
<p><a href='create.php'>Create a new account</a></p>


</body>
</html>