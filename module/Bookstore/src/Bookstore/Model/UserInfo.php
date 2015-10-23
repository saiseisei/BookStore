<?php

namespace Bookstore\Model;

class UserInfo {

    public $id;
    public $user;
    public $email;
    public $password;

    public function exchangeArray($data) {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->user = (isset($data['user'])) ? $data['user'] : null;
        $this->email = (isset($data['email'])) ? $data['email'] : null;
        $this->password = (isset($data['password'])) ? $data['password'] : null;
    }

}
