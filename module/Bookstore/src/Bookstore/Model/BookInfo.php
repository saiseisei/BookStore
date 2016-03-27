<?php

namespace Bookstore\Model;

class BookInfo {

    public $NO;
    public $ISBN;
    public $TITLE;
    public $SUBTITLE;
    public $WRITER;
    public $PRICE;
    public $CATEGORYID;
    public $COMMENT;
    public $DELFLAG;

    public function exchangeArray($data) {
        $this->NO = (isset($data['NO'])) ? $data['NO'] : null;
        $this->ISBN = (isset($data['ISBN'])) ? $data['ISBN'] : null;
        $this->TITLE = (isset($data['TITLE'])) ? $data['TITLE'] : null;
        $this->SUBTITLE = (isset($data['SUBTITLE'])) ? $data['SUBTITLE'] : null;
        $this->WRITER = (isset($data['WRITER'])) ? $data['WRITER'] : null;
        $this->PRICE = (isset($data['PRICE'])) ? $data['PRICE'] : null;
        $this->CATEGORYID = (isset($data['CATEGORYID'])) ? $data['CATEGORYID'] : null;
        $this->COMMENT = (isset($data['COMMENT'])) ? $data['COMMENT'] : null;
        $this->DELFLAG = (isset($data['DELFLAG'])) ? $data['DELFLAG'] : null;
    }

}
