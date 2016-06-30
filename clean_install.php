<?php

if(is_dir(__DIR__.'/install'))
{
	//Delete install files
	unlink(__DIR__.'/install/create_tables.php');
	unlink(__DIR__.'/install/create_config_file.php');
	unlink(__DIR__.'/install/install.php');
	unlink(__DIR__.'/install/test_db.php');
	unlink(__DIR__.'/install/index.html');
	unlink(__DIR__.'/install/clean.php');
	rmdir (__DIR__.'/install');
}
header('location: index.php');
exit;