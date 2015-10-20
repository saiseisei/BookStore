<?php

namespace Bookstore\Model;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;

class UserInfo implements InputFilterAwareInterface {

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

    public function getInputFilter() {

        $inputFilter = new InputFilter();

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

    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new \Excepion("Not used");
    }

}
