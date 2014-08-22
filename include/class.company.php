<?php

/**
* Class to provide methods for handling customer companies
*
* @author Philipp Kiszka
* @name project
* @package Collabtive
* @version 2.0
* @link http://www.o-dyn.de
* @license http://opensource.org/licenses/gpl-license.php GNU General Public License v3 or later
*/
class company {
    private $mylog;

    /**
    * Constructor
    * Initializes the event log
    */
    function __construct()
    {
        $this->mylog = new mylog;
    }

    /**
    * Add a company
    *
    * @param array $data
    * @return int $insid ID of the inserted company
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
    * Edit a company
    *
    * @param array $data Company data
    * @return bool
    */
    function edit($data)
    {
        global $conn;

        $id = (int) $data['id'];

        $updStmt = $conn->prepare("UPDATE company SET `company`=?, `contact`=?, `email`=?, `phone`=?, `mobile`=?, `url`=?, `address`=?, `zip`=?, `city`=?, `country`=?, `state`=?, `desc`=? WHERE ID = ?");
        $upd = $updStmt->execute(array($data['company'], $data['contact'], $data['email'], $data['phone'], $data['mobile'], $data['url'], $data['address'], $data['zip'], $data['city'], $data['country'], $data['state'], $data['desc'], $id));

        if ($upd) {
            return true;
        } else {
            return false;
        }
    }

    /**
    * Delete a company and disconnect all assigned projects
    *
    * @param int $id Company ID
    * @return bool
    */
    function del($id)
    {
        global $conn;

        $id = (int) $id;

        $del_assigns = $conn->query("DELETE FROM company_assigned WHERE customer = $id");
        $del = $conn->query("DELETE FROM customer WHERE ID = $id");

        if ($del) {
            return true;
        } else {
            return false;
        }
    }

    /**
    * Assign a company to a project
    *
    * @param int $task Company ID
    * @param int $id project ID
    * @return bool
    */
    function assign($company, $id)
    {
        global $conn;

        $company = (int) $company;
        $id = (int) $id;

        $upd = $conn->query("INSERT INTO customers_assigned (customer, project) VALUES ($company, $id)");

        if ($upd) {
            return true;
        } else {
            return false;
        }
    }

    /**
    * Disconnect a company from a user
    *
    * @param int $task Company ID
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
    * Get a company
    *
    * @param int $id Company ID
    * @return array $company Company
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

    function getProjectCompany($project)
    {
    	global $conn;

        $project = (int) $project;
        $sel = $conn->prepare("SELECT customer FROM customers_assigned WHERE project = ?");
        $selStmt = $sel->execute(array($project));

        $companyId = $sel->fetch();

		$company = $this->getCompany($companyId);

		if (!empty($company)) {
            return $company;
        } else {
            return false;
        }
    }
    /**
    * Get a list of companies
    *
    * @param int $lim Maximum number of companies to return (default: 10)
    * @return array $companies List of companies
    */
    function getCompanies($lim = 10)
    {
        global $conn;

        $lim = (int) $lim;

        $sel = $conn->prepare("SELECT * FROM company ORDER BY `company` ASC LIMIT $lim");
        $selStmt = $sel->execute();

        $companies = $sel->fetchAll();

        if (!empty($companies)) {
            return $companies;
        } else {
            return false;
        }
    }

    /**
    * Get a list of all companies
    *
    * @return array $companies List of all companies
    */
    function getAllCompanies()
    {
        global $conn;

        $sel = $conn->query("SELECT * FROM company");
        $companies = array();

        while ($company = $sel->fetch()) {
            array_push($companies, $company);
        }

        if (!empty($companies)) {
            return $companies;
        }else {
            return false;
        }
    }

    /**
    * Get a company including all of its members
    *
    * @param int $id Company ID
    * @return array $company Company including all of its members
    */
    function getCompanyMembers($id)
    {
        global $conn;

        $id = (int) $id;

        $sel = $conn->query("SELECT user, company FROM company_assigned WHERE company = $id");

        $staff = array();
        $userobj = (object) new user();
        $company = $this->getProfile($member[1]);

        while ($member = $sel->fetch()) {
            $user = $userobj->getProfile($member[0]);
            array_push($staff, $user);
        }

        $company["staff"] = $staff;

        if (!empty($company)) {
            return $company;
        }else {
            return false;
        }
    }
}

?>
