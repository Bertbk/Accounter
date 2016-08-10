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
Redirect to the admin page of an account.
Used to cancel current editing.
 */

require_once __DIR__.'/../inc/init.php';

require_once(LIBPATH.'/accounts/get_account_admin.php');
require_once(LIBPATH.'/hashid/validate_hashid.php');

require_once __DIR__.'/init_action.php';


if(isset($_POST['submit_cancel']))
{
	$anchor = htmlspecialchars($_POST['submit_cancel']);
	$redirect_link = $redirect_link.$anchor;
}
		
require_once(__DIR__.'/end_action.php');