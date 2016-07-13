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
	var startVal = parseInt(cssId('started').value,10);
	var hrsVal = parseInt(cssId('workhours').value,10);

	var finVal = startVal + hrsVal;

	finVal = zeroFill(finVal,2);
	cssId('ended').value = finVal + ":00";
}
function populateHours()
{
	var startVal = parseInt(cssId('started').value,10);
	var endVal = parseInt(cssId('ended').value,10);
	var hrsVal = parseInt(cssId('workhours').value,10);

	var finVal = endVal - startVal;
	if(hrsVal != finVal && finVal >= 0)
	{
		cssId('workhours').value = finVal;
	}
}