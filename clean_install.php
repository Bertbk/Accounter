<?php

//Delete install files
unlink('install/create_tables.php');
unlink('install/create_config_file.php');
unlink('install/install.php');
unlink('install/test_db.php');
unlink('install/index.html');
rmdir ('install');
header('location: index.php');