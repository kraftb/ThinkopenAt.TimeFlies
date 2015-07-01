
var idx = 0;

var addLineFunc = function(key, keepCategory, keepComment) {
	var cont = $('#itemsContainer');
	var lastBlock = cont.children().last();

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
addLineFunc("X");

