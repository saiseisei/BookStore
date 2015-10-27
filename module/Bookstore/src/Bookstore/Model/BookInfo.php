<?php

namespace Bookstore\Model;

class BookInfo {

    public $isbn;
    public $title;
    public $price;

    public function exchangeArray($data) {
        $this->isbn = (isset($data['isbn'])) ? $data['isbn'] : null;
        $this->title = (isset($data['title'])) ? $data['title'] : null;
        $this->price = (isset($data['price'])) ? $data['price'] : null;
    }

}
