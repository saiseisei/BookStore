<?php

namespace Bookstore\Model;

use Zend\Db\TableGateway\TableGateway;

class BookInfoTable {

    protected $tableGateway;
    protected $table = 'bookinfo';

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }
    
    //get a book from the database by the book's isbn
    public function existBook($isbn) {

        $sql = 'SELECT * FROM bookinfo WHERE ISBN = :isbn;';
        $id = array(':isbn' => $isbn);
        $resultSet = $this->tableGateway->getAdapter()->query($sql);
        $rows = $resultSet->execute($id);

        if (count($rows) > 0) {
            $existBookFlag = TRUE;
        } else {
            $existBookFlag = FALSE;
        }
        return $existBookFlag;
    }

    //fetch all the books from the database
    public function fetchAll() {

        $sql = 'SELECT * FROM bookstoredb.bookinfo;';
        $resultSet = $this->tableGateway->getAdapter()->query($sql)->execute();
        return $resultSet;
    }

    public function countBooksNum() {
        $sql = 'SELECT COUNT(NO) AS NUM FROM bookstoredb.bookinfo;';
        $rowsNum = $this->tableGateway->getAdapter()->query($sql)->execute();
        $nums = $rowsNum->current();
        $num = $nums['NUM'];
//\Zend\Debug\Debug::dump($num);die;
        return $num;
    }

    public function getBooksByPage($currentPage) {
        $startRecordNo = ($currentPage - 1) * RECORDS_IN_ONE_PAGE;
        $endRecordNo = $currentPage * RECORDS_IN_ONE_PAGE;

        $sql = 'SELECT * FROM bookstoredb.bookinfo WHERE NO > :startRecordNo AND NO <= :endRecordNo AND DELFLAG = 0;';
        $param = array(':startRecordNo' => $startRecordNo, ':endRecordNo' => $endRecordNo,);
        $resultSet = $this->tableGateway->getAdapter()->query($sql);
        $result = $resultSet->execute($param);

        $rows = array();
        if (count($result) > 0) {
            foreach ($result as $key => $value) {
               $rows[$key]['NO'] = $value['NO'];
               $rows[$key]['ISBN'] = $value['ISBN'];
               $rows[$key]['TITLE'] = $value['TITLE'];
               $rows[$key]['SUBTITLE'] = $value['SUBTITLE'];
               $rows[$key]['WRITER'] = $value['WRITER'];
               $rows[$key]['PRICE'] = $value['PRICE'];
               $rows[$key]['CATEGORY'] = $value['CATEGORY'];
               $rows[$key]['COMMENT'] = $value['COMMENT'];
            }
        } else {
            throw new \Exception("Could not find a book!");
        }
        return $rows;
    }

    //get a book from the database by the book's isbn
    public function getBook($id) {

        $sql = 'SELECT * FROM bookinfo WHERE NO = :id;';
        $param = array(':id' => $id);
        $resultSet = $this->tableGateway->getAdapter()->query($sql);
        $rows = $resultSet->execute($param);

        if (count($rows) > 0) {
            $row = $rows->current();
            \Zend\Debug\Debug::dump($row);die;
        } else {
            throw new \Exception("Could not find $id");
        }
        return $row;
    }

    //add a book to the database
    public function addBook($bookInfo) {

        $book = array();

        if ($this->existBook($bookInfo->isbn) === TRUE) {
            throw new \Exception('The book has been existed! Try again!');
        } else {
            $book = array(
                'isbn' => $bookInfo->isbn,
                'title' => $bookInfo->title,
                'subtitle' => $bookInfo->subtitle,
                'writer' => $bookInfo->writer,
                'price' => $bookInfo->price,
                'category' => $bookInfo->category,
                'comment' => $bookInfo->comment,
                'delflag' => 0
            );
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
