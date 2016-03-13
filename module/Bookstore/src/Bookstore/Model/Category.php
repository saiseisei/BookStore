<?php

namespace Bookstore\Model;

class Category {

    public $categoryid;
    public $category;
    public $comment;
    public $delflag;

    public function exchangeArray($data) {
        $this->categoryid = (isset($data['categoryid'])) ? $data['categoryid'] : null;
        $this->category = (isset($data['category'])) ? $data['category'] : null;
        $this->comment = (isset($data['comment'])) ? $data['comment'] : null;
        $this->delflag = (isset($data['delflag'])) ? $data['delflag'] : null;
    }

}
