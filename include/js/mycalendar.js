function strtotime (str, now) {
    // http://kevin.vanzonneveld.net
    // +   original by: Caio Ariede (http://caioariede.com)
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +      input by: David
    // +   improved by: Caio Ariede (http://caioariede.com)
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // +   bugfixed by: Wagner B. Soares
    // +   bugfixed by: Artur Tchernychev
    // %        note 1: Examples all have a fixed timestamp to prevent tests to fail because of variable time(zones)
    // *     example 1: strtotime('+1 day', 1129633200);
    // *     returns 1: 1129719600
    // *     example 2: strtotime('+1 week 2 days 4 hours 2 seconds', 1129633200);
    // *     returns 2: 1130425202
    // *     example 3: strtotime('last month', 1129633200);
    // *     returns 3: 1127041200
    // *     example 4: strtotime('2009-05-04 08:30:00');
    // *     returns 4: 1241418600
    var i, match, s, strTmp = '',
        parse = '';

    strTmp = str;
    strTmp = strTmp.replace(/\s{2,}|^\s|\s$/g, ' '); // unecessary spaces
    strTmp = strTmp.replace(/[\t\r\n]/g, ''); // unecessary chars
    if (strTmp == 'now') {
        return (new Date()).getTime() / 1000; // Return seconds, not milli-seconds
    } else if (!isNaN(parse = Date.parse(strTmp))) {
        return (parse / 1000);
    } else if (now) {
        now = new Date(now * 1000); // Accept PHP-style seconds
    } else {
        now = new Date();
    }

    strTmp = strTmp.toLowerCase();

    var __is = {
        day: {
            'sun': 0,
            'mon': 1,
            'tue': 2,
            'wed': 3,
            'thu': 4,
            'fri': 5,
            'sat': 6
        },
        mon: {
            'jan': 0,
            'feb': 1,
            'mar': 2,
            'apr': 3,
            'may': 4,
            'jun': 5,
            'jul': 6,
            'aug': 7,
            'sep': 8,
            'oct': 9,
            'nov': 10,
            'dec': 11
        }
    };

    var process = function (m) {
        var ago = (m[2] && m[2] == 'ago');
        var num = (num = m[0] == 'last' ? -1 : 1) * (ago ? -1 : 1);

        switch (m[0]) {
        case 'last':
        case 'next':
            switch (m[1].substring(0, 3)) {
            case 'yea':
                now.setFullYear(now.getFullYear() + num);
                break;
            case 'mon':
                now.setMonth(now.getMonth() + num);
                break;
            case 'wee':
                now.setDate(now.getDate() + (num * 7));
                break;
            case 'day':
                now.setDate(now.getDate() + num);
                break;
            case 'hou':
                now.setHours(now.getHours() + num);
                break;
            case 'min':
                now.setMinutes(now.getMinutes() + num);
                break;
            case 'sec':
                now.setSeconds(now.getSeconds() + num);
                break;
            default:
                var day;
                if (typeof(day = __is.day[m[1].substring(0, 3)]) != 'undefined') {
                    var diff = day - now.getDay();
                    if (diff == 0) {
                        diff = 7 * num;
                    } else if (diff > 0) {
                        if (m[0] == 'last') {
                            diff -= 7;
                        }
                    } else {
                        if (m[0] == 'next') {
                            diff += 7;
                        }
                    }
                    now.setDate(now.getDate() + diff);
                }
            }
            break;

        default:
            if (/\d+/.test(m[0])) {
                num *= parseInt(m[0], 10);

                switch (m[1].substring(0, 3)) {
                case 'yea':
                    now.setFullYear(now.getFullYear() + num);
                    break;
                case 'mon':
                    now.setMonth(now.getMonth() + num);
                    break;
                case 'wee':
                    now.setDate(now.getDate() + (num * 7));
                    break;
                case 'day':
                    now.setDate(now.getDate() + num);
                    break;
                case 'hou':
                    now.setHours(now.getHours() + num);
                    break;
                case 'min':
                    now.setMinutes(now.getMinutes() + num);
                    break;
                case 'sec':
                    now.setSeconds(now.getSeconds() + num);
                    break;
                }
            } else {
                return false;
            }
            break;
        }
        return true;
    };

    match = strTmp.match(/^(\d{2,4}-\d{2}-\d{2})(?:\s(\d{1,2}:\d{2}(:\d{2})?)?(?:\.(\d+))?)?$/);
    if (match != null) {
        if (!match[2]) {
            match[2] = '00:00:00';
        } else if (!match[3]) {
            match[2] += ':00';
        }

        s = match[1].split(/-/g);

        for (i in __is.mon) {
            if (__is.mon[i] == s[1] - 1) {
                s[1] = i;
            }
        }
        s[0] = parseInt(s[0], 10);

        s[0] = (s[0] >= 0 && s[0] <= 69) ? '20' + (s[0] < 10 ? '0' + s[0] : s[0] + '') : (s[0] >= 70 && s[0] <= 99) ? '19' + s[0] : s[0] + '';
        return parseInt(this.strtotime(s[2] + ' ' + s[1] + ' ' + s[0] + ' ' + match[2]) + (match[4] ? match[4] / 1000 : ''), 10);
    }

    var regex = '([+-]?\\d+\\s' + '(years?|months?|weeks?|days?|hours?|min|minutes?|sec|seconds?' + '|sun\\.?|sunday|mon\\.?|monday|tue\\.?|tuesday|wed\\.?|wednesday' + '|thu\\.?|thursday|fri\\.?|friday|sat\\.?|saturday)' + '|(last|next)\\s' + '(years?|months?|weeks?|days?|hours?|min|minutes?|sec|seconds?' + '|sun\\.?|sunday|mon\\.?|monday|tue\\.?|tuesday|wed\\.?|wednesday' + '|thu\\.?|thursday|fri\\.?|friday|sat\\.?|saturday))' + '(\\sago)?';

    match = strTmp.match(new RegExp(regex, 'gi')); // Brett: seems should be case insensitive per docs, so added 'i'
    if (match == null) {
        return false;
    }

    for (i = 0; i < match.length; i++) {
        if (!process(match[i].split(' '))) {
            return false;
        }
    }

    return (now.getTime() / 1000);
}
function date (format, timestamp) {
    // +   original by: Carlos R. L. Rodrigues (http://www.jsfromhell.com)
    var that = this,
        jsdate, f, formatChr = /\\?([a-z])/gi,
        formatChrCb,
        // Keep this here (works, but for code commented-out
        // below for file size reasons)
        //, tal= [],
        _pad = function (n, c) {
            if ((n = n + '').length < c) {
                return new Array((++c) - n.length).join('0') + n;
            }
            return n;
        },
        txt_words = ["Sun", "Mon", "Tues", "Wednes", "Thurs", "Fri", "Satur", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    formatChrCb = function (t, s) {
        return f[t] ? f[t]() : s;
    };
    f = {
        // Day
        d: function () { // Day of month w/leading 0; 01..31
            return _pad(f.j(), 2);
        },
        D: function () { // Shorthand day name; Mon...Sun
            return f.l().slice(0, 3);
        },
        j: function () { // Day of month; 1..31
            return jsdate.getDate();
        },
        l: function () { // Full day name; Monday...Sunday
            return txt_words[f.w()] + 'day';
        },
        N: function () { // ISO-8601 day of week; 1[Mon]..7[Sun]
            return f.w() || 7;
        },
        S: function () { // Ordinal suffix for day of month; st, nd, rd, th
            var j = f.j();
            return j > 4 || j < 21 ? 'th' : {1: 'st', 2: 'nd', 3: 'rd'}[j % 10] || 'th';
        },
        w: function () { // Day of week; 0[Sun]..6[Sat]
            return jsdate.getDay();
        },
        z: function () { // Day of year; 0..365
            var a = new Date(f.Y(), f.n() - 1, f.j()),
                b = new Date(f.Y(), 0, 1);
            return Math.round((a - b) / 864e5) + 1;
        },

        // Week
        W: function () { // ISO-8601 week number
            var a = new Date(f.Y(), f.n() - 1, f.j() - f.N() + 3),
                b = new Date(a.getFullYear(), 0, 4);
            return _pad(1 + Math.round((a - b) / 864e5 / 7), 2);
        },

        // Month
        F: function () { // Full month name; January...December
            return txt_words[6 + f.n()];
        },
        m: function () { // Month w/leading 0; 01...12
            return _pad(f.n(), 2);
        },
        M: function () { // Shorthand month name; Jan...Dec
            return f.F().slice(0, 3);
        },
        n: function () { // Month; 1...12
            return jsdate.getMonth() + 1;
        },
        t: function () { // Days in month; 28...31
            return (new Date(f.Y(), f.n(), 0)).getDate();
        },

        // Year
        L: function () { // Is leap year?; 0 or 1
            return new Date(f.Y(), 1, 29).getMonth() === 1 | 0;
        },
        o: function () { // ISO-8601 year
            var n = f.n(),
                W = f.W(),
                Y = f.Y();
            return Y + (n === 12 && W < 9 ? -1 : n === 1 && W > 9);
        },
        Y: function () { // Full year; e.g. 1980...2010
            return jsdate.getFullYear();
        },
        y: function () { // Last two digits of year; 00...99
            return (f.Y() + "").slice(-2);
        },

        // Time
        a: function () { // am or pm
            return jsdate.getHours() > 11 ? "pm" : "am";
        },
        A: function () { // AM or PM
            return f.a().toUpperCase();
        },
        B: function () { // Swatch Internet time; 000..999
            var H = jsdate.getUTCHours() * 36e2,
                // Hours
                i = jsdate.getUTCMinutes() * 60,
                // Minutes
                s = jsdate.getUTCSeconds(); // Seconds
            return _pad(Math.floor((H + i + s + 36e2) / 86.4) % 1e3, 3);
        },
        g: function () { // 12-Hours; 1..12
            return f.G() % 12 || 12;
        },
        G: function () { // 24-Hours; 0..23
            return jsdate.getHours();
        },
        h: function () { // 12-Hours w/leading 0; 01..12
            return _pad(f.g(), 2);
        },
        H: function () { // 24-Hours w/leading 0; 00..23
            return _pad(f.G(), 2);
        },
        i: function () { // Minutes w/leading 0; 00..59
            return _pad(jsdate.getMinutes(), 2);
        },
        s: function () { // Seconds w/leading 0; 00..59
            return _pad(jsdate.getSeconds(), 2);
        },
        u: function () { // Microseconds; 000000-999000
            return _pad(jsdate.getMilliseconds() * 1000, 6);
        },

        // Timezone
        e: function () { // Timezone identifier; e.g. Atlantic/Azores, ...
            // The following works, but requires inclusion of the very large
            // timezone_abbreviations_list() function.
/*              return this.date_default_timezone_get();
*/
            throw 'Not supported (see source code of date() for timezone on how to add support)';
        },
        I: function () { // DST observed?; 0 or 1
            // Compares Jan 1 minus Jan 1 UTC to Jul 1 minus Jul 1 UTC.
            // If they are not equal, then DST is observed.
            var a = new Date(f.Y(), 0),
                // Jan 1
                c = Date.UTC(f.Y(), 0),
                // Jan 1 UTC
                b = new Date(f.Y(), 6),
                // Jul 1
                d = Date.UTC(f.Y(), 6); // Jul 1 UTC
            return 0 + ((a - c) !== (b - d));
        },
        O: function () { // Difference to GMT in hour format; e.g. +0200
            var a = jsdate.getTimezoneOffset();
            return (a > 0 ? "-" : "+") + _pad(Math.abs(a / 60 * 100), 4);
        },
        P: function () { // Difference to GMT w/colon; e.g. +02:00
            var O = f.O();
            return (O.substr(0, 3) + ":" + O.substr(3, 2));
        },
        T: function () { // Timezone abbreviation; e.g. EST, MDT, ...
            // The following works, but requires inclusion of the very
            // large timezone_abbreviations_list() function.
/*              var abbr = '', i = 0, os = 0, default = 0;
            if (!tal.length) {
                tal = that.timezone_abbreviations_list();
            }
            if (that.php_js && that.php_js.default_timezone) {
                default = that.php_js.default_timezone;
                for (abbr in tal) {
                    for (i=0; i < tal[abbr].length; i++) {
                        if (tal[abbr][i].timezone_id === default) {
                            return abbr.toUpperCase();
                        }
                    }
                }
            }
            for (abbr in tal) {
                for (i = 0; i < tal[abbr].length; i++) {
                    os = -jsdate.getTimezoneOffset() * 60;
                    if (tal[abbr][i].offset === os) {
                        return abbr.toUpperCase();
                    }
                }
            }
*/
            return 'UTC';
        },
        Z: function () { // Timezone offset in seconds (-43200...50400)
            return -jsdate.getTimezoneOffset() * 60;
        },

        // Full Date/Time
        c: function () { // ISO-8601 date.
            return 'Y-m-d\\Th:i:sP'.replace(formatChr, formatChrCb);
        },
        r: function () { // RFC 2822
            return 'D, d M Y H:i:s O'.replace(formatChr, formatChrCb);
        },
        U: function () { // Seconds since UNIX epoch
            return jsdate.getTime() / 1000 | 0;
        }
    };
    this.date = function (format, timestamp) {
        that = this;
        jsdate = ((typeof timestamp === 'undefined') ? new Date() : // Not provided
        (timestamp instanceof Date) ? new Date(timestamp) : // JS Date()
        new Date(timestamp * 1000) // UNIX timestamp (auto-convert to int)
        );
        return format.replace(formatChr, formatChrCb);
    };
    return this.date(format, timestamp);
}


function makeDatepicker(m,y,div)
{
	var theCal = new calendar(m,y);
	theCal.getDatepicker(div);
}
function calendar(theMonth,theYear,options)
{
	this.dayNames = ["Mo","Di","Mi","Do","Fr","Sa","So"];
	this.monthNames = ["Januar","Februar","März","April","Mai","Juni","Juli","August","September","Oktober","November","Dezember"];
	this.keepEmpty = false;
	this.dateSeparator = ".";
	this.relateTo = "";
	this.dateFormat = "d.m.Y";


	this.calendar = [];
	var datobj = new Date();
	this.currmonth = datobj.getMonth();
	this.curryear = datobj.getFullYear();
	this.currday = datobj.getDate();


	if(theMonth > 12)
	{
		theMonth = 1;
		theYear = theYear+1;
	}
	if(theMonth < 1)
	{
		theMonth = 12;
		theYear = theYear-1;
	}

	this.month = theMonth-1;
	this.year = theYear;

	this.daysInMonth = this.getDaysInMonth(this.month,this.year);
	this.daysLastMonth = this.getDaysInMonth(this.month-1,this.year);
	var firstDay = new Date(this.year, this.month, 1);
	this.startDay = firstDay.getDay()-1;
	if(this.startDay<0)this.startDay+=7;
	var tempDays = this.startDay + this.daysInMonth;
	this.weeksInMonth = Math.ceil(tempDays/7);

}

calendar.prototype.getDatepicker = function(theDiv)
{
    if(this.dateFormat == "m/d/Y" || this.dateFormat == "m/d/y")
    {
        this.dateSeparator = "/";
    }
	var theHtml = "";
	var pmonth = this.month;
	var nmonth = this.month+2;
	this.theDiv = theDiv;


	if(this.relateTo)
	{
		document.getElementById(this.relateTo).onfocus = function()
		{
            Velocity(document.getElementById(theDiv), "fadeIn", {duration: 800});
		}
	}

	theHtml += "<table class=\"cal\" cellpadding=\"0\" cellspacing=\"1\" border = \"0\">";
	theHtml += "<tr class=\"head\" >" +
			"<td class = \"back\"><a href = \"javascript:void(0);\"></a></td>" +
			"<td colspan=\"5\" >" + this.monthNames[this.month] + " " + this.year + "</td>" +
			"<td class=\"next\"><a href = \"javascript:void(0);\"></a></td></tr>";

	theHtml += "<tr class = \"weekday\">";
	for(i=0;i<this.dayNames.length;i++)
	{
		theHtml += "<td >" + this.dayNames[i] + "</td>"
	}
	theHtml += "</tr>";

	var thecal = this.buildCal();

	if(!this.keepEmpty && !document.getElementById(this.relateTo).value)
	{
        var strMon;
        var strDay;
		if((this.month+1) < 10)
		{
			strMon = "0" + (this.month+1);
		}
		else
		{
			strMon = this.month+1;
		}
		if(this.currday < 10 && this.currday > 0)
		{
			strDay = "0" + this.currday;
		}
		else
		{
			strDay = this.currday;
		}


		//initStr = strMon + this.dateSeparator + strDay + this.dateSeparator + this.curryear;
		var initStr2 = strMon + "/" + strDay + "/" + this.curryear;
      //  var theIniStamp = Date.parse(initStr2)/1000;
        var theIniStamp = strtotime(initStr2);

		document.getElementById(this.relateTo).value =  date(this.dateFormat,theIniStamp);
	}
		var selectedVals = document.getElementById(this.relateTo).value.split(this.dateSeparator);


	for(j=0;j<this.weeksInMonth;j++) {
		theHtml += "<tr>";
		for(i=0;i<7;i++) {
				var theDay = thecal[j][i];
				strDay = theDay;
				if(theDay < 10 && theDay > 0)
				{
					strDay = "0" + theDay;
				}

				if((this.month+1) < 10)
				{
					strMon = "0" + (this.month+1);
				}
				else
				{
					strMon = this.month+1;
				}

				var dstring = strDay + this.dateSeparator + strMon + this.dateSeparator + this.year;
				var dstring2 =  strMon+ "/" + strDay + "/" + this.year;

 			//	var theStamp = Date.parse(dstring2)/1000;
 				var theStamp = strtotime(dstring2);
 				var dateString =  date(this.dateFormat,theStamp);
				 //dstring = strMon + "/" + strDay + "/" + this.year;
				if(theDay > 0 && theDay <= this.daysInMonth)
				{
					if(this.currmonth == this.month && this.curryear == this.year && this.currday == theDay)
					{
						theHtml += "<td class = \"today\" onclick = \"document.getElementById('"+this.relateTo+"').value='"+dateString+"';Velocity(document.getElementById('"+theDiv+"'), 'fadeOut', {duration: 500});\">"+ theDay + "</td>";
					}
					else if(this.month == (selectedVals[1]-1) && this.year == selectedVals[2] && selectedVals[0] == theDay)
					{
						theHtml += "<td class = \"red\" onclick = \"document.getElementById('"+this.relateTo+"').value='"+dateString+"';Velocity(document.getElementById('"+theDiv+"'), 'fadeOut', {duration: 500});\">"+ theDay + "</td>";
					}
					else
					{
						theHtml += "<td class = \"normalday\" onclick = \"document.getElementById('"+this.relateTo+"').value='"+dateString+"';Velocity(document.getElementById('"+theDiv+"'), 'fadeOut', {duration: 500});\">"+ theDay + "</td>";
					}
				}
				else if(theDay < 1)
				{
					theHtml += "<td class = \"wrong\">"+(this.daysLastMonth+theDay)+"</td>";
				}
				else if(theDay > this.daysInMonth)
				{
					theHtml += "<td class = \"wrong\">"+(theDay-this.daysInMonth)+"</td>";
				}
			}
		theHtml += "</tr>";
	}

	theHtml += "<tr><td colspan = \"7\" class = \"dpfoot\"><a href = \"javascript:void(0);\" onclick = \"Velocity(document.getElementById('"+theDiv+"'), 'fadeOut', {duration: 600});\">Close</a></td></tr></table>";

	document.getElementById(theDiv).innerHTML = theHtml;

	var theMonths = this.monthNames
	var theDays = this.dayNames;
	var keepEmpty = this.keepEmpty;
	var dateSeparator = this.dateSeparator;
	var theYear = this.year;
	var theRelate = this.relateTo;
	var theDateFormat = this.dateFormat;


    var backToggle = document.querySelector("#" + theDiv + " .cal .back a");
    backToggle.addEventListener("click",function()
        {
            var internalCal = new calendar(pmonth,theYear);
            internalCal.monthNames = theMonths;
            internalCal.dayNames = theDays;
            internalCal.keepEmpty = keepEmpty;
            internalCal.relateTo = theRelate;
            internalCal.dateSeparator = dateSeparator;
            internalCal.dateFormat = theDateFormat;
            internalCal.getDatepicker(theDiv);

        }
    );

    var nextToggle = document.querySelector("#" + theDiv + " .cal .next a");
    nextToggle.addEventListener("click",  function()
        {
            var internalCal = new calendar(nmonth,theYear);
            internalCal.monthNames = theMonths;
            internalCal.dayNames = theDays;
            internalCal.keepEmpty = keepEmpty;
            internalCal.relateTo = theRelate;
            internalCal.dateSeparator = dateSeparator;
            internalCal.dateFormat = theDateFormat;
            internalCal.getDatepicker(theDiv);
        }
    );

}

calendar.prototype.showDatepicker = function()
{

}

calendar.prototype.buildCal = function()
{
	var counter = 0;
	for(j=0;j<this.weeksInMonth;j++) {
			this.calendar[j] = [];
			for(i=0;i<7;i++) {
				counter++;
				var theday = counter-this.startDay;
				this.calendar[j][i] = theday;
		}
	}

	return this.calendar;
}

calendar.prototype.getDaysInMonth = function(intMonth, intYear)
{
	var dteMonth = new Date(intYear,intMonth);
	var intDaysInMonth = 28;
	var blnDateFound = false;

	while (!blnDateFound)
	{
		dteMonth.setDate(intDaysInMonth+1);
		var intNewMonth = dteMonth.getMonth();

		if (intNewMonth != intMonth)
		{
		  blnDateFound = true;
		}
		else
		{
		  intDaysInMonth++;
		}
	}

	return intDaysInMonth;
}

