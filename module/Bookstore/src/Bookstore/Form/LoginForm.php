<?php

namespace Bookstore\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;

class LoginForm extends Form {

    //login form
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
            'type' => 'Password',
            'attributes' => array(
                'size' => '16',
                'maxlength' => '200',
            ),
            'options' => array(
                'label' => 'PASSWORD:',
            ),
        ));
    }

    public function getInputFilter() {

        $inputFilter = new InputFilter();
        //$emailIsEmpty = \Zend\Validator\NotEmpty::IS_EMPTY;
        //$passwordIsEmpty = \Zend\Validator\NotEmpty::IS_EMPTY;
        //$IsEmpty = \Zend\Validator\NotEmpty::IS_EMPTY;

        $inputFilter->add(array(
            'name' => 'email',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'messages' => array(
                            \Zend\Validator\NotEmpty::IS_EMPTY => sprintf('%sを入力してください。', 'メールアドレス'),
                        //$IsEmpty => 'メールアドレスを入力してください。',
                        ),
                    ),
                ),
                array(
                    'name' => 'StringLength',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 0,
                        'max' => 100,
                        'message' => array(
                            \Zend\Validator\StringLength::TOO_LONG => sprintf('%sは%s文字以内で入力してください。', 'メールアドレス', '100'),
                        )
                    ),
                ),
                array(
                    'name' => 'EmailAddress',
                    'options' => array(
                        'message' => array(
                            \Zend\Validator\EmailAddress::INVALID_FORMAT => sprintf('%sは有効な形式で入力してください。', 'メールアドレス'),
                        ),
                    ),
                )
            ),
        ));

        $inputFilter->add(array(
            'name' => 'password',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'messages' => array(
                            \Zend\Validator\NotEmpty::IS_EMPTY => sprintf('%sを入力してください。', 'パスワード'),
                        ),
                    ),
                ),
                array(
                    'name' => 'StringLength',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 6,
                        'messages' => array(
                            \Zend\Validator\StringLength::TOO_SHORT => sprintf('%sを最低%s文字で入力してください。', 'パスワード', '6'),
                        ),
                    ),
                )
            )
        ));

        return $inputFilter;
    }

}
