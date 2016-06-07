<?php

//Delete install files
unlink('create_tables.php');
unlink('create_config_file.php');
unlink('install.php');
unlink('test_db.php');

header('location: ../index.php');