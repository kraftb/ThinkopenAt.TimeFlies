
var alterDateField = function(e) {
	if (
		e.key == "+" ||
		e.key == "-" ||
		e.key == "*" ||
		e.key == "_"
	) {
		var t = e.target;
		var newDate = new TimeFlies.Date( t.value );
		if (e.key == "+") {
			t.value = newDate.alter(+1).toString();
		} else if (e.key == "-") {
			t.value = newDate.alter(-1).toString();
		} else if (e.key == "*") {
			t.value = newDate.alter(+31).toString();
		} else if (e.key == "_") {
			t.value = newDate.alter(-31).toString();
		}
		e.preventDefault();
	}
};

var checkDateField = function(e) {
	var t = e.target;
	var checkDate = new TimeFlies.Date( t.value );
	checkDate.normalize();
	t.value = checkDate.toString();
};


function bindDateEvents() {
	connectEventsByClassName('.dateField', ['keypress'], alterDateField);
	connectEventsByClassName('.dateField', ['change'], checkDateField);
}

