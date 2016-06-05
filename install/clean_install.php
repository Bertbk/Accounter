<?php

//Delete install files
unlink('create_tables.php');
unlink('create_config_file.php');
unlink('install.php');

header('location: ../index.php');