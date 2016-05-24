<!DOCTYPE html>

<html>
<head>
</head>
<body>

<?php
$sha_url = "";
empty($_GET['sha']) ? $sha_url = "" : $sha_url = htmlspecialchars($_GET['sha']);
$admin_mode = false;
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

//Check if admin
$admin_mode_url = false;
empty($_GET['admin']) ? $admin_mode_url = false : $admin_mode_url = true;
if($admin_mode_url)
	{//Let's check that it's not a trap
	try
	{
		$myquery_admin = 'SELECT * FROM accounts WHERE hashid_admin = :sha_url';
		$prepare_query_admin = $db->prepare($myquery);
		$prepare_query_admin->bindValue(':sha_url', $sha_url, PDO::PARAM_STR);
		$prepare_query_admin->execute();
		$reponse_admin = $prepare_query_admin->fetchAll();
	}
	catch (Exception $e)
	{
		echo 'Échec lors de la connexion : ' . $e->getMessage();
	}
	$n_res_admin = count($reponse_admin);
	if($n_res_admin > 0)
	{
		$admin_mode = true;
	}
}

// on fait une boucle qui va faire un tour pour chaque enregistrement 
if($n_res > 0)//$reponse->fetch()) 
{
	// on affiche les informations de l'enregistrement en cours 
	echo '<h1>Account found</h1>';
	echo '<ul>';
	echo '<li>Title of account : '.$reponse[0]['title'].'</li>';
	echo '<li>Email address   : '.$reponse[0]['email'].'</li>';
	echo '</ul>';
}else{
	echo '<h1>WRONG ACCOUNT</h1>';
}	

//If admin mode
if($admin_mode)
{
echo '	<h1>Administration section</h1>';	
}
$prepare_query->closeCursor();
if($admin_mode_url)
{
	$prepare_query_admin->closeCursor();
}
?>
<h1>Menu</h1>
<ul>
<li><a href='/DivideTheBill'>Main Menu</a></li>
<li><a href='/DivideTheBill/create.php'>Create a new account</a></li>
</ul>

</body>
</html>