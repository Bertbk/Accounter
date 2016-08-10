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
Check the data before asking the SQL to delete every members of an account
 */

require_once __DIR__.'/../../inc/init.php';

require_once(LIBPATH.'/accounts/get_account_admin.php');
require_once(LIBPATH.'/accounts/delete_account.php');

require_once(LIBPATH.'/members/get_members.php');
require_once(LIBPATH.'/members/delete_member.php');

require_once(LIBPATH.'/hashid/validate_hashid.php');

require_once __DIR__.'/../init_action.php';

if(isset($_POST['submit_remove_all_members']))
{
	$ErrorEmptyMessage = array(
		'p_hashid_account' => 'No acount provided'
   );
	 
	$ErrorMessage = array(
		'p_hashid_account' => 'Account not valid'
   );

	$members = get_members($account['id']);
	//Delete the members
	foreach($members as $parti)
	{
		$success = delete_member($account['id'], $parti['id']);	
		if($success === true)
			{	array_push($successArray, 'Member has been successfully deleted');}
		else
			{array_push($errArray, 'Server error: Problem while attempting to delete a member: '.$success); 	}
	}
}
		
require_once __DIR__.'/../end_action.php';
