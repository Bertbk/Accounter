<?php
include_once(__DIR__.'/get_hashid_size.php');

function validate_hashid($hashid_arg)
{
	$hashid = htmlspecialchars($hashid_arg);
	$my_size = (int)get_hashid_size();
	
	return preg_match("^[a-z0-9]{".$my_size."}$", $hashid);
}

function validate_hashid_admin($hashid_arg)
{
	$hashid = htmlspecialchars($hashid_arg);
	$my_size = 2*(int)get_hashid_size();
	
	return preg_match("^[a-z0-9]{".$my_size."}$", $hashid);
}