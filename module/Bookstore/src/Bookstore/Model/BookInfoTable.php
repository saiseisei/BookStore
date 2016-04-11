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

    public function countBooksNum($keyword) {
        $sql = 'SELECT 
                    COUNT(*) AS NUM 
                FROM 
                    bookinfo  T1
                LEFT OUTER JOIN 
                    category  T2
                ON 
                    T1.CATEGORYID = T2.CATEGORYID
                WHERE
                    T1.DELFLAG = 0 ';
        if(!empty($keyword)){
            $sql .= 'AND (
                    T1.NO LIKE :keyword OR
                    T1.ISBN LIKE :keyword OR
                    T1.TITLE LIKE :keyword OR
                    T1.SUBTITLE LIKE :keyword OR
                    T1.WRITER LIKE :keyword OR
                    T1.PRICE LIKE :keyword )';
        }
        $param = array(':keyword' => "%$keyword%");
        $query = $this->tableGateway->getAdapter()->query($sql);
        $rowsNum = $query->execute($param);
        if(count($rowsNum)>0){
            $nums = $rowsNum->current();
            $num = $nums['NUM'];
        } else {
            throw new \Exception("Could not find a book!");
        }
//\Zend\Debug\Debug::dump($num);die;
        return $num;
    }
    
    public function getBooks($currentPage, $keyword = null) {
        $startRecordNo = ($currentPage - 1) * RECORDS_IN_ONE_PAGE;
        $endRecordNo = $currentPage * RECORDS_IN_ONE_PAGE;
        $rows = array();

        $sql = 'SELECT 
                    T1.*, 
                    T2.CATEGORY 
                FROM 
                    bookinfo  T1
                LEFT OUTER JOIN 
                    category  T2
                ON 
                    T1.CATEGORYID = T2.CATEGORYID
                WHERE
                    T1.DELFLAG = 0 ';
        if(!empty($keyword)){
            $sql .= 'AND (
                    T1.NO LIKE :keyword OR
                    T1.ISBN LIKE :keyword OR
                    T1.TITLE LIKE :keyword OR
                    T1.SUBTITLE LIKE :keyword OR
                    T1.WRITER LIKE :keyword OR
                    T1.PRICE LIKE :keyword )';
        }
        $sql .= 'LIMIT ' . RECORDS_IN_ONE_PAGE . ' 
                OFFSET ' . $startRecordNo . ';';
        
        $param = array(':keyword' => "%$keyword%");
        $query = $this->tableGateway->getAdapter()->query($sql);
        $result = $query->execute($param);
        
        if (count($result) > 0) {
            foreach ($result as $key => $value) {
                $rows[$key]['NO'] = $value['NO'];
                $rows[$key]['ISBN'] = $value['ISBN'];
                $rows[$key]['TITLE'] = $value['TITLE'];
                $rows[$key]['SUBTITLE'] = $value['SUBTITLE'];
                $rows[$key]['WRITER'] = $value['WRITER'];
                $rows[$key]['PRICE'] = $value['PRICE'];
                $rows[$key]['CATEGORYID'] = $value['CATEGORYID'];
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


        $sql = 'SELECT bookinfo.*, category.CATEGORY FROM bookinfo, category '
                . 'WHERE  category.CATEGORYID = bookinfo.CATEGORYID '
                . 'AND bookinfo.NO = :id '
                . 'AND bookinfo.DELFLAG = 0;';
        $param = array(':id' => $id);
        $resultSet = $this->tableGateway->getAdapter()->query($sql);
        $rows = $resultSet->execute($param);

        if (count($rows) > 0) {
            $row = $rows->current();
        } else {
            throw new \Exception("Could not find $id");
        }
        return $row;
    }

    //add a book to the database
    public function addBook($bookInfo) {

        $book = array();

        if ($this->existBook($bookInfo->ISBN) === TRUE) {
            throw new \Exception('The book has been existed! Try again!');
        } else {
            $book = array(
                'ISBN' => $bookInfo->ISBN,
                'TITLE' => $bookInfo->TITLE,
                'SUBTITLE' => $bookInfo->SUBTITLE,
                'WRITER' => $bookInfo->WRITER,
                'PRICE' => $bookInfo->PRICE,
                'CATEGORYID' => $bookInfo->CATEGORYID,
                'COMMENT' => $bookInfo->COMMENT,
                'DELFLAG' => 0
            );
        }
        $this->tableGateway->insert($book);
    }

    //updata the book's information 
    public function editBook($bookInfo) {

        $this->getBook($bookInfo['NO']);
        $this->tableGateway->update($bookInfo, array('NO' => $bookInfo['NO']));
    }

    //delete the book from the database
    public function deleteBook($params) {

        if (isset($params)) {
            $isbns = implode('","', $params);
            $sql = 'UPDATE 
                        bookinfo SET DELFLAG = 1 WHERE ISBN in ("' . implode('","', $params) . '");';

            $param = array(':isbns' => $isbns);
            $resultSet = $this->tableGateway->getAdapter()->query($sql);
            $resultSet->execute($param);
        } else {
            throw new \Exception("Delete Error!");
        }
    }

    /* public function setDbAdapter(Adapter $adapter)
      {
      $resultSetPrototype = new ResultSet();
      $resultSetPrototype->setArrayObjectPrototype(new UserInfo());
      $this->tableGateway = new TableGateway('userinfo', $adapter, null, $resultSetPrototype);
      } */
}
