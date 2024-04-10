<?php

namespace App\Framework;

use App\Framework\Exception\PropertyNotFoundException;
use PDO;

class BaseManager
{
    private $_table;
    private $_object;
    protected $_bdd;

    public function __construct($table, $object, $datasource)
    {
        $this->_table = $table;
        $this->_object = $object;
        $this->_bdd = BDD::getInstance($datasource);
    }

    public function getById($id)
    {
        $req = $this->_bdd->prepare("SELECT * FROM " . $this->_table . " WHERE id=?");
        $req->execute(array($id));
        $req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'App\\Model\\Entity\\' . $this->_object);
        return $req->fetch();
    }

    public function getAll(): false|array
    {
        $req = $this->_bdd->prepare("SELECT * FROM " . $this->_table);
        $req->execute();
        $req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'App\\Model\\Entity\\' . $this->_object);
        return $req->fetchAll();
    }

    /**
     * @throws PropertyNotFoundException
     */
    public function create($obj, $param): void
    {
        $paramNumber = count($param);
        $valueArray = array_fill(1, $paramNumber, "?");
        $valueString = implode(", ", $valueArray,);
        $sql = "INSERT INTO " . $this->_table . "(" . implode(", ", $param) . ") VALUES(" . $valueString . ")";
        $req = $this->_bdd->prepare($sql);
        $boundParam = array();
        foreach ($param as $paramName) {
            if (property_exists($obj, $paramName)) {
                $methodName = "get" . $paramName;
                $boundParam[$paramName] = $obj->$methodName();
            } else {
                throw new PropertyNotFoundException($this->_object, $paramName);
            }
        }
        $req->execute($boundParam);
    }

    /**
     * @throws PropertyNotFoundException
     */
    public function update($obj, $param): void
    {
        $sql = "UPDATE " . $this->_table . " SET ";
        foreach ($param as $paramName) {
            $sql = $sql . $paramName . " = ?, ";
        }
        $sql = $sql . " WHERE id = ? ";
        $req = $this->_bdd->prepare($sql);

        $param[] = 'id';
        $boundParam = array();
        foreach ($param as $paramName) {
            if (property_exists($obj, $paramName)) {
                $boundParam[$paramName] = $obj->$paramName;
            } else {
                throw new PropertyNotFoundException($this->_object, $paramName);
            }
        }

        $req->execute($boundParam);
    }

    /**
     * @throws PropertyNotFoundException
     */
    public function delete($obj): bool
    {
        if (property_exists($obj, "id")) {
            $req = $this->_bdd->prepare("DELETE FROM " . $this->_table . " WHERE id=?");
            return $req->execute(array($obj->id));
        } else {
            throw new PropertyNotFoundException($obj, "id");
        }
    }
}