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

  // get the start time seconds
	var startVal = 60*startHour+startMin;	

  // calculate the 'workminutes' from the work hours
	var minDiff = 60*parseInt($('workhours').value,10);

  // calculate the end time
  var endVal = startVal + minDiff;
	var endMin = zeroFill(endVal % 60,2);
	var endHour = zeroFill((endVal-endMin) / 60,2);
	
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
