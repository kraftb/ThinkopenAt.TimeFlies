
var getNewBlock = function(template, keepCategory, keepComment) {
	var endTime = template.querySelector('.endTime').value;

	var newBlock = template.cloneNode( true );
	newBlock.querySelector('.beginTime').value = endTime;

	if (!keepComment) {
		newBlock.querySelector('.commentField').value = '';
	}
	if (keepCategory) {
		var selected = Form_Select_getSelectedValue(template.querySelector('.categoriesField'));
		Form_Select_setSelectedValue(newBlock.querySelector('.categoriesField'), selected);
	}
	return newBlock;
}

var updateFieldName = function(fieldName) {
	return fieldName.replace(/newItems\[([0-9]+|\*)\]/, 'newItems['+idx+']');
}

window.addEventListener('keypress', function(e) {
	var blockId = "";
	if (e.altKey && e.key == 'n') {
		blockId = addLineFunc(e);
	} else if (e.altKey && e.key == 'N') {
		var blockId = addLineFunc(e, true, true);
	} else {
		// console.log(e);
	}
	if (blockId != "") {
		var block = document.getElementById(blockId);
		var el = block.querySelector('.endTime');
		el.focus();
	}
});

