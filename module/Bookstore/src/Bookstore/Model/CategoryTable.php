<?php

namespace Bookstore\Model;

use Zend\Db\TableGateway\TableGateway;

class CategoryTable {

    protected $tableGateway;
    protected $table = 'category';

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll() {
        $sql = 'SELECT * FROM bookstoredb.category;';
        $resultSet = $this->tableGateway->getAdapter()->query($sql)->execute();  
        return $resultSet;
    }

    public function getCategory($id) {
        $rowset = $this->tableGateway->select(array('categoryid' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find $id");
        }
        return $row;
    }

}
