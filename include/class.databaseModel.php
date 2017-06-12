<?php

class databaseModel
{
    protected $databaseTable;

    function __construct()
    {
    }

    function getElement($id)
    {
        global $conn;

        $stmt = $conn->prepare("SELECT * FROM " . $this->databaseTable . " WHERE ID = ?");
        $stmt->execute(array($id));

        return $stmt->fetch();
    }

    function getLimited($limit, $offset)
    {
        global $conn;
        $limit = (int)$limit;
        $offset = (int)$offset;

        $stmt = $conn->prepare("SELECT * FROM " . $this->databaseTable . " LIMIT $limit OFFSET $offset");
        $stmt->execute();

        return $stmt->fetchAll();
    }

    function getAllElements()
    {
        global $conn;

        $stmt = $conn->prepare("SELECT * FROM " . $this->databaseTable);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    function getAllElementsBy(array $parameters)
    {
        global $conn;
        $sqlQuery = "SELECT * FROM " . $this->databaseTable . " WHERE ";


        foreach ($parameters as $name => $value) {
            $sqlQuery += $name . " = ?";
        }

        echo $sqlQuery;
        $stmt = $conn->prepare($sqlQuery);
        $stmt->execute(array_values($parameters));

        return $stmt->fetchAll();
    }


    function getElementsBy(array $parameters, $limit, $offset)
    {
        $limit = (int) $limit;
        $offset = (int)$offset;

        global $conn;
        $sqlQuery = "SELECT * FROM " . $this->databaseTable . " WHERE ";


        foreach ($parameters as $name => $value) {
            $sqlQuery .= $name . " = ?";
        }


        $sqlQuery .= " LIMIT $limit OFFSET $offset";

        echo $sqlQuery;
        $stmt = $conn->prepare($sqlQuery);
        $stmt->execute(array_values($parameters));

        return $stmt->fetchAll();
    }

    function countElements()
    {
        global $conn;

        $countStmt = $conn->prepare("SELECT COUNT(*) FROM " . $this->databaseTable);
        $countStmt->execute();

        $count = $countStmt->fetch();
        $count = $count["COUNT(*)"];

        return $count;
    }

    function deleteElement($id)
    {
        global $conn;
        $id = (int)$id;

        $delStmt = $conn->prepare("DELETE FROM deployments WHERE ID = ? LIMIT 1");

        return $delStmt->execute(array($id));
    }
}