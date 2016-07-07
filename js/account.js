/**
 * This software is governed by the CeCILL-B license. If a copy of this license
 * is not distributed with this file, you can obtain one at
 * http://www.cecill.info/licences/Licence_CeCILL-B_V1-en.txt
 *
 * Author of Accounter: Bertrand THIERRY (bertrand.thierry1@gmail.com)
 *
*/

//Menu for a payment
function createOptionDropDown(ddl, text, value) {
	var opt = document.createElement('option');
	opt.value = value;
	opt.text = text;
	ddl.options.add(opt);
}

/*
When submitting/editing a payment, a payer cannot also be the receiver.
So the list of possible receiver must be adapted with respect to the selected payer.
*/
function DropDownListsBetweenParticipants(payer, receiver) {
	var choice1 = payer.value;
	receiver.length= 0;
	createOptionDropDown(receiver, 'Group', -1);
	for (i = 0; i < payer.options.length; i++) {
		if(payer.options[i].value != "" 
			&& payer.value != payer.options[i].value 
			&& payer.options[i].value != 'null')
		{
			createOptionDropDown(receiver, payer.options[i].text, payer.options[i].value);
		}
	}
  $('.selectpicker').selectpicker('refresh');
}


/*
When editing a payment, the bill can be changed. In that case, the list of possible participants
must be adapted in consequence.
billListElement: element in DOM selecting the bills
payerListElement : Select element in DOM
billParticipants : Array of the available bill_participants
*/
function CreatePossiblePayersLists(billListElement, payerListElement, billParticipants) 
{
	var list_bill_part = billParticipants;
	payerListElement.length = 0;
	var bill_choice = billListElement.value;
	//Loop on every possible payer
	for (var bill_hashids in list_bill_part) {
  if (list_bill_part.hasOwnProperty(bill_hashids)) {
		if(bill_choice == bill_hashids)
		{
			for (var cpt in list_bill_part[bill_hashids]) {
				var part_name = list_bill_part[bill_hashids][cpt]['part_name'];
				var part_hashid = list_bill_part[bill_hashids][cpt]['part_hashid'];
				createOptionDropDown(payerListElement, part_name, part_hashid );
			}
		}
  }
}
//	payerListElement.selected=originalPayerHashid;

}

//Add a row for the form to submit a payment (multiple submit) 
function RemoveChild(parent_elem, child_elem){
	parent_elem.removeChild(child_elem);
	return false;
}

//Add a row for the form to submit a payment (multiple submit) 
function AddPaymentLine(name_of_people, hashid_of_people, cpt_bill)
{
	if(typeof AddPaymentLine.counter == 'undefined')
	{
		AddPaymentLine.counter = 1;
	}
	
	if(name_of_people.length != hashid_of_people.length)
	{return false;}

	var div_container = document.createElement("div");
	div_container.id= "container_row_payment_"+ cpt_bill + "_" + AddPaymentLine.counter;
	div_container.className = "new_row_payment";
	var div_row1=document.createElement("div");
	div_row1.setAttribute("class", "row form-group");
	var div_row2=document.createElement("div");
	div_row2.setAttribute("class", "row form-group");
	
	var div_payer=document.createElement("div");
	div_payer.setAttribute("class",	"col-xs-12 col-lg-4");
	var div_amount=document.createElement("div");
	div_amount.setAttribute("class", "col-xs-12 col-lg-4");
	var div_receiver=document.createElement("div");
	div_receiver.setAttribute("class", "col-xs-12 col-lg-4");
	var div_description=document.createElement("div");
	div_description.setAttribute("class", "col-xs-12 col-lg-6");
	var div_date=document.createElement("div");
	div_date.setAttribute("class", "col-xs-12 col-lg-6");
	
	var select_payer = document.createElement("select");
	select_payer.id = "form_set_payment_payer_"+ cpt_bill + "_" + AddPaymentLine.counter;
	select_payer.name = "p_payment["+ AddPaymentLine.counter +"][p_hashid_payer]";
	select_payer.className = "form-control selectpicker";
	select_payer.title = "Payer";
	var input_cost = document.createElement("input");
	input_cost.id = "form_set_payment_cost_"+ cpt_bill + "_" + AddPaymentLine.counter;
	input_cost.type="number";
	input_cost.min="0";
	input_cost.step="0.01";
	input_cost.className = "form-control";
	input_cost.name = "p_payment["+ AddPaymentLine.counter +"][p_cost]";
	input_cost.title = "Amount";
	input_cost.placeholder = "Amount";
//	input_cost.setAttribute("number_type", "float");
	var select_type = document.createElement("select");
	select_type.id = "form_set_payment_type_"+ cpt_bill + "_" + AddPaymentLine.counter;
	select_type.name = "p_payment["+ AddPaymentLine.counter +"][p_type]";
	select_type.className = "form-control selectpicker";
	select_type.title = "Group or specific payment?";
	var select_receiver = document.createElement("select");
	select_receiver.id = "form_set_payment_recv_"+ cpt_bill + "_" + AddPaymentLine.counter;
	select_receiver.name = "p_payment["+ AddPaymentLine.counter +"][p_hashid_recv][]";
	select_receiver.className = "form-control selectpicker";
	select_receiver.title = "Receiver";
	select_receiver.disabled = "true";
	select_receiver.multiple = "true";
	var input_description = document.createElement("input");
	input_description.id = "form_set_payment_desc_"+ cpt_bill + "_" + AddPaymentLine.counter;
	input_description.type="text";
	input_description.name = "p_payment["+ AddPaymentLine.counter +"][p_description]";
	input_description.className = "form-control";
	input_description.placeholder = "Description";
	input_description.title = "Description";
	var input_date = document.createElement("input");
	input_date.id = "form_set_payment_date_"+ cpt_bill + "_" + AddPaymentLine.counter;
	input_date.type="date";
	input_date.name = "p_payment["+ AddPaymentLine.counter +"][p_date_of_payment]";
	input_date.className = "form-control";
	input_date.placeholder = "Date of payment";
	input_date.title = "Date of payment";
 $(input_date).datepicker({ dateFormat: 'dd/mm/yy'});

	//Construct the list of payer
	var opt_null = document.createElement("option");
	opt_null.selected=true;
	opt_null.disabled=true;
	opt_null.value = null;
	opt_null.text = "Choose a payer";
	opt_null.setAttribute("data-hidden", "true");
	select_payer.appendChild(opt_null);
	for (var i =0; i < name_of_people.length; i ++)
	{
		var opt = document.createElement("option");
		opt.value = hashid_of_people[i];
		opt.text = name_of_people[i];
		select_payer.appendChild(opt);

		var opt_recv = document.createElement("option");
		opt_recv.value = hashid_of_people[i];
		opt_recv.text = name_of_people[i];
		select_receiver.appendChild(opt_recv);
	}

	var opt_group = document.createElement("option");
	opt_group.selected=true;
	opt_group.value = "-1";
	opt_group.text = "Entire group";
	var opt_particular = document.createElement("option");
	opt_particular.selected=false;
	opt_particular.value = "1";
	opt_particular.text = "Specific";
	select_type.appendChild(opt_group);
	select_type.appendChild(opt_particular);
	
	select_type.onchange=function(){
		DisableEnableElement(this, select_receiver);
	};

	//Set label
	var label_payer = document.createElement("Label");
	label_payer.setAttribute("for", select_payer.id);
	label_payer.innerHTML = "Payer<span class='glyphicon glyphicon-asterisk red'></span>";
	var label_cost = document.createElement("Label");
	label_cost.setAttribute("for", input_cost.id);
	label_cost.innerHTML = "Amount<span class='glyphicon glyphicon-asterisk red'></span>";
	var label_recv = document.createElement("Label");
	label_recv.setAttribute("for", select_receiver.id);
	label_recv.innerHTML="Receiver<span class='glyphicon glyphicon-asterisk red'></span>";
	var label_desc = document.createElement("Label");
	label_desc.setAttribute("for", input_description.id);
	label_desc.innerHTML="Description";
	label_desc.className="sr-only";
	var label_date = document.createElement("Label");
	label_date.setAttribute("for", input_date.id);
	label_date.innerHTML="Date of payment";
	label_date.className="sr-only";
	
	//Bootstrap add-on
	var div_input_group_payer = document.createElement("div");
	div_input_group_payer.className="input-group";
	var span_glyph_payer = document.createElement("span");
	span_glyph_payer.className="input-group-addon glyphicon glyphicon-user";
	var div_input_group_amount = document.createElement("div");
	div_input_group_amount.className="input-group";
	var span_glyph_amount = document.createElement("span");
	span_glyph_amount.className="input-group-addon glyphicon glyphicon-euro";
	var div_input_group_receiver = document.createElement("div");
	div_input_group_receiver.className="input-group";
	var span_glyph_receiver = document.createElement("span");
	span_glyph_receiver.className="input-group-addon glyphicon glyphicon-user";
	var div_input_group_description = document.createElement("div");
	div_input_group_description.className="input-group";
	var span_glyph_description = document.createElement("span");
	span_glyph_description.className="input-group-addon glyphicon glyphicon-tag";
	var div_input_group_date = document.createElement("div");
	div_input_group_date.className="input-group";
	var span_glyph_date = document.createElement("span");
	span_glyph_date.className="input-group-addon glyphicon glyphicon-calendar";

	//Parent div
	var parent_div = document.getElementById('div_set_payment_' + cpt_bill);
	
	//Title
	var div_title = document.createElement("div");
	div_title.className="row form-group";
	div_title.style.overflow="hidden";
	var div_title_col = document.createElement("div");
	div_title_col.className="col-xs-12 text-center";
	div_title_col.style.overflow="hidden";
	var p_title = document.createElement("p");
	p_title.className="padding_bill_participant";
	p_title.style.display="inline-block";
	p_title.style.paddingLeft="10px";
	p_title.innerHTML = "Additionnal payment"; 
	var button_trash = document.createElement("button");
	button_trash.className = "btn btn-default pull-right";
	button_trash.title = "Remove this submission";
	button_trash.type = "submit";
	button_trash.name = "Remove this submission";
	button_trash.form = "";
	button_trash.onclick=function(){
		RemoveChild(parent_div, div_container);
	};
	var span_trash = document.createElement("span");
	span_trash.className="glyphicon glyphicon-trash";
	button_trash.appendChild(span_trash);
	div_title_col.appendChild(p_title);
	div_title_col.appendChild(button_trash);
	div_title.appendChild(div_title_col);
	//Add to container
	//Payer
	div_payer.appendChild(label_payer);
	div_input_group_payer.appendChild(select_payer);
	div_input_group_payer.appendChild(span_glyph_payer);
	div_payer.appendChild(div_input_group_payer);
	div_row1.appendChild(div_payer);
	//Amount
	div_input_group_amount.appendChild(input_cost);
	div_input_group_amount.appendChild(span_glyph_amount);
	div_amount.appendChild(label_cost);
	div_amount.appendChild(div_input_group_amount);
	div_row1.appendChild(div_amount);
	//Receiver
	div_receiver.appendChild(label_recv);
	div_input_group_receiver.appendChild(select_type);
	div_input_group_receiver.appendChild(select_receiver);
	div_input_group_receiver.appendChild(span_glyph_receiver);
	div_receiver.appendChild(div_input_group_receiver);
	div_row1.appendChild(div_receiver);
	//Description
	div_description.appendChild(label_desc);
	div_input_group_description.appendChild(input_description);
	div_input_group_description.appendChild(span_glyph_description);	
	div_description.appendChild(div_input_group_description);
	div_row2.appendChild(div_description);
	//Date
	div_date.appendChild(label_date);
	div_input_group_date.appendChild(input_date);
	div_input_group_date.appendChild(span_glyph_date);
	div_date.appendChild(div_input_group_date);
	div_row2.appendChild(div_date);
	//container
	var hr = document.createElement("hr");
	hr.className="separator_payments";
	div_container.appendChild(hr);
	div_container.appendChild(div_title);
	div_container.appendChild(div_row1);
	div_container.appendChild(div_row2);
	
	//Parent div
	parent_div.appendChild(div_container);
	
  $('.selectpicker').selectpicker('refresh');
  AddPaymentLine.counter ++;
  return false;
}



//Add a row to add a participant (multiple submit) 
function AddParticipantLine()
{
	if(typeof AddParticipantLine.counter == 'undefined')
	{
		AddParticipantLine.counter = 1;
	}
	

	var div_col_name = document.createElement("div");
	div_col_name.className="col-xs-9";	
	var div_col_nb = document.createElement("div");
	div_col_nb.className="col-xs-3";
	
	var input_name = document.createElement("input");
	input_name.id = "form_set_participant_name_"+ AddParticipantLine.counter;
	input_name.type="text";
	input_name.setAttribute("class",	"form-control");
	input_name.name = "p_new_participant["+ AddParticipantLine.counter +"][p_name]";
	input_name.placeholder="Name";
	input_name.title="Name";
	
	var input_nb_of_people = document.createElement("input");
	input_nb_of_people.id = "form_set_participant_nbpeople_"+ AddParticipantLine.counter;
	input_nb_of_people.type="number";
	input_nb_of_people.value="1";
	input_nb_of_people.min="1";
	input_nb_of_people.step="1";
	input_nb_of_people.setAttribute("class",	"form-control");
	input_nb_of_people.name = "p_new_participant["+ AddParticipantLine.counter +"][p_nb_of_people]";
	input_nb_of_people.title = "Number of people";
	
	//Set label
	var label_name = document.createElement("Label");
	label_name.setAttribute("for", input_name.id);
	label_name.innerHTML="Name:";
	label_name.setAttribute("class", "sr-only");
	var label_nb_of_people = document.createElement("Label");
	label_nb_of_people.setAttribute("for", input_nb_of_people.id);
	label_nb_of_people.innerHTML="Nb. of people:";
	label_nb_of_people.setAttribute("class", "sr-only");
		
	//Assemble everything
	div_col_name.appendChild(label_name);
	div_col_name.appendChild(input_name);
	
	div_col_nb.appendChild(label_nb_of_people);
	div_col_nb.appendChild(input_nb_of_people);

	var div_row = document.createElement("div");
	div_row.setAttribute("class", "row form-group row-no-padding");
	div_row.appendChild(div_col_name);
	div_row.appendChild(div_col_nb);
	
	var form_to_add = document.getElementById('inner_participant_form');
	form_to_add.appendChild(div_row);
	
	AddParticipantLine.counter ++;
	return false;
}

//Onchange on trigger :
//trigger.value == -1 then target is disabled.
//trigger.value == 1 then targer is enable
function DisableEnableElement(trigger, target) {
	var choice1 = trigger.value;
	if(choice1 == '1')
	{
		target.disabled  = false;
	}
	else if(choice1 == '-1')
	{
		target.disabled  = true;
	}
	
  $('.selectpicker').selectpicker('refresh');
}
