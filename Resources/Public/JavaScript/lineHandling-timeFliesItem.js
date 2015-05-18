
var getNewBlock = function(lastBlock) {
	var endTime = lastBlock.children('.endTime').val();
	
	var newBlock = lastBlock.clone( true );
	newBlock.children('.beginTime').val(endTime);
	newBlock.children('.commentField').val('');
	return newBlock;
}

var updateFieldName = function(fieldName) {
	return fieldName.replace(/newItems\[([0-9]+|\*)\]/, 'newItems['+idx+']');
}

