<?php

namespace Bookstore\Model;

class BookInfo {

    public $no;
    public $isbn;
    public $title;
    public $subtitle;
    public $writer;
    public $price;
    public $category;
    public $comment;
    public $delflag;

    public function exchangeArray($data) {
        $this->no = (isset($data['no'])) ? $data['no'] : null;
        $this->isbn = (isset($data['isbn'])) ? $data['isbn'] : null;
        $this->title = (isset($data['title'])) ? $data['title'] : null;
        $this->subtitle = (isset($data['subtitle'])) ? $data['subtitle'] : null;
        $this->writer = (isset($data['writer'])) ? $data['writer'] : null;
        $this->price = (isset($data['price'])) ? $data['price'] : null;
        $this->category = (isset($data['category'])) ? $data['category'] : null;
        $this->comment = (isset($data['comment'])) ? $data['comment'] : null;
        $this->delflag = (isset($data['delflag'])) ? $data['delflag'] : null;
    }

}
