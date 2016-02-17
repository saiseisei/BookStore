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
            'name' => 'isbn',
            'type' => 'text',
            'attributes' => array(
                'size' => 30,
                'maxlength' => 30,
            ),
//            'options' => array(
//                'label' => 'ISBN:',
//            ),
        ));

        $this->add(array(
            'name' => 'title',
            'type' => 'text',
            'attributes' => array(
                'size' => 30,
                'maxlength' => 50,
            ),
        ));

        $this->add(array(
            'name' => 'subtitle',
            'type' => 'text',
            'attributes' => array(
                'size' => 30,
                'maxlength' => 100,
            ),
        ));

        $this->add(array(
            'name' => 'writer',
            'type' => 'text',
            'attributes' => array(
                'size' => 30,
                'maxlength' => 60,
            ),
        ));

        $this->add(array(
            'name' => 'price',
            'type' => 'text',
            'attributes' => array(
                'size' => 15,
                'maxlength' => 11,
            ),
        ));

        $this->add(array(
            'name' => 'category',
            'type' => 'select',
            'attributes' => array(
                'size' => 30,
            ),
            'options' => array(
                'category0' => '選択して下さい',
                'category1' => '小説',
                'category2' => '文芸',
                'category3' => '教養',
                'category4' => '経済',
                'category5' => '社会',
                'category6' => '経営',
                'category7' => '人文',
                'category8' => '語学',
                'category9' => '医学・薬学',
                'category10' => 'コンピュータ',
            ),
        ));

        $this->add(array(
            'name' => 'comment',
            'type' => 'textarea',
            'attributes' => array(
                'row' => 4,
                'cols' => 40,
            ),
        ));
    }

    public function getInputFilter() {

        $inputFilter = new InputFilter();

        $inputFilter->add(array(
            'name' => 'isbn',
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
            'name' => 'title',
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
            'name' => 'price',
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
            'name' => 'category',
            'required' => true,
        ));

        return $inputFilter;
    }

}
