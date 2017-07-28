
var idx = 0;

var addLineFunc = function(key, keepCategory, keepComment) {
	var cont = $('#itemsContainer');
//	console.log(cont.html());
	var lastBlock = cont.children().last();
//	console.log(lastBlock.html());

	var newBlock = getNewBlock(lastBlock, keepCategory, keepComment);

	var newId = 'item_'+idx;
	newBlock.attr('id', newId);

	newBlock.find('.itemField').each(function(index, obj) {
		obj.name = updateFieldName(obj.name);
//		console.log(obj);
	});

	cont.append(newBlock);
	newBlock.show();

	idx++;
	return newId;
};


$('#addLine').bind('click', addLineFunc);

// Create first line
$(document).ready(function() {
	addLineFunc("X");
});

