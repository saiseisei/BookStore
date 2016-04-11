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
    
    public function getBookCategorys() {
        $sql = 'SELECT * FROM bookstoredb.category;';
        $resultSet = $this->tableGateway->getAdapter()->query($sql)->execute();
        return $resultSet;
    }

    public function getCategory($id) {
        $row = array();
        $category = array();
        
        $sql = 'SELECT * FROM bookstoredb.category WHERE CATEGORYID = :id AND DELFLAG = 0;';
        $param = array(':id' => $id);
        $result = $this->tableGateway->getAdapter()->query($sql);
        $rows = $result->execute($param);

        if (count($rows) > 0) {
            $row = $rows->current();
            $category['CATEGORYID'] = $row['CATEGORYID'];
            $category['CATEGORY'] = $row["CATEGORY"];
            $category['COMMENT'] = $row["COMMENT"];
            return $category;
        } else {
            throw new \Exception("Could not find $id");
        }
    }

}
