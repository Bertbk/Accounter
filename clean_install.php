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
Remove install directory
*/
if(is_dir(__DIR__.'/install'))
{
	//Delete install files
	unlink(__DIR__.'/install/create_tables.php');
	unlink(__DIR__.'/install/create_config_file.php');
	unlink(__DIR__.'/install/create_admin_htaccess.php');
	unlink(__DIR__.'/install/install.php');
	unlink(__DIR__.'/install/test_db.php');
	unlink(__DIR__.'/install/index.html');
	unlink(__DIR__.'/install/clean.php');
	rmdir (__DIR__.'/install');
}
header('location: index.php');
exit;