<?php

abstract class databaseModel
{
    protected $databaseTable;


    protected function addElement(array $fields)
    {
        global $conn;
        $sqlQuery = "INSERT INTO " . $this->databaseTable . " (";
        $fieldcount = count($fields);

        foreach ($fields as $name => $value) {
            $sqlQuery .= $name . ",";
        }
        //remove superflous comma
        $sqlQuery = substr($sqlQuery, 0, strlen($sqlQuery) - 1);
        $sqlQuery .= ") VALUES (";

        for ($i = 0; $i < $fieldcount; $i++) {
            $sqlQuery .= "?,";
        }

        //remove comma
        $sqlQuery = substr($sqlQuery, 0, strlen($sqlQuery) - 1);
        $sqlQuery .= ")";
        //execute the query
        $stmt = $conn->prepare($sqlQuery);
        $stmt->execute(array_values($fields));

        //return the ID for the element
        return $conn->lastInsertId();
    }

    protected function editElement($id, $fields)
    {
        global $conn;

        $sqlQuery = "UPDATE " . $this->databaseTable . " SET ";

        foreach ($fields as $name => $value) {
            $sqlQuery .= $name . "=?,";
        }
        //remove comma
        $sqlQuery = substr($sqlQuery, 0, strlen($sqlQuery) - 1);
        $sqlQuery .= " WHERE ID = ?";

        $stmt = $conn->prepare($sqlQuery);

        $values = array_values($fields);
        array_push($values, $id);
        return $stmt->execute($values);
    }

    protected function getElement($id)
    {
        global $conn;

        $stmt = $conn->prepare("SELECT * FROM " . $this->databaseTable . " WHERE ID = ?");
        $stmt->execute(array($id));

        return $stmt->fetch();
    }

    protected function getLimited($limit, $offset)
    {
        global $conn;
        $limit = (int)$limit;
        $offset = (int)$offset;

        $stmt = $conn->prepare("SELECT * FROM " . $this->databaseTable . " LIMIT $limit OFFSET $offset");
        $stmt->execute();

        return $stmt->fetchAll();
    }

    protected function getAllElements()
    {
        global $conn;

        $stmt = $conn->prepare("SELECT * FROM " . $this->databaseTable);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    protected function getElementsBy(array $parameters)
    {
        global $conn;
        $sqlQuery = "SELECT * FROM " . $this->databaseTable . " WHERE ";

        $i = 0;
        foreach ($parameters as $name => $value) {
            if ($i > 0) {
                $sqlQuery .= " AND ";
            }
            $sqlQuery .= $name . " = ?";

            $i++;
        }
        $stmt = $conn->prepare($sqlQuery);
        $stmt->execute(array_values($parameters));

        return $stmt->fetchAll();
    }

    protected function countElements()
    {
        global $conn;

        $countStmt = $conn->prepare("SELECT COUNT(*) FROM " . $this->databaseTable);
        $countStmt->execute();

        $count = $countStmt->fetch();
        $count = $count["COUNT(*)"];

        return $count;
    }

    protected function deleteElement($id)
    {
        global $conn;
        $id = (int)$id;

        $delStmt = $conn->prepare("DELETE FROM deployments WHERE ID = ? LIMIT 1");

        return $delStmt->execute(array($id));
    }
}