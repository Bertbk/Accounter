<?php

include_once(__DIR__.'/get_colorArray.php');

function give_me_next_color($prev_color_arg, $type_arg)
{
	$prev_color = htmlspecialchars($prev_color_arg);
	//Type is Bill or Participant
	$type_color = htmlspecialchars($type_arg);
		
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