<?php

namespace Bookstore\Model;

class Category {

    public $CATEGORYID;
    public $CATEGORY;
    public $COMMENT;
    public $DELFLAG;

    public function exchangeArray($data) {
        $this->CATEGORYID = (isset($data['CATEGORYID'])) ? $data['CATEGORYID'] : null;
        $this->CATEGORY = (isset($data['CATEGORY'])) ? $data['CATEGORY'] : null;
        $this->COMMENT = (isset($data['COMMENT'])) ? $data['COMMENT'] : null;
        $this->DELFLAG = (isset($data['DELFLAG'])) ? $data['DELFLAG'] : null;
    }

}
