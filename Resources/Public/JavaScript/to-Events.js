
/**
 * Connects the given events to given objects and sets the given handler
 *
 * @param string className: The class name whose elements to connect
 * @param array events: Connect those JS events
 * @param closure handler: The handler which to connect
 * @param [optional] object parentObject: The parent object in which to look for "className" elements
 * @return void
 */
function connectEventsByClassName(className, events, handler, parentObject) {
	if (typeof parentObject == "object") {
		var objects = parentObject.querySelectorAll(className);
	} else {
		var objects = document.querySelectorAll(className);
	}
	for (var i = 0; i < objects.length; i++) {
		var o = objects[i];
		for (var j = 0; j < events.length; j ++) {
			o.addEventListener(events[j], handler);
			if (typeof o.toEvents == "undefined") {
				o.toEvents = new Array;
			}
			o.toEvents.push({event: events[j], handler: handler});
		}
	}
}

