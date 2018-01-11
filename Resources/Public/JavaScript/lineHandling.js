
var idx = 0;

var addLineFunc = function(e, keepCategory, keepComment) {
	if (typeof e == "object") {
		if (e.constructor.name == "MouseEvent") {
			if (e.shiftKey) {
				keepCategory = true;
				keepComment = true;
			}
		}
	}

	var container = document.getElementById('itemsContainer');
	var template = container.children[container.children.length-1];

	var newBlock = getNewBlock(template, keepCategory, keepComment);

	var newId = 'item_' + idx;
	newBlock.id = newId;

	var fields = newBlock.querySelectorAll('.itemField');
	var templateFields = template.querySelectorAll('.itemField');
	for (var i = 0; i < fields.length; i++) {
		var c = fields[i];
		var tf = templateFields[i];
		c.name = updateFieldName(c.name);
		if (typeof tf.toEvents == "object") {
			for (var j = 0; j < tf.toEvents.length; j++) {
				var e = tf.toEvents[j];
				c.addEventListener(e.event, e.handler);
			}
		}
	}

	newBlock.style.display = "block";

	container.appendChild(newBlock);

	idx++;
	return newId;
};

document.getElementById('addLine').addEventListener('click', addLineFunc);

// Create first line
window.addEventListener('load', function( ) {
	// console.info('Document has finished loading');
	addLineFunc("X");
	bindTimeEvents();
	bindDateEvents();
});

