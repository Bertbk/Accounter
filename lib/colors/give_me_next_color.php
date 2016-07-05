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

function give_me_next_color($prev_color_arg, $type_arg)
{
	$prev_color = $prev_color_arg;
	//Type is Bill or Participant
	$type_color = $type_arg;
		
	$colorArray = get_colorArray($type_color);
	
	$next_color = $colorArray[0]; //default color
	
	if($prev_color != "")
	{
		$FoundIt = false;
		foreach($colorArray as $col)
		{
			if($FoundIt)
			{
				$next_color = $col;
				break;
			}
			if($col == $prev_color)
			{
				$FoundIt = true;
			}
		}
	}
	return $next_color;
}