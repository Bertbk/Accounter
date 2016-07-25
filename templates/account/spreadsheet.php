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
Template to display all the spreadsheets with their participants and payments
 */
 ?>
 
<!-- spreadsheetS -->
<!-- Loop on the spreadsheets -->
<?php if (is_array($my_spreadsheets) && sizeof($my_spreadsheets) > 0 )
{
$cpt_spreadsheet = -1;
foreach($my_spreadsheets as $spreadsheet)
{
	$cpt_spreadsheet ++;
	$this_type = $my_spreadsheets['type'];
	//Overlay setting
if($admin_mode 
&& $edit_mode == 'spreadsheet'
&& $edit_hashid === $spreadsheet['hashid'])
{
	$overlay="highlight";
}
else{
	$overlay = "";
}
?>

<div class="row spreadsheet <?php echo 'spreadsheet-'.$cpt_spreadsheet?> <?php echo $overlay?>" 
	id="<?php echo 'spreadsheet-'.$cpt_spreadsheet?>">
	<div class="col-xs-12">
		<div class="panel panel-primary">
			<div class="panel-heading <?php if($overlay==""){echo 'cursorpointer';}?>"
				<?php if($overlay==""){echo 'data-toggle="collapse" data-target="#panel-body_spreadsheet'.$cpt_spreadsheet.'"';}?>
				style="background-color:<?php echo '#'.$spreadsheet['color']?>">
				<div class="row">
	<?php 
//Edit the spreadsheet (name, description, ...)
if($admin_mode 
				&& $edit_mode === 'spreadsheet' 
				&& $edit_hashid === $spreadsheet['hashid'])
				{
?>
					<div class="col-xs-12" id="<?php echo 'edit_tag_'.$edit_hashid?>">
						<form method="post" id="<?php echo "form_update_spreadsheet_".$cpt_spreadsheet?>"
							action="<?php echo ACTIONPATH.'/update_spreadsheet.php'?>">
							<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>">
							<input type="hidden" name="p_hashid_spreadsheet" value="<?php echo $spreadsheet['hashid']?>">
							<input type="hidden" name="p_anchor" value="<?php echo '#spreadsheet-'.$cpt_spreadsheet?>">
							<h2>
								<label for="form_edit_spreadsheet_name">Title:</label>
								<input type="text" name="p_title_of_spreadsheet" id="form_edit_spreadsheet_name"
								class="form-control"	value="<?php echo htmlspecialchars($spreadsheet['title'])?>" required 
								title="Title">
							</h2>
						</form>
					</div>
<?php } else{
?>

					<div class="col-md-9 ">
						<h2 class="spreadsheet_title">
							<?php echo ($cpt_spreadsheet+1).'. '.htmlspecialchars($spreadsheet['title']) ?>
						</h2>	
					</div>
					<div class="col-md-3">
		<?php
					if($admin_mode && $edit_mode === false)
					{
						$link_tmp = $link_to_account_admin.'/edit/spreadsheet/'.$spreadsheet['hashid'].'#edit_tag_'.$spreadsheet['hashid'];
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
									<form method="post" action="<?php echo ACTIONPATH.'/remove_spreadsheet_participants.php'?>">
										<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>">
										<input type="hidden" name="p_hashid_spreadsheet" value="<?php echo $spreadsheet['hashid']?>">
										<input type="hidden" name="p_anchor" value="<?php echo '#spreadsheet-'.$cpt_spreadsheet?>">
										<button type="submit" class="btn btn-link confirmation" 
											name="submit_remove_all_participations" onclick="event.stopPropagation();">
											Remove all participations
										</button>
									</form>
								</li>
								<li>
									<form method="post" action="<?php echo ACTIONPATH.'/remove_payments.php'?>">
										<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>">
										<input type="hidden" name="p_hashid_spreadsheet" value="<?php echo $spreadsheet['hashid']?>">
										<input type="hidden" name="p_anchor" value="<?php echo '#spreadsheet-'.$cpt_spreadsheet?>">
										<button type="submit" class="btn btn-link confirmation" 
											name="submit_remove_all_payments" onclick="event.stopPropagation();">
											Remove all payments
										</button>
									</form>
								</li>
								<li class="li_margin_top">
									<form method="post" action="<?php echo ACTIONPATH.'/delete_spreadsheet.php'?>">
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
			<?php 
					}
					?>
						<div class="button_spreadsheet_title">
							<button type="submit" value="" class="btn btn-default" title="Collapse/Expand"
							data-toggle="collapse" data-target="#<?php echo 'panel-body_spreadsheet'.$cpt_spreadsheet?>">
								<span class="glyphicon glyphicon-plus"></span>
							</button>							
						</div>
					</div>
	<?php
		}
?>
				</div>
			</div>
<?php //PANEL BODY OF spreadsheet
$cred = hexdec(substr($spreadsheet['color'], 0, 2));
$cgreen = hexdec(substr($spreadsheet['color'], 2, 2));
$cblue = hexdec(substr($spreadsheet['color'], 4, 2));
?>
			<div id="<?php echo 'panel-body_spreadsheet'.$cpt_spreadsheet?>" class="panel-collapse collapse in">
				<div  class="panel-body"
				style="background-color: rgba(<?php echo $cred.','.$cgreen.','.$cblue?>, 0.5);">
<?php 
//Edit the spreadsheet (name, description, ...)
if($admin_mode 
				&& $edit_mode === 'spreadsheet' 
				&& $edit_hashid === $spreadsheet['hashid'])
				{
?>
					<div class="form-group">
						<label for="form_edit_spreadsheet_description">Description: </label>
						<textarea name="p_description" class="form-control"
						 form="<?php echo "form_update_spreadsheet_".$cpt_spreadsheet?>"><?php if(!empty($spreadsheet['description'])){echo htmlspecialchars($spreadsheet['description']);}?></textarea>
					 </div>
					<button type="submit" name="submit_update_spreadsheet" value="Submit"
						form="<?php echo "form_update_spreadsheet_".$cpt_spreadsheet?>"
						class="btn btn-primary" title="Submit changes">
							Submit changes
					</button> 
					<button type="submit" name="submit_cancel" value="<?php echo '#spreadsheet-'.$cpt_spreadsheet?>" 
						form="form_cancel" class="btn btn-primary" title="Cancel">
						Cancel
					</button> 
<?php	
	}	else{
	//Display only
	if(!empty($spreadsheet['description']) && !is_null($spreadsheet['description']))
	{
?>
					<h3>Description</h3>
					<p><?php echo htmlspecialchars($spreadsheet['description'])?></p>
<?php }
	}
	
	if( $this_type == "budget")
	{
		include(__DIR__.'/spreadsheet/budget.php');
	}
	else if($this_type == "receipt")
	{
		include(__DIR__.'/spreadsheet/receipt.php');
	}
?>
				</div> 
			</div> 
		</div> 
	</div> 
</div> 

<?php
}//foreach spreadsheet
}//if spreadsheets exist
?>
