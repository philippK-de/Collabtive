<?php
/*
 * Converts associative arrays to XML and JSON using PHPs XMLWriter
 *
 * @author Open Dynamics <info@o-dyn.de>
 * @name toXml
 * @private $writer PHP XML Writer object
 * @version 0.4.8
 * @package Collabtive
 * @link http://www.o-dyn.de
 * @license http://opensource.org/licenses/gpl-license.php GNU General Public License v3 or later
 */
class toXml
{
	private $writer;

	function __construct()
	{
		//create XMLWriter Object
		$this->writer = (object) new XMLWriter();
	}

	/**
    * Convert array to complete XML document.
    * Accepts an associative array.
    * Also accepts a numeric and associative indexed array (as for example returned by mysql_fetch_array()),
    * but strips the numeric index from those.
    *
    * @param array $inarr Array to convert
    * @param string $rootname Name of the root XML element
    * @return string Array as XML
    */
	public function arrToXml(array $inarr, $rootname)
	{
		$writer = $this->writer;
		//$writer->openURI('php://output');
		$writer->openMemory();
		//start a new document
		$writer->startDocument("1.0","utf-8","yes");
		$writer->setIndent(true);
		//create the root element
		$writer->startElement($rootname);
	//	$writer->writeAttribute("count",count($inarr));

		//convert the array recursively
		$this->convertit($inarr);

		//end the root element
		$writer->endElement();
		//close the document
		$writer->endDocument();

		//return the XML as string
		return $writer->outputMemory();
	}

	/**
    * Convert array to JSON.
    * Accepts an associative array.
    * Also accepts a numeric and associative indexed array (as for example returned by mysql_fetch_array())
	* This is a wrapper around PHPs json_encode()
    *
    * @param array $inarr Array to convert
    * @return string Array as JSON
    */
	public function arrToJSON(array $inarr)
	{
		return json_encode($inarr);
	}

    /**
    * Private function that recursively converts an Array to XML.
    * Used by arrToXml()
    * Accepts an associative array.
    * Also accepts a numeric and associative indexed array (as for example returned by mysql_fetch_array()),
    * but strips the numeric index from those.
    *
    * @param array $inarr Array to convert
    *
    * @return void
    */
	private function convertit(array $inarr)
	{
		$writer = $this->writer;
		foreach($inarr as $key => $val)
		{
			//if value is an array again, recursively call this function
			if(is_array($val))
			{
				//if key is numeric, convert it to a string
				if(is_numeric($key))
				{
					$numkey = $key;
					$key = "node";
					$writer->startElement($key);
					//$writer->writeAttribute("num",$numkey);
				}
				else
				{
					$writer->startElement($key);
				}
				//convert $val recursively

				$this->convertit($val);
				$writer->endElement();
			}
			else
			{
				if(!is_numeric($key))
				{
					$writer->writeElement($key,strip_tags($val));
				}

			}

		}
	}


}
?>