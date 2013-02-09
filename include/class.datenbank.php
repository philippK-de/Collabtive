<?php

/*
* The class datenbank (database) provides methods to handle a database connection
*
* @author Open Dynamics <info@o-dyn.de>
* @name datenbank
* @version 0.4.6
* @package Collabtive
* @link http://www.o-dyn.de
* @license http://opensource.org/licenses/gpl-license.php GNU General Public License v3 or later
*/
class datenbank
{

    /*
     * Constructor
     */
    function __construct()
    {
    }

    /*
     * Wrap mysql_query function
     *
     * @param string $str SQL search query
     * @return bool
     */
    function query($str)
    {
			global $conn;
    	return $conn->query($str);
    }
}
?>