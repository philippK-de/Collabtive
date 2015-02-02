function zeroFill( number, width )
{
	width -= number.toString().length;
	if ( width > 0 )
  	{
    	return new Array( width + (/\./.test( number ) ? 2 : 1) ).join( '0' ) + number;
	}
	return number + ""; // always return a string
}

function populateEndtime()
{
	var startTime=$('started').value;
	var parts=startTime.split(':');
	var startHour=parseInt(parts[0]);
	var startMin=parseInt(parts[1]);

  // get the start date. used to calculate the end date later
  var startDateString=$('ttstartday').value;
  var startDateParts=startDateString.split('.');
  var startDate=new Date(startDateParts[2],startDateParts[1],startDateParts[0]);

  // get the start time seconds
	var startVal = 60*startHour+startMin;	

  // calculate the 'workminutes' from the work hours
	var minDiff = 60*parseInt($('workhours').value,10);

  // calculate the end time
  var endVal = startVal + minDiff;
	var endMin = zeroFill(endVal % 60,2);
	var endHour = zeroFill((endVal-endMin) / 60,2);
	
  // initialize the end date
  var endDateStamp=Date.parse(startDate);

  // if our timesheet exceeds the 11:59pm barrier, add a day
  if (endHour>23){
    var oneday=24*60*60*1000;
    while (endHour > 23){
      endHour-=24;
      endDateStamp+=oneday;
    }
  }

  // create the end date
  var endDate=new Date(endDateStamp);
  var endDay = zeroFill(endDate.getDate(),2);
  var endMonth= zeroFill(endDate.getMonth(),2);
  var endYear = endDate.getFullYear();

  // set end date and end time
  $('ttendday').value = endDay + "." + endMonth+"."+endYear;
	$('ended').value    = endHour + ":" + endMin;
}
function populateHours()
{
	var startVal = parseInt($('started').value,10);
	var endVal = parseInt($('ended').value,10);
	var hrsVal = parseInt($('workhours').value,10);

	var finVal = endVal - startVal;
	if(hrsVal != finVal && finVal >= 0)
	{
		$('workhours').value = finVal;
	}
}
