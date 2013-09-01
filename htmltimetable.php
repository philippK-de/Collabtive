<?php
function starthtmldocument($handle){
	fwrite($handle, '<html>');
	fwrite($handle, '  <head>');
	fwrite($handle, '  </head>');
	fwrite($handle, '  <body>');
}

function starthtmltable($handle,$columns){
	fwrite($handle, '    <table>');
	fwrite($handle, '      <tr>');
	
	foreach ($columns as $colum){
	  fwrite($handle, '      <th>$column</th>');
	}
	
	fwrite($handle, '      </tr>');	
}

$odd_row=true;

function addtohtmltable($handle,$columns){
	global $odd_row;
	if (odd_row){	
	  fwrite($handle, '      <tr clas="odd">');
	} else fwrite($handle, '      <tr clas="even">');
	$odd_row=!$odd_row;
	
	foreach ($columns as $colum){
		fwrite($handle, '      <td>$column</td>');
	}
	
	fwrite($handle, '      </tr>');
	
}

function closehtmltable($handle){
	fwrite($handle, '    </table>');	
}

function closehtmldocument($handle){
	fwrite($handle, '  </body>');
	fwrite($handle, '</html>');
}
