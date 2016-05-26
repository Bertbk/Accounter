<?php
include_once('/lib/get_db.php');
include_once('/lib/get_contributor_by_id.php');


function update_contributor($account_id_arg, $contributor_id_arg, $name_of_contrib_arg, $nb_of_parts_arg)
{
	$db = get_db();

	$account_id = (int)$account_id_arg;
	$contributor_id = (int)$contributor_id_arg;
	$new_name_of_contrib = htmlspecialchars($name_of_contrib_arg);
	$new_nb_of_parts = (int)$nb_of_parts_arg;

	$contrib = get_contributor_by_id($account_id, $contributor_id);
	if(empty($contrib))
	{
		return false;
	}
	
	//Nothing to change?
	if($new_name_of_contrib === $contrib['name'] && $new_nb_of_parts = $contrib['number_of_parts'])
	{
		return true;
	}

	
	//If the name changes, we have to check if it's free
	if($new_name_of_contrib != $contrib['name'])
	{
		$isthenamefree = get_contributor_by_name($account_id, $new_name_of_contrib);
		if(!empty($isthenamefree))
		{
?>
<script type="text/javascript">
  alert('Contributor with the same name already reccorded!');
</script>
<?php
			return false;
		}
	}
	
	try
	{
		$myquery = 'UPDATE contributors 
		SET name=:new_name_of_contrib, number_of_parts=:new_nb_of_parts
		WHERE id=:contributor_id';
		$prepare_query = $db->prepare($myquery);
		$prepare_query->bindValue(':new_name_of_contrib', $new_name_of_contrib, PDO::PARAM_STR);
		$prepare_query->bindValue(':new_nb_of_parts', $new_nb_of_parts, PDO::PARAM_INT);
		$prepare_query->bindValue(':contributor_id', $contributor_id, PDO::PARAM_INT);
		$isgood = $prepare_query->execute();
		$prepare_query->closeCursor();
	}
	catch (Exception $e)
	{
		echo 'Fail to connect: ' . $e->getMessage();
	}
	return $isgood;
}
?>