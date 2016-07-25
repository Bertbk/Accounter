<?php
/**
 * This software is governed by the CeCILL-B license. If a copy of this license
 * is not distributed with this file, you can obtain one at
 * http://www.cecill.info/licences/Licence_CeCILL-B_V1-en.txt
 *
 * Author of Accounter: Bertrand THIERRY (bertrand.thierry1@gmail.com)
 *
 */
 
/*
Return the next color of member. 
It just loop on a hard-coded $colorArray for the color that is next to $prev_color_arg.
This is used to find the next "free" color of a member or a spreadsheet.
The $type_arg can be either "member" of "spreadsheet"
(see get_colorArray.php)
*/
include_once(__DIR__.'/get_colorArray.php');

function give_me_next_color($entity_array_arg, $type_arg)
{
	$entity_array = $entity_array_arg;
	$type_of_entity = $type_arg;

	if($type_of_entity !== 'member'
	&& $type_of_entity !== 'spreadsheet')
	{return '444444';}
	
	$colorArray = get_colorArray($type_of_entity);
	
	if(empty($entity_array))
	{return $colorArray[0];}
	
	//Count the number of time a color is used
	$countColorArray = Array();
	foreach($colorArray as $key => $col)
	{
		$countColorArray[$col] = 0;
	}
	
	foreach($entity_array_arg as $entity)
	{
		$countColorArray[$entity['color']] ++;
	}
	
	//Find the first less used color
	reset($countColorArray);
	$next_color = key($countColorArray);
	$min_use = $countColorArray[$next_color];
	foreach($countColorArray as $key => $n_time_used)
	{
		if($n_time_used < $min_use)
		{
			$min_use = $n_time_used;
			$next_color = $key;
		}
	}
	
	return $next_color;
}