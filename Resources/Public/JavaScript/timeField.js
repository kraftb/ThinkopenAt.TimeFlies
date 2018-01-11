
var alterTimeField = function(e) {
	var offset = 0;
	if (e.key == "+") {
		offset = +15;
	} else if (e.key == "*") {
		offset = +60;
	} else if (e.key == "-") {
		offset = -15;
	} else if (e.key == "_") {
		offset = -60;
	}
	if (offset != 0) {
		var obj = e.target;
 		var newTime = new TimeFlies.Time( obj.value, obj.dataset.enabledayoffset ? true : false);
		obj.value = newTime.alter(offset).toString();
		e.preventDefault();
	}
};

var handleBeginTimeField = function(e) {
	if (
		(e.type != 'keypress') || (
			(e.key == '+') ||
			(e.key == '*') ||
			(e.key == '-') ||
			(e.key == '_')
		)
	) {
		var obj = e.target;
		var beginTime = new TimeFlies.Time( obj.value, obj.dataset.enabledayoffset ? true : false );
		beginTime.normalize();
		obj.value = beginTime.toString();
		var otherField = obj.parentElement.querySelector('.endTime');
		beginTime.updateWhenSmaller( otherField );
	}
};

var handleEndTimeField = function(e) {
	if (
		(e.type != 'keypress') || (
			(e.key == '+') ||
			(e.key == '*') ||
			(e.key == '-') ||
			(e.key == '_')
		)
	) {
		var obj = e.target;
		var endTime = new TimeFlies.Time( obj.value, obj.dataset.enabledayoffset ? true : false );
		endTime.normalize()
		obj.value = endTime.toString();
		var otherField = obj.parentElement.querySelector('.beginTime');
		endTime.updateWhenLarger( otherField );
	}
};


function bindTimeEvents() {
	connectEventsByClassName('.timeField', ['keypress'], alterTimeField);
	connectEventsByClassName('.beginTime', ['change', 'keypress'], handleBeginTimeField);
	connectEventsByClassName('.endTime', ['change', 'keypress'], handleEndTimeField);
}

