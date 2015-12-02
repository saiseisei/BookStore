<?php

namespace Bookstore\Model;

use Zend\Db\TableGateway\TableGateway;

class UserInfoTable {

    protected $tableGateway;
    protected $table = 'userinfo';

    public function __construct (TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }
    
    /*public function setDbAdapter(Adapter $adapter)
    {
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new UserInfo());
        $this->tableGateway = new TableGateway('userinfo', $adapter, null, $resultSetPrototype);
    }*/

    //For the administrator: list all the users 
    public function fetchAll() {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    //get the user's information from database by email
    public function getUser($email) {
        $rowset = $this->tableGateway->select(array('email' => $email));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find $email");
        }
        return $row;
    }

    //add a user to database
    public function saveUser(User $user) {
        $data = array(
            'nickname' => $user->nickname,
            'email' => $user->email,
            'password' => $user->password,
        );

        $id = (int) $user->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getUser($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    //withdraw from the site
    public function deleteUser($id) {
        $this->tableGateway->delete(array('id' => $id));
    }

    public function registerUser(User $user) {
        $data = array(
            'email' => $user->email,
            'password' => $user->password,
        );
        $this->insert($data);
    }

}
