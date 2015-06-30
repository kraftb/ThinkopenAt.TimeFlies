
$('.timeField').bind('keypress', function(key) {
	var offset = 0;
	if (key.key == "+") {
		offset = +15;
	} else if (key.key == "*") {
		offset = +60;
	} else if (key.key == "-") {
		offset = -15;
	} else if (key.key == "_") {
		offset = -60;
	}
	if (offset != 0) {
		var t = $(key.target);

 		var newTime = new TimeFlies.Time( t.val(), t.data('enabledayoffset') ? true : false );
		t.val( newTime.alter(offset).toString() );

		key.preventDefault();
	}
});

$('.beginTime').bind('change keypress', function(o) {
		if (
			(o.type != 'keypress') || (
				(o.key == '+') ||
				(o.key == '*') ||
				(o.key == '-') ||
				(o.key == '_')
			)
		) {
			var t = $(o.target);
			var beginTime = new TimeFlies.Time( t.val(), t.data('enabledayoffset') ? true : false );
			beginTime.normalize();
			t.val( beginTime.toString() );

			beginTime.updateWhenSmaller( t.parent().children('.endTime') );
		}
});

$('.endTime').bind('change keypress', function(o) {
		if (
			(o.type != 'keypress') || (
				(o.key == '+') ||
				(o.key == '*') ||
				(o.key == '-') ||
				(o.key == '_')
			)
		) {
			var t = $(o.target);
			var endTime = new TimeFlies.Time( t.val(), t.data('enabledayoffset') ? true : false );
			endTime.normalize()
			t.val( endTime.toString() );

			endTime.updateWhenLarger( t.parent().children('.beginTime') );
		}
});


