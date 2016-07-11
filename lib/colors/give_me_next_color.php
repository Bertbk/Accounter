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
Return the next color of participant. 
It just loop on a hard-coded $colorArray for the color that is next to $prev_color_arg.
This is used to find the next "free" color of a participant or a bill.
The $type_arg can be either "participant" of "bill"
(see get_colorArray.php)
*/
include_once(__DIR__.'/get_colorArray.php');

function give_me_next_color($entity_array_arg, $type_arg)
{
	$participant_array = $participant_array_arg;
	$type_of_entity = $type_arg;

	if($type_of_entity !== 'participant'
	&& $type_of_entity !== 'bill')
	{return '000000';}
	
	$colorArray = get_colorArray($type_of_entity);
	
	if(empty($entity_array_arg))
	{return $colorArray[0];}
	
	//Count the number of time a color is used
	$countColorArray = Array();
	foreach($colorArray as $key => $col)
	{
		$countColorArray[$key] = 0;
	}
	
	foreach($entity_array_arg as $entity)
	{
		$countColorArray[$entity['color']] ++;
	}
	
	//Find the first less used color
	reset($countColorArray);
	$key_in_color_array = key($array);
	$min_use = $countColorArray[$key_in_color_array];
	foreach($countColorArray as $key => $n_time_used)
	{
		if($n_time_used < $min_use)
		{
			$min_use = $n_time_used;
			$key_in_color_array = $key;
		}
	}
	
	$next_color = $colorArray[$key_in_color_array];
	return $next_color;
}