
TimeFlies = {}; 

TimeFlies.Time = function(timeString) {

	// Allow instantiation without the 'new' keyword
	if ( !(this instanceof TimeFlies.Time) ) {
		return new TimeFlies.Time( timeString );
	}

	this.fromString( timeString );
}

TimeFlies.Date = function(dateString) {

	// Allow instantiation without the 'new' keyword
	if ( !(this instanceof TimeFlies.Date) ) {
		return new TimeFlies.Date( dateString );
	}

	this.fromString( dateString );
}

TimeFlies.Time.prototype = {
	hour: 0,
	minute: 0,
	second: 0,

	minuteStep: 15,
	separator: ':',


	/**
	 * Alters the current time by adding "diff" minutes.
	 *
	 * @return integer The minutes by which to alter the current time (+/-)
	 * @return object Returns itself for call chaining
	 */
	alter: function(diff) {
		this.minute += diff;
		this.normalize();
		return this;
	},

	/**
	 * Converts the passed time object to an integer representing the seconds since 0:00
	 *
	 * @param string time The time which should get converted
	 * @return integer The seconds since 0:00
	 */
	toInt: function() {
		return this.hour * 60 * 60 + this.minute * 60 + this.second;
	},

	/**
	 * Sets the current time object by splitting the passed time string by the separator
	 *
	 * @param string current: The time which to split as string like "13:45"
	 * @return object Returns itself for call chaining
	 */
	fromString: function(current) {
		var z = current.split(this.separator);
		this.hour = parseInt(z[0]);
		this.minute = parseInt(z[1]);
		if ((typeof this.hour != 'number') || isNaN(this.hour)) {
			this.hour = 0;
		}
		if ((typeof this.minute != 'number') || isNaN(this.minute)) {
			this.minute = 0;
		}
		return this;
	},

	/**
	 * Normalizes this time object. This method takes care that the time is rounded to the value
	 * specified via "minuteStep" and that minutes/hours are not out of bounds (0-59 minutes, 0-23 hours)
	 *
	 * @return object Returns itself for call chaining
	 */
	normalize: function() {
		if ((this.minute % this.minuteStep) != 0) {
			this.minute += 15 - (this.minute % this.minuteStep);
		}
		if (this.minute >= 60) {
			this.hour += 1;
			this.minute -= 60;
		}
		if (this.minute < 0) {
			this.hour -= 1;
			this.minute += 60;
		}
		if (this.hour >= 24) {
			this.hour -= 24;
		}
		if (this.hour < 0) {
			this.hour += 24;
		}
		return this;
	},

	/**
	 * Converts the current time object to a string
	 *
	 * @param boolean includeSeconds: When TRUE seconds will also get returned
	 * @return string This time object formatted as string
	 */
	toString: function(includeSeconds) {
		var res = this.hour + this.separator;
		if (this.minute < 10) {
			res += "0";
		}
		res += this.minute;
		if (includeSeconds) {
			res += this.separator;
			if (this.second < 10) {
				res += "0";
			}
			res += this.second;
		}
		return res;
	},

	/**
	 * Updates the passed element if it's time value is lower than the current time value.
	 *
	 * @param object el: The element which to compare
	 * @return object Returns itself for call chaining
	 */
	updateWhenSmaller: function(obj) {
		var otherTime = new TimeFlies.Time(obj.val());
		if (otherTime.toInt() < this.toInt()) {
			obj.val(this.toString());
		}
		return this;
	},

	/**
	 * Updates the passed element if it's time value is larger than the current time value.
	 *
	 * @param object el: The element which to compare
	 * @return object Returns itself for call chaining
	 */
	updateWhenLarger: function(obj) {
		var otherTime = new TimeFlies.Time(obj.val());
		if (otherTime.toInt() > this.toInt()) {
			obj.val(this.toString());
		}
		return this;
	}

}

TimeFlies.Date.prototype = {
	year: 0,
	month: 0,
	day: 0,

	separator: '-',

	/**
	 * Converts the current date object to a string
	 *
	 * @param boolean includeSeconds: When TRUE seconds will also get returned
	 * @return string This time object formatted as string
	 */
	toString: function() {
		var res = this.year + this.separator;
		if (0 && this.month < 10) {
			res += "0";
		}
		res += this.month;

		res += this.separator;

		if (0 && this.day < 10) {
			day += "0";
		}
		res += this.day;
		return res;
	},

	/**
	 * Normalizes this date object.
	 *
	 * @return object Returns itself for call chaining
	 */
	normalize: function() {
		var d = new Date(this.year, this.month-1, this.day);
		this.year = d.getFullYear();
		this.month = d.getMonth() + 1;
		this.day = d.getDate();
		return this;
	},

	/**
	 * Sets the current date object by splitting the passed time string by the separator
	 *
	 * @param string current: The time which to split as string like "2014-11-19"
	 * @return object Returns itself for call chaining
	 */
	fromString: function(current) {
		var z = current.split(this.separator);
		this.year = parseInt(z[0]);
		this.month = parseInt(z[1]);
		this.day = parseInt(z[2]);
		this.normalize();
		return this;
	},

	/**
	 * Alters the current date by adding "diff" days.
	 *
	 * @return integer The days by which to alter the current date (+/-)
	 * @return object Returns itself for call chaining
	 */
	alter: function(diff) {
		this.day += diff;
		this.normalize();
		return this;
	},

}

