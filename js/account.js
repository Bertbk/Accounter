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
		if(payer.value != payer.options[i].value && payer.options[i].value != 'null')
		{
			createOptionDropDown(receiver, payer.options[i].text, payer.options[i].value);
		}
	}
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
function AddPaymentLine(name_of_people, hashid_of_people, cpt_bill)
{
	if(typeof AddPaymentLine.counter == 'undefined')
	{
		AddPaymentLine.counter = 1;
	}
	
	if(name_of_people.length != hashid_of_people.length)
	{return false;}

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
	select_payer.className = "form-control";
	var input_cost = document.createElement("input");
	input_cost.id = "form_set_payment_cost_"+ cpt_bill + "_" + AddPaymentLine.counter;
	input_cost.type="number";
	input_cost.min="0";
	input_cost.step="0.01";
	input_cost.className = "form-control";
	input_cost.name = "p_payment["+ AddPaymentLine.counter +"][p_cost]";
	var select_receiver = document.createElement("select");
	select_receiver.id = "form_set_payment_recv_"+ cpt_bill + "_" + AddPaymentLine.counter;
	select_receiver.name = "p_payment["+ AddPaymentLine.counter +"][p_hashid_recv]";
	select_receiver.className = "form-control";
	var input_description = document.createElement("input");
	input_description.id = "form_set_payment_desc_"+ cpt_bill + "_" + AddPaymentLine.counter;
	input_description.type="text";
	input_description.name = "p_payment["+ AddPaymentLine.counter +"][p_description]";
	input_description.className = "form-control";
	input_description.placeholder = "Description";
	var input_date = document.createElement("input");
	input_date.id = "form_set_payment_date_"+ cpt_bill + "_" + AddPaymentLine.counter;
	input_date.type="date";
	input_date.name = "p_payment["+ AddPaymentLine.counter +"][p_date_of_payment]";
	input_date.className = "form-control";
	input_date.placeholder = "Date of payment";
	 $(input_date).datepicker({ dateFormat: 'dd/mm/yy'});

	//Construct the list of payer
	var opt_null = document.createElement("option");
	opt_null.selected=true;
	opt_null.disabled=true;
	opt_null.value = null;
	opt_null.text = " -- select a payer -- ";
	select_payer.appendChild(opt_null);
	for (var i =0; i < name_of_people.length; i ++)
	{
		var opt = document.createElement("option");
		opt.value = hashid_of_people[i];
		opt.text = name_of_people[i];
		select_payer.appendChild(opt);
	}

	var opt_group = document.createElement("option");
	opt_group.selected=true;
	opt_group.value = "-1";
	opt_group.text = "Group";
	select_receiver.appendChild(opt_group);
	
	select_payer.onchange=function(){
		DropDownListsBetweenParticipants(this, select_receiver);
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
	var div_input_group_amount = document.createElement("div");
	div_input_group_amount.className="input-group";
	var span_glyph_amount = document.createElement("span");
	span_glyph_amount.className="input-group-addon glyphicon glyphicon-euro";
	var div_input_group_date = document.createElement("div");
	div_input_group_date.className="input-group";
	var span_glyph_date = document.createElement("span");
	span_glyph_date.className="input-group-addon glyphicon glyphicon-calendar";

	//Add to div...
	div_payer.appendChild(label_payer);
	div_payer.appendChild(select_payer);
	div_row1.appendChild(div_payer);
	//Amount
	div_amount.appendChild(label_cost);
	div_input_group_amount.appendChild(input_cost);
	div_input_group_amount.appendChild(span_glyph_amount);
	div_amount.appendChild(div_input_group_amount);
	div_row1.appendChild(div_amount);
	//Receiver
	div_receiver.appendChild(label_recv);
	div_receiver.appendChild(select_receiver);
	div_row1.appendChild(div_receiver);
	//Description
	div_description.appendChild(label_desc);
	div_description.appendChild(input_description);
	div_row2.appendChild(div_description);
	//Date
	div_date.appendChild(label_date);
	div_input_group_date.appendChild(input_date);
	div_input_group_date.appendChild(span_glyph_date);
	div_date.appendChild(div_input_group_date);
	div_row2.appendChild(div_date);

	var hr = document.createElement("hr");
	hr.className="separator_payments";
	
	//Parent div
	var parent_div = document.getElementById('div_set_payment_' + cpt_bill);
	parent_div.appendChild(hr);
	parent_div.appendChild(div_row1);
	parent_div.appendChild(div_row2);
	
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
	
	var input_name = document.createElement("input");
	input_name.id = "form_set_participant_name_"+ AddParticipantLine.counter;
	input_name.type="text";
	input_name.setAttribute("class",	"form-control-inline col-xs-9");
	input_name.name = "p_new_participant["+ AddParticipantLine.counter +"][p_name]";
	input_name.placeholder="Name";
	
	var input_nb_of_people = document.createElement("input");
	input_nb_of_people.id = "form_set_participant_nbpeople_"+ AddParticipantLine.counter;
	input_nb_of_people.type="number";
	input_nb_of_people.value="1";
	input_nb_of_people.min="1";
	input_nb_of_people.step="1";
	input_nb_of_people.setAttribute("class",	"form-control-inline col-xs-3");
	input_nb_of_people.name = "p_new_participant["+ AddParticipantLine.counter +"][p_nb_of_people]";
	
	//Set label
	var label_name = document.createElement("Label");
	label_name.setAttribute("for", input_name.id);
	label_name.innerHTML="Name:";
	label_name.setAttribute("class", "sr-only");
	var label_nb_of_people = document.createElement("Label");
	label_nb_of_people.setAttribute("for", input_nb_of_people.id);
	label_nb_of_people.innerHTML="Nb. of people:";
	label_nb_of_people.setAttribute("class", "sr-only");
	
	var div_row = document.createElement("div");
	div_row.setAttribute("class", "row form-group");
	div_row.appendChild(label_name);
	div_row.appendChild(input_name);
	div_row.appendChild(label_nb_of_people);
	div_row.appendChild(input_nb_of_people);
	
	var form_to_add = document.getElementById('inner_participant_form');
	form_to_add.appendChild(div_row);
	
   AddParticipantLine.counter ++;
   return false;
}
