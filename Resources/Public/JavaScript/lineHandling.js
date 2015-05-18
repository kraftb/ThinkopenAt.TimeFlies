
var idx = 0;

var addLineFunc = function(key) {
	var cont = $('#itemsContainer');
	var lastBlock = cont.children().last();

	var newBlock = getNewBlock(lastBlock);

	newBlock.attr('id', 'item_'+idx);

	newBlock.find('.itemField').each(function(index, obj) {
		obj.name = updateFieldName(obj.name);
//		console.log(obj);
	});

	cont.append(newBlock);
	newBlock.show();

	idx++;
};


$('#addLine').bind('click', addLineFunc);

// Create first line
addLineFunc("X");

