<?php
function starthtmldocument($handle){
	fwrite($handle, '<html>');
	fwrite($handle, '  <head>');
	fwrite($handle, css());
	fwrite($handle, '  </head>');
	fwrite($handle, '  <body>');
}

$classes=null;

function starthtmltable($handle,$columns){
	global $classes;
	fwrite($handle, '<a href="http://kommune10.dyndns.info:815/PMS/managetimetracker.php?action=projecthtml&id=2">reload</a>');
	
	fwrite($handle, '    <table>');
	fwrite($handle, '      <tr>');
	$classes=array();
	foreach ($columns as $column){
		$class=strtolower(str_replace(" ", "_", $column));
	  $classes[]=$class;
	  fwrite($handle, '      <th class="'.$class.'">'.$column.'</th>');
	}
	
	fwrite($handle, '      </tr>');	
}

$odd_row=true;

function addtohtmltable($handle,$columns){
	global $odd_row, $classes;
	if ($odd_row){	
	  fwrite($handle, '      <tr class="odd">');
	} else fwrite($handle, '      <tr class="even">');
	$odd_row=!$odd_row;
	
	for ($index=0; $index<count($columns);$index++){	
		fwrite($handle, '      <td class="'.$classes[$index].'">'.$columns[$index].'</td>');
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

function css(){
	return '<style type="text/css">
			*{
			  font-size: 11px;
			}
			.stunden{
				max-width: 40px;
				overflow: hidden;
			}
			.begonnen,
			.beendet{
				max-width: 40px;
				overflow: hidden;
			}
			.odd{
				background-color: #ddd;
			}
			table{
			  border-collapse:collapse
			}
			td{
			  border: 1px solid black;
			}
			</style>';
}
