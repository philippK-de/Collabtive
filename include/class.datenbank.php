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
    * Establish a database connection
    *
    * @param string $db Database name
    * @param string $user Database user
    * @param string $pass Password for database access
    * @param string $host Database host
    * @return bool
    */
    function connect($db_name, $db_user, $db_pass, $db_host="localhost")
    {

	//mysql
	//$db = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);

	$conn = mysql_connect($db_host,$db_user,$db_pass);
        $db_check = mysql_select_db($db_name);
        if($db_check)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /*
     * Wrap mysql_query function
     *
     * @param string $str SQL search query
     * @return bool
     */
    function query($str)
    {
    	return mysql_query($str);
    }
}
?>