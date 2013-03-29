<?php
/**
 * Die Klasse stellt Methoden bereit um Kunden zu bearbeiten
 *
 * @author Electric Solutions GbR <info@electric-solutions.de>
 * @author Philipp Kiszka
 * @name project
 * @package Collabtive
 * @version 1.0
 * @link http://www.o-dyn.de
 * @license http://opensource.org/licenses/gpl-license.php GNU General Public License v3 or later
 */
class company {
    private $mylog;

    /**
     * Konstruktor
     * Initialisiert den Eventlog
     */
    function __construct()
    {
        $this->mylog = new mylog;
    }

    /**
     * Add a customer
     *
     * @param array $data
     * @return int $insid ID of the insert customer
     */
    function add($data)
    {
        global $conn;

        $ins1Stmt = $conn->prepare("INSERT INTO company (`company`, `contact`, `email`, `phone`, `mobile`, `url`, `address`, `zip`, `city`, `country`, `state`, `desc`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
        $ins1 = $ins1Stmt->execute(array($data['company'], $data['contact'], $data['email'], $data['phone'], $data['mobile'], $data['url'], $data['address'], $data['zip'], $data['city'], $data['country'], $data['state'], $data['desc']));

        $insid = $conn->lastInsertId();
        if ($ins1) {
            return $insid;
        } else {
            return false;
        }
    }

    /**
     * Bearbeitet eines Kunden
     *
     * @param array $data all customer data
     * @return bool
     */
    function edit($data)
    {
        global $conn;
        $id = (int) $data['id'];

        $updStmt = $conn->prepare("UPDATE company SET `company`=?, `contact`=?, `email`=?, `phone`=?, `mobile`=?, `url`=?, `address`=?, `zip`=?, `city`=?, `country`=?, `state`=?, `desc`=? WHERE ID = ?");
        $upd = $updStmt->execute(array($data['company'], $data['contact'], $data['email'], $data['phone'], $data['mobile'], $data['url'], $data['address'], $data['zip'], $data['city'], $data['country'], $data['state'], $data['desc'], $id));

        if ($upd) {
            // $this->mylog->add($name, 'customer' , 2, $id);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Deletes a customer and disconnect every project which was assigned to this customer
     *
     * @param int $id customer ID
     * @return bool
     */
    function del($id)
    {
        global $conn;

        $id = (int) $id;

        $del_assigns = $conn->query("DELETE FROM company_assigned WHERE customer = $id");
        $del = $conn->query("DELETE FROM customer WHERE ID = $id");

        if ($del) {
            // $this->mylog->add($userid, 'customer', 3, $id);
            return true;
        } else {
            return false;
        }
    }

/**
     * Assign a company to a user
     *
     * @param int $task Task ID
     * @param int $id User ID
     * @return bool
     */
    function assign($company, $id)
    {
        global $conn;
        $company = (int) $company;
        $id = (int) $id;

        $upd = $conn->query("INSERT INTO company_assigned (user,company) VALUES ($id,$company)");
        if ($upd) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Delete the assignment of a company to a user
     *
     * @param int $task Task ID
     * @param int $id User ID
     * @return bool
     */
    function deassign($company, $id)
    {
        global $conn;
        $company = (int) $company;
        $id = (int) $id;

        $upd = $conn->query("DELETE FROM company_assigned WHERE user = $id AND company = $company");
        if ($upd) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Gibt alle Daten eines Kunden aus
     *
     * @param int $id Eindeutige Kundennummer
     * @return array $customer Kundendaten
     */
    function getCompany($id)
    {
        global $conn;
        $id = (int) $id;

        $sel = $conn->prepare("SELECT * FROM company WHERE ID = ?");
        $selStmt = $sel->execute(array($id));

        $company = $sel->fetch();

        if (!empty($company)) {
            return $company;
        } else {
            return false;
        }
    }

    /**
     * Listet alle Kunden auf
     *
     * @param int $lim Anzahl der anzuzeigenden Kunden
     * @return array $customers
     */
    function getCompanies($lim = 10)
    {
        global $conn;

        $lim = (int) $lim;

        $sel = $conn->prepare("SELECT * FROM company ORDER BY `company` ASC LIMIT $lim");
        $selStmt = $sel->execute();

        $customers = $sel->fetchAll();


        if (!empty($customers)) {
            return $customers;
        } else {
            return false;
        }
    }

	/**
	 * Get a list of all companies
	 *
	 * @return array $companies Array of all companies
	 */
	function getAllCompanies()
	{
		global $conn;
		$sel = $conn->query("SELECT * FROM company");
		$companies = array();

		while($company = $sel->fetch())
		{
			array_push($companies,$company);
		}

		if(!empty($companies))
		{
			return $companies;
		}
		else
		{
			return false;
		}
	}
    /**
	 * Get all members of a company
	 *
	 * @param int $id Company ID
	 * @return array $company Company including a list of all its members
	 */
	function getCompanyMembers($id)
	{
				global $conn;
		$id = (int) $id;

        $sel = $conn->query("SELECT user, company FROM company_assigned WHERE company = $id");
		$staff = array();
		$userobj = (object) new user();
		$company = $this->getProfile($member[1]);
        while($member = $sel->fetch())
		{
			$user = $userobj->getProfile($member[0]);
			array_push($staff,$user);
		}
		$company["staff"] = $staff;

		if (!empty($company))
		{
			return $company;
		}
		else
		{
			return false;
		}
	}
}

?>