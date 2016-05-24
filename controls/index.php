<?php 
include_once('/lib/get_accounts.php');
$accounts = get_accounts();

//Sanitize values
foreach($accounts as $key => $accounts_backup)
{
    $accounts[$key]['id'] = (int)$accounts_backup['id'];
    $accounts[$key]['hashid'] = htmlspecialchars($accounts_backup['hashid']);
    $accounts[$key]['hashid_admin'] = htmlspecialchars($accounts_backup['hashid_admin']);
    $accounts[$key]['title'] = htmlspecialchars($accounts_backup['title']);
    $accounts[$key]['email'] = htmlspecialchars($accounts_backup['email']);
}

include_once('/templates/index.php');
?>