<?php

namespace Bookstore\Model;

class UserInfo {

    public $email;
    public $username;
    public $age;
    public $password;
    public $delflag;

    public function exchangeArray($data) {
        $this->email = (isset($data['email'])) ? $data['email'] : null;
        $this->username = (isset($data['username'])) ? $data['username'] : null;
        $this->password = (isset($data['password'])) ? $data['password'] : null;
        $this->age = (isset($data['age'])) ? $data['age'] : null;
        $this->delflag = (isset($data['delflag'])) ? $data['delflag'] : null;
    }

}
