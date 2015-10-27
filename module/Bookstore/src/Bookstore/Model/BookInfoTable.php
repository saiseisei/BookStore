<?php

namespace Bookstore\Model;

use Zend\Db\TableGateway\TableGateway;

class BookInfoTable {

    protected $tableGateway;
    protected $table = 'bookinfo';

    public function __construct (TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll() {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getUser($email) {
        $rowset = $this->tableGateway->select(array('email' => $email));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find $email");
        }
        return $row;
    }

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

    
        
    /*public function setDbAdapter(Adapter $adapter)
    {
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new UserInfo());
        $this->tableGateway = new TableGateway('userinfo', $adapter, null, $resultSetPrototype);
    }*/
    
}
