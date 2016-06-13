<?php

function filter_valid_email($email_arg)
{
	$email = filter_var($email_arg, FILTER_SANITIZE_EMAIL);
	return filter_var($email, FILTER_VALIDATE_EMAIL);
}