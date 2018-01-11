
function Form_Select_getSelectedValue(object) {
	if (object.selectedIndex == -1) {
		return null;
	}
	return object.options[object.selectedIndex].value;
}

function Form_Select_setSelectedValue(object, value) {
	if (object.selectedIndex != -1) {
		object.options[object.selectedIndex].selected = false;
	}
	for (i in object.options) {
		var o = object.options[i];
		if (o.value == value) {
			o.selected = true;
			return;
		}
	}
}

