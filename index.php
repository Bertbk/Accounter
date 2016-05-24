<!DOCTYPE html>

<html>
<head>

</head>
<body>

<h1>Table of the accounts</h1>

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
	$myquery = 'SELECT * FROM accounts';
	$prepare_query = $db->prepare($myquery);
	$prepare_query->execute();
	$reponse = $prepare_query->fetchAll();
	$n_res = count($reponse);
}
catch (Exception $e)
{
    echo 'Échec lors de la connexion : ' . $e->getMessage();
}
// on fait une boucle qui va faire un tour pour chaque enregistrement 
echo '<table style="width:100%" border="1" >';
echo ' <tr>';
echo '<td> ID </td>';
echo '<td> hashid </td> ';
echo '<td> hashid_admin </td>';
echo '<td> Title </td> ';
echo '<td> Email </td> ';
echo '  </tr>';
foreach ($reponse as $account)
{
	echo ' <tr>';
    echo '<td>'.$account['id'].'</td>';
    echo '<td> <a href=\'account/'.$account['hashid'].'\'>'.$account['hashid'].'</a></td> ';
    echo '<td> <a href=\'account/'.$account['hashid_admin'].'/admin\'>'.$account['hashid_admin'].'</a></td> ';
    echo '<td>'.$account['title'].'</td> ';
    echo '<td>'.$account['email'].'</td> ';
	echo '  </tr>';
}	
echo'</table>';

$prepare_query->closeCursor();
?>
<h1>Create an account</h1>
<ul>
<li><a href='/DivideTheBill/create.php'>Create a new account</a></li>
</ul>

</body>
</html>