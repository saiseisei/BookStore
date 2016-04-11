<?php

namespace Bookstore\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;

class BookForm extends Form {

    //book form
    public function __construct() {

        parent::__construct('book');

        $this->add(array(
            'name' => 'NO',
            'type' => 'text',
            'attributes' => array(
                'size' => 1,
                'disabled' => 'disabled',
            ),
        ));
        
        $this->add(array(
            'name' => 'ISBN',
            'type' => 'text',
            'attributes' => array(
                'size' => 20,
                'maxlength' => 30,
            ),
//            'options' => array(
//                'label' => 'ISBN:',
//            ),
        ));

        $this->add(array(
            'name' => 'TITLE',
            'type' => 'text',
            'attributes' => array(
                'size' => 30,
                'maxlength' => 50,
            ),
        ));

        $this->add(array(
            'name' => 'SUBTITLE',
            'type' => 'textarea',
            'attributes' => array(
                'rows' => 3,
                'cols' => 20,
            ),
        ));

        $this->add(array(
            'name' => 'WRITER',
            'type' => 'textarea',
            'attributes' => array(
                'rows' => 3,
                'cols' => 20,
            ),
        ));

        $this->add(array(
            'name' => 'PRICE',
            'type' => 'text',
            'attributes' => array(
                'size' => 15,
                'maxlength' => 11,
            ),
        ));

        $this->add(array(
            'name' => 'CATEGORY',
            'type' => 'select',
            'value' => 11,
            'attributes' => array(
                'id' => 'CATEGORY',
            ),
            'options' => array(
                'empty_option' => 'Choose the category',
                'value_options' => array(
                    '1' => '小説',
                    '2' => '文芸',
                    '3' => '教養',
                    '4' => '経済',
                    '5' => '社会',
                    '6' => '経営',
                    '7' => '人文',
                    '8' => '語学',
                    '9' => '医学・薬学',
                    '10' => 'コンピューター'
                    ),
            ),
        ));

        $this->add(array(
            'name' => 'COMMENT',
            'type' => 'textarea',
            'attributes' => array(
                'rows' => 3,
                'cols' => 40,
            ),
        ));
    }

    public function getInputFilter() {

        $inputFilter = new InputFilter();

        $inputFilter->add(array(
            'name' => 'ISBN',
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
                            \Zend\Validator\NotEmpty::IS_EMPTY => sprintf('%sを入力してください。', '書類番号'),
                        ),
                    ),
                ),
                array(
                    'name' => 'StringLength',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 0,
                        'max' => 30,
                        'message' => array(
                            \Zend\Validator\StringLength::TOO_LONG => sprintf('%sは%s文字以内で入力してください。', '書類番号', '20'),
                        )
                    ),
                ),
            ),
        ));

        $inputFilter->add(array(
            'name' => 'TITLE',
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
                            \Zend\Validator\NotEmpty::IS_EMPTY => sprintf('%sを入力してください。', '書類タイトル'),
                        ),
                    ),
                ),
                array(
                    'name' => 'StringLength',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 0,
                        'max' => 50,
                        'messages' => array(
                            \Zend\Validator\StringLength::TOO_LONG => sprintf('%sを最多%s文字で入力してください。', '書類タイトル', '100'),
                        ),
                    ),
                )
            )
        ));

        $inputFilter->add(array(
            'name' => 'PRICE',
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
                            \Zend\Validator\NotEmpty::IS_EMPTY => sprintf('%sを入力してください。', '書類価格'),
                        ),
                    ),
                ),
                array(
                    'name' => 'Digits',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'messages' => array(
                            \Zend\Validator\Digits::NOT_DIGITS => sprintf('%sを数字で入力してください。', '書類価格'),
                        ),
                    ),
                )
            )
        ));

        $inputFilter->add(array(
            'name' => 'CATEGORYID',
            'required' => true,
        ));
        
        return $inputFilter;
    }

}
