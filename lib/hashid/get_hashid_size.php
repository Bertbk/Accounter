<?php
function get_openssl_size()
{
	return 8;
}

function get_hashid_size()
{
	return (get_openssl_size()*2);
}