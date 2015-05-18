
$('.timeField').bind('keypress', function(key) {
	if (key.key == "+") {
		var t = $(key.target);

 		var newTime = new TimeFlies.Time( t.val() );
		t.val( newTime.alter(+15).toString() );

		key.preventDefault();
	} else if (key.key == "-") {
		var t = $(key.target);

		var newTime = new TimeFlies.Time( t.val() );
		t.val( newTime.alter(-15).toString() );

		key.preventDefault();
	}
});

$('.beginTime').bind('change keypress', function(o) {
		if ((o.type== 'keypress') && ((o.key != '+') && (o.key != '-'))) {
			return;
		}
		var t = $(o.target);
		var beginTime = new TimeFlies.Time(t.val());
		beginTime.normalize();
		t.val(beginTime.toString());

		beginTime.updateWhenSmaller(t.parent().children('.endTime'));
});

$('.endTime').bind('change keypress', function(o) {
		var t = $(o.target);
		var endTime = new TimeFlies.Time(t.val());
		endTime.normalize()
		t.val(endTime.toString());

		endTime.updateWhenLarger(t.parent().children('.beginTime'));
});




