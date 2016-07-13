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
Template to display all the receipts with their participants and articles
 */
 ?>
 
<!-- RECEIPTS -->
<!-- Loop on the receipts -->
<?php if (is_array($my_receipts) && sizeof($my_receipts) > 0 )
{
$cpt_receipt = -1;
foreach($my_receipts as $receipt)
{
	$cpt_receipt ++;
	$this_receipt_payers = array();
	$this_free_receipt_payers = array();
	if(!empty($my_receipt_participants[$receipt['id']]))
	{$this_receipt_payers = $my_receipt_participants[$receipt['id']];}
	if(!empty($my_free_receipt_payers[$receipt['id']]))
	{	$this_free_receipt_payers = $my_free_receipt_payers[$receipt['id']];}
?>

<?php //Overlay setting
if($admin_mode 
&& $edit_mode == 'receipt'
&& $edit_hashid === $receipt['hashid'])
{
	$overlay="highlight";
}
else{
	$overlay = "";
}
?>

<div class="row receipt <?php echo 'receipt-'.$cpt_receipt?> <?php echo $overlay?>" 
	id="<?php echo 'receipt-'.$cpt_receipt?>">
	<div class="col-xs-12">
		<div class="panel panel-primary">
			<div class="panel-heading <?php if($overlay==""){echo 'cursorpointer';}?>"
				<?php if($overlay==""){echo 'data-toggle="collapse" data-target="#panel-body_receipt'.$cpt_receipt.'"';}?>
				style="background-color:<?php echo '#'.$receipt['color']?>">
				<div class="row">
	<?php 
//Edit the Receipt (name, description, ...)
if($admin_mode 
				&& $edit_mode === 'receipt' 
				&& $edit_hashid === $receipt['hashid'])
				{
?>
					<div class="col-xs-12" id="<?php echo 'edit_tag_'.$edit_hashid?>">
						<form method="post" id="<?php echo "form_update_receipt_".$cpt_receipt?>"
							action="<?php echo ACTIONPATH.'/update_receipt.php'?>">
							<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>">
							<input type="hidden" name="p_hashid_receipt" value="<?php echo $receipt['hashid']?>">
							<input type="hidden" name="p_anchor" value="<?php echo '#receipt-'.$cpt_receipt?>">
							<h2>
								<label for="form_edit_receipt_name">Title:</label>
								<input type="text" name="p_title_of_receipt" id="form_edit_receipt_name"
								class="form-control"	value="<?php echo htmlspecialchars($receipt['title'])?>" required 
								title="Title">
							</h2>
						</form>
					</div>
<?php } else{
?>

					<div class="col-md-9 ">
						<h2 class="receipt_title">
							<?php echo ($cpt_receipt+1).'. '.htmlspecialchars($receipt['title']) ?>
						</h2>	
					</div>
					<div class="col-md-3">
		<?php
					if($admin_mode && $edit_mode === false)
					{
						$link_tmp = $link_to_account_admin.'/edit/receipt/'.$receipt['hashid'].'#edit_tag_'.$receipt['hashid'];
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
									<form method="post" action="<?php echo ACTIONPATH.'/remove_receipt_payers.php'?>">
										<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>">
										<input type="hidden" name="p_hashid_receipt" value="<?php echo $receipt['hashid']?>">
										<input type="hidden" name="p_anchor" value="<?php echo '#receipt-'.$cpt_receipt?>">
										<button type="submit" class="btn btn-link confirmation" 
											name="submit_remove_all_payers" onclick="event.stopPropagation();">
											Remove all payers
										</button>
									</form>
								</li>
								<li>
									<form method="post" action="<?php echo ACTIONPATH.'/remove_receipt_articles.php'?>">
										<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>">
										<input type="hidden" name="p_hashid_receipt" value="<?php echo $receipt['hashid']?>">
										<input type="hidden" name="p_anchor" value="<?php echo '#receipt-'.$cpt_receipt?>">
										<button type="submit" class="btn btn-link confirmation" 
											name="submit_remove_all_articles" onclick="event.stopPropagation();">
											Remove all articles
										</button>
									</form>
								</li>
								<li class="li_margin_top">
									<form method="post" action="<?php echo ACTIONPATH.'/delete_receipt.php'?>">
											<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>">
											<input type="hidden" name="p_hashid_receipt" value="<?php echo $receipt['hashid']?>">
											<button type="submit" class="btn btn-link confirmation" 
												name="submit_delete_receipt" onclick="event.stopPropagation();">
												Delete the receipt
											</button>
									</form>
								</li>
							</ul>
						</div>
						<div class="button_receipt_title">
							<form action="<?php echo $link_tmp?>">
									<button type="submit" value="" class="btn btn-default" 
										title="Edit receipt" onclick="event.stopPropagation();">
											<span class="glyphicon glyphicon-pencil"></span>
									</button>
							</form>
						</div>
			<?php 
					}
					?>
						<div class="button_receipt_title">
							<button type="submit" value="" class="btn btn-default" title="Collapse/Expand"
							data-toggle="collapse" data-target="#<?php echo 'panel-body_receipt'.$cpt_receipt?>">
								<span class="glyphicon glyphicon-plus"></span>
							</button>							
						</div>
					</div>
	<?php
		}
?>
				</div>
			</div>
<?php //PANEL BODY OF RECEIPT
$cred = hexdec(substr($receipt['color'], 0, 2));
$cgreen = hexdec(substr($receipt['color'], 2, 2));
$cblue = hexdec(substr($receipt['color'], 4, 2));
?>
			<div id="<?php echo 'panel-body_receipt'.$cpt_receipt?>" class="panel-collapse collapse in">
				<div  class="panel-body"
				style="background-color: rgba(<?php echo $cred.','.$cgreen.','.$cblue?>, 0.5);">
<?php 
//Edit the Receipt (name, description, ...)
if($admin_mode 
				&& $edit_mode === 'receipt' 
				&& $edit_hashid === $receipt['hashid'])
				{
?>
					<div class="form-group">
						<label for="form_edit_receipt_description">Description: </label>
						<textarea name="p_description" class="form-control"
						 form="<?php echo "form_update_receipt_".$cpt_receipt?>"><?php if(!empty($receipt['description'])){echo htmlspecialchars($receipt['description']);}?></textarea>
					 </div>
					<button type="submit" name="submit_update_receipt" value="Submit"
						form="<?php echo "form_update_receipt_".$cpt_receipt?>"
						class="btn btn-primary" title="Submit changes">
							Submit changes
					</button> 
					<button type="submit" name="submit_cancel" value="<?php echo '#receipt-'.$cpt_receipt?>" 
						form="form_cancel" class="btn btn-primary" title="Cancel">
						Cancel
					</button> 
<?php	
	}	else{
	//Display only
	if(!empty($receipt['description']) && !is_null($receipt['description']))
	{
?>
					<h3>Description</h3>
					<p><?php echo htmlspecialchars($receipt['description'])?></p>
<?php }
	}?>

	<?php // PAYERS ?>
					<h3 id="<?php echo 'receipt_participants_'.$cpt_receipt?>">Payers</h3>
	<?php // Display the current participant of this receipt
	if(!empty($this_receipt_payers))
	{
?>
					<div class="row">		
<?php
	$participation_to_edit = false; // if editing, place a button after the list
	$cpt_receipt_participant = -1;
	foreach($this_receipt_payers as $key => $receipt_participant)
	{
		$cpt_receipt_participant++;
		if($admin_mode === true
			&& $edit_mode === 'receipt_participant' 
			&& $edit_hashid === $receipt_participant['hashid'])
		{
			//We found the receipt_participant to be edited. Will be displayed after the other.
			$participation_to_edit = $key;
			continue;
		}
		?>
					<div class="col-xs-12 col-sm-6 col-lg-4 receipt_participant">
						<div class="floatleft width60 padding_receipt_participant display_receipt_participant" style="background-color:<?php echo '#'.$receipt_participant['color']?>">
							<?php
								echo htmlspecialchars($receipt_participant['name']).' ('.(float)$receipt_participant['percent_of_usage'].'%)';
							?>
						</div>
						<?php
							if($admin_mode === true
							&& $edit_mode === false){
								$link_tmp = $link_to_account_admin.'/edit/receipt_participant/'.$receipt_participant['hashid'].'#edit_tag_'.$receipt_participant['hashid'];
								?>
						<div class="zeromargin floatleft">
									<form action="<?php echo $link_tmp?>">
										<button type="submit" value="" class="btn btn-default" title="Edit participation">
												<span class="glyphicon glyphicon-pencil"></span>
										</button>
									</form>
						</div>
						<div class="receipt_participant_button">
							<form method="post" 
							class="deleteicon"
							action="<?php echo ACTIONPATH.'/delete_receipt_participant.php'?>">		
								<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>">
								<input type="hidden" name="p_hashid_receipt_participant" value="<?php echo $receipt_participant['hashid']?>">
								<input type="hidden" name="p_anchor" value="<?php echo '#receipt-'.$cpt_receipt?>">
								<button type="submit" class="btn btn-default confirmation" 
									name="submit_delete_receipt_participant" title="Delete participation">
									<span class="glyphicon glyphicon-trash"></span>
								</button>
							</form>
						</div>
				<?php	} ?>
					</div>
			<?php
	}//foreach participant in this receipt
	?>
				</div> <?php //row ?>
	<?php
	
	if($participation_to_edit !== false)
	{
		$receipt_participant_tmp = $this_receipt_payers[$participation_to_edit];
	//Edit activated on a receipt_participant of THIS receipt :
	?>
				<div class="highlight"  id="<?php echo 'edit_tag_'.$edit_hashid?>"
				style="background-color: rgba(<?php echo $cred.','.$cgreen.','.$cblue?>, 0.5);">
					<h3>Edit participation of <?php echo htmlspecialchars($receipt_participant_tmp['name']);?></h3>
					<form method="post" action="<?php echo ACTIONPATH.'/update_receipt_participant.php'?>">

						<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>">
						<input type="hidden" name="p_hashid_receipt_participant" value="<?php echo $receipt_participant_tmp['hashid']?>">
						<input type="hidden" name="p_anchor" value="<?php echo '#receipt-'.$cpt_receipt?>">

						<div class="row form-group row-no-padding">
							<div class="col-xs-6 col-sm-5 col-md-4">
								<div class="fullwidth padding_receipt_participant display_receipt_participant" style="background-color:<?php echo '#'.$receipt_participant_tmp['color']?>">
									<?php echo htmlspecialchars($receipt_participant_tmp['name']);?>
								</div>
							</div>
							<div class="col-xs-6 col-sm-5 col-md-4">
								<div class="input-group">
									<input type="number" step="0.01" min="0" max="100" name="p_percent_of_use"
										class="form-control" value="<?php echo (float)$receipt_participant_tmp['percent_of_usage']?>" required>
									<span class="input-group-addon">%</span>
								</div>
							</div>
						</div>
						<button type="submit" name="submit_update_receipt_participant" 
							value="Submit" class="btn btn-primary" title="Submit changes">
							Submit changes
						</button> 
						<button type="submit" name="submit_cancel" value="<?php echo '#receipt-'.$cpt_receipt?>" 
							form="form_cancel" class="btn btn-primary" title="Cancel">
						 Cancel
						</button>
					</form>
				</div>
<?php	
//reset temporary variables
$participation_to_edit=false;
$receipt_participant_tmp=null;
	}
	?>
<?php }//if my_receipt_participants != empty ?>

	<?php
if($admin_mode && !$edit_mode)
{ //Display possibilities
	//Assign a participant (if there are free guys)
	if(!empty($this_free_receipt_payers))
	{
	?>
					<form method="post"	enctype="multipart/form-data"
						action="<?php echo ACTIONPATH.'/new_receipt_participant.php'?>">
						<fieldset>
							<legend id="<?php echo 'show_hide_receipt_add_payer_'.$cpt_receipt?>"
								class="cursorpointer">
								(+) Add a payer
							</legend>
							<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>">
							<input type="hidden" name="p_hashid_receipt" value="<?php echo $receipt['hashid']?>">
							<input type="hidden" name="p_anchor" value="<?php echo '#receipt-'.$cpt_receipt?>">
							<div class="hidden_at_first"
							id=<?php echo 'show_hide_receipt_add_payer_'.$cpt_receipt.'_target'?>>

<?php
			$cpt = -1;
			foreach($this_free_receipt_payers as $participant)
			{
				$cpt++;
		?>
								<div class="row form-group assign_receipt_participant">
									<div class="col-xs-12 col-md-6 col-lg-4 ">
										<div>
											<input type="checkbox" name="p_participant['<?php echo $cpt?>'][p_hashid_participant]" 
												value="<?php echo $participant['hashid']?>" title="Participant"
												id="<?php echo'assign_participant_'.$cpt_receipt.'_'.$cpt?>" >
											<div class="[ btn-group ] fullwidth" style="overflow:hidden">
												<label for="<?php echo 'assign_participant_'.$cpt_receipt.'_'.$cpt?>"
													class="[ btn btn-default ] btn-assign_receipt_participant">
													<span class="[ glyphicon glyphicon-ok ]"></span>
													<span> </span>
												</label>
												<span class="span-assign_receipt_participant" >
													<label for="<?php echo 'assign_participant_'.$cpt_receipt.'_'.$cpt?>" 
														class="[ btn btn-default active ] btn-assign_receipt_participant2"
														style="background-color:<?php echo '#'.$participant['color']?>">
															<?php echo htmlspecialchars($participant['name'])?>
													</label>
												</span>
											</div>
										</div>
									</div>
									<div class="col-xs-12 col-md-6 col-lg-4">
										<label for="<?php echo 'form_available_percent_'.$cpt_receipt.'_'.$participant['id']?>" 
											class="sr-only">
											Percentage of use
										</label>
										<div class="input-group">
											<input name="p_participant['<?php echo $cpt?>'][p_percent_of_use]" type="number"
														class="form-control" step="0.01" min="0" max="100"	value="100" 
														id="<?php echo 'form_available_percent_'.$cpt_receipt.'_'.(int)$participant['id']?>"
														title="Percentage of usage">
											<span class="input-group-addon">%</span>
										</div>
									</div>
								</div>
		<?php
				}//for each participant
		?>
								<div class="row form-group assign_receipt_participant">
									<div class="col-xs-6 col-md-4 col-lg-3 ">
										<div>
											<input type="checkbox" name="" 
												id="<?php echo'form_select_all_participation_'.$cpt_receipt?>"
												onchange="SelectAllParticipation(this, '<?php echo $cpt_receipt?>')">
											<div class="[ btn-group ] fullwidth" style="overflow:hidden">
												<label for="<?php echo 'form_select_all_participation_'.$cpt_receipt?>"
													class="[ btn btn-default ] btn-assign_receipt_participant">
													<span class="[ glyphicon glyphicon-ok ]"></span>
													<span> </span>
												</label>
												<span class="span-assign_receipt_participant" >
													<label for="<?php echo 'form_select_all_participation_'.$cpt_receipt?>" 
														class="[ btn btn-default active ] btn-select_all_participation">
															Select all
													</label>
												</span>
											</div>
										</div>
									</div>
									<div class="col-xs-12 col-md-6 col-lg-5 ">
										<div class="input-group">
											<span class="input-group-addon btn btn-default"
											onclick="SetAllPercent('<?php echo 'form_set_all_percent_'.$cpt_receipt?>', '<?php echo $cpt_receipt?>')">Set to all</span>
											<input name="" type="number"
												class="form-control"
												step="0.01" min="0" max="100"	value="100" 
												title="Percentage of usage"
												id="<?php echo 'form_set_all_percent_'.$cpt_receipt?>">
											<span class="input-group-addon">%</span>
										</div>
									</div>
								</div>
								<div class="row form-group">
									<div class="col-xs-12">
										<button type="submit" name="submit_new_receipt_participant" 
											value="Submit" class="btn btn-primary" title="Submit new participation">
											Submit
										</button>
									</div>
								</div>
							</div>
						</fieldset>
					</form>
<?php
		} //if empty free_participants
	}//if admin
?>

					<h3>Articles</h3>

<?php // List of the articles
	if(isset($my_articles_per_receipt[$receipt['id']]) && is_array($my_articles_per_receipt[$receipt['id']])
		&& count($my_articles_per_receipt[$receipt['id']]) > 0)
	{
		$this_article = $my_articles_per_receipt[$receipt['id']];
		$cpt_paymt = -1;
	?>
	
					<div class="row text-center">
						<div class="col-xs-4 col-md-2">
							<strong>Payer</strong>
						</div>
						<div class="col-xs-4 col-md-2">
							<strong>Amount</strong>
						</div>
						<div class="col-xs-4 col-md-2">
							<strong>Receiver</strong>
						</div>
						<div class="hidden-xs hidden-sm hidden-md col-lg-2">
							<strong>Designation</strong>
						</div>
						<div class="hidden-xs hidden-sm hidden-md col-lg-2">
							<strong>Date</strong>
						</div>
				<?php if($admin_mode && !$edit_mode){?>
						<div class="hidden-xs hidden-sm col-xs-1">
							<strong>Edit</strong>
						</div>
				<?php }if($admin_mode && !$edit_mode){?>
						<div class="hidden-xs hidden-sm col-xs-1">
							<strong>Delete</strong>
						</div>
				<?php }?>
					</div>
	
	<?php
	
$article_to_edit = false; // if editing, place the article after the other
foreach($this_article as $article)
{
	$cpt_paymt++;
		if($admin_mode && $edit_mode === 'article' 
		&& $article['hashid'] === $edit_hashid)
		{ 
			$article_to_edit = $article;
			continue;
		}
?>
					<div class="row article_table">
						<div class="col-xs-5 col-md-2">
							<div class="fullwidth display_receipt_participant padding_receipt_participant" style="background-color:<?php echo '#'.$article['payer_color']?>">
							<?php echo htmlspecialchars($article['payer_name'])?>
							</div>
						</div>
						<div class="col-xs-2 col-md-2">
							 <?php echo (float)$article['cost']?>&euro;
						</div>
						<div class="col-xs-5 col-md-2">
							<?php if(is_null($article['receiver_name'])) {?>
							<div class="padding_receipt_participant display_receipt_participant group_color">
								Group
							</div>
							<?php }else{ ?>
							<div class="fullwidth display_receipt_participant padding_receipt_participant" style="background-color:<?php echo '#'.$article['receiver_color']?>">			
								<?php echo htmlspecialchars($article['receiver_name'])?>
							</div>
							<?php }?>
						</div>
						<div class="hidden-xs hidden-sm hidden-md col-lg-2 <?php echo 'description_collapse_'.$cpt_receipt.'_'.$cpt_paymt?>">
							<?php if(!empty($article['description']))
							{
									echo htmlspecialchars($article['description']);
							}
							?>
						</div>
						<div class="hidden-xs hidden-sm hidden-md col-lg-2 <?php echo 'description_collapse_'.$cpt_receipt.'_'.$cpt_paymt?>">
							<?php
							if(!empty($article['date_of_article']))
							{
								echo date("d/m/Y", strtotime($article['date_of_article']));
							}?>
						</div>
						<?php //Collapse button (for mobile>) ?>
						<div class="visible-xs visible-sm col-xs-2">
							<button type="submit" class="btn btn-default" title="Collapse/Expand"
								data-toggle="collapse" data-target=".<?php echo 'description_collapse_'.$cpt_receipt.'_'.$cpt_paymt?>">
								<span class="glyphicon glyphicon-plus"></span>
							</button>
						</div>
		
	<?php //EDIT BUTTON
			if($admin_mode && !$edit_mode)
				{
	?>
						<div class="col-xs-2 col-md-1">
		<?php 
		$link_tmp = $link_to_account_admin.'/edit/article/'.$article['hashid'].'#edit_tag_'.$article['hashid'];
		?>
							<form action="<?php echo $link_tmp ?>">
								<button type="submit" class="btn btn-default" title="Edit article">
									<span class="glyphicon glyphicon-pencil"></span>
								</button>
							</form>
						</div>
						<div class="col-xs-2 col-md-1">
							<form method="post" 
								class="deleteicon"
								action="<?php echo ACTIONPATH.'/delete_article.php'?>"
									>
									<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>">
									<input type="hidden" name="p_hashid_article" value="<?php echo $article['hashid']?>">
									<input type="hidden" name="p_anchor" value="<?php echo '#receipt-'.$cpt_receipt?>">
									<button type="submit" class="btn btn-default confirmation" 
										name="submit_delete_article" title="Delete article">
										<span class="glyphicon glyphicon-trash"></span>
									</button>
								</form>
						</div>
		<?php
			}//end if admin + non edit 
			?>
					</div>
<?php	}//foreach current article 

//Display article to edit (if exists)
if($article_to_edit !== false)
{
?>
					<div class="highlight" id="<?php echo 'edit_tag_'.$edit_hashid?>"
					style="background-color: rgba(<?php echo $cred.','.$cgreen.','.$cblue?>, 0.5);">
						<h3>Edit article</h3>
						<form method="post" id="form_edit_article_send"
							action="<?php echo ACTIONPATH.'/update_article.php'?>">
							<input type="hidden" name="p_hashid_account" value="<?php echo $my_account['hashid_admin']?>">
							<input type="hidden" name="p_hashid_article" value="<?php echo $article_to_edit['hashid']?>">
							<input type="hidden" name="p_anchor" value="<?php echo '#receipt-'.$cpt_receipt?>">
							
							<div class="row form-group">
								<div class="col-xs-12">
									<label for="form_edit_article_receipt_<?php echo $cpt_receipt?>">
										Move to another receipt
									</label>
									<div class="input-group">
										<select name="p_hashid_receipt" id="form_edit_article_receipt_<?php echo $cpt_receipt?>"
											onchange="CreatePossiblePayersLists(this, document.getElementById('form_edit_article_payer_<?php echo $cpt_receipt?>'),	
											<?php echo htmlspecialchars(json_encode($list_of_possible_payers, 3))?>)"
											class="form-control selectpicker"> 
								<?php //list of receipts
										foreach($my_receipts as $sub_receipt)
											{
								?>
												<option value="<?php echo $sub_receipt['hashid']?>"
												<?php if($sub_receipt['id']==$article_to_edit['receipt_id']){echo ' selected';}?>
												><?php echo htmlspecialchars($sub_receipt['title'])?></option>
								<?php
											}
								?>
										</select>
										<span class="input-group-addon glyphicon glyphicon-list"></span>
									</div>
								</div>
							</div>
							<div class="row form-group">
								<div class="col-xs-12 col-lg-4">
									<label for="form_edit_article_payer_<?php echo $cpt_receipt?>">
										Payer
									</label>
									<div class="input-group">
										<select name="p_hashid_payer" 
											onchange="DropDownListsBetweenParticipants(this, document.getElementById('form_edit_article_recv_<?php echo $receipt['id']?>'))"
											id="form_edit_article_payer_<?php echo $cpt_receipt?>" class="form-control selectpicker">
								<?php
											foreach($this_receipt_payers as $receipt_participant)
											{
								?>
												<option value="<?php echo $receipt_participant['hashid']?>"
												<?php if($receipt_participant['id']==$article_to_edit['payer_id']){echo ' selected';}?>>
												<?php echo htmlspecialchars($receipt_participant['name'])?></option>
								<?php
											}
								?>
										</select>
										<span class="input-group-addon glyphicon glyphicon-user"></span>
									</div>
								</div>
								<div class="col-xs-12 col-lg-4">
									<label for="form_edit_article_cost_<?php echo $cpt_receipt?>">
										Amount
									</label>
									<div class="input-group">
										<input type="number" step="0.01" min="0" name="p_cost" 
											class="form-control"
											id="form_edit_article_cost_<?php echo $cpt_receipt?>"
											value="<?php echo (float)$article_to_edit['cost']?>" required>
										<span class="input-group-addon glyphicon glyphicon-euro"></span>
									</div>
								</div>
								<div class="col-xs-12 col-lg-4">
									<label for="form_edit_article_recv_<?php echo $cpt_receipt?>">
										Receiver
									</label>
									<div class="input-group">
										<select name="p_hashid_recv" 
											id="form_edit_article_recv_<?php echo $cpt_receipt?>"
											class="form-control selectpicker">
											<option value="-1" >Group</option>
									<?php
											foreach($this_receipt_payers as $receipt_participant)
												{
													if($receipt_participant['id'] == $article_to_edit['payer_id']){continue;}
									?>
													<option value="<?php echo $receipt_participant['hashid']?>"
													<?php if($receipt_participant['id']==$article_to_edit['receiver_id']){echo ' selected';}?>>
													<?php echo htmlspecialchars($receipt_participant['name'])?></option>
									<?php
												}
									?>
										</select>
										<span class="input-group-addon glyphicon glyphicon-user"></span>
									</div>
								</div>
							</div>
							<div class="row form-group">
								<div class="col-xs-12 col-lg-6">
									<label for="form_edit_article_desc_<?php echo $receipt['id']?>">
										Description
									</label>
									<div class="input-group">
										<input type="text" name="p_description" class="form-control"
											id="form_edit_article_desc_<?php echo $receipt['id']?>"
											value="<?php echo htmlspecialchars($article_to_edit['description'])?>"
											placeholder="Description">
										<span class="input-group-addon glyphicon glyphicon-tag"></span>
									</div>
								</div>
								<?php
									$tmp_date_parsed = date_parse($article_to_edit['date_of_article']);
									if ($tmp_date_parsed == false 
									|| !checkdate($tmp_date_parsed['month'], $tmp_date_parsed['day'], $tmp_date_parsed['year'])) 
									{
										$tmp_date_parsed = null;
									}else{
										$tmp_date_parsed=$tmp_date_parsed['day'].'/'.$tmp_date_parsed['month'].'/'.$tmp_date_parsed['year'];
									}
								?>
								<div class="col-xs-12 col-lg-6">
									<label for="form_edit_article_date_<?php echo $receipt['id']?>">
										Date of article (dd/mm/yyyy)
									</label>
									<div class="input-group">
										<input type="text" name="p_date_of_article" 
											class="form-control date_zindex"
											id="form_edit_article_date_<?php echo $receipt['id']?>"
											value="<?php echo $tmp_date_parsed?>">
										<span class="input-group-addon glyphicon glyphicon-calendar"></span>
									</div>
								</div>
								<?php $tmp_date_parsed = null;?>
							</div>
							<div>
								<button type="submit" name="submit_update_article" value="Submit" 
									class="btn btn-primary" title="Update article">
									Submit changes
								</button>
								<button type="submit" name="submit_cancel" 
									value="<?php echo '#receipt-'.$cpt_receipt?>" class="btn btn-primary"
									form="form_cancel" title="Cancel">
									Cancel
								</button>
							</div>
						</form>
					</div>
<?php
$article_to_edit = false;
} //edit this article
?>


<?php
}//if article exist
else
{ 
?>
	<p>No articles recorded.</p>
	<?php
	}//end else article exists
 // PAYMENTS
	if($admin_mode && !$edit_mode)
	{?>
	<!-- Add article -->
	<?php
		if(!empty($my_receipt_participants[$receipt['id']]))
		{
?>
					<form method="post" action="<?php echo ACTIONPATH.'/new_article.php'?>"
						role="form">
						<fieldset>								
							<legend id="<?php echo 'show_hide_receipt_add_article_'.$cpt_receipt?>"
								class="cursorpointer">
								(+) Add a article
							</legend>
							<div  class="hidden_at_first"	id="<?php echo 'show_hide_receipt_add_article_'.$cpt_receipt.'_target'?>">
								<div id="<?php echo 'div_option_add_article_'.$cpt_receipt?>">
									<p><em>Fields with asterisk <span class="glyphicon glyphicon-asterisk red"></span> are required</em></p>
									<input type="hidden" name="p_hashid_account" value ="<?php echo $my_account['hashid_admin']?>">
									<input type="hidden" name="p_hashid_receipt" value ="<?php echo $receipt['hashid']?>">
									<input type="hidden" name="p_anchor" value="<?php echo '#receipt-'.$cpt_receipt?>">
									<div id="div_set_article_<?php echo $cpt_receipt?>">
										<div class="row form-group">
											<div class="col-xs-12 col-lg-4">
												<label for="<?php echo 'form_set_article_payer_'.$cpt_receipt?>_0">Payer<span class="glyphicon glyphicon-asterisk red"></span></label>
												<div class="input-group">
													<select name="p_article[0][p_hashid_payer]" 
														id="form_set_article_payer_<?php echo $cpt_receipt?>_0" 
														class="form-control selectpicker" title="Payer"> 
															<option disabled selected value="null" data-hidden="true">
																Choose a payer
															</option>
														<?php
															foreach($this_receipt_payers as $receipt_participant)
															{ ?>
																<option value="<?php echo $receipt_participant['hashid']?>"><?php echo htmlspecialchars($receipt_participant['name'])?></option>
											<?php	} ?>
													</select>
													<span class="input-group-addon glyphicon glyphicon-user"></span>
												</div>
											</div>

											<div class="col-xs-12 col-lg-4">
												<label for="<?php echo 'form_set_article_cost_'.$cpt_receipt?>_0">
													Amount<span class="glyphicon glyphicon-asterisk red"></span>
												</label>
												<div class="input-group">
													<input type="number" step="0.01" min="0" placeholder="Amount" name="p_article[0][p_cost]" 
														id="<?php echo 'form_set_article_cost_'.$cpt_receipt?>_0" required 
														class="form-control" title="Amount">
													<span class="input-group-addon glyphicon glyphicon-euro"></span>
												</div>
											</div>
											<div class="col-xs-12 col-lg-4">
												<label for="<?php echo 'form_set_article_type_'.$cpt_receipt?>_0">
													Receiver(s)<span class="glyphicon glyphicon-asterisk red"></span>
												</label>
												<div class="input-group">
													<select name="p_article[0][p_type]" id="<?php echo 'form_set_article_type_'.$cpt_receipt?>_0"	
														class="form-control selectpicker" title="Group or specific article?"
														onchange="DisableEnableElement(this, document.getElementById('<?php echo 'form_set_article_recv_'.$cpt_receipt?>_0'))"> 
														<option value="-1" selected="selected">Entire Group</option>
														<option value="1">Specific</option>
													</select>
													<select name="p_article[0][p_hashid_recv][]" id="<?php echo 'form_set_article_recv_'.$cpt_receipt?>_0"	
														class="form-control selectpicker" title="Receiver" multiple="multiple" disabled="disabled"> 
														<?php
															foreach($this_receipt_payers as $receipt_participant)
															{ ?>
																<option value="<?php echo $receipt_participant['hashid']?>">
																	<?php echo htmlspecialchars($receipt_participant['name'])?>
																</option>
												<?php	} ?>
													</select>
													<span class="input-group-addon glyphicon glyphicon-user"></span>
												</div>
											</div>
										</div>
										<div class="row form-group">
											<div class="col-xs-12 col-lg-6">
												<label for="<?php echo 'form_set_article_desc_'.$cpt_receipt?>_0">
													Description
												</label>
												<div class="input-group">
													<input type="text" name="p_article[0][p_description]" 
														id="<?php echo 'form_set_article_desc_'.$cpt_receipt?>_0" 
														class="form-control" placeholder="Description" title="Description">
													<span class="input-group-addon glyphicon glyphicon-tag"></span>
												</div>
											</div>
											<div class="col-xs-12 col-lg-6">
												<label for="<?php echo 'form_set_article_date_'.$cpt_receipt?>_0">
													Date of article (dd/mm/yyyy)
												</label>
												<div class="input-group">
													<input type="text" name="p_article[0][p_date_of_article]" 
														id="<?php echo 'form_set_article_date_'.$cpt_receipt?>_0" 
														class="form-control" title="Date of article">
													<span class="input-group-addon glyphicon glyphicon-calendar"></span>
												</div>
											</div>
										</div>
									</div>
								</div>
	<?php
		$name_of_people = array_column($this_receipt_payers, 'name');
		$hashid_of_people = array_column($this_receipt_payers, 'hashid');
	?>
								
								<div>
									<button type="submit" name="submit_new_article" value="Submit" 
										title="Submit new article" class="btn btn-primary">
										Submit
									</button>
									<button type="button" name="add_row"
										title="Add a row" class="btn btn-primary" 
										onclick="AddArticleLine(<?php echo htmlspecialchars(json_encode($name_of_people)) ?>, 
										<?php echo htmlspecialchars(json_encode($hashid_of_people)) ?>,
										<?php echo $cpt_receipt?>);return false;">
										Add a row
									</button>
								</div>
							</div>
						</fieldset>
					</form>
<?php
		}//if receipt_participants not empty (ie: article possible)
?>
	<?php
	} //if for displaying possibilities
?>

				</div> 
			</div> 
		</div> 
	</div> 
</div> 

<?php
}//foreach receipt
}//if receipts exist
?>
