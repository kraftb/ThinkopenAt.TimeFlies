
var getNewBlock = function(lastBlock, keepCategory, keepComment) {
	var endTime = lastBlock.children('.endTime').val();
	
	var newBlock = lastBlock.clone( true );
	newBlock.children('.beginTime').val(endTime);
	if (!keepComment) {
		newBlock.children('.commentField').val('');
	}
	if (keepCategory) {
		newBlock.children('.categoriesField').val(lastBlock.children('.categoriesField').val());
	}
	return newBlock;
}

var updateFieldName = function(fieldName) {
	return fieldName.replace(/newItems\[([0-9]+|\*)\]/, 'newItems['+idx+']');
}

$(window).keypress(function(e) {
	if (e.altKey && e.key == 'n') {
		var blockId = addLineFunc(e.key);
		$('#'+blockId).find('.endTime').each(function(index, obj) {
			$(obj).focus();
		});
	} else if (e.altKey && e.key == 'N') {
		var blockId = addLineFunc(e.key, true, true);
		$('#'+blockId).find('.endTime').each(function(index, obj) {
			$(obj).focus();
		});
	} else {
//		console.log(e);
	}
});


