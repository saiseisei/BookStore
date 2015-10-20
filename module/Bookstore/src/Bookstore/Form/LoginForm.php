<?php

namespace Bookstore\Form;

use Zend\Form\Form;


class LoginForm extends Form {

    //ログイン
    public function __construct() {

        parent::__construct('login');

        $this->add(array(
            'name' => 'email',
            'type' => 'text',
            'attributes' => array(
                'size' => '16',
                'maxlength' => '200',
            ),
            'options' => array(
                'label' => 'USER MAIL:',
            ),
        ));
        $this->add(array(
            'name' => 'password',
            'type' => 'text',
            'attributes' => array(
                'size' => '16',
                'maxlength' => '200',
            ),
            'options' => array(
                'label' => 'PASSWORD:',
            ),
        ));
    }

}
