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
Panel to add a receipt
 */
 ?>
 
<?php
//Admin only
if($admin_mode && $edit_mode == false)
{
?>
<form method="post"
	id="form_add_receipt"
	action="<?php echo ACTIONPATH.'/new_receipt.php'?>">
	<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>" >
	<input type="hidden" name="p_cpt_receipt" value="<?php echo $n_receipts?>" >
</form>

<div id="add_receipt">
	<div class="panel panel-primary">
		<div class="panel-heading cursorpointer" data-toggle="collapse" data-target="#form_add_receipt_panel_body">
			<h2>Add a receipt</h2>
			<button class="btn btn-default floatright" title="Collapse/Expand"
				data-toggle="collapse" data-target="#form_add_receipt_panel_body">
				<span class="glyphicon glyphicon-plus"></span>
			</button>
		</div>
		<div class="panel-body panel-collapse collapse in" id="form_add_receipt_panel_body">
			<fieldset>
				<p><em>Fields with asterisk <span class="glyphicon glyphicon-asterisk red"></span> are required</em></p>
				<div class="form-group">
					<label for="form_set_receipt_name">Title<span class="glyphicon glyphicon-asterisk red"></span></label>
					<input type="text" name="p_title_of_receipt" 
					id="form_set_receipt_name" class="form-control" required 
					placeholder="Title" title="Title" form="form_add_receipt">
				</div>
				<div class="form-group">
					<label for="form_set_receipt_description">Description</label>
					 <textarea name="p_description" id="form_set_receipt_description" class="form-control" 
					 placeholder="Description" title="Description" form="form_add_receipt"></textarea>
				</div>

				 <button type="submit" name="submit_new_receipt" value="Submit"
					class="btn btn-primary" title="Submit new receipt" form="form_add_receipt">
					Submit
				</button> 
			</fieldset>
		</div>
	</div>
</div>

<?php } //admin mode
?>
