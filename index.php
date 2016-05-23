<!DOCTYPE html>

<html>
<head>
<script>
function validate() {
    var x;
    x = document.getElementById("sha_to_sql_value").value;
    if (x == "") {
        alert("Enter a Valid SHA number");
        return false;
    };

	if (isNaN(parseInt(x)) || !isFinite(x)) {
        alert("Enter a Valid SHA number");
        return false;
    };
}
</script>
</head>
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

echo '<h1>SHA = GOOD ? </h1>';


try
{
	$reponse = $db->query('SELECT * FROM test_sha WHERE sha='.$sha_url);
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

echo '<h1>Table</h1>';

$reponse = $db->query('SELECT * FROM test_sha');
// on fait une boucle qui va faire un tour pour chaque enregistrement 
echo '<table style="width:100%" border="1" >';
echo ' <tr>';
echo '<td> ID </td>';
echo '<td> SHA </td> ';
echo '  </tr>';
while($data = $reponse->fetch()) 
    { 
	echo ' <tr>';
    echo '<td>'.$data['id'].'</td>';
    echo '<td>'.$data['sha'].'</td> ';
	echo '  </tr>';
	}
echo'</table>';

?> 

<h1> Add To DB</h1>

<form method="post" action="insert.php" onsubmit="return validate()">
    <input type="text" name="sha_to_sql" id="sha_to_sql_value" required />
    <input type="submit" name="submit_sha" value="submit" />
</form>


</body>
</html>