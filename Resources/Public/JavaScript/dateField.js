
$('.dateField').bind('keypress', function(key) {
	if (key.key == "+") {
		var t = $(key.target);

 		var newDate = new TimeFlies.Date( t.val() );
		t.val( newDate.alter(+1).toString() );

		key.preventDefault();
	} else if (key.key == "-") {
		var t = $(key.target);

 		var newDate = new TimeFlies.Date( t.val() );
		t.val( newDate.alter(-1).toString() );

		key.preventDefault();
	}
});

$('.dateField').bind('change', function(o) {
		var t = $(o.target);
		var checkDate = new TimeFlies.Date(t.val());
		checkDate.normalize();
		t.val(checkDate.toString());
});

