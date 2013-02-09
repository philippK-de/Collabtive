<?php
/**
 * This class provides methods to realize a company.
 *
 * @author Open Dynamics / Philipp Kiszka <info@o-dyn.de>
 * @name company
 * @package Collabtive
 * @version 1.0
 * @link http://www.o-dyn.de
 * @license http://opensource.org/licenses/gpl-license.php GNU General Public License v3 or later
 */
class company
{
	/**
	 * Constructor
	 *
	 * @access protected
	 */
	function company()
	{
	}

	/**
	 * Add a new company
	 *
	 * @param string $name Name of the company
	 * @param string $email Email-address
	 * @param string $phone Phonenumber
	 * @param string $address1 Main address
	 * @param string $address2 Second address
	 * @param string $state State
	 * @param string $country Country
	 * @param string $logo Company's logo
	 * @return bool
	 */
	function add($name, $email, $phone, $address1, $address2, $state, $country, $logo)
	{
				global $conn;

        $ins1Stmt = $conn->prepare("INSERT INTO company (ID, name, email, phone, address1, address2, state, country, logo) VALUES ('', ?, ?, ?, ?, ?, ?, ?, ? )");
				$ins1 = $ins1Stmt->execute(array($name, $email, $phone, $address1, $address2, $state, $country, $logo));

		if ($ins1)
		{
			$this->mylog->add('company' , 1);
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Edit a company
	 *
	 * @param int $id Company ID
	 * @param string $name Company's name
	 * @param string $email Email address
	 * @param string $phone Telephone number
	 * @param string $address1 Main address
	 * @param string $address2 Second address
	 * @param string $state State
	 * @param string $country Country
	 * @param string $logo Company's logo
	 * @return bool
	 */
	function edit($id, $name, $email, $phone, $address1, $address2, $state, $country, $logo)
	{
				global $conn;

        $updStmt = $conn->prepare("UPDATE company SET name=?, email=?, phone=?, address1=?, address2=?, state=?, country=?, logo=? WHERE ID = ?");
				$upd = $updStmt->execute(array($name, $email, $phone, $address1, $address2, $state, $country, $logo, (int) $id));
		if ($upd)
		{
			$this->mylog->add('company' , 2);
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Delete a company
	 *
	 * @param int $id Company ID
	 * @return bool
	 */
	function del($id)
	{
		global $conn;
		$id = (int) $id;
        $del = $conn->query("DELETE FROM company WHERE ID = $id");
		if ($del)
		{
			$this->mylog->add('company', 3);
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Assign a user to a company
	 *
	 * @param int $company Company ID
	 * @param int $user User ID
	 * @return bool
	 */
	function assign($company, $user)
	{
		global $conn;
		$company = (int) $company;
		$user = (int) $user;

		$ins = $conn->query("UPDATE user SET company = $company WHERE ID = $id");
		if($ins)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Remove a user from a company
	 *
	 * @param int $company Company ID
	 * @param int $user User ID
	 * @return bool
	 */
	function deassign($company, $user)
	{
		global $conn;
		$company = (int) $company;
		$user = (int) $user;

		$ins = $conn->query("UPDATE user SET company = 0 WHERE ID = $id");
		if ($ins)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Return the profile of a company
	 *
	 * @param int $id Company ID
	 * @return bool
	 */
	function getProfile($id)
	{
				global $conn;
		$id = (int) $id;

        $profile = $conn->query("SELECT * FROM company WHERE ID = $id")->fetch();
		if (!empty($profile))
		{
			return $profile;
		}
		else
		{
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
	 * Get a user's company
	 *
	 * @param int $user User ID
	 * @return array $company The user's company
	 */
	function getUserCompany($user)
	{
		// @todo
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