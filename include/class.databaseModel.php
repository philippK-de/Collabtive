<?php

abstract class databaseModel
{
    protected $databaseTable;
    protected $databaseConnection;

    protected function addElement(array $fields)
    {
        $sqlQuery = "INSERT INTO " . $this->databaseTable . " (";
        $fieldcount = count($fields);

        $keys = array_keys($fields);
        foreach ($keys as $key) {
            $sqlQuery .= "`" . $key . "`,";
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
        $stmt = $this->databaseConnection->prepare($sqlQuery);

        $values = array_values($fields);
        $stmt->execute($values);
        //return the ID for the element
        return $this->databaseConnection->lastInsertId();
    }

    protected function editElement($id, $fields)
    {
        $sqlQuery = "UPDATE " . $this->databaseTable . " SET ";

        foreach ($fields as $name => $value) {
            $sqlQuery .= $name . "=?,";
        }
        //remove comma
        $sqlQuery = substr($sqlQuery, 0, strlen($sqlQuery) - 1);
        $sqlQuery .= " WHERE ID = ?";

        $stmt = $this->databaseConnection->prepare($sqlQuery);

        $values = array_values($fields);
        array_push($values, $id);
        return $stmt->execute($values);
    }

    protected function getElement($id)
    {
        $stmt = $this->databaseConnection->prepare("SELECT * FROM " . $this->databaseTable . " WHERE ID = ?");
        $stmt->execute(array($id));

        return $stmt->fetch();
    }

    protected function getLimited($limit, $offset)
    {
        
        $limit = (int)$limit;
        $offset = (int)$offset;

        $stmt = $this->databaseConnection->prepare("SELECT * FROM " . $this->databaseTable . " LIMIT $limit OFFSET $offset");
        $stmt->execute();

        return $stmt->fetchAll();
    }

    protected function getAllElements()
    {
        $stmt = $this->databaseConnection->prepare("SELECT * FROM " . $this->databaseTable);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    protected function getElementsBy(array $parameters)
    {
        $sqlQuery = "SELECT * FROM " . $this->databaseTable . " WHERE ";

        $i = 0;
        foreach ($parameters as $name => $value) {
            if ($i > 0) {
                $sqlQuery .= " AND ";
            }
            $sqlQuery .= $name . " = ?";

            $i++;
        }
        $stmt = $this->databaseConnection->prepare($sqlQuery);
        $stmt->execute(array_values($parameters));

        return $stmt->fetchAll();
    }

    protected function countElements($field = "*")
    {
        $countStmt = $this->databaseConnection->prepare("SELECT COUNT(" . $field . ") FROM " . $this->databaseTable);
        $countStmt->execute();

        $count = $countStmt->fetch();
        $count = $count["COUNT(*)"];

        return $count;
    }

    protected function deleteElement($id)
    {
        $id = (int)$id;

        $delStmt = $this->databaseConnection->prepare("DELETE FROM " . $this->databaseTable . " WHERE ID = ? LIMIT 1");

        return $delStmt->execute(array($id));
    }
}