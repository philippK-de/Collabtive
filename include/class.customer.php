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
class customer {
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

        $ins1Stmt = $conn->prepare("INSERT INTO customer (`company`, `contact`, `email`, `phone`, `mobile`, `url`, `address`, `zip`, `city`, `country`, `state`, `desc`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
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

        $updStmt = $conn->prepare("UPDATE customer SET `company`=?, `contact`=?, `email`=?, `phone`=?, `mobile`=?, `url`=?, `address`=?, `zip`=?, `city`=?, `country`=?, `state`=?, `desc`=? WHERE ID = ?");
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

        $del_assigns = $conn->query("DELETE FROM customer_assigned WHERE customer = $id");
        $del = $conn->query("DELETE FROM customer WHERE ID = $id");

        if ($del) {
            // $this->mylog->add($userid, 'customer', 3, $id);
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
    function getCustomer($id)
    {
        global $conn;
        $id = (int) $id;

        $sel = $conn->prepare("SELECT * FROM customer WHERE ID = ?");
        $selStmt = $sel->execute(array($id));

        $customer = $sel->fetch();

        if (!empty($customer)) {
            /* $project["name"] = stripslashes($project["name"]);
            $project["desc"] = stripslashes($project["desc"]);
            $project["done"] = $this->getProgress($project["ID"]);
			*/
            return $customer;
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
    function getCustomers($lim = 10)
    {
        global $conn;

        $lim = (int) $lim;

        $sel = $conn->prepare("SELECT * FROM customer ORDER BY `company` ASC LIMIT $lim");
        $selStmt = $sel->execute();

        $customers = $sel->fetchAll();

        /* while ($customer = $sel->fetch()) {
            $customer = $this->getProject($customer["ID"]);
            array_push($customers, $customer);
        }*/

        if (!empty($customers)) {
            return $customers;
        } else {
            return false;
        }
    }
}

?>