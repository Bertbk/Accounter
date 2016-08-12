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
Panel to add a spreadsheet
 */
 ?>
 
<?php
//Admin only
if($admin_mode && $edit_mode == false)
{
?>
<form method="post"
	id="form_add_spreadsheet"
	action="<?php echo ACTIONPATH.'/spreadsheets/new_spreadsheet.php'?>">
	<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>" >
	<input type="hidden" name="p_cpt_spreadsheet" value="<?php echo $n_spreadsheets?>" >
</form>

<div id="add_spreadsheet">
	<div class="panel panel-primary">
		<div class="panel-heading cursorpointer overflowhidden" data-toggle="collapse" data-target="#form_add_spreadsheet_panel_body">
			<button class="btn btn-default floatright" title="Collapse/Expand"
				data-toggle="collapse" data-target="#form_add_spreadsheet_panel_body">
				<span class="glyphicon glyphicon-minus"></span>
			</button>
			<h2>Add a spreadsheet</h2>
		</div>
		<div class="panel-body panel-collapse collapse in" id="form_add_spreadsheet_panel_body">
			<fieldset>
				<p><em>Fields with asterisk <span class="glyphicon glyphicon-asterisk red"></span> are required</em></p>
				<div class="form-group">
					<label for="form_set_spreadsheet_description">Type<span class="glyphicon glyphicon-asterisk red"></span></label>
					 <select name="p_type" id="form_set_spreadsheet_type" class="form-control" 
					 placeholder="Type" title="Type" form="form_add_spreadsheet">
						<option value="budget" selected=selected>Budget (default)</option>
						<option value="receipt">Receipt</option>
					</select>
				</div>
				<div class="form-group">
					<label for="form_set_spreadsheet_name">Title<span class="glyphicon glyphicon-asterisk red"></span></label>
					<input type="text" name="p_title_of_spreadsheet" 
					id="form_set_spreadsheet_name" class="form-control" required 
					placeholder="Title" title="Title" form="form_add_spreadsheet">
				</div>
				<div class="form-group">
					<label for="form_set_spreadsheet_description">Description</label>
					 <textarea name="p_description" id="form_set_spreadsheet_description" class="form-control" 
					 placeholder="Description" title="Description" form="form_add_spreadsheet"></textarea>
				</div>

				 <button type="submit" name="submit_new_spreadsheet" value="Submit"
					class="btn btn-primary" title="Submit new spreadsheet" form="form_add_spreadsheet">
					Submit
				</button> 
			</fieldset>
		</div>
	</div>
</div>

<?php } //admin mode
?>
