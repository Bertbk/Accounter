<!DOCTYPE html>

<html>
<head>
</head>
<body>

<?php
$sha_url = "";
empty($_GET['sha']) ? $sha_url = "" : $sha_url = htmlspecialchars($_GET['sha']);
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

echo '<h1>SHA = GOOD ? </h1>';


try
{
	$myquery = 'SELECT * FROM accounts WHERE hashid = :sha_url OR hashid_admin = :sha_url';
	$prepare_query = $db->prepare($myquery);
	$prepare_query->bindValue(':sha_url', $sha_url, PDO::PARAM_STR);
	$prepare_query->execute();
	$reponse = $prepare_query->fetchAll();
	$n_res = count($reponse);
}
catch (Exception $e)
{
    echo 'Échec lors de la connexion : ' . $e->getMessage();
}
// on fait une boucle qui va faire un tour pour chaque enregistrement 
if($n_res > 0)//$reponse->fetch()) 
{
	// on affiche les informations de l'enregistrement en cours 
	echo '<p>Welcome brother</p>';
}else{
    echo '<p>Go back home dude</p>';
}	
$prepare_query->closeCursor();
?>
<h1>Next...</h1>
<p><a href='../create.php'>Create a new account</a></p>


</body>
</html>