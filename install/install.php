<?php 
include_once(__DIR__.'/create_config_file.php');
create_config_file('localhost', 'root', '', 'testdb', 'cpt_', 'http://localhost/test');
include_once(__DIR__.'/create_tables.php');
//include_once(__DIR__.'/create_tables.php');

?>