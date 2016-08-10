<?php
/**
 * This software is governed by the CeCILL-B license. If a copy of this license
 * is not distributed with this file, you can obtain one at
 * http://www.cecill.info/licences/Licence_CeCILL-B_V1-en.txt
 *
 * Author of Accounter: Bertrand THIERRY (bertrand.thierry1@gmail.com)
 *
*/

if (session_id() == '') {
    session_start();
}

require_once(__DIR__.'/constants.php');

if (ini_get('date.timezone') == '') {
    date_default_timezone_set('Europe/Paris');
}

//Localization
define('DEFAULT_LANGUAGE', 'eng');

$ALLOWED_LANGUAGES=[
'fr'=>'Français',
'eng' => 'English',
];

require_once(__DIR__.'/i18n.php');
