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
Hidden form to cancel any editing action in the account page and come back to account admin page.
*/

if($admin_mode
&& $edit_mode !== false)
{
	?>
	<form method="post" 
		action="<?php echo ACTIONPATH.'/cancel.php'?>"
		id="form_cancel">
	<input type="hidden" value="<?php echo $my_account['hashid_admin']?>"
		name="p_hashid_account">
	</form>
<?php 
}
?>