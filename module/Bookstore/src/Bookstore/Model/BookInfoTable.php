<?php

namespace Bookstore\Model;

use Zend\Db\TableGateway\TableGateway;

class BookInfoTable {

    protected $tableGateway;
    protected $table = 'bookinfo';

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    //fetch all the books from the database
    public function fetchAll() {
        $sql = 'SELECT * FROM bookstoredb.bookinfo;';
        $resultSet = $this->tableGateway->getAdapter()->query($sql)->execute();  
        return $resultSet;
    }

    //get a book from the database by the book's isbn
    public function getBook($isbn) {
        $rowset = $this->tableGateway->select(array('isbn' => $isbn));
        $row = $rowset->current();
//        if (!$row) {
//            throw new \Exception("Could not find $isbn");
//        }
        return $row;
    }

    //add a book to the database
    public function addBook($bookInfo) {
        $book = array(
            'isbn' => $bookInfo->isbn,
            'title' => $bookInfo->title,
            'subtitle' => $bookInfo->subtitle,
            'writer' => $bookInfo->writer,
            'price' => $bookInfo->price,
            'category' => $bookInfo->category,
            'comment' => $bookInfo->comment
        );
        if ($this->getBook($bookInfo->isbn)) {
            throw new \Exception('The book has been existed! Try again!');
        }
        $this->tableGateway->insert($book);
    }

    //updata the book's information 
    public function editBook($bookInfo) {

//        $book = array(
//            'isbn' => $bookInfo['isbn'],
//            'title' => $bookInfo['title'],
//            'price' => $bookInfo['price'],
//        );
        $row = $this->getBook($bookInfo['isbn']);
        if (!$row) {
            throw new \Exception('The book doesn\'t exist! ');
        }
        if (($row->title == $bookInfo['title']) && ($row->price == $bookInfo['price'])) {
            throw new \Exception('The book is exist! Edit again!');
        }
        $this->tableGateway->update($bookInfo, array('isbn' => $bookInfo['isbn']));
    }

    //delete the book from the database
    public function deleteBook($bookInfo) {
//        $book = array(
//            'isbn' => $bookInfo->isbn,
//            'title' => $bookInfo->title,
//            'price' => $bookInfo->price,
//        );
        $row = $this->getBook($bookInfo->isbn);
        if (!$row) {
            throw new \Exception('The book doesn\'t exist!');
        }
        $this->tableGateway->delete(array('isbn' => $bookInfo->isbn));
    }

    /* public function setDbAdapter(Adapter $adapter)
      {
      $resultSetPrototype = new ResultSet();
      $resultSetPrototype->setArrayObjectPrototype(new UserInfo());
      $this->tableGateway = new TableGateway('userinfo', $adapter, null, $resultSetPrototype);
      } */
}
