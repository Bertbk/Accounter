<?php 
/**
 * This software is governed by the CeCILL-B license. If a copy of this license
 * is not distributed with this file, you can obtain one at
 * http://www.cecill.info/licences/Licence_CeCILL-B_V1-en.txt
 *
 * Author of Accounter: Bertrand THIERRY (bertrand.thierry1@gmail.com)
 *
 */
 ?>
 <div class="button_account_title">
	<button type="submit" class="btn btn-danger dropdown-toggle" 
		data-toggle="dropdown" title="Delete...">
		<span class="glyphicon glyphicon-trash"></span>
		<span class="sr-only">Delete...</span>
		<span class="caret"></span>
	</button>
	<ul class="dropdown-menu" role="menu">
		<li>
			<form method="post" action="<?php echo ACTIONPATH.'/spreadsheets/receipts/remove_rcpt_payers.php'?>">
				<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>">
				<input type="hidden" name="p_hashid_spreadsheet" value="<?php echo $spreadsheet['hashid']?>">
				<input type="hidden" name="p_anchor" value="<?php echo '#spreadsheet-'.$cpt_spreadsheet?>">
				<button type="submit" class="btn btn-link confirmation" 
					name="submit_remove_all_rcpt_payers" onclick="event.stopPropagation();">
					Remove all payers
				</button>
			</form>
		</li>
		<li>
			<form method="post" action="<?php echo ACTIONPATH.'/spreadsheets/receipts/remove_rcpt_articles.php'?>">
				<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>">
				<input type="hidden" name="p_hashid_spreadsheet" value="<?php echo $spreadsheet['hashid']?>">
				<input type="hidden" name="p_anchor" value="<?php echo '#spreadsheet-'.$cpt_spreadsheet?>">
				<button type="submit" class="btn btn-link confirmation" 
					name="submit_remove_all_rcpt_articles" onclick="event.stopPropagation();">
					Remove all articles
				</button>
			</form>
		</li>
		<li>
			<form method="post" action="<?php echo ACTIONPATH.'/spreadsheets/receipts/remove_rcpt_recipients.php'?>">
				<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>">
				<input type="hidden" name="p_hashid_spreadsheet" value="<?php echo $spreadsheet['hashid']?>">
				<input type="hidden" name="p_anchor" value="<?php echo '#spreadsheet-'.$cpt_spreadsheet?>">
				<button type="submit" class="btn btn-link confirmation" 
					name="submit_remove_all_rcpt_recipients" onclick="event.stopPropagation();">
					Remove all recipients
				</button>
			</form>
		</li>
		<li class="li_margin_top">
			<form method="post" action="<?php echo ACTIONPATH.'/spreadsheets/delete_spreadsheet.php'?>">
					<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>">
					<input type="hidden" name="p_hashid_spreadsheet" value="<?php echo $spreadsheet['hashid']?>">
					<button type="submit" class="btn btn-link confirmation" 
						name="submit_delete_spreadsheet" onclick="event.stopPropagation();">
						Delete the spreadsheet
					</button>
			</form>
		</li>
	</ul>
</div>
<div class="button_spreadsheet_title">
	<form action="<?php echo $link_tmp?>">
			<button type="submit" value="" class="btn btn-default" 
				title="Edit spreadsheet" onclick="event.stopPropagation();">
					<span class="glyphicon glyphicon-pencil"></span>
			</button>
	</form>
</div>