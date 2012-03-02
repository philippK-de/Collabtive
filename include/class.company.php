<?php
/*
* This class provides methods to realize a company
*
* @author Open Dynamics <info@o-dyn.de>
* @name company
* @version 0.4
* @package Collabtive
* @link http://www.o-dyn.de
* @license http://opensource.org/licenses/gpl-license.php GNU General Public License v3 or later
*/

class company
{
    /*
    * Constructor
    * Initialize the event log
    */
    function company()
    {
    }

    /*
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
        $name = mysql_real_escape_string($name);
        $email = mysql_real_escape_string($email);
        $phone = mysql_real_escape_string($phone);
        $address1 = mysql_real_escape_string($address1);
        $address2 = mysql_real_escape_string($address2);
        $state = mysql_real_escape_string($state);
        $country = mysql_real_escape_string($country);
        $logo = mysql_real_escape_string($logo);

        $ins1 = mysql_query("INSERT INTO company (ID, name, email, phone, address1, address2, state, country, logo) VALUES ('', '$name', '$email', '$phone', '$address1', '$address2', '$state', '$country', '$logo')");

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

    /*
    * Edit a company
    *
    * @param int $id Company ID
    * @param string $name Company's name
    * @param string $email Emal address
    * @param string $address1 Main address
    * @param string $address2 Second address
    * @param string $state State
    * @param string $country Country
    * @param string $logo Company's logo
    * @return bool
    */
    function edit($id, $name, $email, $phone, $address1, $address2, $state, $country, $logo)
    {
        $id = (int) $id;
        $name = mysql_real_escape_string($name);
        $email = mysql_real_escape_string($email);
        $phone = mysql_real_escape_string($phone);
        $address1 = mysql_real_escape_string($address1);
        $address2 = mysql_real_escape_string($address2);
        $state = mysql_real_escape_string($state);
        $country = mysql_real_escape_string($country);
        $logo = mysql_real_escape_string($logo);

        $upd = mysql_query("UPDATE company SET name='$name', email='$email', phone='$phone', address1='$address1', address2='$address2', state='$state', country='$country', logo='$logo' WHERE ID = $id");
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

    /*
    * Delete a company
    *
    * @param int $id Company ID
    * @return bool
    */
    function del($id)
    {
        $id = (int) $id;
        $del = mysql_query("DELETE FROM company WHERE ID = $id");
        if ($del)
        {
            $this->mylog->add('company' , 3);
            return true;
        }
        else
        {
            return false;
        }
    }

	/*
    * Assign a user to a company
    *
    * @param int company Company ID
    * @param int user User ID
    * @param int $id Company ID
    * @return bool
    */
	function assign($company,$user)
	{
		$company = (int) $company;
		$user = (int) $user;
		
		$ins = mysql_query("UPDATE user SET company=$company WHERE ID = $id");
		if($ins)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	/*
    * Remove a user from a company
    *
    * @param int company Company ID
    * @param int user User ID
    * @param int $id Company ID
    * @return bool
    */
	function deassign($company,$user)
	{
		$company = (int) $company;
		$user = (int) $user;
		
		$ins = mysql_query("UPDATE user SET company=0 WHERE ID = $id");
		if($ins)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
    /*
    * Return the profile of a company
    *
    * @param int $id Company ID
    * @return bool
    */
    function getProfile($id)
    {
        $id = (int) $id;

        $sel = mysql_query("SELECT * FROM company WHERE ID = $id");
        $profile = mysql_fetch_array($sel);


        if (!empty($profile))
        {
            return $profile;
        }
        else
        {
            return false;
        }
    }

	function getAllCompanies()
	{
		$sel = mysql_query("SELECT * FROM company");
		$companies = array();
		
		while($company = mysql_fetch_array($sel))
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
	function getUserCompany($user)
	{
		
	}
	
    /*
    * Return all members of a company
    *
    * @param int $id Company ID
    * @return array $staff Members of the company
    */
    function getCompanyMembers($id)
    {
        $id = (int) $id;

        $sel = mysql_query("SELECT user,company FROM company_assigned WHERE company = $id");
        $staff = array();
        $userobj = (object) new user();
        $company = $this->getProfile($member[1]);
        while($member = mysql_fetch_row($sel))
        {
            $user = $userobj->getProfile($member[0]);
            array_push($staff,$user); 
        }
        #SELECT user.*,company.* FROM user,company,company_assigned WHERE company_assigned.ID = company.ID AND company_assigned.user = user.company HAVING user.ID = 1
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