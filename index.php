<!DOCTYPE html>

<html>

<body>

<?php
$sha_url = 0;
empty($_GET['sha']) ? $sha_url = 0 : $sha_url = (int)$_GET['sha'];
echo '<p>SHA FROM URL= '.$sha_url.'</p>'
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

try
{
	$reponse = $db->query('SELECT * FROM test_sha WHERE sha='.$sha_url);
}
catch (Exception $e)
{
    echo 'Échec lors de la connexion : ' . $e->getMessage();
}
// on fait une boucle qui va faire un tour pour chaque enregistrement 
while($data = $reponse->fetch()) 
    { 
    // on affiche les informations de l'enregistrement en cours 
    echo '<p>'.$data['sha'].'</p>';
    } 

$reponse->closeCursor();
?> 

</body>
</html>