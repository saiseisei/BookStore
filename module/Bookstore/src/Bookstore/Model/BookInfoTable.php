<?php

namespace Bookstore\Model;

use Zend\Db\TableGateway\TableGateway;

class BookInfoTable {

    protected $tableGateway;
    protected $table = 'bookinfo';

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll() {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getBook($isbn) {
        $rowset = $this->tableGateway->select(array('isbn' => $isbn));
        $row = $rowset->current();
//        if (!$row) {
//            throw new \Exception("Could not find $isbn");
//        }
        return $row;
    }

    public function addBook($bookInfo) {
        $book = array(
            'isbn' => $bookInfo->isbn,
            'title' => $bookInfo->title,
            'price' => $bookInfo->price,
        );
        if ($this->getBook($bookInfo->isbn)) {
            throw new \Exception('The book has been existed! Try again!');
        }
        $this->tableGateway->insert($book);
        
    }

    public function deleteUser(BookInfo $bookInfo) {
        $book = array(
            'isbn' => $bookInfo->isbn,
            'title' => $bookInfo->title,
            'price' => $bookInfo->price,
        );

        if ($this->getBook($book->isbn)) {
            throw new \Exception('The book doesn\'t exist! ');
        } else {
            $this->tableGateway->delete(array('isbn' => $book->isbn));
        }
    }

    /* public function setDbAdapter(Adapter $adapter)
      {
      $resultSetPrototype = new ResultSet();
      $resultSetPrototype->setArrayObjectPrototype(new UserInfo());
      $this->tableGateway = new TableGateway('userinfo', $adapter, null, $resultSetPrototype);
      } */
}
