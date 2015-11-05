<?php

namespace Bookstore\Model;

use Zend\Db\TableGateway\TableGateway;

class BookInfoTable {

    protected $tableGateway;
    protected $table = 'bookinfo';

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    //全ての書類情報を取得する
    public function fetchAll() {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    //isbnより、1件書類情報を取得する
    public function getBook($isbn) {
        $rowset = $this->tableGateway->select(array('isbn' => $isbn));
        $row = $rowset->current();
//        if (!$row) {
//            throw new \Exception("Could not find $isbn");
//        }
        return $row;
    }

    //1件書類情報を登録する
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

    //1件書類情報を更新する
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

    //1件書類を削除する
    public function deleteBook(BookInfo $bookInfo) {
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
