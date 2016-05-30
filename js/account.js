function configureDropDownLists(payer, receiver) {
    var choice1 = payer.value;
	receiver.length= 0;
	createOption(receiver, 'Group', -1);
	for (i = 0; i < payer.options.length; i++) {
		if(payer.value != payer.options[i].value && payer.options[i].value != 'null')
		{
			createOption(receiver, payer.options[i].text, payer.options[i].value);
		}
	}
}

function createOption(ddl, text, value) {
	var opt = document.createElement('option');
	opt.value = value;
	opt.text = text;
	ddl.options.add(opt);
}


