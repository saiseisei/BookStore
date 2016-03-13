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
        $row = array();
        $category = array();
        $sql = 'SELECT * FROM bookstoredb.category WHERE CATEGORYID = :category AND DELFLAG = :delflag;';
        $param = array(':category' => $id, ':delflag' => 0);
        $result = $this->tableGateway->getAdapter()->query($sql);
        $rows = $result->execute($param);

        if (count($rows) > 0) {
            $row = $rows->current();
            $category['id'] = $row['CATEGORYID'];
            $category['category'] = $row["CATEGORY"];
            $category['comment'] = $row["COMMENT"];
            return $category;
        } else {
            throw new \Exception("Could not find $id");
        }
    }

}
