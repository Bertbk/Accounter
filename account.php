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
	$account_id = $reponse[0]['id'];
	// on affiche les informations de l'enregistrement en cours 
	echo '<h1>Account found</h1>';
	echo '<ul>';
	echo '<li>Title of account : '.$reponse[0]['title'].'</li>';
	echo '<li>Email address   : '.$reponse[0]['email'].'</li>';
	echo '</ul>';
	
	try
	{
		$myquery_contrib = 'SELECT * FROM contributor WHERE account_id = :account_id';
		$prepare_query_contrib = $db->prepare($myquery_contrib);
		$prepare_query_contrib->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$prepare_query_contrib->execute();
		$reponse_contrib = $prepare_query_contrib->fetchAll();
		$prepare_query_contrib->closeCursor();

	}
	catch (Exception $e)
	{
		echo 'Échec lors de la connexion : ' . $e->getMessage();
	}
	echo '<h1>Contributors</h1>';
	echo '<ul>';
		foreach($reponse_contrib as $contrib)
		{
			echo '<li>Name of contributor : '.$contrib['Name'].'</li>';
			echo '<li>Number of parts     : '.$contrib['Number_of_part'].'</li>';
		}
	echo '</ul>';	
}else{
	echo '<h1>WRONG ACCOUNT</h1>';
}	

//If admin mode
if($admin_mode)
{
	?>
<h1>Administration section</h1>;

<form method="post">
  <fieldset>
    <legend>Add a contributor:</legend>
    Name: <input type="text" name="name_of_contributor" id="name_of_contributor" required /><br>
    Nb. of parts: <input type="number" name="number_of_parts" id="number_of_parts" value="1" required /><br>
	 <button type="submit" name="submit" value="Submit">Submit</button> 
  </fieldset>
</form>


<?php
if(isset($_POST['submit']))
{
	$name_of_contrib = filter_input(INPUT_POST, 'name_of_contributor', FILTER_SANITIZE_STRING);
	$nb_of_parts = filter_input(INPUT_POST, 'number_of_parts', FILTER_SANITIZE_NUMBER_INT);
	
	// on se connecte à MySQL 
	try
	{
	$db = new PDO('mysql:host=localhost;dbname=dividethebill;charset=utf8', 'root', '');
	}
	catch (Exception $e)
	{
			die('Erreur : ' . $e->getMessage());
	}

	
	try{
		$myquery_add_contrib = 'INSERT INTO contributor(id, account_id, Name, Number_of_part) VALUES(NULL, :account_id, :Name, :Number_of_part)';
		$prepare_query_add_contrib = $db->prepare($myquery_add_contrib);
		$prepare_query_add_contrib->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$prepare_query_add_contrib->bindValue(':Name', $name_of_contrib, PDO::PARAM_STR);
		$prepare_query_add_contrib->bindValue(':Number_of_part', $nb_of_parts, PDO::PARAM_INT);
		$isgood = $prepare_query_add_contrib->execute();
		$prepare_query_add_contrib->closeCursor();
	}
	catch (Exception $e)
	{
			die('Erreur : ' . $e->getMessage());
	}
	if($isgood)
	{
		header("location:list.php");
	}
}
	
?>
<?php
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