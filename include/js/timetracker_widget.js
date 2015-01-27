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
	var startVal = parseInt($('started').value);
	var hrsVal = parseInt($('workhours').value);

	var finVal = startVal + hrsVal;

	console.log(finVal);

	finVal = zeroFill(finVal,2);
	$('ended').value = zeroFill(finVal,2) + ":00";
}
function populateHours()
{
	var startVal = parseInt($('started').value);
	var endVal = parseInt($('ended').value);
	var hrsVal = parseInt($('workhours').value);

	var finVal = endVal - startVal;
	if(hrsVal != finVal)
	{
		$('workhours').value = finVal;
	}
	console.log(finVal);
}